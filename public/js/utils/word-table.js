// =============================
// RENDER WORDS TABLE
// =============================
function renderWordsTable(wordGroup) {
    const tbody = $("#sortable-table tbody");
    tbody.empty();

    if (!wordGroup || !wordGroup.words || wordGroup.words.length === 0) {
        tbody.append(`
            <tr>
                <td colspan="8" class="text-center text-muted">Tidak ada data</td>
            </tr>
        `);

        const editorInfo = $(".editor-kalimat a").contents().last()[0];
        if (editorInfo) {
            editorInfo.textContent = ` -`;
        }
        return;
    }

    // sort word based on order_number
    wordGroup.words.sort(
        (a, b) => (a.order_number || 0) - (b.order_number || 0),
    );

    wordGroup.words.forEach((word) => {
        let simbolClass = "text-dark";
        if (word.color === "red") simbolClass = "text-huruf";
        else if (word.color === "green") simbolClass = "text-fiil";
        else if (word.color === "blue") simbolClass = "text-isim";

        const isAnswerMode = wordGroupsPrefix === "answer_user_";
        const actionButtons = isAnswerMode
            ? `<button class="btn btn-sm btn-icon btn-warning word-edit" title="Edit">Edit 
                   <i class="fa-solid fa-edit"></i>
               </button>`
            : `<button class="btn btn-sm btn-icon btn-warning word-edit" title="Edit">
                   <i class="fa-solid fa-edit"></i>
               </button>
               <button class="btn btn-sm btn-icon btn-danger word-delete" title="Hapus">
                   <i class="fa-solid fa-trash"></i>
               </button>
               <button class="btn btn-sm btn-icon btn-primary btn-move-up" title="Naikkan">
                   <i class="fa-solid fa-arrow-up"></i>
               </button>
               <button class="btn btn-sm btn-icon btn-primary btn-move-down" title="Turunkan">
                   <i class="fa-solid fa-arrow-down"></i>
               </button>`;

        const row = `
            <tr>
            <td class="align-middle col-action">
                    <div class="d-flex justify-content-center action-buttons">
                        ${actionButtons}
                    </div>
                </td>
                <td class="text-center align-middle col-word">
                    <div class="${simbolClass} arabic-text words" id="${word.id}">${word.text}</div>
                </td>
                <td class="text-center align-middle col-kalimat">
                    <div class="text-center mb-2 arabic-text ar-symbol">${
                        word.kalimat ?? ""
                    }</div>
                </td>
                <td class="text-center align-middle col-hukum">
                    <div class="text-center mb-2 arabic-text ar-symbol">${
                        word.hukum ?? ""
                    }</div>
                </td>
                <td class="text-center align-middle col-kategori">
                    <div class="text-center mb-2 arabic-text ar-symbol">${
                        word.kategori ?? ""
                    }</div>
                </td>
                <td class="text-center align-middle col-kedudukan">
                    <div class="text-center mb-2 arabic-text ar-symbol">${
                        word.kedudukan ?? ""
                    }</div>
                </td>
                <td class="text-center align-middle col-irob">
                    <div class="text-center mb-2 arabic-text ar-symbol">${
                        word.irob ?? ""
                    }</div>
                </td>
                <td class="text-center align-middle col-tanda">
                    <div class="text-center mb-2 arabic-text ar-symbol">${
                        word.tanda ?? ""
                    }</div>
                </td>
            </tr>
        `;
        tbody.append(row);
    });

    const firstWord = wordGroup.words[0];
    const editorName = firstWord.editor_info
        ? firstWord.editor_info.name
        : " -";

    const editorInfo = $(".editor-kalimat a").contents().last()[0];
    
    if (editorInfo) {
        editorInfo.textContent = ` ${editorName}`;
    }

    const modified = isModified(wordGroupsPrefix);
    if (modified) {
        $("#btn-save-all").show();
    } else {
        $("#btn-save-all").hide();
    }

    applyComparisonHighlights();
}

// =============================
// RENDER WORDS DETAILS
// =============================
function renderWordsDetails(wordGroup) {
    const tbody = $("#detail-kalimat-table tbody");
    tbody.empty();

    if (!wordGroup || !wordGroup.words || wordGroup.words.length === 0) {
        console.log("Data tidak tersedia");
        tbody.append(`
            <tr>
                <td colspan="8" class="text-center text-muted">Tidak ada data</td>
            </tr>
        `);
        return;
    }

    // sort word based on order_number
    wordGroup.words.sort(
        (a, b) => (a.order_number || 0) - (b.order_number || 0),
    );

    wordGroup.words.forEach((word) => {
        let simbolClass = "text-dark";
        if (word.color === "red") simbolClass = "text-huruf";
        else if (word.color === "green") simbolClass = "text-fiil";
        else if (word.color === "blue") simbolClass = "text-isim";

        const parts = [
            word.kalimat,
            word.hukum,
            word.kategori,
            word.kedudukan,
            word.irob,
            word.tanda,
        ]
            .filter(
                (p) => p !== null && p !== undefined && String(p).trim() !== "",
            )
            .join(" - ");

        const row = `
            <tr class="text-center kalimat-detail-row">
                <td>
                    <div class="text-right arabic-text ar-subtitle">
                        ${parts}
                    </div>
                </td>
                <td class="text-center align-middle word" id="${word.id}">
                       <div class="${simbolClass} arabic-text words">
                           ${word.text}
                       </div>
                          <div class="text-center ${simbolClass} arabic-text ar-symbol-mini">
                            ${word.simbol ?? ""}
                        </div>
                       <div class="translation">
                           ${word.translation ?? ""}
                       </div>
                   </td>
             </tr>
             
        `;
        tbody.append(row);
    });
}

function addRefreshButton() {
    const cardHeader = document.getElementById("word");

    // refresh button
    const wrapper = document.createElement("div");
    wrapper.innerHTML = `
        <button class="btn btn-icon icon-left btn-info btn-lg" id="btn-reload-wordgroups">
            <i class="fa-solid fa-rotate"></i> Refresh
        </button>
    `;

    const refreshBtn = wrapper.querySelector("#btn-reload-wordgroups");

    // event listener
    refreshBtn.addEventListener("click", async function (e) {
        e.preventDefault();

        const confirmed = await showEditConfirmation();
        if (!confirmed) return;

        fetchWordGroups(null, null, currentVerseId.value);
    });

    const headerContainer = cardHeader.querySelector(".d-flex");
    if (headerContainer) {
        headerContainer.appendChild(wrapper);
    } else {
        cardHeader.appendChild(refreshBtn);
    }
}

function removeRefreshButton() {
    const btn = document.getElementById("btn-reload-wordgroups");

    if (btn) {
        btn.remove();
    }
}

function updateCard(label, type) {
    const cardHeader = document.getElementById('input-table-header');
    const headerContainer = cardHeader.querySelector(".d-flex");

    const parent = cardHeader.parentElement;
    parent.classList.add(`card-${type}`)
    
    const bagdeWrapper = document.createElement('div');
    bagdeWrapper.innerHTML = `
        <span class="badge badge-${type}"><i class="fas fa-check mr-1"></i>${label}</span>
    `;

    if (headerContainer) {
        headerContainer.appendChild(bagdeWrapper);
    }
}

function resetCard() {
    const cardHeader = document.getElementById('input-table-header');
    const headerContainer = cardHeader.querySelector(".d-flex");

    const parent = cardHeader.parentElement;
    parent.classList.remove(`card-success`, `card-danger`, `card-warning`, `card-info`);
    
    const bagdeWrapper = headerContainer.querySelector(".badge");

    if (bagdeWrapper) {
        bagdeWrapper.remove();
    }

    // update submit button
    changeSubmitButton('btn-submit-answer', 'Submit', 'primary');
}