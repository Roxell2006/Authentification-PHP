$(function(){
	// le bouton #btn_form active le bouton caché submit du formulaire
	$("#btn_form").click(function(){ 
		$("#profil-form").submit(); 
	});
	
	// quand on clique sur la photo on active le bouton caché du #fileUpload
	$(".photo").click(function(){
		$("#fileUpload").click();
	}); 
	
	// Quand on change la photo de profil
	$('input[type="file"]').change(function(e){
		readURL(this);
	});
	
	// charge la photo et l'affiche dans l'élément <img>
	function readURL(input) {
		if (input.files && input.files[0]) {
			photo = 1;
			var reader = new FileReader();

			reader.onload = function (e) {
				$('.photo').attr('src', e.target.result);
			}
			reader.readAsDataURL(input.files[0]);
		}
	}
});