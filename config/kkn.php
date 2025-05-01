<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Pengaturan Pendaftaran KKN
    |--------------------------------------------------------------------------
    |
    | File ini berisi konfigurasi untuk sistem pendaftaran KKN
    |
    */

    // Tanggal pendaftaran
    'registration_start' => env('KKN_REGISTRATION_START', now()->format('Y-m-d')),
    'registration_end' => env('KKN_REGISTRATION_END', now()->addMonths(1)->format('Y-m-d')),

    // Tanggal pelaksanaan KKN
    'kkn_start' => env('KKN_START_DATE', now()->addMonths(2)->format('Y-m-d')),
    'kkn_end' => env('KKN_END_DATE', now()->addMonths(3)->format('Y-m-d')),

    // Persyaratan pendaftaran
    'registration_fee' => env('KKN_REGISTRATION_FEE', 500000),
    'min_sks' => env('KKN_MIN_SKS', 100),
    'min_gpa' => env('KKN_MIN_GPA', 2.75),

    // Pengumuman
    'announcement' => env('KKN_ANNOUNCEMENT', 'Pendaftaran KKN akan segera dibuka. Harap persiapkan dokumen yang diperlukan.'),

    // Kuota default per fakultas jika tidak disebutkan
    'default_faculty_quota' => env('KKN_DEFAULT_FACULTY_QUOTA', 10),
];