<div style="margin-bottom: var(--spacing-4);">
    <h2 style="margin-bottom: 10px;">Homepage Main Showcase</h2>
    <?php if($data['current_featured']): ?>
        <div class="admin-card" style="display:flex; gap:20px; align-items:center;">
            <img src="<?= htmlspecialchars($data['current_featured']['image_main']) ?>" style="width:100px; height:100px; object-fit:cover; border-radius:8px;">
            <div>
                <h3 style="color:var(--admin-text-main); font-size:1.3rem;"><?= htmlspecialchars($data['current_featured']['name']) ?></h3>
                <p style="color:var(--admin-accent);">Currently displaying as the massive Hero block on the frontend.</p>
            </div>
            <form method="POST" action="/php/Webdev/public/superadmin/featured" style="margin-left:auto;">
                <input type="hidden" name="action" value="remove">
                <input type="hidden" name="product_id" value="<?= $data['current_featured']['id'] ?>">
                <button type="submit" class="btn-admin btn-admin-danger" style="padding: 10px 16px; border: 1px solid var(--admin-danger);">Remove Feature</button>
            </form>
        </div>
    <?php else: ?>
        <div class="admin-card">
            <p>No product is currently featured on the homepage. Select one below to make it your Hero!</p>
        </div>
    <?php endif; ?>
</div>

<h2 style="margin-bottom: 10px; font-size:1.1rem; color:var(--admin-text-muted);">Available Products</h2>
<div class="table-container">
    <table class="admin-table">
        <thead>
            <tr>
                <th>Image</th>
                <th>Name</th>
                <th>Category</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($data['products'] as $p): ?>
            <tr>
                <td><img src="<?= htmlspecialchars($p['image_main']) ?>" class="table-image" alt="Product"></td>
                <td style="font-weight: 500;"><?= htmlspecialchars($p['name']) ?></td>
                <td><?= htmlspecialchars($p['category']) ?></td>
                <td>
                    <?php if(isset($data['current_featured']['id']) && $p['id'] == $data['current_featured']['id']): ?>
                        <span style="color: var(--admin-text-muted); font-weight:600;">Active Feature</span>
                    <?php else: ?>
                        <form method="POST" action="/php/Webdev/public/superadmin/featured" style="margin:0;">
                            <input type="hidden" name="action" value="add">
                            <input type="hidden" name="product_id" value="<?= $p['id'] ?>">
                            <button type="submit" class="btn-admin btn-admin-primary" style="padding: 6px 12px; font-size: 0.8rem;">Feature This</button>
                        </form>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
