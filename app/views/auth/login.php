<div class="auth-page">
    <div class="auth-card glass-card">
        <h2>Welcome Back</h2>
        <p>Sign in to your BLISS account.</p>

        <?php if(isset($_GET['error']) && $_GET['error'] == 'suspended'): ?>
            <div style="background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.2); color: #f87171; padding: 12px; border-radius: 12px; margin-bottom: 20px; font-size: 0.85rem; text-align: center; line-height: 1.5;">
                Your account has been suspended.<br>
                Please <a href="/php/Webdev/public/help/contact" style="color: white; font-weight: 700; text-decoration: underline;">contact support</a> for assistance.
            </div>
        <?php endif; ?>

        <form action="/php/Webdev/public/auth/process_login" method="POST">
            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" required placeholder="name@example.com">
            </div>
            <div class="form-group">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <label>Password</label>
                    <a href="/php/Webdev/public/auth/forgot_password" style="font-size: 0.8rem; color: var(--accent-color); text-decoration: none;">Forgot Password?</a>
                </div>
                <input type="password" name="password" required placeholder="Enter your password">
            </div>
            <button type="submit" class="btn btn-primary w-100">Sign In</button>
        </form>
        <div class="auth-footer">
            <p>Don't have an account? <a href="/php/Webdev/public/auth/register">Join Us</a></p>
        </div>
    </div>
</div>
