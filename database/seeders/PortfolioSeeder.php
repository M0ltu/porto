<?php

namespace Database\Seeders;

use App\Models\Certificate;
use App\Models\Experience;
use App\Models\Message;
use App\Models\Profile;
use App\Models\Project;
use App\Models\ProjectImage;
use App\Models\Setting;
use App\Models\Skill;
use App\Models\Social;
use App\Models\User;
use App\Support\Portfolio;
use Illuminate\Database\Seeder;
use RuntimeException;

class PortfolioSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedAdmin();

        if (Profile::query()->exists()) {
            return;
        }

        $this->seedProfile();
        $this->seedSocials();
        $this->seedSkills();
        $this->seedProjects();
        $this->seedCertificates();
        $this->seedExperiences();
        $this->seedSettings();

        if (! app()->isProduction()) {
            $this->seedMessages();
        }
    }

    private function seedAdmin(): void
    {
        $password = config('portfolio.admin.password');

        if (blank($password)) {
            if (app()->isProduction()) {
                throw new RuntimeException('Set ADMIN_PASSWORD before seeding in production.');
            }

            $password = 'password';
        }

        User::query()->updateOrCreate(
            ['email' => config('portfolio.admin.email')],
            ['name' => 'Admin Portfolio', 'password' => $password, 'email_verified_at' => now()],
        );
    }

    private function seedProfile(): void
    {
        Profile::query()->create([
            'name' => 'Muhammad David Yusuf',
            'nickname' => 'David',
            'school_class' => 'XII PPLG B',
            'major' => 'Software and Game Development',
            'headline' => 'Membangun antarmuka web dan game kecil yang rapi, cepat, dan menyenangkan dipakai.',
            'bio' => 'Siswa XII PPLG B dengan minat besar di pengembangan web dan game. Terbiasa bekerja dengan Laravel, JavaScript, dan desain antarmuka yang aksesibel. Saya suka menerjemahkan masalah nyata menjadi produk yang sederhana dan bisa dipakai.',
            'location' => 'Indonesia',
            'email' => 'muhammaddavidyusuf@example.com',
            'availability' => 'Terbuka untuk kolaborasi, dan proyek kecil',
            'avatar_path' => '/images/demo/avatar.svg',
            'cv_path' => null,
            'education' => [
                [
                    'period' => '2023 — Sekarang',
                    'title' => 'XII PPLG B — Software and Game Development',
                    'organization' => 'SMK',
                    'description' => 'Fokus pada pemrograman web, basis data, dan dasar pengembangan game. Aktif mengerjakan proyek sekolah dan mandiri.',
                ],
                [
                    'period' => '2020 — 2023',
                    'title' => 'Sekolah Menengah Pertama',
                    'organization' => 'SMP',
                    'description' => 'Mulai tertarik pada komputer, logika pemrograman, dan desain antarmuka.',
                ],
            ],
            'interests' => ['Laravel', 'Blade & Tailwind', 'Game Design', 'UI/UX', 'Basis Data', 'Aksesibilitas Web'],
            'goals' => 'Menjadi software engineer yang bisa membangun produk web dan game bermanfaat, sambil terus belajar desain antarmuka yang inklusif.',
            'values' => [
                ['title' => 'Sederhana dulu', 'description' => 'Solusi paling kecil yang benar lebih baik daripada fitur besar yang rapuh.'],
                ['title' => 'Rapi dan konsisten', 'description' => 'Struktur kode dan desain yang konsisten memudahkan orang lain ikut merawatnya.'],
                ['title' => 'Bisa dipakai semua orang', 'description' => 'Aksesibilitas dan performa bukan tambahan, tapi bagian dari kualitas.'],
            ],
        ]);
    }

    private function seedSocials(): void
    {
        foreach ([
            ['label' => 'Email', 'icon' => 'mail', 'href' => 'mailto:muhammaddavidyusuf@example.com', 'external' => false],
            ['label' => 'GitHub', 'icon' => 'github', 'href' => 'https://github.com/', 'external' => true],
            ['label' => 'Instagram', 'icon' => 'instagram', 'href' => 'https://instagram.com/', 'external' => true],
            ['label' => 'LinkedIn', 'icon' => 'linkedin', 'href' => 'https://linkedin.com/', 'external' => true],
        ] as $index => $social) {
            Social::query()->create($social + ['sort_order' => $index]);
        }
    }

    private function seedSkills(): void
    {
        $skills = [
            'hard' => [
                ['name' => 'Laravel & PHP', 'value' => 82, 'note' => 'Routing, Eloquent, Blade, validasi'],
                ['name' => 'HTML & CSS', 'value' => 88, 'note' => 'Semantik, responsif, aksesibilitas'],
                ['name' => 'Tailwind CSS', 'value' => 84, 'note' => 'Design token dan komponen'],
                ['name' => 'JavaScript', 'value' => 74, 'note' => 'DOM, Alpine.js, fetch API'],
                ['name' => 'Basis Data', 'value' => 70, 'note' => 'Desain tabel dan query dasar'],
                ['name' => 'Game Development', 'value' => 62, 'note' => 'Prototipe 2D dan game loop'],
            ],
            'soft' => [
                ['name' => 'Komunikasi', 'value' => 85, 'note' => 'Presentasi dan dokumentasi'],
                ['name' => 'Manajemen waktu', 'value' => 78, 'note' => 'Prioritas tugas dan deadline'],
                ['name' => 'Kerja tim', 'value' => 84, 'note' => 'Kolaborasi proyek kelompok'],
                ['name' => 'Problem solving', 'value' => 80, 'note' => 'Debugging dan eksplorasi solusi'],
                ['name' => 'Kemandirian belajar', 'value' => 88, 'note' => 'Belajar dari dokumentasi'],
                ['name' => 'Detail', 'value' => 76, 'note' => 'Kualitas tampilan dan kode'],
            ],
        ];

        $order = 0;

        foreach ($skills as $type => $items) {
            foreach ($items as $item) {
                Skill::query()->create($item + ['type' => $type, 'sort_order' => $order++]);
            }
        }
    }

    private function seedProjects(): void
    {
        $projects = [
            [
                'slug' => 'jurnal-plk-digital',
                'title' => 'Jurnal PLK Digital',
                'category' => 'Web App',
                'year' => '2026',
                'featured' => true,
                'summary' => 'Aplikasi pencatatan kegiatan PLK yang menggantikan jurnal kertas menjadi catatan digital yang mudah dicari.',
                'cover_path' => '/images/demo/project-1.svg',
                'gallery' => ['/images/demo/project-1.svg', '/images/demo/project-3.svg', '/images/demo/project-5.svg'],
                'tech' => ['Laravel', 'Blade', 'Tailwind CSS', 'SQLite'],
                'role' => 'Full-stack developer & UI designer',
                'problem' => 'Jurnal PLK masih ditulis di buku sehingga rekap kegiatan dan pencarian catatan lama memakan waktu.',
                'goal' => 'Menyediakan jurnal digital yang cepat diisi, mudah dicari, dan bisa dicetak untuk laporan akhir.',
                'features' => [
                    'Catatan harian dengan tanggal dan foto kegiatan',
                    'Pencarian dan filter berdasarkan rentang tanggal',
                    'Rekap otomatis untuk laporan pembimbing',
                    'Ekspor halaman jurnal ke PDF lewat browser',
                ],
                'process' => 'Wawancara singkat dengan pembimbing, pemetaan alur pencatatan, lalu membangun prototipe antarmuka sebelum masuk ke implementasi Laravel dan pengujian di perangkat sekolah.',
                'feedback' => 'Pembimbing merasa rekap jurnal lebih cepat dibuat, dan siswa tidak lagi lupa membawa buku catatan.',
                'metrics' => [
                    ['label' => 'Waktu rekap', 'value' => '±70% lebih cepat'],
                    ['label' => 'Pengguna uji', 'value' => '18 siswa'],
                    ['label' => 'Durasi', 'value' => '3 minggu'],
                ],
                'demo_url' => null,
                'repo_url' => 'https://github.com/',
            ],
            [
                'slug' => 'pixel-kasir',
                'title' => 'Pixel Kasir',
                'category' => 'Game',
                'year' => '2026',
                'featured' => true,
                'summary' => 'Game simulasi kasir kecil untuk melatih kecepatan hitung dan manajemen antrean.',
                'cover_path' => '/images/demo/project-2.svg',
                'gallery' => ['/images/demo/project-2.svg', '/images/demo/project-6.svg'],
                'tech' => ['JavaScript', 'Canvas', 'Game Loop'],
                'role' => 'Game programmer & level designer',
                'problem' => 'Belajar matematika dasar terasa membosankan bagi sebagian siswa, terutama latihan hitung cepat.',
                'goal' => 'Membuat latihan hitung yang terasa seperti bermain, dengan umpan balik langsung.',
                'features' => [
                    'Loop permainan dengan waktu dan skor',
                    'Tiga tingkat kesulitan',
                    'Umpan balik visual saat salah hitung',
                    'Papan skor lokal',
                ],
                'process' => 'Membuat prototipe satu level, menguji ke teman sekelas, lalu memperbaiki ritme permainan dan kejelasan antarmuka sebelum menambah level.',
                'feedback' => 'Teman sekelas meminta mode tambahan karena level awal terasa terlalu mudah setelah beberapa kali main.',
                'metrics' => [
                    ['label' => 'Level', 'value' => '3 tingkat'],
                    ['label' => 'Penguji', 'value' => '9 orang'],
                    ['label' => 'Durasi', 'value' => '2 minggu'],
                ],
                'demo_url' => null,
                'repo_url' => 'https://github.com/',
            ],
            [
                'slug' => 'papan-tugas-kelas',
                'title' => 'Papan Tugas Kelas',
                'category' => 'Dashboard',
                'year' => '2025',
                'featured' => true,
                'summary' => 'Papan tugas bersama untuk kelas: siapa mengerjakan apa, kapan tenggatnya, dan status terbarunya.',
                'cover_path' => '/images/demo/project-3.svg',
                'gallery' => ['/images/demo/project-3.svg', '/images/demo/project-4.svg'],
                'tech' => ['Laravel', 'Alpine.js', 'Tailwind CSS'],
                'role' => 'Backend developer & koordinator UI',
                'problem' => 'Informasi tugas kelas tersebar di banyak chat, sehingga tenggat sering terlewat.',
                'goal' => 'Satu papan tugas yang bisa dilihat semua siswa dengan status yang jelas.',
                'features' => [
                    'Daftar tugas dengan tenggat dan penanggung jawab',
                    'Filter berdasarkan status dan mata pelajaran',
                    'Penanda tugas terlambat',
                    'Ringkasan beban tugas per siswa',
                ],
                'process' => 'Mengumpulkan keluhan teman sekelas, menyusun prioritas fitur, lalu membangun versi pertama dengan data contoh untuk diuji di kelas.',
                'feedback' => 'Ketua kelas merasa lebih mudah mengingatkan tugas karena statusnya terlihat jelas.',
                'metrics' => [
                    ['label' => 'Tugas terkelola', 'value' => '40+'],
                    ['label' => 'Pengguna', 'value' => '1 kelas'],
                    ['label' => 'Durasi', 'value' => '10 hari'],
                ],
                'demo_url' => null,
                'repo_url' => 'https://github.com/',
            ],
            [
                'slug' => 'katalog-umkm-desa',
                'title' => 'Katalog UMKM Desa',
                'category' => 'Web App',
                'year' => '2025',
                'featured' => false,
                'summary' => 'Katalog online sederhana untuk membantu UMKM sekitar menampilkan produk dan kontak penjual.',
                'cover_path' => '/images/demo/project-4.svg',
                'gallery' => ['/images/demo/project-4.svg', '/images/demo/project-1.svg'],
                'tech' => ['Laravel', 'MySQL', 'Tailwind CSS'],
                'role' => 'Full-stack developer',
                'problem' => 'Produk UMKM hanya dikenal dari mulut ke mulut dan belum punya halaman informasi yang bisa dibagikan.',
                'goal' => 'Membuat katalog ringan yang bisa diakses lewat tautan dan mudah diperbarui pemilik usaha.',
                'features' => [
                    'Halaman produk dengan foto dan deskripsi',
                    'Pencarian produk berdasarkan kategori',
                    'Tombol hubungi penjual lewat WhatsApp',
                    'Panel admin sederhana untuk menambah produk',
                ],
                'process' => 'Berdiskusi dengan dua penjual, membuat katalog versi statis, lalu menambahkan panel admin setelah alur kontennya disepakati.',
                'feedback' => 'Penjual merasa lebih percaya diri membagikan tautan katalog ke pembeli baru.',
                'metrics' => [
                    ['label' => 'Produk', 'value' => '24'],
                    ['label' => 'Penjual', 'value' => '2 UMKM'],
                    ['label' => 'Durasi', 'value' => '4 minggu'],
                ],
                'demo_url' => null,
                'repo_url' => 'https://github.com/',
            ],
            [
                'slug' => 'cek-kesehatan-komputer',
                'title' => 'Cek Kesehatan Komputer',
                'category' => 'Tools',
                'year' => '2025',
                'featured' => false,
                'summary' => 'Alat bantu lab komputer untuk mencatat kondisi perangkat dan riwayat perbaikannya.',
                'cover_path' => '/images/demo/project-5.svg',
                'gallery' => ['/images/demo/project-5.svg'],
                'tech' => ['PHP', 'Bootstrap', 'MySQL'],
                'role' => 'Programmer & pencatat kebutuhan',
                'problem' => 'Riwayat kerusakan komputer lab hanya diingat oleh teknisi, sehingga perbaikan berulang sulit dilacak.',
                'goal' => 'Menyimpan riwayat kondisi dan perbaikan tiap unit komputer dalam satu tempat.',
                'features' => [
                    'Data unit komputer dan spesifikasinya',
                    'Riwayat perbaikan dengan tanggal dan catatan',
                    'Status kondisi perangkat',
                    'Ekspor daftar perangkat ke spreadsheet',
                ],
                'process' => 'Mendata 20 unit komputer lab, membuat formulir pencatatan, lalu menguji alur input bersama teknisi sekolah.',
                'feedback' => 'Teknisi dapat melihat perbaikan terakhir tanpa harus membuka catatan manual.',
                'metrics' => [
                    ['label' => 'Unit terdata', 'value' => '20'],
                    ['label' => 'Riwayat', 'value' => '35 catatan'],
                    ['label' => 'Durasi', 'value' => '2 minggu'],
                ],
                'demo_url' => null,
                'repo_url' => 'https://github.com/',
            ],
            [
                'slug' => 'eksperimen-animasi-css',
                'title' => 'Eksperimen Animasi CSS',
                'category' => 'Experiment',
                'year' => '2024',
                'featured' => false,
                'summary' => 'Kumpulan eksperimen animasi ringan untuk mempelajari transisi, easing, dan aksesibilitas gerak.',
                'cover_path' => '/images/demo/project-6.svg',
                'gallery' => ['/images/demo/project-6.svg', '/images/demo/project-3.svg'],
                'tech' => ['HTML', 'CSS', 'JavaScript'],
                'role' => 'Eksperimen pribadi',
                'problem' => 'Animasi sering dipakai tanpa mempertimbangkan performa dan preferensi pengguna.',
                'goal' => 'Memahami kapan animasi membantu dan kapan sebaiknya dimatikan.',
                'features' => [
                    'Contoh transisi dengan durasi berbeda',
                    'Perbandingan easing',
                    'Fallback prefers-reduced-motion',
                    'Catatan performa singkat',
                ],
                'process' => 'Membuat beberapa demonstrasi kecil, mengukur rasa gerak di perangkat lambat, lalu menulis catatan hasilnya.',
                'feedback' => 'Teman satu tim mulai memakai prefers-reduced-motion setelah melihat contohnya.',
                'metrics' => [
                    ['label' => 'Eksperimen', 'value' => '9'],
                    ['label' => 'Dokumentasi', 'value' => 'Lengkap'],
                    ['label' => 'Durasi', 'value' => '1 minggu'],
                ],
                'demo_url' => null,
                'repo_url' => 'https://github.com/',
            ],
        ];

        foreach ($projects as $index => $project) {
            $gallery = $project['gallery'];
            unset($project['gallery']);

            $model = Project::query()->create($project + ['sort_order' => $index]);

            foreach ($gallery as $order => $path) {
                ProjectImage::query()->create([
                    'project_id' => $model->id,
                    'path' => $path,
                    'sort_order' => $order,
                ]);
            }
        }
    }

    private function seedCertificates(): void
    {
        $certificates = [
            [
                'title' => 'Juara 2 Lomba Web Design',
                'issuer' => 'Kompetisi Siswa Tingkat Kabupaten',
                'year' => '2026',
                'type' => 'Prestasi',
                'image_path' => '/images/demo/certificate.svg',
                'description' => 'Merancang dan membangun landing page responsif dalam waktu 6 jam.',
                'credential_url' => null,
            ],
            [
                'title' => 'Sertifikat PLK — Web Developer',
                'issuer' => 'Mitra Industri PLK',
                'year' => '2026',
                'type' => 'Sertifikat',
                'image_path' => '/images/demo/certificate.svg',
                'description' => 'Praktik kerja lapangan selama 3 bulan pada tim pengembangan web.',
                'credential_url' => null,
            ],
            [
                'title' => 'Finalis Game Jam Pelajar',
                'issuer' => 'Komunitas Game Lokal',
                'year' => '2025',
                'type' => 'Prestasi',
                'image_path' => '/images/demo/certificate.svg',
                'description' => 'Membuat prototipe game 2D bertema edukasi dalam 48 jam.',
                'credential_url' => null,
            ],
            [
                'title' => 'Dasar Pemrograman Web',
                'issuer' => 'Kelas Daring Bersertifikat',
                'year' => '2025',
                'type' => 'Sertifikat',
                'image_path' => '/images/demo/certificate.svg',
                'description' => 'HTML, CSS, JavaScript dasar, dan pengenalan HTTP.',
                'credential_url' => null,
            ],
            [
                'title' => 'Laravel untuk Pemula',
                'issuer' => 'Kelas Daring Bersertifikat',
                'year' => '2025',
                'type' => 'Sertifikat',
                'image_path' => '/images/demo/certificate.svg',
                'description' => 'Routing, controller, Blade, dan dasar Eloquent.',
                'credential_url' => null,
            ],
            [
                'title' => 'Peserta Workshop UI/UX Sekolah',
                'issuer' => 'SMK',
                'year' => '2024',
                'type' => 'Sertifikat',
                'image_path' => '/images/demo/certificate.svg',
                'description' => 'Wireframe, prototyping, dan pengujian kegunaan sederhana.',
                'credential_url' => null,
            ],
        ];

        foreach ($certificates as $index => $certificate) {
            Certificate::query()->create($certificate + ['sort_order' => $index]);
        }
    }

    private function seedExperiences(): void
    {
        $experiences = [
            [
                'period' => 'Jul 2026 — Sep 2026',
                'title' => 'Praktik Kerja Lapangan — Web Developer',
                'organization' => 'Mitra Industri PLK',
                'type' => 'PLK',
                'description' => 'Membantu membangun dan merawat halaman internal, memperbaiki tampilan responsif, serta menulis dokumentasi komponen.',
                'tags' => ['Laravel', 'Blade', 'Git'],
                'current' => true,
            ],
            [
                'period' => '2025 — 2026',
                'title' => 'Ketua Divisi Dokumentasi',
                'organization' => 'OSIS / Kepanitiaan Sekolah',
                'type' => 'Organisasi',
                'description' => 'Mengelola dokumentasi kegiatan, menyusun laporan, dan berkoordinasi dengan divisi lain.',
                'tags' => ['Koordinasi', 'Dokumentasi'],
                'current' => true,
            ],
            [
                'period' => '2025',
                'title' => 'Anggota Tim Lomba Web Design',
                'organization' => 'Kompetisi Siswa Tingkat Kabupaten',
                'type' => 'Lomba',
                'description' => 'Bertanggung jawab pada antarmuka dan responsivitas halaman, meraih juara 2.',
                'tags' => ['UI', 'Responsif'],
                'current' => false,
            ],
            [
                'period' => '2025',
                'title' => 'Panitia Game Jam Pelajar',
                'organization' => 'Komunitas Game Lokal',
                'type' => 'Organisasi',
                'description' => 'Membantu persiapan acara dan mendampingi peserta baru saat sesi prototyping.',
                'tags' => ['Game Jam', 'Mentoring'],
                'current' => false,
            ],
            [
                'period' => '2025',
                'title' => 'Finalis Game Jam Pelajar',
                'organization' => 'Komunitas Game Lokal',
                'type' => 'Lomba',
                'description' => 'Membuat prototipe game edukasi 2D bersama tim berisi tiga orang dalam 48 jam.',
                'tags' => ['Game Design', 'Tim'],
                'current' => false,
            ],
            [
                'period' => '2024',
                'title' => 'Relawan Kelas Komputer',
                'organization' => 'Kegiatan Sekolah',
                'type' => 'Organisasi',
                'description' => 'Membantu teman sekelas memahami dasar HTML/CSS pada sesi belajar tambahan.',
                'tags' => ['Mengajar', 'HTML/CSS'],
                'current' => false,
            ],
        ];

        foreach ($experiences as $index => $experience) {
            Experience::query()->create($experience + ['sort_order' => $index]);
        }
    }

    private function seedSettings(): void
    {
        Setting::set('feedback_aspects', Portfolio::defaultFeedbackAspects());
    }

    private function seedMessages(): void
    {
        $messages = [
            ['name' => 'Nadia', 'role' => 'Teman sekelas', 'relation' => 'teman', 'body' => 'Bagian testimoni dan sertifikat enak diikuti. Navigasi dari daftar proyek ke detail terasa jelas, dan saya tidak bingung mencari halaman kontak.', 'days_ago' => 2, 'approved' => true, 'ratings' => ['design' => 5, 'navigation' => 5, 'content' => 5, 'comfort' => 4]],
            ['name' => 'Rizky', 'role' => 'Teman sekelas', 'relation' => 'teman', 'body' => 'Tampilan gelapnya nyaman dipakai malam. Mungkin di halaman proyek bisa ada tombol kembali yang lebih besar supaya tidak perlu scroll ke atas.', 'days_ago' => 3, 'approved' => true, 'ratings' => ['design' => 5, 'navigation' => 4, 'content' => 4, 'comfort' => 5]],
            ['name' => 'Fajar', 'role' => 'Teman sekelas', 'relation' => 'teman', 'body' => 'Suka marquee testimoni di beranda, jadi terasa hidup tanpa perlu klik apa-apa. Isi tiap proyek cukup lengkap buat saya paham peran dan hasilnya.', 'days_ago' => 4, 'approved' => true, 'ratings' => ['design' => 5, 'navigation' => 5, 'content' => 5, 'comfort' => 5]],
            ['name' => 'Salsa', 'role' => 'Teman sekelas', 'relation' => 'teman', 'body' => 'Form kesan dan pesan gampang diisi. Halaman keahlian paling membantu saya melihat fokus belajarnya.', 'days_ago' => 5, 'approved' => true, 'ratings' => ['design' => 4, 'navigation' => 5, 'content' => 5, 'comfort' => 4]],
            ['name' => 'Dimas', 'role' => 'Teman sekelas', 'relation' => 'teman', 'body' => 'Secara keseluruhan rapi dan enak dibaca. Di ponsel, beberapa kartu proyek rasanya masih bisa dibuat lebih ringkas agar daftar tidak terasa panjang.', 'days_ago' => 6, 'approved' => true, 'ratings' => ['design' => 4, 'navigation' => 4, 'content' => 4, 'comfort' => 4]],
            ['name' => 'Putri', 'role' => 'Teman sekelas', 'relation' => 'teman', 'body' => 'Warna dan tipografinya konsisten, enak dibaca lama. Pindah halaman juga cepat dan tidak bikin bingung meski pertama kali membuka.', 'days_ago' => 7, 'approved' => true, 'ratings' => ['design' => 5, 'navigation' => 5, 'content' => 4, 'comfort' => 5]],
            ['name' => 'Bima', 'role' => 'Teman sekelas', 'relation' => 'teman', 'body' => 'Navigasi di desktop jelas dan dropdown Lainnya membantu. Saya baru sadar ada halaman CV setelah membuka menu itu, mungkin perlu petunjuk kecil.', 'days_ago' => 8, 'approved' => true, 'ratings' => ['design' => 4, 'navigation' => 4, 'content' => 4, 'comfort' => 4]],
            ['name' => 'Ayu', 'role' => 'Teman sekelas', 'relation' => 'teman', 'body' => 'Galeri proyeknya enak dilihat dan keterangannya jelas. Akan lebih membantu kalau daftar proyek bisa difilter berdasarkan kategori seperti Laravel atau game.', 'days_ago' => 10, 'approved' => false, 'ratings' => ['design' => 4, 'navigation' => 4, 'content' => 5, 'comfort' => 4]],
            ['name' => 'Tio', 'role' => 'Teman sekelas', 'relation' => 'teman', 'body' => 'Suka bagian pengalaman. Timeline-nya membantu memahami urutan kegiatan, dari organisasi sampai PLK, tanpa harus membaca paragraf panjang.', 'days_ago' => 12, 'approved' => false, 'ratings' => ['design' => 4, 'navigation' => 5, 'content' => 5, 'comfort' => 4]],
            ['name' => 'Citra', 'role' => 'Teman sekelas', 'relation' => 'teman', 'body' => 'Desainnya bersih dan tidak berlebihan. Mungkin tombol kembali ke atas bisa dibuat lebih kelihatan supaya di halaman panjang tidak perlu scroll jauh.', 'days_ago' => 14, 'approved' => false, 'ratings' => ['design' => 5, 'navigation' => 4, 'content' => 4, 'comfort' => 4]],
            ['name' => 'Bu Rina', 'role' => 'Guru pembimbing', 'relation' => 'guru', 'body' => 'Portfolio ini menunjukkan proses belajar yang jelas, bukan hanya hasil akhir. Bagian refleksi dan umpan balik di tiap proyek sangat membantu penilaian pembimbing.', 'days_ago' => 3, 'approved' => true, 'ratings' => ['design' => 5, 'navigation' => 5, 'content' => 5, 'comfort' => 5]],
            ['name' => 'Pak Andi', 'role' => 'Pembimbing PLK', 'relation' => 'guru', 'body' => 'Informasi PLK mudah ditemukan dan terdokumentasi baik. Navigasi ke sertifikat dan CV sudah memadai untuk kebutuhan peninjauan tempat praktik.', 'days_ago' => 5, 'approved' => true, 'ratings' => ['design' => 4, 'navigation' => 5, 'content' => 5, 'comfort' => 4]],
            ['name' => 'Bu Sari', 'role' => 'Guru', 'relation' => 'guru', 'body' => 'Halaman kesan dan pesan mudah dipakai siswa dan masukan yang masuk terasa lebih terarah.', 'days_ago' => 8, 'approved' => true, 'ratings' => ['design' => 4, 'navigation' => 5, 'content' => 4, 'comfort' => 5]],
            ['name' => 'Pak Hendra', 'role' => 'Guru', 'relation' => 'guru', 'body' => 'Tampilannya profesional untuk ukuran portfolio siswa. Perlu sedikit perbaikan pada keterbacaan teks abu-abu di beberapa kartu, terutama saat proyektor.', 'days_ago' => 11, 'approved' => false, 'ratings' => ['design' => 4, 'navigation' => 4, 'content' => 4, 'comfort' => 3]],
            ['name' => 'Bu Maya', 'role' => 'Guru', 'relation' => 'guru', 'body' => 'Saya suka alur beranda menuju proyek. Mohon tambahkan contoh hasil karya game yang lebih terlihat, karena selama ini bagian web lebih dominan.', 'days_ago' => 15, 'approved' => false, 'ratings' => ['design' => 4, 'navigation' => 4, 'content' => 4, 'comfort' => 4]],
        ];

        foreach ($messages as $message) {
            $daysAgo = $message['days_ago'];
            unset($message['days_ago']);

            $ratings = $message['ratings'] ?? null;
            unset($message['ratings']);

            Message::factory()->create($message + [
                'email' => null,
                'subject' => null,
                'ratings' => $ratings,
                'is_read' => false,
                'created_at' => now()->subDays($daysAgo),
            ]);
        }
    }
}
