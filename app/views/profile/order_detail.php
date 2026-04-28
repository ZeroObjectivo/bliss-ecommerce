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
                <div class="section-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; border-bottom: 1px solid #f1f5f9; padding-bottom: 1rem;">
                    <div style="display: flex; align-items: center; gap: 1rem;">
                        <a href="/php/Webdev/public/profile/orders" class="back-btn" style="color: #64748b; text-decoration: none; display: flex; align-items: center;">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                        </a>
                        <h3 style="margin: 0; border: none; padding: 0;">Order #<?= $data['order']['id'] ?></h3>
                    </div>
                    <?php 
                        $badgeClass = $data['order']['status'];
                        if ($badgeClass == 'completed') $badgeClass = 'delivered'; // use same green theme
                    ?>
                    <span class="status-badge <?= $badgeClass ?>"><?= $data['order']['status'] == 'processing' ? 'Payment Confirmed' : ucfirst($data['order']['status']) ?></span>
                </div>

                <!-- Progress Bar -->
                <div class="order-progress-container">
                    <?php 
                        $status = $data['order']['status'];
                        $steps = ['pending', 'processing', 'shipped', 'delivered', 'completed'];
                        $current_step = array_search($status, $steps);
                        if ($status == 'cancelled') $current_step = -1;
                    ?>
                    <div class="progress-track">
                        <div class="progress-line">
                            <div class="progress-line-fill" style="width: <?= $current_step >= 0 ? ($current_step / (count($steps)-1) * 100) : 0 ?>%;"></div>
                        </div>
                        
                        <?php foreach($steps as $index => $step): 
                            $class = '';
                            if ($current_step > $index) $class = 'completed';
                            elseif ($current_step == $index) $class = 'active';
                        ?>
                            <div class="step <?= $class ?>">
                                <div class="step-dot">
                                    <?php if($current_step > $index): ?>
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                    <?php else: ?>
                                        <?= $index + 1 ?>
                                    <?php endif; ?>
                                </div>
                                <span class="step-label">
                                    <?php 
                                        if ($step == 'processing') echo 'Paid';
                                        elseif ($step == 'completed') echo 'Completed';
                                        else echo ucfirst($step);
                                    ?>
                                </span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="order-details-grid">
                    <div class="order-items-card">
                        <h4 style="margin-top: 0; margin-bottom: 1rem; font-size: 1.1rem;">Items Ordered</h4>
                        <div class="items-list">
                            <?php foreach($data['items'] as $item): ?>
                                <div class="item-row">
                                    <div class="item-img">
                                        <img src="<?= htmlspecialchars($item['image_main']) ?>" alt="<?= htmlspecialchars($item['name']) ?>">
                                    </div>
                                    <div class="item-info" style="flex: 1;">
                                        <h5><?= htmlspecialchars($item['name']) ?></h5>
                                        <div class="item-meta">Size: <?= strtoupper($item['size']) ?> | Qty: <?= $item['quantity'] ?></div>
                                        <div style="font-weight: 600;">₱<?= number_format($item['price'], 2) ?></div>
                                    </div>
                                    <div class="item-subtotal" style="text-align: right;">
                                        <div style="font-weight: 700;">₱<?= number_format($item['price'] * $item['quantity'], 2) ?></div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="order-summary">
                            <div class="summary-row">
                                <span style="color: #64748b;">Subtotal</span>
                                <span>₱<?= number_format($data['order']['total_price'], 2) ?></span>
                            </div>
                            <div class="summary-row">
                                <span style="color: #64748b;">Shipping</span>
                                <span>Free</span>
                            </div>
                            <div class="summary-row summary-total">
                                <span>Total</span>
                                <span>₱<?= number_format($data['order']['total_price'], 2) ?></span>
                            </div>
                        </div>
                    </div>

                    <div class="order-info-sidebar">
                        <div class="info-card" style="background: #fff; border: 1px solid #f1f5f9; border-radius: 12px; padding: 1.5rem; margin-bottom: 1.5rem;">
                            <h4 style="margin-top: 0; margin-bottom: 1rem;">Shipping Details</h4>
                            <p style="margin: 0; font-size: 0.9rem; line-height: 1.6; color: #475569;">
                                <?= nl2br(htmlspecialchars($data['order']['shipping_address'])) ?>
                            </p>
                            <h4 style="margin-top: 1.5rem; margin-bottom: 1rem;">Payment Method</h4>
                            <p style="margin: 0; font-size: 0.9rem; color: #475569;">
                                <?= strtoupper(str_replace('_', ' ', $data['order']['payment_method'])) ?>
                            </p>
                        </div>

                        <?php if($data['order']['status'] == 'delivered'): ?>
                            <div class="action-card" style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 1.5rem; text-align: center;">
                                <h4 style="margin-top: 0; margin-bottom: 0.5rem;">Confirm Receipt</h4>
                                <p style="font-size: 0.85rem; color: #64748b; margin-bottom: 1.25rem;">Your order has arrived! Please confirm you've received all items.</p>
                                <form action="/php/Webdev/public/profile/confirm_receipt/<?= $data['order']['id'] ?>" method="POST">
                                    <button type="submit" class="btn-primary" style="width: 100%;">Confirm & Complete</button>
                                </form>
                            </div>
                        <?php endif; ?>

                        <?php if($data['order']['status'] == 'completed'): ?>
                            <div class="action-card" style="background: #ecfdf5; border: 1px solid #10b981; border-radius: 12px; padding: 1.5rem; text-align: center;">
                                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-bottom: 0.5rem;"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                                <h4 style="margin-top: 0; margin-bottom: 0.5rem; color: #065f46;">Order Completed</h4>
                                <p style="font-size: 0.85rem; color: #065f46; margin-bottom: 0;">Thank you for shopping with us! This transaction is complete.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </section>
        </main>
    </div>
</div>

<script>
// Mobile Navigation Toggle
document.addEventListener('DOMContentLoaded', function() {
    const mobileTrigger = document.getElementById('mobile-profile-trigger');
    const sidebar = document.getElementById('profile-sidebar');
    const overlay = document.getElementById('sidebar-overlay');

    if (mobileTrigger && sidebar && overlay) {
        mobileTrigger.addEventListener('click', () => {
            sidebar.classList.add('open');
            overlay.classList.add('active');
        });

        overlay.addEventListener('click', () => {
            sidebar.classList.remove('open');
            overlay.classList.remove('active');
        });
    }
});
</script>

<link rel="stylesheet" href="/php/Webdev/public/css/profile.css">
