document.addEventListener("DOMContentLoaded", function () {
    /* ======================
       YOUTUBE LAZY LOAD
    ====================== */
    const iframe = document.getElementById("youtubePlayer");

    if (iframe) {
        const observer = new IntersectionObserver(
            (entries, obs) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        if (!iframe.getAttribute("src")) {
                            iframe.setAttribute("src", iframe.dataset.src);
                        }
                        obs.unobserve(iframe);
                    }
                });
            },
            { threshold: 0.5 },
        );

        observer.observe(iframe);
    }

    /* ======================
       SLIDER
    ====================== */
    const slider = document.getElementById("slider");
    const dots = document.querySelectorAll(".dot");

    if (slider && dots.length > 0) {
        let index = 0;
        const totalSlides = slider.children.length;

        function showSlide(i) {
            slider.style.transform = `translateX(-${i * 100}%)`;
            dots.forEach((dot) => dot.classList.remove("bg-white"));
            dots[i].classList.add("bg-white");
        }

        function nextSlide() {
            index = (index + 1) % totalSlides;
            showSlide(index);
        }

        dots.forEach((dot, i) => {
            dot.addEventListener("click", () => {
                index = i;
                showSlide(index);
            });
        });

        showSlide(index);
        setInterval(nextSlide, 4000);
    }
});
