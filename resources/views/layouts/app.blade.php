<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'AgroManager' }}</title>
    <style>
        :root {
            --green: #2f855a;
            --green-dark: #276749;
            --gray-100: #f7fafc;
            --gray-600: #4a5568;
            --gray-800: #1a202c;
            --white: #ffffff;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
            background: var(--gray-100);
            color: var(--gray-800);
        }

        .container {
            width: min(1100px, 92%);
            margin: 0 auto;
        }

        .navbar {
            background: var(--white);
            border-bottom: 1px solid #e2e8f0;
        }

        .navbar-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1rem 0;
        }

        .brand {
            font-weight: 700;
            color: var(--green-dark);
            text-decoration: none;
            font-size: 1.2rem;
        }

        .hero {
            padding: 3.5rem 0 2rem;
            background: linear-gradient(120deg, #f0fff4 0%, #ebf8ff 100%);
            border-bottom: 1px solid #e2e8f0;
        }

        .hero h1 {
            margin-top: 0;
            font-size: clamp(1.8rem, 4vw, 2.6rem);
        }

        .hero p {
            color: var(--gray-600);
            max-width: 760px;
            line-height: 1.6;
        }

        .btn {
            display: inline-block;
            margin-top: 0.8rem;
            background: var(--green);
            color: var(--white);
            text-decoration: none;
            padding: 0.8rem 1.2rem;
            border-radius: 8px;
            font-weight: 600;
        }

        .btn:hover { background: var(--green-dark); }

        .section {
            padding: 2rem 0;
        }

        .cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1rem;
        }

        .card {
            background: var(--white);
            border-radius: 10px;
            padding: 1rem;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.06);
        }

        .card h3 {
            margin: 0 0 0.3rem;
            font-size: 1rem;
            color: var(--gray-600);
        }

        .metric {
            font-size: 1.6rem;
            font-weight: 700;
        }

        footer {
            text-align: center;
            padding: 2rem 0 2.5rem;
            color: var(--gray-600);
            font-size: 0.95rem;
        }
    </style>
</head>
<body>
@include('partials.navbar')

<main>
    @yield('content')
</main>

<footer>
    AgroManager • Start small, ship fast 🚜
</footer>
</body>
</html>
