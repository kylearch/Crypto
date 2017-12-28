<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function balances()
    {
        return $this
            ->transactions()
            ->groupBy('currency_id')
            ->selectRaw('*, SUM(CASE WHEN `type` = 0 THEN `amount` ELSE -`amount` END) as `balance`, SUM(CASE WHEN `type` = 0 THEN `price` ELSE 0 END) as `total_price`');
    }
}
