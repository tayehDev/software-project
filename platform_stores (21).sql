-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jan 12, 2026 at 12:23 PM
-- Server version: 8.0.44-0ubuntu0.24.04.1
-- PHP Version: 8.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `platform_stores`
--

-- --------------------------------------------------------

--
-- Table structure for table `about_us`
--

CREATE TABLE `about_us` (
  `id` int NOT NULL,
  `topic` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `description` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `about_us`
--

INSERT INTO `about_us` (`id`, `topic`, `description`) VALUES
(1, 'Office', 'in Ramallah / city center');

-- --------------------------------------------------------

--
-- Table structure for table `area`
--

CREATE TABLE `area` (
  `id` int NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `is_canceled_row` tinyint NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `area`
--

INSERT INTO `area` (`id`, `name`, `is_canceled_row`) VALUES
(1, 'Salfit', 0),
(2, 'Qalqilya', 0),
(3, 'Tulkarm', 0),
(4, 'Bethlehem', 0),
(5, 'Ramalah', 0),
(6, 'Jericho', 0),
(7, 'Hebron', 0),
(8, 'Tobas', 0),
(9, 'Jenin', 0),
(10, 'nablus', 0);

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `id` int NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `sort` int DEFAULT NULL,
  `del` tinyint NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `name`, `sort`, `del`) VALUES
(1, 'Toys & Games', 3, 0),
(2, 'Luggages & Bags', 4, 0),
(3, 'Computer & Phone', 6, 0),
(4, 'Accessories', 7, 0),
(5, 'Watches', 5, 0),
(6, 'Education', 2, 0),
(7, 'Sport', 1, 0),
(8, 'Home Appliances', 2, 0);

-- --------------------------------------------------------

--
-- Table structure for table `change_password`
--

CREATE TABLE `change_password` (
  `id` int NOT NULL,
  `insert_datetime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `user_id` int DEFAULT NULL,
  `user_role_account` int DEFAULT NULL,
  `is_done` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `change_password`
--

INSERT INTO `change_password` (`id`, `insert_datetime`, `user_id`, `user_role_account`, `is_done`) VALUES
(1, '2025-09-23 11:07:50', 2, 2, 0),
(2, '2025-09-27 22:44:11', 2, 2, 0),
(3, '2025-11-27 22:44:32', 2, 2, 0),
(4, '2025-12-20 10:50:08', 3, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `chat_conjunction`
--

CREATE TABLE `chat_conjunction` (
  `id` int NOT NULL,
  `question` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci,
  `answer` text,
  `auto_chat_seller_user_id` int DEFAULT NULL COMMENT 'if NULL mean public chat ,,, if set (int) mean for seller "auto chat to customer"'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `chat_conjunction`
--

INSERT INTO `chat_conjunction` (`id`, `question`, `answer`, `auto_chat_seller_user_id`) VALUES
(1, 'How are you?', 'I’m doing great, thanks for asking! How about you?', NULL),
(2, 'Hello hihi', 'Hi there! Welcome to our community marketplace.', NULL),
(3, 'Good morning', 'Good morning! Hope your day starts with something amazing.', NULL),
(4, 'Good evening', 'Good evening! Are you browsing for something nice today?', NULL),
(5, 'Who are you?', 'I’m your virtual assistant here to help you explore and use the platform easily.', NULL),
(6, 'What is this website?', 'It’s a community market where people share and sell accessories, small gadgets, and creative products.', NULL),
(7, 'Can anyone sell here?', 'Yes! Anyone can register and post products following our simple seller policy.', NULL),
(8, 'How can I register?', 'Click “Sign Up,” fill in your basic info, and start exploring or selling right away.', NULL),
(9, 'How can I log in?', 'Click “Login” at the top of the page, enter your email and password, and you’re in.', NULL),
(10, 'I forgot my password', 'No problem! Go to the login page and click “Forgot Password” to reset it easily.', NULL),
(11, 'How can I recover my account?', 'Use the password recovery page or contact support if you can’t access your email.', NULL),
(12, 'Can I change my password?', 'Yes, go to “Account Settings” then “Security” to update your password.', NULL),
(13, 'How do I post a new product?', 'Go to your dashboard, click “Add Product,” and fill in the product details with photos.', NULL),
(14, 'Is posting products free?', 'Yes, posting products is free for all members!', NULL),
(15, 'How can I edit my product?', 'From your dashboard, open the product and click “Edit.” You can update name, price, or image.', NULL),
(16, 'How can I delete my product?', 'In your dashboard, select the product and choose “Delete” from the options menu.', NULL),
(17, 'Can I contact a seller?', 'Yes, each seller profile includes a “Message Seller” button.', NULL),
(18, 'How do I reply to a message?', 'Go to your inbox and click on the message thread to reply.', NULL),
(19, 'How can I follow a seller?', 'Just click the “Follow” button on their profile to get updates on new items.', NULL),
(20, 'are you speak arabic', 'yes i can speak arabic', NULL),
(21, 'Where can I see my favorites?', 'Your saved items appear under “My Favorites” in your profile.', NULL),
(22, 'Can I share a product?', 'Yes, every product page has share buttons for social media or direct links.', NULL),
(23, 'Is there a mobile version?', 'Yes! The site works perfectly on mobile browsers.', NULL),
(24, 'Do you have a mobile app?', 'Our app is under development, but the mobile website has all main features.', NULL),
(25, 'Can I comment on products?', 'Yes, you can leave comments or reviews on product pages.', NULL),
(26, 'How can I report an issue?', 'Use the “Report” button on the product or contact support.', NULL),
(27, 'What happens if a seller doesn’t reply?', 'You can send a reminder, or report inactivity to our support team.', NULL),
(28, 'Can I see who viewed my product?', 'Yes, your dashboard includes a “Views” counter for each product.', NULL),
(29, 'How can I boost my product visibility?', 'Add good pictures, clear titles, and detailed descriptions.', NULL),
(30, 'Can I promote my product?', 'Yes, you can request paid promotion for more exposure.', NULL),
(31, 'What kind of products are allowed?', 'We allow light items like accessories, handmade goods, and small electronics.', NULL),
(32, 'Are second-hand items allowed?', 'Yes, as long as they are clean and in good condition.', NULL),
(33, 'How do I contact support?', 'Use the “Help” section or chat with us here anytime.', NULL),
(34, 'Can I change my email address?', 'Yes, in “Account Settings,” go to the profile tab and update your email.', NULL),
(35, 'Can I change my profile picture?', 'Yes, click on your avatar and upload a new image.', NULL),
(36, 'Is my information safe?', 'Yes, we protect your data with secure encryption methods.', NULL),
(37, 'Can I deactivate my account?', 'Yes, you can temporarily deactivate or permanently delete it from settings.', NULL),
(38, 'How do I update my username?', 'Usernames can be changed once from your profile settings.', NULL),
(39, 'Can I block a user?', 'Yes, open their profile and choose “Block user.”', NULL),
(40, 'Can I unblock someone?', 'Yes, go to “Privacy Settings” and view your blocked list.', NULL),
(41, 'How can I reset my password?', 'Click “Forgot Password” on the login page to receive a reset link by email.', NULL),
(42, 'Can I search for sellers?', 'Yes, use the search bar and select “Sellers” from the dropdown.', NULL),
(43, 'Can I search by category?', 'Yes, you can browse products by category using the menu at the top.', NULL),
(44, 'What categories are available?', 'We have categories like Accessories, Home Decor, Art, Beauty, and more.', NULL),
(45, 'How can I change my language?', 'Click the language icon at the top and choose your preferred language.', NULL),
(46, 'Do you offer delivery?', 'We don’t deliver directly; sellers arrange delivery or meet-ups.', NULL),
(47, 'How do I pay for a product?', 'Payments are arranged directly between buyers and sellers.', NULL),
(48, 'Is there buyer protection?', 'We recommend meeting in safe public places or using trusted delivery options.', NULL),
(49, 'Can I sell handmade items?', 'Yes! Handmade and creative products are welcome.', NULL),
(50, 'Can I upload videos of my product?', 'Currently, only images are supported, but video uploads are coming soon.', NULL),
(51, 'What should I write in my product title?', 'Be clear and short — include the main item name and type.', NULL),
(52, 'How can I attract more buyers?', 'Upload high-quality photos, write detailed descriptions, and respond fast to messages.', NULL),
(53, 'Do you charge fees for selling?', 'No fees are charged at the moment for regular listings.', NULL),
(54, 'Can I see my sales statistics?', 'Yes, your dashboard shows total views and messages received.', NULL),
(55, 'Can I sort products by price?', 'Yes, you can filter by price, category, or newest listings.', NULL),
(56, 'How can I log out?', 'Click your profile icon and choose “Logout.”', NULL),
(57, 'How do I change my phone number?', 'You can update your contact number in your account settings.', NULL),
(58, 'How do I upload product photos?', 'Click “Add Product,” then choose “Upload Images.”', NULL),
(59, 'What photo size is recommended?', 'Use square images around 800x800 pixels for best display.', NULL),
(60, 'What are trending products?', 'Trending items are those with high likes or views in a short time.', NULL),
(61, 'How can I become a verified seller?', 'Upload your ID or business license under “Seller Verification.”', NULL),
(62, 'Do you support small businesses?', 'Yes, our platform is built to help small sellers grow online.', NULL),
(63, 'Can I promote my store?', 'Yes, we offer advertising packages for verified sellers.', NULL),
(64, 'What are your working hours?', 'We’re available 24/7 online, and support replies from 9 AM to 9 PM.', NULL),
(65, 'Can I sell without registration?', 'No, you must create an account to post or message sellers.', NULL),
(66, 'Can I send product offers?', 'Yes, you can negotiate directly in the chat with the seller.', NULL),
(67, 'Can I upload multiple photos?', 'Yes, you can upload photo per product.', NULL),
(68, 'Do you allow bulk sales?', 'Yes, as long as each listing represents the actual product.', NULL),
(69, 'Is this platform for companies or individuals?', 'Both — individuals and small businesses are welcome.', NULL),
(70, 'Can I list free items?', 'Yes, you can list giveaways or free accessories if you wish.', NULL),
(71, 'Do you review posts before publishing?', 'Yes, all new posts are checked briefly to ensure they follow our rules.', NULL),
(72, 'What items are not allowed?', 'Prohibited items include dangerous goods, fake brands, and restricted products.', NULL),
(73, 'Can I get notifications?', 'Yes, you’ll receive notifications for likes, messages, and new followers.', NULL),
(74, 'Can I turn off notifications?', 'Yes, you can manage them in your account settings.', NULL),
(75, 'Can I change currency display?', 'Yes, currency updates automatically based on your region.', NULL),
(76, 'Can I search nearby sellers?', 'Yes, if you enable location access, nearby results will show first.', NULL),
(77, 'Can I post offers or discounts?', 'Yes, you can mark your items as “On Sale.”', NULL),
(78, 'Can I share my store link?', 'Yes, your store has a unique URL you can share anywhere.', NULL),
(79, 'Can I send images in chat?', 'Not yet, but this feature will be added soon.', NULL),
(80, 'Can I pin my favorite sellers?', 'Yes, from your following list, click the pin icon.', NULL),
(81, 'Do sellers get rated?', 'Yes, buyers can rate and leave feedback on sellers.', NULL),
(82, 'Can I reply to feedback?', 'Yes, sellers can reply to buyer feedback publicly.', NULL),
(83, 'What if I receive spam?', 'You can block the user or report them immediately.', NULL),
(84, 'How do I change my notification sound?', 'You can manage sound preferences in the settings page.', NULL),
(85, 'Can I disable email notifications?', 'Yes, go to notifications settings and turn off “Email updates.”', NULL),
(86, 'How do I find new sellers?', 'Check the “New Sellers” section on the homepage.', NULL),
(87, 'Do you have a referral program?', 'Yes, invite friends and earn bonus credits for each signup.', NULL),
(88, 'Can I filter by new products?', 'Yes, click “Newest” in the product filter options.', NULL),
(89, 'Can I repost an expired item?', 'Yes, go to your old post and click “Repost.”', NULL),
(90, 'Can I save searches?', 'Yes, you can save your search terms to reuse later.', NULL),
(91, 'How can I improve my store page?', 'Add a short bio, upload a clear logo, and post consistently.', NULL),
(92, 'Can I see recently viewed items?', 'Yes, your recent views appear under your profile dashboard.', NULL),
(93, 'Do I need to verify my email?', 'Yes, email verification is required for account safety.', NULL),
(94, 'How do I contact admin?', 'Use the contact form at the bottom of the site.', NULL),
(95, 'Can I translate the site?', 'Yes, language options are available at the top of every page.', NULL),
(96, 'Can I get seller tips?', 'Yes, check our “Tips for Sellers” section for useful advice.', NULL),
(97, 'What should I do if I see fake products?', 'Click “Report” and select “Fake product.” Our team will review it.', NULL),
(98, 'Do you have social media accounts?', 'Yes, follow us on Facebook, Instagram, and TikTok for updates.', NULL),
(99, 'Can I join as a shop owner?', 'Yes, register as a business account during signup.', NULL),
(100, 'Can I sell seasonal items?', 'Yes, you can post seasonal or limited-time products anytime.', NULL),
(101, 'Can I add product tags?', 'Yes, tags help users find your product faster.', NULL),
(102, 'Do you offer seller badges?', 'Yes, verified and active sellers get special badges.', NULL),
(103, 'What if I forget my username?', 'You can recover it by contacting support or checking your email.', NULL),
(104, 'How can I share feedback about the platform?', 'We appreciate feedback! Use the feedback form in settings.', NULL),
(105, 'Can I change my theme color?', 'Currently, the site uses a default theme, but customization is coming soon.', NULL),
(106, 'Can I join as a buyer only?', 'Yes, you can browse, like, and chat without posting products.', NULL),
(107, 'Do you support multiple currencies?', 'Prices are shown in your local currency automatically.', NULL),
(108, 'Can I message buyers?', 'Yes, sellers can reply to buyer inquiries directly.', NULL),
(109, 'Can I upload my business logo?', 'Yes, business sellers can upload a logo under “Store Settings.”', NULL),
(110, 'Can I hide my profile?', 'Yes, you can set your profile visibility to private.', NULL),
(111, 'Is there a help center?', 'Yes, you can access FAQs and guides under “Help Center.”', NULL),
(112, 'Thank you', 'You’re very welcome! Always here to help. ', NULL),
(113, 'How can I become a top seller?', 'Focus on posting high-quality items and responding quickly to messages.', NULL),
(114, 'Can I offer discounts to loyal customers?', 'Yes, you can create special deals for returning buyers.', NULL),
(115, 'How do I report inappropriate content?', 'Click the “Report” button on the product or user profile.', NULL),
(116, 'Can I schedule my product posting?', 'Currently, posting is manual, but we plan to add scheduling soon.', NULL),
(117, 'Can I update product price anytime?', 'Yes, go to your dashboard, edit the product, and change the price.', NULL),
(118, 'Can I upload multiple categories per product?', 'Each product can belong to one primary category, but you can use tags for more details.', NULL),
(119, 'How do I respond to buyer inquiries?', 'Open the message thread in your inbox and reply directly.', NULL),
(120, 'Can I use emojis in my product description?', 'Yes, emojis are supported and can make descriptions more engaging.', NULL),
(121, 'Can I hide sold products?', 'Yes, mark them as sold to hide them from the public view.', NULL),
(122, 'How do I see analytics for my products?', 'Your dashboard includes views, likes, and messages per item.', NULL),
(123, 'Can I add promotional banners?', 'Yes, verified sellers can request banners for their store page.', NULL),
(124, 'How do I verify my email?', 'Check your inbox after signing up and click the verification link.', NULL),
(125, 'Can I reset my security questions?', 'Yes, go to security settings to update them.', NULL),
(126, 'How do I delete my account permanently?', 'Contact support and confirm deletion via email.', NULL),
(127, 'Can I change my store name?', 'Yes, store names can be updated once under “Store Settings.”', NULL),
(128, 'Do you have seasonal promotions?', 'Yes, check the homepage for seasonal offers and deals.', NULL),
(129, 'Can I create a bundle product?', 'Currently, only individual products are supported, but bundles are coming soon.', NULL),
(130, 'Can I promote products on social media?', 'Yes, share the product link or use the share buttons available.', NULL),
(131, 'Can I tag products in comments?', 'Not yet, but this feature is planned for future updates.', NULL),
(132, 'Can I block a buyer?', 'Yes, from the user profile, click “Block” to prevent messages.', NULL),
(133, 'How do I report spam messages?', 'Click “Report” in the message thread and select spam.', NULL),
(134, 'Can I receive notifications for new messages?', 'Yes, notifications are enabled by default.', NULL),
(135, 'Can I change my profile username?', 'Yes, you can update it once in profile settings.', NULL),
(136, 'How can I edit my bio?', 'Go to your profile and click “Edit Bio.”', NULL),
(137, 'Can I connect multiple accounts?', 'No, each person can register only one account.', NULL),
(138, 'Can I follow categories?', 'Yes, you can follow a category to receive updates on new products.', NULL),
(139, 'How can I mark a product as featured?', 'Verified sellers can request a featured badge for selected products.', NULL),
(140, 'Can I create a store logo?', 'Yes, upload your logo under “Store Settings.”', NULL),
(141, 'Can I customize my store page?', 'Basic customizations like logo and bio are supported; full customization is coming.', NULL),
(142, 'Do I get email notifications for likes?', 'Yes, by default you receive notifications for likes and messages.', NULL),
(143, 'Can I turn off email notifications?', 'Yes, go to notification settings and disable email alerts.', NULL),
(144, 'Can I turn off in-app notifications?', 'Yes, adjust the settings in your profile.', NULL),
(145, 'How do I contact a seller anonymously?', 'Currently, all messages require your account identity.', NULL),
(146, 'Can I create a wishlist?', 'Yes, you can add items to your wishlist for easy access later.', NULL),
(147, 'Can I share my wishlist?', 'Yes, copy the link from your profile to share it.', NULL),
(148, 'Can I like multiple products at once?', 'Yes, click the heart icon on each product to like them.', NULL),
(149, 'Can I see recently liked items?', 'Yes, they appear under “Favorites” in your profile.', NULL),
(150, 'Do you have a loyalty program?', 'Yes, frequent buyers can earn credits and badges.', NULL),
(151, 'How can I earn loyalty points?', 'Engage with products, make purchases, and refer friends.', NULL),
(152, 'Can I redeem points for discounts?', 'Yes, points can be applied at checkout for discounts.', NULL),
(153, 'Do you offer gift wrapping?', 'Currently, gift wrapping is handled by individual sellers.', NULL),
(154, 'Can I schedule a delivery with the seller?', 'Yes, coordinate with the seller through the chat.', NULL),
(155, 'Can I cancel a message sent by mistake?', 'No, messages cannot be deleted after sending.', NULL),
(156, 'How do I report a product listing?', 'Click “Report” on the product page and select the reason.', NULL),
(157, 'Can I suggest new features?', 'Yes, submit ideas through the “Feedback” section.', NULL),
(158, 'Can I see trending products?', 'Yes, trending products appear on the homepage and in the category pages.', NULL),
(159, 'How can I contact support quickly?', 'Use the live chat option or contact form under “Help.”', NULL),
(160, 'Can I follow multiple sellers?', 'Yes, follow as many sellers as you like to get updates.', NULL),
(161, 'Can I message multiple sellers at once?', 'No, messaging is one-to-one currently.', NULL),
(162, 'Do you have verified sellers?', 'Yes, verified sellers have a badge on their profile.', NULL),
(163, 'How do I become verified?', 'Upload your ID or business license under “Seller Verification.”', NULL),
(164, 'Can I sell digital products?', 'Currently, only physical products are allowed.', NULL),
(165, 'Can I post services instead of products?', 'No, the platform focuses on products, not services.', NULL),
(166, 'Can I highlight my products?', 'Yes, some paid options for highlighting are available.', NULL),
(167, 'Can I pin products on my profile?', 'Yes, you can pin up to 3 products on your store page.', NULL),
(168, 'Can I use hashtags in descriptions?', 'Yes, hashtags help users discover your items.', NULL),
(169, 'Can I create product categories?', 'Sellers can add tags, but main categories are set by the platform.', NULL),
(170, 'Do you have seller analytics?', 'Yes, dashboards show product performance and engagement.', NULL),
(171, 'Can I hide my online status?', 'Yes, in settings you can appear offline.', NULL),
(172, 'How do I change my notification preferences?', 'Go to “Settings” and adjust notification options.', NULL),
(173, 'Can I block users from messaging me?', 'Yes, use the “Block User” feature in the message thread.', NULL),
(174, 'Can I delete my chat history?', 'Yes, clear your messages from the inbox.', NULL),
(175, 'Can I search by product condition?', 'Yes, filter products by new or used items.', NULL),
(176, 'Can I search by price range?', 'Yes, use the price filter in each category.', NULL),
(177, 'Can I search by location?', 'Yes, enable location access to see nearby sellers.', NULL),
(178, 'Can I search by rating?', 'Yes, filter products or sellers by rating score.', NULL),
(179, 'Can I leave reviews for sellers?', 'Yes, leave feedback after purchasing an item.', NULL),
(180, 'Can I edit my review?', 'Yes, you can update your review within 7 days.', NULL),
(181, 'Can I see my review history?', 'Yes, all reviews you left are listed in your profile.', NULL),
(182, 'Can I report fake reviews?', 'Yes, use the report button next to the review.', NULL),
(183, 'Can I upload multiple images at once?', 'Yes, upload up to 6 images per product.', NULL),
(184, 'Can I edit image captions?', 'Yes, add or update captions when editing the product.', NULL),
(185, 'Do you allow watermarked images?', 'Yes, but they should not obscure the product.', NULL),
(186, 'Can I link external websites?', 'Yes, include links in the product description if relevant.', NULL),
(187, 'Can I create product bundles?', 'Not yet, but this feature is planned.', NULL),
(188, 'Can I see my account activity?', 'Yes, all actions are logged under “Activity History.”', NULL),
(189, 'Can I set product availability?', 'Yes, indicate if an item is in stock or out of stock.', NULL),
(190, 'Can I change product visibility?', 'Yes, you can hide or show items anytime.', NULL),
(191, 'Can I enable product notifications?', 'Yes, buyers can opt-in to alerts for specific products.', NULL),
(192, 'Can I promote my products on the homepage?', 'Paid promotions are available for verified sellers.', NULL),
(193, 'Do you provide seller tips?', 'Yes, check the “Seller Tips” section for advice.', NULL),
(194, 'Can I send bulk messages?', 'Currently, messaging is limited to individual chats.', NULL),
(195, 'Can I set automatic replies?', 'No, auto-replies are not supported yet.', NULL),
(196, 'Can I upload product manuals?', 'Yes, you can attach PDFs to product listings.', NULL),
(197, 'Can I mark a product as limited edition?', 'Yes, indicate this in the product description.', NULL),
(198, 'Do you support barcodes?', 'Yes, you can include barcodes in product details.', NULL),
(199, 'Can I export my product list?', 'Not yet, but we plan to add export features.', NULL),
(200, 'Can I integrate with social media stores?', 'Currently, direct integration is not available.', NULL),
(201, 'How do I get verified as a buyer?', 'Buyer verification is optional but recommended for trust.', NULL),
(202, 'Can I tag sellers in posts?', 'No, tagging other users is not supported.', NULL),
(203, 'Can I see my purchase history?', 'Yes, all your purchases appear in your account dashboard.', NULL),
(204, 'Can I dispute a transaction?', 'Yes, contact support for assistance with disputes.', NULL),
(205, 'Can I schedule messages?', 'Not currently, all messages are sent immediately.', NULL),
(206, 'Can I use this platform internationally?', 'Yes, users from multiple countries can join.', NULL),
(207, 'Can I save draft products?', 'Yes, save as draft before publishing.', NULL),
(208, 'Can I preview products before posting?', 'Yes, a preview option is available on the posting page.', NULL),
(209, 'Can I categorize multiple items at once?', 'No, each product needs individual category selection.', NULL),
(210, 'Can I share my profile link?', 'Yes, your profile has a unique URL you can share.', NULL),
(211, 'Can I post items for auctions?', 'No, auction features are not available yet.', NULL),
(212, 'Can I set product expiration?', 'Yes, indicate availability dates in product details.', NULL),
(213, 'Do you support coupons?', 'Yes, sellers can create discount codes for their products.', NULL),
(214, 'Can I schedule product discounts?', 'Not yet, discounts must be applied manually.', NULL),
(215, 'Do you have a community forum?', 'Currently, community discussions are through comments and messages.', NULL),
(216, 'Can I save searches for later?', 'Yes, use the “Save Search” button to reuse filters.', NULL),
(217, 'Can I hide my wishlist?', 'Yes, wishlist privacy can be adjusted in settings.', NULL),
(218, 'Can I get alerts for product updates?', 'Yes, enable notifications to stay updated.', NULL),
(219, 'Can I follow trends?', 'Yes, check the “Trending” section regularly.', NULL),
(220, 'Can I report abusive users?', 'Yes, click “Report” on their profile or message.', NULL),
(221, 'Can I receive weekly summaries?', 'Yes, enable email summaries in notification settings.', NULL),
(222, 'Can I send gifts?', 'Yes, coordinate with the seller for delivery and payment.', NULL),
(223, 'Can I upload product videos?', 'Not yet, image uploads are supported.', NULL),
(224, 'Can I duplicate a product listing?', 'Yes, use the “Duplicate” option in your dashboard.', NULL),
(225, 'Can I see buyer locations?', 'Yes, only general location is visible to sellers.', NULL),
(226, 'Can I set minimum purchase quantity?', 'Yes, indicate this in product details.', NULL),
(227, 'Can I restrict sales to certain regions?', 'Yes, select regions when posting products.', NULL),
(228, 'Can I add custom tags?', 'Yes, tags help buyers find your products.', NULL),
(229, 'Can I edit product after sale?', 'Once sold, the product cannot be edited.', NULL),
(230, 'Do you offer tutorials for sellers?', 'Yes, check the “Help Center” for tutorials.', NULL),
(231, 'Can I collaborate with other sellers?', 'Yes, you can create joint product posts if desired.', NULL),
(232, 'Can I translate product descriptions?', 'Yes, you can add descriptions in multiple languages.', NULL),
(233, 'Can I import products from a CSV?', 'Not yet, but this feature is planned.', NULL),
(234, 'Can I see buyer messages in real-time?', 'Yes, the chat updates instantly.', NULL),
(235, 'Can I hide my product prices?', 'No, prices must be visible to buyers.', NULL),
(236, 'Can I report counterfeit items?', 'Yes, click “Report” and select counterfeit.', NULL),
(237, 'Can I send newsletters?', 'Only admins can send platform-wide newsletters.', NULL),
(238, 'Can I upload GIFs?', 'No, only static images are allowed.', NULL),
(239, 'Can I ask for product reviews?', 'Yes, politely request buyers to leave feedback.', NULL),
(240, 'Can I get alerts when followers post?', 'Yes, enable notifications for followed users.', NULL),
(241, 'Can I view my store traffic?', 'Yes, your dashboard shows views and engagement stats.', NULL),
(242, 'Can I offer pre-orders?', 'Yes, mention pre-order availability in the description.', NULL),
(243, 'Can I sell internationally?', 'Yes, specify shipping options in your product details.', NULL),
(244, 'Can I delete multiple products at once?', 'Yes, use the batch action in your dashboard.', NULL),
(245, 'Can I change product images later?', 'Yes, edit the product and replace images.', NULL),
(246, 'Can I add seasonal tags?', 'Yes, include tags like “Summer Collection” to attract buyers.', NULL),
(247, 'Can I follow product trends?', 'Yes, check the “Trending” page regularly.', NULL),
(248, 'Can I enable product notifications?', 'Yes, buyers can subscribe to updates for each product.', NULL),
(249, 'Can I organize products by collections?', 'Yes, use tags and categories to group similar items.', NULL),
(250, 'Can I get help writing product descriptions?', 'Yes, our “Tips for Sellers” guide includes examples.', NULL),
(251, 'كيف حالك؟', 'أنا بخير، شكرًا لسؤالك! ماذا عنك؟', NULL),
(252, 'مرحبا', 'أهلاً بك! مرحبًا بك في سوق المجتمع الخاص بنا.', NULL),
(253, 'صباح الخير', 'صباح الخير! نتمنى لك يومًا رائعًا مليئًا بالمفاجآت الجميلة.', NULL),
(254, 'مساء الخير', 'مساء الخير! هل تتصفح بعض المنتجات اليوم؟', NULL),
(255, 'من أنت؟', 'أنا مساعدك الافتراضي هنا لمساعدتك في استكشاف واستخدام المنصة بسهولة.', NULL),
(256, 'ما هذا الموقع؟', 'إنه سوق مجتمعي حيث يمكن للناس عرض وبيع الإكسسوارات والأدوات الصغيرة والمنتجات الإبداعية.', NULL),
(257, 'هل يمكن لأي شخص البيع هنا؟', 'نعم! يمكن لأي شخص التسجيل ونشر المنتجات وفق سياسة البائع البسيطة لدينا.', NULL),
(258, 'كيف يمكنني التسجيل؟', 'انقر على \"تسجيل\" وأدخل معلوماتك الأساسية، وابدأ بالتصفح أو البيع فورًا.', NULL),
(259, 'كيف يمكنني تسجيل الدخول؟', 'انقر على \"تسجيل الدخول\" في أعلى الصفحة وأدخل البريد وكلمة المرور.', NULL),
(260, 'نسيت كلمة المرور', 'لا مشكلة! اذهب إلى صفحة تسجيل الدخول وانقر على \"نسيت كلمة المرور\" لإعادة ضبطها بسهولة.', NULL),
(261, 'كيف أستعيد حسابي؟', 'استخدم صفحة استعادة كلمة المرور أو تواصل مع الدعم إذا لم تتمكن من الوصول إلى بريدك.', NULL),
(262, 'هل يمكنني تغيير كلمة المرور؟', 'نعم، اذهب إلى \"إعدادات الحساب\" ثم \"الأمان\" لتحديث كلمة المرور.', NULL),
(263, 'كيف أنشر منتج جديد؟', 'اذهب إلى لوحة التحكم، انقر على \"إضافة منتج\"، واملأ تفاصيل المنتج مع الصور.', NULL),
(264, 'هل نشر المنتجات مجاني؟', 'نعم، نشر المنتجات مجاني لجميع الأعضاء!', NULL),
(265, 'كيف يمكنني تعديل منتجي؟', 'من لوحة التحكم، افتح المنتج وانقر على \"تعديل\". يمكنك تحديث الاسم، السعر، أو الصورة.', NULL),
(266, 'كيف يمكنني حذف منتجي؟', 'في لوحة التحكم، اختر المنتج وانقر على \"حذف\" من القائمة.', NULL),
(267, 'هل يمكنني التواصل مع البائع؟', 'نعم، كل بروفايل بائع يحتوي على زر \"مراسلة البائع\".', NULL),
(268, 'كيف أرد على رسالة؟', 'اذهب إلى صندوق الوارد وانقر على المحادثة للرد مباشرة.', NULL),
(269, 'كيف أتابع بائع؟', 'انقر على زر \"متابعة\" في ملفه الشخصي لتصلك تحديثات المنتجات الجديدة.', NULL),
(270, 'هل يمكنني الإعجاب بمنتج؟', 'نعم! انقر على رمز القلب لإضافته إلى المفضلة.', NULL),
(271, 'أين أجد منتجاتي المفضلة؟', 'تظهر العناصر المحفوظة في \"المفضلة\" في ملفك الشخصي.', NULL),
(272, 'هل يمكنني مشاركة منتج؟', 'نعم، تحتوي صفحة كل منتج على أزرار المشاركة على وسائل التواصل أو روابط مباشرة.', NULL),
(273, 'هل هناك نسخة للهواتف؟', 'نعم! الموقع يعمل بشكل كامل على متصفحات الهواتف.', NULL),
(274, 'هل لديكم تطبيق جوال؟', 'التطبيق قيد التطوير، لكن نسخة الموقع للهواتف تحتوي على جميع الميزات الرئيسية.', NULL),
(275, 'هل يمكنني التعليق على المنتجات؟', 'نعم، يمكنك ترك تعليق أو تقييم على صفحات المنتجات.', NULL),
(276, 'كيف أبلغ عن مشكلة؟', 'استخدم زر \"الإبلاغ\" على المنتج أو تواصل مع الدعم.', NULL),
(277, 'ماذا يحدث إذا لم يرد البائع؟', 'يمكنك إرسال تذكير، أو الإبلاغ عن عدم النشاط لفريق الدعم.', NULL),
(278, 'هل أستطيع رؤية من شاهد منتجي؟', 'نعم، لوحة التحكم تحتوي على عداد \"المشاهدات\" لكل منتج.', NULL),
(279, 'كيف أجعل منتجي أكثر ظهورًا؟', 'استخدم صور واضحة، عناوين مفهومة، ووصف تفصيلي.', NULL),
(280, 'هل يمكنني الترويج لمنتجي؟', 'نعم، يمكنك طلب الترويج المدفوع لمزيد من الظهور.', NULL),
(281, 'ما نوع المنتجات المسموح بها؟', 'نسمح بالمنتجات الخفيفة مثل الإكسسوارات، الديكور المنزلي، والإلكترونيات الصغيرة.', NULL),
(282, 'هل يسمح بالمنتجات المستعملة؟', 'نعم، طالما نظيفة وفي حالة جيدة.', NULL),
(283, 'كيف أتواصل مع الدعم؟', 'استخدم قسم \"المساعدة\" أو تحدث معنا هنا في أي وقت.', NULL),
(284, 'هل يمكنني تغيير بريدي الإلكتروني؟', 'نعم، في \"إعدادات الحساب\"، يمكن تحديث البريد الإلكتروني.', NULL),
(285, 'هل يمكنني تغيير صورة الملف الشخصي؟', 'نعم، انقر على الصورة الشخصية وحمّل صورة جديدة.', NULL),
(286, 'هل معلوماتي آمنة؟', 'نعم، نستخدم طرق تشفير آمنة لحماية بياناتك.', NULL),
(287, 'هل يمكنني تعطيل حسابي؟', 'نعم، يمكنك تعطيله مؤقتًا أو حذفه نهائيًا من الإعدادات.', NULL),
(288, 'كيف أغير اسم المستخدم؟', 'يمكن تغيير اسم المستخدم مرة واحدة من إعدادات الملف الشخصي.', NULL),
(289, 'هل يمكنني حظر مستخدم؟', 'نعم، افتح ملفه الشخصي واختر \"حظر المستخدم\".', NULL),
(290, 'هل يمكنني إلغاء الحظر؟', 'نعم، اذهب إلى \"إعدادات الخصوصية\" وانظر قائمة المحظورين.', NULL),
(291, 'كيف أعيد ضبط كلمة المرور؟', 'انقر على \"نسيت كلمة المرور\" في صفحة تسجيل الدخول لتصلك رسالة إعادة الضبط.', NULL),
(292, 'هل يمكنني البحث عن البائعين؟', 'نعم، استخدم شريط البحث واختر \"البائعون\" من القائمة.', NULL),
(293, 'هل يمكنني البحث حسب الفئة؟', 'نعم، يمكنك تصفح المنتجات حسب الفئة من القائمة العلوية.', NULL),
(294, 'ما الفئات المتاحة؟', 'لدينا فئات مثل الإكسسوارات، الديكور، الفن، الجمال، والمزيد.', NULL),
(295, 'كيف أغير اللغة؟', 'انقر على أيقونة اللغة أعلى الصفحة واختر لغتك المفضلة.', NULL),
(296, 'هل تقدمون خدمة التوصيل؟', 'لا، البائعون يتفقون على التوصيل أو اللقاءات الشخصية.', NULL),
(297, 'كيف أدفع مقابل منتج؟', 'تتم عمليات الدفع مباشرة بين المشتري والبائع.', NULL),
(298, 'هل هناك حماية للمشتري؟', 'ننصح باللقاء في أماكن عامة آمنة أو استخدام خيارات توصيل موثوقة.', NULL),
(299, 'هل يمكنني بيع منتجات يدوية؟', 'نعم! المنتجات اليدوية والإبداعية مرحب بها.', NULL),
(300, 'هل يمكنني رفع فيديو للمنتج؟', 'حاليًا، فقط الصور مدعومة، لكن الفيديو قادم قريبًا.', NULL),
(301, 'ما الذي يجب كتابته في عنوان المنتج؟', 'كن واضحًا ومختصرًا — ضع اسم المنتج ونوعه.', NULL),
(302, 'كيف أجذب المشترين أكثر؟', 'استخدم صور عالية الجودة، وصف مفصل، ورد بسرعة على الرسائل.', NULL),
(303, 'هل تفرضون رسوم على البيع؟', 'لا توجد رسوم على القوائم العادية حالياً.', NULL),
(304, 'هل يمكنني رؤية إحصائيات المبيعات؟', 'نعم، لوحة التحكم تعرض إجمالي المشاهدات والرسائل المستلمة.', NULL),
(305, 'هل يمكنني ترتيب المنتجات حسب السعر؟', 'نعم، يمكنك التصفية حسب السعر، الفئة، أو الأحدث.', NULL),
(306, 'كيف أسجل الخروج؟', 'انقر على أيقونة الملف الشخصي واختر \"تسجيل الخروج\".', NULL),
(307, 'كيف أغير رقم هاتفي؟', 'يمكنك تحديث رقمك في إعدادات الحساب.', NULL),
(308, 'كيف أرفع صور المنتج؟', 'انقر على \"إضافة منتج\"، ثم اختر \"رفع الصور\".', NULL),
(309, 'ما حجم الصورة الموصى به؟', 'استخدم صور مربعة بحجم حوالي 800x800 بكسل لأفضل عرض.', NULL),
(310, 'ما المنتجات الرائجة؟', 'المنتجات الرائجة هي التي تحصل على إعجابات ومشاهدات كثيرة خلال وقت قصير.', NULL),
(311, 'كيف أصبح بائعًا موثقًا؟', 'قم برفع هويتك أو رخصة عملك تحت \"توثيق البائع\".', NULL),
(312, 'هل تدعمون الشركات الصغيرة؟', 'نعم، منصتنا مخصصة لمساعدة البائعين الصغار على النمو.', NULL),
(313, 'هل يمكنني الترويج لمتجري؟', 'نعم، نوفر حزم إعلانية للبائعين الموثقين.', NULL),
(314, 'ما هي ساعات العمل؟', 'نحن متاحون 24/7 عبر الإنترنت، والردود من الدعم من 9 صباحًا حتى 9 مساءً.', NULL),
(315, 'هل يمكنني البيع بدون تسجيل؟', 'لا، يجب إنشاء حساب لنشر المنتجات أو مراسلة البائعين.', NULL),
(316, 'هل يمكنني إرسال عروض للمنتجات؟', 'نعم، يمكنك التفاوض مباشرة في المحادثة مع البائع.', NULL),
(317, 'هل يمكنني رفع عدة صور؟', 'نعم، يمكنك رفع حتى 6 صور لكل منتج.', NULL),
(318, 'هل تسمحون بالمبيعات بالجملة؟', 'نعم، طالما أن كل قائمة تمثل المنتج الفعلي.', NULL),
(319, 'هل هذه المنصة للأفراد أو الشركات؟', 'كلاهما — الأفراد والشركات الصغيرة مرحب بهم.', NULL),
(320, 'هل يمكنني نشر منتجات مجانية؟', 'نعم، يمكنك نشر الهدايا أو الإكسسوارات المجانية إذا رغبت.', NULL),
(321, 'هل تراجعون المنشورات قبل النشر؟', 'نعم، جميع المنشورات الجديدة يتم مراجعتها سريعًا للتأكد من الالتزام بالقواعد.', NULL),
(322, 'ما المنتجات غير المسموح بها؟', 'المنتجات المحظورة تشمل السلع الخطرة، العلامات التجارية المزيفة، والمنتجات المقيدة.', NULL),
(323, 'هل يمكنني تلقي الإشعارات؟', 'نعم، ستتلقى إشعارات للإعجابات والرسائل والمتابعين الجدد.', NULL),
(324, 'هل يمكنني إيقاف الإشعارات؟', 'نعم، يمكنك التحكم بها في إعدادات الحساب.', NULL),
(325, 'هل تتكلم اللغة العربية', 'نعم يمكنني التحدث بالعربية والانجليزية', NULL),
(326, 'هل يمكنني البحث عن البائعين القريبين؟', 'نعم، إذا فعلت الموقع ستظهر النتائج القريبة أولاً.', NULL),
(327, 'هل يمكنني نشر عروض أو تخفيضات؟', 'نعم، يمكنك وضع المنتجات على أنها \"خصم\".', NULL),
(328, 'هل يمكنني مشاركة رابط متجري؟', 'نعم، لكل متجر رابط فريد يمكنك مشاركته.', NULL),
(329, 'هل يمكنني إرسال صور في المحادثة؟', 'ليس بعد، لكن هذه الميزة ستضاف قريبًا.', NULL),
(330, 'هل يمكنني تثبيت البائعين المفضلين؟', 'نعم، من قائمة المتابعين انقر على أيقونة التثبيت.', NULL),
(331, 'هل يحصل البائعون على تقييمات؟', 'نعم، يمكن للمشترين تقييم البائعين وترك تعليقات.', NULL),
(332, 'هل يمكنني الرد على التعليقات؟', 'نعم، يمكن للبائعين الرد على التعليقات بشكل علني.', NULL),
(333, 'ماذا أفعل إذا تلقيت رسائل مزعجة؟', 'يمكنك حظر المستخدم أو الإبلاغ عنه فورًا.', NULL),
(334, 'كيف أغير صوت الإشعارات؟', 'يمكنك ضبط الصوت من صفحة الإعدادات.', NULL),
(335, 'هل يمكنني تعطيل إشعارات البريد الإلكتروني؟', 'نعم، من إعدادات الإشعارات يمكنك إيقاف البريد الإلكتروني.', NULL),
(336, 'هل يمكنني ربط الحساب بحساب التواصل الاجتماعي؟', 'لا، هذه الميزة غير متوفرة', NULL),
(337, 'هل يمكنني استخدام الموقع من الهاتف؟', 'نعم، الموقع متوافق مع جميع الهواتف الذكية.', NULL),
(338, 'هل يمكنني البحث عن منتجات حسب اللون؟', 'نعم، استخدم الفلاتر لتحديد اللون المطلوب.', NULL),
(339, 'هل يمكنني البحث حسب الحجم؟', 'نعم، استخدم فلاتر الحجم المتاحة في بعض الفئات.', NULL),
(340, 'هل يمكنني وضع علامات على المنتجات؟', 'نعم، العلامات تساعد في البحث والاكتشاف.', NULL),
(341, 'هل يمكنني مشاهدة نشاطي السابق؟', 'نعم، جميع نشاطاتك موجودة في \"سجل النشاط\".', NULL),
(342, 'هل يمكنني عرض منتجاتي الجديدة أولاً؟', 'نعم، استخدم خيار ترتيب حسب \"الأحدث\".', NULL),
(343, 'هل يمكنني حذف كل المنتجات مرة واحدة؟', 'نعم، من لوحة التحكم استخدم خيار حذف دفعي.', NULL),
(344, 'هل يمكنني تصفية المنتجات حسب السعر؟', 'نعم، استخدم شريط التصفية لتحديد نطاق السعر.', NULL),
(345, 'هل يمكنني مشاهدة المنتجات الأكثر مبيعًا؟', 'نعم، قسم \"الأكثر مبيعًا\" يظهر المنتجات الرائجة.', NULL),
(346, 'هل يمكنني البحث حسب تقييمات المشترين؟', 'نعم، يمكن تصفية المنتجات أو البائعين حسب التقييم.', NULL),
(347, 'هل يمكنني إرسال رسائل جماعية؟', 'لا، المحادثات حالياً فردية فقط.', NULL),
(348, 'هل يمكنني وضع رد تلقائي؟', 'لا، هذه الميزة غير متاحة بعد.', NULL),
(349, 'هل يمكنني وضع المنتجات على أنها محدودة؟', 'نعم، أضف ذلك في وصف المنتج.', NULL),
(350, 'هل يمكنني الحصول على نصائح لبيع أفضل؟', 'نعم، راجع قسم \"نصائح للبائعين\" في لوحة التحكم.', NULL),
(351, 'كيف أتعامل مع المشترين؟', 'كن مهذبًا وسريع الاستجابة، وستحصل على تقييمات جيدة.', NULL),
(352, 'هل يمكنني متابعة أكثر من بائع؟', 'نعم، يمكنك متابعة أي عدد من البائعين للحصول على تحديثات منتجاتهم.', NULL),
(353, 'هل يمكنني مشاهدة الرسائل الواردة مباشرةً؟', 'نعم، يتم تحديث المحادثات في الوقت الفعلي.', NULL),
(354, 'هل يمكنني رفع أكثر من صورة للمنتج؟', 'نعم، يمكنك رفع حتى 6 صور لكل منتج.', NULL),
(355, 'هل يمكنني إضافة وصف تفصيلي للمنتج؟', 'نعم، كلما كان الوصف واضحًا، زادت فرص البيع.', NULL),
(356, 'هل يمكنني وضع علامة على المنتج كجديد؟', 'نعم، أضف ذلك في وصف المنتج أو استخدم خيار \"جديد\".', NULL),
(357, 'هل يمكنني إضافة خصومات على المنتجات؟', 'نعم، يمكنك وضع المنتجات ضمن عروض أو خصومات خاصة.', NULL),
(358, 'هل يمكنني مشاركة المنتجات على وسائل التواصل؟', 'نعم، استخدم أزرار المشاركة الموجودة في كل صفحة منتج.', NULL),
(359, 'هل يمكنني الإبلاغ عن منتج غير مناسب؟', 'نعم، اضغط على زر \"إبلاغ\" واختر السبب المناسب.', NULL),
(360, 'هل يمكنني تعديل معلوماتي الشخصية؟', 'نعم، اذهب إلى إعدادات الحساب لتحديث الاسم أو البريد أو الهاتف.', NULL),
(361, 'هل يمكنني تغيير صورة الملف الشخصي؟', 'نعم، انقر على الصورة الحالية واختر صورة جديدة.', NULL),
(362, 'هل يمكنني حذف رسائلي؟', 'نعم، يمكنك حذف الرسائل من صندوق الوارد.', NULL),
(363, 'كيف أصبح بائعًا متميزًا؟', 'نشر منتجات عالية الجودة والرد بسرعة على الرسائل يزيد من ترتيبك.', NULL),
(364, 'هل يمكنني عمل عروض للمشترين المخلصين؟', 'نعم، يمكنك تقديم خصومات خاصة للعملاء الدائمين.', NULL),
(365, 'كيف أبلغ عن محتوى غير لائق؟', 'استخدم زر \"إبلاغ\" على المنتج أو ملف المستخدم.', NULL),
(366, 'هل يمكنني جدولة نشر المنتجات؟', 'حالياً النشر يدوي، ولكن هذه الميزة قادمة قريبًا.', NULL),
(367, 'هل يمكنني تعديل سعر المنتج في أي وقت؟', 'نعم، من لوحة التحكم يمكنك تعديل السعر بسهولة.', NULL),
(368, 'هل يمكنني إضافة أكثر من فئة للمنتج؟', 'يمكن لكل منتج فئة أساسية، ولكن يمكنك استخدام الوسوم لتوضيح التفاصيل.', NULL),
(369, 'كيف أرد على استفسارات المشترين؟', 'استخدم صندوق الوارد للرد مباشرة على رسائل المشترين.', NULL),
(370, 'هل يمكنني استخدام رموز تعبيرية في الوصف؟', 'نعم، الرموز التعبيرية مسموحة لجعل الوصف جذابًا.', NULL),
(371, 'هل يمكنني إخفاء المنتجات المباعة؟', 'نعم، ضعها كـ \"تم البيع\" لإخفائها من العرض العام.', NULL),
(372, 'كيف أرى إحصائيات منتجاتي؟', 'لوحة التحكم تحتوي على عدد المشاهدات، الإعجابات، والرسائل لكل منتج.', NULL),
(373, 'هل يمكنني إضافة بانر ترويجي؟', 'نعم، البائعون الموثقون يمكنهم طلب بانر للمتجر.', NULL),
(374, 'كيف أحقق التحقق من البريد الإلكتروني؟', 'افتح البريد بعد التسجيل وانقر على رابط التحقق المرسل.', NULL),
(375, 'هل يمكنني إعادة ضبط أسئلة الأمان؟', 'نعم، يمكنك تحديثها من إعدادات الأمان.', NULL),
(376, 'كيف أحذف حسابي نهائيًا؟', 'تواصل مع الدعم لتأكيد حذف الحساب عبر البريد الإلكتروني.', NULL),
(377, 'هل يمكنني تغيير اسم المتجر؟', 'نعم، مرة واحدة من إعدادات المتجر.', NULL),
(378, 'هل توجد عروض موسمية؟', 'نعم، تحقق من الصفحة الرئيسية للخصومات والعروض الموسمية.', NULL),
(379, 'هل يمكنني إنشاء منتج مجمّع؟', 'حالياً فقط المنتجات الفردية مدعومة، والمنتجات المجمعة قادمة قريبًا.', NULL),
(380, 'هل يمكنني الترويج لمنتجاتي على وسائل التواصل؟', 'نعم، شارك الرابط أو استخدم أزرار المشاركة المتاحة.', NULL),
(381, 'هل يمكنني وضع علامة على المنتجات في التعليقات؟', 'لا، هذه الميزة غير مدعومة بعد.', NULL),
(382, 'هل يمكنني حظر المشتري؟', 'نعم، من ملفه الشخصي اختر \"حظر\".', NULL),
(383, 'كيف أبلغ عن رسائل مزعجة؟', 'استخدم زر \"إبلاغ\" في المحادثة وحدد السبب.', NULL),
(384, 'هل يمكنني استقبال إشعارات الرسائل؟', 'نعم، الإشعارات مفعلة بشكل افتراضي.', NULL),
(385, 'هل يمكنني تغيير اسم المستخدم؟', 'نعم، يمكنك تغييره مرة واحدة في إعدادات الملف الشخصي.', NULL),
(386, 'كيف أعدل السيرة الذاتية؟', 'اذهب إلى الملف الشخصي وانقر على \"تعديل السيرة الذاتية\".', NULL),
(387, 'هل يمكنني ربط أكثر من حساب؟', 'لا، كل شخص مسموح له بحساب واحد فقط.', NULL),
(388, 'هل يمكنني متابعة فئات محددة؟', 'نعم، يمكنك متابعة الفئة لتصلك تحديثات المنتجات الجديدة.', NULL),
(389, 'كيف أجعل المنتج مميزًا؟', 'يمكن للبائعين الموثقين طلب شارة \"مميز\" لمنتجاتهم.', NULL),
(390, 'هل يمكنني إنشاء شعار للمتجر؟', 'نعم، ارفع الشعار من \"إعدادات المتجر\".', NULL),
(391, 'هل يمكنني تخصيص صفحة المتجر؟', 'يمكنك تعديل الشعار والسيرة، والتخصيص الكامل قادم قريبًا.', NULL),
(392, 'هل أستلم إشعارات الإعجابات؟', 'نعم، بشكل افتراضي تصل الإشعارات عن الإعجابات والرسائل.', NULL),
(393, 'هل يمكنني إيقاف إشعارات البريد الإلكتروني؟', 'نعم، من إعدادات الإشعارات يمكنك تعطيل البريد الإلكتروني.', NULL),
(394, 'هل يمكنني إيقاف إشعارات التطبيق؟', 'نعم، من إعدادات الحساب يمكنك التحكم بالإشعارات.', NULL),
(395, 'هل يمكنني مراسلة البائع مجهول الهوية؟', 'لا، جميع الرسائل تظهر هوية المرسل.', NULL),
(396, 'هل يمكنني إنشاء قائمة أمنيات؟', 'نعم، أضف المنتجات إلى قائمة الأمنيات لتسهيل الوصول لاحقًا.', NULL),
(397, 'هل يمكنني مشاركة قائمة الأمنيات؟', 'نعم، انسخ الرابط من الملف الشخصي وشاركه.', NULL),
(398, 'هل يمكنني الإعجاب بعدة منتجات دفعة واحدة؟', 'نعم، اضغط على رمز القلب لكل منتج ترغب بالإعجاب به.', NULL),
(399, 'أين أرى المنتجات التي أعجبتني مؤخرًا؟', 'تظهر في قسم \"المفضلة\" في الملف الشخصي.', NULL),
(400, 'هل لديكم برنامج ولاء؟', 'نعم، المشترون المتكررون يحصلون على نقاط وشارات.', NULL),
(401, 'كيف أكسب نقاط الولاء؟', 'تفاعل مع المنتجات، قم بالشراء، وادعُ الأصدقاء.', NULL),
(402, 'هل يمكنني استبدال النقاط بخصم؟', 'نعم، يمكن استخدام النقاط عند الدفع للحصول على خصم.', NULL),
(403, 'هل تقدمون خدمة تغليف الهدايا؟', 'حاليًا، كل بائع مسؤول عن التغليف إن رغب.', NULL),
(404, 'هل يمكنني جدولة التوصيل مع البائع؟', 'نعم، تنسق مع البائع عبر الدردشة.', NULL),
(405, 'هل يمكنني حذف رسالة أرسلتها بالخطأ؟', 'لا، لا يمكن حذف الرسائل بعد إرسالها.', NULL),
(406, 'كيف أبلغ عن قائمة منتجات؟', 'اضغط على \"إبلاغ\" على صفحة المنتج وحدد السبب.', NULL),
(407, 'هل يمكنني اقتراح ميزات جديدة؟', 'نعم، أرسل أفكارك عبر قسم \"الملاحظات\".', NULL),
(408, 'هل أستطيع رؤية المنتجات الرائجة؟', 'نعم، تظهر على الصفحة الرئيسية وفي الفئات.', NULL),
(409, 'كيف أتواصل مع الدعم بسرعة؟', 'استخدم الدردشة المباشرة أو نموذج الاتصال في قسم \"المساعدة\".', NULL),
(410, 'هل يمكنني متابعة عدة بائعين؟', 'نعم، تتبع أي عدد من البائعين لتصلك تحديثات منتجاتهم.', NULL),
(411, 'هل يمكنني مراسلة عدة بائعين في وقت واحد؟', 'لا، المراسلة حاليًا فردية فقط.', NULL),
(412, 'هل هناك بائعون موثقون؟', 'نعم، البائعون الموثقون لديهم شارة خاصة في الملف الشخصي.', NULL),
(413, 'كيف أصبح بائعًا موثقًا؟', 'ارفع هويتك أو رخصة عملك ضمن \"توثيق البائع\".', NULL),
(414, 'هل يمكنني بيع منتجات رقمية؟', 'لا، المنصة حالياً مخصصة للمنتجات المادية.', NULL),
(415, 'هل يمكنني نشر خدمات بدلاً من منتجات؟', 'لا، المنصة مخصصة للمنتجات فقط.', NULL),
(416, 'هل يمكنني تمييز منتجاتي؟', 'نعم، خيارات ترويج مدفوعة متاحة للبائعين الموثقين.', NULL),
(417, 'هل يمكنني تثبيت المنتجات في الصفحة؟', 'نعم، يمكن تثبيت حتى 3 منتجات في صفحة المتجر.', NULL),
(418, 'هل يمكنني استخدام الوسوم في الوصف؟', 'نعم، الوسوم تساعد المشترين في العثور على منتجاتك.', NULL),
(419, 'هل يمكنني إنشاء فئات خاصة؟', 'يمكنك استخدام الوسوم، لكن الفئات الرئيسية محددة من المنصة.', NULL),
(420, 'هل هناك تحليلات للبائعين؟', 'نعم، لوحة التحكم تعرض أداء المنتجات وتفاعل المشترين.', NULL),
(421, 'هل يمكنني إخفاء حالة الاتصال؟', 'نعم، يمكنك الظهور كغير متصل من إعدادات الحساب.', NULL),
(422, 'كيف أغير تفضيلات الإشعارات؟', 'اذهب إلى \"الإعدادات\" ثم \"الإشعارات\" لتعديل الخيارات.', NULL),
(423, 'هل يمكنني حظر المستخدمين من المراسلة؟', 'نعم، استخدم خيار \"حظر المستخدم\" في المحادثة.', NULL),
(424, 'هل يمكنني حذف سجل المحادثات؟', 'نعم، يمكنك مسح الرسائل من صندوق الوارد.', NULL),
(425, 'هل يمكنني البحث حسب حالة المنتج؟', 'نعم، يمكن تصفية المنتجات حسب جديد أو مستعمل.', NULL),
(426, 'هل يمكنني البحث حسب نطاق السعر؟', 'نعم، استخدم فلتر السعر لكل فئة.', NULL),
(427, 'هل يمكنني البحث حسب الموقع؟', 'نعم، إذا فعلت الموقع، تظهر النتائج القريبة أولًا.', NULL),
(428, 'هل يمكنني البحث حسب التقييم؟', 'نعم، يمكن تصفية المنتجات أو البائعين حسب التقييم.', NULL),
(429, 'هل يمكنني البحث حسب نوع المنتج؟', 'نعم، استخدم قائمة الفئات لتحديد نوع المنتج المطلوب.', NULL),
(430, 'هل يمكنني الإبلاغ عن حساب مزيف؟', 'نعم، استخدم زر \"إبلاغ\" على ملف المستخدم وحدد السبب.', NULL),
(431, 'هل يمكنني تعديل صور المنتج لاحقًا؟', 'نعم، من لوحة التحكم يمكنك تعديل أو حذف الصور.', NULL),
(432, 'هل يمكنني تغيير وصف المنتج بعد النشر؟', 'نعم، استخدم خيار \"تعديل المنتج\".', NULL),
(433, 'هل يمكنني وضع المنتجات على أنها محدودة الكمية؟', 'نعم، أضف ذلك في وصف المنتج لتشجيع الشراء السريع.', NULL),
(434, 'هل يمكنني مشاهدة المنتجات المشهورة عالميًا؟', 'نعم، هناك قسم \"الأكثر شهرة\" في الصفحة الرئيسية.', NULL),
(435, 'هل يمكنني مشاهدة المنتجات المضافة حديثًا؟', 'نعم، استخدم خيار الترتيب حسب \"الأحدث\".', NULL),
(436, 'هل يمكنني مشاركة رابط المنتج مع أصدقائي؟', 'نعم، استخدم زر \"مشاركة\" لنسخ الرابط أو مشاركته مباشرة.', NULL),
(437, 'هل يمكنني إضافة أكثر من وصف للمنتج؟', 'لا، يوجد حقل واحد للوصف، لكن يمكنك استخدام الوسوم لتوضيح المزيد.', NULL),
(438, 'هل يمكنني رفع ملف PDF للمنتج؟', 'لا، حاليًا فقط الصور مدعومة.', NULL),
(439, 'هل يمكنني تعديل سعر المنتج أكثر من مرة؟', 'نعم، يمكنك تعديل السعر في أي وقت.', NULL),
(440, 'هل يمكنني وضع خصم على المنتج لفترة محددة؟', 'نعم، ضع الخصم وحدد الفترة الزمنية في إعدادات المنتج.', NULL),
(441, 'هل يمكنني إنشاء أكثر من متجر؟', 'لا، لكل مستخدم متجر واحد فقط.', NULL),
(442, 'هل يمكنني تفعيل الإشعارات الصوتية؟', 'نعم، من إعدادات الإشعارات يمكنك تفعيل الصوت.', NULL),
(443, 'هل يمكنني التواصل مع الدعم عبر الهاتف؟', 'حالياً، الدعم متاح فقط عبر الدردشة أو البريد الإلكتروني.', NULL),
(444, 'هل يمكنني عرض المنتجات حسب الحالة الجديدة أو المستعملة؟', 'نعم، استخدم فلتر الحالة لتصفية النتائج.', NULL),
(445, 'هل يمكنني رؤية تقييمات البائعين الآخرين؟', 'نعم، كل بائع لديه قسم تقييمات في الملف الشخصي.', NULL),
(446, 'هل يمكنني حظر مستخدم من التعليقات؟', 'نعم، من إعدادات المتجر يمكنك إدارة التعليقات وحظر المستخدمين.', NULL),
(447, 'هل يمكنني عمل نسخة احتياطية للبيانات؟', 'نعم، يمكنك تصدير بيانات منتجاتك من لوحة التحكم.', NULL),
(448, 'هل يمكنني ربط حسابي بحساب PayPal؟', 'حالياً، الدفع يتم مباشرة بين المشترين والبائعين خارج المنصة.', NULL),
(449, 'هل يمكنني وضع منتجات على أنها مجانية؟', 'نعم، يمكنك تحديد السعر صفر عند إضافة المنتج.', NULL),
(450, 'هل يمكنني إرسال رسائل للمتابعين؟', 'حالياً لا، التواصل يتم عبر الرسائل الفردية فقط.', NULL),
(451, 'هل يمكنني البحث عن الماركات والمنتجات المقلدة اوالنسخة و', 'نعم، استخدم الفلاتر حسب الماركة او العلامة التجارية في بعض الفئات.', NULL),
(452, 'هل يمكنني عرض المنتجات حسب الفئة؟', 'نعم، استخدم فلاتر الحجم المتاحة حسب الفئة.', NULL),
(453, 'هل يمكنني متابعة التحديثات من متجر معين فقط؟', 'نعم، تابع المتجر وسيصلك كل جديد من منتجاته.', NULL),
(454, 'هل يمكن عرض تفاصيل البائع ', 'نعم عند عرض المنتج اذهب للتفاصيل ومنها ستجد اسم البائع اضغط عليه وسيتم عرض بياناته وصفحة البروفايل الخاصة به', NULL),
(455, 'هل يمكنني مشاهدة قائمة المشترين؟', 'لا، لا يمكن عرض بيانات المشترين للحفاظ على الخصوصية.', NULL),
(456, 'هل يمكنني تفعيل الوضع المظلم؟', 'لا ليس متوفر حاليا', NULL),
(457, 'هل يمكنني حذف جميع الرسائل دفعة واحدة؟', 'المحادثات يتم حفظها ولا يمكن حذفها لانها مرجع في حال رفع نزاع', NULL),
(458, 'هل يمكنني البحث عن المنتجات باستخدام كلمات مفتاحية؟', 'نعم، شريط البحث يدعم الكلمات المفتاحية للمنتجات.', NULL),
(459, 'هل يمكنني وضع رابط لمتجري على وسائل التواصل؟', 'نعم، يمكنك نسخ رابط المتجر ومشاركته في أي مكان.', NULL),
(460, 'هل يمكنني متابعة اي منتجات جديدة مباشرة من الهاتف؟', 'نعم، الموقع متوافق مع الهاتف ويمكنك متابعة المنتجات بسهولة.', NULL),
(461, 'هل يمكنني تعديل ملفي الشخصي من الهاتف؟', 'نعم، كل الإعدادات متاحة على الهاتف تمامًا كما على الكمبيوتر.', NULL),
(462, 'هل يمكنني الاطلاع على سجل النشاطات السابقة؟', 'للاسف نحن لم نعمل هذه الجزئية ولكن بالمستقبل سنوفرها ان شاء الله', NULL),
(463, 'هل لديكم منتجات طبية في متجركم', 'للاسف نحن لا نعمل بالمعدات الطبية', 2),
(464, 'هل المعدات التي لديكم فيها تيكن؟', 'نعم اكيد نحن نوفر المعدات التي تطابق المواصفات العالمية الايزو ويطابق التيكن', 2);

-- --------------------------------------------------------

--
-- Table structure for table `head_img`
--

CREATE TABLE `head_img` (
  `id` int NOT NULL,
  `topic` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `img` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `head_img`
--

INSERT INTO `head_img` (`id`, `topic`, `img`) VALUES
(2, 'world of stores', '1548475960_image_upload_1759602215.avif'),
(3, 'Accessories ', '908653025_image_upload_1759602305.avif'),
(4, '  Jewelry', '1779261918_image_upload_1759602371.jpg'),
(5, 'Brands', '20152299_image_upload_1759602130.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `messenger`
--

CREATE TABLE `messenger` (
  `id` int NOT NULL,
  `insert_datetime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `client1_user_id` int DEFAULT NULL,
  `client2_user_id` int DEFAULT NULL,
  `msg_content` text NOT NULL,
  `human_ai` int NOT NULL COMMENT '1.human 2.ai'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `messenger`
--

INSERT INTO `messenger` (`id`, `insert_datetime`, `client1_user_id`, `client2_user_id`, `msg_content`, `human_ai`) VALUES
(1, '2025-11-30 02:03:04', 4, 2, 'hello', 2),
(2, '2025-11-30 02:03:04', 2, 4, 'sorry i dont understand ', 2),
(3, '2025-11-30 02:07:35', 4, 2, 'can you help me', 2),
(4, '2025-11-30 02:07:35', 2, 4, 'sorry i dont understand ', 2),
(5, '2025-11-30 02:07:46', 4, 2, 'hello', 1),
(6, '2025-12-07 22:29:12', 3, 2, 'مرحبا', 2),
(7, '2025-12-07 22:29:12', 2, 3, 'عذرا منك .. لم افهم الجملة', 2),
(8, '2025-12-07 22:29:21', 3, 2, 'مرحبا', 1);

-- --------------------------------------------------------

--
-- Table structure for table `orders_list`
--

CREATE TABLE `orders_list` (
  `id` int UNSIGNED NOT NULL,
  `sent_datetime` datetime DEFAULT CURRENT_TIMESTAMP,
  `product_id` int DEFAULT NULL,
  `amount` int NOT NULL DEFAULT '1',
  `by_user_id` int DEFAULT NULL,
  `client_response` int DEFAULT '0' COMMENT '0.wait 1.accept 2.reject',
  `courier_id` int DEFAULT NULL,
  `evaluate_buyer_by_courier` int DEFAULT '0' COMMENT '1-5 star',
  `evaluate_buyer_by_seller` int DEFAULT '0' COMMENT '1-5 star',
  `evaluate_seller_by_buyer` int DEFAULT '0' COMMENT '1-5 star',
  `payment_method` int DEFAULT NULL,
  `is_canceled_row` tinyint DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `orders_list`
--

INSERT INTO `orders_list` (`id`, `sent_datetime`, `product_id`, `amount`, `by_user_id`, `client_response`, `courier_id`, `evaluate_buyer_by_courier`, `evaluate_buyer_by_seller`, `evaluate_seller_by_buyer`, `payment_method`, `is_canceled_row`) VALUES
(1, '2025-05-26 09:57:24', 4, 1, 6, 1, 7, 4, 5, 3, NULL, 0),
(2, '2025-05-26 10:10:00', 1, 1, 6, 1, 7, 1, 2, 2, 1, 0),
(3, '2025-05-26 11:06:31', 1, 1, 6, 0, 7, 0, 4, 5, 1, 0),
(4, '2025-05-26 11:07:08', 1, 1, 6, 0, 7, 0, 0, 3, 1, 0),
(5, '2025-05-28 08:23:19', 1, 1, 2, 0, 7, 0, 0, 0, NULL, 0),
(6, '2025-05-28 09:20:44', 3, 1, 2, 0, 7, 0, 0, 0, NULL, 0),
(7, '2025-05-28 09:23:07', 4, 1, 2, 0, 7, 0, 0, 0, NULL, 0),
(8, '2025-10-22 11:14:00', 6, 1, 4, 0, 7, 0, 0, 0, NULL, 0),
(9, '2025-11-09 21:57:00', 4, 1, 3, 0, 7, 0, 0, 0, NULL, 0),
(10, '2025-11-14 00:36:56', 3, 1, 4, 0, 7, 0, 0, 0, NULL, 0),
(11, '2025-11-14 00:40:41', 5, 1, 4, 0, 7, 0, 0, 0, NULL, 0),
(12, '2025-11-30 02:33:00', 1, 1, 4, 0, 7, 0, 0, 0, NULL, 0),
(13, '2025-12-21 09:22:00', 6, 1, 6, 0, 7, 0, 0, 3, NULL, 0),
(14, '2025-12-21 20:35:53', 1, 4, 6, 0, 7, 0, 0, 0, NULL, 0),
(15, '2025-12-22 00:07:51', 1, 4, 6, 0, 7, 0, 0, 0, NULL, 0),
(16, '2025-12-22 00:09:43', 2, 1, 6, 0, 7, 0, 0, 0, NULL, 0),
(17, '2025-12-22 00:22:54', 1, 3, 6, 0, 7, 0, 0, 0, NULL, 0),
(18, '2026-01-02 00:44:52', 2, 3, 12, 0, 7, 0, 3, 0, NULL, 0),
(19, '2026-01-06 20:39:16', 2, 3, 4, 0, 7, 0, 0, 0, NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `order_tracking`
--

CREATE TABLE `order_tracking` (
  `id` int UNSIGNED NOT NULL,
  `at_datetime` datetime DEFAULT CURRENT_TIMESTAMP,
  `order_id` int NOT NULL,
  `by_user_id` int DEFAULT NULL,
  `stage` int DEFAULT '0' COMMENT '0.Processing\r\n1.To Sorting Center\r\n2.At Sorting Center\r\n3.To Buyer\r\n4.Delivered',
  `is_canceled_row` tinyint DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `order_tracking`
--

INSERT INTO `order_tracking` (`id`, `at_datetime`, `order_id`, `by_user_id`, `stage`, `is_canceled_row`) VALUES
(1, '2025-12-11 01:15:09', 1, 7, 0, 0),
(2, '2025-12-11 01:15:12', 1, 7, 1, 0),
(3, '2025-12-11 01:18:30', 1, 7, 2, 0),
(4, '2025-12-11 01:18:32', 1, 7, 3, 0),
(5, '2025-12-11 01:18:34', 1, 7, 4, 0),
(6, '2025-12-11 11:05:09', 2, 7, 0, 0),
(7, '2025-12-11 22:27:28', 1, 7, 0, 0),
(8, '2025-12-11 22:27:31', 1, 7, 1, 0),
(9, '2025-12-11 22:27:32', 1, 7, 2, 0),
(10, '2025-12-11 22:27:33', 1, 7, 3, 0),
(11, '2025-12-11 22:27:34', 1, 7, 4, 0),
(12, '2026-01-02 17:50:13', 2, NULL, 1, 0),
(13, '2026-01-02 17:50:20', 2, NULL, 2, 0),
(14, '2026-01-06 20:35:20', 2, NULL, 3, 0),
(15, '2026-01-06 20:35:24', 2, NULL, 4, 0);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int NOT NULL,
  `publish_datetime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `title` varchar(200) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `ai_analysis` varchar(200) NOT NULL DEFAULT 'wait' COMMENT 'نتائج تحليل ai',
  `img` varchar(50) DEFAULT NULL,
  `price` float DEFAULT NULL,
  `description` mediumtext CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci,
  `category_id` int DEFAULT NULL,
  `by_user_id` int DEFAULT NULL,
  `brand_copy` int DEFAULT NULL COMMENT '1.brand 2.copy',
  `is_canceled_row` tinyint NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `publish_datetime`, `title`, `ai_analysis`, `img`, `price`, `description`, `category_id`, `by_user_id`, `brand_copy`, `is_canceled_row`) VALUES
(1, '2025-03-20 11:32:10', 'Gold Dial Analogue Watch', 'clock', '529511478_image_upload_1759603224.webp', 200, 'Maxima GOLD Men ', 5, 2, 2, 0),
(2, '2025-03-20 11:32:28', 'Traditional women Watch', 'clock', '1379922850_image_upload_1759603723.webp', 250, NULL, 5, 2, 2, 0),
(3, '2024-03-20 11:32:37', 'Traditional Men Watch', 'no_objects', '1646213102_image_upload_1759613448.png', 300, 'New Universal 1080° Swivel Faucet Extender 2 Water Outlet Modes Faucet Aerator Extension with Filter Robotic Arm Bathroom Splash\r\n', 8, 2, 1, 0),
(4, '2024-03-20 11:37:27', 'Spinner Aluminum alloy Triangle ', 'wait', '862981206_image_upload_1759603677.avif', 20, 'New Luminous Metal Fidget Spinner Aluminum alloy Triangle Hand Spinner Rotating Decompression Toys for Adult EDC Desk Fidget Toy\r\n', 1, 2, 1, 1),
(5, '2024-05-14 16:17:12', 'Helmet MICH2000 Airsoft ', 'motorcycle', '1918104613_image_upload_1759613611.png', 120, 'Protective Helmet FAST Helmet MICH2000 Airsoft MH Tactical Helmet Outdoor Tactical Painball CS SWAT Riding Protect Equipment\r\n', 4, 3, 2, 0),
(6, '2024-05-14 16:22:31', 'One-Wheel Hoverboard', 'wait', '6063903_image_upload_1759605565.avif', 400, 'One-Wheel Hoverboard for Adults Electric Self-Balancing Scooter with Gyroor Technology for Off-Road Terrains\r\n', 7, 2, 2, 1),
(7, '2025-03-19 20:14:45', 'Metal Vintage Round Glasses Frame', 'tennis racket', '797884467_image_upload_1759606162.png', 100, 'New Fashion Women Men Metal Vintage Round Glasses Frame Oversized Eyeglasses Optical Spectacles Vision Care Eyewear for Unisex', 4, 3, 1, 0),
(8, '2025-05-10 10:56:32', 'Half finger Gloves Men\'s', 'no_objects', '1031026996_image_upload_1759605801.jpg', 120, 'Tactical Hard Knuckle Half finger Gloves Men\'s Combat Hunting Shooting Paintball Duty - Fingerless\r\n', 4, 1, 1, 0),
(9, '2025-05-10 11:04:07', 'Intel Celeron N5095 Ultraslim Laptop', 'book, laptop', '1702777255_image_upload_1759613847.png', 1500, 'BYONE 15.6 Inch Intel Celeron N5095 Ultraslim Laptop 12G 16GB RAM 512GB 1TB SSD Keyboard Backlight Fingerprint Portable Computer\r\n', 3, 1, 1, 0),
(10, '2025-05-10 22:27:22', 'Navceker 4K 60Hz ', 'cell phone', '1627343990_image_upload_1759613921.png', 380, 'Navceker 4K 60Hz Thunderbolt 3 USB C HDMI KVM Switch 100W PD Charge Type C USB KVM Switcher for Computer PC Macbook 1Monitor\r\n', 3, 1, 1, 0),
(11, '2025-05-26 08:27:10', 'Bincoo Coffee Moka Pot', 'cake, cup, donut', '657356252_image_upload_1759614020.png', 20, 'Bincoo Coffee Moka Pot Espresso Maker Electric Stove For Italian Home Barista Accessories Coffee Professional Coffee Maker Tools\r\n', 8, 1, 1, 0),
(12, '2025-05-27 09:41:26', 'Coffee Machine', 'cup', '1867930638_image_upload_1759614113.png', 300, 'Cafelffe 4in1 Cafetera Cappuccino Coffee Machine Dolce gusto Nes Capsule Espresso Maker ESE Pod Ground Gift for Lover,Dad,Mom\r\n', 8, 1, 1, 0),
(13, '2025-06-09 14:01:59', 'Small 3D Printer', 'no_objects', '478722562_image_upload_1759604130.jpg', 499, 'Small Frequency Division Multiplexing 3D Printer High Accuracy Fast Heating Compact 3D Printing Machine 100X100X100MM\r\n', 6, 1, 1, 0),
(14, '2025-06-09 18:00:19', 'Travel Luggage', 'no_objects', '1759641913_image_upload_1759605903.webp', 340, 'Luggage - Travel Luggage & Rolling Suitcases ', 2, 3, 1, 0),
(15, '2025-06-09 18:00:59', 'Men Crossbody Bags', 'suitcase', '1376751614_image_upload_1759605979.webp', 110, 'Men Crossbody Bags Male Nylon Shoulder Bags 4 Zippers Boy Messenger Bags Man Handbags for Travel Casual Large Satchel\r\n', 2, 3, 1, 0),
(16, '2025-06-09 18:01:14', 'MOTAORA 2025 New Women Handbag', 'bus, person, stop sign, tie', '93273141_image_upload_1759606043.avif', 90, 'MOTAORA 2025 New Women Handbag Fashion Leather Shoulder Bag Ladies Large Capaarea Messenger Bags Laptop Bag For 14\" Macbook Air\r\n', 2, 3, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `seller_courier`
--

CREATE TABLE `seller_courier` (
  `id` int UNSIGNED NOT NULL,
  `seller_id` int NOT NULL,
  `courier_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `seller_courier`
--

INSERT INTO `seller_courier` (`id`, `seller_id`, `courier_id`) VALUES
(1, 2, 7);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `join_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fullname` varchar(15) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `business_name` varchar(15) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `img` varchar(55) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `phone` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `password` varchar(200) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `area_id` int DEFAULT NULL,
  `address` text,
  `latitude` varchar(20) DEFAULT NULL,
  `longitude` varchar(20) DEFAULT NULL,
  `user_role_account` int NOT NULL DEFAULT '3' COMMENT '1.admin 2.seller 3.buyer 4.courier',
  `card_number` varchar(50) DEFAULT NULL,
  `card_name` varchar(50) DEFAULT NULL,
  `card_expiry` varchar(50) DEFAULT NULL,
  `card_cvv` varchar(50) DEFAULT NULL,
  `is_canceled_row` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `join_at`, `fullname`, `business_name`, `img`, `phone`, `password`, `area_id`, `address`, `latitude`, `longitude`, `user_role_account`, `card_number`, `card_name`, `card_expiry`, `card_cvv`, `is_canceled_row`) VALUES
(1, '2025-11-29 15:44:51', 'High Tech Store', 'HTS Ltd', NULL, '0568222222', '13f4afaff1316760072f042aec3d08052e6076d803af8d4fff5538e395b79808', 1, 'وسط البلد', '32.22503900', '35.26097300', 2, NULL, NULL, NULL, NULL, 0),
(2, '2025-11-29 15:44:51', 'Five Star Shop', 'FSS Ltd', '1046734032_image_upload_1765230440.png', '0568111111', '28c89b18a5135fb7f4bdcf29970605f583ba11d51dd1a31dc1cd29ecd637b7ef', 6, 'دير قرنطل', '32.22503900', '35.26097300', 2, NULL, NULL, NULL, NULL, 0),
(3, '2025-11-29 15:44:51', 'master', 'admin', '894613985_image_upload_1760943387.png', '0568000000', '2b0bae663cd34b55dafa63a2431c469f69ddcdc14410538c67cddd90d80d0551', 7, 'حلحول', '32.22503900', '35.26097300', 1, '1234123412341234', 'ahmad yousef', '11/28', '123', 0),
(4, '2025-11-29 15:44:51', 'ahmad rami', 'abu sami', '542249521_image_upload_1761122252.png', '0565000222', '8a1ab1062d04d6317309010f7296a3ee2e43bd68016070db79e152d1c6322b3d', 2, 'دير شرف', '31.53256999', '35.09982722', 3, NULL, NULL, NULL, NULL, 0),
(5, '2025-11-29 15:44:51', 'ali hassan', 'Eng. Ali', NULL, '5555555555', '91a73fd806ab2c005c13b4dc19130a884e909dea3f72d46e30266fe1a1f588d8', 5, 'بيت لقيا', '32.22503900', '35.26097300', 3, NULL, NULL, NULL, NULL, 0),
(6, '2025-11-29 15:44:51', 'najeh hamdan', 'Dr. Najeh', NULL, '0568222221', 'd22ddf7b1e8b251482871f65cec5980786197c3e3dee6eb2c7496fb89e8778d6', 9, 'عرابة', '31.53256999', '35.09982722', 3, NULL, NULL, NULL, NULL, 0),
(7, '2025-11-29 15:44:51', 'Sabeq', 'Logistic', NULL, '0568111000', 'db149ae4c6dc47fb7ed2c85fa7f0e3ff68c5b86cfab929b4db4ae851f923b0d0', 6, NULL, NULL, NULL, 4, NULL, NULL, NULL, NULL, 0),
(8, '2025-12-20 10:53:09', NULL, NULL, NULL, '0568000123', 'd87770168ed225083c226d8929fbe49f331fb99d66fb6e58f7666132b035c8c0', NULL, NULL, NULL, NULL, 3, NULL, NULL, NULL, NULL, 1),
(10, '2025-12-20 10:55:01', NULL, NULL, NULL, '0568000125', 'd87770168ed225083c226d8929fbe49f331fb99d66fb6e58f7666132b035c8c0', NULL, NULL, NULL, NULL, 3, NULL, NULL, NULL, NULL, 1),
(11, '2025-12-20 10:55:28', NULL, NULL, NULL, '0568000126', 'd87770168ed225083c226d8929fbe49f331fb99d66fb6e58f7666132b035c8c0', NULL, NULL, NULL, NULL, 3, NULL, NULL, NULL, NULL, 1),
(12, '2025-12-20 11:06:47', 'ahamad yousef', 'Dr. Ahmad', '93_62_68d5535b971d558f594f10a5affd0a71.jpeg', '0568000127', 'd87770168ed225083c226d8929fbe49f331fb99d66fb6e58f7666132b035c8c0', 5, 'city center', NULL, NULL, 3, NULL, NULL, NULL, NULL, 0),
(13, '2025-12-20 11:11:42', '', NULL, NULL, '0568000128', 'd87770168ed225083c226d8929fbe49f331fb99d66fb6e58f7666132b035c8c0', NULL, NULL, NULL, NULL, 3, NULL, NULL, NULL, NULL, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `about_us`
--
ALTER TABLE `about_us`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `area`
--
ALTER TABLE `area`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `change_password`
--
ALTER TABLE `change_password`
  ADD PRIMARY KEY (`id`),
  ADD KEY `change_password_user_id_id_con` (`user_id`);

--
-- Indexes for table `chat_conjunction`
--
ALTER TABLE `chat_conjunction`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `head_img`
--
ALTER TABLE `head_img`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `messenger`
--
ALTER TABLE `messenger`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders_list`
--
ALTER TABLE `orders_list`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_order_from_user_id` (`by_user_id`),
  ADD KEY `fk_order_product_id` (`product_id`);

--
-- Indexes for table `order_tracking`
--
ALTER TABLE `order_tracking`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_order_from_user_id` (`by_user_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_product_category_id` (`category_id`),
  ADD KEY `fk_product_by_user_id` (`by_user_id`);

--
-- Indexes for table `seller_courier`
--
ALTER TABLE `seller_courier`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `loginname` (`phone`),
  ADD KEY `users_area_id` (`area_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `about_us`
--
ALTER TABLE `about_us`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `area`
--
ALTER TABLE `area`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `change_password`
--
ALTER TABLE `change_password`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `chat_conjunction`
--
ALTER TABLE `chat_conjunction`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=465;

--
-- AUTO_INCREMENT for table `head_img`
--
ALTER TABLE `head_img`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `messenger`
--
ALTER TABLE `messenger`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `orders_list`
--
ALTER TABLE `orders_list`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `order_tracking`
--
ALTER TABLE `order_tracking`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `seller_courier`
--
ALTER TABLE `seller_courier`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `change_password`
--
ALTER TABLE `change_password`
  ADD CONSTRAINT `fk_change_password_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `orders_list`
--
ALTER TABLE `orders_list`
  ADD CONSTRAINT `fk_order_from_user_id` FOREIGN KEY (`by_user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `fk_order_product_id` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `fk_product_by_user_id` FOREIGN KEY (`by_user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `fk_product_category_id` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_area_id` FOREIGN KEY (`area_id`) REFERENCES `area` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
