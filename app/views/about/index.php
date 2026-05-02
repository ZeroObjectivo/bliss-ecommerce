<div class="about-bg-glow"></div>

<div class="container about-page">
    <!-- Hero Section -->
    <section class="about-hero reveal-on-scroll">
        <h1>Elevating <br>E-Commerce</h1>
        <p>BLISS is a premium shopping experience designed with a focus on minimalism, speed, and elegance.</p>
        <div class="scroll-indicator">
            <span>Scroll to Explore</span>
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><polyline points="19 12 12 19 5 12"></polyline></svg>
        </div>
    </section>

    <!-- About the Project -->
    <section class="about-section">
        <h2 class="section-title reveal-on-scroll">The Project</h2>
        <div class="project-card-premium reveal-on-scroll">
            <p>
                BLISS is an academic project developed for the <strong>Application Development</strong> subject. 
                We've combined modern UI principles with robust backend technologies to create a seamless digital journey for the modern user.
            </p>
            <div class="tech-stack reveal-on-scroll">
                <div class="tech-chip tech-php">PHP</div>
                <div class="tech-chip tech-mysql">MySQL</div>
                <div class="tech-chip tech-html">HTML5</div>
                <div class="tech-chip tech-css">CSS3</div>
                <div class="tech-chip tech-js">JavaScript</div>
            </div>
        </div>
    </section>

    <!-- Team Section -->
    <section class="about-section">
        <h2 class="section-title reveal-on-scroll">Meet the Team</h2>
        <div class="team-grid">
            <div class="team-member-premium reveal-on-scroll" style="transition-delay: 0.1s;">
                <div class="member-avatar-premium">CK</div>
                <h3>CK Boregas</h3>
                <span class="role">Full Stack Developer</span>
            </div>
            <div class="team-member-premium reveal-on-scroll" style="transition-delay: 0.2s;">
                <div class="member-avatar-premium">RC</div>
                <h3>Raymark Cervantes</h3>
                <span class="role">Full Stack Developer</span>
            </div>
            <div class="team-member-premium reveal-on-scroll" style="transition-delay: 0.3s;">
                <div class="member-avatar-premium">PM</div>
                <h3>Peter Paul Marfil</h3>
                <span class="role">UI/UX Designer</span>
            </div>
            <div class="team-member-premium reveal-on-scroll" style="transition-delay: 0.4s;">
                <div class="member-avatar-premium">HS</div>
                <h3>Harris Sanggalang</h3>
                <span class="role">UI/UX Designer</span>
            </div>
            <div class="team-member-premium reveal-on-scroll" style="transition-delay: 0.5s;">
                <div class="member-avatar-premium">CS</div>
                <h3>Christian Salazar</h3>
                <span class="role">Database</span>
            </div>
        </div>
    </section>

    <!-- Vision Section -->
    <section class="about-section">
        <div class="vision-canvas reveal-on-scroll">
            <h2>Our Vision</h2>
            <p>To redefine how users interact with online storefronts by prioritizing clarity, responsiveness, and aesthetic precision in every click.</p>
            <div class="vision-cta">
                <a href="/php/Webdev/public/catalog" class="btn-vision">
                    Shop Now
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                </a>
            </div>
        </div>
    </section>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    // Apply scroll snap to HTML for this page
    document.documentElement.classList.add('about-snap');
    document.querySelectorAll('section, .vision-canvas').forEach(el => {
        el.style.scrollSnapAlign = 'start';
    });

    // Background Glow Parallax
    const glow = document.querySelector('.about-bg-glow');
    window.addEventListener('scroll', () => {
        const scrolled = window.pageYOffset;
        if(glow) glow.style.transform = `translateY(${scrolled * 0.25}px)`;
    }, { passive: true });
});

// Cleanup scroll snap when leaving the page
window.addEventListener('beforeunload', () => {
    document.documentElement.classList.remove('about-snap');
});
</script>
