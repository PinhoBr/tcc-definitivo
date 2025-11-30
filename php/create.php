<?php
session_start();
include "conn.php"; // Inclui o arquivo de conexão

// Redireciona se o usuário já estiver logado
if (isset($_SESSION['user'])) {
    header("Location: ../index.php");
    exit;
}

// Variáveis para mensagens e preenchimento
$errorMessage = "";
$successMessage = "";
$nameValue = "";
$emailValue = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 1. Coleta e sanitiza os dados do formulário
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = filter_input(INPUT_POST, 'password', FILTER_UNSAFE_RAW);
    $confirmPassword = filter_input(INPUT_POST, 'confirm_password', FILTER_UNSAFE_RAW);
    $userType = filter_input(INPUT_POST, 'user_type', FILTER_SANITIZE_STRING);
    
    // Salva valores para preenchimento em caso de erro
    $nameValue = htmlspecialchars($name);
    $emailValue = htmlspecialchars($email);

    // 2. Validação básica
    if (empty($name) || empty($email) || empty($password) || empty($confirmPassword) || empty($userType)) {
        $errorMessage = "Por favor, preencha todos os campos.";
    } elseif (!in_array($userType, ['USUARIO', 'DONO'])) {
        $errorMessage = "Tipo de usuário inválido.";
    } elseif ($password !== $confirmPassword) {
        $errorMessage = "A senha e a confirmação de senha não coincidem.";
    } elseif (strlen($password) < 6) {
        $errorMessage = "A senha deve ter pelo menos 6 caracteres.";
    } else {
        // 3. Verifica se o e-mail já existe (usando a propriedade UNIQUE do banco)
        $sqlCheck = "SELECT COUNT(*) FROM usuarios WHERE email = :email";
        $stmtCheck = $pdo->prepare($sqlCheck);
        $stmtCheck->execute(['email' => $email]);
        
        if ($stmtCheck->fetchColumn() > 0) {
            $errorMessage = "Este e-mail já está cadastrado. Tente fazer login.";
        } else {
            // 4. Criptografa a senha (CRUCIAL para segurança)
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            
            // 5. Insere o novo usuário no banco de dados
            $sqlInsert = "INSERT INTO usuarios (nome, email, senha, tipo_usuario, data_registro) VALUES (:nome, :email, :senha, :tipo_usuario, NOW())";
            
            try {
                $stmtInsert = $pdo->prepare($sqlInsert);
                $stmtInsert->execute([
                    'nome' => $name,
                    'email' => $email,
                    'senha' => $hashedPassword,
                    'tipo_usuario' => $userType
                ]);
                
                $successMessage = "Cadastro realizado com sucesso! Você pode fazer login agora.";
                
                // Limpa os campos após o sucesso
                $nameValue = "";
                $emailValue = "";

            } catch (PDOException $e) {
                // Em caso de erro de inserção
                $errorMessage = "Erro ao cadastrar usuário. Tente novamente.";
                error_log("DB Error: " . $e->getMessage());
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro - Lins Travel</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">

    <style>
        /* Estilos específicos para o formulário de Cadastro */
        .register-container {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: linear-gradient(135deg, #e0f7fa 0%, #f4f6f8 100%);
        }
        .register-box {
            background: #ffffff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 450px;
            text-align: center;
        }
        .register-box h2 {
            margin-bottom: 25px;
            color: #0ea5a4;
            font-weight: 700;
        }
        .form-group {
            margin-bottom: 20px;
            text-align: left;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #555;
        }
        .form-group input, .form-group select {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 1rem;
            transition: border-color 0.2s;
            box-sizing: border-box; /* Garante que padding e border sejam incluídos na largura */
        }
        .form-group input:focus, .form-group select:focus {
            border-color: #0ea5a4;
            outline: none;
        }
        .message-box {
            padding: 10px;
            border-radius: 6px;
            margin-bottom: 20px;
            text-align: left;
            font-size: 0.9rem;
        }
        .error-message {
            background-color: #ffdddd;
            color: #cc0000;
        }
        .success-message {
            background-color: #ddffdd;
            color: #008000;
        }
        .btn-submit {
            width: 100%;
            padding: 12px;
            background: linear-gradient(90deg, #0084db, #1fb5b2);
            color: white;
            font-weight: bold;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: opacity 0.2s;
        }
        .btn-submit:hover {
            opacity: 0.9;
        }
        .link-back {
            display: block;
            margin-top: 20px;
            color: #0084db;
            text-decoration: none;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>

<div class="register-container">
    <div class="register-box">
        <h2>Criar Conta</h2>
        
        <?php if (!empty($errorMessage)): ?>
            <div class="message-box error-message"><?= $errorMessage ?></div>
        <?php endif; ?>
        
        <?php if (!empty($successMessage)): ?>
            <div class="message-box success-message">
                <?= $successMessage ?>
                <a href="login.php" class="link-back" style="margin-top: 10px; text-align: center;">Ir para o Login</a>
            </div>
        <?php endif; ?>

        <form method="POST" action="create.php">
            <div class="form-group">
                <label for="name">Nome Completo</label>
                <input type="text" id="name" name="name" value="<?= $nameValue ?>" required>
            </div>
            
            <div class="form-group">
                <label for="email">E-mail</label>
                <input type="email" id="email" name="email" value="<?= $emailValue ?>" required>
            </div>
            
            <div class="form-group">
                <label for="password">Senha (mínimo 6 caracteres)</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <div class="form-group">
                <label for="confirm_password">Confirmar Senha</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>
            
            <div class="form-group">
                <label for="user_type">Tipo de Conta</label>
                <select id="user_type" name="user_type" required>
                    <option value="">Selecione...</option>
                    <option value="USUARIO" selected>Usuário Comum (Quero explorar Lins)</option>
                    <option value="DONO">Dono de Estabelecimento (Quero cadastrar um local)</option>
                </select>
            </div>
            
            <button type="submit" class="btn-submit">Cadastrar</button>
        </form>
        
        <a href="login.php" class="link-back">Já tem uma conta? Faça Login</a>
    </div>
</div>

</body>
</html>