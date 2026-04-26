</main>

<footer class="main-footer">
    <div class="container footer-content">
        <div class="footer-col">
            <h3>BLISS</h3>
            <p>A new wave of elegance.</p>
        </div>
        <div class="footer-col">
            <h4>Shop</h4>
            <a href="/php/Webdev/public/catalog?filter=new">New Arrivals</a>
            <a href="/php/Webdev/public/catalog?filter=best">Best Sellers</a>
        </div>
        <div class="footer-col">
            <h4>Support</h4>
            <a href="/php/Webdev/public/help/contact">Contact Us</a>
            <a href="/php/Webdev/public/help">Help Center</a>
            <a href="/php/Webdev/public/help/shipping_returns">Shipping & Returns</a>
        </div>
    </div>
    <div class="footer-bottom">
        <p>&copy; <?= date('Y'); ?> BLISS. All rights reserved.</p>
    </div>
</footer>

<!-- Quick Add Modal (Premium Design) -->
<div id="quick-add-modal" class="modal-overlay">
    <div class="modal-content glass-premium-modal">
        <div class="modal-header-premium">
            <div class="modal-title-group">
                <span class="modal-subtitle">Fast Add</span>
                <h3 id="modal-name">Product Name</h3>
            </div>
            <button class="modal-close-btn">&times;</button>
        </div>
        
        <div class="modal-body-premium">
            <div class="modal-product-display">
                <div class="modal-img-container">
                    <img id="modal-img" src="" alt="">
                </div>
                <div class="modal-pricing">
                    <span class="price-label">Price</span>
                    <p id="modal-price" class="price-value"></p>
                </div>
            </div>

            <div class="modal-selection-area">
                <div class="selection-header-row">
                    <span class="selection-label">Select Size</span>
                    <span id="modal-stock-info" class="stock-status"></span>
                </div>
                <div id="modal-size-grid" class="premium-size-grid"></div>
            </div>

            <form action="/php/Webdev/public/cart/add" method="POST" id="modal-cart-form">
                <input type="hidden" name="product_id" id="modal-product-id">
                <input type="hidden" name="size" id="modal-selected-size">
                <!-- is_edit and old_key for cart modifications -->
                <input type="hidden" name="is_edit" id="modal-is-edit" value="0">
                <input type="hidden" name="old_key" id="modal-old-key" value="">
                <input type="hidden" name="remove_from_favorites" id="modal-remove-from-fav" value="0">
                
                <button type="submit" id="modal-submit-btn" class="btn-premium-action w-100" disabled>
                    <span class="btn-text">Select a Size</span>
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                </button>
            </form>
        </div>
    </div>
</div>

<style>
.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.4);
    backdrop-filter: blur(12px);
    display: none;
    align-items: center;
    justify-content: center;
    z-index: 2000;
    opacity: 0;
    transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
}

.modal-overlay.active { display: flex; opacity: 1; }

.glass-premium-modal {
    background: #fff;
    width: 90%;
    max-width: 480px;
    border-radius: 32px;
    padding: 0;
    overflow: hidden;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15);
    transform: translateY(30px) scale(0.95);
    transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
}

.modal-overlay.active .glass-premium-modal { transform: translateY(0) scale(1); }

.modal-header-premium {
    padding: 30px 40px;
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    border-bottom: 1px solid #f1f5f9;
}

@media (max-width: 480px) {
    .modal-header-premium { padding: 20px 25px; }
    .modal-body-premium { padding: 25px 25px 30px; }
    .modal-header-premium h3 { font-size: 1.25rem; }
    .premium-size-grid { grid-template-columns: repeat(3, 1fr); }
}

.modal-subtitle { font-size: 0.7rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.1em; color: #94a3b8; display: block; margin-bottom: 4px; }
.modal-header-premium h3 { margin: 0; font-size: 1.5rem; font-weight: 800; letter-spacing: -0.03em; color: #0f172a; }

.modal-close-btn { background: #f8fafc; border: none; width: 36px; height: 36px; border-radius: 50%; font-size: 1.4rem; cursor: pointer; color: #64748b; display: flex; align-items: center; justify-content: center; transition: all 0.2s; }
.modal-close-btn:hover { background: #0f172a; color: #fff; }

.modal-body-premium { padding: 30px 40px 40px; }

.modal-product-display { display: flex; align-items: center; gap: 24px; margin-bottom: 35px; }
.modal-img-container { width: 100px; height: 100px; border-radius: 20px; background: #f8fafc; overflow: hidden; border: 1px solid #f1f5f9; }
.modal-img-container img { width: 100%; height: 100%; object-fit: cover; }

.price-label { font-size: 0.75rem; font-weight: 700; color: #94a3b8; text-transform: uppercase; }
.price-value { font-size: 1.5rem; font-weight: 900; color: #0f172a; margin: 0; }

.selection-header-row { display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; }
.selection-label { font-size: 0.85rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; color: #0f172a; }
.stock-status { font-size: 0.8rem; font-weight: 700; }

.premium-size-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 10px; margin-bottom: 35px; }
.premium-size-btn { 
    height: 54px; 
    border: 2px solid #f1f5f9; 
    background: #fff; 
    border-radius: 14px; 
    font-weight: 700; 
    font-size: 0.95rem; 
    cursor: pointer; 
    transition: all 0.2s;
    color: #475569;
}

.premium-size-btn:hover:not(:disabled) { border-color: #cbd5e1; background: #f8fafc; }
.premium-size-btn.active { border-color: #0f172a; background: #0f172a; color: #fff; box-shadow: 0 8px 20px rgba(15, 23, 42, 0.15); }
.premium-size-btn:disabled { opacity: 0.3; cursor: not-allowed; text-decoration: line-through; background: #f1f5f9; }

.btn-premium-action {
    background: #0f172a;
    color: #fff;
    border: none;
    height: 64px;
    border-radius: 18px;
    font-weight: 700;
    font-size: 1rem;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 12px;
    transition: all 0.3s;
}

.btn-premium-action:hover:not(:disabled) { transform: translateY(-2px); box-shadow: 0 12px 30px rgba(15, 23, 42, 0.2); }
.btn-premium-action:disabled { background: #cbd5e1; cursor: not-allowed; }
</style>

<script src="/php/Webdev/public/js/main.js?v=<?= time() ?>"></script>
</body>
</html>
