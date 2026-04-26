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
                <input type="password" name="password" required 
                       pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$" 
                       title="Must be at least 8 characters long and contain at least one uppercase letter, one lowercase letter, and one number."
                       placeholder="Create a password">
                <small style="display: block; margin-top: 5px; color: #64748b; font-size: 0.75rem; line-height: 1.4;">
                    Minimum 8 characters, including uppercase, lowercase, and a number.
                </small>
            </div>

            <hr style="margin: 20px 0; border: none; border-top: 1px solid rgba(0,0,0,0.1);">
            <p style="font-size: 0.85rem; color: #64748b; margin-bottom: 15px;">Set up security questions to recover your account.</p>

            <?php 
            $questions = [
                "What was the name of your first pet?",
                "What is your mother's maiden name?",
                "What was the name of your elementary school?",
                "In what city were you born?",
                "What is your favorite movie?",
                "What was your first car?",
                "What is your favorite book?"
            ];
            ?>

            <?php for($i=1; $i<=3; $i++): ?>
            <div class="form-group">
                <label>Security Question <?= $i ?></label>
                <select name="security_question_<?= $i ?>" required style="width: 100%; padding: 12px; border-radius: 12px; border: 1px solid #e2e8f0; background: white; margin-bottom: 10px;">
                    <option value="" disabled selected>Select a question</option>
                    <?php foreach($questions as $q): ?>
                        <option value="<?= htmlspecialchars($q) ?>"><?= htmlspecialchars($q) ?></option>
                    <?php endforeach; ?>
                </select>
                <input type="text" name="security_answer_<?= $i ?>" required placeholder="Your answer" style="width: 100%; padding: 12px; border-radius: 12px; border: 1px solid #e2e8f0;">
            </div>
            <?php endfor; ?>

            <button type="submit" class="btn btn-primary w-100" style="margin-top: 10px;">Join Us</button>
        </form>
        <div class="auth-footer">
            <p>Already a member? <a href="/php/Webdev/public/auth/login">Sign In</a></p>
        </div>
    </div>
</div>
