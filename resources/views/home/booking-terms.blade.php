<style>
.terms-card {
    border: 1.5px solid var(--neutral-200);
    border-radius: var(--radius-md);
    margin-bottom: 16px;
    overflow: hidden;
}
.terms-card.terms-error { border-color: var(--red); }

.terms-card-head {
    background: linear-gradient(135deg, var(--green-900), var(--green-700));
    padding: 14px 18px;
    display: flex; align-items: center; gap: 10px;
    font-family: 'Instrument Sans', sans-serif;
    font-size: .78rem; font-weight: 700; color: #fff;
}
.terms-card-head i { font-size: .95rem; color: var(--green-300); }

.terms-body {
    max-height: 320px; overflow-y: auto;
    padding: 18px 20px; background: var(--neutral-0);
    font-size: .76rem; line-height: 1.75; color: var(--neutral-600);
}
.terms-body::-webkit-scrollbar { width: 4px; }
.terms-body::-webkit-scrollbar-thumb { background: var(--green-300); border-radius: 4px; }

.terms-article { margin-bottom: 18px; }
.terms-article:last-child { margin-bottom: 0; }
.terms-article-title {
    font-size: .7rem; font-weight: 700; letter-spacing: .08em;
    text-transform: uppercase; color: var(--neutral-800);
    margin-bottom: 8px; padding-bottom: 6px;
    border-bottom: 1px solid var(--neutral-100);
}
.terms-article ol { padding-left: 1.2rem; }
.terms-article ol li { margin-bottom: 5px; }
.terms-article ul { padding-left: 1.2rem; list-style: disc; }
.terms-article ul li { margin-bottom: 5px; }

.terms-scroll-hint {
    padding: 8px 18px; background: var(--neutral-50);
    border-top: 1px solid var(--neutral-100);
    font-size: .65rem; color: var(--neutral-400);
    display: flex; align-items: center; gap: 6px;
}
.terms-scroll-hint i { color: var(--green-400); }

.terms-agree-row {
    padding: 14px 18px; background: var(--green-50);
    border-top: 1.5px solid var(--green-200);
    display: flex; align-items: flex-start; gap: 12px;
}
.terms-agree-cb {
    width: 17px; height: 17px; flex-shrink: 0;
    accent-color: var(--green-500); cursor: pointer;
    margin-top: 2px;
}
.terms-agree-label {
    font-size: .78rem; font-weight: 600; color: var(--neutral-700);
    line-height: 1.5; cursor: pointer;
}
.terms-agree-label span { color: var(--green-600); }
</style>

<div class="terms-card {{ isset($termsHasError) && $termsHasError ? 'terms-error' : '' }}">

    <div class="terms-card-head">
        <i class="bi bi-shield-lock-fill"></i>
        <span>Syarat &amp; Ketentuan Sewa Kamar – Urban Kost</span>
    </div>

    <div class="terms-body" id="termsBody">

        <div class="terms-article">
            <div class="terms-article-title">Pasal 1 — Kewajiban Pembayaran</div>
            <ol>
                <li>Pembayaran sewa wajib dilunasi sesuai invoice yang diterbitkan pada tanggal jatuh tempo yang tertera.</li>
                <li>Keterlambatan pembayaran melewati tanggal jatuh tempo dikenakan <strong>denda sebesar 2% per minggu</strong> dari total tagihan yang belum terbayar, dihitung sejak hari pertama keterlambatan.</li>
                @if($isAdvance)
                <li>Untuk Advance Booking, Penyewa wajib membayar <strong>Uang Muka (DP) sebesar ±30%</strong> dari total sewa dalam waktu <strong>3×24 jam</strong> setelah permohonan disetujui sebagai pengunci posisi waiting list.</li>
                @endif
                <li>Urban Kost berhak mencabut hak hunian apabila pembayaran tidak dilakukan tanpa konfirmasi dalam batas waktu yang telah ditetapkan.</li>
            </ol>
        </div>

        <div class="terms-article">
            <div class="terms-article-title">Pasal 2 — Larangan Pembatalan &amp; Keluar Sepihak</div>
            <ol>
                <li>Setelah pembayaran pertama diproses dan Penyewa telah menghuni kamar, <strong>tidak ada pembatalan atau pengunduran diri sepihak yang diizinkan</strong>.</li>
                <li>Penyewa yang meninggalkan kamar sebelum masa sewa berakhir tanpa persetujuan tertulis dari Urban Kost tetap berkewajiban melunasi <strong>seluruh sisa tagihan</strong> periode sewa yang berjalan.</li>
                <li>Pemberitahuan tidak memperpanjang sewa wajib disampaikan secara tertulis minimal <strong>10 (sepuluh) hari kalender</strong> sebelum masa sewa berakhir.</li>
                <li>Penyewa yang tidak hadir pada tanggal check-in yang disepakati tanpa pemberitahuan apapun dianggap membatalkan booking, dan seluruh pembayaran yang telah diterima tidak akan dikembalikan.</li>
            </ol>
        </div>

        <div class="terms-article">
            <div class="terms-article-title">Pasal 3 — Ketentuan Refund &amp; Waiting List</div>
            <ol>
                <li>Seluruh pembayaran yang telah dilakukan bersifat <strong>tidak dapat dikembalikan (non-refundable)</strong>.</li>
                <li>Satu-satunya jalur refund hanya berlaku untuk pembatalan pada status <strong>Waiting List</strong>, sebelum kamar ditetapkan kepada Penyewa.</li>
                <li>Refund waiting list dikenakan <strong>denda 10% dari nilai DP</strong>. Sisa DP dikembalikan dalam <strong>7 hari kerja</strong>.</li>
                <li>Pembatalan waiting list sebelum DP dibayar tidak dikenakan denda apapun.</li>
            </ol>
        </div>

        <div class="terms-article">
            <div class="terms-article-title">Pasal 4 — Tata Tertib &amp; Larangan Hunian</div>
            <ol>
                <li>Tamu lawan jenis <strong>hanya diizinkan di area ruang tunggu dan taman</strong>. Dilarang memasuki koridor kamar atau area privat.</li>
                <li>Dilarang membawa siapapun menginap di kamar tanpa izin tertulis dari Urban Kost. Pelanggaran dikenakan denda dan dapat berujung pada pemutusan sewa sepihak.</li>
                <li>Jam malam berlaku pukul <strong>22.00 – 05.00 WIB</strong>. Selama jam malam, Penyewa wajib menjaga ketenangan.</li>
                <li>Dilarang membawa, menyimpan, atau mengonsumsi minuman beralkohol, narkotika, dan zat berbahaya lainnya di seluruh area Urban Kost.</li>
                <li>Dilarang membawa hewan peliharaan dalam bentuk apapun ke dalam area Urban Kost.</li>
                <li>Penyewa wajib menjaga kebersihan kamar dan fasilitas bersama. Kerusakan akibat kelalaian wajib diganti sepenuhnya.</li>
                <li>Dilarang mengubah, merusak, atau memasang instalasi tanpa izin tertulis dari Urban Kost.</li>
            </ol>
        </div>

        <div class="terms-article">
            <div class="terms-article-title">Pasal 5 — Sanksi &amp; Ketentuan Akhir</div>
            <ol>
                <li>Pelanggaran memberikan hak kepada Urban Kost untuk memberikan teguran, menjatuhkan denda, hingga <strong>mengeluarkan Penyewa dari hunian secara sepihak</strong> tanpa kewajiban mengembalikan pembayaran.</li>
                <li>Pemalsuan data identitas merupakan tindak pidana dan dapat diproses sesuai hukum yang berlaku.</li>
                <li>Segala perselisihan diselesaikan melalui musyawarah antara Penyewa dan pihak Urban Kost.</li>
                <li>Urban Kost berhak mengubah ketentuan ini dengan pemberitahuan yang wajar. Perubahan tidak berlaku surut.</li>
                <li>Dengan menyetujui formulir ini, Penyewa dianggap telah membaca, memahami, dan terikat atas seluruh ketentuan di atas.</li>
            </ol>
        </div>

    </div>

    <div class="terms-scroll-hint" id="termsScrollHint">
        <i class="bi bi-arrow-down-circle"></i>
        <span>Gulir ke bawah untuk membaca seluruh syarat &amp; ketentuan</span>
    </div>

    <div class="terms-agree-row">
        <input type="checkbox" name="agree_terms" id="agreeTerms"
               class="terms-agree-cb" value="1"
               {{ old('agree_terms') ? 'checked' : '' }}>
        <label for="agreeTerms" class="terms-agree-label">
            Saya telah membaca, memahami, dan <span>menyetujui seluruh Syarat &amp; Ketentuan</span>
            di atas. Saya bersedia mematuhi semua ketentuan yang berlaku selama masa sewa.
        </label>
    </div>

</div>

@if(isset($termsHasError) && $termsHasError)
<span class="fld-err" style="display:block;margin-top:6px;margin-bottom:4px">
    <i class="bi bi-exclamation-circle"></i>
    Anda wajib menyetujui Syarat &amp; Ketentuan untuk melanjutkan.
</span>
@endif