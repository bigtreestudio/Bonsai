/*

Cada cambio utilizar http://www.minifyjavascript.com/ y minimizar a noticias.js
CLASE QUE EXTIENDE DE JQUERY
PROPOSITO: MOSTRAR EL ULTIMO POST DE CADA RSS.
EJEMPLO DE LLAMADA:
$(#idHtml).LoadNoticias({file: '/xmlfront/blogs.xml', cantidad:'5'});
*/
(function($) {
	if (/1\.(0|1|2)\.(0|1|2)/.test($.fn.jquery) || /^1.1/.test($.fn.jquery)) {
		alert('blockUI requires jQuery v1.2.3 or later!  You are using v' + $.fn.jquery);
		return;
	};
	/*
		FUNCTION QUE CARGA Y MUESTRA EL XML.
	*/
	$.fn.LoadNoticias = function(options){
		var opts = $.extend({}, $.fn.LoadNoticias.estruct, options);  
		if (opts.file==" ")
			return false;
		$.fn.LoadNoticias.loadxml(opts.file,opts.cantidad,this);
	};
	
	/*
		DEFINICION DE LA ESTRUCTURA DEL BLOG
	*/
	$.fn.LoadNoticias.estruct = {  
		file: ' ',
		cantidad: '5'  
	};  
	
	/*
		FUNCION QUE LEE EL BLOG
		PARAMETRO DE ENTRADA: NOMBRE DEL ARCHIVO XML QUE LEE 
							 EL OBJETO QUE INVOCA EL BLOG
	*/
	$.fn.LoadNoticias.loadxml = function(archivo,cantidad,el){
		$.ajax({type: 'GET', url: archivo, cache: false, 
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
			$.fn.LoadNoticias.CargarDatos(xml, cantidad, el);
		}});

    };	
	
	$.fn.LoadNoticias.CargarDatos = function(xmlData,cantidad,elemento){
		var li, ul;
		var finder = $(xmlData).find("item");
		var ul = $('<ul/>');
		var count = 1;
		finder.each(function(){
			var Titulo = $(this).find('title').text();
			var Link = $(this).find('link').text();
			var Fecha = $(this).find('Fecha').text();
			var Hora = $(this).find('Hora').text();
			var span = $('<span/>').attr("class","time").html(Hora);			
			var a = $('<a/>').attr("href","/"+Link).attr("title",Titulo).html(Titulo);			
			var li = $('<li/>').append(span).append(a);
			ul.append(li);
			count++;
			if (count>cantidad)
				return false;
		});
		
		elemento.html(ul);
	
    };	
})(jQuery);


