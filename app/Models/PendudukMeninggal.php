<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PendudukMeninggal extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'penduduk_meninggal';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'kode_kematian',
        'penduduk_id',
        'tanggal_meninggal',
        'tempat_meninggal',
        'sebab_meninggal',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'tanggal_meninggal' => 'date',
        ];
    }

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::created(function (self $model): void {
            $model->penduduk()->update(['status_keberadaan' => 'meninggal']);
        });

        static::deleted(function (self $model): void {
            if ($model->penduduk) {
                $pindahExists = PendudukPindah::where('penduduk_id', $model->penduduk_id)->exists();
                $model->penduduk()->update([
                    'status_keberadaan' => $pindahExists ? 'pindah' : 'tetap',
                ]);
            }
        });
    }

    /**
     * Get the deceased penduduk.
     */
    public function penduduk(): BelongsTo
    {
        return $this->belongsTo(Penduduk::class, 'penduduk_id');
    }
}
