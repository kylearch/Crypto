@extends ('layouts.app')

@section ('content')
    <div class="container">
        <div class="row">
            <div class="col">
                <nav class="nav nav-pills nav-fill mb-4" id="transaction-types" role="tablist">
                    @foreach(Transaction::TYPES as $id => $type)
                        <a class="nav-item nav-link {{ $id === Transaction::TYPE_BUY ? 'active' : '' }}" id="type-{{ strtolower($type) }}" data-toggle="tab" href="#tab-{{ strtolower($type) }}" role="tab" aria-controls="form-{{ strtolower($type) }}" aria-selected="{{ $id === Transaction::TYPE_BUY ? 'true' : 'false' }}">{{ title_case($type) }}</a>
                    @endforeach
                </nav>
                <div class="tab-content" id="type-forms">
                    <div class="tab-pane fade show active" id="tab-buy" role="tabpanel" aria-labelledby="type-buy">
                        {!! Form::open(['route' => 'transactions.store', 'method' => 'post', 'id' => 'form-buy']) !!}
                        {!! Form::hidden('from_id', Currency::USD) !!}
                        {!! Form::hidden('type', Transaction::TYPE_BUY) !!}
                        <div class="form-group">
                            {!! Form::label('to_id', 'Currency:') !!}
                            {!! Form::select('to_id', $currencies->pluck('name', 'id'), NULL, ['class' => 'form-control select-2']) !!}
                        </div>
                        <div class="form-group">
                            {!! Form::label('amount_to', 'Amount:') !!}
                            {!! Form::text('amount_to', 0.00, ['class' => 'form-control']) !!}
                        </div>
                        <div class="form-group">
                            {!! Form::label('amount_from', 'Price Paid:') !!}
                            {!! Form::text('amount_from', 0.00, ['class' => 'form-control']) !!}
                        </div>
                        <div class="form-group">
                            {!! Form::submit('Add Transaction', ['class' => 'btn btn-primary']) !!}
                        </div>
                        {!! Form::close() !!}
                    </div>
                    <div class="tab-pane fade" id="tab-sell" role="tabpanel" aria-labelledby="type-sell">
                        {!! Form::open(['route' => 'transactions.store', 'method' => 'post', 'id' => 'form-buy']) !!}
                        {!! Form::hidden('to_id', Currency::USD) !!}
                        {!! Form::hidden('type', Transaction::TYPE_SELL) !!}
                        <div class="form-group">
                            {!! Form::label('from_id', 'Currency:') !!}
                            {!! Form::select('from_id', $currencies->pluck('name', 'id'), NULL, ['class' => 'form-control select-2']) !!}
                        </div>
                        <div class="form-group">
                            {!! Form::label('amount_from', 'Amount:') !!}
                            {!! Form::text('amount_from', 0.00, ['class' => 'form-control']) !!}
                        </div>
                        <div class="form-group">
                            {!! Form::label('amount_to', 'Sell Price:') !!}
                            {!! Form::text('amount_to', 0.00, ['class' => 'form-control']) !!}
                        </div>
                        <div class="form-group">
                            {!! Form::submit('Add Transaction', ['class' => 'btn btn-primary']) !!}
                        </div>
                        {!! Form::close() !!}
                    </div>
                    <div class="tab-pane fade" id="tab-trade" role="tabpanel" aria-labelledby="type-trade">
                        {!! Form::open(['route' => 'transactions.store', 'method' => 'post', 'id' => 'form-buy']) !!}
                        {!! Form::hidden('type', Transaction::TYPE_TRADE) !!}
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    {!! Form::label('from_id', 'From:') !!}
                                    {!! Form::select('from_id', $currencies->pluck('name', 'id'), NULL, ['class' => 'form-control select-2']) !!}
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    {!! Form::label('amount_from', 'Amount:') !!}
                                    {!! Form::text('amount_from', 0.00, ['class' => 'form-control']) !!}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    {!! Form::label('to_id', 'To:') !!}
                                    {!! Form::select('to_id', $currencies->pluck('name', 'id'), NULL, ['class' => 'form-control select-2']) !!}
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    {!! Form::label('amount_to', 'Amount:') !!}
                                    {!! Form::text('amount_to', 0.00, ['class' => 'form-control']) !!}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    {!! Form::submit('Add Transaction', ['class' => 'btn btn-primary']) !!}
                                </div>
                            </div>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection