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
                                <i class="cc {{ $balance->currency->symbol }}"></i>
                                {{ $balance->currency->symbol }}
                                <span class="float-right">{{ $balance->market_price }}</span>
                            </div>
                            <div class="card-body text-center" data-toggle="collapse" href="#collapse-{{ $balance->currency->symbol }}" role="button" aria-expanded="false" aria-controls="collapse-{{ $balance->currency->symbol }}">
                                <p class="card-title"><h3>{{ $balance->current_price }}</h3></p>
                                <div class="row collapse" id="collapse-{{ $balance->currency->symbol }}">
                                    <div class="col">
                                        <table class="table table-sm mb-0">
                                            <tbody>
                                                <tr>
                                                    <td class="text-left">Gain ($):</td>
                                                    <td class="text-right">{{ $balance->gain_usd }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="text-left">Gain (%):</td>
                                                    <td class="text-right">{{ $balance->gain_percent }}%</td>
                                                </tr>
                                                <tr>
                                                    <td class="text-left">Change (1 hour):</td>
                                                    <td class="text-right">{{ $balance->change_hour}}%</td>
                                                </tr>
                                                <tr>
                                                    <td class="text-left">Change (1 day):</td>
                                                    <td class="text-right">{{ $balance->change_day }}%</td>
                                                </tr>
                                                <tr>
                                                    <td class="text-left">Change (1 week):</td>
                                                    <td class="text-right">{{ $balance->change_week }}%</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
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