<div class="auth-page">
    <div class="auth-card glass-card">
        <h2>Reset Password</h2>
        <p>Enter your email address to find your account.</p>

        <?php if(isset($_GET['error']) && $_GET['error'] == 'not_found'): ?>
            <p style="color: #ef4444; font-size: 0.85rem; margin-bottom: 15px; text-align: center; background: #fef2f2; padding: 10px; border-radius: 8px;">No account found with that email address.</p>
        <?php endif; ?>

        <form action="/php/Webdev/public/auth/process_forgot_password" method="POST">
            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" required placeholder="name@example.com">
            </div>
            <button type="submit" class="btn btn-primary w-100">Continue</button>
        </form>
        <div class="auth-footer">
            <p>Remember your password? <a href="/php/Webdev/public/auth/login">Sign In</a></p>
        </div>
    </div>
</div>
