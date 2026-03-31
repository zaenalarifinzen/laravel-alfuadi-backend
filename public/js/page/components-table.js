"use strict";

$("[data-checkboxes]").each(function() {
  var me = $(this),
    group = me.data('checkboxes'),
    role = me.data('checkbox-role');

  me.change(function() {
    var all = $('[data-checkboxes="' + group + '"]:not([data-checkbox-role="dad"])'),
      checked = $('[data-checkboxes="' + group + '"]:not([data-checkbox-role="dad"]):checked'),
      dad = $('[data-checkboxes="' + group + '"][data-checkbox-role="dad"]'),
      total = all.length,
      checked_length = checked.length;

    if(role == 'dad') {
      if(me.is(':checked')) {
        all.prop('checked', true);
      }else{
        all.prop('checked', false);
      }
    }else{
      if(checked_length >= total) {
        dad.prop('checked', true);
      }else{
        dad.prop('checked', false);
      }
    }
  });
});

// Move word up/down
$(document).on('click', '.btn-move-up, .btn-move-down', function () {
    const $row = $(this).closest('tr');
    const isUp = $(this).hasClass('btn-move-up');

    if (isUp) {
      const $prev = $row.prev('tr');
      if ($prev.length) $row.insertBefore($prev);
      else return;
    } else {
      const $next = $row.next('tr');
      if ($next.length) $row.insertAfter($next);
      else return;
    }

    saveNewOrder();
    markModified();
    $("#btn-save-all").show();
})

function saveNewOrder() {
    // get key local storage
    const currentKey = Object.keys(localStorage).find(k => k.startsWith('wordgroups_'));
    const stored = JSON.parse(localStorage.getItem(currentKey));

    // get active wordgroup id
    const activeWordGroupId = $('.owl-item.active .word-group').attr('wg-id');

    const groupIndex = stored.data.wordGroups.findIndex(g => g.id == activeWordGroupId);
    if (groupIndex === -1) return;

    const words = stored.data.wordGroups[groupIndex].words;

    // loop each row to get word ID based new order
    $('#sortable-table tbody tr').each(function (index) {
      const wordId = $(this).find('.words').attr('id');

      // update order number in local
      const w = words.find(w => w.id == wordId);
      if (w) {
        w.order_number = index + 1;
      }
    });

    // re save to local
    localStorage.setItem(currentKey, JSON.stringify(stored));
  }