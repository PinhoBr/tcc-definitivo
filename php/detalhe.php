<?php
session_start();
// Arquivo necessário para conexão com o banco de dados
include "conn.php";

// Função para buscar os dados de um único lugar
function buscarLugarPorId($pdo, $id) {
    if (!is_numeric($id)) {
        return null; // ID inválido
    }
    
    // Consulta SQL para buscar o local e garantir que esteja aprovado
    $sql = "SELECT * FROM lugares WHERE id = :id AND status_aprovacao = 'aprovado'";
    
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Erro ao buscar local: " . $e->getMessage());
        return null;
    }
}

// 1. Pega o ID da URL
$id = $_GET['id'] ?? null;

// 2. Busca o local (se o ID for válido)
$local = $id ? buscarLugarPorId($pdo, $id) : null;

// 3. Redireciona se o local não for encontrado ou se o ID for inválido
if (!$local) {
    header("Location: explorar.php");
    exit;
}

// Variáveis para o Header e Links (usando a mesma lógica do ../index.explorar.php)
$isLoggedIn = isset($_SESSION['user']);
$userRole = $_SESSION['user']['role'] ?? null;
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title><?= htmlspecialchars($local['nome']) ?> | Lins Travel</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700;800&display=swap" rel="stylesheet">
    
    <!-- Usando o CSS principal (style.css) para garantir o header e estilos base -->
    <link rel="stylesheet" href="../css/style.css"> 
    
    <!-- CSS específico para a página de detalhes -->
    <link rel="stylesheet" href="../css/detalhe_style.css">
    
    <!-- Ícone para o botão do WhatsApp/Telefone -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>

<!-- HEADER (Copiado INTEGRALMENTE de explorar.php para consistência visual) -->
<header class="site-header">
    <nav class="navbar" id="navbar">
        <div class="container nav-inner">
            <a class="brand" href="../index.php">Lins<span class="brand-accent">Travel</span></a>
            
            <div class="nav-links" id="navLinks">
               
                <a href="explorar.php">Explorar</a> 
                <a href="contato.php">Contato</a>
            </div>

            <div class="nav-actions">
                <?php if ($isLoggedIn): ?>
                    <div class="user-greeting">
                        Olá, <?= htmlspecialchars($_SESSION['user']['name'] ?? 'Usuário') ?> 
                        
                        <?php if ($userRole === 'ADM'): ?>
                            <a href="admin_dashboard.php" class="btn btn-primary" style="margin-right: 10px; padding: 5px 10px;">Administração</a>
                        <?php endif; ?>
                        
                        <?php if ($userRole === 'DONO'): ?>
                            <a href="owner_dashboard.php" class="btn btn-primary" style="margin-right: 10px; padding: 5px 10px;">Meu Painel</a>
                        <?php endif; ?>

                        <a href="logout.php" class="btn btn-outline">Sair</a>
                    </div>
                <?php else: ?>
                    <a class="btn btn-outline" href="login.php">Login</a>
                    <a class="btn btn-primary" href="create.php">Cadastrar</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>
</header>
<!-- FIM DO HEADER -->

<main>
    <section class="section detail-container">
        <div class="container">
            <!-- Breadcrumb / Navegação -->
            <p class="breadcrumb">
                <a href="explorar.php">Explorar</a> &raquo; 
                <?= htmlspecialchars(ucfirst($local['categoria'])) ?>
            </p>

            <div class="content-wrapper">
                
                <!-- Coluna da Esquerda: Imagem e Dados -->
                <div class="info-column">
                    
                    <!-- Imagem Principal (Galeria Simplificada) -->
                    <div class="main-image" style="background-image: url('<?= htmlspecialchars($local['imagem']) ?>');"
                        onerror="this.style.backgroundImage='url(https://placehold.co/800x600/cccccc/333333?text=Imagem+Indispon%C3%ADvel)'">
                        <span class="category-tag"><?= htmlspecialchars(ucfirst($local['categoria'])) ?></span>
                    </div>

                    <!-- Botões de Ação (Telefone e WhatsApp) -->
                    <div class="action-buttons">
                        <?php if (!empty($local['telefone'])): 
                            // Remove caracteres não numéricos para o link do WhatsApp
                            $whatsapp_num = preg_replace("/[^0-9]/", "", $local['telefone']);
                        ?>
                            <a href="https://wa.me/55<?= $whatsapp_num ?>" target="_blank" class="btn btn-whatsapp">
                                <i class="fab fa-whatsapp"></i> Conversar
                            </a>
                            <a href="tel:<?= htmlspecialchars($local['telefone']) ?>" class="btn btn-call">
                                <i class="fas fa-phone"></i> Ligar
                            </a>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Coluna da Direita: Conteúdo -->
                <div class="text-column">
                    <h1><?= htmlspecialchars($local['nome']) ?></h1>
                    
                    <div class="description-box">
                        <p class="lead-description"><?= nl2br(htmlspecialchars($local['descricao'])) ?></p>
                        <!-- Se você tiver uma descrição maior, pode colocá-la aqui -->
                    </div>

                    <!-- Seção de Localização e Mapa -->
                    <div class="location-map">
                        <h2>Localização</h2>
                        <div class="location-info">
                            <i class="fas fa-map-marker-alt"></i>
                            <p>CEP: <?= htmlspecialchars($local['cep'] ?? 'Não informado') ?></p>
                        </div>

                        <!-- Link para o Google Maps usando o CEP para navegação -->
                        <?php if (!empty($local['cep'])): 
                            $cep_query = urlencode($local['cep'] . ", Lins, SP");
                            // Link para abrir o Google Maps e buscar a rota a partir da posição atual do usuário
                            $maps_link = "https://www.google.com/maps/dir/?api=1&destination=" . $cep_query;
                        ?>
                            <a href="<?= $maps_link ?>" target="_blank" class="btn btn-maps">
                                <i class="fas fa-route"></i> Traçar Rota no Google Maps
                            </a>
                        <?php else: ?>
                            <p class="muted-error">Localização (CEP) não disponível para traçar rota.</p>
                        <?php endif; ?>
                    </div>
                    
                    <a href="explorar.php" class="btn btn-outline" style="margin-top: 30px;">
                        &laquo; Voltar para Explorar
                    </a>
                </div>
            </div>
        </div>
    </section>
</main>

<footer class="site-footer">
    <div class="container footer-bottom">
        <small>&copy; <?= date('Y') ?> Lins Travel. Todos os direitos reservados.</small>
    </div>
</footer>

<!-- Replicando o JS do ../index.php para o menu e a classe 'scrolled' funcionarem -->
<script>
    // navbar scroll
    const navbar = document.getElementById('navbar');
    window.addEventListener('scroll', () => {
        if (window.scrollY > 30) navbar.classList.add('scrolled');
        else navbar.classList.remove('scrolled');
    });
</script>

</body>
</html>