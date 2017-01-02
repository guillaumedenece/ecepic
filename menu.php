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
			


			if(isset($_POST['poster_photo'])) 
			 {if(isset($_FILES['photo']['name']))
				 {
	        	
	     $path = "photos/".$_FILES['photo']['name'];
		 
        	$requete_id_user = $bdd->prepare('SELECT ID from users WHERE pseudo = ?');
        	$requete_id_user->execute(array($_SESSION['user']));
			$id_user = $requete_id_user->fetch();
			$deplacement = move_uploaded_file($_FILES['photo']['tmp_name'], $path);
	            if($deplacement)
		           { if(isset($_POST['visibilité']))
			          { if(!empty($_POST['titre']))
						  { if(!empty($_POST['lieu']))
							  { if(isset($_POST['categorie']))
								  {
	        	$insert_photo = $bdd->prepare('INSERT INTO photos (path, public, ID_owner,location,theme,name,date) VALUES (?, ?, ?,?,?,?,NOW())');
		        	$insert_photo->execute(array($path, $_POST['visibilité'], $id_user['ID'],$_POST['lieu'],$_POST['categorie'],$_POST['titre']));
								  }
							  
							 else
								  
				 $message="Veuillez précisez la categorie de votre photo";
			                      }	
				else
				
					 $message="Veuillez précisez le lieu de votre photo";
						  }
						  else
							   $message = "Veuillez précisez le titre de votre photo";
					  }
					  else 
						  $message = "Veuillez précisez la visibilité de votre photo";
               				
		           }
				   else
					    $message = "Veuillez choisir votre photo";
				 }
			 }

			if(isset($_POST['poster_album']))
			{
				if(isset($_POST['title']))
				{
					if(isset($_POST['description']))
					{
						$id_user_request = $bdd->prepare('SELECT ID FROM users WHERE pseudo = ?');
						$id_user_request->execute(array($_SESSION['user']));
						$id_user_answer = $id_user_request->fetch();
						$add_album_request = $bdd->prepare('INSERT INTO album(title, description, ID_owner) VALUES (?, ?, ?)');
						$add_album_request->execute(array($_POST['title'], $_POST['description'], $id_user_answer['ID']));
					}
				}
			}

			$avatar_request = $bdd->prepare('SELECT avatar, admin FROM users WHERE pseudo =?');
			$avatar_request->execute(array($_SESSION['user']));
			$avatar_answer = $avatar_request->fetch();
			
			echo('<div id="background_menu">');

			if($avatar_answer['admin'] == '0')
			{
				echo('<ul class="menu_4">');
			}

			if($avatar_answer['admin'] == '1')
			{
				echo('<ul class="menu">');
			}

	    		echo('<li>');
					echo('<a href="espace_perso.php"><img class = "pictograme_menu" src="ressources/ecepic.png"/></a>');
				echo('</li>');

				echo('<li>');
					echo('<img id="add_picture" class = "pictograme_menu" src="ressources/add_picture.png"/>');
			 	echo('</li>');

				echo('<li>');
					if(!empty($avatar_answer['avatar']))
					{
			 			echo('<a href="#"><img class = "pictograme_menu_avatar" src="'.$avatar_answer['avatar'].'"/></a>');
					}
					else
					{
						echo('<a href="#"><img class = "pictograme_menu_avatar" src="avatars/unknown.png"/></a>');
					}
					echo('<ul>');
						echo('<li>');
						echo('
							<form method = "POST" action="index.php" enctype="multipart/form-data">
	 						<input id="deconnexion" value="Déconnexion" type="submit" name="deconnexion"/>
	 						</form>');
						echo('</li>');
						echo('<li><a href="moncompte.php">Mon compte</a></li>');
					echo('</ul>');
				echo('</li>');

				echo('<li>');
					echo('<a href="#"><img class = "pictograme_menu" src="ressources/my_pictures.png"/></a>');
					echo('<ul><li class="listed" ><a href="mes_images.php">Mes albums</a></li>');
					echo('<li class="listed"><a id="mon_compte">Créer album</a></li></ul>');
				echo('</li>');

			if($avatar_answer['admin'] == '1')
			{
				echo('<li>');
				echo('<a href="admin.php"><img class = "pictograme_menu" src="ressources/parameters.png"/></a>');
				echo('</li>');
			}

			echo('</ul>');
			echo('</div>');
			 
			//fond d'affichage de la fenêtre d'ajout de photo
			echo('<div id="background_add_picture">');

				  //formulaire de demande
				  echo('<div class="formulaire" id="form_ajout_photo">');
				  echo('<form method="POST" action="" enctype="multipart/form-data">

				    	Veuillez indiquer la visibilité de la photo :
				    	<br />
				       	<input type="radio" name="visibilité" value="1" id="public" /> <label for="public">Publique</label><br />
				       	<input type="radio" name="visibilité" value="0" id="privé" /> <label for="privé">privé</label><br />
					   	Veuillez indiquez le titre de la photo<br/>
					    <textarea name="titre" id="titre" class="areas"></textarea><br/>
					   	Veuillez indiquez le lieu de la photo<br/>
					   	<input type="text" name="lieu" class="areas" /><br/>
					   	Veuillez indiquez la catégorie de la photo<br/>
					  	<select name="categorie" class="areas" id="categorie">
				           <option value="Voiture">Voiture</option>
                            <option value="Paysage">Paysage</option>
                            <option value="Portrait">Portrait</option>
                            <option value="Sport">Sport</option>
                            <option value="Technologie">Technologie</option>
                            <option value="Animaux">Animaux</option>
                            <option value="Nourriture">Nourriture</option>
                            <option value="Autres">Autres</option>
				       	</select>
				       	<br/>
							Veuillez choisir une photo à importer 
						<br/>
							<input type="file" class="areas" name="photo"/>
						<br/> 
							<input value="valider" class= "areas" type="submit" name="poster_photo"/>
					</form>
			
			      </div>
				  ');

				  echo('<div class="formulaire" id="form_ajout_album">');

				  	echo('
				  			<form method="POST" action="" enctype="multipart/form-data">
				  				<table>
				  					<caption>
				  						Créer un album
				  					</caption>
				  					<tr>
				  						<td>
				  							<label>Titre:</label>
				  						</td>
				  						<td>
				  							<input type="text" name="title" />
				  						</td>
				  					</tr>
				  					<tr>
				  						<td>
				  							<label>Description:</label>
				  						</td>
				  						<td>
				  							<textarea name="description" id="description"></textarea>
				  						</td>
				  					</tr>
				  					<tr>
				  						<td colspan="2">
				  							<input value="Valider" type="submit" name="poster_album"/>
				  						</td>
				  					</tr>
				  				</table>
				  			</form>
				  		');

				  echo('</div>');



			echo('</div>');

/*				  
				    <label>Veuillez indiquer la visibilité de la photo :</label><br />
       <input type="radio" name="visibilité" value="1" id="public" /> <label for="public">Publique</label><br />
       <input type="radio" name="visibilité" value="0" id="privé" /> <label for="privé">privé</label><br />
	   <label>Veuillez indiquez le titre de la photo</label><br/>
	    <textarea name="titre" id="titre"></textarea><br/>
	   <label>Veuillez indiquez le lieu de la photo</label><br/>
	   <input type="text" name="lieu" /><br/>
	   <label>Veuillez indiquez la catégorie de la photo</label><br/>
	   <select name="categorie" id="categorie">
           <option value="Voiture">Voiture</option>
           <option value="paysage">Paysage</option>
           <option value="portrait">Portrait</option>
           <option value="sport">Sport</option>
           <option value="technologie">Technologie</option>
           <option value="animaux">Animaux</option>
           <option value="nourriture">nourriture</option>
           <option value="autres">Autres</option>
       </select><br/>
	   <input type="datetime" ></code><br/>
				  <label>Veuillez choisir une photo à importer</label> <br/>
				  <input type="file" name="photo"/>
				  
			      <br/> <input value="valider" type="submit" name="poster_photo"/>
			      
				  </form>');
				  echo('</p>');
				  if(isset($message))
                        {
                            echo($message);
                        }

*/
			echo('<script src="jquery-1.11.0.min.js"></script>');
			echo('<script src="https://code.jquery.com/jquery-1.10.2.js"></script>');
			echo('<script src="jquery-migrate-1.2.1.min.js"></script>');
    		echo('<script type="text/javascript" src="script.js"></script>');
    		echo('<script type="text/javascript" src="menu.js"></script>');

                        if(isset($message))
                        {
                            echo("<div id='erreur'>".$message)."</div>";
                            
                        }
			 
    	?>
		