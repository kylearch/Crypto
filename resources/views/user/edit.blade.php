@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col">
                {!! Form::model($user, ['route' => 'user.update', 'method' => 'post']) !!}
                <div class="form-group">
                    {!! Form::label('name', 'Name:') !!}
                    {!! Form::text('name', $user->name, ['class' => 'form-control']) !!}
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@endsection