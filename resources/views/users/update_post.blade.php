@extends('layout')

@section('title') Proposer un article - Mon compte @endsection

@section('content')

    <h1>Modifier l'article {{ $post->title }}</h1>

    @if ($post->status === 'pending')
        <p class="alert alert-warning">
            Cet article est en cours de validation.
        </p>
    @elseif($post->status === 'writing')
        <p class="alert alert-danger">
            Cet article est en cours de rédaction.
        </p>
    @else
        <p class="alert alert-success">Cet article a été validé.</p>
    @endif
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