document.addEventListener('DOMContentLoaded', function() {
    const mobileNavToggle = document.querySelector('.mobile-nav-toggle');
    const navLinks = document.querySelector('.nav-links');
    const closeMenuBtn = document.querySelector('.close-mobile-menu');
    const menuOverlay = document.getElementById('mobile-menu-overlay');
    const mainHeader = document.querySelector('.main-header');

    if (mobileNavToggle && navLinks) {
        const toggleMenu = (show) => {
            navLinks.classList.toggle('open', show);
            mobileNavToggle.classList.toggle('active', show);
            if (menuOverlay) menuOverlay.classList.toggle('active', show);
            if (mainHeader) mainHeader.classList.toggle('menu-open', show);
            
            if (show) {
                document.body.style.overflow = 'hidden';
                document.documentElement.style.overflow = 'hidden';
            } else {
                document.body.style.overflow = '';
                document.documentElement.style.overflow = '';
            }
        };

        mobileNavToggle.addEventListener('click', (e) => {
            e.stopPropagation();
            const isOpen = navLinks.classList.contains('open');
            toggleMenu(!isOpen);
        });

        if (closeMenuBtn) {
            closeMenuBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                toggleMenu(false);
            });
        }

        if (menuOverlay) {
            menuOverlay.addEventListener('click', () => toggleMenu(false));
        }

        // Close on escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && navLinks.classList.contains('open')) {
                toggleMenu(false);
            }
        });

        // Highlight active link
        const currentPath = window.location.pathname;
        navLinks.querySelectorAll('a').forEach(link => {
            if (link.getAttribute('href') === currentPath) {
                link.classList.add('active');
            } else {
                link.classList.remove('active');
            }
        });

        // Close on link click
        navLinks.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', () => toggleMenu(false));
        });
        
        // Prevent clicks inside the drawer from closing it via bubbling if any parent has listener
        navLinks.addEventListener('click', (e) => {
            e.stopPropagation();
        });
    }
});