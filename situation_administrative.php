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
    <!-- Ajout du CSS pour le menu de navigation -->
<style>
    /* Style pour l'icône de navigation */
    .nav-icon {
        position: fixed;
        top: 100px;
        right: 30px;
        background-color: #2ecc71;
        color: white;
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
        cursor: pointer;
        font-size: 24px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
        z-index: 1000;
        transition: all 0.3s ease;
    }
    
    .nav-icon:hover {
        transform: scale(1.1);
        background-color: #219653;
    }
    
    /* Style pour le menu de navigation */
    .nav-menu {
        position: fixed;
        top: 160px;
        right: 30px;
        background-color: #1a1a1a;
        border: 1px solid #29802f;
        border-radius: 10px;
        width: 280px;
        max-height: 400px;
        overflow-y: auto;
        box-shadow: 0 5px 15px rgba(0, 255, 108, 0.2);
        z-index: 999;
        display: none;
        padding: 15px;
    }
    
    .nav-menu-title {
        color: #2ecc71;
        text-align: center;
        margin-bottom: 15px;
        font-weight: 600;
        padding-bottom: 8px;
        border-bottom: 1px solid #29802f;
    }
    
    .nav-links {
        display: grid;
        grid-template-columns: 1fr;
        gap: 10px;
    }
    
    .nav-link {
        display: flex;
        align-items: center;
        text-decoration: none;
        color: white;
        padding: 10px;
        border-radius: 5px;
        transition: all 0.3s;
    }
    
    .nav-link:hover {
        background-color: #2a2a2a;
        transform: translateX(5px);
    }
    
    .nav-link-icon {
        margin-right: 10px;
        font-size: 20px;
        color: #2ecc71;
        width: 25px;
        text-align: center;
    }
    
    .nav-link-text {
        font-size: 14px;
    }

    /* Animation pour l'apparition du menu */
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .nav-menu.active {
        display: block;
        animation: fadeIn 0.3s ease;
    }

    /* Style pour le bouton de fermeture du menu */
    .close-menu {
        position: absolute;
        top: 10px;
        right: 10px;
        color: #aaa;
        font-size: 18px;
        cursor: pointer;
        transition: color 0.3s;
    }
    
    .close-menu:hover {
        color: #2ecc71;
    }
</style>

<!-- HTML pour l'icône et le menu de navigation -->
<div class="nav-icon" id="navToggle">
    <i>≡</i>
</div>

<div class="nav-menu" id="navMenu">
    <div class="close-menu" id="closeMenu">×</div>
    <h3 class="nav-menu-title">Navigation </h3>
    <div class="nav-links">
        <a href="situation_personnelle.php" class="nav-link">
            <span class="nav-link-icon">👤</span>
            <span class="nav-link-text">Situation Personnelle</span>
        </a>
        <a href="situation_administrative.php" class="nav-link">
            <span class="nav-link-icon">📋</span>
            <span class="nav-link-text">Situation Administrative</span>
        </a>
        <a href="avancement_grade.php" class="nav-link">
            <span class="nav-link-icon">📈</span>
            <span class="nav-link-text">Avancement de Grade</span>
        </a>
        <a href="position_fonctionnaire.php" class="nav-link">
            <span class="nav-link-icon">🔄</span>
            <span class="nav-link-text">Position des Fonctionnaires</span>
        </a>
        <a href="maladie.php" class="nav-link">
            <span class="nav-link-icon">🏥</span>
            <span class="nav-link-text">Maladie</span>
        </a>
        <a href="conge_permission.php" class="nav-link">
            <span class="nav-link-icon">🏖️</span>
            <span class="nav-link-text">Congé et Permission</span>
        </a>
        <a href="sanction_disciplinaire.php" class="nav-link">
            <span class="nav-link-icon">⚖️</span>
            <span class="nav-link-text">Sanction Disciplinaire</span>
        </a>
        <a href="mouvement_personnel.php" class="nav-link">
            <span class="nav-link-icon">🔃</span>
            <span class="nav-link-text">Mouvement du Personnel</span>
        </a>
        <a href="ressources_humaines.php" class="nav-link">
            <span class="nav-link-icon">🏠</span>
            <span class="nav-link-text">Page RH principale</span>
        </a>
    </div>
</div>

<!-- JavaScript pour le fonctionnement du menu -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const navToggle = document.getElementById('navToggle');
        const navMenu = document.getElementById('navMenu');
        const closeMenu = document.getElementById('closeMenu');
        
        // Fonction pour ouvrir/fermer le menu
        navToggle.addEventListener('click', function() {
            navMenu.classList.toggle('active');
        });
        
        // Fermer le menu en cliquant sur le X
        closeMenu.addEventListener('click', function() {
            navMenu.classList.remove('active');
        });
        
        // Fermer le menu en cliquant en dehors
        document.addEventListener('click', function(event) {
            if (!navMenu.contains(event.target) && event.target !== navToggle) {
                navMenu.classList.remove('active');
            }
        });
    });
</script>
<?php
    include('header.php')?>
    
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
    
    <?php
    include('footer.php')?>
</body>
</html>
<?php
$conn->close();
?>