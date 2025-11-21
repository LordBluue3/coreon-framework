<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coreon Framework</title>
    <style>
        /* Reset básico */
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: "Segoe UI", Roboto, sans-serif;
            background: linear-gradient(135deg, #e0f2ff, #f4f6f8);
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            animation: backgroundShift 20s infinite alternate;
        }

        @keyframes backgroundShift {
            0% { background-position: 0 0; }
            100% { background-position: 100% 100%; }
        }

        .container {
            background: #fff;
            padding: 2.5rem 3rem;
            border-radius: 16px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.12);
            text-align: center;
            max-width: 500px;
            animation: fadeIn 1s ease forwards;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        h1 {
            color: #1d4ed8;
            font-size: 2.2rem;
            margin-bottom: 0.8rem;
        }

        p {
            font-size: 1rem;
            margin-bottom: 1.5rem;
            line-height: 1.5;
        }

        .button {
            display: inline-block;
            background: #1d4ed8;
            color: #fff;
            padding: 0.7rem 1.5rem;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 500;
            transition: transform 0.2s, background 0.2s;
        }

        .button:hover {
            background: #2563eb;
            transform: scale(1.05);
        }

        .features {
            margin-top: 2rem;
            text-align: left;
        }

        .feature {
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.8rem;
        }

        .feature-icon {
            width: 28px;
            height: 28px;
            color: #1d4ed8;
        }

        footer {
            margin-top: 2rem;
            font-size: 0.85rem;
            color: #777;
        }

        /* Ícones simples com CSS */
        .dot {
            width: 10px;
            height: 10px;
            background: #1d4ed8;
            border-radius: 50%;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1><?= htmlspecialchars($name) ?>!</h1>
        <p><?= htmlspecialchars($name) ?> é um micro framework criado para estudos. A intenção é desenvolver tudo manualmente sem depender de bibliotecas externas, ideal para aprendizado e projetos leves.</p>
        <a href="https://github.com/LordBluue3/coreon-framework" class="button">Documentação</a>

        <footer>&copy; 2025 Mikael Oliveira</footer>
    </div>
</body>

</html>
