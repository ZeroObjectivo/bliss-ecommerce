<div style="margin-bottom: var(--spacing-3);">
    <a href="/php/Webdev/public/superadmin/hero_settings" style="color: var(--admin-text-muted); text-decoration: none;">&larr; Back to Hero Settings</a>
</div>

<form action="/php/Webdev/public/superadmin/fallback_add" method="POST" class="admin-form">
    <div class="form-group-admin">
        <label>Campaign Name (Admin Only)</label>
        <input type="text" name="campaign_name" required placeholder="e.g. Summer Collection 2026">
    </div>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: var(--spacing-4);">
        <div class="form-group-admin">
            <label>Badge Text (Step 1)</label>
            <input type="text" name="badge_text" placeholder="e.g. Limited Edition">
        </div>
        <div class="form-group-admin">
            <label>Hero Title (Step 1)</label>
            <input type="text" name="hero_title" placeholder="e.g. New Arrivals">
        </div>
    </div>

    <div class="form-group-admin">
        <label>Hero Subtitle (Step 1)</label>
        <input type="text" name="hero_subtitle" placeholder="e.g. Discover the latest trends.">
    </div>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: var(--spacing-4);">
        <div class="form-group-admin">
            <label>Tagline (Step 2)</label>
            <input type="text" name="tagline" placeholder="e.g. Pure Innovation">
        </div>
        <div class="form-group-admin">
            <label>Category Pill (Step 2)</label>
            <input type="text" name="category_pill" placeholder="e.g. Premium">
        </div>
    </div>

    <div class="form-group-admin">
        <label>Description (Step 2)</label>
        <textarea name="description" rows="3" placeholder="Explain the theme or collection..."></textarea>
    </div>

    <div class="form-group-admin">
        <label>Action Headline (Step 3)</label>
        <input type="text" name="action_headline" placeholder="e.g. Find Your Fit">
    </div>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: var(--spacing-4);">
        <div class="form-group-admin">
            <label>Button 1 Text</label>
            <input type="text" name="btn1_text" value="Shop Now">
        </div>
        <div class="form-group-admin">
            <label>Button 1 Destination</label>
            <select name="btn1_link">
                <option value="/php/Webdev/public/catalog">All Products (Catalog)</option>
                <option value="/php/Webdev/public/catalog?category=Running">Running Collection</option>
                <option value="/php/Webdev/public/catalog?category=Lifestyle">Lifestyle Collection</option>
                <option value="/php/Webdev/public/catalog?category=Training">Training Collection</option>
                <option value="/php/Webdev/public/auth/register">Join Us (Register)</option>
                <option value="/php/Webdev/public/auth/login">Sign In</option>
                <option value="/php/Webdev/public/">Homepage</option>
            </select>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: var(--spacing-4);">
        <div class="form-group-admin">
            <label>Button 2 Text</label>
            <input type="text" name="btn2_text" value="Learn More">
        </div>
        <div class="form-group-admin">
            <label>Button 2 Destination</label>
            <select name="btn2_link">
                <option value="#">No Link (Disabled)</option>
                <option value="/php/Webdev/public/catalog">All Products (Catalog)</option>
                <option value="/php/Webdev/public/catalog?category=Running">Running Collection</option>
                <option value="/php/Webdev/public/catalog?category=Lifestyle">Lifestyle Collection</option>
                <option value="/php/Webdev/public/catalog?category=Training">Training Collection</option>
                <option value="/php/Webdev/public/auth/register" selected>Join Us (Register)</option>
                <option value="/php/Webdev/public/auth/login">Sign In</option>
                <option value="/php/Webdev/public/">Homepage</option>
            </select>
        </div>
    </div>

    <div class="form-group-admin">
        <label>Background Gradient</label>
        <div style="display: flex; gap: 20px; align-items: center; background: var(--admin-bg-soft); padding: 15px; border-radius: 8px; border: 1px solid var(--admin-border);">
            <div style="display: flex; flex-direction: column; gap: 5px;">
                <small style="color: var(--admin-text-muted);">Start Color</small>
                <input type="color" id="grad_start" value="#0f172a" style="width: 60px; height: 40px; border: none; cursor: pointer; background: none;">
            </div>
            <div style="display: flex; flex-direction: column; gap: 5px;">
                <small style="color: var(--admin-text-muted);">End Color</small>
                <input type="color" id="grad_end" value="#334155" style="width: 60px; height: 40px; border: none; cursor: pointer; background: none;">
            </div>
            <div id="grad_preview" style="flex-grow: 1; height: 40px; border-radius: 4px; border: 1px solid var(--admin-border); background: linear-gradient(135deg, #0f172a 0%, #334155 100%);"></div>
        </div>
        <input type="hidden" name="bg_gradient" id="bg_gradient_input" value="linear-gradient(135deg, #0f172a 0%, #334155 100%)">
        <small style="color: var(--admin-text-muted); display: block; margin-top: 10px;">Select two colors to create a smooth diagonal transition.</small>
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

    <div style="margin-top: 20px;">
        <button type="submit" class="btn-admin btn-admin-primary" style="width: 100%; padding: 12px;">Create Campaign</button>
    </div>
</form>
