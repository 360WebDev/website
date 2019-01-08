<nav class="navbar navbar-expand-md navbar-dark fixed-top bg-primary">
    <div class="container">
        <a class="navbar-brand" href="{{ route('home.index') }}">360° Dev</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarsExampleDefault">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link {{ Menu::isActive('Posts') }}" href="{{ route('blog.index') }}">Blog</a>
                </li>
                @auth
                    @if (auth()->user()->isAdmin())
                        <li class="nav-item"><a href="{{ route('admin.index') }}" class="nav-link">Administration</a></li>
                    @endif
                @endauth
            </ul>
            <ul class="navbar-nav my-2 my-lg-0">
                @if (Route::has('login'))
                    @auth
                        @if(auth()->user()->notifications->isNotEmpty())
                            <li class="nav-item dropdown mt-2">
                                <a href="#" class="nav-link dropdown-toggle" id="notifications" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-bell"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="notifications">
                                    @foreach(auth()->user()->notifications as $notification)
                                        <a
                                            href="{{ route('posts.edit.notif', [$notification->data['id'], $notification]) }}"
                                            class="dropdown-item {{ !$notification->read() ? 'active' : 'disabled' }}">{{ $notification->data['title'] }}
                                        </a>
                                    @endforeach
                                    <div class="dropdown-divider"></div>
                                    <a href="{{ route('user.notif.delete') }}" class="dropdown-item">Tout supprimer</a>
                                </div>
                            </li>
                        @else
                            <li class="nav-item dropdown mt-2">
                                <a href="#" class="nav-link dropdown-toggle" id="empty_notifications" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="far fa-bell"></i>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="empty_notifications">
                                    <p class="dropdown-item">Toutes vos notifications</p>
                                </div>
                            </li>
                        @endif
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <img class="rounded-circle" src="{{ auth()->user()->getAvatarUrl() }}" alt="{{ auth()->user()->name }}">
                                {{ auth()->user()->name }}
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('user.account') }}">Mon compte</a>
                                <a class="dropdown-item" href="{{ route('user.favorites') }}">Mes favoris</a>
                                <a class="dropdown-item" href="{{ route('user.posts') }}">Mes articles</a>
                                <form action="{{ route('logout') }}" class="form-inline" method="post">
                                    {{ csrf_field() }}
                                    <button type="submit" href="" class="dropdown-item btn btn-link">Se déconnecter</button>
                                </form>
                            </div>
                        </li>
                    @else
                        <li class="nav-item"><a class="nav-link btn btn-outline-success" href="{{ route('login') }}">Se connecter</a></li>
                        <li class="nav-item"><a class="nav-link btn-outline-default" href="{{ route('register') }}">Créer un compte</a></li>
                    @endauth
                @endif
            </ul>
        </div>
    </div>
</nav>
