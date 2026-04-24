<?php if(!empty($data['featured'])): ?>
    <section class="apple-sticky-container" style="background: <?= $data['featured']['bg_gradient'] ?? '#000' ?>;">
        <!-- Scroll Navigation Dots -->
        <div class="scroll-progress-nav">
            <div class="progress-dot active"></div>
            <div class="progress-dot"></div>
            <div class="progress-dot"></div>
        </div>

        <!-- The Sticky Product Visual -->
        <div class="sticky-visual">
            <div class="sticky-image-container">
                <div class="product-glow" id="product-glow"></div>
                <div class="huge-bg-text" id="huge-bg-text">BLISS</div>
                <img src="<?= htmlspecialchars($data['featured']['image_main']) ?>" alt="<?= htmlspecialchars($data['featured']['name']) ?>" id="sticky-product-img">
                <div class="visual-overlay"></div>
            </div>
        </div>

        <!-- The Scrolling Text Content -->
        <div class="scrolling-text-content">
            <!-- Block 1: Introduction -->
            <div class="scrolling-text-block" data-step="1">
                <div class="reveal-on-scroll glass-panel">
                    
                    <span class="hero-badge">Featured Drop</span>
                    <h1 class="hero-title"><?= htmlspecialchars($data['featured']['name']) ?></h1>
                    <p class="hero-subtitle">The future of performance and style. Reimagined for the bold.</p>
                </div>
            </div>

            <!-- Block 2: Details -->
            <div class="scrolling-text-block" data-step="2">
                <div class="reveal-on-scroll glass-panel">
                    <span class="section-tagline"><?= htmlspecialchars($data['featured']['brand']) ?> Engineering</span>
                    <h2 class="section-description"><?= htmlspecialchars($data['featured']['description']) ?></h2>
                    <span class="category-pill"><?= htmlspecialchars($data['featured']['category']) ?> Elite</span>
                </div>
            </div>

            <div class="scrolling-text-block" data-step="3">
                <div class="reveal-on-scroll glass-panel">
                    <span class="section-tagline">Secure Your Pair</span>
                    <h2 class="price-display" style="margin-bottom: 15px;">₱<?= number_format($data['featured']['price'], 2) ?></h2>
                    
                    <?php 
                        $sizes = json_decode($data['featured']['sizes'], true) ?: [];
                        $totalStock = array_sum($sizes);
                    ?>

                    <?php if($totalStock > 0 && $totalStock <= 10): ?>
                        <p style="color: #fca5a5; font-weight: 600; margin-bottom: var(--spacing-4); font-size: 1.1rem;">🔥 Only <?= $totalStock ?> left in stock - Order soon!</p>
                    <?php elseif($totalStock > 10): ?>
                        <p style="color: #6ee7b7; font-weight: 600; margin-bottom: var(--spacing-4); font-size: 1.1rem;">✨ In Stock & Ready to Ship</p>
                    <?php else: ?>
                        <p style="color: #94a3b8; font-weight: 600; margin-bottom: var(--spacing-4); font-size: 1.1rem;">Sold Out</p>
                    <?php endif; ?>

                    <div class="hero-actions">
                        <?php if($totalStock > 0): ?>
                            <a href="/php/Webdev/public/product/detail/<?= $data['featured']['id'] ?>" class="btn btn-primary btn-large">Buy Now</a>
                        <?php else: ?>
                            <a href="#" class="btn btn-primary btn-large" style="opacity: 0.5; pointer-events: none;">Out of Stock</a>
                        <?php endif; ?>
                        <a href="/php/Webdev/public/product/detail/<?= $data['featured']['id'] ?>" class="btn btn-secondary btn-large">Learn More</a>
                    </div>
                    
                    <div style="margin-top: var(--spacing-4); display: flex; gap: 20px; justify-content: center; font-size: 0.85rem; color: rgba(255,255,255,0.6); font-weight: 500; text-transform: uppercase; letter-spacing: 0.05em;">
                        <span style="display: flex; align-items: center; gap: 6px;">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg> 
                            Fast Delivery
                        </span>
                        <span style="display: flex; align-items: center; gap: 6px;">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg> 
                            Secure Checkout
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php else: ?>
    <!-- Static Fallback if no product is featured -->
    <section class="apple-sticky-container">
        <?php if(!empty($data['fallback'])): ?>
            <div class="scroll-progress-nav">
                <div class="progress-dot active"></div>
                <div class="progress-dot"></div>
                <div class="progress-dot"></div>
            </div>
            <div class="sticky-visual" style="background: <?= $data['fallback']['bg_gradient'] ?>;">
                <div class="sticky-image-container">
                    <!-- No product image for fallback, just the immersive gradient -->
                    <div class="product-glow" id="product-glow" style="background: radial-gradient(circle at center, rgba(255,255,255,0.2) 0%, transparent 70%);"></div>
                    <div class="huge-bg-text" id="huge-bg-text">BLISS</div>
                    <div class="visual-overlay" style="background: radial-gradient(circle at center, transparent 0%, rgba(0,0,0,0.4) 100%);"></div>
                </div>
            </div>
            <div class="scrolling-text-content">
                <!-- Step 1: Introduction -->
                <div class="scrolling-text-block" data-step="1">
                    <div class="reveal-on-scroll glass-panel">
                        
                        <?php if(!empty($data['fallback']['badge_text'])): ?>
                            <span class="hero-badge"><?= htmlspecialchars($data['fallback']['badge_text']) ?></span>
                        <?php endif; ?>
                        <h1 class="hero-title"><?= htmlspecialchars($data['fallback']['hero_title']) ?></h1>
                        <p class="hero-subtitle"><?= htmlspecialchars($data['fallback']['hero_subtitle']) ?></p>
                    </div>
                </div>
                <!-- Step 2: Details -->
                <div class="scrolling-text-block" data-step="2">
                    <div class="reveal-on-scroll glass-panel">
                        <span class="section-tagline"><?= htmlspecialchars($data['fallback']['tagline']) ?></span>
                        <h2 class="section-description"><?= htmlspecialchars($data['fallback']['description']) ?></h2>
                        <span class="category-pill"><?= htmlspecialchars($data['fallback']['category_pill']) ?></span>
                    </div>
                </div>

                <!-- Step 3: Action -->
                <div class="scrolling-text-block" data-step="3">
                    <div class="reveal-on-scroll glass-panel">
                        <h2 class="price-display" style="font-size: clamp(3rem, 6vw, 5rem);"><?= htmlspecialchars($data['fallback']['action_headline']) ?></h2>
                        <div class="hero-actions">
                            <a href="<?= htmlspecialchars($data['fallback']['btn1_link']) ?>" class="btn btn-primary btn-large"><?= htmlspecialchars($data['fallback']['btn1_text']) ?></a>
                            <?php if(($data['fallback']['num_buttons'] ?? 2) == 2): ?>
                                <a href="<?= htmlspecialchars($data['fallback']['btn2_link']) ?>" class="btn btn-secondary btn-large"><?= htmlspecialchars($data['fallback']['btn2_text']) ?></a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <!-- Ultra-minimal fallback if even the database fallback is missing -->
            <div class="sticky-visual" style="background: linear-gradient(135deg, #0f172a 0%, #334155 100%);"></div>
            <div class="scrolling-text-content">
                <div class="scrolling-text-block">
                    <div class="reveal-on-scroll glass-panel">
                        <h1 class="hero-title">Welcome to BLISS</h1>
                        <a href="/php/Webdev/public/catalog" class="btn btn-primary btn-large">Shop Collection</a>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </section>
<?php endif; ?>


<?php if(!empty($data['products'])): ?>
<section class="container featured-section">
    <div class="section-header">
        <h2>Featured Drops</h2>
        <a href="/php/Webdev/public/catalog" class="view-all">View All</a>
    </div>
    <div class="product-grid">
        <?php foreach($data['products'] as $product): ?>
            <?php include '../app/views/templates/product_card.php'; ?>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>

<?php if(!empty($data['newArrivals'])): ?>
<section class="container featured-section">
    <div class="section-header">
        <h2>New Arrivals</h2>
        <a href="/php/Webdev/public/catalog?filter=new" class="view-all">View All</a>
    </div>
    <div class="product-grid">
        <?php foreach($data['newArrivals'] as $product): ?>
            <?php include '../app/views/templates/product_card.php'; ?>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>

<?php if(!empty($data['bestSellers'])): ?>
<section class="container featured-section">
    <div class="section-header">
        <h2>Best Sellers</h2>
        <a href="/php/Webdev/public/catalog?filter=best" class="view-all">View All</a>
    </div>
    <div class="product-grid">
        <?php foreach($data['bestSellers'] as $product): ?>
            <?php include '../app/views/templates/product_card.php'; ?>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>
