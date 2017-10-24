/*
CLASE QUE EXTIENDE DE JQUERY
PROPOSITO: MOSTRAR EL ULTIMO POST DE CADA RSS.
EJEMPLO DE LLAMADA:
$(#idHtml).LoadBlog({file: '/xmlfront/blogs.xml'});
*/
(function($) {
	if (/1\.(0|1|2)\.(0|1|2)/.test($.fn.jquery) || /^1.1/.test($.fn.jquery)) {
		alert('blockUI requires jQuery v1.2.3 or later!  You are using v' + $.fn.jquery);
		return;
	};
	/*
		FUNCTION QUE CARGA Y MUESTRA EL XML.
	*/
	$.fn.LoadGaleria = function(options){
		var opts = $.extend({}, $.fn.LoadBlog.estruct, options);  
		if (opts.file==" ")
			return false;
		$("#blogcargando").show();
		$.fn.LoadBlog.loadxml(opts.file,this);
	};
	
	/*
		DEFINICION DE LA ESTRUCTURA DEL BLOG
	*/
	$.fn.LoadBlog.estruct = {  
		file: ' '  
	};  
	
	/*
		FUNCION QUE LEE EL BLOG
		PARAMETRO DE ENTRADA: NOMBRE DEL ARCHIVO XML QUE LEE 
							 EL OBJETO QUE INVOCA EL BLOG
	*/
	$.fn.LoadBlog.loadxml = function(archivo,el){
		$.ajax({type: 'GET', url: archivo, cache: false, 
		dataType:  'json', 
		success: function(data){
			$.fn.LoadBlog.loadblogs(data.blogs,el);
		}});


    };	
	
	/*
		FUNCION QUE LEE EL ARCHIVO XML DEL BLOG Y ARMA EL CONTENEDOR DEL MISMO
		PARAMETRO DE ENTRADA: EL OBJETO XML 
							  EL OBJETO QUE INVOCA EL BLOG
	*/
	$.fn.LoadBlog.loadblogs = function(jsonblog,el){
		var $m, img, autor, nombre, ultimopost, linkultimopos, linkblog, li, ul;

		var ul = $(document.createElement('ul')).attr('class','blog'); 
		$(jsonblog).each(function(index, value){
			img = this.ImgM;
			autor = this.Autor;
			nombre = this.Nombre;
			ultimopost = this.UltimoPostCorta;
			linkultimopost = this.UrlUltimoPost;
			linkblog = this.UrlBlog;
			var li = $(document.createElement('li')); 
			var divimagentitulo = $(document.createElement('div')).attr('class','imagentitulo').attr("style","height:45px;"); 
			var divfotoblog = $(document.createElement('div')).attr('class','fotoblog'); 
			var imgbloglinkultimopost = $(document.createElement('a')).attr('href',linkultimopost).attr('target','_blank').attr('title',ultimopost); 
			var imgblog = $(document.createElement('img')).attr('src',img).attr('alt',autor).attr('style','text-align:center'); 
			imgbloglinkultimopost.append(imgblog);
			divfotoblog.append(imgbloglinkultimopost);	
			var tituloblog = $(document.createElement('div')).attr('class','tituloblog'); 
			
			var blogultimopost = $(document.createElement('a')).attr('href',linkultimopost).attr('target','_blank').attr('title',ultimopost); 
			blogultimopost.html(ultimopost);	
			tituloblog.append(blogultimopost);	
			var cleardiv = $(document.createElement('div')).attr('style','clear:both; height:0; font-size:0'); 
			var vermasitio = $(document.createElement('div')).attr('class','vermarsitio'); 
			var linkvermas = $(document.createElement('a')).attr('href',linkblog).attr('target','_blank').attr('title',nombre); 
			linkvermas.html(autor);	
			vermasitio.append(linkvermas);	
			divimagentitulo.append(divfotoblog);	
			divimagentitulo.append(tituloblog);	
			li.append(divimagentitulo);	
			//li.append(cleardiv);	
			li.append(vermasitio);	
			ul.append(li);
			jQuery('.tituloblog').each(function(index, element) {
				jQuery(element).css('margin-top', ((jQuery(element).parent().height() - jQuery(element).height()) / 2) + 'px');
			});
			$("#blogcargando").hide();
		});
		el.append(ul);
	
		jQuery('.blog').jcarousel({
			vertical: true,
			wrap: 'circular',
			auto: 6,
			visible: 5,
			scroll : 1
		});
    };	
})(jQuery);


