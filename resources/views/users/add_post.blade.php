@extends('layout')

@section('title') Proposer un article - Mon compte @endsection

@section('content')

    <h1>Proposer un article</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    
    <p><a href="{{ route('user.posts') }}" class="btn btn-primary"><= Revenir à la liste</a></p>

    {!! form($form) !!}

@endsection