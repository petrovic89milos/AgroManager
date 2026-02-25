@extends('layouts.app')

@section('content')
    <section class="hero">
        <span class="badge">AgroManager • Profesionalna kalkulacija stočarstva</span>
        <h1>Planiranje proizvodnje na ozbiljan i praktičan način</h1>
        <p>
            AgroManager je alat za gazdinstva koja žele jasan odgovor pre ulaska u turnus:
            koliko košta hrana, koliki su svi ostali troškovi, koji je očekivani prihod i gde je break-even tačka.
            Svi obračuni su u RSD i prilagođeni radu na telefonu i desktopu.
        </p>
        <div style="display:flex; gap:.6rem; flex-wrap:wrap; margin-top:.5rem;">
            <a href="{{ route('kalkulator') }}" class="dugme">Pokreni kalkulaciju</a>
            @auth
                <a href="{{ route('account.index') }}" class="dugme dugme-outline">Moj nalog i istorija proračuna</a>
            @else
                <a href="{{ route('login') }}" class="dugme dugme-outline">Prijava korisnika</a>
            @endauth
        </div>
    </section>

    <section class="kartica">
        <h2 class="landing-section-title">Šta dobijaš odmah nakon unosa podataka</h2>
        <p class="pomocni-tekst">Rezultati su organizovani tako da brzo vidiš da li je proizvodnja isplativa.</p>

        <div class="kpi-row">
            <article class="feature-card">
                <small class="pomocni-tekst">Ukupan trošak</small>
                <strong>Hrana + dodatni troškovi</strong>
                <p class="pomocni-tekst">Automatski sabrani svi troškovi relevantni za model proizvodnje.</p>
            </article>
            <article class="feature-card">
                <small class="pomocni-tekst">Prihod i profit</small>
                <strong>Brza procena marže</strong>
                <p class="pomocni-tekst">Jasan prikaz prihoda, profita/gubitka i profitabilnosti po grlu ili jedinici.</p>
            </article>
            <article class="feature-card">
                <small class="pomocni-tekst">Trošak po kg</small>
                <strong>Kontrola cene koštanja</strong>
                <p class="pomocni-tekst">Odmah vidiš koliko te realno košta kilogram proizvodnje.</p>
            </article>
            <article class="feature-card">
                <small class="pomocni-tekst">Break-even / ROI</small>
                <strong>Tačka isplativosti</strong>
                <p class="pomocni-tekst">Lakše poređenje scenarija pre kupovine hrane i ulaska u turnus.</p>
            </article>
        </div>
    </section>

    <section class="content-grid-2" style="margin-bottom:1rem;">
        <article class="kartica">
            <h2 class="landing-section-title">Podržane kategorije i logika obračuna</h2>
            <p class="pomocni-tekst">Modeli su prilagođeni tipičnim potrebama malih i srednjih gazdinstava.</p>
            <ul class="list-clean">
                <li><strong>Brojleri:</strong> fazna raspodela hrane 1/2/3, FCR i trošak po kilogramu proizvodnje.</li>
                <li><strong>Nosilje:</strong> mesečni obračun hrane, broj jaja, cena koštanja jajeta i profit po nosilji.</li>
                <li><strong>Ćurke:</strong> tovni model sa višim završnim masama i prilagođenim cenama smeša.</li>
                <li><strong>Svinje:</strong> obračun preko razlike početne i završne mase + FCR i mortalitet.</li>
            </ul>
        </article>

        <article class="kartica timeline-card">
            <h2 class="landing-section-title">Tipičan tok rada</h2>
            <p class="pomocni-tekst">Kalkulacija traje 2–3 minuta.</p>
            <ul class="list-clean">
                <li>Izaberi proizvodnju i unesi osnovne parametre.</li>
                <li>Po potrebi koriguj cene hrane i prodajnu cenu.</li>
                <li>Klikni „Izračunaj“ i analiziraj rezultat po sekcijama.</li>
                <li>Sačuvaj proračun u nalog i preuzmi PDF za evidenciju.</li>
            </ul>
        </article>
    </section>

    <section class="kartica">
        <h2 class="landing-section-title">Zašto je ovo korisno pre početka turnusa</h2>
        <div class="mreza tri-kolone">
            <article class="feature-card">
                <h3>Bolje odluke o kupovini hrane</h3>
                <p class="pomocni-tekst">Možeš uporediti scenario sa skupljom i jeftinijom hranom pre naručivanja.</p>
            </article>
            <article class="feature-card">
                <h3>Smanjenje rizika</h3>
                <p class="pomocni-tekst">Lakše prepoznaješ kada je proizvodnja blizu gubitka i gde treba korekcija.</p>
            </article>
            <article class="feature-card">
                <h3>Evidencija za dalje planiranje</h3>
                <p class="pomocni-tekst">Sačuvani proračuni olakšavaju praćenje i poređenje kroz više ciklusa.</p>
            </article>
        </div>
    </section>
@endsection
