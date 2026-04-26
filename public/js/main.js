// Toast Notification System (Global)
window.showToast = function(message, type = 'success') {
    let toastContainer = document.querySelector('.toast-container');
    if (!toastContainer) {
        toastContainer = document.createElement('div');
        toastContainer.className = 'toast-container';
        document.body.appendChild(toastContainer);
    }

    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;
    toast.innerHTML = `
        <div class="toast-content">
            <span class="toast-icon">${type === 'success' ? '✓' : '✕'}</span>
            <span class="toast-message">${message}</span>
        </div>
        <button class="toast-close">&times;</button>
    `;

    toastContainer.appendChild(toast);

    const closeBtn = toast.querySelector('.toast-close');
    const dismissToast = () => {
        toast.classList.remove('active');
        setTimeout(() => toast.remove(), 500);
    };
    if (closeBtn) closeBtn.addEventListener('click', dismissToast);

    setTimeout(() => toast.classList.add('active'), 50);

    setTimeout(() => {
        if (toast.parentElement) dismissToast();
    }, 4500);
};

document.addEventListener('DOMContentLoaded', () => {
    // Check for success/error messages in URL and show toasts
    const urlParams = new URLSearchParams(window.location.search);
    const successKey = urlParams.get('success');
    const errorKey = urlParams.get('error');

    if (successKey) {
        const messages = {
            'profile_updated': 'Profile updated successfully!',
            'info_updated': 'Profile updated successfully!',
            'password_changed': 'Your password has been changed.',
            'password_updated': 'Your password has been changed.',
            'address_added': 'New address saved to your account.',
            'address_updated': 'Address updated successfully.',
            'address_deleted': 'Address removed successfully.',
            'default_set': 'Default address updated.',
            'default_address_set': 'Default address updated.',
            'avatar_updated': 'Profile picture updated!',
            'order_completed': 'Order marked as completed!'
        };
        const msg = messages[successKey] || successKey.replace(/_/g, ' ') + '!';
        window.showToast(msg);
        const newUrl = new URL(window.location);
        newUrl.searchParams.delete('success');
        window.history.replaceState({}, document.title, newUrl);
    }

    if (errorKey) {
        const msg = errorKey.replace(/_/g, ' ') + '. Please try again.';
        window.showToast(msg, 'error');
        const newUrl = new URL(window.location);
        newUrl.searchParams.delete('error');
        window.history.replaceState({}, document.title, newUrl);
    }

    // Search Functionality
    const searchInput = document.querySelector('.search-bar input');
    const searchBar = document.querySelector('.search-bar');
    
    if (searchBar && searchInput) {
        const searchResults = document.createElement('div');
        searchResults.className = 'search-results-dropdown';
        searchResults.style.display = 'none';
        searchBar.appendChild(searchResults);

        let debounceTimer;
        searchInput.addEventListener('input', (e) => {
            clearTimeout(debounceTimer);
            const query = e.target.value.trim();
            if (query.length < 2) {
                searchResults.style.display = 'none';
                return;
            }

            debounceTimer = setTimeout(() => {
                fetch(`/php/Webdev/public/catalog/search_ajax?q=${encodeURIComponent(query)}`)
                    .then(response => response.json())
                    .then(data => {
                        searchResults.innerHTML = '';
                        if (data.results && data.results.length > 0) {
                            data.results.forEach(product => {
                                const item = document.createElement('a');
                                item.href = `/php/Webdev/public/product/detail/${product.id}`;
                                item.className = 'search-result-item';
                                item.innerHTML = `
                                    <img src="${product.image_main}" alt="${product.name}">
                                    <div>
                                        <h4>${product.name}</h4>
                                        <span class="price">₱${product.price}</span>
                                    </div>
                                `;
                                searchResults.appendChild(item);
                            });
                        } else {
                            searchResults.innerHTML = '<div class="search-result-item no-results">No products found.</div>';
                        }
                        searchResults.style.display = 'block';
                    })
                    .catch(err => console.error('Search error:', err));
            }, 300);
        });

        document.addEventListener('click', (e) => {
            if (!searchBar.contains(e.target)) {
                searchResults.style.display = 'none';
            }
        });
    }

    // Help Page Search Functionality
    const helpSearchInput = document.querySelector('.help-search-input');
    if (helpSearchInput) {
        const faqItems = document.querySelectorAll('.faq-item');
        const categoryCards = document.querySelectorAll('.help-category-card');
        const faqSection = document.querySelector('.faq-section');
        const helpGrid = document.querySelector('.help-grid');

        const noResultsMsg = document.createElement('div');
        noResultsMsg.className = 'no-results-message';
        noResultsMsg.innerHTML = `
            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
            <h3>No results found</h3>
            <p>Try searching for different keywords.</p>
        `;
        noResultsMsg.style.display = 'none';
        if (faqSection) faqSection.parentNode.insertBefore(noResultsMsg, faqSection.nextSibling);

        helpSearchInput.addEventListener('input', (e) => {
            const query = e.target.value.toLowerCase().trim();
            filterHelp(query);
        });

        const clearFilterBtn = document.getElementById('clear-filter');
        const faqTitle = document.getElementById('faq-title');

        categoryCards.forEach(card => {
            card.addEventListener('click', () => {
                const category = card.getAttribute('data-category');
                const categoryName = card.querySelector('h3').textContent;
                filterHelp('', category);
                faqSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
                if (faqTitle) faqTitle.textContent = `${categoryName} - FAQ`;
                if (clearFilterBtn) clearFilterBtn.style.display = 'block';
            });
        });

        if (clearFilterBtn) {
            clearFilterBtn.addEventListener('click', () => {
                filterHelp('');
                if (faqTitle) faqTitle.textContent = 'Frequently Asked Questions';
                clearFilterBtn.style.display = 'none';
                helpSearchInput.value = '';
            });
        }

        let currentHelpPage = 1;
        const helpItemsPerPage = 5;

        function filterHelp(query, categoryFilter = null) {
            currentHelpPage = 1;
            updateHelpUI(query, categoryFilter);
        }

        function updateHelpUI(query, categoryFilter) {
            let filteredFAQs = [];
            let categoryVisibleCount = 0;

            faqItems.forEach(item => {
                const text = item.textContent.toLowerCase();
                const itemCategory = item.getAttribute('data-category');
                let matchesQuery = text.includes(query);
                let matchesCategory = !categoryFilter || itemCategory === categoryFilter;
                if (matchesQuery && matchesCategory) filteredFAQs.push(item);
                item.style.display = 'none';
            });

            const totalPages = Math.ceil(filteredFAQs.length / helpItemsPerPage);
            const startIndex = (currentHelpPage - 1) * helpItemsPerPage;
            const endIndex = startIndex + helpItemsPerPage;
            filteredFAQs.slice(startIndex, endIndex).forEach(item => { item.style.display = 'block'; });

            renderPagination(totalPages, query, categoryFilter);

            categoryCards.forEach(card => {
                if (categoryFilter) {
                    card.style.display = 'flex';
                    if (card.getAttribute('data-category') === categoryFilter) {
                        card.style.border = '2px solid var(--accent-color)';
                        card.style.background = 'white';
                    } else {
                        card.style.border = '1px solid rgba(255, 255, 255, 0.8)';
                        card.style.background = 'rgba(255, 255, 255, 0.6)';
                    }
                    categoryVisibleCount++;
                } else {
                    card.style.border = '1px solid rgba(255, 255, 255, 0.8)';
                    card.style.background = 'rgba(255, 255, 255, 0.6)';
                    const text = card.textContent.toLowerCase();
                    if (text.includes(query)) {
                        card.style.display = 'flex';
                        categoryVisibleCount++;
                    } else {
                        card.style.display = 'none';
                    }
                }
            });

            if (faqSection) {
                const faqHeader = faqSection.querySelector('h2');
                if (filteredFAQs.length === 0 && !categoryFilter) {
                    if (faqHeader) faqHeader.style.display = 'none';
                } else {
                    if (faqHeader) faqHeader.style.display = 'block';
                }
            }

            if (helpGrid && !categoryFilter) {
                helpGrid.style.display = categoryVisibleCount === 0 ? 'none' : 'grid';
            } else if (helpGrid) {
                helpGrid.style.display = 'grid';
            }

            noResultsMsg.style.display = (filteredFAQs.length === 0 && categoryVisibleCount === 0) ? 'block' : 'none';
        }

        function renderPagination(totalPages, query, categoryFilter) {
            const paginationContainer = document.getElementById('faq-pagination');
            if (!paginationContainer) return;
            paginationContainer.innerHTML = '';
            if (totalPages <= 1) return;
            for (let i = 1; i <= totalPages; i++) {
                const btn = document.createElement('button');
                btn.textContent = i;
                btn.style.cssText = `padding: 8px 16px; border: 1px solid ${i === currentHelpPage ? 'var(--accent-color)' : '#e2e8f0'}; background: ${i === currentHelpPage ? 'var(--accent-color)' : 'white'}; color: ${i === currentHelpPage ? 'white' : 'var(--text-primary)'}; border-radius: 8px; cursor: pointer; font-weight: 600; transition: all 0.2s ease;`;
                btn.addEventListener('click', () => {
                    currentHelpPage = i;
                    updateHelpUI(query, categoryFilter);
                    faqSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
                });
                paginationContainer.appendChild(btn);
            }
        }
        filterHelp('');
    }

    // Apple-style Sticky Scroll & Reveal
    const stickyContainer = document.querySelector('.apple-sticky-container');
    const stickyImg = document.getElementById('sticky-product-img');
    const productGlow = document.getElementById('product-glow');
    const hugeBgText = document.getElementById('huge-bg-text');
    const revealElements = document.querySelectorAll('.reveal-on-scroll');
    const progressDots = document.querySelectorAll('.progress-dot');

    if (stickyContainer) {
        const visualContainer = stickyContainer.querySelector('.sticky-image-container');
        if (visualContainer) {
            for (let i = 0; i < 15; i++) {
                const particle = document.createElement('div');
                particle.className = 'particle';
                const size = Math.random() * 4 + 2;
                particle.style.width = `${size}px`;
                particle.style.height = `${size}px`;
                particle.style.left = `${Math.random() * 100}%`;
                particle.style.top = `${Math.random() * 100}%`;
                visualContainer.appendChild(particle);
            }
        }

        let isScrolling = false;
        const updateHero = () => {
            const rect = stickyContainer.getBoundingClientRect();
            const containerHeight = stickyContainer.offsetHeight;
            const viewportHeight = window.innerHeight;
            const header = document.querySelector('.main-header');
            const headerHeight = header ? header.offsetHeight : 0;
            
            // Progress is 0 when top of container reaches top of viewport
            let progress = -rect.top / (containerHeight - viewportHeight);
            progress = Math.max(0, Math.min(1, progress));

            // Section is active as long as we are within its scroll range
            // On mobile we extend the range slightly to prevent early disappearance
            const isMobile = window.innerWidth <= 768;
            const activeThreshold = isMobile ? 0.99 : 0.98;

            if (rect.top <= headerHeight && progress < activeThreshold) {
                stickyContainer.classList.add('is-active');
            } else {
                stickyContainer.classList.remove('is-active');
            }

            if (stickyImg) {
                const scaleBase = isMobile ? 0.85 : 0.95;
                const scaleAmount = isMobile ? 0.3 : 0.25;
                const scale = scaleBase + (progress * scaleAmount);
                
                // Parallax movement for mobile to make it feel less "fixed"
                const yOffset = isMobile ? (progress * -100) : 0;
                
                const rotateX = (progress * 15) - 7.5;
                const rotateY = (progress * 20) - 10;
                stickyImg.style.transform = `translateY(${yOffset}px) scale(${scale}) rotateX(${rotateX}deg) rotateY(${rotateY}deg)`;
            }

            if (productGlow) {
                const glowScale = 0.8 + (progress * 0.6);
                const glowOpacity = isMobile ? (0.2 + (progress * 0.4)) : (0.1 + (progress * 0.5));
                const glowY = isMobile ? (progress * -80) : 0;
                productGlow.style.transform = `translateY(${glowY}px) scale(${glowScale})`;
                productGlow.style.opacity = glowOpacity;
            }

            if (hugeBgText) {
                const bgScale = 1 + (progress * 0.5);
                const bgY = isMobile ? (progress * -150) : 0; // "BLISS" moves fastest for parallax
                // Text fades out towards the end
                const bgOpacity = progress < 0.5 ? 0.15 : Math.max(0, 0.15 - (progress - 0.5) * 0.5);
                hugeBgText.style.transform = `translateY(${bgY}px) scale(${bgScale})`;
                hugeBgText.style.opacity = bgOpacity;
            }

            const particles = stickyContainer.querySelectorAll('.particle');
            particles.forEach((p, i) => {
                const speed = 0.2 + (i * 0.05);
                const yOffset = progress * 200 * speed;
                p.style.transform = `translateY(-${yOffset}px)`;
            });

            if (progressDots.length > 0) {
                const step = Math.min(progressDots.length - 1, Math.floor(progress * progressDots.length));
                progressDots.forEach((dot, index) => {
                    dot.classList.toggle('active', index === step);
                });
            }

            isScrolling = false;
        };

        window.addEventListener('scroll', () => {
            if (!isScrolling) {
                requestAnimationFrame(updateHero);
                isScrolling = true;
            }
        }, { passive: true });

        // Initial call to set positions
        updateHero();

        // Handle window resize for dynamic viewport height (mobile)
        window.addEventListener('resize', updateHero, { passive: true });
    }

    if (revealElements.length > 0) {
        const observerOptions = { threshold: 0.15, rootMargin: '0px 0px -50px 0px' };
        const revealObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                } else {
                    entry.target.classList.remove('is-visible');
                }
            });
        }, observerOptions);
        revealElements.forEach(el => revealObserver.observe(el));
    }

    // Quick Add Modal Logic
    const modal = document.getElementById('quick-add-modal');
    if (modal) {
        const closeBtn = modal.querySelector('.modal-close-btn');
        const sizeGrid = document.getElementById('modal-size-grid');
        const submitBtn = document.getElementById('modal-submit-btn');
        const sizeInput = document.getElementById('modal-selected-size');
        const isEditInput = document.getElementById('modal-is-edit');
        const oldKeyInput = document.getElementById('modal-old-key');
        const stockInfo = document.getElementById('modal-stock-info');
        const btnText = submitBtn.querySelector('.btn-text');

        const openModal = (data) => {
            const { id, name, price, image, sizes, isEdit, oldKey, currentSize, removeFromFav } = data;
            document.getElementById('modal-img').src = image;
            document.getElementById('modal-name').textContent = name;
            document.getElementById('modal-price').textContent = `₱${parseFloat(price).toLocaleString(undefined, {minimumFractionDigits: 2})}`;
            document.getElementById('modal-product-id').value = id;
            isEditInput.value = isEdit ? '1' : '0';
            oldKeyInput.value = oldKey || '';
            document.getElementById('modal-remove-from-fav').value = removeFromFav ? '1' : '0';
            btnText.textContent = isEdit ? 'Update Size' : (removeFromFav ? 'Move to Cart' : 'Select a Size');
            sizeGrid.innerHTML = '';
            sizeInput.value = '';
            submitBtn.disabled = true;
            stockInfo.textContent = '';
            Object.entries(sizes).forEach(([size, stock]) => {
                const sBtn = document.createElement('button');
                sBtn.type = 'button';
                sBtn.className = 'premium-size-btn';
                sBtn.textContent = size;
                if (stock <= 0) sBtn.disabled = true;
                if (isEdit && size === currentSize) {
                    sBtn.classList.add('active');
                    sizeInput.value = size;
                    submitBtn.disabled = false;
                    btnText.textContent = 'Keep Size';
                }
                sBtn.addEventListener('click', () => {
                    sizeGrid.querySelectorAll('.premium-size-btn').forEach(b => b.classList.remove('active'));
                    sBtn.classList.add('active');
                    sizeInput.value = size;
                    submitBtn.disabled = false;
                    btnText.textContent = isEdit ? (size === currentSize ? 'Keep Size' : 'Update Size') : 'Add to Cart';
                    if (stock <= 5) { stockInfo.textContent = `Only ${stock} left!`; stockInfo.style.color = '#ef4444'; }
                    else { stockInfo.textContent = 'In Stock'; stockInfo.style.color = '#10b981'; }
                });
                sizeGrid.appendChild(sBtn);
            });
            modal.classList.add('active');
        };

        document.querySelectorAll('.quick-add-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                openModal({
                    id: btn.dataset.id,
                    name: btn.dataset.name,
                    price: btn.dataset.price,
                    image: btn.dataset.image,
                    sizes: JSON.parse(btn.dataset.sizes),
                    isEdit: false,
                    removeFromFav: btn.dataset.removeFromFav === 'true'
                });
            });
        });

        document.querySelectorAll('.edit-size-link').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                openModal({
                    id: btn.dataset.id,
                    name: btn.dataset.name,
                    price: btn.dataset.price,
                    image: btn.dataset.image,
                    sizes: JSON.parse(btn.dataset.sizes),
                    isEdit: true,
                    oldKey: btn.dataset.key,
                    currentSize: btn.dataset.size
                });
            });
        });

        const closeModal = () => modal.classList.remove('active');
        closeBtn.addEventListener('click', closeModal);
        window.addEventListener('click', (e) => { if (e.target === modal) closeModal(); });

        const cartForm = document.getElementById('modal-cart-form');
        if (cartForm) {
            cartForm.addEventListener('submit', (e) => {
                e.preventDefault();
                const formData = new FormData(cartForm);
                const submitBtn = cartForm.querySelector('button[type="submit"]');
                const originalBtnContent = submitBtn.innerHTML;
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="loading-spinner"></span> Adding...';
                fetch(cartForm.action, { method: 'POST', body: formData, headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        const cartBadge = document.querySelector('.cart-link .cart-badge');
                        if (cartBadge) { cartBadge.textContent = data.cartCount; cartBadge.style.display = data.cartCount > 0 ? 'flex' : 'none'; }
                        const isFromFav = document.getElementById('modal-remove-from-fav').value === '1';
                        if (isFromFav) {
                            const productId = document.getElementById('modal-product-id').value;
                            const favItem = document.querySelector(`.favorite-item button[data-id="${productId}"]`)?.closest('.favorite-item');
                            if (favItem) {
                                favItem.style.opacity = '0';
                                favItem.style.transition = 'all 0.3s ease';
                                setTimeout(() => { favItem.remove(); if (document.querySelectorAll('.favorite-item').length === 0) location.reload(); }, 300);
                            }
                        }
                        window.showToast(isFromFav ? 'Moved to cart!' : 'Item added to cart successfully!');
                        closeModal();
                    } else window.showToast(data.error || 'Failed to add item.', 'error');
                })
                .catch(err => { console.error('Cart Error:', err); window.showToast('An error occurred.', 'error'); })
                .finally(() => { submitBtn.disabled = false; submitBtn.innerHTML = originalBtnContent; });
            });
        }
    }

    // Cart Quantity Updates
    const cartItems = document.querySelectorAll('.cart-item');
    if (cartItems.length > 0) {
        cartItems.forEach(item => {
            const minusBtn = item.querySelector('.qty-btn.minus');
            const plusBtn = item.querySelector('.qty-btn.plus');
            const qtyInput = item.querySelector('.qty-input');
            const cartKey = item.dataset.key;
            const maxStock = parseInt(item.dataset.stock);
            const updateQty = (newQty) => {
                if (newQty < 1 || newQty > maxStock) return;
                fetch('/php/Webdev/public/cart/update_quantity', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `key=${encodeURIComponent(cartKey)}&qty=${newQty}`
                }).then(res => res.json()).then(data => { if (data.success) location.reload(); });
            };
            if (minusBtn) minusBtn.addEventListener('click', () => updateQty(parseInt(qtyInput.value) - 1));
            if (plusBtn) plusBtn.addEventListener('click', () => updateQty(parseInt(qtyInput.value) + 1));
            if (qtyInput) qtyInput.addEventListener('change', () => updateQty(parseInt(qtyInput.value)));
        });
    }

    // Profile Sidebar Toggle (Mobile)
    const profileTrigger = document.getElementById('mobile-profile-trigger');
    const profileSidebar = document.getElementById('profile-sidebar');
    const sidebarOverlay = document.getElementById('sidebar-overlay');
    if (profileTrigger && profileSidebar && sidebarOverlay) {
        const toggle = (show) => {
            profileSidebar.classList.toggle('open', show);
            sidebarOverlay.classList.toggle('active', show);
            document.body.style.overflow = show ? 'hidden' : '';
        };
        profileTrigger.addEventListener('click', () => toggle(true));
        sidebarOverlay.addEventListener('click', () => toggle(false));
        profileSidebar.querySelectorAll('nav a').forEach(link => link.addEventListener('click', () => toggle(false)));
    }
});
