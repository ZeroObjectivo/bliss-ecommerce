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

    <div style="padding: 40px; background: var(--admin-card);" class="order-detail-body">
        <div class="admin-grid-1-1" style="margin-bottom: 40px;">
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
                    
                    <?php if($data['order']['status'] == 'delivered' || $data['order']['status'] == 'completed' || $data['order']['status'] == 'cancelled'): ?>
                        <div style="text-align: center; padding: 20px; background: var(--admin-card); border-radius: 12px; border: 1px dashed var(--admin-border);">
                            <div style="font-size: 0.85rem; font-weight: 700; color: var(--admin-text-muted); text-transform: uppercase; margin-bottom: 10px;">Terminal State</div>
                            <span class="badge" style="background: <?= ($data['order']['status'] == 'delivered' || $data['order']['status'] == 'completed') ? 'rgba(16, 185, 129, 0.1)' : 'rgba(239, 68, 68, 0.1)' ?>; color: <?= ($data['order']['status'] == 'delivered' || $data['order']['status'] == 'completed') ? 'var(--admin-success)' : 'var(--admin-danger)' ?>; padding: 8px 20px; font-size: 0.9rem;">
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
                                <option value="completed" <?= $data['order']['status'] == 'completed' ? 'selected' : '' ?>>Completed</option>
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

<style>
.order-detail-body { padding: 40px; }
.order-detail-body {
    --order-summary-columns: minmax(0, 2.4fr) minmax(90px, 1fr) minmax(70px, 0.7fr) minmax(110px, 1fr);
    --order-summary-gap: 16px;
    --order-summary-row-space: 16px;
    --order-summary-cell-space: 16px;
}
.order-detail-body .table-container {
    box-sizing: border-box;
    padding: 0;
    overflow-x: hidden;
    overflow-y: hidden;
    width: 100%;
    max-width: 100%;
    min-width: 0;
}
.order-detail-body .admin-table {
    box-sizing: border-box;
    min-width: 0;
    width: 100%;
    max-width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    table-layout: fixed;
}
.order-detail-body .admin-table thead,
.order-detail-body .admin-table tbody {
    display: block;
    width: 100%;
}
.order-detail-body .admin-table thead tr,
.order-detail-body .admin-table tbody tr {
    display: grid;
    grid-template-columns: var(--order-summary-columns);
    gap: var(--order-summary-gap);
    align-items: start;
    width: 100%;
}
.order-detail-body .admin-table thead tr {
    margin-bottom: 8px;
}
.order-detail-body .admin-table td,
.order-detail-body .admin-table th {
    box-sizing: border-box;
    max-width: 100%;
    padding-left: 16px;
    padding-right: 16px;
    padding-top: var(--order-summary-cell-space);
    padding-bottom: var(--order-summary-cell-space);
    line-height: 1.6;
    vertical-align: top;
    overflow-wrap: break-word;
    word-break: break-word;
    word-wrap: break-word;
    hyphens: auto;
}
.order-detail-body .admin-table tbody tr {
    margin-bottom: var(--order-summary-row-space);
}
.order-detail-body .admin-table tbody tr:last-child {
    margin-bottom: 0;
}
.order-detail-body,
.order-detail-body div,
.order-detail-body span,
.order-detail-body p,
.order-detail-body h2,
.order-detail-body h3,
.order-detail-body td,
.order-detail-body th {
    min-width: 0;
    overflow-wrap: break-word;
    word-break: break-word;
}
.order-detail-body .admin-table td:first-child > div {
    display: grid !important;
    grid-template-columns: auto minmax(0, 1fr);
    gap: var(--order-summary-gap);
    align-items: start;
    width: 100%;
    max-width: 100%;
}
.order-detail-body .admin-table td:first-child > div > * {
    min-width: 0;
}
.order-detail-body .admin-table td > * {
    min-width: 0;
    max-width: 100%;
}
.order-detail-body .admin-table img {
    max-width: 100%;
    display: block;
}
.order-detail-body .table-container > div:last-child {
    box-sizing: border-box;
    width: 100%;
    max-width: 100%;
    padding-left: 16px !important;
    padding-right: 16px !important;
}
@media (max-width: 600px) {
    .order-detail-body { padding: 20px; }
    .order-detail-body {
        --order-summary-columns: minmax(0, 1fr);
        --order-summary-gap: 12px;
        --order-summary-row-space: 12px;
        --order-summary-cell-space: 14px;
    }
    .order-detail-body .table-container { padding: 0; }
    .order-detail-body .admin-table thead tr,
    .order-detail-body .admin-table tbody tr {
        gap: var(--order-summary-gap);
    }
    .order-detail-body .admin-table tbody tr {
        display: flex;
        flex-direction: column;
        align-items: stretch;
    }
    .order-detail-body .admin-table td,
    .order-detail-body .admin-table th {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        gap: var(--order-summary-gap);
        padding-left: 12px;
        padding-right: 12px;
        padding-top: 12px;
        padding-bottom: 12px;
        text-align: left !important;
    }
    .order-detail-body .admin-table tbody td::before {
        flex: 0 0 auto;
        width: auto;
        min-width: 0;
        max-width: 100%;
        font-size: 0.78rem;
        font-weight: 700;
        line-height: 1.4;
        color: var(--admin-text-muted);
        text-transform: uppercase;
        letter-spacing: 0.04em;
        content: "";
    }
    .order-detail-body .admin-table tbody td:nth-child(1)::before { content: "Product"; }
    .order-detail-body .admin-table tbody td:nth-child(2)::before { content: "Price"; }
    .order-detail-body .admin-table tbody td:nth-child(3)::before { content: "Qty"; }
    .order-detail-body .admin-table tbody td:nth-child(4)::before { content: "Subtotal"; }
    .order-detail-body .admin-table td:first-child {
        align-items: flex-start;
    }
    .order-detail-body .admin-table td > div,
    .order-detail-body .admin-table td > span,
    .order-detail-body .admin-table td > strong,
    .order-detail-body .admin-table td > b {
        flex: 1 1 auto;
        min-width: 0;
        max-width: 100%;
        width: 100%;
    }
    .order-detail-body .admin-table td:first-child > div {
        grid-template-columns: 54px minmax(0, 1fr);
        gap: var(--order-summary-gap);
        flex: 1 1 auto;
    }
    .order-detail-body .table-container > div:last-child {
        padding-left: 12px !important;
        padding-right: 12px !important;
    }
}
</style>
