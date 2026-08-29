@props([
    'title' => 'Input Kalimat',
    'tableId' => 'sortable-table',
    'addButtonId' => 'btn-add-word',
])

<div class="card" data-input-word-table>
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center w-100">
            <h4 class="mb-0">{{ $title }}</h4>
            <button class="btn btn-icon icon-left btn-primary btn-lg" id="{{ $addButtonId }}" type="button">
                <i class="fa-solid fa-plus"></i> Tambah
            </button>
        </div>
    </div>

    <div class="table-responsive" style="direction: rtl;">
        <div class="table-sm">
            <table class="table-striped table" id="{{ $tableId }}">
                <thead>
                    <tr class="text-center">
                        <th class="col-action">Opsi</th>
                        <th class="col-word">Lafadz</th>
                        <th class="col-kalimat">Kalimat</th>
                        <th class="col-hukum">Hukum</th>
                        <th class="col-kategori">Kategori</th>
                        <th class="col-kedudukan">Kedudukan</th>
                        <th class="col-irob">I'rob</th>
                        <th class="col-tanda">Tanda</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="8" class="text-center text-muted">Tidak ada data</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
