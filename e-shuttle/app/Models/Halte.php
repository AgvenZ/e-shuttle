<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Halte extends Model
{
    use HasFactory;

    protected $table = 'halte';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'nama_halte',
        'cctv',
        'latitude',
        'longitude'
    ];

    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float'
    ];

    /**
     * Check if CCTV is available for this halte
     */
    public function hasCctv(): bool
    {
        return !empty($this->cctv) && filter_var($this->cctv, FILTER_VALIDATE_URL);
    }

    /**
     * Get CCTV URL if available
     */
    public function getCctvUrl(): ?string
    {
        return $this->hasCctv() ? $this->cctv : null;
    }

    /**
     * Relasi ke model Kerumunan
     */
    public function kerumunan()
    {
        return $this->hasMany(Kerumunan::class, 'id_halte');
    }
}