
function Insertar(){
	var param;
	$.blockUI({ message: '<div style="font-size:20px; font-weight:bold"><img src="./images/cargando.gif" />Agregando...</h1>',baseZ: 9999999999 });
	param = $("#formalta").serialize();
	param += "&accion=1";
	EnviarDatosInsertarModificar(param,1);
	return true;
}


function Modificar(){
	var param;
	$.blockUI({ message: '<div style="font-size:20px; font-weight:bold"><img src="./images/cargando.gif" />Actualizando...</h1>',baseZ: 9999999999 });
	param = $("#formalta").serialize();
	param += "&accion=2";
	EnviarDatosInsertarModificar(param,2);
	return true;
}


function EnviarDatosInsertarModificar(param,tipo){
	$.ajax({
		type: "POST",
		url: "gcba_comunas_upd.php",
		data: param,
		dataType:"json",
		success: function(msg){
			if (msg.IsSucceed==true)
			{
				$(".msgaccionupd").html(msg.Msg);
				if (tipo==1)
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