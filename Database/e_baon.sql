SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `role` enum('admin','customer','delivery','') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `users` (`id`, `username`, `password`, `role`, `created_at`) VALUES
(1, 'ardy', 'Password Admin', 'admin', '2025-11-19 11:59:03'),
(2, 'brent', 'Password Customer', 'customer', '2025-11-19 11:59:03'),
(3, 'lester', 'Password Delivery', 'delivery', '2025-11-19 11:59:03'),
(20, 'ReinFhaul', '12345', 'customer', '2025-11-24 14:34:08'),
(21, 'admin', 'admin', 'admin', '2025-11-24 14:42:39');

CREATE TABLE `products` (
  `productID` int(25) NOT NULL AUTO_INCREMENT,
  `shopName` varchar(25) NOT NULL,
  `productName` varchar(255) NOT NULL,
  `productPrice` int(25) NOT NULL,
  `productQuantity` int(25) NOT NULL,
  PRIMARY KEY (`productID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `orders` (
  `orderID` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `orderName` int(11) NOT NULL,
  `orderQuantity` int(11) NOT NULL,
  `orderPrice` int(11) NOT NULL,
  `order_date` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`orderID`),
  KEY `idx_orders_user` (`user_id`),
  CONSTRAINT `fk_orders_user`
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `archives` (
  `archiveID` int(11) NOT NULL AUTO_INCREMENT,
  `orderID` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  PRIMARY KEY (`archiveID`),
  KEY `idx_archives_order` (`orderID`),
  KEY `idx_archives_user` (`userID`),
  CONSTRAINT `fk_archives_order`
    FOREIGN KEY (`orderID`) REFERENCES `orders`(`orderID`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_archives_user`
    FOREIGN KEY (`userID`) REFERENCES `users`(`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

COMMIT;