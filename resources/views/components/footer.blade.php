<footer class="site-footer">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-md-6 mb-4">
                <a href="{{ route('home') }}" class="footer-brand">Al-<span>Fuadi</span></a>
                <p class="footer-desc">Platform belajar nahwu dan linguistik Al-Qur'an, dirancang agar setiap ayat bisa
                    dipahami strukturnya, bukan sekadar dibaca.</p>
                <div class="social-row">
                    <a href="https://www.instagram.com/ngajifuadi/" target="blank"><i
                            class="fa-brands fa-instagram"></i></a>
                    <a href="http://youtube.com/@ngajifuadi" target="blank"><i class="fa-brands fa-youtube"
                            target="blank"></i></a>
                    <a href="https://www.tiktok.com/@ngajialfuadi" target="blank"><i class="fa-brands fa-tiktok"
                            target="blank"></i></a>
                </div>
            </div>

            <div class="col-lg-2 col-md-6 col-6 mb-4">
                <h6>Produk</h6>
                <ul>
                    <li><a href="{{ route('quran.index') }}">Al-Qur'an</a></li>
                    <li><a href="#">Modul</a></li>
                </ul>
            </div>

            <div class="col-lg-2 col-md-6 col-6 mb-4">
                <h6>Lembaga</h6>
                <ul>
                    <li><a href="#">Tentang Kami</a></li>
                    <li><a href="#">Pelatihan</a></li>
                    <li><a href="#">Kontak</a></li>
                </ul>
            </div>

            <div class="col-lg-2 col-md-6 col-6 mb-4">
                <h6>Bantuan</h6>
                <ul>
                    <li><a href="#">FAQ</a></li>
                    <li><a href="#">Kebijakan Privasi</a></li>
                </ul>
            </div>

            <div class="col-lg-2 col-md-6 col-6 mb-4">
                <h6>Unduh Aplikasi</h6>
                <a href="https://play.google.com/store/apps/details?id=com.fuadi.quran" target="blank"
                    class="store-badge store-badge-img">
                    <img src="{{ asset('img/google-play-badge.png') }}" alt="Google Play Store" class="img-fluid">
                </a>
            </div>
        </div>

        <div class="footer-bottom">
            <div>© @php echo date('Y'); @endphp Al-Fuadi Learning Center</div>
            <div>Versi 1.2.5</div>
        </div>
    </div>
</footer>
