<div style="margin-bottom: var(--spacing-3);">
    <a href="/php/Webdev/public/admin/orders" style="color: var(--admin-text-muted); text-decoration: none; display: inline-flex; align-items: center; gap: 8px; font-weight: 500; transition: color 0.2s;" onmouseover="this.style.color='var(--admin-accent)'" onmouseout="this.style.color='var(--admin-text-muted)'">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
        Back to Order List
    </a>
</div>

<div class="admin-card" style="max-width: 1000px; padding: 0; overflow: hidden; border: none; box-shadow: 0 10px 40px rgba(0,0,0,0.08);">
    <div style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); padding: 35px; color: white;">
        <div style="display: flex; justify-content: space-between; align-items: flex-start;">
            <div>
                <h2 style="font-size: 1.75rem; font-weight: 800; letter-spacing: -0.02em; margin-bottom: 5px;">Order #<?= $data['order']['id'] ?></h2>
                <p style="opacity: 0.8; font-size: 0.95rem;">Placed on <?= date('F j, Y, \a\t g:i a', strtotime($data['order']['created_at'])) ?></p>
            </div>
            <div style="text-align: right;">
                <span class="badge" style="background: rgba(255,255,255,0.2); color: white; padding: 8px 20px; border-radius: 12px; backdrop-filter: blur(10px); font-size: 0.85rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; display: inline-block; margin-bottom: 10px;">
                    <?= $data['order']['status'] ?>
                </span>
                <div style="font-size: 1.5rem; font-weight: 800; color: white;">₱<?= number_format($data['order']['total_price'], 2) ?></div>
            </div>
        </div>
    </div>

    <div style="padding: 40px; background: var(--admin-card);">
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 40px; margin-bottom: 40px;">
            <!-- Customer & Shipping Info -->
            <div style="display: flex; flex-direction: column; gap: 20px;">
                <div style="background: var(--admin-bg-soft); border: 1px solid var(--admin-border); border-radius: 16px; padding: 25px; flex-grow: 1;">
                    <h3 style="font-size: 0.9rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; color: var(--admin-text-muted); margin-bottom: 20px;">Customer Details</h3>
                    <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 15px;">
                        <div style="width: 44px; height: 44px; background: var(--admin-card); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.9rem; font-weight: 800; color: var(--admin-accent); border: 1px solid var(--admin-border);">
                            <?= strtoupper(substr($data['order']['user_name'], 0, 1)) ?>
                        </div>
                        <div>
                            <div style="font-size: 1rem; font-weight: 700; color: var(--admin-text-main);"><?= htmlspecialchars($data['order']['user_name']) ?></div>
                            <div style="font-size: 0.85rem; color: var(--admin-text-muted);"><?= htmlspecialchars($data['order']['user_email']) ?></div>
                        </div>
                    </div>
                </div>

                <div style="background: var(--admin-bg-soft); border: 1px solid var(--admin-border); border-radius: 16px; padding: 25px; flex-grow: 1;">
                    <h3 style="font-size: 0.9rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; color: var(--admin-text-muted); margin-bottom: 20px;">Fulfillment Information</h3>
                    <div style="margin-bottom: 15px;">
                        <label style="display: block; font-size: 0.75rem; font-weight: 800; color: var(--admin-text-muted); text-transform: uppercase; margin-bottom: 5px;">Shipping Address</label>
                        <div style="font-size: 0.95rem; color: var(--admin-text-main); line-height: 1.5; font-weight: 600;">
                            <?= !empty($data['order']['shipping_address']) ? nl2br(htmlspecialchars($data['order']['shipping_address'])) : '<i style="color:var(--admin-text-muted)">No address provided</i>' ?>
                        </div>
                    </div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                        <div>
                            <label style="display: block; font-size: 0.75rem; font-weight: 800; color: var(--admin-text-muted); text-transform: uppercase; margin-bottom: 5px;">Method</label>
                            <span style="background: var(--admin-card); padding: 5px 12px; border-radius: 8px; font-size: 0.85rem; font-weight: 700; border: 1px solid var(--admin-border);"><?= $data['order']['shipping_method'] ?? 'Standard' ?></span>
                        </div>
                        <div>
                            <label style="display: block; font-size: 0.75rem; font-weight: 800; color: var(--admin-text-muted); text-transform: uppercase; margin-bottom: 5px;">Payment</label>
                            <span style="background: var(--admin-card); padding: 5px 12px; border-radius: 8px; font-size: 0.85rem; font-weight: 700; border: 1px solid var(--admin-border);"><?= $data['order']['payment_method'] ?? 'N/A' ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Management -->
            <div style="display: flex; flex-direction: column;">
                <div style="background: var(--admin-bg-soft); border: 1px solid var(--admin-border); border-radius: 16px; padding: 25px; height: 100%;">
                    <h3 style="font-size: 0.9rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; color: var(--admin-text-muted); margin-bottom: 20px;">Order Status</h3>
                    
                    <?php if($data['order']['status'] == 'delivered' || $data['order']['status'] == 'cancelled'): ?>
                        <div style="text-align: center; padding: 20px; background: var(--admin-card); border-radius: 12px; border: 1px dashed var(--admin-border);">
                            <div style="font-size: 0.85rem; font-weight: 700; color: var(--admin-text-muted); text-transform: uppercase; margin-bottom: 10px;">Terminal State</div>
                            <span class="badge" style="background: <?= $data['order']['status'] == 'delivered' ? 'rgba(16, 185, 129, 0.1)' : 'rgba(239, 68, 68, 0.1)' ?>; color: <?= $data['order']['status'] == 'delivered' ? 'var(--admin-success)' : 'var(--admin-danger)' ?>; padding: 8px 20px; font-size: 0.9rem;">
                                <?= ucfirst($data['order']['status']) ?>
                            </span>
                            <p style="font-size: 0.8rem; color: var(--admin-text-muted); margin-top: 15px; line-height: 1.4;">This order is finalized and can no longer be updated.</p>
                        </div>
                    <?php else: ?>
                        <form action="/php/Webdev/public/admin/order_update" method="POST" style="display: flex; flex-direction: column; gap: 15px;">
                            <input type="hidden" name="order_id" value="<?= $data['order']['id'] ?>">
                            <select name="status" style="width: 100%; padding: 12px; border-radius: 10px; border: 1px solid var(--admin-border); background: var(--admin-card); color: var(--admin-text-main); font-family: inherit; font-weight: 600;">
                                <option value="pending" <?= $data['order']['status'] == 'pending' ? 'selected' : '' ?>>Pending</option>
                                <option value="processing" <?= $data['order']['status'] == 'processing' ? 'selected' : '' ?>>Processing</option>
                                <option value="shipped" <?= $data['order']['status'] == 'shipped' ? 'selected' : '' ?>>Shipped</option>
                                <option value="delivered" <?= $data['order']['status'] == 'delivered' ? 'selected' : '' ?>>Delivered</option>
                                <option value="cancelled" <?= $data['order']['status'] == 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                            </select>
                            <button type="submit" class="btn-admin btn-admin-primary" style="padding: 15px; border-radius: 10px; font-weight: 700;">Update Status</button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <h3 style="font-size: 1rem; font-weight: 800; color: var(--admin-text-main); margin-bottom: 20px;">Order Summary</h3>
        <div class="table-container" style="border: 1px solid var(--admin-border); background: var(--admin-card);">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th style="background: var(--admin-bg-soft); color: var(--admin-text-muted);">Product Details</th>
                        <th style="text-align: center; background: var(--admin-bg-soft); color: var(--admin-text-muted);">Price</th>
                        <th style="text-align: center; background: var(--admin-bg-soft); color: var(--admin-text-muted);">Qty</th>
                        <th style="text-align: right; background: var(--admin-bg-soft); color: var(--admin-text-muted);">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($data['items'] as $item): ?>
                    <tr>
                        <td style="border-bottom: 1px solid var(--admin-border);">
                            <div style="display: flex; align-items: center; gap: 15px;">
                                <img src="<?= htmlspecialchars($item['image_main']) ?>" style="width: 54px; height: 54px; object-fit: cover; border-radius: 10px; border: 1px solid var(--admin-border);">
                                <div>
                                    <div style="font-weight: 700; color: var(--admin-text-main);"><?= htmlspecialchars($item['name']) ?></div>
                                    <div style="font-size: 0.8rem; color: var(--admin-text-muted); font-weight: 600;">Size: <?= htmlspecialchars($item['size']) ?></div>
                                </div>
                            </div>
                        </td>
                        <td style="text-align: center; font-weight: 600; color: var(--admin-text-main); border-bottom: 1px solid var(--admin-border);">₱<?= number_format($item['price'], 2) ?></td>
                        <td style="text-align: center; font-weight: 600; color: var(--admin-text-main); border-bottom: 1px solid var(--admin-border);"><?= $item['quantity'] ?></td>
                        <td style="text-align: right; font-weight: 800; color: var(--admin-text-main); border-bottom: 1px solid var(--admin-border);">₱<?= number_format($item['price'] * $item['quantity'], 2) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            
            <div style="background: var(--admin-bg-soft); padding: 25px; display: flex; justify-content: flex-end; border-top: 1px solid var(--admin-border);">
                <div style="text-align: right;">
                    <div style="font-size: 0.9rem; font-weight: 700; color: var(--admin-text-muted); margin-bottom: 5px;">Total Paid Amount</div>
                    <div style="font-size: 1.5rem; font-weight: 900; color: var(--admin-accent);">₱<?= number_format($data['order']['total_price'], 2) ?></div>
                </div>
            </div>
        </div>
    </div>
</div>
