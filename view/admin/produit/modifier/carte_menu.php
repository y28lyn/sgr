<?php
if (!isset($_SESSION)) {
    session_start();
}

if ($_SESSION["role"] == "admin") {
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/SGRC/css/style_service/plat.css" />
    <link rel="stylesheet" href="/SGRC/css/style_admin/produit/produit.css" />
    <link href="/SGRC/css/style_admin/produit/edition-ajoute.css" rel="stylesheet">
    <link rel="stylesheet" href="/SGRC/css/common.css">
    <title>Modification carte</title>
</head>
<body>
    <main>
        <a href="?page=menu" class="back_btn">Retour</a>
        <caption>Carte</caption>
        <div class="container_menu">
            <div class="tabs">
                <?php
                $statmt28->execute();
                $categories = $statmt28->fetchAll(PDO::FETCH_ASSOC);
                // CREATION DES BOUTONS
                foreach ($categories as $categorie){
                    $cat = $categorie['id_cat'];
                    ?>
                    <button class="tab-button" id="tab-button-<?php echo $cat; ?>"><?php echo $categorie['nom_cat']; ?></button>
                    <?php
                }
                ?>
            </div>
            <?php
            // CREATION DES SOUS BOUTONS
            foreach ($categories as $categorie){
                $cat = $categorie['id_cat'];
            ?>
            <div class="tab-content" id="tab-content-<?php echo $cat ?>">
                <?php
                $statmt->execute();
                $id_sous_cats = $statmt->fetchAll(PDO::FETCH_ASSOC);

                // CREATION DES DONNEES A AFFICHER
                foreach ($id_sous_cats as $id_sous_cat) {
                    $idsouscat = $id_sous_cat['id_sous_cat'];
                    if ($id_sous_cat['id_cat'] == $cat){

                    
                ?>
                <button class="sub-tab-button" onclick="toggleSubTabContent('<?php echo $idsouscat; ?>')"><?php echo $id_sous_cat['nom_sous_cat'] ?></button>
                <div class="sub-tab-content" id="sub-tab-content-<?php echo $idsouscat; ?>" style="display: none;">
                    <?php
                    echo '<table class="tableauMenu" border="0">';
                    echo '<tr class="nomColonne"><td> Nom du plat </p></td><td> Description </td><td> Prix </td><td> Action </td></tr>';
                    foreach ($cartes as $carte) {
                        if ($carte['nom_sous_cat'] === $id_sous_cat['nom_sous_cat']) {
                            // Tableau pour afficher les détails du plat
                            echo'
                                <tr>
                                <td>' . $carte['nom_plat'] . '</td>
                                <td>' . $carte['description'] . '</td>
                                <td>' . $carte['PU_carte'] . '</td>
                                <td>
                                <form method="post" action="">
                                    <input type="hidden" name="action" value="suppr carte">
                                    <input type="hidden" name="id_p" value="' . $carte['id_plat'] . '">
                                    <button type="submit" onclick="return confirm(\'Êtes-vous sûr de vouloir supprimer ?\')">
                                        <img class="icon_size" src="image\icone\trash.svg" alt="Icone Delete Plat">
                                    </button>
                                </form>
                                </td>
                                </tr>';
                        }
                    }
                    echo '</table>';
                    ?>
                </div>
                <?php
                    }
                }
                ?>
            </div>
                <?php
                }
            ?>
            <tr class="add">
                <input type="hidden" name="action" value="ajout carte">
                <input type="hidden" name="id_m" value="48">
                <td colspan="5">
                    <a class="icon_size" href="?page=ajout_carte">
                        <img class="icon_size_sidebar" src="image\icone\plus.svg" alt="Icone Plus">
                    </a>
                </td>
            </tr>
            <script>
                function toggleSubTabContent(subTabId) {
                    var subTabContent = document.getElementById('sub-tab-content-' + subTabId);
                    if (subTabContent.style.display === 'none') {
                        subTabContent.style.display = 'block';
                    } else {
                        subTabContent.style.display = 'none';
                    }
                }
            </script>
        </div>
    </main>
    <div class="right">
        <div class="top">
            <div class="theme-toggler" id="theme-toggler">
                <!-- Dark and Light -->
                <i><img class="icon_darkmode" src="image\icone\darkmode.svg" alt="Icone Dark Mode"></i>
            </div>
            <div class="profil">
                <?php
                $sql_req = "SELECT * FROM user WHERE id_user = " . $_SESSION['id_user'];
                $statm = $pdo->prepare($sql_req);
                $statm->execute();
                $row = $statm->fetch();
                ?>
                <div class="profil-photot">
                    <!-- <img src="/SGRC/php/image/profils/<?php echo $row['image']; ?>" alt=""> -->
                    <!-- <img src="/SGRC/image/img/source/profil.jpg" alt="Profil" /> -->
                </div>
            </div>
        </div>
    </div>
    <!-- Script DarkMode -->
    <script src="/SGRC/js/source/dark_mode.js"></script>
    <!-- Script Menu -->
    <script src="/SGRC/js/source/menu.js"></script>
    <!-- SCRIPT FONT AWESOME -->
    <script src="https://kit.fontawesome.com/438cd94e6c.js" crossorigin="anonymous"></script>
    <script>
        const tabButtons = document.querySelectorAll('.tab-button'); //Cuisine/Bar/Sommelier
        const tabContents = document.querySelectorAll('.tab-content');//mise en bouche /entrée /plat principal /dessert
        function handleTabButtonClick(event) {
            // Masquer tous les contenus d'onglet
            tabContents.forEach(tabContent => {
                tabContent.style.display = 'none';
            });
            // Afficher le contenu de l'onglet sélectionné
            const tabContentId = event.target.id.replace('tab-button-', 'tab-content-');
            document.getElementById(tabContentId).style.display = 'flex';

            // Supprimer la classe active de tous les boutons d'onglet
            tabButtons.forEach(tabButton => {
                tabButton.classList.remove('active');
            });

            // Ajouter la classe active au bouton d'onglet sélectionné
            event.target.classList.add('active');
        }


        // Ajouter un gestionnaire d'événement de clic sur chaque bouton d'onglet
        tabButtons.forEach(tabButton => {
            tabButton.addEventListener('click', handleTabButtonClick);
        });  

        

        const subTabButtons = document.querySelectorAll('.sub-tab-button');
        const subTabContents = document.querySelectorAll('.sub-tab-content');

        function handleSubTabButtonClick(event) {
            // Si le bouton est active alors remove active sinon met active
            if (event.target.classList.contains('active')) {
                event.target.classList.remove('active');
            } else {
                // Ajouter la classe active au bouton d'onglet sélectionné
                event.target.classList.add('active');
            }            
        }
        //Ajouter un gestionnaire d'événement de clic sur chaque sous bouton d'onglet
        subTabButtons.forEach(subTabButton => {
            subTabButton.addEventListener('click', handleSubTabButtonClick);
        });

        // Selection par défaut du premier onglet 
        const affichageCuisine = document.getElementById("tab-button-1");
        affichageCuisine.click();
        

        // Sélectionne toutes les lignes du tableau ayant la classe 'tableauMenu', à partir de la deuxième ligne
        const tableRows = document.querySelectorAll('.tableauMenu tr');
        const rowsToIterate = Array.from(tableRows).slice(1); // Exclut la première ligne

        rowsToIterate.forEach((row, index) => {
            // Sélectionne tous les boutons avec la classe 'icon_size' à l'intérieur de chaque ligne
            const icons = row.querySelectorAll('td form button .icon_size');

            icons.forEach((icon, i) => {
                // Applique les styles alternés en fonction de la position de l'élément dans le tableau
                if ((index + i) % 2 === 0) {
                // Ligne paire
                icon.style.backgroundColor = 'var(--color-cartemenu-dark)';
                icon.style.color = 'var(--color-dark)';
                } else {
                // Ligne impaire
                icon.style.backgroundColor = 'var(--color-cartemenu-light)';
                icon.style.color = 'var(--color-dark)';
            }});
        });

    </script>
</body>
</html>
<?php
} else {
    echo ("Vous n'avez pas le droit d'être là");
    header("Location: /SGRC/index.php");
    exit();
}
?>
