@extends('layouts.app')

@section('content')
    <section class="kartica" style="max-width:520px;margin:0 auto;">
        <h1>Prijava korisnika</h1>
        <p class="pomocni-tekst">Prijavi se da koristiš lični istorijat i čuvanje proračuna.</p>

        @if ($errors->any())
            <div class="greske">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('login.store') }}">
            @csrf
            <label for="email">Email</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" required>

            <label for="password" style="margin-top:.65rem;">Lozinka</label>
            <input id="password" name="password" type="password" required>

            <label style="display:flex; align-items:center; gap:.45rem; margin-top:.65rem; font-weight:600;">
                <input type="checkbox" name="remember" value="1" style="width:auto; min-height:auto;">
                Zapamti me
            </label>

            <button class="dugme" style="margin-top:.9rem; width:100%;">Prijavi se</button>
        </form>
    </section>
@endsection
