// JavaScript Document
//CARGA INICIAL UNA VEZ QUE TERMINE DE CARGAR EL DOCUMENTO
function AgregarDominioMenu(menucod)
{
 if(menucod === undefined){
	id="#PopupDetalleDominioMenu_";
	menucod="";
	}else {
	id="#PopupDetalleDominioMenu_"+menucod;
	}
	
	var param, url;
	param = "menucod="+menucod;
	$.ajax({
	   type: "POST",
	   url: "tap_menu_dominio.php",
	   data: param,
	   success: function(msg){
			$(id).dialog({	
				width: 800, 
				zIndex: 999999,
				height: 'auto',
				position: 'top', 
				resizable: true,
				title: "Dominios", 
				open: function(type, data) {$(id).html(msg);}
			});
	   }
	 });
	
}

