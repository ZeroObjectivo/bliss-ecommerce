<!-- Cropper.js Dependencies -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>

<div class="container py-8">
    <!-- Sidebar Overlay for Mobile -->
    <div class="sidebar-overlay" id="sidebar-overlay"></div>

    <!-- Mobile Profile Navigation Trigger -->
    <button class="mobile-profile-nav-trigger" id="mobile-profile-trigger">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>
        <span>Profile Menu</span>
    </button>

    <div class="profile-layout">
        <!-- Sidebar -->
        <aside class="profile-sidebar" id="profile-sidebar">
            <div class="user-avatar-section">
                <div class="avatar-wrapper">
                    <?php if($data['user']['profile_picture']): ?>
                        <img src="/php/Webdev/public/<?= $data['user']['profile_picture'] ?>" alt="Avatar" id="avatar-preview">
                    <?php else: ?>
                        <div class="avatar-placeholder"><?= strtoupper(substr($data['user']['name'], 0, 1)) ?></div>
                    <?php endif; ?>
                    <form action="/php/Webdev/public/profile/update_avatar" method="POST" enctype="multipart/form-data" id="avatar-form">
                        <label for="profile_picture" class="avatar-edit-btn">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"></path><circle cx="12" cy="13" r="4"></circle></svg>
                        </label>
                        <input type="file" name="profile_picture" id="profile_picture" hidden>
                    </form>
                </div>
                <h2 class="user-name"><?= $data['user']['name'] ?></h2>
                <p class="user-email"><?= $data['user']['email'] ?></p>
            </div>
            
            <nav class="profile-nav">
                <a href="/php/Webdev/public/profile" class="active">Account Settings</a>
                <a href="/php/Webdev/public/profile/orders">My Orders</a>
                <a href="/php/Webdev/public/profile/inbox">My Inbox</a>
                <a href="/php/Webdev/public/auth/logout" class="logout-link">Logout</a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="profile-content">
            <?php if(!$data['has_security']): ?>
                <div class="security-warning-banner">
                    <div class="security-warning-icon">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
                    </div>
                    <div class="security-warning-text">
                        <h4>Security Action Required</h4>
                        <p>You haven't set up your security questions. These are required to recover your account if you forget your password. Please set them below.</p>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Mobile-Friendly Profile Picture Section -->
            <section class="profile-section collapsible-section active">
                <div class="collapsible-header">
                    <h3>Profile Picture</h3>
                    <svg class="chevron" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>
                </div>
                <div class="collapsible-body">
                    <div class="profile-picture-edit-card">
                        <div class="avatar-wrapper-main">
                            <?php if($data['user']['profile_picture']): ?>
                                <img src="/php/Webdev/public/<?= $data['user']['profile_picture'] ?>" alt="Avatar" class="main-avatar-preview">
                            <?php else: ?>
                                <div class="avatar-placeholder-main"><?= strtoupper(substr($data['user']['name'], 0, 1)) ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="picture-actions">
                            <p class="picture-tip">Upload a high-quality image to personalize your account. (Max: 5MB)</p>
                            <form action="/php/Webdev/public/profile/update_avatar" method="POST" enctype="multipart/form-data">
                                <label for="profile_picture_main" class="btn btn-secondary btn-sm" style="cursor: pointer;">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right: 8px;"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
                                    Change Picture
                                </label>
                                <input type="file" name="profile_picture" id="profile_picture_main" hidden>
                            </form>
                        </div>
                    </div>
                </div>
            </section>

            <section class="profile-section collapsible-section">
                <div class="collapsible-header">
                    <h3>Personal Information</h3>
                    <svg class="chevron" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>
                </div>
                <div class="collapsible-body">
                    <form action="/php/Webdev/public/profile/update_info" method="POST" class="profile-form">
                        <div class="form-grid">
                            <div class="form-group">
                                <label>Full Name</label>
                                <input type="text" name="name" value="<?= $data['user']['name'] ?>" required>
                            </div>
                            <div class="form-group">
                                <label>Username</label>
                                <input type="text" name="username" value="<?= $data['user']['username'] ?>" placeholder="Choose a username">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </form>
                </div>
            </section>

            <section class="profile-section collapsible-section">
                <div class="collapsible-header">
                    <h3>Saved Addresses</h3>
                    <svg class="chevron" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>
                </div>
                <div class="collapsible-body">
                    <div class="address-grid">
                        <?php foreach($data['addresses'] as $address): ?>
                            <div class="address-card <?= $address['is_default'] ? 'default' : '' ?>">
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                                    <span class="badge" style="background: #f1f5f9; color: #475569;"><?= htmlspecialchars($address['category'] ?? 'Home Address') ?></span>
                                    <?php if($address['is_default']): ?>
                                        <span class="badge">Default</span>
                                    <?php endif; ?>
                                </div>
                                <p><?= $address['street_address'] ?></p>
                                <p><?= $address['city'] ?>, <?= $address['province'] ?></p>
                                <p><?= $address['postal_code'] ?></p>
                                <div class="address-actions" style="margin-top: 15px;">
                                    <?php if(!$address['is_default']): ?>
                                        <a href="/php/Webdev/public/profile/set_default_address/<?= $address['id'] ?>" class="text-sm">Set Default</a>
                                    <?php endif; ?>
                                    <button type="button" class="text-sm" onclick="openEditAddressModal(<?= $address['id'] ?>, '<?= htmlspecialchars($address['street_address'], ENT_QUOTES) ?>', '<?= htmlspecialchars($address['city'], ENT_QUOTES) ?>', '<?= htmlspecialchars($address['province'], ENT_QUOTES) ?>', '<?= htmlspecialchars($address['postal_code'], ENT_QUOTES) ?>', '<?= htmlspecialchars($address['category'] ?? 'Home Address', ENT_QUOTES) ?>')" style="background:none; border:none; padding:0; cursor:pointer; color: var(--primary-color);">Edit</button>
                                    <a href="/php/Webdev/public/profile/delete_address/<?= $address['id'] ?>" class="text-sm text-error" onclick="return confirm('Delete this address?')">Delete</a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                        
                        <button class="add-address-card" onclick="document.getElementById('address-modal').classList.add('active')">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                            <span>Add New Address</span>
                        </button>
                    </div>
                </div>
            </section>

            <section class="profile-section collapsible-section">
                <div class="collapsible-header">
                    <h3>Security Questions</h3>
                    <svg class="chevron" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>
                </div>
                <div class="collapsible-body">
                    <p class="picture-tip" style="margin-bottom: 20px;">Use these questions to verify your identity if you lose access to your account.</p>
                    <form action="/php/Webdev/public/profile/update_security" method="POST" class="profile-form">
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
                        <div style="display: flex; flex-direction: column; gap: 20px;">
                            <?php for($i=1; $i<=3; $i++): ?>
                                <div class="form-group security-question-group">
                                    <label>Question <?= $i ?></label>
                                    <select name="security_question_<?= $i ?>" required>
                                        <option value="" disabled <?= empty($data['user']["security_q$i"]) ? 'selected' : '' ?>>Select a question</option>
                                        <?php foreach($questions as $q): ?>
                                            <option value="<?= htmlspecialchars($q) ?>" <?= ($data['user']["security_q$i"] == $q) ? 'selected' : '' ?>><?= htmlspecialchars($q) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <input type="text" name="security_answer_<?= $i ?>" required value="<?= htmlspecialchars($data['user']["security_a$i"] ?? '') ?>" placeholder="Your answer">
                                </div>
                            <?php endfor; ?>
                        </div>
                        <button type="submit" class="btn btn-primary" style="margin-top: 20px;">Update Security Questions</button>
                    </form>
                </div>
            </section>

            <section class="profile-section collapsible-section">
                <div class="collapsible-header">
                    <h3>Change Password</h3>
                    <svg class="chevron" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>
                </div>
                <div class="collapsible-body">
                    <form action="/php/Webdev/public/profile/update_password" method="POST" class="profile-form">
                        <div class="form-group">
                            <label>Current Password</label>
                            <input type="password" name="current_password" required>
                        </div>
                        <div class="form-grid">
                            <div class="form-group">
                                <label>New Password</label>
                                <input type="password" name="new_password" required 
                                       pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$" 
                                       title="Must be at least 8 characters long and contain at least one uppercase letter, one lowercase letter, and one number."
                                       placeholder="At least 8 characters + complexity">
                                <small style="display: block; margin-top: 5px; color: #64748b; font-size: 0.75rem;">
                                    Min. 8 characters, including uppercase, lowercase, and a number.
                                </small>
                            </div>
                            <div class="form-group">
                                <label>Confirm New Password</label>
                                <input type="password" name="confirm_password" required 
                                       pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$"
                                       placeholder="Repeat new password">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-secondary">Update Password</button>
                    </form>
                </div>
            </section>
        </main>
    </div>
</div>

<!-- Add Address Modal -->
<div id="address-modal" class="modal-overlay">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Add New Address</h3>
            <button onclick="document.getElementById('address-modal').classList.remove('active')">&times;</button>
        </div>
        <form action="/php/Webdev/public/profile/add_address" method="POST" class="modal-form">
            <div class="form-group">
                <label>Address Category</label>
                <select name="category" required style="width: 100%; padding: 0.75rem; border: 1px solid #e2e8f0; border-radius: 0.5rem; outline: none;">
                    <option value="Home Address">Home Address</option>
                    <option value="Business Address">Business Address</option>
                    <option value="Other">Other</option>
                </select>
            </div>
            <div class="form-group">
                <label>Street Address</label>
                <input type="text" name="street_address" required pattern="[a-zA-Z0-9\s\.,#-]+" title="Please enter a valid street address (letters, numbers, and basic punctuation)">
            </div>
            <div class="form-grid">
                <div class="form-group">
                    <label>City</label>
                    <input type="text" name="city" required pattern="[a-zA-Z\s\-]+" oninput="this.value = this.value.replace(/[^a-zA-Z\s\-]/g, '')" title="Letters only">
                </div>
                <div class="form-group">
                    <label>Province</label>
                    <input type="text" name="province" required pattern="[a-zA-Z\s\-]+" oninput="this.value = this.value.replace(/[^a-zA-Z\s\-]/g, '')" title="Letters only">
                </div>
            </div>
            <div class="form-group">
                <label>Postal Code</label>
                <input type="text" name="postal_code" required pattern="\d+" oninput="this.value = this.value.replace(/[^0-9]/g, '')" title="Numbers only">
            </div>
            <button type="submit" class="btn btn-primary w-full">Save Address</button>
        </form>
    </div>
</div>

<!-- Edit Address Modal -->
<div id="edit-address-modal" class="modal-overlay">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Edit Address</h3>
            <button onclick="document.getElementById('edit-address-modal').classList.remove('active')">&times;</button>
        </div>
        <form id="edit-address-form" action="" method="POST" class="modal-form">
            <div class="form-group">
                <label>Address Category</label>
                <select name="category" id="edit_category" required style="width: 100%; padding: 0.75rem; border: 1px solid #e2e8f0; border-radius: 0.5rem; outline: none;">
                    <option value="Home Address">Home Address</option>
                    <option value="Business Address">Business Address</option>
                    <option value="Other">Other</option>
                </select>
            </div>
            <div class="form-group">
                <label>Street Address</label>
                <input type="text" name="street_address" id="edit_street_address" required pattern="[a-zA-Z0-9\s\.,#-]+" title="Please enter a valid street address (letters, numbers, and basic punctuation)">
            </div>
            <div class="form-grid">
                <div class="form-group">
                    <label>City</label>
                    <input type="text" name="city" id="edit_city" required pattern="[a-zA-Z\s\-]+" oninput="this.value = this.value.replace(/[^a-zA-Z\s\-]/g, '')" title="Letters only">
                </div>
                <div class="form-group">
                    <label>Province</label>
                    <input type="text" name="province" id="edit_province" required pattern="[a-zA-Z\s\-]+" oninput="this.value = this.value.replace(/[^a-zA-Z\s\-]/g, '')" title="Letters only">
                </div>
            </div>
            <div class="form-group">
                <label>Postal Code</label>
                <input type="text" name="postal_code" id="edit_postal_code" required pattern="\d+" oninput="this.value = this.value.replace(/[^0-9]/g, '')" title="Numbers only">
            </div>
            <button type="submit" class="btn btn-primary w-full">Update Address</button>
        </form>
    </div>
</div>

<!-- Crop Modal -->
<div id="crop-modal" class="modal-overlay">
    <div class="modal-content glass-premium-modal" style="max-width: 500px;">
        <div class="modal-header">
            <h3>Crop Profile Picture</h3>
            <button onclick="closeCropModal()">&times;</button>
        </div>
        <div style="padding: 20px;">
            <div style="max-height: 400px; overflow: hidden; border-radius: 12px; background: #f8fafc; margin-bottom: 25px;">
                <img id="image-to-crop" style="max-width: 100%; display: block;">
            </div>
            <div style="display: flex; gap: 12px;">
                <button type="button" onclick="closeCropModal()" class="btn btn-secondary" style="flex: 1;">Cancel</button>
                <button type="button" id="apply-crop-btn" class="btn btn-primary" style="flex: 2;">Apply & Save</button>
            </div>
        </div>
    </div>
</div>

<script>
let cropper = null;

function initCropper(file) {
    if (!file.type.startsWith('image/')) {
        window.showToast('Please select a valid image file.', 'error');
        return;
    }

    const reader = new FileReader();
    reader.onload = function(e) {
        const image = document.getElementById('image-to-crop');
        image.src = e.target.result;
        document.getElementById('crop-modal').classList.add('active');
        
        if (cropper) {
            cropper.destroy();
        }
        
        // Wait for modal transition then init cropper
        setTimeout(() => {
            cropper = new Cropper(image, {
                aspectRatio: 1,
                viewMode: 2,
                dragMode: 'move',
                autoCropArea: 1,
                restore: false,
                guides: true,
                center: true,
                highlight: false,
                cropBoxMovable: true,
                cropBoxResizable: true,
                toggleDragModeOnDblclick: false,
            });
        }, 300);
    };
    reader.readAsDataURL(file);
}

function closeCropModal() {
    document.getElementById('crop-modal').classList.remove('active');
    if (cropper) {
        cropper.destroy();
        cropper = null;
    }
}

// Intercept file inputs
document.getElementById('profile_picture')?.addEventListener('change', function(e) {
    if (this.files && this.files[0]) initCropper(this.files[0]);
    this.value = ''; // Reset input so it triggers again on same file
});

document.getElementById('profile_picture_main')?.addEventListener('change', function(e) {
    if (this.files && this.files[0]) initCropper(this.files[0]);
    this.value = ''; // Reset input
});

document.getElementById('apply-crop-btn')?.addEventListener('click', function() {
    if (!cropper) return;
    
    const btn = this;
    const originalText = btn.innerText;
    btn.disabled = true;
    btn.innerText = 'Processing...';
    
    cropper.getCroppedCanvas({
        width: 500,
        height: 500
    }).toBlob((blob) => {
        if (!blob) {
            window.showToast('Error generating image.', 'error');
            return;
        }

        const formData = new FormData();
        formData.append('profile_picture', blob, 'avatar.png');
        
        fetch('/php/Webdev/public/profile/update_avatar_ajax', {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                // Update all previews
                const newSrc = '/php/Webdev/public/' + data.path + '?v=' + new Date().getTime();
                document.querySelectorAll('.main-avatar-preview, #avatar-preview').forEach(img => {
                    img.src = newSrc;
                });
                
                // Handle placeholders
                document.querySelectorAll('.avatar-placeholder-main, .avatar-placeholder').forEach(ph => {
                    const img = document.createElement('img');
                    img.src = newSrc;
                    img.className = ph.className.replace('placeholder', 'preview');
                    ph.replaceWith(img);
                });

                window.showToast('Profile picture updated successfully!');
                closeCropModal();
            } else {
                window.showToast(data.error || 'Failed to update avatar.', 'error');
            }
        })
        .catch(err => {
            console.error(err);
            window.showToast('An unexpected error occurred.', 'error');
        })
        .finally(() => {
            btn.disabled = false;
            btn.innerText = originalText;
        });
    }, 'image/png');
});

function openEditAddressModal(id, street, city, province, postalCode, category) {
    document.getElementById('edit-address-form').action = '/php/Webdev/public/profile/edit_address/' + id;
    document.getElementById('edit_street_address').value = street;
    document.getElementById('edit_city').value = city;
    document.getElementById('edit_province').value = province;
    document.getElementById('edit_postal_code').value = postalCode;
    document.getElementById('edit_category').value = category;
    document.getElementById('edit-address-modal').classList.add('active');
}

// Collapsible Logic
document.addEventListener('DOMContentLoaded', function() {
    const headers = document.querySelectorAll('.collapsible-header');
    headers.forEach(header => {
        header.addEventListener('click', () => {
            const section = header.parentElement;
            const isActive = section.classList.contains('active');
            
            // Close all other sections (optional, remove if you want multiple open)
            // document.querySelectorAll('.collapsible-section').forEach(s => s.classList.remove('active'));
            
            if (isActive) {
                section.classList.remove('active');
            } else {
                section.classList.add('active');
            }
        });
    });

    // Mobile Navigation Toggle
    const mobileTrigger = document.getElementById('mobile-profile-trigger');
    const sidebar = document.getElementById('profile-sidebar');
    const overlay = document.getElementById('sidebar-overlay');

    if (mobileTrigger && sidebar && overlay) {
        mobileTrigger.addEventListener('click', () => {
            sidebar.classList.add('open');
            overlay.classList.add('active');
        });

        overlay.addEventListener('click', () => {
            sidebar.classList.remove('open');
            overlay.classList.remove('active');
        });
    }
});
</script>

<link rel="stylesheet" href="/php/Webdev/public/css/profile.css">
