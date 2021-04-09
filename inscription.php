<?php
session_start();

// Detecter envoie du formulaire:

if (!empty($_POST['email']) && !empty($_POST['password']) && !empty($_POST['password_two'])) {

	require('src/connect.php');
	// VARIABLES:

	$email = htmlspecialchars($_POST['email']);
	$password = htmlspecialchars($_POST['password']);
	$passwordTwo = htmlspecialchars($_POST['password_two']);

	//Vérification si les 2 MDP sont identiques:

	if ($password != $passwordTwo) {
		header('location: inscription.php?error=1&message=Vos mots de passe ne sont pas identiques.');
		exit;
	}

	// Vérification du champs email (s'il n'est pas valide):
	if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		header('location: inscription.php?error=1&message=Votre adresse e-mail est invalide.');
		exit;
	}

	// VERIFIER SI L'EMAIL EST DÉJÀ UTILISÉ:
	$req = $db->prepare(" SELECT count(*) as numberEmail FROM user WHERE email= ? ");
	$req->execute(array($email));
	while ($emailVerif = $req->fetch()) {
		if ($emailVerif['numberEmail'] != 0) {
			header('location: inscription.php?error=1&message=Vous êtes déjà inscrit.');
			exit;
		}
	}

	// HASH DU MDP:
	$secret = sha1($email) . time();
	$secret = sha1($secret) . time();

	//CHIFFRAGE DU MDP:
	$password = "Zt3iS" . sha1($password . "782") . "26";

	//ENVOI EN BDD:
	$req = $db->prepare("INSERT INTO user ( email, password, secret ) VALUES (?,?,?)");
	$req->execute(array($email, $password, $secret));
	header('location: inscription.php?success=1');
	exit;
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
			<h1>S'inscrire</h1>

			<?php if (isset($_GET['error'])) {
				if (isset($_GET['message'])) {
					echo '<div class="alert error">' . htmlspecialchars($_GET['message']) . "</div>";
				}
			} else if (isset($_GET['success'])) {
				echo '<div class="alert success"> Bienvenu parmi nous. <a href="index.php"> Veuillez vous connecter</a></div>';
			}  ?>
			<form method="post" action="inscription.php">
				<input type="email" name="email" placeholder="Votre adresse email" required />
				<input type="password" name="password" placeholder="Mot de passe" required />
				<input type="password" name="password_two" placeholder="Retapez votre mot de passe" required />
				<button type="submit">S'inscrire</button>
			</form>

			<p class="grey">Déjà sur Netflix ? <a href="index.php">Connectez-vous</a>.</p>
		</div>
	</section>

	<?php include('src/footer.php'); ?>
</body>

</html>