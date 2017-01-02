<!DOCTYPE html>
<html>
    <head>
        <title>ECEpic</title>
        <meta charset="utf-8" />
        <link rel="stylesheet" type="text/css" href="index.css">
    </head>

    <body onkeydown="testEchap(event)">

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
session_start();
            if(isset($_POST['formconnexion']))
            {
                $pseudo=$_POST['pseudo'];
                $mdp=$_POST['mdp'];

                if(!empty($_POST['pseudo']) && !empty($_POST['mdp']))
                {
                    $check_pseudo = $bdd->prepare('SELECT pseudo, mdp FROM users WHERE pseudo = ?');
                    $check_pseudo->execute(array($pseudo));
                    $pseudo_exist = $check_pseudo->rowCount();
                    if($pseudo_exist == 1)
                    {
                        $info_user = $check_pseudo->fetch();
                        if($info_user['mdp'] == sha1($mdp))
                        {
                            session_start();
                            $_SESSION['user'] = $pseudo;
                            $message = "Vous êtes connecté!";
                            header('Location: espace_perso.php');
                            exit();
                        }
                        else
                            $message = "Mot de passe incorrect";
                    }
                    else
                        $message = "L'utilisateur n'existe pas";
                }
                else
                    $message = "Veuillez remplir tous les champs";
            }
        ?>
<?php
if(isset($_POST['deconnexion']))
	{
		session_destroy();
	}
	?>
        <h1>Bienvenue sur ECEpic!</h1>
		
<div class='cadre'>
        <h2>Connexion: </h2>

        <form method="POST" action="">

            <table id="form_connexion">
                
            <tr>
                <td>
                    <label>Pseudo: </label>
                </td>
                <td>
                    <input type="text" name="pseudo" />
                </td>
            </tr>

            <tr>
                <td>
                    <label>Mot de passe: </label>
                </td>
                <td>
                    <input type="password" name="mdp"/>
                </td>
            </tr>

            <tr>
                <td></td>
                <td>
                    <input value="Se connecter" type="submit" name="formconnexion"/>
                </td>
            </tr>

            <tr>
                <td></td>
                <td>
                    <?php
                        if(isset($message))
                        {
                            echo($message);
                        }
                    ?>
                </td>
            </tr>

            </table>

        </form>
        <p class='lien'>Login de l'administrateur: admin</p>
        <p class='lien'>Mot de passe de l'administrateur: admin</p>
		<p class='lien'>Pas encore inscrit? Clique <a href="inscription.php">ici</a></p>
		</div>


        

    </body>

</html>