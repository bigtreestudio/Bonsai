<?php  
require('./config/include.php');

$conexion = new accesoBDLocal(SERVIDORBD,USUARIOBD,CLAVEBD);
$conexion->SeleccionBD(BASEDATOS);

// carga las constantes generales
FuncionesPHPLocal::CargarConstantes($conexion,array("roles"=>"si","sistema"=>SISTEMA));
$conexion->SetearAdmiGeneral(ADMISITE);

// arma las variables de sesion y verifica si se tiene permisos
$sesion = new Sesion($conexion,false); // Inicia session y no borra
$sesion->TienePermisos($conexion,$_SESSION['usuariocod'],$_SESSION['rolcod'],$_SERVER['PHP_SELF']);

// ve si el sistema está bloqueado
$oSistemaBloqueo = new SistemaBloqueo();
$oSistemaBloqueo->VerificarBloqueo($conexion);

$oEncabezados = new cEncabezados($conexion);
$oEncabezados->EncabezadoMenuEmergente($_SESSION['rolcod'],$_SESSION['usuariocod']);

$_SESSION['datosusuario'] = $_SESSION['busqueda'] = array();
//----------------------------------------------------------------------------------------- 	
// Inicio de pantalla
?>

<script type="text/javascript" src="js/grid.locale-es.js"></script>
<script type="text/javascript" src="js/jquery.jqGrid.min.js"></script>

<script type="text/javascript">

jQuery(document).ready(function(){
	jQuery("#ListadoUsuarios").jqGrid(
			{ 
				url:'usuarios_lst_ajax.php?rand='+Math.random(),
				datatype: "json", 
				colNames:['Código','Nombre Completo', 'Email', 'Estado','Telefono',' '], 
				colModel:[ {name:'usuariocod',index:'usuariocod', width:30, hidden:true}, 
						  {name:'usuarioapellido',index:'usuarioapellido'}, 
						  {name:'usuarioemail',index:'usuarioemail',}, 
						  {name:'usuarioestado',index:'usuarioestado', width:30, align:"center"}, 
						  {name:'usuariotel',index:'usuariotel', width:50, align:"right"},
						  {name:'edit',index:'edit', width:55, align:"center", sortable:false}
						  ], 
				rowNum:15, 
				ajaxGridOptions: {cache: false},
				rowList:[10,20,30],
				mtype: "POST",
				pager: '#pager2', 
				sortname: 'usuariocod', 
				viewrecords: true, 
				sortorder: "asc", 
				height:340,
				caption:"",
				emptyrecords: "Sin usuarios cargados.",
				ondblClickRow: function(rowid) {
					document.location.href=$("#editar_"+rowid).attr('href');
				},
				loadError : function(xhr,st,err) {
                        //alert("Type: "+st+"; Response: "+ xhr.responseText +" "+xhr.statusText + " : "+ err);
						//alert("Error al procesar los datos");
                }
			}); 
	
			$(window).bind('resize', function() {
				$("#ListadoUsuarios").setGridWidth($("#AnchoListadoUsuarios").width());
			}).trigger('resize');
			jQuery("#ListadoUsuarios").jqGrid('navGrid','#pager2',{edit:false,add:false,del:false,search:false,refresh:false});
			
			
		});

		var timeoutHnd; 
		function doSearch(ev){ 
			if(timeoutHnd) 
				clearTimeout(timeoutHnd) 
			timeoutHnd = setTimeout(gridReload,500) 
		}
		function gridReload(){ 
			var datos = $("#formbusqueda").serializeObject();
			jQuery("#ListadoUsuarios").jqGrid('setGridParam', {url:"usuarios_lst_ajax.php?rand="+Math.random(), postData: datos,page:1}).trigger("reloadGrid"); 
		} 
    
		function Resetear()
		{
			$("#usuarionombre").val("");
			$("#usuarioapellido").val("");
			$("#usuarioemail").val("");
			$("#usuariodoc").val("");
			timeoutHnd = setTimeout(gridReload,500) 
		}
	
</script>

<div class="inner-page-title" style="padding-bottom:2px;">
    <h2>Listado de usuarios</h2>
</div>
<div class="form">
    <div class="col-md-12">
        <form action="usuarios_alta.php" method="post" name="formbusqueda"  class="general_form" id="formbusqueda" >
            <div class="col-md-12">
                <div class="col-md-4">
                    <div class="col-md-3">
                        <label>Nombre:</label>
                    </div>
                    <div class="col-md-9">
                        <input name="usuarionombre" id="usuarionombre" class="form-control input-md" type="text"  onKeyDown="doSearch(arguments[0]||event)" maxlength="100" value="" />
                    </div>
                </div>
                <div class="col-md-4">
                     <div class="col-md-3">
                       <label>Apellido:</label>
                    </div>
                    <div class="col-md-9">
                        <input name="usuarioapellido" id="usuarioapellido" class="form-control input-md" type="text"  onKeyDown="doSearch(arguments[0]||event)" maxlength="100" value="" />
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="col-md-3">
                        <label>Email:</label>
                    </div>
                    <div class="col-md-9">
                        <input name="usuarioemail" id="usuarioemail" class="form-control input-md" type="text"  onKeyDown="doSearch(arguments[0]||event)" maxlength="100" value="" />
                    </div>
                </div>
            </div>
			<div class="clearboth">&nbsp;</div>

            <div class="col-md-12">
                <div class="col-md-4">
                     <div class="col-md-3">
                       <label>Estado:</label>
                    </div>
                    <div class="col-md-9">
                        <select name="usuarioestado" id="usuarioestado" class="form-control input-md" onChange="doSearch(arguments[0]||event)">
                            <option value="">Todos</option>
                            <option value="<?php  echo USUARIONUEVO?>">Nuevos</option>
                            <option value="<?php  echo USUARIOACT?>">Activos</option>
                            <option value="<?php  echo USUARIOBAJA?>">Dados de Baja</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="col-md-3">
                        <label>Documento:</label>
                    </div>
                    <div class="col-md-9">
                        <input name="usuariodoc" id="usuariodoc" class="form-control input-md" type="text"  onKeyDown="doSearch(arguments[0]||event)" maxlength="100" value="" />
                    </div>
                </div>
            </div>
			<div class="clearboth">&nbsp;</div>

            <div class="menubarra">
                <ul>
                    <li><div class="ancho_boton aire"><a class="boton verde" href="usuarios_alta.php">Nuevo usuario</a></div></li>
                    <li><div class="ancho_boton aire"><a class="boton base" href="javascript:void(0)" onClick="Resetear()">Limpiar b&uacute;squeda</a></div></li>
            	</ul>
            </div>    
        </form>
     </div>   
     <div class="clearboth">&nbsp;</div>
     <div class="ancho_10" id="AnchoListadoUsuarios">
     	<table id="ListadoUsuarios"></table>
     </div>
     <div id="pager2"></div>
     <div class="clearboth">&nbsp;</div>
</div>        
<div style="height:50px; clear:both">&nbsp;</div>


<?php  
$oEncabezados->PieMenuEmergente();
?>
