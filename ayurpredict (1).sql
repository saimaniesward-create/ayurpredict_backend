-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 22, 2026 at 03:28 AM
-- Server version: 10.4.32-MariaDB-log
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ayurpredict`
--

-- --------------------------------------------------------

--
-- Table structure for table `body_balance_scores`
--

CREATE TABLE `body_balance_scores` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `checkin_date` date NOT NULL,
  `score` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `body_balance_scores`
--

INSERT INTO `body_balance_scores` (`id`, `user_id`, `checkin_date`, `score`, `created_at`) VALUES
(1, 15, '2026-01-03', 63, '2026-01-03 08:19:48'),
(2, 15, '2026-01-20', 53, '2026-01-20 01:48:38'),
(4, 15, '2026-01-04', 61, '2026-01-04 02:50:56'),
(6, 16, '2026-01-04', 50, '2026-01-04 03:48:30'),
(7, 17, '2026-01-04', 20, '2026-01-04 04:38:02'),
(8, 20, '2026-01-04', 70, '2026-01-04 14:17:22'),
(9, 15, '2026-01-05', 20, '2026-01-05 00:19:54'),
(18, 17, '2026-01-05', 20, '2026-01-05 17:10:01'),
(19, 15, '2026-01-06', 20, '2026-01-06 00:59:34'),
(21, 17, '2026-01-06', 5, '2026-01-06 01:05:07'),
(32, 15, '2026-01-07', 80, '2026-01-18 10:30:46'),
(33, 15, '2026-01-08', 75, '2026-01-18 10:30:46'),
(34, 15, '2026-01-09', 85, '2026-01-18 10:30:46'),
(35, 15, '2026-01-10', 80, '2026-01-18 10:30:46'),
(36, 15, '2026-01-11', 60, '2026-01-18 10:30:46'),
(37, 15, '2026-01-12', 70, '2026-01-18 10:30:46'),
(38, 15, '2026-01-13', 82, '2026-01-18 10:30:46'),
(39, 15, '2026-01-14', 88, '2026-01-18 10:30:46'),
(40, 15, '2026-01-15', 78, '2026-01-18 10:30:46'),
(41, 15, '2026-01-16', 65, '2026-01-18 10:30:46'),
(42, 15, '2026-01-17', 80, '2026-01-18 10:30:46'),
(61, 15, '2026-01-18', 90, '2026-01-18 11:42:43'),
(65, 15, '2026-01-19', 33, '2026-01-19 04:20:10'),
(69, 15, '2026-01-21', 60, '2026-01-21 05:37:03'),
(71, 22, '2026-01-21', 61, '2026-01-21 14:45:43'),
(75, 22, '2026-01-22', 40, '2026-01-22 01:50:42');

-- --------------------------------------------------------

--
-- Table structure for table `daily_checkins`
--

CREATE TABLE `daily_checkins` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `checkin_date` date NOT NULL,
  `sleep_hours` int(11) DEFAULT NULL,
  `sleep_quality` enum('Good','Moderate','Poor') DEFAULT NULL,
  `stress_level` enum('Low','Medium','High') DEFAULT NULL,
  `morning_energy` enum('Low','Normal','High') DEFAULT NULL,
  `evening_energy` enum('Low','Normal','High') DEFAULT NULL,
  `body_dryness` tinyint(4) DEFAULT NULL,
  `body_heat` tinyint(4) DEFAULT NULL,
  `body_heaviness` tinyint(4) DEFAULT NULL,
  `cold_body` tinyint(4) DEFAULT NULL,
  `sweet_craving` tinyint(4) DEFAULT NULL,
  `spicy_craving` tinyint(4) DEFAULT NULL,
  `elimination` enum('Regular','Dry','Loose','Heavy') DEFAULT NULL,
  `hydration_level` int(11) DEFAULT NULL,
  `mood` enum('Calm','Irritable','Anxious','Low') DEFAULT NULL,
  `physical_activity` enum('None','Light','Moderate','Intense') DEFAULT NULL,
  `digestion` enum('Light','Normal','Heavy','Bloated') DEFAULT NULL,
  `appetite` enum('Low','Normal','Strong') DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `vata_score` int(11) DEFAULT 0,
  `pitta_score` int(11) DEFAULT 0,
  `kapha_score` int(11) DEFAULT 0,
  `dominant_dosha` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `daily_checkins`
--

INSERT INTO `daily_checkins` (`id`, `user_id`, `checkin_date`, `sleep_hours`, `sleep_quality`, `stress_level`, `morning_energy`, `evening_energy`, `body_dryness`, `body_heat`, `body_heaviness`, `cold_body`, `sweet_craving`, `spicy_craving`, `elimination`, `hydration_level`, `mood`, `physical_activity`, `digestion`, `appetite`, `created_at`, `vata_score`, `pitta_score`, `kapha_score`, `dominant_dosha`) VALUES
(2, 15, '2026-01-20', 6, 'Moderate', 'High', 'Low', 'Low', 1, 0, 1, 0, 0, 0, 'Regular', 5, 'Calm', 'Light', 'Normal', 'Strong', '2026-01-20 01:50:45', 0, 0, 0, NULL),
(3, 1, '2025-12-27', 1, '', '', '', '', 1, 0, 0, 0, 0, 1, 'Regular', 8, 'Calm', '', '', 'Normal', '2025-12-27 08:53:31', 0, 0, 0, NULL),
(8, 15, '2026-01-03', 7, 'Moderate', 'Medium', 'Normal', 'Normal', 1, 1, 1, 0, 0, 0, 'Regular', 7, 'Calm', 'Light', 'Heavy', 'Strong', '2026-01-03 03:23:24', 0, 0, 0, NULL),
(54, 15, '2026-01-04', 7, 'Moderate', 'Low', 'Normal', 'High', 1, 1, 1, 0, 0, 0, 'Loose', 10, 'Irritable', 'Intense', 'Bloated', 'Strong', '2026-01-04 02:50:56', 0, 0, 0, NULL),
(56, 16, '2026-01-04', 7, 'Moderate', 'Low', 'High', 'High', 0, 1, 1, 1, 0, 0, '', 6, 'Anxious', 'Moderate', 'Heavy', 'Strong', '2026-01-04 03:48:30', 0, 0, 0, NULL),
(57, 17, '2026-01-04', 7, 'Moderate', 'Medium', 'High', 'Low', 0, 1, 0, 1, 0, 1, '', 6, 'Irritable', 'Moderate', 'Bloated', 'Normal', '2026-01-04 04:38:02', 0, 0, 0, NULL),
(58, 20, '2026-01-04', 3, 'Poor', 'Low', 'Normal', 'Low', 1, 0, 0, 0, 0, 0, 'Loose', 2, 'Irritable', 'Moderate', 'Heavy', 'Normal', '2026-01-04 14:17:22', 0, 0, 0, NULL),
(59, 15, '2026-01-05', 10, 'Good', 'Medium', 'High', 'Low', 0, 0, 0, 1, 1, 0, '', 6, 'Low', 'Light', 'Normal', 'Strong', '2026-01-05 00:19:54', 0, 0, 0, NULL),
(62, 17, '2026-01-05', 7, 'Good', 'High', 'High', 'Normal', 0, 1, 0, 0, 1, 0, 'Loose', 6, 'Irritable', 'Intense', 'Heavy', 'Strong', '2026-01-05 04:27:59', 0, 0, 0, NULL),
(77, 17, '2026-01-06', 7, 'Poor', 'High', 'Normal', 'Low', 1, 0, 1, 0, 1, 1, '', 8, 'Low', 'Moderate', 'Bloated', 'Normal', '2026-01-06 01:05:07', 0, 0, 0, NULL),
(133, 15, '2026-01-18', 7, 'Good', 'Low', 'Normal', 'Normal', 0, 0, 0, 0, 0, 0, 'Regular', 3, 'Calm', 'Moderate', 'Normal', 'Normal', '2026-01-18 11:42:43', 0, 0, 0, NULL),
(135, 15, '2026-01-19', 3, 'Poor', 'High', 'Low', 'Low', 1, 1, 0, 0, 0, 0, 'Regular', 7, 'Irritable', 'None', 'Bloated', 'Strong', '2026-01-19 04:20:10', 0, 0, 0, NULL),
(139, 15, '2026-01-13', 6, NULL, 'Medium', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, NULL, NULL, NULL, NULL, '2026-01-13 04:30:00', 0, 0, 0, NULL),
(140, 15, '2026-01-14', 8, NULL, 'Medium', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, NULL, NULL, NULL, NULL, '2026-01-14 04:30:00', 0, 0, 0, NULL),
(141, 15, '2026-01-15', 5, NULL, 'Medium', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, NULL, NULL, NULL, NULL, '2026-01-15 04:30:00', 0, 0, 0, NULL),
(142, 15, '2026-01-16', 7, NULL, 'Medium', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, NULL, NULL, NULL, NULL, '2026-01-16 04:30:00', 0, 0, 0, NULL),
(143, 15, '2026-01-17', 6, NULL, 'Medium', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, NULL, NULL, NULL, NULL, '2026-01-17 04:30:00', 0, 0, 0, NULL),
(144, 15, '2026-01-06', 7, NULL, 'Low', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, NULL, NULL, NULL, NULL, '2026-01-06 04:30:00', 0, 0, 0, NULL),
(145, 15, '2026-01-07', 6, NULL, 'Low', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, NULL, NULL, NULL, NULL, '2026-01-07 04:30:00', 0, 0, 0, NULL),
(146, 15, '2026-01-08', 8, NULL, 'Low', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, NULL, NULL, NULL, NULL, '2026-01-08 04:30:00', 0, 0, 0, NULL),
(147, 15, '2026-01-09', 7, NULL, 'Low', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, NULL, NULL, NULL, NULL, '2026-01-09 04:30:00', 0, 0, 0, NULL),
(148, 15, '2026-01-10', 6, NULL, 'Low', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, NULL, NULL, NULL, NULL, '2026-01-10 04:30:00', 0, 0, 0, NULL),
(149, 15, '2026-01-11', 8, NULL, 'Low', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, NULL, NULL, NULL, NULL, '2026-01-11 04:30:00', 0, 0, 0, NULL),
(150, 15, '2026-01-12', 7, NULL, 'Low', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, NULL, NULL, NULL, NULL, '2026-01-12 04:30:00', 0, 0, 0, NULL),
(152, 15, '2026-01-21', 7, 'Good', 'Medium', 'High', 'Normal', 0, 0, 0, 0, 0, 1, 'Regular', 5, 'Calm', 'Light', 'Heavy', 'Strong', '2026-01-21 05:37:03', 0, 0, 0, NULL),
(153, 22, '2026-01-21', 6, 'Moderate', 'High', 'Low', 'Low', 0, 1, 0, 0, 0, 0, 'Regular', 2, 'Low', 'Light', 'Normal', 'Normal', '2026-01-21 14:45:43', 0, 0, 0, NULL),
(155, 22, '2026-01-22', 7, 'Moderate', 'High', 'Low', 'Low', 1, 0, 1, 0, 0, 0, 'Regular', 7, 'Calm', 'Intense', 'Bloated', 'Strong', '2026-01-22 01:50:42', 0, 0, 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `dosha_scores`
--

CREATE TABLE `dosha_scores` (
  `score_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `checkin_date` date NOT NULL,
  `vata_score` int(11) DEFAULT 0,
  `pitta_score` int(11) DEFAULT 0,
  `kapha_score` int(11) DEFAULT 0,
  `dominant_dosha` varchar(50) DEFAULT NULL,
  `body_balance_score` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dosha_scores`
--

INSERT INTO `dosha_scores` (`score_id`, `user_id`, `checkin_date`, `vata_score`, `pitta_score`, `kapha_score`, `dominant_dosha`, `body_balance_score`) VALUES
(1, 15, '2026-01-03', 0, 70, 40, 'Pitta', NULL),
(2, 15, '2026-01-20', 40, 35, 65, 'Kapha', NULL),
(10, 15, '2026-01-04', 70, 105, 40, 'Pitta', NULL),
(12, 16, '2026-01-04', 60, 70, 70, 'Pitta', NULL),
(13, 17, '2026-01-04', 45, 105, 30, 'Pitta', NULL),
(14, 20, '2026-01-04', 55, 35, 40, 'Vata', NULL),
(15, 15, '2026-01-05', 25, 45, 100, 'Kapha', NULL),
(18, 17, '2026-01-05', 35, 110, 55, 'Pitta', NULL),
(31, 15, '2026-01-06', 110, 70, 50, 'Vata', NULL),
(33, 17, '2026-01-06', 95, 25, 75, 'Vata', NULL),
(40, 1, '2026-01-06', 80, 50, 20, NULL, NULL),
(44, 15, '2026-01-07', 35, 40, 25, 'Pitta', 80),
(45, 15, '2026-01-08', 40, 35, 25, 'Vata', 75),
(46, 15, '2026-01-09', 30, 45, 25, 'Pitta', 85),
(47, 15, '2026-01-10', 35, 35, 30, 'Vata', 80),
(48, 15, '2026-01-11', 50, 25, 25, 'Vata', 60),
(49, 15, '2026-01-12', 40, 35, 25, 'Vata', 70),
(50, 15, '2026-01-13', 35, 40, 25, 'Pitta', 82),
(51, 15, '2026-01-14', 30, 40, 30, 'Pitta', 88),
(52, 15, '2026-01-15', 35, 35, 30, 'Vata', 78),
(53, 15, '2026-01-16', 45, 25, 30, 'Vata', 65),
(54, 15, '2026-01-17', 35, 40, 25, 'Pitta', 80),
(65, 15, '2026-01-18', 0, 10, 20, 'Kapha', NULL),
(67, 15, '2026-01-19', 95, 55, 50, 'Vata', NULL),
(69, 15, '2026-01-21', 0, 70, 50, 'Pitta', NULL),
(70, 22, '2026-01-21', 20, 40, 55, 'Kapha', NULL),
(72, 22, '2026-01-22', 75, 50, 55, 'Vata', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `herbs`
--

CREATE TABLE `herbs` (
  `id` int(11) NOT NULL,
  `dosha` enum('Vata','Pitta','Kapha','Tri-Dosha') NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `benefits` text DEFAULT NULL,
  `usage_dosage` varchar(255) DEFAULT NULL,
  `usage_preparation` varchar(255) DEFAULT NULL,
  `usage_time` varchar(255) DEFAULT NULL,
  `precautions` text DEFAULT NULL,
  `image_filename` varchar(255) DEFAULT NULL,
  `tags` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `herbs`
--

INSERT INTO `herbs` (`id`, `dosha`, `name`, `description`, `benefits`, `usage_dosage`, `usage_preparation`, `usage_time`, `precautions`, `image_filename`, `tags`, `created_at`) VALUES
(1, 'Vata', 'Ashwagandha', 'Best adaptability herb for stress and sleep.', 'Reduces stress and anxiety; Improves sleep quality; Boosts energy levels', '300-500mg root extract', 'Capsule, powder, or tea', 'Before bedtime with warm milk', 'Avoid during pregnancy. Consult if you have thyroid issues.', 'ashwagandha.jpg', 'sleep,stress', '2026-01-05 00:58:55'),
(2, 'Vata', 'Ginger', 'Warms digestion and clears cold.', 'Burns excess mucus; Stimulates sluggish digestion; Clears sinuses', '1-2g powder', 'Mix with honey or take as tea', 'After meals', 'Avoid if experiencing high pitta (heartburn).', 'ginger.jpg', 'digestion,cold', '2026-01-05 00:58:55'),
(3, 'Vata', 'Triphala', 'Balances digestion and elimination.', 'Gently detoxifies the colon; Regulates elimination; Balances all three doshas', '1/2 to 1 tsp powder', 'Mix in warm water', 'Before bedtime', 'Reduce dose if loose stools occur.', 'triphala.jpg', 'constipation,digestion', '2026-01-05 00:58:55'),
(4, 'Vata', 'Brahmi', 'Calms the mind and nerves.', 'Enhances memory and focus; Calms a racing mind; Supports nervous system', '250-500mg extract', 'Capsule or boiled with milk', 'Morning and evening', 'May cause mild drowsiness in sensitive individuals.', 'brahmi.jpg', 'stress,mind', '2026-01-05 00:58:55'),
(5, 'Vata', 'Shatavari', 'Nourishing and hydrating for dry tissues.', 'Nourishing and hydrating; Supports hormonal balance; Rejuvenates dry tissues', '500-1000mg', 'Capsule or powder with milk', 'After meals', 'Generally safe, avoid if suffering from heavy congestion.', 'shatavari.jpg', 'dryness,hydration', '2026-01-05 00:58:55'),
(6, 'Vata', 'Licorice (Yashtimadhu)', 'Soothing demulcent for throat and gut.', 'Soothes throat and gut; Relieves acidity; Natural demulcent', '1-2g powder', 'Tea or with honey', 'Any time for relief', 'Avoid if you have high blood pressure.', 'licorice.jpg', 'digestion,throat', '2026-01-05 00:58:55'),
(7, 'Vata', 'Cardamom', 'Aromatic spice that aids nutrient absorption.', 'Aromatic digestive; Reduces gas; Freshens breath', '2-3 pods or 1/2 tsp powder', 'Chew pods or add to food/tea', 'After meals', 'None.', 'cardamom.jpg', 'digestion,appetite', '2026-01-05 00:58:55'),
(8, 'Vata', 'Cumin', 'Gentle digestive aid.', 'Gentle digestive stimulant; Resolves bloating; Improves absorption', '1/2 tsp powder', 'Add to food or drink as tea', 'During or after meals', 'Safe for daily use.', 'cumin.jpg', 'digestion', '2026-01-05 00:58:55'),
(9, 'Vata', 'Fennel', 'Relieves gas and bloating.', 'Relieves gas and bloating; Cools the gut; Fresh digestive aid', '1/2 to 1 tsp seeds', 'Steep as tea or chew seeds', 'After meals', 'Safe for most.', 'fennel.jpg', 'digestion,bloating', '2026-01-05 00:58:55'),
(10, 'Vata', 'Haritaki', 'Vata-balancing fruit for detoxification.', 'Deeply detoxifying; King of Vata-balancing fruits; Improves cognitive health', '1-3g powder', 'Mix with warm water', 'Before bed', 'Avoid during pregnancy or acute diarrhea.', 'haritaki.jpg', 'detox,digestion', '2026-01-05 00:58:55'),
(11, 'Vata', 'Pippali', 'Long pepper; warms the lungs and gut.', 'Strong respiratory tonic; Boosts metabolic fire; Clears deep toxins', '250-500mg powder', 'Take with honey', 'Early morning', 'Avoid in high pitta or pregnancy.', 'pippali.jpg', 'cold,respiratory', '2026-01-05 00:58:55'),
(12, 'Vata', 'Nutmeg', 'Natural sedative for insomnia.', 'Natural sedative; Improves sleep depth; Calms Vata in the mind', 'Pinch (approx 500mg)', 'Add to warm milk', '30 mins before sleep', 'Large doses can be toxic; use sparingly.', 'nutmeg.jpg', 'sleep', '2026-01-05 00:58:55'),
(13, 'Vata', 'Valerian', 'Strong herb for deep sleep and anxiety.', 'Powerful sleep aid; Helps management of anxiety; Relaxes muscles', '400-900mg extract', 'Capsule/Tablet', 'Before sleep', 'Do not mix with alcohol or other sedatives.', 'valerian.jpg', 'sleep,anxiety', '2026-01-05 00:58:55'),
(14, 'Vata', 'Jatamansi', 'Calms a racing mind and promotes sleep.', 'Calms a racing mind; Promotes deep sleep; Reduces panic and stress', '1-3g powder', 'With honey or warm water', 'Before bed', 'Consult if on blood pressure medication.', 'jatamansi.jpg', 'stress,sleep', '2026-01-05 00:58:55'),
(15, 'Vata', 'Shankhpushpi', 'Brain tonic for memory and focus.', 'Highest brain tonic; Improves focus and concentration; Stops mental fatigue', '1/2 to 1 tsp powder', 'With warm milk or juice', 'Morning on empty stomach', 'None reported.', 'shankhpushpi.jpg', 'mind,focus', '2026-01-05 00:58:55'),
(16, 'Pitta', 'Aloe Vera', 'Best cooling herb for digestion and skin.', 'Cools digestive heat; Relieves heartburn; Soothes skin inflammation', '1-2 tbsp gel', 'Juice or topical gel', 'Morning empty stomach', 'Avoid during menstruation.', 'aloe_vera.jpg', 'digestion,heat,skin', '2026-01-05 00:58:55'),
(17, 'Pitta', 'Shatavari', 'Cools inflammation and reproductive system.', 'Nourishing and hydrating; Supports hormonal balance; Rejuvenates dry tissues', '500-1000mg', 'Capsule or powder with milk', 'After meals', 'Generally safe, avoid if suffering from heavy congestion.', 'shatavari.jpg', 'heat,inflammation', '2026-01-05 00:58:55'),
(18, 'Pitta', 'Amalaki', 'High Vitamin C, removes excess heat.', 'Highest source of Vit C; Cools the heart; Boosts immunity', '1-3g powder', 'With water or juice', 'Morning', 'Generally safe.', 'amalaki.jpg', 'heat,immunity,acidity', '2026-01-05 00:58:55'),
(19, 'Pitta', 'Neem', 'Powerful blood purifier and skin clearer.', 'Blood purifier; Clears acne and skin rashes; Removes heat', '1 tablet or 1/2 tsp powder', 'With honey or water', 'Before meals', 'Avoid if you have very low body temperature.', 'neem.jpg', 'skin,detox,heat', '2026-01-05 00:58:55'),
(20, 'Pitta', 'Fennel', 'Cools digestion and stops heartburn.', 'Relieves gas and bloating; Cools the gut; Fresh digestive aid', '1/2 to 1 tsp seeds', 'Steep as tea or chew seeds', 'After meals', 'Safe for most.', 'fennel.jpg', 'acidity,digestion', '2026-01-05 00:58:55'),
(21, 'Pitta', 'Coriander', 'Cooling spice for urinary and gut health.', 'Cooling digestive; Reduces burning in gut; Clears urine path', '1/2 tsp powder', 'Tea or added to food', 'During meals', 'None.', 'coriander.jpg', 'heat,digestion', '2026-01-05 00:58:55'),
(22, 'Pitta', 'Rose', 'Soothes emotions and cools the heart.', 'Soothes emotions; Cools the heart and mind; Improves skin glow', '1-2 tsp rose water or petals', 'Tea or added to water', 'Any time', 'None.', 'rose.jpg', 'stress,heat', '2026-01-05 00:58:55'),
(23, 'Pitta', 'Guduchi', 'Top immunity herb that does not overheat.', 'Supreme immunity herb; Removes excess Pitta heat; Removes fever', '500mg extract', 'Capsule or decoction', 'Morning and evening', 'Safe, but avoid during acute cold.', 'guduchi.jpg', 'immunity,fever', '2026-01-05 00:58:55'),
(24, 'Pitta', 'Manjistha', 'Lymphatic cleanser and skin support.', 'Blood cleanser; Removes skin blockages; Pitta-Vata balance', '1/2 to 1 tsp powder', 'With warm water or honey', 'After meals', 'Avoid during pregnancy.', 'manjistha.jpg', 'skin,detox', '2026-01-05 00:58:55'),
(25, 'Pitta', 'Brahmi', 'Cools the mind (good for both Vata/Pitta).', 'Enhances memory and focus; Calms a racing mind; Supports nervous system', '250-500mg extract', 'Capsule or boiled with milk', 'Morning and evening', 'May cause mild drowsiness in sensitive individuals.', 'brahmi.jpg', 'mind,stress', '2026-01-05 00:58:55'),
(26, 'Pitta', 'Bhringaraj', 'Excellent for hair growth and liver cooling.', 'Best for hair growth; Cools the liver; Enhances vision', '250-500mg powder', 'Capsule or oil massage', 'Morning', 'None for topical use.', 'bhringaraj.jpg', 'hair,liver', '2026-01-05 00:58:55'),
(27, 'Pitta', 'Mint', 'Immediate cooling relief for digestion.', 'Immediate cooling; Relieves indigestion; Soothes mind', '5-10 leaves or 1/2 tsp tea', 'Steep as tea', 'After lunch', 'Caution in high Vata.', 'mint.jpg', 'digestion,heat', '2026-01-05 00:58:55'),
(28, 'Pitta', 'Licorice', 'Heals ulcers and acidity.', 'Soothes throat and gut; Relieves acidity; Natural demulcent', '1-2g powder', 'Tea or with honey', 'Any time for relief', 'Avoid if you have high blood pressure.', 'licorice.jpg', 'acidity,throat', '2026-01-05 00:58:55'),
(29, 'Pitta', 'Chandan (Sandalwood)', 'Topically and internally cooling.', 'Cooling brain tonic; Stops anger and burnout; Improves focus', '1 tsp paste or 500mg powder', 'Internal or external application', 'Morning', 'None.', 'chandan.jpg', 'skin,heat', '2026-01-05 00:58:55'),
(30, 'Pitta', 'Kutki', 'Liver support and detoxifier.', 'Elite liver detox; Stimulates bile; Clears skin allergies', '500mg to 1g powder', 'With honey', 'Before meals', 'Avoid during diarrhea.', 'kutki.jpg', 'liver,detox', '2026-01-05 00:58:55'),
(31, 'Kapha', 'Turmeric', 'Anti-inflammatory and clears congestion.', 'Anti-inflammatory; Clears lung congestion; Boosts metabolism', '1/2 to 1 tsp powder', 'Mix in food or milk', 'After meals', 'Avoid in high doses during pregnancy.', 'turmeric.jpg', 'inflammation,congestion', '2026-01-05 00:58:55'),
(32, 'Kapha', 'Ginger', 'Stimulates digestion and burns mucus.', 'Burns excess mucus; Stimulates sluggish digestion; Clears sinuses', '1-2g powder', 'Mix with honey or take as tea', 'After meals', 'Avoid if experiencing high pitta (heartburn).', 'ginger.jpg', 'digestion,metabolism,cold', '2026-01-05 00:58:55'),
(33, 'Kapha', 'Black Pepper', 'Clears sinuses and boosts metabolism.', 'Clears sinuses; Strong metabolism booster; Burns ama (toxins)', '1-2 pinches', 'Add to food or honey', 'With lunch', 'Avoid in stomach ulcers.', 'black_pepper.jpg', 'metabolism,congestion', '2026-01-05 00:58:55'),
(34, 'Kapha', 'Tulsi (Holy Basil)', 'Supports respiratory health and immunity.', NULL, NULL, NULL, NULL, NULL, 'tulsi.jpg', 'immunity,respiratory', '2026-01-05 00:58:55'),
(35, 'Kapha', 'Cinnamon', 'Improves circulation and blood sugar.', NULL, NULL, NULL, NULL, NULL, 'cinnamon.jpg', 'circulation,appetite', '2026-01-05 00:58:55'),
(36, 'Kapha', 'Guggul', 'Lowers cholesterol and promotes weight loss.', 'Reduces weight and cholesterol; Clears lymphatic stasis', '500-1000mg', 'Tablet or capsule', 'Morning and evening', 'Can cause mild digestive upset.', 'guggul.jpg', 'weight,cholesterol', '2026-01-05 00:58:55'),
(37, 'Kapha', 'Trikatu', 'Classic blend for weak digestion and cold.', 'Supreme Kapha burner; Stimulates all digestive enzymes', '250-500mg', 'With honey after food', 'After heavy meals', 'Highly pungent; avoid in high Pitta.', 'Trikatu.jpg', 'digestion,metabolism', '2026-01-05 00:58:55'),
(38, 'Kapha', 'Pippali', 'Rejuvenative for lungs and breathing.', 'Strong respiratory tonic; Boosts metabolic fire; Clears deep toxins', '250-500mg powder', 'Take with honey', 'Early morning', 'Avoid in high pitta or pregnancy.', 'pippali.jpg', 'respiratory,immunity', '2026-01-05 00:58:55'),
(39, 'Kapha', 'Chitrak', 'Powerful digestive fire igniter.', 'Ignites internal fire; Removes stagnation; Aids weight loss', '250mg', 'Capsule or with honey', 'Before lunch', 'Toxic in large amounts; professional advice only.', 'chitrak.jpg', 'digestion,metabolism', '2026-01-05 00:58:55'),
(40, 'Kapha', 'Triphala', 'Good for gentle detox (good for all).', 'Gently detoxifies the colon; Regulates elimination; Balances all three doshas', '1/2 to 1 tsp powder', 'Mix in warm water', 'Before bedtime', 'Reduce dose if loose stools occur.', 'triphala.jpg', 'detox,digestion', '2026-01-05 00:58:55'),
(41, 'Kapha', 'Punarnava', 'Kidney support and reduces water retention.', 'Diuretic; Reduces water retention; Kidneys support', '500mg-1g', 'Tea or powder with water', 'Morning', 'Avoid during pregnancy.', 'punarnava.jpg', 'water_retention,kidney', '2026-01-05 00:58:55'),
(42, 'Kapha', 'Bibhitaki', 'Clears congestion from throat and lungs.', 'Clears throat and lungs; Respiratory rejuvenative', '1-3g powder', 'Mixed with honey', 'After breakfast', 'None.', 'bibhitaki.jpg', 'congestion,throat', '2026-01-05 00:58:55'),
(43, 'Kapha', 'Mustard Seeds', 'Heating and stimulating.', 'Strong warming spice; Clear cold from blood; Stimulating', 'As needed in food', 'Culinary use', 'During meals', 'Avoid in skin ulcers.', 'Mustard Seeds.jpg', 'heat,digestion', '2026-01-05 00:58:55'),
(44, 'Kapha', 'Garlic', 'Strong immunity booster and heart health.', 'Natural antibiotic; Heart protector; Burns toxic Kapha', '1-2 cloves', 'Crushed or in food', 'Morning', 'Avoid if on blood thinners.', 'garlic.jpg', 'immunity,heart', '2026-01-05 00:58:55'),
(45, 'Kapha', 'Fenugreek', 'Balance blood sugar and congestion.', 'Balances blood sugar; Clears skin; Reduces stagnation', '1 tsp seeds soaked', 'Soaked seeds or tea', 'Empty stomach morning', 'Slightly heating for Pitta.', 'fenugreek.jpg', 'diabetes,digestion', '2026-01-05 00:58:55');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` text DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `title`, `message`, `type`, `is_read`, `created_at`) VALUES
(1, 15, 'Daily Check-in Reminder', 'Time for your daily Ayurvedic check-in!', 'checkin', 0, '2026-01-05 04:53:23'),
(2, 15, 'New Personalized Guidance', 'Your Dosha balance has shifted. Check new tips.', 'guidance', 0, '2026-01-05 04:53:23'),
(3, 15, 'Progress Milestone Reached', '7-Day check-in streak completed!', 'milestone', 0, '2026-01-05 04:53:23'),
(4, 15, 'App Update Available', 'Version 2.0 is now live with enhanced AI.', 'update', 0, '2026-01-05 04:53:23');

-- --------------------------------------------------------

--
-- Table structure for table `recommendations`
--

CREATE TABLE `recommendations` (
  `id` int(11) NOT NULL,
  `dosha` enum('Vata','Pitta','Kapha','Tri-Dosha') NOT NULL,
  `category` enum('food','yoga','lifestyle') NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `recommendations`
--

INSERT INTO `recommendations` (`id`, `dosha`, `category`, `content`, `created_at`) VALUES
(1, 'Vata', 'food', 'Warm, creamy soups and stews', '2026-01-05 00:58:55'),
(2, 'Vata', 'food', 'Cooked root vegetables (carrots, beets, sweet potatoes)', '2026-01-05 00:58:55'),
(3, 'Vata', 'food', 'Ghee (clarified butter) and sesame oil', '2026-01-05 00:58:55'),
(4, 'Vata', 'food', 'Warm milk with ginger and cardamom', '2026-01-05 00:58:55'),
(5, 'Vata', 'food', 'Soaked almonds and walnuts', '2026-01-05 00:58:55'),
(6, 'Vata', 'food', 'Sweet, ripe fruits like berries, peaches, and mangoes', '2026-01-05 00:58:55'),
(7, 'Vata', 'food', 'Avocados and coconut meat', '2026-01-05 00:58:55'),
(8, 'Vata', 'food', 'Cooked oats and rice pudding', '2026-01-05 00:58:55'),
(9, 'Vata', 'food', 'Herbal teas: Ginger, Cinnamon, and Licorice', '2026-01-05 00:58:55'),
(10, 'Vata', 'food', 'Mung bean soup (Kitchari)', '2026-01-05 00:58:55'),
(11, 'Vata', 'food', 'Avoid raw, cold salads', '2026-01-05 00:58:55'),
(12, 'Vata', 'food', 'Avoid ice-cold drinks and carbonated beverages', '2026-01-05 00:58:55'),
(13, 'Vata', 'food', 'Limit dried fruits unless soaked', '2026-01-05 00:58:55'),
(14, 'Vata', 'food', 'Favor sweet, sour, and salty tastes', '2026-01-05 00:58:55'),
(15, 'Vata', 'food', 'Eat small, frequent meals at regular times', '2026-01-05 00:58:55'),
(16, 'Vata', 'yoga', 'Mountain Pose (Tadasana) - for stability', '2026-01-05 00:58:55'),
(17, 'Vata', 'yoga', 'Tree Pose (Vrksasana) - for balance', '2026-01-05 00:58:55'),
(18, 'Vata', 'yoga', 'Warrior I (Virabhadrasana I) - for grounding', '2026-01-05 00:58:55'),
(19, 'Vata', 'yoga', 'Child’s Pose (Balasana) - for calming', '2026-01-05 00:58:55'),
(20, 'Vata', 'yoga', 'Corpse Pose (Savasana) - for deep rest', '2026-01-05 00:58:55'),
(21, 'Vata', 'yoga', 'Legs Up the Wall (Viparita Karani)', '2026-01-05 00:58:55'),
(22, 'Vata', 'yoga', 'Cat-Cow Stretch - for gentle movement', '2026-01-05 00:58:55'),
(23, 'Vata', 'yoga', 'Cobra Pose (Bhujangasana)', '2026-01-05 00:58:55'),
(24, 'Vata', 'yoga', 'Seated Forward Bend (Paschimottanasana)', '2026-01-05 00:58:55'),
(25, 'Vata', 'yoga', 'Bridge Pose (Setu Bandhasana)', '2026-01-05 00:58:55'),
(26, 'Vata', 'yoga', 'Sphinx Pose - gentle backbend', '2026-01-05 00:58:55'),
(27, 'Vata', 'yoga', 'Alternate Nostril Breathing (Nadi Shodhana)', '2026-01-05 00:58:55'),
(28, 'Vata', 'yoga', 'Sun Salutations (Slow, meditative pace)', '2026-01-05 00:58:55'),
(29, 'Vata', 'yoga', 'Thunderbolt Pose (Vajrasana)', '2026-01-05 00:58:55'),
(30, 'Vata', 'yoga', 'Root Lock (Mula Bandha) practice', '2026-01-05 00:58:55'),
(31, 'Vata', 'lifestyle', 'Stick to a strict daily routine (Dinacharya)', '2026-01-05 00:58:55'),
(32, 'Vata', 'lifestyle', 'Go to bed early (by 10 PM)', '2026-01-05 00:58:55'),
(33, 'Vata', 'lifestyle', 'Daily self-massage with warm sesame oil (Abhyanga)', '2026-01-05 00:58:55'),
(34, 'Vata', 'lifestyle', 'Keep calm and avoid overstimulation', '2026-01-05 00:58:55'),
(35, 'Vata', 'lifestyle', 'Stay warm and avoid drafts/wind', '2026-01-05 00:58:55'),
(36, 'Vata', 'lifestyle', 'Gentle walking in nature', '2026-01-05 00:58:55'),
(37, 'Vata', 'lifestyle', 'Practice meditation for grounding', '2026-01-05 00:58:55'),
(38, 'Vata', 'lifestyle', 'Limit screen time 1 hour before bed', '2026-01-05 00:58:55'),
(39, 'Vata', 'lifestyle', 'Listen to calming, slow-tempo music', '2026-01-05 00:58:55'),
(40, 'Vata', 'lifestyle', 'Avoid skipping meals', '2026-01-05 00:58:55'),
(41, 'Vata', 'lifestyle', 'Sip warm water throughout the day', '2026-01-05 00:58:55'),
(42, 'Vata', 'lifestyle', 'Use aromatherapy: Lavender, Sandalwood, Orange', '2026-01-05 00:58:55'),
(43, 'Vata', 'lifestyle', 'Take warm, relaxing baths', '2026-01-05 00:58:55'),
(44, 'Vata', 'lifestyle', 'Practice silence (Mauna) for 15 mins daily', '2026-01-05 00:58:55'),
(45, 'Vata', 'lifestyle', 'Avoid multitasking; focus on one thing at a time', '2026-01-05 00:58:55'),
(46, 'Pitta', 'food', 'Sweet, ripe fruits (grapes, melons, pears)', '2026-01-05 00:58:55'),
(47, 'Pitta', 'food', 'Coconut water and coconut meat', '2026-01-05 00:58:55'),
(48, 'Pitta', 'food', 'Cucumber, celery, and leafy greens', '2026-01-05 00:58:55'),
(49, 'Pitta', 'food', 'Basmati rice, quinoa, and oats', '2026-01-05 00:58:55'),
(50, 'Pitta', 'food', 'Ghee and unsalted butter', '2026-01-05 00:58:55'),
(51, 'Pitta', 'food', 'Cow’s milk and fresh cheese (paneer)', '2026-01-05 00:58:55'),
(52, 'Pitta', 'food', 'Cooling herbs: Mint, Cilantro, Dill', '2026-01-05 00:58:55'),
(53, 'Pitta', 'food', 'Sunflower and pumpkin seeds', '2026-01-05 00:58:55'),
(54, 'Pitta', 'food', 'Mung beans and lentils', '2026-01-05 00:58:55'),
(55, 'Pitta', 'food', 'Avoid spicy chilies and hot peppers', '2026-01-05 00:58:55'),
(56, 'Pitta', 'food', 'Avoid sour foods (pickles, vinegar)', '2026-01-05 00:58:55'),
(57, 'Pitta', 'food', 'Avoid fried and oily foods', '2026-01-05 00:58:55'),
(58, 'Pitta', 'food', 'Avoid fermented foods (yogurt, alcohol)', '2026-01-05 00:58:55'),
(59, 'Pitta', 'food', 'Favor sweet, bitter, and astringent tastes', '2026-01-05 00:58:55'),
(60, 'Pitta', 'food', 'Drink room temperature water, never ice cold', '2026-01-05 00:58:55'),
(61, 'Pitta', 'yoga', 'Moon Salutations (Chandra Namaskar)', '2026-01-05 00:58:55'),
(62, 'Pitta', 'yoga', 'Child’s Pose (Balasana)', '2026-01-05 00:58:55'),
(63, 'Pitta', 'yoga', 'Cobra Pose (Bhujangasana) - gentle', '2026-01-05 00:58:55'),
(64, 'Pitta', 'yoga', 'Bow Pose (Dhanurasana)', '2026-01-05 00:58:55'),
(65, 'Pitta', 'yoga', 'Bridge Pose (Setu Bandhasana)', '2026-01-05 00:58:55'),
(66, 'Pitta', 'yoga', 'Fish Pose (Matsyasana)', '2026-01-05 00:58:55'),
(67, 'Pitta', 'yoga', 'Shoulder Stand (Sarvangasana)', '2026-01-05 00:58:55'),
(68, 'Pitta', 'yoga', 'Half Lord of the Fishes Twist', '2026-01-05 00:58:55'),
(69, 'Pitta', 'yoga', 'Standing Forward Bend (Uttanasana)', '2026-01-05 00:58:55'),
(70, 'Pitta', 'yoga', 'Sheetali Breath (Cooling Breath)', '2026-01-05 00:58:55'),
(71, 'Pitta', 'yoga', 'Pigeon Pose (Eka Pada Rajakapotasana)', '2026-01-05 00:58:55'),
(72, 'Pitta', 'yoga', 'Camel Pose (Ustrasana)', '2026-01-05 00:58:55'),
(73, 'Pitta', 'yoga', 'Gentle Boat Pose', '2026-01-05 00:58:55'),
(74, 'Pitta', 'yoga', 'Supine Spinal Twist', '2026-01-05 00:58:55'),
(75, 'Pitta', 'yoga', 'Corpse Pose (longer duration)', '2026-01-05 00:58:55'),
(76, 'Pitta', 'lifestyle', 'Avoid excessive heat and direct midday sun', '2026-01-05 00:58:55'),
(77, 'Pitta', 'lifestyle', 'Spend time near water (lakes, ocean)', '2026-01-05 00:58:55'),
(78, 'Pitta', 'lifestyle', 'Moon bathing (walking in moonlight)', '2026-01-05 00:58:55'),
(79, 'Pitta', 'lifestyle', 'Cultivate patience and forgiveness', '2026-01-05 00:58:55'),
(80, 'Pitta', 'lifestyle', 'Avoid competitive or aggressive activities', '2026-01-05 00:58:55'),
(81, 'Pitta', 'lifestyle', 'Take cool showers, not hot baths', '2026-01-05 00:58:55'),
(82, 'Pitta', 'lifestyle', 'Wear cooling colors (blue, green, white, silver)', '2026-01-05 00:58:55'),
(83, 'Pitta', 'lifestyle', 'Use aromatherapy: Rose, Sandalwood, Jasmine', '2026-01-05 00:58:55'),
(84, 'Pitta', 'lifestyle', 'Engage in non-competitive sports (swimming)', '2026-01-05 00:58:55'),
(85, 'Pitta', 'lifestyle', 'Take time for leisure and play', '2026-01-05 00:58:55'),
(86, 'Pitta', 'lifestyle', 'Do not skip meals (avoids being \"hangry\")', '2026-01-05 00:58:55'),
(87, 'Pitta', 'lifestyle', 'Moderation in work; avoid burnout', '2026-01-05 00:58:55'),
(88, 'Pitta', 'lifestyle', 'Use coconut oil for massage', '2026-01-05 00:58:55'),
(89, 'Pitta', 'lifestyle', 'Drink aloe vera juice', '2026-01-05 00:58:55'),
(90, 'Pitta', 'lifestyle', 'Practice cooling meditation (focus on compassion)', '2026-01-05 00:58:55'),
(91, 'Kapha', 'food', 'Spicy and pungent foods (Chilies, Garlic)', '2026-01-05 00:58:55'),
(92, 'Kapha', 'food', 'Leafy green vegetables (Kale, Spinach)', '2026-01-05 00:58:55'),
(93, 'Kapha', 'food', 'Legumes and beans (all types)', '2026-01-05 00:58:55'),
(94, 'Kapha', 'food', 'Quinoa, millet, and buckwheat', '2026-01-05 00:58:55'),
(95, 'Kapha', 'food', 'Astringent fruits (Apples, Pomegranates, Berries)', '2026-01-05 00:58:55'),
(96, 'Kapha', 'food', 'Ginger tea and black coffee (in moderation)', '2026-01-05 00:58:55'),
(97, 'Kapha', 'food', 'Raw honey (only sweetener allowed)', '2026-01-05 00:58:55'),
(98, 'Kapha', 'food', 'Black pepper, turmeric, and mustard seeds', '2026-01-05 00:58:55'),
(99, 'Kapha', 'food', 'Light dinners (soup or salad)', '2026-01-05 00:58:55'),
(100, 'Kapha', 'food', 'Popcorn (no butter)', '2026-01-05 00:58:55'),
(101, 'Kapha', 'food', 'Avoid dairy (milk, cheese, yogurt)', '2026-01-05 00:58:55'),
(102, 'Kapha', 'food', 'Avoid sweets and sugary desserts', '2026-01-05 00:58:55'),
(103, 'Kapha', 'food', 'Avoid fried and oily foods', '2026-01-05 00:58:55'),
(104, 'Kapha', 'food', 'Avoid cold drinks and ice cream', '2026-01-05 00:58:55'),
(105, 'Kapha', 'food', 'Favor Pungent, Bitter, and Astringent tastes', '2026-01-05 00:58:55'),
(106, 'Kapha', 'yoga', 'Sun Salutations (Fast, vigorous pace)', '2026-01-05 00:58:55'),
(107, 'Kapha', 'yoga', 'Warrior II (Virabhadrasana II)', '2026-01-05 00:58:55'),
(108, 'Kapha', 'yoga', 'Triangle Pose (Trikonasana)', '2026-01-05 00:58:55'),
(109, 'Kapha', 'yoga', 'Reverse Warrior', '2026-01-05 00:58:55'),
(110, 'Kapha', 'yoga', 'Camel Pose (Ustrasana)', '2026-01-05 00:58:55'),
(111, 'Kapha', 'yoga', 'Bow Pose (Dhanurasana)', '2026-01-05 00:58:55'),
(112, 'Kapha', 'yoga', 'Boat Pose (Navasana) - core strength', '2026-01-05 00:58:55'),
(113, 'Kapha', 'yoga', 'Headstand (Sirsasana)', '2026-01-05 00:58:55'),
(114, 'Kapha', 'yoga', 'Lion’s Breath (Simhasana)', '2026-01-05 00:58:55'),
(115, 'Kapha', 'yoga', 'Kapalabhati (Skull Shining Breath)', '2026-01-05 00:58:55'),
(116, 'Kapha', 'yoga', 'Chair Pose (Utkatasana)', '2026-01-05 00:58:55'),
(117, 'Kapha', 'yoga', 'Plank Pose', '2026-01-05 00:58:55'),
(118, 'Kapha', 'yoga', 'Side Plank (Vasisthasana)', '2026-01-05 00:58:55'),
(119, 'Kapha', 'yoga', 'Crow Pose (Bakasana)', '2026-01-05 00:58:55'),
(120, 'Kapha', 'yoga', 'Locust Pose (Salabhasana)', '2026-01-05 00:58:55'),
(121, 'Kapha', 'lifestyle', 'Wake up early (ideally before 6 AM)', '2026-01-05 00:58:55'),
(122, 'Kapha', 'lifestyle', 'Engage in vigorous exercise daily (cardio)', '2026-01-05 00:58:55'),
(123, 'Kapha', 'lifestyle', 'Seek new experiences and variety', '2026-01-05 00:58:55'),
(124, 'Kapha', 'lifestyle', 'Dry brushing (Garshana) to stimulate circulation', '2026-01-05 00:58:55'),
(125, 'Kapha', 'lifestyle', 'Sauna or steam baths to sweat', '2026-01-05 00:58:55'),
(126, 'Kapha', 'lifestyle', 'Avoid daytime napping', '2026-01-05 00:58:55'),
(127, 'Kapha', 'lifestyle', 'Declutter your living space regularly', '2026-01-05 00:58:55'),
(128, 'Kapha', 'lifestyle', 'Socialize and stay active with friends', '2026-01-05 00:58:55'),
(129, 'Kapha', 'lifestyle', 'Try fasting or skipping breakfast', '2026-01-05 00:58:55'),
(130, 'Kapha', 'lifestyle', 'Dress in warm, bright colors (Red, Orange)', '2026-01-05 00:58:55'),
(131, 'Kapha', 'lifestyle', 'Go hiking or running', '2026-01-05 00:58:55'),
(132, 'Kapha', 'lifestyle', 'Dancing or Zumba', '2026-01-05 00:58:55'),
(133, 'Kapha', 'lifestyle', 'Use stimulating aromatherapy: Eucalyptus, Rosemary', '2026-01-05 00:58:55'),
(134, 'Kapha', 'lifestyle', 'Engage in challenging mental tasks', '2026-01-05 00:58:55'),
(135, 'Kapha', 'lifestyle', 'Avoid sedentary activities like excessive TV', '2026-01-05 00:58:55');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `is_verified` tinyint(4) DEFAULT 0,
  `reset_otp` varchar(6) DEFAULT NULL,
  `reset_otp_expires` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `api_token` varchar(64) DEFAULT NULL,
  `reset_token` varchar(64) DEFAULT NULL,
  `checkin_streak` int(11) DEFAULT 0,
  `last_checkin_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `phone`, `email`, `password_hash`, `is_verified`, `reset_otp`, `reset_otp_expires`, `created_at`, `api_token`, `reset_token`, `checkin_streak`, `last_checkin_date`) VALUES
(9, 'eswar', NULL, 'saimanieswardutta8@gmail.com', '$2y$10$CcXMZsQIjeZvS6D5R.9Y2.kqKweAfEJxGoNLAeTdSQjEWgs7UfBpq', 0, '162406', '2026-01-02 17:15:34', '2026-01-02 16:05:34', NULL, NULL, 0, NULL),
(11, 'eswar', NULL, 'rohithnaidu269@gmail.com', '$2y$10$2yhphrylkR/qsG45bEVlQuJBsdy1YJdUYymqynm7vVyucSDknaLwe', 0, '293863', '2026-01-02 17:17:48', '2026-01-02 16:07:49', NULL, NULL, 0, NULL),
(12, 'gwyw', NULL, 'eswar@gmail.com', '$2y$10$t8jq2tLfcRYNvSXpvawuNuWzHOz/IPy1DXLWNFRu14zqtDRfAiudi', 0, '602368', '2026-01-02 17:32:35', '2026-01-02 16:22:35', NULL, NULL, 0, NULL),
(13, 'eswar', NULL, 'esw1ar@gmail.com', '$2y$10$yGA2akdSYLPhjFRbGAw6/.KAQp.ScONoNzaF5xZiCL/7FjTlrVoVK', 0, '906493', '2026-01-02 17:36:40', '2026-01-02 16:26:40', NULL, NULL, 0, NULL),
(14, 'sai manieswar', NULL, 'duttasaimanieswar@gmail.com', '$2y$10$YwGJGe6hRHjssZ2jRTMThuDWR4aV4RbwkWcKPYr098.eYIf9bAHTu', 0, NULL, NULL, '2026-01-02 16:47:58', NULL, NULL, 0, NULL),
(15, 'Sai', NULL, 'e02697113@gmail.com', '$2y$10$5VQ60L9iTHZpwBro5uSFU.b7DJCqsdEvNlzz3JlcRIkrv72SSlDFi', 1, NULL, NULL, '2026-01-03 01:47:55', '0fb354ebf7c692ec4fe566c946190b9d2e11ec35ec63cbd7d4e7755ae2854319', NULL, 19, '2026-01-21'),
(16, 'Gnaneswar', NULL, 'v.gnaneswarreddy18@gmail.com', '$2y$10$F2UYOczl0klFD8dsW/a1Fe65OlfEN/kF587Fgdcm4SCmOGNevNo2K', 1, NULL, NULL, '2026-01-04 03:46:54', 'a8ab90a816fb9962599266b1671019e672a6dbb96bd6bd50517d5aa7681db5ff', NULL, 0, NULL),
(17, 'siva', NULL, 'ysivaprasad0273@gmail.com', '$2y$10$SZy2SJ2Kr8mEq8Fe2Ck7iOkZTh5/rKfKh/7SKpdRJpSYbdcvvr7B.', 1, NULL, NULL, '2026-01-04 04:36:52', '190f952d742c890d5c4b51b550c8b43557e0107196fd752c6a5a3d320c82fc01', NULL, 4, '2026-01-06'),
(20, 'malli kharjuna', NULL, 'chevvumallikarjuna11@gmail.com', '$2y$10$zwoa1ktXrF36gh741.Hh4eNOvh0p5M/HE36hBHvjBKeRVWO1i3wSK', 1, NULL, NULL, '2026-01-04 14:14:49', '13934f5cf47c51a7e040961421dc6a26645fe711e1f4bbec4634b38f14982ec5', NULL, 0, NULL),
(22, 'User', '9573507649', 'aravachandu208@gmail.com', '$2y$10$f0XNoyhQz5x2MsrxI5u2O.KYHWIKCSNkDjEhPqum4ZSFiSWSaV78C', 1, NULL, NULL, '2026-01-21 14:43:30', '313f96abb2a9b4b2a904b74375e63dea3535af827a8e4b74c9b6eb6f144e01c4', NULL, 2, '2026-01-22');

-- --------------------------------------------------------

--
-- Table structure for table `user_profiles`
--

CREATE TABLE `user_profiles` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `gender` varchar(20) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `country` varchar(100) DEFAULT NULL,
  `profile_photo` varchar(255) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_profiles`
--

INSERT INTO `user_profiles` (`id`, `user_id`, `full_name`, `phone`, `gender`, `dob`, `country`, `profile_photo`, `updated_at`) VALUES
(1, 15, 'Sai', '9573507346', 'Male', '2004-06-26', 'India', 'http://10.65.241.223/ayurpredict/uploads/profiles/15_1768973861.jpg', '2026-01-21 05:37:41'),
(3, 22, 'User', '', '', '0000-00-00', '', 'http://10.65.241.223/ayurpredict/uploads/profiles/22_1769008372.jpg', '2026-01-21 15:12:52');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `body_balance_scores`
--
ALTER TABLE `body_balance_scores`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_daily_balance` (`user_id`,`checkin_date`);

--
-- Indexes for table `daily_checkins`
--
ALTER TABLE `daily_checkins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_checkin` (`user_id`,`checkin_date`);

--
-- Indexes for table `dosha_scores`
--
ALTER TABLE `dosha_scores`
  ADD PRIMARY KEY (`score_id`),
  ADD UNIQUE KEY `unique_checkin` (`user_id`,`checkin_date`);

--
-- Indexes for table `herbs`
--
ALTER TABLE `herbs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `recommendations`
--
ALTER TABLE `recommendations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `api_token` (`api_token`);

--
-- Indexes for table `user_profiles`
--
ALTER TABLE `user_profiles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `body_balance_scores`
--
ALTER TABLE `body_balance_scores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=77;

--
-- AUTO_INCREMENT for table `daily_checkins`
--
ALTER TABLE `daily_checkins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=156;

--
-- AUTO_INCREMENT for table `dosha_scores`
--
ALTER TABLE `dosha_scores`
  MODIFY `score_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;

--
-- AUTO_INCREMENT for table `herbs`
--
ALTER TABLE `herbs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `recommendations`
--
ALTER TABLE `recommendations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=136;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `user_profiles`
--
ALTER TABLE `user_profiles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_profiles`
--
ALTER TABLE `user_profiles`
  ADD CONSTRAINT `user_profiles_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
