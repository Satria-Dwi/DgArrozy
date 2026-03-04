document.addEventListener("DOMContentLoaded", () => {
    const card = document.querySelector("[data-video-harian]");
    if (!card) return;

    const videoEl = card.querySelector(".kpi-video");

    const hari = new Date().getDay(); // 0 = Minggu

    const videoMap = {
        0: { src: "/vid/minggu.mp4"},
        1: { src: "/vid/senin.mp4"},
        2: { src: "/vid/selasa.mp4"},
        3: { src: "/vid/rabu.mp4"},
        4: { src: "/vid/kamis.mp4"},
        5: { src: "/vid/jumat.mp4"},
        6: { src: "/vid/sabtu.mp4"},
    };

    const video = videoMap[hari];

    if (video) {
        videoEl.src = video.src;
    }
});
