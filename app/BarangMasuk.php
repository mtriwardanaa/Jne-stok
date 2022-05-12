<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $created_by
 * @property string $tanggal
 * @property string $created_at
 * @property string $updated_at
 * @property User $user
 */
class BarangMasuk extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'stok_barang_masuk';

    /**
     * The "type" of the auto-incrementing ID.
     * 
     * @var string
     */
    protected $keyType = 'integer';

    /**
     * @var array
     */
    protected $fillable = ['no_barang_masuk', 'created_by', 'updated_by', 'tanggal', 'tanggal_update', 'created_at', 'updated_at', 'deleted_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\User', 'created_by');
    }

    public function userUpdate()
    {
        return $this->belongsTo('App\User', 'updated_by');
    }

    public function details()
    {
        return $this->hasMany('App\BarangMasukDetail', 'id_barang_masuk');
    }

    public function detailStok()
    {
        return $this->hasMany('App\BarangHarga', 'id_barang_masuk');
    }
}
