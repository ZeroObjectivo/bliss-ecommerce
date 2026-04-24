<div class="auth-page">
    <div class="auth-card glass-card">
        <h2>Become a Member</h2>
        <p>Create your BLISS profile and get first access to the very best of BLISS products.</p>
        <form action="/php/Webdev/public/auth/process_register" method="POST">
            <div class="form-group">
                <label>Full Name</label>
                <input type="text" name="name" required placeholder="John Doe">
            </div>
            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" required placeholder="name@example.com">
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required minlength="6" placeholder="Create a password">
            </div>
            <button type="submit" class="btn btn-primary w-100">Join Us</button>
        </form>
        <div class="auth-footer">
            <p>Already a member? <a href="/php/Webdev/public/auth/login">Sign In</a></p>
        </div>
    </div>
</div>
