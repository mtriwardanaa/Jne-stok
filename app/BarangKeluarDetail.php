<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $id_barang_keluar
 * @property integer $id_barang
 * @property integer $id_supplier
 * @property int $qty_barang
 * @property string $created_at
 * @property string $updated_at
 * @property StokBarang $stokBarang
 * @property StokBarangKeluar $stokBarangKeluar
 * @property StokSupplier $stokSupplier
 */
class BarangKeluarDetail extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'stok_barang_keluar_detail';

    /**
     * The "type" of the auto-incrementing ID.
     * 
     * @var string
     */
    protected $keyType = 'integer';

    /**
     * @var array
     */
    protected $fillable = ['id_barang_keluar', 'id_barang', 'qty_barang', 'harga_barang', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function stokBarang()
    {
        return $this->belongsTo('App\Barang', 'id_barang');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function stokBarangKeluar()
    {
        return $this->belongsTo('App\BarangKeluar', 'id_barang_keluar');
    }
}
