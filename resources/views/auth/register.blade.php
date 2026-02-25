@extends('layouts.app')

@section('content')
    <section class="kartica" style="max-width:560px;margin:0 auto;">
        <h1>Registracija korisnika</h1>
        <p class="pomocni-tekst">Kreiraj nalog da bi čuvao svoje proračune i istorijat.</p>

        @if ($errors->any())
            <div class="greske">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register.store') }}">
            @csrf
            <label for="name">Ime i prezime</label>
            <input id="name" name="name" type="text" value="{{ old('name') }}" required>

            <label for="email" style="margin-top:.65rem;">Email</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" required>

            <label for="password" style="margin-top:.65rem;">Lozinka</label>
            <input id="password" name="password" type="password" required>

            <label for="password_confirmation" style="margin-top:.65rem;">Potvrdi lozinku</label>
            <input id="password_confirmation" name="password_confirmation" type="password" required>

            <button class="dugme" style="margin-top:.9rem; width:100%;">Napravi nalog</button>
        </form>
    </section>
@endsection
