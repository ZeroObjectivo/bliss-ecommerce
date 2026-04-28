<div class="flex-between" style="margin-bottom: var(--spacing-4);">
    <h2 style="margin: 0; font-size: 1.25rem;">Order Management</h2>
    <div class="admin-actions-group" style="display: flex; gap: 12px; align-items: center; flex-wrap: wrap;">
        <form action="/php/Webdev/public/admin/orders_clear_all" method="POST" style="margin:0;" onsubmit="return confirm('ARE YOU SURE? This will permanently delete ALL orders and order items. This action cannot be undone.');">
            <button type="submit" class="btn-admin" style="background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; padding: 8px 16px; font-weight: 600;">Delete All</button>
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
            <div class="search-group orders-search">
                <input type="text" id="order-search" placeholder="Search orders..." class="admin-filter-control">
                <button type="button" class="btn-admin btn-admin-primary orders-search-button" aria-label="Search orders">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <circle cx="11" cy="11" r="7"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                </button>
            </div>
        </div>
    </div>
</div>

<div class="admin-tabs-container" style="overflow-x: auto; margin-bottom: var(--spacing-4); -webkit-overflow-scrolling: touch;">
    <div class="admin-tabs" style="display: flex; gap: 8px; background: var(--admin-bg-soft); padding: 6px; border-radius: 12px; width: fit-content; min-width: 100%;">
        <button class="btn-admin active" id="tab-all" onclick="switchAdminTab('all')" style="background: transparent; color: var(--admin-text-muted); border: none; white-space: nowrap;">All (<?= count($data['all']) ?>)</button>
        <button class="btn-admin" id="tab-pending" onclick="switchAdminTab('pending')" style="background: transparent; color: var(--admin-text-muted); border: none; white-space: nowrap;">Pending (<?= count($data['pending']) ?>)</button>
        <button class="btn-admin" id="tab-shipped" onclick="switchAdminTab('shipped')" style="background: transparent; color: var(--admin-text-muted); border: none; white-space: nowrap;">Shipped (<?= count($data['shipped']) ?>)</button>
        <button class="btn-admin" id="tab-delivered" onclick="switchAdminTab('delivered')" style="background: transparent; color: var(--admin-text-muted); border: none; white-space: nowrap;">Delivered (<?= count($data['delivered']) ?>)</button>
        <button class="btn-admin" id="tab-completed" onclick="switchAdminTab('completed')" style="background: transparent; color: var(--admin-text-muted); border: none; white-space: nowrap;">Completed (<?= count($data['completed']) ?>)</button>
        <button class="btn-admin" id="tab-cancelled" onclick="switchAdminTab('cancelled')" style="background: transparent; color: var(--admin-text-muted); border: none; white-space: nowrap;">Cancelled (<?= count($data['cancelled']) ?>)</button>
    </div>
</div>

<?php
function renderOrderTable($orders, $tabId, $display = 'none') {
?>
<div id="content-<?= $tabId ?>" class="table-container order-tab-content" style="display: <?= $display ?>;">
    <table class="admin-table orders-table">
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
            <?php
            $data = [
                'orders' => $orders,
                'query' => '',
                'status' => $tabId
            ];
            require '../app/views/admin/partials/order_rows.php';
            ?>
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
const orderFilters = {
    status: 'all',
    query: ''
};

function switchAdminTab(tab) {
    orderFilters.status = tab;
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

    applyOrderFilters();
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
            return 0;
        });

        rows.forEach(row => tbody.appendChild(row));
    });
}

async function applyOrderFilters() {
    const searchInput = document.getElementById('order-search');
    const searchButton = document.querySelector('.orders-search .btn-admin');
    const activeTab = document.getElementById('content-' + orderFilters.status);
    const tbody = activeTab ? activeTab.querySelector('tbody') : null;

    if (!searchInput || !searchButton || !tbody) return;

    orderFilters.query = searchInput.value.trim();
    const originalContent = searchButton.innerHTML;

    searchButton.disabled = true;
    searchButton.textContent = 'Searching...';

    try {
        const params = new URLSearchParams({
            q: orderFilters.query,
            status: orderFilters.status
        });

        const response = await fetch(`/php/Webdev/public/admin/orders/search?${params.toString()}`, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        if (!response.ok) {
            throw new Error(`Search request failed: ${response.status}`);
        }

        tbody.innerHTML = await response.text();
    } catch (error) {
        console.error('Order search failed:', error);
    } finally {
        searchButton.disabled = false;
        searchButton.innerHTML = originalContent;
    }
}

(function () {
    const searchInput = document.getElementById('order-search');
    const searchButton = document.querySelector('.orders-search .btn-admin');

    if (!searchInput || !searchButton || searchButton.dataset.searchBound === 'true') return;

    const triggerSearch = function (e) {
        if (e) e.preventDefault();
        orderFilters.query = searchInput.value.trim();
        applyOrderFilters();
    };

    searchButton.addEventListener('click', triggerSearch);
    searchInput.addEventListener('keydown', function (e) {
        if (e.key === 'Enter') {
            triggerSearch(e);
        }
    });

    searchButton.dataset.searchBound = 'true';
})();
</script>
