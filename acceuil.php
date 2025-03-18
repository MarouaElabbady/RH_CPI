<!DOCTYPE html>
<html lang="fr">
<headr>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Conseil Provincial d'Ifrane</title>
    <style>
        /* Réinitialisation et styles de base */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #111;
            color: white;
            line-height: 1.6;
        }
        
        /* En-tête avec effet de verre */
        .header {
            background-color: rgba(13, 94, 33, 0.95);
            backdrop-filter: blur(10px);
            padding: 15px 30px;
            display: flex;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.5);
        }
        
        .logo {
            font-size: 32px;
            margin-right: 20px;
            animation: pulse 2s infinite alternate;
        }
        
        @keyframes pulse {
            from { opacity: 0.8; transform: scale(1); }
            to { opacity: 1; transform: scale(1.1); }
        }
        
        .site-title {
            font-size: 26px;
            font-weight: 600;
            margin: 0;
            background: linear-gradient(45deg, #fff, #a3ffb8);
            background-clip: text;
            -webkit-background-clip: text;
            color: transparent;
        }
        
        /* Conteneur principal avec effet de profondeur */
        .main-container {
            max-width: 1300px;
            margin: 40px auto;
            padding: 40px;
            background-color: #1a1a1a;
            border: 1px solid #29802f;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 255, 108, 0.15);
            position: relative;
            overflow: hidden;
        }
        
        .main-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(90deg, #1e8c3a, #2ecc71, #1e8c3a);
            z-index: 1;
        }
        
        /* Titres stylisés */
        .welcome-title {
            text-align: center;
            color: #2ecc71;
            font-size: 48px;
            margin-bottom: 30px;
            font-weight: 800;
            text-shadow: 0 0 15px rgba(46, 204, 113, 0.3);
            letter-spacing: 1px;
        }
        
        .welcome-subtitle {
            text-align: center;
            font-size: 22px;
            margin-bottom: 50px;
            color: #e0e0e0;
            font-weight: 300;
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
        }
        
        /* Conteneur de statistiques redessiné */
        .stats-container {
            display: flex;
            justify-content: space-around;
            margin: 50px 0;
            flex-wrap: wrap;
            perspective: 1000px;
        }
        
        .stat-box {
            background: linear-gradient(145deg, #1e1e1e, #2a2a2a);
            border-radius: 15px;
            padding: 30px;
            text-align: center;
            width: 240px;
            margin: 15px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3), 
                        0 0 0 1px rgba(46, 204, 113, 0.1);
            transition: transform 0.5s, box-shadow 0.5s;
            transform-style: preserve-3d;
            position: relative;
            z-index: 1;
        }
        
        .stat-box::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(145deg, rgba(46, 204, 113, 0.05), rgba(46, 204, 113, 0.1));
            border-radius: 15px;
            z-index: -1;
            opacity: 0;
            transition: opacity 0.5s;
        }
        
        .stat-box:hover {
            transform: translateY(-10px) rotateX(5deg);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.4), 
                        0 0 0 1px rgba(46, 204, 113, 0.2);
        }
        
        .stat-box:hover::before {
            opacity: 1;
        }
        
        .stat-title {
            font-size: 20px;
            margin-bottom: 20px;
            color: #bbb;
            font-weight: 500;
            position: relative;
            display: inline-block;
        }
        
        .stat-title::after {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 50%;
            transform: translateX(-50%);
            width: 40px;
            height: 3px;
            background: linear-gradient(90deg, transparent, #2ecc71, transparent);
        }
        
        .stat-value {
            font-size: 48px;
            font-weight: 700;
            color: #2ecc71;
            text-shadow: 0 0 10px rgba(46, 204, 113, 0.3);
            position: relative;
            display: inline-block;
        }
        
        .stat-value::before {
            content: attr(data-symbol);
            position: absolute;
            font-size: 24px;
            right: -15px;
            top: 5px;
            color: rgba(46, 204, 113, 0.7);
        }
        
        /* Bouton principal avec effet de brillance */
        .main-button {
            display: block;
            width: 350px;
            margin: 50px auto;
            padding: 18px 25px;
            background: linear-gradient(45deg, #219653, #2ecc71);
            color: white;
            text-align: center;
            text-decoration: none;
            font-size: 20px;
            font-weight: 600;
            border-radius: 50px;
            transition: all 0.3s;
            position: relative;
            overflow: hidden;
            box-shadow: 0 8px 25px rgba(46, 204, 113, 0.4);
            letter-spacing: 0.5px;
        }
        
        .main-button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: 0.6s;
        }
        
        .main-button:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 30px rgba(46, 204, 113, 0.6);
            background: linear-gradient(45deg, #27ae60, #33d976);
        }
        
        .main-button:hover::before {
            left: 100%;
        }
        
        /* Conteneur de services amélioré */
        .services-container {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 25px;
            margin-top: 60px;
        }
        
        .service-box {
            background: rgba(39, 39, 39, 0.8);
            border-radius: 12px;
            padding: 35px 30px;
            text-align: center;
            width: 220px;
            transition: all 0.4s ease;
            border: 1px solid rgba(255, 255, 255, 0.05);
            position: relative;
            overflow: hidden;
        }
        
        .service-box::before {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 3px;
            background: linear-gradient(90deg, transparent, #2ecc71, transparent);
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }
        
        .service-box:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.25);
            background: rgba(45, 45, 45, 0.9);
            border-color: rgba(46, 204, 113, 0.2);
        }
        
        .service-box:hover::before {
            transform: scaleX(1);
        }
        
        .service-icon {
            font-size: 48px;
            margin-bottom: 20px;
            color: #2ecc71;
            filter: drop-shadow(0 0 10px rgba(46, 204, 113, 0.3));
            transition: transform 0.3s;
        }
        
        .service-box:hover .service-icon {
            transform: scale(1.1);
        }
        
        .service-title {
            font-size: 18px;
            color: white;
            font-weight: 500;
            transition: color 0.3s;
        }
        
        .service-box:hover .service-title {
            color: #2ecc71;
        }
        
        /* Pied de page amélioré */
        .footer {
            background-color: #0d0d0d;
            text-align: center;
            padding: 30px;
            color: #888;
            border-top: 1px solid #333;
            margin-top: 60px;
            font-size: 14px;
            letter-spacing: 0.5px;
        }
        
        .footer span {
            color: #2ecc71;
        }
        
        /* Animations pour les compteurs */
        @keyframes countUp {
            0% {
                opacity: 0;
                transform: translateY(30px);
            }
            20% {
                opacity: 1;
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .stat-value {
            opacity: 0;
            animation: countUp 2s ease-out forwards;
            animation-delay: calc(var(--delay) * 0.2s);
        }
        
        /* Effet de particules pour l'arrière-plan */
        .particles {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: 0;
        }
        
        .particle {
            position: absolute;
            width: 6px;
            height: 6px;
            background-color: rgba(46, 204, 113, 0.3);
            border-radius: 50%;
            opacity: 0.3;
            animation: float 15s infinite linear;
        }
        
        @keyframes float {
            0% {
                transform: translateY(0) translateX(0) rotate(0deg);
                opacity: 0;
            }
            10% {
                opacity: 0.5;
            }
            90% {
                opacity: 0.5;
            }
            100% {
                transform: translateY(-1000px) translateX(100px) rotate(360deg);
                opacity: 0;
            }
        }
        
        /* Media queries pour la responsivité */
        @media (max-width: 1200px) {
            .main-container {
                margin: 30px 20px;
                padding: 30px;
            }
            
            .welcome-title {
                font-size: 40px;
            }
        }
        
        @media (max-width: 768px) {
            .welcome-title {
                font-size: 32px;
            }
            
            .welcome-subtitle {
                font-size: 18px;
            }
            
            .stat-box {
                width: 200px;
            }
            
            .main-button {
                width: 100%;
                max-width: 300px;
            }
        }
        
        @media (max-width: 480px) {
            .header {
                flex-direction: column;
                padding: 10px;
            }
            
            .logo {
                margin-right: 0;
                margin-bottom: 5px;
            }
            
            .site-title {
                font-size: 22px;
                text-align: center;
            }
            
            .welcome-title {
                font-size: 28px;
            }
            
            .stat-box {
                width: 100%;
                max-width: 250px;
            }
        }
    </style>
</headr>
<body>
    <header class="header">
        <div class="logo" >🏛️</div>
        <h1 class="site-title">Conseil Provincial d'Ifrane</h1>
    </header>
    
    <div class="main-container">
        <!-- Particules d'arrière-plan -->
        <div class="particles" id="particles"></div>
        
        <h1 class="welcome-title">Bienvenue au Conseil Provincial d'Ifrane</h1>
        <p class="welcome-subtitle">Lorem ipsum dolor sit, </p>
        
        <div class="stats-container">
            <div class="stat-box">
                <div class="stat-title">Fonctionnaires</div>
                <div class="stat-value" id="total-count" style="--delay: 1">0</div>
            </div>
            <div class="stat-box">
                <div class="stat-title">Femmes</div>
                <div class="stat-value" id="women-count" style="--delay: 2">0</div>
            </div>
            <div class="stat-box">
                <div class="stat-title">Hommes</div>
                <div class="stat-value" id="men-count" style="--delay: 3">0</div>
            </div>
        </div>
        
        <a href="organigramme.php" class="main-button">Notre Organigramme</a>
        
        <div class="services-container">
            <div class="service-box">
                <div class="service-icon">📄</div>
                <div class="service-title">Documents administratifs</div>
            </div>
            <div class="service-box">
                <div class="service-icon">🏗️</div>
                <div class="service-title">Projets de développement</div>
            </div>
            <div class="service-box">
                <div class="service-icon">📅</div>
                <div class="service-title">Événements</div>
            </div>
            <div class="service-box">
                <div class="service-icon">ℹ️</div>
                <div class="service-title">autre </div>
            </div>
        </div>
    </div>
    
    <footer class="footer">
        © 2025 - <span>Conseil Provincial d'Ifrane</span> - Tous droits réservés
    </footer>

    <script>
        // Créer des particules en arrière-plan
        function createParticles() {
            const particlesContainer = document.getElementById('particles');
            const particleCount = 30;
            
            for (let i = 0; i < particleCount; i++) {
                const particle = document.createElement('div');
                particle.classList.add('particle');
                
                // Position aléatoire
                const posX = Math.random() * 100;
                const posY = Math.random() * 100;
                const size = Math.random() * 5 + 2;
                const delay = Math.random() * 15;
                const duration = Math.random() * 20 + 10;
                
                particle.style.left = `${posX}%`;
                particle.style.top = `${posY}%`;
                particle.style.width = `${size}px`;
                particle.style.height = `${size}px`;
                particle.style.animationDelay = `${delay}s`;
                particle.style.animationDuration = `${duration}s`;
                
                particlesContainer.appendChild(particle);
            }
        }
        
        // Fonction pour animer les compteurs avec format numérique
        function animateCounter(elementId, targetValue, duration) {
            const element = document.getElementById(elementId);
            const start = 0;
            const increment = Math.ceil(targetValue / (duration / 20));
            let current = 0;
            
            const timer = setInterval(() => {
                current += increment;
                if (current >= targetValue) {
                    current = targetValue;
                    clearInterval(timer);
                }
                // Formater le nombre avec des séparateurs de milliers
                element.textContent = current.toLocaleString('fr-FR');
            }, 20);
        }
        
        // Fonction pour charger les données depuis PHP/la base de données
        async function loadStats() {
            try {
                const response = await fetch('get_stats.php');
                const data = await response.json();
                
                // Animer les compteurs
                animateCounter('total-count', data.total, 2500);
                animateCounter('women-count', data.women, 2500);
                animateCounter('men-count', data.men, 2500);
            } catch (error) {
                console.error('Erreur lors du chargement des statistiques:', error);
                // Valeurs de test au cas où la connexion échoue
                animateCounter('total-count', 253, 2500);
                animateCounter('women-count', 98, 2500);
                animateCounter('men-count', 155, 2500);
            }
        }
        
        // Exécuter les fonctions au chargement de la page
        window.addEventListener('load', () => {
            createParticles();
            loadStats();
        });
    </script>
</body>
</html>