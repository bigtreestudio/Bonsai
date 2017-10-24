(function( $ ) {function icontains( elem, text ) { return ( elem.textContent || elem.innerText || $( elem ).text() || "").toLowerCase().indexOf( (text || "").toLowerCase() ) > -1;} $.expr[':'].icontains = $.expr.createPseudo ? $.expr.createPseudo(function( text ) { return function( elem ) {  return icontains( elem, text );}; }) : function( elem, i, match ) {return icontains( elem, match[3] );};})( jQuery );
$(document).ready(function(){CargarXMLPaises();});

function AbrirMapa()
{
	$("#mapa_ampliado").show();
}
function CerrarMapa()
{
	$("#mapa_ampliado").hide();
}
function AbrirPais(paisprefijo)
{
	TraerNoticiaMapaPais(paisprefijo)
}
function CerrarPais()
{
	$("#mapa_cajapais").hide();
}
function TraerNoticiaMapaPais(paisprefijo)
{
	var filter = "item";
	if (paisprefijo!="")
	{
		filter += '[id='+paisprefijo+']';
	
		var finder = $(xmlCargado).find(filter);
		html='';
		finder.each(function(){
			html='<h2>'+$(this).find('pais').text()+'</h2>';
			html+='<div class="clearboth" style="font-size:5px;">&nbsp;</div>';
			html+='<span style="color:'+$(this).find('paiscolor').text()+';font-weight:bold;" class="fechatitulo">Ultima noticia</span>&nbsp;';
			html+='<b>'+$(this).find('Fecha').text()+'</b>&nbsp;'+$(this).find('Hora').text()+'HS';
			html+='<div class="clearboth">&nbsp;</div>';
			html+='<div class="mapa_cajacategoria">';
			html+='<h3 style="color:'+$(this).find('categoriacolor').text()+';font-weight:bold;">'+$(this).find('nombrecategoria').text()+'</h3>';
			html+='</div>';
			html+='<div class="mapa_cajanoticia">';
			html+='<a href="/'+$(this).find('link').text()+'" title="Ir a noticia de '+$(this).find('pais').text()+' : '+$(this).find('title').text()+'"';
			html+='<h3>'+$(this).find('title').text()+'</h3>';
			html+='</a></div>';
			html+='<div class="mapa_cerrarcajanoticia"><a href="javascript:void(0)" onclick="CerrarPais()" title="Cerrar caja de noticia">Cerrar</a></div>';
			$("#mapa_cajapais").html(html);
			$("#mapa_cajapais").show();
		});
		
	}
	else
		return false;
	return true;
}



var xmlCargado;
var totalBusqueda;
function CargarXMLPaises()
{
	$.ajax({type: 'GET', url: '/rss/ultimas_paises.xml', cache: false, 
	dataType: ($.browser.msie) ? 'text' : 'xml', 
	success: function(data){
		var xml; 
		if(typeof data == 'string'){ 
			xml = new	ActiveXObject('Microsoft.XMLDOM'); 
			xml.async = false; 
			xml.loadXML(data);
		} 
		else {
			xml = data;	
		}
		xmlCargado = xml;
		//var finder = $(xmlCargado).find("item");
		//totalBusqueda = finder.length;
		//cantPaginas = Math.round(totalBusqueda/cantPorPagina);
	}});
}
