<!DOCTYPE html>
<html>
    <head>
        <title>ECEpic</title>
        <meta charset="utf-8" />
        <link rel="stylesheet" type="text/css" href="inscription.css">
    </head>

    <body>

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

        	if(isset($_POST['forminscription']))
        	{
        		$pseudo = $_POST['pseudo'];
        		$email1 = $_POST['email1'];
        		$email2 = $_POST['email2'];
        		$mdp1 = sha1(htmlspecialchars($_POST['mdp1']));
        		$mdp2 = sha1(htmlspecialchars($_POST['mdp2']));

        		if(!empty($_POST['pseudo']) && !empty($_POST['email1']) && !empty($_POST['email2']) && !empty($_POST['mdp1']) && !empty($_POST['mdp2']))
        		{
        			if($email1 == $email2)
        			{
        				$verifpseudo = $bdd->prepare('SELECT * FROM users WHERE pseudo = ?');
        				$verifpseudo->execute(array($pseudo));
        				$pseudoexist = $verifpseudo->rowCount();
        				if($pseudoexist == 0)
        				{
	        				$verifemail = $bdd->prepare('SELECT * FROM users WHERE email = ?');
	        				$verifemail->execute(array($email1));
	        				$emailexist = $verifemail->rowCount();
	        				if($emailexist == 0)
	        				{
	        					if($mdp1 == $mdp2)
	        					{
	        						
	        						if(isset($_FILES['avatar']) && !empty($_FILES['avatar']['name']) )
	        						{
	        							//limite de 2Mo
	        							$taillemax = 2097152;
	        							$extension_valide = array('jpg','jpeg','gif','png');

	        							if($_FILES['avatar']['size'] <= $taillemax)
	        							{
	        								//strtolower : mettre toutes les lettres en minuscules
	        								//substr: ignorer certain charactères
	        								//strrchr: prendre l'extension du fichier
	        								//récupère l'extension du fichier en minuscule
	        								$extension_upload = strtolower(substr(strrchr($_FILES['avatar']['name'], '.'),1));
	        								if(in_array($extension_upload, $extension_valide))
	        								{
	        									$path = "avatars/".$pseudo.".".$extension_upload;
	        									//tmp_name est le dossier ou est situé temporairement le fichier sur le serveur
	        									$deplacement = move_uploaded_file($_FILES['avatar']['tmp_name'], $path);
	        									if($deplacement)
	        									{
	        										$insertuser = $bdd->prepare('INSERT INTO users (pseudo, mdp, email, avatar) VALUES (?, ?, ?, ?)');
		        									$insertuser->execute(array($pseudo, $mdp1, $email1, $path));
		        									$message = 'Votre compte a bien été créé <a href="index.php">se connecter</a>';
	        									}
	        									else
	        									{
	        										$message = "Il y a eu une erreur pendant l'importation de votre avatar";
	        									}
	        								}
	        								else
	        								{
	        									$message = "Votre avatar doit être au format jpg, jpeg, gif ou png";
	        								}
	        							}
	        							else
	        							{
	        								$message = "Votre avatar ne doit pas dépasser 2Mo";
	        							}
	        						}
	        						else
	        						{
	        							$insertuser = $bdd->prepare('INSERT INTO users (pseudo, mdp, email) VALUES (?, ?, ?)');
		        						$insertuser->execute(array($pseudo, $mdp1, $email1));
		        						$message = 'Votre compte a bien été créé <a href="index.php">se connecter</a>';
	        						}
	        					}
	        					else
	        						$message = "Les 2 mots de passe ne sont pas identiques";
	        				}
	        				else
	        					$message = "Email existe déjà";
	        			}
	        			else
	        				$message = "Pseudo existe déjà";
        			}
        			else
        				$message = "Les 2 email doivent être identiques";
        		}
        		else
        			$message = "Merci de bien vouloir remplir tous les champs!";
        	}
        ?>

        <h1>Bienvenue sur ECEpic! </h1>

        <h2>Inscription</h2>

        <form method = "POST" action="" enctype="multipart/form-data">

        	<table id = "form_inscription">

	        	<tr>
	        		<td>
		        		<label>Pseudo :</label>
		        	</td>
		        	<td>
		        		<input type="text" name="pseudo"  value='<?php if(isset($pseudo)) { echo $pseudo; } ?>' placeholder="Votre pseudo"/>
		        	</td>
		        </tr>

		        <tr>
		        	<td>
	        			<label>Email :</label>
	        		</td>
	        		<td>
	        			<input type="text" name="email1" placeholder="Votre email" value='<?php if(isset($email1)) { echo $email1; } ?>'/>
	        		</td>
	        	</tr>

	        	<tr>
	     			<td>
	       				<label>Confirmation Email :</label> 
	       			</td>
	       			<td>
	       				<input type="text" name="email2" placeholder="Verification email" value='<?php if(isset($email2)) { echo $email2; } ?>'/>
	       			</td>
	       		</tr>

	        	<tr>
	        		<td>
	       				<label>Mot de Passe :</label> 
	       			</td>
	       			<td>
	       				<input type="password" name="mdp1" placeholder="Votre mot de passe"/>
	       			</td>
	       		</tr>

	       		<tr>
	       			<td>
	        			<label>Vérification Mot de Passe :</label>
	        		</td>
	        		<td>
	        			<input type="password" name="mdp2" placeholder="Vérification de votre mot de passe"/>
	        		</td>
	        	</tr>

	        	<tr>
	        		<td>
	        			<label>Avatar : (facultatif)</label>
	        		</td>
	        		<td>
	        			<input type="file" name="avatar"/>
	        		<td>
	        	</tr>

	        	<tr>
	        		<td></td>
	        		<td>
	        	        <input value="S'inscrire" type="submit" name="forminscription"/>
	        	    </td>
	        	</tr>

	        </table>
<p class='lien'>Déjà inscrit? Clique <a href="index.php">ici</a> pour te connecter!</p>
        </form>

        <?php
        	if(isset($message))
        	{
        		echo( '<p id="message_inscription">' . $message . '</p>');
        	}
        ?>

        

    </body>

</html>
