<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $id_order
 * @property integer $id_barang
 * @property int $qty_barang
 * @property string $created_at
 * @property string $updated_at
 * @property StokBarang $stokBarang
 * @property StokOrder $stokOrder
 */
class OrderDetail extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'stok_order_detail';

    /**
     * The "type" of the auto-incrementing ID.
     * 
     * @var string
     */
    protected $keyType = 'integer';

    /**
     * @var array
     */
    protected $fillable = ['id_order', 'id_barang', 'qty_barang', 'jumlah_approve', 'created_at', 'updated_at'];

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
    public function stokOrder()
    {
        return $this->belongsTo('App\Order', 'id_order');
    }
}
