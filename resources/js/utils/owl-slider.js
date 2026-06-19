// =============================
// OWL CAROUSEL INIT
// =============================
export function initOwlSlider({ fetchWords, elements }) {
    const $slider = $("#slider-rtl").owlCarousel({
        rtl: true,
        items: 1,
        dots: false,
        nav: false,
        loop: false,
    });

    $("#btn-next-slide").click(() => $slider.trigger("next.owl.carousel"));
    $("#btn-prev-slide").click(() => $slider.trigger("prev.owl.carousel"));

    function getActiveSlideId() {
        const $active = $slider.find(".owl-item.active").first();
        const id = $active.find(".word-group").attr("wg-id");
        return id || null;
    }

    function bindActiveSlideEvents() {
        $slider.on("initialized.owl.carousel", () => {
            const id = getActiveSlideId();
            if (id) fetchWords(id);
        });

        $slider.on("translated.owl.carousel", () => {
            const id = getActiveSlideId();
            if (id) fetchWords(id);
        });
    }

    bindActiveSlideEvents();

    function renderOwlSlider(data) {
        $slider.off("initialized.owl.carousel");
        $slider.off("translated.owl.carousel");

        $slider.trigger("destroy.owl.carousel");
        $slider.html("");

        data.wordGroups.forEach((wordGroup) => {
            $slider.append(`
      <div>
        <h4 class="arabic-text ar-title word-group" wg-id="${wordGroup.id}">${wordGroup.text}</h4>
      </div>`);
        });

        $slider.owlCarousel({ rtl: true, items: 1, dots: false, nav: false });
        bindActiveSlideEvents();

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
