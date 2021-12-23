<nav style="background-color:#f7ce05;border:none;" class="main-header navbar navbar-expand navbar-light navbar-white">
    <div class="container">
        <a href="{{url('')}}" class="navbar-brand">
            <img src="{{asset('img/logo.png')}}" alt="image" class="brand-image">
            <!-- <span class="brand-text font-weight-light">KopiKita</span> -->
        </a>

        <!-- Left navbar links -->
        @if (Auth::user()->hasAnyRole('admin'))

        <ul class="navbar-nav">
            <li class="nav-item dropdown">
                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                    {{ __('Manage') }} <span class="caret"></span>
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                    <a href="{{route('product.index')}}" class="nav-link">Produk</a>
                    <a href="{{route('shop.index')}}" class="nav-link">Toko</a>
                    <a href="{{route('users.index')}}" class="nav-link">Pegawai</a>
                    <a href="{{route('categories.index')}}" class="nav-link">Kategori</a>
                    <a href="{{url('rekap')}}" class="nav-link">Rekap</a>
                </div>

            </li>
        </ul>
        @endif

        <!-- Right navbar links -->
        <ul class="navbar-nav ml-auto">
            <!-- Authentication Links -->
            @guest
            <li class="nav-item">
                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
            </li>
            @if (Route::has('register'))
            <li class="nav-item">
                <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
            </li>
            @endif
            @else
            <li class="nav-item dropdown">
                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                    {{ Auth::user()->name }} <span class="caret"></span>
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                <a class="dropdown-item" href="{{url('reset/'.Auth::user()->id)}}">
                    {{ __('Ubah Password') }}
                </a>

                @if (Auth::user()->hasAnyRole('kasir'))
                <a class="dropdown-item" href="{{url('rekap/'.Auth::user()->id)}}">
                    {{ __('Rekap Penjualan') }}
                </a>
                @endif
                    <a class="dropdown-item" href="{{ route('logout') }}"
                    onclick="event.preventDefault();
                    document.getElementById('logout-form').submit();">
                    {{ __('Logout') }}
                </a>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>

        </li>
        @endguest
    </ul>
</div>
</nav>
