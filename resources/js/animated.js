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

fetch("https://2b01-2001-448a-1170-34af-d88b-b60f-31f2-fdc0.ngrok-free.app/", {
    headers: {
        "ngrok-skip-browser-warning": "true",
    },
});
