import "./bootstrap";

import Alpine from "alpinejs";

window.Alpine = Alpine;

Alpine.start();

document.addEventListener("DOMContentLoaded", () => {
    // Magnetic Buttons Interaction
    // Add class 'btn-magnetic' to buttons you want to have this effect
    const magneticButtons = document.querySelectorAll(
        ".btn-magnetic, .btn-primary, .btn-accent",
    );

    magneticButtons.forEach((btn) => {
        btn.addEventListener("mousemove", (e) => {
            const position = btn.getBoundingClientRect();
            // Calculate position relative to the button center
            // pageX/Y includes scroll, so we use clientX/Y relative to viewport for getBoundingClientRect math
            const x = e.clientX - position.left - position.width / 2;
            const y = e.clientY - position.top - position.height / 2;

            // Move the button slightly towards the mouse
            btn.style.transform = `translate(${x * 0.2}px, ${y * 0.2}px) scale(1.05)`;
            btn.style.transition = "transform 0.1s ease-out";
        });

        btn.addEventListener("mouseleave", () => {
            // Reset position
            btn.style.transform = "translate(0px, 0px) scale(1)";
            btn.style.transition = "transform 0.3s ease-in-out";
        });
    });

    // Parallax Effect for Background Images
    // Add class 'parallax-bg' to elements
    const parallaxElements = document.querySelectorAll(".parallax-bg");

    if (parallaxElements.length > 0) {
        window.addEventListener("scroll", () => {
            const scrolled = window.pageYOffset;

            parallaxElements.forEach((element) => {
                const speed = element.dataset.speed || 0.5;
                const limit = element.offsetHeight;

                // Only animate if in view to save resources
                const rect = element.getBoundingClientRect();
                if (rect.top < window.innerHeight && rect.bottom > 0) {
                    element.style.backgroundPositionY = scrolled * speed + "px";
                }
            });
        });
    }

    // General Parallax for elements (floating effect on scroll)
    const floatElements = document.querySelectorAll(".parallax-float");

    if (floatElements.length > 0) {
        window.addEventListener("scroll", () => {
            const scrolled = window.pageYOffset;

            floatElements.forEach((element) => {
                const speed = element.dataset.speed || 0.1;
                element.style.transform = `translateY(${scrolled * speed}px)`;
            });
        });
    }
});
