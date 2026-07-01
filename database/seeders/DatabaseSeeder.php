<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $chatbotData = [
            [
                'category' => 'greeting',
                'keywords' => json_encode(['halo','hai','hello','hi','selamat','pagi','siang','sore','malam','hei','hey','assalamu','permisi','oi']),
                'response' => "Halo!  Selamat datang di Urban Kost Yogyakarta!\n\nKami siap membantu Anda mendapatkan informasi seputar:\n Tipe kamar & harga sewa\n Prosedur booking & reservasi\n Fasilitas yang tersedia\n Lokasi & kemudahan akses\n Metode pembayaran\n Peraturan & ketentuan hunian\n Program promo & diskon\n Layanan penghuni & penanganan keluhan\n\nSilakan ketikkan pertanyaan Anda atau pilih topik di atas. ",
                'priority' => 10,
            ],
            [
                'category' => 'booking',
                'keywords' => json_encode(['booking','pesan','reservasi','daftar','cara','proses','langkah','mendaftar','survey','survei','kunjungi','mau tinggal','mau sewa','ingin masuk','bisa sewa','advanced','advance','booking maju']),
                'response' => " Prosedur Booking Kamar Urban Kost:\n\nTerdapat dua alur pemesanan yang dapat Anda pilih sesuai ketersediaan kamar:\n\n━━━━━━━━━━━━━━━━━━━━\n BOOKING REGULER (Kamar Tersedia)\n━━━━━━━━━━━━━━━━━━━━\nDiperuntukkan bagi kamar yang sudah tersedia dan siap ditempati.\n\n1 Pilih kamar yang tersedia di website\n2 Isi formulir data diri secara lengkap\n3 Unggah dokumen identitas (KTP/Kartu Pelajar)\n4 Ajukan booking & tunggu verifikasi admin\n5 Admin memproses & memberikan konfirmasi\n6 Setelah disetujui → lakukan pembayaran penuh\n7 Konfirmasi pembayaran diterima → kamar resmi menjadi milik Anda\n\n━━━━━━━━━━━━━━━━━━━━\n BOOKING ADVANCED (Kamar Belum Tersedia)\n━━━━━━━━━━━━━━━━━━━━\nDiperuntukkan bagi kamar yang saat ini masih terisi namun akan segera kosong.\n\n1 Pilih kamar dengan status 'Advanced' di website\n2 Isi formulir data diri secara lengkap\n3 Unggah dokumen identitas (KTP/Kartu Pelajar)\n4 Ajukan booking & tunggu verifikasi admin\n5 Admin memproses & memberikan konfirmasi\n6 Setelah disetujui → lakukan pembayaran uang muka (DP)\n7 Invoice pelunasan akan diterbitkan secara otomatis ketika kamar telah tersedia\n8 Lakukan pelunasan sesuai invoice → kamar resmi menjadi milik Anda\n\n Catatan: Status kamar dapat berubah sewaktu-waktu. Segera ajukan booking untuk mengamankan kamar pilihan Anda.",
                'priority' => 9,
            ],
            [
                'category' => 'facilities',
                'keywords' => json_encode(['fasilitas','ada apa','tersedia','lengkap','listrik','air','wifi','internet','tv','kulkas','dapur','ac','kamar mandi','fitur','balkon','taman','parkir']),
                'response' => " Fasilitas Urban Kost:\n\n Fasilitas Dalam Kamar:\n• Kasur dengan spring bed yang nyaman\n• Kamar mandi dalam (private)\n• AC (pendingin ruangan)\n• Lemari pakaian\n• Meja belajar\n\n Fasilitas Area Bersama:\n• Taman hijau yang asri\n• Balkon untuk bersantai di area taman\n• Dapur bersama yang lengkap\n• Area parkir luas (motor & mobil)\n\n Konektivitas:\n• WiFi berkecepatan tinggi di seluruh area\n\n Semua fasilitas tersedia untuk seluruh penghuni tanpa biaya tambahan.",
                'priority' => 8,
            ],
            [
                'category' => 'price',
                'keywords' => json_encode(['harga','tarif','biaya','berapa','sewa','bayar','mahal','murah','budget','rate','cost','charge','iuran']),
                'response' => " Informasi Harga Sewa Urban Kost:\n\nUntuk informasi harga sewa terkini, silakan kunjungi halaman kamar di website kami atau hubungi admin melalui:\n\n WhatsApp: 0812-2930-2431\n Email: urbankost_jogja@gmail.com\n Instagram: @urbankost.jogja\n\nAdmin kami siap membantu memberikan penawaran terbaik sesuai kebutuhan Anda. ",
                'priority' => 9,
            ],
            [
                'category' => 'location',
                'keywords' => json_encode(['lokasi','alamat','dimana','letak','maps','jalan','transportasi','akses','rute','jauh','dekat','kampus','yogya','yogyakarta','bantul','banguntapan','plumbon','sokowaten']),
                'response' => " Lokasi Urban Kost:\n\nJln. Ontoseno, RT.3/RW.13, Sokowaten, Plumbon,\nBanguntapan, Bantul, Yogyakarta,\nDaerah Istimewa Yogyakarta 55198\n\n Temukan kami di Google Maps dengan mencari:\n\"Urban Kost Yogyakarta\"\n\n Lokasi kami berada di kawasan Banguntapan, Bantul — area yang strategis dan mudah dijangkau dari berbagai penjuru Yogyakarta.\n\nUntuk panduan arah lebih lanjut, jangan ragu menghubungi admin kami:\n WhatsApp: 0812-2930-2431",
                'priority' => 8,
            ],
            [
                'category' => 'contact',
                'keywords' => json_encode(['kontak','hubungi','telepon','whatsapp','wa','phone','call','chat','instagram','email','admin','pengelola','bantuan','nomor']),
                'response' => " Kontak Urban Kost:\n\n WhatsApp: 0812-2930-2431\n Email: urbankost_jogja@gmail.com\n Instagram: @urbankost.jogja\n\n Jam Operasional Admin:\nSenin – Jumat : 08.00 – 20.00 WIB\nSabtu – Minggu : 09.00 – 17.00 WIB\n\nKami siap membantu menjawab pertanyaan dan memberikan informasi terbaik untuk Anda. ",
                'priority' => 8,
            ],
            [
                'category' => 'payment',
                'keywords' => json_encode(['bayar','pembayaran','transfer','qris','payment','tunai','cash','dana','gopay','ovo','shopeepay','tagihan','invoice','rekening','metode','cara bayar','midtrans','virtual account','va']),
                'response' => " Metode Pembayaran Urban Kost:\n\nSeluruh transaksi pembayaran dilakukan secara digital melalui platform resmi website Urban Kost, menggunakan payment gateway Midtrans yang aman dan terpercaya.\n\n Metode yang Tersedia:\n\n QRIS\n• Dapat dibayar melalui semua aplikasi dompet digital\n• (GoPay, OVO, Dana, ShopeePay, dan lainnya)\n\n Virtual Account\n• Transfer via berbagai bank nasional\n• Pembayaran dapat dilakukan melalui ATM, mobile banking, maupun internet banking\n\n Alur Pembayaran:\n1. Lakukan booking melalui website\n2. Admin melakukan verifikasi & konfirmasi\n3. Invoice pembayaran akan diterbitkan secara otomatis\n4. Pilih metode pembayaran yang tersedia\n5. Lakukan pembayaran sebelum batas waktu yang tertera\n6. Konfirmasi pembayaran dikirimkan secara otomatis\n\n Seluruh transaksi tercatat dan aman melalui sistem. Tidak ada pembayaran tunai di luar platform.",
                'priority' => 8,
            ],
            [
                'category' => 'rules',
                'keywords' => json_encode(['peraturan','rules','tata tertib','aturan','larangan','boleh','dilarang','tidak boleh','ketentuan','kebijakan','policy','syarat','gerbang','tamu laki']),
                'response' => " Peraturan & Tata Tertib Urban Kost:\n\n Ketentuan Umum:\n• Setiap penghuni wajib mematuhi seluruh peraturan yang berlaku\n• Pastikan selalu mengunci gerbang setiap kali keluar maupun masuk area kost\n• Jaga kebersihan dan ketertiban lingkungan bersama\n\n Ketentuan Tamu:\n• Tamu laki-laki hanya diperkenankan berada hingga area balkon/taman — tidak diizinkan masuk ke dalam bangunan kost\n• Tamu wajib meninggalkan area kost sesuai batas waktu yang ditentukan\n• Dilarang keras membawa tamu untuk menginap tanpa izin tertulis dari pengelola\n\n Larangan:\n• Dilarang merokok di dalam kamar maupun area dalam bangunan\n• Dilarang melakukan segala bentuk kegiatan yang melanggar hukum di lingkungan kost\n• Dilarang membuat keributan atau gangguan yang mengganggu kenyamanan penghuni lain\n\n Pelanggaran terhadap peraturan di atas dapat berakibat pada teguran tertulis hingga pemutusan kontrak sewa.",
                'priority' => 7,
            ],
            [
                'category' => 'available',
                'keywords' => json_encode(['kamar kosong','tersedia','available','masih ada','ada kamar','cek kamar','lihat kamar','pilihan kamar','sisa kamar','cek ketersediaan','sudah penuh','kapasitas']),
                'response' => " Ketersediaan Kamar Urban Kost:\n\nInformasi ketersediaan kamar dapat dilihat secara langsung di halaman utama website kami.\n\n Keterangan Status Kamar:\n Tersedia → Kamar siap dipesan dan ditempati\n Dipesan → Kamar sedang dalam proses konfirmasi\n Terisi → Kamar sedang ditempati oleh penghuni aktif\n Advanced → Kamar terisi namun dapat dipesan terlebih dahulu untuk periode berikutnya\n\n Ketersediaan kamar dapat berubah sewaktu-waktu.\nSegera lakukan booking untuk mengamankan kamar pilihan Anda. \n\n Pertanyaan lebih lanjut:\nWhatsApp: 0812-2930-2431",
                'priority' => 8,
            ],
            [
                'category' => 'wifi',
                'keywords' => json_encode(['wifi','internet','koneksi','speed','kecepatan','bandwidth','jaringan','signal','sinyal','streaming','gaming','fiber']),
                'response' => " Fasilitas WiFi Urban Kost:\n\nUrban Kost menyediakan akses internet WiFi berkecepatan tinggi yang dapat dinikmati di seluruh area kost.\n\n Aktif 24 jam / 7 hari\n Tanpa batas kuota (unlimited)\n Mencakup seluruh area kost\n\n Informasi SSID dan password jaringan akan diberikan pada saat proses check-in.\n\n Apabila mengalami gangguan koneksi, silakan hubungi admin:\nWhatsApp: 0812-2930-2431",
                'priority' => 8,
            ],
            [
                'category' => 'parking',
                'keywords' => json_encode(['parkir','motor','mobil','kendaraan','garasi','sepeda','helm','area parkir','parkiran']),
                'response' => " Fasilitas Parkir Urban Kost:\n\n Parkir GRATIS untuk seluruh penghuni\n Tersedia untuk kendaraan roda dua (motor)\n Tersedia untuk kendaraan roda empat (mobil)\n\n Keamanan Area Parkir:\n• Area parkir yang luas dan memadai\n• Dilengkapi CCTV di sekitar area parkir dan lingkungan kost\n• Akses terbatas hanya untuk penghuni\n\n Fasilitas parkir merupakan bagian dari hunian dan tidak dikenakan biaya tambahan.",
                'priority' => 7,
            ],
            [
                'category' => 'kitchen',
                'keywords' => json_encode(['dapur','masak','kompor','memasak','makan','makanan','kulkas','microwave','peralatan masak','rice cooker','dispenser']),
                'response' => " Dapur Bersama Urban Kost:\n\nUrban Kost menyediakan fasilitas dapur bersama yang dapat digunakan oleh seluruh penghuni.\n\n Peralatan yang Tersedia:\n• Kompor gas\n• Peralatan memasak (wajan, panci, dll.)\n• Peralatan makan\n\n Tata Tertib Penggunaan Dapur:\n• Bersihkan peralatan setelah digunakan\n• Jaga kebersihan dan kerapian area dapur bersama\n• Gunakan fasilitas dengan bertanggung jawab\n\nUntuk informasi lebih lengkap mengenai fasilitas, silakan hubungi admin kami.",
                'priority' => 7,
            ],
            [
                'category' => 'garden_balcony',
                'keywords' => json_encode(['taman','balkon','santai','outdoor','hijau','area luar','bersantai','teras']),
                'response' => " Taman & Balkon Urban Kost:\n\nUrban Kost dilengkapi dengan fasilitas area luar yang nyaman untuk bersantai.\n\n Taman:\n• Area taman yang asri dan terawat\n• Suasana yang tenang dan nyaman\n\n Balkon:\n• Area balkon untuk bersantai di dekat taman\n• Tersedia sebagai ruang relaksasi bagi penghuni\n\n Catatan Penting:\nTamu laki-laki hanya diperkenankan berada hingga area balkon/taman dan tidak diizinkan masuk ke dalam bangunan kost.\n\nNikmati suasana kost yang nyaman dan asri bersama kami! ",
                'priority' => 6,
            ],
            [
                'category' => 'contract',
                'keywords' => json_encode(['kontrak','perjanjian','masa sewa','durasi','minimal','sewa berapa lama','lama sewa','ktp','dokumen','syarat masuk','syarat daftar']),
                'response' => " Ketentuan Kontrak Sewa Urban Kost:\n\n Durasi Sewa:\n• Masa sewa minimum: 1 bulan\n• Tersedia pilihan: 1, 3, 6, hingga 12 bulan\n\n Dokumen yang Diperlukan:\n• KTP / SIM (wajib)\n• Kartu Pelajar / KTM (untuk mahasiswa)\n• Foto selfie dengan KTP\n\n Kontrak sewa ditandatangani secara digital melalui dashboard website.\n\n Pertanyaan lebih lanjut:\nWhatsApp: 0812-2930-2431\nEmail: urbankost_jogja@gmail.com",
                'priority' => 8,
            ],
            [
                'category' => 'deposit',
                'keywords' => json_encode(['deposit','uang jaminan','dp','down payment','uang muka','jaminan','refund deposit','kembalikan uang']),
                'response' => " Ketentuan Deposit & Uang Jaminan Urban Kost:\n\n Deposit dikenakan sesuai dengan tipe kamar yang dipilih.\n\n Deposit dikembalikan secara penuh apabila:\n• Kamar diserahkan dalam kondisi baik dan terawat\n• Tidak terdapat tunggakan pembayaran\n• Proses check-out dilakukan sesuai jadwal yang telah disepakati\n\n Deposit dapat dikenakan pemotongan apabila terdapat kerusakan fasilitas atau tunggakan yang belum diselesaikan.\n\n Proses pengembalian deposit: 7 hari kerja setelah selesai check-out.\n\n Informasi lebih lanjut:\nWhatsApp: 0812-2930-2431",
                'priority' => 8,
            ],
            [
                'category' => 'checkin_checkout',
                'keywords' => json_encode(['check in','check out','checkin','checkout','masuk','keluar','pindah','kapan bisa masuk','hari pertama','serah terima','kunci','bawa apa']),
                'response' => " Prosedur Check-in & Check-out Urban Kost:\n\n CHECK-IN:\n• Siapkan KTP asli dan bukti pembayaran yang sah\n• Proses serah terima kunci dan kartu akses dilakukan oleh admin\n• Pastikan seluruh dokumen telah diverifikasi sebelumnya\n\n CHECK-OUT:\n• Sampaikan pemberitahuan kepada admin minimal H-14 sebelum tanggal check-out\n• Kembalikan seluruh kunci dan kartu akses kepada admin\n• Pastikan kamar dalam kondisi bersih dan rapi\n• Deposit akan diproses dan dikembalikan dalam 7 hari kerja setelah check-out\n\n Pengajuan check-out dapat dilakukan melalui dashboard website.\n\n Pertanyaan lebih lanjut:\nWhatsApp: 0812-2930-2431",
                'priority' => 7,
            ],
            [
                'category' => 'complaint',
                'keywords' => json_encode(['keluhan','komplain','rusak','kerusakan','perbaikan','lapor','tiket','trouble','masalah','bocor','mati lampu','ac mati','kran','toilet','pompa air']),
                'response' => " Prosedur Pengajuan Keluhan & Perbaikan Urban Kost:\n\n Cara Melaporkan Keluhan:\n1. Login ke dashboard website Urban Kost\n2. Pilih menu 'Ajukan Tiket' atau 'Laporan Keluhan'\n3. Pilih kategori permasalahan yang sesuai\n4. Isi deskripsi masalah secara lengkap dan sertakan foto apabila diperlukan\n5. Submit laporan — admin akan merespons sesegera mungkin\n\n Untuk keperluan mendesak, hubungi kami langsung:\nWhatsApp: 0812-2930-2431\n\n Seluruh perbaikan atas kerusakan yang terjadi secara normal (bukan akibat kelalaian penghuni) tidak dikenakan biaya tambahan.",
                'priority' => 7,
            ],
            [
                'category' => 'security',
                'keywords' => json_encode(['keamanan','aman','cctv','security','satpam','maling','kehilangan','gembok','kunci hilang','safe','secure']),
                'response' => " Sistem Keamanan Urban Kost:\n\nUrban Kost berkomitmen menjaga keamanan dan kenyamanan seluruh penghuni.\n\n CCTV terpasang di area parkir dan sekitar lingkungan kost\n Gerbang utama wajib dikunci setiap saat\n Akses masuk menggunakan kunci khusus penghuni\n\n Tata Tertib Keamanan:\n• Setiap penghuni wajib mengunci gerbang setiap kali keluar maupun masuk\n• Tamu wajib melapor dan tidak diperkenankan masuk melewati area yang ditentukan\n\n Kontak Darurat:\nWhatsApp admin: 0812-2930-2431\n\n Keamanan lingkungan adalah tanggung jawab bersama. Mari jaga ketertiban demi kenyamanan seluruh penghuni.",
                'priority' => 7,
            ],
            [
                'category' => 'guests',
                'keywords' => json_encode(['tamu','pengunjung','kunjungan','mampir','menginap','tamu menginap','pacar','orang tua','teman berkunjung','tamu laki','laki-laki']),
                'response' => " Ketentuan Tamu Urban Kost:\n\n Tamu Diperbolehkan:\n• Berkunjung pada jam yang telah ditentukan\n• Tamu perempuan dapat masuk ke area dalam kost\n• Tamu laki-laki hanya diperkenankan berada di area balkon dan taman — tidak diizinkan memasuki bangunan kost\n\n Dilarang:\n• Membawa tamu untuk menginap tanpa izin tertulis dari pengelola\n• Tamu berada di luar batas area yang telah ditentukan\n\n Permohonan tamu menginap (misal: kunjungan keluarga) dapat diajukan kepada admin dengan pemberitahuan minimal H-2 sebelumnya.\n\n Informasi lebih lanjut:\nWhatsApp: 0812-2930-2431",
                'priority' => 6,
            ],
            [
                'category' => 'smoking',
                'keywords' => json_encode(['rokok','merokok','smoking','smoke','vape','vaping','asap rokok','area merokok']),
                'response' => " Kebijakan Merokok Urban Kost:\n\n Merokok Dilarang di:\n• Dalam kamar\n• Seluruh area dalam bangunan kost (koridor, dapur, ruang bersama, dll.)\n\n Pelanggaran terhadap ketentuan ini dapat berdampak pada pemotongan deposit dan/atau tindakan lebih lanjut sesuai peraturan yang berlaku.\n\n Vape dan rokok elektrik termasuk dalam kategori yang sama dengan rokok konvensional.\n\nMohon untuk mematuhi ketentuan ini demi kenyamanan bersama seluruh penghuni. ",
                'priority' => 6,
            ],
            [
                'category' => 'pets',
                'keywords' => json_encode(['hewan','peliharaan','kucing','anjing','hamster','burung','ikan','pets','binatang','bawa kucing','bawa anjing']),
                'response' => " Kebijakan Hewan Peliharaan Urban Kost:\n\n Hewan Peliharaan Tidak Diperkenankan\n\nUrban Kost menerapkan kebijakan bebas hewan peliharaan (No Pets Policy) demi:\n• Menjaga kebersihan dan higienitas lingkungan bersama\n• Menghormati kenyamanan penghuni lain, termasuk yang memiliki alergi\n\n Semua jenis hewan peliharaan tidak diizinkan berada di area kost, termasuk namun tidak terbatas pada: kucing, anjing, hamster, burung, dan reptil.\n\n Pelanggaran terhadap ketentuan ini dapat berakibat pada:\n1. Surat peringatan resmi\n2. Pengenaan denda\n3. Pemutusan kontrak sewa\n\nKami mohon kerja sama Anda untuk mematuhi ketentuan ini. ",
                'priority' => 6,
            ],
            [
                'category' => 'cancellation',
                'keywords' => json_encode(['batal','pembatalan','cancel','refund','uang kembali','tidak jadi','batalkan','tidak jadi booking']),
                'response' => " Kebijakan Pembatalan Booking Urban Kost:\n\n Ketentuan Refund berdasarkan waktu pembatalan:\n• Lebih dari 14 hari sebelum check-in → Refund 90%\n• 7 – 14 hari sebelum check-in → Refund 50%\n• 3 – 7 hari sebelum check-in → Refund 25%\n• Kurang dari 3 hari sebelum check-in → Tidak ada refund\n\n Penjadwalan Ulang (Reschedule):\n• Dapat dilakukan 1× secara gratis, maksimal H-7 sebelum tanggal check-in\n\n Proses refund akan diselesaikan dalam 5–7 hari kerja ke rekening yang terdaftar.\n\n Cara mengajukan pembatalan:\nLogin Dashboard → Booking Saya → Batalkan Booking\n\n Bantuan lebih lanjut:\nWhatsApp: 0812-2930-2431\nEmail: urbankost_jogja@gmail.com",
                'priority' => 7,
            ],
            [
                'category' => 'promo',
                'keywords' => json_encode(['promo','diskon','discount','potongan','cashback','gratis','bonus','hemat','special','penawaran','ada promo']),
                'response' => " Program Promo & Diskon Urban Kost:\n\nUrban Kost menyediakan berbagai penawaran menarik untuk penghuni kami:\n\n Pantau promo terbaru kami melalui:\n Instagram: @urbankost.jogja\n\nAtau hubungi admin untuk informasi penawaran terkini:\n WhatsApp: 0812-2930-2431\n Email: urbankost_jogja@gmail.com\n\nJangan lewatkan penawaran spesial yang tersedia! ",
                'priority' => 8,
            ],
            [
                'category' => 'thanks',
                'keywords' => json_encode(['terima kasih','makasih','thanks','thank you','thx','tq','oke','ok','baik','siap','noted','mantap','keren','bagus']),
                'response' => " Terima kasih! Kami senang dapat membantu Anda.\n\nApabila ada pertanyaan lain seputar Urban Kost, jangan ragu untuk menghubungi kami kembali.\n\nHubungi kami melalui:\n WhatsApp: 0812-2930-2431\n Email: urbankost_jogja@gmail.com\n Instagram: @urbankost.jogja\n\nSemoga hari Anda menyenangkan! ",
                'priority' => 6,
            ],
            [
                'category' => 'goodbye',
                'keywords' => json_encode(['bye','dadah','sampai jumpa','selamat tinggal','pamit','cabut','keluar','tutup','cukup']),
                'response' => " Terima kasih telah menghubungi Urban Kost. Sampai jumpa!\n\nKami selalu siap membantu kapan pun Anda membutuhkan informasi.\n\n Urban Kost Yogyakarta\n Jln. Ontoseno, RT.3/RW.13, Sokowaten, Plumbon, Banguntapan, Bantul, Yogyakarta 55198\n 0812-2930-2431\n urbankost_jogja@gmail.com\n Instagram: @urbankost.jogja\n\nSemoga Anda segera menemukan hunian yang nyaman dan sesuai kebutuhan! ",
                'priority' => 6,
            ],
            [
                'category' => 'default',
                'keywords' => json_encode([]),
                'response' => "Mohon maaf, kami kurang memahami pertanyaan Anda. \n\nSilakan tanyakan seputar topik berikut:\n Harga & tipe kamar\n Prosedur booking\n Fasilitas hunian\n Lokasi & akses\n Metode pembayaran\n Peraturan & ketentuan\n Program promo & diskon\n Keluhan & perbaikan\n\nAtau hubungi kami langsung:\n WhatsApp: 0812-2930-2431\n Email: urbankost_jogja@gmail.com\n Instagram: @urbankost.jogja",
                'priority' => 0,
            ],
        ];

        DB::table('chatbot_responses')->truncate();
        foreach ($chatbotData as $item) {
            DB::table('chatbot_responses')->insert(array_merge($item, [
                'is_active'  => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }

        $this->command->info(' Chatbot responses seeded: ' . count($chatbotData) . ' kategori.');
    }
}