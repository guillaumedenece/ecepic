<!DOCTYPE html>
<html>
    <head>
        <title>ECEpic</title>
        <meta charset="utf-8" />
        <link rel="stylesheet" type="text/css" href="profil.css">
         <link rel="stylesheet" type="text/css" href="style.css">
        <link rel="stylesheet" type="text/css" href="menu.css">
        <link rel="stylesheet" type="text/css" href="album.css">

    </head>

    <body  onkeydown="testEchap(event)">

<?php
            try
            {
                $bdd = new PDO('mysql:host=localhost;dbname=ecepic;charset=utf8', 'root', '');
            }

            catch (Exception $e)
            {
                die('Erreur : ' . $e->getMessage());
            }
       //recupere l'avatar du profil
        $avatar_user_requete = $bdd->prepare('SELECT avatar FROM users WHERE pseudo = ?');
            $avatar_user_requete->execute(array($_GET['pseudo']));
            $avatar_user_reponse = $avatar_user_requete->fetch();



         include('menu.php');


//affiche l'avatar du profil
echo('<div class="profile"');

if(!empty($avatar_user_reponse['avatar']))
					{
			 			echo('<a href="#"><img class = "avatar" src="'.$avatar_user_reponse['avatar'].'"/></a>');
					}
					else
					{
						echo('<a href="#"><img class = "avatar" src="avatars/unknown.png"/></a>');
					}

echo('<div class="user">');
                    //affiche le nom du profil
					echo($_GET['pseudo']); 


echo('</div>');
echo('</div>');

$id_user_requete = $bdd->prepare('SELECT ID FROM users WHERE pseudo = ?');
            $id_user_requete->execute(array($_GET['pseudo']));
            $id_user_reponse = $id_user_requete->fetch();

            $album_requete = $bdd->prepare('SELECT * FROM album WHERE album.ID_owner = ?');
            $album_requete->execute(array($id_user_reponse['ID']));

            while($album_reponse = $album_requete->fetch())
            {
                $photo_album_requete = $bdd->prepare('SELECT ID_photo FROM album_photo WHERE album_photo.ID_album = ?');
                $photo_album_requete->execute(array($album_reponse['ID']));

                $ID_album = $album_reponse['ID'];

                echo('<div class="bloc_photo"> <a href="visionnage_album.php?id_album=' . $ID_album . '">');

                //partie à modifier pour faire diaporama de photos
                $photo_album_reponse = $photo_album_requete->fetch();

                echo('<p class="album_title">' . $album_reponse['title']. '</p>');

                //récupération de la première photo de l'album
                $photo_path_request = $bdd->prepare('SELECT path FROM photos WHERE photos.ID = ?');
                $photo_path_request->execute(array($photo_album_reponse['ID_photo']));
                $photo_path_reponse = $photo_path_request->fetch();

                echo('<p class="image">' . '<img class = "photo" src="'.$photo_path_reponse['path'].'"/>' . '</p>');

                echo('<p class="album_description">' . $album_reponse['description']. '</p>');

                echo('</a></div>');
            }
?>		

