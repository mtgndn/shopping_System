<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Ana Sayfa - Alışveriş Sitesi</title>
    <style>
        /* Genel düzen */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f0f2f5;
            margin: 0;
            padding: 0;
            color: #333;
        }
        header {
            background-color: #007bff;
            color: white;
            padding: 15px 30px;
            text-align: center;
            font-size: 24px;
            font-weight: 700;
            letter-spacing: 1px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }
        nav {
            background: white;
            padding: 10px 30px;
            display: flex;
            justify-content: flex-end;
            gap: 20px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.05);
        }
        nav a {
            color: #007bff;
            font-weight: 600;
            text-decoration: none;
            transition: color 0.3s ease;
        }
        nav a:hover {
            color: #0056b3;
        }
        main {
            max-width: 960px;
            margin: 40px auto;
            padding: 0 15px;
        }
        .welcome {
            text-align: center;
            margin-bottom: 40px;
        }
        .welcome h1 {
            font-size: 32px;
            margin-bottom: 10px;
        }
        .welcome p {
            font-size: 18px;
            color: #555;
        }
        .btn {
            display: inline-block;
            padding: 14px 28px;
            font-size: 18px;
            font-weight: 600;
            color: white;
            background-color: #28a745;
            border-radius: 8px;
            text-decoration: none;
            box-shadow: 0 5px 10px rgba(40, 167, 69, 0.4);
            transition: background-color 0.3s ease;
        }
        .btn:hover {
            background-color: #218838;
        }

        /* Responsive */
        @media (max-width: 600px) {
            nav {
                justify-content: center;
                flex-wrap: wrap;
                gap: 10px;
            }
            main {
                margin: 20px 15px;
            }
            .welcome h1 {
                font-size: 26px;
            }
            .btn {
                font-size: 16px;
                padding: 12px 24px;
            }
        }
    </style>
</head>
<body>

<header>Alışveriş Sitesi</header>

<nav>
    <a href="products.php">Ürünleri Görüntüle</a>
    <a href="cart.php">🛒 Sepetim</a>
    <a href="logout.php">Çıkış Yap</a>
</nav>

<main>
    <section class="welcome">
        <h1>Hoşgeldiniz!</h1>
        <p>En kaliteli ürünleri en uygun fiyatlarla keşfedin.</p>
        <a href="products.php" class="btn">Alışverişe Başla</a>
    </section>
</main>

</body>
</html>
