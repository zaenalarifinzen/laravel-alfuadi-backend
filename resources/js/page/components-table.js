"use strict";

export function initComponentsTable({ getPrefix, markModified, renderWordsDetails }) {
    $("[data-checkboxes]").each(function () {
        const me = $(this);
        const group = me.data("checkboxes");
        const role = me.data("checkbox-role");

        me.change(function () {
            const all = $(
                `[data-checkboxes="${group}"]:not([data-checkbox-role="dad"])`,
            );
            const checked = $(
                `[data-checkboxes="${group}"]:not([data-checkbox-role="dad"]):checked`,
            );
            const dad = $(
                `[data-checkboxes="${group}"][data-checkbox-role="dad"]`,
            );
            const total = all.length;
            const checkedLength = checked.length;

            if (role == "dad") {
                all.prop("checked", me.is(":checked"));
            } else {
                dad.prop("checked", checkedLength >= total);
            }
        });
    });

    $(document).on("click", ".btn-move-up, .btn-move-down", function () {
        const $row = $(this).closest("tr");
        const isUp = $(this).hasClass("btn-move-up");

        if (isUp) {
            const $prev = $row.prev("tr");
            if ($prev.length) $row.insertBefore($prev);
            else return;
        } else {
            const $next = $row.next("tr");
            if ($next.length) $row.insertAfter($next);
            else return;
        }

        saveNewOrder();
        markModified(getPrefix());
        $("#btn-save-all").show();
    });

    function saveNewOrder() {
        const prefix = getPrefix();
        const currentKey = Object.keys(localStorage).find((k) =>
            k.startsWith(prefix),
        );
        const stored = JSON.parse(localStorage.getItem(currentKey));

        const activeWordGroupId = $(".owl-item.active .word-group").attr("wg-id");
        const activeWordGroup = stored.wordGroups.find(
            (wg) => wg.id == activeWordGroupId,
        );

        const groupIndex = stored.wordGroups.findIndex(
            (g) => g.id == activeWordGroupId,
        );
        if (groupIndex === -1) return;

        const words = stored.wordGroups[groupIndex].words;

        $("#sortable-table tbody tr").each(function (index) {
            const wordId = $(this).find(".words").attr("id");
            const word = words.find((w) => w.id == wordId);
            if (word) {
                word.order_number = index + 1;
            }
        });

        localStorage.setItem(currentKey, JSON.stringify(stored));
        renderWordsDetails(activeWordGroup);
    }
}
