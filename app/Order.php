<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $id_divisi
 * @property integer $id_kategori
 * @property integer $created_by
 * @property integer $approved_by
 * @property string $tanggal
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property User $user
 * @property User $user
 * @property Divisi $divisi
 * @property AgenKategori $agenKategori
 */
class Order extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'stok_order';

    /**
     * The "type" of the auto-incrementing ID.
     * 
     * @var string
     */
    protected $keyType = 'integer';

    /**
     * @var array
     */
    protected $fillable = ['id_divisi', 'no_order', 'tanggal_approve', 'id_kategori', 'created_by', 'approved_by', 'tanggal', 'created_at', 'updated_at', 'deleted_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function approved_user()
    {
        return $this->belongsTo('App\User', 'approved_by');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function created_user()
    {
        return $this->belongsTo('App\User', 'created_by');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function divisi()
    {
        return $this->belongsTo('App\Divisi', 'id_divisi');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function kategori()
    {
        return $this->belongsTo('App\AgenKategori', 'id_kategori');
    }

    public function details()
    {
        return $this->hasMany('App\OrderDetail', 'id_order');
    }
}
