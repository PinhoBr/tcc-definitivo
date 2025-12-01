-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 30/11/2025 às 04:48
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
  `data_cadastro` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `lugares`
--

INSERT INTO `lugares` (`id`, `dono_id`, `nome`, `descricao`, `endereco`, `imagem`, `categoria`, `status_aprovacao`, `estrelas_media`, `data_cadastro`) VALUES
(1, 2, 'chopao', 'lugar pra comer', 'chopao local', 'https://dynamic-media-cdn.tripadvisor.com/media/photo-o/0f/1f/55/2c/entrada-principal.jpg?w=900&h=500&s=1', 'gastronomia', 'aprovado', 0.0, '2025-11-30 00:25:25');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

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
