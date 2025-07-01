// Add this to your main JavaScript file or create a separate one
class NotificationManager {
    constructor() {
        this.unreadCount = 0;
        this.init();
    }

    init() {
        this.updateUnreadCount();
        this.startPolling();
    }

    updateUnreadCount() {
        fetch("/api/notifications/unread-count")
            .then((response) => response.json())
            .then((data) => {
                this.unreadCount = data.count;
                this.updateBadge();
            });
    }

    updateBadge() {
        const badge = document.querySelector(".notification-badge");
        if (badge) {
            if (this.unreadCount > 0) {
                badge.textContent =
                    this.unreadCount > 9 ? "9+" : this.unreadCount;
                badge.style.display = "flex";
            } else {
                badge.style.display = "none";
            }
        }
    }

    startPolling() {
        setInterval(() => {
            this.updateUnreadCount();
        }, 30000); // Check every 30 seconds
    }

    markAsRead(notificationId) {
        fetch("/notifications/mark-read", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector(
                    'meta[name="csrf-token"]'
                ).content,
            },
            body: JSON.stringify({ notification_id: notificationId }),
        }).then(() => {
            this.updateUnreadCount();
        });
    }
}

// Initialize when DOM is loaded
document.addEventListener("DOMContentLoaded", () => {
    new NotificationManager();
});
