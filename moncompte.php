<!DOCTYPE html>
<html>
    <head>
        <title>ECEpic</title>
        <meta charset="utf-8" />
        <link rel="stylesheet" type="text/css" href="style.css">
        <link rel="stylesheet" type="text/css" href="moncompte.css">
        <link rel="stylesheet" type="text/css" href="menu.css">

    </head>

    <body  onkeydown="testEchap(event)">

	<?php include('menu.php'); ?>
	
	<?php
            try
            {
                $bdd = new PDO('mysql:host=localhost;dbname=ecepic;charset=utf8', 'root', '');
            }

            catch (Exception $e)
            {
                die('Erreur : ' . $e->getMessage());
            }
        ?>
	<?php
	
	$requete_id_user = $bdd->prepare('SELECT ID from users WHERE pseudo = ?');
        	$requete_id_user->execute(array($_SESSION['user']));
			$id_user = $requete_id_user->fetch();
			
			if(isset($_POST['modifier']))	
	{
	$req = $bdd->prepare('UPDATE users SET pseudo = :pseudo, mdp = :mdp WHERE pseudo = :ex_pseudo ');
	$req->execute(array(
	'pseudo' => $_POST['pseudo_mod'],
	'mdp' => sha1(htmlspecialchars($_POST['mdp_mod'])),
	'ex_pseudo' => $_POST['pseudo'],
	));
	}
?>

	 <div class="modi">
	 <h2>Modifier ses données : </h2>
	 <form method = "POST" action="" enctype="multipart/form-data">
	 		<table>

	        	<tr>
	        		<td> 
	        			<label>Ancien Pseudo :</label>
		        	</td>
		        	<td>
		        		<input type="text" name="pseudo"   placeholder="Votre ancien pseudo"/>
		        	</td>
		        </tr>

				<tr>
	        		<td>
		        		<label>Nouveau Pseudo :</label>
		        	</td>
		        	<td>
		        		<input type="text" name="pseudo_mod"   placeholder="Votre nouveau pseudo"/>
		        	</td>
		        </tr>

		        <tr>
		        	<td>
	        			<br/><label>Ancien Mot de passe :</label>
	        		</td>
	        		<td>
	        			<input type="password" name="mdp" />
	        		</td>
	        	</tr>

				<tr>
		        	<td>
	        			<br/><label>Nouveau Mot de passe :</label>
	        		</td>
	        		<td>
	        			<input type="password" name="mdp_mod" />
	        		</td>
	        	</tr>

				<tr>
	        		<td></td>
	        		<td>
	        	        <input value="modifier" type="submit" name="modifier"/>
	        	    </td>
	        	</tr>
	        </table>
	 </form>
	 </div>
	</body>

</html>