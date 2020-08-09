<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $created_by
 * @property integer $updated_by
 * @property string $tanggal
 * @property string $created_at
 * @property string $updated_at
 * @property User $user
 * @property User $user
 * @property StokBarangKeluarDetail[] $stokBarangKeluarDetails
 */
class BarangKeluar extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'stok_barang_keluar';

    /**
     * The "type" of the auto-incrementing ID.
     * 
     * @var string
     */
    protected $keyType = 'integer';

    /**
     * @var array
     */
    protected $fillable = ['no_barang_keluar', 'id_divisi', 'id_order', 'id_kategori', 'created_by', 'updated_by', 'id_agen', 'nama_user_request', 'tanggal', 'created_at', 'updated_at', 'deleted_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\User', 'created_by');
    }

    public function agen()
    {
        return $this->belongsTo('App\User', 'id_agen');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function userUpdate()
    {
        return $this->belongsTo('App\User', 'updated_by');
    }

    public function divisi()
    {
        return $this->belongsTo('App\Divisi', 'id_divisi');
    }

    public function kategori()
    {
        return $this->belongsTo('App\AgenKategori', 'id_kategori');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function details()
    {
        return $this->hasMany('App\BarangKeluarDetail', 'id_barang_keluar');
    }

    public function detailStok()
    {
        return $this->hasMany('App\BarangHarga', 'id_barang_keluar');
    }
}
