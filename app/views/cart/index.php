<div class="cart-page">
    <div class="container">
        <div class="cart-header-actions">
            <h1 class="cart-title">Your Cart</h1>
            <?php if(!empty($data['items'])): ?>
                <a href="/php/Webdev/public/cart/clear" class="btn-clear-cart" onclick="return confirm('Are you sure you want to remove all items from your cart?')">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                    Clear Cart
                </a>
            <?php endif; ?>
        </div>
        
        <?php if(empty($data['items'])): ?>
            <div class="empty-cart glass-card">
                <p>Your cart is empty.</p>
                <a href="/php/Webdev/public/catalog" class="btn btn-primary">Continue Shopping</a>
            </div>
        <?php else: ?>
            <div class="cart-layout">
                <!-- Cart Items List -->
                <div class="cart-items-container">
                    <div class="cart-items" id="paginated-cart-items">
                        <?php foreach($data['items'] as $item): ?>
                            <div class="cart-item glass-card" data-key="<?= $item['cart_key'] ?>" data-stock="<?= $item['stock'] ?>">
                                <img src="<?= htmlspecialchars($item['product']['image_main']) ?>" alt="<?= htmlspecialchars($item['product']['name']) ?>">
                                <div class="cart-item-details">
                                    <h3><?= htmlspecialchars($item['product']['name']) ?></h3>
                                    <p class="category">
                                        <?= htmlspecialchars($item['product']['category']) ?> | 
                                        Size: <?= htmlspecialchars($item['size']) ?>
                                        <button type="button" class="edit-size-link" 
                                                data-id="<?= $item['product']['id'] ?>"
                                                data-name="<?= htmlspecialchars($item['product']['name']) ?>"
                                                data-price="<?= $item['product']['price'] ?>"
                                                data-image="<?= htmlspecialchars($item['product']['image_main']) ?>"
                                                data-sizes='<?= $item['product']['sizes'] ?>'
                                                data-size="<?= htmlspecialchars($item['size']) ?>"
                                                data-key="<?= $item['cart_key'] ?>">
                                            Edit
                                        </button>
                                    </p>
                                    
                                    <div class="cart-item-actions">
                                        <div class="quantity-selector">
                                            <button type="button" class="qty-btn minus">&minus;</button>
                                            <input type="number" class="qty-input" value="<?= $item['quantity'] ?>" min="1" max="<?= $item['stock'] ?>" readonly>
                                            <button type="button" class="qty-btn plus">&plus;</button>
                                        </div>
                                        <a href="/php/Webdev/public/cart/remove?key=<?= urlencode($item['cart_key']) ?>" class="remove-link">Remove</a>
                                    </div>
                                    <?php if($item['stock'] <= 5): ?>
                                        <p class="stock-warning">Only <?= $item['stock'] ?> left in this size!</p>
                                    <?php endif; ?>
                                </div>
                                <div class="cart-item-price">
                                    <strong>₱<?= number_format($item['subtotal'], 2) ?></strong>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <!-- Pagination Controls -->
                    <div id="cart-pagination" class="pagination-controls"></div>
                </div>

                <!-- Order Summary -->
                <div class="cart-summary glass-card">
                    <h3>Order Summary</h3>
                    <div class="summary-row">
                        <span>Subtotal</span>
                        <span>₱<?= number_format($data['total'], 2) ?></span>
                    </div>
                    <div class="summary-row">
                        <span>Delivery</span>
                        <span>Free</span>
                    </div>
                    <div class="summary-total">
                        <span>Total</span>
                        <span>₱<?= number_format($data['total'], 2) ?></span>
                    </div>
                    <a href="/php/Webdev/public/cart/checkout" class="btn btn-primary btn-large w-100">Checkout</a>
                </div>
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
    const items = document.querySelectorAll('#paginated-cart-items .cart-item');
    const paginationContainer = document.getElementById('cart-pagination');
    let currentPage = 1;

    if (items.length > itemsPerPage) {
        const totalPages = Math.ceil(items.length / itemsPerPage);

        function showPage(page) {
            currentPage = page;
            const start = (page - 1) * itemsPerPage;
            const end = start + itemsPerPage;

            items.forEach((item, index) => {
                if (index >= start && index < end) {
                    item.style.display = ''; // Reset to CSS default (flex)
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

    </div>
</div>
