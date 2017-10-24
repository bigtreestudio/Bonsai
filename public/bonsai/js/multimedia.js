$(document).ready(function(){
	CargarIsotope('#container_videos');
	CargarIsotope('#container');
	FiltrarDatos();
	$( "#icon-multimedia" ).click(function() {
	  $( "#icon-multimedia" ).removeClass( "icon-multimedia" );
	  $( "#icon-camara" ).removeClass( "icon-camara-seleccionado" );
	   $( "#icon-camara" ).addClass( "icon-camara" );
	   $( "#icon-multimedia" ).addClass( "icon-multimedia-seleccionado" );
	});
	$( "#icon-camara" ).click(function() {
	  $( "#icon-camara" ).removeClass( "icon-camara" );
	  $( "#icon-multimedia" ).removeClass( "icon-multimedia-seleccionado" );
	  $( "#icon-multimedia" ).addClass( "icon-multimedia" );
	   $( "#icon-camara" ).addClass( "icon-camara-seleccionado" );
	});
});


function CargarIsotope(id)
{
   var $container = $(id);
   $container.isotope({
        itemSelector: '.item',
        getSortData : {
          number : function( $elem ) {
            return parseInt( $elem.find('.number').text(), 10 );
          }
        }
    });
}


function FiltrarDatosxVideoImagen(tipo){

	if(tipo==1){
		$("#container").show();
		$("#container_videos").hide();	
		CargarIsotope('#container');
	}else{
		$("#container").hide();
		$("#container_videos").show();	
		CargarIsotope('#container_videos')
	}
	
	return true;
}
function FiltrarDatos(){
	var pais = "", categoria = "", selector = "", 
	arreglo = new Array, $container = $('#container'),$container_videos = $('#container_videos');
	
	if ($("#pais").val()!="")
		pais = ".pais_"+$("#pais").val();
	if ($("#categoria").val()!="")
		categoria = ".categoria_"+$("#categoria").val();

	if ($("#categoria").val()!="")
		arreglo.push(categoria);
	if ($("#pais").val()!="")
		arreglo.push(pais);
		
    if (arreglo.length>0)
		selector = arreglo.join('');
	else
		selector = "*";

	$container.isotope({ filter: selector });
	$container_videos.isotope({ filter: selector });

	return false;
}


function FiltrarDatosSort(){

	var $container = $('#container');
	if ($("#visita").val()!="")
		$container.isotope({ sortBy : 'number',sortAscending : false });
	else
		$container.isotope({ sortBy : 'original-order',sortAscending : true });

	return true;
  }
  
 