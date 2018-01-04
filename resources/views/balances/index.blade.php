@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col">
                <h2 class="pt-4 pb-2 {{ $gain > 0 ? 'text-success' : 'text-danger' }}">
                    {{ usd($value) }}
                    <small class="float-right">{{ usd($gain) }}</small>
                </h2>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="card-columns">
                    @each('partials.balances.card', $balances, 'balance')
                </div>
            </div>
        </div>
    </div>
@endsection