// =============================
// SWIPER SLIDER INIT
// =============================

import Swiper from "swiper";
import { Navigation } from "swiper/modules";
import "swiper/css";
import "swiper/css/navigation";

export function initOwlSlider({ fetchWords, elements }) {
    const swiper = new Swiper("#slider-rtl", {
        modules: [Navigation], // wajib didaftarkan di Swiper modular (v9+)
        rtl: true,
        slidesPerView: "auto", // lebar tiap slide mengikuti lebar teks kata
        centeredSlides: true, // slide aktif otomatis di tengah
        slideToClickedSlide: true,
        spaceBetween: 8, // jarak antar kata
        speed: 300,
        navigation: {
            nextEl: "#btn-next-slide",
            prevEl: "#btn-prev-slide",
        },
    });

    function getActiveSlideId() {
        const activeSlide = swiper.slides?.[swiper.activeIndex];
        if (!activeSlide) return null;
        const id = $(activeSlide).find(".word-group").attr("wg-id");
        return id || null;
    }

    function bindActiveSlideEvents() {
        swiper.on("slideChange", () => {
            const id = getActiveSlideId();
            if (id) fetchWords(id);
        });
    }

    bindActiveSlideEvents();

    // Fetch kata pertama saat halaman pertama kali dimuat (data awal dari
    // blade sudah ada di DOM sebelum initOwlSlider dipanggil)
    const initialId = getActiveSlideId();
    if (initialId) fetchWords(initialId);

    function renderOwlSlider(data) {
        const wrapper = document.querySelector("#slider-rtl .swiper-wrapper");
        if (!wrapper) return;

        wrapper.innerHTML = "";

        const wordGroups = data.wordGroups || [];

        // Urutan DOM TIDAK perlu dibalik seperti workaround Owl sebelumnya —
        // Swiper dengan rtl:true menangani urutan visual RTL dengan benar
        // dari urutan data aslinya (kata pertama ayat = slide pertama).
        wordGroups.forEach((wordGroup) => {
            const slide = document.createElement("div");
            slide.className = "swiper-slide";
            slide.innerHTML = `<h4 class="arabic-text ar-title word-group" wg-id="${wordGroup.id}">${wordGroup.text}</h4>`;
            wrapper.appendChild(slide);
        });

        swiper.update();
        swiper.slideTo(0, 0); // mulai dari kata pertama ayat, tanpa animasi

        // slideTo(0,0) tidak selalu memicu event slideChange kalau index
        // sebelumnya juga 0, jadi fetch manual supaya tabel kata tetap sinkron
        const id = getActiveSlideId();
        if (id) fetchWords(id);

        elements.currentSurahId.value = data.surah.id;
        elements.currentVerseNumber.value = data.verse.number;
        elements.currentVerseId.value = data.verse.id;

        elements.surahOption.value = "";
        elements.verseOption.value = "";

        elements.currentVerseLabel.textContent = `${data.surah.id}. ${data.surah.name} - Ayat ${data.verse.number}`;

        const firstWordGroup = data?.wordGroups?.[0];
        const editorName = firstWordGroup?.editor_info
            ? firstWordGroup.editor_info.name
            : " -";

        const editorWgInfo = $(".editor-wordgroup a").contents().last()[0];
        const btnAddWord = document.getElementById("btn-add-word");

        if (editorWgInfo && firstWordGroup?.editor_info?.name) {
            editorWgInfo.textContent = ` ${editorName}`;
            if (btnAddWord) {
                btnAddWord.style.display = "inline-block";
            }
        } else if (btnAddWord) {
            btnAddWord.style.display = "none";
        }
    }

    return {
        renderOwlSlider,
    };
}