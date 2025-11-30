<?php
session_start();
// Inclui o arquivo de conexão com o banco de dados
include "conn.php";

// 1. Verificação de Autenticação e Autorização (MUITO IMPORTANTE)
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'DONO') {
    // Redireciona o usuário para a página de login se não for um Dono autenticado
    header("Location: login.php");
    exit();
}

$currentUserId = $_SESSION['user']['id'];
$userName = htmlspecialchars($_SESSION['user']['name']);
$userEmail = htmlspecialchars($_SESSION['user']['email']);

/**
 * Função para buscar todos os locais cadastrados por um dono específico,
 * independentemente do status de aprovação.
 *
 * @param PDO $pdo Objeto de conexão PDO.
 * @param int $donoId ID do usuário (dono)
 * @return array Dados encontrados.
 */
function buscarMeusLugares($pdo, $donoId) {
    // Busca todos os lugares onde o dono_id é igual ao ID do usuário logado
    $sql = "SELECT id, nome, categoria, status_aprovacao, data_cadastro FROM lugares WHERE dono_id = :donoId ORDER BY data_cadastro DESC";
    
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':donoId', $donoId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Erro ao buscar meus locais: " . $e->getMessage());
        return [];
    }
}

// Executa a busca
$meusLugares = buscarMeusLugares($pdo, $currentUserId);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Meu Painel - Lins Travel</title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700;800&display=swap" rel="stylesheet">
    <!-- Novo link para o arquivo CSS centralizado -->
    <link rel="stylesheet" href="../css/owner.css"> 
</head>
<body>

<nav class="navbar">
    <div class="nav-inner">
        <div class="brand"><a href="../index.php">Lins<span class="brand-accent">Travel</span></a></div>
        <div>
            <a href="../index.php" class="btn btn-back">← Voltar ao Site</a>
            <a href="logout.php" class="btn btn-outline">Sair</a>
        </div>
    </div>
</nav>

<div class="dashboard-container">
    <header class="dashboard-header">
        <h1>Painel do Dono</h1>
        <div class="profile-info">
            <p><strong>Nome:</strong> <?= $userName ?></p>
            <p><strong>Email:</strong> <?= $userEmail ?></p>
        </div>
    </header>

    <div class="action-bar">
        <h2>Meus Locais Cadastrados</h2>
        <a href="add_lugar.php" class="btn btn-primary">+ Cadastrar Novo Local</a>
    </div>
    
    <?php if (empty($meusLugares)): ?>
        <div style="padding: 20px; border: 1px dashed #ccc; text-align: center; margin-top: 20px; border-radius: 5px;">
            <p>Você ainda não cadastrou nenhum local.</p>
            <a href="add_lugar.php" class="btn btn-primary" style="margin-top: 10px;">Comece a Cadastrar Agora!</a>
        </div>
    <?php else: ?>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Local</th>
                    <th>Categoria</th>
                    <th>Cadastrado em</th>
                    <th>Status de Aprovação</th>
                    <th>Ações</th> 
                </tr>
            </thead>
            <tbody>
                <?php foreach ($meusLugares as $lugar): ?>
                    <tr>
                        <td><?= htmlspecialchars($lugar['nome']) ?></td>
                        <td><?= htmlspecialchars(ucfirst($lugar['categoria'])) ?></td>
                        <td><?= date('d/m/Y', strtotime($lugar['data_cadastro'])) ?></td>
                        <td>
                            <?php 
                                $status = htmlspecialchars($lugar['status_aprovacao']);
                                $class  = "status-" . strtolower($status);
                                $displayStatus = match ($status) {
                                    'aprovado'  => 'Aprovado',
                                    'pendente'  => 'Pendente',
                                    'rejeitado' => 'Rejeitado',
                                    default     => 'Desconhecido',
                                };
                            ?>
                            <span class="status-badge <?= $class ?>"><?= $displayStatus ?></span>
                        </td>
                        <td>
                            <!-- Link de Edição: Redireciona para a nova página de edição -->
                            <a href="edit_lugar.php?id=<?= $lugar['id'] ?>" class="action-link">Editar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <p style="margin-top: 20px; font-size: 0.9rem;">
            * **Aprovado:** Seu local está visível no site principal.
            * **Pendente:** Aguardando revisão e aprovação do Administrador.
            * **Rejeitado:** Seu local não pôde ser aprovado. Entre em contato com o suporte.
        </p>
    <?php endif; ?>

</div>

</body>
</html>