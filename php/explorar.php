<?php
session_start();
include "conn.php"; // Inclui o arquivo de conexão

/**
 * Função para buscar todos os locais APENAS com status 'aprovado', 
 * com filtro opcional por termo de busca.
 *
 * @param PDO $pdo Objeto de conexão PDO.
 * @param string|null $termoBusca Termo para buscar no nome ou descrição.
 * @return array Dados dos locais aprovados.
 */
function buscarLocaisAprovados($pdo, $termoBusca = null) {
    
    $sql = "SELECT 
                id, 
                nome, 
                descricao, 
                categoria, 
                imagem 
            FROM lugares 
            WHERE status_aprovacao = 'aprovado'";

    $params = [];

    if ($termoBusca) {
        // Filtra por nome OU descrição, usando LIKE para busca parcial
        $sql .= " AND (nome LIKE :busca OR descricao LIKE :busca)";
        $params['busca'] = '%' . $termoBusca . '%';
    }
    
    // Ordena por categoria e depois por nome
    $sql .= " ORDER BY categoria, nome ASC";

    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Erro ao buscar locais aprovados: " . $e->getMessage());
        return [];
    }
}

// Obtém o termo de busca do formulário (GET)
$termoBusca = $_GET['busca'] ?? null;
$locais = buscarLocaisAprovados($pdo, $termoBusca);

// Agrupa os locais por categoria para exibição
$locaisPorCategoria = [];
foreach ($locais as $lugar) {
    // Garante que o nome da categoria seja sempre lowercase para o array key e IDs de âncora
    $categoria_key = strtolower($lugar['categoria']); 
    $locaisPorCategoria[$categoria_key][] = $lugar;
}

// Mapeamento de categorias para títulos amigáveis
$categoriasMap = [
    'destaque' => 'Destaques',
    'turismo' => 'Turismo',
    'gastronomia' => 'Gastronomia',
    'hospedagem' => 'Hospedagem'
];

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

    <style>
        .search-form {
            display: flex;
            gap: 10px;
            padding: 20px;
            margin-bottom: 30px;
            background-color: #f0f8ff; /* Azul muito claro */
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }
        .search-form input[type="text"] {
            flex-grow: 1;
            padding: 10px 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 1rem;
        }
        .search-form button {
            background-color: #0084db;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.2s;
        }
        .search-form button:hover {
            background-color: #006bbd;
        }
        /* Estilo para a seção de categoria */
        .category-section {
            margin-bottom: 40px;
            padding-top: 20px; /* Ajuda na rolagem para a âncora */
        }
        .category-title {
            border-bottom: 2px solid #0084db;
            padding-bottom: 10px;
            margin-bottom: 20px;
            color: #333;
            font-size: 1.8rem;
        }
    </style>
</head>
<body>

<!-- Usando a classe site-header para manter o mesmo estilo de padding/sombra do ../index -->
<header class="site-header">
    <nav class="navbar" id="navbar">
        <div class="container nav-inner">
            <a class="brand" href="../index.php">Lins<span class="brand-accent">Travel</span></a>
            
            <!-- Links de Navegação Principal -->
            <div class="nav-links" id="navLinks">
              
                
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
            
            <!-- Formulário de Busca -->
            <form action="explorar.php" method="GET" class="search-form">
                <input type="text" name="busca" placeholder="Buscar por nome ou descrição..." value="<?= htmlspecialchars($termoBusca ?? '') ?>">
                <button type="submit">Buscar</button>
            </form>

            <?php if (empty($locais)): ?>
                <div class="no-results">
                    <p>Nenhum local aprovado encontrado no momento<?php echo $termoBusca ? ' para o termo: "'.htmlspecialchars($termoBusca).'"' : ''; ?>.</p>
                    <p>Volte mais tarde ou <a href="contato.php" class="action-link">entre em contato</a> para sugerir um local!</p>
                </div>
            <?php else: ?>
                <!-- Itera sobre as categorias e exibe os locais -->
                <?php 
                $foundResults = false;
                foreach ($categoriasMap as $categoria_key => $categoria_titulo): 
                    // Verifica se há locais nesta categoria após a busca
                    if (isset($locaisPorCategoria[$categoria_key])):
                        $foundResults = true;
                ?>
                        <!-- Âncora para o link "Ver Mais" -->
                        <section id="<?= $categoria_key ?>" class="category-section">
                            <h2 class="category-title"><?= $categoria_titulo ?></h2>
                            <div class="grid">
                                <?php foreach ($locaisPorCategoria[$categoria_key] as $lugar): 
                                    $detail_link = "detalhe.php?id=" . $lugar['id'];
                                ?>
                                    <!-- O card inteiro se torna clicável -->
                                    <a href="<?= $detail_link ?>" class="card"> 
                                        <div class="card-media" 
                                            style="background-image: url('<?= htmlspecialchars($lugar['imagem']) ?>');"
                                            onerror="this.style.backgroundImage='url(https://placehold.co/400x200/cccccc/333333?text=Imagem+N%C3%A3o+Dispon%C3%ADvel)'">
                                        </div>
                                        
                                        <div class="card-body">
                                            <span class="status-badge" 
                                                style="background: linear-gradient(90deg, #0084db, #1fb5b2); font-size: 0.75rem;">
                                                <?= htmlspecialchars(ucfirst($lugar['categoria'])) ?>
                                            </span>
                                            
                                            <h3><?= htmlspecialchars($lugar['nome']) ?></h3>
                                            <p>
                                                <?= htmlspecialchars(mb_strimwidth($lugar['descricao'], 0, 100, "...")) ?>
                                            </p>
                                            
                                            <div class="card-action-btn">
                                                Ver Detalhes &rarr;
                                            </div>
                                        </div>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        </section>
                <?php 
                    endif;
                endforeach; 
                
                // Se o termo de busca não retornou nada em NENHUMA categoria
                if (!$foundResults && $termoBusca):
                ?>
                    <div class="no-results">
                        <p>Nenhum local encontrado para o termo: "<?= htmlspecialchars($termoBusca) ?>".</p>
                        <p>Tente refinar sua busca.</p>
                    </div>
                <?php endif; ?>
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

    // Função para rolagem suave para a âncora (útil para links do index)
    document.addEventListener('DOMContentLoaded', () => {
        if (window.location.hash) {
            const target = document.querySelector(window.location.hash);
            if (target) {
                // Atraso para garantir que o scroll ocorra após o carregamento de todo o conteúdo
                setTimeout(() => {
                    target.scrollIntoView({ behavior: 'smooth' });
                }, 100); 
            }
        }
    });
</script>

</body>
</html>