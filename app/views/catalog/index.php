<div class="catalog-page">
    <div class="container catalog-container">
        
        <!-- Mobile Filter/Sort Controls -->
        <div class="mobile-filter-bar">
            <button type="button" class="mobile-filter-trigger" id="mobile-filter-btn">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="4" y1="21" x2="4" y2="14"></line><line x1="4" y1="10" x2="4" y2="3"></line><line x1="12" y1="21" x2="12" y2="12"></line><line x1="12" y1="8" x2="12" y2="3"></line><line x1="20" y1="21" x2="20" y2="16"></line><line x1="20" y1="12" x2="20" y2="3"></line><line x1="1" y1="14" x2="7" y2="14"></line><line x1="9" y1="8" x2="15" y2="8"></line><line x1="17" y1="16" x2="23" y2="16"></line></svg>
                Filter By
            </button>
            <div class="mobile-sort-container">
                <select id="catalog-sort-mobile">
                    <option value="featured">Sort: Featured</option>
                    <option value="newest">Sort: Newest</option>
                    <option value="low-to-high">Low to High</option>
                    <option value="high-to-low">High to Low</option>
                </select>
            </div>
        </div>

        <!-- Sidebar Filters -->
        <aside class="catalog-sidebar glass-card" id="catalog-sidebar">
            <div class="sidebar-header-mobile">
                <h3>Filters</h3>
                <button type="button" id="close-filter-mobile">&times;</button>
            </div>
            <h3 class="sidebar-title">Explore</h3>
            
            <!-- Catalog Live Search -->
            <div class="catalog-search">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="search-icon"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                <input type="text" id="catalog-live-search" placeholder="Search products...">
            </div>

            <!-- Category Group (Collapsible) -->
            <div class="filter-group-collapsible">
                <button class="filter-group-trigger">
                    <span>Shop By Category</span>
                    <svg class="chevron" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>
                </button>
                <div class="filter-group-content">
                    <div class="filter-list">
                        <a href="/php/Webdev/public/catalog" class="filter-btn <?= (!isset($_GET['category']) && !isset($_GET['filter'])) ? 'active' : '' ?>">All Products</a>
                        
                        <div class="filter-divider">Highlights</div>
                        <a href="/php/Webdev/public/catalog?filter=featured" class="filter-btn <?= (isset($_GET['filter']) && $_GET['filter'] == 'featured') ? 'active' : '' ?>">Featured Drop</a>
                        <a href="/php/Webdev/public/catalog?filter=new" class="filter-btn <?= (isset($_GET['filter']) && $_GET['filter'] == 'new') ? 'active' : '' ?>">New Arrivals</a>
                        <a href="/php/Webdev/public/catalog?filter=best" class="filter-btn <?= (isset($_GET['filter']) && $_GET['filter'] == 'best') ? 'active' : '' ?>">Best Sellers</a>
                        
                        <div class="filter-divider">Collections</div>
                        <?php 
                            $cats = ['Running', 'Lifestyle', 'Training', 'Basketball', 'Football', 'Outdoor', 'Limited Edition'];
                            foreach($cats as $cat):
                        ?>
                            <a href="/php/Webdev/public/catalog?category=<?= $cat ?>" class="filter-btn <?= (isset($_GET['category']) && $_GET['category'] == $cat) ? 'active' : '' ?>"><?= $cat ?></a>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- Price Range Group (Collapsible) -->
            <div class="filter-group-collapsible">
                <button class="filter-group-trigger">
                    <span>Shop By Price</span>
                    <svg class="chevron" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>
                </button>
                <div class="filter-group-content">
                    <div class="filter-list">
                        <label class="custom-radio">
                            <input type="radio" name="price" value="any" checked>
                            <span class="radio-mark"></span>
                            <span class="radio-label">Any Price</span>
                        </label>
                        <label class="custom-radio">
                            <input type="radio" name="price" value="under5k">
                            <span class="radio-mark"></span>
                            <span class="radio-label">Under ₱5,000</span>
                        </label>
                        <label class="custom-radio">
                            <input type="radio" name="price" value="5kto10k">
                            <span class="radio-mark"></span>
                            <span class="radio-label">₱5,000 - ₱10,000</span>
                        </label>
                        <label class="custom-radio">
                            <input type="radio" name="price" value="over10k">
                            <span class="radio-mark"></span>
                            <span class="radio-label">Over ₱10,000</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Stock Status Group (Collapsible) -->
            <div class="filter-group-collapsible active">
                <button class="filter-group-trigger">
                    <span>Stock Status</span>
                    <svg class="chevron" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>
                </button>
                <div class="filter-group-content">
                    <div class="filter-list">
                        <label class="custom-radio">
                            <input type="radio" name="stock" value="in" checked>
                            <span class="radio-mark"></span>
                            <span class="radio-label">In Stock</span>
                        </label>
                        <label class="custom-radio">
                            <input type="radio" name="stock" value="out">
                            <span class="radio-mark"></span>
                            <span class="radio-label">Out of Stock</span>
                        </label>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Product Grid -->
        <main class="catalog-main">
            <div class="catalog-header">
                <h2><?= htmlspecialchars($data['title']) ?></h2>
                <div class="sort-by">
                    <select id="catalog-sort">
                        <option value="featured">Sort By: Featured</option>
                        <option value="newest">Sort By: Newest</option>
                        <option value="low-to-high">Price: Low to High</option>
                        <option value="high-to-low">Price: High to Low</option>
                    </select>
                </div>
            </div>

            <div class="product-grid" id="catalog-grid">
                <?php if(empty($data['products'])): ?>
                    <p class="no-products">No products found in this category.</p>
                <?php else: ?>
                    <?php foreach($data['products'] as $product): ?>
                        <?php include '../app/views/templates/product_card.php'; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            
            <!-- Dynamic No Results Message -->
            <div id="no-results-msg" class="glass-card no-results-placeholder">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" style="margin-bottom: var(--spacing-2); opacity: 0.5;"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                <p>We couldn't find any products matching your search.</p>
                <button onclick="document.getElementById('catalog-live-search').value=''; document.querySelector('input[name=\'price\'][value=\'any\']').checked=true; document.querySelector('input[name=\'stock\'][value=\'in\']').checked=true; window.dispatchEvent(new Event('catalog-filter'));" class="btn btn-secondary" style="margin-top: 15px; font-size: 0.8rem;">Clear Filters</button>
            </div>
        </main>
    </div>
</div>

<style>
.catalog-container {
    display: flex;
    gap: var(--spacing-6);
    padding-top: var(--spacing-6);
}

.mobile-filter-bar {
    display: none;
    width: 100%;
    margin-bottom: var(--spacing-4);
    background: #fff;
    border: 1px solid var(--glass-border);
    border-radius: 12px;
    overflow: hidden;
}

.sidebar-header-mobile {
    display: none;
    justify-content: space-between;
    align-items: center;
    padding-bottom: var(--spacing-4);
    border-bottom: 1px solid rgba(0,0,0,0.05);
    margin-bottom: var(--spacing-4);
}

.sidebar-header-mobile h3 { font-size: 1.25rem; font-weight: 800; }
#close-filter-mobile { background: none; border: none; font-size: 2rem; color: var(--text-secondary); cursor: pointer; }

@media (max-width: 768px) {
    .catalog-container { flex-direction: column !important; padding-top: var(--spacing-4) !important; }
    
    .mobile-filter-bar { 
        display: flex !important; 
        align-items: center; 
        justify-content: space-between; 
        position: relative;
        z-index: 500;
    }
    
    .mobile-filter-trigger {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 15px;
        border: none;
        background: none;
        font-weight: 700;
        font-size: 0.9rem;
        text-transform: uppercase;
        border-right: 1px solid var(--glass-border);
        cursor: pointer;
    }

    .mobile-sort-container { flex: 1; }
    #catalog-sort-mobile {
        width: 100%;
        padding: 15px;
        border: none;
        background: none;
        font-weight: 700;
        font-size: 0.9rem;
        text-transform: uppercase;
        cursor: pointer;
        outline: none;
        text-align: center;
        -webkit-appearance: none;
    }

    .catalog-sidebar {
        position: fixed !important;
        top: 0 !important;
        left: -100% !important;
        width: 100% !important;
        height: 100% !important;
        z-index: 2000 !important;
        background: #fff !important;
        border-radius: 0 !important;
        padding: var(--spacing-5) !important;
        transition: left 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
        overflow-y: auto !important;
        display: flex !important; /* Ensure it is flex when displayed */
    }

    .catalog-sidebar.open { left: 0 !important; }
    .sidebar-header-mobile { display: flex !important; }
    .catalog-header .sort-by { display: none !important; }
}

.filter-group-collapsible {
    border-bottom: 1px solid rgba(0,0,0,0.05);
    margin-bottom: 15px;
}

.filter-group-trigger {
    width: 100%;
    padding: 15px 0;
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: none;
    border: none;
    cursor: pointer;
    font-family: inherit;
}

.filter-group-trigger span {
    font-size: 0.9rem;
    font-weight: 700;
    color: var(--text-primary);
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.filter-group-trigger .chevron {
    transition: transform 0.3s ease;
    color: var(--text-secondary);
}

.filter-group-collapsible.active .chevron {
    transform: rotate(180deg);
}

.filter-group-content {
    max-height: 0;
    overflow: hidden;
    transition: max-height 0.3s ease-out;
}

.filter-group-collapsible.active .filter-group-content {
    max-height: 1000px; /* Large enough to hold content */
}

.filter-divider {
    font-size: 0.75rem;
    font-weight: 800;
    color: var(--text-secondary);
    margin: 15px 0 8px 10px;
    text-transform: uppercase;
    opacity: 0.6;
}

.filter-list {
    padding-bottom: 15px;
}

.filter-btn {
    display: block;
    padding: 8px 12px;
    color: var(--text-secondary);
    text-decoration: none;
    font-size: 0.95rem;
    border-radius: 8px;
    transition: all 0.2s;
}

.filter-btn:hover, .filter-btn.active {
    color: var(--primary-color);
    background: rgba(0,0,0,0.03);
    font-weight: 600;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', () => {
    // Mobile Filter Sidebar Toggle
    const mobileFilterBtn = document.getElementById('mobile-filter-btn');
    const closeFilterBtn = document.getElementById('close-filter-mobile');
    const catalogSidebar = document.getElementById('catalog-sidebar');

    const toggleFilter = (show) => {
        if (!catalogSidebar) return;
        if (show) {
            catalogSidebar.classList.add('open');
            document.body.style.overflow = 'hidden';
        } else {
            catalogSidebar.classList.remove('open');
            document.body.style.overflow = '';
        }
    };

    if (mobileFilterBtn) {
        mobileFilterBtn.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            toggleFilter(true);
        });
    }

    if (closeFilterBtn) {
        closeFilterBtn.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            toggleFilter(false);
        });
    }

    // Collapsible Logic
    const triggers = document.querySelectorAll('.filter-group-trigger');
    triggers.forEach(trigger => {
        trigger.addEventListener('click', () => {
            const parent = trigger.parentElement;
            parent.classList.toggle('active');
        });
    });

    // Client-side Live Search & Filtering for Catalog
    const catalogSearch = document.getElementById('catalog-live-search');
    const productCards = document.querySelectorAll('.product-card');
    const noResultsMsg = document.getElementById('no-results-msg');
    const priceRadios = document.querySelectorAll('input[name="price"]');
    const stockRadios = document.querySelectorAll('input[name="stock"]');
    const catalogGrid = document.getElementById('catalog-grid');
    const sortSelect = document.getElementById('catalog-sort');
    const sortSelectMobile = document.getElementById('catalog-sort-mobile');

    function filterProducts() {
        const query = catalogSearch.value.toLowerCase().trim();
        const priceFilter = document.querySelector('input[name="price"]:checked').value;
        const stockFilter = document.querySelector('input[name="stock"]:checked').value;
        let visibleCount = 0;

        productCards.forEach(card => {
            const title = card.querySelector('h3').textContent.toLowerCase();
            const category = card.querySelector('.category').textContent.toLowerCase();
            const priceText = card.querySelector('.price').textContent.replace(/[^0-9.]/g, '');
            const priceVal = parseFloat(priceText);
            const totalStock = parseInt(card.dataset.totalStock || 0);

            let matchesSearch = title.includes(query) || category.includes(query);
            let matchesPrice = true;
            let matchesStock = true;

            // Price Filter
            if (priceFilter === 'under5k') {
                matchesPrice = priceVal < 5000;
            } else if (priceFilter === '5kto10k') {
                matchesPrice = priceVal >= 5000 && priceVal <= 10000;
            } else if (priceFilter === 'over10k') {
                matchesPrice = priceVal > 10000;
            }

            // Stock Filter
            if (stockFilter === 'in') {
                matchesStock = totalStock > 0;
            } else if (stockFilter === 'out') {
                matchesStock = totalStock === 0;
            }

            if (matchesSearch && matchesPrice && matchesStock) {
                card.style.display = 'block';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });

        if (visibleCount === 0 && productCards.length > 0) {
            noResultsMsg.style.display = 'flex';
            catalogGrid.style.display = 'none';
        } else {
            noResultsMsg.style.display = 'none';
            catalogGrid.style.display = 'grid';
        }
    }

    function sortProducts(sortBy) {
        const cardsArray = Array.from(productCards);

        cardsArray.sort((a, b) => {
            const priceA = parseFloat(a.querySelector('.price').textContent.replace(/[^0-9.]/g, ''));
            const priceB = parseFloat(b.querySelector('.price').textContent.replace(/[^0-9.]/g, ''));
            const titleA = a.querySelector('h3').textContent.toLowerCase();
            const titleB = b.querySelector('h3').textContent.toLowerCase();

            if (sortBy === 'low-to-high') return priceA - priceB;
            if (sortBy === 'high-to-low') return priceB - priceA;
            if (sortBy === 'newest') return 0; // Default order is newest from PHP
            return 0;
        });

        // Re-append sorted cards
        cardsArray.forEach(card => catalogGrid.appendChild(card));
    }

    // Listen for clear filter event
    window.addEventListener('catalog-filter', filterProducts);

    if (catalogSearch) {
        catalogSearch.addEventListener('input', filterProducts);
    }
    
    priceRadios.forEach(radio => {
        radio.addEventListener('change', filterProducts);
    });

    stockRadios.forEach(radio => {
        radio.addEventListener('change', filterProducts);
    });

    if (sortSelect) {
        sortSelect.addEventListener('change', () => sortProducts(sortSelect.value));
    }

    if (sortSelectMobile) {
        sortSelectMobile.addEventListener('change', () => sortProducts(sortSelectMobile.value));
    }

    // Initial Filter on Page Load
    filterProducts();
});
</script>
