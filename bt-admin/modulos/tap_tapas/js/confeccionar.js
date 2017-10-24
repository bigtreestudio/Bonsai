// JavaScript Document
//CARGA INICIAL UNA VEZ QUE TERMINE DE CARGAR EL DOCUMENTO
jQuery(document).ready(function(){
	RecargarColumnaModulosTmp();
	MovimientoZonas();
	$(".imgfondo").MoverImagenBackground();     
	

	 if (checkVersion())
	 {
		$('li').has('ul').mouseover(function(){
				$(this).children('ul').css('visibility','visible');
		}).mouseout(function(){
				$(this).children('ul').css('visibility','hidden');
		})
	  }
 

	// iPad
	var isiPad = navigator.userAgent.match(/iPad/i) != null;
	if (isiPad) $('#menu ul').addClass('no-transition');  
  
});


function getInternetExplorerVersion()
{
    var rv = -1; // Return value assumes failure.

    if (navigator.appName == 'Microsoft Internet Explorer')
    {
        var ua = navigator.userAgent;
        var re  = new RegExp("MSIE ([0-9]{1,}[\.0-9]{0,})");
        if (re.exec(ua) != null)
            rv = parseFloat( RegExp.$1 );
    }

    return rv;
}

function checkVersion()
{
    var ver = getInternetExplorerVersion();

    if ( ver > -1 )
    {
        if ( ver < 7 ) 
            return true;
    }

   return false;
}
function viewTools(idtools)
{
	$("#"+idtools).show();
}

function hideTools(idtools)
{
	$("#"+idtools).hide();
}





function AbrirEditarModulos(zonamodulocod)
{
	var param, url;
	param = "zonamodulocod="+zonamodulocod;
	$.ajax({
	   type: "POST",
	   url: "tap_tapas_module_edit.php",
	   data: param,
	   success: function(msg){
			$("#PopupModulo").dialog({	
				width: 800, 
				height: 'auto',
				position: 'top', 
				resizable: true,
				title: "Editar", 
				open: function(type, data) {$("#PopupModulo").html(msg);}
			});
	   }
	 });
	
}

function AbrirAgregarModulos(modulo,catestado)
{
	if($("#modulocod").val()==""){
		alert("Debe seleccionar un modulo")
		return false;	
	}

	var param, url;
	param = "modulocod="+modulo;
	param += "&tapacod="+$("#tapacod").val();
	param += "&catestado="+catestado;
	$.ajax({
	   type: "POST",
	   url: "tap_tapas_module_add.php",
	   data: param,
	   success: function(msg){
		   if ($('#PopupModulo').hasClass('ui-dialog-content')) {
			   $("#PopupModulo").dialog("close");
		   }
			$("#PopupModulo").dialog({	
				width: 800, 
				position: 'top', 
				resizable: true,
				title: "Editar", 
				open: function(type, data) {$("#PopupModulo").html(msg);}
			});
	   }
	 });
	
} 

function AbrirAgregarModulosAccesosDirectos(modulocod)
{

	var param, url;
	param = "modulocod="+modulocod;
	param += "&tapacod="+$("#tapacod").val();
	$.ajax({
	   type: "POST",
	   url: "tap_tapas_module_add.php",
	   data: param,
	   success: function(msg){
		   $("#PopupModulo").dialog("close");
			$("#PopupModulo").dialog({	
				width: 800, 
				position: 'top', 
				resizable: true,
				title: "Editar", 
				open: function(type, data) {$("#PopupModulo").html(msg);}
			});
	   }
	 });
	
}


function CargarNuevoModulo()
{
	var param, url;
	$.ajax({
	   type: "POST",
	   url: "tap_tapas_combo_categorias_module.php",
	   data: param,
	   success: function(msg){
			$("#PopupModulo").dialog({	
				width: 800, 
				position: 'center', 
				resizable: true,
				modal:false,
				title: "Agregar Modulo", 
				open: function(type, data) {$("#PopupModulo").html(msg);}
			});
	   }
	   
	   
	   
	 });
	 

	 
	
}



function saveModulo()
{
	switch($("#accionModulo").val())
	{
		case "1":
			GuardarModulo();
			break;
			
		case "2":
			ModificarModulo();
			break;		
	}	
}

function agregarModulo()
{
	var param, url;
	param = $("#form_tap_modules").serialize();
	param += "&tapacod="+$("#tapacod").val(); 
	$.ajax({
	   type: "POST",
	   url: "tap_tapas_module_upd.php",
	   data: param,
	   dataType:"json",
	   success: function(msg){
		   if (msg.IsSuccess)
			{
				RecargarColumnaModulosTmp();

			}else
			{
				alert(msg.Msg)
			}
	   }
	 });
	
}

function GuardarModulo()
{
	var param, url;
	param = $("#form_tap_modules").serialize();
	param += "&tapacod="+$("#tapacod").val(); 
	$.ajax({
	   type: "POST",
	   url: "tap_tapas_module_upd.php",
	   data: param,
	   dataType:"json",
	   success: function(msg){
		   if (msg.IsSuccess)
			{
				RecargarColumnaModulosTmp();
				$("#PopupModulo").dialog("close");

			}else
			{
				alert(msg.Msg)
			}
	   }
	 });
	
}

function ModificarModulo()
{
	var param, url;

	param = $("#form_tap_modules").serialize();
	$("#module_"+$("#zonamodulocod").val()).block({ 
		message: '<div style="text-align:left; font-weight:bold; background:url(images/cargando.gif) no-repeat left; padding:5px 0 5px 20px">Recargando</div>', 
		css: { border: '1px solid #202020', padding:'0 5px', width: '110px'  } 
	});
	$.ajax({
	   type: "POST",
	   url: "tap_tapas_module_upd.php",
	   data: param,
	   dataType:"json",
	   success: function(msg){

		   if (msg.IsSuccess)
			{
				ReloadModule($("#zonamodulocod").val());
				$("#PopupModulo").dialog("close");
			}else
			{
				alert(msg.Msg)
			}
	   }
	 });
	
}


function saveModuloSinRecarga()
{
	var param, url;

	param = $("#form_tap_modules").serialize();
	$("#module_"+$("#zonamodulocod").val()).block({ 
		message: '<div style="text-align:left; font-weight:bold; background:url(images/cargando.gif) no-repeat left; padding:5px 0 5px 20px">Recargando</div>', 
		css: { border: '1px solid #202020', padding:'0 5px', width: '110px'  } 
	});
	$.ajax({
	   type: "POST",
	   url: "tap_tapas_module_upd.php",
	   data: param,
	   dataType:"json",
	   success: function(msg){

		   if (msg.IsSuccess)
			{
				ReloadModule($("#zonamodulocod").val());
			}else
			{
				alert(msg.Msg)
			}
	   }
	 });
	
}

function ReloadModule(zonamodulocod)
{
	var param, url;
	param = "zonamodulocod="+zonamodulocod;
	$.ajax({
	   type: "POST",
	   url: "tap_tapas_module_recarga.php",
	   data: param,
	   dataType:"html",
	   success: function(msg){
			$("#module_"+zonamodulocod).replaceWith(msg);
			 MovimientoZonas();
			 $(".imgfondo").MoverImagenBackground();
	   }
	 });
}





function RecargarColumnaModulosTmp()
{
	var param, url;

	param = "tapacod="+$("#tapacod").val();
	$.ajax({
	   type: "POST",
	   url: "tap_tapas_module_tmp.php",
	   data: param,
	   dataType:"html",
	   success: function(msg){
		   $("#modulostmp").html(msg);
		   MovimientoZonasTemporales();
		  
	   }
	 });

}

function MovimientoZonasTemporales()
{	
	$(".zona_tmp_data").sortable(
	  { 
		tolerance: 'pointer',
		scroll: true , 
		placeholder: "placeholderzona",
		connectWith: '.zona',
		cursor: 'pointer',
		revert: 'invalid',
		opacity: 0.6
	});
	$(".zona_tmp_data").disableSelection();
}



var position_updated = true; //helper flag for sortable below
function MovimientoZonas()
{	
	$(".zona").sortable(
	  { 
		tolerance: 'pointer',
		scroll: true , 
		handle: $(".modules_move"),
		revert: 'invalid',
		connectWith: '.zona',
		cursor: 'pointer',
		placeholder: "placeholderzona",
		opacity: 0.6, 		
		update: function(event, ui) {
			if (position_updated)
			{
				var zona = $(this).attr("id").substring(8);
				var order = $(this).sortable("serialize")+"&tapacod="+$("#tapacod").val()+"&zonacod="+zona+"&accionModulo=5"; 
				order+="&width="+$("#zonacod_"+zona).width();
				var zonamodulocod = $(ui.item).attr("id").substring(7);
				ModificarOrdenZona(order,zonamodulocod);	
				$("#module_"+zonamodulocod).block({ 
					message: '<div style="text-align:left; font-weight:bold; background:url(images/cargando.gif) no-repeat left; padding:5px 0 5px 20px">Recargando</div>', 
					css: { border: '1px solid #202020', padding:'0 5px', width: '110px'  } 
				});
			}
		},
		start: function(event, ui) {
			position_updated = true;
			var zonamodulocod = $(ui.item).attr("id").substring(7);
			//var div = "<div class='moveModule'>&nbsp;</div>"
			//$("#module_"+zonamodulocod).html(div);
			$(this).sortable('refreshPositions');
		},
		cancel: function(event, ui) {
			var zonamodulocod = $(ui.item).attr("id").substring(7);
			$("#module_"+zonamodulocod).html("<div class='moveModule'>&nbsp;</div>");
			$("#module_"+zonamodulocod).block({ 
				message: '<div style="text-align:left; font-weight:bold; background:url(images/cargando.gif) no-repeat left; padding:5px 0 5px 20px">Recargando</div>', 
				css: { border: '1px solid #202020', padding:'0 5px', width: '110px'  } 
			});
			console.log($(ui.item))

			//ReloadModule(zonamodulocod)
		},
		helper: function() {
			var helper = $(this).clone(); // Untested - I create my helper using other means...
			// jquery.ui.sortable will override width of class unless we set the style explicitly.
			//console.log(helper)
			helper.css({'height': '100px'}).html("<div class='moveModule'>&nbsp;</div>");
			return helper;
		},
		receive: function(event, ui) {
			position_updated = false;
			if ($(ui.sender).attr("id")=="zona_temporal")
			{
				var modulotmpcod = $(ui.item).attr("id").substring(10);
				var zonasuperior = ui.item.parent().attr("id").substring(8);
				sourceIndex = $(ui.item).parent().children().index(ui.item);
				InsertarModuloEnZona(modulotmpcod,zonasuperior,sourceIndex+1);
			}else
			{
				var zona = $(this).attr("id").substring(8);
				var order = $(this).sortable("serialize")+"&tapacod="+$("#tapacod").val()+"&zonacod="+zona+"&accionModulo=5"; 
				var zonamodulocod = $(ui.item).attr("id").substring(7);
				order+="&width="+$("#zonacod_"+zona).width();
				ModificarOrdenZona(order,zonamodulocod);	
				$("#module_"+zonamodulocod).block({ 
					message: '<div style="text-align:left; font-weight:bold; background:url(images/cargando.gif) no-repeat left; padding:5px 0 5px 20px">Recargando</div>', 
					css: { border: '1px solid #202020', padding:'0 5px', width: '110px'  } 
				});
			}
		}			  
	});
	
	$(".zona").disableSelection();
}
function ModificarOrdenZona(param,zonamodulocod)
{	
	$.ajax({
	   type: "POST",
	   url: "tap_tapas_module_upd.php",
	   data: param,
	   dataType:"json",
	   success: function(msg){
			if (msg.IsSuccess)
			{			
				ReloadModule(zonamodulocod);
			}else
			{
				alert(msg.Msg)
			}
	   }
	 });

}



function InsertarModuloEnZona(modulotmpcod,zonasuperior,orden)
{	
	
	var param, url;
	param = "zonacod="+zonasuperior;
	param += "&tapacod="+$("#tapacod").val();
	param += "&modulotmpcod="+modulotmpcod;
	param += "&moduloorden="+orden;
	param += "&accionModulo=4";
	param +="&width="+$("#zonacod_"+zonasuperior).width();
	$("#modulotmp_"+modulotmpcod).block({ 
		message: '<div style="text-align:left; font-weight:bold; background:url(images/cargando.gif) no-repeat left; padding:5px 0 5px 20px">Agregando</div>', 
		css: { border: '1px solid #202020', padding:'0 5px', width: '110px'  } 
	});

	$.ajax({
	   type: "POST",
	   url: "tap_tapas_module_upd.php",
	   data: param,
	   dataType:"json",
	   success: function(msg){

		   if (msg.IsSuccess)
			{			
				$("#modulotmp_"+modulotmpcod).replaceWith('<div id="module_'+msg.zonamodulocod+'"></div>');
				ReloadModule(msg.zonamodulocod);

			}else
			{
				alert(msg.Msg)
			}
	   }
	 });
}
function EliminarModulos(zonamodulocod)
{	
	var param, url;
	if (!confirm("Est\u00e1 seguro que desea eliminar el m\u00f3dulo?"))
		return false;

	param = "zonamodulocod="+zonamodulocod;
	param += "&accionModulo=3";

	$.ajax({
	   type: "POST",
	   url: "tap_tapas_module_upd.php",
	   data: param,
	   dataType:"json",
	   success: function(msg){

		   if (msg.IsSuccess)
			{
				$("#module_"+zonamodulocod).fadeOut(300,function() {
			    $("#module_"+zonamodulocod).remove();
			    });				
			}else
			{
				alert(msg.Msg)
			}
	   }
	 });
}

function EliminarTmp(modulotmpcod)
{	
	if (!confirm("Esta seguro que desea eliminar?"))
		return false;

	var param, url;

	param = "modulotmpcod="+modulotmpcod;
	param += "&accionModulo=6";

	$.ajax({
	   type: "POST",
	   url: "tap_tapas_module_upd.php",
	   data: param,
	   dataType:"json",
	   success: function(msg){

		   if (msg.IsSuccess)
			{
				$("#modulotmp_"+modulotmpcod).fadeOut(300,function() {
			    $("#modulotmp_"+modulotmpcod).remove();
				 });	
			}else
			{
				alert(msg.Msg)
			}
	   }
	 });
}

var previsualizar = 0;
function Previsualizar()
{
	
	
	if (previsualizar==0)
	{
		$(".zona").css("margin","0");
		$(".zona").css("border","none");
		$(".zona").css("min-height","0");
		$(".zonascargadas").css("min-height","0");
		$(".zonascargadas").css("margin-bottom","0");
		previsualizar = 1;
	}else
	{ 
		$(".zona").css("margin","-1px");
		$(".zona").css("border","1px dashed #000");
		$(".zona").css("min-height","40px");
		$(".zonascargadas").css("min-height","60px");
		$(".zonascargadas").css("margin-bottom","20px");
		previsualizar = 0;
	}
}

function Publicar(tapacod)
{
	if (!confirm("Esta seguro que desea publicar la p\u00e1gina?"))
		return false;	
	
	$.blockUI({ message: '<h1 style="font-size:30px; color:#000; font-weight:bold; padding:5px 0; background-image:/images/cargando.gif"><img src="images/cargando.gif" style="margin:0 10px;" />Publicando p\u00e1gina...</h1>',baseZ: 9999999999999 })	
	var param, url;
	param = "tapacod="+tapacod;
	$.ajax({
	   type: "POST",
	   url: "tap_tapas_publicar.php",
	   data: param,
	   dataType:"json",
	   success: function(msg){

		   if (msg.IsSuccess)
			{
				alert("Se ha publicado correctamente.");
				$.unblockUI();	
			 	
			}else
			{
				alert(msg.Msg);
				$.unblockUI();	
			}
	   }
	 });
}


$.fn.MoverImagenBackground = function () {	
	
	var IdElement = this;
	var clicking = false;
	$(this).bind('click',function(ev){
		return false;
	});
	
	$(this).bind('mousedown',function(ev){
		clicking = true;
		var alto;
		if(typeof ev.offsetY === "undefined") {
		   var targetOffset = $(ev.target).offset();
		   ev.offsetY = ev.pageY - targetOffset.top;
		}
		var PoxYClick = ev.offsetY;

		var imgposY = $(this).bgPosition()[1];
		$(this).css("cursor","pointer");
		var img = new Image;
		img.src = $(this).css('background-image').replace(/url\(|\)$/ig, "");

		var altoimg = imgHeight(this);
		
		var altototal;
		if (altoimg>img.height)
			altototal=altoimg;
		else
			altototal=img.height;

		var alto = - altototal + $(this).height();
		
		$(this).bind('mousemove',function(e){
			if(clicking == false) return;
			
			if(typeof e.offsetY === "undefined") {
			   var targetOffset = $(e.target).offset();
			   e.offsetY = e.pageY - targetOffset.top;
			}
			
			var mouseY = imgposY + (e.offsetY - PoxYClick);
			if (mouseY<0 && mouseY>alto)
				$(this).css("background-position","center "+mouseY+"px")
			else
			{
				if (mouseY>0)
					$(this).css("background-position","center 0");
				else
					$(this).css("background-position","center "+alto+"px");
			}
		}).mouseout(function(){
			clicking = false;
			$(this).css("cursor","auto");
			ModificarModuloDataExtra(this);
    });
	
	});
	$(this).bind('mouseup',function(e){
		clicking = false;
		$(this).css("cursor","auto");
		ModificarModuloDataExtra(this);
	});

	function ModificarModuloDataExtra(element)
	{
		var param, url;
		var id = $(element).parents(".tap_modules").attr("id");
		var modulo = id.substring(7); 
		var imgposY = $(element).bgPosition()[1];
		var data;
		data = "Top="+imgposY;
		GuardarModuloDataExtra(modulo,data)
	}
	
	function GuardarModuloDataExtra(modulo,data)
	{
		var param, url;
		param = data;
		param += "&tapacod="+$("#tapacod").val(); 
		param += "&zonamodulocod="+modulo; 
		param += "&accionModulo=7";
		$.ajax({
		   type: "POST",
		   url: "tap_tapas_module_upd.php",
		   data: param,
		   dataType:"json",
		   success: function(msg){
			   if (msg.IsSuccess)
				{
				}else
				{
					alert(msg.Msg)
				}
		   }
		 });
	}
	
}
	
$.fn.bgPosition = function() {
    var bgp = "background-position";
    return $.browser.msie ? [parseInt($(this).css(bgp + "-x").split(" ")[0]), parseInt($(this).css(bgp + "-y").split(" ")[0])] : [parseInt($(this).css(bgp).split(" ")[0]), parseInt($(this).css(bgp).split(" ")[1])];
}
	
	
function imgHeight(id) {
	var height = 0;
	var path = $(id).css('background-image').replace('url', '').replace('(', '').replace(')', '').replace('"', '').replace('"', '');
	var tempImg = '<img id="tempImg" src="' + path + '"/>';
	$('body').append(tempImg); // add to DOM before </body>
	$('#tempImg').hide(); //hide image
	height = $('#tempImg').height(); //get height
	$('#tempImg').remove(); //remove from DOM
	return height;
}


function BloquearDebloquearModulo(zonamodulocod,bloquear)
{
	var param, url;
	var txt = "Bloqueando"
	if (bloquear==0)
		txt = "Desbloqueando";
	$.blockUI({ message: '<h1 style="font-size:30px; color:#000; font-weight:bold; padding:5px 0; background-image:images/cargando.gif"><img src="images/cargando.gif" style="margin:0 10px;" />'+txt+' modulo...</h1>',baseZ: 9999999999999 })	
	param = "zonamodulocod="+zonamodulocod+"&tapacod="+$("#tapacod").val()+"&accionModulo=8&bloqueo="+bloquear; 
	$.ajax({
	   type: "POST",
	   url: "tap_tapas_module_upd.php",
	   data: param,
	   dataType:"json",
	   success: function(msg){
		   if (msg.IsSuccess)
			{
				param = "zonamodulocod="+zonamodulocod;
				$.ajax({
				   type: "POST",
				   url: "tap_tapas_module_recarga.php",
				   data: param,
				   dataType:"html",
				   success: function(msg){
						$("#module_"+zonamodulocod).replaceWith(msg);
						 MovimientoZonas();
						 $(".imgfondo").MoverImagenBackground();
						 $.unblockUI();	
				   }
				 });
				
			}else
			{
				alert(msg.Msg)
			}
	   }
	 });
	
}


	
	
