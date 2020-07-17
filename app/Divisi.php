<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property string $nama
 * @property string $created_at
 * @property string $updated_at
 * @property CancelOrder[] $cancelOrders
 * @property Laporan[] $laporans
 */
class Divisi extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'divisi';

    /**
     * The "type" of the auto-incrementing ID.
     * 
     * @var string
     */
    protected $keyType = 'integer';

    /**
     * @var array
     */
    protected $fillable = ['nama', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function cancelOrders()
    {
        return $this->hasMany('App\CancelOrder', 'id_divisi');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function laporans()
    {
        return $this->hasMany('App\Laporan', 'id_divisi');
    }
}
