<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($data['title']) ? $data['title'] . ' | BLISS Admin' : 'BLISS Admin Dashboard' ?></title>
    <!-- Inter Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Admin Core CSS -->
    <link rel="stylesheet" href="/php/Webdev/public/css/admin.css">
    <script>
        // Apply theme immediately to prevent flash
        (function() {
            const savedTheme = localStorage.getItem('admin-theme') || 'light';
            document.documentElement.setAttribute('data-theme', savedTheme);
        })();
    </script>
</head>
<body class="admin-body">

<div class="admin-layout">
    <!-- Vertical Sidebar Navigation -->
    <aside class="admin-sidebar">
        <div class="sidebar-brand">
            <h2>BLISS <span class="badge">admin</span></h2>
        </div>
        <nav class="sidebar-nav">
            <a href="/php/Webdev/public/admin" class="<?= rtrim($_SERVER['REQUEST_URI'], '/') === '/php/Webdev/public/admin' ? 'active' : '' ?>">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
                Dashboard
            </a>
            <?php if ($_SESSION['admin_role'] === 'superadmin'): ?>
            <a href="/php/Webdev/public/superadmin/products" class="<?= strpos($_SERVER['REQUEST_URI'], '/superadmin/products') !== false ? 'active' : '' ?>">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg>
                Products
            </a>
            <a href="/php/Webdev/public/superadmin/hero_settings" class="<?= strpos($_SERVER['REQUEST_URI'], '/superadmin/hero_settings') !== false ? 'active' : '' ?>">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
                Hero Settings
            </a>
            <?php endif; ?>
            <a href="/php/Webdev/public/admin/customers" class="<?= strpos($_SERVER['REQUEST_URI'], '/admin/customers') !== false ? 'active' : '' ?>">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                Customers
            </a>
            <a href="/php/Webdev/public/admin/inbox" class="<?= strpos($_SERVER['REQUEST_URI'], '/admin/inbox') !== false ? 'active' : '' ?>" style="display: flex; justify-content: space-between; align-items: center;">
                <div style="display: flex; align-items: center; gap: 12px;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                    Inbox
                </div>
                <?php 
                    if(!class_exists('MessageModel')) require_once '../app/models/MessageModel.php';
                    $adminMsgDb = new MessageModel();
                    $adminUnread = $adminMsgDb->getUnreadCountAdmin();
                ?>
                <?php if($adminUnread > 0): ?>
                    <span style="background: #10b981; color: #000; border-radius: 50%; width: 20px; height: 20px; font-size: 11px; display: flex; align-items: center; justify-content: center; font-weight: 800;"><?= $adminUnread ?></span>
                <?php endif; ?>
            </a>
            <a href="/php/Webdev/public/admin/orders" class="<?= strpos($_SERVER['REQUEST_URI'], '/admin/orders') !== false || strpos($_SERVER['REQUEST_URI'], '/admin/order_detail') !== false ? 'active' : '' ?>">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
                Orders
            </a>
        </nav>
        <div class="sidebar-footer">
            <div class="user-info">
                <span><?= htmlspecialchars($_SESSION['admin_name']) ?></span>
                <small><?= htmlspecialchars(ucfirst($_SESSION['admin_role'])) ?></small>
            </div>
            <a href="/php/Webdev/public/adminauth/logout" class="logout-btn">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
            </a>
        </div>
    </aside>

    <!-- Main Content Area -->
    <main class="admin-main">
        <header class="admin-topbar">
            <h1><?= isset($data['title']) ? $data['title'] : 'Dashboard' ?></h1>
            <div class="topbar-actions" style="display: flex; gap: 12px; align-items: center;">
                <button id="theme-toggle" class="btn-storefront" style="padding: 8px; border-radius: 50%; display: flex; align-items: center; justify-content: center; width: 38px; height: 38px; cursor: pointer;">
                    <svg class="sun-icon" style="display: none;" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="5"></circle><line x1="12" y1="1" x2="12" y2="3"></line><line x1="12" y1="21" x2="12" y2="23"></line><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line><line x1="1" y1="12" x2="3" y2="12"></line><line x1="21" y1="12" x2="23" y2="12"></line><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line></svg>
                    <svg class="moon-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path></svg>
                </button>
                <a href="/php/Webdev/public/" target="_blank" class="btn-storefront">View Store</a>
            </div>
        </header>

        <script>
            const themeToggle = document.getElementById('theme-toggle');
            const sunIcon = themeToggle.querySelector('.sun-icon');
            const moonIcon = themeToggle.querySelector('.moon-icon');

            function updateIcons(theme) {
                if (theme === 'dark') {
                    sunIcon.style.display = 'block';
                    moonIcon.style.display = 'none';
                } else {
                    sunIcon.style.display = 'none';
                    moonIcon.style.display = 'block';
                }
            }

            // Init icons based on current theme
            updateIcons(document.documentElement.getAttribute('data-theme'));

            themeToggle.addEventListener('click', () => {
                const currentTheme = document.documentElement.getAttribute('data-theme');
                const newTheme = currentTheme === 'light' ? 'dark' : 'light';
                
                document.documentElement.setAttribute('data-theme', newTheme);
                localStorage.setItem('admin-theme', newTheme);
                updateIcons(newTheme);
            });
        </script>
        <div class="admin-content">
