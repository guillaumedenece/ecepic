<!DOCTYPE html>
<html>
    <head>
        <title>ECEpic</title>
        <meta charset="utf-8" />
        <link rel="stylesheet" type="text/css" href="style.css">
        <link rel="stylesheet" type="text/css" href="menu.css">
        <link rel="stylesheet" type="text/css" href="parser.css">
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

<!--Code pour modifier les informations d'une image-->
<?php
    
    if(isset($_POST['modif_info_photo']))
    {
        $request_modif_infos_photo = $bdd->prepare('UPDATE photos SET name = :name, location = :location, theme = :theme, public = :public WHERE ID = :ID ');
        $request_modif_infos_photo->execute(array(
            'name' => $_POST['name_modif'],
            'location' => $_POST['location_modif'],
            'theme' => $_POST['categorie_modif'],
            'public' => $_POST['visibilite_modif'],
            'ID' => $_POST['photo_tomodif'],
            ));
    }

?>

<!--Code pour ajouter une photo à l'album-->
<?php

    if(isset($_POST['ajout_a_album']))
    {
        $request_ajouter_a_album = $bdd->prepare('INSERT INTO album_photo (ID_album, ID_photo) VALUES (?, ?)');

        $request_id_album = $bdd->prepare('SELECT ID FROM album WHERE title = ?');
        $request_id_album->execute(array($_POST['album_cible']));
        $reponse_id_album = $request_id_album->fetch();

        $request_ajouter_a_album->execute(array($reponse_id_album['ID'], $_POST['photo_tomove']));
    }
?>

<?php
            $photo_bdd_request = $bdd->query('SELECT * FROM photos');

            while($photo_bdd_answer = $photo_bdd_request->fetch())
            {
                $identifiant_photo_trash = 's' . $photo_bdd_answer['ID'];
                if(isset($_POST[$identifiant_photo_trash]))
                {
                    $delete_photo = $bdd->prepare('DELETE FROM photos WHERE ID = ?');
                    $delete_photo->execute(array($photo_bdd_answer['ID']));
                    $delete_photo_album = $bdd->prepare('DELETE FROM album_photo WHERE ID_photo = ?');
                    $delete_photo_album->execute(array($photo_bdd_answer['ID']));
                }

                $identifiant_photo_add_album = 'add' . $photo_bdd_answer['ID'];
                if(isset($_POST[$identifiant_photo_add_album]))
                {
                    echo('<div class="ajout_album">');
                    echo('<form method="POST" action ="" enctype="multipart/form-data">');
                    echo('Veuillez sélectionner l\'album dans lequel ajouter la photo:<br>');
                        echo('<select name="album_cible" id="album">');

                            $requete_id_user_album = $bdd->prepare('SELECT ID FROM users WHERE pseudo = ?');
                            $requete_id_user_album->execute(array($_SESSION['user']));
                            $reponse_id_user_album = $requete_id_user_album->fetch();
                            $requete_albums_name = $bdd->prepare('SELECT * FROM album WHERE ID_owner = ?');
                            $requete_albums_name->execute(array($reponse_id_user_album['ID']));

                            while($reponse_albums_name = $requete_albums_name->fetch())
                            {
                                echo('<option value="' . $reponse_albums_name['title'] . '"">' . $reponse_albums_name['title'] .'</option>');
                            }
                        echo('</select>');

                        echo('<input type="text" value="'. $photo_bdd_answer['ID'] .'"name="photo_tomove" style="display:none;"/>');

                        echo('<br><input value="valider" type="submit" name="ajout_a_album"/>');
                        echo('<input value="annuler" type="submit" name="annuler"/>');
                    echo('</form>');
                    echo('</div>');
                }

                $identifiant_photo_modif = 'modif' . $photo_bdd_answer['ID'];
                if(isset($_POST[$identifiant_photo_modif]))
                {
                    echo('<div class="ajout_album" style="text-align:left;">');
                    echo('<form method="POST" action ="" enctype="multipart/form-data">');
                    echo('Veuillez modifier les informations que vous souhaitez:<br>');

                        echo('<label>Titre: </label><textarea name="name_modif" value= rows="1" cols="20">'. $photo_bdd_answer['name'] .'</textarea><br>');

                        echo('<label>Localisation: </label><input type="text" value="'. $photo_bdd_answer['location'] .'"name="location_modif"/><br>');

                        echo('Visibilité:');
                        echo('<label>Publique</label><input type="radio" name="visibilite_modif" checked = "checked" value="1"/>');
                        echo('<label>Prive</label><input type="radio" name="visibilite_modif" value="0"/><br>');

                        echo('
                                Veuillez indiquez la catégorie de la photo<br/>
                                <select name="categorie_modif">
                                   <option value="Voiture">Voiture</option>
                                   <option value="Paysage">Paysage</option>
                                   <option value="Portrait">Portrait</option>
                                   <option value="Sport">Sport</option>
                                   <option value="Technologie">Technologie</option>
                                   <option value="Animaux">Animaux</option>
                                   <option value="Nourriture">Nourriture</option>
                                   <option value="Autres">Autres</option>
                                </select>
                            ');
                        
                        echo('<input type="text" value="'. $photo_bdd_answer['ID'] .'"name="photo_tomodif" style="display:none;"/>');

                        echo('<br><input value="valider" type="submit" name="modif_info_photo"/>');
                        echo('<input value="annuler" type="submit" name="annuler"/>');
                    echo('</form>');
                    echo('</div>');
                }
            }

            $photo_bdd_request->closeCursor();
?>

<?php $photos_requete = $bdd->query('SELECT * FROM photos ORDER BY date DESC'); ?>

<?php
            $requete_id_user = $bdd->prepare('SELECT ID from users WHERE pseudo = ?');
            $requete_id_user->execute(array($_SESSION['user']));
            $id_user = $requete_id_user->fetch();

            while($photos_reponse = $photos_requete->fetch())
            {
                if($photos_reponse['public'] == 1 || $photos_reponse['ID_owner'] == $id_user['ID'])

                {
                    echo('<div class="bloc_photo">');

                    //récupère le nom du propriétaire de la photo et son avatar
                    $requete_pseudo_owner = $bdd->prepare('SELECT pseudo, avatar FROM users, photos WHERE users.ID = ?');
                    $requete_pseudo_owner -> execute(array($photos_reponse['ID_owner']));
                    $pseudo_owner = $requete_pseudo_owner->fetch();
    
                    //affichage de l'avatar du propriétaire de la photo si il en a un                   
                    if(!empty($pseudo_owner['avatar']))
                    {
                        echo('<p class="paragraph_owner">' . '<a href="profil.php?pseudo=' . $pseudo_owner['pseudo'] .'"> <img class = "avatar_owner" src="'.$pseudo_owner['avatar'].'"/></a>');
                    }
                    else
                    {
                        echo('<p class="paragraph_owner">' . '<a href="profil.php?pseudo=' . $pseudo_owner['pseudo'] .'"> <img class = "avatar_owner" src="avatars/unknown.png"/></a>');
                    }

                    if($pseudo_owner['pseudo'] ==  $_SESSION['user'])
                    {
                        $name_trash= 's' . $photos_reponse['ID'];
                        echo('<form method="POST" action ="" enctype="multipart/form-data">');
                            echo('<input class = "float_right" src="ressources/trash.png" type="image" value="submit" name="' . $name_trash . '" /> ');
                        echo('</form>');

                        $information_modif= 'modif' . $photos_reponse['ID'];

                        echo('<form method="POST" action ="" enctype="multipart/form-data">');
                            echo('<input class = "float_right" src="ressources/edit_information.png" type="image" value="submit" name="' . $information_modif . '" /> ');
                        echo('</form>');                     
                    } 

                    $name_add= 'add' . $photos_reponse['ID'];

                    echo('<form method="POST" action ="" enctype="multipart/form-data">');
                            echo('<input class = "float_right" src="ressources/add_to_album.png" type="image" value="submit" name="' . $name_add . '" /> ');
                    echo('</form>');

                    $exif_to_display = $photos_reponse['ID'];

                    echo('<img class = "float_right" src="ressources/exif.png" onClick="afficher_exif('.$exif_to_display.');" />');   

                    //affiche le pseudo du propriétaire de la photo
                    echo('<span class="name_owner">' . '<a href="profil.php?pseudo=' . $pseudo_owner['pseudo'] .'">' . $pseudo_owner['pseudo'] . '</a></span>');

                    //Affiche le nom de la photo si elle en a un
                    if(!empty($photos_reponse['name']))
                    {
                        echo('<br/><span class="name_photo">' . $photos_reponse['name'] . '</span></p>');
                    }
                    else
                    {
                        echo('</p>');
                    }

                    $path_image = $photos_reponse['path'];

                     
    
                    if(isset($_POST['modification']))   
                        {
                            $req = $bdd->prepare('UPDATE photos SET public = :public, location = :location,theme = :theme,name = :name WHERE ID = :photos_requete');
                            $req->execute(array(
                            'public' => $_POST['visibilité_mod'],
                            'location' => $_POST['lieu_mod'],
                            'theme' => $_POST['categorie_mod'],
                            'name' => $_POST['titre_mod'],
                            'photos_requete' => $photos_reponse['ID'],
                            ));
                        }


                    include("parser_exif.php");

                    //affichage de la photo
                    echo('<p class="image">' . '<img class = "photo" src="'.$photos_reponse['path'].'"/>' . '</p>');
                    
                    //affichage info sur la photo et modif
                     if($pseudo_owner['pseudo'] ==  $_SESSION['user'])
                     {
                        echo('<div class="modif_photo">');
                        echo('<form method="POST" action="" enctype="multipart/form-data">

                        Changer la visibilité de la photo :
                        <br />
                        <input type="radio" name="visibilité_mod" value="1" id="public" /> <label for="public">Publique</label><br />
                        <input type="radio" name="visibilité_mod" value="0" id="privé" /> <label for="privé">privé</label><br />
                        Changer le titre de la photo<br/>
                        <textarea name="titre_mod" id="titre_mod"></textarea><br/>
                        Changer le lieu de la photo<br/>
                        <input type="text" name="lieu_mod" /><br/>
                        Changer la catégorie de la photo<br/>
                        <select name="categorie_mod" id="categorie_mod">
                           <option value="Voiture">Voiture</option>
                           <option value="paysage">Paysage</option>
                           <option value="portrait">Portrait</option>
                           <option value="sport">Sport</option>
                           <option value="technologie">Technologie</option>
                           <option value="animaux">Animaux</option>
                           <option value="nourriture">nourriture</option>
                           <option value="autres">Autres</option>
                        </select>
                        <br/>
                    
                        <br/> 

                            <input value="Modifier" type="submit" name="modification"/>

                    </form>
                     <br/>  OU <br/>
                    <br/>

                        <input value="Supprimer" type="submit" name="suppression" />
                    </div>
                  ');
                    }

                    //affichage de la localisation de la photo
                    if(!empty($photos_reponse['location']))
                    {
                        echo('<p class="description"> <img class = "logo_description" src="ressources/location.png"/>' . '<a href="https://www.google.fr/maps/search/'.$photos_reponse['location'].'" target="blank" title="Cliquer pour accéder à une carte">' . $photos_reponse['location'] . '</a>' . '</p>');
                    }

                    //affichage de la date de la photo
                    if(!empty($photos_reponse['date']))
                    {
                        $date=date_create($photos_reponse['date']);
                        echo('<p class="description"> <img class = "logo_description" src="ressources/date.png"/>' . date_format($date, "d M Y H:i"));
                    }

                    //affichage du thème de la photo
                    if(!empty($photos_reponse['theme']))
                    {
                        echo('<p class="description"> <img class = "logo_description" src="ressources/theme.png"/>' . $photos_reponse['theme'] . '</p>');
                    }

                    echo('</div>');
                }
            }



            echo('<script src="jquery-1.11.0.min.js"></script>');
            echo('<script src="jquery-migrate-1.2.1.min.js"></script>');
        ?>

    </body>

</html>