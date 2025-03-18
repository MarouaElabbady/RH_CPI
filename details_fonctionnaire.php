<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "RH_CPI";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Échec de connexion: " . $conn->connect_error);
}

// Vérifier si l'ID est fourni
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: situation_personnelle.php");
    exit();
}

$id = intval($_GET['id']);

// Récupérer toutes les informations du fonctionnaire
$sql = "SELECT sa.*, sp.DATE_NAISSANCE, sp.SITUATION_FAMILIALE, sp.NBR_ENFANT, 
                sp.TELEPHONE, sp.SEXE
        FROM situation_administratif sa
        LEFT JOIN situation_personnelle sp ON sa.PPR = sp.PPR
        WHERE sa.idFonctionaire = $id";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    // Le fonctionnaire n'existe pas
    header("Location: situation_personnelle.php?message=fonctionnaire_inexistant");
    exit();
}

$row = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails du Fonctionnaire - Conseil Provincial d'Ifrane</title>
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
            max-width: 900px;
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
        
        /* Style pour les détails */
        .details-container {
            background-color: #222;
            border-radius: 10px;
            padding: 30px;
            margin-top: 40px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }
        
        .details-section {
            margin-bottom: 25px;
            border-bottom: 1px solid #333;
            padding-bottom: 25px;
        }
        
        .details-section:last-child {
            border-bottom: none;
            padding-bottom: 0;
            margin-bottom: 0;
        }
        
        .section-title {
            color: #2ecc71;
            font-size: 24px;
            margin-bottom: 20px;
            padding-left: 15px;
            border-left: 4px solid #2ecc71;
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }
        
        .info-item {
            margin-bottom: 15px;
        }
        
        .info-label {
            font-size: 14px;
            color: #999;
            margin-bottom: 5px;
        }
        
        .info-value {
            font-size: 18px;
            color: #fff;
            font-weight: 500;
        }
        
        /* Boutons */
        .button-container {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 40px;
        }
        
        .action-button {
            padding: 12px 25px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
            text-align: center;
        }
        
        .edit-button {
            background: linear-gradient(45deg, #2980b9, #3498db);
            color: white;
        }
        
        .back-button {
            background: linear-gradient(45deg, #333, #555);
            color: white;
        }
        
        .delete-button {
            background: linear-gradient(45deg, #c0392b, #e74c3c);
            color: white;
        }
        
        .action-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
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
        
        /* Responsive */
        @media (max-width: 768px) {
            .main-container {
                margin: 30px 20px;
                padding: 30px;
            }
            
            .page-title {
                font-size: 32px;
            }
            
            .info-grid {
                grid-template-columns: 1fr;
            }
            
            .button-container {
                flex-direction: column;
            }
            
            .action-button {
                width: 100%;
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
        <h1 class="page-title">Détails du Fonctionnaire</h1>
        
        <div class="details-container">
            <div class="details-section">
                <h2 class="section-title">Informations Administratives</h2>
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">PPR</div>
                        <div class="info-value"><?= htmlspecialchars($row['PPR']) ?></div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">CIN</div>
                        <div class="info-value"><?= htmlspecialchars($row['CNIE']) ?></div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Nom</div>
                        <div class="info-value"><?= htmlspecialchars($row['NOM']) ?></div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Prénom</div>
                        <div class="info-value"><?= htmlspecialchars($row['PRENOM']) ?></div>
                    </div>
                </div>
            </div>
            
            <div class="details-section">
                <h2 class="section-title">Informations Personnelles</h2>
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">Date de Naissance</div>
                        <div class="info-value">
                            <?= $row['DATE_NAISSANCE'] ? htmlspecialchars(date('d/m/Y', strtotime($row['DATE_NAISSANCE']))) : 'Non renseigné' ?>
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Sexe</div>
                        <div class="info-value"><?= htmlspecialchars($row['SEXE'] ?? 'Non renseigné') ?></div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Situation Familiale</div>
                        <div class="info-value"><?= htmlspecialchars($row['SITUATION_FAMILIALE'] ?? 'Non renseigné') ?></div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Nombre d'Enfants</div>
                        <div class="info-value"><?= htmlspecialchars($row['NBR_ENFANT'] ?? '0') ?></div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Téléphone</div>
                        <div class="info-value"><?= htmlspecialchars($row['TELEPHONE'] ?? 'Non renseigné') ?></div>
                    </div>
                </div>
            </div>
            
            <!-- Vous pourriez ajouter d'autres sections ici, comme les informations professionnelles -->
        </div>
        
        <div class="button-container">
            <a href="modifier_fonctionnaire.php?id=<?= $id ?>" class="action-button edit-button">Modifier</a>
            <a href="supprimer_fonctionnaire.php?id=<?= $id ?>" class="action-button delete-button" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce fonctionnaire ?')">Supprimer</a>
            <a href="situation_personnelle.php" class="action-button back-button">Retour à la Liste</a>
        </div>
    </div>
    
    <footer class="footer">
        <p>&copy; <?= date('Y') ?> <span>Conseil Provincial d'Ifrane</span> - Tous droits réservés</p>
    </footer>
</body>
</html>
<?php
$conn->close();
?>