<div class="catalog-page">
    <div class="container catalog-container">
        
        <!-- Sidebar Filters -->
        <aside class="catalog-sidebar glass-card">
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

    function sortProducts() {
        const sortBy = sortSelect.value;
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
        sortSelect.addEventListener('change', sortProducts);
    }

    // Initial Filter on Page Load
    filterProducts();
});
</script>
