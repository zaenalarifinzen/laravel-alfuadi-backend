<div class="modal fade" id="modal-add-word" tabindex="-1" role="dialog" aria-labelledby="modalAddWordLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="form-add-word-label">Tambah Kalimat</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form id="form-add-word">
                <div class="modal-body">
                    <input type="hidden" id="input-id">
                    <input type="hidden" id="input-order-number">
                    <div class="form-row">
                        <div class="form-group col-12">
                            <input type="text" class="form-control arabic-text ar-title input-big text-center"
                                id="input-lafadz" placeholder="لفظ">
                        </div>
                        <div class="form-group col-12">
                            <input type="text" class="form-control text-center" id="input-translation"
                                placeholder="terjemah">
                        </div>
                    </div>
                    <div id="additional-fields" style="display: none;">
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="input-kalimat">Kalimat</label>
                                <select id="input-kalimat" class="custom-dropdown" name="kalimat" required></select>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="input-hukum">Hukum</label>
                                <select id="input-hukum" class="custom-dropdown" name="hukum"></select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="input-kategori">Kategori / Alasan mabni</label>
                                <select id="input-kategori" class="custom-dropdown" name="kategori"></select>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="input-kedudukan">Kedudukan</label>
                                <select id="input-kedudukan" class="custom-dropdown" name="kedudukan"></select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="input-irob">I'rob</label>
                                <select id="input-irob" class="custom-dropdown" name="irob"></select>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="input-tanda">Tanda i'rob</label>
                                <select id="input-tanda" class="custom-dropdown" name="tanda"></select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="input-simbol">Simbol</label>
                                <select id="input-simbol" class="custom-dropdown" name="simbol"></select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-lg" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary btn-lg" id="btn-submit">Tambahkan</button>
                </div>
            </form>
        </div>
    </div>
</div>
