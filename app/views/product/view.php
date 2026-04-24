<div class="product-detail-page">
    <!-- Apple-Style Hero Image -->
    <div class="product-hero glass-hero">
        <img src="<?= htmlspecialchars($data['product']['image_main']) ?>" alt="<?= htmlspecialchars($data['product']['name']) ?>" class="main-image">
    </div>

    <!-- Product Info Section -->
    <div class="container product-info-container">
        <div class="product-meta">
            <span class="brand-badge"><?= htmlspecialchars($data['product']['brand']) ?></span>
            <span class="category-text"><?= htmlspecialchars($data['product']['category']) ?> Shoe</span>
        </div>
        
        <div class="product-header">
            <h1 class="product-title"><?= htmlspecialchars($data['product']['name']) ?></h1>
            <p class="product-price">₱<?= number_format($data['product']['price'], 2) ?></p>
        </div>

        <p class="product-description"><?= htmlspecialchars($data['product']['description']) ?></p>

        <!-- Size Selection -->
        <?php $sizesStock = json_decode($data['product']['sizes'], true) ?: []; ?>
        <div class="size-selector">
            <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 20px;">
                <h3 style="margin: 0; font-weight: 800; text-transform: uppercase; font-size: 0.9rem; letter-spacing: 0.05em;">Select Size</h3>
                <span id="stock-feedback" style="font-size: 0.85rem; font-weight: 700; transition: all 0.3s ease;"></span>
            </div>
            <div class="size-grid">
                <?php foreach($sizesStock as $size => $count): ?>
                    <button type="button" 
                            class="size-btn <?= $count <= 0 ? 'out-of-stock' : '' ?>" 
                            data-size="<?= $size ?>" 
                            data-stock="<?= $count ?>"
                            <?= $count <= 0 ? 'disabled' : '' ?>
                            style="height: 60px; border: 2px solid #f1f5f9; border-radius: 16px; font-weight: 700; background: #fff; transition: all 0.2s;">
                        <?= $size ?>
                    </button>
                <?php endforeach; ?>
            </div>
        </div>

<style>
.size-btn.active {
    border-color: #000 !important;
    background: #000 !important;
    color: #fff !important;
    box-shadow: 0 8px 20px rgba(0,0,0,0.1);
}
.size-btn:hover:not(.active):not(:disabled) {
    border-color: #cbd5e1 !important;
}
.size-btn:disabled {
    opacity: 0.3;
    text-decoration: line-through;
}
</style>

        <!-- Action Buttons -->
        <div class="product-actions">
            <!-- Add to Cart & Buy Now Form -->
            <form action="/php/Webdev/public/cart/add" method="POST" class="add-to-cart-form" id="cart-form" style="flex: 1; display: flex; gap: 15px;">
                <input type="hidden" name="product_id" value="<?= $data['product']['id'] ?>">
                <input type="hidden" name="size" id="selected-size-input" value="">
                
                <button type="submit" id="add-to-cart-btn" class="btn btn-secondary btn-large" style="flex: 1;" disabled>Add to Cart</button>
                <button type="submit" id="buy-now-btn" name="buy_now" value="1" class="btn btn-primary btn-large" style="flex: 1.5;" disabled>Buy Now</button>
            </form>
            
            <?php $isFavorite = in_array($data['product']['id'], $_SESSION['favorites_list'] ?? []); ?>
            <form action="/php/Webdev/public/favorites/toggle" method="POST">
                <input type="hidden" name="product_id" value="<?= $data['product']['id'] ?>">
                <button type="submit" class="btn btn-secondary btn-icon-large <?= $isFavorite ? 'active' : '' ?>" style="height: 64px; width: 64px;" title="<?= $isFavorite ? 'Remove from Favorites' : 'Add to Favorites' ?>">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="<?= $isFavorite ? 'currentColor' : 'none' ?>" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
                </button>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const sizeButtons = document.querySelectorAll('.size-btn');
    const sizeInput = document.getElementById('selected-size-input');
    const cartBtn = document.getElementById('add-to-cart-btn');
    const buyNowBtn = document.getElementById('buy-now-btn');
    const feedback = document.getElementById('stock-feedback');
    const cartForm = document.getElementById('cart-form');

    sizeButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            // UI Toggle
            sizeButtons.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');

            // Set Data
            const size = btn.dataset.size;
            const stock = parseInt(btn.dataset.stock);
            sizeInput.value = size;
            
            // Enable Buttons
            cartBtn.disabled = false;
            buyNowBtn.disabled = false;

            // Feedback logic
            if (stock > 0 && stock <= 5) {
                feedback.textContent = `🔥 Only ${stock} left!`;
                feedback.style.color = '#ef4444';
            } else if (stock > 5) {
                feedback.textContent = '✨ In Stock';
                feedback.style.color = '#10b981';
            } else {
                feedback.textContent = 'Sold Out';
                feedback.style.color = '#94a3b8';
            }
        });
    });

    // AJAX Submission
    cartForm.addEventListener('submit', (e) => {
        e.preventDefault();
        
        if (!sizeInput.value) {
            alert('Please select a size first.');
            return;
        }

        const isBuyNow = e.submitter === buyNowBtn;
        const formData = new FormData(cartForm);
        const originalBtnContent = e.submitter.innerHTML;
        
        // Loading state
        e.submitter.disabled = true;
        e.submitter.innerHTML = '<span class="loading-spinner"></span> Processing...';

        fetch(cartForm.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                // Update Cart Badge
                const cartBadge = document.querySelector('.cart-link .cart-badge');
                if (cartBadge) {
                    cartBadge.textContent = data.cartCount;
                    cartBadge.style.display = data.cartCount > 0 ? 'flex' : 'none';
                }
                
                if (isBuyNow) {
                    window.location.href = '/php/Webdev/public/cart';
                } else {
                    if (typeof showToast === 'function') {
                        showToast('Item added to cart!');
                    } else {
                        alert('Item added to cart!');
                    }
                }
            } else {
                alert(data.error || 'Failed to add item to cart.');
            }
        })
        .catch(err => {
            console.error('Error:', err);
            alert('An error occurred. Please try again.');
        })
        .finally(() => {
            if (!isBuyNow) {
                e.submitter.disabled = false;
                e.submitter.innerHTML = originalBtnContent;
            }
        });
    });
});
</script>
