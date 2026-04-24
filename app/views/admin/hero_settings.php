<!-- Hero Settings Dashboard -->
<div class="hero-settings-container">
    
    <!-- Primary Showcase Section -->
    <section class="settings-section">
        <div class="section-header">
            <h2 class="section-title">Primary Showcase</h2>
            <p class="section-desc">Choose a specific product to feature as the primary Hero on the homepage.</p>
        </div>

        <?php if($data['current_featured']): ?>
            <div class="hero-card active-card">
                <div class="card-header" onclick="toggleCard(this)">
                    <div class="card-main-info">
                        <img src="<?= htmlspecialchars($data['current_featured']['image_main']) ?>" class="card-thumb">
                        <div>
                            <h3><?= htmlspecialchars($data['current_featured']['name']) ?></h3>
                            <span class="active-badge">Active Product Hero</span>
                        </div>
                    </div>
                    <div class="card-actions-fixed">
                        <form method="POST" action="/php/Webdev/public/superadmin/hero_settings">
                            <input type="hidden" name="action" value="remove">
                            <input type="hidden" name="product_id" value="<?= $data['current_featured']['id'] ?>">
                            <button type="submit" class="btn-icon-danger" title="Remove Hero">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6L6 18M6 6l12 12"></path></svg>
                            </button>
                        </form>
                        <div class="chevron">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"></polyline></svg>
                        </div>
                    </div>
                </div>
                
                <div class="card-content collapsible">
                    <div class="visual-customizer">
                        <form method="POST" action="/php/Webdev/public/superadmin/hero_settings">
                            <input type="hidden" name="action" value="update_gradient">
                            <input type="hidden" name="product_id" value="<?= $data['current_featured']['id'] ?>">
                            
                            <h4>Visual Customization</h4>
                            <?php 
                                $current_grad = $data['current_featured']['bg_gradient'];
                                preg_match_all('/#[a-fA-F0-0]{3,6}/', $current_grad, $matches);
                                $start_color = $matches[0][0] ?? '#000000';
                                $end_color = $matches[0][1] ?? '#1e293b';
                            ?>
                            <div class="gradient-tool">
                                <div class="color-inputs">
                                    <div class="color-picker-wrapper">
                                        <label>Start</label>
                                        <input type="color" id="hero_grad_start" value="<?= $start_color ?>">
                                    </div>
                                    <div class="color-picker-wrapper">
                                        <label>End</label>
                                        <input type="color" id="hero_grad_end" value="<?= $end_color ?>">
                                    </div>
                                </div>
                                <div class="gradient-preview-container">
                                    <label>Live Preview</label>
                                    <div id="hero_grad_preview" style="background: <?= $current_grad ?>;"></div>
                                </div>
                                <input type="hidden" name="bg_gradient" id="hero_bg_gradient_input" value="<?= $current_grad ?>">
                                <button type="submit" class="btn-admin btn-admin-primary">Save Visuals</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="empty-hero-card">
                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path></svg>
                <p>No product is currently featured. The Fallback Campaign will be displayed.</p>
            </div>
        <?php endif; ?>
    </section>

    <!-- Fallback Campaigns Section -->
    <section class="settings-section">
        <div class="section-header-flex">
            <div>
                <h2 class="section-title">Fallback Campaigns</h2>
                <p class="section-desc">Themes displayed when no specific product is featured.</p>
            </div>
            <a href="/php/Webdev/public/superadmin/fallback_add" class="btn-admin btn-admin-primary">Create New Campaign</a>
        </div>

        <div class="fallback-grid">
            <?php foreach($data['fallbacks'] as $f): ?>
                <div class="hero-card <?= $f['is_active'] ? 'active-fallback' : '' ?>">
                    <div class="card-header" onclick="toggleCard(this)">
                        <div class="card-main-info">
                            <div class="gradient-thumb" style="background: <?= $f['bg_gradient'] ?>;"></div>
                            <div>
                                <h3><?= htmlspecialchars($f['campaign_name']) ?></h3>
                                <?php if($f['is_active']): ?>
                                    <span class="active-badge-green">Active Theme</span>
                                <?php else: ?>
                                    <span class="inactive-badge">Draft</span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="card-actions-fixed">
                            <?php if(!$f['is_active']): ?>
                                <a href="/php/Webdev/public/superadmin/fallback_activate/<?= $f['id'] ?>" class="btn-icon-success" title="Activate">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                </a>
                            <?php endif; ?>
                            <a href="/php/Webdev/public/superadmin/fallback_edit/<?= $f['id'] ?>" class="btn-icon" title="Edit">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                            </a>
                            <a href="/php/Webdev/public/superadmin/fallback_delete/<?= $f['id'] ?>" onclick="return confirm('Delete this campaign?');" class="btn-icon-danger" title="Delete">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18m-2 0v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                            </a>
                            <div class="chevron">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"></polyline></svg>
                            </div>
                        </div>
                    </div>
                    <div class="card-content collapsible">
                        <div class="campaign-details">
                            <div class="detail-row">
                                <label>Title</label>
                                <span><?= htmlspecialchars($f['hero_title']) ?></span>
                            </div>
                            <div class="detail-row">
                                <label>Subtitle</label>
                                <span><?= htmlspecialchars($f['hero_subtitle']) ?></span>
                            </div>
                            <div class="detail-row">
                                <label>Badge</label>
                                <span class="badge-mini"><?= htmlspecialchars($f['badge_text']) ?></span>
                            </div>
                            <div class="detail-row">
                                <label>Gradient</label>
                                <code class="grad-code"><?= $f['bg_gradient'] ?></code>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Product Selector Section -->
    <section class="settings-section">
        <div class="hero-card selector-card">
            <div class="card-header" onclick="toggleCard(this)">
                <div class="card-main-info">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
                    <div>
                        <h3>Set Product as Hero</h3>
                        <span class="inactive-badge">Click to expand product list</span>
                    </div>
                </div>
                <div class="card-actions-fixed">
                    <div class="chevron">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"></polyline></svg>
                    </div>
                </div>
            </div>
            <div class="card-content collapsible">
                <div class="table-container no-border">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Image</th>
                                <th>Name</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($data['products'] as $p): ?>
                            <tr>
                                <td><img src="<?= htmlspecialchars($p['image_main']) ?>" class="table-image"></td>
                                <td style="font-weight: 500;"><?= htmlspecialchars($p['name']) ?></td>
                                <td>
                                    <?php if(isset($data['current_featured']['id']) && $p['id'] == $data['current_featured']['id']): ?>
                                        <span class="active-badge">CURRENT</span>
                                    <?php else: ?>
                                        <form method="POST" action="/php/Webdev/public/superadmin/hero_settings" style="margin:0;">
                                            <input type="hidden" name="action" value="add">
                                            <input type="hidden" name="product_id" value="<?= $p['id'] ?>">
                                            <button type="submit" class="btn-admin btn-admin-primary btn-sm">Make Hero</button>
                                        </form>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

    <!-- Announcement Bar Settings -->
    <section class="settings-section">
        <div class="section-header-admin">
            <h2 class="section-title">Announcement Bar</h2>
            <p class="section-desc">Manage the scrolling text messages displayed at the very top of the store.</p>
        </div>

        <div class="hero-card selector-card">
            <div class="card-header" onclick="toggleCard(this)">
                <div class="card-main-info">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path><path d="M7 15v4a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-4"></path><path d="M3 5v14a2 2 0 0 0 2 2h2"></path></svg>
                    <div>
                        <h3>Manage Announcements</h3>
                        <span class="inactive-badge">Click to expand messages</span>
                    </div>
                </div>
                <div class="card-actions-fixed">
                    <div class="chevron">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"></polyline></svg>
                    </div>
                </div>
            </div>
            <div class="card-content collapsible">
                <div style="padding: 24px;">
                    
                    <div style="display: flex; gap: 24px; margin-bottom: 24px; padding-bottom: 24px; border-bottom: 1px solid var(--admin-border); align-items: flex-end; flex-wrap: wrap;">
                        <form method="POST" action="/php/Webdev/public/superadmin/hero_settings" style="display: flex; gap: 15px; align-items: flex-end;">
                            <input type="hidden" name="action" value="toggle_announcement_bar">
                            <div class="color-picker-wrapper" style="align-items: flex-start;">
                                <label>Global Visibility</label>
                                <div style="height: 36px; display: flex; align-items: center;">
                                    <?php if($data['announcement_bar_enabled'] === '1'): ?>
                                        <span class="active-badge" style="background: var(--admin-success); color: #fff; padding: 6px 12px; border-radius: 6px; font-size: 0.75rem;">Currently Enabled</span>
                                    <?php else: ?>
                                        <span class="inactive-badge" style="background: var(--admin-danger); color: #fff; padding: 6px 12px; border-radius: 6px; font-size: 0.75rem;">Currently Disabled</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <button type="submit" class="btn-admin" style="padding: 8px 16px; border-radius: 8px; border: 1px solid var(--admin-border); background: var(--admin-card); color: var(--admin-text-main); font-weight: 600;">
                                <?= $data['announcement_bar_enabled'] === '1' ? 'Disable Bar' : 'Enable Bar' ?>
                            </button>
                        </form>

                        <div style="width: 1px; background: var(--admin-border); height: 50px;"></div>

                        <form method="POST" action="/php/Webdev/public/superadmin/hero_settings" style="display: flex; gap: 15px; align-items: flex-end;">
                            <input type="hidden" name="action" value="update_announcement_color">
                            <div class="color-picker-wrapper" style="align-items: flex-start;">
                                <label>Bar Background Color</label>
                                <input type="color" name="announcement_bg_color" value="<?= htmlspecialchars($data['announcement_bg_color']) ?>" style="width: 100px;">
                            </div>
                            <button type="submit" class="btn-admin btn-admin-primary" style="padding: 8px 16px; border-radius: 8px;">Save Color</button>
                        </form>
                    </div>

                    <form method="POST" action="/php/Webdev/public/superadmin/hero_settings" style="display: flex; gap: 15px; margin-bottom: 24px;">
                        <input type="hidden" name="action" value="add_announcement">
                        <input type="text" name="message" required placeholder="E.g. ✨ Free Delivery on orders over ₱8,500" style="flex-grow: 1; padding: 12px 16px; border: 2px solid var(--admin-border); border-radius: 12px; font-size: 0.95rem; background: var(--admin-bg); color: var(--admin-text-main); outline: none; transition: border-color 0.2s;" onfocus="this.style.borderColor='var(--admin-accent)'" onblur="this.style.borderColor='var(--admin-border)'">
                        <button type="submit" class="btn-admin btn-admin-primary" style="padding: 12px 24px; border-radius: 12px; white-space: nowrap;">Add Message</button>
                    </form>

                    <div class="table-container no-border" style="box-shadow: none;">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Scrolling Message</th>
                                <th style="width: 120px; text-align: center;">Status</th>
                                <th style="width: 150px; text-align: right;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(empty($data['announcements'])): ?>
                                <tr>
                                    <td colspan="3" style="text-align: center; color: var(--admin-text-muted); padding: 30px;">No announcements configured. The bar will be hidden.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach($data['announcements'] as $ann): ?>
                                    <tr>
                                        <td style="font-weight: 500; color: <?= $ann['is_active'] ? 'var(--admin-text-main)' : 'var(--admin-text-muted)' ?>;">
                                            <?= htmlspecialchars($ann['message']) ?>
                                        </td>
                                        <td style="text-align: center;">
                                            <form method="POST" action="/php/Webdev/public/superadmin/hero_settings" style="margin: 0; display: inline-block;">
                                                <input type="hidden" name="action" value="toggle_announcement">
                                                <input type="hidden" name="id" value="<?= $ann['id'] ?>">
                                                <input type="hidden" name="current_status" value="<?= $ann['is_active'] ?>">
                                                <button type="submit" style="background: none; border: none; cursor: pointer; padding: 0;">
                                                    <?php if($ann['is_active']): ?>
                                                        <span class="active-badge" style="background: var(--admin-success); color: #fff; padding: 4px 10px; border-radius: 6px; cursor: pointer;" title="Click to deactivate">Active</span>
                                                    <?php else: ?>
                                                        <span class="inactive-badge" style="background: var(--admin-bg-soft); color: var(--admin-text-muted); padding: 4px 10px; border-radius: 6px; cursor: pointer;" title="Click to activate">Hidden</span>
                                                    <?php endif; ?>
                                                </button>
                                            </form>
                                        </td>
                                        <td style="text-align: right;">
                                            <form method="POST" action="/php/Webdev/public/superadmin/hero_settings" style="margin: 0; display: inline-block;">
                                                <input type="hidden" name="action" value="delete_announcement">
                                                <input type="hidden" name="id" value="<?= $ann['id'] ?>">
                                                <button type="submit" class="btn-icon-danger" title="Delete Message" onclick="return confirm('Are you sure you want to delete this message?')">
                                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>

<style>
.hero-settings-container { display: flex; flex-direction: column; gap: 40px; }
.settings-section { display: flex; flex-direction: column; gap: 20px; }

.section-header { margin-bottom: 10px; }
.section-header-flex { display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; }
.section-title { font-size: 1.25rem; color: var(--admin-text-main); font-weight: 700; }
.section-desc { color: var(--admin-text-muted); font-size: 0.85rem; }

/* Hero Card Styles */
.hero-card {
    background: var(--admin-card);
    border: 1px solid var(--admin-border);
    border-radius: 12px;
    overflow: hidden;
    transition: all 0.3s ease;
    box-shadow: var(--shadow-sm);
}
.hero-card:hover { border-color: var(--admin-text-muted); box-shadow: var(--shadow-md); }
.active-card { border-left: 4px solid var(--admin-accent); }
.active-fallback { border-left: 4px solid var(--admin-success); }

.card-header {
    padding: 16px 24px;
    cursor: pointer;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.card-main-info { display: flex; gap: 20px; align-items: center; }
.card-thumb { width: 50px; height: 50px; object-fit: cover; border-radius: 8px; border: 1px solid var(--admin-border); }
.gradient-thumb { width: 50px; height: 50px; border-radius: 8px; border: 1px solid var(--admin-border); }

.card-main-info h3 { font-size: 1rem; color: var(--admin-text-main); margin-bottom: 2px; font-weight: 600; }
.active-badge { font-size: 0.65rem; font-weight: 700; color: var(--admin-accent); text-transform: uppercase; letter-spacing: 0.05em; }
.active-badge-green { font-size: 0.65rem; font-weight: 700; color: var(--admin-success); text-transform: uppercase; letter-spacing: 0.05em; }
.inactive-badge { font-size: 0.65rem; font-weight: 600; color: var(--admin-text-muted); text-transform: uppercase; }

.card-actions-fixed { display: flex; gap: 12px; align-items: center; }

/* Collapsible Content */
.collapsible {
    max-height: 0;
    overflow: hidden;
    transition: all 0.3s ease-in-out;
    padding: 0 24px;
}
.hero-card.expanded .collapsible {
    max-height: 1000px;
    padding: 24px;
    border-top: 1px solid var(--admin-border);
}
.hero-card.expanded .chevron { transform: rotate(180deg); }
.chevron { transition: transform 0.3s ease; color: var(--admin-text-muted); }

/* Customizer & Details */
.visual-customizer h4 { font-size: 0.85rem; margin-bottom: 15px; color: var(--admin-text-main); font-weight: 600; text-transform: uppercase; }
.gradient-tool { display: flex; gap: 24px; align-items: flex-end; }
.color-picker-wrapper { display: flex; flex-direction: column; gap: 6px; }
.color-picker-wrapper label { font-size: 0.75rem; color: var(--admin-text-muted); font-weight: 500; }
.color-picker-wrapper input { width: 50px; height: 36px; border: 1px solid var(--admin-border); border-radius: 6px; cursor: pointer; background: var(--admin-bg); padding: 2px; }

.gradient-preview-container { flex-grow: 1; display: flex; flex-direction: column; gap: 6px; }
.gradient-preview-container label { font-size: 0.75rem; color: var(--admin-text-muted); font-weight: 500; }
#hero_grad_preview { height: 36px; border-radius: 8px; border: 1px solid var(--admin-border); }

.campaign-details { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
.detail-row { display: flex; flex-direction: column; gap: 4px; }
.detail-row label { font-size: 0.7rem; color: var(--admin-text-muted); font-weight: 700; text-transform: uppercase; }
.detail-row span { font-size: 0.9rem; color: var(--admin-text-main); font-weight: 500; }
.badge-mini { background: var(--admin-accent-soft); color: var(--admin-accent); padding: 2px 8px; border-radius: 4px; display: inline-block; width: fit-content; font-size: 0.75rem; font-weight: 600; }
.grad-code { font-family: 'JetBrains Mono', monospace; font-size: 0.75rem; background: var(--admin-bg); padding: 4px 8px; border-radius: 6px; color: var(--admin-text-main); border: 1px solid var(--admin-border); }

/* Buttons */
.btn-icon, .btn-icon-danger, .btn-icon-success {
    background: var(--admin-card);
    border: 1px solid var(--admin-border);
    width: 32px;
    height: 32px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    color: var(--admin-text-muted);
    transition: all 0.2s;
}
.btn-icon:hover { background: var(--admin-bg-soft); color: var(--admin-text-main); }
.btn-icon-danger:hover { background: rgba(239, 68, 68, 0.1); color: var(--admin-danger); border-color: var(--admin-danger); }
.btn-icon-success:hover { background: rgba(16, 185, 129, 0.1); color: var(--admin-success); border-color: var(--admin-success); }

.empty-hero-card {
    padding: 40px;
    text-align: center;
    background: var(--admin-card);
    border: 2px dashed var(--admin-border);
    border-radius: 12px;
    color: var(--admin-text-muted);
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 15px;
}

.no-border { border: none !important; }
.btn-sm { padding: 4px 10px; font-size: 0.75rem; }
</style>

<script>
function toggleCard(header) {
    const card = header.closest('.hero-card');
    card.classList.toggle('expanded');
}

// Live Gradient Preview for Primary Hero
const heroStart = document.getElementById('hero_grad_start');
const heroEnd = document.getElementById('hero_grad_end');
if(heroStart && heroEnd) {
    const heroPreview = document.getElementById('hero_grad_preview');
    const heroInput = document.getElementById('hero_bg_gradient_input');

    function updateHeroGradient() {
        const gradString = `linear-gradient(135deg, ${heroStart.value} 0%, ${heroEnd.value} 100%)`;
        heroPreview.style.background = gradString;
        heroInput.value = gradString;
    }

    heroStart.addEventListener('input', updateHeroGradient);
    heroEnd.addEventListener('input', updateHeroGradient);
}
</script>
