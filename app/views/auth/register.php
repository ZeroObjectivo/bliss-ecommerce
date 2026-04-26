<div class="auth-page">
    <!-- Background Decoration -->
    <div class="contact-bg-glow" style="top: 20%; left: 30%; opacity: 0.4;"></div>
    
    <div class="container" style="display: flex; justify-content: center; position: relative; z-index: 1; padding: 60px 20px;">
        <div class="glass-card auth-card reveal-on-scroll" style="max-width: 500px; width: 100%; overflow: hidden;">
            
            <div id="multi-step-form-container" style="position: relative;">
                <div class="progress-bar">
                    <div class="progress-bar-fill" id="progress-bar-fill"></div>
                    <div class="step active" data-step="1">1</div>
                    <div class="step" data-step="2">2</div>
                    <div class="step" data-step="3">3</div>
                </div>

                <form action="/php/Webdev/public/auth/process_register" method="POST" id="registration-form">
                    <!-- Step 1: Account Info -->
                    <div class="form-step active" data-step="1">
                        <div class="auth-header" style="margin-bottom: 30px;">
                            <h2 style="font-size: 1.6rem;">Create Your Account</h2>
                            <p style="color: var(--text-secondary); font-size: 0.95rem;">Let's start with the basics.</p>
                        </div>
                        <div class="form-group">
                            <label>Full Name</label>
                            <input type="text" name="name" required placeholder="John Doe">
                        </div>
                        <div class="form-group">
                            <label>Email Address</label>
                            <input type="email" name="email" required placeholder="name@example.com">
                        </div>
                        <button type="button" class="btn btn-primary w-full next-step-btn" style="justify-content: center;">Continue</button>
                    </div>

                    <!-- Step 2: Password -->
                    <div class="form-step" data-step="2">
                        <div class="auth-header" style="margin-bottom: 30px;">
                            <h2 style="font-size: 1.6rem;">Secure Your Account</h2>
                            <p style="color: var(--text-secondary); font-size: 0.95rem;">Choose a strong password.</p>
                        </div>
                        <div class="form-group">
                            <label>Password</label>
                            <input type="password" name="password" required 
                                   pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$" 
                                   title="Must be at least 8 characters long and contain one uppercase, one lowercase, and one number."
                                   placeholder="Min. 8 characters + complexity">
                            <small style="display: block; margin-top: 5px; color: #64748b; font-size: 0.75rem;">
                                Must include uppercase, lowercase, and a number.
                            </small>
                        </div>
                        <div class="form-group">
                            <label>Confirm Password</label>
                            <input type="password" name="password_confirm" required>
                        </div>

                        <div id="password-alert" class="password-validation-alert">
                            <ul>
                                <li data-req="length">At least 8 characters</li>
                                <li data-req="upper">One uppercase letter</li>
                                <li data-req="lower">One lowercase letter</li>
                                <li data-req="number">One number</li>
                                <li data-req="match">Passwords match</li>
                            </ul>
                        </div>

                        <div style="display: flex; gap: 10px; margin-top: 15px;">
                            <button type="button" class="btn btn-secondary prev-step-btn" style="flex: 1; justify-content: center;">Back</button>
                            <button type="button" class="btn btn-primary next-step-btn" style="flex: 2; justify-content: center;">Continue</button>
                        </div>
                    </div>

                    <!-- Step 3: Security Questions -->
                    <div class="form-step" data-step="3">
                        <div class="auth-header" style="margin-bottom: 30px;">
                            <h2 style="font-size: 1.6rem;">Account Recovery</h2>
                            <p style="color: var(--text-secondary); font-size: 0.95rem;">Set up security questions in case you lose access.</p>
                        </div>
                        <?php 
                        $questions = [
                            "What was the name of your first pet?", "What is your mother's maiden name?",
                            "What was the name of your elementary school?", "In what city were you born?",
                            "What is your favorite movie?", "What was your first car?", "What is your favorite book?"
                        ];
                        ?>
                        <div style="display: flex; flex-direction: column; gap: 15px;">
                            <?php for($i=1; $i<=3; $i++): ?>
                            <div class="form-group">
                                <label style="font-size: 0.8rem; color: #64748b;">Question <?= $i ?></label>
                                <select name="security_question_<?= $i ?>" required>
                                    <option value="" disabled selected>Select a security question</option>
                                    <?php foreach($questions as $q): ?><option value="<?= htmlspecialchars($q) ?>"><?= htmlspecialchars($q) ?></option><?php endforeach; ?>
                                </select>
                                <input type="text" name="security_answer_<?= $i ?>" required placeholder="Your secret answer">
                            </div>
                            <?php endfor; ?>
                        </div>
                        <div style="display: flex; gap: 10px; margin-top: 20px;">
                            <button type="button" class="btn btn-secondary prev-step-btn" style="flex: 1; justify-content: center;">Back</button>
                            <button type="submit" class="btn btn-primary" style="flex: 2; justify-content: center;">Create My Account</button>
                        </div>
                    </div>
                </form>
            </div>
            
            <div class="auth-footer" style="padding: 20px 40px; background: #f8fafc; border-top: 1px solid #f1f5f9;">
                <p style="text-align: center; margin: 0; color: var(--text-secondary);">Already a member? <a href="/php/Webdev/public/auth/login" style="color: var(--accent-color); font-weight: 700; text-decoration: none;">Sign In.</a></p>
            </div>
        </div>
    </div>
</div>

<style>
.progress-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    position: relative;
    margin: 0 40px 30px;
}
.progress-bar::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 0;
    width: 100%;
    height: 3px;
    background: #e2e8f0;
    transform: translateY(-50%);
    z-index: 1;
}
.progress-bar-fill {
    position: absolute;
    top: 50%;
    left: 0;
    width: 0%;
    height: 3px;
    background: var(--accent-color);
    transform: translateY(-50%);
    z-index: 2;
    transition: width 0.4s ease;
}
.step {
    width: 30px; height: 30px;
    border-radius: 50%;
    background: #e2e8f0;
    color: var(--text-secondary);
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    border: 3px solid #e2e8f0;
    z-index: 3;
    transition: all 0.4s ease;
}
.step.active {
    background: white;
    color: var(--accent-color);
    border-color: var(--accent-color);
    transform: scale(1.1);
}

.password-validation-alert {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 15px;
    font-size: 0.8rem;
    display: none; /* Hidden by default */
}
.password-validation-alert ul {
    list-style: none;
    padding: 0;
    margin: 0;
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 8px;
}
.password-validation-alert li {
    color: #94a3b8;
    transition: all 0.3s;
    display: flex;
    align-items: center;
    gap: 6px;
}
.password-validation-alert li::before {
    content: '•';
    font-size: 1.2rem;
    line-height: 1;
}
.password-validation-alert li.valid {
    color: #10b981;
    font-weight: 600;
}
.password-validation-alert li.valid::before {
    content: '✓';
    font-size: 1rem;
}

.form-step {
    padding: 0 40px 40px;
    opacity: 0;
    visibility: hidden;
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    transform: translateX(30px);
    transition: all 0.4s ease-out;
}
.form-step.active {
    opacity: 1;
    visibility: visible;
    position: relative;
    transform: translateX(0);
}
</style>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const nextBtns = document.querySelectorAll('.next-step-btn');
    const prevBtns = document.querySelectorAll('.prev-step-btn');
    const formSteps = document.querySelectorAll('.form-step');
    const progressSteps = document.querySelectorAll('.progress-bar .step');
    const progressBarFill = document.getElementById('progress-bar-fill');
    let currentStep = 1;

    function updateProgress() {
        progressSteps.forEach((step, index) => {
            step.classList.toggle('active', index < currentStep);
        });
        const progressPercentage = ((currentStep - 1) / (progressSteps.length - 1)) * 100;
        progressBarFill.style.width = `${progressPercentage}%`;
    }

    function goToStep(step) {
        formSteps.forEach(s => s.classList.remove('active'));
        document.querySelector(`.form-step[data-step="${step}"]`).classList.add('active');
        currentStep = step;
        updateProgress();
    }

    const passwordInput = document.querySelector('input[name="password"]');
    const confirmInput = document.querySelector('input[name="password_confirm"]');
    const passwordStepBtn = document.querySelector('.form-step[data-step="2"] .next-step-btn');

    const passwordAlert = document.getElementById('password-alert');
    const reqs = {
        length: document.querySelector('[data-req="length"]'),
        upper: document.querySelector('[data-req="upper"]'),
        lower: document.querySelector('[data-req="lower"]'),
        number: document.querySelector('[data-req="number"]'),
        match: document.querySelector('[data-req="match"]'),
    };

    function validatePasswordStep() {
        const password = passwordInput.value;
        const confirm = confirmInput.value;
        
        const isLength = password.length >= 8;
        const isUpper = /[A-Z]/.test(password);
        const isLower = /[a-z]/.test(password);
        const isNumber = /\d/.test(password);
        const isMatch = password && password === confirm;

        reqs.length.classList.toggle('valid', isLength);
        reqs.upper.classList.toggle('valid', isUpper);
        reqs.lower.classList.toggle('valid', isLower);
        reqs.number.classList.toggle('valid', isNumber);
        reqs.match.classList.toggle('valid', isMatch);
        
        const allValid = isLength && isUpper && isLower && isNumber && isMatch;
        passwordStepBtn.disabled = !allValid;
        
        // Show/Hide alert
        if(password || confirm) {
            passwordAlert.style.display = 'block';
        } else {
            passwordAlert.style.display = 'none';
        }
    }

    if (passwordInput && confirmInput && passwordStepBtn) {
        passwordStepBtn.disabled = true;
        passwordInput.addEventListener('input', validatePasswordStep);
        confirmInput.addEventListener('input', validatePasswordStep);
    }

    nextBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            const currentFormStep = btn.closest('.form-step');
            const inputs = currentFormStep.querySelectorAll('input[required], select[required]');
            let allValid = true;
            
            inputs.forEach(input => {
                if (!input.checkValidity()) {
                    input.reportValidity(); 
                    allValid = false;
                }
            });

            // Special check for password confirmation mismatch
            if (currentFormStep.dataset.step === "2") {
                if (passwordInput.value !== confirmInput.value) {
                    allValid = false;
                    if(typeof window.showToast === 'function') {
                        window.showToast('Passwords do not match.', 'error');
                    } else {
                        alert('Passwords do not match.');
                    }
                }
            }

            if (allValid) {
                goToStep(currentStep + 1);
            }
        });
    });

    prevBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            goToStep(currentStep - 1);
        });
    });

    // Allow Enter key to proceed to next step
    formSteps.forEach(step => {
        const inputs = step.querySelectorAll('input');
        inputs.forEach(input => {
            input.addEventListener('keypress', (e) => {
                if (e.key === 'Enter') {
                    e.preventDefault(); // Prevent form submission
                    step.querySelector('.next-step-btn')?.click();
                }
            });
        });
    });
});
</script>
