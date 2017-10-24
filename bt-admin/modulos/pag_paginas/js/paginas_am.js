
//VARIABLE GLOBAL QUE INDICA SI ES O NO UNA NUEVA PAGINA
var nuevapagina = true;

//VARIABLE GLOBAL QUE DETECTA SI HUBO O NO UN CAMBIO PARA UTILIZAR EL GUARDADO AUTOMATICO
var existecambio = false;


//CARGA INICIAL UNA VEZ QUE TERMINE DE CARGAR EL DOCUMENTO
	//CARGAN LOS CONTADORES DE CARACTERES
	//CARGAN LOS EDITORES DE TEXTO
jQuery(document).ready(function(){

	$('#tabs, #datos_adicionales').tabs();
	
	initTextEditors();
	initTextEditorsAvanzado();
	VerificarNuevaPagina();
	
	//agregue esta restriccion por los ids que no se generan
	
	jQuery('#pagtitulo').charCount();
	updateCharCount('pagtitulo');

	jQuery('#pagsubtitulo').charCount();
	updateCharCount('pagsubtitulo');

	jQuery('#pagtitulocorto').charCount();
	updateCharCount('pagtitulocorto');

	jQuery('#pagcuerpo').wordCount();
	updateWordCount('pagcuerpo');
	
	// Handler for the new line key
	jQuery('textarea#pagcuerpo').bind('keypress', newLineKeyHandler);
	
	// Capture the pressed key for paste handler
	jQuery('textarea#pagcuerpo').bind('keypress', detectPressedKeyHandler);
	
	jQuery('#pagcopete').wordCount();
	updateWordCount('pagcopete');
	
	// Handler for the new line key
	jQuery('textarea#pagcopete').bind('keypress', newLineKeyHandler);
	
	// Capture the pressed key for paste handler
	jQuery('textarea#pagcopete').bind('keypress', detectPressedKeyHandler);
		
});

function VerificarNuevaPagina(){if ($("#paginaedit").val()==true) {nuevapagina=false; $(".states").removeClass("disabled");}}

function Guardar(id)
{
	$("#MsgGuardando").show();
	if (nuevapagina)
		AgregarPagina(id);
	else
		ModificarPagina(id);	
		
	nuevapagina = false;
}



function AgregarPagina(element)
{
	 var pagcuerpo = tinyMCE.get('pagcuerpo');
	 var pagcopete = tinyMCE.get('pagcopete');
	$("#pagcopete").val(pagcopete.getContent());
	$("#pagcuerpo").val(pagcuerpo.getContent());

	$("#MsgGuardando").show();
	param = $("#formulario").serialize(); 
	param += "&accion=1";
	param += "&paginaworkflowcod="+$(element).attr("rel");
	param += "&paginacuerpohtml="+pagcuerpo.getContent();
	param += "&paginacopetehtml="+sanitize(pagcopete.getContent());

	$(".msgaccionpagina").html("&nbsp;");
	$(".states").addClass("disabled");
	$(".states a").attr('onclick','').unbind('click');
	$(".states a").attr('href','javascript:void(0)');
	
	var param, url;
	$.ajax({
	   type: "POST",
	   url: "pag_paginas_upd.php",
	   data: param,
	   dataType:"json",
	   success: function(msg){
			if (msg.IsSuccess)
			{
				$("#codigoTablaRelacionMultimedia").val(msg.pagcod);
				$("#pagcod").val(msg.pagcod);
				$(".msgaccionpagina").html(msg.Msg);
				$("#pagestadocod").val(msg.pagestadocod);
				$("#estadonombre").html(msg.pagestadodesc);
				if (msg.cambioestado)
				{
					if (!msg.tienepermisos)
						VisualizarPagina($("#pagcod").val())
					else
						ObtenerAcciones($("#pagcod").val(),1);
				}else
				{
					ObtenerAcciones($("#pagcod").val(),1);
				}


			}else
			{
				alert(msg.Msg);
				ObtenerAcciones($("#pagcod").val(),1);
				nuevapagina = true;
			}
			$("#MsgGuardando").hide();
	   }
	});
}


function ModificarPagina(element)
{
	var pagcuerpo = tinyMCE.get('pagcuerpo');
	 var pagcopete = tinyMCE.get('pagcopete');
	$("#pagcopete").val(pagcopete.getContent());
	$("#pagcuerpo").val(pagcuerpo.getContent());


	var block = false;
	if ($(element).attr("id")!=$("#pagestadocod").val())
		block = true;
	
	if (block)
		$.blockUI({ message: '<h1 class="h1block"><img src="images/cargando.gif" /> Actualizando datos...</h1>',baseZ: 9999999999 })	



	$("#MsgGuardando").show();
	param = $("#formulario").serialize(); 
	param += "&accion=2";
	param += "&pagcod="+$("#pagcod").val();;
	param += "&paginaworkflowcod="+$(element).attr("rel");
	param += "&paginacuerpohtml="+pagcuerpo.getContent();
	param += "&paginacopetehtml="+sanitize(pagcopete.getContent());


	$(".msgaccionpagina").html("&nbsp;");
	$(".states").addClass("disabled");
	$(".states a").attr('onclick','').unbind('click');
	$(".states a").attr('href','javascript:void(0)');

	var param, url;
	$.ajax({
	   type: "POST",
	   url: "pag_paginas_upd.php",
	   data: param,
	   dataType:"json",
	   success: function(msg){
			if (msg.IsSuccess)
			{
				$(".msgaccionpagina").html(msg.Msg);
				$("#pagestadocod").val(msg.pagestadocod);
				$("#estadonombre").html(msg.pagestadodesc);

				if (msg.cambioestado)
				{
					if (!msg.tienepermisos)
						VisualizarPagina($("#pagcod").val())
					else
					{	
						ObtenerAcciones($("#pagcod").val(),1);
						if (block)
							$.unblockUI();
					}
				}else
				{
					ObtenerAcciones($("#pagcod").val(),1);
					if (block)
						$.unblockUI();
				}
			}else
			{
				ObtenerAcciones($("#pagcod").val(),1);
				alert(msg.Msg);
				$.unblockUI();	
			}
			$("#MsgGuardando").hide();
	   }
	});
}


function ObtenerAcciones(codigo,accion)
{
	var param, url;
	param = "pagcod="+codigo;
	param += "&accion="+accion;
	$.ajax({
	   type: "POST",
	   url: "pag_paginas_obtener_acciones.php",
	   data: param,
	   dataType:"html",
	   success: function(msg){ 
			$(".accionespagina").html(msg);
	   }
	});
}


function VisualizarPagina(codigo)
{
	$.blockUI({ message: '<h1 class="h1block"><img src="images/cargando.gif" /> Verficando permisos...</h1>',baseZ: 9999999999 })	
	var param, url;
	param = "pagcod="+$("#pagcod").val();
	
	$.ajax({
	   type: "POST",
	   url: "pag_paginas_visualizar_ajax.php",
	   data: param,
	   dataType:"html",
	   success: function(msg){ 
			$("#DetallePaginaAm").html(msg);
			$.unblockUI();
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
	var textObj = jQuery('textarea#pagcuerpo')[0];

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
		jQuery('textarea#pagcuerpo').insertAtCaret('\n•');
		
		updateWordCount('pagcuerpo');
		
		return false;
	}
	
	if (code == 8 || code == 46) // Backspace or Delete
	{
		jQuery('textarea#pagcuerpo').each(function () {
			
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
		
		updateWordCount('pagcuerpo');
		
		return false;
	}
	
	updateWordCount('pagcuerpo');
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
	var textObj = jQuery('textarea#pagcuerpo')[0];
	
	try { // Capture the pasted content in IE
		clipboardData.setData('text', sanitize( clipboardData.getData('text') ));
		jQuery(textObj).val( clipboardData.getData('text') );
	} catch (e) { // Capture the pasted content otherwise
		jQuery(this).val( sanitize(jQuery(this).val()) );
		jQuery(textObj).val( jQuery(this).val() );
	}
	
	updateWordCount('pagcuerpo');
	
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



