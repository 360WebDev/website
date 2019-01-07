@extends('layout')

@section('title') Mes article - Mon compte @endsection

@section('content')

    <h1>Mes articles</h1>

        <p><a href="{{ route('user.add.post') }}" class="btn btn-success">Proposer un article</a></p>
        <table class="table">
            <thead>
                <tr>
                    <th>Titre</th>
                    <th>URL</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            @forelse($posts as $post)
                <tr>
                    @if(!$post->statusIsAccepted() || $post->user()->first()->isAdmin())
                        <td><a href="{{ route('user.update.post', $post) }}">{{ $post->name }}</a></td>
                    @else
                        <td>{{ $post->name }}</td>
                    @endif
                    <td>{{ $post->slug }}</td>
                    <td><span class="badge {{ $post->showBadgeToStatus() }}">{{ ucfirst($post->status) }}</span></td>
                    <td></td>
                </tr>
            @empty
                <tr><td>Vous n'avez pas encore proposé d'article</td></tr>
            @endforelse
            </tbody>
        </table>

@endsection
