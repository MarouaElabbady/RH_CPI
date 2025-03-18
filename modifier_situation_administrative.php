<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "RH_CPI";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Échec de connexion: " . $conn->connect_error);
}

// Initialisation des variables
$id = 0;
$success_message = "";
$error_message = "";

// Vérification si l'ID est fourni
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    // Récupération des données du fonctionnaire
    $sql = "SELECT sa.*, ag.CADRE, ag.ECHELLE, ag.ECHELON, ag.INDICE
            FROM situation_administratif sa
            LEFT JOIN avencement_grade ag ON sa.PPR = ag.PPR
            WHERE sa.idFonctionaire = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        $error_message = "Fonctionnaire non trouvé";
    }
} else {
    $error_message = "ID de fonctionnaire non spécifié";
}

// Traitement du formulaire de modification
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    // Récupération des données du formulaire
    $nom = mysqli_real_escape_string($conn, $_POST['nom']);
    $prenom = mysqli_real_escape_string($conn, $_POST['prenom']);
    $ppr = mysqli_real_escape_string($conn, $_POST['ppr']);
    $cnie = mysqli_real_escape_string($conn, $_POST['cnie']);
    $grade = mysqli_real_escape_string($conn, $_POST['grade']);
    $fonction = mysqli_real_escape_string($conn, $_POST['fonction']);
    $service = mysqli_real_escape_string($conn, $_POST['service']);
    $date_recrutement = !empty($_POST['date_recrutement']) ? $_POST['date_recrutement'] : NULL;
    $date_fonction = !empty($_POST['date_fonction']) ? $_POST['date_fonction'] : NULL;
    $date_affectation = !empty($_POST['date_affectation']) ? $_POST['date_affectation'] : NULL;
    $anciennete_grade = mysqli_real_escape_string($conn, $_POST['anciennete_grade']);
    $anciennete_echelon = mysqli_real_escape_string($conn, $_POST['anciennete_echelon']);
    
    // Mise à jour des données dans la table situation_administratif
    $sql_update = "UPDATE situation_administratif 
                  SET NOM = ?, PRENOM = ?, PPR = ?, CNIE = ?, GRADE = ?, FONCTION = ?, SERVICE = ?,
                      DATE_RECRUTEMENT = ?, DATE_FONCTION = ?, DATE_AFFECTATION = ?,
                      ANCIENNETE_GRADE = ?, ANCIENNETE_ECHELON = ?
                  WHERE idFonctionaire = ?";
    
    $stmt = $conn->prepare($sql_update);
    $stmt->bind_param("ssssssssssssi", $nom, $prenom, $ppr, $cnie, $grade, $fonction, $service, 
                      $date_recrutement, $date_fonction, $date_affectation, 
                      $anciennete_grade, $anciennete_echelon, $id);
    
    if ($stmt->execute()) {
        // Mise à jour des données dans la table avencement_grade si elle existe
        $echelle = mysqli_real_escape_string($conn, $_POST['echelle']);
        $echelon = mysqli_real_escape_string($conn, $_POST['echelon']);
        $indice = mysqli_real_escape_string($conn, $_POST['indice']);
        $cadre = mysqli_real_escape_string($conn, $_POST['cadre']);
        
        // Vérifie si l'entrée existe déjà dans avencement_grade
        $check_sql = "SELECT * FROM avencement_grade WHERE PPR = ?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("s", $ppr);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();
        
        if ($check_result->num_rows > 0) {
            // Mise à jour de l'entrée existante
            $update_ag_sql = "UPDATE avencement_grade 
                             SET NOM = ?, PRENOM = ?, CNIE = ?, CADRE = ?, GRADE = ?, ECHELLE = ?, ECHELON = ?, INDICE = ?
                             WHERE PPR = ?";
            $update_ag_stmt = $conn->prepare($update_ag_sql);
            $update_ag_stmt->bind_param("sssssssss", $nom, $prenom, $cnie, $cadre, $grade, $echelle, $echelon, $indice, $ppr);
            $update_ag_stmt->execute();
        } else {
            // Insertion d'une nouvelle entrée
            $insert_ag_sql = "INSERT INTO avencement_grade (NOM, PRENOM, PPR, CNIE, CADRE, GRADE, ECHELLE, ECHELON, INDICE)
                             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $insert_ag_stmt = $conn->prepare($insert_ag_sql);
            $insert_ag_stmt->bind_param("sssssssss", $nom, $prenom, $ppr, $cnie, $cadre, $grade, $echelle, $echelon, $indice);
            $insert_ag_stmt->execute();
        }
        
        $success_message = "Informations du fonctionnaire mises à jour avec succès";
        
        // Récupération des données mises à jour
        $sql = "SELECT sa.*, ag.CADRE, ag.ECHELLE, ag.ECHELON, ag.INDICE
                FROM situation_administratif sa
                LEFT JOIN avencement_grade ag ON sa.PPR = ag.PPR
                WHERE sa.idFonctionaire = ?";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
    } else {
        $error_message = "Erreur lors de la mise à jour: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier la Situation Administrative - Conseil Provincial d'Ifrane</title>
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
            font-size: 32px;
            margin-bottom: 30px;
            font-weight: 800;
            text-shadow: 0 0 15px rgba(46, 204, 113, 0.3);
            letter-spacing: 1px;
            position: relative;
        }
        
        .page-title::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 3px;
            background: linear-gradient(90deg, transparent, #2ecc71, transparent);
        }
        
        /* Formulaire */
        .form-container {
            margin-top: 30px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #2ecc71;
        }
        
        input[type="text"],
        input[type="date"],
        input[type="number"],
        select {
            width: 100%;
            padding: 12px 15px;
            background-color: #333;
            color: white;
            border: 1px solid #444;
            border-radius: 8px;
            font-size: 16px;
            outline: none;
            transition: all 0.3s;
        }
        
        input[type="text"]:focus,
        input[type="date"]:focus,
        input[type="number"]:focus,
        select:focus {
            border-color: #2ecc71;
            box-shadow: 0 0 10px rgba(46, 204, 113, 0.3);
        }
        
        .btn {
            padding: 12px 25px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
            transition: all 0.3s;
            margin-right: 10px;
        }
        
        .btn-primary {
            background: linear-gradient(45deg, #219653, #2ecc71);
            color: white;
        }
        
        .btn-secondary {
            background: linear-gradient(45deg, #333, #555);
            color: white;
        }
        
        .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }
        
        .btn-primary:hover {
            background: linear-gradient(45deg, #2ecc71, #219653);
            box-shadow: 0 5px 15px rgba(46, 204, 113, 0.4);
        }
        
        .actions {
            margin-top: 30px;
            display: flex;
            justify-content: center;
        }
        
        /* Messages */
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 8px;
            font-weight: 500;
        }
        
        .alert-success {
            background-color: rgba(46, 204, 113, 0.2);
            border: 1px solid #2ecc71;
            color: #2ecc71;
        }
        
        .alert-danger {
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
            .form-grid {
                grid-template-columns: 1fr;
            }
            
            .main-container {
                margin: 20px;
                padding: 20px;
            }
            
            .page-title {
                font-size: 28px;
            }
            
            .actions {
                flex-direction: column;
                gap: 10px;
            }
            
            .btn {
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
        <h1 class="page-title">Modifier la Situation Administrative</h1>
        
        <?php if(!empty($error_message)): ?>
            <div class="alert alert-danger">
                <?= $error_message ?>
            </div>
        <?php endif; ?>
        
        <?php if(!empty($success_message)): ?>
            <div class="alert alert-success">
                <?= $success_message ?>
            </div>
        <?php endif; ?>
        
        <?php if(isset($row)): ?>
            <div class="form-container">
                <form method="post" action="">
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="ppr">PPR:</label>
                            <input type="text" id="ppr" name="ppr" value="<?= htmlspecialchars($row['PPR']) ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="cnie">CIN:</label>
                            <input type="text" id="cnie" name="cnie" value="<?= htmlspecialchars($row['CNIE']) ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="nom">Nom:</label>
                            <input type="text" id="nom" name="nom" value="<?= htmlspecialchars($row['NOM']) ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="prenom">Prénom:</label>
                            <input type="text" id="prenom" name="prenom" value="<?= htmlspecialchars($row['PRENOM']) ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="date_recrutement">Date de recrutement:</label>
                            <input type="date" id="date_recrutement" name="date_recrutement" value="<?= $row['DATE_RECRUTEMENT'] ? htmlspecialchars($row['DATE_RECRUTEMENT']) : '' ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="grade">Grade:</label>
                            <input type="text" id="grade" name="grade" value="<?= htmlspecialchars($row['GRADE']) ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="cadre">Cadre:</label>
                            <input type="text" id="cadre" name="cadre" value="<?= htmlspecialchars($row['CADRE'] ?? '') ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="echelle">Échelle:</label>
                            <input type="text" id="echelle" name="echelle" value="<?= htmlspecialchars($row['ECHELLE'] ?? '') ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="echelon">Échelon:</label>
                            <input type="text" id="echelon" name="echelon" value="<?= htmlspecialchars($row['ECHELON'] ?? '') ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="indice">Indice:</label>
                            <input type="text" id="indice" name="indice" value="<?= htmlspecialchars($row['INDICE'] ?? '') ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="fonction">Fonction:</label>
                            <input type="text" id="fonction" name="fonction" value="<?= htmlspecialchars($row['FONCTION']) ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="date_fonction">Date fonction:</label>
                            <input type="date" id="date_fonction" name="date_fonction" value="<?= $row['DATE_FONCTION'] ? htmlspecialchars($row['DATE_FONCTION']) : '' ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="service">Service/Affectation:</label>
                            <input type="text" id="service" name="service" value="<?= htmlspecialchars($row['SERVICE']) ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="date_affectation">Date d'affectation:</label>
                            <input type="date" id="date_affectation" name="date_affectation" value="<?= $row['DATE_AFFECTATION'] ? htmlspecialchars($row['DATE_AFFECTATION']) : '' ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="anciennete_grade">Ancienneté dans le grade:</label>
                            <input type="text" id="anciennete_grade" name="anciennete_grade" value="<?= htmlspecialchars($row['ANCIENNETE_GRADE'] ?? '') ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="anciennete_echelon">Ancienneté dans l'échelon:</label>
                            <input type="text" id="anciennete_echelon" name="anciennete_echelon" value="<?= htmlspecialchars($row['ANCIENNETE_ECHELON'] ?? '') ?>">
                        </div>
                    </div>
                    
                    <div class="actions">
                        <button type="submit" name="submit" class="btn btn-primary">Enregistrer les modifications</button>
                        <a href="situation_administrative.php" class="btn btn-secondary">Retour</a>
                    </div>
                </form>
            </div>
        <?php endif; ?>
    </div>
    
    <footer class="footer">
        <p>&copy; <?= date('Y') ?> <span>Conseil Provincial d'Ifrane</span> - Tous droits réservés</p>
    </footer>
</body>
</html>
<?php
$conn->close();
?>