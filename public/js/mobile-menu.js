document.addEventListener('DOMContentLoaded', function() {
    const mobileNavToggle = document.querySelector('.mobile-nav-toggle');
    const navLinks = document.querySelector('.nav-links');

    if (mobileNavToggle && navLinks) {
        mobileNavToggle.addEventListener('click', () => {
            navLinks.classList.toggle('open');
            mobileNavToggle.classList.toggle('active');
        });

        document.addEventListener('click', (event) => {
            if (!navLinks.contains(event.target) && !mobileNavToggle.contains(event.target) && navLinks.classList.contains('open')) {
                navLinks.classList.remove('open');
                mobileNavToggle.classList.remove('active');
            }
        });
    }
});