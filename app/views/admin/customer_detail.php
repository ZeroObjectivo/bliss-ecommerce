<div style="margin-bottom: var(--spacing-3);">
    <a href="/php/Webdev/public/admin/customers" style="color: var(--admin-text-muted); text-decoration: none; display: inline-flex; align-items: center; gap: 8px; font-weight: 500; transition: color 0.2s;" onmouseover="this.style.color='var(--admin-accent)'" onmouseout="this.style.color='var(--admin-text-muted)'">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
        Back to Customers
    </a>
</div>

<style>
.customer-profile-layout {
    display: grid; 
    grid-template-columns: 350px 1fr; 
    gap: var(--spacing-4);
    align-items: start;
}

@media (max-width: 1024px) {
    .customer-profile-layout {
        grid-template-columns: 1fr;
    }
    
    .customer-sidebar {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: var(--spacing-4);
    }
}

@media (max-width: 768px) {
    .customer-sidebar {
        grid-template-columns: 1fr;
    }
}

.security-grid {
    display: grid; 
    grid-template-columns: 1fr; 
    gap: 15px;
}

@media (min-width: 1400px) {
    .security-grid {
        grid-template-columns: 1fr 1fr;
    }
}
</style>

<div class="customer-profile-layout">
    <!-- Sidebar: Profile Overview -->
    <div class="customer-sidebar" style="display: flex; flex-direction: column; gap: var(--spacing-4);">
        <div class="admin-card" style="text-align: center; padding: 40px 20px;">
            <div style="width: 100px; height: 100px; background: var(--admin-accent-soft); color: var(--admin-accent); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2.5rem; font-weight: 800; margin: 0 auto 20px; border: 4px solid white; box-shadow: var(--shadow-md);">
                <?= strtoupper(substr($data['customer']['name'], 0, 1)) ?>
            </div>
            <h2 style="font-size: 1.5rem; font-weight: 800; margin-bottom: 5px;"><?= htmlspecialchars($data['customer']['name']) ?></h2>
            <p style="color: var(--admin-text-muted); font-size: 0.9rem; margin-bottom: 25px;"><?= htmlspecialchars($data['customer']['email']) ?></p>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 25px;">
                <div style="background: var(--admin-bg-soft); padding: 15px; border-radius: 12px; border: 1px solid var(--admin-border);">
                    <div style="font-size: 0.7rem; text-transform: uppercase; color: var(--admin-text-muted); font-weight: 700; margin-bottom: 5px;">Status</div>
                    <span class="badge" style="background: <?= $data['customer']['status'] == 'active' ? 'rgba(16, 185, 129, 0.1)' : 'rgba(239, 68, 68, 0.1)' ?>; color: <?= $data['customer']['status'] == 'active' ? 'var(--admin-success)' : 'var(--admin-danger)' ?>;">
                        <?= ucfirst($data['customer']['status']) ?>
                    </span>
                </div>
                <div style="background: var(--admin-bg-soft); padding: 15px; border-radius: 12px; border: 1px solid var(--admin-border);">
                    <div style="font-size: 0.7rem; text-transform: uppercase; color: var(--admin-text-muted); font-weight: 700; margin-bottom: 5px;">Member Since</div>
                    <div style="font-weight: 700; font-size: 0.85rem;"><?= date('M Y', strtotime($data['customer']['created_at'])) ?></div>
                </div>
            </div>

            <?php if ($_SESSION['admin_role'] === 'superadmin'): ?>
                <form method="POST" action="/php/Webdev/public/superadmin/customer_status" style="width: 100%;">
                    <input type="hidden" name="user_id" value="<?= $data['customer']['id'] ?>">
                    <input type="hidden" name="current_status" value="<?= $data['customer']['status'] ?>">
                    <button type="submit" class="btn-admin w-100" style="padding: 12px; border-radius: 12px; background: <?= $data['customer']['status'] == 'active' ? 'white' : 'var(--admin-success)' ?>; color: <?= $data['customer']['status'] == 'active' ? 'var(--admin-danger)' : 'white' ?>; border: 1px solid <?= $data['customer']['status'] == 'active' ? 'var(--admin-danger)' : 'var(--admin-success)' ?>; font-weight: 700;">
                        <?= $data['customer']['status'] == 'active' ? 'Suspend Account' : 'Activate Account' ?>
                    </button>
                </form>
            <?php endif; ?>
        </div>

        <div class="admin-card">
            <h3 style="font-size: 0.9rem; text-transform: uppercase; letter-spacing: 0.05em; color: var(--admin-text-muted); margin-bottom: 20px;">Activity Information</h3>
            <div style="display: flex; flex-direction: column; gap: 15px;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span style="font-size: 0.85rem; color: var(--admin-text-muted);">Last Activity</span>
                    <span style="font-size: 0.85rem; font-weight: 600;"><?= $data['customer']['last_login'] ? date('M d, H:i', strtotime($data['customer']['last_login'])) : 'Never' ?></span>
                </div>
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span style="font-size: 0.85rem; color: var(--admin-text-muted);">Total Orders</span>
                    <span style="font-size: 0.85rem; font-weight: 600;"><?= count($data['orders']) ?></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content: Details & History -->
    <div style="display: flex; flex-direction: column; gap: var(--spacing-4);">
        <!-- Security Questions -->
        <div class="admin-card">
            <h3 style="font-size: 1.1rem; font-weight: 800; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: var(--admin-accent);"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                Security Credentials
            </h3>
            <div style="display: grid; grid-template-columns: 1fr; gap: 15px;">
                <?php for($i=1; $i<=3; $i++): ?>
                    <?php if($data['customer']["security_q$i"]): ?>
                    <div style="background: var(--admin-bg-soft); padding: 18px; border-radius: 16px; border: 1px solid var(--admin-border);">
                        <div style="font-size: 0.75rem; font-weight: 700; color: var(--admin-text-muted); text-transform: uppercase; margin-bottom: 8px;">Question <?= $i ?></div>
                        <div style="font-weight: 700; margin-bottom: 10px; color: var(--admin-text-main);"><?= htmlspecialchars($data['customer']["security_q$i"]) ?></div>
                        <div style="font-size: 0.75rem; font-weight: 700; color: var(--admin-text-muted); text-transform: uppercase; margin-bottom: 4px;">Stored Answer</div>
                        <div style="font-family: monospace; background: white; padding: 8px 12px; border-radius: 8px; border: 1px solid var(--admin-border); display: inline-block; color: var(--admin-accent); font-weight: 600;">
                            <?= htmlspecialchars($data['customer']["security_a$i"]) ?>
                        </div>
                    </div>
                    <?php endif; ?>
                <?php endfor; ?>
                <?php if(!$data['customer']['security_q1']): ?>
                    <div style="text-align: center; padding: 20px; color: var(--admin-text-muted); background: var(--admin-bg-soft); border-radius: 16px; border: 1px dashed var(--admin-border);">
                        No security questions set for this account.
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Address Book -->
        <div class="admin-card">
            <h3 style="font-size: 1.1rem; font-weight: 800; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: var(--admin-accent);"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                Address Book
            </h3>
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 15px;">
                <?php if(empty($data['addresses'])): ?>
                    <div style="grid-column: 1/-1; text-align: center; padding: 30px; color: var(--admin-text-muted); background: var(--admin-bg-soft); border-radius: 16px;">
                        No addresses registered.
                    </div>
                <?php else: ?>
                    <?php foreach($data['addresses'] as $addr): ?>
                    <div style="background: var(--admin-bg-soft); padding: 20px; border-radius: 16px; border: 1px solid <?= $addr['is_default'] ? 'var(--admin-accent)' : 'var(--admin-border)' ?>; position: relative;">
                        <?php if($addr['is_default']): ?>
                            <span style="position: absolute; top: 15px; right: 15px; background: var(--admin-accent); color: white; font-size: 0.65rem; padding: 4px 8px; border-radius: 6px; font-weight: 800; text-transform: uppercase;">Default</span>
                        <?php endif; ?>
                        <div style="font-weight: 800; font-size: 0.8rem; text-transform: uppercase; color: var(--admin-text-muted); margin-bottom: 10px;"><?= htmlspecialchars($addr['category']) ?></div>
                        <div style="font-weight: 700; color: var(--admin-text-main); margin-bottom: 5px;"><?= htmlspecialchars($addr['street_address']) ?></div>
                        <div style="font-size: 0.9rem; color: var(--admin-text-muted);"><?= htmlspecialchars($addr['city']) ?>, <?= htmlspecialchars($addr['province']) ?> <?= htmlspecialchars($addr['postal_code']) ?></div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- Order History -->
        <div class="admin-card" style="padding: 0;">
            <div style="padding: 25px; border-bottom: 1px solid var(--admin-border);">
                <h3 style="font-size: 1.1rem; font-weight: 800; margin: 0; display: flex; align-items: center; gap: 10px;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: var(--admin-accent);"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
                    Recent Orders
                </h3>
            </div>
            <div class="table-container no-border">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Total</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(empty($data['orders'])): ?>
                            <tr><td colspan="5" style="text-align: center; padding: 30px; color: var(--admin-text-muted);">No orders placed yet.</td></tr>
                        <?php else: ?>
                            <?php foreach($data['orders'] as $order): ?>
                            <tr>
                                <td style="font-weight: 700; color: var(--admin-accent);">#<?= $order['id'] ?></td>
                                <td><?= date('M d, Y', strtotime($order['created_at'])) ?></td>
                                <td>
                                    <span class="badge" style="background: var(--admin-accent-soft); color: var(--admin-accent);">
                                        <?= ucfirst($order['status']) ?>
                                    </span>
                                </td>
                                <td style="font-weight: 700;">₱<?= number_format($order['total_price'], 2) ?></td>
                                <td><a href="/php/Webdev/public/admin/order_detail/<?= $order['id'] ?>" class="btn-admin" style="font-size: 0.7rem; padding: 6px 12px; background: white; border: 1px solid var(--admin-border);">Details</a></td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
