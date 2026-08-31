<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Peserta extends Model
{
    use HasFactory;

    protected $table = 'peserta';

    protected $fillable = ['user_id', 'nama', 'no_hp', 'nik', 'foto_ktp'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function pengajuanKlaim(): HasOne
    {
        return $this->hasOne(PengajuanKlaim::class);
    }
}
