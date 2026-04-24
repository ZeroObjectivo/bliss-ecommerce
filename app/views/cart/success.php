<div class="cart-page" style="min-height: 70vh; display: flex; align-items: center; justify-content: center;">
    <div class="container" style="max-width: 600px; text-align: center;">
        <div class="empty-cart glass-card" style="padding: 60px 40px;">
            <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-bottom: 20px;">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                <polyline points="22 4 12 14.01 9 11.01"></polyline>
            </svg>
            <h1 style="margin-bottom: 15px; font-size: 2.5rem; color: var(--text-color);">Success!</h1>
            <p style="font-size: 1.1rem; color: var(--text-light); margin-bottom: 30px; line-height: 1.6;">
                Your order has been placed securely. We are processing it immediately.<br>
                Your invoice number is: <strong style="color:var(--text-color);">#<?= htmlspecialchars($data['order_id']) ?></strong>.
            </p>
            <a href="/php/Webdev/public/catalog" class="btn btn-primary" style="padding: 12px 30px;">Continue Shopping</a>
        </div>
    </div>
</div>
