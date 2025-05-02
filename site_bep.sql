-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: mysql
-- Tempo de geração: 13/04/2025 às 12:04
-- Versão do servidor: 9.2.0
-- Versão do PHP: 8.2.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `site_bep`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `orders`
--

CREATE TABLE `orders` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `order_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `_status` enum('pending','completed') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `order_items`
--

CREATE TABLE `order_items` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `product_id` int NOT NULL,
  `quantity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estrutura stand-in para view `pending_cart`
-- (Veja abaixo para a visão atual)
--
CREATE TABLE `pending_cart` (
`order_status` enum('pending','completed')
,`price` decimal(10,2)
,`product` varchar(100)
,`quantity` int
,`stock` int
,`subtotal` decimal(20,2)
,`type` varchar(10)
,`username` varchar(50)
);

-- --------------------------------------------------------

--
-- Estrutura para tabela `products`
--

CREATE TABLE `products` (
  `id` int NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text,
  `price` decimal(10,2) NOT NULL,
  `stock` int NOT NULL,
  `type` varchar(10) NOT NULL,
  `image` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Despejando dados para a tabela `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price`, `stock`, `type`, `image`) VALUES
(1, 'T-shirt banda', 'Uma t-shirt estiloza com a imagem da banda', 9.99, 20, 'tshirt', '../image-produtos/camiseta_where.webp'),
(2, 'T-shirt preta', 'Uma t-shirt estiloza com a imagem de um monkey', 14.99, 20, 'tshirt', '../image-produtos/camiseta-black_monkey.webp'),
(3, 'CDs Black Eyed Peas', 'CD especial da banda', 25.00, 2, 'cds', '../image-produtos/cd-elevation.webp'),
(4, 'CDs B.E.P', 'CD especial da banda', 29.99, 2, 'cds', '../image-produtos/cd-the_beginning.webp'),
(5, 'T-shirt B.E.P', 'Uma t-shirt estiloza com a capa do CD Monkey Business', 14.99, 200, 'tshirt', '../image-produtos/camiseta-black_other.webp'),
(6, 'CD Monkey Business', 'CD completo Monkey Business', 24.99, 5, 'cds', '../image-produtos/cd-monkey_business.webp'),
(7, 'T-shirt B.E.P', 'Uma t-shirt estiloza preta da banda', 10.00, 200, 'tshirt', '../image-produtos/camiseta-black_red.jpg'),
(8, 'Quandro B.E.P', 'Um quadro para brilhar o seu lugar de descanço', 24.99, 7, 'casa', '../image-produtos/quadro_banda.webp'),
(9, 'T-shirt Disco', 'Uma t-shirt com um desenho lindo', 19.99, 100, 'tshirt', '../image-produtos/camiseta-black_white.webp'),
(10, 'Bombeta', 'Bombeta para ficar mais estiloso', 14.99, 4, 'acessorios', '../image-produtos/bone.jpg'),
(11, 'Metal Lunch', 'Lancheira de metal para manter temperatura', 22.00, 15, 'acessorios', '../image-produtos/iron_bag.png'),
(12, 'Knuckle Ring', 'Anel de ouro The Black Eyed Peas', 54.99, 4, 'acessorios', '../image-produtos/gold.png'),
(13, 'Quadro Disco', 'Moldura para abrilhantar vossa casa', 29.99, 4, 'casa', '../image-produtos/quadro2_banda.jpg');

-- --------------------------------------------------------

--
-- Estrutura stand-in para view `summary_orders`
-- (Veja abaixo para a visão atual)
--
CREATE TABLE `summary_orders` (
`_status` enum('pending','completed')
,`first_order` timestamp
,`total_orders` bigint
,`username` varchar(50)
);

-- --------------------------------------------------------

--
-- Estrutura para tabela `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `_password` varchar(255) NOT NULL,
  `role` enum('user','admin') DEFAULT 'user',
  `profile_pic` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Despejando dados para a tabela `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `_password`, `role`, `profile_pic`) VALUES
(1, 'kevin', 'kevin@admin.com', '$2y$12$Kyd1Dig9eDMFE.R5m7qtRuD1TrdzfLXtUa.w5OUhDTjbK1vxWNMDK', 'admin', 'uploads/dev-image4.jpeg'),
(2, 'admin', 'admin@admin.com', '$2y$12$v8T/z6yF/D1zoSewjfOa.uNqfKasHn0t5Ank8r30NMAREP/ifbFrS', 'admin', 'uploads/mysql_logo.png');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Índices de tabela `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Índices de tabela `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `products`
--
ALTER TABLE `products`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de tabela `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

-- --------------------------------------------------------

--
-- Estrutura para view `pending_cart`
--
DROP TABLE IF EXISTS `pending_cart`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `pending_cart`  AS SELECT `u`.`username` AS `username`, `p`.`name` AS `product`, `p`.`price` AS `price`, `p`.`stock` AS `stock`, `p`.`type` AS `type`, `oi`.`quantity` AS `quantity`, (`oi`.`quantity` * `p`.`price`) AS `subtotal`, `o`.`_status` AS `order_status` FROM (((`order_items` `oi` join `products` `p` on((`oi`.`product_id` = `p`.`id`))) join `users` `u` on((`oi`.`user_id` = `u`.`id`))) join `orders` `o` on((`u`.`id` = `o`.`user_id`))) WHERE (`o`.`_status` = 'pending') ;

-- --------------------------------------------------------

--
-- Estrutura para view `summary_orders`
--
DROP TABLE IF EXISTS `summary_orders`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `summary_orders`  AS SELECT `u`.`username` AS `username`, count(distinct `o`.`id`) AS `total_orders`, min(`o`.`order_date`) AS `first_order`, `o`.`_status` AS `_status` FROM (`orders` `o` join `users` `u` on((`o`.`user_id` = `u`.`id`))) WHERE (`o`.`_status` = 'completed') GROUP BY `u`.`id`, `u`.`username` ;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Restrições para tabelas `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
