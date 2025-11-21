<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coreon Framework</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: "Segoe UI", Roboto, sans-serif;
            background: linear-gradient(to bottom, #0d1b2a, #1d2d50);
            color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            overflow: hidden;
            position: relative;
        }

        canvas {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
        }

        .container {
            position: relative;
            z-index: 1;
            background: rgba(15, 40, 70, 0.85);
            padding: 2.5rem 3rem;
            border-radius: 16px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.5);
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

        footer {
            margin-top: 2rem;
            font-size: 0.85rem;
            color: #aaa;
        }
    </style>
</head>

<body>
    <canvas id="stars"></canvas>

    <div class="container">
        <h1><?= htmlspecialchars($name) ?>!</h1>
        <p><?= htmlspecialchars($name) ?> é um micro framework criado para estudos. A intenção é desenvolver tudo manualmente sem depender de bibliotecas externas, ideal para aprendizado e projetos leves.</p>
        <a href="https://github.com/LordBluue3/coreon-framework" class="button">Documentação</a>

        <footer>&copy; 2025 Mikael Oliveira</footer>
    </div>

    <script>
        const canvas = document.getElementById('stars');
        const ctx = canvas.getContext('2d');
        let stars = [];

        function resizeCanvas() {
            canvas.width = window.innerWidth;
            canvas.height = window.innerHeight;
        }
        window.addEventListener('resize', resizeCanvas);
        resizeCanvas();

        function randomBlue() {
            // tons de azul variados
            const shades = ['#1d4ed8', '#2563eb', '#3b82f6', '#60a5fa', '#93c5fd'];
            return shades[Math.floor(Math.random() * shades.length)];
        }

        function initStars() {
            stars = [];
            for (let i = 0; i < 200; i++) {
                stars.push({
                    x: Math.random() * canvas.width,
                    y: Math.random() * canvas.height,
                    radius: Math.random() * 1.5,
                    speed: Math.random() * 0.5 + 0.1,
                    color: randomBlue()
                });
            }
        }

        function drawStars() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            stars.forEach(star => {
                ctx.fillStyle = star.color;
                ctx.beginPath();
                ctx.arc(star.x, star.y, star.radius, 0, Math.PI * 2);
                ctx.fill();
                star.y -= star.speed;
                if (star.y < 0) star.y = canvas.height;
            });
            requestAnimationFrame(drawStars);
        }

        initStars();
        drawStars();
    </script>
</body>
</html>
