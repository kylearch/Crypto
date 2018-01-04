<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Transaction
 *
 * @property-read \App\Models\Currency $from
 * @property-read mixed $amount_from_signed
 * @property-read mixed $amount_to_signed
 * @property-read mixed $description
 * @property-read \App\Models\Currency $to
 * @property-read \App\Models\User $user
 * @mixin \Eloquent
 */
class Transaction extends Model
{
    const TYPE_BUY   = 0;
    const TYPE_SELL  = 1;
    const TYPE_TRADE = 2;

    const TYPES = [
        self::TYPE_BUY   => 'Buy',
        self::TYPE_SELL  => 'Sell',
        self::TYPE_TRADE => 'Trade',
    ];

    protected $fillable = [ 'from_id', 'to_id', 'type', 'amount_from', 'amount_to' ];
    protected $casts    = [
        'type' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function from()
    {
        return $this->belongsTo(Currency::class, 'from_id');
    }

    public function to()
    {
        return $this->belongsTo(Currency::class, 'to_id');
    }

    public function getAmountSigned(string $direction)
    {
        return $direction === 'from' ? $this->amountFromSigned : $this->amountToSigned;
    }

    public function getAmountFromSignedAttribute()
    {
        $amount = $this->type === self::TYPE_BUY ? -$this->amount_from : $this->amount_from;

        return (float) $amount;
    }

    public function getAmountToSignedAttribute()
    {
        $amount = $this->type === self::TYPE_SELL ? -$this->amount_to : $this->amount_to;

        return (float) $amount;
    }

    public function getDescriptionAttribute()
    {
        switch ($this->type) {
            case self::TYPE_BUY:
                $description = '<span class="text-success font-weight-bold">BUY</span>&nbsp;' . $this->to->symbol;
                break;
            case self::TYPE_SELL:
                $description = '<span class="text-warning font-weight-bold">SELL</span>&nbsp;' . $this->from->symbol;
                break;
            case self::TYPE_TRADE:
                $description = '<span class="text-primary font-weight-bold">TRADE</span>&nbsp;' . $this->from->symbol . ' to ' . $this->to->symbol;
                break;
            default:
                $description = 'Transaction';
                break;
        }

        return $description;
    }
}
