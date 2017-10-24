var timeEffect = 700;

jQuery(document).ready(function(){
	
	$.localScroll();
	
	
	$(document).on('click', '.shortcut.eje', function (e) {
		e.preventDefault();
		var parentSection = $(this).parents("section");
		var eje = $(this).data("id");
		$(parentSection).find(".contentWindow").html($(".loadSite").html());
		$(parentSection).find(".home_page_move").animate({
			opacity: '0'
		}, timeEffect);
		$(parentSection).find(".message-window").show().animate({
			opacity: '1'
		}, timeEffect, function() {
			$(this).addClass("posrelative");
			$(parentSection).find(".home_page_move").hide();
			if (eje!="")
				LoadObjetivosxEje(this,eje)
		});
	});
	
	
	$(document).on('click', '.tag .shortcut', function (e) {
		e.preventDefault();
		var parentSection = $(this).parents("section");
		var tag = $(this).data("id");
		$(parentSection).find(".contentWindow").html($(".loadSite").html());
		$(parentSection).find(".home_page_move").animate({
			opacity: '0'
		}, timeEffect);
		$(parentSection).find(".message-window").show().animate({
			opacity: '1'
		}, timeEffect, function() {
			$(this).addClass("posrelative");
			$(parentSection).find(".home_page_move").hide();
			if (tag!="")
				LoadProyectosxTag(this,tag)
		});
	});
	/*

	$(document).on('click', '.jqcloud-word', function (e) {
		e.preventDefault();
		var parentSection = $(this).parents("section");
		var tagCompleto = $(this).find("a").attr("href");
		var tag = tagCompleto.substring(5);
		$(parentSection).find(".contentWindow").html($(".loadSite").html());
		
		$(parentSection).find(".home_page_move").animate({
			opacity: '0'
		}, timeEffect);
		$(parentSection).find(".message-window").show().animate({
			opacity: '1'
		}, timeEffect, function() {
			$(this).addClass("posrelative");
			$(parentSection).find(".home_page_move").hide();
			if (tag!="")
				LoadProyectosxTag(parentSection,tag)
				
		});
    });
	*/
	

	$(document).on('click', '.closeWindow', function (e) {
		e.preventDefault();
		var parentSection = $(this).parents("section");
		$(parentSection).find(".contentWindow").html("");
		
		$(parentSection).find(".message-window").removeClass("posrelative");
		$(parentSection).find(".home_page_move").show();
		
		$(parentSection).find(".message-window").animate({
			opacity: '0'
		}, timeEffect, function() {
			$(this).hide();
		});
		$(parentSection).find(".home_page_move").animate({
			opacity: '1'
		}, timeEffect);
    });
	

	$( ".map-item" )
	  .mouseenter(function() {
		var Comuna = $(this).data("id");
		$("#comuna_"+Comuna).addClass("seleccionado");
	  })
	  .mouseleave(function() {
		var Comuna = $(this).data("id");
		$("#comuna_"+Comuna).removeClass("seleccionado");
	  });


	$( ".lst-mapa-item" )
	  .mouseenter(function() {
		var Comuna = $(this).data("id");
		$(".mapa-comuna-"+Comuna).css({'fill':"#fcda59",'stroke-width':"1",'display': "block"});
	  })
	  .mouseleave(function() {
		var Comuna = $(this).data("id");
		$(".mapa-comuna-"+Comuna).removeAttr('style');
		$(".mapa-comuna-"+Comuna).css({'stroke-width':"3",'display': "block"});
	  });
  
  /*
	$( ".lst-mapa-item" )
	  .mouseenter(function() {
		var Comuna = $(this).data("id");
		$(".mapa-comuna-"+Comuna).css({'fill':"#fcda59",'stroke-width':"1",'display': "block"});
	  })
	  .mouseleave(function() {
		var Comuna = $(this).data("id");
		$(".mapa-comuna-"+Comuna).removeAttr('style');
		$(".mapa-comuna-"+Comuna).css({'stroke-width':"1",'display': "block"});
	  });*/
	  
	$( ".item-barrio" )
	  .mouseenter(function() {
		var Barrio = $(this).data("id");
		$(".barrio-"+Barrio).css({'fill':"#fcda59",'stroke-width':"1",'display': "block"});
	  })
	  .mouseleave(function() {
		var Barrio = $(this).data("id");
		$(".barrio-"+Barrio).removeAttr('style');
		$(".barrio-"+Barrio).css({'stroke-width':"1",'display': "block"});
	  });
	  
	
	$( ".barrio" )
	  .mouseenter(function() {
		var Barrio = $(this).data("id");
		$(".item-barrio-"+Barrio).addClass("seleccionado");
		$(".item-barrio-"+Barrio).parents(".mapa-item-barrios").addClass("selected");
	  })
	  .mouseleave(function() {
		var Barrio = $(this).data("id");
		$(".item-barrio-"+Barrio).removeClass("seleccionado");
		$(".item-barrio-"+Barrio).parents(".mapa-item-barrios").removeClass("selected");
	  });
	  
	$(document).on('click', '.barrio', function (e) {
		e.preventDefault();
		var parentSection = $(this).parents("section");
		var Barrio = $(this).data("id");
		$(parentSection).find(".contentWindow").html($(".loadSite").html());
		
		$(parentSection).find(".home_page_move").animate({
			opacity: '0'
		}, timeEffect);
		$(parentSection).find(".message-window").show().animate({
			opacity: '1'
		}, timeEffect, function() {
			$(this).addClass("posrelative");
			$(parentSection).find(".home_page_move").hide();
			if (Barrio!="")
				LoadProyectosxBarrio(parentSection,Barrio)
				
		});
    });
	  
	$(document).on('click', '.item-barrio', function (e) {
		e.preventDefault();
		var parentSection = $(this).parents("section");
		var Barrio = $(this).data("id");
		$(parentSection).find(".contentWindow").html($(".loadSite").html());
		
		$(parentSection).find(".home_page_move").animate({
			opacity: '0'
		}, timeEffect);
		$(parentSection).find(".message-window").show().animate({
			opacity: '1'
		}, timeEffect, function() {
			$(this).addClass("posrelative");
			$(parentSection).find(".home_page_move").hide();
			if (Barrio!="")
				LoadProyectosxBarrio(parentSection,Barrio)
				
		});
    });
	  
	  
  
	$(document).on('click', '.map-item', function (e) {
		e.preventDefault();
		var parentSection = $(this).parents("section");
		var Comuna = $(this).data("id");
		$(parentSection).find(".contentWindow").html($(".loadSite").html());
		
		$(parentSection).find(".home_page_move").animate({
			opacity: '0'
		}, timeEffect);
		$(parentSection).find(".message-window").show().animate({
			opacity: '1'
		}, timeEffect, function() {
			$(this).addClass("posrelative");
			$(parentSection).find(".home_page_move").hide();
			if (Comuna!="")
				LoadProyectosxComuna(parentSection,Comuna)
				
		});
    });
	
	$(document).on('click', '.lst-mapa-item', function (e) {
		e.preventDefault();
		var parentSection = $(this).parents("section");
		var Comuna = $(this).data("id");
		$(parentSection).find(".contentWindow").html($(".loadSite").html());
		
		$(parentSection).find(".home_page_move").animate({
			opacity: '0'
		}, timeEffect);
		$(parentSection).find(".message-window").show().animate({
			opacity: '1'
		}, timeEffect, function() {
			$(this).addClass("posrelative");
			$(parentSection).find(".home_page_move").hide();
			if (Comuna!="")
				LoadProyectosxComuna(parentSection,Comuna)
				
		});
    });
	
	
	

});



function LoadProyectosxEje(parentSection,eje)
{
	
	var param = "eje="+eje; 
	$.ajax({
	   type: "POST",
	   url: "/planes/proyectos",
	   data: param,
	   dataType:"html",
	   success: function(msg){
			$(parentSection).find(".contentWindow").html(msg);
			$("html, body").animate({ scrollTop: parentSection.position().top }, 1000);
	   }
	 });
}

function LoadObjetivosxEje(obj,eje)
{
	var parentSection = $(obj).parents("section");
	var param = "eje="+eje; 
	$.ajax({
	   type: "POST",
	   url: "/planes/objetivos",
	   data: param,
	   dataType:"html",
	   success: function(msg){
			$(parentSection).find(".contentWindow").html(msg);
			$("html, body").animate({ scrollTop: parentSection.position().top }, 1000);
	   }
	 });
}


function LoadProyectosxComuna(parentSection,numero)
{
	var param = "comuna="+numero; 
	$.ajax({
	   type: "POST",
	   url: "/planes/comunas",
	   data: param,
	   dataType:"html",
	   success: function(msg){
			$(parentSection).find(".contentWindow").html(msg);
			$("html, body").animate({ scrollTop: parentSection.position().top }, 1000);
	   }
	 });
}


function LoadProyectosxBarrio(parentSection,codigo)
{
	var param = "barrio="+codigo; 
	$.ajax({
	   type: "POST",
	   url: "/planes/barrios",
	   data: param,
	   dataType:"html",
	   success: function(msg){
			$(parentSection).find(".contentWindow").html(msg);
			$("html, body").animate({ scrollTop: parentSection.position().top }, 1000);
	   }
	 });
}


function LoadProyectosxObjetivo(obj,objetivo,eje)
{
	var parentSection = $(obj).parents("section");
	$(parentSection).find(".contentWindow").html($(".loadSite").html());
	var param = "objetivo="+objetivo; 
	param += "&eje="+eje; 
	$.ajax({
	   type: "POST",
	   url: "/planes/proyectos",
	   data: param,
	   dataType:"html",
	   success: function(msg){
			$(parentSection).find(".contentWindow").html(msg);
			$("html, body").animate({ scrollTop: parentSection.position().top }, 1000);
	   }
	 });
}


function LoadProyectosxTag(obj,tag)
{
	var parentSection = $(obj).parents("section");
	var param = "tag="+tag; 
	$.ajax({
	   type: "POST",
	   url: "/planes/tags/proyectos",
	   data: param,
	   dataType:"html",
	   success: function(msg){
			$(parentSection).find(".contentWindow").html(msg);
			$("html, body").animate({ scrollTop: parentSection.position().top }, 1000);
	   }
	 });
}

function BuscarProyectos(obj,page)
{
	var parentSection = $(obj).parents("section");
	var param = "eje="+$("#eje").val(); 
	param += "&comunacod="+$("#comunacod").val(); 
	param += "&nombre="+$("#nombre").val(); 
	param += "&pagina="+page; 
	param += "&tag="+$("#tag").val(); 
	$.ajax({
	   type: "POST",
	   url: "/planes/proyectos/lst",
	   data: param,
	   dataType:"html",
	   success: function(msg){
			$(parentSection).find(".detallePlanesProyectosLst .lst-overflow").animate({
			   scrollTop: 0
			}, 'slow', function() {
					$(parentSection).find(".detallePlanesProyectosLst").html(msg);
				  }			
			);
			
	   }
	 });
	
	
	
}


function OpenProject(id)
{
	
	var param = ""; 
	$.ajax({
	   type: "POST",
	   url: "/planes/proyectos/"+id,
	   data: param,
	   dataType:"html",
	   success: function(msg){
		    $("#ModalData .modal-body").html(msg);
			$("#ModalData").modal();
			
	   }
	 });
	
}

