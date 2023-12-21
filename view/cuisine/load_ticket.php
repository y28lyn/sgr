<div class="card_ticket">
    <?php
    require_once("../../include/connexion.php");
    // Récupérer les données des requêtes SQL
    $tickets = $_GET['tickets'];
    list($statmt16, $statmt17) = $_GET['sqlQueries'];
    $dateDuJour = date("Y-m-d");
    $requetePrixMenu = $pdo->prepare("SELECT PU FROM menu WHERE date_menu = :dateDuJour;");
    $requetePrixMenu->bindParam(':dateDuJour', $dateDuJour);
    $requetePrixMenu->execute();
    $prixMenu = $requetePrixMenu->fetch();
    foreach ($tickets as $ticket) {
    ?>
        <div class="ticket" id="load_ticket">


            <table>
                <caption>
                    <?php
                    echo "Table n° :" . "\n" . $ticket['numero_table'] . '<br/>';
                    echo "N° Ticket : #";
                    $numTicket = $ticket['id_ticket'];
                    while (strlen($numTicket) < 3) {
                        $numTicket = '0' . $numTicket;
                    }
                    echo $numTicket . '<br>';
                    $status = "";
                    if ($ticket['statut'] == 'PAY') {
                        $status = "<p class='success'>PAY</p>";
                    } elseif ($ticket['statut'] == 'VAL') {
                        $status = "<p class='warning'>VALIDE</p>";
                    } else {
                        $status = "<p class='danger'>SAISIE</p>";
                    }
                    echo "statut : " . $status;
                    ?>
                </caption>
                <thead>
                    <tr>
                        <th>Quantit&eacute;</th>
                        <th>Nom du Plat</th>
                        <th>Commentaires</th>
                        <th>Etat</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <?php
                // recup de la liste des tickets non payé
                $statmt16 = $pdo->prepare('SELECT ticket.*,sgr_table.* FROM ticket,sgr_table,ligne_ticket,plat WHERE statut != "PAY" AND plat.type_plat != "boisson" AND ticket.id_table = sgr_table.id_table AND ticket.id_ticket = ligne_ticket.id_ticket AND ligne_ticket.id_plat = plat.id_plat GROUP BY ticket.id_ticket');
                $statmt16->execute();
                $tickets = $statmt16->fetchAll(PDO::FETCH_ASSOC);
                $u = $ticket['id_ticket'];

                $statmt17 = $pdo->prepare('SELECT ticket.id_ticket, plat.id_plat, plat.nom_plat, COUNT(nom_plat) AS quantite, ligne_ticket.commentaire,ligne_ticket.Etat AS Etat, categorie_plat.ordre_affichage_cat, sous_categorie.ordre_aff_sous_cat FROM ligne_ticket, plat, ticket,categorie_plat, sous_categorie  WHERE ticket.id_ticket = :id_ticket AND ticket.id_ticket = ligne_ticket.id_ticket AND plat.id_plat=ligne_ticket.id_plat AND type_plat != "boisson" AND categorie_plat.id_cat = sous_categorie.id_cat AND plat.id_sous_cat = sous_categorie.id_sous_cat GROUP BY nom_plat, ligne_ticket.commentaire,Etat ORDER BY categorie_plat.ordre_affichage_cat, sous_categorie.ordre_aff_sous_cat;');
                $statmt17->bindParam(':id_ticket', $u, PDO::PARAM_INT);
                $statmt17->execute();

                $commandes = $statmt17->fetchAll();
                foreach ($commandes as $commande) {
                ?>
                    <tbody>
                        <tr>
                            <td>
                                <?php echo $commande['quantite']; ?>
                            </td>
                            <td>
                                <?php echo $commande['nom_plat']; ?>
                            </td>
                            <td>
                                <?php echo $commande['commentaire']; ?>
                            </td>
                            <td>
                                <?php echo $commande['Etat']; ?>
                            </td>
                            <?php
                            if ($commande['Etat'] == "En saisie") {
                            ?>
                                <td>
                                    En Attente de Validation
                                </td>
                                <?php
                            } else {

                                if ($commande['Etat'] == "Demandé") { ?>
                                    <td>
                                        <form method="POST">
                                            <input type="hidden" name="action" value="etatEnCours">
                                            <input type="hidden" name="id_ticket" value="<?php echo $commande['id_ticket']; ?>">
                                            <input type="hidden" name="id_plat" value="<?php echo $commande['id_plat']; ?>">
                                            <input type="hidden" name="commentaire" value="<?php echo $commande['commentaire'] ?>">
                                            <input type="hidden" name="etat" value="<?php echo $commande['Etat']; ?>">
                                            <input type="submit" value="En cours">
                                        </form>
                                    </td>
                                    <?php } else {
                                    if ($commande['Etat'] == "En cours") { ?>
                                        <td>
                                            <form method="POST">
                                                <input type="hidden" name="action" value="etatPret">
                                                <input type="hidden" name="id_ticket" value="<?php echo $commande['id_ticket']; ?>">
                                                <input type="hidden" name="id_plat" value="<?php echo $commande['id_plat']; ?>">
                                                <input type="hidden" name="commentaire" value="<?php echo $commande['commentaire']; ?>">
                                                <input type="hidden" name="etat" value="<?php echo $commande['Etat']; ?>">
                                                <input type="submit" value="Prêt">
                                            </form>
                                        </td>
                                        <?php } else { ?><?php
                                                            if ($commande['Etat'] == "Prêt") { ?>
                                        <td>
                                            <p>A servir</p>
                                        </td>
                                    <?php } else { ?>
                                        <td>
                                            Fini
                                        <td>
                            <?php }
                                                        }
                                                    }
                                                } ?>
                        </tr>
                    </tbody>
                <?php
                }
                ?>
            </table>
        </div>
    <?php
    }
    ?>
</div>