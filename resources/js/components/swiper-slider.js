// =============================
// SWIPER SLIDER
// =============================

import Swiper from "swiper";
import { Navigation } from "swiper/modules";
import "swiper/css";
import "swiper/css/navigation";

export function initSwiperSlider({
    fetchWords,
    elements = {},
    root = document,
    eventName = "wordgroup:changed",
    onWordGroupChange,
}) {
    const sliderElement = root.querySelector?.("[data-wordgroup-slider]") ?? root.querySelector?.("#slider-rtl");
    const nextButton = root.querySelector?.("[data-wordgroup-prev]") ?? root.querySelector?.("#btn-next-slide");
    const previousButton = root.querySelector?.("[data-wordgroup-next]") ?? root.querySelector?.("#btn-prev-slide");

    if (!sliderElement) return null;

    const swiper = new Swiper(sliderElement, {
        modules: [Navigation], 
        rtl: true,
        slidesPerView: "auto",
        centeredSlides: true,
        slideToClickedSlide: true,
        spaceBetween: 8,
        speed: 300,
        navigation: {
            nextEl: nextButton,
            prevEl: previousButton,
        },
    });

    function getActiveSlideId() {
        const activeSlide = swiper.slides?.[swiper.activeIndex];
        if (!activeSlide) return null;
        const id = activeSlide.querySelector("[data-wordgroup-id]")?.dataset.wordgroupId
            ?? $(activeSlide).find(".word-group").attr("wg-id");
        return id || null;
    }

    function notifyActiveSlide() {
        const id = getActiveSlideId();
        if (!id) return null;

        onWordGroupChange?.(id);
        fetchWords?.(id);
        root.dispatchEvent?.(
            new CustomEvent(eventName, {
                detail: { wordgroupId: id },
                bubbles: true,
            }),
        );
        return id;
    }

    function bindActiveSlideEvents() {
        swiper.on("slideChange", () => {
            notifyActiveSlide();
        });
    }

    bindActiveSlideEvents();
    notifyActiveSlide();

    function renderSwiperSlider(data) {           
        const wrapper = sliderElement.querySelector(".swiper-wrapper");
        if (!wrapper) return;

        wrapper.innerHTML = "";

        const wordGroups = data.wordGroups || [];
        const label = data.title ? data.title : `${data.surah.id}. ${data.surah.name} - Ayat ${data.verse.number}`;

        wordGroups.forEach((wordGroup) => {
            const slide = document.createElement("div");
            const wordGroupElement = document.createElement("h4");

            slide.className = "swiper-slide";
            wordGroupElement.className = "arabic-text ar-title word-group";
            wordGroupElement.dataset.wordgroupId = wordGroup.id;
            wordGroupElement.setAttribute("wg-id", wordGroup.id);
            wordGroupElement.textContent = wordGroup.text;
            slide.appendChild(wordGroupElement);
            wrapper.appendChild(slide);
        });

        swiper.update();
        swiper.slideTo(0, 0); 

        notifyActiveSlide();

        if (elements.currentVerseId) elements.currentVerseId.value = data.verse?.id ?? "";

        if (elements.surahOption) elements.surahOption.value = "";
        if (elements.verseOption) elements.verseOption.value = "";

        const currentWordGroupLabel =
            elements.currentWordGroupLabel ?? root.querySelector?.("[data-wordgroup-title]");
        if (currentWordGroupLabel) currentWordGroupLabel.textContent = label;

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
        renderSwiperSlider,
        getActiveWordGroupId: getActiveSlideId,
    };
}