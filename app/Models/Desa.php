<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Desa extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'desa';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'kode_desa',
        'nama_desa',
        'kepala_desa',
        'telp',
        'alamat',
    ];

    /**
     * Get all penduduk registered in this desa.
     */
    public function penduduk(): HasMany
    {
        return $this->hasMany(Penduduk::class, 'desa_id');
    }

    /**
     * Get only tetap penduduk in this desa.
     */
    public function pendudukTetap(): HasMany
    {
        return $this->hasMany(Penduduk::class, 'desa_id')->where('status_keberadaan', 'tetap');
    }

    /**
     * Get all kelahiran records in this desa.
     */
    public function pendudukLahir(): HasMany
    {
        return $this->hasMany(PendudukLahir::class, 'desa_id');
    }

    /**
     * Get all pendatang records in this desa.
     */
    public function pendudukPendatang(): HasMany
    {
        return $this->hasMany(PendudukPendatang::class, 'desa_id');
    }
}
