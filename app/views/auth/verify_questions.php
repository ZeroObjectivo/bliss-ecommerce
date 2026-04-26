<div class="auth-page">
    <div class="auth-card glass-card">
        <h2>Security Check</h2>
        <p>Please answer your security questions to continue.</p>

        <?php if(isset($_GET['error']) && $_GET['error'] == 'incorrect'): ?>
            <p style="color: #ef4444; font-size: 0.85rem; margin-bottom: 15px; text-align: center; background: #fef2f2; padding: 10px; border-radius: 8px;">One or more answers are incorrect.</p>
        <?php endif; ?>

        <form action="/php/Webdev/public/auth/process_verify_questions" method="POST">
            <div class="form-group">
                <label><?= htmlspecialchars($data['q1']) ?></label>
                <input type="text" name="a1" required placeholder="Your answer">
            </div>
            <div class="form-group">
                <label><?= htmlspecialchars($data['q2']) ?></label>
                <input type="text" name="a2" required placeholder="Your answer">
            </div>
            <div class="form-group">
                <label><?= htmlspecialchars($data['q3']) ?></label>
                <input type="text" name="a3" required placeholder="Your answer">
            </div>
            <button type="submit" class="btn btn-primary w-100">Verify Identity</button>
        </form>
        <div class="auth-footer">
            <p>Back to <a href="/php/Webdev/public/auth/login">Sign In</a></p>
        </div>
    </div>
</div>
