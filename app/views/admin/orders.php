<div class="orders-header-container header-toggle" onclick="toggleSection('orders-content', this)" style="padding: 0 var(--spacing-2); margin-bottom: 50px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 24px; cursor: pointer;">
    <div style="display: flex; align-items: center; gap: 20px; min-width: 300px;">
        <div style="width: 56px; height: 56px; background: rgba(59, 130, 246, 0.1); border-radius: 16px; display: flex; align-items: center; justify-content: center; color: #3b82f6; flex-shrink: 0;">
            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"></path><line x1="3" y1="6" x2="21" y2="6"></line><path d="M16 10a4 4 0 0 1-8 0"></path></svg>
        </div>
        <div>
            <h2 style="margin: 0; font-size: 1.5rem; font-weight: 900; color: var(--admin-text-main); text-transform: uppercase; letter-spacing: 0.08em; line-height: 1.2;">Order Management</h2>
            <p style="margin: 5px 0 0 0; font-size: 0.85rem; color: var(--admin-text-muted); font-weight: 600; letter-spacing: 0.01em;">Process customer orders and track fulfillment status</p>
        </div>
    </div>
    <div style="display: flex; align-items: center; gap: 15px;">
        <div class="orders-status-badge">
            <span class="badge" style="background: #3b82f6; color: white; border: none; padding: 12px 28px; border-radius: 14px; font-size: 0.85rem; font-weight: 800; letter-spacing: 0.05em; box-shadow: 0 10px 25px rgba(59, 130, 246, 0.3); display: inline-flex; align-items: center; gap: 10px; white-space: nowrap;">
                 <?= count($data['all']) ?> TOTAL ORDERS
            </span>
        </div>
        <div class="chevron-icon" style="color: var(--admin-text-muted); transition: transform 0.3s ease;">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>
        </div>
    </div>
</div>

<div id="orders-content" class="collapsible-content expanded">
    <!-- Order Controls -->
    <div style="margin-bottom: var(--spacing-4); padding: 0 var(--spacing-2); display: flex; gap: 12px; align-items: center; flex-wrap: wrap; justify-content: flex-start;">
        <form action="/php/Webdev/public/admin/orders_clear_all" method="POST" style="margin:0;" onsubmit="return confirm('ARE YOU SURE? This will permanently delete ALL orders and order items. This action cannot be undone.');">
            <button type="submit" class="btn-admin" style="background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; padding: 10px 20px; font-weight: 600; border-radius: 12px; height: 40px; display: flex; align-items: center;">Delete All</button>
        </form>
        <div class="admin-sort-container">
            <select id="order-sort" onchange="sortOrders()" class="admin-filter-control">
                <option value="newest">Newest First</option>
                <option value="oldest">Oldest First</option>
                <option value="price-high">Price: High to Low</option>
                <option value="price-low">Price: Low to High</option>
            </select>
        </div>
        <div class="admin-search-container" style="flex: 0 1 320px; min-width: 220px;">
            <div class="search-group orders-search">
                <input type="text" id="order-search" placeholder="Search orders..." class="admin-filter-control">
                <button type="button" class="btn-admin btn-admin-primary orders-search-button" aria-label="Search orders" style="background: #3b82f6; border-color: #3b82f6; color: white; border-radius: 12px; height: 40px; width: 40px; display: flex; align-items: center; justify-content: center;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <circle cx="11" cy="11" r="7"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div class="admin-tabs-container" style="overflow-x: auto; margin-bottom: var(--spacing-8); -webkit-overflow-scrolling: touch;">
        <div class="admin-tabs" style="display: flex; gap: 8px; background: var(--admin-bg-soft); padding: 8px; border-radius: 16px; width: fit-content; min-width: 100%;">
            <button class="btn-admin active" id="tab-all" onclick="switchAdminTab('all')" style="background: transparent; color: var(--admin-text-muted); border: none; white-space: nowrap; padding: 12px 24px;">All (<?= count($data['all']) ?>)</button>
            <button class="btn-admin" id="tab-pending" onclick="switchAdminTab('pending')" style="background: transparent; color: var(--admin-text-muted); border: none; white-space: nowrap; padding: 12px 24px;">Pending (<?= count($data['pending']) ?>)</button>
            <button class="btn-admin" id="tab-shipped" onclick="switchAdminTab('shipped')" style="background: transparent; color: var(--admin-text-muted); border: none; white-space: nowrap; padding: 12px 24px;">Shipped (<?= count($data['shipped']) ?>)</button>
            <button class="btn-admin" id="tab-delivered" onclick="switchAdminTab('delivered')" style="background: transparent; color: var(--admin-text-muted); border: none; white-space: nowrap; padding: 12px 24px;">Delivered (<?= count($data['delivered']) ?>)</button>
            <button class="btn-admin" id="tab-completed" onclick="switchAdminTab('completed')" style="background: transparent; color: var(--admin-text-muted); border: none; white-space: nowrap; padding: 12px 24px;">Completed (<?= count($data['completed']) ?>)</button>
            <button class="btn-admin" id="tab-cancelled" onclick="switchAdminTab('cancelled')" style="background: transparent; color: var(--admin-text-muted); border: none; white-space: nowrap; padding: 12px 24px;">Cancelled (<?= count($data['cancelled']) ?>)</button>
        </div>
    </div>

    <?php
    function renderOrderTable($orders, $tabId, $display = 'none') {
    ?>
    <div id="content-<?= $tabId ?>" class="table-container order-tab-content" style="display: <?= $display ?>; border-radius: 24px; padding: 10px;">
        <table class="admin-table orders-table" style="border-collapse: separate; border-spacing: 0 8px; margin: -8px 0;">
            <thead>
                <tr>
                    <th style="padding: 20px 25px; border-radius: 16px 0 0 16px; border: none;">Order #</th>
                    <th style="padding: 20px 15px; border: none;">Customer</th>
                    <th style="padding: 20px 15px; border: none;">Date</th>
                    <th style="padding: 20px 15px; border: none;">Total</th>
                    <th style="padding: 20px 15px; border: none;">Status</th>
                    <th style="padding: 20px 25px; border-radius: 0 16px 16px 0; border: none; text-align: right;">Action</th>
                </tr>
            </thead>
            <tbody id="tbody-<?= $tabId ?>">
                <tr style="height: 15px;"><td colspan="6" style="padding: 0; border: none;"></td></tr>
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
</div>

<!-- Returns & Refunds Section -->
<div style="margin-top: 100px; margin-bottom: var(--spacing-8);">
    <div class="returns-header-container header-toggle" onclick="toggleSection('returns-content', this)" style="padding: 0 var(--spacing-2); margin-bottom: 50px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 24px; cursor: pointer;">
        <div style="display: flex; align-items: center; gap: 20px; min-width: 300px;">
            <div style="width: 56px; height: 56px; background: rgba(59, 130, 246, 0.1); border-radius: 16px; display: flex; align-items: center; justify-content: center; color: #3b82f6; flex-shrink: 0;">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg>
            </div>
            <div>
                <h3 style="margin: 0; font-size: 1.5rem; font-weight: 900; color: var(--admin-text-main); text-transform: uppercase; letter-spacing: 0.08em; line-height: 1.2;">Returns & Refunds</h3>
                <p style="margin: 5px 0 0 0; font-size: 0.85rem; color: var(--admin-text-muted); font-weight: 600; letter-spacing: 0.01em;">Manage product returns and customer refund requests</p>
            </div>
        </div>
        <div style="display: flex; align-items: center; gap: 15px;">
            <div class="returns-status-badge">
                <span class="badge" style="background: #3b82f6; color: white; border: none; padding: 12px 28px; border-radius: 14px; font-size: 0.85rem; font-weight: 800; letter-spacing: 0.05em; box-shadow: 0 10px 25px rgba(59, 130, 246, 0.3); display: inline-flex; align-items: center; gap: 10px; white-space: nowrap;">
                    <span class="pulse-dot"></span>
                    <?= count($data['returns']) ?> <?= count($data['returns']) === 1 ? 'ACTIVE REQUEST' : 'ACTIVE REQUESTS' ?>
                </span>
            </div>
            <div class="chevron-icon rotated" style="color: var(--admin-text-muted); transition: transform 0.3s ease;">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>
            </div>
        </div>
    </div>

    <div id="returns-content" class="collapsible-content">
        <!-- Returns Controls -->
        <div style="margin-bottom: var(--spacing-4); padding: 0 var(--spacing-2); display: flex; gap: 12px; align-items: center; flex-wrap: wrap; justify-content: flex-start;">
            <div class="admin-sort-container">
                <select id="return-sort" onchange="sortReturns()" class="admin-filter-control">
                    <option value="newest">Newest First</option>
                    <option value="oldest">Oldest First</option>
                    <option value="price-high">Price: High to Low</option>
                    <option value="price-low">Price: Low to High</option>
                </select>
            </div>
            <div class="admin-search-container" style="flex: 0 1 320px; min-width: 220px;">
                <div class="search-group returns-search">
                    <input type="text" id="return-search" placeholder="Search returns..." class="admin-filter-control">
                    <button type="button" class="btn-admin btn-admin-primary returns-search-button" aria-label="Search returns" style="background: #3b82f6; border-color: #3b82f6; color: white;">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <circle cx="11" cy="11" r="7"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Returns Tabs -->
        <div class="admin-tabs-container" style="overflow-x: auto; margin-bottom: var(--spacing-6); padding: 0 var(--spacing-2); -webkit-overflow-scrolling: touch;">
            <div class="admin-tabs returns-tabs-list" style="display: flex; gap: 8px; background: var(--admin-bg-soft); padding: 6px; border-radius: 12px; width: fit-content;">
                <button class="btn-admin active" id="tab-returns" onclick="switchReturnsTab('returns')" style="background: var(--admin-card); color: var(--admin-accent); border: none; white-space: nowrap; padding: 10px 20px; box-shadow: var(--shadow-sm);">All Returns (<?= count($data['returns']) ?>)</button>
                <button class="btn-admin" id="tab-returns_pending" onclick="switchReturnsTab('returns_pending')" style="background: transparent; color: var(--admin-text-muted); border: none; white-space: nowrap; padding: 10px 20px;">Pending (<?= count($data['returns_pending']) ?>)</button>
                <button class="btn-admin" id="tab-returns_approved" onclick="switchReturnsTab('returns_approved')" style="background: transparent; color: var(--admin-text-muted); border: none; white-space: nowrap; padding: 10px 20px;">Approved (<?= count($data['returns_approved']) ?>)</button>
                <button class="btn-admin" id="tab-returns_resolved" onclick="switchReturnsTab('returns_resolved')" style="background: transparent; color: var(--admin-text-muted); border: none; white-space: nowrap; padding: 10px 20px;">Resolved (<?= count($data['returns_resolved']) ?>)</button>
            </div>
        </div>
        
        <div class="admin-card" style="border: 1px solid var(--admin-border); background: var(--admin-card); box-shadow: 0 20px 50px rgba(0,0,0,0.06); border-radius: 28px; overflow: hidden; padding: 10px;">
            <div class="table-container" style="display: block; padding: 0; border: none; background: transparent; box-shadow: none; border-radius: 20px;">
                <table class="admin-table orders-table" style="border: none; border-collapse: separate; border-spacing: 0 8px; margin: -8px 0;">
                    <thead>
                        <tr>
                            <th style="background: var(--admin-bg-soft); padding: 20px 25px; border-radius: 16px 0 0 16px; color: var(--admin-text-muted); font-weight: 800; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.08em; border: none;">Order #</th>
                            <th style="background: var(--admin-bg-soft); padding: 20px 15px; color: var(--admin-text-muted); font-weight: 800; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.08em; border: none;">Customer</th>
                            <th style="background: var(--admin-bg-soft); padding: 20px 15px; color: var(--admin-text-muted); font-weight: 800; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.08em; border: none;">Date</th>
                            <th style="background: var(--admin-bg-soft); padding: 20px 15px; color: var(--admin-text-muted); font-weight: 800; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.08em; border: none;">Total</th>
                            <th style="background: var(--admin-bg-soft); padding: 20px 15px; color: var(--admin-text-muted); font-weight: 800; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.08em; border: none;">Status</th>
                            <th style="background: var(--admin-bg-soft); padding: 20px 25px; border-radius: 0 16px 16px 0; color: var(--admin-text-muted); font-weight: 800; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.08em; border: none; text-align: right;">Action</th>
                        </tr>
                    </thead>
                    <?php
                    function renderReturnsTbody($orders, $statusId, $display = 'none') {
                        global $data; 
                    ?>
                    <tbody id="tbody-<?= $statusId ?>" class="returns-tab-content" style="display: <?= $display ?>;">
                        <tr style="height: 15px;"><td colspan="6" style="padding: 0; border: none;"></td></tr>
                        <?php
                        $originalData = $data;
                        $data = [
                            'orders' => $orders,
                            'query' => '',
                            'status' => $statusId
                        ];
                        require '../app/views/admin/partials/order_rows.php';
                        $data = $originalData;
                        ?>
                    </tbody>
                    <?php } ?>

                    <?php
                    renderReturnsTbody($data['returns'], 'returns', 'table-row-group');
                    renderReturnsTbody($data['returns_pending'], 'returns_pending');
                    renderReturnsTbody($data['returns_approved'], 'returns_approved');
                    renderReturnsTbody($data['returns_resolved'], 'returns_resolved');
                    ?>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
/* Collapsible Sections */
.collapsible-content {
    display: none;
    opacity: 0;
    transition: opacity 0.3s ease;
}
.collapsible-content.expanded {
    display: block;
    opacity: 1;
}
.header-toggle:hover {
    opacity: 0.8;
}
.chevron-icon.rotated {
    transform: rotate(-180deg);
}

/* Add extra padding to returns section table cells for less compression */
.returns-tab-content td {
    padding: 22px 15px !important;
}
.returns-tab-content td:first-child {
    padding-left: 25px !important;
}
.returns-tab-content td:last-child {
    padding-right: 25px !important;
    text-align: right;
}

.pulse-dot {
    width: 8px;
    height: 8px;
    background: white;
    border-radius: 50%;
    display: inline-block;
    animation: badge-pulse 2s infinite;
}

.returns-search-button {
    width: 40px !important;
    min-width: 40px !important;
    max-width: 40px !important;
    height: 40px !important;
    padding: 0 !important;
    border-radius: 12px !important;
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
}

.returns-search-button svg {
    flex-shrink: 0;
}

@keyframes badge-pulse {
    0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(255, 255, 255, 0.7); }
    70% { transform: scale(1); box-shadow: 0 0 0 10px rgba(255, 255, 255, 0); }
    100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(255, 255, 255, 0); }
}

@media (max-width: 768px) {
    .returns-header-container {
        flex-direction: column;
        align-items: flex-start !important;
        gap: 20px !important;
    }
    .returns-status-badge {
        width: 100%;
    }
    .returns-status-badge .badge {
        width: 100%;
        justify-content: center;
    }
}
</style>

<script>
const orderFilters = {
    status: 'all',
    query: ''
};

const returnsFilters = {
    status: 'returns',
    query: ''
};

function toggleSection(contentId, headerElement) {
    const content = document.getElementById(contentId);
    const chevron = headerElement.querySelector('.chevron-icon');

    if (content.classList.contains('expanded')) {
        content.classList.remove('expanded');
        if(chevron) chevron.classList.add('rotated');
    } else {
        content.classList.add('expanded');
        if(chevron) chevron.classList.remove('rotated');
    }
}

function switchAdminTab(tab) {
    orderFilters.status = tab;
    document.querySelectorAll('.order-tab-content').forEach(el => el.style.display = 'none');
    document.querySelectorAll('.admin-tabs:not(.returns-tabs-list) .btn-admin').forEach(el => {
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

function switchReturnsTab(tab) {
    returnsFilters.status = tab;
    document.querySelectorAll('.returns-tab-content').forEach(el => el.style.display = 'none');
    
    const tabList = document.querySelector('.returns-tabs-list');
    tabList.querySelectorAll('.btn-admin').forEach(el => {
        el.classList.remove('active');
        el.style.background = 'transparent';
        el.style.color = 'var(--admin-text-muted)';
        el.style.boxShadow = 'none';
    });

    const activeBtn = document.getElementById('tab-' + tab);
    const tbody = document.getElementById('tbody-' + tab);

    if (activeBtn) {
        activeBtn.classList.add('active');
        activeBtn.style.background = 'var(--admin-card)';
        activeBtn.style.color = 'var(--admin-accent)';
        activeBtn.style.boxShadow = 'var(--shadow-sm)';
    }

    if (tbody) {
        tbody.style.display = 'table-row-group';
    }

    applyReturnsFilters();
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

function sortReturns() {
    const sortBy = document.getElementById('return-sort').value;
    const tbody = document.getElementById('tbody-' + returnsFilters.status);
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
}

async function applyOrderFilters() {
    const searchInput = document.getElementById('order-search');
    const searchButton = document.querySelector('.orders-search .btn-admin');
    const activeTab = document.getElementById('content-' + orderFilters.status);
    const tbody = activeTab ? activeTab.querySelector('tbody') : null;

    if (!searchInput || !searchButton) return;

    orderFilters.query = searchInput.value.trim();
    const originalContent = searchButton.innerHTML;

    searchButton.disabled = true;
    searchButton.textContent = 'Searching...';

    try {
        if (tbody) {
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

            if (response.ok) {
                tbody.innerHTML = await response.text();
            }
        }
    } catch (error) {
        console.error('Order search failed:', error);
    } finally {
        searchButton.disabled = false;
        searchButton.innerHTML = originalContent;
        sortOrders();
    }
}

async function applyReturnsFilters() {
    const searchInput = document.getElementById('return-search');
    const searchButton = document.querySelector('.returns-search .btn-admin');
    const returnsTbody = document.getElementById('tbody-' + returnsFilters.status);

    if (!searchInput || !searchButton || !returnsTbody) return;

    const query = searchInput.value.trim();
    const originalContent = searchButton.innerHTML;

    searchButton.disabled = true;
    searchButton.textContent = '...';

    try {
        const returnParams = new URLSearchParams({
            q: query,
            status: returnsFilters.status
        });

        const returnResponse = await fetch(`/php/Webdev/public/admin/orders/search?${returnParams.toString()}`, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        if (returnResponse.ok) {
            returnsTbody.innerHTML = await returnResponse.text();
        }
    } catch (error) {
        console.error('Returns search failed:', error);
    } finally {
        searchButton.disabled = false;
        searchButton.innerHTML = originalContent;
        sortReturns();
    }
}

(function () {
    const searchInput = document.getElementById('order-search');
    const searchButton = document.querySelector('.orders-search .btn-admin');
    const returnInput = document.getElementById('return-search');
    const returnButton = document.querySelector('.returns-search .btn-admin');

    if (searchButton && searchButton.dataset.searchBound !== 'true') {
        searchButton.addEventListener('click', e => { e.preventDefault(); applyOrderFilters(); });
        searchInput.addEventListener('keydown', e => { if (e.key === 'Enter') { e.preventDefault(); applyOrderFilters(); } });
        searchButton.dataset.searchBound = 'true';
    }

    if (returnButton && returnButton.dataset.searchBound !== 'true') {
        returnButton.addEventListener('click', e => { e.preventDefault(); applyReturnsFilters(); });
        returnInput.addEventListener('keydown', e => { if (e.key === 'Enter') { e.preventDefault(); applyReturnsFilters(); } });
        returnButton.dataset.searchBound = 'true';
    }
})();
</script>
