<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Nilai KKM & Parameter Kelulusan
    |--------------------------------------------------------------------------
    |
    | Batas minimal skor kelulusan siswa pada Quiz Pengetahuan Algoritma.
    |
    */
    'passing_score' => 75,
    'total_questions' => 20,

    /*
    |--------------------------------------------------------------------------
    | Bobot Poin per Soal
    |--------------------------------------------------------------------------
    |
    | Setiap soal memiliki bobot poin yang sama yaitu 5 poin.
    | 20 soal x 5 poin = 100 total poin maksimal.
    |
    */
    'points_per_question' => 5,
    'total_points' => 100,

    /*
    |--------------------------------------------------------------------------
    | Rentang Predikat & Ringkasan Evaluasi
    |--------------------------------------------------------------------------
    |
    | Konfigurasi teks predikat dan ringkasan evaluasi untuk halaman hasil.
    |
    */
    'predicates' => [
        'sangat_baik' => [
            'min_score' => 90,
            'title' => 'Sangat Baik',
            'badge' => 'LULUS DENGAN PREDIKAT SANGAT BAIK',
            'summary' => 'Penguasaan logika algoritma terstruktur di atas rata-rata kompetensi kelas. Siap melangkah ke materi pengkondisian bertingkat.',
        ],
        'baik' => [
            'min_score' => 75,
            'title' => 'Baik',
            'badge' => 'LULUS DENGAN PREDIKAT BAIK',
            'summary' => 'Pemahaman alur logika flowchart sudah memenuhi standar kompetensi minimal. Terus tingkatkan latihan untuk hasil optimal.',
        ],
        'remedial' => [
            'min_score' => 0,
            'title' => 'Perlu Remedial',
            'badge' => 'PERLU REMEDIAL',
            'summary' => 'Nilai belum mencapai standar kelulusan KKM 75. Silakan pelajari kembali materi simbol dan logika alur sebelum mencoba lagi.',
        ],
    ],
];
