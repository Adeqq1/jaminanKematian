<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->string('role')->default('peserta')->after('username');
        });

        Schema::create('peserta', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('nama');
            $table->string('no_hp', 30);
            $table->string('nik', 16)->unique();
            $table->string('foto_ktp');
            $table->timestamps();
        });

        Schema::create('pengajuan_klaim', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('peserta_id')->unique()->constrained('peserta')->cascadeOnDelete();
            $table->string('nama_ahli_waris');
            $table->string('no_hp_peserta', 30);
            $table->string('no_hp_ahli_waris', 30);
            $table->string('nik_peserta', 16);
            $table->string('akta_kematian');
            $table->string('kartu_bpjs');
            $table->text('keterangan_ahli_waris');
            $table->string('nomor_rekening_ahli_waris', 50);
            $table->string('buku_rekening');
            $table->timestamps();
        });

        Schema::create('nomor_antrian', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('pengajuan_klaim_id')->unique()->constrained('pengajuan_klaim')->cascadeOnDelete();
            $table->unsignedInteger('nomor_urut');
            $table->date('tanggal_antrian');
            $table->string('jenis_pelayanan');
            $table->string('nomor_loket');
            $table->dateTime('waktu_pengambilan');
            $table->timestamps();
            $table->unique(['tanggal_antrian', 'jenis_pelayanan', 'nomor_urut']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nomor_antrian');
        Schema::dropIfExists('pengajuan_klaim');
        Schema::dropIfExists('peserta');
        Schema::table('users', fn (Blueprint $table) => $table->dropColumn('role'));
    }
};
