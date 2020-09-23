<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * @property integer $id
 * @property integer $id_barang_satuan
 * @property string $kode_barang
 * @property string $nama_barang
 * @property int $qty_barang
 * @property string $created_at
 * @property string $updated_at
 * @property StokBarangSatuan $stokBarangSatuan
 */
class Barang extends Model implements Auditable
{
	use \OwenIt\Auditing\Auditable;

    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'stok_barang';

    /**
     * The "type" of the auto-incrementing ID.
     * 
     * @var string
     */
    protected $keyType = 'integer';

    /**
     * @var array
     */
    protected $fillable = ['id_barang_satuan', 'internal', 'agen', 'subagen', 'harga_barang', 'warning_stok', 'kode_barang', 'nama_barang', 'qty_barang', 'created_at', 'updated_at', 'deleted_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function stokBarangSatuan()
    {
        return $this->belongsTo('App\BarangSatuan', 'id_barang_satuan');
    }
}
