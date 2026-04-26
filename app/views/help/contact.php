<div class="contact-page">
    <!-- Background Decoration -->
    <div class="contact-bg-glow"></div>
    
    <div class="container">
        <header class="contact-hero reveal-on-scroll">
            <span class="hero-badge">Get in Touch</span>
            <h1 class="hero-title">We'd love to hear from you.</h1>
            <p class="hero-subtitle">Whether you have a question about products, shipping, or anything else, our team is ready to answer all your questions.</p>
            <div style="margin-top: 30px; display: flex; justify-content: center; align-items: center; gap: 15px; flex-wrap: wrap;">
                <span style="font-size: 0.95rem; font-weight: 600; color: rgba(255,255,255,0.7);">Already submitted a concern?</span>
                <?php if(isset($_SESSION['user_id'])): ?>
                    <a href="/php/Webdev/public/profile/inbox" class="btn" style="background: rgba(255,255,255,0.15); backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.25); color: white; text-decoration: none; font-weight: 700; padding: 12px 25px; border-radius: 14px; display: inline-flex; align-items: center; gap: 10px; transition: all 0.3s ease;" onmouseover="this.style.background='white'; this.style.color='black'" onmouseout="this.style.background='rgba(255,255,255,0.15)'; this.style.color='white'">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                        Check My Inbox
                    </a>
                <?php else: ?>
                    <a href="/php/Webdev/public/help/track_ticket" class="btn" style="background: rgba(255,255,255,0.15); backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.25); color: white; text-decoration: none; font-weight: 700; padding: 12px 25px; border-radius: 14px; display: inline-flex; align-items: center; gap: 10px; transition: all 0.3s ease;" onmouseover="this.style.background='white'; this.style.color='black'" onmouseout="this.style.background='rgba(255,255,255,0.15)'; this.style.color='white'">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15.33V21a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-5.67"></path><path d="M7 11l5 5 5-5"></path><line x1="12" y1="4" x2="12" y2="16"></line></svg>
                        Track Existing Request
                    </a>
                <?php endif; ?>
            </div>
        </header>

        <div class="contact-wrapper">
            <!-- Contact Info -->
            <div class="contact-info-side reveal-on-scroll" style="transition-delay: 0.1s;">
                <div class="info-card glass-card">
                    <div class="info-item">
                        <div class="info-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                        </div>
                        <div class="info-text">
                            <h3>Phone</h3>
                            <p>+63 2 8883 1867</p>
                            <span>Mon-Fri from 9am to 9pm.</span>
                        </div>
                    </div>

                    <div class="info-item">
                        <div class="info-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                        </div>
                        <div class="info-text">
                            <h3>Email</h3>
                            <p>store@bliss.com</p>
                            <span>We'll respond within 24 hours.</span>
                        </div>
                    </div>

                    <div class="info-item">
                        <div class="info-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                        </div>
                        <div class="info-text">
                            <h3>STORE</h3>
                            <p>University of Makati</p>
                            <span>J.P. Rizal Ext, Makati, 1215</span>
                        </div>
                    </div>
                </div>

                <div class="social-connect glass-card">
                    <h3>Follow our journey</h3>
                    <div class="social-links">
                        <a href="https://facebook.com" target="_blank" class="social-icon-btn">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path></svg>
                        </a>
                        <a href="https://instagram.com" target="_blank" class="social-icon-btn">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line></svg>
                        </a>
                        <a href="https://twitter.com" target="_blank" class="social-icon-btn">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M23 3a10.9 10.9 0 0 1-3.14 1.53 4.48 4.48 0 0 0-7.86 3v1A10.66 10.66 0 0 1 3 4s-4 9 5 13a11.64 11.64 0 0 1-7 2c9 5 20 0 20-11.5a4.5 4.5 0 0 0-.08-.83A7.72 7.72 0 0 0 23 3z"></path></svg>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Form Side -->
            <div class="contact-form-side reveal-on-scroll" style="transition-delay: 0.2s;">
                <?php if(isset($_GET['success'])): ?>
                    <?php $t = $_GET['t'] ?? ''; ?>
                    <div class="alert alert-success-proper" id="success-alert" style="height: auto; padding: 20px 25px;">
                        <div class="alert-icon-circle">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"></polyline></svg>
                        </div>
                        <div class="alert-content" style="flex-grow: 1; margin-right: 15px;">
                            <div style="font-weight: 800; font-size: 1rem; margin-bottom: 4px;">Concern Submitted Successfully</div>
                            <div style="font-size: 0.85rem; line-height: 1.4; opacity: 0.9;">
                                Your ticket number is <strong style="text-decoration: underline;"><?= htmlspecialchars($t) ?></strong>.
                                <?php if(isset($_SESSION['user_id'])): ?>
                                    You can track its progress in your <a href="/php/Webdev/public/profile/inbox" style="color: inherit; font-weight: 800;">Inbox</a>.
                                <?php else: ?>
                                    Please <strong>copy and save</strong> this number to track your request.
                                <?php endif; ?>
                            </div>
                        </div>
                        <button class="alert-close-btn" onclick="closeSuccessAlert()" style="align-self: flex-start; margin-top: -5px;">
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
                            url.searchParams.delete('t');
                            window.history.replaceState({}, document.title, url);
                        }, 8000);
                    </script>
                <?php endif; ?>
                <?php if(isset($_GET['error'])): ?>
                    <div class="alert alert-error" style="background: #fee2e2; color: #991b1b; padding: 15px; border-radius: 12px; margin-bottom: 20px; border: 1px solid #fecaca;">
                        <?= str_replace('_', ' ', $_GET['error']) ?>
                    </div>
                <?php endif; ?>

                <div class="form-container glass-card">
                    <div class="form-header">
                        <h2>Write to Us</h2>
                        <p>Have a specific concern? Send us a message and we'll get back to you.</p>
                    </div>

                    <!-- Internal Action Block for existing concerns -->
                    <div style="background: rgba(0,0,0,0.03); padding: 15px 20px; border-radius: 16px; border: 1px solid rgba(0,0,0,0.05); margin-bottom: 30px; display: flex; justify-content: space-between; align-items: center; gap: 15px;">
                        <span style="font-size: 0.85rem; font-weight: 600; color: var(--text-secondary);">Already submitted a concern?</span>
                        <?php if(isset($_SESSION['user_id'])): ?>
                            <a href="/php/Webdev/public/profile/inbox" style="color: var(--accent-color); text-decoration: none; font-weight: 700; font-size: 0.85rem; display: flex; align-items: center; gap: 6px;">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                                Check My Inbox
                            </a>
                        <?php else: ?>
                            <a href="/php/Webdev/public/help/track_ticket" style="color: var(--accent-color); font-weight: 700; text-decoration: none; font-size: 0.85rem; display: flex; align-items: center; gap: 6px;">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15.33V21a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-5.67"></path><path d="M7 11l5 5 5-5"></path><line x1="12" y1="4" x2="12" y2="16"></line></svg>
                                Track Request
                            </a>
                        <?php endif; ?>
                    </div>

                    <form id="contact-page-form" action="/php/Webdev/public/help/send_concern" method="POST">
                        <input type="hidden" name="redirect" value="contact">
                        
                        <?php if(!isset($_SESSION['user_id'])): ?>
                            <div class="form-group">
                                <label>Email Address</label>
                                <input type="email" name="email" required placeholder="your@email.com" style="width: 100%; padding: 14px 18px; border: 1px solid #e2e8f0; border-radius: 12px; background: rgba(255,255,255,0.5);">
                                <small style="display: block; margin-top: 5px; color: var(--text-secondary); font-size: 0.75rem;">Required for guests and suspended accounts.</small>
                            </div>
                        <?php endif; ?>

                        <div class="form-group">
                            <label>What can we help you with?</label>
                            <select name="subject" required>
                                <option value="" disabled selected>Select a subject</option>
                                <option value="Order Inquiry">Order Inquiry</option>
                                <option value="Account Suspension Appeal">Account Suspension Appeal</option>
                                <option value="Returns & Refunds">Returns & Refunds</option>
                                <option value="Account Issues">Account Issues</option>
                                <option value="Product Information">Product Information</option>
                                <option value="Technical Support">Technical Support</option>
                                <option value="Feedback">Feedback</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Your Message</label>
                            <textarea name="message" required placeholder="Tell us more about your concern..."></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary btn-large w-full" style="justify-content: center;">Send Message</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('contact-page-form')?.addEventListener('submit', function(e) {
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
            form.reset();
            showDynamicAlert('Your concern has been successfully submitted.', data.ticket_number, data.is_logged_in);
        } else {
            alert('Failed to send message. Please try again.');
        }
    })
    .catch(err => console.error('Error:', err));
});

function showDynamicAlert(message, ticketNumber = '', isLoggedIn = false) {
    const alertHtml = `
        <div class="alert alert-success-proper" id="success-alert" style="height: auto; padding: 20px 25px;">
            <div class="alert-icon-circle">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"></polyline></svg>
            </div>
            <div class="alert-content" style="flex-grow: 1; margin-right: 15px; text-align: left;">
                <div style="font-weight: 800; font-size: 1rem; margin-bottom: 4px;">Concern Submitted Successfully</div>
                <div style="font-size: 0.85rem; line-height: 1.4; opacity: 0.9;">
                    Your ticket number is <strong style="text-decoration: underline;">${ticketNumber}</strong>.
                    ${isLoggedIn 
                        ? `You can track its progress in your <a href="/php/Webdev/public/profile/inbox" style="color: inherit; font-weight: 800;">Inbox</a>.` 
                        : `Please <strong>copy and save</strong> this number to track your request.`}
                </div>
            </div>
            <button class="alert-close-btn" onclick="closeSuccessAlert()" style="align-self: flex-start; margin-top: -5px;">
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
    }, 8000);
}
</script>