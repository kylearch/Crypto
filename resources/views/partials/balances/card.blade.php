<div class="card border-{{ $balance->color }} mb-3">
    <div class="card-header text-white bg-{{ $balance->color }}">
        <i class="cc {{ $balance->currency->symbol }}"></i>
        {{ $balance->currency->symbol }}
        <span class="float-right">${{ $balance->price }}</span>
    </div>
    <div class="card-body text-center text-{{ $balance->color }}" data-toggle="collapse" href="#collapse-{{ $balance->currency->symbol }}" role="button" aria-expanded="false" aria-controls="collapse-{{ $balance->currency->symbol }}">
        <h3 class="card-title">{{ usd($balance->value) }}</h3>
        <div class="row collapse" id="collapse-{{ $balance->currency->symbol }}">
            <div class="col">
                <hr class="mt-0 mb-2">
                <div class="row mb-2">
                    <div title="Gain/Loss ($)" class="col text-left">{{ usd($balance->gain) }}</div>
                    <div title="Gain/Loss (%) " class="col text-right">{{ $balance->percent }}%</div>
                </div>
                <div class="row">
                    <div title="% Change (1h)" class="col text-left text-{{ color($balance->change_hour) }}">{{ $balance->change_hour }}%</div>
                    <div title="% Change (1d)" class="col text-center text-{{ color($balance->change_day) }}">{{ $balance->change_day }}%</div>
                    <div title="% Change (1w)" class="col text-right text-{{ color($balance->change_week) }}">{{ $balance->change_week }}%</div>
                </div>
            </div>
        </div>
    </div>
</div>