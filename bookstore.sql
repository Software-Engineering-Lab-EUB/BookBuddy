
--
-- Table structure for table `books`
--

DROP TABLE IF EXISTS `books`;

CREATE TABLE `books` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `author` varchar(100) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock` int NOT NULL,
  `category_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `category_id` (`category_id`),
  CONSTRAINT `books_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `books`
--

LOCK TABLES `books` WRITE;

INSERT INTO `books` VALUES (1,'The Great Gatsby','F. Scott Fitzgerald',12.99,10,1),(2,'To Kill a Mockingbird','Harper Lee',14.99,15,1),(3,'A Brief History of Time','Stephen Hawking',18.50,5,3),(4,'The Art of Computer Programming','Donald Knuth',55.00,7,4),(5,'Easy to learn code','Noor Alam Islam',10.00,5,3);

UNLOCK TABLES;

--
-- Table structure for table `cart`
--

DROP TABLE IF EXISTS `cart`;

CREATE TABLE `cart` (
  `cart_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `book_id` int DEFAULT NULL,
  `quantity` int DEFAULT NULL,
  PRIMARY KEY (`cart_id`),
  KEY `user_id` (`user_id`),
  KEY `book_id` (`book_id`),
  CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `cart`
--

LOCK TABLES `cart` WRITE;

INSERT INTO `cart` VALUES (1,1,1,2),(2,1,1,1),(3,2,3,1),(4,3,2,1),(6,3,1,1),(7,3,3,1),(8,3,5,1),(9,3,2,1),(10,3,3,1),(11,3,1,1),(12,3,5,1),(13,3,1,1),(14,3,2,1),(15,3,5,1),(16,2,5,1);

UNLOCK TABLES;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;

CREATE TABLE `categories` (
  `category_id` int NOT NULL AUTO_INCREMENT,
  `category_name` varchar(100) NOT NULL,
  PRIMARY KEY (`category_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;

INSERT INTO `categories` VALUES (1,'Fiction'),(2,'Non-Fiction'),(3,'Science'),(4,'Technology');

UNLOCK TABLES;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;

CREATE TABLE `orders` (
  `order_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `total_price` decimal(10,2) DEFAULT NULL,
  `status` enum('pending','completed','canceled') DEFAULT 'pending',
  `payment_method` varchar(50) DEFAULT NULL,
  `transaction_id` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`order_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;

INSERT INTO `orders` VALUES (7,1,25.98,'completed',NULL,NULL),(8,1,12.99,'pending',NULL,NULL),(9,2,18.50,'pending',NULL,NULL),(10,3,14.99,'pending',NULL,NULL),(12,3,12.99,'pending',NULL,NULL),(13,3,18.50,'pending',NULL,NULL),(14,3,10.00,'pending',NULL,NULL),(15,3,14.99,'pending',NULL,NULL),(16,3,18.50,'pending',NULL,NULL),(17,3,12.99,'pending',NULL,NULL),(18,3,10.00,'pending',NULL,NULL),(19,1,100.00,'pending','bkash','TXN123456'),(20,3,12.99,'canceled',NULL,NULL),(21,3,14.99,'completed',NULL,NULL),(22,3,10.00,'pending',NULL,NULL),(23,2,10.00,'pending',NULL,NULL),(24,2,25.98,'pending','bkash','TXN608983'),(25,2,12.99,'pending','nagad','TXN674430'),(26,1,10.00,'pending','nagad','TXN155194'),(27,2,12.99,'pending','bkash','TXN701975');

UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('user','admin') DEFAULT 'user',
  `is_verified` tinyint(1) DEFAULT '0',
  `verification_code` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;

INSERT INTO `users` VALUES (1,'noor alam','nooralamislammanik@gmail.com','$2y$12$yycLk8CLs5V7HX0K5V2ncee6f7r5YSiewm/KE6UaiHnzbFbRaK7JO','admin',0,NULL),
(2,'Najmul Islam','najmulislam@gmail.com','$2y$12$679oPB2wY4GpehuuC2r7OOERi6/O.MCHiukM/ofp6/28F/GxYkkGS','user',0,NULL),
(3,'Noor Alam ','nooralamislam@gmail.com','$2y$12$RtQGLawbTY5AjVgTkPfb5uL8Cj8ut0X49p.SRrKltLEnI05MidmUK','user',0,NULL),
(9,'alhasan Bepari','alhasanbepari789@gmail.com','$2y$12$/Pb5GYzQJJxURJTkIArAhO6MkrgM.Rsns.Eu76Dot1FTPmbRoSxMW','user',0,'4960c34a84c1a978c4a57352420f0f53');

UNLOCK TABLES;

-- Dump completed on 2025-03-04 21:58:16


