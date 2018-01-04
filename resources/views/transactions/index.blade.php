@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col">
                <table class="table">
                    <thead>
                    <tr>
                        <th>Transaction</th>
                        <th>From</th>
                        <th>To</th>
                        <th>Date</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($transactions as $transaction)
                        <tr>
                            <td>{!! $transaction->description !!}</td>
                            <td><i class="{{ $transaction->from->icon }}"></i>&nbsp;{{ NumberHelper::format($transaction->amount_from, $transaction->from->isUSD()) }}</td>
                            <td><i class="{{ $transaction->to->icon }}"></i>&nbsp;{{ NumberHelper::format($transaction->amount_to, $transaction->to->isUSD()) }}</td>
                            <td>{{ $transaction->created_at->format('Y-m-d g:i A') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">No transactions yet.</td>
                        </tr>
                    @endforelse
                    </tbody>
                    <tfoot>
                    <tr>
                        <td colspan="4"><a class="btn btn-primary btn-block" href="{{ route('transactions.create') }}">Add Transaction</a></td>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
@endsection