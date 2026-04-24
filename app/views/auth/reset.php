<div class="auth-page">
    <div class="contact-bg-glow" style="top: 20%; left: 30%; opacity: 0.4;"></div>
    
    <div class="container" style="display: flex; justify-content: center; position: relative; z-index: 1;">
        <div class="glass-card auth-card reveal-on-scroll">
            <div class="auth-header" style="margin-bottom: 30px;">
                <h2 style="font-size: 2rem; font-weight: 900; letter-spacing: -0.02em; margin-bottom: 8px;">Create New Password</h2>
                <p style="color: var(--text-secondary); font-size: 0.95rem;">Please secure your account with a new password below.</p>
            </div>
            
            <?php if(isset($data['error'])): ?>
                <div class="alert alert-error" style="display: flex; align-items: center; gap: 10px; padding: 15px; border-radius: 12px; background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; margin-bottom: 25px; text-align: left; font-size: 0.9rem; font-weight: 500;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                    <?= $data['error'] ?>
                </div>
                <?php if($data['error'] == 'This password reset link is invalid or has expired.'): ?>
                    <a href="/php/Webdev/public/auth/login" class="btn btn-primary btn-large w-full" style="justify-content: center;">Return to Login</a>
                <?php endif; ?>
            <?php endif; ?>

            <?php if(!isset($data['error']) || $data['error'] !== 'This password reset link is invalid or has expired.'): ?>
                <form action="/php/Webdev/public/auth/process_reset" method="POST">
                    <input type="hidden" name="token" value="<?= htmlspecialchars($data['token']) ?>">
                    
                    <div class="form-group">
                        <label style="font-weight: 700; color: #000; margin-bottom: 8px; display: block;">New Password</label>
                        <input type="password" name="password" required minlength="6" placeholder="At least 6 characters" 
                               style="width: 100%; padding: 14px 18px; border: 1px solid #e2e8f0; border-radius: 12px; background: #f8fafc; font-family: inherit;">
                    </div>
                    
                    <div class="form-group" style="margin-bottom: 30px;">
                        <label style="font-weight: 700; color: #000; margin-bottom: 8px; display: block;">Confirm Password</label>
                        <input type="password" name="password_confirm" required minlength="6" placeholder="Repeat new password"
                               style="width: 100%; padding: 14px 18px; border: 1px solid #e2e8f0; border-radius: 12px; background: #f8fafc; font-family: inherit;">
                    </div>
                    
                    <button type="submit" class="btn btn-primary btn-large w-full" style="justify-content: center; padding: 16px;">
                        Save & Secure Password
                    </button>
                </form>
            <?php endif; ?>

            <div class="auth-footer" style="margin-top: 25px; font-size: 0.9rem; color: var(--text-secondary);">
                Remembered your password? <a href="/php/Webdev/public/auth/login" style="color: var(--accent-color); font-weight: 700; text-decoration: none;">Sign In</a>
            </div>
        </div>
    </div>
</div>
