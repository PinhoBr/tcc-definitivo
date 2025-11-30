<?php
session_start();
include "conn.php"; // Inclui o arquivo de conexão

// 1. PROTEÇÃO DE ROTA: Verifica se o usuário está logado E se ele é um ADM
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'ADM') {
    // Redireciona para o login ou página inicial se não for autorizado
    header("Location: login.php");
    exit;
}

$message = "";
$places = [];

// 2. PROCESSAMENTO DE AÇÕES (Aprovar, Rejeitar, Excluir)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && isset($_POST['place_id'])) {
    $placeId = filter_input(INPUT_POST, 'place_id', FILTER_VALIDATE_INT);
    $action = filter_input(INPUT_POST, 'action', FILTER_SANITIZE_STRING);
    
    if ($placeId) {
        try {
            switch ($action) {
                case 'approve':
                    // APROVAR: Mudar status_aprovacao para 'aprovado'
                    $sql = "UPDATE lugares SET status_aprovacao = 'aprovado' WHERE id = :id";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute(['id' => $placeId]);
                    $message = "Local ID #{$placeId} APROVADO com sucesso!";
                    break;
                case 'reject':
                    // REJEITAR: Mudar status_aprovacao para 'rejeitado'
                    $sql = "UPDATE lugares SET status_aprovacao = 'rejeitado' WHERE id = :id";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute(['id' => $placeId]);
                    $message = "Local ID #{$placeId} REJEITADO.";
                    break;
                case 'delete':
                    // EXCLUIR: Remover o registro
                    $sql = "DELETE FROM lugares WHERE id = :id";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute(['id' => $placeId]);
                    $message = "Local ID #{$placeId} EXCLUÍDO permanentemente.";
                    break;
                default:
                    $message = "Ação inválida.";
            }
        } catch (PDOException $e) {
            $message = "Erro ao executar ação no banco de dados. " . $e->getMessage();
            error_log("DB Error: " . $e->getMessage());
        }
    } else {
        $message = "ID do local inválido.";
    }
}

// 3. BUSCAR LUGARES PENDENTES
try {
    // Busca todos os lugares (aprovados, rejeitados e, principalmente, pendentes)
    // Para um painel ADM, é útil ver todos, mas destacamos os 'pendentes'
    $sql = "SELECT l.*, u.nome AS dono_nome, u.email AS dono_email FROM lugares l
            LEFT JOIN usuarios u ON l.dono_id = u.id
            ORDER BY l.data_cadastro DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $places = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    $message = "Erro ao carregar lista de locais: " . $e->getMessage();
    error_log("DB Error: " . $e->getMessage());
}

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel de Administração - Lins Travel</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">

    <style>
        .admin-container {
            font-family: 'Poppins', sans-serif;
            background-color: #f0f4f8;
            min-height: 100vh;
            padding: 20px;
        }
        .header {
            background-color: #ffffff;
            padding: 15px 30px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #cc0000; /* Cor de ADM */
            font-size: 1.5rem;
            font-weight: 700;
        }
        .header a {
            color: #0084db;
            text-decoration: none;
            font-weight: 500;
        }
        .header a:hover {
            text-decoration: underline;
        }
        .content-area {
            background: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            margin: 0 auto;
        }
        .content-area h2 {
            color: #333;
            border-bottom: 2px solid #eee;
            padding-bottom: 10px;
            margin-bottom: 25px;
            font-weight: 600;
        }
        .message-box {
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
            font-weight: 500;
            background-color: #e0f7fa;
            color: #0084db;
        }
        .place-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .place-table th, .place-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        .place-table th {
            background-color: #f5f5f5;
            color: #555;
            font-weight: 600;
            font-size: 0.9rem;
        }
        .status-pendente { color: #f90; font-weight: 600; }
        .status-aprovado { color: #0a0; font-weight: 600; }
        .status-rejeitado { color: #c00; font-weight: 600; }

        .actions button {
            padding: 5px 10px;
            margin-right: 5px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.85rem;
            transition: opacity 0.2s;
        }
        .actions button:hover {
            opacity: 0.8;
        }
        .btn-approve { background-color: #28a745; color: white; }
        .btn-reject { background-color: #ffc107; color: #333; }
        .btn-delete { background-color: #dc3545; color: white; }
    </style>
</head>
<body>

<div class="admin-container">
    <div class="header">
        <h1>Painel do Administrador</h1>
        <div>
            <span class="user-info">ADM: <?= htmlspecialchars($_SESSION['user']['name']) ?></span>
            <a href="../index.php" style="margin-left: 15px;">Voltar ao Site</a>
            <a href="logout.php" class="logout-btn" style="margin-left: 15px; background-color: #cc0000; color: white; padding: 8px 15px; border-radius: 6px;">Sair</a>
        </div>
    </div>

    <div class="content-area">
        <h2>Moderação de Locais Cadastrados (<?= count($places) ?> no total)</h2>

        <?php if (!empty($message)): ?>
            <div class="message-box"><?= $message ?></div>
        <?php endif; ?>

        <?php if (empty($places)): ?>
            <p>Nenhum local cadastrado no momento.</p>
        <?php else: ?>
            <table class="place-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome do Local</th>
                        <th>Dono</th>
                        <th>Categoria</th>
                        <th>Status</th>
                        <th>Data Cadastro</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($places as $place): ?>
                        <tr class="<?= $place['status_aprovacao'] === 'pendente' ? 'highlight-pending' : '' ?>">
                            <td><?= htmlspecialchars($place['id']) ?></td>
                            <td>
                                <strong><?= htmlspecialchars($place['nome']) ?></strong>
                                <br><small style="color: #777;"><?= htmlspecialchars(substr($place['descricao'], 0, 50)) ?>...</small>
                            </td>
                            <td>
                                <?= htmlspecialchars($place['dono_nome'] ?? 'Dono Excluído') ?>
                                <br><small style="color: #0084db;"><?= htmlspecialchars($place['dono_email'] ?? '') ?></small>
                            </td>
                            <td><?= htmlspecialchars(ucfirst($place['categoria'])) ?></td>
                            <td class="status-<?= htmlspecialchars($place['status_aprovacao']) ?>">
                                <?= htmlspecialchars(ucfirst($place['status_aprovacao'])) ?>
                            </td>
                            <td><?= date('d/m/Y', strtotime($place['data_cadastro'])) ?></td>
                            <td class="actions">
                                <form method="POST" action="admin_dashboard.php" style="display:inline;">
                                    <input type="hidden" name="place_id" value="<?= $place['id'] ?>">
                                    <?php if ($place['status_aprovacao'] !== 'aprovado'): ?>
                                        <button type="submit" name="action" value="approve" class="btn-approve">Aprovar</button>
                                    <?php endif; ?>
                                    <?php if ($place['status_aprovacao'] !== 'rejeitado'): ?>
                                        <button type="submit" name="action" value="reject" class="btn-reject">Rejeitar</button>
                                    <?php endif; ?>
                                    <button type="submit" name="action" value="delete" class="btn-delete" onclick="return confirm('Tem certeza que deseja EXCLUIR o local ID #<?= $place['id'] ?>? Esta ação é irreversível.');">Excluir</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>

</body>
</html>