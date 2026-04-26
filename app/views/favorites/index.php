<div class="cart-page">
    <div class="container">
        <div class="cart-header-actions">
            <h1 class="cart-title">Your Favorites</h1>
            <?php if(!empty($data['items'])): ?>
                <a href="/php/Webdev/public/favorites/clear" class="btn-clear-cart" onclick="return confirm('Are you sure you want to remove all items from your favorites?')">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                    Clear All
                </a>
            <?php endif; ?>
        </div>
        
        <?php if(empty($data['items'])): ?>
            <div class="empty-cart glass-card">
                <p>You haven't saved any favorites yet.</p>
                <a href="/php/Webdev/public/catalog" class="btn btn-primary">Discover Products</a>
            </div>
        <?php else: ?>
            <div class="favorites-container">
                <div class="favorites-grid" id="paginated-favorites">
                    <?php foreach($data['items'] as $product): ?>
                        <div class="cart-item glass-card favorite-item">
                            <a href="/php/Webdev/public/product/detail/<?= $product['id'] ?>">
                                <img src="<?= htmlspecialchars($product['image_main']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                            </a>
                            <div class="cart-item-details">
                                <a href="/php/Webdev/public/product/detail/<?= $product['id'] ?>" style="text-decoration: none; color: inherit;">
                                    <h3><?= htmlspecialchars($product['name']) ?></h3>
                                </a>
                                <p class="category"><?= htmlspecialchars($product['category']) ?> | <?= htmlspecialchars($product['brand']) ?></p>
                                <div class="cart-item-actions">
                                    <!-- Move to Cart (Quick Add) -->
                                    <button type="button" class="btn btn-primary btn-sm quick-add-btn" 
                                            data-id="<?= $product['id'] ?>"
                                            data-name="<?= htmlspecialchars($product['name']) ?>"
                                            data-price="<?= $product['price'] ?>"
                                            data-image="<?= htmlspecialchars($product['image_main']) ?>"
                                            data-sizes='<?= $product['sizes'] ?>'
                                            data-remove-from-fav="true">
                                        Move to Cart
                                    </button>
                                    
                                    <!-- Remove from Favorites Form (Toggle) -->
                                    <form action="/php/Webdev/public/favorites/toggle" method="POST" style="display:inline;" class="favorite-toggle-form" data-remove-item="true">
                                        <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                                        <button type="submit" class="remove-link" style="background:none; border:none; padding:0; cursor:pointer; margin-left:15px;">Remove</button>
                                    </form>
                                </div>
                            </div>
                            <div class="cart-item-price">
                                <strong>₱<?= number_format($product['price'], 2) ?></strong>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Pagination Controls -->
                <div id="fav-pagination" class="pagination-controls"></div>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
.cart-header-actions {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
}

.btn-clear-cart {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 10px 18px;
    background: #fff;
    border: 1px solid #e2e8f0;
    color: #ef4444;
    border-radius: 12px;
    font-weight: 600;
    font-size: 0.9rem;
    cursor: pointer;
    transition: all 0.2s ease;
}

.btn-clear-cart:hover {
    background: #fef2f2;
    border-color: #fecaca;
    transform: translateY(-1px);
}

.favorites-grid {
    display: flex;
    flex-direction: column;
    gap: 20px;
}
.btn-sm {
    padding: 8px 16px;
    font-size: 0.85rem;
}

.pagination-controls {
    display: flex;
    justify-content: center;
    gap: 8px;
    margin-top: 2rem;
}

.page-btn {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 10px;
    border: 1px solid #e2e8f0;
    background: white;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
}

.page-btn:hover {
    background: #f8fafc;
    border-color: #cbd5e1;
}

.page-btn.active {
    background: #0f172a;
    color: white;
    border-color: #0f172a;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const itemsPerPage = 5;
    const items = document.querySelectorAll('#paginated-favorites .favorite-item');
    const paginationContainer = document.getElementById('fav-pagination');
    let currentPage = 1;

    if (items.length > itemsPerPage) {
        const totalPages = Math.ceil(items.length / itemsPerPage);

        function showPage(page) {
            currentPage = page;
            const start = (page - 1) * itemsPerPage;
            const end = start + itemsPerPage;

            items.forEach((item, index) => {
                if (index >= start && index < end) {
                    item.style.display = 'grid';
                } else {
                    item.style.display = 'none';
                }
            });

            renderPagination();
        }

        function renderPagination() {
            paginationContainer.innerHTML = '';
            for (let i = 1; i <= totalPages; i++) {
                const btn = document.createElement('button');
                btn.className = `page-btn ${i === currentPage ? 'active' : ''}`;
                btn.textContent = i;
                btn.addEventListener('click', () => {
                    showPage(i);
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                });
                paginationContainer.appendChild(btn);
            }
        }

        showPage(1);
    }
});
</script>
