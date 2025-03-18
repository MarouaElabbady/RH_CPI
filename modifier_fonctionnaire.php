<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "RH_CPI";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Échec de connexion: " . $conn->connect_error);
}

$message = '';
$messageType = '';

// Vérifier si l'ID est fourni
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: situation_personnelle.php");
    exit();
}

$id = intval($_GET['id']);

// Récupérer les données du fonctionnaire
$sql = "SELECT sa.*, sp.DATE_NAISSANCE, sp.SITUATION_FAMILIALE, sp.NBR_ENFANT, 
                sp.TELEPHONE, sp.SEXE
        FROM situation_administratif sa
        LEFT JOIN situation_personnelle sp ON sa.PPR = sp.PPR
        WHERE sa.idFonctionaire = $id";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    header("Location: index.php");
    exit();
}

$row = $result->fetch_assoc();

// Traitement du formulaire de modification
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupération et validation des données
    $ppr = mysqli_real_escape_string($conn, $_POST['ppr']);
    $cnie = mysqli_real_escape_string($conn, $_POST['cnie']);
    $nom = mysqli_real_escape_string($conn, $_POST['nom']);
    $prenom = mysqli_real_escape_string($conn, $_POST['prenom']);
    $date_naissance = mysqli_real_escape_string($conn, $_POST['date_naissance']);
    $situation_familiale = mysqli_real_escape_string($conn, $_POST['situation_familiale']);
    $nbr_enfant = mysqli_real_escape_string($conn, $_POST['nbr_enfant']);
    $telephone = mysqli_real_escape_string($conn, $_POST['telephone']);
    $sexe = mysqli_real_escape_string($conn, $_POST['sexe']);
    
    // Mise à jour des données administratives
    $sql_admin = "UPDATE situation_administratif SET 
                  PPR = '$ppr', 
                  CNIE = '$cnie', 
                  NOM = '$nom', 
                  PRENOM = '$prenom' 
                  WHERE idFonctionaire = $id";
    
    if ($conn->query($sql_admin) === TRUE) {
        // Vérifier si les données personnelles existent déjà
        $check_sql = "SELECT * FROM situation_personnelle WHERE PPR = '$ppr'";
        $check_result = $conn->query($check_sql);
        
        if ($check_result->num_rows > 0) {
            // Mise à jour des données personnelles
            $sql_perso = "UPDATE situation_personnelle SET 
                          DATE_NAISSANCE = '$date_naissance', 
                          SITUATION_FAMILIALE = '$situation_familiale', 
                          NBR_ENFANT = '$nbr_enfant', 
                          TELEPHONE = '$telephone', 
                          SEXE = '$sexe' 
                          WHERE PPR = '$ppr'";
        } else {
            // Insertion des données personnelles si elles n'existent pas
            $sql_perso = "INSERT INTO situation_personnelle 
                         (PPR, DATE_NAISSANCE, SITUATION_FAMILIALE, NBR_ENFANT, TELEPHONE, SEXE) 
                         VALUES 
                         ('$ppr', '$date_naissance', '$situation_familiale', '$nbr_enfant', '$telephone', '$sexe')";
        }
        
        if ($conn->query($sql_perso) === TRUE) {
            $message = "Les informations du fonctionnaire ont été mises à jour avec succès!";
            $messageType = "success";
            
            // Mettre à jour les données locales pour l'affichage
            $row = array_merge($row, [
                'PPR' => $ppr,
                'CNIE' => $cnie,
                'NOM' => $nom,
                'PRENOM' => $prenom,
                'DATE_NAISSANCE' => $date_naissance,
                'SITUATION_FAMILIALE' => $situation_familiale,
                'NBR_ENFANT' => $nbr_enfant,
                'TELEPHONE' => $telephone,
                'SEXE' => $sexe
            ]);
            
            // Redirection après 2 secondes
            // Redirection après 2 secondes
header("refresh:2;url=situation_personnelle.php");
        } else {
            $message = "Erreur lors de la mise à jour des informations personnelles: " . $conn->error;
            $messageType = "error";
        }
    } else {
        $message = "Erreur lors de la mise à jour des informations administratives: " . $conn->error;
        $messageType = "error";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier un Fonctionnaire - Conseil Provincial d'Ifrane</title>
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
            max-width: 1000px;
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

        /* Formulaire */
        .form-container {
            margin-top: 40px;
        }
        
        .form-group {
            margin-bottom: 25px;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            font-size: 16px;
            font-weight: 500;
            color: #2ecc71;
        }
        
        input[type="text"],
        input[type="date"],
        input[type="number"],
        input[type="tel"],
        select {
            width: 100%;
            padding: 12px 15px;
            background-color: #333;
            color: white;
            border: 1px solid #444;
            border-radius: 5px;
            font-size: 16px;
            transition: all 0.3s;
        }
        
        input[type="text"]:focus,
        input[type="date"]:focus,
        input[type="number"]:focus,
        input[type="tel"]:focus,
        select:focus {
            border-color: #2ecc71;
            box-shadow: 0 0 8px rgba(46, 204, 113, 0.5);
            outline: none;
        }
        
        .button-container {
            display: flex;
            justify-content: space-between;
            margin-top: 40px;
        }
        
        .submit-button {
            padding: 15px 30px;
            background: linear-gradient(45deg, #219653, #2ecc71);
            color: white;
            border: none;
            border-radius: 50px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
            transition: all 0.3s;
            box-shadow: 0 5px 15px rgba(46, 204, 113, 0.4);
        }
        
        .submit-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(46, 204, 113, 0.6);
        }
        
        .cancel-button {
            padding: 15px 30px;
            background: linear-gradient(45deg, #333, #555);
            color: white;
            text-decoration: none;
            text-align: center;
            border-radius: 50px;
            font-size: 16px;
            font-weight: 600;
            transition: all 0.3s;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }
        
        .cancel-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.4);
        }
        
        /* Message de confirmation/erreur */
        .message {
            padding: 15px;
            margin-bottom: 25px;
            border-radius: 5px;
            text-align: center;
            font-weight: 500;
        }
        
        .success {
            background-color: rgba(46, 204, 113, 0.2);
            border: 1px solid #2ecc71;
            color: #2ecc71;
        }
        
        .error {
            background-color: rgba(231, 76, 60, 0.2);
            border: 1px solid #e74c3c;
            color: #e74c3c;
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
            
            .button-container {
                flex-direction: column;
                gap: 15px;
            }
            
            .submit-button, .cancel-button {
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
        <h1 class="page-title">Modifier un Fonctionnaire</h1>
        
        <?php if (!empty($message)): ?>
            <div class="message <?= $messageType ?>">
                <?= $message ?>
            </div>
        <?php endif; ?>
        
        <div class="form-container">
            <!-- CORRECTION ICI: Utiliser le nom exact du fichier au lieu de PHP_SELF -->
            <form method="post" action="modifier_fonctionnaire.php?id=<?= $id ?>">
                <div class="form-group">
                    <label for="ppr">PPR</label>
                    <input type="text" id="ppr" name="ppr" value="<?= htmlspecialchars($row['PPR']) ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="cnie">CIN</label>
                    <input type="text" id="cnie" name="cnie" value="<?= htmlspecialchars($row['CNIE']) ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="nom">Nom</label>
                    <input type="text" id="nom" name="nom" value="<?= htmlspecialchars($row['NOM']) ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="prenom">Prénom</label>
                    <input type="text" id="prenom" name="prenom" value="<?= htmlspecialchars($row['PRENOM']) ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="date_naissance">Date de naissance</label>
                    <input type="date" id="date_naissance" name="date_naissance" value="<?= htmlspecialchars($row['DATE_NAISSANCE'] ?? '') ?>">
                </div>
                
                <div class="form-group">
                    <label for="situation_familiale">Situation Familiale</label>
                    <select id="situation_familiale" name="situation_familiale">
                        <option value="Célibataire" <?= ($row['SITUATION_FAMILIALE'] ?? '') == 'Célibataire' ? 'selected' : '' ?>>Célibataire</option>
                        <option value="Marié(e)" <?= ($row['SITUATION_FAMILIALE'] ?? '') == 'Marié(e)' ? 'selected' : '' ?>>Marié(e)</option>
                        <option value="Divorcé(e)" <?= ($row['SITUATION_FAMILIALE'] ?? '') == 'Divorcé(e)' ? 'selected' : '' ?>>Divorcé(e)</option>
                        <option value="Veuf(ve)" <?= ($row['SITUATION_FAMILIALE'] ?? '') == 'Veuf(ve)' ? 'selected' : '' ?>>Veuf(ve)</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="nbr_enfant">Nombre d'enfants</label>
                    <input type="number" id="nbr_enfant" name="nbr_enfant" min="0" value="<?= htmlspecialchars($row['NBR_ENFANT'] ?? 0) ?>">
                </div>
                
                <div class="form-group">
                    <label for="telephone">Téléphone</label>
                    <input type="tel" id="telephone" name="telephone" value="<?= htmlspecialchars($row['TELEPHONE'] ?? '') ?>">
                </div>
                
                <div class="form-group">
                    <label for="sexe">Sexe</label>
                    <select id="sexe" name="sexe">
                        <option value="Homme" <?= ($row['SEXE'] ?? '') == 'Homme' ? 'selected' : '' ?>>Homme</option>
                        <option value="Femme" <?= ($row['SEXE'] ?? '') == 'Femme' ? 'selected' : '' ?>>Femme</option>
                    </select>
                </div>
                
                <div class="button-container">
                    <a href="situation_personnelle.php" class="cancel-button">Annuler</a>
                    <button type="submit" class="submit-button">Mettre à jour</button>
                </div>
            </form>
        </div>
    </div>
    
    <footer class="footer">
        <p>© <?= date("Y") ?> <span>Conseil Provincial d'Ifrane</span> - Tous droits réservés</p>
    </footer>
</body>
</html>