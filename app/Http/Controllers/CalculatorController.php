<?php

namespace App\Http\Controllers;

use App\Models\CalculationHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Throwable;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CalculatorController extends Controller
{
    private const DEFAULTS = [
        'brojleri' => [
            'broj_grla' => 100,
            'mortalitet_procenat' => 5,
            'prosecna_masa_kg' => 2.2,
            'fcr' => 1.75,
            'prodajna_cena_po_kg' => 350,
            'cena_1' => 78,
            'cena_2' => 72,
            'cena_3' => 66,
            'procenat_1' => 0.23,
            'procenat_2' => 0.38,
            'procenat_3' => 0.39,
            'stelja' => 120,
            'struja' => 150,
            'veterinar' => 100,
            'investicija' => 0,
        ],
        'nosilje' => [
            'broj_grla' => 100,
            'mortalitet_procenat' => 3,
            'dnevna_potrosnja_kg' => 0.115,
            'cena_hrane' => 64,
            'jaja_po_koki_mesecno' => 25,
            'cena_jajeta' => 22,
            'investicija' => 0,
        ],
        'curke' => [
            'broj_grla' => 50,
            'mortalitet_procenat' => 5,
            'prosecna_masa_kg' => 12,
            'fcr' => 2.6,
            'prodajna_cena_po_kg' => 480,
            'cena_1' => 92,
            'cena_2' => 85,
            'cena_3' => 78,
            'procenat_1' => 0.20,
            'procenat_2' => 0.45,
            'procenat_3' => 0.35,
            'stelja' => 250,
            'struja' => 300,
            'veterinar' => 200,
            'investicija' => 0,
        ],
        'svinje' => [
            'broj_grla' => 20,
            'mortalitet_procenat' => 3,
            'pocetna_masa' => 25,
            'zavrsna_masa' => 120,
            'fcr' => 2.8,
            'prodajna_cena_po_kg' => 260,
            'cena_1' => 95,
            'cena_2' => 78,
            'cena_3' => 72,
            'procenat_1' => 0.15,
            'procenat_2' => 0.45,
            'procenat_3' => 0.40,
            'stelja' => 1500,
            'struja' => 2000,
            'veterinar' => 1200,
            'investicija' => 0,
        ],
    ];

    public function index()
    {
        $unos = ['kategorija' => 'brojleri'] + self::DEFAULTS['brojleri'];

        return view('calculator', [
            'rezultat' => null,
            'unos' => $unos,
            'defaults' => self::DEFAULTS,
        ]);
    }

    public function calculate(Request $request)
    {
        $unos = $request->validate([
            'kategorija' => 'required|in:brojleri,nosilje,curke,svinje',
            'broj_grla' => 'required|integer|min:1|max:1000000',
            'mortalitet_procenat' => 'required|numeric|min:0|max:100',
            'investicija' => 'nullable|numeric|min:0|max:1000000000',

            'prosecna_masa_kg' => 'nullable|numeric|min:0.1|max:100',
            'pocetna_masa' => 'nullable|numeric|min:0|max:500',
            'zavrsna_masa' => 'nullable|numeric|min:0|max:500',
            'fcr' => 'nullable|numeric|min:0.1|max:10',
            'prodajna_cena_po_kg' => 'nullable|numeric|min:1|max:100000',
            'cena_1' => 'nullable|numeric|min:0|max:100000',
            'cena_2' => 'nullable|numeric|min:0|max:100000',
            'cena_3' => 'nullable|numeric|min:0|max:100000',
            'procenat_1' => 'nullable|numeric|min:0|max:1',
            'procenat_2' => 'nullable|numeric|min:0|max:1',
            'procenat_3' => 'nullable|numeric|min:0|max:1',
            'stelja' => 'nullable|numeric|min:0|max:100000',
            'struja' => 'nullable|numeric|min:0|max:100000',
            'veterinar' => 'nullable|numeric|min:0|max:100000',

            'dnevna_potrosnja_kg' => 'nullable|numeric|min:0|max:10',
            'cena_hrane' => 'nullable|numeric|min:0|max:100000',
            'jaja_po_koki_mesecno' => 'nullable|numeric|min:0|max:1000',
            'cena_jajeta' => 'nullable|numeric|min:0|max:10000',
        ]);

        $unos['investicija'] = (float) ($unos['investicija'] ?? 0);

        $rezultat = $unos['kategorija'] === 'nosilje'
            ? $this->calculateNosilje($unos)
            : $this->calculateTov($unos);

        if ($request->hasSession()) {
            $request->session()->put('pdf_podaci', ['unos' => $unos, 'rezultat' => $rezultat]);
        }

        return view('calculator', [
            'rezultat' => $rezultat,
            'unos' => $unos,
            'defaults' => self::DEFAULTS,
        ]);
    }

    public function saveCalculation(Request $request)
    {
        if (! $request->hasSession()) {
            return redirect()->route('kalkulator', ['greska' => 'session-nije-dostupna']);
        }

        $user = $request->user();
        if (! $user) {
            return redirect()->route('login')->with('greska', 'Prijavi se da bi mogao da sačuvaš proračun.');
        }

        $podaci = $request->session()->get('pdf_podaci');

        if (! $podaci) {
            return redirect()->route('kalkulator')->with('greska', 'Prvo klikni na "Izračunaj", pa tek onda sačuvaj proračun.');
        }

        if (! $this->canStoreHistory()) {
            return back()->with('greska', 'Baza nije spremna za čuvanje proračuna. Pokreni migracije.');
        }

        try {
            CalculationHistory::create([
                'user_id' => $user->id,
                'kategorija' => $podaci['unos']['kategorija'] ?? 'nepoznato',
                'input_payload' => $podaci['unos'] ?? [],
                'result_payload' => $podaci['rezultat'] ?? [],
            ]);
        } catch (Throwable $e) {
            report($e);

            return back()->with('greska', 'Došlo je do greške pri čuvanju proračuna. Proveri bazu i pokušaj ponovo.');
        }

        return back()->with('uspeh', 'Proračun je uspešno sačuvan u nalogu.');
    }

    public function downloadPdf(Request $request): StreamedResponse
    {
        if (! $request->hasSession()) {
            abort(400, 'Session nije dostupna.');
        }

        $podaci = $request->session()->get('pdf_podaci');
        abort_if(! $podaci, 404);

        $unos = $podaci['unos'];
        $rezultat = $podaci['rezultat'];

        $linije = [
            'AgroManager - Univerzalni kalkulator stocne proizvodnje (Srbija 2026)',
            'Kategorija: ' . ucfirst($unos['kategorija']),
            'Broj grla: ' . number_format($unos['broj_grla'], 0, ',', '.'),
            'Realan broj nakon mortaliteta: ' . number_format($rezultat['realan_broj'], 2, '.', ''),
            'Ukupan trosak (RSD): ' . number_format($rezultat['ukupan_trosak'], 2, '.', ''),
            'Prihod (RSD): ' . number_format($rezultat['prihod'], 2, '.', ''),
            'Profit (RSD): ' . number_format($rezultat['profit'], 2, '.', ''),
            'Trosak po kg proizvodnje (RSD): ' . number_format($rezultat['trosak_po_kg'], 2, '.', ''),
            'Break-even cena (RSD/kg): ' . number_format($rezultat['break_even_cena'], 2, '.', ''),
        ];

        if (isset($rezultat['roi_procenat'])) {
            $linije[] = 'ROI (%): ' . number_format($rezultat['roi_procenat'], 2, '.', '');
        }

        $pdf = $this->simplePdf($linije);

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf;
        }, 'agromanager-proracun.pdf', ['Content-Type' => 'application/pdf']);
    }

    private function calculateTov(array $unos): array
    {
        $required = ['fcr', 'prodajna_cena_po_kg', 'cena_1', 'cena_2', 'cena_3', 'procenat_1', 'procenat_2', 'procenat_3', 'stelja', 'struja', 'veterinar'];
        foreach ($required as $field) {
            if (!isset($unos[$field])) {
                throw ValidationException::withMessages([$field => 'Nedostaje obavezno polje.']);
            }
        }

        $zbirProcenata = (float) $unos['procenat_1'] + (float) $unos['procenat_2'] + (float) $unos['procenat_3'];
        if (abs($zbirProcenata - 1.0) > 0.0001) {
            throw ValidationException::withMessages(['procenat_1' => 'Zbir procenata hrane mora biti 1.00 (npr. 0.23 + 0.38 + 0.39).']);
        }

        $realanBroj = (float) $unos['broj_grla'] * (1 - ((float) $unos['mortalitet_procenat'] / 100));

        if ($unos['kategorija'] === 'svinje') {
            if (!isset($unos['pocetna_masa'], $unos['zavrsna_masa'])) {
                throw ValidationException::withMessages(['pocetna_masa' => 'Za svinje su obavezne početna i završna masa.']);
            }
            $razlikaMase = (float) $unos['zavrsna_masa'] - (float) $unos['pocetna_masa'];
            if ($razlikaMase <= 0) {
                throw ValidationException::withMessages(['zavrsna_masa' => 'Završna masa mora biti veća od početne.']);
            }
            $ukupnaMasa = $realanBroj * $razlikaMase;
        } else {
            if (!isset($unos['prosecna_masa_kg'])) {
                throw ValidationException::withMessages(['prosecna_masa_kg' => 'Unesi prosečnu masu.']);
            }
            $ukupnaMasa = $realanBroj * (float) $unos['prosecna_masa_kg'];
        }

        $ukupnoHrane = $ukupnaMasa * (float) $unos['fcr'];
        $hrana1 = $ukupnoHrane * (float) $unos['procenat_1'];
        $hrana2 = $ukupnoHrane * (float) $unos['procenat_2'];
        $hrana3 = $ukupnoHrane * (float) $unos['procenat_3'];

        $trosakHrane =
            ($hrana1 * (float) $unos['cena_1']) +
            ($hrana2 * (float) $unos['cena_2']) +
            ($hrana3 * (float) $unos['cena_3']);

        $dodatniTroskovi = $realanBroj * ((float) $unos['stelja'] + (float) $unos['struja'] + (float) $unos['veterinar']);
        $ukupanTrosak = $trosakHrane + $dodatniTroskovi;
        $prihod = $ukupnaMasa * (float) $unos['prodajna_cena_po_kg'];
        $profit = $prihod - $ukupanTrosak;

        $stvarniFcr = $ukupnaMasa > 0 ? $ukupnoHrane / $ukupnaMasa : 0;
        $trosakPoKg = $ukupnaMasa > 0 ? $ukupanTrosak / $ukupnaMasa : 0;
        $profitPoGrlu = $realanBroj > 0 ? $profit / $realanBroj : 0;
        $breakEven = $ukupnaMasa > 0 ? $ukupanTrosak / $ukupnaMasa : 0;
        $roi = $unos['investicija'] > 0 ? ($profit / $unos['investicija']) * 100 : null;

        return [
            'tip' => 'tov',
            'realan_broj' => $realanBroj,
            'ukupna_masa' => $ukupnaMasa,
            'ukupno_hrane' => $ukupnoHrane,
            'hrana_1' => $hrana1,
            'hrana_2' => $hrana2,
            'hrana_3' => $hrana3,
            'trosak_hrane' => $trosakHrane,
            'dodatni_troskovi' => $dodatniTroskovi,
            'ukupan_trosak' => $ukupanTrosak,
            'prihod' => $prihod,
            'profit' => $profit,
            'stvarni_fcr' => $stvarniFcr,
            'trosak_po_kg' => $trosakPoKg,
            'profit_po_grlu' => $profitPoGrlu,
            'break_even_cena' => $breakEven,
            'roi_procenat' => $roi,
            'grafik' => [
                'labels' => ['Trošak hrane', 'Dodatni troškovi'],
                'values' => [$trosakHrane, $dodatniTroskovi],
            ],
        ];
    }

    private function calculateNosilje(array $unos): array
    {
        $required = ['dnevna_potrosnja_kg', 'cena_hrane', 'jaja_po_koki_mesecno', 'cena_jajeta'];
        foreach ($required as $field) {
            if (!isset($unos[$field])) {
                throw ValidationException::withMessages([$field => 'Nedostaje obavezno polje za nosilje.']);
            }
        }

        $realanBroj = (float) $unos['broj_grla'] * (1 - ((float) $unos['mortalitet_procenat'] / 100));
        $mesecnaHrana = $realanBroj * (float) $unos['dnevna_potrosnja_kg'] * 30;
        $trosakHrane = $mesecnaHrana * (float) $unos['cena_hrane'];

        $ukupanBrojJaja = $realanBroj * (float) $unos['jaja_po_koki_mesecno'];
        $prihod = $ukupanBrojJaja * (float) $unos['cena_jajeta'];
        $profit = $prihod - $trosakHrane;

        $cenaKostanjaJajeta = $ukupanBrojJaja > 0 ? $trosakHrane / $ukupanBrojJaja : 0;
        $profitPoNosilji = $realanBroj > 0 ? $profit / $realanBroj : 0;
        $roi = $unos['investicija'] > 0 ? ($profit / $unos['investicija']) * 100 : null;

        return [
            'tip' => 'nosilje',
            'realan_broj' => $realanBroj,
            'mesecna_hrana' => $mesecnaHrana,
            'trosak_hrane' => $trosakHrane,
            'ukupan_trosak' => $trosakHrane,
            'ukupan_broj_jaja' => $ukupanBrojJaja,
            'prihod' => $prihod,
            'profit' => $profit,
            'cena_kostanja_jajeta' => $cenaKostanjaJajeta,
            'profit_po_nosilji' => $profitPoNosilji,
            'stvarni_fcr' => null,
            'trosak_po_kg' => 0,
            'profit_po_grlu' => $profitPoNosilji,
            'break_even_cena' => $ukupanBrojJaja > 0 ? $trosakHrane / $ukupanBrojJaja : 0,
            'roi_procenat' => $roi,
            'grafik' => [
                'labels' => ['Trošak hrane', 'Profit'],
                'values' => [$trosakHrane, max($profit, 0)],
            ],
        ];
    }


    private function canStoreHistory(): bool
    {
        return Schema::hasTable('calculation_histories')
            && Schema::hasColumns('calculation_histories', [
                'user_id',
                'kategorija',
                'input_payload',
                'result_payload',
            ]);
    }

    private function simplePdf(array $lines): string
    {
        $escapedLines = array_map(static function (string $line): string {
            return str_replace(['\\', '(', ')'], ['\\\\', '\\(', '\\)'], $line);
        }, $lines);

        $commands = ['BT', '/F1 11 Tf', '40 790 Td'];
        foreach ($escapedLines as $index => $line) {
            if ($index > 0) {
                $commands[] = '0 -16 Td';
            }
            $commands[] = sprintf('(%s) Tj', $line);
        }
        $commands[] = 'ET';

        $stream = implode("\n", $commands);

        $objects = [];
        $objects[] = '1 0 obj << /Type /Catalog /Pages 2 0 R >> endobj';
        $objects[] = '2 0 obj << /Type /Pages /Kids [3 0 R] /Count 1 >> endobj';
        $objects[] = '3 0 obj << /Type /Page /Parent 2 0 R /MediaBox [0 0 595 842] /Resources << /Font << /F1 4 0 R >> >> /Contents 5 0 R >> endobj';
        $objects[] = '4 0 obj << /Type /Font /Subtype /Type1 /BaseFont /Helvetica >> endobj';
        $objects[] = sprintf('5 0 obj << /Length %d >> stream' . "\n%s\nendstream endobj", strlen($stream), $stream);

        $pdf = "%PDF-1.4\n";
        $offsets = [0];
        foreach ($objects as $obj) {
            $offsets[] = strlen($pdf);
            $pdf .= $obj . "\n";
        }

        $xrefPos = strlen($pdf);
        $pdf .= 'xref' . "\n";
        $pdf .= sprintf('0 %d', count($offsets)) . "\n";
        $pdf .= "0000000000 65535 f \n";
        for ($i = 1; $i < count($offsets); $i++) {
            $pdf .= sprintf('%010d 00000 n ', $offsets[$i]) . "\n";
        }

        $pdf .= 'trailer << /Size ' . count($offsets) . ' /Root 1 0 R >>' . "\n";
        $pdf .= 'startxref' . "\n" . $xrefPos . "\n%%EOF";

        return $pdf;
    }
}
