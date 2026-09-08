<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NomorAntrian extends Model
{
    protected $table = 'nomor_antrian';

    protected $fillable = ['pengajuan_klaim_id', 'nomor_urut', 'tanggal_antrian', 'jenis_pelayanan', 'nomor_loket', 'waktu_pengambilan'];

    protected function casts(): array
    {
        return ['tanggal_antrian' => 'date', 'waktu_pengambilan' => 'datetime'];
    }

    public function pengajuanKlaim(): BelongsTo
    {
        return $this->belongsTo(PengajuanKlaim::class);
    }
}
