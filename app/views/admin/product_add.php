<div style="margin-bottom: var(--spacing-3);">
    <a href="/php/Webdev/public/superadmin/products" style="color: var(--admin-text-muted); text-decoration: none; display: inline-flex; align-items: center; gap: 8px; font-weight: 500; transition: color 0.2s;" onmouseover="this.style.color='var(--admin-accent)'" onmouseout="this.style.color='var(--admin-text-muted)'">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
        Back to Inventory
    </a>
</div>

<div class="admin-card" style="max-width: 900px; padding: 0; overflow: hidden; border: none; box-shadow: 0 10px 40px rgba(0,0,0,0.08);">
    <div style="background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%); padding: 30px; color: white;">
        <h2 style="font-size: 1.75rem; font-weight: 800; letter-spacing: -0.02em; margin-bottom: 5px;">Add New Product</h2>
        <p style="opacity: 0.8; font-size: 0.9rem;">Fill in the details to create a new premium listing.</p>
    </div>

    <form action="/php/Webdev/public/superadmin/product_add" method="POST" enctype="multipart/form-data" class="product-form">
        
        <div class="admin-grid-2-1">
            <!-- Left Side: Basic Info -->
            <div style="display: flex; flex-direction: column; gap: 25px;">
                <div class="form-group-admin">
                    <label style="text-transform: uppercase; letter-spacing: 0.05em; font-size: 0.75rem; color: var(--admin-text-muted);">Product Name</label>
                    <input type="text" name="name" required placeholder="e.g. Nike Air Max Phantom" style="font-size: 1.1rem; font-weight: 600; padding: 15px; border-radius: 12px;">
                </div>

                <div class="admin-grid-1-1">
                    <div class="form-group-admin">
                        <label style="text-transform: uppercase; letter-spacing: 0.05em; font-size: 0.75rem; color: var(--admin-text-muted);">Price (₱)</label>
                        <input type="number" step="0.01" name="price" required placeholder="0.00" style="font-size: 1.1rem; font-weight: 700; color: var(--admin-accent); padding: 15px; border-radius: 12px;">
                    </div>
                    <div class="form-group-admin">
                        <label style="text-transform: uppercase; letter-spacing: 0.05em; font-size: 0.75rem; color: var(--admin-text-muted);">Brand</label>
                        <input type="text" name="brand" value="Nike" required style="padding: 15px; border-radius: 12px; font-weight: 600;">
                    </div>
                </div>

                <div class="form-group-admin">
                    <label style="text-transform: uppercase; letter-spacing: 0.05em; font-size: 0.75rem; color: var(--admin-text-muted);">Description</label>
                    <textarea name="description" rows="5" required placeholder="Describe the craftsmanship, materials, and feel..." style="padding: 15px; border-radius: 12px; line-height: 1.6; resize: none;"></textarea>
                </div>
            </div>

            <!-- Right Side: Status & Media -->
            <div style="display: flex; flex-direction: column; gap: 25px;">
                <div class="form-group-admin">
                    <label style="text-transform: uppercase; letter-spacing: 0.05em; font-size: 0.75rem; color: var(--admin-text-muted);">Product Image</label>
                    <div style="position: relative; border-radius: 16px; overflow: hidden; border: 2px dashed var(--admin-border); margin-bottom: 15px; aspect-ratio: 1; background: var(--admin-bg-soft); display: flex; align-items: center; justify-content: center; cursor: pointer;" onclick="document.getElementById('image-upload').click()">
                        <img id="image-preview" src="" style="width: 100%; height: 100%; object-fit: cover; display: none;">
                        <div id="upload-placeholder" style="text-align: center; color: var(--admin-text-muted);">
                            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="margin-bottom: 10px;"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
                            <p style="font-size: 0.85rem; font-weight: 600;">Upload Photo</p>
                        </div>
                        <input id="image-upload" type="file" name="image_file" accept="image/*" required style="display: none;" onchange="const preview = document.getElementById('image-preview'); const placeholder = document.getElementById('upload-placeholder'); preview.src = window.URL.createObjectURL(this.files[0]); preview.style.display = 'block'; placeholder.style.display = 'none';">
                    </div>
                </div>

                <div class="form-group-admin">
                    <label style="text-transform: uppercase; letter-spacing: 0.05em; font-size: 0.75rem; color: var(--admin-text-muted);">Status Highlights</label>
                    <div style="display: flex; flex-direction: column; gap: 10px; background: var(--admin-bg-soft); padding: 15px; border-radius: 12px; border: 1px solid var(--admin-border);">
                        <label style="display: flex; align-items: center; gap: 10px; cursor: pointer; font-size: 0.95rem; font-weight: 500;">
                            <input type="checkbox" name="status[]" value="Featured" style="width: 18px; height: 18px; accent-color: var(--admin-accent);"> Featured Drop
                        </label>
                        <label style="display: flex; align-items: center; gap: 10px; cursor: pointer; font-size: 0.95rem; font-weight: 500;">
                            <input type="checkbox" name="status[]" value="New Arrival" style="width: 18px; height: 18px; accent-color: var(--admin-accent);"> New Arrival
                        </label>
                        <label style="display: flex; align-items: center; gap: 10px; cursor: pointer; font-size: 0.95rem; font-weight: 500;">
                            <input type="checkbox" name="status[]" value="Best Seller" style="width: 18px; height: 18px; accent-color: var(--admin-accent);"> Best Seller
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <hr style="margin: 40px 0; border: none; border-top: 1px solid var(--admin-border);">

        <!-- Inventory & Categories -->
        <div class="admin-grid-2-1">
            <div>
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 10px;">
                    <label style="text-transform: uppercase; letter-spacing: 0.05em; font-size: 0.75rem; color: var(--admin-text-muted); margin: 0;">Initial Inventory (per size)</label>
                    <div style="display: flex; gap: 10px; align-items: center;">
                        <input type="number" id="fill-all-input" placeholder="All" style="width: 60px; padding: 5px 10px; border-radius: 8px; border: 1px solid var(--admin-border); font-size: 0.85rem; font-weight: 600;">
                        <button type="button" onclick="fillAllInventory()" style="background: var(--admin-bg-soft); border: 1px solid var(--admin-border); padding: 5px 12px; border-radius: 8px; font-size: 0.75rem; font-weight: 700; cursor: pointer; color: var(--admin-text-main); transition: all 0.2s;" onmouseover="this.style.background='#e2e8f0'" onmouseout="this.style.background='var(--admin-bg-soft)'">Fill All</button>
                    </div>
                </div>
                <div class="admin-grid-3">
                    <?php for($i=7; $i<=12; $i++): ?>
                        <div style="background: var(--admin-bg-soft); padding: 15px; border-radius: 12px; border: 1px solid var(--admin-border); display: flex; align-items: center; justify-content: space-between;">
                            <span style="font-weight: 700; color: var(--admin-text-main);">US <?= $i ?></span>
                            <input type="number" name="size_<?= $i ?>" class="size-inventory-input" value="10" min="0" required style="width: 60px; text-align: center; border: none; background: white; padding: 8px; border-radius: 8px; font-weight: 600; box-shadow: 0 2px 4px rgba(0,0,0,0.02);">
                        </div>
                    <?php endfor; ?>
                </div>
            </div>

            <script>
            function fillAllInventory() {
                const val = document.getElementById('fill-all-input').value;
                if (val === '') return;
                document.querySelectorAll('.size-inventory-input').forEach(input => {
                    input.value = val;
                });
            }
            </script>

            <div>
                <label style="text-transform: uppercase; letter-spacing: 0.05em; font-size: 0.75rem; color: var(--admin-text-muted); display: block; margin-bottom: 20px;">Categories</label>
                <select name="categories[]" multiple style="width: 100%; height: 180px; padding: 10px; border-radius: 12px; border: 1px solid var(--admin-border); font-family: inherit; font-size: 0.95rem; font-weight: 500;">
                    <?php 
                        $cats = ['Running', 'Lifestyle', 'Training', 'Basketball', 'Football', 'Outdoor', 'Limited Edition'];
                        foreach($cats as $cat):
                    ?>
                    <option value="<?= $cat ?>" style="padding: 10px; border-radius: 8px; margin-bottom: 2px;">
                        <?= $cat ?>
                    </option>
                    <?php endforeach; ?>
                </select>
                <p style="margin-top: 10px; font-size: 0.8rem; color: var(--admin-text-muted);">Hold Ctrl (or Cmd) to select multiple.</p>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-admin btn-admin-primary" style="flex-grow: 2; padding: 18px; font-size: 1rem; border-radius: 14px; box-shadow: 0 10px 20px rgba(37, 99, 235, 0.2);">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px;"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                Publish Product Listing
            </button>
            <a href="/php/Webdev/public/superadmin/products" class="btn-admin" style="flex-grow: 1; padding: 18px; border-radius: 14px; background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0; font-weight: 600;">
                Discard
            </a>
        </div>
    </form>
</div>

<style>
.product-form { padding: 40px; }
.form-actions { margin-top: 50px; display: flex; gap: 15px; }
@media (max-width: 600px) {
    .product-form { padding: 20px; }
    .form-actions { flex-direction: column; }
}
</style>
