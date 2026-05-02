<?php
$status = isset($data['status']) ? strtolower(trim((string) $data['status'])) : 'all';
$query = isset($data['query']) ? trim((string) $data['query']) : '';

$statusLabelMap = [
    'all' => 'orders',
    'pending' => 'pending orders',
    'shipped' => 'shipped orders',
    'delivered' => 'delivered orders',
    'completed' => 'completed orders',
    'cancelled' => 'cancelled orders',
];

$subject = $statusLabelMap[$status] ?? 'orders';
$queryLabel = $query !== '' ? "'" . htmlspecialchars($query, ENT_QUOTES, 'UTF-8') . "'" : '';
?>
<?php if (empty($data['orders'])): ?>
    <tr>
        <td colspan="6" class="orders-empty-cell">
            <div class="orders-empty-state">
                <div class="orders-empty-title">
                    <?php if ($query !== ''): ?>
                        No <?= $subject ?> found matching <?= $queryLabel ?>
                    <?php else: ?>
                        No <?= $subject ?> found
                    <?php endif; ?>
                </div>
                <div class="orders-empty-text">
                    <?php if ($query !== ''): ?>
                        Try a different order number, customer name, or email.
                    <?php else: ?>
                        Adjust the selected filter or check back later.
                    <?php endif; ?>
                </div>
            </div>
        </td>
    </tr>
<?php else: ?>
    <?php foreach ($data['orders'] as $o): ?>
        <tr class="order-row" data-date="<?= strtotime($o['created_at']) ?>" data-price="<?= (float) $o['total_price'] ?>">
            <td style="font-weight: 700; color: var(--admin-accent);">#<?= (int) $o['id'] ?></td>
            <td>
                <div style="font-weight: 600; color: var(--admin-text-main);"><?= htmlspecialchars($o['user_name']) ?></div>
                <div style="font-size: 0.75rem; color: var(--admin-text-muted);"><?= htmlspecialchars($o['user_email']) ?></div>
            </td>
            <td style="color: var(--admin-text-muted); font-size: 0.85rem;"><?= date('M d, Y', strtotime($o['created_at'])) ?></td>
            <td style="font-weight: 700;">₱<?= number_format($o['total_price'], 2) ?></td>
            <td>
                <?php 
                $terminalStatuses = ['completed', 'cancelled', 'Refunded', 'Return Rejected'];
                $isReturnProcess = in_array($o['status'], ['Return Requested', 'Return Approved', 'Return Rejected', 'Refunded']);
                ?>
                <?php if (in_array($o['status'], $terminalStatuses)): ?>
                    <?php
                        $isSuccess = ($o['status'] == 'completed' || $o['status'] == 'Refunded');
                        $bg = $isSuccess ? 'rgba(16, 185, 129, 0.1)' : 'rgba(239, 68, 68, 0.1)';
                        $fg = $isSuccess ? 'var(--admin-success)' : 'var(--admin-danger)';
                    ?>
                    <span class="badge" style="background: <?= $bg ?>; color: <?= $fg ?>; padding: 6px 12px;">
                        <?= ucfirst($o['status']) ?>
                    </span>
                <?php else: ?>
                    <form method="POST" action="/php/Webdev/public/admin/order_update" style="margin:0; display:flex; gap:8px;">
                        <input type="hidden" name="order_id" value="<?= (int) $o['id'] ?>">
                        <select name="status" class="admin-filter-control" style="padding: 4px 28px 4px 8px; font-size: 0.8rem; background-position: right 6px center;">
                            <?php if(!$isReturnProcess): ?>
                                <option value="pending" <?= $o['status'] == 'pending' ? 'selected' : '' ?>>Pending</option>
                                <option value="processing" <?= $o['status'] == 'processing' ? 'selected' : '' ?>>Payment Confirmed</option>
                                <option value="shipped" <?= $o['status'] == 'shipped' ? 'selected' : '' ?>>Shipped</option>
                                <option value="delivered" <?= $o['status'] == 'delivered' ? 'selected' : '' ?>>Delivered</option>
                                <option value="completed" <?= $o['status'] == 'completed' ? 'selected' : '' ?>>Completed</option>
                                <option value="cancelled" <?= $o['status'] == 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                            <?php else: ?>
                                <option value="Return Requested" <?= $o['status'] == 'Return Requested' ? 'selected' : '' ?>>Return Requested</option>
                                <option value="Return Approved" <?= $o['status'] == 'Return Approved' ? 'selected' : '' ?>>Approve Return</option>
                                <option value="Return Rejected" <?= $o['status'] == 'Return Rejected' ? 'selected' : '' ?>>Reject Return</option>
                                <option value="Refunded" <?= $o['status'] == 'Refunded' ? 'selected' : '' ?>>Mark as Refunded</option>
                            <?php endif; ?>
                        </select>
                        <button type="submit" class="btn-admin btn-admin-primary" style="padding: 4px 10px; font-size: 0.75rem;">Save</button>
                    </form>
                <?php endif; ?>
            </td>
            <td>
                <a href="/php/Webdev/public/admin/order_detail/<?= (int) $o['id'] ?>" class="btn-admin" style="background: var(--admin-card); border: 1px solid var(--admin-border); color: var(--admin-text-main); padding: 6px 12px; font-size: 0.8rem;">Details</a>
            </td>
        </tr>
    <?php endforeach; ?>
<?php endif; ?>
