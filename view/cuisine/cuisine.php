<?php
if ($_SESSION["role"] == "cuisine") {

?>
	<!DOCTYPE html>
	<html lang="fr">

	<head>
		<meta charset="UTF-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<title>Commande</title>
		<!-- Lien CSS -->
		<link rel="stylesheet" href="/SGRC/css/style_admin/tableau_de_bord/tableau_de_bord.css" />
		<link rel="stylesheet" href="/SGRC/css/style_bar/bar.css">
		<link rel="stylesheet" href="/SGRC/css/common.css" />
	</head>

	<body>
		<!-- Conteneur -->
		<div class="container">
			<aside>
				<!-- MENU (logo & titre & bouton fermer) -->
				<div class="top">
					<div class="logo">
						<img src="/SGRC/image/img/source/logo.png" alt="logo du site" />
						<h2>La table <span class="primary">d'Hélène</span></h2>
					</div>
					<div class="close" id="close-btn">
						<img class="icon_size_sidebar" src="image\icone\xmark.svg" alt="Icone X Mark">
					</div>
				</div>
				<div class="sidebar">
					<!-- Ticket -->
					<a href="#" class="active">
					<img class="icon_size_sidebar" src="image\icone\ticket.svg" alt="Icone Ticket">
						<h3>Ticket</h3>
					</a>
					<!-- Deconnexion-->
					<a href="/SGRC/php/deconnexion.php">
						<img class="icon_size_sidebar" src="image\icone\logout.svg" alt="Icone Logout">
						<h3>Déconnexion</h3>
					</a>
				</div>
			</aside>
			<!-------------Fin ASIDE  ----------------->
		<main>
			<h1>Voir les commande passées</h1>
			<div class="commander">
				<div class="card_ticket">
						<?php
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
												</tr>
											</thead>

											<?php
											$u = $ticket['id_ticket'];
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
															<?php echo $commande['commentaires']; ?>
														</td>
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

				</div>

	</body>
	</div>
	</div>
	</main>
	<!-- Fin du  main -->
	<div class="right">
		<div class="top">
			<button id="menu-btn">
				<i><img class="icon_size" src="image\icone\bar.svg" alt="Icone Bars"></i>
			</button>
			<div class="theme-toggler" id="theme-toggler">
				<!-- Dark and Light -->
				<i><img class="icon_darkmode" src="image\icone\darkmode.svg" alt="Icone Dark Mode"></i>
			</div>
			<div class="profil">
				<div class="info">
					<p style="text-transform: uppercase;"><b>
							<?php echo $_SESSION['role'] ?>
						</b></p>
				</div>
				<?php
				$sql = mysqli_query($link, "SELECT * FROM user WHERE id_user = {$_SESSION['id_user']}");
				if (mysqli_num_rows($sql) > 0) {
					$row = mysqli_fetch_assoc($sql);
				}
				?>
				<div class="profil-photot">
					<img src="/SGRC/php/images/<?php echo $row['image']; ?>" alt="">
				</div>
			</div>
		</div>
	</div>
	</div>
	<!-- Script Dark Mode -->
	<script src="/SGRC/js/source/dark_mode.js"></script>
	<!-- Script Menu -->
	<script src="/SGRC/js/source/menu.js"></script>
	<!-- SCRIPT FONT AWESOME -->
	<script src="https://kit.fontawesome.com/438cd94e6c.js" crossorigin="anonymous"></script>
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	<script>
		// Recharger la page avce intervale
		setInterval('load_ticket()', 2000);

		function load_ticket() {
			location.reload();
		};
	</script>
	</body>

	</html>


<?php

} else {
	echo ("vous n'avez pas le droit d'être là");
	header("Location:../../../index.php");
	exit();
}
?>