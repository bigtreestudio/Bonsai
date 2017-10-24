
//VARIABLE GLOBAL QUE INDICA SI ES O NO UNA NUEVA NOTICIA
var nuevanoticia = true;

//VARIABLE GLOBAL QUE DETECTA SI HUBO O NO UN CAMBIO PARA UTILIZAR EL GUARDADO AUTOMATICO
var existecambio = false;


//CARGA INICIAL UNA VEZ QUE TERMINE DE CARGAR EL DOCUMENTO
	//CARGAN LAS PESTAÑAS
	//CARGAN LOS CONTADORES DE CARACTERES
	//CARGAN LOS EDITORES DE TEXTO
jQuery(document).ready(function(){
	
	$("#NuevoTema").hide();
	$(".chzn-select").chosen();
	$(".chzn-select-paises").chosen();
	$(".chzn-select-categorias").chosen();
	$(".chzn-select-cateroria-rel").chosen();
	$('#tabs, #datos_adicionales').tabs();

	
	VerificarNuevaNoticia();
	initTextEditors();
	initTextEditorsAvanzado();

 //agregue esta restriccion por los ids que no se generan

		jQuery('#noticiatitulocorto').charCount();
		updateCharCount('noticiatitulocorto');
	
		jQuery('#noticiatitulo').charCount();
		updateCharCount('noticiatitulo');
	
		jQuery('#noticiavolanta').charCount();
		updateCharCount('noticiavolanta');
				
		jQuery('#noticiacopete').wordCount();
		updateWordCount('noticiacopete');
	
		jQuery('#noticiacuerpo').wordCount();
		updateWordCount('noticiacuerpo');
		
		jQuery('#noticiatags').wordCount();
		updateWordCount('noticiatags');
	
		// Handler for the new line key
		jQuery('textarea#noticiacuerpo').bind('keypress', newLineKeyHandler);
		
		// Capture the pressed key for paste handler
		jQuery('textarea#noticiacuerpo').bind('keypress', detectPressedKeyHandler);
	

	$("#noticiafecha").datepicker( {dateFormat:"dd/mm/yy"});
	
	setTimeout(function(){ 
	  $('#noticiacuerpo_tbl').css('width', '100%')
	  $('#noticiacopete_tbl').css('width', '100%')
	},500);


	
});



function VerificarNuevaNoticia(){if ($("#noticiaedit").val()==true) {nuevanoticia=false; $(".states").removeClass("disabled");}}


function CambiarEstado(state,noticiacod)
{
	if (!nuevanoticia)
	{
		$.blockUI({ message: '<h1 class="h1block"><img src="images/cargando.gif" /> Actualizando datos...</h1>',baseZ: 9999999999 })	
		$("#MsgGuardando").show();
		param = "&accion=3";
		param += "&noticiacod="+noticiacod;
		param += "&state="+state;
	
		
		$(".msgaccionnoticia").html("&nbsp;");
		var param, url;
		$.ajax({
		   type: "POST",
		   url: "not_noticias_upd.php",
		   data: param,
		   dataType:"json",
		   success: function(msg){ 
				
				if (msg.IsSucceed)
				{
					alert(msg.Msg);	
					window.location='not_noticias.php';
					$.unblockUI();
				}else
				{
					alert(msg.Msg);	
					$.unblockUI();		
				}
				$("#MsgGuardando").hide();
		   }
		});	
	}	
}



function AgregarNoticia(id)
{
	 var noticiacuerpo = tinyMCE.get('noticiacuerpo');
	 var noticiacopete = tinyMCE.get('noticiacopete');
	$("#noticiacopete").val(noticiacopete.getContent());
	$("#noticiacuerpo").val(noticiacuerpo.getContent());

	$("#MsgGuardando").show();
	param = $("#formnoticia").serialize(); 
	param += "&"+$("#formnoticiaextra").serialize();
	param += "&"+$("#formmapa").serialize(); 
	param += "&noticiaworkflowcod="+$(id).attr("rel");
	param += "&accion=1";

	$(".msgaccionnoticia").html("&nbsp;");
	$(".states").addClass("disabled");
	$(".states a").attr('onclick','').unbind('click');
	$(".states a").attr('href','javascript:void(0)');

	var param, url;
	$.ajax({
	   type: "POST",
	   url: "not_noticias_upd.php",
	   data: param,
	   dataType:"json",
	   success: function(msg){ 
			if (msg.IsSucceed)
			{
				$("#codigoTablaRelacionMultimedia").val(msg.noticiacod);
				$("#noticiacod").val(msg.noticiacod);
				$(".msgaccionnoticia").html(msg.Msg);
				$("#estadonoticia").html(msg.noticiaestadodesc);
				if (msg.cambioestado && !msg.tienepermisos)
					VisualizarNoticia($("#noticiacod").val())
				ObtenerAcciones($("#noticiacod").val(),1);
			}else
			{
				alert(msg.Msg);	
				nuevanoticia = true;
				ObtenerAcciones($("#noticiacod").val(),1);
			}
			$("#MsgGuardando").hide();
	   }
	});
}


function ModificarNoticia(element)
{
	 var noticiacuerpo = tinyMCE.get('noticiacuerpo');
	 var noticiacopete = tinyMCE.get('noticiacopete');
	$("#noticiacopete").val(noticiacopete.getContent());
	$("#noticiacuerpo").val(noticiacuerpo.getContent());

	var block = false;
	if ($(element).attr("id")!=$("#noticiaestadocod").val())
		block = true;
	
	if (block)
		$.blockUI({ message: '<h1 class="h1block"><img src="images/cargando.gif" /> Actualizando datos...</h1>',baseZ: 9999999999 })	

	$("#MsgGuardando").show();
	param = $("#formnoticia").serialize(); 
	param += "&"+$("#formnoticiaextra").serialize();
	param += "&"+$("#formmapa").serialize(); 
	param += "&accion=2";
	param += "&noticiaworkflowcod="+$(element).attr("rel");
	param += "&noticiacuerpohtml="+noticiacuerpo.getContent();
	param += "&noticiacopetehtml="+sanitize(noticiacopete.getContent());
	
	$(".msgaccionnoticia").html("&nbsp;");
	$(".states").addClass("disabled");
	$(".states a").attr('onclick','').unbind('click');
	$(".states a").attr('href','javascript:void(0)');

	var param, url;
	$.ajax({
	   type: "POST",
	   url: "not_noticias_upd.php",
	   data: param,
	   dataType:"json",
	   success: function(msg){ 
			if (msg.IsSucceed)
			{
				$(".msgaccionnoticia").html(msg.Msg);
				$("#estadonoticia").html(msg.noticiaestadodesc);
				$("#noticiaestadocod").val(msg.noticiaestadocod);
				if (msg.cambioestado && !msg.tienepermisos)
					VisualizarNoticia($("#noticiacod").val())
				ObtenerAcciones($("#noticiacod").val(),1);
				if (block)
					$.unblockUI();

			}else
			{
				alert(msg.Msg);	
				ObtenerAcciones($("#noticiacod").val(),1);
				if (block)
					$.unblockUI();
			}
			$("#MsgGuardando").hide();
	   }
	});
}


function ObtenerAcciones(codigo,accion)
{
	var param, url;
	param = "noticiacod="+codigo;
	param += "&accion="+accion;
	$.ajax({
	   type: "POST",
	   url: "not_noticias_obtener_acciones.php",
	   data: param,
	   dataType:"html",
	   success: function(msg){ 
			$(".accionesnoticia").html(msg);
	   }
	});
}


function VisualizarNoticia(codigo)
{
	$.blockUI({ message: '<h1 class="h1block"><img src="images/cargando.gif" /> Verficando permisos...</h1>',baseZ: 9999999999 })	
	var param, url;
	param = "noticiacod="+codigo;
	$.ajax({
	   type: "POST",
	   url: "not_noticias_visualizar_ajax.php",
	   data: param,
	   dataType:"html",
	   success: function(msg){ 
			$("#DetalleNoticiaAm").html(msg);
			$.unblockUI();
	   }
	});
}




function Guardar(id)
{
	$("#NuevoTema").hide();
	$("#MsgGuardando").show();
	if (nuevanoticia)
		AgregarNoticia(id);
	else
		ModificarNoticia(id);	
		
	nuevanoticia = false;
}





function SetearGuardadoAutomatico()
{
	if (!existecambio)
	{
		//setInterval("GuardarAutomatico()", 20000);
		existecambio = true;
	}
}



function GuardarAutomatico()
{
	if (existecambio)
	{
		Guardar();	
	}	
}



function CrearTemaNuevo()
{
	$("#NuevoTema").show();
}

function GuardarTema()
{

	//$.blockUI({ message: '<h1 class="h1block"><img src="images/cargando.gif" /> Actualizando datos...</h1>',baseZ: 9999999999 })	

	$("#MsgGuardando").show();
	param = "tematitulo="+$("#tematitulo").val(); 
	param += "&temacodsuperior="+$("#temacodsuperior").val(); 
	param += "&temadesc="+$("#tematitulo").val(); 
	param += "&temacolor="+$("#temacolor").val();
	param += "&accion=1";
	var param, url;
	$.ajax({
	   type: "POST",
	   url: "tem_temas_upd.php",
	   data: param,
	   dataType:"json",
	   success: function(msg){ 
			if (msg.IsSuccess)
			{
				$(".chzn-select").append('<option value="'+msg.temacod+'" selected="selected">'+$("#tematitulo").val()+'</option>');
				$(".chzn-select").trigger("liszt:updated");
				$("#NuevoTema").hide();
				$("#tematitulo").val("");

			}else
			{
				alert(msg.Msg);	
			}
			$("#MsgGuardando").hide();
	   }
	});
}


/**
 * For Jquery Listener Format the text of the textarea
 * 
 * @param event
 */
function formatTextHandler(event)
{
	var textObj = jQuery('textarea#noticiacuerpo')[0];

	jQuery(textObj).val(sanitize(jQuery(textObj).val()));

	return false;
}

/**
 * Handler to insert the format character when a new line break is inserted.
 * Also update the word count
 * 
 * @param e
 * @return
 */
function newLineKeyHandler(e)
{
	var code = (e.keyCode ? e.keyCode : e.which);
	
	if (code == 46 && e.charCode == 46)
	{
		code = 0;
	}
	
	if (code == 13) // New Line
	{
		jQuery('textarea#noticiacuerpo').insertAtCaret('\n•');
		
		updateWordCount('noticiacuerpo');
		
		return false;
	}
	
	if (code == 8 || code == 46) // Backspace or Delete
	{
		jQuery('textarea#noticiacuerpo').each(function () {
			
			if (this.selectionStart || this.selectionStart == '0')
			{
				var scrollTop = this.scrollTop;
				
				if (this.selectionStart == this.selectionEnd)
				{
					if (code == 8) // Backspace
					{
						var startPos = (this.selectionStart - 1);
						var endPos = this.selectionEnd;
					}
					else // Delete
					{
						var startPos = this.selectionStart;
						var endPos = (this.selectionEnd + 1);
					}
				}
				else
				{
					var startPos = this.selectionStart;
					var endPos = this.selectionEnd;
				}
				
				if (this.value.charAt(startPos) == '•' && this.value.charAt(startPos - 1) == "\n")
				{
					startPos = startPos - 1;
				}
				
				if (this.value.charAt(endPos - 1) == "\n" && this.value.charAt(endPos) == '•')
				{
					endPos = endPos + 1;
				}
				
				this.value = this.value.substring(0, startPos) + this.value.substring(endPos, this.value.length);
				
				this.focus();
				this.selectionStart = startPos;
				this.selectionEnd = startPos;
				this.scrollTop = scrollTop;
			}
		
		});
		
		if (this.value == '')
		{
			this.value = '•';
		}
		else if (this.value.charAt(0) == "\n")
		{
			this.value = this.value.substring(1, this.value.length);
		}
		
		updateWordCount('noticiacuerpo');
		
		return false;
	}
	
	updateWordCount('noticiacuerpo');
	return true;
}

/**
 * Handler to detect keys on keypress event Used for cross-browser compatibility
 * on input/paste event
 * 
 * @param {Event}
 *            e
 * @returns {Boolean}
 */
function detectPressedKeyHandler(e)
{
	pressedKeyCode = (e.keyCode ? e.keyCode : e.which);
	
	return true;
}

/**
 * Handler to sanitize pasted text.
 * 
 * @param event
 * @returns {Boolean}
 */
function pasteTextHandler(event)
{
	var textObj = jQuery('textarea#noticiacuerpo')[0];
	
	try { // Capture the pasted content in IE
		clipboardData.setData('text', sanitize( clipboardData.getData('text') ));
		jQuery(textObj).val( clipboardData.getData('text') );
	} catch (e) { // Capture the pasted content otherwise
		jQuery(this).val( sanitize(jQuery(this).val()) );
		jQuery(textObj).val( jQuery(this).val() );
	}
	
	updateWordCount('noticiacuerpo');
	
	return true;
}


function sendCableHandler(event)
{
	// we stop the default submit behaviour
	Event.stop(event);
	var element = event.element(); // element = input

	var trayElement = element.adjacent('input.hiddenTrayField')[0];
	var stateElement = element.adjacent('input.hiddenStateField')[0];

	sendCableForm(trayElement.value, stateElement.value);
	
	return false;
}


