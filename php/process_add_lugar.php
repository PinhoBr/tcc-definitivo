<?php
session_start();
include "conn.php";

// 1. Verificação de Autenticação e Autorização (Fundamental)
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'DONO' || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    // Redireciona se não for um DONO ou se não for uma submissão POST
    header("Location: login.php");
    exit();
}

// Dados do usuário logado (dono do estabelecimento)
$currentUserId = $_SESSION['user']['id'];

// 2. Coleta e Validação dos Dados do Formulário
$nome      = trim($_POST['nome'] ?? '');
$descricao = trim($_POST['descricao'] ?? '');
$categoria = trim($_POST['categoria'] ?? '');
$imagem    = trim($_POST['imagem'] ?? '');

// Validação básica
if (empty($nome) || empty($descricao) || empty($categoria)) {
    header("Location: add_lugar.php?status=error");
    exit();
}

// Validação de categoria
$categoriasValidas = ['turismo', 'gastronomia', 'hospedagem', 'destaque'];
if (!in_array($categoria, $categoriasValidas)) {
    header("Location: add_lugar.php?status=error");
    exit();
}

// 3. Processamento da Inserção no Banco de Dados
try {
    // A query de inserção define o dono_id e, crucialmente, o status inicial como 'pendente'.
    $sql = "INSERT INTO lugares (dono_id, nome, descricao, categoria, imagem, status_aprovacao, data_cadastro) 
            VALUES (:donoId, :nome, :descricao, :categoria, :imagem, 'pendente', NOW())";

    $stmt = $pdo->prepare($sql);
    
    $stmt->bindParam(':donoId', $currentUserId, PDO::PARAM_INT);
    $stmt->bindParam(':nome', $nome);
    $stmt->bindParam(':descricao', $descricao);
    $stmt->bindParam(':categoria', $categoria);
    $stmt->bindParam(':imagem', $imagem);

    $stmt->execute();

    // Se a inserção foi bem-sucedida, redireciona com mensagem de sucesso
    header("Location: add_lugar.php?status=success");
    exit();

} catch (PDOException $e) {
    // Log do erro e redirecionamento
    error_log("Erro no processamento de cadastro: " . $e->getMessage());
    header("Location: add_lugar.php?status=error_db");
    exit();
}
?>