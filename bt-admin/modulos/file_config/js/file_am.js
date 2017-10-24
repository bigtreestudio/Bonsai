
function Actualizar()
{
	if (!confirm("Esta seguro que desea actualizar el archivo, recuerde que no podra deshacer la accrion?"))
		return false;
		
	$.blockUI({ message: '<h1 class="h1block"><img src="images/cargando.gif" /> Actualizando archivo...</h1>',baseZ: 9999999999 })	
	$("#MsgGuardando").show();
	var param, url;
	param = $("#formfile").serialize();
	$.ajax({
	   type: "POST",
	   url: "fil_config_upd.php",
	   data: param,
	   dataType:"json",
	   success: function(msg){
			if (msg.IsSuccess==true)
			{
				alert("Se ha actualizado el archivo correctamente");
				$.unblockUI();
			}else
			{
				alert(msg.Msg);
				$.unblockUI();
			}
			$("#MsgGuardando").hide();
	   }
	});		
	return true;
}	

