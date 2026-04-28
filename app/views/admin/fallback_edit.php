<div style="margin-bottom: var(--spacing-3);">
    <a href="/php/Webdev/public/superadmin/hero_settings" style="color: var(--admin-text-muted); text-decoration: none; display: inline-flex; align-items: center; gap: 8px; font-weight: 500; transition: color 0.2s;" onmouseover="this.style.color='var(--admin-accent)'" onmouseout="this.style.color='var(--admin-text-muted)'">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
        Back to Hero Settings
    </a>
</div>

<div class="admin-card" style="max-width: 1000px; padding: 0; overflow: hidden; border: none; box-shadow: 0 10px 40px rgba(0,0,0,0.08);">
    <div style="background: linear-gradient(135deg, #1e293b 0%, #334155 100%); padding: 35px; color: white;">
        <h2 style="font-size: 1.75rem; font-weight: 800; letter-spacing: -0.02em; margin-bottom: 5px;">Edit Fallback Campaign</h2>
        <p style="opacity: 0.8; font-size: 0.95rem;">Configure the backup hero content for <strong><?= htmlspecialchars($data['fallback']['campaign_name']) ?></strong></p>
    </div>

    <form action="/php/Webdev/public/superadmin/fallback_edit/<?= $data['fallback']['id'] ?>" method="POST" style="padding: 40px; background: var(--admin-card);">
        
        <!-- Campaign Base -->
        <div class="form-group-admin" style="margin-bottom: 40px;">
            <label style="text-transform: uppercase; letter-spacing: 0.05em; font-size: 0.75rem; color: var(--admin-text-muted);">Campaign Identification</label>
            <input type="text" name="campaign_name" value="<?= htmlspecialchars($data['fallback']['campaign_name']) ?>" required style="font-size: 1.1rem; font-weight: 600; padding: 15px; border-radius: 12px; background: var(--admin-bg-soft); color: var(--admin-text-main);">
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 40px;">
            <!-- Step 1 & 2: Content -->
            <div style="display: flex; flex-direction: column; gap: 25px;">
                <div style="border-left: 4px solid var(--admin-accent); padding-left: 20px; margin-bottom: 10px;">
                    <h3 style="font-size: 0.9rem; font-weight: 800; text-transform: uppercase; color: var(--admin-text-main);">Hero Messaging</h3>
                </div>

                <div class="form-group-admin">
                    <label style="font-size: 0.85rem; font-weight: 700; color: var(--admin-text-main);">Badge Text</label>
                    <input type="text" name="badge_text" value="<?= htmlspecialchars($data['fallback']['badge_text']) ?>" placeholder="e.g. New Collection" style="background: var(--admin-bg); color: var(--admin-text-main);">
                </div>

                <div class="form-group-admin">
                    <label style="font-size: 0.85rem; font-weight: 700; color: var(--admin-text-main);">Hero Title</label>
                    <input type="text" name="hero_title" value="<?= htmlspecialchars($data['fallback']['hero_title']) ?>" placeholder="Main headline" style="background: var(--admin-bg); color: var(--admin-text-main);">
                </div>

                <div class="form-group-admin">
                    <label style="font-size: 0.85rem; font-weight: 700; color: var(--admin-text-main);">Hero Subtitle</label>
                    <input type="text" name="hero_subtitle" value="<?= htmlspecialchars($data['fallback']['hero_subtitle']) ?>" placeholder="Secondary supporting text" style="background: var(--admin-bg); color: var(--admin-text-main);">
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div class="form-group-admin">
                        <label style="font-size: 0.85rem; font-weight: 700; color: var(--admin-text-main);">Tagline</label>
                        <input type="text" name="tagline" value="<?= htmlspecialchars($data['fallback']['tagline']) ?>" style="background: var(--admin-bg); color: var(--admin-text-main);">
                    </div>
                    <div class="form-group-admin">
                        <label style="font-size: 0.85rem; font-weight: 700; color: var(--admin-text-main);">Category Pill</label>
                        <input type="text" name="category_pill" value="<?= htmlspecialchars($data['fallback']['category_pill']) ?>" style="background: var(--admin-bg); color: var(--admin-text-main);">
                    </div>
                </div>

                <div class="form-group-admin">
                    <label style="font-size: 0.85rem; font-weight: 700; color: var(--admin-text-main);">Description Body</label>
                    <textarea name="description" rows="4" style="padding: 15px; border-radius: 12px; resize: none; background: var(--admin-bg); color: var(--admin-text-main);"><?= htmlspecialchars($data['fallback']['description']) ?></textarea>
                </div>
            </div>

            <!-- Step 3: Action & Appearance -->
            <div style="display: flex; flex-direction: column; gap: 25px;">
                <div style="border-left: 4px solid #ec4899; padding-left: 20px; margin-bottom: 10px;">
                    <h3 style="font-size: 0.9rem; font-weight: 800; text-transform: uppercase; color: var(--admin-text-main);">Actions & Aesthetics</h3>
                </div>

                <div class="form-group-admin">
                    <label style="font-size: 0.85rem; font-weight: 700; color: var(--admin-text-main);">Action Headline</label>
                    <input type="text" name="action_headline" value="<?= htmlspecialchars($data['fallback']['action_headline']) ?>" placeholder="Call to action title" style="background: var(--admin-bg); color: var(--admin-text-main);">
                </div>

                <?php
                    $links = [
                        '#' => 'No Link (Disabled)',
                        '/php/Webdev/public/catalog' => 'All Products (Catalog)',
                        '/php/Webdev/public/catalog?category=Running' => 'Running Collection',
                        '/php/Webdev/public/catalog?category=Lifestyle' => 'Lifestyle Collection',
                        '/php/Webdev/public/catalog?category=Training' => 'Training Collection',
                        '/php/Webdev/public/auth/register' => 'Join Us (Register)',
                        '/php/Webdev/public/auth/login' => 'Sign In',
                        '/php/Webdev/public/' => 'Homepage'
                    ];
                ?>

                <div style="background: var(--admin-bg-soft); padding: 20px; border-radius: 16px; border: 1px solid var(--admin-border);">
                    <div class="form-group-admin" style="margin-bottom: 20px;">
                        <label style="font-size: 0.85rem; font-weight: 800; color: var(--admin-text-main); text-transform: uppercase; letter-spacing: 0.05em; display: block; margin-bottom: 15px;">Action Layout</label>
                        <select name="num_buttons" style="background: var(--admin-card); color: var(--admin-text-main); font-weight: 600;">
                            <option value="1" <?= ($data['fallback']['num_buttons'] ?? 2) == 1 ? 'selected' : '' ?>>1 Button (Primary Only)</option>
                            <option value="2" <?= ($data['fallback']['num_buttons'] ?? 2) == 2 ? 'selected' : '' ?>>2 Buttons (Primary & Secondary)</option>
                        </select>
                    </div>

                    <div class="form-group-admin" style="margin-bottom: 15px;">
                        <label style="font-size: 0.8rem; font-weight: 700; color: var(--admin-text-muted);">Button 1 (Primary)</label>
                        <input type="text" name="btn1_text" value="<?= htmlspecialchars($data['fallback']['btn1_text']) ?>" style="margin-bottom: 8px; background: var(--admin-bg); color: var(--admin-text-main);">
                        <select name="btn1_link" style="font-size: 0.85rem; background: var(--admin-bg); color: var(--admin-text-main);">
                            <?php foreach($links as $val => $label): ?>
                                <option value="<?= $val ?>" <?= $data['fallback']['btn1_link'] == $val ? 'selected' : '' ?>><?= $label ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group-admin" style="margin: 0;">
                        <label style="font-size: 0.8rem; font-weight: 700; color: var(--admin-text-muted);">Button 2 (Secondary)</label>
                        <input type="text" name="btn2_text" value="<?= htmlspecialchars($data['fallback']['btn2_text']) ?>" style="margin-bottom: 8px; background: var(--admin-bg); color: var(--admin-text-main);">
                        <select name="btn2_link" style="font-size: 0.85rem; background: var(--admin-bg); color: var(--admin-text-main);">
                            <?php foreach($links as $val => $label): ?>
                                <option value="<?= $val ?>" <?= $data['fallback']['btn2_link'] == $val ? 'selected' : '' ?>><?= $label ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <?php 
                    $current_grad = $data['fallback']['bg_gradient'];
                    preg_match_all('/#[a-fA-F0-9]{3,6}/', $current_grad, $matches);
                    $start_color = $matches[0][0] ?? '#0f172a';
                    $end_color = $matches[0][1] ?? '#334155';
                ?>

                <div class="form-group-admin">
                    <label style="font-size: 0.85rem; font-weight: 700; color: var(--admin-text-main);">Atmosphere (Background Gradient)</label>
                    <div style="background: var(--admin-bg-soft); padding: 15px; border-radius: 16px; border: 1px solid var(--admin-border);">
                        <div style="display: flex; gap: 15px; align-items: center; margin-bottom: 15px;">
                            <div style="flex: 1;">
                                <small style="display: block; font-size: 0.7rem; font-weight: 700; color: var(--admin-text-muted); margin-bottom: 5px; text-transform: uppercase;">Start</small>
                                <input type="color" id="grad_start" value="<?= $start_color ?>" style="width: 100%; height: 35px; border: none; border-radius: 8px; cursor: pointer; background: var(--admin-card); padding: 4px;">
                            </div>
                            <div style="flex: 1;">
                                <small style="display: block; font-size: 0.7rem; font-weight: 700; color: var(--admin-text-muted); margin-bottom: 5px; text-transform: uppercase;">End</small>
                                <input type="color" id="grad_end" value="<?= $end_color ?>" style="width: 100%; height: 35px; border: none; border-radius: 8px; cursor: pointer; background: var(--admin-card); padding: 4px;">
                            </div>
                        </div>
                        <div id="grad_preview" style="height: 60px; border-radius: 12px; border: 1px solid rgba(0,0,0,0.1); background: <?= $current_grad ?>; display: flex; align-items: center; justify-content: center; color: white; font-size: 0.75rem; font-weight: 700; text-shadow: 0 2px 4px rgba(0,0,0,0.2);">
                            Live Preview
                        </div>
                    </div>
                    <input type="hidden" name="bg_gradient" id="bg_gradient_input" value="<?= $current_grad ?>">
                </div>
            </div>
        </div>

        <div style="margin-top: 50px; display: flex; gap: 15px;">
            <button type="submit" class="btn-admin btn-admin-primary" style="flex-grow: 2; padding: 18px; font-size: 1rem; border-radius: 14px; box-shadow: 0 10px 20px rgba(37, 99, 235, 0.2);">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px;"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg>
                Save Campaign Configuration
            </button>
            <a href="/php/Webdev/public/superadmin/hero_settings" class="btn-admin" style="flex-grow: 1; padding: 18px; border-radius: 14px; background: var(--admin-bg-soft); color: var(--admin-text-muted); border: 1px solid var(--admin-border); font-weight: 600;">
                Cancel
            </a>
        </div>
    </form>
</div>

<script>
    const startPicker = document.getElementById('grad_start');
    const endPicker = document.getElementById('grad_end');
    const preview = document.getElementById('grad_preview');
    const hiddenInput = document.getElementById('bg_gradient_input');

    function updateGradient() {
        const gradString = `linear-gradient(135deg, ${startPicker.value} 0%, ${endPicker.value} 100%)`;
        preview.style.background = gradString;
        hiddenInput.value = gradString;
    }

    startPicker.addEventListener('input', updateGradient);
    endPicker.addEventListener('input', updateGradient);
</script>

<style>
.admin-card form {
    --fallback-form-gap: 24px;
    --fallback-section-gap: 32px;
    --fallback-field-space: 10px;
    --fallback-heading-size: 0.95rem;
    --fallback-label-size: 0.85rem;
    --fallback-micro-label-size: 0.75rem;
    --fallback-input-size: 0.95rem;
    --fallback-body-line: 1.5;
    width: 100%;
    max-width: 100%;
    min-width: 0;
    box-sizing: border-box;
    overflow-x: hidden;
}

.admin-card[style*="overflow: hidden"] {
    overflow: visible !important;
}

.admin-card form,
.admin-card form .form-group-admin,
.admin-card form .form-group-admin input,
.admin-card form .form-group-admin select,
.admin-card form .form-group-admin textarea {
    position: relative;
}

.admin-card form .form-group-admin {
    z-index: 1;
}

.admin-card form .form-group-admin:focus-within {
    z-index: 5;
}

.admin-card form .form-group-admin select,
.admin-card form .form-group-admin input,
.admin-card form .form-group-admin textarea {
    z-index: 2;
}

.admin-card form > div[style*="grid-template-columns: 1fr 1fr"] {
    display: grid !important;
    grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)) !important;
    gap: var(--fallback-section-gap) !important;
    align-items: start;
}

.admin-card form > div[style*="grid-template-columns: 1fr 1fr"] > div[style*="display: flex"] {
    min-width: 0;
}

.admin-card form > div[style*="grid-template-columns: 1fr 1fr"] > * {
    min-width: 0;
}

.admin-card form div[style*="grid-template-columns: 1fr 1fr; gap: 20px"] {
    display: grid !important;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)) !important;
    gap: var(--fallback-form-gap) !important;
    align-items: start;
}

.admin-card form div[style*="grid-template-columns: 1fr 1fr; gap: 20px"] > * {
    min-width: 0;
}

.admin-card form div[style*="display: flex; flex-direction: column; gap: 25px;"] {
    min-width: 0;
    gap: var(--fallback-form-gap) !important;
}

.admin-card form div[style*="background: var(--admin-bg-soft); padding: 20px; border-radius: 16px; border: 1px solid var(--admin-border);"],
.admin-card form div[style*="background: var(--admin-bg-soft); padding: 15px; border-radius: 16px; border: 1px solid var(--admin-border);"] {
    min-width: 0;
    max-width: 100%;
    box-sizing: border-box;
}

.admin-card form .form-group-admin,
.admin-card form .form-group-admin input,
.admin-card form .form-group-admin select,
.admin-card form .form-group-admin textarea {
    width: 100%;
    max-width: 100%;
    min-width: 0;
    box-sizing: border-box;
}

.admin-card form .form-group-admin {
    overflow: visible;
    display: flex;
    flex-direction: column;
    gap: var(--fallback-field-space);
}

.admin-card form *,
.admin-card form > * {
    max-width: 100%;
    min-width: 0;
    box-sizing: border-box;
}

.admin-card > div[style*="padding: 35px"] h2 {
    line-height: 1.15;
}

.admin-card > div[style*="padding: 35px"] p {
    line-height: var(--fallback-body-line);
}

.admin-card form h3 {
    font-size: var(--fallback-heading-size) !important;
    font-weight: 800 !important;
    line-height: 1.2;
    margin: 0;
}

.admin-card form .form-group-admin label {
    font-size: var(--fallback-label-size) !important;
    font-weight: 700 !important;
    line-height: 1.35;
    margin: 0 !important;
}

.admin-card form label[style*="text-transform: uppercase"],
.admin-card form small[style*="text-transform: uppercase"] {
    letter-spacing: 0.05em !important;
}

.admin-card form small {
    font-size: var(--fallback-micro-label-size) !important;
    font-weight: 700 !important;
    line-height: 1.3;
}

.admin-card form input,
.admin-card form select,
.admin-card form textarea {
    font-size: var(--fallback-input-size) !important;
    font-weight: 500;
    line-height: var(--fallback-body-line);
}

.admin-card form textarea {
    line-height: 1.6;
}

.admin-card form .btn-admin {
    font-size: 0.95rem !important;
    line-height: 1.2;
}

.admin-card form div[style*="border-left: 4px solid"] {
    margin-bottom: 0 !important;
}

.admin-card form .form-group-admin[style*="margin-bottom: 20px;"],
.admin-card form .form-group-admin[style*="margin-bottom: 15px;"],
.admin-card form .form-group-admin[style*="margin: 0;"] {
    margin-bottom: 0 !important;
}

@media (max-width: 768px) {
    .admin-card form {
        --fallback-form-gap: 20px;
        --fallback-section-gap: 24px;
        --fallback-input-size: 0.92rem;
        padding: 24px !important;
    }

    .admin-card form > div[style*="grid-template-columns: 1fr 1fr"],
    .admin-card form div[style*="grid-template-columns: 1fr 1fr; gap: 20px"] {
        grid-template-columns: 1fr !important;
    }

    .admin-card form div[style*="display: flex; gap: 15px; align-items: center; margin-bottom: 15px;"],
    .admin-card form div[style*="margin-top: 50px; display: flex; gap: 15px;"] {
        display: flex !important;
        flex-direction: column !important;
        align-items: stretch !important;
    }

    .admin-card form div[style*="margin-top: 50px; display: flex; gap: 15px;"] > * {
        width: 100%;
    }

    .admin-card > div[style*="padding: 35px"] {
        padding: 28px !important;
    }

    .admin-card > div[style*="padding: 35px"] h2 {
        font-size: 1.45rem !important;
    }

    .admin-card > div[style*="padding: 35px"] p {
        font-size: 0.9rem !important;
    }
}

@media (max-width: 480px) {
    .admin-card form {
        --fallback-form-gap: 18px;
        --fallback-section-gap: 20px;
        --fallback-field-space: 8px;
        --fallback-heading-size: 0.9rem;
        --fallback-label-size: 0.8rem;
        --fallback-micro-label-size: 0.72rem;
        --fallback-input-size: 0.9rem;
        padding: 20px !important;
    }

    .admin-card > div[style*="padding: 35px"] {
        padding: 24px 20px !important;
    }

    .admin-card > div[style*="padding: 35px"] h2 {
        font-size: 1.3rem !important;
    }

    .admin-card > div[style*="padding: 35px"] p {
        font-size: 0.88rem !important;
        line-height: 1.5;
    }
}
</style>
