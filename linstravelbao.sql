-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 30/11/2025 às 22:22
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `linstravelbao`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `lugares`
--

CREATE TABLE `lugares` (
  `id` int(11) NOT NULL,
  `dono_id` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `descricao` text NOT NULL,
  `endereco` varchar(255) NOT NULL,
  `imagem` varchar(255) NOT NULL,
  `categoria` enum('destaque','turismo','gastronomia','hospedagem') NOT NULL,
  `status_aprovacao` enum('pendente','aprovado','rejeitado','') NOT NULL,
  `estrelas_media` decimal(2,1) NOT NULL,
  `data_cadastro` datetime NOT NULL,
  `cep` varchar(10) DEFAULT NULL,
  `telefone` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `lugares`
--

INSERT INTO `lugares` (`id`, `dono_id`, `nome`, `descricao`, `endereco`, `imagem`, `categoria`, `status_aprovacao`, `estrelas_media`, `data_cadastro`, `cep`, `telefone`) VALUES
(1, 2, 'chopao', 'lugar pra comer', 'chopao local', 'https://dynamic-media-cdn.tripadvisor.com/media/photo-o/0f/1f/55/2c/entrada-principal.jpg?w=900&h=500&s=1', 'gastronomia', 'aprovado', 5.0, '2025-11-30 00:25:25', '16400-472', '01435110707'),
(14, 2, 'Pizzaria Veneza', 'As melhores pizzas artesanais da cidade, preparadas em forno a lenha.', '', 'https://placehold.co/800x600/1fb5b2/ffffff?text=LINS+TRAVEL', 'gastronomia', 'aprovado', 0.0, '0000-00-00 00:00:00', NULL, NULL),
(15, 2, 'Café Expresso da Hora', 'Ambiente aconchegante, ideal para um café da manhã ou brunch.', '', 'https://placehold.co/800x600/1fb5b2/ffffff?text=LINS+TRAVEL', 'gastronomia', 'aprovado', 0.0, '0000-00-00 00:00:00', NULL, NULL),
(16, 2, 'Sushi Lins Premium', 'Comida japonesa fresca e criativa, perfeita para amantes de sushi.', '', 'https://placehold.co/800x600/1fb5b2/ffffff?text=LINS+TRAVEL', 'gastronomia', 'aprovado', 0.0, '0000-00-00 00:00:00', NULL, NULL),
(17, 2, 'Lanchonete Big Burger', 'Hambúrgueres artesanais e porções generosas para toda a família.', '', 'https://placehold.co/800x600/1fb5b2/ffffff?text=LINS+TRAVEL', 'gastronomia', 'aprovado', 0.0, '0000-00-00 00:00:00', NULL, NULL),
(18, 2, 'Doce Tentação Confeitaria', 'Doces finos, bolos e sobremesas que são uma verdadeira tentação.', '', 'https://placehold.co/800x600/1fb5b2/ffffff?text=LINS+TRAVEL', 'gastronomia', 'aprovado', 0.0, '0000-00-00 00:00:00', NULL, NULL),
(19, 2, 'Bar do Zé Carioca', 'Petiscos e cerveja gelada, ponto de encontro tradicional na noite de Lins.', '', 'https://placehold.co/800x600/1fb5b2/ffffff?text=LINS+TRAVEL', 'gastronomia', 'aprovado', 0.0, '0000-00-00 00:00:00', NULL, NULL),
(20, 2, 'Cozinha Saudável Fit', 'Refeições leves e fitness, feitas com ingredientes orgânicos e frescos.', '', 'https://placehold.co/800x600/1fb5b2/ffffff?text=LINS+TRAVEL', 'gastronomia', 'aprovado', 0.0, '0000-00-00 00:00:00', NULL, NULL),
(21, 2, 'Cantina Italiana Nonna', 'Massas caseiras e pratos clássicos italianos em um ambiente familiar.', '', 'https://placehold.co/800x600/1fb5b2/ffffff?text=LINS+TRAVEL', 'gastronomia', 'aprovado', 0.0, '0000-00-00 00:00:00', NULL, NULL),
(22, 2, 'Praça Central da Matriz', 'O coração da cidade, com jardins bem cuidados e arquitetura histórica.', '', 'https://placehold.co/800x600/1fb5b2/ffffff?text=LINS+TRAVEL', 'turismo', 'aprovado', 0.0, '0000-00-00 00:00:00', NULL, NULL),
(23, 2, 'Museu Histórico de Lins', 'Acervo que conta a trajetória da cidade desde sua fundação.', '', 'https://placehold.co/800x600/1fb5b2/ffffff?text=LINS+TRAVEL', 'turismo', 'aprovado', 0.0, '0000-00-00 00:00:00', NULL, NULL),
(24, 2, 'Bosque Municipal', 'Uma grande área verde para caminhadas, piqueniques e contato com a natureza.', '', 'https://placehold.co/800x600/1fb5b2/ffffff?text=LINS+TRAVEL', 'turismo', 'aprovado', 0.0, '0000-00-00 00:00:00', NULL, NULL),
(25, 2, 'Catedral Santo Antônio', 'Principal templo religioso, com vitrais e arquitetura imponente.', '', 'https://placehold.co/800x600/1fb5b2/ffffff?text=LINS+TRAVEL', 'turismo', 'aprovado', 0.0, '0000-00-00 00:00:00', NULL, NULL),
(27, 2, 'Memorial do Imigrante', 'Homenagem aos colonos que ajudaram a construir a cidade de Lins.', '', 'https://placehold.co/800x600/1fb5b2/ffffff?text=LINS+TRAVEL', 'turismo', 'aprovado', 0.0, '0000-00-00 00:00:00', NULL, NULL),
(28, 2, 'Feira Livre do Agricultor', 'Ponto de comércio de produtos frescos e típicos da região (Somente aos Sábados).', '', 'https://placehold.co/800x600/1fb5b2/ffffff?text=LINS+TRAVEL', 'turismo', 'aprovado', 0.0, '0000-00-00 00:00:00', NULL, NULL),
(29, 2, 'Centro de Eventos Lins', 'Local onde acontecem os maiores shows, exposições e festas da região.', '', 'https://placehold.co/800x600/1fb5b2/ffffff?text=LINS+TRAVEL', 'turismo', 'aprovado', 0.0, '0000-00-00 00:00:00', NULL, NULL),
(30, 2, 'Recanto do Pescador', 'Ótimo local para pesca esportiva e lazer à beira do rio.', '', 'https://placehold.co/800x600/1fb5b2/ffffff?text=LINS+TRAVEL', 'turismo', 'aprovado', 0.0, '0000-00-00 00:00:00', NULL, NULL),
(31, 2, 'Biblioteca Municipal', 'Um espaço cultural importante com vasto acervo para pesquisa e leitura.', '', 'https://placehold.co/800x600/1fb5b2/ffffff?text=LINS+TRAVEL', 'turismo', 'aprovado', 0.0, '0000-00-00 00:00:00', NULL, NULL),
(32, 2, 'Hotel Central De Lins', 'Conforto e comodidade no centro de Lins, ideal para viagens a trabalho.', '', 'https://images.trvl-media.com/lodging/38000000/37500000/37491300/37491257/4c4ead55.jpg?impolicy=resizecrop&rw=1200&ra=fit', 'hospedagem', 'aprovado', 0.0, '0000-00-00 00:00:00', NULL, NULL),
(33, 2, 'Pousada Solar da Fazenda', 'Ambiente rústico e acolhedor, com café da manhã típico da fazenda.', '', 'https://placehold.co/800x600/1fb5b2/ffffff?text=LINS+TRAVEL', 'hospedagem', 'aprovado', 0.0, '0000-00-00 00:00:00', NULL, NULL),
(35, 2, 'Hostel Mochileiro Feliz', 'Opção econômica e social, perfeita para viajantes solo ou em grupo.', '', 'https://placehold.co/800x600/1fb5b2/ffffff?text=LINS+TRAVEL', 'hospedagem', 'aprovado', 0.0, '0000-00-00 00:00:00', NULL, NULL),
(36, 2, 'Flat Executivo Lins', 'Apartamentos com cozinha compacta, ideais para estadias prolongadas.', '', 'https://placehold.co/800x600/1fb5b2/ffffff?text=LINS+TRAVEL', 'hospedagem', 'aprovado', 0.0, '0000-00-00 00:00:00', NULL, NULL),
(37, 2, 'Hotel Econômico Simples', 'Limpo e funcional, a melhor relação custo-benefício para pernoites rápidos.', '', 'https://placehold.co/800x600/1fb5b2/ffffff?text=LINS+TRAVEL', 'hospedagem', 'aprovado', 0.0, '0000-00-00 00:00:00', NULL, NULL),
(38, 2, 'Pousada Recanto Verde', 'Localizada na área rural, oferece paz, tranquilidade e contato com a natureza.', '', 'https://placehold.co/800x600/1fb5b2/ffffff?text=LINS+TRAVEL', 'hospedagem', 'aprovado', 0.0, '0000-00-00 00:00:00', NULL, NULL),
(39, 2, 'Hotel Express Lins', 'Focado em check-in e check-out rápidos, próximo à rodoviária.', '', 'https://placehold.co/800x600/1fb5b2/ffffff?text=LINS+TRAVEL', 'hospedagem', 'aprovado', 0.0, '0000-00-00 00:00:00', NULL, NULL),
(40, 2, 'Suítes Temporada Lins', 'Aluguel de casas e apartamentos mobiliados por curtos períodos.', '', 'https://placehold.co/800x600/1fb5b2/ffffff?text=LINS+TRAVEL', 'hospedagem', 'aprovado', 0.0, '0000-00-00 00:00:00', NULL, NULL),
(41, 2, 'Hotel Fazenda do Bosque', 'Oferece atividades de lazer, pensão completa e ambiente familiar.', '', 'https://placehold.co/800x600/1fb5b2/ffffff?text=LINS+TRAVEL', 'hospedagem', 'aprovado', 0.0, '0000-00-00 00:00:00', NULL, NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(150) CHARACTER SET ucs2 COLLATE ucs2_bin NOT NULL,
  `senha` varchar(255) NOT NULL,
  `tipo_usuario` enum('ADM','DONO','USUARIO','') NOT NULL,
  `data_registro` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome`, `email`, `senha`, `tipo_usuario`, `data_registro`) VALUES
(1, 'luis felipe gazeta', 'testeaaaa@gmail.com', '$2y$10$xFHYWevhepgASgTy.IsU6.XSANpOmFjLJ9UK1f6B4QBt7P2YFZZ.6', 'ADM', '2025-11-30 00:20:50'),
(2, 'jorge dono do chopao', 'jorgin@gmail.com', '$2y$10$ovJ2QiR3ERltPsyqA1uGw.LtU6iMAIn.J0GnXZTSHlyko/apK.1/i', 'DONO', '2025-11-30 00:23:08');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `lugares`
--
ALTER TABLE `lugares`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`,`dono_id`),
  ADD KEY `sim` (`dono_id`);

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `lugares`
--
ALTER TABLE `lugares`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `lugares`
--
ALTER TABLE `lugares`
  ADD CONSTRAINT `sim` FOREIGN KEY (`dono_id`) REFERENCES `usuarios` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
