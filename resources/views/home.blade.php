@extends('layouts.app')

@section('content')
    <section class="kartica">
        <h1>Kalkulator troškova i profita</h1>
        <p class="pomocni-tekst">
            Unesi količinu hrane i cene u dinarima. Možeš ručno uneti cene ili izabrati predefinisane cene iz cenovnika.
        </p>
    </section>

    @if ($errors->any())
        <div class="greske">
            <strong>Proveri unos:</strong>
            <ul>
                @foreach ($errors->all() as $greska)
                    <li>{{ $greska }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <section class="kartica">
        <form method="POST" action="{{ route('kalkulator.izracunaj') }}">
            @csrf

            <div class="mreza">
                <div>
                    <label for="vrsta_proizvodnje">Vrsta proizvodnje</label>
                    <select id="vrsta_proizvodnje" name="vrsta_proizvodnje" data-cenovnik='@json($cenovnik)' required>
                        <option value="brojleri" @selected(($stariUnos['vrsta_proizvodnje'] ?? old('vrsta_proizvodnje')) === 'brojleri')>Brojleri</option>
                        <option value="nosilje" @selected(($stariUnos['vrsta_proizvodnje'] ?? old('vrsta_proizvodnje')) === 'nosilje')>Nosilje</option>
                        <option value="svinje" @selected(($stariUnos['vrsta_proizvodnje'] ?? old('vrsta_proizvodnje')) === 'svinje')>Svinje</option>
                    </select>
                </div>
                <div>
                    <label for="kolicina_hrane_kg">Ukupna količina hrane (kg)</label>
                    <input id="kolicina_hrane_kg" name="kolicina_hrane_kg" type="number" min="1" step="0.01" value="{{ old('kolicina_hrane_kg', $stariUnos['kolicina_hrane_kg']) }}" required>
                </div>
            </div>

            <h3>Udeo sastava hrane (%)</h3>
            <div class="mreza">
                <div>
                    <label for="udeo_kukuruza">Kukuruz (%)</label>
                    <input id="udeo_kukuruza" name="udeo_kukuruza" type="number" min="0" max="100" step="0.01" value="{{ old('udeo_kukuruza', $stariUnos['udeo_kukuruza']) }}" required>
                </div>
                <div>
                    <label for="udeo_soje">Soja (%)</label>
                    <input id="udeo_soje" name="udeo_soje" type="number" min="0" max="100" step="0.01" value="{{ old('udeo_soje', $stariUnos['udeo_soje']) }}" required>
                </div>
                <div>
                    <label for="udeo_premiksa">Premiks (%)</label>
                    <input id="udeo_premiksa" name="udeo_premiksa" type="number" min="0" max="100" step="0.01" value="{{ old('udeo_premiksa', $stariUnos['udeo_premiksa']) }}" required>
                </div>
            </div>

            <h3>Cene hrane (RSD/kg)</h3>
            <p class="pomocni-tekst">Promeni po potrebi ili odaberi vrstu proizvodnje da se učitaju predefinisane cene.</p>
            <div class="mreza">
                <div>
                    <label for="cena_kukuruza">Cena kukuruza (RSD/kg)</label>
                    <input id="cena_kukuruza" name="cena_kukuruza" type="number" min="0" step="0.01" value="{{ old('cena_kukuruza', $stariUnos['cena_kukuruza']) }}" required>
                </div>
                <div>
                    <label for="cena_soje">Cena soje (RSD/kg)</label>
                    <input id="cena_soje" name="cena_soje" type="number" min="0" step="0.01" value="{{ old('cena_soje', $stariUnos['cena_soje']) }}" required>
                </div>
                <div>
                    <label for="cena_premiksa">Cena premiksa (RSD/kg)</label>
                    <input id="cena_premiksa" name="cena_premiksa" type="number" min="0" step="0.01" value="{{ old('cena_premiksa', $stariUnos['cena_premiksa']) }}" required>
                </div>
            </div>

            <h3>Prodaja i prihod</h3>
            <div class="mreza">
                <div>
                    <label for="prodajna_cena_po_kg">Prodajna cena proizvoda (RSD/kg)</label>
                    <input id="prodajna_cena_po_kg" name="prodajna_cena_po_kg" type="number" min="0" step="0.01" value="{{ old('prodajna_cena_po_kg', $stariUnos['prodajna_cena_po_kg']) }}" required>
                </div>
                <div>
                    <label for="proizvodnja_kg">Ukupna proizvodnja (kg)</label>
                    <input id="proizvodnja_kg" name="proizvodnja_kg" type="number" min="1" step="0.01" value="{{ old('proizvodnja_kg', $stariUnos['proizvodnja_kg']) }}" required>
                </div>
            </div>

            <button type="submit" class="dugme">Izračunaj trošak i profit</button>
        </form>
    </section>

    @if ($rezultat)
        <section class="kartica">
            <h2>Rezultat proračuna</h2>
            <p><strong>Ukupna količina hrane:</strong> {{ number_format($stariUnos['kolicina_hrane_kg'], 2, ',', '.') }} kg ({{ number_format($rezultat['kolicina_tona'], 2, ',', '.') }} t)</p>
            <p><strong>Ukupan trošak ishrane:</strong> {{ number_format($rezultat['ukupni_trosak'], 2, ',', '.') }} RSD</p>
            <p><strong>Ukupan prihod:</strong> {{ number_format($rezultat['ukupan_prihod'], 2, ',', '.') }} RSD</p>
            <p>
                <strong>Profit / gubitak:</strong>
                <span class="{{ $rezultat['profit'] >= 0 ? 'rezultat-pozitivan' : 'rezultat-negativan' }}">
                    {{ number_format($rezultat['profit'], 2, ',', '.') }} RSD
                </span>
            </p>
            <p><strong>Trošak po kg proizvodnje:</strong> {{ number_format($rezultat['trosak_po_kg_proizvodnje'], 2, ',', '.') }} RSD/kg</p>

            <h3>Detaljan prikaz troškova hrane</h3>
            <table>
                <thead>
                    <tr>
                        <th>Sastojak</th>
                        <th>Količina (kg)</th>
                        <th>Trošak (RSD)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Kukuruz</td>
                        <td>{{ number_format($rezultat['detalji']['kukuruz']['kg'], 2, ',', '.') }}</td>
                        <td>{{ number_format($rezultat['detalji']['kukuruz']['din'], 2, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td>Soja</td>
                        <td>{{ number_format($rezultat['detalji']['soja']['kg'], 2, ',', '.') }}</td>
                        <td>{{ number_format($rezultat['detalji']['soja']['din'], 2, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td>Premiks</td>
                        <td>{{ number_format($rezultat['detalji']['premiks']['kg'], 2, ',', '.') }}</td>
                        <td>{{ number_format($rezultat['detalji']['premiks']['din'], 2, ',', '.') }}</td>
                    </tr>
                </tbody>
            </table>
        </section>
    @endif

    <script>
        const vrstaProizvodnje = document.getElementById('vrsta_proizvodnje');
        const cenovnik = JSON.parse(vrstaProizvodnje.dataset.cenovnik);

        function postaviPredefinisaneCene(vrsta) {
            if (!cenovnik[vrsta]) {
                return;
            }

            document.getElementById('cena_kukuruza').value = cenovnik[vrsta].kukuruz;
            document.getElementById('cena_soje').value = cenovnik[vrsta].soja;
            document.getElementById('cena_premiksa').value = cenovnik[vrsta].premiks;
        }

        vrstaProizvodnje.addEventListener('change', (event) => {
            postaviPredefinisaneCene(event.target.value);
        });
    </script>
@endsection
