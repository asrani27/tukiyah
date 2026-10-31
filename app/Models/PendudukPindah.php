<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PendudukPindah extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'penduduk_pindah';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'kode_pindah',
        'penduduk_id',
        'alamat_tujuan',
        'tanggal_pindah',
        'alasan_pindah',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'tanggal_pindah' => 'date',
        ];
    }

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::created(function (self $model): void {
            $model->penduduk()->update(['status_keberadaan' => 'pindah']);
        });

        static::deleted(function (self $model): void {
            if ($model->penduduk) {
                $model->penduduk()->update(['status_keberadaan' => 'tetap']);
            }
        });
    }

    /**
     * Get the penduduk that moved.
     */
    public function penduduk(): BelongsTo
    {
        return $this->belongsTo(Penduduk::class, 'penduduk_id');
    }
}
