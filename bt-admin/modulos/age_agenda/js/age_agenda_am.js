jQuery(document).ready(function(){
	initTextEditors();
	$("#agendafdesde").datepicker( {dateFormat:"dd/mm/yy"});
	$("#agendafhasta").datepicker( {dateFormat:"dd/mm/yy"});


	$('#tabs').tabs();
	
});
	

function InsertarEvento()
{
	var param;
	 var agendaobservaciones = tinyMCE.get('agendaobservaciones');
	$("#agendaobservaciones").val(agendaobservaciones.getContent());
	 var agendabajada = tinyMCE.get('agendabajada');
	$("#agendabajada").val(agendabajada.getContent());
	$.blockUI({ message: '<div style="font-size:20px; font-weight:bold"><img src="./images/cargando.gif" />Agregando evento...</h1>',baseZ: 9999999999 })	
	param = $("#formnuevoevento").serialize();
	param += "&accion=1";
	EnviarDatos(param);
	
	return true;
}
//va al upd con la accion 2
function ModificarEvento()
{

	 var agendaobservaciones = tinyMCE.get('agendaobservaciones');
	$("#agendaobservaciones").val(agendaobservaciones.getContent());
	 var agendabajada = tinyMCE.get('agendabajada');
	$("#agendabajada").val(agendabajada.getContent());
	$.blockUI({ message: '<div style="font-size:20px; font-weight:bold"><img src="./images/cargando.gif" />Actualizando evento...</h1>',baseZ: 9999999999 })	
	param = $("#formnuevoevento").serialize();
	param += "&accion=2";
	
	EnviarDatos(param);
	
	return true;
}



function EnviarDatos(param)
{
	$.ajax({
	   type: "POST",
	   url: "age_agenda_alta_upd.php",
	   data: param,
	   dataType:"json",
	   success: function(msg){
		if (msg.IsSuccess==true)
		{
			window.location=msg.header;
			$.unblockUI();	
		}
		 else
		{
			alert(msg.Msg);	 
			$.unblockUI();	
		}
		 
	   }
	   
	 });
}

