// Add confirmation dialogs and any additional functionality
document.addEventListener("DOMContentLoaded", function () {
    // Auto-refresh every 5 minutes
    setInterval(function () {
        location.reload();
    }, 300000);

    // Add tooltips for long text
    const tooltipElements = document.querySelectorAll("[data-tooltip]");
    tooltipElements.forEach((element) => {
        element.addEventListener("mouseenter", function () {
            const tooltip = document.createElement("div");
            tooltip.className = "tooltip";
            tooltip.textContent = this.getAttribute("data-tooltip");
            document.body.appendChild(tooltip);

            const rect = this.getBoundingClientRect();
            tooltip.style.left = rect.left + "px";
            tooltip.style.top = rect.bottom + 5 + "px";
        });

        element.addEventListener("mouseleave", function () {
            const tooltip = document.querySelector(".tooltip");
            if (tooltip) tooltip.remove();
        });
    });
});
