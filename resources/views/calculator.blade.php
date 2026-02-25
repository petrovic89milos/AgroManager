@extends('layouts.app')

@section('content')
    <section class="kartica">
        <div class="sekcija-header">
            <h1>Univerzalni kalkulator stočne proizvodnje (Srbija 2026)</h1>
            <p class="pomocni-tekst">Podržane kategorije: brojleri, nosilje, ćurke i svinje. Sva polja su promenljiva i prikazana po jasnim sekcijama.</p>
        </div>
        <div class="mreza tri-kolone" style="margin-top:.65rem;">
            <article class="feature-card">
                <h3>1) Osnovni podaci</h3>
                <p class="pomocni-tekst">Broj grla, mortalitet i ulaganje za ROI analizu.</p>
            </article>
            <article class="feature-card">
                <h3>2) Tehnički parametri</h3>
                <p class="pomocni-tekst">FCR, mase i raspodela hrane po fazama.</p>
            </article>
            <article class="feature-card">
                <h3>3) Finansijski rezultat</h3>
                <p class="pomocni-tekst">Troškovi, prihod, profit i break-even cena.</p>
            </article>
        </div>
    </section>

    @if (session('uspeh'))
        <div class="kartica" style="border-color:#2f855a;">{{ session('uspeh') }}</div>
    @endif

    @if (session('greska'))
        <div class="greske"><strong>{{ session('greska') }}</strong></div>
    @endif

    @if (request()->query('greska') === 'session-nije-dostupna')
        <div class="greske"><strong>Session nije dostupna, proračun nije moguće sačuvati.</strong></div>
    @endif

    @if ($errors->any())
        <div class="greske">
            <strong>Proveri unos:</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <section class="kartica">
        <form method="POST" action="{{ route('kalkulator.izracunaj') }}" id="kalkulator-forma">
            @csrf
            <div class="mreza">
                <div>
                    <label for="kategorija">Kategorija proizvodnje</label>
                    <select id="kategorija" name="kategorija" data-defaults='@json($defaults)' required>
                        <option value="brojleri" @selected(old('kategorija', $unos['kategorija']) === 'brojleri')>Brojleri</option>
                        <option value="nosilje" @selected(old('kategorija', $unos['kategorija']) === 'nosilje')>Nosilje</option>
                        <option value="curke" @selected(old('kategorija', $unos['kategorija']) === 'curke')>Ćurke</option>
                        <option value="svinje" @selected(old('kategorija', $unos['kategorija']) === 'svinje')>Svinje</option>
                    </select>
                </div>
                <div>
                    <label for="broj_grla">Broj grla</label>
                    <input id="broj_grla" name="broj_grla" type="number" min="1" step="1" value="{{ old('broj_grla', $unos['broj_grla']) }}" required>
                </div>
                <div>
                    <label for="mortalitet_procenat">Mortalitet (%)</label>
                    <input id="mortalitet_procenat" name="mortalitet_procenat" type="number" min="0" max="100" step="0.01" value="{{ old('mortalitet_procenat', $unos['mortalitet_procenat']) }}" required>
                </div>
                <div>
                    <label for="investicija">Investicija (RSD) - opciono za ROI</label>
                    <input id="investicija" name="investicija" type="number" min="0" step="0.01" value="{{ old('investicija', $unos['investicija'] ?? 0) }}">
                </div>
            </div>

            <section class="sekcija-obracun" id="blok-tov">
                <div class="sekcija-header">
                    <h3>Tov (brojleri / ćurke / svinje)</h3>
                    <p class="pomocni-tekst">Sekcija za tov: masa, FCR, cene hrane, raspodela i dodatni troškovi.</p>
                </div>
                <div class="mreza">
                    <div class="field-prosecna-masa">
                        <label for="prosecna_masa_kg">Prosečna masa (kg)</label>
                        <input id="prosecna_masa_kg" name="prosecna_masa_kg" type="number" min="0.1" step="0.01" value="{{ old('prosecna_masa_kg', $unos['prosecna_masa_kg'] ?? '') }}">
                    </div>
                    <div class="field-svinje skriveno">
                        <label for="pocetna_masa">Početna masa svinje (kg)</label>
                        <input id="pocetna_masa" name="pocetna_masa" type="number" min="0" step="0.01" value="{{ old('pocetna_masa', $unos['pocetna_masa'] ?? '') }}">
                    </div>
                    <div class="field-svinje skriveno">
                        <label for="zavrsna_masa">Završna masa svinje (kg)</label>
                        <input id="zavrsna_masa" name="zavrsna_masa" type="number" min="0" step="0.01" value="{{ old('zavrsna_masa', $unos['zavrsna_masa'] ?? '') }}">
                    </div>
                    <div>
                        <label for="fcr">FCR</label>
                        <input id="fcr" name="fcr" type="number" min="0.1" step="0.01" value="{{ old('fcr', $unos['fcr'] ?? '') }}">
                    </div>
                    <div>
                        <label for="prodajna_cena_po_kg">Prodajna cena (RSD/kg)</label>
                        <input id="prodajna_cena_po_kg" name="prodajna_cena_po_kg" type="number" min="1" step="0.01" value="{{ old('prodajna_cena_po_kg', $unos['prodajna_cena_po_kg'] ?? '') }}">
                    </div>
                </div>

                <div class="phase-grid">
                    <article class="phase-card">
                        <h4>Hrana 1</h4>
                        <label for="cena_1">Cena 1 (RSD/kg)</label>
                        <input id="cena_1" name="cena_1" type="number" min="0" step="0.01" value="{{ old('cena_1', $unos['cena_1'] ?? '') }}">
                        <label for="procenat_1">Udeo 1 (0-1)</label>
                        <input id="procenat_1" name="procenat_1" type="number" min="0" max="1" step="0.01" value="{{ old('procenat_1', $unos['procenat_1'] ?? '') }}">
                    </article>
                    <article class="phase-card">
                        <h4>Hrana 2</h4>
                        <label for="cena_2">Cena 2 (RSD/kg)</label>
                        <input id="cena_2" name="cena_2" type="number" min="0" step="0.01" value="{{ old('cena_2', $unos['cena_2'] ?? '') }}">
                        <label for="procenat_2">Udeo 2 (0-1)</label>
                        <input id="procenat_2" name="procenat_2" type="number" min="0" max="1" step="0.01" value="{{ old('procenat_2', $unos['procenat_2'] ?? '') }}">
                    </article>
                    <article class="phase-card">
                        <h4>Hrana 3</h4>
                        <label for="cena_3">Cena 3 (RSD/kg)</label>
                        <input id="cena_3" name="cena_3" type="number" min="0" step="0.01" value="{{ old('cena_3', $unos['cena_3'] ?? '') }}">
                        <label for="procenat_3">Udeo 3 (0-1)</label>
                        <input id="procenat_3" name="procenat_3" type="number" min="0" max="1" step="0.01" value="{{ old('procenat_3', $unos['procenat_3'] ?? '') }}">
                    </article>
                </div>

                <div class="mreza" style="margin-top: .7rem;">
                    <div>
                        <label for="stelja">Stelja po grlu (RSD)</label>
                        <input id="stelja" name="stelja" type="number" min="0" step="0.01" value="{{ old('stelja', $unos['stelja'] ?? '') }}">
                    </div>
                    <div>
                        <label for="struja">Struja po grlu (RSD)</label>
                        <input id="struja" name="struja" type="number" min="0" step="0.01" value="{{ old('struja', $unos['struja'] ?? '') }}">
                    </div>
                    <div>
                        <label for="veterinar">Veterinar po grlu (RSD)</label>
                        <input id="veterinar" name="veterinar" type="number" min="0" step="0.01" value="{{ old('veterinar', $unos['veterinar'] ?? '') }}">
                    </div>
                </div>
            </section>

            <section class="sekcija-obracun" id="blok-nosilje">
                <div class="sekcija-header">
                    <h3>Nosilje (mesečni obračun)</h3>
                    <p class="pomocni-tekst">Poseban model za jaja: hrana po danu, broj jaja i profit po nosilji.</p>
                </div>
                <div class="mreza">
                    <div>
                        <label for="dnevna_potrosnja_kg">Dnevna potrošnja hrane po koki (kg)</label>
                        <input id="dnevna_potrosnja_kg" name="dnevna_potrosnja_kg" type="number" min="0" step="0.001" value="{{ old('dnevna_potrosnja_kg', $unos['dnevna_potrosnja_kg'] ?? '') }}">
                    </div>
                    <div>
                        <label for="cena_hrane">Cena hrane (RSD/kg)</label>
                        <input id="cena_hrane" name="cena_hrane" type="number" min="0" step="0.01" value="{{ old('cena_hrane', $unos['cena_hrane'] ?? '') }}">
                    </div>
                    <div>
                        <label for="jaja_po_koki_mesecno">Jaja po koki mesečno</label>
                        <input id="jaja_po_koki_mesecno" name="jaja_po_koki_mesecno" type="number" min="0" step="0.01" value="{{ old('jaja_po_koki_mesecno', $unos['jaja_po_koki_mesecno'] ?? '') }}">
                    </div>
                    <div>
                        <label for="cena_jajeta">Cena jajeta (RSD)</label>
                        <input id="cena_jajeta" name="cena_jajeta" type="number" min="0" step="0.01" value="{{ old('cena_jajeta', $unos['cena_jajeta'] ?? '') }}">
                    </div>
                </div>
            </section>

            <div style="display:flex; gap:0.6rem; flex-wrap:wrap; margin-top:0.9rem;">
                <button class="dugme" type="submit">Izračunaj</button>
                @if ($rezultat)
                    <a class="dugme dugme-outline" href="{{ route('kalkulator.pdf') }}">Preuzmi PDF</a>
                @endif
            </div>
        </form>
    </section>

    @if ($rezultat)
        <section class="kartica">
            <h2>Rezultat proračuna</h2>
            <div class="rezultat-grid">
                <div class="metric"><small>Realan broj grla</small><strong>{{ number_format($rezultat['realan_broj'], 2, ',', '.') }}</strong></div>
                <div class="metric"><small>Ukupan trošak</small><strong>{{ number_format($rezultat['ukupan_trosak'], 2, ',', '.') }} RSD</strong></div>
                <div class="metric"><small>Prihod</small><strong>{{ number_format($rezultat['prihod'], 2, ',', '.') }} RSD</strong></div>
                <div class="metric"><small>Profit</small><strong class="{{ $rezultat['profit'] >= 0 ? 'ok' : 'lose' }}">{{ number_format($rezultat['profit'], 2, ',', '.') }} RSD</strong></div>
            </div>

            @if ($rezultat['tip'] === 'nosilje')
                <div class="mreza">
                    <p><strong>Mesečna hrana:</strong> {{ number_format($rezultat['mesecna_hrana'], 2, ',', '.') }} kg</p>
                    <p><strong>Ukupan broj jaja:</strong> {{ number_format($rezultat['ukupan_broj_jaja'], 2, ',', '.') }}</p>
                    <p><strong>Cena koštanja jajeta:</strong> {{ number_format($rezultat['cena_kostanja_jajeta'], 2, ',', '.') }} RSD</p>
                    <p><strong>Profit po nosilji:</strong> {{ number_format($rezultat['profit_po_nosilji'], 2, ',', '.') }} RSD</p>
                </div>
            @else
                <div class="mreza">
                    <p><strong>Ukupna masa:</strong> {{ number_format($rezultat['ukupna_masa'], 2, ',', '.') }} kg</p>
                    <p><strong>Ukupno hrane:</strong> {{ number_format($rezultat['ukupno_hrane'], 2, ',', '.') }} kg</p>
                    <p><strong>Stvarni FCR:</strong> {{ number_format($rezultat['stvarni_fcr'], 3, ',', '.') }}</p>
                    <p><strong>Trošak po kg proizvodnje:</strong> {{ number_format($rezultat['trosak_po_kg'], 2, ',', '.') }} RSD/kg</p>
                    <p><strong>Profit po grlu:</strong> {{ number_format($rezultat['profit_po_grlu'], 2, ',', '.') }} RSD</p>
                    <p><strong>Break-even cena:</strong> {{ number_format($rezultat['break_even_cena'], 2, ',', '.') }} RSD</p>
                </div>
            @endif

            @if (!is_null($rezultat['roi_procenat']))
                <p><strong>ROI:</strong> {{ number_format($rezultat['roi_procenat'], 2, ',', '.') }}%</p>
            @endif

            <div style="display:flex; gap:0.6rem; flex-wrap:wrap; margin:0.8rem 0 1rem;">
                @if (\Illuminate\Support\Facades\Route::has('kalkulator.sacuvaj'))
                    @auth
                        <form method="POST" action="{{ route('kalkulator.sacuvaj') }}">
                            @csrf
                            <button class="dugme" type="submit">Sačuvaj proračun u nalog</button>
                        </form>
                    @else
                        <a class="dugme dugme-outline" href="{{ route('login') }}">Prijavi se da sačuvaš proračun</a>
                    @endauth
                @else
                    <p class="pomocni-tekst">Čuvanje proračuna trenutno nije dostupno (ruta nije učitana).</p>
                @endif
            </div>

            <h3>Struktura troškova</h3>
            <div class="chart-wrap">
                <canvas id="grafikTroskova" width="260" height="260" data-labels='@json($rezultat['grafik']['labels'])' data-values='@json($rezultat['grafik']['values'])'></canvas>
            </div>
        </section>
    @endif

    <script>
        const kategorijaEl = document.getElementById('kategorija');
        const defaults = JSON.parse(kategorijaEl.dataset.defaults);
        const blockTov = document.getElementById('blok-tov');
        const blockNosilje = document.getElementById('blok-nosilje');
        const svinjeFields = document.querySelectorAll('.field-svinje');
        const prosecnaMasaField = document.querySelector('.field-prosecna-masa');

        const tovFieldIds = ['prosecna_masa_kg', 'pocetna_masa', 'zavrsna_masa', 'fcr', 'prodajna_cena_po_kg', 'cena_1', 'cena_2', 'cena_3', 'procenat_1', 'procenat_2', 'procenat_3', 'stelja', 'struja', 'veterinar'];
        const nosiljeFieldIds = ['dnevna_potrosnja_kg', 'cena_hrane', 'jaja_po_koki_mesecno', 'cena_jajeta'];

        function setValue(id, value) {
            const el = document.getElementById(id);
            if (el && value !== undefined && value !== null) {
                el.value = value;
            }
        }

        function toggleCategoryUI() {
            const category = kategorijaEl.value;
            const isNosilje = category === 'nosilje';
            const isSvinje = category === 'svinje';

            blockTov.classList.toggle('skriveno', isNosilje);
            blockNosilje.classList.toggle('skriveno', !isNosilje);

            svinjeFields.forEach((el) => el.classList.toggle('skriveno', !isSvinje));
            prosecnaMasaField.classList.toggle('skriveno', isSvinje);
        }

        function applyDefaults() {
            const category = kategorijaEl.value;
            const data = defaults[category];
            if (!data) return;

            setValue('broj_grla', data.broj_grla);
            setValue('mortalitet_procenat', data.mortalitet_procenat);
            setValue('investicija', data.investicija ?? 0);

            tovFieldIds.forEach((id) => setValue(id, data[id]));
            nosiljeFieldIds.forEach((id) => setValue(id, data[id]));
        }

        function drawCostChart() {
            const canvas = document.getElementById('grafikTroskova');
            if (!canvas) return;

            const labels = JSON.parse(canvas.dataset.labels || '[]');
            const values = JSON.parse(canvas.dataset.values || '[]').map(Number);
            const total = values.reduce((a, b) => a + b, 0);
            if (total <= 0) return;

            const ctx = canvas.getContext('2d');
            const colors = ['#2f855a', '#68d391', '#a0aec0', '#f6ad55'];
            let start = -Math.PI / 2;

            values.forEach((value, index) => {
                const angle = (value / total) * Math.PI * 2;
                ctx.beginPath();
                ctx.moveTo(130, 130);
                ctx.arc(130, 130, 110, start, start + angle);
                ctx.closePath();
                ctx.fillStyle = colors[index % colors.length];
                ctx.fill();
                start += angle;
            });

            ctx.globalCompositeOperation = 'destination-out';
            ctx.beginPath();
            ctx.arc(130, 130, 55, 0, Math.PI * 2);
            ctx.fill();
            ctx.globalCompositeOperation = 'source-over';

            ctx.fillStyle = '#2d3748';
            ctx.font = '12px Arial';
            labels.forEach((label, index) => {
                ctx.fillStyle = colors[index % colors.length];
                ctx.fillRect(8, 10 + index * 18, 10, 10);
                ctx.fillStyle = '#2d3748';
                ctx.fillText(label, 24, 19 + index * 18);
            });
        }

        kategorijaEl.addEventListener('change', () => {
            applyDefaults();
            toggleCategoryUI();
        });

        toggleCategoryUI();
        drawCostChart();
    </script>
@endsection
