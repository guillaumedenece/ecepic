var add_picture = 0;

$('#add_picture').on('click', 

	function()
	{
		if(add_picture == 0)
		{
			$('#form_ajout_photo').fadeIn();
			$('#background_add_picture').fadeIn();
			$('#form_ajout_album').hide();
			add_picture = 1;
		}

		else
		{
			$('#form_ajout_photo').fadeOut();
			$('#background_add_picture').fadeOut();
			$('#form_ajout_album').hide();
			add_picture = 0;
		}
	}
);

$('#mon_compte').on('click',

	function()
	{
		if(add_picture == 0)
		{
			$('#form_ajout_album').fadeIn();
			$('#background_add_picture').fadeIn();
			$('#form_ajout_picture').hide();
			add_picture = 1;
		}

		else
		{
			$('#form_ajout_album').fadeOut();
			$('#background_add_picture').fadeOut();
			$('#form_ajout_picture').hide();
			add_picture = 0;
		}
	}
);

function testEchap(e)
{
	var keynum;

	if(window.event) // IE
	  {
	  	keynum = e.keyCode;
	  }

	else if(e.which) // Netscape/Firefox/Opera
	  {
	  	keynum = e.which;
	  }

	 if(keynum == 27 && add_picture == 1)
	 {
	 	$('#form_ajout_album').fadeOut();
		$('#background_add_picture').fadeOut();
		$('#form_ajout_photo').fadeOut();
		add_picture = 0;
	 }
};

var actuel_exif = -1;

function afficher_exif(photo)
{
	if(actuel_exif == photo)
	{
		$('#exif' + photo + '').fadeOut();
		actuel_exif = -1;
	}

	else
	{
		$('#exif' + photo + '').fadeIn();
		$('#exif' + actuel_exif + '').fadeOut();
		actuel_exif = photo;
	}
}