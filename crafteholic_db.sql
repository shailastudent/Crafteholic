-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 26, 2026 at 04:54 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `crafteholic_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `coupons`
--

CREATE TABLE `coupons` (
  `id` int(11) NOT NULL,
  `code` varchar(50) NOT NULL,
  `discount_amount` decimal(10,2) NOT NULL,
  `discount_type` enum('fixed','percentage') DEFAULT 'fixed',
  `expiry_date` date DEFAULT NULL,
  `status` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `coupons`
--

INSERT INTO `coupons` (`id`, `code`, `discount_amount`, `discount_type`, `expiry_date`, `status`) VALUES
(1, 'TAJIA100', 100.00, 'fixed', NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL DEFAULT 0,
  `total_price` decimal(10,2) NOT NULL,
  `address` text NOT NULL,
  `phone` varchar(20) NOT NULL,
  `status` varchar(50) DEFAULT 'Pending',
  `order_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `product_id`, `total_price`, `address`, `phone`, `status`, `order_date`) VALUES
(11, 3, 21, 4460.00, 'banasree', '01733333333', 'Completed', '2026-03-25 15:29:52');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `stock` int(11) NOT NULL,
  `discount_price` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `price`, `description`, `image`, `created_at`, `stock`, `discount_price`) VALUES
(-3, 'Butterfly Canvas Wall Art', 1400.00, 'Your child will be delighted as they recognize this vibrant butterfly created by Eric Carle. This iconic character is featured in the book The Very Hungry Caterpillar and serves as a wonderful way to unite the joy of reading with your child\'s surroundings. The World of Eric Carle is beautifully reproduced in this giclee wall art decor collection that features his most beloved characters. This canvas wall art is printed using the best digital reproduction method available, resulting in great clarity and color saturation. Printed onto an artist\'s grade premium canvas. After printing, the canvas is stretched by hand over a custom built 1. 5\" wood frame. The canvas wraps around the sides providing a finished decorative edge. Mounting hardware is attached for easy hanging and artist biography information is included on the back so that children can learn more about today\'s working artists.', 'art4.png', '2026-03-21 17:59:48', 10, NULL),
(-2, 'Handmade Candle Holder Art', 1200.00, 'Our hand-painted glass candle holder is a unique addition to your dull evening. The exquisite craftsmanship and vine-leaf design on the clear glass create a beautiful shadow illusion on the walls of the room when lit by candlelight. It is a modern work of art and an effective decor item at the same time.', 'art-3.png', '2026-03-21 17:46:44', 15, NULL),
(-1, 'Hand-Painted Ceramic Storage Jar', 3000.00, 'This hand-painted ceramic jar is the perfect choice for storing your favorite keepsakes or jewelry. It\'s not just a storage jar, but also a great decor piece. The 3D texture of the red rose on top, set against a blue and sky blue backdrop, will add a touch of elegance to your dressing table or shelf.', 'art2.png', '2026-03-21 17:36:51', 10, NULL),
(0, 'Folk Art Canvas', 300.00, 'This is a unique fusion art, where an aesthetic mandala is created around the traditional Bengali \'Misthi Daai\' or \'Daiyar Patra\'. The bright pink lotus and teal design on black will add a touch of elegance to your drawing room or office wall.', 'art1.png', '2026-03-21 17:23:57', 10, NULL),
(1, 'Handcrafted Resin Keyrings', 350.00, 'Perfect For:\r\n1.Everyday use\r\n2.Birthday, holiday, or special occasion gifts\r\n3.Adding a personal, artistic touch to your keys or bag', 'key.png', '2026-03-19 10:35:33', 7, NULL),
(2, 'Explosion Gift Box ', 1500.00, 'Unwrap the Magic! Our Explosion Gift Box is more than just a gift — it’s an experience. With layers that unfold to reveal surprises, keepsakes, and heartfelt messages, it turns any occasion into a memorable celebration. Perfect for birthdays, anniversaries, or just to show someone you care. Elegant, fun, and unforgettable.', 'photo.png', '2026-03-19 15:34:28', 25, NULL),
(3, 'Resin Pendants', 600.00, 'Capture the essence of nature and artistry with this exquisite resin pendant. Crafted with precision, each piece showcases delicate details encased in crystal-clear resin, making it a timeless accessory. Perfect for adding a touch of elegance to any outfit, this pendant is both lightweight and durable, designed to complement your style effortlessly.', 'pendent.png', '2026-03-19 15:42:20', 40, NULL),
(4, 'Rose Bouquet', 1200.00, 'A timeless bouquet of fresh, hand-picked roses, beautifully arranged to convey love, admiration, and elegance. Perfect for special moments or to brighten someone’s day.', 'rose.png', '2026-03-19 15:47:24', 30, NULL),
(5, 'Scrapbook', 1500.00, 'This premium scrapbook is designed to hold your cherished memories with grace. Featuring high-quality pages and a sophisticated cover, it’s ideal for photos, journaling, and creative expression.', 'book.png', '2026-03-19 15:49:14', 15, NULL),
(6, 'Chocolate Gift Box', 1200.00, 'Treat your loved ones to the perfect blend of luxury and delight. Our Chocolate Gift Box is crafted with the finest, hand-selected chocolates, each piece a symphony of rich flavors and delicate textures. Elegantly packaged, it’s the ideal gift for any occasion — from heartfelt celebrations to spontaneous moments of sweetness.', 'chocolate.png', '2026-03-19 15:51:30', 28, NULL),
(7, 'Eid Bouquets ', 100.00, 'Celebrate the spirit of Eid with this exquisite bouquet, thoughtfully arranged to convey love, joy, and elegance. Featuring a harmonious blend of fresh, vibrant blooms, it’s the perfect gift to honor your loved ones and make this festive season truly memorable.', 'eid bouqet.png', '2026-03-19 15:54:16', 15, NULL),
(8, 'Hijab Bouquets', 1600.00, 'Celebrate beauty and grace with our meticulously crafted Hijab Bouquet. Each bouquet combines delicate fabrics, soft textures, and tasteful colors to create a stunning arrangement perfect for gifting or personal adornment. Ideal for special occasions, weddings, or as a thoughtful gesture, this bouquet exudes elegance, charm, and sophistication.', 'hijab.png', '2026-03-19 15:56:34', 20, 1200.00),
(9, 'Eid Card', 70.00, 'Celebrate the joy of Eid with a beautifully crafted card that conveys your heartfelt wishes. A perfect way to share love, gratitude, and blessings with your dear ones.', 'card.png', '2026-03-19 16:00:30', 0, NULL),
(10, 'Valentine Bouquet', 500.00, 'A timeless expression of love, this exquisite Valentine’s bouquet is thoughtfully curated with delicate blooms and soft hues. Perfect for celebrating heartfelt emotions, it speaks the language of romance without words.', 'valentine.png', '2026-03-19 16:03:14', 5, NULL),
(12, 'Money Bouquet', 5500.00, 'A premium handcrafted money bouquet, thoughtfully designed with crisp currency notes and delicate decorative elements. Perfect for celebrating special moments with a touch of luxury, love, and creativity.', 'money.png', '2026-03-19 16:09:34', 34, 4500.00),
(17, 'Magicbox With Flowers', 6000.00, 'Buy flowers in a box \"Magicbox\" with free delivery to your home or office!', 'magic1.png', '2026-03-22 05:38:44', 12, NULL),
(19, 'Engagement Ceremony Bouquet ', 5500.00, 'Crafted with soft satin fabric, this bouquet exudes timeless beauty and elegance, enhanced by sparkling rhinestones, artificial gemstones, and faux pearls for a touch of luxury.', 'weeding2.png', '2026-03-22 06:01:05', 6, 3500.00),
(20, 'Mini Flower Bouquets', 500.00, 'It is the best gift for your mother and girlfriend, a suit for anniversaries, birthdays, holidays, graduation, Valentine\'s Day, Christmas or any other gift giving occasions.', 'mini.png', '2026-03-22 14:45:16', 15, NULL),
(21, 'Sunflower Bouquet', 4500.00, 'Sunflowers may vary in size. Large Sunflower blooms are not guaranteed as it depends on supply and climate, but rest assured we try our best to source the prettiest one out there!', 'sun2.png', '2026-03-22 15:12:21', 22, 3000.00);

-- --------------------------------------------------------

--
-- Table structure for table `product_images`
--

CREATE TABLE `product_images` (
  `id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `image_name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_images`
--

INSERT INTO `product_images` (`id`, `product_id`, `image_name`) VALUES
(1, 12, 'money2.png'),
(2, 12, 'money3.png'),
(3, 12, 'money4.png'),
(4, 12, 'money5.png'),
(5, 12, 'money6.png'),
(10, 10, 'bouqet1.png'),
(11, 10, 'bouqet2.png'),
(12, 10, 'bouqet3.png'),
(13, 10, 'bouqet4.png'),
(14, 10, 'bouqet6.png'),
(15, 10, 'bouqet7.png'),
(16, 9, 'card1.png'),
(17, 9, 'card2.png'),
(18, 9, 'card3.png'),
(19, 9, 'card4.png'),
(20, 8, 'hijab1.png'),
(21, 8, 'hijab2.png'),
(22, 8, 'hijab3.png'),
(23, 8, 'hijab4.png'),
(24, 7, 'mehedi.png'),
(25, 7, 'mehedi1.png'),
(26, 6, 'box1.png'),
(28, 6, 'box4.png'),
(29, 6, 'box5.png'),
(30, 6, 'box6.png'),
(31, 6, 'box7.png'),
(32, 5, 'book1.png'),
(33, 5, 'book2.png'),
(34, 5, 'book3.png'),
(37, 5, 'book4.png'),
(38, 4, 'flower1.png'),
(39, 4, 'flower2.png'),
(40, 4, 'flower3.png'),
(41, 4, 'flower4.png'),
(42, 4, 'flower5.png'),
(43, 3, 'pendent1.png'),
(44, 3, 'pendent2.png'),
(45, 3, 'pendent3.png'),
(46, 2, 'gift1.png'),
(47, 2, 'gift2.png'),
(48, 2, 'gift3.png'),
(49, 2, 'gift4.png'),
(50, 2, 'gift5.png'),
(51, 1, 'key1.png'),
(52, 1, 'key2.png'),
(53, 1, 'key3.png'),
(54, 1, 'key4.png'),
(55, 1, 'key5.png'),
(56, 1, 'key6.png'),
(57, 0, 'art5.png'),
(58, 0, 'art6.png'),
(59, -3, 'art7.png'),
(60, 17, 'magic2.png'),
(61, 17, 'magic3.png'),
(63, 19, 'weeding1.png'),
(64, 19, 'weeding3.png'),
(65, 20, 'mini2.png'),
(66, 20, 'mini1.png'),
(67, 20, 'mini3.png'),
(68, 20, 'mini4.png'),
(69, 21, 'sun1.png'),
(70, 21, 'sun2.png'),
(71, 21, 'sun3.png'),
(72, 21, 'sun4.png');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `user_name` varchar(100) NOT NULL,
  `rating` int(11) NOT NULL,
  `comment` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `reset_token` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `created_at`, `reset_token`) VALUES
(3, 'Shaila Islam', 'islamshaila000@gmail.com', '$2y$10$Hv.GTH8QmFZlVh2gAXsPFO6qIwCGTtcbUcrkr4VKiO1nv6dzubidm', 'admin', '2026-03-19 10:05:59', 'd8ce76c455eda8ad497745230c77691fea9d157910903650ce80360d1c8ea67ea12fd46e211746ca2e6258855c8c6388a368'),
(5, 'Tajia Islam', 'tajia000@gmail.com', '$2y$10$gAKrMq95vT59VCBuT.Uhs.HP2LsXpCFkia7MruJVMtFxhTg6D7Zpq', 'admin', '2026-03-23 14:07:26', NULL),
(6, 'Jannat Jahin', 'jannat000@gmail.com', '$2y$10$PH2fyXliSmxvarq.0cevouwNUpWbSqQouo8UdfA6Ne9IZ9LPtNpqm', 'admin', '2026-03-23 14:08:05', NULL),
(7, 'Maria Marjan', 'maria000@gmail.com', '$2y$10$Z.js7ImTeIfcRUeeopV1cO7/gmtRZ/E4deWixb0IEOghGgiuJGLeS', 'admin', '2026-03-23 14:08:46', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `coupons`
--
ALTER TABLE `coupons`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_images`
--
ALTER TABLE `product_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `coupons`
--
ALTER TABLE `coupons`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `product_images`
--
ALTER TABLE `product_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `product_images`
--
ALTER TABLE `product_images`
  ADD CONSTRAINT `product_images_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
