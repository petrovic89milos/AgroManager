<nav class="navbar">
    <div class="container navbar-content">
        <a href="{{ route('pocetna') }}" class="brand">
            <img src="{{ asset('pictures/agrilogo.png') }}" alt="AgroManager logo">
            <span>AgroManager</span>
        </a>
        <div class="nav-right">
            <div class="nav-links">
                <a href="{{ route('pocetna') }}" class="nav-link">Početna</a>
                <a href="{{ route('kalkulator') }}" class="nav-link">Kalkulacija</a>
            </div>

            @auth
                <details class="account-menu">
                    <summary>Moj nalog ▾</summary>
                    <div class="account-dropdown">
                        <p><strong>{{ auth()->user()->name }}</strong></p>
                        <p>{{ auth()->user()->email }}</p>
                        <a href="{{ route('account.index') }}" class="auth-link login">Pregled naloga</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="auth-link register" style="cursor:pointer; border:0; width:100%;">Odjava</button>
                        </form>
                    </div>
                </details>
            @else
                <div class="nav-links">
                    <a href="{{ route('login') }}" class="auth-link login" aria-label="Prijava korisnika">Prijava</a>
                    <a href="{{ route('register') }}" class="auth-link register" aria-label="Registracija korisnika">Registracija</a>
                </div>
            @endauth
        </div>
    </div>
</nav>
