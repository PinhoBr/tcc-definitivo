<?php
session_start();

// Define o endereço de e-mail de destino (o e-mail que receberá as mensagens)
$toEmail = "suporte@linstravel.com.br"; 

// Verifica se a requisição é POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: contato.php");
    exit();
}

// Coleta e sanitiza os dados do formulário
$nome      = trim($_POST['nome'] ?? '');
$email     = trim($_POST['email'] ?? '');
$assunto   = trim($_POST['assunto'] ?? '');
$mensagem  = trim($_POST['mensagem'] ?? '');

// Validação básica
if (empty($nome) || empty($email) || empty($assunto) || empty($mensagem) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    // Redireciona com erro se algum campo for inválido
    header("Location: contato.php?status=error");
    exit();
}

// ----------------------------------------------------
// 1. Constrói o corpo do e-mail
// ----------------------------------------------------

$emailSubject = "Nova Mensagem de Contato (Lins Travel): " . $assunto;

$emailBody = "Você recebeu uma nova mensagem de contato através do site Lins Travel.\n\n";
$emailBody .= "---------------------------------------------------\n";
$emailBody .= "Nome: " . $nome . "\n";
$emailBody .= "E-mail: " . $email . "\n";
$emailBody .= "Assunto: " . $assunto . "\n";
$emailBody .= "---------------------------------------------------\n\n";
$emailBody .= "Mensagem:\n" . $mensagem . "\n\n";
$emailBody .= "Data do Envio: " . date("d/m/Y H:i:s") . "\n";

// Cabeçalhos para o envio
$headers = "From: " . $nome . " <" . $email . ">\r\n";
$headers .= "Reply-To: " . $email . "\r\n";
$headers .= "X-Mailer: PHP/" . phpversion();


// ----------------------------------------------------
// 2. Tenta enviar o e-mail (SIMULAÇÃO)
// ----------------------------------------------------

// OBSERVAÇÃO IMPORTANTE: A função mail() do PHP só funciona se o servidor estiver
// configurado com um agente de transferência de e-mail (MTA, como Sendmail ou Postfix).
// Em muitos ambientes de hospedagem, é mais recomendado usar bibliotecas como PHPMailer.
// Para este exemplo, usaremos a função nativa mail(), assumindo que o ambiente pode enviá-lo.

$mailSentSuccessfully = false; // Flag para rastrear o sucesso

// Se o ambiente estiver configurado para enviar e-mails, descomente a linha abaixo:
// $mailSentSuccessfully = mail($toEmail, $emailSubject, $emailBody, $headers);

// Para propósitos de simulação em um ambiente sem MTA configurado (como este), 
// vamos SIMULAR que o envio foi bem-sucedido.
$mailSentSuccessfully = true; 

if ($mailSentSuccessfully) {
    // Redireciona com sucesso
    header("Location: contato.php?status=success");
    exit();
} else {
    // Redireciona com erro
    header("Location: contato.php?status=error");
    exit();
}
?>