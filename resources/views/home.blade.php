@extends('layouts.app')

@section('content')
    <section class="hero">
        <div class="container">
            <h1>Dobrodošao u AgroManager</h1>
            <p>
                Ovo je početna verzija sajta. Sljedeći koraci su dodavanje autentikacije,
                CRUD-a za farme i životinje, te evidencija troškova i prihoda.
            </p>
            <a href="#next-steps" class="btn">Nastavi razvoj</a>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <h2>Brzi pregled</h2>
            <div class="cards">
                <article class="card">
                    <h3>Broj farmi</h3>
                    <p class="metric">{{ $stats['farms'] }}</p>
                </article>
                <article class="card">
                    <h3>Broj životinja</h3>
                    <p class="metric">{{ $stats['animals'] }}</p>
                </article>
                <article class="card">
                    <h3>Troškovi (mjesec)</h3>
                    <p class="metric">{{ number_format($stats['monthly_expense'], 2) }} €</p>
                </article>
            </div>
        </div>
    </section>

    <section class="section" id="next-steps">
        <div class="container">
            <h2>Šta dalje gradimo?</h2>
            <ul>
                <li>Autentikaciju korisnika (Breeze)</li>
                <li>Modul Farma (listanje + unos)</li>
                <li>Modul Životinje (vezano za farmu)</li>
                <li>Troškovi i prihodi sa mjesečnim izvještajem</li>
            </ul>
        </div>
    </section>
@endsection
