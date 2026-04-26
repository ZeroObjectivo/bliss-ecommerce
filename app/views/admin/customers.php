<div class="orders-header-row" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: var(--spacing-4);">
    <h2 style="margin: 0; font-size: 1.25rem;">Customer Management</h2>
    <div class="admin-actions-group" style="display: flex; gap: 12px; align-items: center;">
        <div class="admin-sort-container">
            <select id="customer-sort" onchange="sortCustomers()" class="admin-filter-control">
                <option value="newest">Newest Joined</option>
                <option value="oldest">Oldest Joined</option>
                <option value="name-asc">Name: A-Z</option>
                <option value="name-desc">Name: Z-A</option>
            </select>
        </div>
        <div class="admin-search-container">
            <input type="text" id="customer-search" placeholder="Search Name or Email..." 
                   class="admin-filter-control"
                   onkeyup="filterCustomers()">
        </div>
    </div>
</div>

<div class="admin-tabs" style="display: flex; gap: 8px; margin-bottom: var(--spacing-4); background: var(--admin-bg-soft); padding: 6px; border-radius: 12px; width: fit-content;">
    <button class="btn-admin active" id="tab-all" onclick="switchCustomerTab('all')" style="background: transparent; color: var(--admin-text-muted); border: none;">All (<?= count($data['all']) ?>)</button>
    <button class="btn-admin" id="tab-active" onclick="switchCustomerTab('active')" style="background: transparent; color: var(--admin-text-muted); border: none;">Active (<?= count($data['active']) ?>)</button>
    <button class="btn-admin" id="tab-suspended" onclick="switchCustomerTab('suspended')" style="background: transparent; color: var(--admin-text-muted); border: none;">Suspended (<?= count($data['suspended']) ?>)</button>
</div>

<!-- Customer Tables -->
<?php 
$tabs = ['all', 'active', 'suspended'];
foreach($tabs as $tab): 
?>
<div id="content-<?= $tab ?>" class="table-container customer-tab-content" style="<?= $tab == 'all' ? '' : 'display: none;' ?>">
    <table class="admin-table">
        <thead>
            <tr>
                <th>Customer</th>
                <th>Joined Date</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="tbody-<?= $tab ?>">
            <?php if(empty($data[$tab])): ?>
                <tr><td colspan="4" style="text-align:center; padding: 3rem; color: var(--admin-text-muted);">No customers found.</td></tr>
            <?php else: ?>
                <?php foreach($data[$tab] as $user): ?>
                <tr class="customer-row" data-date="<?= strtotime($user['created_at']) ?>" data-name="<?= htmlspecialchars($user['name']) ?>">
                    <td>
                        <div style="font-weight: 600; color: var(--admin-text-main);"><?= htmlspecialchars($user['name']) ?></div>
                        <div style="font-size: 0.75rem; color: var(--admin-text-muted);"><?= htmlspecialchars($user['email']) ?></div>
                    </td>
                    <td style="color: var(--admin-text-muted); font-size: 0.85rem;"><?= date('M d, Y', strtotime($user['created_at'])) ?></td>
                    <td>
                        <span class="badge" style="background: <?= $user['status'] == 'active' ? 'rgba(16, 185, 129, 0.1)' : 'rgba(239, 68, 68, 0.1)' ?>; color: <?= $user['status'] == 'active' ? 'var(--admin-success)' : 'var(--admin-danger)' ?>;">
                            <?= ucfirst($user['status']) ?>
                        </span>
                    </td>
                    <td>
                        <div style="display: flex; gap: 8px; align-items: center;">
                            <a href="/php/Webdev/public/admin/customer_detail/<?= $user['id'] ?>" class="btn-admin" style="background: var(--admin-card); border: 1px solid var(--admin-border); color: var(--admin-text-main); padding: 6px 10px; font-size: 0.75rem; text-decoration: none;">View</a>

                            <?php if ($_SESSION['admin_role'] === 'superadmin'): ?>
                                <form method="POST" action="/php/Webdev/public/superadmin/customer_status" style="margin:0;">
                                    <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                                    <input type="hidden" name="current_status" value="<?= $user['status'] ?>">
                                    <button type="submit" class="btn-admin" style="background: var(--admin-card); border: 1px solid var(--admin-border); color: <?= $user['status'] == 'active' ? 'var(--admin-warning)' : 'var(--admin-success)' ?>; padding: 6px 10px; font-size: 0.75rem;">
                                        <?= $user['status'] == 'active' ? 'Suspend' : 'Activate' ?>
                                    </button>
                                </form>
                                <form method="POST" action="/php/Webdev/public/superadmin/customer_reset_pass" style="margin:0;">
                                    <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                                    <button type="submit" class="btn-admin" style="background: var(--admin-card); border: 1px solid var(--admin-border); color: var(--admin-accent); padding: 6px 10px; font-size: 0.75rem;">Reset Pass</button>
                                </form>
                                <form method="POST" action="/php/Webdev/public/superadmin/customer_delete/<?= $user['id'] ?>" onsubmit="return confirm('This will permanently delete the customer and all associated data. This cannot be undone. Continue?');" style="margin:0;">
                                    <button type="submit" class="btn-admin btn-admin-danger" style="padding: 6px 10px; font-size: 0.75rem;">
                                        Delete
                                    </button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<?php endforeach; ?>

<script>
function switchCustomerTab(tab) {
    document.querySelectorAll('.customer-tab-content').forEach(el => el.style.display = 'none');
    document.querySelectorAll('.admin-tabs .btn-admin').forEach(el => {
        el.classList.remove('active');
        el.style.background = 'transparent';
        el.style.color = 'var(--admin-text-muted)';
        el.style.boxShadow = 'none';
    });

    const activeBtn = document.getElementById('tab-' + tab);
    const content = document.getElementById('content-' + tab);

    activeBtn.classList.add('active');
    activeBtn.style.background = 'var(--admin-card)';
    activeBtn.style.color = 'var(--admin-accent)';
    activeBtn.style.boxShadow = 'var(--shadow-sm)';
    content.style.display = 'block';
}

function filterCustomers() {
    const query = document.getElementById('customer-search').value.toLowerCase();
    const rows = document.querySelectorAll('.customer-row');
    
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(query) ? '' : 'none';
    });
}

function sortCustomers() {
    const sortBy = document.getElementById('customer-sort').value;
    const tabContents = ['tbody-all', 'tbody-active', 'tbody-suspended'];

    tabContents.forEach(tbodyId => {
        const tbody = document.getElementById(tbodyId);
        if (!tbody) return;
        
        const rows = Array.from(tbody.querySelectorAll('.customer-row'));
        if (rows.length === 0) return;

        rows.sort((a, b) => {
            if (sortBy === 'newest') return b.dataset.date - a.dataset.date;
            if (sortBy === 'oldest') return a.dataset.date - b.dataset.date;
            if (sortBy === 'name-asc') return a.dataset.name.localeCompare(b.dataset.name);
            if (sortBy === 'name-desc') return b.dataset.name.localeCompare(a.dataset.name);
        });

        rows.forEach(row => tbody.appendChild(row));
    });
}
</script>
