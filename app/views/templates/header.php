<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($data['title']) ? $data['title'] . ' | BLISS' : 'BLISS' ?></title>
    <!-- Inter Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Main CSS -->
    <link rel="stylesheet" href="/php/Webdev/public/css/style.css">
    <link rel="stylesheet" href="/php/Webdev/public/css/catalog.css">
    <link rel="stylesheet" href="/php/Webdev/public/css/auth.css">
    <link rel="stylesheet" href="/php/Webdev/public/css/product.css">
    <link rel="stylesheet" href="/php/Webdev/public/css/cart.css">
    <link rel="stylesheet" href="/php/Webdev/public/css/filter.css">
    <link rel="stylesheet" href="/php/Webdev/public/css/store.css">
    <link rel="stylesheet" href="/php/Webdev/public/css/help.css">
    <link rel="stylesheet" href="/php/Webdev/public/css/contact.css">
    <link rel="stylesheet" href="/php/Webdev/public/css/shipping_returns.css">
</head>
<body>

<!-- Sub Header (Top Bar) -->
<div class="sub-header">
    <div class="container sub-header-content">
        <div class="sub-header-left"></div>
        <div class="sub-header-right">
            <a href="/php/Webdev/public/store">Find a Store</a>
            <span>|</span>
            <a href="/php/Webdev/public/help">Help</a>
            <span>|</span>
            <a href="/php/Webdev/public/auth/register">Join Us</a>
            <span>|</span>
            <a href="/php/Webdev/public/auth/login">Sign In</a>
        </div>
    </div>
</div>

<!-- Scrolling Announcement Bar -->
<?php
    if(!class_exists('SettingsModel')) require_once '../app/models/SettingsModel.php';
    $headerSettingsDb = new SettingsModel();
    $activeAnnouncements = $headerSettingsDb->getActiveAnnouncements();
    $announcementBgColor = $headerSettingsDb->getSiteSetting('announcement_bg_color') ?: '#0f172a'; // Default to slate
    $announcementBarEnabled = $headerSettingsDb->getSiteSetting('announcement_bar_enabled') ?? '1'; // Get global enabled state, default to enabled

    // Calculate text color based on background luminance
    $hex = ltrim($announcementBgColor, '#');
    $r = $g = $b = 0;
    if (strlen($hex) == 3) {
        $r = hexdec(substr($hex, 0, 1) . substr($hex, 0, 1));
        $g = hexdec(substr($hex, 1, 1) . substr($hex, 1, 1));
        $b = hexdec(substr($hex, 2, 1) . substr($hex, 2, 1));
    } elseif (strlen($hex) == 6) {
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));
    }
    $luminance = (0.299 * $r + 0.587 * $g + 0.114 * $b) / 255;
    $announcementTextColor = ($luminance > 0.5) ? '#000000' : '#ffffff';
?>
<?php if($announcementBarEnabled === '1' && !empty($activeAnnouncements)): ?>
<div class="announcement-bar" style="background-color: <?= htmlspecialchars($announcementBgColor) ?>; color: <?= $announcementTextColor ?>;">
    <div class="marquee">
        <div class="marquee-content">
            <?php foreach($activeAnnouncements as $ann): ?>
                <span><?= htmlspecialchars($ann['message']) ?></span>
                <span class="dot">•</span>
            <?php endforeach; ?>
        </div>
        <!-- Duplicate for seamless scrolling -->
        <div class="marquee-content" aria-hidden="true">
            <?php foreach($activeAnnouncements as $ann): ?>
                <span><?= htmlspecialchars($ann['message']) ?></span>
                <span class="dot">•</span>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Setup Glassmorphic Header -->
<header class="main-header">
    <div class="header-container main-nav">
        <div class="logo">
            <a href="/php/Webdev/public/">BLISS</a>
        </div>
        <nav class="nav-links">
            <a href="/php/Webdev/public/">New & Featured</a>
            <a href="/php/Webdev/public/catalog">Catalog</a>
        </nav>
        <div class="nav-actions">
            <div class="search-bar">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                <input type="text" placeholder="Search products...">
            </div>
            <a href="/php/Webdev/public/favorites" class="icon-link" aria-label="Favorites" style="position: relative;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
                <?php 
                    $favCount = (isset($_SESSION['favorites_list']) && count($_SESSION['favorites_list']) > 0) ? count($_SESSION['favorites_list']) : 0;
                ?>
                <span class="cart-badge" style="background: #ef4444; top: -6px; right: -8px; display: <?= $favCount > 0 ? 'flex' : 'none' ?>;"><?= $favCount ?></span>
            </a>
            <a href="/php/Webdev/public/cart" class="icon-link cart-link" aria-label="Cart">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
                <?php 
                    $cartCount = isset($_SESSION['cart']) ? array_sum($_SESSION['cart']) : 0;
                ?>
                <span class="cart-badge" style="display: <?= $cartCount > 0 ? 'flex' : 'none' ?>;"><?= $cartCount ?></span>
            </a>
            <?php if(isset($_SESSION['user_id'])): 
                // Safely load UserModel to get fresh data
                if(!class_exists('Database')) require_once '../app/core/Database.php';
                if(!class_exists('UserModel')) require_once '../app/models/UserModel.php';
                
                $headerUserDb = new UserModel();
                $headerUser = $headerUserDb->getUserById($_SESSION['user_id']);
                $profilePic = ($headerUser && $headerUser['profile_picture']) ? '/php/Webdev/public/' . $headerUser['profile_picture'] : null;
            ?>
                <div class="profile-dropdown">
                    <button class="icon-link profile-trigger" aria-label="Profile">
                        <?php if($profilePic): ?>
                            <img src="<?= $profilePic ?>?v=<?= time() ?>" alt="Profile" class="header-profile-img">
                        <?php else: ?>
                            <div class="header-profile-placeholder"><?= strtoupper(substr(!empty($headerUser['username']) ? $headerUser['username'] : $_SESSION['user_name'], 0, 1)) ?></div>
                        <?php endif; ?>
                        <span class="profile-name"><?= !empty($headerUser['username']) ? htmlspecialchars($headerUser['username']) : htmlspecialchars(explode(' ', $_SESSION['user_name'])[0]) ?></span>
                    </button>
                    <div class="dropdown-content">
                        <?php if(isset($headerUser) && in_array($headerUser['role'], ['admin', 'superadmin'])): ?>
                            <a href="/php/Webdev/public/admin/dashboard" class="admin-dashboard-link" style="font-weight: 800; background: linear-gradient(135deg, #1e1b4b 0%, #4338ca 100%); color: #fff !important; border-radius: 8px; padding: 10px 16px; margin-bottom: 8px; box-shadow: 0 4px 12px rgba(67, 56, 202, 0.2); text-align: center; letter-spacing: 0.05em; text-transform: uppercase; font-size: 0.8rem; display: flex; align-items: center; justify-content: center; gap: 8px; transition: all 0.3s ease;">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 2L2 7l10 5 10-5-10-5z"></path><path d="M2 17l10 5 10-5"></path><path d="M2 12l10 5 10-5"></path></svg>
                                Admin Dashboard
                            </a>
                        <?php endif; ?>
                        <a href="/php/Webdev/public/profile">My Account</a>
                        <a href="/php/Webdev/public/profile/orders">My Orders</a>
                        <?php 
                            if(!class_exists('MessageModel')) require_once '../app/models/MessageModel.php';
                            $headerMsgDb = new MessageModel();
                            $unreadCount = $headerMsgDb->getUnreadCountUser($_SESSION['user_id']);
                        ?>
                        <a href="/php/Webdev/public/profile/inbox" style="display: flex; justify-content: space-between; align-items: center;">
                            My Inbox
                            <?php if($unreadCount > 0): ?>
                                <span style="background: #10b981; color: #000; border-radius: 50%; width: 18px; height: 18px; font-size: 10px; display: flex; align-items: center; justify-content: center; font-weight: 800;"><?= $unreadCount ?></span>
                            <?php endif; ?>
                        </a>
                        <hr>
                        <a href="/php/Webdev/public/auth/logout">Logout</a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</header>

<main class="page-content">
