
function ActualizarDatos()
{
	confirm ("Esta seguro que desea actualizar los datos?");
	$("#MsgGuardando").show();
	var param, url;
	param = $("#formanalytics").serialize();
	$.ajax({
	   type: "POST",
	   url: "goo_analytics_upd.php",
	   data: param,
	   dataType:"json",
	   success: function(msg){			
			if (msg.IsSuccess==true)
			{
				alert(msg.Msg);
				CargarProfiles();
			}else
			{
				alert(msg.Msg);
			}
			$("#MsgGuardando").hide();
	   }
	});		
	return true;
}



function ActualizarProfile()
{
	$("#MsgGuardando").show();
	var param, url;
	param = $("#formprofilesanalytics").serialize();
	$.ajax({
	   type: "POST",
	   url: "goo_analytics_upd.php",
	   data: param,
	   dataType:"json",
	   success: function(msg){			
			if (msg.IsSuccess==true)
			{
				alert(msg.Msg);
			}else
			{
				alert(msg.Msg);
			}
			$("#MsgGuardando").hide();
	   }
	});		
	return true;
}


function CargarProfiles()
{
	var param, url;
	$("#profiles").html("");
	param = "";
	$.blockUI({ message: '<h1 class="h1block"><img src="images/cargando.gif" /> Cargando profiles...</h1>',baseZ: 9999999999 })	
	$.ajax({
	   type: "POST",
	   url: "goo_analytics_lst_ajax.php",
	   data: param,
	   dataType:"html",
	   success: function(msg){
			$("#profiles").html(msg);
			$.unblockUI();
	   }
	});		
	return true;
		 
}	

