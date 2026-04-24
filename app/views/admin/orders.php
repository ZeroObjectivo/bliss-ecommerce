<div class="orders-header-row" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: var(--spacing-4);">
    <h2 style="margin: 0; font-size: 1.25rem;">Order Management</h2>
    <div class="admin-actions-group" style="display: flex; gap: 12px; align-items: center;">
        <form action="/php/Webdev/public/admin/orders_clear_all" method="POST" style="margin:0;" onsubmit="return confirm('ARE YOU SURE? This will permanently delete ALL orders and order items. This action cannot be undone.');">
            <button type="submit" class="btn-admin" style="background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; padding: 8px 16px; font-weight: 600;">Delete All Orders</button>
        </form>
        <div class="admin-sort-container">
            <select id="order-sort" onchange="sortOrders()" class="admin-filter-control">
                <option value="newest">Newest First</option>
                <option value="oldest">Oldest First</option>
                <option value="price-high">Price: High to Low</option>
                <option value="price-low">Price: Low to High</option>
            </select>
        </div>
        <div class="admin-search-container">
            <input type="text" id="order-search" placeholder="Search Order #, Name..." 
                   class="admin-filter-control"
                   onkeyup="filterOrders()">
        </div>
    </div>
</div>

<div class="admin-tabs" style="display: flex; gap: 8px; margin-bottom: var(--spacing-4); background: var(--admin-bg-soft); padding: 6px; border-radius: 12px; width: fit-content;">
    <button class="btn-admin active" id="tab-all" onclick="switchAdminTab('all')" style="background: transparent; color: var(--admin-text-muted); border: none;">All (<?= count($data['all']) ?>)</button>
    <button class="btn-admin" id="tab-pending" onclick="switchAdminTab('pending')" style="background: transparent; color: var(--admin-text-muted); border: none;">Pending (<?= count($data['pending']) ?>)</button>
    <button class="btn-admin" id="tab-shipped" onclick="switchAdminTab('shipped')" style="background: transparent; color: var(--admin-text-muted); border: none;">Shipped (<?= count($data['shipped']) ?>)</button>
    <button class="btn-admin" id="tab-delivered" onclick="switchAdminTab('delivered')" style="background: transparent; color: var(--admin-text-muted); border: none;">Delivered (<?= count($data['delivered']) ?>)</button>
    <button class="btn-admin" id="tab-completed" onclick="switchAdminTab('completed')" style="background: transparent; color: var(--admin-text-muted); border: none;">Completed (<?= count($data['completed']) ?>)</button>
    <button class="btn-admin" id="tab-cancelled" onclick="switchAdminTab('cancelled')" style="background: transparent; color: var(--admin-text-muted); border: none;">Cancelled (<?= count($data['cancelled']) ?>)</button>
</div>

<!-- Table Templates (reusable helper for tabs) -->
<?php 
function renderOrderTable($orders, $tabId, $display = 'none') {
    $isEmpty = empty($orders);
?>
<div id="content-<?= $tabId ?>" class="table-container order-tab-content" style="display: <?= $display ?>;">
    <table class="admin-table">
        <thead>
            <tr>
                <th>Order #</th>
                <th>Customer</th>
                <th>Date</th>
                <th>Total</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody id="tbody-<?= $tabId ?>">
            <?php if($isEmpty): ?>
                <tr><td colspan="6" style="text-align:center; padding: 3rem; color: var(--admin-text-muted);">No orders found.</td></tr>
            <?php else: ?>
                <?php foreach($orders as $o): ?>
                <tr class="order-row" data-date="<?= strtotime($o['created_at']) ?>" data-price="<?= $o['total_price'] ?>">
                    <td style="font-weight: 700; color: var(--admin-accent);">#<?= $o['id'] ?></td>
                    <td>
                        <div style="font-weight: 600; color: var(--admin-text-main);"><?= htmlspecialchars($o['user_name']) ?></div>
                        <div style="font-size: 0.75rem; color: var(--admin-text-muted);"><?= htmlspecialchars($o['user_email']) ?></div>
                    </td>
                    <td style="color: var(--admin-text-muted); font-size: 0.85rem;"><?= date('M d, Y', strtotime($o['created_at'])) ?></td>
                    <td style="font-weight: 700;">₱<?= number_format($o['total_price'], 2) ?></td>
                    <td>
                        <?php if($o['status'] == 'delivered' || $o['status'] == 'completed' || $o['status'] == 'cancelled'): ?>
                            <?php 
                                $isSuccess = ($o['status'] == 'delivered' || $o['status'] == 'completed');
                                $bg = $isSuccess ? 'rgba(16, 185, 129, 0.1)' : 'rgba(239, 68, 68, 0.1)';
                                $fg = $isSuccess ? 'var(--admin-success)' : 'var(--admin-danger)';
                            ?>
                            <span class="badge" style="background: <?= $bg ?>; color: <?= $fg ?>; padding: 6px 12px;">
                                <?= ucfirst($o['status']) ?>
                            </span>
                        <?php else: ?>
                            <form method="POST" action="/php/Webdev/public/admin/order_update" style="margin:0; display:flex; gap:8px;">
                                <input type="hidden" name="order_id" value="<?= $o['id'] ?>">
                                <select name="status" class="admin-filter-control" style="padding: 4px 28px 4px 8px; font-size: 0.8rem; background-position: right 6px center;">
                                    <option value="pending" <?= $o['status'] == 'pending' ? 'selected' : '' ?>>Pending</option>
                                    <option value="processing" <?= $o['status'] == 'processing' ? 'selected' : '' ?>>Payment Confirmed</option>
                                    <option value="shipped" <?= $o['status'] == 'shipped' ? 'selected' : '' ?>>Shipped</option>
                                    <option value="delivered" <?= $o['status'] == 'delivered' ? 'selected' : '' ?>>Delivered</option>
                                    <option value="completed" <?= $o['status'] == 'completed' ? 'selected' : '' ?>>Completed</option>
                                    <option value="cancelled" <?= $o['status'] == 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                                </select>
                                <button type="submit" class="btn-admin btn-admin-primary" style="padding: 4px 10px; font-size: 0.75rem;">Save</button>
                            </form>
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="/php/Webdev/public/admin/order_detail/<?= $o['id'] ?>" class="btn-admin" style="background: var(--admin-card); border: 1px solid var(--admin-border); color: var(--admin-text-main); padding: 6px 12px; font-size: 0.8rem;">Details</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<?php } ?>

<?php 
    renderOrderTable($data['all'], 'all', 'block');
    renderOrderTable($data['pending'], 'pending');
    renderOrderTable($data['shipped'], 'shipped');
    renderOrderTable($data['delivered'], 'delivered');
    renderOrderTable($data['completed'], 'completed');
    renderOrderTable($data['cancelled'], 'cancelled');
?>

<script>
function switchAdminTab(tab) {
    document.querySelectorAll('.order-tab-content').forEach(el => el.style.display = 'none');
    document.querySelectorAll('.admin-tabs .btn-admin').forEach(el => {
        el.classList.remove('active');
        el.style.background = 'transparent';
        el.style.color = 'var(--admin-text-muted)';
        el.style.boxShadow = 'none';
    });

    const activeBtn = document.getElementById('tab-' + tab);
    const content = document.getElementById('content-' + tab);

    if (activeBtn) {
        activeBtn.classList.add('active');
        activeBtn.style.background = 'var(--admin-card)';
        activeBtn.style.color = 'var(--admin-accent)';
        activeBtn.style.boxShadow = 'var(--shadow-sm)';
    }
    if (content) {
        content.style.display = 'block';
    }
}

function filterOrders() {
    const query = document.getElementById('order-search').value.toLowerCase();
    const rows = document.querySelectorAll('.order-row');
    
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(query) ? '' : 'none';
    });
}

function sortOrders() {
    const sortBy = document.getElementById('order-sort').value;
    const tabContents = ['tbody-all', 'tbody-pending', 'tbody-shipped', 'tbody-delivered', 'tbody-completed', 'tbody-cancelled'];

    tabContents.forEach(tbodyId => {
        const tbody = document.getElementById(tbodyId);
        if (!tbody) return;
        
        const rows = Array.from(tbody.querySelectorAll('.order-row'));
        if (rows.length === 0) return;

        rows.sort((a, b) => {
            if (sortBy === 'newest') return b.dataset.date - a.dataset.date;
            if (sortBy === 'oldest') return a.dataset.date - b.dataset.date;
            if (sortBy === 'price-high') return b.dataset.price - a.dataset.price;
            if (sortBy === 'price-low') return a.dataset.price - b.dataset.price;
        });

        rows.forEach(row => tbody.appendChild(row));
    });
}
</script>
