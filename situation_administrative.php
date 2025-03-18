<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "RH_CPI";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Échec de connexion: " . $conn->connect_error);
}

// Recherche par PPR ou CIN
$search = "";
if (isset($_POST['search'])) {
    $search = mysqli_real_escape_string($conn, $_POST['search']); // Prévention des injections SQL
    $sql = "SELECT sa.idFonctionaire, sa.PPR, sa.CNIE, sa.NOM, sa.PRENOM, 
                  sa.GRADE, sa.FONCTION, sa.SERVICE,
                  ag.CADRE, ag.ECHELLE, ag.ECHELON, ag.INDICE,
                  sa.DATE_RECRUTEMENT, sa.DATE_FONCTION, sa.DATE_AFFECTATION,
                  sa.ANCIENNETE_GRADE, sa.ANCIENNETE_ECHELON
           FROM situation_administratif sa
           LEFT JOIN avencement_grade ag ON sa.PPR = ag.PPR
           WHERE sa.PPR = '$search' OR sa.CNIE = '$search'";
} else {
    $sql = "SELECT sa.idFonctionaire, sa.PPR, sa.CNIE, sa.NOM, sa.PRENOM, 
                  sa.GRADE, sa.FONCTION, sa.SERVICE,
                  ag.CADRE, ag.ECHELLE, ag.ECHELON, ag.INDICE,
                  sa.DATE_RECRUTEMENT, sa.DATE_FONCTION, sa.DATE_AFFECTATION,
                  sa.ANCIENNETE_GRADE, sa.ANCIENNETE_ECHELON
           FROM situation_administratif sa
           LEFT JOIN avencement_grade ag ON sa.PPR = ag.PPR";
}
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Situation Administrative - Conseil Provincial d'Ifrane</title>
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
        
        input[type="text"] {
            padding: 12px 20px;
            width: 350px;
            background-color: #333;
            color: white;
            border: none;
            border-radius: 30px 0 0 30px;
            font-size: 16px;
            outline: none;
        }
        
        button {
            padding: 12px 25px;
            background: linear-gradient(45deg, #219653, #2ecc71);
            color: white;
            border: none;
            border-radius: 0 30px 30px 0;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
            transition: all 0.3s;
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
        
        .edit-btn {
            background-color: #3498db;
            color: white;
        }
        
        .delete-btn {
            background-color: #e74c3c;
            color: white;
        }
        
        .details-btn {
            background-color: #f39c12;
            color: white;
        }
        
        .actions a:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.3);
        }
        
        .add-button {
            display: inline-block;
            margin: 20px auto;
            padding: 15px 30px;
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
        
        .add-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(46, 204, 113, 0.6);
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
            
            input[type="text"] {
                width: 200px;
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
            
            button, .add-button, .back-button {
                width: 100%;
                margin-top: 10px;
            }
            
            .search-container form {
                flex-direction: column;
                width: 100%;
            }
            
            input[type="text"] {
                width: 100%;
                border-radius: 30px;
                margin-bottom: 10px;
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
        <h1 class="page-title">Situation Administrative des Fonctionnaires</h1>
        
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
                        <th>Date de recrutement</th>
                        <th>Grade</th>
                        <th>Échelle</th>
                        <th>Échelon</th>
                        <th>Indice</th>
                        <th>Fonction</th>
                        <th>Date fonction</th>
                        <th>Affectation</th>
                        <th>Date d'affectation</th>
                        <th>Ancienneté grade</th>
                        <th>Ancienneté échelon</th>
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
                            <td><?= $row['DATE_RECRUTEMENT'] ? htmlspecialchars(date('d/m/Y', strtotime($row['DATE_RECRUTEMENT']))) : '-' ?></td>
                            <td><?= htmlspecialchars($row['GRADE'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($row['ECHELLE'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($row['ECHELON'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($row['INDICE'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($row['FONCTION'] ?? '-') ?></td>
                            <td><?= $row['DATE_FONCTION'] ? htmlspecialchars(date('d/m/Y', strtotime($row['DATE_FONCTION']))) : '-' ?></td>
                            <td><?= htmlspecialchars($row['SERVICE'] ?? '-') ?></td>
                            <td><?= $row['DATE_AFFECTATION'] ? htmlspecialchars(date('d/m/Y', strtotime($row['DATE_AFFECTATION']))) : '-' ?></td>
                            <td><?= htmlspecialchars($row['ANCIENNETE_GRADE'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($row['ANCIENNETE_ECHELON'] ?? '-') ?></td>
                            <td class="actions">
                                <a href="modifier_situation_administrative.php?id=<?= $row['idFonctionaire'] ?>" class="edit-btn">Modifier</a>
                                <a href="details_fonctionnaire.php?id=<?= $row['idFonctionaire'] ?>" class="details-btn">Détails</a>
                            </td>
                        </tr>
                    <?php 
                        }
                    } else {
                    ?>
                        <tr>
                            <td colspan="16" style="text-align: center;">Aucun fonctionnaire trouvé</td>
                        </tr>
                    <?php 
                    }
                    ?>
                </tbody>
            </table>
        </div>
        
        <div class="button-container">
            <a href="ajouter_fonctionnaire.php" class="add-button">Ajouter un Fonctionnaire</a>
            <a href="ressources_humaines.php" class="back-button">Retour à la page principale</a>
            <a href="situation_personnelle.php" class="back-button">Situation Personnelle</a>
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