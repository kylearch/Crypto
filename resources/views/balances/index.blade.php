@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col">
                <h2 class="pt-4 pb-2 {{ $gain> 0 ? 'text-success' : 'text-danger' }}">
                    {{ money_format('%.2n', $value) }}
                    <small class="float-right">{{ money_format('%.2n', $gain) }}</small>
                </h2>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="card-columns">
                    @forelse ($balances as $balance)
                        <div class="card text-white bg-{{ $balance->color }} mb-3">
                            <div class="card-header">
                                {{ $balance->coin->symbol }}
                                <span class="float-right">{{ $balance->market_price }}</span>
                            </div>
                            <div class="card-body text-center">
                                <p class="card-title">
                                <h3>{{ $balance->current_price }}</h3></p>
                                <p class="card-text">
                                    {{ $balance->gain_usd }}
                                    <br>
                                    {{ $balance->gain_percent }}%
                                </p>
                            </div>
                        </div>
                    @empty
                    @endforelse
                </div>
            </div>
        </div>
        @isset ($last_fetch)
            <div class="row">
                <div class="col">
                    <h6 class="float-right">Last Fetched: {{ $last_fetch->diffForHumans() }}</h6>
                </div>
            </div>
        @endisset
    </div>
@endsection