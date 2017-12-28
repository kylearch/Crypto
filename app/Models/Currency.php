<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    protected $fillable = [ 'symbol', 'name', 'slug' ];

    public function transactions()
    {
        return $this->belongsToMany(Transaction::class);
    }

}
