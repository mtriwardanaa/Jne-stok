<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $id_barang_keluar
 * @property integer $created_by
 * @property string $no_invoice
 * @property string $tanggal_invoice
 * @property string $status
 * @property string $created_at
 * @property string $updated_at
 * @property User $user
 * @property StokBarangKeluar $stokBarangKeluar
 */
class Invoice extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'stok_invoice';

    /**
     * The "type" of the auto-incrementing ID.
     * 
     * @var string
     */
    protected $keyType = 'integer';

    /**
     * @var array
     */
    protected $fillable = ['id_barang_keluar', 'created_by', 'no_invoice', 'tanggal_invoice', 'status', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\User', 'created_by');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function stokBarangKeluar()
    {
        return $this->belongsTo('App\BarangKeluar', 'id_barang_keluar');
    }
}
