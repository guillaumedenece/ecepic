<!DOCTYPE html>
<html>
    <head>
        <title>ECEpic</title>
        <meta charset="utf-8" />
        <link rel="stylesheet" type="text/css" href="style.css">
        <link rel="stylesheet" type="text/css" href="menu.css">
        <link rel="stylesheet" type="text/css" href="admin.css">
    </head>

    <body onkeydown="testEchap(event)">

    <?php include('menu.php'); ?>

	<?php
	try
	{
		
		$bdd = new PDO('mysql:host=localhost;dbname=ecepic;charset=utf8', 'root', '');
	}
	catch(Exception $e)
	{
	        die('Erreur : '.$e->getMessage());

	}

//récupération de la variable d'URL,
  //qui va nous permettre de savoir quel enregistrement supprimer:

$reponse = $bdd->query('SELECT * FROM users');

while ($donnees = $reponse->fetch())
{
	if(isset($_POST[$donnees['pseudo']]))
	{   
		$delete_user = $bdd->prepare('DELETE FROM users WHERE pseudo = ?');
		$delete_user->execute(array($donnees['pseudo']));
	}

	if(isset($_POST[$donnees['email']]))
	{  
		$promo_user = $bdd->prepare('UPDATE users SET admin = :admin WHERE email = :email');
		$promo_user->execute(array('admin' => 1, 'email' => $donnees['email']));
	}
}

echo('<h1>Interface d\'administration</h1>');
echo('<div id="administration">');

$reponse = $bdd->query('SELECT * FROM users');
echo ('<table>');
while ($donnees = $reponse->fetch())
{

    echo('<p><tr>');
    echo ('<td>');
    echo('Utilisateur :'.$donnees['pseudo']); 
    echo('</td>');

    echo('<form method="POST" action="" enctype="multipart/form-data">
    	<td><input class = "boutton" value="Supprimer" type="submit" name="' . $donnees['pseudo'] . '" /> </td>
    	<td><input class="boutton" value="Promouvoir" type="submit" name="' . $donnees['email'] . '" /> </td></form></tr></p>');
}

$reponse->closeCursor();
echo('</table>');
echo('</div>');

?>
	
	</body>

</html>