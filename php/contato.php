<?php
session_start();
// A página de contato não precisa de login, mas pode pré-preencher campos se o usuário estiver logado.

$userName = '';
$userEmail = '';

if (isset($_SESSION['user'])) {
    $userName = htmlspecialchars($_SESSION['user']['name']);
    $userEmail = htmlspecialchars($_SESSION['user']['email']);
}

$errorMessage = '';
$successMessage = '';

// Se houver mensagens de status na URL (após processamento)
if (isset($_GET['status'])) {
    if ($_GET['status'] === 'success') {
        $successMessage = "Sua mensagem foi enviada com sucesso! Responderemos em breve.";
    } elseif ($_GET['status'] === 'error') {
        $errorMessage = "Erro ao enviar a mensagem. Por favor, verifique os campos e tente novamente.";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Contato - Lins Travel</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700;800&display=swap" rel="stylesheet">
    <!-- Reutilizando o CSS principal para manter a consistência visual -->
    <!-- Assumindo que 'contato.php' está na raiz e o CSS está em '../css/owner.css' -->
    <link rel="stylesheet" href="../css/owner.css"> 

    <style>
        /* Estilos específicos para a página de contato, ajustando o container */
        .contact-container {
            max-width: 800px;
            margin: 60px auto;
            padding: 30px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }
        .contact-header {
            text-align: center;
            margin-bottom: 40px;
        }
        .contact-header h1 {
            font-size: 2.5rem;
            color: #0084db;
            margin-bottom: 10px;
        }
        .contact-header p {
            color: #666;
        }
        
        /* Ajustes para o formulário */
        .form-group label {
            font-weight: 600;
        }
    </style>
</head>
<body>

<nav class="navbar">
    <div class="nav-inner">
        <div class="brand"><a href="../index.php">Lins<span class="brand-accent">Travel</span></a></div>
        <div>
            <!-- Links de navegação principais (simulação) -->
            <a href="../Index.php" class="btn btn-outline">Início</a>
            <!-- Se o usuário estiver logado, exibe link para o painel -->
            <?php if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'DONO'): ?>
                <a href="owner_dashboard.php" class="btn btn-back">Meu Painel</a>
            <?php elseif (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'ADM'): ?>
                <a href="admin_dashboard.php" class="btn btn-back">Painel ADM</a>
            <?php else: ?>
                <a href="login.php" class="btn btn-primary">Login</a>
            <?php endif; ?>
        </div>
    </div>
</nav>

<div class="contact-container">
    <header class="contact-header">
        <h1>Fale Conosco</h1>
        <p>Preencha o formulário abaixo para entrar em contato com a equipe Lins Travel. Seja para dúvidas, sugestões ou parcerias.</p>
    </header>

    <?php if ($errorMessage): ?>
        <div class="alert alert-error"><?= $errorMessage ?></div>
    <?php endif; ?>

    <?php if ($successMessage): ?>
        <div class="alert alert-success"><?= $successMessage ?></div>
    <?php endif; ?>

    <form action="process_contato.php" method="POST">
        
        <div class="form-group">
            <label for="nome">Seu Nome:</label>
            <input type="text" id="nome" name="nome" value="<?= $userName ?>" required>
        </div>

        <div class="form-group">
            <label for="email">Seu E-mail:</label>
            <input type="email" id="email" name="email" value="<?= $userEmail ?>" required>
        </div>

        <div class="form-group">
            <label for="assunto">Assunto:</label>
            <input type="text" id="assunto" name="assunto" placeholder="Ex: Sugestão de Local ou Dúvida de Parceria" required>
        </div>

        <div class="form-group">
            <label for="mensagem">Mensagem:</label>
            <textarea id="mensagem" name="mensagem" required></textarea>
        </div>
        
        <div class="form-group" style="text-align: right;">
            <button type="submit" class="btn btn-primary">Enviar Mensagem</button>
        </div>
    </form>
</div>

</body>
</html>