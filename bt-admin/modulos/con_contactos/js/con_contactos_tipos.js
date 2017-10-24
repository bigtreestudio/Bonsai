jQuery(document).ready(function(){
	
	ListarTiposFormularios();			
});

	var timeoutHnd; 
function doSearch(ev){ 
	if(timeoutHnd) 
		clearTimeout(timeoutHnd) 
	timeoutHnd = setTimeout(gridReload,500) 
}
function gridReload(){ 
	var datos = $("#formbusqueda").serializeObject();
	jQuery("#ListarTiposFormularios").jqGrid('setGridParam', {url:"con_formularios_tipos_lst_ajax.php?rand="+Math.random(), postData: datos,page:1}).trigger("reloadGrid"); 
} 


	
function Resetear()
{
	timeoutHnd = setTimeout(gridReload,500) 
}


function ListarTiposFormularios()
{
	var datos = $("#formbusqueda").serializeObject();
	jQuery("#ListarTiposFormularios").jqGrid(
	{ 
				url:'con_formularios_tipos_lst_ajax.php?rand='+Math.random(),
				postData: datos,
				datatype: "json", 
				colNames:['COD','Nombre','Editar','Eliminar'], 
				colModel:[ {name:'formulariotipocod',index:'formulariotipocod', width:20, align:"center", hidden:true}, 
						  {name:'formulariotipodesc',index:'formulariotipodesc'},
						  {name:'edit',index:'edit', width:15, align:"center", sortable:false},
						  {name:'del',index:'del', width:15, align:"center", sortable:false}	
						  ], 
				rowNum:20, 
				ajaxGridOptions: {cache: false},
				rowList:[20,40,60],
				mtype: "POST",
				pager: '#pager2', 
				sortname: 'formulariotipodesc', 
				viewrecords: true, 
				sortorder: "asc", 
				height:440,
				caption:"",
				emptyrecords: "Sin tipos de formularios para mostrar.",
				/*
				ondblClickRow: function(rowid) {
					document.location.href=$("#editar_"+rowid).attr('href');
				},*/
				loadError : function(xhr,st,err) {
                      // alert("Type: "+st+"; Response: "+ xhr.responseText +" "+xhr.statusText + " : "+ err);
						//alert("Error al procesar los datos");
                },
					
				
			}); 
	
			$(window).bind('resize', function() {
				$("#ListarTiposFormularios").setGridWidth($("#LstTiposFormularios").width());
			}).trigger('resize');
				jQuery("#ListarTiposFormularios").jqGrid('navGrid','#pager2',{edit:false,add:false,del:false,search:false,refresh:false});
}



function FormTipoFormulario(modif,formulariotipocod)
{
	var param, url;
	$("#cargando").show();
	if (modif)
		param = "formulariotipocod="+formulariotipocod;
	
	$.ajax({
   type: "POST",
   url: "con_formularios_tipos_am.php",
   data: param,
   success: function(msg){
		$("#Popup").dialog({	
			height: 200, 
			width: 550,
			zIndex: 999999999, 
			position: 'center', 
			modal:false,
			title: "Tipo de Formulario", 
			open: function(type, data) {$("#Popup").html(msg); }
		});
		$("#cargando").hide();
   
	   }
	 
	 }
	 
	 );

	return true;
}

function AltaTipoFormulario()
{
	FormTipoFormulario(0,0);
	return true;
}
function EditarTipoFormulario(formulariotipocod)
{
	FormTipoFormulario(2,formulariotipocod);
	return true;
}





function EnviarDatos(param)
{
	$.blockUI({ message: '<h1 class="h1block"><img src="images/cargando.gif" />Guardando datos...</h1>',baseZ: 9999999999 })	
	$.ajax({
	   type: "POST",
	   url: "con_formularios_tipos_upd.php",
	   data: param,
	   dataType:"json",
	   success: function(msg){
		if (msg.IsSuccess==true)
		{
			gridReload();
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

function Insertar()
{
	
	var param;
	param = $("#formtiposformularios").serialize();
	param += "&accion=1";
	
	EnviarDatos(param);
	
	return true;
}


function Modificar()
{
	var param;
	param = $("#formtiposformularios").serialize();
	param += "&accion=2";
	EnviarDatos(param);
	
	return true;
}


function EliminarTipoFormulario(formulariotipocod)
{
	var param;
	if (!confirm("Est\u00e1 seguro que desea eliminar el tipo de formulario?"))
		return false;
	param = "formulariotipocod="+formulariotipocod;
	param += "&accion=3";
	EnviarDatos(param);

	return true;
}

