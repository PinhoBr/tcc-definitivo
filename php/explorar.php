<?php
session_start();
include "conn.php"; // Inclui o arquivo de conexão

/**
 * Função para buscar todos os locais APENAS com status 'aprovado'.
 *
 * @param PDO $pdo Objeto de conexão PDO.
 * @return array Dados dos locais aprovados.
 */
function buscarLocaisAprovados($pdo) {
    // Seleciona todos os campos necessários, filtrando apenas por 'aprovado'
    $sql = "SELECT id, nome, descricao, categoria, imagem FROM lugares WHERE status_aprovacao = 'aprovado' ORDER BY nome ASC";
    
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        // Em caso de erro, loga e retorna array vazio para não quebrar a página
        error_log("Erro ao buscar locais aprovados: " . $e->getMessage());
        return [];
    }
}

$locais = buscarLocaisAprovados($pdo);

// Verifica o estado de autenticação para exibir links de painel
$isLoggedIn = isset($_SESSION['user']);
$userRole = $_SESSION['user']['role'] ?? null;

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Explorar Lins - Lins Travel</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700;800&display=swap" rel="stylesheet">
    
    <!-- Usando o CSS principal (style.css) para garantir o mesmo header -->
    <link rel="stylesheet" href="../css/style.css"> 
    
    <!-- Este CSS específico é necessário para os cards e o layout da página Explorar -->
    <link rel="stylesheet" href="../css/explorar.css">
</head>
<body>

<!-- Usando a classe site-header para manter o mesmo estilo de padding/sombra do ../index -->
<header class="site-header">
    <nav class="navbar" id="navbar">
        <div class="container nav-inner">
            <a class="brand" href="../index.php">Lins<span class="brand-accent">Travel</span></a>
            
            <!-- Links de Navegação Principal -->
            <div class="nav-links" id="navLinks">
                <!-- Links de Categoria da Home (agora apontam para a página ../index e a âncora) -->
               
                
                <!-- Link específico para Explorar com a classe ativa -->
                <a href="explorar.php" class="nav-active">Explorar</a> 
                
                <a href="contato.php">Contato</a>
            </div>

            <!-- Ações de Login/Logout e Painéis (Mantendo a estrutura do ../index.php) -->
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

<main>
    <header class="page-header">
        <div class="container">
            <h1>Descubra Lins</h1>
            <p>Conheça os melhores pontos turísticos, gastronômicos e de hospedagem que a cidade oferece.</p>
        </div>
    </header>

    <section class="section explorar-section">
        <div class="container">
            
            <?php if (empty($locais)): ?>
                <div class="no-results">
                    <p>Nenhum local aprovado encontrado no momento.</p>
                    <p>Volte mais tarde ou <a href="contato.php" class="action-link">entre em contato</a> para sugerir um local!</p>
                </div>
            <?php else: ?>
                <div class="grid">
                    <?php foreach ($locais as $lugar): 
                        // Cria o link de detalhes
                        $detail_link = "detalhe.php?id=" . $lugar['id'];
                    ?>
                        <!-- O card inteiro se torna clicável -->
                        <a href="<?= $detail_link ?>" class="card"> 
                            <!-- Imagem de fundo do card -->
                            <div class="card-media" 
                                style="background-image: url('<?= htmlspecialchars($lugar['imagem']) ?>');"
                                onerror="this.style.backgroundImage='url(https://placehold.co/400x200/cccccc/333333?text=Imagem+N%C3%A3o+Dispon%C3%ADvel)'">
                            </div>
                            
                            <div class="card-body">
                                <!-- Categoria como um marcador -->
                                <span class="status-badge" 
                                    style="background: linear-gradient(90deg, #0084db, #1fb5b2); font-size: 0.75rem;">
                                    <?= htmlspecialchars(ucfirst($lugar['categoria'])) ?>
                                </span>
                                
                                <h3><?= htmlspecialchars($lugar['nome']) ?></h3>
                                <p>
                                    <?= htmlspecialchars(
                                        // Limita a descrição para caber no card
                                        mb_strimwidth($lugar['descricao'], 0, 100, "...")
                                    ) ?>
                                </p>
                                
                                <!-- Botão de Ação/Detalhes -->
                                <div class="card-action-btn">
                                    Ver Detalhes &rarr;
                                </div>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </section>
</main>

<!-- O rodapé será estilizado pelo style.css e explorado_layout.css -->
<footer class="site-footer">
    <div class="container footer-bottom">
        <small>&copy; <?= date('Y') ?> Lins Travel. Todos os direitos reservados. | <a href="contato.php">Contato</a></small>
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

    // Removendo o toggle mobile, pois ele não estava no ../index.php
</script>

</body>
</html>