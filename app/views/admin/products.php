<div class="flex-between" style="margin-bottom: var(--spacing-3);">
    <h2 style="font-size: 1.25rem;">Product Inventory</h2>
    <a href="/php/Webdev/public/superadmin/product_add" class="btn-admin btn-admin-primary">Add Product</a>
</div>

<div class="table-container">
    <table class="admin-table">
        <thead>
            <tr>
                <th>Image</th>
                <th>Name</th>
                <th>Category</th>
                <th>Status</th>
                <th>Price (₱)</th>
                <th>Stock</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($data['products'] as $p): ?>
            <tr class="desktop-table-row">
                <td><img src="<?= htmlspecialchars($p['image_main']) ?>" class="table-image" alt="Product"></td>
                <td style="font-weight: 500;"><?= htmlspecialchars($p['name']) ?></td>
                <td>
                    <?php 
                        $allCats = array_map('trim', explode(',', $p['category']));
                        $functionalCats = array_filter($allCats, function($c) {
                            return !in_array($c, ['Featured', 'New Arrival', 'Best Seller']);
                        });
                        foreach($functionalCats as $c):
                    ?>
                        <span style="background: var(--admin-bg-soft); padding: 2px 8px; border-radius: 4px; font-size: 0.75rem; border: 1px solid var(--admin-border); margin-right: 4px;"><?= $c ?></span>
                    <?php endforeach; ?>
                </td>
                <td>
                    <?php if(in_array('Featured', $allCats)): ?>
                        <span class="badge" style="background: #000; color: #fff; margin-bottom: 2px; display: inline-block;">Featured</span>
                    <?php endif; ?>
                    <?php if(in_array('New Arrival', $allCats)): ?>
                        <span class="badge" style="background: var(--admin-accent); color: #fff; margin-bottom: 2px; display: inline-block;">New</span>
                    <?php endif; ?>
                    <?php if(in_array('Best Seller', $allCats)): ?>
                        <span class="badge" style="background: var(--admin-warning); color: #fff; margin-bottom: 2px; display: inline-block;">Best</span>
                    <?php endif; ?>
                </td>
                <td>₱<?= number_format($p['price'], 2) ?></td>
                <td>
                    <?php 
                        $sizes = json_decode($p['sizes'], true) ?: [];
                        $totalStock = array_sum($sizes);
                    ?>
                    <?php if($totalStock < 10): ?>
                        <span style="color: var(--admin-danger); font-weight: bold;"><?= $totalStock ?></span>
                    <?php else: ?>
                        <?= $totalStock ?>
                    <?php endif; ?>
                </td>
                <td>
                    <div style="display:flex; gap: 8px;">
                        <a href="/php/Webdev/public/superadmin/product_edit/<?= $p['id'] ?>" class="btn-admin btn-admin-primary" style="padding: 4px 8px; font-size: 0.8rem;">Edit</a>
                        <a href="/php/Webdev/public/superadmin/product_delete/<?= $p['id'] ?>" onclick="return confirm('Delete this product?');" class="btn-admin btn-admin-danger">Delete</a>
                    </div>
                </td>
            </tr>
            <div class="mobile-card">
                <div class="card-header">
                    <img src="<?= htmlspecialchars($p['image_main']) ?>" class="table-image" alt="Product">
                    <div class="product-info">
                        <h3><?= htmlspecialchars($p['name']) ?></h3>
                        <p class="price">₱<?= number_format($p['price'], 2) ?></p>
                    </div>
                </div>
                <div class="card-body">
                    <div class="card-item">
                        <span class="label">Category:</span>
                        <span>
                            <?php 
                                $allCats = array_map('trim', explode(',', $p['category']));
                                $functionalCats = array_filter($allCats, function($c) {
                                    return !in_array($c, ['Featured', 'New Arrival', 'Best Seller']);
                                });
                                foreach($functionalCats as $c):
                            ?>
                                <span style="background: var(--admin-bg-soft); padding: 2px 8px; border-radius: 4px; font-size: 0.75rem; border: 1px solid var(--admin-border); margin-right: 4px;"><?= $c ?></span>
                            <?php endforeach; ?>
                        </span>
                    </div>
                    <div class="card-item">
                        <span class="label">Status:</span>
                        <span>
                            <?php if(in_array('Featured', $allCats)): ?>
                                <span class="badge" style="background: #000; color: #fff; margin-bottom: 2px; display: inline-block;">Featured</span>
                            <?php endif; ?>
                            <?php if(in_array('New Arrival', $allCats)): ?>
                                <span class="badge" style="background: var(--admin-accent); color: #fff; margin-bottom: 2px; display: inline-block;">New</span>
                            <?php endif; ?>
                            <?php if(in_array('Best Seller', $allCats)): ?>
                                <span class="badge" style="background: var(--admin-warning); color: #fff; margin-bottom: 2px; display: inline-block;">Best</span>
                            <?php endif; ?>
                        </span>
                    </div>
                    <div class="card-item">
                        <span class="label">Stock:</span>
                        <span>
                            <?php 
                                $sizes = json_decode($p['sizes'], true) ?: [];
                                $totalStock = array_sum($sizes);
                            ?>
                            <?php if($totalStock < 10): ?>
                                <span style="color: var(--admin-danger); font-weight: bold;"><?= $totalStock ?></span>
                            <?php else: ?>
                                <?= $totalStock ?>
                            <?php endif; ?>
                        </span>
                    </div>
                </div>
                <div class="card-actions">
                    <a href="/php/Webdev/public/superadmin/product_edit/<?= $p['id'] ?>" class="btn-admin btn-admin-primary">Edit</a>
                    <a href="/php/Webdev/public/superadmin/product_delete/<?= $p['id'] ?>" onclick="return confirm('Delete this product?');" class="btn-admin btn-admin-danger">Delete</a>
                </div>
            </div>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
