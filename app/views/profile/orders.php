<div class="container py-8">
    <!-- Sidebar Overlay for Mobile -->
    <div class="sidebar-overlay" id="sidebar-overlay"></div>

    <!-- Mobile Profile Navigation Trigger -->
    <button class="mobile-profile-nav-trigger" id="mobile-profile-trigger">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>
        <span>Profile Menu</span>
    </button>

    <div class="profile-layout">
        <!-- Sidebar -->
        <aside class="profile-sidebar" id="profile-sidebar">
            <div class="user-avatar-section">
                <div class="avatar-wrapper">
                    <?php if(isset($data['user']['profile_picture']) && $data['user']['profile_picture']): ?>
                        <img src="/php/Webdev/public/<?= $data['user']['profile_picture'] ?>" alt="Avatar">
                    <?php else: ?>
                        <div class="avatar-placeholder"><?= strtoupper(substr($_SESSION['user_name'], 0, 1)) ?></div>
                    <?php endif; ?>
                </div>
                <h2 class="user-name"><?= $_SESSION['user_name'] ?></h2>
            </div>
            
            <nav class="profile-nav">
                <a href="/php/Webdev/public/profile">Account Settings</a>
                <a href="/php/Webdev/public/profile/orders" class="active">My Orders</a>
                <a href="/php/Webdev/public/profile/inbox">My Inbox</a>
                <a href="/php/Webdev/public/auth/logout" class="logout-link">Logout</a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="profile-content">
            <section class="profile-section">
                <h3>My Orders</h3>
                
                <div class="order-tabs">
                    <button class="tab-btn active" onclick="showTab('pending')">Pending (<?= count($data['pending']) ?>)</button>
                    <button class="tab-btn" onclick="showTab('shipped')">Shipped (<?= count($data['shipped']) ?>)</button>
                    <button class="tab-btn" onclick="showTab('delivered')">Delivered (<?= count($data['delivered']) ?>)</button>
                    <button class="tab-btn" onclick="showTab('completed')">Completed (<?= count($data['completed']) ?>)</button>
                    <button class="tab-btn" onclick="showTab('cancelled')">Cancelled (<?= count($data['cancelled']) ?>)</button>
                </div>

                <!-- Pending Tab -->
                <div id="pending" class="tab-content active">
                    <?php if(empty($data['pending'])): ?>
                        <div class="empty-state">No pending orders.</div>
                    <?php else: ?>
                        <?php foreach($data['pending'] as $order): ?>
                            <div class="order-card">
                                <div class="order-header">
                                    <span>Order #<?= $order['id'] ?></span>
                                    <span class="status-badge <?= $order['status'] ?>"><?= $order['status'] == 'processing' ? 'Payment Confirmed' : ucfirst($order['status']) ?></span>
                                </div>
                                <div class="order-body">
                                    <div>
                                        <p>Placed on: <?= date('M d, Y', strtotime($order['created_at'])) ?></p>
                                        <p class="order-total">Total: ₱<?= number_format($order['total_price'], 2) ?></p>
                                    </div>
                                    <a href="/php/Webdev/public/profile/order_details/<?= $order['id'] ?>" class="btn-outline">View Details</a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <!-- Shipped Tab -->
                <div id="shipped" class="tab-content">
                    <?php if(empty($data['shipped'])): ?>
                        <div class="empty-state">No shipped orders.</div>
                    <?php else: ?>
                        <?php foreach($data['shipped'] as $order): ?>
                            <div class="order-card">
                                <div class="order-header">
                                    <span>Order #<?= $order['id'] ?></span>
                                    <span class="status-badge shipped">Shipped</span>
                                </div>
                                <div class="order-body">
                                    <div>
                                        <p>Placed on: <?= date('M d, Y', strtotime($order['created_at'])) ?></p>
                                        <p class="order-total">Total: ₱<?= number_format($order['total_price'], 2) ?></p>
                                    </div>
                                    <a href="/php/Webdev/public/profile/order_details/<?= $order['id'] ?>" class="btn-outline">View Details</a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <!-- Delivered Tab -->
                <div id="delivered" class="tab-content">
                    <?php if(empty($data['delivered'])): ?>
                        <div class="empty-state">No orders for delivery confirmation.</div>
                    <?php else: ?>
                        <?php foreach($data['delivered'] as $order): ?>
                            <div class="order-card">
                                <div class="order-header">
                                    <span>Order #<?= $order['id'] ?></span>
                                    <span class="status-badge delivered">Delivered</span>
                                </div>
                                <div class="order-body">
                                    <div>
                                        <p>Placed on: <?= date('M d, Y', strtotime($order['created_at'])) ?></p>
                                        <p class="order-total">Total: ₱<?= number_format($order['total_price'], 2) ?></p>
                                    </div>
                                    <a href="/php/Webdev/public/profile/order_details/<?= $order['id'] ?>" class="btn-outline" style="border-color: #059669; color: #059669;">Confirm Receipt</a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <!-- Completed Tab -->
                <div id="completed" class="tab-content">
                    <?php if(empty($data['completed'])): ?>
                        <div class="empty-state">No completed orders.</div>
                    <?php else: ?>
                        <?php foreach($data['completed'] as $order): ?>
                            <div class="order-card">
                                <div class="order-header">
                                    <span>Order #<?= $order['id'] ?></span>
                                    <span class="status-badge delivered">Completed</span>
                                </div>
                                <div class="order-body">
                                    <div>
                                        <p>Placed on: <?= date('M d, Y', strtotime($order['created_at'])) ?></p>
                                        <p class="order-total">Total: ₱<?= number_format($order['total_price'], 2) ?></p>
                                    </div>
                                    <a href="/php/Webdev/public/profile/order_details/<?= $order['id'] ?>" class="btn-outline">View Details</a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <!-- Cancelled Tab -->
                <div id="cancelled" class="tab-content">
                    <?php if(empty($data['cancelled'])): ?>
                        <div class="empty-state">No cancelled orders.</div>
                    <?php else: ?>
                        <?php foreach($data['cancelled'] as $order): ?>
                            <div class="order-card">
                                <div class="order-header">
                                    <span>Order #<?= $order['id'] ?></span>
                                    <span class="status-badge cancelled">Cancelled</span>
                                </div>
                                <div class="order-body">
                                    <div>
                                        <p>Placed on: <?= date('M d, Y', strtotime($order['created_at'])) ?></p>
                                        <p class="order-total">Total: ₱<?= number_format($order['total_price'], 2) ?></p>
                                    </div>
                                    <a href="/php/Webdev/public/profile/order_details/<?= $order['id'] ?>" class="btn-outline">View Details</a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </section>
        </main>
    </div>
</div>

<script>
function showTab(tabId) {
    document.querySelectorAll('.tab-content').forEach(tab => tab.classList.remove('active'));
    document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
    document.getElementById(tabId).classList.add('active');
    event.currentTarget.classList.add('active');
}
</script>

<link rel="stylesheet" href="/php/Webdev/public/css/profile.css">
