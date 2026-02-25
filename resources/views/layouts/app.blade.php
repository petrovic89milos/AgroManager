<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'AgroManager' }}</title>
    <link rel="icon" type="image/png" href="{{ asset('pictures/agrilogo.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('pictures/agrilogo.png') }}">
    <style>
        :root {
            --green-900: #243141;
            --green-700: #3e5a73;
            --green-500: #c57b3a;
            --green-100: #f7ede2;
            --sky-100: #eaf3ff;
            --bg: #f3f0ea;
            --card: #ffffff;
            --text: #1c2732;
            --muted: #5c6776;
            --danger: #b83232;
            --border: #ddd2c6;
            --shadow: 0 16px 36px rgba(31, 27, 20, 0.12);
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            font-family: "Inter", "Segoe UI", Arial, sans-serif;
            color: var(--text);
            background:
                radial-gradient(circle at 20% 10%, rgba(214, 170, 117, 0.14), transparent 35%),
                radial-gradient(circle at 80% 15%, rgba(100, 134, 89, 0.14), transparent 30%),
                linear-gradient(rgba(243, 240, 234, 0.92), rgba(243, 240, 234, 0.94)),
                url("{{ asset('pictures/farm-bg.svg') }}") center top / cover no-repeat fixed,
                var(--bg);
        }

        .container {
            width: min(1120px, 94%);
            margin: 0 auto;
        }

        .navbar {
            position: sticky;
            top: 0;
            z-index: 100;
            backdrop-filter: blur(8px);
            background: rgba(253, 251, 248, 0.92);
            border-bottom: 1px solid var(--border);
        }

        .navbar-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            padding: 0.9rem 0;
        }

        .brand {
            color: var(--green-900);
            font-size: 1.1rem;
            font-weight: 800;
            text-decoration: none;
            letter-spacing: 0.3px;
            display: inline-flex;
            align-items: center;
            gap: .55rem;
            min-width: 0;
        }

        .brand img {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            border: 1px solid #c9e5d0;
            object-fit: cover;
            background: #fff;
            flex-shrink: 0;
        }

        .brand span {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 0.45rem;
            flex-wrap: wrap;
        }

        .nav-right {
            display: flex;
            align-items: center;
            gap: .65rem;
            flex-wrap: wrap;
            justify-content: flex-end;
            min-width: 0;
        }

        .auth-link {
            text-decoration: none;
            font-weight: 700;
            font-size: .9rem;
            border-radius: 999px;
            padding: .42rem .82rem;
            transition: all .2s ease;
        }

        .auth-link.login {
            color: var(--green-900);
            background: #ecf7ef;
            border: 1px solid #c9e5d0;
        }

        .auth-link.register {
            color: #fff;
            background: linear-gradient(135deg, var(--green-500), var(--green-700));
            box-shadow: 0 6px 14px rgba(41, 111, 74, 0.23);
        }

        .auth-link:hover { filter: brightness(1.04); }


        .account-menu {
            position: relative;
        }

        .account-menu summary {
            list-style: none;
            cursor: pointer;
            user-select: none;
            color: var(--green-900);
            font-weight: 700;
            border: 1px solid #c9e5d0;
            background: #ecf7ef;
            border-radius: 999px;
            padding: .42rem .82rem;
        }

        .account-menu summary::-webkit-details-marker { display: none; }

        .account-dropdown {
            position: absolute;
            right: 0;
            top: calc(100% + 8px);
            width: 260px;
            z-index: 120;
            border: 1px solid var(--border);
            border-radius: 12px;
            background: #fff;
            box-shadow: var(--shadow);
            padding: .75rem;
            display: grid;
            gap: .5rem;
        }

        .account-dropdown p {
            margin: 0;
            color: var(--muted);
            font-size: .9rem;
            line-height: 1.35;
            overflow-wrap: anywhere;
        }

        .nav-link {
            text-decoration: none;
            color: var(--muted);
            font-weight: 600;
            border-radius: 999px;
            padding: 0.42rem 0.86rem;
            transition: all .2s ease;
        }

        .nav-link:hover {
            color: var(--green-900);
            background: var(--green-100);
        }

        main { padding: 1.35rem 0 2rem; }

        .kartica {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 1.15rem;
            margin-bottom: 1rem;
            box-shadow: var(--shadow);
        }

        .sekcija-header h3,
        .sekcija-header h2 {
            margin: 0 0 0.25rem;
        }

        .hero {
            position: relative;
            overflow: hidden;
            border-radius: 20px;
            border: 1px solid #cedbe8;
            background:
                linear-gradient(115deg, rgba(19, 39, 58, 0.86), rgba(36, 74, 108, 0.82)),
                url("{{ asset('pictures/farm-bg.svg') }}") center / cover no-repeat;
            color: #f3f8fd;
            padding: 1.6rem;
            margin-bottom: 1rem;
            box-shadow: var(--shadow);
        }

        .hero h1 { margin: 0.4rem 0 0.55rem; font-size: clamp(1.9rem, 4vw, 2.8rem); color: #ffffff; }
        .hero p { color: #d9e7f5; max-width: 760px; line-height: 1.6; }
        .hero .badge { background: rgba(255,255,255,0.14); color: #f2f8ff; border: 1px solid rgba(255,255,255,.35); }

        .badge {
            display: inline-flex;
            align-items: center;
            gap: .35rem;
            border-radius: 999px;
            padding: .3rem .7rem;
            font-size: .82rem;
            color: var(--green-900);
            background: #d6eedf;
            font-weight: 700;
        }

        .mreza { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: .85rem; }
        .tri-kolone { grid-template-columns: repeat(3, minmax(0, 1fr)); }

        @media (max-width: 960px) {
            .tri-kolone, .phase-grid, .rezultat-grid, .kpi-row, .content-grid-2 { grid-template-columns: 1fr !important; }
        }

        @media (max-width: 760px) {
            .navbar-content {
                flex-wrap: wrap;
                row-gap: .55rem;
                padding: .65rem 0;
            }

            .brand {
                width: 100%;
                justify-content: center;
            }

            .brand img { width: 34px; height: 34px; }

            .nav-right {
                width: 100%;
                justify-content: center;
                gap: .5rem;
            }

            .nav-right .nav-links {
                width: 100%;
                justify-content: center;
            }

            .nav-link, .auth-link {
                padding: .5rem .75rem;
                font-size: .88rem;
            }

            .account-menu { width: 100%; }

            .account-menu summary {
                width: 100%;
                text-align: center;
                border-radius: 12px;
            }

            .account-dropdown {
                position: static;
                width: 100%;
                margin-top: .45rem;
            }

            .mreza { grid-template-columns: 1fr; }

            .kartica {
                padding: .9rem;
                border-radius: 14px;
            }

            table {
                display: block;
                overflow-x: auto;
                white-space: nowrap;
            }
        }

        label { display: block; margin-bottom: .35rem; font-weight: 700; font-size: .95rem; }

        .pomocni-tekst { color: var(--muted); font-size: .93rem; margin: .2rem 0 .55rem; line-height: 1.5; }

        input, select {
            width: 100%;
            border: 1px solid #c8d7ca;
            border-radius: 12px;
            min-height: 48px;
            padding: .8rem .74rem;
            font-size: 1rem;
            background: #fff;
            transition: border-color .2s ease, box-shadow .2s ease;
        }

        input:focus, select:focus {
            outline: none;
            border-color: #58a376;
            box-shadow: 0 0 0 4px rgba(94, 176, 117, .15);
        }

        .dugme {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 0;
            border-radius: 12px;
            min-height: 48px;
            padding: .77rem 1.1rem;
            background: linear-gradient(135deg, var(--green-500), var(--green-700));
            color: #fff;
            font-weight: 700;
            text-decoration: none;
            cursor: pointer;
            box-shadow: 0 8px 18px rgba(41, 111, 74, 0.25);
        }

        .dugme:hover { filter: brightness(1.03); }

        .dugme-outline {
            background: #fff;
            color: var(--green-900);
            border: 1px solid #b8d4bf;
            box-shadow: none;
        }

        .dugme-outline:hover { background: #f0f8f2; }

        .greske {
            border: 1px solid #f4c1c1;
            background: #fff5f5;
            color: var(--danger);
            border-radius: 12px;
            padding: .85rem 1rem;
            margin-bottom: 1rem;
        }

        .rezultat-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: .75rem;
            margin-bottom: .75rem;
        }

        .metric {
            background: linear-gradient(180deg, #f9fcf9, #f3faf4);
            border: 1px solid #dcebdd;
            border-radius: 12px;
            padding: .72rem;
        }

        .metric small { display: block; color: var(--muted); margin-bottom: .2rem; }
        .metric strong { font-size: 1.08rem; }

        .ok { color: #1b7c4b; }
        .lose { color: var(--danger); }

        table { width: 100%; border-collapse: collapse; margin-top: .75rem; background: #fff; }
        th, td { text-align: left; padding: .58rem; border-bottom: 1px solid #eaf0eb; font-size: .94rem; }

        .phase-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: .8rem;
            margin-top: .75rem;
        }

        .phase-card {
            border: 1px solid #d9e8db;
            background: linear-gradient(180deg, #fcfffc, #f6fbf6);
            border-radius: 14px;
            padding: .8rem;
        }

        .phase-card h4 {
            margin: 0 0 .45rem;
            color: var(--green-900);
            font-size: .98rem;
        }

        .sekcija-obracun {
            margin-top: 1rem;
            padding-top: .95rem;
            border-top: 1px dashed #c8dccb;
        }

        .chart-wrap {
            margin-top: .7rem;
            display: flex;
            justify-content: center;
            border: 1px solid #e2ece3;
            border-radius: 12px;
            background: #fff;
            padding: .85rem;
        }

        .feature-card {
            border: 1px solid #e0ece1;
            border-radius: 14px;
            background: #fbfefb;
            padding: .9rem;
            box-shadow: 0 4px 12px rgba(30, 60, 40, 0.04);
        }

        .feature-card h3 { margin: 0 0 .35rem; }

        .landing-section-title {
            margin: .2rem 0 .3rem;
            font-size: 1.4rem;
            color: #233140;
        }

        .kpi-row {
            display: grid;
            grid-template-columns: repeat(4, minmax(0,1fr));
            gap: .7rem;
            margin-top: .8rem;
        }

        .kpi-row .feature-card {
            background: linear-gradient(180deg, #fffdfa, #f7f2eb);
            border-color: #e5d8c8;
        }

        .kpi-row strong { font-size: 1.1rem; display:block; margin-top:.3rem; color:#233140; }

        .content-grid-2 {
            display: grid;
            grid-template-columns: 1.15fr 1fr;
            gap: .9rem;
        }

        .list-clean {
            margin: .35rem 0 0;
            padding-left: 1rem;
            color: var(--muted);
            line-height: 1.6;
        }

        .timeline-card {
            border-left: 4px solid #c57b3a;
            padding-left: .8rem;
            background: #fff;
        }

        footer {
            margin-top: .6rem;
            color: #e6f2e8;
            background: linear-gradient(145deg, #10263a, #1d4367);
            border-top: 1px solid rgba(255,255,255,.08);
        }

        .footer-wrap {
            width: min(1120px, 94%);
            margin: 0 auto;
            padding: 1.35rem 0 1.1rem;
        }

        .footer-grid {
            display: grid;
            grid-template-columns: 1.2fr 1fr 1fr;
            gap: 1rem;
            margin-bottom: .8rem;
        }

        .footer-title { margin: 0 0 .45rem; color: #fff; }
        .footer-text, .footer-list { margin: 0; color: #d4e6d8; font-size: .92rem; line-height: 1.5; }
        .footer-list { padding-left: 1rem; }
        .footer-badge {
            display: inline-block;
            margin-left: .4rem;
            font-size: .72rem;
            color: #cce8d3;
            border: 1px solid rgba(255,255,255,.25);
            border-radius: 999px;
            padding: .08rem .45rem;
        }
        .footer-kpi {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: .6rem;
            margin-top: .8rem;
        }
        .footer-kpi div {
            border: 1px solid rgba(255,255,255,.16);
            border-radius: 10px;
            padding: .45rem .5rem;
            background: rgba(255,255,255,.04);
        }
        .footer-kpi small { color: #cce0d1; display:block; }
        .footer-kpi strong { color: #fff; }

        .footer-bottom {
            border-top: 1px solid rgba(255,255,255,.12);
            padding-top: .75rem;
            font-size: .85rem;
            color: #c5ddcb;
            display: flex;
            justify-content: space-between;
            gap: .8rem;
            flex-wrap: wrap;
        }

        .skriveno { display: none !important; }

        @media (max-width: 900px) {
            .footer-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    @include('partials.navbar')
    <main>
        <div class="container">
            @yield('content')
        </div>
    </main>

    <footer>
        <div class="footer-wrap">
            <div class="footer-grid">
                <section>
                    <h3 class="footer-title">AgroManager</h3>
                    <p class="footer-text">Praktičan alat za farmere i mala gazdinstva u Srbiji za planiranje troškova ishrane, procenu profita i donošenje odluka pre ulaska u turnus.</p>
                    <div class="footer-kpi">
                        <div><small>Kategorije</small><strong>4 modela</strong></div>
                        <div><small>Valuta</small><strong>RSD</strong></div>
                        <div><small>Izveštaj</small><strong>PDF eksport</strong></div>
                    </div>
                </section>
                <section>
                    <h3 class="footer-title">Kontakt i podrška</h3>
                    <ul class="footer-list">
                        <li>Email: podrska@agromanager.rs</li>
                        <li>Telefon: +381 60 123 4567</li>
                        <li>Radno vreme: Pon–Pet 08:00–16:00</li>
                        <li>Novi Sad, Srbija</li>
                    </ul>
                </section>
                <section>
                    <h3 class="footer-title">Napomena o podacima</h3>
                    <ul class="footer-list">
                        <li>Proračuni su informativnog karaktera</li>
                        <li>Vrednosti zavise od ulaznih cena i uslova na gazdinstvu</li>
                        <li>Pre odluke uporediti sa stvarnim tržištem i ponudama</li>
                    </ul>
                </section>
            </div>
            <div class="footer-bottom">
                <span>© {{ date('Y') }} AgroManager • Srbija 2026 model</span>
                <span>Napomena: rezultati su informativni i treba ih proveriti prema aktuelnim tržišnim cenama i uslovima na farmi.</span>
            </div>
        </div>
    </footer>
</body>
</html>
