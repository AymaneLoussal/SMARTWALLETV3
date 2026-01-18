/**
 * Smart Wallet MVC - Main Application JavaScript
 *
 * Handles menu interactions, dropdowns, responsive behavior
 * No external dependencies - vanilla JavaScript only
 */

document.addEventListener('DOMContentLoaded', function() {

    // ============================================
    // MOBILE MENU TOGGLE
    // ============================================
    const menuToggle = document.getElementById('menuToggle');
    const navMenu = document.getElementById('navMenu');
    const userBtn = document.getElementById('userBtn');
    const userDropdown = document.getElementById('userDropdown');

    if (menuToggle && navMenu) {
        menuToggle.addEventListener('click', function(e) {
            e.stopPropagation();
            navMenu.classList.toggle('active');

            // Close user dropdown when opening mobile menu
            if (navMenu.classList.contains('active') && userDropdown) {
                userDropdown.classList.remove('active');
                if (userBtn) userBtn.classList.remove('active');
            }
        });

        // Close menu when clicking on a link
        const navLinks = navMenu.querySelectorAll('a');
        navLinks.forEach(link => {
            link.addEventListener('click', function() {
                navMenu.classList.remove('active');
            });
        });
    }

    // ============================================
    // USER DROPDOWN TOGGLE
    // ============================================
    if (userBtn && userDropdown) {
        userBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            userDropdown.classList.toggle('active');
            userBtn.classList.toggle('active');

            // Close mobile menu when opening user dropdown
            if (userDropdown.classList.contains('active') && navMenu) {
                navMenu.classList.remove('active');
            }
        });
    }

    // ============================================
    // CLOSE DROPDOWNS WHEN CLICKING OUTSIDE
    // ============================================
    document.addEventListener('click', function(e) {
        // Close user dropdown
        if (userDropdown && !e.target.closest('.user-profile')) {
            userDropdown.classList.remove('active');
            if (userBtn) userBtn.classList.remove('active');
        }

        // Close mobile menu
        if (navMenu && !e.target.closest('nav') && !e.target.closest('#menuToggle')) {
            navMenu.classList.remove('active');
        }

        // Close sidebar (if on mobile)
        const sidebar = document.getElementById('sidebar');
        if (sidebar && !e.target.closest('aside') && !e.target.closest('.mobile-toggle')) {
            if (window.innerWidth <= 768) {
                sidebar.classList.remove('active');
                const overlay = document.getElementById('sidebarOverlay');
                if (overlay) overlay.classList.remove('active');
            }
        }
    });

    // ============================================
    // HEADER - SET ACTIVE MENU ITEM
    // ============================================
    const currentUrl = window.location.pathname;
    const navLinks = document.querySelectorAll('.nav-menu a');

    navLinks.forEach(link => {
        const href = link.getAttribute('href');
        if (href && currentUrl.includes(href.split('/').filter(Boolean).pop())) {
            link.classList.add('active');
        } else {
            link.classList.remove('active');
        }
    });

    // ============================================
    // SIDEBAR INTERACTIONS
    // ============================================
    const sidebar = document.getElementById('sidebar');
    const sidebarOverlay = document.getElementById('sidebarOverlay');
    const sidebarClose = document.getElementById('sidebarClose');
    const headerMenuToggle = document.getElementById('menuToggle');

    if (sidebar) {
        // Check if we're on a desktop view
        const isDesktop = () => window.innerWidth > 768;

        if (!isDesktop()) {
            // Setup mobile menu interactions
            const showSidebar = () => {
                sidebar.classList.add('active');
                if (sidebarOverlay) sidebarOverlay.classList.add('active');
            };

            const hideSidebar = () => {
                sidebar.classList.remove('active');
                if (sidebarOverlay) sidebarOverlay.classList.remove('active');
            };

            // Menu toggle from header
            if (headerMenuToggle) {
                headerMenuToggle.addEventListener('click', showSidebar);
            }

            // Close button
            if (sidebarClose) {
                sidebarClose.addEventListener('click', hideSidebar);
            }

            // Overlay click
            if (sidebarOverlay) {
                sidebarOverlay.addEventListener('click', hideSidebar);
            }

            // Close sidebar when clicking a link
            const menuLinks = sidebar.querySelectorAll('.menu-link');
            menuLinks.forEach(link => {
                link.addEventListener('click', hideSidebar);
            });
        }

        // ============================================
        // SIDEBAR - SUBMENU TOGGLE
        // ============================================
        const menuItems = sidebar.querySelectorAll('.menu-item');
        menuItems.forEach(item => {
            const link = item.querySelector('.menu-link');
            const submenu = item.querySelector('.submenu');

            if (submenu && link) {
                link.addEventListener('click', function(e) {
                    // Don't toggle if it's just a normal link
                    if (!this.href.includes('#') || submenu) {
                        e.preventDefault();
                        submenu.classList.toggle('active');
                    }
                });
            }
        });

        // ============================================
        // SIDEBAR - SET ACTIVE MENU ITEM
        // ============================================
        const sidebarLinks = sidebar.querySelectorAll('.menu-link');

        sidebarLinks.forEach(link => {
            const href = link.getAttribute('href');
            if (href && currentUrl.includes(href.split('/').filter(Boolean).pop())) {
                link.classList.add('active');

                // Show parent submenu if link is in submenu
                const submenu = link.closest('.submenu');
                if (submenu) {
                    submenu.classList.add('active');
                }
            }
        });
    }

    // ============================================
    // FORM HANDLING
    // ============================================
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        // Add error message display
        const errorMessages = form.querySelectorAll('.form-error');
        if (errorMessages.length > 0) {
            // Scroll to first error on load
            window.scrollTo(0, errorMessages[0].offsetTop - 100);
        }

        // Add form validation feedback
        const inputs = form.querySelectorAll('.form-control');
        inputs.forEach(input => {
            input.addEventListener('change', function() {
                const errorMsg = this.parentElement.querySelector('.form-error');
                if (errorMsg && this.value.trim()) {
                    errorMsg.style.display = 'none';
                }
            });
        });
    });

    // ============================================
    // RESPONSIVE MENU BEHAVIOR
    // ============================================
    let lastWidth = window.innerWidth;

    window.addEventListener('resize', function() {
        const currentWidth = window.innerWidth;

        // If switching between mobile and desktop, adjust menus
        if ((lastWidth <= 768 && currentWidth > 768) || (lastWidth > 768 && currentWidth <= 768)) {
            // Close mobile menu
            if (navMenu) navMenu.classList.remove('active');
            if (userDropdown) userDropdown.classList.remove('active');
            if (userBtn) userBtn.classList.remove('active');

            // Close sidebar on mobile
            if (sidebar && currentWidth <= 768) {
                sidebar.classList.remove('active');
                if (sidebarOverlay) sidebarOverlay.classList.remove('active');
            }
        }

        lastWidth = currentWidth;
    });

    // ============================================
    // ALERT AUTO-CLOSE
    // ============================================
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        // Only auto-close success messages after 5 seconds
        if (alert.classList.contains('alert-success')) {
            setTimeout(() => {
                alert.style.opacity = '0';
                alert.style.transition = 'opacity 0.3s ease';
                setTimeout(() => {
                    alert.remove();
                }, 300);
            }, 5000);
        }
    });

    // ============================================
    // SMOOTH SCROLL FOR INTERNAL LINKS
    // ============================================
    const smoothScrollLinks = document.querySelectorAll('a[href^="#"]');
    smoothScrollLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            const href = this.getAttribute('href');
            const target = document.querySelector(href);

            if (target && href !== '#') {
                e.preventDefault();
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // ============================================
    // KEYBOARD NAVIGATION
    // ============================================
    // Close dropdowns on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            if (userDropdown) userDropdown.classList.remove('active');
            if (userBtn) userBtn.classList.remove('active');
            if (navMenu) navMenu.classList.remove('active');

            const sidebar = document.getElementById('sidebar');
            if (sidebar && window.innerWidth <= 768) {
                sidebar.classList.remove('active');
                if (sidebarOverlay) sidebarOverlay.classList.remove('active');
            }
        }
    });

    // Tab navigation for menus
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Tab') {
            // Handle focus management for dropdowns
            if (userDropdown && userDropdown.classList.contains('active')) {
                const links = userDropdown.querySelectorAll('a');
                const lastLink = links[links.length - 1];

                if (document.activeElement === lastLink && !e.shiftKey) {
                    e.preventDefault();
                    userBtn.focus();
                }
            }
        }
    });

    // ============================================
    // LOAD MORE / PAGINATION HELPERS
    // ============================================
    const loadMoreButtons = document.querySelectorAll('[data-load-more]');
    loadMoreButtons.forEach(button => {
        button.addEventListener('click', function() {
            const url = this.getAttribute('data-load-more');
            if (url) {
                fetch(url)
                    .then(response => response.text())
                    .then(html => {
                        const container = this.closest('[data-load-container]');
                        if (container) {
                            container.insertAdjacentHTML('beforeend', html);
                            this.remove();
                        }
                    })
                    .catch(error => console.error('Load more error:', error));
            }
        });
    });

    // ============================================
    // UTILITY: CONFIRM BEFORE ACTION
    // ============================================
    const confirmLinks = document.querySelectorAll('[data-confirm]');
    confirmLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            const message = this.getAttribute('data-confirm');
            if (message && !confirm(message)) {
                e.preventDefault();
            }
        });
    });

    // ============================================
    // INITIALIZATION COMPLETE
    // ============================================
    console.log('Smart Wallet Application initialized successfully');
});

/**
 * Utility function to show notifications
 * Usage: showNotification('message', 'success', 3000)
 */
function showNotification(message, type = 'info', duration = 3000) {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type}`;
    alertDiv.textContent = message;
    alertDiv.style.margin = '1rem';
    alertDiv.style.position = 'fixed';
    alertDiv.style.top = '80px';
    alertDiv.style.left = '50%';
    alertDiv.style.transform = 'translateX(-50%)';
    alertDiv.style.zIndex = '2000';
    alertDiv.style.minWidth = '300px';
    alertDiv.style.textAlign = 'center';

    document.body.appendChild(alertDiv);

    if (duration > 0) {
        setTimeout(() => {
            alertDiv.style.opacity = '0';
            alertDiv.style.transition = 'opacity 0.3s ease';
            setTimeout(() => alertDiv.remove(), 300);
        }, duration);
    }
}

/**
 * Utility function to format currency
 * Usage: formatCurrency(1000.50, 'USD')
 */
function formatCurrency(amount, currency = 'USD') {
    return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: currency
    }).format(amount);
}

/**
 * Utility function to format date
 * Usage: formatDate(new Date(), 'short')
 */
function formatDate(date, format = 'short') {
    if (typeof date === 'string') {
        date = new Date(date);
    }

    const options = {
        short: { year: 'numeric', month: 'short', day: 'numeric' },
        long: { year: 'numeric', month: 'long', day: 'numeric' },
        full: { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' }
    };

    return date.toLocaleDateString('en-US', options[format] || options.short);
}
