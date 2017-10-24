$(document).ready(function() {

	$("#MsgGuardando").html("Cargando datos Analytics...");
	$("#MsgGuardando").show();
	var param, url;
	$.ajax({
	   type: "POST",
	   url: "ingreso_analytics.php",
	   data: param,
	   dataType:"html",
	   success: function(msg){ 
			$("#reportesGoogleAnalytics").html(msg);
			$("#MsgGuardando").hide();
			$("#MsgGuardando").html("Guardando...");
	   }
	});

});
