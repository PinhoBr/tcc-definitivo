-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 01/12/2025 às 01:29
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
(1, 2, 'Choppão Cristal', 'Desde 1980, o mais tradicional restaurante de Lins. Cardápio variado, com opções para todos os gostos e bolsos. Três ambientes: Ao ar livre, interno climatizado e salão de festas climatizado. Atraente adega com vinhos nacionais e importados. Equipe muito bem treinada para agilizar o atendimento com excelência mesmo quando a casa está lotada.', ' R. Luiz Gama, 1799 - Jardim Arapua, Lins - SP', 'https://dynamic-media-cdn.tripadvisor.com/media/photo-o/0f/1f/55/2c/entrada-principal.jpg?w=900&h=500&s=1', 'gastronomia', 'aprovado', 5.0, '2025-11-30 00:25:25', '16400-472', '3511-0707'),
(14, 2, 'Churrascaria Gaúcha', 'tem uma fachada rústica com detalhes em madeira e um letreiro bem visível. O visual é acolhedor e organizado, com plantas na entrada e um ambiente que passa sensação de conforto e boa comida, com cardápios cheios de opções e junto de um serviço self-service.', 'Churrascaria Gaúcha - Rua Pedro de Toledo, 1239 - Centro, Lins - SP, 16400-106', 'https://lh3.googleusercontent.com/gps-cs-s/AG0ilSwFVdfYWbaKqe4DtNG5nZcn0m2TB-VDdcmCLb_np8pcswfP5LwpTef-R6Tk9ASLq3BOjDsbWGvV9qPWbcoTmFOIJ61lTAKe_F_BEw1gQ30VDvXV8hKuQ4c6-Z6Oba8cnG40Ww=s680-w680-h510-rw', 'gastronomia', 'aprovado', 0.0, '2025-11-30 20:47:09', '16400-106', '3522-5546'),
(15, 2, 'Santo Tempero', 'tem uma fachada ampla e aberta, que deixa o interior do restaurante visível e convidativo. O ambiente é simples, organizado e iluminado, com balcão de atendimento na entrada e decoração discreta que passa uma sensação de praticidade e conforto', 'R. Osvaldo Cruz, 219 - Centro, Lins - SP, ', 'https://lh3.googleusercontent.com/gps-cs-s/AG0ilSzuyh2oQ9EXDtD-qoc866mZjy1ypS05rfGjbKzJoLMfW2_f7mnaCXPOB0l0Joey2GMfHLkEfS_hMeKi71bRqlMV8bek49HOqCj6ddUUpaTGZJy1CTCqjPuFWh0quyuNIJPKWmW2VA=s680-w680-h510-rw', 'gastronomia', 'aprovado', 0.0, '2025-11-30 20:56:42', '16400-060', '99764-9315'),
(16, 2, 'Pastelata Pastéis Gourmet', '\r\nO espaço de pastéis tem um ambiente externo aconchegante, com mesas ao ar livre, luzes decorativas e guarda-sóis que criam um clima agradável. O local fica bem movimentado à noite e oferece uma atmosfera descontraída, ideal para quem curte comer e conversar com tranquilidade\r\n', ' R. Luiz Gama, 95 - Centro, Lins - SP,', 'https://lh3.googleusercontent.com/p/AF1QipMvepavnLRp-TGcPvELGuHClJE_HFebGvqdhrvV=s680-w680-h510-rw', 'gastronomia', 'aprovado', 0.0, '2025-11-30 20:54:59', '16400-080', '99639-6656'),
(17, 2, 'restaurante Copacabana', 'O restaurante Copacabana tem uma entrada bem iluminada, com piso decorado e um totem central que destaca o nome do lugar. As luzes penduradas criam um clima sofisticado e acolhedor, dando ao espaço um visual organizado e elegante, ideal para quem busca uma experiência mais refinada.', 'R. Tomás Antônio Gonzaga, 135 - Jardim Ariano, Lins - SP', 'https://lh3.googleusercontent.com/gps-cs-s/AG0ilSwZjz8tl6tMkamhIytcy9g6O-FbHbEbGn_hlIl_rmmG70y11iRQvhttpJVAp9IejFDDsh3qo6bBa-ylKiYp8IV2ImpowJlGt-0E6k81buixNoPCWezNL-hhGniC0PTYU7gZD_RXuuGu3nVU=s680-w680-h510-rw', 'gastronomia', 'aprovado', 0.0, '2025-11-30 20:56:52', '16400-465', '98818-5064'),
(18, 2, 'Churrascaria Pampeana', 'A Churrascaria Pampeana tem uma fachada moderna e bem cuidada, com palmeiras, vidro e detalhes em madeira que dão um ar sofisticado ao lugar. A entrada ampla transmite organização e um clima de restaurante de alto padrão, conhecido pelos sabores bem preparados e ambiente confortável.\r\n', ' R. Fagundes Varela, 175 - Jardim Ariano, Lins - SP', 'https://lh3.googleusercontent.com/gps-cs-s/AG0ilSyhA8di6Qhss5zgXqDRtqps80nIDwjy0qf6qv4sywxD8Bu9UE6Xb_NO7gLFm24BGTK4LYsD4LevZgiwhRI8Qsc-qwCIboyVzjmaep5gMbq10XwoNB9s7HlJip9nmdbiXaffx9U7QA=s680-w680-h510-rw', 'gastronomia', 'aprovado', 0.0, '2025-11-30 21:03:56', '16400-463', ' 3522-3566'),
(19, 2, ' Austin Steakhouse', 'O Austin Steakhouse apresenta um estilo moderno e refinado, com acabamento de alto padrão e ambiente que remete a uma experiência gastronômica premium. O local é bem estruturado e acolhedor, ideal para quem busca pratos de primeira e um toque de exclusividade.', ' Av. José Ariano Rodrigues, 736 - Jardim Ariano, Lins - SP', 'https://lh3.googleusercontent.com/gps-cs-s/AG0ilSzzGOdgghja7IKwKK8AP38SJ_ndD4Lxz6lPUywdHEH2FLYO5XyUUAJZwkuL1PovTwicGpRuCserWiM-z2HioWJVu2QSaMGvMPmQSdDp3_6BpRDnRqLDflqCaXQ0rG2eFoJuGRM=s680-w680-h510-rw', 'gastronomia', 'aprovado', 0.0, '2025-11-30 21:03:09', '16400-400', '3025-9997'),
(20, 2, 'Flor de Truck', 'O Flor de Truck tem uma identidade divertida e criativa, inspirada em Alice no País das Maravilhas. O espaço é colorido, temático e cheio de detalhes que deixam o ambiente leve e encantado, ideal para quem curte um lanche com personalidade', 'R. Dr. Arnaldo Bastos, 120 - Labate, Lins - SP,', 'https://lh3.googleusercontent.com/gps-cs-s/AG0ilSxtPi1cZ1NCKaCC9mIwO5FDnMT3XJldEWSRhKRC3YZV0cvc-g0mA3DMrzdN0Zq1xrmH98PxH3rfVCOpxInvObCmXAd0UHInF2rpdQPJNxtDkcNa9SVKXcH_lgSx1a--XbPLhcw=s680-w680-h510-rw', 'gastronomia', 'aprovado', 0.0, '2025-11-30 21:01:43', '16400-553', '99753-0494'),
(21, 2, 'Casa do Tom – Pizzaria e Porções', 'A Casa do Tom tem um visual simples e acolhedor, aquele tipo de lugar que já passa vibe de comida boa só de olhar. O ambiente é iluminado, organizado e com aquele clima descontraído de pizzaria de bairro. As porções e pizzas são o destaque, trazendo aquele ar de “comida quentinha e bem feita” que todo mundo curte. É o tipo de espaço perfeito pra colar com a galera, comer bem e relaxar.', 'R. São Vito, 10 - Labate, Lins - SP', 'https://lh3.googleusercontent.com/gps-cs-s/AG0ilSw9TRC29GlQMHf3jfrIjY7emSq2QCrW5kCTuDY5t2iu1we6QluWJSaDnINXlqtFuEyXROvcIzdjVgT4AFKrHCUqFJ84fUvRAqH3uOt9-Ak3Vm4uMmrr-0uqyPw4Wync7kW2W9Bn=s680-w680-h510-rw', 'gastronomia', 'aprovado', 0.0, '2025-11-30 21:06:29', '16400-565', '99782-0777'),
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
(33, 2, 'Cristal Palace Hotel', 'O Cristal Palace Hotel apresenta uma fachada sofisticada e bem iluminada, transmitindo elegância logo na entrada. O visual moderno e organizado reforça o padrão de alto nível do local, oferecendo uma experiência refinada para hóspedes que buscam conforto e exclusividade.', 'Rua Luiz Gama 1771, Lins', 'https://lh3.googleusercontent.com/p/AF1QipOlD9z_uyqpho0_qaPzh2-slPUCfZkIHihqZ8Ds=s680-w680-h510-rw', 'hospedagem', 'aprovado', 0.0, '2025-11-30 21:11:26', '16400-472', '3511-0700'),
(35, 2, ' Blue Three Resort', 'O Blue Three Resort é um empreendimento de alto padrão, reconhecido por sua infraestrutura extensa e sofisticada. Como um resort voltado à experiência premium, oferece áreas amplas, ambientes elegantes e uma gama completa de serviços exclusivos. Sua estrutura de thermas, piscinas e espaços de lazer reforça o conceito de hospedagem de primeira linha, proporcionando conforto, privacidade e excelência em todos os detalhes. É um destino ideal para quem busca requinte, relaxamento e atendimento de alto nível.', 'Rod.Mal.Rondon, Km. 444 3, s/n - Zona Rural, Lins,', 'https://lh3.googleusercontent.com/p/AF1QipPbTP-IG-w9T1buuYb0YNYTYMt-JO49W6sghKRr=s680-w680-h510-rw', 'hospedagem', 'aprovado', 0.0, '2025-11-30 21:13:20', '16400-970', '3533-6300'),
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
