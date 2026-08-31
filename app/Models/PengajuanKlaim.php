<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class PengajuanKlaim extends Model
{
    protected $table = 'pengajuan_klaim';

    protected $fillable = ['peserta_id', 'nama_ahli_waris', 'no_hp_peserta', 'no_hp_ahli_waris', 'nik_peserta', 'akta_kematian', 'kartu_bpjs', 'keterangan_ahli_waris', 'nomor_rekening_ahli_waris', 'nama_bank', 'buku_rekening'];

    protected $hidden = ['akta_kematian', 'kartu_bpjs', 'buku_rekening'];

    public function peserta(): BelongsTo
    {
        return $this->belongsTo(Peserta::class);
    }

    public function nomorAntrian(): HasOne
    {
        return $this->hasOne(NomorAntrian::class);
    }
}
