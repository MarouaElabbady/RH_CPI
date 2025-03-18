<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Organigramme - Conseil Provincial d'Ifrane</title>
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
        
        /* Titre de la page */
        .page-title {
            text-align: center;
            color: #2ecc71;
            font-size: 42px;
            margin-bottom: 50px;
            font-weight: 800;
            text-shadow: 0 0 15px rgba(46, 204, 113, 0.3);
            letter-spacing: 1px;
            position: relative;
        }
        
        .page-title::after {
            content: '';
            position: absolute;
            bottom: -15px;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 3px;
            background: linear-gradient(90deg, transparent, #2ecc71, transparent);
        }
        
        /* Structure de l'organigramme */
        .org-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-top: 50px;
        }
        
        .org-president {
            background: linear-gradient(145deg, #1e1e1e, #2a2a2a);
            border: 2px solid #2ecc71;
            border-radius: 15px;
            padding: 25px 40px;
            text-align: center;
            margin-bottom: 50px;
            position: relative;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3);
            max-width: 350px;
        }
        
        .org-president h3 {
            font-size: 24px;
            color: #2ecc71;
            margin-bottom: 10px;
        }
        
        .org-president p {
            font-size: 16px;
            color: #ddd;
        }
        
        /* Ligne de connexion verticale */
        .org-president::after {
            content: '';
            position: absolute;
            top: 100%;
            left: 50%;
            height: 50px;
            width: 3px;
            background: linear-gradient(to bottom, #2ecc71, rgba(46, 204, 113, 0.1));
        }
        
        /* Container pour les services */
        .services-row {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 40px;
            position: relative;
            width: 100%;
        }
        
        /* Ligne de connexion horizontale */
        .services-row::before {
            content: '';
            position: absolute;
            top: -25px;
            left: 50%;
            transform: translateX(-50%);
            width: 80%;
            height: 3px;
            background: linear-gradient(to right, rgba(46, 204, 113, 0.1), #2ecc71, rgba(46, 204, 113, 0.1));
        }
        
        .service-box {
            background: linear-gradient(145deg, #1e1e1e, #2a2a2a);
            border-radius: 15px;
            padding: 30px;
            text-align: center;
            width: 280px;
            position: relative;
            transition: all 0.4s ease;
            border: 1px solid rgba(255, 255, 255, 0.05);
            cursor: pointer;
            overflow: hidden;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3);
        }
        
        .service-box::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(145deg, rgba(46, 204, 113, 0.05), rgba(46, 204, 113, 0.1));
            opacity: 0;
            transition: opacity 0.4s;
            z-index: 0;
        }
        
        .service-box:hover {
            transform: translateY(-10px);
            border-color: rgba(46, 204, 113, 0.3);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.4);
        }
        
        .service-box:hover::before {
            opacity: 1;
        }
        
        .service-box * {
            position: relative;
            z-index: 1;
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
            font-size: 20px;
            color: white;
            font-weight: 600;
            margin-bottom: 15px;
            transition: color 0.3s;
        }
        
        .service-box:hover .service-title {
            color: #2ecc71;
        }
        
        .service-desc {
            font-size: 14px;
            color: #bbb;
            line-height: 1.5;
        }
        
        /* Lien pour les services */
        .service-link {
            text-decoration: none;
            color: inherit;
            display: block;
            width: 280px; /* Même largeur que service-box */
        }
        
        /* Pied de page */
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
        
        /* Animation pour les services */
        .service-box {
            animation: fadeUp 0.8s forwards;
            opacity: 0;
        }
        
        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .service-link:nth-child(1) { animation-delay: 0.1s; }
        .service-link:nth-child(2) { animation-delay: 0.3s; }
        .service-link:nth-child(3) { animation-delay: 0.5s; }
        .service-link:nth-child(4) { animation-delay: 0.7s; }
        
        /* Bouton de retour */
        .back-button {
            display: inline-block;
            margin: 30px auto 0;
            padding: 12px 25px;
            background: linear-gradient(45deg, #219653, #2ecc71);
            color: white;
            text-align: center;
            text-decoration: none;
            font-size: 16px;
            font-weight: 600;
            border-radius: 50px;
            transition: all 0.3s;
            box-shadow: 0 5px 15px rgba(46, 204, 113, 0.4);
        }
        
        .back-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(46, 204, 113, 0.6);
        }
        
        /* Media queries pour la responsivité */
        @media (max-width: 1200px) {
            .main-container {
                margin: 30px 20px;
                padding: 30px;
            }
        }
        
        @media (max-width: 768px) {
            .page-title {
                font-size: 32px;
            }
            
            .services-row {
                flex-direction: column;
                align-items: center;
            }
            
            .service-link {
                width: 100%;
                max-width: 320px;
                margin-bottom: 30px;
            }
            
            .services-row::before {
                width: 3px;
                height: 100%;
                top: -25px;
                left: 50%;
                transform: translateX(-50%);
                background: linear-gradient(to bottom, rgba(46, 204, 113, 0.1), #2ecc71, rgba(46, 204, 113, 0.1));
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
            
            .page-title {
                font-size: 28px;
            }
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="logo">🏛️</div>
        <h1 class="site-title">Conseil Provincial d'Ifrane</h1>
    </header>
    
    <div class="main-container">
        <h1 class="page-title">Organigramme</h1>
        
        <div class="org-container">
            <div class="org-president">
                <h3>Président du Conseil</h3>
                <p>Direction Générale</p>
            </div>
            
            <div class="services-row">
                <a href="ressources_humaines.php" class="service-link">
                    <div class="service-box">
                        <div class="service-icon">👥</div>
                        <div class="service-title">RESSOURCES HUMAINES</div>
                        <div class="service-desc">Gestion du personnel, recrutement, formation et développement des compétences.</div>
                    </div>
                </a>
                
                <a href="service_marches.php" class="service-link">
                    <div class="service-box">
                        <div class="service-icon">📊</div>
                        <div class="service-title">SERVICE DES MARCHÉS</div>
                        <div class="service-desc">Appels d'offres, adjudications, suivi des contrats et des fournisseurs.</div>
                    </div>
                </a>
                
                <a href="service_logistique.php" class="service-link">
                    <div class="service-box">
                        <div class="service-icon">🚚</div>
                        <div class="service-title">SERVICE LOGISTIQUE</div>
                        <div class="service-desc">Gestion des équipements, flottes de véhicules et approvisionnements.</div>
                    </div>
                </a>
                
                <a href="service_etudes.php" class="service-link">
                    <div class="service-box">
                        <div class="service-icon">📝</div>
                        <div class="service-title">SERVICE DES ÉTUDES ET SUIVI DES PROJETS</div>
                        <div class="service-desc">Élaboration, planification et suivi des projets de développement.</div>
                    </div>
                </a>
            </div>
            
            <a href="acceuil.php" class="back-button">Retour à l'accueil</a>
        </div>
    </div>
    
    <footer class="footer">
        © 2025 - <span>Conseil Provincial d'Ifrane</span> - Tous droits réservés
    </footer>
    
    <script>
        // Animation des éléments au scroll
        document.addEventListener('DOMContentLoaded', function() {
            // Ajouter un effet de survol pour montrer l'interactivité de tous les services
            const serviceLinks = document.querySelectorAll('a.service-link .service-box');
            serviceLinks.forEach(function(service) {
                service.addEventListener('mouseenter', function() {
                    this.style.borderColor = '#2ecc71';
                    this.style.boxShadow = '0 15px 30px rgba(46, 204, 113, 0.4)';
                });
                
                service.addEventListener('mouseleave', function() {
                    this.style.borderColor = '';
                    this.style.boxShadow = '';
                });
            });
        });
    </script>
</body>
</html>