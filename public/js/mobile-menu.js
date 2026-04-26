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
            
            // Toggle body class for scroll prevention
            document.body.classList.toggle('no-scroll', show);
            
            // Add slight delay for visibility to prevent flicker
            if (show) {
                navLinks.style.visibility = 'visible';
            } else {
                setTimeout(() => {
                    if (!navLinks.classList.contains('open')) {
                        navLinks.style.visibility = 'hidden';
                    }
                }, 400); // Match CSS transition duration
            }
        };

        mobileNavToggle.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            const isOpen = navLinks.classList.contains('open');
            toggleMenu(!isOpen);
        });

        if (closeMenuBtn) {
            closeMenuBtn.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                toggleMenu(false);
            });
        }

        if (menuOverlay) {
            menuOverlay.addEventListener('click', (e) => {
                e.preventDefault();
                toggleMenu(false);
            });
        }

        // Close on escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && navLinks.classList.contains('open')) {
                toggleMenu(false);
            }
        });

        // Close on link click
        navLinks.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', () => {
                // Remove no-scroll before navigation
                document.body.classList.remove('no-scroll');
                toggleMenu(false);
            });
        });
        
        // Prevent clicks inside the drawer from closing it via bubbling
        navLinks.addEventListener('click', (e) => {
            e.stopPropagation();
        });
    }
});