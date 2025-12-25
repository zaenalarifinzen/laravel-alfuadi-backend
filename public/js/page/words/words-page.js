/* ==========================================================================
   File: griuping.js
   Description: Handles I'rob Input Page (Words)
   Dependencies: jQuery, OwlCarousel, Bootstrap Modal
   ========================================================================== */

// =============================
// CONSTANTS
// =============================
const WORDS_SYNC_URL = window.WORDS_SYNC_URL;
const CSRF_TOKEN = window.CSRF_TOKEN;

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
// GLOBAL ELEMENTS
// =============================
const surahOption = document.getElementById('surah-option');
const verseOption = document.getElementById('verse-option');
const filterForm = document.getElementById('filter-form');

const btnPrev = document.getElementById('btn-prev-verse');
const btnNext = document.getElementById('btn-next-verse');
const currentVerseLabel = document.getElementById('current-verse-label');

const currentSurahId = document.getElementById('surah-id');
const currentVerseNumber = document.getElementById('verse-number');
const currentVerseId = document.getElementById('verse-id');

let activeWordGroupId = null;
let verseCount = 0;

// =============================
// HELPERS
// =============================
function updateVerseCount() {
    const selected = surahOption.options[surahOption.selectedIndex];
    verseCount = selected ? selected.getAttribute('data-verse-count') : 0;
}

function showEditConfirmation() {
    return swal({
        icon: 'warning',
        title: 'Perubahan belum disimpan',
        text: 'Abaikan perubahan yang sudah ada?',
        buttons: {
            cancel: {
                text: 'Kembali',
                visible: true,
            },
            confirm: {
                text: 'Abaikan',
                visible: true,
                className: 'btn-success'
            }
        },
    });
}

function showLoading() {
    $('#loading-overlay').css({
        visibility: 'visible',
        opacity: '1'
    });
}

function hideLoading() {
    $('#loading-overlay').css({
        visibility: 'hidden',
        opacity: '0'
    });
}

// =============================
// FETCH WORDGROUPS
// =============================
function fetchWordGroups(surah_id, verse_number, verse_id) {
    let url;

    if (verse_id) {
        url = WORDGROUP_GET_URL.replace(':id', verse_id);
    } else if (surah_id && verse_number) {
        url = WORDGROUP_GET_URL.replace('/:id', `?surah_id=${surah_id}&verse_number=${verse_number}`);
    } else {
        alert('Parameter tidak lengkap');
        return;
    }

    $.ajax({
        url,
        type: "GET",
        success: function (response) {
            const verseId = response.data.verse.id;
            const storageKey = `wordgroups_${verseId}`;

            response.modified = false;
            removeRefreshButton();

            // Clear old cache
            Object.keys(localStorage)
                .filter(k => k.startsWith('wordgroups_'))
                .forEach(k => localStorage.removeItem(k));

            localStorage.setItem(storageKey, JSON.stringify(response));
            renderWordGroups(response);

            // Update URL in address bar
            history.pushState({}, '', `?verse_id=${verseId}`);
        },
        error: function () {
            alert('Terjadi kesalahan');
        }
    });
}

// =============================
// RENDER WORDGROUPS
// =============================
function renderWordGroups(response) {
    $slider.trigger('destroy.owl.carousel');
    $slider.html('');

    response.data.wordGroups.forEach(wordGroup => {
        $slider.append(`
      <div>
        <h4 class="arabic-text ar-title word-group" wg-id="${wordGroup.id}">${wordGroup.text}</h4>
      </div>`);
    });

    $slider.owlCarousel({ rtl: true, items: 1, dots: false, nav: false });

    currentSurahId.value = response.data.surah.id;
    currentVerseNumber.value = response.data.verse.number;
    currentVerseId.value = response.data.verse.id;

    surahOption.value = '';
    verseOption.value = '';

    currentVerseLabel.textContent = `${response.data.surah.id}. ${response.data.surah.name} - Ayat ${response.data.verse.number}`;
}

// =============================
// FETCH WORDS
// =============================
function fetchWords(word_group_id) {
    const tbodyWords = $("#sortable-table tbody");
    const tbodyWordsDetail = $("#detail-kalimat-table tbody");

    const key = Object.keys(localStorage).find(k => k.startsWith('wordgroups_'));
    if (!key) {
        const row = `
            <tr>
                <div class="spinner-border text-primary" role="status"></div>
                <span class="ml-2">Memuat...</span>
            </tr>
        `
        tbodyWords.html(row);
        tbodyWordsDetail.html(row);
        return;
    }

    const stored = JSON.parse(localStorage.getItem(key));
    const activeGroup = stored.data.wordGroups.find(wg => wg.id == word_group_id);

    if (!activeGroup || !activeGroup.words || activeGroup.words.length === 0) {
        const row = `
            <tr>
                <td colspan="5" class="text-center text-muted">Tidak ada data</td>
            </tr>
        `;

        tbodyWords.html(row);
        tbodyWordsDetail.html(row);
        return;
    }

    renderWordsTable(activeGroup);
    renderWordsDetails(activeGroup);
}

// =============================
// GET ACTIVE OWL ITEM
// =============================
function getActiveWgId(event) {
    const $active = $slider.find('.owl-item.active').first();
    const id = $active.find('.word-group').attr('wg-id');
    return id || null;
}

$slider.on('initialized.owl.carousel', e => {
    const id = getActiveWgId(e);
    if (id) fetchWords(id);
});

$slider.on('translated.owl.carousel', e => {
    const id = getActiveWgId(e);
    if (id) fetchWords(id);
});

// =============================
// NAVIGASI AYAT
// =============================
async function goToPrevVerse() {
    const modified = isModified()
    if (modified) {
        const confirmed = await showEditConfirmation();
        if (!confirmed) return;
    };

    let id = parseInt(currentVerseId.value);
    if (id > 1) fetchWordGroups(null, null, id - 1);
}

async function goToNextVerse() {
    const modified = isModified()
    if (modified) {
        const confirmed = await showEditConfirmation();
        if (!confirmed) return;
    };

    let id = parseInt(currentVerseId.value);
    if (id < 6236) fetchWordGroups(null, null, id + 1);
}

function addRefreshButton() {
    const cardHeader = document.getElementById('word');
    
    // buat tombol refresh
    const wrapper = document.createElement('div');
    wrapper.innerHTML = `
        <button class="btn btn-icon icon-left btn-info btn-lg" id="btn-reload-wordgroups">
            <i class="fa-solid fa-rotate"></i> Refresh
        </button>
    `;

    const refreshBtn = wrapper.querySelector('#btn-reload-wordgroups');

    // tambahkan event listener
    refreshBtn.addEventListener('click', async function (e) {
        e.preventDefault();

        const confirmed = await showEditConfirmation();
        if (!confirmed) return;

        fetchWordGroups(null, null, currentVerseId.value); 
    });

    const headerContainer = cardHeader.querySelector('.d-flex');
    if (headerContainer) {
        headerContainer.appendChild(wrapper);
    } else {
        cardHeader.appendChild(refreshBtn);
    }
}

function removeRefreshButton() {
    const btn = document.getElementById('btn-reload-wordgroups');

    if (btn) {
        btn.remove();
    }
}

// =============================
// EVENT LISTENERS
// =============================
async function searchVerse(e) {
    e.preventDefault();

    const modified = isModified()
    if (modified) {
        const confirmed = await showEditConfirmation();
        if (!confirmed) return;
    };

    fetchWordGroups(surahOption.value, verseOption.value);
}

surahOption.addEventListener('change', () => {
    updateVerseCount();
    verseOption.value = 1;
});

verseOption.addEventListener('change', () => {
    if (parseInt(verseOption.value) < 1) {
        verseOption.value = 1;
    }
    if (parseInt(verseOption.value) > verseCount)
        verseOption.value = verseCount;
});

filterForm.addEventListener('submit', searchVerse);
btnPrev.addEventListener('click', goToPrevVerse);
btnNext.addEventListener('click', goToNextVerse);

// =============================
// DOM
// =============================
document.addEventListener('DOMContentLoaded', () => {
    const initialVerseId = currentVerseId?.value;

    if (!initialVerseId) {
        console.warn('No initial verse ID found');
        return;
    }

    const cachedKey = getActiveStorageKey();
    const currentKey = `wordgroups_${initialVerseId}`;
    const cached = localStorage.getItem(cachedKey);

    // ------------------------------------------------------
    // 1. Jika TIDAK ADA cache sama sekali → fetch baru
    // ------------------------------------------------------
    if (!cached) {
        fetchWordGroups(null, null, initialVerseId);
        return;
    }

    // ------------------------------------------------------
    // 2. Jika ADA cache, tapi berbeda ayat → tampilkan modal restore
    // ------------------------------------------------------
    if (cachedKey !== currentKey) {
        const data = JSON.parse(cached);

        const lastProgressLabel =
            `${data.data.surah.name} - Ayat ${data.data.verse.number}`;

        const restoreUrl = `/words/create?verse_id=${data.data.verse.id}`;

        $('#last-location-label').text(lastProgressLabel);

        $('#btn-restore-continue')
            .off('click')
            .on('click', () => window.location.href = restoreUrl);

        $('#btn-restore-cancel')
            .off('click')
            .on('click', () => {
                $('#modal-restore').modal('hide');
                fetchWordGroups(null, null, initialVerseId);
            });

        $('#modal-restore').modal('show');
        return;
    }

    // ------------------------------------------------------
    // 3. Jika ADA cache dan sesuai ayat → restore langsung
    // ------------------------------------------------------
    const data = JSON.parse(cached);
    renderWordGroups(data);
    if (data.modified) {
        // add refresh button in card header
        addRefreshButton();

        iziToast.info({
            message: 'Data sebelumnya berhasil dipulihkan',
            position: 'bottomCenter'
        });
    }

    const firstGroup = data.data.wordGroups?.[0];
    if (firstGroup) fetchWords(firstGroup.id);
});


$(document).ajaxStart(showLoading);
$(document).ajaxStop(hideLoading);
