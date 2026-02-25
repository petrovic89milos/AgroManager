@extends('layouts.app')

@section('content')
    <section class="kartica">
        <div class="sekcija-header">
            <h1>Moj nalog</h1>
            <p class="pomocni-tekst">Pregled osnovnih informacija i istorije svih sačuvanih proračuna.</p>
        </div>

        <div class="mreza">
            <article class="feature-card">
                <h3>Ime</h3>
                <p class="pomocni-tekst">{{ $user->name }}</p>
            </article>
            <article class="feature-card">
                <h3>Email</h3>
                <p class="pomocni-tekst">{{ $user->email }}</p>
            </article>
            <article class="feature-card">
                <h3>Ukupno proračuna</h3>
                <p class="pomocni-tekst">{{ $histories->count() }}</p>
            </article>
        </div>
    </section>

    <section class="kartica">
        <div class="sekcija-header">
            <h2>Moji proračuni</h2>
            <p class="pomocni-tekst">Naziv se automatski pravi kao kategorija + datum i vreme kada klikneš na "Sačuvaj proračun".</p>
        </div>

        @if($histories->isEmpty())
            <p class="pomocni-tekst">Nema sačuvanih proračuna još uvek. Nakon kalkulacije klikni na dugme "Sačuvaj proračun u nalog".</p>
        @else
            <table>
                <thead>
                    <tr>
                        <th>Naziv</th>
                        <th>Kategorija</th>
                        <th>Vreme</th>
                        <th>Profit (RSD)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($histories as $history)
                        @php
                            $autoNaziv = ucfirst($history->kategorija) . ' - ' . $history->created_at->format('d.m.Y H:i');
                            $profit = data_get($history->result_payload, 'profit', 0);
                        @endphp
                        <tr>
                            <td>{{ $autoNaziv }}</td>
                            <td>{{ ucfirst($history->kategorija) }}</td>
                            <td>{{ $history->created_at->format('d.m.Y H:i') }}</td>
                            <td>{{ number_format((float)$profit, 2, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </section>
@endsection
