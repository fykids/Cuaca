// Add loading animation
document.querySelector(".search-form").addEventListener("submit", function () {
    document.getElementById("loading").classList.add("show");
});

// Add some interactive effects
document.querySelectorAll(".detail-item, .info-card").forEach((item) => {
    item.addEventListener("mouseenter", function () {
        this.style.transform = "translateY(-8px) scale(1.02)";
    });

    item.addEventListener("mouseleave", function () {
        this.style.transform = "translateY(0) scale(1)";
    });
});
