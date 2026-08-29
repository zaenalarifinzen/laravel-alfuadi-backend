@props([
    'title' => 'Detail Kalimat',
    'tableId' => 'detail-kalimat-table',
])

<div class="card" data-detail-word-table>
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center w-100">
            <h4 class="mb-0">{{ $title }}</h4>
        </div>
    </div>
    <div class="table-sm">
        <table class="table-striped table" id="{{ $tableId }}">
            <thead>
                <tr class="text-center">
                    <th>Irob</th>
                    <th style="width:110px;">Lafadz</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="2" class="text-center text-muted">Tidak ada data</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
