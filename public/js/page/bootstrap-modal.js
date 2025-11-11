"use strict";

$("#modal-1").fireModal({body: 'Modal body text goes here.'});
$("#modal-2").fireModal({body: 'Modal body text goes here.', center: true});

let modal_3_body = '<p>Object to create a button on the modal.</p><pre class="language-javascript"><code>';
modal_3_body += '[\n';
modal_3_body += ' {\n';
modal_3_body += "   text: 'Login',\n";
modal_3_body += "   submit: true,\n";
modal_3_body += "   class: 'btn btn-primary btn-shadow',\n";
modal_3_body += "   handler: function(modal) {\n";
modal_3_body += "     alert('Hello, you clicked me!');\n"
modal_3_body += "   }\n"
modal_3_body += ' }\n';
modal_3_body += ']';
modal_3_body += '</code></pre>';
$("#modal-3").fireModal({
  title: 'Modal with Buttons',
  body: modal_3_body,
  buttons: [
    {
      text: 'Click, me!',
      class: 'btn btn-primary btn-shadow',
      handler: function(modal) {
        alert('Hello, you clicked me!');
      }
    }
  ]
});

$("#modal-4").fireModal({
  footerClass: 'bg-whitesmoke',
  body: 'Add the <code>bg-whitesmoke</code> class to the <code>footerClass</code> option.',
  buttons: [
    {
      text: 'No Action!',
      class: 'btn btn-primary btn-shadow',
      handler: function(modal) {
      }
    }
  ]
});

$("#modal-5").fireModal({
  title: 'Login',
  body: $("#modal-login-part"),
  footerClass: 'bg-whitesmoke',
  autoFocus: false,
  onFormSubmit: function(modal, e, form) {
    // Form Data
    let form_data = $(e.target).serialize();
    console.log(form_data)

    // DO AJAX HERE
    let fake_ajax = setTimeout(function() {
      form.stopProgress();
      modal.find('.modal-body').prepend('<div class="alert alert-info">Please check your browser console</div>')

      clearInterval(fake_ajax);
    }, 1500);

    e.preventDefault();
  },
  shown: function(modal, form) {
    console.log(form)
  },
  buttons: [
    {
      text: 'Login',
      submit: true,
      class: 'btn btn-primary btn-shadow',
      handler: function(modal) {
      }
    }
  ]
});

$("#modal-6").fireModal({
  body: '<p>Now you can see something on the left side of the footer.</p>',
  created: function(modal) {
    modal.find('.modal-footer').prepend('<div class="mr-auto"><a href="#">I\'m a hyperlink!</a></div>');
  },
  buttons: [
    {
      text: 'No Action',
      submit: true,
      class: 'btn btn-primary btn-shadow',
      handler: function(modal) {
      }
    }
  ]
});

$('.oh-my-modal').fireModal({
  title: 'My Modal',
  body: 'This is cool plugin!'
});

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
    form.stopProgress();

    // Tutup modal
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

