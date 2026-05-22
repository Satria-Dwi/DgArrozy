document.addEventListener("DOMContentLoaded", () => {

    const guy = document.getElementById("pixelGuy");
    const bubble = document.getElementById("pixelBubble");

    const state = {
        work: "/img/gift-pixel/work.gif",
        break: "/img/gift-pixel/break.gif",
        sleep: "/img/gift-pixel/sleep.gif",
        weekend: "/img/gift-pixel/weekend.gif"
    };

    function setMode(img, text) {
        guy.src = img;
        bubble.innerHTML = text;
        bubble.classList.remove("opacity-0");
    }

    function updateCharacter() {

        const now = new Date();
        const day = now.getDay(); // 0 Minggu - 6 Sabtu
        const hour = now.getHours();

        // WEEKEND
        if (day === 0 || day === 6) {

            setMode(
                state.weekend,
                "Weekend mode 😎 santai dulu..."
            );

            return;
        }

        // WEEKDAY (Senin - Jumat)
        if (hour >= 8 && hour < 12) {

            setMode(
                state.work,
                "Mas IT lagi coding 💻"
            );

        }
        else if (hour >= 12 && hour < 13) {

            setMode(
                state.break,
                "Istirahat dulu ☕"
            );

        }
        else if (hour >= 13 && hour < 17) {

            setMode(
                state.work,
                "Balik kerja lagi 💻"
            );

        }
        else {

            setMode(
                state.sleep,
                "Mas IT tidur 😴"
            );

        }
    }

    updateCharacter();

    setInterval(updateCharacter, 60000);

});