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
                        <input type="file" name="profile_picture" id="profile_picture" hidden onchange="this.form.submit()">
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
            <!-- Mobile-Friendly Profile Picture Section -->
            <section class="profile-section profile-picture-main-section">
                <h3>Profile Picture</h3>
                <div class="profile-picture-edit-card">
                    <div class="avatar-wrapper-main">
                        <?php if($data['user']['profile_picture']): ?>
                            <img src="/php/Webdev/public/<?= $data['user']['profile_picture'] ?>" alt="Avatar" class="main-avatar-preview">
                        <?php else: ?>
                            <div class="avatar-placeholder-main"><?= strtoupper(substr($data['user']['name'], 0, 1)) ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="picture-actions">
                        <p class="picture-tip">Upload a high-quality image to personalize your account.</p>
                        <form action="/php/Webdev/public/profile/update_avatar" method="POST" enctype="multipart/form-data">
                            <label for="profile_picture_main" class="btn btn-secondary btn-sm" style="cursor: pointer;">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right: 8px;"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
                                Change Picture
                            </label>
                            <input type="file" name="profile_picture" id="profile_picture_main" hidden onchange="this.form.submit()">
                        </form>
                    </div>
                </div>
            </section>

            <section class="profile-section">
                <h3>Personal Information</h3>
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
            </section>

            <section class="profile-section">
                <h3>Saved Addresses</h3>
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
            </section>

            <section class="profile-section">
                <h3>Change Password</h3>
                <form action="/php/Webdev/public/profile/update_password" method="POST" class="profile-form">
                    <div class="form-group">
                        <label>Current Password</label>
                        <input type="password" name="current_password" required>
                    </div>
                    <div class="form-grid">
                        <div class="form-group">
                            <label>New Password</label>
                            <input type="password" name="new_password" required>
                        </div>
                        <div class="form-group">
                            <label>Confirm New Password</label>
                            <input type="password" name="confirm_password" required>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-secondary">Update Password</button>
                </form>
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

<script>
function openEditAddressModal(id, street, city, province, postalCode, category) {
    document.getElementById('edit-address-form').action = '/php/Webdev/public/profile/edit_address/' + id;
    document.getElementById('edit_street_address').value = street;
    document.getElementById('edit_city').value = city;
    document.getElementById('edit_province').value = province;
    document.getElementById('edit_postal_code').value = postalCode;
    document.getElementById('edit_category').value = category;
    document.getElementById('edit-address-modal').classList.add('active');
}
</script>

<link rel="stylesheet" href="/php/Webdev/public/css/profile.css">
