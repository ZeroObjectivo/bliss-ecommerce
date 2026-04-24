<div class="help-page">
    <div class="container">
        <?php if(isset($_GET['success'])): ?>
            <div class="alert alert-success-proper" id="success-alert">
                <div class="alert-icon-circle">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"></polyline></svg>
                </div>
                <span class="alert-text">Your concern has been successfully submitted to our support team.</span>
                <button class="alert-close-btn" onclick="closeSuccessAlert()">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
                <div class="alert-progress-bar"></div>
            </div>
            <script>
                function closeSuccessAlert() {
                    const alert = document.getElementById('success-alert');
                    if(alert) {
                        alert.style.opacity = '0';
                        alert.style.transform = 'translateX(-50%) translateY(-20px)';
                        alert.style.transition = 'all 0.4s cubic-bezier(0.4, 0, 0.2, 1)';
                        setTimeout(() => alert.remove(), 400);
                    }
                }
                setTimeout(() => {
                    closeSuccessAlert();
                    const url = new URL(window.location);
                    url.searchParams.delete('success');
                    window.history.replaceState({}, document.title, url);
                }, 3000);
            </script>
        <?php endif; ?>
        <?php if(isset($_GET['error'])): ?>
            <div class="alert alert-error" style="background: #fee2e2; color: #991b1b; padding: 15px; border-radius: 12px; margin-bottom: 20px; text-align: center;">
                <?= str_replace('_', ' ', $_GET['error']) ?>
            </div>
        <?php endif; ?>

        <header class="help-hero">
            <span class="hero-badge">Help Center</span>
            <h1 class="hero-title">How can we help?</h1>
            <div class="help-search-container">
                <svg class="search-icon-absolute" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                <input type="text" class="help-search-input" placeholder="Search for orders, returns, and more...">
            </div>
        </header>

        <div class="help-grid">
            <div class="glass-card help-category-card" data-category="shipping">
                <div class="category-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="3" width="15" height="13"></rect><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon><circle cx="5.5" cy="18.5" r="2.5"></circle><circle cx="18.5" cy="18.5" r="2.5"></circle></svg>
                </div>
                <h3>Orders & Shipping</h3>
                <p>Track your package, view history, or change delivery details.</p>
            </div>

            <div class="glass-card help-category-card" data-category="returns">
                <div class="category-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="23 4 23 10 17 10"></polyline><polyline points="1 20 1 14 7 14"></polyline><path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"></path></svg>
                </div>
                <h3>Returns & Refunds</h3>
                <p>Start a return, check refund status, or read our policy.</p>
            </div>

            <div class="glass-card help-category-card" data-category="account">
                <div class="category-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                </div>
                <h3>Account Settings</h3>
                <p>Manage your profile, change passwords, and privacy.</p>
            </div>

            <div class="glass-card help-category-card" data-category="guides">
                <div class="category-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path><line x1="7" y1="7" x2="7.01" y2="7"></line></svg>
                </div>
                <h3>Product Guides</h3>
                <p>Size charts, care instructions, and material details.</p>
            </div>
        </div>

        <section class="faq-section">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: var(--spacing-6);">
                <h2 id="faq-title" style="margin-bottom: 0;">Frequently Asked Questions</h2>
                <button id="clear-filter" style="display: none; background: none; border: 1px solid var(--accent-color); color: var(--accent-color); padding: 5px 15px; border-radius: var(--radius-full); cursor: pointer; font-size: 0.85rem; font-weight: 600;">Show All</button>
            </div>
            
            <div class="faq-list">
                <!-- Orders & Shipping -->
                <div class="faq-item" data-category="shipping">
                    <h4>How do I track my order?</h4>
                    <p>Once your order ships, you'll receive an email with a tracking number and a link to follow your package's journey.</p>
                </div>
                <div class="faq-item" data-category="shipping">
                    <h4>Do you ship internationally?</h4>
                    <p>Yes, we currently ship to over 50 countries worldwide. International shipping rates and delivery times vary by location.</p>
                </div>
                <div class="faq-item" data-category="shipping">
                    <h4>How long does shipping take?</h4>
                    <p>Standard shipping usually takes 3-5 business days. Express shipping is available for 1-2 business day delivery.</p>
                </div>
                <div class="faq-item" data-category="shipping">
                    <h4>Can I change my shipping address?</h4>
                    <p>Shipping addresses can be modified within 30 minutes of placement. Please contact support immediately for changes.</p>
                </div>

                <!-- Returns & Refunds -->
                <div class="faq-item" data-category="returns">
                    <h4>What is your return policy?</h4>
                    <p>You can return any unworn items within 30 days of purchase. Items must be in their original packaging with tags attached.</p>
                </div>
                <div class="faq-item" data-category="returns">
                    <h4>How do I start a return?</h4>
                    <p>Log into your account, go to 'My Orders', select the item you want to return, and click 'Start Return' to get your prepaid label.</p>
                </div>
                <div class="faq-item" data-category="returns">
                    <h4>How long do refunds take?</h4>
                    <p>Once we receive your return, it takes 3-5 business days to process. The refund will appear on your statement in 5-10 days.</p>
                </div>
                <div class="faq-item" data-category="returns">
                    <h4>Can I exchange my item?</h4>
                    <p>Yes, we offer free exchanges for size or color within 30 days. Start the exchange process through your order history.</p>
                </div>

                <!-- Account Settings -->
                <div class="faq-item" data-category="account">
                    <h4>How do I change my password?</h4>
                    <p>Go to Account Settings > Security and click 'Change Password'. You'll need your current password to set a new one.</p>
                </div>
                <div class="faq-item" data-category="account">
                    <h4>Can I change my email address?</h4>
                    <p>Yes, you can update your email in the Profile section. You'll need to verify the new email via a confirmation link.</p>
                </div>
                <div class="faq-item" data-category="account">
                    <h4>How do I update my profile picture?</h4>
                    <p>In your Profile view, click the 'Edit Profile' button to upload a new image from your device.</p>
                </div>
                <div class="faq-item" data-category="account">
                    <h4>Is my personal information secure?</h4>
                    <p>We use industry-standard encryption (AES-256) and secure servers to ensure your data and payment information stay protected.</p>
                </div>

                <!-- Product Guides -->
                <div class="faq-item" data-category="guides">
                    <h4>How do I know my size?</h4>
                    <p>Check our detailed Size Guide on every product page. We provide measurements for height, chest, waist, and hips.</p>
                </div>
                <div class="faq-item" data-category="guides">
                    <h4>How should I wash my items?</h4>
                    <p>Most items are machine washable. We recommend cold water and air drying to preserve the lifespan and color of your clothing.</p>
                </div>
                <div class="faq-item" data-category="guides">
                    <h4>Are your materials sustainable?</h4>
                    <p>We are committed to sustainability, using 100% organic cotton and recycled polyester in over 60% of our collection.</p>
                </div>
                <div class="faq-item" data-category="guides">
                    <h4>Do you offer custom sizing?</h4>
                    <p>Currently, we only offer standard sizing. However, we are exploring custom tailoring options for select premium items.</p>
                </div>
            </div>

            <!-- Pagination Controls -->
            <div id="faq-pagination" class="faq-pagination" style="display: flex; justify-content: center; gap: 10px; margin-top: 30px;">
                <!-- Pagination buttons will be injected by JS -->
            </div>
        </section>

        <section class="contact-cta">
            <h2>Still need help?</h2>
            <p>Our support team is available 24/7 to assist you with any questions.</p>
            <div class="contact-buttons">
                <?php if(isset($_SESSION['user_id'])): ?>
                    <button onclick="document.getElementById('contact-modal').classList.add('active')" class="btn btn-primary btn-large">Write to Us</button>
                <?php else: ?>
                    <a href="/php/Webdev/public/auth/login" class="btn btn-primary btn-large">Sign in to Write to Us</a>
                <?php endif; ?>
            </div>
        </section>
    </div>
</div>

<!-- Contact Modal -->
<div id="contact-modal" class="modal-overlay">
    <div class="glass-card" style="width: 100%; max-width: 500px; padding: 30px; background: white; position: relative;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
            <h3 style="font-size: 1.5rem; font-weight: 800; color: #000;">Write to Us</h3>
            <button onclick="document.getElementById('contact-modal').classList.remove('active')" style="background: none; border: none; font-size: 1.5rem; cursor: pointer;">&times;</button>
        </div>
        <form id="help-contact-form" action="/php/Webdev/public/help/send_concern" method="POST">
            <div style="margin-bottom: 20px;">
                <label style="display: block; font-weight: 600; margin-bottom: 8px; color: #000;">Subject</label>
                <select name="subject" required style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; background: white; cursor: pointer;">
                    <option value="" disabled selected>Select your concern</option>
                    <option value="Order Inquiry">Order Inquiry</option>
                    <option value="Returns & Refunds">Returns & Refunds</option>
                    <option value="Account Issues">Account Issues</option>
                    <option value="Product Information">Product Information</option>
                    <option value="Technical Support">Technical Support</option>
                    <option value="Feedback">Feedback</option>
                    <option value="Other">Other</option>
                </select>
            </div>
            <div style="margin-bottom: 25px;">
                <label style="display: block; font-weight: 600; margin-bottom: 8px; color: #000;">Message</label>
                <textarea name="message" required style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; height: 150px; font-family: inherit;" placeholder="Describe your concern in detail..."></textarea>
            </div>
            <button type="submit" class="btn btn-primary btn-large" style="width: 100%; justify-content: center;">Send Message</button>
        </form>
    </div>
</div>

<script>
document.getElementById('help-contact-form')?.addEventListener('submit', function(e) {
    e.preventDefault();
    const form = this;
    const formData = new FormData(form);
    
    fetch(form.action, {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            document.getElementById('contact-modal').classList.remove('active');
            form.reset();
            showDynamicAlert('Your concern has been successfully submitted to our support team.');
        } else {
            alert('Failed to send message. Please try again.');
        }
    })
    .catch(err => console.error('Error:', err));
});

function showDynamicAlert(message) {
    const alertHtml = `
        <div class="alert alert-success-proper" id="success-alert">
            <div class="alert-icon-circle">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"></polyline></svg>
            </div>
            <span class="alert-text">${message}</span>
            <button class="alert-close-btn" onclick="closeSuccessAlert()">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
            </button>
            <div class="alert-progress-bar"></div>
        </div>
    `;
    const div = document.createElement('div');
    div.innerHTML = alertHtml.trim();
    document.body.appendChild(div.firstChild);
    
    setTimeout(() => {
        closeSuccessAlert();
    }, 3000);
}
</script>
