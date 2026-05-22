// =============================
// OWL CAROUSEL INIT
// =============================
const $slider = $("#slider-rtl").owlCarousel({
    rtl: true,
    items: 1,
    dots: false,
    nav: false,
    loop: false,
});

$("#btn-next-slide").click(() => $slider.trigger("next.owl.carousel"));
$("#btn-prev-slide").click(() => $slider.trigger("prev.owl.carousel"));

// =============================
// GET ACTIVE SLIDE ITEM
// =============================
function getActiveSlideId(event) {
    const $active = $slider.find(".owl-item.active").first();
    const id = $active.find(".word-group").attr("wg-id");
    return id || null;
}

$slider.on("initialized.owl.carousel", (e) => {
    const id = getActiveSlideId(e);
    if (id) fetchWords(id);
});

$slider.on("translated.owl.carousel", (e) => {
    const id = getActiveSlideId(e);
    if (id) fetchWords(id);
});

// =============================
// RENDER WORDGROUPS SLIDER
// =============================
function renderOwlSlider(data) {
    // Remove existing event listeners to prevent duplicates
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

    // Re-add event listeners after initialization
    $slider.on("initialized.owl.carousel", (e) => {
        const id = getActiveSlideId(e);
        if (id) fetchWords(id);
    });

    $slider.on("translated.owl.carousel", (e) => {
        const id = getActiveSlideId(e);
        if (id) fetchWords(id);
    });

    currentSurahId.value = data.surah.id;
    currentVerseNumber.value = data.verse.number;
    currentVerseId.value = data.verse.id;

    surahOption.value = "";
    verseOption.value = "";

    const currentVerseLabel = document.getElementById("current-verse-label");
    currentVerseLabel.textContent = `${data.surah.id}. ${data.surah.name} - Ayat ${data.verse.number}`;

    // update wordgroup editor info
    const firstWordGroup = data?.wordGroups?.[0];
    const editorWgInfo = $(".editor-wordgroup a").contents().last()[0];
    const btnAddWord = document.getElementById("btn-add-word");

    if (firstWordGroup?.editor_info) {
        const editorName = firstWordGroup.editor_info.name;
        editorWgInfo.textContent = ` ${editorName}`;
        btnAddWord.style.display = "inline-block";
    } else {
        editorWgInfo.textContent = ` -`;
        btnAddWord.style.display = "none";
    }
}