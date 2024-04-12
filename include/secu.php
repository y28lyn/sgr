<?php
require_once('connexion.php');
//inclusion de la laison bdd 
if (isset($_SESSION["role"])) {
    switch ($_SESSION["role"]) {

            //liste des re requêtes de l'interface admin
        case "admin": {
                //login ok et admin
                if (isset($_POST) && isset($_POST['action'])) {
                    //on est connecté en admin mais on viens d'un formulaire

                    //case gérant les ajouts/suppressions/modifications dans les différentes tables de la bdd
                    switch ($_POST['action']) {
                        case "ajouter table": {
                                $id_t = htmlspecialchars($_POST['id_table']);
                                $num_table = htmlspecialchars($_POST['numero_table']);
                                $type_table = htmlspecialchars($_POST['type_table']);
                                $vu_table = htmlspecialchars($_POST['vu']);

                                // Vérification si le numéro de table existe déjà
                                $checkQuery = "SELECT id_table FROM sgr_table WHERE numero_table = ?";
                                $checkStatement = $pdo->prepare($checkQuery);
                                $checkStatement->execute([$num_table]);
                                $existingTable = $checkStatement->fetch();

                                if (!$existingTable) {
                                    $insertQuery = "INSERT INTO sgr_table (id_table, numero_table, type_table, vu) VALUES (NULL, ?, ?, ?)";
                                    $insertStatement = $pdo->prepare($insertQuery);
                                    $insertStatement->execute([$num_table, $type_table, $vu_table]);
                                    header('location: /SGRC/index.php?page=table');
                                } else {
                                    echo "<script> alert('ERREUR : le numéro de table existe déjà !')</script>";
                                }
                                break;
                            }

                        case "update table": {
                                $id_t = htmlspecialchars($_POST['id_table']);
                                $num_table = htmlspecialchars($_POST['numero_table']);
                                $type_table = htmlspecialchars($_POST['type_table']);
                                $vu_tb = htmlspecialchars($_POST['vu']);
                                $vu_tb = ($vu_tb == 'Visible') ? '0' : '1';

                                // Vérification si le numéro de table existe déjà
                                $checkQuery = "SELECT id_table FROM sgr_table WHERE numero_table = ? AND id_table <> ?";
                                $checkStatement = $pdo->prepare($checkQuery);
                                $checkStatement->execute([$num_table, $id_t]);
                                $existingTable = $checkStatement->fetch();

                                if (!$existingTable) {
                                    $updateQuery = "UPDATE sgr_table SET numero_table=?, type_table=?, vu=? WHERE id_table=?";
                                    $updateStatement = $pdo->prepare($updateQuery);
                                    $updateStatement->execute([$num_table, $type_table, $vu_tb, $id_t]);
                                    header('location: /SGRC/index.php?page=table');
                                } else {
                                    $_POST["id_t"] = $id_t;
                                    echo "<script> alert('ERREUR : le numéro de table existe déjà !')</script>";
                                }
                                break;
                            }

                        case "vis table": {
                                if (isset($_POST['id_table'])) {
                                    $id_table = $_POST['id_table'];
                                    $statmt = $pdo->prepare('UPDATE `sgr_table` SET `vu` = ' . $_POST['visibilite'] . ' WHERE `id_table`=' . $id_table . ';');
                                    $statmt->execute();
                                    header('location: /SGRC/index.php?page=table');
                                }
                                break;
                            }

                        case "suppr table": {
                                if (isset($_POST['id_table'])) {
                                    $id_table = $_POST['id_table'];
                                    $statmt = $pdo->prepare("DELETE from sgr_table where `id_table`=" . $id_table . ';');
                                    $statmt->execute();
                                    header('location: /SGRC/index.php?page=table');
                                }
                                break;
                            }
                            //ajout/modifictaion/suppression des boissons et plats dans la base de données
                        case "ajout boisson": {
                                $id_pl = htmlspecialchars($_POST['id_plat']);
                                $nom_pl = htmlspecialchars($_POST['nom_plat']);
                                $desc_pl = htmlspecialchars($_POST['description']);
                                $stp = htmlspecialchars($_POST['type_plat']);
                                $prix_carte = htmlspecialchars($_POST['PU_carte']);
                                $type_pl = htmlspecialchars("boisson");
                                $vu = htmlspecialchars($_POST['vu']);
                                $pdo->query("INSERT INTO plat (id_plat, nom_plat, description, type_plat, PU_carte, id_sous_cat, vu ) VALUE(NULL, '$nom_pl' , '$desc_pl', '$type_pl','$prix_carte','$stp','$vu')");
                                header('location: /SGRC/index.php?page=boisson');
                                break;
                            }

                        case "update boisson": {
                                $id_pl = htmlspecialchars($_POST['id_plat']);
                                $nom_pl = htmlspecialchars($_POST['nom_plat']);
                                $desc_pl = htmlspecialchars($_POST['description']);
                                $type_pl = htmlspecialchars($_POST['type_plat']);
                                $prix_carte = htmlspecialchars($_POST['PU_carte']);
                                $vu_pl = htmlspecialchars($_POST['vu']);
                                $vu_pl = ($vu_pl == 'Visible') ? '0' : '1';

                                $reqUP = "UPDATE plat SET nom_plat='$nom_pl',description='$desc_pl',id_sous_cat='$type_pl' ,PU_carte='$prix_carte' ,vu='$vu_pl' WHERE id_plat ='$id_pl'";
                                $resulat = mysqli_query($link, $reqUP);
                                header('location: /SGRC/index.php?page=boisson');
                                break;
                            }

                        case "vis boisson": {
                                if (isset($_POST['id_b'])) {
                                    $id_plat = $_POST['id_b'];
                                    $statmt = $pdo->prepare('UPDATE `plat` SET `vu` = ' . $_POST['visibilite'] . ' WHERE `id_plat`=' . $id_plat . ';');
                                    $statmt->execute();
                                    header('location: /SGRC/index.php?page=boisson');
                                }
                                break;
                            }

                        case "suppr boisson": {
                                if (isset($_POST['id_b'])) {
                                    $id_boisson = $_POST['id_b'];
                                    $statmt = $pdo->prepare('DELETE from plat where `id_plat`=' . $id_boisson . ';');
                                    $statmt->execute();
                                    header('location: /SGRC/index.php?page=boisson');
                                }
                                break;
                            }

                        case "ajout plat": {
                                $np = $_POST["nom_plat"];
                                $desc = $_POST["description"];
                                $tp = "plat";
                                $puc = $_POST["PU_carte"];
                                $stp = $_POST['sous_type_plat'];
                                $vu_pl = $_POST['vu'];
                                //echo 
                                $statmt = $pdo->prepare("INSERT INTO `plat` (`id_plat`, `nom_plat`, `description`, `type_plat`, `PU_carte`, `id_sous_cat`, `vu`) VALUES (NULL, '" . $np . "', '" . $desc . "', '" . $tp . "', '" . $puc . "', '" . $stp . "', '" . $vu_pl . "');");
                                $statmt->execute();
                                header('location: /SGRC/index.php?page=plat');
                                break;
                            }

                        case "update plat": {
                                $id_pl = htmlspecialchars($_POST['id_plat']);
                                $nom_pl = htmlspecialchars($_POST['nom_plat']);
                                $desc_pl = htmlspecialchars($_POST['description']);
                                $type_pl = "plat";
                                $sous_type_pl = htmlspecialchars($_POST['sous_type_plat']);
                                $prix_carte = htmlspecialchars($_POST['PU_carte']);
                                $vu_pl = htmlspecialchars($_POST['vu']);
                                $vu_pl = ($vu_pl == 'Visible') ? '0' : '1';
                                // Variable de id_menu en GET
                                $reqUP = "UPDATE plat SET nom_plat='$nom_pl', description='$desc_pl', id_sous_cat='$sous_type_pl', PU_carte='$prix_carte', vu='$vu_pl' WHERE id_plat ='$id_pl'";
                                $resulat = mysqli_query($link, $reqUP);
                                header('location: /SGRC/index.php?page=plat');
                                break;
                            }

                        case "vis plat": {
                                if (isset($_POST['id_b'])) {
                                    $id_plat = $_POST['id_b'];
                                    $statmt = $pdo->prepare('UPDATE `plat` SET `vu` = ' . $_POST['visibilite'] . ' WHERE `id_plat`=' . $id_plat . ';');
                                    $statmt->execute();
                                    header('location: /SGRC/index.php?page=plat');
                                }
                                break;
                            }



                        case "suppr plat": {
                                if (isset($_POST['id_pl'])) {
                                    $id_plat = $_POST['id_pl'];
                                    $statmt = $pdo->prepare('DELETE from plat where `id_plat`=' . $id_plat . ';');
                                    $statmt->execute();
                                    header('location: /SGRC/index.php?page=plat');
                                }
                                break;
                            }

                        case "ajout menu": {
                                $nm = $_POST["nom_menu"];
                                $dm = $_POST["description"];
                                $Pm = $_POST["PU"];
                                $dtm = $_POST["date_menu"];
                                $vu = $_POST["vu"];
                                $statmt = $pdo->prepare("INSERT INTO `menu` (`id_menu`, `nom_menu`, `description`, `PU`,`date_menu`,`vu`) VALUES (NULL, '" . $nm . "', '" . $dm . "', '" . $Pm . "', '" . $dtm . "', '" . $vu . "');");
                                $statmt->execute();

                                $stmt2 = $pdo->prepare("SELECT `id_menu` FROM `menu` ORDER BY id_menu DESC LIMIT 1");
                                $stmt2->execute();
                                $result = $stmt2->fetch(PDO::FETCH_ASSOC);
                                $id_m = $result['id_menu'];

                                $stmt = $pdo->query("SELECT id_plat FROM `plat` WHERE type_plat ='boisson'");

                                // Boucle pour ajouter chaque enregistrements
                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                    // Préparation de la requête d'insertion
                                    $insert = $pdo->prepare("INSERT INTO menu_contient_plat (id_menu, id_plat) VALUES (:id_menu, :id_plat)");
                                    $insert->bindParam(':id_menu', $id_m, PDO::PARAM_INT);
                                    $insert->bindValue(':id_plat', $row['id_plat'], PDO::PARAM_STR);

                                    // Exécution de la requête d'insertion
                                    $insert->execute();
                                }
                                header('location: /SGRC/index.php?page=menu');
                                break;
                            }


                        case "update menu": {
                                $id_m = htmlspecialchars($_POST['id_menu']);
                                $nom_m = htmlspecialchars($_POST['nom_menu']);
                                $desc_m = htmlspecialchars($_POST['description']);
                                $prix_u = htmlspecialchars($_POST['PU']);
                                $vu_menu = htmlspecialchars($_POST['vu']);
                                $dtm = htmlspecialchars($_POST['date_menu']);

                                $stmt = $pdo->prepare("UPDATE menu SET nom_menu = :nom_m, description = :desc_m, PU = :prix_u, vu = :vu_menu, date_menu = :dtm WHERE id_menu = :id_m");
                                $stmt->bindParam(':id_m', $id_m);
                                $stmt->bindParam(':nom_m', $nom_m);
                                $stmt->bindParam(':desc_m', $desc_m);
                                $stmt->bindParam(':prix_u', $prix_u);
                                $stmt->bindParam(':vu_menu', $vu_menu);
                                $stmt->bindParam(':dtm', $dtm);
                                $stmt->execute();
                                header('location: /SGRC/index.php?page=menu');
                                break;
                            }

                        case "suppr menu": {
                                if (isset($_POST['id_m'])) {
                                    $id_menu = $_POST['id_m'];
                                    $statmt = $pdo->prepare("DELETE FROM menu_contient_plat WHERE id_menu = :id_menu");
                                    $statmt->bindParam(":id_menu", $id_menu, PDO::PARAM_INT);
                                    $statmt->execute();
                                    $statmt = $pdo->prepare("DELETE FROM menu WHERE `id_menu`= :id_menu ;");
                                    $statmt->bindParam(':id_menu', $id_menu, PDO::PARAM_INT);
                                    $statmt->execute();
                                    header('location: /SGRC/index.php?page=menu');
                                }
                                break;
                            }

                        case "carte menu": {
                                $id_m = $_POST['id_m'];
                                $_SESSION['id_m'] = $_POST['id_m'];
                                $requete_carte = $pdo->prepare("SELECT P.id_plat, P.nom_plat, P.description, P.type_plat, S.nom_sous_cat, P.PU_carte from menu_contient_plat MP, plat P, sous_categorie S, menu M WHERE MP.id_menu=M.id_menu AND MP.id_plat=P.id_plat AND P.id_sous_cat = S.id_sous_cat AND P.vu=0 and MP.id_menu = :id_m;");
                                $requete_carte->bindParam(':id_m', $id_m, PDO::PARAM_INT);
                                header('location: /SGRC/index.php?page=carte_menu');
                                break;
                            }

                        case "suppr carte": {
                                $id_pl = $_POST['id_p'];
                                $statmt = $pdo->prepare('DELETE FROM `menu_contient_plat` WHERE id_plat =' . $id_pl . ';');
                                $statmt->execute();
                                break;
                            }

                        case "ajout carte": {
                                $id_m = $_SESSION['id_m'];
                                $id_p = $_POST['id_plat'];
                                $statmt = $pdo->prepare("INSERT INTO `menu_contient_plat`(`id_menu`, `id_plat`) VALUES ('.$id_m.','.$id_p.');");
                                $statmt->execute();
                                header('location: /SGRC/index.php?page=carte_menu');
                                break;
                            }



                        case "suppr_utilisateur": {
                                $id_u = $_POST['id_u'];
                                $statmt = $pdo->prepare('DELETE FROM `user` WHERE id_user =' . $id_u . ';');
                                $statmt->execute();
                                header('location: /SGRC/index.php?page=users');
                                break;
                            }

                        case "modif_utilisateur": {
                                $id_u = $_POST['id_user'];
                                $nouveau_login = $_POST['login'];
                                $nouveau_role = $_POST['role'];
                                $nouveau_mot_de_passe = password_hash($_POST['mdp'], PASSWORD_DEFAULT);

                                $statmt = $pdo->prepare("UPDATE user SET login = ?, role = ?, mdp = ? WHERE id_user = ?");
                                $statmt->execute([$nouveau_login, $nouveau_role, $nouveau_mot_de_passe, $id_u]);

                                header('Location: /SGRC/index.php?page=users');
                                break;
                            }



                        case "suppr_utilisateur": {
                                $id_u = $_POST['id_u'];
                                $statmt = $pdo->prepare('DELETE FROM `user` WHERE id_user =' . $id_u . ';');
                                $statmt->execute();
                                header('location: /SGRC/index.php?page=users');
                                break;
                            }

                        case "modif_utilisateur": {
                                $id_u = $_POST['id_u'];
                                $nouveau_login = $_POST['nouveau_login'];
                                $nouveau_role = $_POST['nouveau_role'];
                                $nouveau_mot_de_passe = $_POST['nouveau_mot_de_passe'];

                                $statmt = $pdo->prepare("UPDATE user SET login = ?, role = ?, mdp = ? WHERE id_user = ?");
                                $statmt->execute([$nouveau_login, $nouveau_role, $nouveau_mot_de_passe, $id_u]);

                                header('Location: /SGRC/index.php?page=modif_utilisateur');
                                break;
                            }



                        case "monter ordre cat": {
                                $id = htmlspecialchars($_POST['id_c']);
                                $stmt = $pdo->prepare("SELECT ordre_affichage_cat FROM categorie_plat WHERE id_cat = :id");
                                $stmt->execute(array(':id' => $id));
                                $currentValue = $stmt->fetch(PDO::FETCH_ASSOC);
                                $current_ordre = $currentValue['ordre_affichage_cat'];
                                if ($current_ordre > 1) {
                                    $ordremoin = $current_ordre - 1;
                                    $stmt = $pdo->prepare("SELECT id_cat FROM categorie_plat WHERE ordre_affichage_cat = :ordremoin");
                                    $stmt->execute(array(':ordremoin' => $ordremoin));
                                    $previousValue = $stmt->fetch(PDO::FETCH_ASSOC);
                                    $previousId = $previousValue['id_cat'];
                                    $stmt = $pdo->prepare("UPDATE categorie_plat SET ordre_affichage_cat = :ordremoin WHERE id_cat = :id");
                                    $stmt->execute(array(':ordremoin' => $ordremoin, ':id' => $id));
                                    $stmt = $pdo->prepare("UPDATE categorie_plat SET ordre_affichage_cat = :current_ordre WHERE id_cat = :previousId");
                                    $stmt->execute(array(':current_ordre' => $current_ordre, ':previousId' => $previousId));
                                }
                                break;
                            }

                        case "monter ordre sscat": {
                                $id = htmlspecialchars($_POST['id_c']);
                                $idcat = htmlspecialchars($_POST['id_t']);
                                $sql = "SELECT ordre_aff_sous_cat FROM sous_categorie WHERE id_sous_cat = :id AND id_cat = :idcat";
                                $stmt = $pdo->prepare($sql);
                                $stmt->bindValue(':id', $id, PDO::PARAM_INT);
                                $stmt->bindValue(':idcat', $idcat, PDO::PARAM_INT);
                                $stmt->execute();
                                $currentValue = $stmt->fetch(PDO::FETCH_ASSOC);
                                $current_ordre = $currentValue['ordre_aff_sous_cat'];
                                if ($current_ordre > 1) {
                                    $ordremoin = $current_ordre - 1;
                                    $sql = "SELECT id_sous_cat FROM sous_categorie WHERE ordre_aff_sous_cat = :ordremoin AND id_cat = :idcat";
                                    $stmt = $pdo->prepare($sql);
                                    $stmt->bindValue(':ordremoin', $ordremoin, PDO::PARAM_INT);
                                    $stmt->bindValue(':idcat', $idcat, PDO::PARAM_INT);
                                    $stmt->execute();
                                    $previousValue = $stmt->fetch(PDO::FETCH_ASSOC);
                                    $previousId = $previousValue['id_sous_cat'];
                                    $sql = "UPDATE sous_categorie SET ordre_aff_sous_cat = :ordremoin WHERE id_sous_cat = :id AND id_cat = :idcat";
                                    $stmt = $pdo->prepare($sql);
                                    $stmt->bindValue(':ordremoin', $ordremoin, PDO::PARAM_INT);
                                    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
                                    $stmt->bindValue(':idcat', $idcat, PDO::PARAM_INT);
                                    $stmt->execute();
                                    $sql = "UPDATE sous_categorie SET ordre_aff_sous_cat = :current_ordre WHERE id_sous_cat = :previousId AND id_cat = :idcat";
                                    $stmt = $pdo->prepare($sql);
                                    $stmt->bindValue(':current_ordre', $current_ordre, PDO::PARAM_INT);
                                    $stmt->bindValue(':previousId', $previousId, PDO::PARAM_INT);
                                    $stmt->bindValue(':idcat', $idcat, PDO::PARAM_INT);
                                    $stmt->execute();
                                }
                                break;
                            }

                        case "sous_categorie": {
                                break;
                            }

                        case "descendre ordre cat": {
                                $id = htmlspecialchars($_POST['id_c']);
                                $sql = "SELECT ordre_affichage_cat FROM categorie_plat WHERE id_cat = :id";
                                $stmt = $pdo->prepare($sql);
                                $stmt->bindParam(':id', $id);
                                $stmt->execute();
                                $currentValue = $stmt->fetch(PDO::FETCH_ASSOC);
                                $current_ordre = $currentValue['ordre_affichage_cat'];
                                $sql = "SELECT MAX(ordre_affichage_cat) FROM categorie_plat";
                                $stmt = $pdo->prepare($sql);
                                $stmt->execute();
                                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                                $maximum = $row['MAX(ordre_affichage_cat)'];
                                if ($current_ordre < $maximum) {
                                    $ordreplus = $current_ordre + 1;
                                    $sql = "SELECT id_cat FROM categorie_plat WHERE ordre_affichage_cat = :ordreplus";
                                    $stmt = $pdo->prepare($sql);
                                    $stmt->bindParam(':ordreplus', $ordreplus);
                                    $stmt->execute();
                                    $previousValue = $stmt->fetch(PDO::FETCH_ASSOC);
                                    $previousId = $previousValue['id_cat'];
                                    $sql = "UPDATE categorie_plat SET ordre_affichage_cat = :ordreplus WHERE id_cat = :id";
                                    $stmt = $pdo->prepare($sql);
                                    $stmt->bindParam(':id', $id);
                                    $stmt->bindParam(':ordreplus', $ordreplus);
                                    $stmt->execute();
                                    $sql = "UPDATE categorie_plat SET ordre_affichage_cat = :current_ordre WHERE id_cat = :previousId";
                                    $stmt = $pdo->prepare($sql);
                                    $stmt->bindParam(':previousId', $previousId);
                                    $stmt->bindParam(':current_ordre', $current_ordre);
                                    $stmt->execute();
                                }
                                break;
                            }

                        case "descendre ordre sscat": {
                                $ssid = htmlspecialchars($_POST['id_t']);
                                $id = htmlspecialchars($_POST['id_c']);
                                $sql = "SELECT ordre_aff_sous_cat FROM sous_categorie WHERE id_sous_cat = ? AND id_cat = ?";
                                $stmt = $pdo->prepare($sql);
                                $stmt->execute([$id, $ssid]);
                                $currentValue = $stmt->fetch();
                                $current_ordre = $currentValue['ordre_aff_sous_cat'];
                                $sql = "SELECT MAX(ordre_aff_sous_cat) FROM sous_categorie WHERE id_cat = ? AND id_cat = ?";
                                $stmt = $pdo->prepare($sql);
                                $stmt->execute([$ssid, $ssid]);
                                $row = $stmt->fetch();
                                $maximum = $row['MAX(ordre_aff_sous_cat)'];
                                if ($current_ordre < $maximum) {
                                    $ordreplus = $current_ordre + 1;
                                    $sql = "SELECT id_sous_cat FROM sous_categorie WHERE ordre_aff_sous_cat = ? AND id_cat = ?";
                                    $stmt = $pdo->prepare($sql);
                                    $stmt->execute([$ordreplus, $ssid]);
                                    $previousValue = $stmt->fetch();
                                    $previousId = $previousValue['id_sous_cat'];
                                    $sql = "UPDATE sous_categorie SET ordre_aff_sous_cat = ? WHERE id_sous_cat = ? AND id_cat = ?";
                                    $stmt = $pdo->prepare($sql);
                                    $stmt->execute([$ordreplus, $id, $ssid]);
                                    $stmt = $pdo->prepare("UPDATE sous_categorie SET ordre_aff_sous_cat = ? WHERE id_sous_cat = ? AND id_cat = ?");
                                    $stmt->execute([$current_ordre, $previousId, $ssid]);
                                }
                                break;
                            }

                        case "ajout categorie": {
                                $nom_cat = $_POST['nom_cat'];
                                $sql = "SELECT MAX(ordre_affichage_cat) as oac FROM categorie_plat";
                                $stmt = $pdo->prepare($sql);
                                $stmt->execute();
                                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                                $oac = $result['oac'] + 1;
                                $stmt = $pdo->prepare("INSERT INTO categorie_plat (id_cat, nom_cat, ordre_affichage_cat) VALUES (null, '$nom_cat', $oac)");
                                $stmt->execute();
                                header('location: /SGRC/index.php?page=categorie');
                                break;
                            }

                        case "ajout sous categorie": {
                                $nom_sous_cat = $_POST['nom_sous_cat'];
                                $idcat = $_SESSION['id_t'];
                                $couleur = htmlspecialchars($_POST['couleur']);

                                // Préparation de la première requête pour obtenir la valeur MAX
                                $sql = "SELECT MAX(ordre_aff_sous_cat) as oac FROM sous_categorie WHERE id_cat = :idcat";
                                $stmt = $pdo->prepare($sql);
                                $stmt->bindParam(':idcat', $idcat, PDO::PARAM_INT);
                                $stmt->execute();
                                $result = $stmt->fetch(PDO::FETCH_ASSOC);

                                $oac = $result['oac'] + 1;

                                // Préparation de la deuxième requête pour l'INSERT
                                $sql = "INSERT INTO sous_categorie (id_sous_cat, id_cat, nom_sous_cat, ordre_aff_sous_cat, couleur) VALUES (null, :idcat, :nom_sous_cat, :oac, :couleur)";
                                $stmt = $pdo->prepare($sql);
                                $stmt->bindParam(':idcat', $idcat, PDO::PARAM_INT);
                                $stmt->bindParam(':nom_sous_cat', $nom_sous_cat, PDO::PARAM_STR);
                                $stmt->bindParam(':oac', $oac, PDO::PARAM_INT);
                                $stmt->bindParam(':couleur', $couleur, PDO::PARAM_STR);

                                $stmt->execute();
                                header('location: /SGRC/index.php?page=sous_categorie');
                                break;
                            }


                        case "update cat": {
                                $id_cat = htmlspecialchars($_POST['id_cat']);
                                $nom_cat = htmlspecialchars($_POST['nom_cat']);

                                $reqUP = $pdo->prepare("UPDATE categorie_plat SET nom_cat='$nom_cat' WHERE id_cat ='$id_cat'");
                                $reqUP->execute();
                                header('location: /SGRC/index.php?page=categorie');
                                break;
                            }
                        case "update sous cat": {
                                $id_cat = htmlspecialchars($_POST['id_t']);
                                $id_sous_cat = htmlspecialchars($_POST['id_sous_cat']);
                                $nom_sous_cat = htmlspecialchars($_POST['nom_sous_cat']);
                                $couleur = htmlspecialchars($_POST['couleur']);
                                $reqUP = $pdo->prepare("UPDATE sous_categorie SET nom_sous_cat='$nom_sous_cat', couleur='$couleur' WHERE id_cat ='$id_cat' AND id_sous_cat = '$id_sous_cat'");
                                $reqUP->execute();

                                header('location: /SGRC/index.php?page=sous_categorie');
                                break;
                            }

                        case "suppr cat": {
                                $id = htmlspecialchars($_POST['id_t']);
                                $sql = "SELECT ordre_affichage_cat FROM categorie_plat WHERE id_cat = :id";
                                $stmtordre = $pdo->prepare($sql);
                                $stmtordre->bindParam(':id', $id);
                                $stmtordre->execute();
                                $currentValue = $stmtordre->fetch(PDO::FETCH_ASSOC);
                                $current_ordre = $currentValue['ordre_affichage_cat'];
                                $sql = "SELECT MAX(ordre_affichage_cat) FROM categorie_plat";
                                $stmt = $pdo->prepare($sql);
                                $stmt->execute();
                                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                                $maximum = $row['MAX(ordre_affichage_cat)'];
                                while ($current_ordre < $maximum) {
                                    $ordreplus = $current_ordre + 1;
                                    $sql = "SELECT id_cat FROM categorie_plat WHERE ordre_affichage_cat = :ordreplus";
                                    $stmt = $pdo->prepare($sql);
                                    $stmt->bindParam(':ordreplus', $ordreplus);
                                    $stmt->execute();
                                    $previousValue = $stmt->fetch(PDO::FETCH_ASSOC);
                                    $previousId = $previousValue['id_cat'];
                                    $sql = "UPDATE categorie_plat SET ordre_affichage_cat = :ordreplus WHERE id_cat = :id";
                                    $stmt = $pdo->prepare($sql);
                                    $stmt->bindParam(':id', $id);
                                    $stmt->bindParam(':ordreplus', $ordreplus);
                                    $stmt->execute();
                                    $sql = "UPDATE categorie_plat SET ordre_affichage_cat = :current_ordre WHERE id_cat = :previousId";
                                    $stmt = $pdo->prepare($sql);
                                    $stmt->bindParam(':previousId', $previousId);
                                    $stmt->bindParam(':current_ordre', $current_ordre);
                                    $stmt->execute();
                                    $stmtordre->execute();
                                    $currentValue = $stmtordre->fetch(PDO::FETCH_ASSOC);
                                    $current_ordre = $currentValue['ordre_affichage_cat'];
                                }
                                $stmt = $pdo->prepare("DELETE FROM sous_categorie WHERE id_cat = :id_cat");
                                $stmt->bindParam(':id_cat', $id, PDO::PARAM_INT);
                                $stmt->execute();

                                $statmt = $pdo->prepare('DELETE FROM `categorie_plat` WHERE id_cat = :id_cat;');
                                $statmt->bindParam(':id_cat', $id, PDO::PARAM_INT);
                                $statmt->execute();
                                header('location: /SGRC/index.php?page=categorie');
                                break;
                            }

                        case "suppr sous cat": {
                                //code complémentaire
                                $ssid = htmlspecialchars($_POST['id_t']);
                                $id = htmlspecialchars($_POST['id_c']);

                                $sql = "SELECT ordre_aff_sous_cat FROM sous_categorie WHERE id_sous_cat = ? AND id_cat = ?";
                                $stmtordre = $pdo->prepare($sql);
                                $stmtordre->execute([$id, $ssid]);
                                $currentValue = $stmtordre->fetch();
                                $current_ordre = $currentValue['ordre_aff_sous_cat'];
                                $sql = "SELECT MAX(ordre_aff_sous_cat) FROM sous_categorie WHERE id_cat = ? AND id_cat = ?";
                                $stmt = $pdo->prepare($sql);
                                $stmt->execute([$ssid, $ssid]);
                                $row = $stmt->fetch();
                                $maximum = $row['MAX(ordre_aff_sous_cat)'];
                                while ($current_ordre < $maximum) {
                                    $ordreplus = $current_ordre + 1;
                                    $sql = "SELECT id_sous_cat FROM sous_categorie WHERE ordre_aff_sous_cat = ? AND id_cat = ?";
                                    $stmt = $pdo->prepare($sql);
                                    $stmt->execute([$ordreplus, $ssid]);
                                    $previousValue = $stmt->fetch();
                                    $previousId = $previousValue['id_sous_cat'];
                                    $sql = "UPDATE sous_categorie SET ordre_aff_sous_cat = ? WHERE id_sous_cat = ? AND id_cat = ?";
                                    $stmt = $pdo->prepare($sql);
                                    $stmt->execute([$ordreplus, $id, $ssid]);
                                    $stmt = $pdo->prepare("UPDATE sous_categorie SET ordre_aff_sous_cat = ? WHERE id_sous_cat = ? AND id_cat = ?");
                                    $stmt->execute([$current_ordre, $previousId, $ssid]);
                                    $stmtordre->execute([$id, $ssid]);
                                    $currentValue = $stmtordre->fetch();
                                    $current_ordre = $currentValue['ordre_aff_sous_cat'];
                                }

                                //on supprime la ligne qui a la valeur max
                                $statmt = $pdo->prepare('DELETE FROM `sous_categorie` WHERE id_sous_cat = :id_sous_cat;');
                                $statmt->bindParam(':id_sous_cat', $id, PDO::PARAM_INT);
                                $statmt->execute();
                                header('location: /SGRC/index.php?page=sous_categorie');
                                break;
                            }

                        default: {
                                echo "erreur d'action: " . $_POST["action"];
                            }
                    }
                }
                //recup de la liste des tables
                $statmt = $pdo->prepare('SELECT * FROM sgr_table order by `numero_table`');
                $statmt->execute();
                $tables = $statmt->fetchAll(PDO::FETCH_ASSOC);

                //recup de la liste des boissons
                $statmt3 = $pdo->prepare('SELECT * FROM plat INNER JOIN sous_categorie ON plat.id_sous_cat = sous_categorie.id_sous_cat WHERE id_cat != 1 ORDER BY vu ASC');
                $statmt3->execute();
                $boissons = $statmt3->fetchAll(PDO::FETCH_ASSOC);

                //recup de la liste des plats
                $statmt5 = $pdo->prepare('SELECT * FROM plat INNER JOIN sous_categorie ON plat.id_sous_cat = sous_categorie.id_sous_cat WHERE id_cat = 1 ORDER BY vu ASC');
                $statmt5->execute();
                $plats = $statmt5->fetchAll(PDO::FETCH_ASSOC);

                //recup de la liste des menus
                $statmt7 = $pdo->prepare('SELECT * FROM menu');
                $statmt7->execute();
                $menus = $statmt7->fetchAll(PDO::FETCH_ASSOC);

                //recup de la liste des utilisateurs
                $statmt8 = $pdo->prepare('SELECT * FROM user');
                $statmt8->execute();
                $users = $statmt8->fetchAll(PDO::FETCH_ASSOC);

                //recup nombre d'utilisateurs
                $statmt9 = $pdo->prepare('SELECT COUNT(id_user) AS nb_users FROM user');
                $statmt9->execute();
                $nbusers = $statmt9->fetch(PDO::FETCH_ASSOC);

                //recup la liste des categories
                $statmt30 = $pdo->prepare('SELECT * FROM categorie_plat ORDER BY ordre_affichage_cat');
                $statmt30->execute();
                $categories = $statmt30->fetchAll(PDO::FETCH_ASSOC);

                //recup nombre de commandes
                $statmt13 = $pdo->prepare('SELECT COUNT(id_ticket) AS nb_tickets FROM ticket');
                $statmt13->execute();
                $nbtickets = $statmt13->fetch(PDO::FETCH_ASSOC);

                //recup prix
                $statmt17 = $pdo->prepare('SELECT DISTINCT ticket.id_ticket, plat.nom_plat,ligne_ticket.quantite, ligne_ticket.commentaire, plat.PU_carte * ligne_ticket.quantite as prix  FROM ligne_ticket, plat, ticket WHERE ticket.id_ticket = :id_ticket and ticket.id_ticket = ligne_ticket.id_ticket and plat.id_plat=ligne_ticket.id_plat');
                $statmt17->bindParam(':id_ticket', $idticket_caisse, PDO::PARAM_INT);

                //recup le prix total du ticket
                $statmt20 = $pdo->prepare('SELECT sum(plat.PU_carte * ligne_ticket.quantite) as TT FROM ligne_ticket, plat, ticket WHERE ligne_ticket.id_ticket = ticket.id_ticket AND ligne_ticket.id_plat = plat.id_plat AND ticket.id_ticket = :id_ticket ');
                $statmt20->bindParam(':id_ticket', $idticket_caisse, PDO::PARAM_INT);

                //recup résumé tickets
                $statmt22 = $pdo->prepare('SELECT * FROM ticket,sgr_table WHERE ticket.id_table = sgr_table.id_table ORDER BY ordre ASC, date_c DESC');
                $statmt22->execute();
                $sumtickets = $statmt22->fetchAll(PDO::FETCH_ASSOC);

                $statmt24 = $pdo->prepare("SELECT time(date_c) as H, date(date_c) as D FROM `ticket` WHERE id_ticket = :idTT");
                $statmt24->bindParam(':idTT', $idticket_caisse, PDO::PARAM_INT);
                $statmt24->execute();

                //variable "page" permet de gérer les include
                if (isset($_GET["page"])) {
                    switch ($_GET["page"]) {
                        case "tableau": {
                                include "view/admin/tableau_de_bord/tableau_de_bord.php";
                                break;
                            }

                        case "table": {
                                include "view/admin/produit/table.php";
                                break;
                            }

                        case "plat": {
                                include "view/admin/produit/plat.php";
                                break;
                            }

                        case "boisson": {
                                include "view/admin/produit/boisson.php";
                                break;
                            }

                        case "menu": {
                                unset($_SESSION['id_m']);
                                include "view/admin/produit/menu.php";
                                break;
                            }

                        case "users": {
                                include "view/admin/utilisateur/utilisateur.php";
                                break;
                            }

                        case "categorie": {
                                unset($_SESSION['id_t']);
                                include "view/admin/categorie/categorie.php";
                                break;
                            }

                        case "ajout_categorie": {
                                include "view/admin/categorie/ajout/categorie.php";
                                break;
                            }

                        case "ajout_sous_cat": {
                                include "view/admin/categorie/ajout/sous_categorie.php";
                                break;
                            }

                        case "suppr_sous_cat": {
                                include "view/admin/categorie/suppr/sous_categorie.php";
                                break;
                            }

                        case "modif_categorie": {
                                $id_t = $_POST['id_t'];
                                $Requete_edit_cat = $pdo->prepare("SELECT * FROM `categorie_plat` WHERE id_cat = " . $id_t . "");
                                $Requete_edit_cat->execute();
                                $cat = $Requete_edit_cat->fetchALL(PDO::FETCH_ASSOC);
                                include "view/admin/categorie/modifier/categorie.php";
                                break;
                            }

                        case "suppr_cat": {
                                include "view/admin/categorie/suppr/categorie.php";
                                break;
                            }

                        case "modif_sous_cat": {
                                $id_t = $_SESSION['id_t'];
                                $id_c = $_POST['id_c'];
                                $Requete_edit_cat = $pdo->prepare("SELECT * FROM `sous_categorie` WHERE id_sous_cat = " . $id_c . "");
                                $Requete_edit_cat->execute();
                                $cat = $Requete_edit_cat->fetchALL(PDO::FETCH_ASSOC);
                                include "view/admin/categorie/modifier/sous_categorie.php";
                                break;
                            }

                        case "sous_categorie": {

                                if (isset($_SESSION['id_t'])) {

                                    $idw = $_SESSION['id_t'];
                                    $statmt35 = $pdo->prepare("SELECT * FROM sous_categorie WHERE id_cat = '$idw'  ORDER BY ordre_aff_sous_cat");
                                    $statmt35->execute();
                                    $sous_cats = $statmt35->fetchAll(PDO::FETCH_ASSOC);
                                    include "view/admin/categorie/sous_categorie.php";
                                } else {
                                    $id_t = $_POST['id_t'];
                                    $_SESSION['id_t'] = $id_t;
                                    $statmt35 = $pdo->prepare("SELECT * FROM sous_categorie WHERE id_cat = '$id_t'  ORDER BY ordre_aff_sous_cat");
                                    $statmt35->execute();
                                    $sous_cats = $statmt35->fetchAll(PDO::FETCH_ASSOC);
                                    include "view/admin/categorie/sous_categorie.php";
                                }
                                break;
                            }

                        case "ajout_menu": {
                                include "view/admin/produit/ajouter/menu.php";
                                break;
                            }


                        case "modif_menu": {
                                $id_m = $_POST['id_m'];
                                $Requete_edit_menu = $pdo->prepare("SELECT * FROM `menu` WHERE id_menu = :id_m");
                                $Requete_edit_menu->bindParam(":id_m", $id_m, PDO::PARAM_INT);
                                $Requete_edit_menu->execute();
                                $menu = $Requete_edit_menu->fetchALL(PDO::FETCH_ASSOC);
                                include "view/admin/produit/modifier/menu.php";
                                break;
                            }

                        case "carte_menu": {
                                if (isset($_SESSION['id_m'])) {
                                    $id_m = $_SESSION['id_m'];
                                    $statmt28 = $pdo->prepare('SELECT * FROM categorie_plat ORDER BY ordre_affichage_cat asc');
                                    $requete_carte = $pdo->prepare("SELECT P.id_plat, P.nom_plat, P.description, P.type_plat, S.nom_sous_cat, P.PU_carte from menu_contient_plat MP, plat P, sous_categorie S, menu M WHERE MP.id_menu=M.id_menu AND MP.id_plat=P.id_plat AND P.id_sous_cat = S.id_sous_cat AND P.vu=0 and MP.id_menu = :id_m ORDER BY S.ordre_aff_sous_cat asc;");
                                    $requete_carte->bindParam(':id_m', $id_m, PDO::PARAM_INT);
                                    $requete_carte->execute();
                                    $cartes = $requete_carte->fetchAll();

                                    //$statmt29 = $pdo->prepare('SELECT * FROM sous_categorie inner join plat on plat.id_sous_cat = sous_categorie.id_sous_cat where id_cat = :idcat AND (SELECT COUNT(P.id_plat) FROM menu M, plat P, menu_contient_plat MP,sous_categorie SC WHERE P.id_plat=MP.id_plat AND M.id_menu=MP.id_menu and P.id_sous_cat=SC.id_sous_cat and P.id_sous_cat = :idsouscat and P.vu = 0 and M.date_menu = CURDATE()) is not null GROUP BY nom_sous_cat ORDER BY ordre_aff_sous_cat'); /*  */
                                    //$statmt29->bindParam(':idcat', $cat, PDO::PARAM_INT);
                                    //$statmt29->bindParam(':idsouscat', $sous_cat, PDO::PARAM_INT);
                                    //$statmt29->execute();
                                    //$cat_plats = $statmt29->fetchAll(PDO::FETCH_ASSOC);
                                    $statmt = $pdo->prepare('SELECT sc.nom_sous_cat, sc.id_sous_cat, sc.id_cat FROM sous_categorie sc INNER JOIN categorie_plat c ON sc.id_cat = c.id_cat ORDER BY ordre_aff_sous_cat ASC');
                                    include "view/admin/produit/modifier/carte_menu.php";
                                } else {
                                    include "view/admin/produit/menu.php";
                                }

                                break;
                            }

                        case "modif_utilisateur": {
                                include "view/admin/utilisateur/modifier.php";
                                break;
                            }


                        case "ajout_carte": {
                                $id_m = $_SESSION['id_m'];
                                $statmt = $pdo->prepare('SELECT * from plat WHERE plat.id_plat not in (SELECT id_plat FROM menu_contient_plat where menu_contient_plat.id_menu = :id_m) and plat.vu =0');
                                $statmt->bindParam(':id_m', $id_m, PDO::PARAM_INT);
                                $statmt->execute();
                                $cartes = $statmt->fetchAll();
                                $statmt = $pdo->prepare('SELECT * FROM sous_categorie');
                                $statmt->execute();
                                $cat_plat = $statmt->fetchAll();
                                include "view/admin/produit/ajouter/carte_menu.php";
                                break;
                            }

                        case "suppr_menu": {
                                include "view/admin/produit/supprimer/menu.php";
                                break;
                            }

                        case "suppr_boisson": {
                                include "view/admin/produit/supprimer/boisson.php";
                                break;
                            }

                        case "modif_boisson": {
                                $statmt32 = $pdo->prepare('SELECT * FROM sous_categorie WHERE id_cat != 1');
                                $statmt32->execute();
                                $souscats = $statmt32->fetchAll(PDO::FETCH_ASSOC);
                                include "view/admin/produit/modifier/boisson.php";
                                break;
                            }

                        case "ajout_boisson": {
                                $statmt32 = $pdo->prepare('SELECT * FROM sous_categorie WHERE id_cat != 1');
                                $statmt32->execute();
                                $souscats = $statmt32->fetchAll(PDO::FETCH_ASSOC);
                                include "view/admin/produit/ajouter/boisson.php";
                                break;
                            }

                        case "suppr_plat": {
                                include "view/admin/produit/supprimer/plat.php";
                                break;
                            }

                        case "modif_plat": {
                                $statmt32 = $pdo->prepare('SELECT * FROM sous_categorie WHERE id_cat = 1');
                                $statmt32->execute();
                                $souscats = $statmt32->fetchAll(PDO::FETCH_ASSOC);
                                include "view/admin/produit/modifier/plat.php";
                                break;
                            }

                        case "ajout_plat": {
                                $statmt32 = $pdo->prepare('SELECT * FROM sous_categorie WHERE id_cat = 1');
                                $statmt32->execute();
                                $souscats = $statmt32->fetchAll(PDO::FETCH_ASSOC);
                                include "view/admin/produit/ajouter/plat.php";
                                break;
                            }

                        case "suppr_table": {
                                include "view/admin/produit/supprimer/table.php";
                                break;
                            }

                        case "modif_table": {
                                include "view/admin/produit/modifier/table.php";
                                break;
                            }

                        case "ajout_table": {
                                include "view/admin/produit/ajouter/table.php";
                                break;
                            }

                        default: {
                                include "view/admin/tableau_de_bord/tableau_de_bord.php";
                                break;
                            }
                    }
                } else {
                    include "view/admin/tableau_de_bord/tableau_de_bord.php";
                }
                break;
            }


        case "cuisine": {

                if (isset($_POST) && isset($_POST['action'])) {
                    switch ($_POST['action']) {
                        case "etatEnCours": {
                                try {
                                    $id_ticket = $_POST['id_ticket'];
                                    $id_plat = $_POST['id_plat'];
                                    $commentaire = $_POST['commentaire'];
                                    $etat = "Demandé";

                                    // Vérifier si le commentaire est vide
                                    if (empty($commentaire)) {
                                        $commentaireCondition = 'commentaire IS NULL';
                                    } else {
                                        $commentaireCondition = 'commentaire = :commentaire';
                                    }

                                    // Utilisation de l'opérateur ternaire pour définir la condition Etat
                                    $etatCondition = (empty($commentaire)) ? 'Etat = :etat' : 'Etat = :etat AND ' . $commentaireCondition;

                                    $sql = 'UPDATE ligne_ticket SET Etat = "En cours" WHERE id_ticket = :id_ticket AND id_plat = :id_plat AND ' . $etatCondition;

                                    $statmt = $pdo->prepare($sql);
                                    $statmt->bindParam(':id_ticket', $id_ticket, PDO::PARAM_INT);
                                    $statmt->bindParam(':id_plat', $id_plat, PDO::PARAM_INT);
                                    $statmt->bindParam(':etat', $etat, PDO::PARAM_STR);

                                    // Ne lier le paramètre que si le commentaire n'est pas vide
                                    if (!empty($commentaire)) {
                                        $statmt->bindParam(':commentaire', $commentaire, PDO::PARAM_STR);
                                    }

                                    $statmt->execute();
                                } catch (PDOException $e) {
                                    echo $e->getMessage();
                                }

                                break;
                            }
                        case 'etatPret': {
                                try {
                                    $id_ticket = $_POST['id_ticket'];
                                    $id_plat = $_POST['id_plat'];
                                    $commentaire = $_POST['commentaire'];
                                    $etat = "En cours";

                                    // Vérifier si le commentaire est vide
                                    if ($commentaire == "") {
                                        $commentaireCondition = 'commentaire IS NULL';
                                    } else {
                                        $commentaireCondition = 'commentaire = :commentaire';
                                    }

                                    $sql = 'UPDATE ligne_ticket SET Etat = "Prêt" WHERE id_ticket = :id_ticket AND id_plat = :id_plat AND Etat = :etat AND ' . $commentaireCondition;
                                    $stmt = $pdo->prepare($sql);

                                    $stmt->bindParam(':id_ticket', $id_ticket, PDO::PARAM_INT);
                                    $stmt->bindParam(':id_plat', $id_plat, PDO::PARAM_INT);
                                    $stmt->bindParam(':etat', $etat, PDO::PARAM_STR);

                                    // Ne lier le paramètre que si le commentaire n'est pas vide
                                    if ($commentaire != "") {
                                        $stmt->bindParam(':commentaire', $commentaire, PDO::PARAM_STR);
                                    }

                                    $stmt->execute();
                                } catch (PDOException $e) {
                                    echo $e->getMessage();
                                }

                                break;
                            }
                        default: {
                                echo "erreur d'action: " . $_POST["action"];
                                break;
                            }
                    }
                }

                //liste des requêtes de l'interface cuisine

                // recup de la liste des tickets
                $statmt16 = $pdo->prepare('SELECT ticket.*,sgr_table.* FROM ticket,sgr_table,ligne_ticket,plat WHERE statut != "PAY" AND plat.type_plat != "boisson" AND ticket.id_table = sgr_table.id_table AND ticket.id_ticket = ligne_ticket.id_ticket AND ligne_ticket.id_plat = plat.id_plat GROUP BY ticket.id_ticket');
                $statmt16->execute();
                $tickets = $statmt16->fetchAll(PDO::FETCH_ASSOC);

                $statmt17 = $pdo->prepare('SELECT ticket.id_ticket, plat.id_plat, plat.nom_plat, COUNT(nom_plat) AS quantite, ligne_ticket.commentaire ,ligne_ticket.Etat AS Etat, categorie_plat.ordre_affichage_cat, sous_categorie.ordre_aff_sous_cat FROM ligne_ticket, plat, ticket,categorie_plat, sous_categorie  WHERE ticket.id_ticket = :id_ticket AND ticket.id_ticket = ligne_ticket.id_ticket AND plat.id_plat=ligne_ticket.id_plat AND type_plat != "boisson" AND categorie_plat.id_cat = sous_categorie.id_cat AND plat.id_sous_cat = sous_categorie.id_sous_cat GROUP BY nom_plat, ligne_ticket.commentaire,Etat ORDER BY categorie_plat.ordre_affichage_cat, sous_categorie.ordre_aff_sous_cat;');
                $statmt17->bindParam(':id_ticket', $u, PDO::PARAM_INT);



                include "view/cuisine/cuisine.php";
                break;
            }


        case "bar": {
                //liste des requêtes de l'interface bar
                if (isset($_POST) && isset($_POST['action'])) {
                    switch ($_POST['action']) {
                        case "etatEnCours": {
                                try {
                                    $id_ticket = $_POST['id_ticket'];
                                    $id_plat = $_POST['id_plat'];
                                    $commentaire = $_POST['commentaire'];
                                    $etat = "Demandé";

                                    // Vérifier si le commentaire est vide
                                    if ($commentaire == "") {
                                        $commentaireCondition = 'commentaire IS NULL';
                                    } else {
                                        $commentaireCondition = 'commentaire = :commentaire';
                                    }

                                    $sql = 'UPDATE ligne_ticket SET Etat = "En cours" WHERE id_ticket = :id_ticket AND id_plat = :id_plat AND Etat = :etat AND ' . $commentaireCondition;
                                    $statmt = $pdo->prepare($sql);

                                    $statmt->bindParam(':id_ticket', $id_ticket, PDO::PARAM_INT);
                                    $statmt->bindParam(':id_plat', $id_plat, PDO::PARAM_INT);
                                    $statmt->bindParam(':etat', $etat, PDO::PARAM_STR);

                                    // Ne lier le paramètre que si le commentaire n'est pas vide
                                    if ($commentaire != "") {
                                        $statmt->bindParam(':commentaire', $commentaire, PDO::PARAM_STR);
                                    }

                                    $statmt->execute();
                                } catch (PDOException $e) {
                                    echo $e->getMessage();
                                }

                                break;
                            }
                        case 'etatPret': {
                                try {
                                    $id_ticket = $_POST['id_ticket'];
                                    $id_plat = $_POST['id_plat'];
                                    $commentaire = $_POST['commentaire'];
                                    $etat = "En cours";

                                    // Vérifier si le commentaire est vide
                                    if ($commentaire == "") {
                                        $commentaireCondition = 'commentaire IS NULL';
                                    } else {
                                        $commentaireCondition = 'commentaire = :commentaire';
                                    }

                                    $sql = 'UPDATE ligne_ticket SET Etat = "Prêt" WHERE id_ticket = :id_ticket AND id_plat = :id_plat AND Etat = :etat AND ' . $commentaireCondition;
                                    $stmt = $pdo->prepare($sql);

                                    $stmt->bindParam(':id_ticket', $id_ticket, PDO::PARAM_INT);
                                    $stmt->bindParam(':id_plat', $id_plat, PDO::PARAM_INT);
                                    $stmt->bindParam(':etat', $etat, PDO::PARAM_STR);

                                    // Ne lier le paramètre que si le commentaire n'est pas vide
                                    if ($commentaire != "") {
                                        $stmt->bindParam(':commentaire', $commentaire, PDO::PARAM_STR);
                                    }

                                    $stmt->execute();
                                } catch (PDOException $e) {
                                    echo $e->getMessage();
                                }

                                break;
                            }
                        default: {
                                echo "erreur d'action: " . $_POST["action"];
                                break;
                            }
                    }
                }
                // recup de la liste des tickets
                $statmt16 = $pdo->prepare('SELECT ticket.*,sgr_table.* FROM ticket,sgr_table,ligne_ticket,plat WHERE statut != "PAY" AND plat.type_plat = "boisson" AND ticket.id_table = sgr_table.id_table AND ticket.id_ticket = ligne_ticket.id_ticket AND ligne_ticket.id_plat = plat.id_plat GROUP BY ticket.id_ticket');
                $statmt16->execute();
                $ticketsBar = $statmt16->fetchAll(PDO::FETCH_ASSOC);

                $statmt17 = $pdo->prepare('SELECT ticket.id_ticket, plat.id_plat, plat.nom_plat, COUNT(nom_plat) AS quantite, ligne_ticket.commentaire,ligne_ticket.Etat AS Etat, categorie_plat.ordre_affichage_cat, sous_categorie.ordre_aff_sous_cat FROM ligne_ticket, plat, ticket,categorie_plat, sous_categorie  WHERE ticket.id_ticket = :id_ticket AND ticket.id_ticket = ligne_ticket.id_ticket AND plat.id_plat=ligne_ticket.id_plat AND type_plat = "boisson" AND categorie_plat.id_cat = sous_categorie.id_cat AND plat.id_sous_cat = sous_categorie.id_sous_cat GROUP BY nom_plat, ligne_ticket.commentaire,Etat ORDER BY categorie_plat.ordre_affichage_cat, sous_categorie.ordre_aff_sous_cat;');
                $statmt17->bindParam(':id_ticket', $u, PDO::PARAM_INT);
                include "view/bar/bar.php";
                break;
            }


        case "service": {
                //liste des requêtes de l'interface service

                //login ok et sevice
                if (isset($_POST) && isset($_POST['action'])) {
                    switch ($_POST['action']) {

                        case "choix_table": {
                                $num_table = $_POST['numero_table_voulue'];
                                $statmt = $pdo->prepare('SELECT id_table FROM sgr_table WHERE numero_table = ?');
                                $statmt->bindParam(1, $num_table, PDO::PARAM_STR);
                                $statmt->execute();
                                $reponse = $statmt->fetch();
                                $id = $reponse["id_table"];

                                //Variables de session qui seront récup dans la page suivante
                                $_SESSION['idT'] = $id;
                                $_SESSION['tables'] = $_POST['numero_table_voulue'];
                                $_SESSION['numero_table'] = $_POST['numero_table_voulue'];
                                $_SESSION['vu'] = $_POST['numero_table_voulue'];

                                // ******************CREATION D'UN TICKET**********************//

                                // Requette de récuperation du status "CRE"  et l'exécute.
                                $check_CRE = $pdo->prepare('SELECT count(*) as nbt FROM ticket WHERE statut IN ("CRE","SAI","VAL","VU") AND id_table = :id_table');
                                $check_CRE->bindParam(":id_table", $id, PDO::PARAM_INT);
                                $check_CRE->execute();
                                $check_CRE_rep = $check_CRE->fetch();
                                $nb_ticket_ouvert = $check_CRE_rep['nbt'];

                                // Vérifie si une table possède un ticket avec le statut CRE ou SAI ou VAL ou VU.
                                if ($nb_ticket_ouvert == 0) {

                                    // Si n'existe pas cree un ticket.
                                    $reqete = "INSERT INTO `ticket` (`id_ticket`, `id_table`,statut) VALUES (NULL,$id,'CRE');";
                                    $pdo->exec($reqete);
                                }

                                // ******************NOMBRE DE COUVERT SUR LA TABLE DU RESTAU******************\\

                                // Verification du nombre de couvert dans la base de données ce qui permette selon celui ci
                                //de passe a la page suivant qui est la saisi des plat dans le cas contraire il fait son
                                //comportement habituelle.

                                //Action si le nbCouvert > 0 dans BDD

                                // Requette de récuperation du nb_couvert  et l'exécute.
                                $check_nbC = $pdo->prepare('SELECT id_ticket,nb_couvert FROM ticket WHERE id_table = :id_table AND statut != "PAY" ');
                                $check_nbC->bindParam(":id_table", $id, PDO::PARAM_INT);
                                $check_nbC->execute();
                                $check_nbC_rec = $check_nbC->fetch();
                                $nb_couvert_ticket = $check_nbC_rec["nb_couvert"];
                                $_SESSION['id_ticket'] = $check_nbC_rec["id_ticket"];
                                if ($nb_couvert_ticket > 0) {

                                    header("Location:index.php?page=plat");
                                    // si c'est supérieur a 0 rediretion sur la page plat pour saisir les plat pour le client.
                                } else { //si c'est 0 on dirige sur le formulaire de nb de couvert
                                    header("Location:index.php?page=nbcouv_modif");
                                    //include "view/service/prise_de_commande/nbCouvert.php";
                                }
                                break;
                            }

                        case "oublier_table": {
                                $id_t = $_POST['id_ticket'];
                                $stmt = $pdo->prepare("UPDATE`ticket` set `statut`= 'VAL' WHERE `id_ticket` = :id_ticket");
                                $stmt->bindParam(':idTicket', $id_t, PDO::PARAM_INT);
                                $stmt->execute();
                                unset($_SESSION["tables"]);
                                unset($_SESSION["numero_table"]);
                                header("Location:index.php");
                                break;
                            }

                        case "affecter_nb_couvert"; {
                                $nb_couverts = $_POST['nb_couvert'];
                                $nb_couverts = intval($nb_couverts);

                                $idTable = $_SESSION['idT'];
                                $Table_selec = $_SESSION['tables'];
                                $_SESSION['nb_couvert'] = $_POST['nb_couvert'];

                                //Requête pour récup l'id du ticket
                                $recupTicket = $pdo->prepare('SELECT id_ticket FROM ticket where id_table = :ccc AND statut != "PAY"');
                                $recupTicket->bindParam(":ccc", $idTable, PDO::PARAM_INT);
                                $recupTicket->execute();
                                $idTicket = $recupTicket->fetch();
                                $idTicket1 = isset($idTicket['id_ticket']) ? $idTicket["id_ticket"] : '';

                                //Requete pour inserer dans la bdd les valeurs dans les bon champs
                                $reqete = $pdo->prepare("UPDATE `ticket` SET `nb_couvert` = :nbC, statut = 'SAI' where id_ticket  = :idT ");
                                $reqete->bindParam(":nbC", $nb_couverts, PDO::PARAM_INT);
                                $reqete->bindParam(":idT", $idTicket1, PDO::PARAM_INT);

                                //Exécute une requête sur la base de données
                                $reqete->execute();

                                header("Location:index.php?page=plat");
                                break;
                            }


                        case "cree_ligne_ticket": {

                                $idTicket = $_POST['id_ticket'];
                                $_SESSION['id_ticket'] = $idTicket;
                                $id_p = $_POST['id_plat'];
                                $n = $_POST['nb_plat'];


                                for ($i = 1; $i <= $n; ++$i) {
                                    $stmt = $pdo->prepare("INSERT INTO ligne_ticket (id_ticket, id_plat) VALUES (:idticket, :idplat)");
                                    $stmt->bindValue(':idticket', $idTicket, PDO::PARAM_STR);
                                    $stmt->bindValue(':idplat', $id_p, PDO::PARAM_STR);
                                    $stmt->execute();
                                }

                                header("Location:index.php?page=plat");
                                break;
                            }

                        case "diminue_ligne_ticket": {
                                try {
                                    $id_plat = $_POST['id_plat'];
                                    $id_ticket = $_POST['id_ticket'];
                                    $commentaire = $_POST['commentaire'];
                                    $Etat = $_POST['Etat'];

                                    // Vérifier si le commentaire est vide
                                    if ($commentaire == "") {
                                        $commentaireCondition = 'commentaire IS NULL';
                                    } else {
                                        $commentaireCondition = 'commentaire = :commentaire';
                                    }

                                    //Supprime un ligne du ticket
                                    $sql = 'DELETE FROM ligne_ticket WHERE id_ticket = :id_ticket AND id_plat = :id_plat AND Etat = :Etat AND ' . $commentaireCondition . ' LIMIT 1';
                                    $requeteDiminueLigneTicket = $pdo->prepare($sql);
                                    $requeteDiminueLigneTicket->bindParam(':id_ticket', $id_ticket, PDO::PARAM_INT);
                                    $requeteDiminueLigneTicket->bindParam(':id_plat', $id_plat, PDO::PARAM_INT);
                                    $requeteDiminueLigneTicket->bindParam(':Etat', $Etat, PDO::PARAM_STR);

                                    // Ne lier le paramètre que si le commentaire n'est pas vide
                                    if ($commentaire != "") {
                                        $requeteDiminueLigneTicket->bindParam(':commentaire', $commentaire, PDO::PARAM_STR);
                                    }

                                    $requeteDiminueLigneTicket->execute();
                                } catch (PDOException $e) {
                                    echo $e->getMessage();
                                }

                                /*Refresh Ticket*/
                                header("Location:index.php?page=plat");
                                break;
                            }

                        case "modifier_commentaire": {
                                try {
                                    $id_plat = $_POST['id_plat'];
                                    $id_ticket = $_POST['id_ticket'];
                                    $commentaire = $_POST['commentaire'];
                                    $oldCommentaire = $_POST['old_commentaire'];
                                    $Etat = $_POST['Etat'];

                                    // Vérifier si le commentaire est vide
                                    if ($commentaire == "") {
                                        $commentaireCondition = 'commentaire = NULL';
                                    } else {
                                        $commentaireCondition = 'commentaire = :newCommentaire';
                                    }

                                    // Vérifier si l'ancien commentaire est vide
                                    if ($oldCommentaire == "") {
                                        $oldCommentaireCondition = 'commentaire IS NULL';
                                    } else {
                                        $oldCommentaireCondition = 'commentaire = :oldCommentaire';
                                    }

                                    // Modifie le commentaire d'une ligne du ticket
                                    $sql = 'UPDATE `ligne_ticket` SET ' . $commentaireCondition . ' WHERE id_ticket = :id_ticket AND id_plat = :id_plat AND Etat = :Etat AND ' . $oldCommentaireCondition . ' LIMIT 1;';
                                    $requeteUpdateCommentaire = $pdo->prepare($sql);

                                    $requeteUpdateCommentaire->bindParam(':id_ticket', $id_ticket, PDO::PARAM_INT);
                                    $requeteUpdateCommentaire->bindParam(':id_plat', $id_plat, PDO::PARAM_INT);
                                    $requeteUpdateCommentaire->bindParam(':Etat', $Etat, PDO::PARAM_STR);

                                    // Ne lier le paramètre que si le commentaire n'est pas vide
                                    if ($commentaire != "") {
                                        $requeteUpdateCommentaire->bindParam(':newCommentaire', $commentaire, PDO::PARAM_STR);
                                    }

                                    // Ne lier le paramètre que si l'ancien commentaire n'est pas vide
                                    if ($oldCommentaire != "") {
                                        $requeteUpdateCommentaire->bindParam(':oldCommentaire', $oldCommentaire, PDO::PARAM_STR);
                                    }

                                    $requeteUpdateCommentaire->execute();
                                } catch (PDOException $e) {
                                    echo $e->getMessage();
                                }

                                /*Refresh Ticket*/
                                header("Location:index.php?page=plat");
                                break;
                            }


                        case "supprimer_ligne_ticket": {

                                try {
                                    $id_plat = $_POST['id_plat'];
                                    $id_ticket = $_POST['id_ticket'];
                                    $Etat = $_POST['Etat'];
                                    $commentaire = $_POST['commentaire'];

                                    // Vérifier si le commentaire est vide
                                    if ($commentaire == "") {
                                        $commentaireCondition = 'commentaire IS NULL';
                                    } else {
                                        $commentaireCondition = 'commentaire = :commentaire';
                                    }

                                    //Supprime toutes les lignes du plat
                                    $sql = 'DELETE FROM ligne_ticket WHERE id_ticket = :id_ticket AND id_plat = :id_plat AND Etat = :Etat AND ' . $commentaireCondition;
                                    $requeteSupprimerLigneTicket = $pdo->prepare($sql);
                                    $requeteSupprimerLigneTicket->bindParam(':id_ticket', $id_ticket, PDO::PARAM_INT);
                                    $requeteSupprimerLigneTicket->bindParam(':id_plat', $id_plat, PDO::PARAM_INT);
                                    $requeteSupprimerLigneTicket->bindParam(':Etat', $Etat, PDO::PARAM_STR);

                                    if ($commentaire != "") {
                                        $requeteSupprimerLigneTicket->bindParam(':commentaire', $commentaire, PDO::PARAM_STR);
                                    }

                                    $requeteSupprimerLigneTicket->execute();
                                } catch (PDOException $e) {
                                    echo $e->getMessage();
                                }

                                /*Refresh Ticket*/
                                header("Location:index.php?page=plat");
                                break;
                            }

                        case "etatDemande": {
                                try {
                                    $id_ticket = $_POST['id_ticket'];
                                    $id_plat = $_POST['id_plat'];
                                    $commentaire = $_POST['commentaire'];
                                    $etat = "En saisie";

                                    // Vérifier si le commentaire est vide
                                    if ($commentaire == "") {
                                        $commentaireCondition = 'commentaire IS NULL';
                                    } else {
                                        $commentaireCondition = 'commentaire = :commentaire';
                                    }

                                    $sql = 'UPDATE ligne_ticket SET Etat = "Demandé" WHERE id_ticket = :id_ticket AND id_plat = :id_plat AND Etat = :etat AND ' . $commentaireCondition;
                                    $statmt = $pdo->prepare($sql);

                                    $statmt->bindParam(':id_ticket', $id_ticket, PDO::PARAM_INT);
                                    $statmt->bindParam(':id_plat', $id_plat, PDO::PARAM_INT);
                                    $statmt->bindParam(':etat', $etat, PDO::PARAM_STR);

                                    // Ne lier le paramètre que si le commentaire n'est pas vide
                                    if ($commentaire != "") {
                                        $statmt->bindParam(':commentaire', $commentaire, PDO::PARAM_STR);
                                    }

                                    $statmt->execute();
                                } catch (PDOException $e) {
                                    echo $e->getMessage();
                                }

                                break;
                            }

                        case "etatServi": {
                                try {
                                    $id_ticket = $_POST['id_ticket'];
                                    $id_plat = $_POST['id_plat'];
                                    $commentaire = $_POST['commentaire'];
                                    $etat = "Prêt";

                                    // Vérifier si le commentaire est vide
                                    if ($commentaire == "") {
                                        $commentaireCondition = 'commentaire IS NULL';
                                    } else {
                                        $commentaireCondition = 'commentaire = :commentaire';
                                    }

                                    $sql = 'UPDATE ligne_ticket SET Etat = "Servi" WHERE id_ticket = :id_ticket AND id_plat = :id_plat AND Etat = :etat AND ' . $commentaireCondition;
                                    $statmt = $pdo->prepare($sql);

                                    $statmt->bindParam(':id_ticket', $id_ticket, PDO::PARAM_INT);
                                    $statmt->bindParam(':id_plat', $id_plat, PDO::PARAM_INT);
                                    $statmt->bindParam(':etat', $etat, PDO::PARAM_STR);

                                    // Ne lier le paramètre que si le commentaire n'est pas vide
                                    if ($commentaire != "") {
                                        $statmt->bindParam(':commentaire', $commentaire, PDO::PARAM_STR);
                                    }

                                    $statmt->execute();
                                } catch (PDOException $e) {
                                    echo $e->getMessage();
                                }

                                break;
                            }

                        default: {
                            }
                    }
                }

                if (isset($_GET["page"])) {
                    switch ($_GET["page"]) {
                        case "plat": {
                                include "view/service/prise_de_commande/plat.php";
                                break;
                            }
                        case "nbcouv": {
                                include "view/service/prise_de_commande/nbCouvert.php";
                                break;
                            }
                        case "nbcouv_modif": {
                                include "view/service/prise_de_commande/nbCouvert_Modif.php";
                                break;
                            }

                        default: {
                                //par defaut on fait choisir la table
                                //recup de la liste des tables
                                $statmt = $pdo->prepare('SELECT * FROM sgr_table where vu=0 order by `numero_table`');
                                $statmt->execute();
                                $tables = $statmt->fetchAll(PDO::FETCH_ASSOC);
                                include "view/service/prise_de_commande/prise_de_commande.php";
                                break;
                            }
                    }
                } else {
                    //par defaut on fait choisir la table
                    //recup de la liste des tables
                    $statmt = $pdo->prepare('SELECT * FROM sgr_table where vu=0 order by `numero_table`');
                    $statmt->execute();
                    $tables = $statmt->fetchAll(PDO::FETCH_ASSOC);
                    include "view/service/prise_de_commande/prise_de_commande.php";
                }
                break;
            }


        case "caisse": {
                //liste des requêtes de l'interface caisse

                if (isset($_POST) && isset($_POST['action'])) {
                    switch ($_POST['action']) {

                        case "Payer": {
                                if (isset($_POST["id_ticket"])) {
                                    $statmt12 = $pdo->prepare("UPDATE ticket SET statut = 'PAY' where id_ticket  = :idTT");
                                    $statmt12->bindParam(':idTT', $id_ticket, PDO::PARAM_INT);
                                    $id_ticket = $_POST["id_ticket"];
                                    $statmt12->execute();
                                }
                                break;
                            }
                        default: {
                                echo "erreur d'action";
                            }
                    }
                }
                // recup de la liste des tickets non payé
                $statmt16 = $pdo->prepare('SELECT * FROM ticket,sgr_table WHERE statut != "PAY" AND ticket.id_table = sgr_table.id_table');
                $statmt16->execute();
                $ticketsBar = $statmt16->fetchAll(PDO::FETCH_ASSOC);

                // recup de la liste des tickets payé (pour l'historique)
                $statmt185 = $pdo->prepare('SELECT * FROM ticket,sgr_table WHERE statut = "PAY" AND ticket.id_table = sgr_table.id_table ORDER BY ticket.id_ticket DESC');
                $statmt185->execute();
                $ticketsBar2 = $statmt185->fetchAll(PDO::FETCH_ASSOC);

                // recup nom plat / quantite / prix unitaire
                $statmt17 = $pdo->prepare('SELECT DISTINCT ticket.id_ticket, plat.nom_plat, COUNT(nom_plat) AS quantite ,ligne_ticket.commentaire, (plat.PU_carte * COUNT(nom_plat)) as prix FROM ligne_ticket, plat, ticket, sous_categorie, categorie_plat WHERE ticket.id_ticket = :id_ticket and ticket.id_ticket = ligne_ticket.id_ticket and plat.id_plat=ligne_ticket.id_plat and plat.id_sous_cat = sous_categorie.id_sous_cat and sous_categorie.id_cat = categorie_plat.id_cat GROUP BY nom_plat ORDER BY ordre_affichage_cat, ordre_aff_sous_cat;');
                $statmt17->bindParam(':id_ticket', $idticket_caisse, PDO::PARAM_INT);




                //recup le prix total du ticket
                $statmt20 = $pdo->prepare('SELECT sum(plat.PU_carte) as TT FROM ligne_ticket, plat, ticket WHERE ligne_ticket.id_ticket = ticket.id_ticket AND ligne_ticket.id_plat = plat.id_plat AND ticket.id_ticket = :id_ticket ');
                $statmt20->bindParam(':id_ticket', $idticket_caisse, PDO::PARAM_INT);

                if (isset($_GET['page'])) {
                    switch ($_GET['page']) {

                        case "encours": {
                                include "view/caisse/caisse.php";
                                break;
                            }

                        case "historique": {
                                include "view/caisse/historique.php";
                                break;
                            }

                        default: {
                                include "view/caisse/caisse.php";
                                break;
                            }
                    }
                } else {
                    include "view/caisse/caisse.php";
                }
                break;
            }

        default: {
                //login ok mais pas de role défini
                //on kick
                session_destroy();
                header("Location:index.php");
            }
    }
} else {

    if (isset($_POST["login"]) && isset($_POST["mdp"])) {
        //on viens de la page de login
        //on interroge la base et on renseigne les infos utiles au profile

        $statmt = $pdo->prepare("SELECT * FROM user where login=:log");
        $statmt->bindParam(":log", $_POST["login"], PDO::PARAM_STR);
        $statmt->execute();

        if ($statmt->rowCount() != 0) {
            $rep = $statmt->fetchAll(PDO::FETCH_ASSOC);
            $passwordHash = $rep[0]['mdp'];
            if (password_verify($_POST["mdp"], $passwordHash)) {
                $_SESSION["role"] = $rep[0]["role"];
                $_SESSION["id_user"] = $rep[0]["id_user"];
                $_SESSION["login"] = $rep[0]["login"];
                header("Location: index.php");
            } else {
                header("Location: index.php?error=Erreur de connexion, vérifier vos identifiants");
            }
        } else {
            header("Location: index.php?error=Erreur de connexion, vérifier vos identifiants");
        }
    } else {
        // login fail et on ne viens pas de la page de login
        //on kick
        include("view/admin/tableau_de_bord/login.php");
    }
}
