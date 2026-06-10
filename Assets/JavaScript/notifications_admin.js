// Toggles for UI elements
const notifTrigger = document.getElementById('notifTrigger');
const notifDropdown = document.getElementById('notifDropdown');
const profileTrigger = document.querySelector('.profile-icon');

// Toggle Notification Center Window Box
notifTrigger.addEventListener('click', (e) => {
    e.stopPropagation();
    notifDropdown.classList.toggle('show');
});

// Structural Event Listener capturing outside body workspace layout clicks to safely hide active panels
document.addEventListener('click', (e) => {
    if (!notifDropdown.contains(e.target) && e.target !== notifTrigger) {
        notifDropdown.classList.remove('show');
    }
});

// Handle active class changes across filter header buttons
document.querySelectorAll('.pt').forEach(btn => {
    btn.addEventListener('click', () => {
        document.querySelectorAll('.pt').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
    });
});

//fetch notifications from the server and populate the notification dropdown
async function fetchNotifications() {
    let unreadCount = 0;
    try {
        const response = await fetch(dots+ 'Actions/fetch_notifications_admin.php');
        const result = await response.json();
        if (!response.ok) {
            throw new Error(result.error || 'Failed to fetch notifications');
        }
        const notifList = document.querySelector('.nd-body-scroller');
        notifList.innerHTML = ''; // Clear existing notifications
        result.data.forEach(notif => {
            unreadCount += notif.is_read ? 0 : 1;
            const notifItem = document.createElement('div');
            notifItem.classList.add('notif-item');
            notifItem.innerHTML = `
                <div class="nd-item ${notif.is_read ? 'read' : 'unread'}" onclick="markOneAsRead(${notif.notification_id})">
                    <div class="nd-icon purple"><i class="fa-solid fa-bell"></i></div>
                    <div class="nd-content">
                        <p>${notif.message}</p>
                        <span class="nd-time">${new Date(notif.created_at.replace(' ', 'T')).toLocaleTimeString('en-US', {
                month: 'long',
                day: 'numeric',
                year: 'numeric',
                hour: 'numeric',
                minute: '2-digit',
                hour12: true
            })}</span>
                    </div>
                </div>
            `;
            notifList.appendChild(notifItem);
        });
        document.querySelector('.notif-dot').style.display = unreadCount > 0 ? 'block' : 'none';
    } catch (error) {
        console.error('Error fetching notifications:', error);
    }
}

async function markOneAsRead(notificationId) {
    const formData = new FormData();
    formData.append('notification_id', notificationId);
    try {
        const response = await fetch('../Actions/mark_notification_read_admin.php', {
            method: 'POST',
            body: formData
        });
        const result = await response.text();
        console.log(result);
        if (!response.ok) {
            throw new Error(result.error || 'Failed to mark notification as read');
        }
        // Update the UI to reflect the notification as read
        fetchNotifications(); // Refresh the notifications list
    } catch (error) {
        console.error('Error marking notification as read:', error);
    }
}

async function markAllAsRead() {
    try {
        const response = await fetch('../Actions/mark_all_notifications_read_admin.php', {
            method: 'POST'
        });
        const result = await response.text();
        console.log(result);
        if (!response.ok) {
            throw new Error(result.error || 'Failed to mark all notifications as read');
        }
        // Update the UI to reflect all notifications as read
        fetchNotifications(); // Refresh the notifications list
    } catch (error) {
        console.error('Error marking all notifications as read:', error);
    }
}

// Initial fetch of notifications when the page loads
document.addEventListener('DOMContentLoaded', fetchNotifications);