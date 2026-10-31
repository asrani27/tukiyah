<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Penduduk extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'penduduk';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nik',
        'desa_id',
        'nama_lengkap',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'agama',
        'status_perkawinan',
        'pendidikan_terakhir',
        'alamat',
        'telp',
        'status_keberadaan',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'tanggal_lahir' => 'date',
        ];
    }

    /**
     * Get the desa this penduduk belongs to.
     */
    public function desa(): BelongsTo
    {
        return $this->belongsTo(Desa::class, 'desa_id');
    }

    /**
     * Get mutasi pindah record if any.
     */
    public function pindah(): HasOne
    {
        return $this->hasOne(PendudukPindah::class, 'penduduk_id');
    }

    /**
     * Get mutasi kematian record if any.
     */
    public function meninggal(): HasOne
    {
        return $this->hasOne(PendudukMeninggal::class, 'penduduk_id');
    }

    /**
     * Scope query to only tetap penduduk.
     */
    public function scopeTetap($query)
    {
        return $query->where('status_keberadaan', 'tetap');
    }
}
