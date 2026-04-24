<div class="product-card glass-card <?= array_sum(json_decode($product['sizes'], true) ?: []) == 0 ? 'is-out-of-stock' : '' ?>" data-total-stock="<?= array_sum(json_decode($product['sizes'], true) ?: []) ?>">
    <div class="product-image-container">
        <?php 
            $totalStock = array_sum(json_decode($product['sizes'], true) ?: []);
            $allCats = array_map('trim', explode(',', $product['category']));
            $isNew = in_array('New Arrival', $allCats);
            $isBest = in_array('Best Seller', $allCats);
            $isFeatured = isset($product['is_featured']) && $product['is_featured'] == 1;
        ?>
        
        <?php if($totalStock == 0): ?>
            <span class="product-badge badge-out-of-stock">Out of Stock</span>
        <?php elseif($isFeatured): ?>
            <span class="product-badge badge-featured">Featured</span>
        <?php elseif($isNew): ?>
            <span class="product-badge badge-new">New Arrival</span>
        <?php elseif($isBest): ?>
            <span class="product-badge badge-best">Best Seller</span>
        <?php endif; ?>

        <a href="/php/Webdev/public/product/detail/<?= $product['id'] ?>">
            <img src="<?= htmlspecialchars($product['image_main']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
        </a>
    </div>
    <div class="product-info">
        <a href="/php/Webdev/public/product/detail/<?= $product['id'] ?>">
            <h3><?= htmlspecialchars($product['name']) ?></h3>
        </a>
        <p class="category">
            <?php 
                // Display functional categories only, hide status highlights
                $functionalCats = array_filter($allCats, function($c) {
                    return !in_array($c, ['New Arrival', 'Best Seller']);
                });
                echo htmlspecialchars(implode(', ', $functionalCats));
            ?>
        </p>
        <div class="product-footer">
            <span class="price">₱<?= number_format($product['price'], 2) ?></span>
            <div class="card-actions">
                <?php $isFavorite = in_array($product['id'], $_SESSION['favorites_list'] ?? []); ?>
                <form action="/php/Webdev/public/favorites/toggle" method="POST" style="margin:0;">
                    <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                    <button type="submit" class="icon-btn <?= $isFavorite ? 'active' : '' ?>" title="<?= $isFavorite ? 'Remove from Favorites' : 'Add to Favorites' ?>">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="<?= $isFavorite ? 'currentColor' : 'none' ?>" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
                    </button>
                </form>
                <button type="button" 
                        class="icon-btn quick-add-btn" 
                        title="Add to Cart"
                        data-id="<?= $product['id'] ?>"
                        data-name="<?= htmlspecialchars($product['name']) ?>"
                        data-price="<?= $product['price'] ?>"
                        data-image="<?= htmlspecialchars($product['image_main']) ?>"
                        data-sizes='<?= $product['sizes'] ?>'>
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
                </button>
            </div>
        </div>
    </div>
</div>
