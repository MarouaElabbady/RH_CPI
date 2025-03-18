<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "RH_CPI";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Échec de connexion: " . $conn->connect_error);
}

$success_message = "";
$error_message = "";

// Traitement du formulaire lors de la soumission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupération des données du formulaire
    $nom = mysqli_real_escape_string($conn, $_POST['nom']);
    $prenom = mysqli_real_escape_string($conn, $_POST['prenom']);
    $ppr = mysqli_real_escape_string($conn, $_POST['ppr']);
    $cnie = mysqli_real_escape_string($conn, $_POST['cnie']);
    $grade = mysqli_real_escape_string($conn, $_POST['grade']);
    $fonction = mysqli_real_escape_string($conn, $_POST['fonction']);
    $service = mysqli_real_escape_string($conn, $_POST['service']);
    
    // Données pour avencement_grade
    $cadre = mysqli_real_escape_string($conn, $_POST['cadre']);
    $echelle = mysqli_real_escape_string($conn, $_POST['echelle']);
    $echelon = mysqli_real_escape_string($conn, $_POST['echelon']);
    $indice = mysqli_real_escape_string($conn, $_POST['indice']);
    
    // Données personnelles
    $date_naissance = mysqli_real_escape_string($conn, $_POST['date_naissance']);
    $lieu_naissance = mysqli_real_escape_string($conn, $_POST['lieu_naissance']);
    $adresse = mysqli_real_escape_string($conn, $_POST['adresse']);
    $situation_familiale = mysqli_real_escape_string($conn, $_POST['situation_familiale']);
    $nbr_enfant = mysqli_real_escape_string($conn, $_POST['nbr_enfant']);
    $telephone = mysqli_real_escape_string($conn, $_POST['telephone']);
    $sexe = mysqli_real_escape_string($conn, $_POST['sexe']);
    
    // Vérification si PPR ou CNIE existe déjà
    $check_sql = "SELECT PPR FROM avencement_grade WHERE PPR = '$ppr'";
    $check_result = $conn->query($check_sql);
    
    if ($check_result->num_rows > 0) {
        $error_message = "Un fonctionnaire avec ce PPR existe déjà.";
    } else {
        // Démarrer une transaction
        $conn->begin_transaction();
        
        try {
            // 1. Insérer d'abord dans avencement_grade (table parente)
            $sql_avencement = "INSERT INTO avencement_grade (NOM, PRENOM, PPR, CNIE, CADRE, GRADE, ECHELLE, ECHELON, INDICE) 
                              VALUES ('$nom', '$prenom', '$ppr', '$cnie', '$cadre', '$grade', '$echelle', '$echelon', '$indice')";
            
            if (!$conn->query($sql_avencement)) {
                throw new Exception("Erreur lors de l'ajout des données d'avancement: " . $conn->error);
            }
            
            // 2. Insérer dans situation_administratif
            $sql_admin = "INSERT INTO situation_administratif (NOM, PRENOM, PPR, CNIE, GRADE, FONCTION, SERVICE) 
                          VALUES ('$nom', '$prenom', '$ppr', '$cnie', '$grade', '$fonction', '$service')";
            
            if (!$conn->query($sql_admin)) {
                throw new Exception("Erreur lors de l'ajout des données administratives: " . $conn->error);
            }
            
            // 3. Insérer dans situation_personnelle
            $sql_perso = "INSERT INTO situation_personnelle (NOM, PRENOM, PPR, CIN, DATE_NAISSANCE, LIEU_NAISSANCE, ADRESSE, SITUATION_FAMILIALE, NBR_ENFANT, TELEPHONE, SEXE) 
                          VALUES ('$nom', '$prenom', '$ppr', '$cnie', '$date_naissance', '$lieu_naissance', '$adresse', '$situation_familiale', '$nbr_enfant', '$telephone', '$sexe')";
            
            if (!$conn->query($sql_perso)) {
                throw new Exception("Erreur lors de l'ajout des données personnelles: " . $conn->error);
            }
            
            // Si tout est réussi, valider la transaction
            $conn->commit();
            $success_message = "Fonctionnaire ajouté avec succès!";
            // Redirection après 2 secondes
            header("refresh:2;url=situation_personnelle.php");
            
        } catch (Exception $e) {
            // En cas d'erreur, annuler toutes les modifications
            $conn->rollback();
            $error_message = $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Fonctionnaire - Conseil Provincial d'Ifrane</title>
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
            font-size: 36px;
            margin-bottom: 40px;
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
            margin-top: 30px;
        }
        
        .form-section {
            margin-bottom: 30px;
            padding: 20px;
            background-color: #222;
            border-radius: 10px;
            border: 1px solid #333;
        }
        
        .form-section h3 {
            color: #2ecc71;
            font-size: 20px;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid #333;
        }
        
        .form-row {
            display: flex;
            flex-wrap: wrap;
            margin: 0 -10px;
        }
        
        .form-group {
            flex: 1 1 300px;
            margin: 0 10px 20px;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #ddd;
        }
        
        input[type="text"],
        input[type="date"],
        input[type="number"],
        input[type="tel"],
        select,
        textarea {
            width: 100%;
            padding: 12px 15px;
            background-color: #333;
            border: 1px solid #444;
            border-radius: 8px;
            color: white;
            font-size: 16px;
            transition: all 0.3s;
        }
        
        input[type="text"]:focus,
        input[type="date"]:focus,
        input[type="number"]:focus,
        input[type="tel"]:focus,
        select:focus,
        textarea:focus {
            border-color: #2ecc71;
            box-shadow: 0 0 10px rgba(46, 204, 113, 0.3);
            outline: none;
        }
        
        .submit-btn {
            display: block;
            width: 200px;
            margin: 30px auto 0;
            padding: 15px;
            background: linear-gradient(45deg, #219653, #2ecc71);
            color: white;
            border: none;
            border-radius: 50px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 5px 15px rgba(46, 204, 113, 0.4);
        }
        
        .submit-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(46, 204, 113, 0.6);
        }
        
        .back-button {
            display: block;
            width: 200px;
            margin: 20px auto;
            padding: 12px;
            background: linear-gradient(45deg, #333, #555);
            color: white;
            text-align: center;
            text-decoration: none;
            font-size: 16px;
            font-weight: 600;
            border-radius: 50px;
            transition: all 0.3s;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }
        
        .back-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.4);
        }
        
        /* Messages */
        .success-message,
        .error-message {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 8px;
            font-weight: 500;
            text-align: center;
        }
        
        .success-message {
            background-color: rgba(46, 204, 113, 0.2);
            border: 1px solid #2ecc71;
            color: #2ecc71;
        }
        
        .error-message {
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
                margin: 20px;
                padding: 20px;
            }
            
            .page-title {
                font-size: 28px;
            }
            
            .form-group {
                flex: 1 1 100%;
            }
            
            .submit-btn,
            .back-button {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="logo"><a href="acceuil.php" class="main-button">   🏛️Conseil Provincial d'Ifrane</a>
        
    </div>
        
    </header>
    
    <div class="main-container">
        <h1 class="page-title">Ajouter un Fonctionnaire</h1>
        
        <?php if (!empty($success_message)): ?>
            <div class="success-message"><?= $success_message ?></div>
        <?php endif; ?>
        
        <?php if (!empty($error_message)): ?>
            <div class="error-message"><?= $error_message ?></div>
        <?php endif; ?>
        
        <div class="form-container">
            <form method="post" action="<?= htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <div class="form-section">
                    <h3>Informations Administratives</h3>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="nom">Nom</label>
                            <input type="text" id="nom" name="nom" required>
                        </div>
                        <div class="form-group">
                            <label for="prenom">Prénom</label>
                            <input type="text" id="prenom" name="prenom" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="ppr">PPR</label>
                            <input type="number" id="ppr" name="ppr" required>
                        </div>
                        <div class="form-group">
                            <label for="cnie">CNIE</label>
                            <input type="text" id="cnie" name="cnie" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="grade">Grade</label>
                            <input type="text" id="grade" name="grade" required>
                        </div>
                        <div class="form-group">
                            <label for="fonction">Fonction</label>
                            <input type="text" id="fonction" name="fonction" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="service">Service</label>
                            <input type="text" id="service" name="service" required>
                        </div>
                    </div>
                </div>
                
                <div class="form-section">
                    <h3>Informations d'Avancement</h3>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="cadre">Cadre</label>
                            <input type="text" id="cadre" name="cadre" required>
                        </div>
                        <div class="form-group">
                            <label for="echelle">Echelle</label>
                            <input type="text" id="echelle" name="echelle" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="echelon">Echelon</label>
                            <input type="text" id="echelon" name="echelon" required>
                        </div>
                        <div class="form-group">
                            <label for="indice">Indice</label>
                            <input type="text" id="indice" name="indice" required>
                        </div>
                    </div>
                </div>
                
                <div class="form-section">
                    <h3>Informations Personnelles</h3>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="date_naissance">Date de naissance</label>
                            <input type="date" id="date_naissance" name="date_naissance" required>
                        </div>
                        <div class="form-group">
                            <label for="lieu_naissance">Lieu de naissance</label>
                            <input type="text" id="lieu_naissance" name="lieu_naissance" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="adresse">Adresse</label>
                            <textarea id="adresse" name="adresse" rows="3" required></textarea>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="situation_familiale">Situation familiale</label>
                            <select id="situation_familiale" name="situation_familiale" required>
                                <option value="Célibataire">Célibataire</option>
                                <option value="Marié(e)">Marié(e)</option>
                                <option value="Divorcé(e)">Divorcé(e)</option>
                                <option value="Veuf(ve)">Veuf(ve)</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="nbr_enfant">Nombre d'enfants</label>
                            <input type="number" id="nbr_enfant" name="nbr_enfant" min="0" value="0" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="telephone">Téléphone</label>
                            <input type="tel" id="telephone" name="telephone" required>
                        </div>
                        <div class="form-group">
                            <label for="sexe">Sexe</label>
                            <select id="sexe" name="sexe" required>
                                <option value="Homme">Homme</option>
                                <option value="Femme">Femme</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <button type="submit" class="submit-btn">Ajouter</button>
            </form>
            
            <a href="situation_personnelle.php" class="back-button">Retour à la liste</a>
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