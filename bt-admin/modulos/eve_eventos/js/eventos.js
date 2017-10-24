// JavaScript Document
var diainicio;
var diafin;
$(window).unload( function () { $('#calendar').fullCalendar('destroy'); } );
$(document).ready(function() {
	$('#calendar').fullCalendar({
		header: { left: 'prev,next today', center: 'title', right: 'agendaDay,agendaWeek,month'},
		allDaySlot: true,
		editable: true,
		draggable:true,
		disableResizing:true,
		selectable: true,
		selectHelper: true,
		monthNames: monthNames, 
		allDayText : TextoTodoElDia,
		monthNamesShort: monthNamesShort,
		dayNames: dayNames,
		dayNamesShort: dayNamesShort,
		buttonText: buttonText,
		axisFormat: 'HH:mm',
		timeFormat: 'H:mm{ - H:mm}',
		columnFormat: formatocolumna,
		titleFormat: formatotitulo,
		defaultView: 'agendaWeek',
		ignoreTimezone:true,
		cache: false,
		eventClick: function(calEvent, jsEvent, view) {
			if (calEvent.puedoeditar==true)
				EditarTurno(calEvent.id,$("#usuariocod").val(),medicocod);
			
			// VerTurno(calEvent.id,$("#usuariocod").val(),medicocod);
			// change the border color just for fun
			
		},	
		eventDrop : function( calEvent, dayDelta, minuteDelta, allDay, revertFunc, jsEvent, ui, view ) { 
			
			if (confirm("Esta seguro que desea cambiar el horario?"))			
				ModificarHorarioTurno( calEvent, dayDelta, minuteDelta, allDay, revertFunc, jsEvent, ui, view);
			else
				revertFunc();
		},
		loading: function(bool) {
			if (bool){ 
				$('#cargando').show();
				$.blockUI({ message: '<h1><img src="images/cargando.gif" /> Cargando eventos...</h1>'});
			}
			else {
				$('#cargando').hide();
				$.unblockUI();
			}
			
		},
		select: function(start, end, allDay, jsEvent, view) {
			//alta rapida de un evento - no paso medico
			var start = $.fullCalendar.formatDate(start,"dS MMM yyyy HH:mm");
			var end = $.fullCalendar.formatDate(end,"dS MMM yyyy HH:mm");

			if (view.name!='month')
				ValidarHorarioSeleccionDia(start, end, $("#usuariocod").val(), medicocod);
			else
				ValidarHorarioSeleccionMes(start, end, $("#usuariocod").val(), medicocod);
			
			//calendar.fullCalendar('unselect');
		},
		eventSources: [
        // your event source
        {		
			events: function(start, end, cargadatos) {
				$.ajax({
					url: 'eventos_datos_json.php',
					dataType: 'json',
					type: 'POST',
					data: {
						_ : Math.round(start.getTime() / 1000),
						start: Math.round(start.getTime() / 1000),
						end: Math.round(end.getTime() / 1000)
				   },
					success: function(s) {
						cargadatos(s);
					},
				});
			}
		}
		],
		viewDisplay: function(view){
				diainicio = $.fullCalendar.formatDate(view.start,"dS MMM yyyy HH:mm");
				diafin = $.fullCalendar.formatDate(view.end,"dS MMM yyyy HH:mm");
		},
		
		
	});
	if (seleccionodia)
		$('#calendar').fullCalendar("gotoDate",aniosel,messel,diasel);
});


function ModificarHorarioTurno( calEvent, dayDelta, minuteDelta, allDay, revertFunc, jsEvent, ui, view)
{
	var a = calEvent.start;
	var b = calEvent.end
	$.blockUI({ message: '<h1><img src="images/cargando.gif" /> Verificando horarios...</h1>'});
	var param = "medicocod="+medicocod;
	param += "&turnocod="+calEvent.id;
	param += "&start="+a;
	param += "&end="+b;
	$.ajax({
		url: 'turno_modificar_horario.php',
		dataType: 'json',
		type: 'POST',
		data: param,
		success: function(msg) {
		   	if (msg.IsSuccess==true)
		   	{
			    alert(msg.Msg);
				if (msg.accion==1)
				{
						
					
				}
			}else
			{
				alert(msg.Msg);
				revertFunc();
			}
			$.unblockUI();
		},
	});
	return true;
	
}


function ValidarHorarioSeleccionDia(start, end, usuariocod, medicocod)
{

	var param = "medicocod="+medicocod;
	param += "&consultoriocod="+$("#consultoriocodsel").val();
	param += "&start="+start;
	param += "&end="+end;
	$("#cargando").show();
	param += "&tipo=3";
	$.ajax({
	   type: "POST",
	   url: "validar_horario_turno.php",
	   data: param,
	   success: function(msg){
	     $("#cargando").hide();
		 if(msg=="")
		 	NuevoTurno(start, end, usuariocod,medicocod);
		 else	
		 	 alert(msg);
	   }
	 });
}


function ValidarHorarioSeleccionMes(start, end, usuariocod, medicocod)
{
 	//VALIDAR SI SE ENCUENTRA EL DIA DENTRO DE LOS HORARIOS
	NuevoTurno(start, end, usuariocod,medicocod);
}




function BuscarHorariosMedico(diainicio,diafin)
{
	$("#ColIzquierda").html("");
	$("#carga_datos").html("Cargando horarios del m\u00e9dico...");
	$("#carga_datos").show();
	var param, url;
	$("#cargando").show();
	param="&medicocod="+medicocod;
	param+="&consultoriocod="+$("#consultoriocodsel").val();
	//param+="&start="+Math.round(view.start.getTime() / 1000);
	//param+="&end="+Math.round(view.end.getTime() / 1000);
	param+="&start="+diainicio;
	param+="&end="+diafin;
	
	$.ajax({
	   type: "POST",
	   url: "agenda_horarios_medicos.php",
	   data: param,
	   success: function(msg){ 
	   		$("#ColIzquierda").html(msg);
			$("#cargando").hide();
			$("#carga_datos").html("");
			$("#carga_datos").hide();
	   }
	 });
}

function BuscarTurnosxEstado()
{
	$('#calendar').fullCalendar( 'refetchEvents' );
	
}

function BuscarTurnosxConsultorio()
{
	$('#calendar').fullCalendar( 'refetchEvents' );
	BuscarHorariosMedico(diainicio,diafin);

}

function AsignarNuevoTurno()
{
	NuevoTurno(hoy, hoy, $("#usuariocod").val(),medicocod);
	
}


function CargarModalPaciente(usuariocod)
{
	var param, url;
	$("#cargando").show();
	param = "usuariocod="+usuariocod;
	$.ajax({
	   type: "POST",
	   url: "agenda_paciente.php",
	   data: param,
	   success: function(msg){ 
			$.modal(msg,{onShow: function (dialog) {dialog.container.css("height", "auto"); }});
			$("#cargando").hide();
	   }
	 });
}
function VerTurno(turnocod,usuario, medicocod)
{
	var param, url;
	$("#cargando").show();
	param = "turnocod="+turnocod;
	param+="&usuariocod="+usuario;
	param+="&medicocod="+medicocod;

	$.ajax({
	   type: "POST",
	   url: "turno_ver.php",
	   data: param,
	   success: function(msg){ 
			//$.modal(msg,{containerCss: {width: 850, height: 500}});
			$("#divturno").html(msg).dialog({height: 500, width: 850, /*show: 'drop', hide: 'fold',*/  position: 'center',title: "Visualizar turno de atenci\u00f3n"});
			$("#cargando").hide();
	   }
	 });
}


function NuevoTurno(start, end, usuario, medicocod)
{
	var param, url;
	$("#cargando").show();
	param = "start="+start;
	param+="&end="+end;
	param+="&usuariocod="+usuario;
	param+="&medicocod="+medicocod;
	param+="&consultoriocod="+$("#consultoriocodsel").val();

	$.ajax({
	   type: "POST",
	   url: "turno_alta.php",
	   data: param,
	   success: function(msg){ 
			//$.modal(msg,{containerCss: {width: 850, height: 600}});
			$("#divturno").html(msg).dialog({height: 540, width: 850, /*show: 'drop', hide: 'fold',*/ position: 'center',title: "Nuevo turno de atenci\u00f3n", close: function(){$("#divbusquedaturno").dialog("close");}
});
			$("#cargando").hide();
	   }
	 });
}



function EditarTurno(turnocod, usuario, medicocod)
{
	var param, url;
	$("#cargando").show();
	param = "turnocod="+turnocod;
	param+="&usuariocod="+usuario;
	param+="&medicocod="+medicocod;
	
	$.ajax({
	   type: "POST",
	   url: "turno_alta.php",
	   data: param,
	   success: function(msg){ 
			//$.modal(msg,{containerCss: {width: 850, height: 600}});
			$("#divturno").html(msg).dialog({height: 530, width: 850, /*show: 'drop', hide: 'fold',*/ position: 'center',title: "Modificar turno de atenci\u00f3n", close: function(){$("#divbusquedaturno").dialog("close");}});
			$("#cargando").hide();
	   }
	 });
}


function validarHorarioTurno(id,medicocod)
{
	$(id).html("");
	$("#MsgLoading").html("Verificando horarios de atenci\u00f3n...");
	$("#Loading").show();
	var param = "medicocod="+medicocod;
	param+= "&consultoriocod="+$("#consultoriocod").val();
	param += "&diainicio="+$("#diainicio").val();
	param += "&diafin="+$("#diafin").val();
	param += "&horainicio="+$("#horainicio").val();
	param += "&minutosinicio="+$("#minutosinicio").val();
	param += "&horafin="+$("#horafin").val();
	param += "&minutosfin="+$("#minutosfin").val();
	param += "&accion="+$("#accion").val();
	//alert(param);
	ValidarHorariosMedico(param,1,id);
	
	return true;
}

function ValidarHorariosMedico(param,tipo,id)
{
	$("#cargando").show();
	param += "&tipo="+tipo;
	$.ajax({
	   type: "POST",
	   url: "validar_horario_turno.php",
	   data: param,
	   success: function(msg){
		 $(id).html(msg);
		 $("#Loading").hide();
		 $("#cargando").hide();
		 if(msg=="")
		 	ValidarTurnosIngresados(param,2,id);
	   }
	 });

	return true;
}


function ValidarTurnosIngresados(param,tipo,id)
{
	$("#MsgLoading").html("Verificando turnos en el horario seleccionado...");
	$("#Loading").show();
	$("#cargando").show();
	param += "&tipo="+tipo;
	$.ajax({
	   type: "POST",
	   url: "validar_horario_turno.php",
	   data: param,
	   success: function(msg){
		 $(id).html(msg);
		 $("#Loading").hide();
		 $("#cargando").hide();
	   }
	 });

	return true;
}


function ValidarJs()
{
	if ($("#turnotipocod").val()=="")
	{
		alert("Debe seleccionar un tipo de turno");
		$("#turnotipocod").focus();
		return false;	
	}	
	if ($("#turnotipocod").val()=="4")
	{
		if ($("#pacientenombre").val()=="")
		{
			alert("Debe ingresar un nombre de paciente");
			$("#pacientenombre").focus();
			return false;	
		}	
		if ($("#pacienteapellido").val()=="")
		{
			alert("Debe ingresar un apellido de paciente");
			$("#pacienteapellido").focus();
			return false;	
		}	
		/*
		if ($("#pacientetelefono").val()=="")
		{
			alert("Debe ingresar un telefono de paciente");
			$("#pacientetelefono").focus();
			return false;	
		}	
		*/
		if ($("#tipodocumentocod").val()=="")
		{
			alert("Debe seleccionar un tipo de documento");
			$("#tipodocumentocod").focus();
			return false;	
		}	
		if ($("#pacientedoc").val()=="")
		{
			alert("Debe ingresar un n\u00famero de documento");
			$("#pacientedoc").focus();
			return false;	
		}	
	}else
	{
		if ($("#pacientecod").val()=="")
		{
			alert("Debe seleccionar un paciente");
			$("#pacientecod").focus();
			return false;	
		}	
	}
	if ($("#turnomotivo").val()=="")
	{
		alert("Debe ingresar un motivo de turno");
		$("#turnomotivo").focus();
		return false;	
	}	
	return true;
}




function ModificarInsertarNuevoTurno()
{
	if (!ValidarJs())
		return false;
	$("#MsgLoading").html("Guardando datos...");
	$("#Loading").show();
	var param, url;
	$("#cargando").show();
	param = $("#formnuevoturno").serialize();	
	$.ajax({
	   type: "POST",
	   url: "turno_alta_upd.php",
	   data: param,
	   dataType:"json",
	   success: function(msg){
		    //alert(msg)
			$("#Loading").hide();
		   	if (msg.IsSuccess==true)
		   	{
			    alert(msg.Msg);
			    //$.modal.close(); 
				$("#divturno").dialog("close"); 
			    $('#calendar').fullCalendar( 'refetchEvents' );
			}else
			{
				alert(msg.Msg);
			}
		 	$("#cargando").hide();
	   }
	 });
}

function EliminarTurno()
{
	if (!confirm("Est\u00e1 seguro que desea eliminar el turno?"))
		return false;
		
	$("#MsgLoading").html("Eliminando turno...");
	$("#Loading").show();
	var param, url;
	$("#cargando").show();
	param = $("#formnuevoturno").serialize();	
	$.ajax({
	   type: "POST",
	   url: "turno_alta_del.php",
	   data: param,
	   dataType:"json",
	   success: function(msg){
			$("#Loading").hide();
		   	if (msg.IsSuccess==true)
		   	{
			    alert(msg.Msg);
			    //$.modal.close();  
				$("#divturno").dialog("close"); 
			    $('#calendar').fullCalendar( 'refetchEvents' );
			}else
			{
				alert(msg.Msg);
			}
		 	$("#cargando").hide();
	   }
	 });
}



function CargarModalBusquedaPaciente()
{
	var param, url;
	$("#cargando").show();
	param="&medicocod="+medicocod;
	$.ajax({
	   type: "POST",
	   url: "turno_paciente_buscar.php",
	   data: param,
	   success: function(msg){ 
			$("#divbusquedaturno").html(msg).dialog({height: 500, width: 550, position: 'right',title: "Buscar Paciente"});
			$("#cargando").hide();
	   }
	 });
}


function SeleccionarPaciente(codigo)
{
	
	var param, url;
	$("#cargando").show();
	var param = "medicocod="+medicocod;
	param+= "&pacientecod="+codigo;
	$.ajax({
	   type: "POST",
	   url: "turnos_datos_paciente.php",
	   data: param,
	   dataType:"json",
	   success: function(msg){
			$("#Loading").hide();
			
		   	if (msg.IsSuccess==true)
		   	{
				$("#tiponombre").html(msg.pacientenombre);
				$("#tipoapellido").html(msg.pacienteapellido);
				$("#tipotelefono").html(msg.pacientetelefono);
				$("#tipopaciente").html(msg.tipodocumentocod);
				$("#dnipaciente").html(msg.pacientedoc);
				$("#CoberturaPaciente").html(msg.coberturanombre);
				$("#PlanPaciente").html(msg.coberturaplannombre);
				$("#pacientecod").val(msg.pacientecod);
				
				$("#divbusquedaturno").dialog("close"); 
			}else
			{
				alert(msg.Msg);
			}
		 	$("#cargando").hide();
	   }
	 });

	
}



function DebloquearCargarDatos()
{
	$("#PacienteNuevo").show();
	$("#PacienteSeleccionado").hide();
}


function BloquearCargarDatos()
{
	$("#PacienteSeleccionado").show();
	$("#PacienteNuevo").hide();
}


function BloquearDesbloquearCargarDatos(tipo)
{
		
	if (tipo==1)
		DebloquearCargarDatos()
	else
		BloquearCargarDatos()
}

function SeleccionCargaDatos(valor,tienepaciente)
{
	if (valor==4 || tienepaciente==0)
		BloquearDesbloquearCargarDatos(1);
	else
		BloquearDesbloquearCargarDatos(0);
		
	if (valor==0)
	{
		$("#turnomotivo").addClass("inputdisabled");
		$("#turnomotivo").prop("disabled",true);
		$("#turnoobservaciones").addClass("inputdisabled");
		$("#turnoobservaciones").prop("disabled",true);
		$("#PacienteSeleccionado").hide();
		$("#PacienteNuevo").hide();
	}else
	{
		$("#turnomotivo").removeClass("inputdisabled");
		$("#turnomotivo").prop("disabled",false);
		$("#turnoobservaciones").removeClass("inputdisabled");
		$("#turnoobservaciones").prop("disabled",false);
	}
	
}



function VerTurnoRepetir(valor)
{
	if (valor.checked==true)
	{
		$("#FrecuenciaTurno").show();
		
	}else
	{
		$("#FrecuenciaTurno").hide();
		
	}
	
}



function CargarCoberturaPlan()
{
	var param, url;
	$("#cargando").show();
	$.ajax({
	   type: "POST",
	   url: "coberturas_plan_buscar.php",
	   data: param,
	   success: function(msg){ 
			$("#divcoberturaplan").html(msg).dialog({height: 450, width: 650, position: 'right',title: "Buscar Cobertura"});
			$("#cargando").hide();
	   }
	 });
}


function SeleccionarCoberturaPlan(cobertura,plan,coberturacod,coberturaplancod)
{
	$("#Cobertura").html(cobertura);
	$("#Plan").html(plan);
	$("#coberturacod").val(coberturacod);
	$("#coberturaplancod").val(coberturaplancod);	
	$("#divcoberturaplan").dialog("close"); 
}

/*HORARIOS TRABAJO*/
function CargarHorarioTrabajo(view)
{
	/*
	$.ajax({
		url: 'agenda_working.php',
		dataType: 'json',
		success: function(data){
			if(view.name=='agendaWeek')
				selectWorkTime(data, duracionminutos, horariominimo, horariomaximo, false);
			else if(view.name=='agendaDay')
				selectDayWorkTime(data, duracionminutos, horariominimo, horariomaximo, view, false);
		}
	});*/
}


/*
function selectDayWorkTime(timeArray, slotMinutes, minTime, maxTime, viewObject, showAtHolidays){
    var dayname;
    $('.fc-content').find('.fc-view-agendaWeek').find('.fc-agenda-body')
    .children('.fc-work-time').remove();
    $('.fc-content').find('.fc-view-agendaDay')
    .find('.fc-work-time-day').removeClass('fc-work-time-day');
    switch(viewObject.start.getDay()){
        case 1: dayname='mon'; break;
        case 2: dayname='tue'; break;
        case 3: dayname='wed'; break;
        case 4: dayname='thu'; break;
        case 5: dayname='fri'; break;
        case 6: dayname='sat'; break;
        case 0: dayname='sun'; break;
    }
	
    for(var day in timeArray){
		
        if(day == dayname){
			
            if($('.fc-content').find('.fc-view-agendaDay').find('.fc-'+day).attr('class').search('fc-holiday') == -1 || showAtHolidays){
                var startBefore = 0;
                var endBefore = timeArray[day][0] / (60 * slotMinutes) - (minTime * 60) / slotMinutes;
                var startAfter = timeArray[day][1] / (60 * slotMinutes) - (minTime * 60) / slotMinutes;
                var endAfter = (maxTime - minTime) * 60 / slotMinutes - 1;
                for(startBefore; startBefore < endBefore; startBefore++){
                    $('.fc-view-agendaDay').find('.fc-slot'+startBefore).find('div').addClass('fc-work-time-day');
                }
                for(startAfter; startAfter <= endAfter; startAfter++){
                    $('.fc-view-agendaDay').find('.fc-slot'+startAfter).find('div').addClass('fc-work-time-day');
                }
            }
        }
    }
}



function selectWorkTime(timeArray, slotMinutes, minTime, maxTime, showAtHolidays){
    for(var day in timeArray){
        var startBefore = 0;
        var endBefore = timeArray[day][0] / (60 * slotMinutes) - (minTime * 60) / slotMinutes;
        var startAfter = timeArray[day][1] / (60 * slotMinutes) - (minTime * 60) / slotMinutes;
        var endAfter = (maxTime - minTime) * 60 / slotMinutes - 1;
        if(startBefore > endBefore) endBefore = startBefore;
        if(startAfter > endAfter) startAfter = endAfter;
		
        try{
            selectCell(startBefore, endBefore, 'fc-'+day, 'fc-work-time', false, showAtHolidays);
            selectCell(startAfter, endAfter, 'fc-'+day, 'fc-work-time', true, showAtHolidays);
        }
        catch(e){
            continue;
        }
    }
}


function selectCell2(startRowNo, endRowNo, collClass, cellClass, closeGap, showAtHolidays){
    $('.fc-content').find('.fc-view-agendaWeek').find('.fc-agenda-body').children('.'+cellClass+''+startRowNo+''+collClass).remove();
    $('.fc-content').find('.fc-view-agendaDay').find('.fc-work-time-day').removeClass('fc-work-time-day');
    if($('.fc-content').find('.fc-view-agendaWeek').find('.'+collClass).attr('class').search('fc-holiday') == -1 || showAtHolidays){
        var width = $('.fc-content').find('.fc-view-agendaWeek').find('.'+collClass+':last').width();
        var height = 0;
        if(closeGap && (startRowNo != endRowNo)){
            height = $('.fc-content').find('.fc-view-agendaWeek').find('.fc-slot'+ startRowNo).height();
        }
		
        $('.fc-view-agendaWeek').find('.fc-agenda-body').prepend('<div class="'+cellClass+' '+ ''+cellClass+''+startRowNo+''+collClass+'"></div>');
        $('.'+cellClass).width(width - 2);
        height += $('.fc-content').find('.fc-view-agendaWeek').find('.fc-slot'+ endRowNo).position().top - $('.fc-content').find('.fc-view-agendaWeek').find('.fc-slot'+ startRowNo).position().top;
        $('.'+cellClass+''+startRowNo+''+collClass).height(height);
        $('.'+cellClass+''+startRowNo+''+collClass).css('margin-top',$('.fc-content').find('.fc-view-agendaWeek').find('.fc-slot'+ startRowNo).position().top);
        $('.'+cellClass+''+startRowNo+''+collClass).css('margin-left',$('.fc-content').find('.fc-view-agendaWeek').find('.'+collClass+':last').offset().left - width / 2);
    }
}


function selectCell(startRowNo, endRowNo, collClass, cellClass, closeGap, showAtHolidays){
    $('.fc-content').find('.fc-view-agendaWeek').find('.fc-agenda-body').children('.'+cellClass+''+startRowNo+''+collClass).remove();
    $('.fc-content').find('.fc-view-agendaDay').find('.fc-work-time-day').removeClass('fc-work-time-day');
    if($('.fc-content').find('.fc-view-agendaWeek').find('.'+collClass).attr('class').search('fc-holiday') == -1 || showAtHolidays){
        var width = $('.fc-content').find('.fc-view-agendaWeek').find('.'+collClass+':last').width();
        var height = 0;
        if(closeGap && (startRowNo != endRowNo)){
            height = $('.fc-content').find('.fc-view-agendaWeek').find('.fc-slot'+ startRowNo).height();
        }
		alert(cellClass+''+startRowNo+''+collClass)
        $('.fc-view-agendaWeek').find('.fc-agenda-body').prepend('<div class="'+cellClass+' '+ ''+cellClass+''+startRowNo+''+collClass+'"></div>');
        $('.'+cellClass).width(width - 2);
        height += $('.fc-content').find('.fc-view-agendaWeek').find('.fc-slot'+ endRowNo).position().top - $('.fc-content').find('.fc-view-agendaWeek').find('.fc-slot'+ startRowNo).position().top;
        $('.'+cellClass+''+startRowNo+''+collClass).height(height);
        $('.'+cellClass+''+startRowNo+''+collClass).css('margin-top',$('.fc-content').find('.fc-view-agendaWeek').find('.fc-slot'+ startRowNo).position().top);
        $('.'+cellClass+''+startRowNo+''+collClass).css('margin-left',$('.fc-content').find('.fc-view-agendaWeek').find('.'+collClass+':last').offset().left - width / 2);
    }
}

function print_calendar()
{
    // save current calendar width
    w = $('#calendar').css('width');

    // prepare calendar for printing
    $('#calendar').css('width', '6.5in');
    $('.fc-header').hide();  
    $('#calendar').fullCalendar('render');

    window.print();

    // return calendar to original, delay so the print processes the correct width
    window.setTimeout(function() {
        $('.fc-header').show();
        $('#calendar').css('width', w);
        $('#calendar').fullCalendar('render');
    }, 1000);
}

*/