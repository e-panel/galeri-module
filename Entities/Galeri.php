<?php

namespace Modules\Galeri\Entities;

use Illuminate\Database\Eloquent\Model;

class Galeri extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'media_galeri';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'uuid', 
        'caption', 
        'foto', 
        'url_video', 
        'jenis', 
        'id_operator', 
        'id_album'
    ];

    /**
     *  Setup model event hooks
     * 
     */
    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->uuid = uuid();
        });
    }

    /**
     * Scope a query for UUID.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query, $uuid
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUuid($query, $uuid) 
    {
        return $query->whereUuid($uuid);
    }

    /**
     * Define an inverse one-to-one or many relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function album() 
    {
        return $this->belongsTo('\Modules\Galeri\Entities\Album', 'id_album');
    }

    /**
     * Define an inverse one-to-one or many relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function operator() 
    {
        return $this->belongsTo('\Modules\Pengguna\Entities\Operator', 'id_operator');
    }
}
