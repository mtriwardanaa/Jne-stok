<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $id_barang_masuk
 * @property integer $id_barang
 * @property int $qty_barang
 * @property string $harga_barang
 * @property string $tanggal_barang
 * @property string $created_at
 * @property string $updated_at
 * @property StokBarang $stokBarang
 * @property StokBarangMasuk $stokBarangMasuk
 */
class BarangHarga extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'stok_barang_harga';

    /**
     * The "type" of the auto-incrementing ID.
     * 
     * @var string
     */
    protected $keyType = 'integer';

    /**
     * @var array
     */
    protected $fillable = ['id_barang_masuk', 'id_barang', 'qty_barang', 'harga_barang', 'tanggal_barang', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function stokBarang()
    {
        return $this->belongsTo('App\StokBarang', 'id_barang');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function stokBarangMasuk()
    {
        return $this->belongsTo('App\StokBarangMasuk', 'id_barang_masuk');
    }
}
