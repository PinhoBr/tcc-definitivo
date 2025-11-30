<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Explorar Lins | Lins Travel</title>
    <!-- Assumindo que você tem um style.css geral, e adicionando o específico -->
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/explorer.css"> 
</head>
<body>

<!-- Navegação fixa (Estática - Sem lógica de sessão PHP) -->
<nav class="navbar">
    <div class="nav-container">
        <a href="../index.php" class="nav-links">Lins Travel</a>
        
        <div class="nav-links">
            <!-- Links da Home Page Estáticos -->
            <a href="index.html#destaques">Destaques</a>
            <a href="index.html#turismo">Turismo</a>
            <a href="index.html#gastronomia">Gastronomia</a>
            <a href="index.html#contato">Contato</a>
            
            <!-- Links de Usuário Estáticos -->
            <a href="login.html">Login</a> 
            <a href="create.html">Cadastrar</a> 
        </div>
    </div>
</nav>

<main>
    <section id="locais-explorer" class="section">
        <h2>Explore Lins: Todos os Lugares</h2>
        
        <!-- ABAS DE FILTRO (Estáticas) -->
        <div class="filter-tabs">
            <a href="explorar.html?categoria=locais" 
               class="tab-link active">Todos</a>
            <a href="explorar.html?categoria=turismo" 
               class="tab-link">Turismo</a>
            <a href="explorar.html?categoria=gastronomia" 
               class="tab-link">Gastronomia</a>
        </div>
        
        <div class="grid explorer-grid">
            <!-- ESTE ESPAÇO ESTÁ VAZIO, PRONTO PARA RECEBER O LOOP DO BANCO DE DADOS EM PHP -->
            <!-- 
                EXEMPLO DE CARD (para referência de estrutura):
                <div class="card explorer-card" style="background-image: url('URL_DA_IMAGEM');">
                    <div class="card-body">
                        <h3>NOME DO LOCAL</h3>
                        <p>DESCRIÇÃO DO LOCAL</p>
                        <a href="detalhe.html?id=ID_DO_LOCAL" class="btn-secondary">Ver Detalhes</a>
                    </div>
                </div>
            -->
            
            <!-- Mensagem de placeholder para indicar que a área está vazia -->
            <p style="grid-column: 1 / -1; text-align: center; color: #6c757d; padding: 50px;">
                Área de conteúdo vazia. Insira seu código PHP de loop de banco de dados aqui.
            </p>

        </div>
    </section>
</main>

<footer>
    <p>&copy; 2025 Lins Travel. Todos os direitos reservados.</p>
</footer>

</body>
</html>
