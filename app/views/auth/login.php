<div class="auth-page">
    <div class="auth-card glass-card">
        <h2>Welcome Back</h2>
        <p>Sign in to your BLISS account.</p>
        <form action="/php/Webdev/public/auth/process_login" method="POST">
            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" required placeholder="name@example.com">
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required placeholder="Enter your password">
            </div>
            <button type="submit" class="btn btn-primary w-100">Sign In</button>
        </form>
        <div class="auth-footer">
            <p>Don't have an account? <a href="/php/Webdev/public/auth/register">Join Us</a></p>
        </div>
    </div>
</div>
