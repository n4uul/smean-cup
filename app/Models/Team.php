<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Team extends Model
{
    protected $fillable = [
        'user_id',
        'nama_tim',
        'asal_sekolah',
        'nama_pelatih',
        'no_wa',
        'logo_tim',
        'berkas_syarat',
        'status_verifikasi',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
