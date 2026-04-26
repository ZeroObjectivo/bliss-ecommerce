<div class="checkout-wrapper">
    <div class="checkout-header-minimal">
        <div class="container">
            <div class="header-inner">
                <a href="/php/Webdev/public/cart" class="back-nav">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                    <span>Back to Cart</span>
                </a>
                <div class="checkout-brand">BLISS</div>
                <div class="secure-badge">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                    Secure Checkout
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <!-- Modern Step Indicator -->
        <div class="stepper-horizontal">
            <div class="step-indicator" data-step="1">
                <div class="step-label">01</div>
                <div class="step-text">Information</div>
            </div>
            <div class="step-line"></div>
            <div class="step-indicator" data-step="2">
                <div class="step-label">02</div>
                <div class="step-text">Shipping</div>
            </div>
            <div class="step-line"></div>
            <div class="step-indicator" data-step="3">
                <div class="step-label">03</div>
                <div class="step-text">Payment</div>
            </div>
        </div>

        <form action="/php/Webdev/public/cart/process" method="POST" id="checkout-form" class="checkout-grid">
            <div class="checkout-flow-main">
                
                <!-- Step 1: Information -->
                <div class="step-content active" id="step-1">
                    <div class="content-card glass-premium">
                        <div class="content-header">
                            <h2>Where should we ship?</h2>
                            <p>Select a saved address or add a new delivery destination.</p>
                        </div>
                        
                        <div class="address-selection-grid">
                            <?php if(!empty($data['addresses'])): ?>
                                <?php foreach($data['addresses'] as $addr): ?>
                                    <label class="address-box-premium <?= $addr['is_default'] ? 'selected' : '' ?>">
                                        <input type="radio" name="address_id" value="<?= $addr['id'] ?>" <?= $addr['is_default'] ? 'checked' : '' ?>>
                                        <div class="box-inner">
                                            <div class="radio-circle"></div>
                                            <div class="box-text">
                                                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:6px;">
                                                    <span class="pill-mini" style="margin:0; background:#f1f5f9; color:#475569; font-size:0.65rem;"><?= htmlspecialchars($addr['category'] ?? 'Home Address') ?></span>
                                                    <?php if($addr['is_default']): ?><span class="pill-mini" style="margin:0;">Default</span><?php endif; ?>
                                                </div>
                                                <span class="addr-title"><?= htmlspecialchars($addr['street_address']) ?></span>
                                                <span class="addr-meta"><?= htmlspecialchars($addr['city']) ?>, <?= htmlspecialchars($addr['province']) ?></span>
                                            </div>
                                        </div>
                                    </label>
                                <?php endforeach; ?>
                            <?php endif; ?>

                            <button type="button" class="address-box-premium add-new" onclick="document.getElementById('address-modal').classList.add('active')" style="text-align: left; background: transparent; border: none; outline: none; padding: 0;">
                                <div class="box-inner">
                                    <div class="plus-icon" style="width: 18px; height: 18px; border: 2px dashed #cbd5e1; border-radius: 50%; margin-bottom: 15px; display: flex; align-items: center; justify-content: center; color: #cbd5e1; font-weight: bold;">+</div>
                                    <div class="box-text">
                                        <span class="addr-title">New Address</span>
                                        <span class="addr-meta">Add a new delivery location</span>
                                    </div>
                                </div>
                            </button>
                        </div>

                        <div class="action-footer">
                            <button type="button" class="btn-premium btn-next" data-next="2">
                                Continue to Shipping
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Step 2: Shipping -->
                <div class="step-content" id="step-2">
                    <div class="content-card glass-premium">
                        <div class="content-header">
                            <h2>Delivery Method</h2>
                            <p>All our shipping options are currently free for BLISS members.</p>
                        </div>

                        <div class="shipping-options-list">
                            <label class="shipping-row-premium">
                                <input type="radio" name="shipping_method" value="LBC" checked>
                                <div class="row-inner">
                                    <div class="radio-circle"></div>
                                    <div class="method-info">
                                        <span class="m-name">LBC Express</span>
                                        <span class="m-desc">3-5 Business Days • Nationwide Tracking</span>
                                    </div>
                                    <div class="m-price">Free</div>
                                </div>
                            </label>

                            <label class="shipping-row-premium">
                                <input type="radio" name="shipping_method" value="J&T">
                                <div class="row-inner">
                                    <div class="radio-circle"></div>
                                    <div class="method-info">
                                        <span class="m-name">J&T Express</span>
                                        <span class="m-desc">2-4 Business Days • Fast Local Fulfillment</span>
                                    </div>
                                    <div class="m-price">Free</div>
                                </div>
                            </label>

                            <label class="shipping-row-premium">
                                <input type="radio" name="shipping_method" value="Standard">
                                <div class="row-inner">
                                    <div class="radio-circle"></div>
                                    <div class="method-info">
                                        <span class="m-name">BLISS Standard</span>
                                        <span class="m-desc">5-7 Business Days • Eco-friendly packaging</span>
                                    </div>
                                    <div class="m-price">Free</div>
                                </div>
                            </label>
                        </div>

                        <div class="action-footer">
                            <button type="button" class="btn-text btn-prev" data-prev="1">Back</button>
                            <button type="button" class="btn-premium btn-next" data-next="3">
                                Continue to Payment
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Step 3: Payment -->
                <div class="step-content" id="step-3">
                    <div class="content-card glass-premium">
                        <div class="content-header">
                            <h2>Finalize Payment</h2>
                            <p>Select your preferred method. We don't charge your card until we ship.</p>
                        </div>

                        <div class="payment-selection-grid">
                            <label class="payment-tile">
                                <input type="radio" name="payment_method" value="COD" checked>
                                <div class="tile-inner">
                                    <div class="tile-icon"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="5" width="20" height="14" rx="2"></rect><line x1="2" y1="10" x2="22" y2="10"></line></svg></div>
                                    <span class="tile-label">Cash on Delivery</span>
                                    <div class="tile-check"></div>
                                </div>
                            </label>

                            <label class="payment-tile">
                                <input type="radio" name="payment_method" value="GCash">
                                <div class="tile-inner">
                                    <div class="tile-icon gcash">G</div>
                                    <span class="tile-label">GCash</span>
                                    <div class="tile-check"></div>
                                </div>
                            </label>

                            <label class="payment-tile">
                                <input type="radio" name="payment_method" value="Maya">
                                <div class="tile-inner">
                                    <div class="tile-icon maya">M</div>
                                    <span class="tile-label">Maya</span>
                                    <div class="tile-check"></div>
                                </div>
                            </label>

                            <label class="payment-tile">
                                <input type="radio" name="payment_method" value="CreditCard">
                                <div class="tile-inner">
                                    <div class="tile-icon"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 4H3a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h18a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2z"></path><line x1="1" y1="10" x2="23" y2="10"></line></svg></div>
                                    <span class="tile-label">Card</span>
                                    <div class="tile-check"></div>
                                </div>
                            </label>
                        </div>

                        <div class="action-footer">
                            <button type="button" class="btn-text btn-prev" data-prev="2">Back</button>
                            <button type="submit" class="btn-premium">
                                Place Order
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"></polyline></svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Receipt-style Sidebar -->
            <div class="checkout-summary-sidebar">
                <div class="receipt-card">
                    <h3 class="receipt-title">Order Overview</h3>
                    <div class="receipt-items">
                        <?php foreach($data['items'] as $item): ?>
                            <div class="receipt-item">
                                <div class="ri-img-wrapper">
                                    <img src="<?= htmlspecialchars($item['product']['image_main']) ?>">
                                    <span class="ri-qty"><?= $item['quantity'] ?></span>
                                </div>
                                <div class="ri-details">
                                    <span class="ri-name"><?= htmlspecialchars($item['product']['name']) ?></span>
                                    <span class="ri-meta">Size <?= htmlspecialchars($item['size']) ?></span>
                                </div>
                                <span class="ri-price">₱<?= number_format($item['subtotal'], 2) ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="receipt-totals">
                        <div class="total-row">
                            <span>Subtotal</span>
                            <span>₱<?= number_format($data['total'], 2) ?></span>
                        </div>
                        <div class="total-row">
                            <span>Shipping</span>
                            <span class="success-text">FREE</span>
                        </div>
                        <div class="total-row final">
                            <span>Total</span>
                            <span>₱<?= number_format($data['total'], 2) ?></span>
                        </div>
                    </div>

                    <div class="receipt-badge">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M20 6L9 17l-5-5"></path></svg>
                        Official BLISS Member Store
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<style>
/* Global Reset Overrides for Checkout */
.checkout-wrapper { background: #fdfdfd; min-height: 100vh; padding-bottom: 80px; overflow-x: hidden; }

/* Minimal Header */
.checkout-header-minimal { background: rgba(255,255,255,0.8); backdrop-filter: saturate(180%) blur(20px); border-bottom: 1px solid rgba(0,0,0,0.06); padding: 20px 0; margin-bottom: 60px; position: relative; z-index: 10; }
.header-inner { display: flex; justify-content: space-between; align-items: center; }
.back-nav { display: flex; align-items: center; gap: 8px; font-weight: 600; font-size: 0.9rem; color: #64748b; transition: color 0.2s; }
.back-nav:hover { color: #000; }
.checkout-brand { font-weight: 900; font-size: 1.25rem; letter-spacing: -0.04em; color: #000; }
.secure-badge { font-size: 0.75rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; color: #10b981; display: flex; align-items: center; gap: 6px; }

/* Premium Stepper */
.stepper-horizontal { max-width: 800px; margin: 0 auto 60px; display: flex; align-items: center; gap: 20px; }
.step-indicator { display: flex; align-items: center; gap: 12px; color: #cbd5e1; transition: all 0.3s; }
.step-indicator.active { color: #000; }
.step-indicator.completed { color: #10b981; }
.step-label { font-size: 1.5rem; font-weight: 900; letter-spacing: -0.05em; opacity: 0.3; }
.active .step-label { opacity: 1; }
.step-text { font-size: 0.75rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.1em; }
.step-line { flex-grow: 1; height: 2px; background: #f1f5f9; border-radius: 2px; }

/* Grid Layout */
.checkout-grid { 
    display: grid; 
    grid-template-columns: 1fr 400px; 
    gap: 60px; 
    align-items: flex-start; 
    max-width: 1300px;
    margin: 0 auto;
}

.checkout-flow-main {
    width: 100%;
    max-width: 750px;
    justify-self: center;
}

@media (max-width: 1200px) {
    .checkout-grid { gap: 40px; }
}

@media (max-width: 1024px) {
    .checkout-grid { grid-template-columns: 1fr 350px; gap: 30px; }
}

@media (max-width: 768px) {
    .checkout-wrapper { padding-bottom: 40px; }
    .checkout-header-minimal { margin-bottom: 30px; padding: 15px 0; }
    
    .stepper-horizontal { gap: 10px; margin-bottom: 40px; width: 100%; padding: 0 10px; }
    .step-line { display: none; }
    .step-indicator { flex-direction: column; gap: 4px; text-align: center; flex: 1; min-width: 0; }
    .step-label { font-size: 1.1rem; }
    .step-text { font-size: 0.6rem; letter-spacing: 0.05em; word-break: break-word; }

    .checkout-grid { 
        display: flex;
        flex-direction: column;
        gap: 40px; 
        width: 100%; 
    }
    
    .checkout-summary-sidebar {
        order: -1; /* Move Summary to top */
        width: 100%;
    }

    .checkout-flow-main { 
        max-width: 100%; 
        order: 2;
    }
    
    .content-card { padding: 30px 20px; border-radius: 20px; }
    .content-header h2 { font-size: 1.75rem; }
    
    .address-selection-grid { grid-template-columns: 1fr; }
    .payment-selection-grid { grid-template-columns: 1fr; }
    
    .action-footer { flex-direction: column-reverse; gap: 20px; }
    .btn-premium { width: 100%; justify-content: center; padding: 20px; }
    .btn-text { width: 100%; padding: 10px; }

    .receipt-card { position: static; padding: 25px; border-radius: 20px; }
}

/* Premium Cards */
.glass-premium { background: #fff; border: 1px solid rgba(0,0,0,0.08); border-radius: 24px; box-shadow: 0 10px 40px rgba(0,0,0,0.02); }
.content-card { padding: 50px; }
.content-header { margin-bottom: 40px; }
.content-header h2 { font-size: 2.25rem; font-weight: 800; letter-spacing: -0.04em; margin-bottom: 8px; }
.content-header p { color: #64748b; font-size: 1rem; }

/* Interactive Boxes */
.address-selection-grid { display: grid; grid-template-columns: 1fr; gap: 12px; margin-bottom: 24px; }
.address-box-premium, .shipping-row-premium { display: block; cursor: pointer; position: relative; }
.address-box-premium input, .shipping-row-premium input { position: absolute; opacity: 0; }
.box-inner, .row-inner { padding: 20px 24px; border: 2px solid #f1f5f9; border-radius: 18px; transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1); }
label:hover .box-inner, label:hover .row-inner { border-color: #e2e8f0; background: #fcfcfc; }
input:checked + .box-inner, input:checked + .row-inner { border-color: #000; background: #fff; box-shadow: 0 4px 20px rgba(0,0,0,0.05); }

.radio-circle { width: 18px; height: 18px; border: 2px solid #cbd5e1; border-radius: 50%; margin-bottom: 15px; position: relative; transition: all 0.2s; }
input:checked + .box-inner .radio-circle, input:checked + .row-inner .radio-circle { border-color: #000; }
input:checked + .box-inner .radio-circle::after, input:checked + .row-inner .radio-circle::after { content: ''; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 8px; height: 8px; background: #000; border-radius: 50%; }

.addr-title { display: block; font-weight: 700; font-size: 0.95rem; margin-bottom: 4px; }
.addr-meta { font-size: 0.85rem; color: #64748b; display: block; line-height: 1.4; }
.pill-mini { display: inline-block; font-size: 0.6rem; background: #000; color: #fff; padding: 2px 8px; border-radius: 4px; margin-top: 10px; font-weight: 800; text-transform: uppercase; }

/* Custom Inputs */
.input-premium-group { margin-top: 30px; }
.input-premium-group label { display: block; font-size: 0.8rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; color: #94a3b8; margin-bottom: 12px; }
.input-premium-group textarea { width: 100%; border: 2px solid #f1f5f9; border-radius: 16px; padding: 20px; font-family: inherit; font-size: 1rem; transition: all 0.2s; outline: none; background: #fafafa; }
.input-premium-group textarea:focus { border-color: #000; background: #fff; }

/* Payment Tiles */
.payment-selection-grid { display: grid; grid-template-columns: 1fr; gap: 12px; }
.payment-tile input { position: absolute; opacity: 0; }
.tile-inner { padding: 20px 24px; border: 2px solid #f1f5f9; border-radius: 20px; text-align: left; transition: all 0.2s; position: relative; height: 100%; display: flex; flex-direction: row; align-items: center; justify-content: flex-start; gap: 20px; }
input:checked + .tile-inner { border-color: #000; background: #fff; box-shadow: 0 4px 20px rgba(0,0,0,0.05); }
.tile-label { font-weight: 700; font-size: 0.95rem; flex-grow: 1; }
.tile-icon { width: 44px; height: 44px; background: #f8fafc; border-radius: 12px; display: flex; align-items: center; justify-content: center; border: 1px solid #f1f5f9; flex-shrink: 0; }
.tile-icon.gcash { background: #0055ff; color: #fff; }
.tile-icon.maya { background: #00ff88; color: #000; }
.tile-check { width: 18px; height: 18px; border: 2px solid #cbd5e1; border-radius: 50%; position: relative; flex-shrink: 0; }
input:checked + .tile-inner .tile-check { background: #000; border-color: #000; box-shadow: inset 0 0 0 4px #fff; }

/* Receipt Styling */
.receipt-card { background: #fff; border: 1px solid rgba(0,0,0,0.08); border-radius: 28px; padding: 30px; position: sticky; top: 130px; }
.receipt-title { font-size: 1.1rem; font-weight: 800; margin-bottom: 25px; letter-spacing: -0.02em; }
.receipt-item { display: flex; align-items: center; gap: 15px; margin-bottom: 15px; }
.ri-img-wrapper { position: relative; }
.ri-img-wrapper img { width: 60px; height: 60px; object-fit: cover; border-radius: 12px; background: #f1f5f9; }
.ri-qty { position: absolute; top: -6px; right: -6px; background: #000; color: #fff; width: 20px; height: 20px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.7rem; font-weight: 800; border: 2px solid #fff; }
.ri-details { flex-grow: 1; min-width: 0; }
.ri-name { display: block; font-size: 0.85rem; font-weight: 700; color: #000; margin-bottom: 2px; word-break: break-word; }
.ri-meta { font-size: 0.75rem; color: #64748b; font-weight: 600; }
.ri-price { font-size: 0.85rem; font-weight: 800; flex-shrink: 0; }

.receipt-totals { margin-top: 30px; border-top: 1px dashed #e2e8f0; padding-top: 25px; }
.total-row { display: flex; justify-content: space-between; margin-bottom: 12px; font-size: 0.95rem; font-weight: 600; color: #64748b; }
.total-row.final { border-top: 1px solid #f1f5f9; margin-top: 15px; padding-top: 15px; font-size: 1.25rem; font-weight: 900; color: #000; }
.success-text { color: #10b981; font-weight: 800; }

.receipt-badge { margin-top: 25px; background: #f0fdf4; color: #166534; padding: 10px; border-radius: 12px; font-size: 0.7rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; display: flex; align-items: center; justify-content: center; gap: 8px; }

/* Buttons & Actions */
.action-footer { display: flex; justify-content: space-between; align-items: center; margin-top: 50px; }
.btn-premium { background: #000; color: #fff; border: none; padding: 18px 35px; border-radius: 16px; font-weight: 700; font-size: 0.95rem; cursor: pointer; display: flex; align-items: center; gap: 12px; transition: all 0.2s; }
.btn-premium:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(0,0,0,0.15); }
.btn-text { background: none; border: none; color: #64748b; font-weight: 700; cursor: pointer; font-size: 0.95rem; }
.btn-text:hover { color: #000; }

/* Step Logic */
.step-content { display: none; }
.step-content.active { display: block; animation: slideUp 0.5s cubic-bezier(0.16, 1, 0.3, 1); }

@keyframes slideUp {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

.hidden { display: none; }
</style>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const steps = document.querySelectorAll('.step-content');
    const indicators = document.querySelectorAll('.step-indicator');
    
    function updateSteps(targetId) {
        steps.forEach(s => s.classList.remove('active'));
        document.getElementById('step-' + targetId).classList.add('active');
        
        indicators.forEach(ind => {
            const stepNum = parseInt(ind.getAttribute('data-step'));
            const targetNum = parseInt(targetId);
            
            ind.classList.remove('active', 'completed');
            if(stepNum < targetNum) ind.classList.add('completed');
            if(stepNum === targetNum) ind.classList.add('active');
        });

        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    document.querySelectorAll('.btn-next').forEach(btn => {
        btn.addEventListener('click', () => {
            const next = btn.getAttribute('data-next');
            
            // Validation
            if(next === "2") {
                const addrSelected = document.querySelector('input[name="address_id"]:checked');
                if(!addrSelected) {
                    alert("Please select a saved address or add a new one.");
                    return;
                }
            }
            
            updateSteps(next);
        });
    });

    document.querySelectorAll('.btn-prev').forEach(btn => {
        btn.addEventListener('click', () => {
            updateSteps(btn.getAttribute('data-prev'));
        });
    });

    // Address Box Logic
    document.querySelectorAll('input[name="address_id"]').forEach(radio => {
        radio.addEventListener('change', (e) => {
            const boxes = document.querySelectorAll('.address-box-premium');
            boxes.forEach(b => b.classList.remove('selected'));
            if(e.target.closest('.address-box-premium')) {
                e.target.closest('.address-box-premium').classList.add('selected');
            }
        });
    });
});
</script>

<!-- Address Modal -->
<div id="address-modal" class="modal-overlay">
    <div class="modal-content glass-premium-modal">
        <div class="modal-header-premium">
            <div class="modal-title-group">
                <span class="modal-subtitle">Add Address</span>
                <h3 style="margin:0; font-size:1.5rem; font-weight:800;">New Delivery Location</h3>
            </div>
            <button type="button" class="modal-close-btn" onclick="document.getElementById('address-modal').classList.remove('active')">&times;</button>
        </div>
        <div class="modal-body-premium">
            <form action="/php/Webdev/public/cart/add_address" method="POST">
                <div class="input-premium-group" style="margin-top: 0; margin-bottom: 15px;">
                    <label>Address Category</label>
                    <select name="category" required style="width: 100%; border: 2px solid #f1f5f9; border-radius: 12px; padding: 12px 15px; font-family: inherit; font-size: 0.95rem; outline: none; transition: border-color 0.2s;">
                        <option value="Home Address">Home Address</option>
                        <option value="Business Address">Business Address</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                <div class="input-premium-group" style="margin-top: 0; margin-bottom: 15px;">
                    <label>Street Address</label>
                    <input type="text" name="street_address" required pattern="[a-zA-Z0-9\s\.,#-]+" title="Please enter a valid street address (letters, numbers, and basic punctuation)" style="width: 100%; border: 2px solid #f1f5f9; border-radius: 12px; padding: 12px 15px; font-family: inherit; font-size: 0.95rem; outline: none; transition: border-color 0.2s;" placeholder="House/Unit No., Street, Subdivision/Barangay">
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">
                    <div class="input-premium-group" style="margin-top: 0;">
                        <label>City</label>
                        <input type="text" name="city" required pattern="[a-zA-Z\s\-]+" oninput="this.value = this.value.replace(/[^a-zA-Z\s\-]/g, '')" title="Letters only" style="width: 100%; border: 2px solid #f1f5f9; border-radius: 12px; padding: 12px 15px; font-family: inherit; font-size: 0.95rem; outline: none; transition: border-color 0.2s;">
                    </div>
                    <div class="input-premium-group" style="margin-top: 0;">
                        <label>Province</label>
                        <input type="text" name="province" required pattern="[a-zA-Z\s\-]+" oninput="this.value = this.value.replace(/[^a-zA-Z\s\-]/g, '')" title="Letters only" style="width: 100%; border: 2px solid #f1f5f9; border-radius: 12px; padding: 12px 15px; font-family: inherit; font-size: 0.95rem; outline: none; transition: border-color 0.2s;">
                    </div>
                </div>
                <div class="input-premium-group" style="margin-top: 0; margin-bottom: 25px;">
                    <label>Postal Code</label>
                    <input type="text" name="postal_code" required pattern="\d+" oninput="this.value = this.value.replace(/[^0-9]/g, '')" title="Numbers only" style="width: 100%; border: 2px solid #f1f5f9; border-radius: 12px; padding: 12px 15px; font-family: inherit; font-size: 0.95rem; outline: none; transition: border-color 0.2s;">
                </div>
                <button type="submit" class="btn-premium" style="width: 100%; justify-content: center;">Save Address</button>
            </form>
        </div>
    </div>
</div>
