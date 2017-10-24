var count = 0;
function MasResultados(catdominio,pagcod){
	$(".centerpaginado, .botoninferior").remove();
	$(".cargandoresultados").show();
	var paginair = catdominio+"/r"+pagcod;

	param = "encabezado=0";$.ajax({type: "POST",url: paginair,
	data: param,success: function(msg){	
			$("#Paginado").remove().fadeOut();
			$('.noticias_lst').append('<div style="display:none" id="post'+count+'">'+msg+'</div>');
			$("#post"+count).fadeIn();
			count++;
			}});
	return true;
}



function Autocomplete()
{
	var cache = {};
	$( "#qFinder" ).autocomplete({
		minLength: 1,
		source: function( request, response ) {
			var term = request.term;
			if ( term in cache ) {response( cache[ term ] );return;	}
			$.getJSON( "/search.php", request, function( data, status, xhr ) {cache[ term ] = data;	response( data );});
		}
	}).data( "autocomplete" )._renderItem = function( ul, item ) {
            var term = this.term,
			formattedLabel = item.label.replace(new RegExp('(' + term + ')', 'ig'), function ($1, match) {return '<strong>' + match + '</strong>';});
            return $( "<li></li>" ).data( "item.autocomplete", item ).append( "<a>" + formattedLabel + "</a>" ).appendTo( ul );
	};
}



function CargarComboCiudad(id)
{
	var param = "provinciacod="+$("#provinciacod").val();
	CargarCombo(param,2,id);
	return true;
}

function CargarCombo(param,tipo,id)
{
	$("#cargando").show();
	param += "&tipo="+tipo;
	$.ajax({
	   type: "POST",
	   url: "combo_ajax.php",
	   data: param,
	   success: function(msg){
		 $(id).html(msg);
		 $("#cargando").hide();
	   }
	 });

	return true;
}




/**
 * abre una ventana de dialogo con twiter
 * @titulo <string>
 * @newsLink <string>
 */
function twitterDialog( titulo, newsLink ){
    var windowWidth = 450;
    var leftPostion = Math.round($(window).width() / 2) - Math.round(windowWidth / 2);
    window.open("http://twitter.com/home?status=" + encodeURI(titulo) + " + " + newsLink, "","status=0, width="+windowWidth+"px, height=250px, left="+leftPostion+"px, top=200px" );
}



/**
 * abre una ventana de dialogo con facebook
 * @link <string>
 */
function facebookDialog( title, link )
{
    var windowWidth = 600;
    var leftPostion = Math.round($(window).width() / 2) - Math.round(windowWidth / 2);
    var facebookUrl = "http://www.facebook.com/sharer.php?u=" + encodeURI(link);
    if (typeof(title) != 'undefined' && title != '')
    {
    	facebookUrl += '&t=' + encodeURI(title);
    }
    window.open(facebookUrl, "","status=0, width="+windowWidth+"px, height=250px, left="+leftPostion+"px, top=200px" );
}


/**
 * abre una ventana de dialogo con googlemas
 * @link <string>
 */
function googlePlusDialog( link )
{
    var windowWidth = 600;
    var leftPostion = Math.round($(window).width() / 2) - Math.round(windowWidth / 2);
    var googlePlusUrl = "https://plusone.google.com/_/+1/confirm?hl=es&url=" + encodeURI(link);
    window.open(googlePlusUrl, "","status=0, width="+windowWidth+"px, height=450px, left="+leftPostion+"px, top=200px" );
}




function VerVideoYoutube(idExterno,idTxt)
{
	$('#IframeVideo').attr('src', 'http://www.youtube.com/embed/'+idExterno);
	$('#textoVideo').html($("#"+idTxt).html());
}



var count = 0;
function MasResultadosAjax(catdominio,catcod,noticiacod,pagcod){
	$(".centerpaginado, .botoninferior").remove();
	$(".cargandoresultados").show();
	var paginair = catdominio+"/"+catcod+"/"+pagcod+"/";
	param = "encabezado=0&noticias="+noticiacod;$.ajax({type: "POST",
	url: paginair,
	data: param,
	success: function(msg){	
			$("#Paginado").remove().fadeOut();
			$('.notasDinamicasLoad').append('<div style="display:none" id="post'+count+'">'+msg+'</div>');
			$("#post"+count).fadeIn();
			count++;
		}});
	return true;
}

function MasResultadosSecciones(catdominio,pagcod){
	$(".centerpaginado, .botoninferior").remove();
	$(".cargandoresultados").show();
	var paginair = catdominio+"/r"+pagcod;

	param = "encabezado=0";$.ajax({type: "POST",url: paginair,
	data: param,success: function(msg){	
			$("#Paginado").remove().fadeOut();
			$('.noticiasLstHome').append('<div style="display:none" id="post'+count+'">'+msg+'</div>');
			$("#post"+count).fadeIn();
			count++;
			}});
	return true;
}
