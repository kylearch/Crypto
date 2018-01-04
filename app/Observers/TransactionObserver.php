<?php

namespace App\Observers;

use App\Models\Balance;
use App\Models\Currency;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;

class TransactionObserver
{

    public function created(Transaction $transaction): void
    {
        /** @var $balance Balance */
        if ($transaction->type === Transaction::TYPE_BUY || $transaction->type === Transaction::TYPE_SELL) {
            $currency = $transaction->type === Transaction::TYPE_BUY ? $transaction->to : $transaction->from;
            $amount   = $transaction->type === Transaction::TYPE_BUY ? $transaction->amount_to : -$transaction->amount_from;

            $balance = Balance::firstOrNew([
                'user_id'     => Auth::id(),
                'currency_id' => $currency->id,
            ]);
            $balance->adjustAmount($amount);
        } else if ($transaction->type === Transaction::TYPE_TRADE) {
            $balance = Balance::firstOrNew([
                'user_id'     => Auth::id(),
                'currency_id' => $transaction->from->id,
            ]);
            $balance->adjustAmount(-$transaction->amount_from);

            $balance = Balance::firstOrNew([
                'user_id'     => Auth::id(),
                'currency_id' => $transaction->to->id,
            ]);
            $balance->adjustAmount($transaction->amount_to);
        }
    }

    public function updating(Transaction $transaction): void
    {
        // TODO: Untested
        if ($transaction->isDirty([ 'from_id', 'to_id', 'amount_from', 'amount_to' ])) {
            // Change "from" currency
            $this->change('from_id', $transaction);

            // Change "to" currency
            $this->change('to_id', $transaction);

            // Change "from" amount
            $this->change('amount_from', $transaction);

            // Change "to" amount
            $this->change('amount_to', $transaction);
        }
    }

    private function change(string $attribute, Transaction $transaction): void
    {
        $direction = strpos($attribute, 'from') !== FALSE ? 'from' : 'to';
        $original  = $transaction->getOriginal();

        $old_value = $original[$attribute] ?? 0;
        $new_value = $transaction->{$attribute} ?? 0;

        if ($old_value !== $new_value) {
            /** @var $balance Balance */
            if ($attribute === 'from_id' || $attribute === 'to_id') {
                $balance = Balance::firstOrNew([
                    'user_id'     => $transaction->user_id,
                    'currency_id' => $old_value,
                ]);
                $balance->adjustAmount($transaction->getAmountSigned($direction));

                $balance = Balance::firstOrNew([
                    'user_id'     => $transaction->user_id,
                    'currency_id' => $new_value,
                ]);
                $balance->adjustAmount($transaction->getAmountSigned($direction));
            } else if ($attribute === 'amount_from' || $attribute === 'amount_to') {
                $id_attribute = "{$direction}_id";
                $difference   = $old_value - $new_value;

                $balance = Balance::firstOrNew([
                    'user_id'     => $transaction->user_id,
                    'currency_id' => $transaction->{$id_attribute},
                ]);
                $balance->adjustAmount($difference);
            }
        }

    }

}