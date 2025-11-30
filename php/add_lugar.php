<?php
session_start();
// Inclui o arquivo de conexão com o banco de dados
include "conn.php";

// 1. Verificação de Autenticação e Autorização
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'DONO') {
    // Redireciona o usuário para a página de login se não for um Dono autenticado
    header("Location: login.php");
    exit();
}

$errorMessage = '';
$successMessage = '';

// Lista de categorias permitidas para o menu suspenso
$categorias = ['turismo', 'gastronomia', 'hospedagem', 'destaque'];

// Se houver mensagens de status na URL (após processamento)
if (isset($_GET['status'])) {
    if ($_GET['status'] === 'success') {
        $successMessage = "Local cadastrado com sucesso! Ele foi enviado para a aprovação do Administrador.";
    } elseif ($_GET['status'] === 'error') {
        $errorMessage = "Erro ao cadastrar o local. Verifique os dados e tente novamente.";
    } elseif ($_GET['status'] === 'error_db') {
        $errorMessage = "Erro interno ao salvar no banco de dados. Tente novamente mais tarde.";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Cadastrar Novo Local - Lins Travel</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700;800&display=swap" rel="stylesheet">
    <!-- Novo link para o arquivo CSS centralizado -->
    <link rel="stylesheet" href="../css/owner.css"> 
</head>
<body>

<nav class="navbar">
    <div class="nav-inner">
        <div class="brand"><a href="../index.php">Lins<span class="brand-accent">Travel</span></a></div>
        <a href="owner_dashboard.php" class="btn btn-back">← Voltar ao Painel</a>
    </div>
</nav>

<div class="dashboard-container"> <!-- Usando a classe de container do dashboard -->
    <header class="form-header">
        <h1>Cadastrar Novo Local</h1>
        <p>Preencha os dados do seu novo estabelecimento em Lins.</p>
    </header>

    <?php if ($errorMessage): ?>
        <div class="alert alert-error"><?= $errorMessage ?></div>
    <?php endif; ?>

    <?php if ($successMessage): ?>
        <div class="alert alert-success"><?= $successMessage ?></div>
    <?php endif; ?>

    <!-- O formulário aponta para o novo arquivo de processamento -->
    <form action="process_add_lugar.php" method="POST">
        <div class="form-group">
            <label for="nome">Nome do Estabelecimento:</label>
            <input type="text" id="nome" name="nome" placeholder="Ex: Praça Joaquim da Silva" required>
        </div>

        <div class="form-group">
            <label for="categoria">Categoria:</label>
            <select id="categoria" name="categoria" required>
                <option value="" disabled selected>Selecione uma categoria</option>
                <?php foreach ($categorias as $cat): ?>
                    <option value="<?= $cat ?>">
                        <?= ucfirst($cat) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="descricao">Descrição:</label>
            <textarea id="descricao" name="descricao" required placeholder="Descreva o local, o que ele oferece e por que as pessoas devem visitar."></textarea>
            <small>Descreva brevemente seu local (máximo 255 caracteres).</small>
        </div>

        <!-- NOVO CAMPO: CEP para Localização -->
        <div class="form-group">
            <label for="cep">CEP (apenas números):</label>
            <input type="text" id="cep" name="cep" placeholder="Ex: 16400000" maxlength="8" pattern="\d{8}">
            <small>O CEP é essencial para a função de traçar rotas no Google Maps.</small>
        </div>

        <!-- NOVO CAMPO: Telefone para Contato/Ligar -->
        <div class="form-group">
            <label for="telefone">Telefone / WhatsApp:</label>
            <input type="text" id="telefone" name="telefone" placeholder="Ex: 14999999999" maxlength="11" pattern="\d{10,11}">
            <small>Inclua o DDD e apenas números. Usado para botões de contato.</small>
        </div>

        <div class="form-group">
            <label for="imagem">URL da Imagem:</label>
            <input type="text" id="imagem" name="imagem" placeholder="https://exemplo.com/sua-imagem.jpg">
            <small>Cole aqui o link direto da imagem que será exibida.</small>
        </div>
        
        <div class="form-group">
            <button type="submit" class="btn btn-primary">Cadastrar Local</button>
        </div>
    </form>
</div>

</body>
</html>