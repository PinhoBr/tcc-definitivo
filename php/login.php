<?php
// Inicia a sessão para armazenar informações do usuário
session_start();

// Inclui o arquivo de conexão com o banco de dados
// Certifique-se de que 'conn.php' está no diretório 'php' e define $pdo
include "conn.php";

// Definição das variáveis de estado (para mensagens de erro e preenchimento de formulário)
$errorMessage = "";
$emailValue = "";

// 1. Processa o formulário quando é enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Coleta e sanitiza os dados do formulário
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = filter_input(INPUT_POST, 'password', FILTER_UNSAFE_RAW);

    // Salva o email para preencher novamente em caso de erro
    $emailValue = htmlspecialchars($email);

    // 2. Validação básica (campos não podem estar vazios)
    if (empty($email) || empty($password)) {
        $errorMessage = "Por favor, preencha todos os campos.";
    } else {
        // 3. Busca o usuário pelo email no banco de dados (Tabela: usuarios)
        $sql = "SELECT id, nome, email, senha, tipo_usuario FROM usuarios WHERE email = :email";
        
        try {
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['email' => $email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // 4. Verifica se o usuário existe e se a senha está correta
            if ($user && password_verify($password, $user['senha'])) {
                
                // Sucesso: Define a sessão do usuário com dados essenciais
                $_SESSION['user'] = [
                    'id'    => $user['id'],
                    'name'  => $user['nome'],
                    'email' => $user['email'],
                    'role'  => $user['tipo_usuario'] // <-- CRUCIAL: Salva o tipo de usuário
                ];

                // 5. Redireciona o usuário para o dashboard apropriado
                switch ($user['tipo_usuario']) {
                    case 'ADM':
                        header("Location: admin_dashboard.php"); // Criaremos este arquivo
                        exit;
                    case 'DONO':
                        header("Location: owner_dashboard.php"); // Criaremos este arquivo
                        exit;
                    case 'USUARIO':
                    default:
                        header("Location: ../index.php"); // Volta para a página inicial
                        exit;
                }
            } else {
                // Falha na autenticação
                $errorMessage = "E-mail ou senha incorretos. Tente novamente.";
            }

        } catch (PDOException $e) {
            // Em um ambiente de produção, este erro deve ser logado, não exibido.
            $errorMessage = "Ocorreu um erro interno. Tente mais tarde. (Detalhe: " . $e->getMessage() . ")";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Lins Travel</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css"> <!-- Assume que o CSS está em ../css/style.css -->

    <style>
        /* Estilos específicos para o formulário de Login */
        .login-container {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            /* Degradê de fundo sutil para a página de login */
            background: linear-gradient(135deg, #e0f7fa 0%, #f4f6f8 100%);
        }
        .login-box {
            background: #ffffff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }
        .login-box h2 {
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
        .form-group input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 1rem;
            transition: border-color 0.2s;
        }
        .form-group input:focus {
            border-color: #0ea5a4;
            outline: none;
        }
        .error-message {
            background-color: #ffdddd;
            color: #cc0000;
            padding: 10px;
            border-radius: 6px;
            margin-bottom: 20px;
            text-align: left;
            font-size: 0.9rem;
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

<div class="login-container">
    <div class="login-box">
        <h2>Acesso de Usuário</h2>
        
        <?php if (!empty($errorMessage)): ?>
            <div class="error-message"><?= $errorMessage ?></div>
        <?php endif; ?>

        <form method="POST" action="login.php">
            <div class="form-group">
                <label for="email">E-mail</label>
                <input type="email" id="email" name="email" value="<?= $emailValue ?>" required>
            </div>
            
            <div class="form-group">
                <label for="password">Senha</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <button type="submit" class="btn-submit">Entrar</button>
        </form>
        
        <a href="../index.php" class="link-back">Voltar para a página inicial</a>
    </div>
</div>

</body>
</html>