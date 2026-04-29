<!-- Stats Overview -->
<div class="overview-grid">
    <div class="admin-card stat-card">
        <div class="stat-icon" style="background: var(--admin-accent-soft); color: var(--admin-accent);">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"></path><line x1="3" y1="6" x2="21" y2="6"></line><path d="M16 10a4 4 0 0 1-8 0"></path></svg>
        </div>
        <div class="stat-info">
            <h3>Total Products</h3>
            <div class="stat"><?= number_format($data['total_products']) ?></div>
        </div>
    </div>
    <div class="admin-card stat-card">
        <div class="stat-icon" style="background: rgba(16, 185, 129, 0.1); color: var(--admin-success);">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
        </div>
        <div class="stat-info">
            <h3>Total Orders</h3>
            <div class="stat"><?= number_format($data['total_orders']) ?></div>
            <small style="color: <?= $data['pending_orders_count'] > 0 ? 'var(--admin-warning)' : 'inherit' ?>; font-weight: 600;">
                <?= $data['pending_orders_count'] ?> pending
            </small>
        </div>
    </div>
    <div class="admin-card stat-card">
        <div class="stat-icon" style="background: rgba(139, 92, 246, 0.1); color: #8b5cf6;">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1v22"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
        </div>
        <div class="stat-info">
            <h3>Total Revenue</h3>
            <div class="stat">₱<?= number_format($data['total_revenue'], 2) ?></div>
        </div>
    </div>
    <div class="admin-card stat-card">
        <div class="stat-icon" style="background: rgba(236, 72, 153, 0.1); color: #ec4899;">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
        </div>
        <div class="stat-info">
            <h3>Total Customers</h3>
            <div class="stat"><?= number_format($data['total_customers']) ?></div>
        </div>
    </div>
</div>

<!-- Quick Actions (Management Console) - Relocated to Middle -->
<div class="admin-card" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); border: none; margin-bottom: var(--spacing-4);">
    <h3 style="color: white; margin-bottom: var(--spacing-3); font-size: 0.9rem; border-bottom: 1px solid rgba(255,255,255,0.1); padding-bottom: 15px; text-transform: uppercase; letter-spacing: 0.025em;">Management Console</h3>
    
    <div class="quick-actions-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin-top: 15px;">
        <?php if ($_SESSION['admin_role'] === 'superadmin'): ?>
        <a href="/php/Webdev/public/superadmin/product_add" class="quick-action-btn dark">
            <div class="qa-icon" style="background: rgba(255,255,255,0.1); color: white;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
            </div>
            <span>New Listing</span>
        </a>
        <a href="/php/Webdev/public/superadmin/hero_settings" class="quick-action-btn dark">
            <div class="qa-icon" style="background: rgba(255,255,255,0.1); color: white;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
            </div>
            <span>Featured Editor</span>
        </a>
        <?php endif; ?>
        
        <a href="/php/Webdev/public/admin/orders" class="quick-action-btn dark">
            <div class="qa-icon" style="background: rgba(255,255,255,0.1); color: white;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
            </div>
            <span>Manage Orders</span>
        </a>
        
        <a href="/php/Webdev/public/admin/inbox" class="quick-action-btn dark">
            <div class="qa-icon" style="background: rgba(255,255,255,0.1); color: white;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
            </div>
            <span>Customer Inbox</span>
        </a>
    </div>
</div>

<div class="dashboard-main-grid">
    <!-- Left Column: Orders & Revenue -->
    <div style="display: flex; flex-direction: column; gap: var(--spacing-3);">
        <!-- Recent Orders -->
        <div class="admin-card" style="padding: 0;">
            <div style="padding: var(--spacing-3); border-bottom: 1px solid var(--admin-border); display: flex; justify-content: space-between; align-items: center;">
                <h3 style="color: var(--admin-text-main); font-size: 0.9rem; margin: 0;">Recent Orders</h3>
                <a href="/php/Webdev/public/admin/orders" style="font-size: 0.85rem; color: var(--admin-accent); text-decoration: none; font-weight: 600;">View All</a>
            </div>
            <div class="table-container no-border">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Customer</th>
                            <th>Status</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(empty($data['recent_orders'])): ?>
                            <tr><td colspan="4" style="text-align:center; padding: 2rem; color: var(--admin-text-muted);">No orders yet.</td></tr>
                        <?php else: ?>
                            <?php foreach($data['recent_orders'] as $order): ?>
                            <tr class="desktop-table-row">
                                <td style="font-weight: 600; color: var(--admin-accent);">#<?= $order['id'] ?></td>
                                <td style="font-weight: 500;"><?= htmlspecialchars($order['user_name']) ?></td>
                                <td>
                                    <span class="badge" style="background: <?= $order['status'] == 'delivered' ? 'rgba(16, 185, 129, 0.1)' : ($order['status'] == 'shipped' ? 'var(--admin-accent-soft)' : 'rgba(245, 158, 11, 0.1)') ?>; color: <?= $order['status'] == 'delivered' ? 'var(--admin-success)' : ($order['status'] == 'shipped' ? 'var(--admin-accent)' : 'var(--admin-warning)') ?>;">
                                        <?= ucfirst($order['status']) ?>
                                    </span>
                                </td>
                                <td style="font-weight: 600;">₱<?= number_format($order['total_price'], 2) ?></td>
                            </tr>
                            <div class="mobile-card order-card" data-date="<?= strtotime($order['created_at']) ?>" data-price="<?= $order['total_price'] ?>">
                                <div class="card-header">
                                    <div class="order-id" style="font-weight: 700; color: var(--admin-accent);">#<?= $order['id'] ?></div>
                                    <div class="customer-info">
                                        <div style="font-weight: 600; color: var(--admin-text-main);"><?= htmlspecialchars($order['user_name']) ?></div>
                                    </div>
                                    <div class="total-price" style="font-weight: 700;">₱<?= number_format($order['total_price'], 2) ?></div>
                                </div>
                                <div class="card-body">
                                    <div class="card-item">
                                        <span class="label">Status:</span>
                                        <span>
                                            <span class="badge" style="background: <?= $order['status'] == 'delivered' ? 'rgba(16, 185, 129, 0.1)' : ($order['status'] == 'shipped' ? 'var(--admin-accent-soft)' : 'rgba(245, 158, 11, 0.1)') ?>; color: <?= $order['status'] == 'delivered' ? 'var(--admin-success)' : ($order['status'] == 'shipped' ? 'var(--admin-accent)' : 'var(--admin-warning)') ?>;">
                                                <?= ucfirst($order['status']) ?>
                                            </span>
                                        </span>
                                    </div>
                                </div>
                                <div class="card-actions">
                                    <a href="/php/Webdev/public/admin/order_detail/<?= $order['id'] ?>" class="btn-admin" style="background: var(--admin-card); border: 1px solid var(--admin-border); color: var(--admin-text-main);">Details</a>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Revenue Breakdown -->
        <div class="admin-card">
            <h3 style="color: var(--admin-text-main); font-size: 0.9rem; margin-bottom: var(--spacing-3);">Revenue by Category</h3>
            <div class="revenue-grid">
                <?php foreach($data['revenue_by_category'] as $cat => $rev): ?>
                    <div class="revenue-item">
                        <div style="font-size: 0.75rem; font-weight: 700; color: var(--admin-text-muted); text-transform: uppercase; margin-bottom: 5px;"><?= $cat ?></div>
                        <div style="font-size: 1.1rem; font-weight: 800; color: var(--admin-text-main);">₱<?= number_format($rev, 0) ?></div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Right Column: Products & Customers -->
    <div style="display: flex; flex-direction: column; gap: var(--spacing-3);">
        <!-- Top Selling -->
        <div class="admin-card" style="padding: 0;">
            <div style="padding: var(--spacing-3); border-bottom: 1px solid var(--admin-border);">
                <h3 style="color: var(--admin-text-main); font-size: 0.9rem; margin: 0;">Top Selling Products</h3>
            </div>
            <div style="padding: 15px;">
                <div class="top-selling-grid">
                    <?php foreach($data['top_selling'] as $item): ?>
                        <article class="top-selling-card">
                            <img src="<?= htmlspecialchars($item['image_main']) ?>" alt="<?= htmlspecialchars($item['name']) ?>" class="top-selling-image">
                            <div class="top-selling-content">
                                <div class="top-selling-title"><?= htmlspecialchars($item['name']) ?></div>
                                <div class="top-selling-meta"><?= $item['sales_count'] ?> sales</div>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- Inventory Alerts -->
        <div class="admin-card" style="padding: 0;">
            <div style="padding: var(--spacing-3); border-bottom: 1px solid var(--admin-border); display: flex; justify-content: space-between; align-items: center;">
                <h3 style="color: var(--admin-text-main); font-size: 0.9rem; margin: 0; display: flex; align-items: center; gap: 8px;">
                    Inventory Alerts
                    <div class="info-tooltip-container">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="color: var(--admin-text-muted); cursor: help;"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
                        <div class="info-tooltip">
                            <div style="font-weight: 800; margin-bottom: 8px; font-size: 0.75rem; text-transform: uppercase; color: white;">Stock Legends</div>
                            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 5px;">
                                <div style="width: 8px; height: 8px; background: #ef4444; border-radius: 50%;"></div>
                                <span style="font-size: 0.8rem;">Critical (< 3)</span>
                            </div>
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <div style="width: 8px; height: 8px; background: #f59e0b; border-radius: 50%;"></div>
                                <span style="font-size: 0.8rem;">Low (3 - 10)</span>
                            </div>
                        </div>
                    </div>
                </h3>
            </div>
            <div style="padding: var(--spacing-3);">
                <div class="desktop-view-only">
                    <?php if(empty($data['low_stock_items'])): ?>
                        <div style="text-align: center; color: var(--admin-success); padding: 1rem;">
                            <p style="font-size: 0.8rem; font-weight: 600;">Stock levels healthy.</p>
                        </div>
                    <?php else: ?>
                        <div style="display: flex; flex-direction: column; gap: 12px;">
                            <?php foreach($data['low_stock_items'] as $item): ?>
                                <?php 
                                    $sizes = json_decode($item['sizes'], true);
                                    $total = array_sum($sizes);
                                    $statusColor = $total < 3 ? '#ef4444' : '#f59e0b';
                                ?>
                                <div style="display: flex; align-items: center; gap: 12px;">
                                    <div style="width: 8px; height: 8px; background: <?= $statusColor ?>; border-radius: 50%;"></div>
                                    <div style="font-size: 0.8rem; font-weight: 600; color: var(--admin-text-main); flex-grow: 1;"><?= htmlspecialchars($item['name']) ?></div>
                                    <div style="font-size: 0.75rem; font-weight: 700; color: <?= $statusColor ?>;"><?= $total ?> units</div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="mobile-card-list">
                    <?php if(empty($data['low_stock_items'])): ?>
                        <div class="mobile-card">
                            <div style="text-align: center; color: var(--admin-success); padding: 1rem;">
                                <p style="font-size: 0.8rem; font-weight: 600;">Stock levels healthy.</p>
                            </div>
                        </div>
                    <?php else: ?>
                        <?php foreach($data['low_stock_items'] as $item): ?>
                            <?php 
                                $sizes = json_decode($item['sizes'], true);
                                $total = array_sum($sizes);
                                $statusColor = $total < 3 ? '#ef4444' : '#f59e0b';
                            ?>
                            <div class="mobile-card">
                                <div class="card-header" style="border-bottom: none; padding-bottom: 0; margin-bottom: 0;">
                                    <div style="width: 8px; height: 8px; background: <?= $statusColor ?>; border-radius: 50%;"></div>
                                    <div class="product-info">
                                        <h3><?= htmlspecialchars($item['name']) ?></h3>
                                        <p class="stock-count" style="color: <?= $statusColor ?>;"><?= $total ?> units</p>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Recent Customers -->
        <div class="admin-card" style="padding: 0;">
            <div style="padding: var(--spacing-3); border-bottom: 1px solid var(--admin-border);">
                <h3 style="color: var(--admin-text-main); font-size: 0.9rem; margin: 0;">Recent Customers</h3>
            </div>
            <div style="padding: 15px;">
                <div class="desktop-view-only">
                    <?php foreach($data['recent_customers'] as $c): ?>
                        <div style="margin-bottom: 10px; display: flex; align-items: center; gap: 10px;">
                            <div style="width: 32px; height: 32px; background: var(--admin-bg-soft); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.7rem; font-weight: 800; color: var(--admin-accent); border: 1px solid var(--admin-border);">
                                <?= strtoupper(substr($c['name'], 0, 1)) ?>
                            </div>
                            <div>
                                <div style="font-size: 0.8rem; font-weight: 600;"><?= htmlspecialchars($c['name']) ?></div>
                                <div style="font-size: 0.7rem; color: var(--admin-text-muted);"><?= htmlspecialchars($c['email']) ?></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="mobile-card-list">
                    <?php foreach($data['recent_customers'] as $c): ?>
                        <div class="mobile-card customer-card">
                            <div class="card-header" style="border-bottom: none; padding-bottom: 0; margin-bottom: 0;">
                                <div style="width: 32px; height: 32px; background: var(--admin-bg-soft); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.7rem; font-weight: 800; color: var(--admin-accent); border: 1px solid var(--admin-border); flex-shrink: 0;">
                                    <?= strtoupper(substr($c['name'], 0, 1)) ?>
                                </div>
                                <div class="customer-info">
                                    <h3><?= htmlspecialchars($c['name']) ?></h3>
                                    <p class="email"><?= htmlspecialchars($c['email']) ?></p>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.dashboard-main-grid {
    display: grid; 
    grid-template-columns: 2fr 1fr; 
    gap: var(--spacing-3); 
    margin-bottom: var(--spacing-4);
}

.revenue-grid {
    display: grid; 
    grid-template-columns: repeat(auto-fill, minmax(140px, 1fr)); 
    gap: 15px;
}

.revenue-item {
    background: var(--admin-bg-soft); 
    padding: 15px; 
    border-radius: 12px; 
    border: 1px solid var(--admin-border);
}

.stat-card {
    display: flex;
    align-items: center;
    gap: 20px;
}

.stat-icon {
    width: 54px;
    height: 54px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.no-border {
    border: none !important;
}

.quick-action-btn {
    display: flex;
    flex-direction: row;
    align-items: center;
    gap: 15px;
    padding: 16px;
    background: var(--admin-card);
    border: 1px solid var(--admin-border);
    border-radius: 12px;
    color: var(--admin-text-main);
    text-decoration: none;
    transition: all 0.2s ease;
}

.quick-action-btn.dark {
    background: rgba(255,255,255,0.03);
    border-color: rgba(255,255,255,0.1);
    color: white;
}

.quick-action-btn.dark:hover {
    background: rgba(255,255,255,0.08);
    border-color: var(--admin-accent);
}

.qa-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.quick-action-btn span {
    font-size: 0.9rem;
    font-weight: 600;
}

.top-selling-grid {
    display: grid;
    gap: 14px;
    position: relative;
    z-index: 1;
}

.top-selling-card {
    position: relative;
    z-index: 1;
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 14px;
    background: var(--admin-bg-soft);
    border: 1px solid var(--admin-border);
    border-radius: 12px;
    min-width: 0;
    overflow: visible;
}

.top-selling-image {
    position: relative;
    z-index: 1;
    width: 52px;
    min-width: 52px;
    max-width: 52px;
    height: 52px;
    aspect-ratio: 1;
    object-fit: cover;
    border-radius: 10px;
    flex-shrink: 0;
}

.top-selling-content {
    position: relative;
    z-index: 2;
    flex: 1 1 auto;
    min-width: 0;
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.top-selling-title {
    font-size: 0.85rem;
    font-weight: 700;
    color: var(--admin-text-main);
    line-height: 1.3;
    min-width: 0;
    margin: 0;
    overflow-wrap: anywhere;
    word-break: break-word;
}

.top-selling-meta {
    font-size: 0.72rem;
    color: var(--admin-text-muted);
    text-transform: uppercase;
    letter-spacing: 0.04em;
    margin: 0;
}

/* Tooltip Styles */
.info-tooltip-container {
    position: relative;
    display: inline-flex;
    align-items: center;
}

.info-tooltip {
    position: absolute;
    bottom: calc(100% + 10px);
    left: 50%;
    transform: translateX(-50%) translateY(10px);
    background: #1e293b;
    color: white;
    padding: 12px 16px;
    border-radius: 12px;
    width: 160px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.2);
    z-index: 100;
    pointer-events: none;
    opacity: 0;
    transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
}

.info-tooltip::after {
    content: '';
    position: absolute;
    top: 100%;
    left: 50%;
    margin-left: -6px;
    border-width: 6px;
    border-style: solid;
    border-color: #1e293b transparent transparent transparent;
}

.info-tooltip-container:hover .info-tooltip {
    opacity: 1;
    transform: translateX(-50%) translateY(0);
}

@media (max-width: 1200px) {
    .dashboard-main-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 768px) {
    .quick-actions-grid {
        grid-template-columns: 1fr !important;
    }
    
    .revenue-grid {
        grid-template-columns: 1fr;
    }

    .top-selling-grid {
        gap: 12px;
    }

    .top-selling-card {
        flex-direction: column;
        align-items: flex-start;
        gap: 12px;
        padding: 12px;
    }

    .top-selling-image {
        width: 100%;
        min-width: 0;
        max-width: 100%;
        height: auto;
        max-height: 180px;
    }

    .top-selling-content {
        width: 100%;
    }

    .top-selling-title {
        font-size: 0.8rem;
        line-height: 1.4;
    }

    .top-selling-meta {
        font-size: 0.68rem;
    }
}
</style>
