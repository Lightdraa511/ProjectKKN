<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('description')->nullable();
            $table->string('type')->default('text'); // text, number, date, boolean
            $table->timestamps();
        });

        // Menambahkan data awal
        $this->seedDefaultSettings();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }

    /**
     * Seed default settings
     */
    private function seedDefaultSettings()
    {
        $settings = [
            [
                'key' => 'registration_start',
                'value' => now()->format('Y-m-d'),
                'description' => 'Tanggal mulai pendaftaran KKN',
                'type' => 'date'
            ],
            [
                'key' => 'registration_end',
                'value' => now()->addMonths(1)->format('Y-m-d'),
                'description' => 'Tanggal selesai pendaftaran KKN',
                'type' => 'date'
            ],
            [
                'key' => 'kkn_start',
                'value' => now()->addMonths(2)->format('Y-m-d'),
                'description' => 'Tanggal mulai pelaksanaan KKN',
                'type' => 'date'
            ],
            [
                'key' => 'kkn_end',
                'value' => now()->addMonths(3)->format('Y-m-d'),
                'description' => 'Tanggal selesai pelaksanaan KKN',
                'type' => 'date'
            ],
            [
                'key' => 'registration_fee',
                'value' => '500000',
                'description' => 'Biaya pendaftaran KKN',
                'type' => 'number'
            ],
            [
                'key' => 'min_sks',
                'value' => '100',
                'description' => 'Jumlah minimal SKS untuk pendaftaran KKN',
                'type' => 'number'
            ],
            [
                'key' => 'min_gpa',
                'value' => '2.75',
                'description' => 'Nilai minimal IPK untuk pendaftaran KKN',
                'type' => 'number'
            ],
            [
                'key' => 'announcement',
                'value' => 'Pendaftaran KKN akan segera dibuka. Harap persiapkan dokumen yang diperlukan.',
                'description' => 'Pengumuman pada dashboard mahasiswa',
                'type' => 'text'
            ],
            [
                'key' => 'default_faculty_quota',
                'value' => '10',
                'description' => 'Kuota default per fakultas',
                'type' => 'number'
            ],
        ];

        DB::table('settings')->insert($settings);
    }
};
