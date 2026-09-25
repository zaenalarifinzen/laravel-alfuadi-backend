/* ==========================================================================
   Answer Comparator — membandingkan jawaban user vs kunci jawaban,
   dan menerapkan highlight hasil perbandingan ke tabel di DOM.
   Semua fungsi stateless: hasil compare disimpan oleh pemanggil
   (analysis-page.js), bukan di modul ini.
   ========================================================================== */

const COMPARED_FIELDS = [
    "kalimat",
    "hukum",
    "kategori",
    "kedudukan",
    "irob",
    "tanda",
];

const COLUMN_CLASS_BY_FIELD = {
    kalimat: ".col-kalimat",
    hukum: ".col-hukum",
    kategori: ".col-kategori",
    kedudukan: ".col-kedudukan",
    irob: ".col-irob",
    tanda: ".col-tanda",
};

export function compareAnswers(prefix) {
    const exerciseRaw = localStorage.getItem(prefix);

    if (!exerciseRaw) {
        console.warn("Answer key atau user answer tidak ditemukan");
        return [];
    }

    const exerciseData = JSON.parse(exerciseRaw);
    const correctAnswer = exerciseData.wordGroups;
    const userAnswer = exerciseData.userAnswer;
    const result = [];

    correctAnswer.forEach((keyGroup) => {
        const userGroup = userAnswer.find((g) => g.id === keyGroup.id);
        if (!userGroup) return;

        keyGroup.words.forEach((keyWord) => {
            const userWord = userGroup.words.find(
                (w) => String(w.id) === String(keyWord.id),
            );

            if (!userWord) return;

            const fieldsResult = COMPARED_FIELDS.map((field) => {
                const expected = String(keyWord[field] ?? "").trim();
                const actual = String(userWord[field] ?? "").trim();
                const isHinted = Boolean(userWord.hints && userWord.hints[field]);
                return {
                    field,
                    expected,
                    actual,
                    correct: expected === actual,
                    isHinted,
                };
            });

            result.push({
                wordId: keyWord.id,
                text: keyWord.text,
                correct: fieldsResult.every((f) => f.correct),
                fields: fieldsResult,
            });
        });
    });

    return result;
}

export function clearComparisonHighlights() {
    document.querySelectorAll("#sortable-table tbody tr").forEach((tr) => {
        tr.classList.remove("is-wrong", "is-correct");
        tr.querySelectorAll("td").forEach((td) => {
            td.classList.remove("is-wrong");
            td.querySelectorAll(".btn-cell-hint").forEach((btn) => btn.remove());
        });
    });
}

export function highlightErrors(compareResult) {
    clearComparisonHighlights();

    compareResult.forEach((item) => {
        const row = document.querySelector(
            `#sortable-table tbody tr div.words[id="${item.wordId}"]`,
        );
        if (!row) return;

        const tr = row.closest("tr");
        if (!tr) return;

        if (item.correct) {
            tr.classList.remove("is-wrong");
            tr.classList.add("is-correct");
            return;
        }

        tr.classList.remove("is-correct");
        tr.classList.add("is-wrong");

        item.fields.forEach((fieldResult) => {
            const colClass = COLUMN_CLASS_BY_FIELD[fieldResult.field];
            if (!colClass) return;

            const td = tr.querySelector(colClass);
            if (!td) return;

            if (fieldResult.correct) {
                if (fieldResult.isHinted) {
                    td.classList.add("is-hinted");
                }
                return;
            }

            td.classList.add("is-wrong");

            // Append lightbulb hint button if not present
            if (!td.querySelector(".btn-cell-hint")) {
                const hintBtn = document.createElement("button");
                hintBtn.type = "button";
                hintBtn.className = "btn-cell-hint";
                hintBtn.setAttribute("data-word-id", item.wordId);
                hintBtn.setAttribute("data-field", fieldResult.field);
                hintBtn.setAttribute("data-expected", fieldResult.expected);
                hintBtn.title = `Buka bantuan untuk ${fieldResult.field}`;
                hintBtn.innerHTML = '<i class="fas fa-circle-question"></i>';
                td.appendChild(hintBtn);
            }
        });
    });
}