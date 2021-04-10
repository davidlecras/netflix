<?php
session_start();

if (!empty($_POST['email']) && !empty($_POST['password'])) {
	require('src/connect.php');

	//VARIABLES:
	$email = htmlspecialchars($_POST['email']);
	$password = htmlspecialchars($_POST['password']);

	// CHIFFRAGE DU MOT DE PASSE
	$password = "Zt3iS" . sha1($password . "782") . "26";

	// VERIFICATION DU CHAMP E-MAIL (s'il n'est pas valide):
	if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		header('location: index.php?error=1&message=Votre adresse e-mail est invalide.');
		exit;
	}

	// VERIFIER SI L'EMAIL EST DÉJÀ UTILISÉ:
	$req = $db->prepare(" SELECT count(*) as numberEmail FROM user WHERE email= ? ");
	$req->execute(array($email));
	while ($emailVerif = $req->fetch()) {
		if ($emailVerif['numberEmail'] != 1) {
			header('location: index.php?error=1&message=Impossible de vous identifier correctement.');
			exit;
		}
	}

	// CONNEXION:
	$req = $db->prepare("SELECT * FROM user where email = ?");
	$req->execute(array($email));

	while ($user = $req->fetch()) {
		if ($password == $user['password']) {
			$_SESSION['connect'] = 1;
			$_SESSION['email'] = $user['email'];
			header('location: index.php?success=1');
			exit;
		} else {
			header('location: index.php?error=1&message=Impossible de vous identifier correctement.');
		}
	}
}
?>

<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<title>Netflix</title>
	<link rel="stylesheet" type="text/css" href="design/default.css">
	<link rel="icon" type="image/pngn" href="img/favicon.png">
</head>

<body>

	<?php include('src/header.php'); ?>

	<section>
		<div id="login-body">
			<?php if (isset($_SESSION['connect'])) { ?>
				<h1>Bonjour</h1>
				<p>Qu'allez-vous regarder aujourd'hui?</p>
			<?php } else { ?>
				<h1>S'identifier</h1>
				<?php
				if (isset($_GET['error'])) {
					if (isset($_GET['message'])) {
						echo '<div class="alert error">' . htmlspecialchars($_GET['message']) . '<:div>';
					}
				} else if (isset($_GET['success'])) {
					echo '<div class="alert success">Vous êtes maintenant connecté!</div>';
				}
				?>
				<form method="post" action="index.php">
					<input type="email" name="email" placeholder="Votre adresse email" required />
					<input type="password" name="password" placeholder="Mot de passe" required />
					<button type="submit">S'identifier</button>
					<label id="option"><input type="checkbox" name="auto" checked />Se souvenir de moi</label>
				</form>


				<p class="grey">Première visite sur Netflix ? <a href="inscription.php">Inscrivez-vous</a>.</p>
			<?php } ?>

		</div>
	</section>

	<?php include('src/footer.php'); ?>
</body>

</html>