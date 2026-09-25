-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 25, 2026 at 09:07 AM
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
-- Database: `marketlink`
--

-- --------------------------------------------------------

--
-- Table structure for table `announcements`
--

CREATE TABLE `announcements` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `type` varchar(255) NOT NULL DEFAULT 'announcement',
  `link` varchar(255) DEFAULT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `announcements`
--

INSERT INTO `announcements` (`id`, `user_id`, `title`, `message`, `type`, `link`, `is_read`, `created_at`, `updated_at`) VALUES
(1, NULL, 'Welcome to MarketLink Platform!', 'Connect directly with local farmers, pre-order fresh produce for market pickup, and support sustainable local agriculture.', 'announcement', '/markets', 0, '2026-09-24 22:35:05', '2026-09-24 22:35:05'),
(2, NULL, 'Weekend Farmers Markets Now Open', 'Central Downtown and Clifton Seaside markets are open this Saturday and Sunday with over 40 fresh stalls.', 'announcement', '/markets', 0, '2026-09-24 22:35:05', '2026-09-24 22:35:05'),
(3, 6, 'Order #ML-2026-1003 is Ready for Pickup!', 'Your order with Meadow Brook Dairy & Honey is packed and waiting at Stall #8 in Gulshan Green Market.', 'order_status', '/orders/3', 0, '2026-09-24 22:35:05', '2026-09-24 22:35:05'),
(4, 6, 'Restock Alert: Organic Heirloom Tomatoes', 'Green Valley Organic Produce has updated fresh weekly stock for Heirloom Tomatoes. Reserve yours now!', 'restock', '/products/1', 0, '2026-09-24 22:35:05', '2026-09-24 22:35:05');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `icon` varchar(255) NOT NULL DEFAULT 'bi-tag',
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `icon`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Fresh Vegetables', 'vegetables', 'bi-flower2', 'Crisp, organically cultivated and pesticide-free garden vegetables harvested within 24 hours.', '2026-09-24 22:35:01', '2026-09-24 22:35:01'),
(2, 'Orchard Fruits', 'fruits', 'bi-apple', 'Naturally sweet, tree-ripened seasonal fruits harvested at peak freshness.', '2026-09-24 22:35:01', '2026-09-24 22:35:01'),
(3, 'Dairy & Eggs', 'dairy-eggs', 'bi-egg-fried', 'Free-range pasture-raised eggs, artisan goat milk cheese, and cultured farm butter.', '2026-09-24 22:35:01', '2026-09-24 22:35:01'),
(4, 'Artisan Bakery', 'bakery', 'bi-cup-hot', 'Wood-fired sourdough breads, rustic grain boules, pies, and wholesome morning rolls.', '2026-09-24 22:35:01', '2026-09-24 22:35:01'),
(5, 'Honey & Preserves', 'honey-preserves', 'bi-droplet-half', 'Unpasteurized wildflower honey, small-batch fruit jams, and pickled country delicacies.', '2026-09-24 22:35:01', '2026-09-24 22:35:01'),
(6, 'Herbs & Microgreens', 'herbs-microgreens', 'bi-tree', 'Freshly cut culinary herbs, aromatic rosemary, vibrant mint, and nutrient-dense microgreens.', '2026-09-24 22:35:01', '2026-09-24 22:35:01');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `favorites`
--

CREATE TABLE `favorites` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `customer_id` bigint(20) UNSIGNED NOT NULL,
  `favoritable_type` varchar(255) NOT NULL,
  `favoritable_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `favorites`
--

INSERT INTO `favorites` (`id`, `customer_id`, `favoritable_type`, `favoritable_id`, `created_at`, `updated_at`) VALUES
(1, 6, 'Farmer', 2, '2026-09-24 22:35:05', '2026-09-24 22:35:05'),
(2, 6, 'Product', 1, '2026-09-24 22:35:05', '2026-09-24 22:35:05'),
(3, 7, 'Farmer', 3, '2026-09-24 22:35:05', '2026-09-24 22:35:05'),
(4, 7, 'Product', 6, '2026-09-24 22:35:05', '2026-09-24 22:35:05');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `markets`
--

CREATE TABLE `markets` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `market_name` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `city` varchar(255) NOT NULL DEFAULT 'Metropolis',
  `operating_days` varchar(255) NOT NULL,
  `timings` varchar(255) NOT NULL,
  `latitude` decimal(10,8) NOT NULL,
  `longitude` decimal(11,8) NOT NULL,
  `map_provider` varchar(255) NOT NULL DEFAULT 'OpenStreetMap',
  `image` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `markets`
--

INSERT INTO `markets` (`id`, `market_name`, `address`, `city`, `operating_days`, `timings`, `latitude`, `longitude`, `map_provider`, `image`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Central Downtown Farmers Market', 'Market Square, Main Downtown Boulevard', 'Metropolis', 'Saturday, Sunday', '07:30 AM - 02:00 PM', 24.86070000, 67.00110000, 'OpenStreetMap', NULL, 'The largest and oldest farmers market in the city, featuring over 40 local producers, fresh organic fruits, vegetables, and artisan breads.', '2026-09-24 22:35:01', '2026-09-24 22:35:01'),
(2, 'Clifton Seaside Farmers Market', 'Pier 4, Seaside Promenade, Clifton Beach Road', 'Metropolis', 'Sunday', '08:00 AM - 01:00 PM', 24.81380000, 67.03050000, 'OpenStreetMap', NULL, 'Enjoy refreshing coastal breezes while browsing farm-fresh produce, coastal honey, dairy eggs, and handmade jams.', '2026-09-24 22:35:01', '2026-09-24 22:35:01'),
(3, 'Gulshan Green Community Market', 'Block 6 Community Ground, University Road', 'Metropolis', 'Wednesday, Saturday', '08:00 AM - 12:30 PM', 24.92000000, 67.09000000, 'OpenStreetMap', NULL, 'A lively neighborhood market connecting suburban families directly with sustainable regional growers.', '2026-09-24 22:35:01', '2026-09-24 22:35:01'),
(4, 'North Hills Village Organic Market', 'Hillview Park Plaza, North Valley Avenue', 'Metropolis', 'Tuesday, Friday', '07:00 AM - 11:30 AM', 24.95000000, 67.04000000, 'OpenStreetMap', NULL, 'Certified organic produce, hydroponic herbs, microgreens, and artisanal breakfast pastries.', '2026-09-24 22:35:01', '2026-09-24 22:35:01');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000001_create_cache_table', 1),
(2, '0001_01_01_000002_create_jobs_table', 1),
(3, '2026_09_24_052000_create_markets_table', 1),
(4, '2026_09_24_052100_create_categories_table', 1),
(5, '2026_09_24_052200_create_users_table', 1),
(6, '2026_09_24_052300_create_products_table', 1),
(7, '2026_09_24_052400_create_orders_table', 1),
(8, '2026_09_24_052500_create_order_items_table', 1),
(9, '2026_09_24_052600_create_reviews_table', 1),
(10, '2026_09_24_052700_create_favorites_table', 1),
(11, '2026_09_24_052800_create_announcements_table', 1),
(12, '2026_09_24_052900_create_reports_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_number` varchar(255) NOT NULL,
  `customer_id` bigint(20) UNSIGNED NOT NULL,
  `farmer_id` bigint(20) UNSIGNED NOT NULL,
  `market_id` bigint(20) UNSIGNED DEFAULT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `order_status` enum('placed','accepted','ready_for_pickup','completed','cancelled') NOT NULL DEFAULT 'placed',
  `pickup_date` date NOT NULL,
  `pickup_time_slot` varchar(255) NOT NULL,
  `cutoff_time` datetime DEFAULT NULL,
  `customer_notes` text DEFAULT NULL,
  `farmer_notes` text DEFAULT NULL,
  `cancellation_reason` text DEFAULT NULL,
  `cancelled_by` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `order_number`, `customer_id`, `farmer_id`, `market_id`, `total_amount`, `order_status`, `pickup_date`, `pickup_time_slot`, `cutoff_time`, `customer_notes`, `farmer_notes`, `cancellation_reason`, `cancelled_by`, `created_at`, `updated_at`) VALUES
(1, 'ML-2026-1001', 6, 2, 1, 15.50, 'placed', '2026-09-27', '09:00 AM - 10:00 AM', '2026-09-26 15:35:05', 'Please pick firm ripe tomatoes suitable for slicing.', NULL, NULL, NULL, '2026-09-24 22:35:05', '2026-09-24 22:35:05'),
(2, 'ML-2026-1002', 7, 3, 2, 14.40, 'accepted', '2026-09-28', '10:00 AM - 11:00 AM', '2026-09-27 09:35:05', 'Looking forward to the Honeycrisp apples!', 'Reserved our finest crate for you!', NULL, NULL, '2026-09-24 22:35:05', '2026-09-24 22:35:05'),
(3, 'ML-2026-1003', 6, 4, 3, 15.00, 'ready_for_pickup', '2026-09-25', '08:30 AM - 09:30 AM', '2026-09-24 22:35:05', 'Pre-order for morning market run.', 'Packed in cooler box with your name on it!', NULL, NULL, '2026-09-24 22:35:05', '2026-09-24 22:35:05'),
(4, 'ML-2026-0995', 8, 2, 1, 9.00, 'completed', '2026-09-21', '09:00 AM - 10:00 AM', '2026-09-20 03:35:05', 'Cash ready at pickup.', 'Collected in person. Thank you!', NULL, NULL, '2026-09-24 22:35:05', '2026-09-24 22:35:05');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `unit` varchar(255) NOT NULL DEFAULT 'kg',
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `product_name`, `unit`, `quantity`, `price`, `subtotal`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'Organic Heirloom Tomatoes', 'kg', 2, 4.50, 9.00, '2026-09-24 22:35:05', '2026-09-24 22:35:05'),
(2, 1, 2, 'Crisp Baby Spinach & Arugula', 'bunch', 2, 3.25, 6.50, '2026-09-24 22:35:05', '2026-09-24 22:35:05'),
(3, 2, 6, 'Crisp Honeycrisp Orchard Apples', 'kg', 2, 5.20, 10.40, '2026-09-24 22:35:05', '2026-09-24 22:35:05'),
(4, 2, 7, 'Sweet Farm Strawberries (Box)', 'box', 1, 4.00, 4.00, '2026-09-24 22:35:05', '2026-09-24 22:35:05'),
(5, 3, 9, 'Pasture-Raised Brown Farm Eggs', 'dozen', 1, 5.50, 5.50, '2026-09-24 22:35:05', '2026-09-24 22:35:05'),
(6, 3, 10, 'Raw Unfiltered Wildflower Honey (500g)', 'jar', 1, 9.50, 9.50, '2026-09-24 22:35:05', '2026-09-24 22:35:05'),
(7, 4, 1, 'Organic Heirloom Tomatoes', 'kg', 2, 4.50, 9.00, '2026-09-24 22:35:05', '2026-09-24 22:35:05');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `farmer_id` bigint(20) UNSIGNED NOT NULL,
  `category_id` bigint(20) UNSIGNED DEFAULT NULL,
  `category` varchar(255) NOT NULL DEFAULT 'Vegetables',
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `unit` varchar(255) NOT NULL DEFAULT 'kg',
  `stock_quantity` int(11) NOT NULL DEFAULT 0,
  `image` varchar(255) DEFAULT NULL,
  `is_available` tinyint(1) NOT NULL DEFAULT 1,
  `is_weekly_template` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `farmer_id`, `category_id`, `category`, `name`, `description`, `price`, `unit`, `stock_quantity`, `image`, `is_available`, `is_weekly_template`, `created_at`, `updated_at`) VALUES
(1, 2, 1, 'Fresh Vegetables', 'Organic Heirloom Tomatoes', 'Vine-ripened multi-color heirloom tomatoes bursting with natural sweet acidity. Perfect for caprese salads and pasta.', 4.50, 'kg', 45, NULL, 1, 1, '2026-09-24 22:35:05', '2026-09-24 22:35:05'),
(2, 2, 1, 'Fresh Vegetables', 'Crisp Baby Spinach & Arugula', 'Tender, triple-washed organic greens packed with vitamins. Harvested fresh dawn of market day.', 3.25, 'bunch', 30, NULL, 1, 1, '2026-09-24 22:35:05', '2026-09-24 22:35:05'),
(3, 2, 1, 'Fresh Vegetables', 'Sweet Rainbow Bell Peppers', 'Crunchy red, yellow, and orange bell peppers grown pesticide-free in open sunlight.', 3.80, 'kg', 25, NULL, 1, 1, '2026-09-24 22:35:05', '2026-09-24 22:35:05'),
(4, 2, 6, 'Herbs & Microgreens', 'Aromatic Sweet Genovese Basil', 'Fragrant fresh cut Italian basil bunch, ideal for homemade pesto and tomato pairings.', 2.00, 'bunch', 40, NULL, 1, 1, '2026-09-24 22:35:05', '2026-09-24 22:35:05'),
(5, 2, 1, 'Fresh Vegetables', 'Sweet Bi-Color Butter Sweetcorn', 'Plump, juicy ears of sweet corn freshly picked from the stalk. Delicious grilled or boiled.', 1.50, 'piece', 60, NULL, 1, 1, '2026-09-24 22:35:05', '2026-09-24 22:35:05'),
(6, 3, 2, 'Orchard Fruits', 'Crisp Honeycrisp Orchard Apples', 'Extra crisp, sweet-tart apples freshly hand-picked from mountain hillside orchards.', 5.20, 'kg', 50, NULL, 1, 1, '2026-09-24 22:35:05', '2026-09-24 22:35:05'),
(7, 3, 2, 'Orchard Fruits', 'Sweet Farm Strawberries (Box)', 'Deep red, highly fragrant strawberries harvested at the sweetest peak of perfection.', 4.00, 'box', 35, NULL, 1, 1, '2026-09-24 22:35:05', '2026-09-24 22:35:05'),
(8, 3, 2, 'Orchard Fruits', 'Juicy Golden Sun Peaches', 'Velvety skin, yellow sweet flesh, bursting with tree-ripened orchard juice.', 6.00, 'kg', 20, NULL, 1, 0, '2026-09-24 22:35:05', '2026-09-24 22:35:05'),
(9, 4, 3, 'Dairy & Eggs', 'Pasture-Raised Brown Farm Eggs', 'Dozen large eggs from pasture-roaming hens with deep golden yolks and superior flavor.', 5.50, 'dozen', 40, NULL, 1, 1, '2026-09-24 22:35:05', '2026-09-24 22:35:05'),
(10, 4, 5, 'Honey & Preserves', 'Raw Unfiltered Wildflower Honey (500g)', 'Pure unpasteurized honey gathered by bees from clover, lavender, and summer wildflowers.', 9.50, 'jar', 25, NULL, 1, 1, '2026-09-24 22:35:05', '2026-09-24 22:35:05'),
(11, 4, 3, 'Dairy & Eggs', 'Artisan Handcrafted Goat Cheese', 'Creamy, tangy soft chèvre goat cheese lightly rolled in crushed wild herbs.', 7.00, 'piece', 18, NULL, 1, 1, '2026-09-24 22:35:05', '2026-09-24 22:35:05');

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE `reports` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `generated_by` bigint(20) UNSIGNED NOT NULL,
  `report_type` varchar(255) NOT NULL,
  `parameters` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`parameters`)),
  `summary_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`summary_data`)),
  `generated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `reports`
--

INSERT INTO `reports` (`id`, `generated_by`, `report_type`, `parameters`, `summary_data`, `generated_at`, `created_at`, `updated_at`) VALUES
(1, 1, 'Platform Launch Overview', '{\"period\":\"all_time\"}', '{\"total_markets\":4,\"total_farmers\":4,\"total_customers\":3,\"total_orders\":4,\"total_revenue\":53.9}', '2026-09-24 22:35:05', '2026-09-24 22:35:05', '2026-09-24 22:35:05');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED DEFAULT NULL,
  `product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `farmer_id` bigint(20) UNSIGNED NOT NULL,
  `customer_id` bigint(20) UNSIGNED NOT NULL,
  `rating` int(11) NOT NULL,
  `comment` text DEFAULT NULL,
  `farmer_reply` text DEFAULT NULL,
  `farmer_replied_at` datetime DEFAULT NULL,
  `is_flagged` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `order_id`, `product_id`, `farmer_id`, `customer_id`, `rating`, `comment`, `farmer_reply`, `farmer_replied_at`, `is_flagged`, `created_at`, `updated_at`) VALUES
(1, 4, 1, 2, 8, 5, 'The heirloom tomatoes were the sweetest and juiciest I have tasted this year! Perfect condition, no bruising.', 'Thank you Emily! We pick them just hours before the market starts. See you next weekend!', '2026-09-22 03:35:05', 0, '2026-09-24 22:35:05', '2026-09-24 22:35:05'),
(2, NULL, 9, 4, 6, 5, 'Deep orange yolks and very fresh. You can really tell the difference with pasture-raised hens.', 'Delighted you enjoyed them, Alice! Our hens graze freely on natural pasture clover every day.', '2026-09-23 03:35:05', 0, '2026-09-24 22:35:05', '2026-09-24 22:35:05'),
(3, NULL, 6, 3, 7, 5, 'Fantastic crisp apples! Made wonderful homemade apple cider and snacks for the kids.', NULL, NULL, 0, '2026-09-24 22:35:05', '2026-09-24 22:35:05');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `contact_person` varchar(255) DEFAULT NULL,
  `username` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','farmer','customer') NOT NULL DEFAULT 'customer',
  `status` enum('active','pending_approval','suspended') NOT NULL DEFAULT 'active',
  `phone` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `stall_name` varchar(255) DEFAULT NULL,
  `market_id` bigint(20) UNSIGNED DEFAULT NULL,
  `operating_days` varchar(255) DEFAULT NULL,
  `pickup_windows` varchar(255) DEFAULT NULL,
  `cutoff_hours` int(11) NOT NULL DEFAULT 12,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `preferred_market_id` bigint(20) UNSIGNED DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `contact_person`, `username`, `email`, `password`, `role`, `status`, `phone`, `address`, `stall_name`, `market_id`, `operating_days`, `pickup_windows`, `cutoff_hours`, `latitude`, `longitude`, `bio`, `avatar`, `preferred_market_id`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Platform Administrator', NULL, 'admin', 'admin@marketlink.com', '$2y$10$7vN3v4bX5z8C1qW6e9R2t.uK8L5h3G1f0d9s8a7z6x5c4v3b2n1m', 'admin', 'active', '+1-555-010-9999', 'MarketLink HQ, Innovation Tower, Suite 400', NULL, NULL, NULL, NULL, 12, NULL, NULL, NULL, NULL, NULL, NULL, '2026-09-24 22:35:02', '2026-09-24 22:35:02'),
(2, 'John Miller', 'John Miller (Owner)', 'greenvalley', 'farmer@marketlink.com', '$2y$12$YuJdWye1kxfYiWCBxjEB7e.2kDijjEa3kcrgSaaND0DD5Ho6Vka1S', 'farmer', 'active', '+1-555-014-2233', 'Stall #14, North Pavilion, Central Downtown Farmers Market', 'Green Valley Organic Produce', 1, 'Saturday, Sunday', '08:00 AM - 01:00 PM', 12, 24.86090000, 67.00150000, 'Family-owned certified organic farm cultivating heirloom tomatoes, greens, and crisp seasonal vegetables for 3 generations.', NULL, NULL, NULL, '2026-09-24 22:35:02', '2026-09-24 22:35:02'),
(3, 'Sarah Jenkins', 'Sarah Jenkins', 'sunrisegrove', 'sunrisegrove@marketlink.com', '$2y$12$ixLKH8EpzJYrW1.OeoQ1V.M4KElBfc7.9fHXxr4ylkyauI9dYiib2', 'farmer', 'active', '+1-555-019-8877', 'Stall #3, Seaside Boardwalk, Clifton Seaside Market', 'Sunrise Grove Orchards', 2, 'Sunday', '08:30 AM - 12:30 PM', 18, 24.81400000, 67.03080000, 'Growing sweet honeycrisp apples, golden peaches, and sun-drenched berries picked fresh Saturday afternoon for Sunday morning market.', NULL, NULL, NULL, '2026-09-24 22:35:03', '2026-09-24 22:35:03'),
(4, 'Robert Davis', 'Robert Davis', 'meadowbrook', 'meadowbrook@marketlink.com', '$2y$12$0crOqWggssvD75oXNNFUUu0/e1nBcSIXxI2IfzFrrCtTVyVfFkyie', 'farmer', 'active', '+1-555-017-3344', 'Stall #8, East Canopy, Gulshan Green Community Market', 'Meadow Brook Dairy & Honey', 3, 'Wednesday, Saturday', '08:00 AM - 12:00 PM', 24, 24.92050000, 67.09050000, 'Pasture-fed dairy cows and hives nestled in wildflower meadows. Known for unpasteurized raw honey and rich artisanal cheese.', NULL, NULL, NULL, '2026-09-24 22:35:03', '2026-09-24 22:35:03'),
(5, 'Clara Woods', 'Clara Woods', 'heritagebakes', 'pending@marketlink.com', '$2y$12$Sc4C6ZNAeHekVVdoB4FqDe1x7InD7ZmfT5RMmiW.1q/S4muT6wVD6', 'farmer', 'pending_approval', '+1-555-018-5522', 'Stall #5, North Hills Village Market', 'Heritage Bakes & Sourdough', 4, 'Tuesday, Friday', '07:30 AM - 11:00 AM', 12, 24.95050000, 67.04080000, 'Natural stoneground wheat flours, 72-hour wild yeast fermentation, sourdough loaves, croissants, and morning pastries.', NULL, NULL, NULL, '2026-09-24 22:35:04', '2026-09-24 22:35:04'),
(6, 'Alice Walker', NULL, 'alice_w', 'customer@marketlink.com', '$2y$12$HHDTHDYMbNIbb6MRRsObrubSn1X09vfA9oUx7jb8Ojm97jVxqzcUe', 'customer', 'active', '+1-555-011-4455', 'Apartment 7B, Greenview Heights, Central District', NULL, NULL, NULL, NULL, 12, NULL, NULL, NULL, NULL, 1, NULL, '2026-09-24 22:35:04', '2026-09-24 22:35:04'),
(7, 'David Smith', NULL, 'davids', 'david@marketlink.com', '$2y$12$pt8bf4HoIiX2YgI5NqGZ6u4DgBa/9gpHED3zxaMd1Yy7rLZW3e8sm', 'customer', 'active', '+1-555-012-7788', '12 Marina Vista Road, Clifton Area', NULL, NULL, NULL, NULL, 12, NULL, NULL, NULL, NULL, 2, NULL, '2026-09-24 22:35:04', '2026-09-24 22:35:04'),
(8, 'Emily Clark', NULL, 'emilyc', 'emily@marketlink.com', '$2y$12$Vp.JsUQ2rTd0eKzlPAjLSeyhHS3/nUKTWAf/UuUYIEUnB7rgLqxUm', 'customer', 'active', '+1-555-013-9900', '84 University Boulevard, Block 4', NULL, NULL, NULL, NULL, 12, NULL, NULL, NULL, NULL, 3, NULL, '2026-09-24 22:35:05', '2026-09-24 22:35:05'),
(9, 'Ali', NULL, NULL, 'Alikhattak@gmail.com', '$2y$12$CO7STgcQOtc6/P9Q8xdB5.3SpwkEYHzbaZazga76r.vSZBvXoR85y', 'admin', 'active', '239399444', 'ertthfg', NULL, NULL, NULL, NULL, 12, NULL, NULL, NULL, NULL, NULL, NULL, '2026-09-25 00:38:04', '2026-09-25 00:38:04');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `announcements`
--
ALTER TABLE `announcements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `announcements_user_id_foreign` (`user_id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `categories_name_unique` (`name`),
  ADD UNIQUE KEY `categories_slug_unique` (`slug`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `favorites`
--
ALTER TABLE `favorites`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `favorites_customer_id_favoritable_type_favoritable_id_unique` (`customer_id`,`favoritable_type`,`favoritable_id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `markets`
--
ALTER TABLE `markets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `orders_order_number_unique` (`order_number`),
  ADD KEY `orders_customer_id_foreign` (`customer_id`),
  ADD KEY `orders_farmer_id_foreign` (`farmer_id`),
  ADD KEY `orders_market_id_foreign` (`market_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_items_order_id_foreign` (`order_id`),
  ADD KEY `order_items_product_id_foreign` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `products_farmer_id_foreign` (`farmer_id`),
  ADD KEY `products_category_id_foreign` (`category_id`);

--
-- Indexes for table `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reports_generated_by_foreign` (`generated_by`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reviews_order_id_foreign` (`order_id`),
  ADD KEY `reviews_product_id_foreign` (`product_id`),
  ADD KEY `reviews_farmer_id_foreign` (`farmer_id`),
  ADD KEY `reviews_customer_id_foreign` (`customer_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_market_id_foreign` (`market_id`),
  ADD KEY `users_preferred_market_id_foreign` (`preferred_market_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `announcements`
--
ALTER TABLE `announcements`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `favorites`
--
ALTER TABLE `favorites`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `markets`
--
ALTER TABLE `markets`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `announcements`
--
ALTER TABLE `announcements`
  ADD CONSTRAINT `announcements_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `favorites`
--
ALTER TABLE `favorites`
  ADD CONSTRAINT `favorites_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `orders_farmer_id_foreign` FOREIGN KEY (`farmer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `orders_market_id_foreign` FOREIGN KEY (`market_id`) REFERENCES `markets` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `products_farmer_id_foreign` FOREIGN KEY (`farmer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reports`
--
ALTER TABLE `reports`
  ADD CONSTRAINT `reports_generated_by_foreign` FOREIGN KEY (`generated_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_farmer_id_foreign` FOREIGN KEY (`farmer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `reviews_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_market_id_foreign` FOREIGN KEY (`market_id`) REFERENCES `markets` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `users_preferred_market_id_foreign` FOREIGN KEY (`preferred_market_id`) REFERENCES `markets` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
