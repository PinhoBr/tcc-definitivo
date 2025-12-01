<?php
session_start();
// Arquivo necessário para conexão com o banco de dados
include "php/conn.php";

/**
 * Função para buscar locais aprovados com filtros de categoria e limite.
 *
 * @param PDO $pdo Objeto de conexão PDO.
 * @param string|null $categoria Categoria a ser filtrada.
 * @param string $aprovacao Status de aprovação ('aprovado').
 * @param int|null $limit Limite de resultados (ex: 3).
 * @return array Dados dos locais.
 */
function buscarLugares($pdo, $categoria = null, $aprovacao = 'aprovado', $limit = null) {
    // 1. Define a query base com filtro de segurança
    $sql = "SELECT id, nome, descricao, categoria, imagem FROM lugares WHERE status_aprovacao = :aprovacao";
    $params = ['aprovacao' => $aprovacao];

    // 2. Adiciona o filtro de categoria, se for passado
    if ($categoria) {
        $sql .= " AND categoria = :categoria";
        $params['categoria'] = $categoria;
    }
    
    // Ordena apenas pelo nome
    $sql .= " ORDER BY nome ASC";

    // 3. Adiciona o limite (PDO precisa de bindValue para LIMIT)
    if ($limit) {
        $sql .= " LIMIT :limit_val";
    }

    try {
        $stmt = $pdo->prepare($sql);
        
        // Bind manual dos parâmetros para lidar com o LIMIT corretamente
        $stmt->bindValue(':aprovacao', $aprovacao, PDO::PARAM_STR);
        if ($categoria) {
            $stmt->bindValue(':categoria', $categoria, PDO::PARAM_STR);
        }
        if ($limit) {
            $stmt->bindValue(':limit_val', $limit, PDO::PARAM_INT);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        // Se houver um erro, registra e retorna vazio
        error_log("Erro ao buscar dados: " . $e->getMessage());
        return [];
    }
}

// Busca os dados do banco de dados (COM LIMIT 3)
$destaques   = buscarLugares($pdo, "destaque", 'aprovado', 3);
$turismo     = buscarLugares($pdo, "turismo", 'aprovado', 3);
$gastronomia = buscarLugares($pdo, "gastronomia", 'aprovado', 3);
$hospedagem  = buscarLugares($pdo, "hospedagem", 'aprovado', 3); 

// Verifica o tipo de usuário para exibir botões condicionalmente
$userRole = $_SESSION['user']['role'] ?? 'GUEST'; // 'GUEST' se não estiver logado
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Lins Travel</title>

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700;800&display=swap" rel="stylesheet">

  <!-- Estilos CSS (Incorporados para garantir que apareça) -->
  <link rel="stylesheet" href="css/style.css" />
</head>
<body>

<header class="site-header">
  <nav class="navbar" id="navbar">
    <div class="container nav-inner">
      <a class="brand" href="#">Lins<span class="brand-accent">Travel</span></a>

      <!-- Links de Navegação Principal -->
      <div class="nav-links" id="navLinks">
        
        <a href="#destaques">Destaques</a>
        <a href="#turismo">Turismo</a>
        <a href="#gastronomia">Gastronomia</a>
        <a href="#hospedagem">Hospedagem</a> 
        <a href="php/contato.php">Contato</a>
      </div>

      <!-- Ações de Login/Logout e Painéis -->
      <div class="nav-actions">
        <?php if (isset($_SESSION['user'])): ?>
          <div class="user-greeting">
            Olá, <?= htmlspecialchars($_SESSION['user']['name']) ?> 
            
            <?php if ($userRole === 'ADM'): ?>
              <!-- BOTÃO DE ADMINISTRAÇÃO -->
              <a href="php/admin_dashboard.php" class="btn btn-primary" style="margin-right: 10px; padding: 5px 10px;">Administração</a>
            <?php endif; ?>
            
            <?php if ($userRole === 'DONO'): ?>
              <!-- BOTÃO PARA O PAINEL DO DONO -->
              <a href="php/owner_dashboard.php" class="btn btn-primary" style="margin-right: 10px; padding: 5px 10px;">Meu Painel</a>
            <?php endif; ?>

            <a href="php/logout.php" class="btn btn-outline">Sair</a>
          </div>
        <?php else: ?>
          <a class="btn btn-outline" href="php/login.php">Login</a>
          <a class="btn btn-primary" href="php/create.php">Cadastrar</a>
        <?php endif; ?>
      </div>

      
    </div>
  </nav>

  <section class="hero">
    <div class="container hero-inner">
      <h1 class="hero-title">Lins Travel</h1>
      <p class="hero-sub">Descubra as maravilhas de Lins, SP</p>
      <a href="php/explorar.php" class="btn btn-cta">Explore Agora</a>
    </div>
  </section>
</header>

<main>
  <!-- SEÇÃO DE DESTAQUES (Lugares Aprovados) -->
  <section id="destaques" class="section">
    <div class="container section-header">
      <h2>Destaques</h2>
      <p class="muted">Locais mais relevantes ou promovidos</p>
    </div>

    <div class="container grid">
      <?php if (!empty($destaques)): ?>
        <?php foreach ($destaques as $item): ?>
          <article class="card reveal">
            <!-- Link para a página de detalhe -->
            <a href="php/detalhe.php?id=<?= $item['id'] ?>" style="text-decoration:none; color:inherit;">
              <div class="card-media" style="background-image:url('<?= htmlspecialchars($item['imagem'] ?: 'https://placehold.co/300x200/cccccc/333333?text=Sem+Imagem') ?>')"></div>
            </a>
            <div class="card-body">
              <h3><?= htmlspecialchars($item['nome']) ?></h3>
              <p><?= htmlspecialchars(substr($item['descricao'], 0, 100)) ?>...</p>
            </div>
          </article>
        <?php endforeach; ?>
      <?php else: ?>
        <p class="container">Nenhum destaque aprovado encontrado.</p>
      <?php endif; ?>
    </div>
    <!-- Botão "Ver Mais" -->
    <div class="container text-center" style="margin-top: 20px; text-align: center;">
        <a href="php/explorar.php#destaque" class="btn btn-outline">Ver Mais Destaques</a>
    </div>
  </section>


  <!-- SEÇÃO DE TURISMO (Lugares Aprovados) -->
  <section id="turismo" class="section alt-bg">
    <div class="container section-header">
      <h2>Turismo</h2>
      <p class="muted">Passeios e pontos turísticos</p>
    </div>

    <div class="container grid">
      <?php if (!empty($turismo)): ?>
        <?php foreach ($turismo as $item): ?>
          <article class="card reveal">
            <!-- Link para a página de detalhe -->
            <a href="php/detalhe.php?id=<?= $item['id'] ?>" style="text-decoration:none; color:inherit;">
              <div class="card-media" style="background-image:url('<?= htmlspecialchars($item['imagem'] ?: 'https://placehold.co/300x200/cccccc/333333?text=Sem+Imagem') ?>')"></div>
            </a>
            <div class="card-body">
              <h3><?= htmlspecialchars($item['nome']) ?></h3>
              <p><?= htmlspecialchars(substr($item['descricao'], 0, 100)) ?>...</p>
            </div>
          </article>
        <?php endforeach; ?>
      <?php else: ?>
        <p class="container">Nenhum ponto turístico aprovado encontrado.</p>
      <?php endif; ?>
    </div>
    <!-- Botão "Ver Mais" -->
    <div class="container text-center" style="margin-top: 20px; text-align: center;">
        <a href="php/explorar.php#turismo" class="btn btn-outline">Ver Mais Turismo</a>
    </div>
  </section>

  <!-- SEÇÃO DE GASTRONOMIA (Lugares Aprovados) -->
  <section id="gastronomia" class="section">
    <div class="container section-header">
      <h2>Gastronomia</h2>
      <p class="muted">Sabores e restaurantes locais</p>
    </div>

    <div class="container grid">
      <?php if (!empty($gastronomia)): ?>
        <?php foreach ($gastronomia as $item): ?>
          <article class="card reveal">
            <!-- Link para a página de detalhe -->
            <a href="php/detalhe.php?id=<?= $item['id'] ?>" style="text-decoration:none; color:inherit;">
              <div class="card-media" style="background-image:url('<?= htmlspecialchars($item['imagem'] ?: 'https://placehold.co/300x200/cccccc/333333?text=Sem+Imagem') ?>')"></div>
            </a>
            <div class="card-body">
              <h3><?= htmlspecialchars($item['nome']) ?></h3>
              <p><?= htmlspecialchars(substr($item['descricao'], 0, 100)) ?>...</p>
            </div>
          </article>
        <?php endforeach; ?>
      <?php else: ?>
        <p class="container">Nenhum local de gastronomia aprovado encontrado.</p>
      <?php endif; ?>
    </div>
    <!-- Botão "Ver Mais" -->
    <div class="container text-center" style="margin-top: 20px; text-align: center;">
        <a href="php/explorar.php#gastronomia" class="btn btn-outline">Ver Mais Gastronomia</a>
    </div>
  </section>
  
  <!-- SEÇÃO DE HOSPEDAGEM (Lugares Aprovados) -->
  <section id="hospedagem" class="section alt-bg">
    <div class="container section-header">
      <h2>Hospedagem</h2>
      <p class="muted">Hotéis, pousadas e acomodações</p>
    </div>

    <div class="container grid">
      <?php if (!empty($hospedagem)): ?>
        <?php foreach ($hospedagem as $item): ?>
          <article class="card reveal">
            <!-- Link para a página de detalhe -->
            <a href="php/detalhe.php?id=<?= $item['id'] ?>" style="text-decoration:none; color:inherit;">
              <div class="card-media" style="background-image:url('<?= htmlspecialchars($item['imagem'] ?: 'https://placehold.co/300x200/cccccc/333333?text=Sem+Imagem') ?>')"></div>
            </a>
            <div class="card-body">
              <h3><?= htmlspecialchars($item['nome']) ?></h3>
              <p><?= htmlspecialchars(substr($item['descricao'], 0, 100)) ?>...</p>
            </div>
          </article>
        <?php endforeach; ?>
      <?php else: ?>
        <p class="container">Nenhuma opção de hospedagem aprovada encontrada.</p>
      <?php endif; ?>
    </div>
    <!-- Botão "Ver Mais" -->
    <div class="container text-center" style="margin-top: 20px; text-align: center;">
        <a href="php/explorar.php#hospedagem" class="btn btn-outline">Ver Mais Hospedagem</a>
    </div>
  </section>

  <section id="contato" class="section contact">
    <div class="container contact-inner reveal" style="text-align: center; background: #e0f7fa; padding: 40px; border-radius: 10px;">
      <h2>Planeje sua Viagem</h2>
      <p>Entre em contato conosco para mais informações. Estamos prontos para ajudar.</p>
      <a class="btn btn-cta" href="php/contato.php">Fale Conosco</a>
    </div>
  </section>
</main>

<footer class="site-footer">
  <div class="container footer-grid">
    <div>
      <h4>Sobre</h4>
      <p>Agência local dedicada a mostrar o melhor de Lins.</p>
    </div>
    <div>
      <h4>Contato</h4>
      <p>contato@linstravel.com<br/> (xx) xxxx-xxxx</p>
      <a class="btn btn-primary" href="php/add_lugar.php">Cadastrar Meu Local</a>
    </div>
    <div>
      <h4>Siga-nos</h4>
      <p class="socials">
        <a href="#" aria-label="Instagram">Instagram</a> • <a href="#" aria-label="WhatsApp">WhatsApp</a>
      </p>
    </div>
  </div>

  <div class="container footer-bottom">
    <small>&copy; <?= date('Y') ?> Lins Travel. Todos os direitos reservados.</small>
  </div>
</footer>

<!-- Pequeno JS: toggle menu + navbar scrolled + reveal on scroll -->
<script>
  // navbar scroll
  const navbar = document.getElementById('navbar');
  window.addEventListener('scroll', () => {
    if (window.scrollY > 30) navbar.classList.add('scrolled');
    else navbar.classList.remove('scrolled');
  });

  // mobile toggle
  const navToggle = document.getElementById('navToggle');
  const navLinks = document.getElementById('navLinks');
  // Verifica se os elementos existem antes de adicionar o listener
  if (navToggle) {
    navToggle.addEventListener('click', () => {
      navLinks.classList.toggle('open');
    });
  }
  

  // reveal on scroll
  const reveals = document.querySelectorAll('.card.reveal');
  const io = new IntersectionObserver((entries) => {
    entries.forEach(e => {
      if (e.isIntersecting) {
        e.target.classList.add('is-visible');
        io.unobserve(e.target);
      }
    });
  }, {threshold: 0.12});
  reveals.forEach(r => io.observe(r));
</script>

</body>
</html>