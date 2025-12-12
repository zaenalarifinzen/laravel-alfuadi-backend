document.addEventListener('DOMContentLoaded', async () => {
    // --- 1. Load data JSON ---
    const response = await fetch('/json/data-nahwu.json');
    const data = await response.json();    

    // --- 2. Ambil elemen form ---
    const kalimatSelect   = document.getElementById('input-kalimat');
    const kategoriSelect  = document.getElementById('input-kategori');
    const hukumSelect     = document.getElementById('input-hukum');
    const irobSelect      = document.getElementById('input-irob');
    const alamatSelect    = document.getElementById('input-alamat');
    const kedudukanSelect = document.getElementById('input-kedudukan');
    const simbolSelect    = document.getElementById('input-simbol');

    // --- 3. Saat Kalimat dipilih, isi dropdown Kategori ---
    kalimatSelect.addEventListener('change', () => {        
        const selectedKalimat = kalimatSelect.value.trim();

        const filteredKategori = data.kategori.filter(k => k.id_kalimat === selectedKalimat);
        kategoriSelect.innerHTML = '<option selected disabled>Pilih Kategori</option>';
        filteredKategori.forEach(k => {
            kategoriSelect.innerHTML += `<option value="${k.id}">${k.kategori_ar}</option>`;
        });

        const filteredKedudukan = data.kedudukan.filter(k => k.id_kalimat === selectedKalimat);
        kedudukanSelect.innerHTML = '<option selected disabled>Pilih Kedudukan</option>';
        filteredKedudukan.forEach(k => {
            kedudukanSelect.innerHTML += `<option value="${k.id}">${k.kedudukan_ar}</option>`;
        });
    });

    // --- 4. Saat Kategori dipilih, isi Hukum otomatis ---
    kategoriSelect.addEventListener('change', () => {
        const kategori = data.kategori.find(k => k.id == kategoriSelect.value);
        if (!kategori) return;

        // Isi hukum
        console.log(kategori.hukum);
        
        hukumSelect.innerHTML = `<option selected>${kategori.hukum}</option>`;
    });

    // --- 5. Saat I‘rob dipilih, isi Tanda I‘rob ---
    irobSelect.addEventListener('change', () => {
        const kategori = data.kategori.find(k => k.id == kategoriSelect.value);
        if (!kategori) return;

        let tanda = '';
        const irob = irobSelect.value.trim();
        switch (irob) {
            case 'رَفْعٌ': tanda = kategori.rofa; break;
            case 'نَصْبٌ': tanda = kategori.nashob; break;
            case 'جَرٌّ': tanda = kategori.jar; break;
            case 'جَزْمٌ': tanda = kategori['jazm ']; break;
        }
        alamatSelect.innerHTML = tanda ? `<option selected>${tanda}</option>` : '<option selected></option>';
    });

    // --- 6. Saat Kedudukan dipilih, isi simbol otomatis ---
    kedudukanSelect.addEventListener('change', () => {
        const selectedKedudukan = kedudukanSelect.value;
        const kd = data.kedudukan.find(k => k.id == selectedKedudukan);
        if (!kd) return;

        simbolSelect.innerHTML = `<option selected>${kd.simbol}</option>`;
        irobSelect.innerHTML = `<option selected>${kd.irob}</option>`;
    });

    // --- 7. Validasi dasar sebelum submit ---
    const form = document.getElementById('form-add-word');
    form.addEventListener('submit', (e) => {
        const kalimat = kalimatSelect.value;
        const kategori = kategoriSelect.value;
        if (!kalimat || !kategori) {
            e.preventDefault();
            alert('Kalimat dan kategori wajib diisi!');
        }
    });
});
