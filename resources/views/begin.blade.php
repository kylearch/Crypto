@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col">

                <form action="" method="post">
                    {{ csrf_field() }}

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
                </form>
            </div>
        </div>
    </div>
@endsection