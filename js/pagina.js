// JavaScript Document

$(document).ready(function() {
});


function CambiarImagen(imgsrc)
{

	$(".cargandoMultimedia").show();
	$.ajax({
	   data: imgsrc,
	   dataType:"html",
	   success: function(msg){
			var img = $('<img/>').attr("src",imgsrc);
			$(".primerImagen").html(img);
			$(".cargandoMultimedia").hide();
	   }
	 });
	 return false;
}

function CambiarVideo(vidsrc)
{
	$(".cargandoMultimedia").show();
	$.ajax({
	   data: vidsrc,
	   dataType:"html",
	   success: function(msg){
			var video = $('<iframe/>').attr("src",vidsrc).attr("width",640).attr("height",380).attr("frameborder",0);
			$(".primerVideo").html(video);
			$(".cargandoMultimedia").hide();
	   }
	 });
	 return false;
}

function CambiarMultimedia(elemento,classMultimedia,id,tipo)
{
	$(".iconosLinks").removeClass("active");
	$(elemento+" .iconosLinks").addClass("active");
	$(".paginasMultimedia").hide();
	$(classMultimedia).show();
	LoadCarrousel(id,tipo);
	return true;
}


function LoadCarrousel(id,tipo)
{
	var carousel = $(id);
	carousel.carouFredSel({
		circular:true, 
		items : {visible : 4},		
		scroll  : {items   : "page"},
		prev    : {button  : "#multimage_prev_"+tipo, key: "left",items : 1},
		next    : {button  : "#multimage_next_"+tipo, key: "right",items : 1},
		auto	: false
	});
}

$(document).ready(function(){
	// Reiniciar el tamaño de la fuente
	var tamOriginal = $('.cuerpo').css('font-size');

	// Incrementar el tamaño de la fuente
	$(".aumFuente").click(function(){
		var tamActual = $('.cuerpo').css('font-size');
		var tamActualNum = parseFloat(tamActual, 10);
		if (tamActualNum<16)
		{
			var nuevaFuente = tamActualNum+1;
			$('.cuerpo').css('font-size', nuevaFuente);
		}
		return false;
	});
	// Disminuir el tamaño de la fuente
	$(".disFuente").click(function(){
		var tamActual = $('.cuerpo').css('font-size');
		var tamActualNum = parseFloat(tamActual, 10);
		if (tamActualNum>12)
		{
			var nuevaFuente = tamActualNum-1;
			$('.cuerpo').css('font-size', nuevaFuente);
		}
		return false;
	});
});
