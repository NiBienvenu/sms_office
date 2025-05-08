document.addEventListener('DOMContentLoaded', function() {
    // Sidebar Toggle for Mobile and Desktop
    const sidebar = document.getElementById('sidebar');
    const sidebarToggle = document.getElementById('sidebar-toggle');
    const mainContent = document.getElementById('main-content');
    const mobileSidebarClose = document.getElementById('mobile-sidebar-close');

    // Create Loading Indicator
    function createLoadingIndicator() {
        const loadingIndicator = document.createElement('div');
        loadingIndicator.id = 'loading-indicator';
        loadingIndicator.innerHTML = `
            <div class="fixed inset-0 bg-gray-500 bg-opacity-50 z-50 flex items-center justify-center">
                <div class="animate-spin rounded-full h-16 w-16 border-t-4 border-blue-500 border-solid"></div>
            </div>
        `;
        return loadingIndicator;
    }

    // Show Loading Indicator
    function showLoading() {
        const existingLoader = document.getElementById('loading-indicator');
        if (existingLoader) existingLoader.remove();

        const loadingIndicator = createLoadingIndicator();
        document.body.appendChild(loadingIndicator);
    }

    // Hide Loading Indicator
    function hideLoading() {
        const loadingIndicator = document.getElementById('loading-indicator');
        if (loadingIndicator) {
            loadingIndicator.remove();
        }
    }

    // Toggle sidebar on mobile and desktop
    function toggleSidebar() {
        sidebar.classList.toggle('-translate-x-full');
        mainContent.classList.toggle('ml-0');
        mainContent.classList.toggle('md:ml-72');
    }

    // Sidebar toggle button event
    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', toggleSidebar);
    }

    // Mobile sidebar close button
    if (mobileSidebarClose) {
        mobileSidebarClose.addEventListener('click', toggleSidebar);
    }

    // Route Navigation Function
    function navigateToRoute(route) {
        // Check if route is defined
        if (!route) {
            console.warn('No route defined for this menu item');
            return;
        }

        // Show loading indicator
        showLoading();

        // Use fetch for GET request
        fetch(route, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'text/html',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.text();
        })
        .then(html => {
            // Update main content with the fetched HTML
            const mainContentArea = document.querySelector('main');
            if (mainContentArea) {
                // Add fade-out and fade-in transitions
                mainContentArea.classList.add('opacity-0');

                // Use setTimeout to create a smooth transition
                setTimeout(() => {
                    mainContentArea.innerHTML = html;
                    mainContentArea.classList.remove('opacity-0');
                    mainContentArea.classList.add('opacity-100');
                }, 300);
            }

            // Optional: Update browser URL without page reload
            if (window.history.pushState) {
                window.history.pushState({path: route}, '', route);
            }
        })
        .catch(error => {
            console.error('Navigation error:', error);
            // Show error message
            const mainContentArea = document.querySelector('main');
            if (mainContentArea) {
                mainContentArea.innerHTML = `
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                        <strong class="font-bold">Erreur de navigation!</strong>
                        <span class="block sm:inline">Impossible de charger la page demandée. Veuillez réessayer.</span>
                        <button id="retry-navigation" class="absolute top-0 right-0 px-4 py-3 text-blue-500 hover:text-blue-700">
                            Réessayer
                        </button>
                    </div>
                `;

                // Add retry functionality
                const retryButton = document.getElementById('retry-navigation');
                if (retryButton) {
                    retryButton.addEventListener('click', () => navigateToRoute(route));
                }
            }
        })
        .finally(() => {
            // Hide loading indicator
            hideLoading();
        });
    }

    // Menu Toggle and Navigation Functionality
    const menuItems = document.querySelectorAll('.menu-item, .submenu li');

    menuItems.forEach(item => {
        // Handle main menu items with submenus
        const menuToggle = item.querySelector('.menu-toggle');
        const submenu = item.querySelector('.submenu');
        const submenuArrow = item.querySelector('.submenu-arrow');

        if (menuToggle && submenu && submenuArrow) {
            menuToggle.addEventListener('click', function() {
                // Toggle submenu visibility
                submenu.classList.toggle('hidden');

                // Rotate submenu arrow
                submenuArrow.classList.toggle('rotate-180');
            });
        }

        // Add click event to all navigatable items
        const navItem = item.querySelector('a') || menuToggle;
        if (navItem) {
            navItem.addEventListener('click', function(e) {
                e.preventDefault();

                // Close mobile sidebar after navigation
                if (window.innerWidth < 768) {
                    toggleSidebar();
                }

                // Get route from data-route attribute
                const route = this.getAttribute('data-route');
                navigateToRoute(route);
            });
        }
    });

    // Close sidebar when clicking outside on mobile
    document.addEventListener('click', function(event) {
        const isClickInsideSidebar = sidebar.contains(event.target);
        const isClickOnToggle = sidebarToggle.contains(event.target);

        // Check if we're in mobile view (sidebar is translatable)
        if (!isClickInsideSidebar && !isClickOnToggle &&
            sidebar.classList.contains('-translate-x-full') === false) {
            toggleSidebar();
        }
    });

    // Initial page load handling
    document.addEventListener('DOMContentLoaded', () => {
        // Optional: Handle initial page load or refresh
        const initialRoute = window.location.pathname;
        if (initialRoute && initialRoute !== '/') {
            navigateToRoute(initialRoute);
        }
    });
});
