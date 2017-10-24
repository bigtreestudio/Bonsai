jQuery(document).ready(function(){
	ListarFilas();
	ListarColumnasFilas();
});
	
	
	
function ListarColumnasFilas()
{
	var param = "graficocod="+$("#graficocod").val();
	$.ajax({
	   type: "POST",
	   url: "gra_graficos_columnas_filas_porcentajes.php",
	   data: param,
	   dataType:"html",
	   success: function(msg){
			$("#ValoresCampos").html(msg);
	   }
	   
	 });
}
	



function gridReloadFilas(){ 
	jQuery("#ListarFilas").jqGrid('setGridParam', {url:"gra_graficos_filas.php?graficocod="+$("#graficocod").val()+"rand="+Math.random(), page:1}).trigger("reloadGrid"); 
	ListarColumnasFilas();
} 



function ListarFilas()
{
	jQuery("#ListarFilas").jqGrid(
	{ 
				url:'gra_graficos_filas.php?graficocod='+$("#graficocod").val()+'rand='+Math.random(),
				datatype: "json", 
				colNames:['COD','Titulo','Color','Editar','Eliminar'], 
				colModel:[ {name:'catcod',index:'catcod', width:20, align:"center", hidden:true}, 
						  {name:'filatitulo',index:'filatitulo', sortable:false},
						  {name:'filacolor',index:'filacolor', width:15, sortable:false},
						  {name:'edit',index:'edit', width:15, align:"center", sortable:false},
						  {name:'del',index:'del', width:15, align:"center", sortable:false}	
						  ], 
				rowNum:20, 
				ajaxGridOptions: {cache: false},
				rowList:[20,40,60],
				mtype: "POST",
				sortname: 'graficocod', 
				viewrecords: true, 
				sortorder: "asc", 
				height:140,
				caption:"",
				emptyrecords: "Sin filas para mostrar.",
				loadError : function(xhr,st,err) {
                       alert("Type: "+st+"; Response: "+ xhr.responseText +" "+xhr.statusText + " : "+ err);
						alert("Error al procesar los datos");
                },
					
				
			}); 
	
			$(window).bind('resize', function() {
				$("#ListarFilas").setGridWidth($("#LstFilas").width());
			}).trigger('resize');
				jQuery("#ListarFilas").jqGrid('navGrid',{edit:false,add:false,del:false,search:false,refresh:false});

			jQuery("#ListarFilas").jqGrid('sortableRows', 
			 { cursor: 'move',items: '.jqgrow:not(.unsortable)',
			   update : function(e,ui) {
				   var neworder = $("#ListarFilas").jqGrid("getDataIDs");
				   ReordenarFilas(neworder);
			   }}
			 );	
}





function FormEjeY(modif,filacod)
{
	$.blockUI({ message: '<h1 class="h1block"><img src="images/cargando.gif" /> Cargando formulario...</h1>',baseZ: 9999999999 })	
	var param, url;
	param = "graficocod="+$("#graficocod").val();
	if (modif)
		param += "&filacod="+filacod;
	$.ajax({
	   type: "POST",
	   url: "gra_graficos_filas_am.php",
	   data: param,
	   success: function(msg){
			$("#Popup").dialog({	
				height: 300, 
				width: 550, 
				position: 'center', 
				modal:true,
				title: "Series del grafico", 
				open: function(type, data) {$("#Popup").html(msg); $.unblockUI();}
			});

	   }
	 });

	return true;
}



function AgregarEjeY()
{
	FormEjeY(0,0);
	return true;
}

function ModificarEjeY(filacod)
{
	FormEjeY(1,filacod);
	return true;
}


function DialogClose()
{
	 $("#Popup").dialog("close"); 
}



/*
//FUNCIONES PARA UPD DE LOS GRAFICOS
*/

function EnviarDatosGrafico(param)
{
	$.blockUI({ message: '<h1 class="h1block"><img src="images/cargando.gif" />Guardando datos...</h1>',baseZ: 2000 })	
	$.ajax({
	   type: "POST",
	   url: "gra_graficos_upd.php",
	   data: param,
	   dataType:"json",
	   success: function(msg){
		if (msg.IsSuccess==true)
		{
			PrevisualizarGrafico();
			alert(msg.Msg);
			$("#Popup").dialog("close"); 
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


function ActualizarGrafico()
{
	param = $("#formulariografico").serialize();
	param += "&accion=2";
	EnviarDatosGrafico(param);
	
	return true;
}




/*
//FUNCIONES PARA UPD DE LOS DATOS DE LAS FILAS.
*/


function ReordenarFilas(orden)
{
	$("#MsgGuardando").show();
	 
	param  = "orden="+orden; 
	param += "&graficocod="+$("#graficocod").val(); 
	param += "&accion=4";
	var param, url;
	$.ajax({
	   type: "POST",
	   url: "gra_graficos_filas_upd.php",
	   data: param,
	   dataType:"json",
	   success: function(msg){ 
			if (msg.IsSuccess)
			{
				ListarColumnasFilas();
				PrevisualizarGrafico();
			}else
			{
				alert(msg.Msg);	
			}
			$("#MsgGuardando").hide();
	   }
	});
	
}

function EnviarDatosFila(param)
{
	$.blockUI({ message: '<h1 class="h1block"><img src="images/cargando.gif" />Guardando datos...</h1>',baseZ: 2000 })	
	$.ajax({
	   type: "POST",
	   url: "gra_graficos_filas_upd.php",
	   data: param,
	   dataType:"json",
	   success: function(msg){
		if (msg.IsSuccess==true)
		{
			gridReloadFilas();
			PrevisualizarGrafico();
			alert(msg.Msg);
			$("#Popup").dialog("close"); 
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


function InsertarFila()
{
	var param;
	if (!ValidarJsFila())
		return false;

	param = $("#formulariofilas").serialize();
	param += "&accion=1";
	EnviarDatosFila(param);
	
	return true;
}

	
function ValidarJsFila()
{
	if ($("#filatitulo").val()=="")
	{
		alert("Debe ingresar un titulo");
		$("#filatitulo").focus();
		return false;
	}

	return true;
}


function ModificarFila()
{
	var param;
	if (!ValidarJsFila())
		return false;

	param = $("#formulariofilas").serialize();
	param += "&accion=2";
	EnviarDatosFila(param);
	
	return true;
}


function EliminarFila(filacod)
{
	var param;
	if (!confirm("Est\u00e1 seguro que desea eliminar la fila?"))
		return false;
	param = "filacod="+filacod;
	param += "&graficocod="+$("#graficocod").val();
	param += "&accion=3";
	EnviarDatosFila(param);

	return true;
}



function GuardarDatosValores(fila,idvalor)
{
	var param;
	param = "filacod="+fila;
	param += "&graficocod="+$("#graficocod").val();
	param += "&valor="+$(idvalor).val();
	param += "&accion=2";
	$("#MsgGuardando").show();

	$.ajax({
	   type: "POST",
	   url: "gra_graficos_valores.php",
	   data: param,
	   dataType:"json",
	   success: function(msg){
			if (msg.IsSuccess==true)
			{
				$("#MsgGuardando").hide();
				
			}
			 else
			{
				alert(msg.Msg);	 
				$("#MsgGuardando").hide();
			}
	   }
	 });
}


var chart;
function PrevisualizarGrafico()
{
	$("#GraficoPrevisualizar").html('<div style="text-align:center"><h1 class="h1block"><img src="images/cargando.gif" />Previsualizando grafico...</h1></div>');
	var param = "graficocod="+$("#graficocod").val()+"&rand="+Math.random();
	$.ajax({
	   type: "POST",
	   url: "gra_graficos_previsualizar_porcentajes.php",
	   data: param,
	   dataType:"html",
	   success: function(msg){
			$("#GraficoPrevisualizar").html(msg);
	   }
	   
	 });
}
	


function VerOpcionesFlotar()
{
	if($("#graficofilaflota").val()==1)
	{
		$("#ValoresFlotar").show();
	}else
	{
		$("#ValoresFlotar").hide();
	}
}
	






