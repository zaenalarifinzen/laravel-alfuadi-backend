"use strict";

$("#modal-word-part").fireModal({
  title: 'Tambah kalimah',
  body: $("#modal-add-word"),
  footerClass: 'bg-whitesmoke',
  autoFocus: false,
  onFormSubmit: function(modal, e, form) {
    e.preventDefault();

    const lafadz = $("#inputLafadz").val();
    const translation = $("#inputTranslation").val();
    const kalimah = $("#inputKalimah").val();
    const hukum = $("#inputHukum").val();
    const mabniDetail = $("#inputMabniDetail").val();
    const category = $("#inputCategory").val();
    const kedudukan = $("#inputMahal").val();
    const irob = $("#inputIrob").val();
    const alamat = $("#inputAlamat").val();
    const condition = $("#inputCondition").val();
    const matbu = $("#inputMatbu").val();

    // Tambahkan ke tabel
    const tbody = $("#sortable-table tbody");
    const badgeClass =
      kalimah === "فعل" ? "badge-success" :
      kalimah === "اسم" ? "badge-info" :
      kalimah === "حرف" ? "badge-danger" : "badge-light";

    const newRow = `
      <tr class="text-center align-middle">
        <td><div class="sort-handler"><i class="fa-solid fa-grip"></i></div></td>
        <td class="text-center">
          <div class="arabic-text words">${lafadz}</div>
          <div class="table-links">
            <a href="#" class="detail">Detail</a>
            <div class="bullet"></div>
            <a href="#" class="edit">Edit</a>
            <div class="bullet"></div>
            <a href="#" class="text-danger remove">Hapus</a>
          </div>
        </td>
        <td>${translation}</td>
        <td><div class="badge ${badgeClass}">${kalimah}</div></td>
        <td class="arabic-text words">${kedudukan}</td>
        <td hidden class="extra-data"
            data-hukum="${hukum}"
            data-mabni="${mabniDetail}"
            data-category="${category}"
            data-irob="${irob}"
            data-alamat="${alamat}"
            data-condition="${condition}"
            data-matbu="${matbu}">
        </td>
      </tr>
    `;
    tbody.append(newRow);

    // saveTableToStorage();

    form.stopProgress();
    $(this)[0].reset();
    modal.modal('hide');
  },
  shown: function(modal, form) {
    console.log(form)
  },
  buttons: [
    {
      text: 'Tambahkan',
      submit: true,
      class: 'btn btn-primary btn-shadow',
      handler: function(modal) {
      }
    }
  ],
});

// Hapus baris
$(document).on('click', '.remove', function(e) {
  e.preventDefault();
  $(this).closest('tr').remove();
});

// function saveTableToStorage() {
//   const rows = [];
//   $("#sortable-table tbody tr").each(function() {
//     const lafadz = $(this).find(".arabic-text.words").text().trim();
//     const translation = $(this).find("td:nth-child(3)").text().trim();
//     const kalimah = $(this).find(".badge").text().trim();
//     const kedudukan = $(this).find("td:nth-child(5)").text().trim();
//     const extra = $(this).find(".extra-data").data();
//     rows.push({ lafadz, translation, kalimah, kedudukan, ...extra });
//   });
//   localStorage.setItem("pendingWords", JSON.stringify(rows));
// }