<?php
session_start();
include "conn.php";

// 1. Verificação de Autenticação e Autorização (Fundamental)
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'DONO' || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    // Redireciona se não for um DONO ou se não for uma submissão POST
    header("Location: login.php");
    exit();
}

// Dados do usuário logado
$currentUserId = $_SESSION['user']['id'];

// 2. Coleta e Validação dos Dados do Formulário
$lugarId   = $_POST['lugar_id'] ?? null;
$nome      = trim($_POST['nome'] ?? '');
$descricao = trim($_POST['descricao'] ?? '');
$categoria = trim($_POST['categoria'] ?? '');
$imagem    = trim($_POST['imagem'] ?? '');

// Validação básica (campos não podem ser vazios)
if (!$lugarId || !is_numeric($lugarId) || empty($nome) || empty($descricao) || empty($categoria)) {
    header("Location: edit_lugar.php?id=$lugarId&status=error");
    exit();
}

// Lista de categorias permitidas para validação
$categoriasValidas = ['turismo', 'gastronomia', 'hospedagem', 'destaque'];
if (!in_array($categoria, $categoriasValidas)) {
    header("Location: edit_lugar.php?id=$lugarId&status=error");
    exit();
}

// 3. Processamento da Atualização no Banco de Dados
try {
    // A query de atualização deve garantir que o local pertence ao usuário logado (dono_id = :donoId)
    // E o status_aprovacao é definido como 'pendente' para forçar uma re-aprovação.
    $sql = "UPDATE lugares 
            SET nome = :nome, 
                descricao = :descricao, 
                categoria = :categoria, 
                imagem = :imagem, 
                status_aprovacao = 'pendente' 
            WHERE id = :lugarId AND dono_id = :donoId";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':nome', $nome);
    $stmt->bindParam(':descricao', $descricao);
    $stmt->bindParam(':categoria', $categoria);
    $stmt->bindParam(':imagem', $imagem);
    $stmt->bindParam(':lugarId', $lugarId, PDO::PARAM_INT);
    $stmt->bindParam(':donoId', $currentUserId, PDO::PARAM_INT);

    $stmt->execute();

    // Se a atualização foi bem-sucedida, redireciona com mensagem de sucesso
    header("Location: edit_lugar.php?id=$lugarId&status=success");
    exit();

} catch (PDOException $e) {
    // Log do erro e redirecionamento
    error_log("Erro no processamento de edição: " . $e->getMessage());
    header("Location: edit_lugar.php?id=$lugarId&status=error");
    exit();
}
?>