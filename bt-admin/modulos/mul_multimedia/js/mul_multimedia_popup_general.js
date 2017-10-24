// JavaScript Document
function DialogClose(){$(ObjMultimedia.getConfig().IdPopup).dialog("close");}
function EliminarMultimedia(multimediacod,tipo){ObjMultimedia.EliminarMultimedia(multimediacod,tipo);}
function SeleccionarImagenMultimedia(multimediacod){ObjMultimedia.RelacionarMultimedia("seleccionado im\u00e1gen",multimediacod,1);}
function SeleccionarVideoMultimedia(multimediacod){ObjMultimedia.RelacionarMultimedia("seleccionado video",multimediacod,2);}
function SeleccionarAudioMultimedia(multimediacod){ObjMultimedia.RelacionarMultimedia("seleccionado audio",multimediacod,3);}
function SeleccionarArchivoMultimedia(multimediacod){ObjMultimedia.RelacionarMultimedia("seleccionado archivo",multimediacod,4);}
function GuardarImagen(){ObjMultimedia.SubirRelacionarMultimedia("Subiendo im\u00e1gen",1,"multimediaformulario");}
function GuardarVideo(){ObjMultimedia.SubirRelacionarMultimedia("Subiendo video",2,"multimediaformulario");}
function GuardarAudio(){ObjMultimedia.SubirRelacionarMultimedia("Subiendo audio",3,"multimediaformulario");}
function GuardarArchivo(){ObjMultimedia.SubirRelacionarMultimedia("Subiendo archivo",4,"multimediaformulario");}
function MultimediaSoloHome(multimediacod){	var multimediahome=0;if ($("#enhome_"+multimediacod).is(':checked')){multimediahome=1;};ObjMultimedia.ModificarHome(multimediahome,"Modificando...",multimediacod);}
function ModificarDescripcionListadoMultimedia(multimediacod){	ObjMultimedia.ModificarDescripcion("Modificando...",multimediacod);}
function ModificarTituloListadoMultimedia(multimediacod){	ObjMultimedia.ModificarTitulo("Modificando...",multimediacod);}
function SeleccionarSubirMultimediaPreview(multimediacod){ObjMultimedia.IdMultimedia=multimediacod; ObjMultimedia.SeleccionarSubirMultimediaPreview();}
function AbrirPopupDominio(multimediacod){ObjMultimedia.IdMultimedia=multimediacod; ObjMultimedia.AbrirPopupDominio(multimediacod);}
function RelacionarPreview(multimediacod){ObjMultimedia.RelacionarPreview(multimediacod); $(ObjMultimedia.getConfig().IdPopup).dialog("close");}
function EliminarPreview(multimediacod){ObjMultimedia.EliminarPreview(multimediacod); $(ObjMultimedia.getConfig().IdPopup).dialog("close");}
function GuardarImagenPreview(multimediacod){ObjMultimedia.SubirRelacionarMultimediaPreview(multimediacod);}
function GuardarVideoPropietario(){ObjMultimedia.SubirRelacionarMultimedia("Subiendo video",2,"multimediaformvidpropietario");}

