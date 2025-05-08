document.addEventListener('DOMContentLoaded', () => {
    // Sidebar Toggle
    const sidebarToggle = document.getElementById('sidebar-toggle');
    const navItem = document.getElementById('item-value');

    sidebarToggle.addEventListener('click', () => {
        // Toggle the sidebar collapse state
        document.body.classList.toggle('sidebar-collapsed');

        // Check if the sidebar has the 'sidebar-collapsed' class
        if (document.body.classList.contains('sidebar-collapsed')) {
            // Hide the element when sidebar is collapsed
            navItem.style.display = 'none';
        } else {
            // Show the element when sidebar is expanded
            navItem.style.display = 'block';
        }
    });


    // Notifications Dropdown
    const notificationsToggle = document.getElementById('notifications-toggle');
    const notificationsDropdown = document.getElementById('notifications-dropdown');
    const markAllReadBtn = document.getElementById('mark-all-read');
    const notificationsBadge = document.getElementById('notifications-badge');

    notificationsToggle.addEventListener('click', () => {
        toggleDropdown(notificationsDropdown);
    });

    markAllReadBtn.addEventListener('click', () => {
        notificationsBadge.classList.add('hidden');

        // Optional: Send AJAX request to mark notifications as read
        fetch('/notifications/mark-read', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .catch(error => console.error('Error:', error));
    });

    // Profile Dropdown
    const profileToggle = document.getElementById('profile-toggle');
    const profileDropdown = document.getElementById('profile-dropdown');
    const logoutBtn = document.getElementById('logout-btn');

    profileToggle.addEventListener('click', () => {
        toggleDropdown(profileDropdown);
    });

    logoutBtn.addEventListener('click', (e) => {
        e.preventDefault();

        fetch('/logout', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(() => {
            window.location.href = '/login';
        })
        .catch(error => console.error('Logout error:', error));
    });

    // Close dropdowns when clicking outside
    document.addEventListener('click', (event) => {
        const notificationsContainer = document.getElementById('notifications-container');
        const profileContainer = document.getElementById('profile-container');

        if (!notificationsContainer.contains(event.target)) {
            notificationsDropdown.classList.add('hidden');
        }

        if (!profileContainer.contains(event.target)) {
            profileDropdown.classList.add('hidden');
        }
    });

    // Helper function to toggle dropdowns
    function toggleDropdown(dropdown) {
        dropdown.classList.toggle('hidden');

        // Close other dropdowns
        const otherDropdowns = [
            document.getElementById('notifications-dropdown'),
            document.getElementById('profile-dropdown')
        ];

        otherDropdowns.forEach(otherDropdown => {
            if (otherDropdown !== dropdown) {
                otherDropdown.classList.add('hidden');
            }
        });
    }
});
