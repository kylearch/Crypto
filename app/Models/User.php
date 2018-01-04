<?php

namespace App\Models;

use App\Helpers\PriceHelper;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * App\Models\User
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Balance[]                                            $balances
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Transaction[]                                        $transactions
 * @mixin \Eloquent
 */
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

    public function balances()
    {
        return $this->hasMany(Balance::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function getPositiveBalances(bool $with_prices)
    {
        $balances = $this->balances()
            ->where('amount', '>', 0.0001)
            ->with('currency')
            ->orderByDesc('amount')
            ->get();

        if ($with_prices === TRUE) {
            PriceHelper::getPrices($balances);
        }

        return $balances;
    }
}
