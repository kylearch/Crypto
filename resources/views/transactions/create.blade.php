@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-lg-center">
            <div class="col-lg-6">
                <div class="card mt-5">
                    <div class="card-header">Add New Balance</div>
                    <div class="card-body">
                        {!! Form::open(['route' => 'balances.store']) !!}
                            <div class="form-group">
                                <label for="coin_id">Symbol:</label>
                                <select class="form-control" name="coin_id" id="coin_id">
                                    @foreach($coins as $coin)
                                        <option value="{{ $coin->id }}">{{ $coin->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="balance">Balance:</label>
                                <input class="form-control" type="text" name="balance" placeholder="1.00">
                            </div>

                            <div class="form-group">
                                <label for="price">Price:</label>
                                <input class="form-control" type="text" name="price" placeholder="1000">
                            </div>

                            <input class="btn btn-primary" type="submit" value="Submit">
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection