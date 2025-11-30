<?php
session_start();
include "conn.php"; // Inclui o arquivo de conexão

// 1. Verificação de Autenticação e Autorização
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'DONO') {
    header("Location: login.php");
    exit();
}

// 2. Coleta do ID e Verificação
$lugarId = $_GET['id'] ?? null;
if (!$lugarId || !is_numeric($lugarId)) {
    // Redireciona se o ID for inválido
    header("Location: owner_dashboard.php");
    exit();
}

$currentUserId = $_SESSION['user']['id'];
$errorMessage = '';
$successMessage = '';

/**
 * Função para buscar um local específico e verificar a posse.
 *
 * @param PDO $pdo Objeto de conexão PDO.
 * @param int $lugarId ID do local a ser editado.
 * @param int $donoId ID do usuário logado (para segurança).
 * @return array|null Dados do local ou null se não for encontrado/propriedade.
 */
function buscarLugarParaEdicao($pdo, $lugarId, $donoId) {
    // Busca o local, garantindo que o dono_id seja o do usuário logado
    $sql = "SELECT id, nome, descricao, categoria, imagem FROM lugares WHERE id = :lugarId AND dono_id = :donoId";
    
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':lugarId', $lugarId, PDO::PARAM_INT);
        $stmt->bindParam(':donoId', $donoId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Erro ao buscar local para edição: " . $e->getMessage());
        return null;
    }
}

$lugar = buscarLugarParaEdicao($pdo, $lugarId, $currentUserId);

if (!$lugar) {
    // Se o local não existir ou não pertencer a este dono, redireciona
    header("Location: owner_dashboard.php?error=nao_encontrado");
    exit();
}

// Lista de categorias permitidas para o menu suspenso
$categorias = ['turismo', 'gastronomia', 'hospedagem', 'destaque'];

// Se houver mensagens de status na URL (após processamento da edição)
if (isset($_GET['status'])) {
    if ($_GET['status'] === 'success') {
        $successMessage = "Local atualizado com sucesso! Ele foi reenviado para aprovação.";
    } elseif ($_GET['status'] === 'error') {
        $errorMessage = "Erro ao atualizar o local. Tente novamente.";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Editar Local - <?= htmlspecialchars($lugar['nome']) ?></title>
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

<div class="dashboard-container">
    <header class="form-header">
        <h1>Editar Local: <?= htmlspecialchars($lugar['nome']) ?></h1>
        <p>Faça as alterações necessárias no seu estabelecimento.</p>
    </header>

    <?php if ($errorMessage): ?>
        <div class="alert alert-error"><?= $errorMessage ?></div>
    <?php endif; ?>

    <?php if ($successMessage): ?>
        <div class="alert alert-success"><?= $successMessage ?></div>
    <?php endif; ?>

    <!-- O formulário aponta para o novo arquivo de processamento -->
    <form action="process_edit_lugar.php" method="POST">
        <!-- Campo Oculto para o ID do Local -->
        <input type="hidden" name="lugar_id" value="<?= htmlspecialchars($lugar['id']) ?>">

        <div class="form-group">
            <label for="nome">Nome do Estabelecimento:</label>
            <input type="text" id="nome" name="nome" value="<?= htmlspecialchars($lugar['nome']) ?>" required>
        </div>

        <div class="form-group">
            <label for="categoria">Categoria:</label>
            <select id="categoria" name="categoria" required>
                <?php foreach ($categorias as $cat): ?>
                    <option value="<?= $cat ?>" <?= ($lugar['categoria'] === $cat) ? 'selected' : '' ?>>
                        <?= ucfirst($cat) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="descricao">Descrição:</label>
            <textarea id="descricao" name="descricao" required><?= htmlspecialchars($lugar['descricao']) ?></textarea>
            <small>Descreva brevemente seu local (máximo 255 caracteres).</small>
        </div>

        <div class="form-group">
            <label for="imagem">URL da Imagem:</label>
            <input type="text" id="imagem" name="imagem" value="<?= htmlspecialchars($lugar['imagem']) ?>">
            <small>Cole aqui o link direto da imagem. Ela aparecerá abaixo.</small>
            <?php if ($lugar['imagem']): ?>
                <img src="<?= htmlspecialchars($lugar['imagem']) ?>" alt="Pré-visualização da Imagem" class="image-preview" onerror="this.style.display='none'">
            <?php endif; ?>
        </div>
        
        <div class="form-group">
            <button type="submit" class="btn btn-primary">Salvar Alterações</button>
        </div>
    </form>
</div>

</body>
</html>