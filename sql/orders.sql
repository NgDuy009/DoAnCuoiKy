CREATE DATABASE IF NOT EXISTS `tzy_store` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `tzy_store`;

CREATE TABLE IF NOT EXISTS `orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_email` varchar(255) NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `address` text NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'Chờ xử lý',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `orders` (`id`, `user_email`, `fullname`, `phone`, `address`, `total_amount`, `status`, `created_at`) VALUES
(1, 'luffduy21@gmail.com', 'Tzy', '0293922048', 'aaaaaa', 5650000.00, 'Đã hoàn thành', '2026-06-27 20:36:15'),
(2, 'trannguyenduy1907@icloud.com', 'Tzy', '0293922048', 'Tân Thành', 5000000.00, 'Đã hoàn thành', '2026-06-28 19:45:08'),
(3, 'trannguyenduy1907@icloud.com', 'Tzy', '0293922048', 'Tân Thành', 2300000.00, 'Đã hủy', '2026-06-28 22:57:20'),
(4, 'trannguyenduy1907@icloud.com', 'Tzy', '0293922048', 'Tân Thành', 890000.00, 'Đã hoàn thành', '2026-06-28 23:06:09');