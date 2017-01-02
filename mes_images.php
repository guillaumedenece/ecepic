<!DOCTYPE html>
<html>
    <head>
        <title>ECEpic</title>
        <meta charset="utf-8" />
        <link rel="stylesheet" type="text/css" href="style.css">
        <link rel="stylesheet" type="text/css" href="album.css">
        <link rel="stylesheet" type="text/css" href="menu.css">
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
        ?>

<?php
include('menu.php'); 

            echo('<h1 class="montitre" >Mes albums!!</h1>');

            $album_bdd_request = $bdd->query('SELECT * FROM album');

            while($album_bdd_answer = $album_bdd_request->fetch())
            {
                $identifiant_album_trash = 'a' . $album_bdd_answer['ID'];
                if(isset($_POST[$identifiant_album_trash]))
                {
                    $delete_album = $bdd->prepare('DELETE FROM album WHERE ID = ?');
                    $delete_album->execute(array($album_bdd_answer['ID']));
                    $album_bdd_request->closeCursor();
                }
            }         
?>

        <?php
            
            $id_user_requete = $bdd->prepare('SELECT ID FROM users WHERE pseudo = ?');
            $id_user_requete->execute(array($_SESSION['user']));
            $id_user_reponse = $id_user_requete->fetch();

          
                  
            $album_requete = $bdd->prepare('SELECT * FROM album WHERE album.ID_owner = ?');
            $album_requete->execute(array($id_user_reponse['ID']));

            while($album_reponse = $album_requete->fetch())
            {
                $photo_album_requete = $bdd->prepare('SELECT ID_photo FROM album_photo WHERE album_photo.ID_album = ?');
                $photo_album_requete->execute(array($album_reponse['ID']));

                $ID_album = $album_reponse['ID'];

                echo('<div class="bloc_photo"> <a href="visionnage_album.php?id_album=' . $ID_album . '">');

                $name_trash= 'a' . $album_reponse['ID']; 
                echo('<form method="POST" action ="" enctype="multipart/form-data">');
                            echo('<input class = "float_right" src="ressources/trash.png" type="image" value="submit" name="' . $name_trash . '" /> ');
                        echo('</form>');

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

    </body>

</html>