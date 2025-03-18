<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ressources Humaines - Conseil Provincial d'Ifrane</title>
    <style>
        /* Réinitialisation et styles de base */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, Helvetica, sans-serif;
            background-color: #ffffff;
            color: #333333;
            line-height: 1.5;
        }
        
        /* En-tête */
        .header {
            background-color: #0d5e21;
            padding: 15px 30px;
            display: flex;
            align-items: center;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }
        
        .logo {
            font-size: 24px;
            margin-right: 15px;
        }
        
        .site-title {
            font-size: 20px;
            font-weight: bold;
            color: white;
        }
        
        /* Conteneur principal */
        .main-container {
            max-width: 1100px;
            margin: 30px auto;
            padding: 25px;
            background-color: #fff;
            border: 1px solid #e6e6e6;
        }
        
        /* Titre de la page */
        .page-title {
            text-align: center;
            color: #0d5e21;
            font-size: 24px;
            margin-bottom: 30px;
            font-weight: bold;
            border-bottom: 2px solid #e6e6e6;
            padding-bottom: 10px;
        }
        
        /* Container pour les fonctions RH */
        .rh-functions {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }
        
        .function-card {
            background: #fff;
            border: 1px solid #e6e6e6;
            padding: 20px;
            text-align: center;
            height: 100%;
        }
        
        .function-card:hover {
            background-color: #f9f9f9;
            border-color: #cccccc;
        }
        
        .function-icon {
            font-size: 28px;
            margin-bottom: 12px;
            color: #0d5e21;
        }
        
        .function-title {
            font-size: 16px;
            color: #333;
            font-weight: bold;
            margin-bottom: 10px;
        }
        
        .function-desc {
            font-size: 14px;
            color: #666;
        }
        
        .function-link {
            text-decoration: none;
            color: inherit;
            display: block;
            height: 100%;
        }
        
        /* Boutons de retour */
        .back-button {
            display: inline-block;
            padding: 8px 16px;
            background-color: #0d5e21;
            color: white;
            text-align: center;
            text-decoration: none;
            font-size: 14px;
            border: none;
            cursor: pointer;
        }
        
        .back-buttons-container {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-top: 30px;
        }
        
        .back-button:hover {
            background-color: #094718;
        }
        
        /* Pied de page */
        .footer {
            background-color: #f5f5f5;
            text-align: center;
            padding: 15px;
            color: #666;
            border-top: 1px solid #e6e6e6;
            margin-top: 30px;
            font-size: 12px;
        }
        
        /* Media queries pour la responsivité */
        @media (max-width: 768px) {
            .rh-functions {
                grid-template-columns: 1fr 1fr;
            }
            
            .main-container {
                margin: 20px 10px;
                padding: 15px;
            }
        }
        
        @media (max-width: 576px) {
            .header {
                padding: 10px 15px;
            }
            
            .rh-functions {
                grid-template-columns: 1fr;
            }
            
            .back-buttons-container {
                flex-direction: column;
                align-items: center;
                gap: 10px;
            }
            
            .back-button {
                width: 200px;
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
        <h1 class="page-title">Service des Ressources Humaines</h1>
        
        <div class="rh-functions">
            <a href="situation_administrative.php" class="function-link">
                <div class="function-card">
                    <div class="function-icon">📋</div>
                    <div class="function-title">Situation Administrative</div>
                    <div class="function-desc">Gestion des données administratives des fonctionnaires incluant les grades, fonctions et services.</div>
                </div>
            </a>
            
            <a href="situation_personnelle.php" class="function-link">
                <div class="function-card">
                    <div class="function-icon">👤</div>
                    <div class="function-title">Situation Personnelle</div>
                    <div class="function-desc">Gestion des informations personnelles des employés incluant l'état civil, contacts et situation familiale.</div>
                </div>
            </a>
            
            <a href="avancement_grade.php" class="function-link">
                <div class="function-card">
                    <div class="function-icon">📈</div>
                    <div class="function-title">Avancement de Grade</div>
                    <div class="function-desc">Suivi des promotions, échelons, échelles et indices des fonctionnaires.</div>
                </div>
            </a>
            
            <a href="maladie.php" class="function-link">
                <div class="function-card">
                    <div class="function-icon">🏥</div>
                    <div class="function-title">Maladie</div>
                    <div class="function-desc">Gestion des certificats médicaux et suivi des jours d'absence pour raison médicale.</div>
                </div>
            </a>
            
            <a href="conge_permission.php" class="function-link">
                <div class="function-card">
                    <div class="function-icon">🏖️</div>
                    <div class="function-title">Congé et Permission</div>
                    <div class="function-desc">Administration des congés, départs et reliquats de jours de repos.</div>
                </div>
            </a>
            
            <a href="sanction_disciplinaire.php" class="function-link">
                <div class="function-card">
                    <div class="function-icon">⚖️</div>
                    <div class="function-title">Sanction Disciplinaire</div>
                    <div class="function-desc">Suivi des procédures disciplinaires et des sanctions administratives.</div>
                </div>
            </a>
            
            <a href="position_fonctionnaire.php" class="function-link">
                <div class="function-card">
                    <div class="function-icon">🔄</div>
                    <div class="function-title">Position des Fonctionnaires</div>
                    <div class="function-desc">Gestion des décisions administratives et directions réceptrices.</div>
                </div>
            </a>
            
            <a href="mouvement_personnel.php" class="function-link">
                <div class="function-card">
                    <div class="function-icon">🔃</div>
                    <div class="function-title">Mouvement du Personnel</div>
                    <div class="function-desc">Suivi des changements d'affectation, mutations et motifs des mouvements.</div>
                </div>
            </a>
        </div>
        
        <div class="back-buttons-container">
            <a href="organigramme.php" class="back-button">Retour à l'organigramme</a>
            <a href="acceuil.php" class="back-button">Retour à l'accueil</a>
        </div>
    </div>
    
    <footer class="footer">
        © 2025 - Conseil Provincial d'Ifrane - Tous droits réservés
    </footer>
</body>
</html>