<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $id_barang_masuk
 * @property integer $id_barang
 * @property integer $id_supplier
 * @property int $qty_barang
 * @property string $created_at
 * @property string $updated_at
 * @property StokBarang $stokBarang
 * @property StokBarangMasuk $stokBarangMasuk
 * @property StokSupplier $stokSupplier
 */
class BarangMasukDetail extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'stok_barang_masuk_detail';

    /**
     * The "type" of the auto-incrementing ID.
     * 
     * @var string
     */
    protected $keyType = 'integer';

    /**
     * @var array
     */
    protected $fillable = ['id_barang_masuk', 'id_barang', 'id_supplier', 'qty_barang', 'created_at', 'updated_at'];

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
    public function stokBarangMasuk()
    {
        return $this->belongsTo('App\BarangMasuk', 'id_barang_masuk');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function stokSupplier()
    {
        return $this->belongsTo('App\Supplier', 'id_supplier');
    }
}
