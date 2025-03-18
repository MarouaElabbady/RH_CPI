<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "RH_CPI";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Échec de connexion: " . $conn->connect_error);
}

// Traitement du formulaire de modification
if (isset($_POST['modifier']) && isset($_POST['id'])) {
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $ppr = mysqli_real_escape_string($conn, $_POST['ppr']);
    $grade = mysqli_real_escape_string($conn, $_POST['grade']);
    $echelle = mysqli_real_escape_string($conn, $_POST['echelle']);
    $echelon = mysqli_real_escape_string($conn, $_POST['echelon']);
    $indice = mysqli_real_escape_string($conn, $_POST['indice']);
    $date_nomination_echelle = mysqli_real_escape_string($conn, $_POST['date_nomination_echelle']);
    $date_nomination_echelon = mysqli_real_escape_string($conn, $_POST['date_nomination_echelon']);
    $type_avancement = mysqli_real_escape_string($conn, $_POST['type_avancement']);
    
    // Vérifier si un enregistrement existe déjà pour ce PPR
    $check_sql = "SELECT * FROM avencement_grade WHERE PPR = '$ppr'";
    $check_result = $conn->query($check_sql);
    
    if ($check_result->num_rows > 0) {
        // Mise à jour des données existantes
        $update_sql = "UPDATE avencement_grade SET 
                      GRADE = '$grade',
                      ECHELLE = '$echelle',
                      ECHELON = '$echelon',
                      INDICE = '$indice',
                      DATE_NOMINATION_ECHELLE = '$date_nomination_echelle',
                      DATE_NOMINATION_ECHELON = '$date_nomination_echelon',
                      TYPE_AVANCEMENT = '$type_avancement'
                      WHERE PPR = '$ppr'";
        
        if ($conn->query($update_sql) === TRUE) {
            $success_message = "Les informations d'avancement ont été mises à jour avec succès";
        } else {
            $error_message = "Erreur lors de la mise à jour: " . $conn->error;
        }
    } else {
        // Insertion de nouvelles données
        $insert_sql = "INSERT INTO avencement_grade (PPR, GRADE, ECHELLE, ECHELON, INDICE, DATE_NOMINATION_ECHELLE, DATE_NOMINATION_ECHELON, TYPE_AVANCEMENT)
                      VALUES ('$ppr', '$grade', '$echelle', '$echelon', '$indice', '$date_nomination_echelle', '$date_nomination_echelon', '$type_avancement')";
        
        if ($conn->query($insert_sql) === TRUE) {
            $success_message = "Les informations d'avancement ont été ajoutées avec succès";
        } else {
            $error_message = "Erreur lors de l'ajout: " . $conn->error;
        }
    }
}

// Recherche par PPR ou CIN
$search = "";
if (isset($_POST['search'])) {
    $search = mysqli_real_escape_string($conn, $_POST['search']); // Prévention des injections SQL
    $sql = "SELECT sa.idFonctionaire, sa.PPR, sa.CNIE, sa.NOM, sa.PRENOM, 
                  ag.GRADE, ag.ECHELLE, ag.ECHELON, ag.INDICE,
                  ag.DATE_NOMINATION_ECHELLE, ag.DATE_NOMINATION_ECHELON,
                  ag.TYPE_AVANCEMENT
           FROM situation_administratif sa
           LEFT JOIN avencement_grade ag ON sa.PPR = ag.PPR
           WHERE sa.PPR = '$search' OR sa.CNIE = '$search'";
} else {
    $sql = "SELECT sa.idFonctionaire, sa.PPR, sa.CNIE, sa.NOM, sa.PRENOM, 
                  ag.GRADE, ag.ECHELLE, ag.ECHELON, ag.INDICE,
                  ag.DATE_NOMINATION_ECHELLE, ag.DATE_NOMINATION_ECHELON,
                  ag.TYPE_AVANCEMENT
           FROM situation_administratif sa
           LEFT JOIN avencement_grade ag ON sa.PPR = ag.PPR";
}
$result = $conn->query($sql);

// Pour récupérer les données pour le formulaire de modification
$edit_data = null;
if (isset($_GET['edit']) && is_numeric($_GET['edit'])) {
    $edit_id = mysqli_real_escape_string($conn, $_GET['edit']);
    $edit_sql = "SELECT sa.idFonctionaire, sa.PPR, sa.CNIE, sa.NOM, sa.PRENOM, 
                      ag.GRADE, ag.ECHELLE, ag.ECHELON, ag.INDICE,
                      ag.DATE_NOMINATION_ECHELLE, ag.DATE_NOMINATION_ECHELON,
                      ag.TYPE_AVANCEMENT
               FROM situation_administratif sa
               LEFT JOIN avencement_grade ag ON sa.PPR = ag.PPR
               WHERE sa.idFonctionaire = '$edit_id'";
    $edit_result = $conn->query($edit_sql);
    if ($edit_result->num_rows > 0) {
        $edit_data = $edit_result->fetch_assoc();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Avancement de Grade - Conseil Provincial d'Ifrane</title>
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

        /* Messages d'alerte */
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 8px;
            text-align: center;
            font-weight: 500;
        }
        
        .alert-success {
            background-color: rgba(46, 204, 113, 0.2);
            color: #2ecc71;
            border: 1px solid #2ecc71;
        }
        
        .alert-error {
            background-color: rgba(231, 76, 60, 0.2);
            color: #e74c3c;
            border: 1px solid #e74c3c;
        }

        /* Styles pour la table et les formulaires */
        .search-container {
            margin-bottom: 30px;
            text-align: center;
        }

        .search-container form {
            display: inline-flex;
            background: #222;
            padding: 8px;
            border-radius: 50px;
            border: 1px solid #29802f;
        }
        
        input[type="text"], input[type="number"], input[type="date"] {
            padding: 12px 20px;
            background-color: #333;
            color: white;
            border: none;
            border-radius: 30px;
            font-size: 16px;
            outline: none;
            margin-bottom: 15px;
            width: 100%;
        }
        
        .search-container input[type="text"] {
            border-radius: 30px 0 0 30px;
            width: 350px;
            margin-bottom: 0;
        }
        
        button {
            padding: 12px 25px;
            background: linear-gradient(45deg, #219653, #2ecc71);
            color: white;
            border: none;
            border-radius: 30px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
            transition: all 0.3s;
        }
        
        .search-container button {
            border-radius: 0 30px 30px 0;
        }
        
        button:hover {
            background: linear-gradient(45deg, #2ecc71, #219653);
            box-shadow: 0 0 15px rgba(46, 204, 113, 0.5);
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 30px 0;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 25px rgba(0, 0, 0, 0.15);
        }
        
        table th, table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #333;
        }
        
        table th {
            background-color: #2a2a2a;
            color: #2ecc71;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 14px;
            letter-spacing: 1px;
        }
        
        table tr {
            background-color: #222;
            transition: all 0.3s;
        }
        
        table tr:nth-child(even) {
            background-color: #1a1a1a;
        }
        
        table tr:hover {
            background-color: #2a2a2a;
            transform: scale(1.005);
        }
        
        .actions {
            display: flex;
            gap: 10px;
            justify-content: center;
        }
        
        .actions a {
            display: inline-block;
            padding: 8px 12px;
            text-decoration: none;
            border-radius: 5px;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.3s;
            text-align: center;
        }
        
        .details-btn {
            background-color: #f39c12;
            color: white;
        }
        
        .edit-btn {
            background-color: #3498db;
            color: white;
        }
        
        .actions a:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.3);
        }

        .button-container {
            display: flex;
            justify-content: center;
            margin-top: 30px;
            gap: 20px;
        }

        .back-button {
            display: inline-block;
            padding: 12px 25px;
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
        
        /* Tableaux avec défilement horizontal */
        .table-container {
            overflow-x: auto;
            margin-bottom: 30px;
        }
        
        /* Formulaire de modification */
        .edit-form {
            background-color: #222;
            padding: 30px;
            border-radius: 10px;
            margin-bottom: 40px;
            border: 1px solid #29802f;
        }
        
        .edit-form h2 {
            color: #2ecc71;
            margin-bottom: 25px;
            text-align: center;
        }
        
        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #ddd;
        }
        
        .form-actions {
            margin-top: 30px;
            display: flex;
            justify-content: center;
            gap: 15px;
        }
        
        .cancel-btn {
            background: linear-gradient(45deg, #95a5a6, #7f8c8d);
        }
        
        .submit-btn {
            min-width: 150px;
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
            
            .actions {
                flex-direction: column;
                gap: 5px;
            }
            
            .actions a {
                width: 100%;
            }
            
            .search-container input[type="text"] {
                width: 200px;
            }
            
            .form-grid {
                grid-template-columns: 1fr;
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
            
            button, .back-button {
                width: 100%;
                margin-top: 10px;
            }
            
            .search-container form {
                flex-direction: column;
                width: 100%;
            }
            
            .search-container input[type="text"] {
                width: 100%;
                border-radius: 30px;
                margin-bottom: 10px;
            }
            
            .search-container button {
                border-radius: 30px;
            }
            
            .form-actions {
                flex-direction: column;
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
        <h1 class="page-title">Avancement de Grade des Fonctionnaires</h1>
        
        <?php if (isset($success_message)): ?>
            <div class="alert alert-success">
                <?= $success_message ?>
            </div>
        <?php endif; ?>
        
        <?php if (isset($error_message)): ?>
            <div class="alert alert-error">
                <?= $error_message ?>
            </div>
        <?php endif; ?>
        
        <?php if ($edit_data): ?>
            <!-- Formulaire de modification -->
            <div class="edit-form">
                <h2>Modifier les informations d'avancement</h2>
                <form method="post">
                    <input type="hidden" name="id" value="<?= $edit_data['idFonctionaire'] ?>">
                    <input type="hidden" name="ppr" value="<?= $edit_data['PPR'] ?>">
                    
                    <div class="form-grid">
                        <div class="form-group">
                            <label>PPR:</label>
                            <input type="text" value="<?= htmlspecialchars($edit_data['PPR']) ?>" disabled>
                        </div>
                        
                        <div class="form-group">
                            <label>CIN:</label>
                            <input type="text" value="<?= htmlspecialchars($edit_data['CNIE']) ?>" disabled>
                        </div>
                        
                        <div class="form-group">
                            <label>Nom:</label>
                            <input type="text" value="<?= htmlspecialchars($edit_data['NOM']) ?>" disabled>
                        </div>
                        
                        <div class="form-group">
                            <label>Prénom:</label>
                            <input type="text" value="<?= htmlspecialchars($edit_data['PRENOM']) ?>" disabled>
                        </div>
                        
                        <div class="form-group">
                            <label for="grade">Grade:</label>
                            <input type="text" id="grade" name="grade" value="<?= htmlspecialchars($edit_data['GRADE'] ?? '') ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="echelle">Échelle:</label>
                            <input type="text" id="echelle" name="echelle" value="<?= htmlspecialchars($edit_data['ECHELLE'] ?? '') ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="date_nomination_echelle">Date Nomination Échelle:</label>
                            <input type="date" id="date_nomination_echelle" name="date_nomination_echelle" 
                                   value="<?= $edit_data['DATE_NOMINATION_ECHELLE'] ? htmlspecialchars(date('Y-m-d', strtotime($edit_data['DATE_NOMINATION_ECHELLE']))) : '' ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="echelon">Échelon:</label>
                            <input type="number" id="echelon" name="echelon" value="<?= htmlspecialchars($edit_data['ECHELON'] ?? '') ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="date_nomination_echelon">Date Nomination Échelon:</label>
                            <input type="date" id="date_nomination_echelon" name="date_nomination_echelon" 
                                   value="<?= $edit_data['DATE_NOMINATION_ECHELON'] ? htmlspecialchars(date('Y-m-d', strtotime($edit_data['DATE_NOMINATION_ECHELON']))) : '' ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="indice">Indice:</label>
                            <input type="number" id="indice" name="indice" value="<?= htmlspecialchars($edit_data['INDICE'] ?? '') ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="type_avancement">Type d'Avancement:</label>
                            <input type="text" id="type_avancement" name="type_avancement" value="<?= htmlspecialchars($edit_data['TYPE_AVANCEMENT'] ?? '') ?>" required>
                        </div>
                    </div>
                    
                    <div class="form-actions">
                        <a href="avancement_grade.php" class="back-button cancel-btn">Annuler</a>
                        <button type="submit" name="modifier" class="submit-btn">Enregistrer les modifications</button>
                    </div>
                </form>
            </div>
        <?php endif; ?>
        
        <div class="search-container">
            <form method="post">
                <input type="text" name="search" placeholder="Rechercher par PPR ou CIN" value="<?= htmlspecialchars($search) ?>">
                <button type="submit">Rechercher</button>
            </form>
        </div>
        
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>PPR</th>
                        <th>CIN</th>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Grade</th>
                        <th>Échelle</th>
                        <th>Date Nomination Échelle</th>
                        <th>Échelon</th>
                        <th>Date Nomination Échelon</th>
                        <th>Indice</th>
                        <th>Type d'Avancement</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    if ($result && $result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) { 
                    ?>
                        <tr>
                            <td><?= htmlspecialchars($row['PPR']) ?></td>
                            <td><?= htmlspecialchars($row['CNIE']) ?></td>
                            <td><?= htmlspecialchars($row['NOM']) ?></td>
                            <td><?= htmlspecialchars($row['PRENOM']) ?></td>
                            <td><?= htmlspecialchars($row['GRADE'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($row['ECHELLE'] ?? '-') ?></td>
                            <td><?= $row['DATE_NOMINATION_ECHELLE'] ? htmlspecialchars(date('d/m/Y', strtotime($row['DATE_NOMINATION_ECHELLE']))) : '-' ?></td>
                            <td><?= htmlspecialchars($row['ECHELON'] ?? '-') ?></td>
                            <td><?= $row['DATE_NOMINATION_ECHELON'] ? htmlspecialchars(date('d/m/Y', strtotime($row['DATE_NOMINATION_ECHELON']))) : '-' ?></td>
                            <td><?= htmlspecialchars($row['INDICE'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($row['TYPE_AVANCEMENT'] ?? '-') ?></td>
                            <td class="actions">
                                <a href="details_fonctionnaire.php?id=<?= $row['idFonctionaire'] ?>" class="details-btn">Détails</a>
                                <a href="?edit=<?= $row['idFonctionaire'] ?>" class="edit-btn">Modifier</a>
                            </td>
                        </tr>
                    <?php 
                        }
                    } else {
                    ?>
                        <tr>
                            <td colspan="12" style="text-align: center;">Aucun fonctionnaire trouvé</td>
                        </tr>
                    <?php 
                    }
                    ?>
                </tbody>
            </table>
        </div>
        
        <div class="button-container">
            <a href="situation_administrative.php" class="back-button">Situation Administrative</a>
            <a href="situation_personnelle.php" class="back-button">Situation Personnelle</a>
            <a href="ressources_humaines.php" class="back-button">Retour à la page principale</a>
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