<?php

	if(in_array(strtolower(substr(strrchr($path_image, '.'),1)),array('jpg', 'jpeg', 'tif', 'tiff'))) // Si fichier Jpeg ou Tiff
	{
		if($exif = exif_read_data($path_image, 'EXIF', true)) // Si le fichier $img contient des infos Exif
		{
			foreach ($exif as $key => $section) // On parcourt la première partie du tableau mtrtidimensionnel
			{       
				foreach ($section as $name => $value) // On parcourt la seconde partie
				{
					$exif_tab[$name] = $value; // Récupération des valeurs dans le tableau $exif_tab
				}
			}
		}
	}
/*
	if(!empty($exif_tab))
	{
		echo '<pre>';
		print_r($exif_tab);
		echo '</pre>';
	}*/

	echo('<div class="table_exif" id="exif'.$photos_reponse['ID'].'">');
	echo('<table>');

		if(!empty($exif_tab['FileName']))
		{
			echo('<tr>');
				echo('<td>');
					echo('Nom du Fichier:');
				echo('</td>');
				echo('<td>');
					echo($exif_tab['FileName']);
				echo('</td>');
			echo('</tr>');
		}

		if(!empty($exif_tab['DateTime']))
		{
			echo('<tr>');
				echo('<td>');
					echo('Date/heure de prise:');
				echo('</td>');
				echo('<td>');
					echo($exif_tab['DateTime']);
				echo('</td>');
			echo('</tr>');
		}

		if(!empty($exif_tab['Height']))
		{
			echo('<tr>');
				echo('<td>');
					echo('Hauteur de la photo (pixels):');
				echo('</td>');
				echo('<td>');
					echo($exif_tab['Height']);
				echo('</td>');
			echo('</tr>');
		}

		if(!empty($exif_tab['Width']))
		{
			echo('<tr>');
				echo('<td>');
					echo('Largeur de la photo (pixels)');
				echo('</td>');
				echo('<td>');
					echo($exif_tab['Width']);
				echo('</td>');
			echo('</tr>');
		}

		if(!empty($exif_tab['Make']))
		{
			echo('<tr>');
				echo('<td>');
					echo('Marque de l\'appareil photo:');
				echo('</td>');
				echo('<td>');
					echo($exif_tab['Make']);
				echo('</td>');
			echo('</tr>');
		}

		if(!empty($exif_tab['Model']))
		{
			echo('<tr>');
				echo('<td>');
					echo('Model de l\'appareil photo:');
				echo('</td>');
				echo('<td>');
					echo($exif_tab['Model']);
				echo('</td>');
			echo('</tr>');
		}

		if(!empty($exif_tab['FocalLength']))
		{
			echo('<tr>');
				echo('<td>');
					echo('Distance focale:');
				echo('</td>');
				echo('<td>');
					echo($exif_tab['FocalLength']);
				echo('</td>');
			echo('</tr>');
		}

		if(!empty($exif_tab['Saturation']))
		{
			echo('<tr>');
				echo('<td>');
					echo('Saturation:');
				echo('</td>');
				echo('<td>');
					echo($exif_tab['Saturation']);
				echo('</td>');
			echo('</tr>');
		}

		if(!empty($exif_tab['Flash']))
		{
			echo('<tr>');
				echo('<td>');
					echo('Flash:');
				echo('</td>');
				echo('<td>');
					echo($exif_tab['Flash']);
				echo('</td>');
			echo('</tr>');
		}		

	echo('</table>');

	echo('</div>');

?>
