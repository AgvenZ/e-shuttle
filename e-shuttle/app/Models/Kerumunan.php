<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kerumunan extends Model
{
    use HasFactory;

    protected $table = 'kerumunan';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'id_halte',
        'waktu',
        'jumlah_kerumunan'
    ];

    protected $casts = [
        'id_halte' => 'integer',
        'jumlah_kerumunan' => 'integer',
        'waktu' => 'datetime'
    ];

    /**
     * Relationship with Halte
     */
    public function halte()
    {
        return $this->belongsTo(Halte::class, 'id_halte', 'id');
    }
}