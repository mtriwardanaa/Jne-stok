<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Lib\MyHelper;

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

    protected $appends = ['bulan', 'all'];

    /**
     * @var array
     */
    protected $fillable = ['id_divisi', 'no_order', 'tanggal_update', 'tanggal_approve', 'tanggal_reject', 'id_kategori', 'created_by', 'updated_by', 'approved_by', 'rejected_by', 'rejected_text', 'nama_user_request', 'hp_user_request', 'tanggal', 'created_at', 'updated_at', 'deleted_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */

    public function getBulanAttribute()
    {
        return MyHelper::indonesian_date($this->tanggal, 'F');
    }

    public function getAllAttribute()
    {
        return MyHelper::indonesian_date($this->tanggal);
    }

    public function approved_user()
    {
        return $this->belongsTo('App\User', 'approved_by');
    }

    public function updated_user()
    {
        return $this->belongsTo('App\User', 'updated_by');
    }

    public function rejected_user()
    {
        return $this->belongsTo('App\User', 'rejected_by');
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
