/*
Bigtree Studio
Class Multimedia.
*/




(function($){

    var MultimediaBigTree = function(element, options)
    {
        var obj = this;
		var fileupd = "mul_multimedia_general_upd.php";
        var defaults = {
			IdPrefijo: "#prefijo",
			IdCodigo: "#codigoTablaRelacionMultimedia",
			IdPopup: "#PopupMultimedia",
			Imagen: true,
			Video: true,
			Audio: true,
			Archivos:true,
			BtImagen: ".multimediaImagen",
			BtVideo: ".multimediaVideo",
			BtAudio: ".multimediaAudio",
			BtArchivo: ".multimediaArchivos",
			IdMsgAccion: ".msgaccionmultimedia",
			IdLstImagen: "#fotos",
			IdLstOrdenImagen: "#multimedia_fotos",
			ClassConnectOrdenImagen: ".sortable_multimedia_fotos",
			IdLstVideo: "#videos",
			IdLstOrdenVideo: "#multimedia_videos",
			ClassConnectOrdenVideo: ".sortable_multimedia_videos",
			IdLstAudio: "#audios",
			IdLstOrdenAudio: "#multimedia_audios",
			IdMultimedia: "",
			ClassConnectOrdenAudio: ".sortable_multimedia_audios",

			IdLstArchivos: "#files",
			IdLstOrdenArchivos: "#multimedia_archivos",
			ClassConnectOrdenArchivos: ".sortable_multimedia_archivos",

			HandleSortable: ".orden",
			TipoMultimedia:1

        };
        
        var config = $.extend(defaults, options || {});

		this.getConfig = function(){return config;};
		this.setConfig = function(variable,data){obj.getConfig().variable=data};
		
        this.Inicializate = function()
        {
			if (obj.getConfig().Imagen==true)
			{
				$( obj.getConfig().BtImagen ).click(function() {
					obj.AbrirPopupMultimedia("Cargando im\u00e1genes...","mul_multimedia_foto.php");
				});
				obj.CargarImagenes();
			}
			if (obj.getConfig().Video==true)
			{
				$( obj.getConfig().BtVideo ).click(function() {
					obj.AbrirPopupMultimedia("Cargando videos...","mul_multimedia_video.php");
				});
				obj.CargarVideos();
			}
			if (obj.getConfig().Audio==true)
			{
				$( obj.getConfig().BtAudio ).click(function() {
					obj.AbrirPopupMultimedia("Cargando audios...","mul_multimedia_audio.php");
				});
				obj.CargarAudios();
			}			
			if (obj.getConfig().Archivos==true)
			{
				$( obj.getConfig().BtArchivo ).click(function() {
					obj.AbrirPopupMultimedia("Cargando archivos...","mul_multimedia_archivo.php");
				});
				obj.CargarArchivos();
			}			
			
			return true;
        };
		
		this.CargarImagenes = function(){obj.CargarMultimedia("Cargando im\u00e1genes",obj.getConfig().IdLstImagen,1);}
		this.CargarVideos = function(){obj.CargarMultimedia("Cargando videos",obj.getConfig().IdLstVideo,2);}
		this.CargarAudios = function(){obj.CargarMultimedia("Cargando audios",obj.getConfig().IdLstAudio,3);}
		this.CargarArchivos = function(){obj.CargarMultimedia("Cargando archivos",obj.getConfig().IdLstArchivos,4);}
		
		this.CargarListadoTipo = function(tipo)
		{
			switch(tipo)
			{
				case 1:
					obj.CargarImagenes();
					break;	
				case 2:
					obj.CargarVideos();
					break;	
				case 3:
					obj.CargarAudios();
					break;	
				case 4:
					obj.CargarArchivos();
					break;	
				
			}	
			
		}


		this.CargarMultimedia = function(txt,idListado,tipo)
		{
			$("#MsgGuardando").html(txt);
			$("#MsgGuardando").show();
			var param = "prefijo="+$(obj.getConfig().IdPrefijo).val();
			param += "&codigo="+$(obj.getConfig().IdCodigo).val();
			param += "&tipo="+tipo;
		
			$.ajax({
			   type: "POST",
			   url: "mul_multimedia_general_lst.php",
			   data: param,
			   dataType:"html",
			   success: function(msg){ 
					jQuery(idListado).html(msg);
					obj.AplicarOrdenMultimedia(tipo);
					jQuery("#MsgGuardando").hide();
					jQuery("#MsgGuardando").html("Guardando...");
			   }
			});
					
		}

        this.AbrirPopupMultimedia = function(txt,archivo)
		{
			if ($(obj.getConfig().IdCodigo).val()=="")
			{
				alert("Debe guardar antes de subir un archivo multimedia");
				return false;			
			}
		
			var param;
			$("#MsgGuardando").html(txt)
			$("#MsgGuardando").show();
			$.ajax({
			   type: "POST",
			   url: archivo,
			   data: param,
			   success: function(msg){
					$($(obj.getConfig().IdPopup)).dialog({	
						height: 500, 
						width: 800, 
						zIndex: 999999999,
						position: 'center', 
						resizable: false,
						title: "Multimedia", 
						open: function(type, data) {$($(obj.getConfig().IdPopup)).html(msg);},
						beforeclose: function(event, ui) {$($(obj.getConfig().IdPopup)).html("");}
						
					});
					$("#MsgGuardando").hide();
					$("#MsgGuardando").html("Guardando...");
			   }
			 });
		}// Fin Multimedia


        this.AplicarOrdenMultimedia = function(tipoSortable)
		{
			
			var id, connect, tipo;
			switch(tipoSortable)
			{
				case 1:
					tipo = obj.getConfig().IdLstOrdenImagen;
					connect = obj.getConfig().ClassConnectOrdenImagen;
				break;	
				case 2:
					tipo = obj.getConfig().IdLstOrdenVideo;
					connect = obj.getConfig().ClassConnectOrdenVideo;
				break;	
				case 3:
					tipo = obj.getConfig().IdLstOrdenAudio;
					connect = obj.getConfig().ClassConnectOrdenAudio;
				break;	
				case 4:
					tipo = obj.getConfig().IdLstOrdenArchivos;
					connect = obj.getConfig().ClassConnectOrdenArchivos;
				break;	
				
			}
			

			$(function() {
				$(tipo).sortable(
				  { 
					tolerance: 'pointer',
					scroll: true , 
					handle: $(obj.getConfig().HandleSortable),
					connectWith: connect,
					axis: 'y',
					cursor: 'pointer',
					opacity: 0.6, 
					update: function() {
						
						var order = $(this).sortable("serialize");
						order += "&prefijo="+$(obj.getConfig().IdPrefijo).val();
						order += "&codigo="+$(obj.getConfig().IdCodigo).val();
						order += "&accion=2";

						$("#MsgGuardando").show();
						$.post(fileupd, order, function(msg){
							if (msg.IsSucceed)
								$($(obj.getConfig().IdMsgAccion)).html("&nbsp;");
							else
								alert(msg.Msg)	
							$("#MsgGuardando").hide();
						}, "json");
					}				  
			 });
			
		});// fin functio()
		
		
		}// Fin sortable Multimedia
		
		

        this.EliminarMultimedia = function(multimedia,tipo)
		{
			if (!confirm("Esta seguro que desea eliminar el archivo multimedia?"))
				return false;
		
			$("#MsgGuardando").html("Eliminando...");
			$("#MsgGuardando").show();
			var param = "prefijo="+$(obj.getConfig().IdPrefijo).val();
			param += "&codigo="+$(obj.getConfig().IdCodigo).val();
			param += "&tipo="+tipo; 
			param += "&multimediacod="+multimedia;
			param += "&accion=3";
			
			$($(obj.getConfig().IdMsgAccion)).html("&nbsp;");
			var param, url;
			$.ajax({
			   type: "POST",
			   url: fileupd,
			   data: param,
			   dataType:"json",
			   success: function(msg){ 
					if (msg.IsSucceed)
					{
						obj.CargarListadoTipo(tipo);
						$($(obj.getConfig().IdMsgAccion)).html(msg.Msg);
					}else
					{
						alert(msg.Msg);	
					}
					$("#MsgGuardando").hide();
					$("#MsgGuardando").html("Guardando...");
			   }
			});
		
		
		}// Fin eliminar Multimedia
		

		this.RelacionarMultimedia = function(txt,multimediacod,tipo)
		{
			$("#MsgGuardando").html(txt);
			$("#MsgGuardando").show();
			$.blockUI({ message: '<h1 class="h1block"><img src="images/cargando.gif" /> '+txt+'...</h1>',baseZ: 9999999999 })	
			var param = "prefijo="+$(obj.getConfig().IdPrefijo).val();
			param += "&codigo="+$(obj.getConfig().IdCodigo).val();
			param += "&tipo="+tipo; 
			param += "&multimediacod="+multimediacod; 
			param += "&accion=4";
			
			$($(obj.getConfig().IdMsgAccion)).html("&nbsp;");
			$.ajax({
			   type: "POST",
			   url: fileupd,
			   data: param,
			   dataType:"json",
			   success: function(msg){ 
					if (msg.IsSucceed)
					{
						obj.CargarListadoTipo(tipo);
						$($(obj.getConfig().IdMsgAccion)).html(msg.Msg);
					}else
					{
						alert(msg.Msg);	
					}
					$("#MsgGuardando").hide();
					$("#MsgGuardando").html("Guardando...");
					 $.unblockUI();
			   }
			});
		}



		this.SubirRelacionarMultimedia = function(txt,tipo,form)
		{
			$("#MsgGuardando").show();
			var param, url;
			$.blockUI({ message: '<h1 class="h1block"><img src="images/cargando.gif" /> '+txt+'...</h1>',baseZ: 9999999999 })	
			param = $("#"+form).serialize(); 
			param += "&prefijo="+$(obj.getConfig().IdPrefijo).val();
			param += "&codigo="+$(obj.getConfig().IdCodigo).val();
			param += "&tipo="+tipo; 
			param += "&accion=1";
			
			$($(obj.getConfig().IdMsgAccion)).html("&nbsp;");
			$.ajax({
			   type: "POST",
			   url: fileupd,
			   data: param,
			   dataType:"json",
			   success: function(msg){ 
					if (msg.IsSucceed)
					{
						$($(obj.getConfig().IdMsgAccion)).html(msg.Msg);
						$(ObjMultimedia.getConfig().IdPopup).dialog("close");
						obj.CargarListadoTipo(tipo);
					}else
					{
						alert(msg.Msg);	
					}
					$.unblockUI();
					$("#MsgGuardando").hide();
					$("#MsgGuardando").html("Guardando...");
			   }
			});
		}


		this.ModificarHome = function(multimediahome,txt,multimediacod)
		{
			$("#MsgGuardando").show();
			var param, url;
			$.blockUI({ message: '<h1 class="h1block"><img src="images/cargando.gif" /> '+txt+'...</h1>',baseZ: 9999999999 })	
			param = "prefijo="+$(obj.getConfig().IdPrefijo).val();
			param += "&codigo="+$(obj.getConfig().IdCodigo).val();
			param += "&multimediahome="+multimediahome; 
			param += "&multimediacod="+multimediacod; 
			param += "&accion=5";
			
			$($(obj.getConfig().IdMsgAccion)).html("&nbsp;");
			$.ajax({
			   type: "POST",
			   url: fileupd,
			   data: param,
			   dataType:"json",
			   success: function(msg){ 
					if (msg.IsSucceed)
					{
						$($(obj.getConfig().IdMsgAccion)).html(msg.Msg);
					}else
					{
						alert(msg.Msg);	
					}
					$.unblockUI();
					$("#MsgGuardando").hide();
					$("#MsgGuardando").html("Guardando...");
			   }
			});
		}



		this.SeleccionarSubirMultimediaPreview = function(multimediacod)
		{
			$("#MsgGuardando").show();
			var param, url;
			param = "prefijo="+$(obj.getConfig().IdPrefijo).val();
			param += "&codigo="+$(obj.getConfig().IdCodigo).val();
			param += "&multimediacod="+multimediacod; 
			param += "&accion=5";
			
			obj.AbrirPopupMultimedia("Cargando formulario...","mul_multimedia_general_preview_fotos_am.php");
		}


 	  this.AbrirPopupDominio = function(multimediacod)
		{
			var param, url;
			param = "multimediacod="+multimediacod; 
			
			$("#MsgGuardando").html("Cargando...")
			$("#MsgGuardando").show();
			$.ajax({
			   type: "POST",
			   url: "mul_multimedia_general_dominio.php",
			   data: param,
			   success: function(msg){
					$($(obj.getConfig().IdPopup)).dialog({	
						height: 100, 
						width: 800, 
						zIndex: 999999999,
						position: 'center', 
						resizable: false,
						title: "Multimedia", 
						open: function(type, data) {$($(obj.getConfig().IdPopup)).html(msg);}
					});
					$("#MsgGuardando").hide();
					$("#MsgGuardando").html("Guardando...");
			   }
			 });
		}// Fin Multimedia


		this.ModificarTitulo = function(txt,multimediacod)
		{
			$("#MsgGuardando").show();
			var param, url;
			param = "prefijo="+$(obj.getConfig().IdPrefijo).val();
			param += "&codigo="+$(obj.getConfig().IdCodigo).val();
			param += "&multimediatitulo="+$("#multimediatitulo_"+multimediacod).val(); 
			param += "&multimediacod="+multimediacod; 
			param += "&accion=6";
			
			$($(obj.getConfig().IdMsgAccion)).html("&nbsp;");
			$.ajax({
			   type: "POST",
			   url: fileupd,
			   data: param,
			   dataType:"json",
			   success: function(msg){ 
					if (msg.IsSucceed)
					{
						$($(obj.getConfig().IdMsgAccion)).html(msg.Msg);
					}else
					{
						alert(msg.Msg);	
					}
					$("#MsgGuardando").hide();
			   }
			});
		}

		this.ModificarDescripcion = function(txt,multimediacod)
		{
			$("#MsgGuardando").show();
			var param, url;
			param = "prefijo="+$(obj.getConfig().IdPrefijo).val();
			param += "&codigo="+$(obj.getConfig().IdCodigo).val();
			param += "&multimediadesc="+$("#multimediadesc_"+multimediacod).val(); 
			param += "&multimediacod="+multimediacod; 
			param += "&accion=7";
			
			$($(obj.getConfig().IdMsgAccion)).html("&nbsp;");
			$.ajax({
			   type: "POST",
			   url: fileupd,
			   data: param,
			   dataType:"json",
			   success: function(msg){ 
					if (msg.IsSucceed)
					{
						$($(obj.getConfig().IdMsgAccion)).html(msg.Msg);
					}else
					{
						alert(msg.Msg);	
					}
					$("#MsgGuardando").hide();
			   }
			});
		}


		this.RelacionarPreview = function(multimediacod,multimediacodrelacion)
		{
			$("#MsgGuardando").show();
			var param, url;
			$.blockUI({ message: '<h1 class="h1block"><img src="images/cargando.gif" /> Seleccionando preview...</h1>',baseZ: 9999999999 })	
			param = "prefijo="+$(obj.getConfig().IdPrefijo).val();
			param += "&codigo="+$(obj.getConfig().IdCodigo).val();
			param += "&multimediacod="+ObjMultimedia.IdMultimedia; 
			param += "&multimediacodRelacion="+multimediacod; 
			param += "&accion=8";
			
			$($(obj.getConfig().IdMsgAccion)).html("&nbsp;");
			$.ajax({
			   type: "POST",
			   url: fileupd,
			   data: param,
			   dataType:"json",
			   success: function(msg){ 
					if (msg.IsSucceed)
					{
						$($(obj.getConfig().IdMsgAccion)).html(msg.Msg);
						if (obj.getConfig().Video==true)
							obj.CargarVideos();
						if (obj.getConfig().Audio==true)
							obj.CargarAudios();
						if (obj.getConfig().Archivos==true)
							obj.CargarArchivos();
					}else
					{
						alert(msg.Msg);	
					}
					$.unblockUI();
					$("#MsgGuardando").hide();
					$("#MsgGuardando").html("Guardando...");
			   }
			});
		}


		this.EliminarPreview = function(multimediacod)
		{
			if (!confirm("Esta seguro que desea eliminar el preview?"))
				return false;
				
			$("#MsgGuardando").show();
			var param, url;
			$.blockUI({ message: '<h1 class="h1block"><img src="images/cargando.gif" /> Eliminando preview...</h1>',baseZ: 9999999999 })	
			param = "prefijo="+$(obj.getConfig().IdPrefijo).val();
			param += "&codigo="+$(obj.getConfig().IdCodigo).val();
			param += "&multimediacod="+multimediacod; 
			param += "&accion=9";
			
			$($(obj.getConfig().IdMsgAccion)).html("&nbsp;");
			$.ajax({
			   type: "POST",
			   url: fileupd,
			   data: param,
			   dataType:"json",
			   success: function(msg){ 
					if (msg.IsSucceed)
					{
						$($(obj.getConfig().IdMsgAccion)).html(msg.Msg);

						if (obj.getConfig().Video==true)
							obj.CargarVideos();
						if (obj.getConfig().Audio==true)
							obj.CargarAudios();
						if (obj.getConfig().Archivos==true)
							obj.CargarArchivos();
			
					}else
					{
						alert(msg.Msg);	
					}
					$.unblockUI();
					$("#MsgGuardando").hide();
					$("#MsgGuardando").html("Guardando...");
			   }
			});
		}
		
		
		this.SubirRelacionarMultimediaPreview = function(multimediacod)
		{
			$("#MsgGuardando").show();
			var param, url;
			$.blockUI({ message: '<h1 class="h1block"><img src="images/cargando.gif" /> Subiendo preview...</h1>',baseZ: 9999999999 })	
			param = $("#form_mul_multimedia_img").serialize(); 
			param += "&prefijo="+$(obj.getConfig().IdPrefijo).val();
			param += "&codigo="+$(obj.getConfig().IdCodigo).val();
			param += "&multimediacod="+ObjMultimedia.IdMultimedia; 
			param += "&accion=10";
			
			$($(obj.getConfig().IdMsgAccion)).html("&nbsp;");
			$.ajax({
			   type: "POST",
			   url: fileupd,
			   data: param,
			   dataType:"json",
			   success: function(msg){ 
					if (msg.IsSucceed)
					{
						$($(obj.getConfig().IdMsgAccion)).html(msg.Msg);
						$(ObjMultimedia.getConfig().IdPopup).dialog("close");
						if (obj.getConfig().Video==true)
							obj.CargarVideos();
						if (obj.getConfig().Audio==true)
							obj.CargarAudios();
						if (obj.getConfig().Archivos==true)
							obj.CargarArchivos();
					}else
					{
						alert(msg.Msg);	
					}
					$.unblockUI();
					$("#MsgGuardando").hide();
					$("#MsgGuardando").html("Guardando...");
			   }
			});
		}


		
    };
    
    $.fn.extend({
        multimediaBigTree: function(options)
        {
			return multimediaBigTree = new MultimediaBigTree(this, options);
        }
    });
})(jQuery);