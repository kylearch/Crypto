@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row pt-5">
        <div class="col">
            <h2>
                <i class="cc {{ $current->coin->symbol }}"></i>
                &nbsp;
                {{ $current->coin->symbol }}: {{ $current_value }}
                <small class="float-right">({{ money_format('%.2n', $current->price) }})</small>
            </h2>
        </div>
    </div>
    <div class="row">
        <div class="col">
                @foreach($results as $group => $result)
                    <div class="card-deck">
                        @php $color = $group === 0 ? 'success' : 'danger'; @endphp
                        @foreach ($result as $card)
                            <div class="card mb-4 border-{{ $color }}">
                                <div class="card-header bg-{{ $color }} text-white"><i class="cc fa-lg {{ $card['symbol'] }}"></i>&nbsp;{{ $card['name'] }}</div>
                                <div class="card-body">
                                    <h2 class="card-title mb-2 text-center text-{{ $color }}">{{ money_format('%.2n', $card['gain']) }}</h2>
                                </div>
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item"><strong>Value: </strong><span class="float-right">{{ money_format('%.2n', $card['value']) }}</span></li>
                                    <li class="list-group-item"><strong>Price: </strong><span class="float-right">{{ money_format('%.2n', $card['price']) }}</span></li>
                                    <li class="list-group-item"><strong>Coins: </strong><span class="float-right">{{ round($card['coins'], 6) }}</span></li>
                                </ul>
                            </div>
                            @if ($loop->iteration % 4 === 0)
                            <div class="w-100 hidden-lg-down"></div>
                            @endif
                        @endforeach
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection

@section('javascript')
    @if (empty($results[0]))
        let timeout = 20000;
    @else
        let timeout = 300000;
        if (Notification.permission === "granted") {
            const notification = new Notification('Trade Alert', {
                icon: 'https://cdn2.iconfinder.com/data/icons/bitcoin-and-mining/44/trade-512.png',
                body: "{{ $results[0]->first()['name'] }} has a favorable rate right now!",
            });
            notification.onclick = function (x) {
                window.focus();
                this.cancel();
            };
        } else {
            if (Notification.permission !== "granted") {
                Notification.requestPermission();
            }
        }
    @endif
    setTimeout(function () {
        location.reload();
    }, timeout);
@endsection
</body>
</html>
