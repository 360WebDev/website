@extends('layout')

@section('content')

    <div class="row">

        <!-- Post Content Column -->
        <div class="col-lg-8">

            <!-- Title -->
            <h1 class="mt-4">{{ $post->name }}</h1>
            <hr>
            <!-- Author -->
            <p class="lead">
                De <a href="#"><strong>{{ $post->user->name }}</strong></a> | Créé le {{ $post->getCreatedAt() }}
                | Catégorie : <strong><a href="">{{ $post->category->name }}</a></strong>
            </p>

            <hr>

            <!-- Preview Image -->
            <img class="img-fluid rounded" src="{{ $post->getImage('crop') ?: 'http://placehold.it/900x300' }}" alt="">

            <hr>

            <!-- Post Content -->
            <p>{!! nl2br($post->html) !!}</p>
            <hr>

            <!-- Comments Form -->
            @if(!Auth::guest())
                <div class="card my-4">
                    <h5 class="card-header">Ajouter un commentaire</h5>
                    <div class="card-body" id="comment">
                        <form method="post" action="{{ route('comment.store', $post->slug) }}">
                            {{ csrf_field() }}
                            <input type="hidden" value="{{ $post->id }}" name="post_id">
                            <div class="form-group">
                                <textarea class="form-control" rows="3" placeholder="Contenu" name="content"></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Ajouter</button>
                        </form>
                    </div>
                </div>
            @else
                <p>Vous devez être inscrit afin poster un commentaire - <a href="{{ route('register') }}">Créer un compte</a></p>
            @endif

            <!-- Comments List -->
            @include('partials/comments')

            <!-- Pagination -->
            <ul class="pagination justify-content-center mb-4">
                {{ $comments->links() }}
            </ul>

        </div>
    @include('partials/sidebar')

@endsection