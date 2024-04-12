<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

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
        <link href="/SGRC/css/style_admin/produit/edition-ajoute.css" rel="stylesheet">
        <title>Ajout Table</title>
    </head>

    <body>
        <div class="right">
            <div class="theme-toggler" id="theme-toggler">
                <!-- Dark and Light -->
                <i><img class="icon_darkmode" src="image\icone\darkmode.svg" alt="🌙"></i>
            </div>
        </div>
        <form action="" method="POST" id="ValidationDuFormulaireTable">
            <input type="hidden" name="action" value="ajouter table">
            <a href="?page=table" class="back_btn">Retour</a>
            <h2>Ajouter une table </h2>
            <!-- <label for="id_table">Identifiant</label> -->
            <input name="id_table" id="id_table" type="hidden">
            <!-- Le numero de la table -->
            <label for="numero_table">Numero de la table</label>
            <input name="numero_table" id="numero_table" autofocus="autofocus" type="number" min="1"> <br>
            <!-- Le type de table -->
            <label for="type_table">Type de table</label>
            <select name="type_table" id="type_table">
                <?php
                // Options manuelles
                $optionsManuelles = array("Carrée", "Ronde", "Rectangulaire");

                // Boucle pour les options manuelles
                foreach ($optionsManuelles as $optionManuelle) {
                ?>
                    <option value="<?php echo $optionManuelle; ?>">
                        <?php echo $optionManuelle; ?>
                    </option>
                <?php
                }


                ?>
            </select><br>
            <!--visible-->
            <label for="vu">visible/invisible</label>
            <select name="vu" id="vu">
                <option value="0">visible</option>
                <option value="1">invisible</option>
            </select>
            <br>
            <!-- Le bouton d'envoi -->
            <input type="submit" name="Validez" value="Ajouter"> <br>

            <p style="color: red;" id="erreur"></p>
        </form>



        <!-- Script DarkMode -->
        <script src="/SGRC/js/source/dark_mode.js"></script>
        <!-- SCRIPT FONT AWESOME -->
        <script src="https://kit.fontawesome.com/438cd94e6c.js" crossorigin="anonymous"></script>
    </body>

    </html>
<?php
} else {
    echo ("vous n'avez pas le droit d'être là");
    header("Location: /SGRC/index.php");
    exit();
}
?>