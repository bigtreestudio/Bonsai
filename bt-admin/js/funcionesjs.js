// Sniffer based on http://www.mozilla.org/docs/web-developer/sniffer/browser_type.html

var uagent    = navigator.userAgent.toLowerCase();
var is_safari = ( (uagent.indexOf('safari') != -1) || (navigator.vendor == "Apple Computer, Inc.") );
var is_ie     = ( (uagent.indexOf('msie') != -1) && (!is_opera) && (!is_safari) && (!is_webtv) );
var is_ie4    = ( (is_ie) && (uagent.indexOf("msie 4.") != -1) );
var is_moz    = (navigator.product == 'Gecko');
var is_ns     = ( (uagent.indexOf('compatible') == -1) && (uagent.indexOf('mozilla') != -1) && (!is_opera) && (!is_webtv) && (!is_safari) );
var is_ns4    = ( (is_ns) && (parseInt(navigator.appVersion) == 4) );
var is_opera  = (uagent.indexOf('opera') != -1);
var is_kon    = (uagent.indexOf('konqueror') != -1);
var is_webtv  = (uagent.indexOf('webtv') != -1);

var is_win    =  ( (uagent.indexOf("win") != -1) || (uagent.indexOf("16bit") !=- 1) );
var is_mac    = ( (uagent.indexOf("mac") != -1) || (navigator.vendor == "Apple Computer, Inc.") );
var ua_vers   = parseInt(navigator.appVersion);


//----------------------------------------------------------------------------------------- 

function GoToURL() {
  var i, args=arguments; 
  document.ReturnValue = false;
  for (i=0; i<(args.length-1); i+=2) eval(args[i]+".location='"+args[i+1]+"'");
}

//----------------------------------------------------------------------------------------- 

function ValidarFecha(caja)
{ 
   if (caja)
   {  
      borrar = caja;
      if ((caja.substr(2,1) == "/") && (caja.substr(5,1) == "/"))
      {      
         for (i=0; i<10; i++)
	     {	
            if (((caja.substr(i,1)<"0") || (caja.substr(i,1)>"9")) && (i != 2) && (i != 5))
			{
               borrar = '';
               break;  
			}  
         }
	     if (borrar)
	     { 
	        a = caja.substr(6,4);
	        c = caja.substr(6,2);
		    m = caja.substr(3,2);
		    d = caja.substr(0,2);
		    if((a < 1900) || (a > 2050) || (m < 1) || (m > 12) || (d < 1) || (d > 31))
		       borrar = '';
		    else
		    {
		       if(((a%4 != 0) || ((caja.substr(8,2)=='00') && (c%4 != 0))) && (m == 2) && (d > 28))	   
		          borrar = ''; // Año no bisiesto y es febrero y el dia es mayor a 28
			   else	
			   {
		          if ((((m == 4) || (m == 6) || (m == 9) || (m==11)) && (d>30)) || ((m==2) && (d>29)))
			         borrar = '';	      				  	 
			   }  // else
		    } // fin else
         } // if (error)
      } // if ((caja.substr(2,1) == "/") && (caja.substr(5,1) == "/"))			    			
	  else
	     borrar = '';
	  if (borrar == '')
	     return false;
	 else
	 	 return true;
   } // if (caja)   
} // FUNCION

//----------------------------------------------------------------------------------------- 

// Verifica que la Fecha Actual sea mayor a FechaInferior y menor/igual a FechaSuperior
// Formatos Fecha: dd/mm/aaaa
function FechasOrdenadas(FechaInferior,FechaActual,FechaSuperior)
{
	FIDia=FechaInferior.substr(0,2);
	FIMes=FechaInferior.substr(3,2);
	FIAnio=FechaInferior.substr(6,4);
	FADia=FechaActual.substr(0,2);
	FAMes=FechaActual.substr(3,2);
	FAAnio=FechaActual.substr(6,4);
	FSDia=FechaSuperior.substr(0,2);
	FSMes=FechaSuperior.substr(3,2);
	FSAnio=FechaSuperior.substr(6,4);
	//alert(FIDia + "/" + FIMes + "/" + FIAnio + " " + FADia + "/" + FAMes + "/" + FAAnio + " " + FSDia + "/" + FSMes + "/" + FSAnio);
	
	if(FAAnio<FIAnio)
		return false;
	else if(FAAnio==FIAnio && FAMes<FIMes)
		return false;
	else if(FAAnio==FIAnio && FAMes==FIMes && FADia<=FIDia)
		return false;
	else if(FAAnio>FSAnio)
		return false;
	else if(FAAnio==FSAnio && FAMes>FSMes)
		return false;
	else if(FAAnio==FSAnio && FAMes==FSMes && FADia>FSDia)
		return false;
	else
		return true;
}

//----------------------------------------------------------------------------------------- 

// Verifica que la CantActual sea mayor/igual a CantInferior y menor/igual a CantSuperior
// Depende del parámetro Orden
// Las cantidades deben tener punto en vez de coma decimal
function CantOrdenadas(CantInferior,CantActual,CantSuperior,Orden)
{
//	alert("cantordenadasdjs -" + CantInferior + "-" + CantActual + "-" + CantSuperior);
	if(Orden=='C')
	{
		if(CantActual<CantInferior || CantActual>CantSuperior)
			return false;
	}
	else if(Orden=='D')
	{
		if(CantActual>CantInferior || CantActual<CantSuperior)
			return false;
	}
	return true;
}

//----------------------------------------------------------------------------------------- 
// Convierte la coma en punto
// Convierte lo ingresado en un número
function convertirPunto (s)
{
  var texto="";
  var i;

  s=String(s);

  for (i = 0; i < s.length; i++)
  {
	 var c = s.charAt(i);	 
	 if(c==",")
		 	texto=texto+".";
	 else
	 		texto=texto+c;
  }
  return parseFloat(texto);
}

//-----------------------------------------------------------------------------------------
// Convierte el punto en coma
function convertirComa (s)
{
  var texto="";
  var i;

  s=String(s);

  for (i = 0; i < s.length; i++)
  {
	 var c = s.charAt(i);	 
	 if(c==".")
		 	texto=texto+",";
	 else
	 		texto=texto+c;
  }
  return texto;
}

//----------------------------------------------------------------------------------------- 

function popup(popup_url,name,ancho,alto,varx,vary,barras) {
window.focus(name);
var tmp = window.open(popup_url,name,'menubar=no,location=no,toolbar=no,status=no, resizable = yes,scrollbars=' + barras +',directories=no,width=' + ancho + ',height=' + alto);
tmp.moveTo(varx,vary);
tmp.window.focus();
}

//----------------------------------------------------------------------------------------- 

function EncontrarObjeto(n, d) { 
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=EncontrarObjeto(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}

//----------------------------------------------------------------------------------------- 

function CambiarPropiedad(objName,x,theProp,theValue) {
  var obj = EncontrarObjeto(objName);
  if (obj && (theProp.indexOf("style.")==-1 || obj.style)){
    if (theValue == true || theValue == false)
      eval("obj."+theProp+"="+theValue);
    else eval("obj."+theProp+"='"+theValue+"'");
  }
}

//----------------------------------------------------------------------------------------- 

function SetearTexto(objName,newText,d) 
{ //v4.01
  var obj;
  
  if(!d) d=document;
  
  if ((obj=EncontrarObjeto(objName,d))!=null)
  {
	 
	 if (d.layers) 
	  {
		
		 d.write(unescape(newText)); d.close();
	  }
      else 
		 if ((obj.type=="textarea") || (obj.type=="text"))
		 	obj.value=newText;
		 else
		 obj.innerHTML = unescape(newText);
		
  }

/*  if ((obj=EncontrarObjeto(objName))!=null) with (obj)
    if (document.layers) {document.write(unescape(newText)); document.close();}
    else innerHTML = unescape(newText);*/
}

//----------------------------------------------------------------------------------------- 

function addValorPopup(docu, idTextbox, codigo, nombrecontenido, idtextocontenido){
	var elSelect = docu.getElementById(idTextbox);
	elSelect.value=codigo;
	SetearTexto(idtextocontenido,nombrecontenido,docu);
}



//----------------------------------------------------------------------------------------- 

function CompletarString(texto,largo,caracter)
{
	var nuevotexto=texto;
	
	for(i=texto.length+1;i<=largo;i++)
		nuevotexto=nuevotexto+caracter;
	
	return nuevotexto
}
//----------------------------------------------------------------------------------------- 

function ValidarContenido(campo,tipovalidacion)
{
	var rexp;
	
 if(campo.substr(0,1)==" ")
 	return false;
 
 switch(tipovalidacion)
 {
	case "AlfanumericoPuro": // campo alfanumerico sin caracteres especiales
		rexp = /[^A-Za-z0-9]/; 
		if(rexp.test(campo)) 
			return false;
 		break;
	case "AlfabeticoPuro": // campo alfabetico con caracteres especiales
		rexp = /[^A-Za-zÁÉÍÓÚÄËÏÖÜÑñáéíóúäëïöüàèìòùâêîôûÇç ']/; 
		if(rexp.test(campo)) 
			return false;
 		break;		
	case "Email": // campo alfanumerico con algunos caracteres especiales (emails)
//		rexp = new RegExp("^[-\._a-z0-9]+@([a-z0-9]+[\.]{1}){1,2}[a-z]{2,4}([\.]{1}[a-z]{2}){0,1}$","gi");
		rexp = new RegExp("^[-\._a-z0-9]+@([-_a-z0-9]+[\.]{1}){1,2}[a-z]{2,4}([\.]{1}[a-z]{2}){0,1}$","gi");

		if(!rexp.test(campo)) 
			return false;
 		break;
	case "Hexa6Digitos": // color de semáforos
		rexp = new RegExp("^[0-9A-F]{6}$","gi");
		if(!rexp.test(campo)) 
			return false;
		break;
	case "NumericoEntero": // campo numerico entero
		rexp = /[^0-9]/; 
		if(rexp.test(campo)) 
			return false;
		break;
	case "Numerico2Decimales": // campo numerico con 2 decimales maximo
		rexp = new RegExp("^[0-9]+(,[0-9]{1,2}){0,1}$","gi");
		if(!rexp.test(campo)) 
			return false;
		break;
	case "Numerico2DecimalesPunto": // campo numerico con 2 decimales maximo
		rexp = new RegExp("^[0-9]+(.[0-9]{1,2}){0,1}$","gi");
		if(!rexp.test(campo)) 
			return false;
		break;
	case "Hora24": // campo HH:MM
		rexp = new RegExp("^[0-9]{2}:[0-9]{2}$","gi");
		if(!rexp.test(campo)) 
			return false;

		var hora = campo.substr(0,2);
		var minuto = campo.substr(3,2);
		if((hora < 0) || (hora > 23) || (minuto < 0) || (minuto > 59))
		   return false;
			
		break;	
	
		
 	default:
		alert(" Validación no definida - Avise a su administrador");
 		return false;
 		break;
 }
 return true;
}

//----------------------------------------------------------------------------------------- 
function ValidarPassword(clave,claveactual,identificacion,longmin)
{
	
//	var message="Error: Ingrese una nueva clave";
	var i = 0;
	var p = 0; // codigo de error que se produjo
	var c3 = 1; // cantidad de letras iguales secuenciales
	var a = 0; // mantiene el codigo ascii al recorrer la clave para las validaciones
	var sa = 1; // cantidad de letras consecutivas segun abecedario
	var sd = 1; // cantidad de letras consecutivas segun abecedario inverso
	var pa = 0; // mantiene la cantidad de caracteres iguales entre la nueva clave y la clave actual
	var ca = 0; // cuenta la cantidad de letras
	var cn = 0; // cuenta la cantidad de numeros
	var ant = 0; // mantiene un registro de la letra anterior
	var layout = new Array(); // array que contiene secuencia de caracteres comunes

	layout[layout.length] = "QWERTY";
	layout[layout.length] = "YTREWQ";
	layout[layout.length] = "ASDFG";
	layout[layout.length] = "GFDSA";
	layout[layout.length] = "ZXCVB";
	layout[layout.length] = "BVCXZ";

	if (clave.length<longmin)
		p=12;

	var UsrId = identificacion.charAt(identificacion.length -1); // contiene la identificacion al reves
	for (i = identificacion.length - 2; i >= 0; i--)
		UsrId += identificacion.charAt(i);

	// Verifica si la clave contiene a la identificacion (al derecho y al reves)
	if ((clave.indexOf(identificacion) != -1) || (identificacion.indexOf(clave) != -1))
		p = 9;
	else
		if ((clave.indexOf(UsrId) != -1) || (UsrId.indexOf(clave) != -1))
			p = 10;

	// Verifica si la clave contiene una secuencia de caracteres comunes
	if (p == 0)
	{
		for (i = 0; i < layout.length; i++)
		{
			if ((clave.toUpperCase().indexOf(layout[i]) != -1))
			{
				p = 6;
				break;
			}
		}
	}

	if (p == 0)
	{
		for (i = 0; i < clave.length; i++)
		{
			a = clave.charCodeAt(i);
			if (a >= 48 && a <= 57)
				cn++;
			else
				if ((a >= 65 && a <= 90) || (a >= 97 && a <= 122))
					ca++;

			if (a == ant)
			{
				if (++c3 > 3) // si tengo mas de 3 letras iguales consecutivas
				{
					p = 2;
					break;
				}
			}
			else
			{
				c3 = 1;
				if (ant == a - 1)
				{
					if (++sa > 3) // mas de 3 letras consecutivas segun abecedario
					{
						p = 4;
						break;
					}
					sd = 1;
				}
				else
				{
					if (ant == a + 1)
					{
						if (++sd > 3) // mas de 3 letras consecutivas segun abecedario inverso
						{
							p = 5;
							break;
						}
						sa = 1;
					}
					else
					{
						sa = 1;
						sd = 1;
					}
				}
			}

			if (a == claveactual.charCodeAt(i))
			{
				if (++pa > 5) // si tiene mas de 5 caracteres iguales que la clave actual
				{
					p = 14;
					break;
				}
			}

			ant = a;

		}
	}

	if (p == 0 && (ca + cn) != clave.length) // si la cantidad de letras y numeros es distinta al largo total, entonces hay caracteres especiales
		p = 11;

	if (ca==0 || cn==0)
		return false;
		
	if (p != 0)
		return false;
	else
		return true;
}
//----------------------------------------------------------------------------------------- 


function CambiarHabil(control,estado)
{
	if(!estado)
		control.value=control.defaultValue;

	CambiarPropiedad(control.name,"","disabled",!estado);
}

//----------------------------------------------------------------------------------------- 


function PasarItemListaMultiple (ListaOrigen,ListaDestino,Eliminar)
{
	
	var opt;
	var arreglo=new Array();
	var doc;
	var i, j=0;
	for(i=0;i<ListaOrigen.length;i++)
	{
		if (ListaOrigen.options[i].selected==true)
		{	
			doc = ListaOrigen.options[i].ownerDocument;
			if (!doc)
				doc = ListaOrigen.document;
			opt = doc.createElement('OPTION');
			opt.value= ListaOrigen.options[i].value;
			opt.text = ListaOrigen.options[i].text;
			ListaDestino.options.add(opt);
			arreglo[j]=i;
			j++;
		}
	}
	if (Eliminar)
		if (arreglo.length>0)
		{	
			for (i=0;i<arreglo.length;i++)
				ListaOrigen.remove(arreglo[i]-i);
		}
}


function PasarItemLista(ListaOrigen,ListaDestino,Eliminar)
{
	if(ListaOrigen.selectedIndex!=-1)
	{
		var doc = ListaOrigen.ownerDocument;
		if (!doc)
			doc = ListaOrigen.document;
		var opt = doc.createElement('OPTION');
		opt.value = ListaOrigen.options[ListaOrigen.selectedIndex].value;
		opt.text = ListaOrigen.options[ListaOrigen.selectedIndex].text;
		ListaDestino.options.add(opt);

		if(Eliminar)
			ListaOrigen.remove(ListaOrigen.selectedIndex);
	}
	else
		alert("No hay ningún item seleccionado");
}
//----------------------------------------------------------------------------------------- 

function PasarTodosItemsLista(formulario,ListaOrigen,ListaDestino)
{
	
	while(ListaOrigen.length>0)
	{
		var doc = ListaOrigen.ownerDocument;
		if (!doc)
			doc = ListaOrigen.document;
		var opt = doc.createElement('OPTION');
		opt.value = ListaOrigen.options[0].value;
		opt.text = ListaOrigen.options[0].text;
		ListaDestino.options.add(opt);
		ListaOrigen.remove(0);
	}
}

//----------------------------------------------------------------------------------------- 

function PasarItemsInicialesLista(formulario,ListaOrigen,ListaDestino,ItemsIniciales)
{
	var dato;
	var posListaOrigen;

	for(dato in ItemsIniciales)
	{
		for(posListaOrigen=0;posListaOrigen<ListaOrigen.length;)
		{
			if(ItemsIniciales[dato]==ListaOrigen.options[posListaOrigen].value)
			{
				ListaOrigen.selectedIndex=posListaOrigen;
				PasarItemListaMultiple(ListaOrigen,ListaDestino,true);
			}
			posListaOrigen++;
		}
	}
}

//----------------------------------------------------------------------------------------- 

function MoverItemLista(Lista,accion)
{
	var valor,texto;
	if(Lista.selectedIndex==-1)
		return false;
		
	switch(accion)
	{
		case "subir":
			if(Lista.selectedIndex>0)
			{
				valor=Lista.options[Lista.selectedIndex-1].value;
				texto=Lista.options[Lista.selectedIndex-1].text;
				Lista.options[Lista.selectedIndex-1].value=Lista.options[Lista.selectedIndex].value;
				Lista.options[Lista.selectedIndex-1].text=Lista.options[Lista.selectedIndex].text;
				Lista.options[Lista.selectedIndex].value=valor;
				Lista.options[Lista.selectedIndex].text=texto;
				Lista.selectedIndex--;
			}
			break;
		case "bajar":
			if(Lista.selectedIndex<Lista.length-1)
			{
				valor=Lista.options[Lista.selectedIndex+1].value;
				texto=Lista.options[Lista.selectedIndex+1].text;
				Lista.options[Lista.selectedIndex+1].value=Lista.options[Lista.selectedIndex].value;
				Lista.options[Lista.selectedIndex+1].text=Lista.options[Lista.selectedIndex].text;
				Lista.options[Lista.selectedIndex].value=valor;
				Lista.options[Lista.selectedIndex].text=texto;
				Lista.selectedIndex++;
			}
			break;
		case "borrar":
			if(Lista.selectedIndex!=-1)
			{
				Lista.remove(Lista.selectedIndex);
			}
			break;
		default:
			return false;
	}
	
	return true;	
}

//----------------------------------------------------------------------------------------- 
function SeleccionarTodosLista(Lista)
{
	var i;
	
	for(i=0;i<Lista.length;i++)
	{
		Lista.options[i].selected=true;
	}
}

//----------------------------------------------------------------------------------------- 

function CargarDatosModificar(accion,formulario,campos_linea,datos,label,textolabel)
{
	var i;
	
	switch(accion)
	{
		case "CARGAR":
			for(campo in campos_linea)
			{
				switch(campos_linea[campo].substr(0,3))
				{
					case "hid":
					case "txt":
						formulario[campos_linea[campo].substr(4)].value=datos[campo];
						break;
					case "cmb":
						var combo=campos_linea[campo].substr(4);
						
						for(i=0;i<formulario[combo].length;i++)
						{
							if(formulario[combo].options[i].value==datos[campo])
								formulario[combo].selectedIndex=i;
						}
							
						break;
					case "lst":
						var nombreslist=campos_linea[campo].split("|");
						var ListaOrigen=EncontrarObjeto(nombreslist[1]);
						var ListaDestino=EncontrarObjeto(nombreslist[2]);

						PasarTodosItemsLista(formulario,ListaDestino,ListaOrigen);
						
						PasarItemsInicialesLista(formulario,ListaOrigen,ListaDestino,datos[campo]);

						break;
					case "ls2":
						var dato,posListaOrigen;
						var nombreslist=campos_linea[campo].split("|");
						var encontrado=false,i;
					
						eval("selec_txt_"+nombreslist[1]+".splice(0, selec_txt_"+nombreslist[1]+".length);");
						eval("selec_cod_"+nombreslist[1]+".splice(0, selec_cod_"+nombreslist[1]+".length);");

						for(dato in datos[campo])
						{
							encontrado=false;
							for(i=0;!encontrado && i<eval("txt_todos_"+nombreslist[1]+".length");i++)
							{
								if(eval("cod_todos_"+nombreslist[1]+"["+i+"]")==datos[campo][dato])
								{
									eval("selec_txt_"+nombreslist[1]+"[selec_txt_"+nombreslist[1]+".length] = txt_todos_"+nombreslist[1]+"["+i+"];");
									eval("selec_cod_"+nombreslist[1]+"[selec_cod_"+nombreslist[1]+".length] = cod_todos_"+nombreslist[1]+"["+i+"];");
									encontrado=true;
								}
							}
						}
						ActualizarSelec(nombreslist[1],eval("selec_cod_"+nombreslist[1]),eval("selec_txt_"+nombreslist[1]));						
						
						break;

					case "opt":
						for(i=0;i<formulario[campos_linea[campo].substr(4)].length;i++)
						{
							if(formulario[campos_linea[campo].substr(4)][i].value==datos[campo])
							{
								formulario[campos_linea[campo].substr(4)][i].checked=true;
							}
						}							
						break;
				} // fin switch campo.substr
			} // for campo in campos_linea
			formulario.botonalta.value="Actualizar";
			break;
		case "VACIAR":
			for(campo in campos_linea)
			{
				switch(campos_linea[campo].substr(0,3))
				{
					case "hid":
					case "txt":
						formulario[campos_linea[campo].substr(4)].value="";
						break;
					case "cmb":
						formulario[campos_linea[campo].substr(4)].selectedIndex=0;
						break;
					case "lst":
						var nombreslist=campos_linea[campo].split("|");
						var ListaOrigen=EncontrarObjeto(nombreslist[2]);
						var ListaDestino=EncontrarObjeto(nombreslist[1]);

						PasarTodosItemsLista(formulario,ListaOrigen,ListaDestino);
						break;	
					case "ls2":
						var nombreslist=campos_linea[campo].split("|");

						eval("selec_txt_"+nombreslist[1]+".splice(0, selec_txt_"+nombreslist[1]+".length);");
						eval("selec_cod_"+nombreslist[1]+".splice(0, selec_cod_"+nombreslist[1]+".length);");

						ActualizarSelec(nombreslist[1],eval("selec_cod_"+nombreslist[1]),eval("selec_txt_"+nombreslist[1]));						

						break;
					case "opt":
						for(i=0;i<formulario[campos_linea[campo].substr(4)].length;i++)
							formulario[campos_linea[campo].substr(4)][i].checked=false;
						break;
				} // fin switch campo.substr
			} // for campo in campos_linea
			formulario.botonalta.value="Aceptar";
			break;
	}
	
	SetearTexto(label,textolabel);
}

//----------------------------------------------------------------------------------------- 

function ExisteEnArray(ItemsIniciales,ValorABuscar)
{
	for(valor in ItemsIniciales)
		if(ItemsIniciales[valor]==ValorABuscar)
			return true;
	
	return false;
}

//----------------------------------------------------------------------------------------- 

function SeleccionCombo(formulario,campos_linea,datos_grilla,seleccionado)
{
	if(seleccionado>0)
	{
		CargarDatosModificar('CARGAR',formulario,campos_linea,datos_grilla[seleccionado],'','');
		$("#botonalta").html("Actualizar");
		if(formulario.botonbaja)
			formulario.botonbaja.disabled=false;
	}
	else
	{
		CargarDatosModificar('VACIAR',formulario,campos_linea,'','','');
		$("#botonalta").html("Agregar");
		if(formulario.botonbaja)
			formulario.botonbaja.disabled=true;
	}
}

//----------------------------------------------------------------------------------------- 

function EC(TheTR)
{
	var DataTR = eval('document.getElementById("' + TheTR + '")');
	var img = eval('document.getElementById("' + TheTR + 'img")');
	
	if (DataTR.style.visibility=="visible" || DataTR.style.visibility=="" )
	{
		DataTR.style.visibility="hidden";
		DataTR.style.position="absolute";
		DataTR.parentNode.style.padding="0px 0px 0px 0px";
		if(img!=null)
			img.src='images/i.p.arr.down.jpg';
	}
	else
	{
		DataTR.style.visibility="visible";
		DataTR.style.position="relative";
		DataTR.parentNode.style.padding="5px 5px 5px 5px";
		if(img!=null)
			img.src='images/i.p.arr.up.jpg';
	}
}

//----------------------------------------------------------------------------------------- 


function AgregarEstilo(CajaTexto,estilo,inicio)
{
	switch(estilo)
	{
		case "B":
			if(inicio)
				InsertarTexto(CajaTexto,"$$Tipo='Bold'$$");
			else
				InsertarTexto(CajaTexto,"$$Tipo='BoldFin'$$");
			break;
		case "I":
			if(inicio)
				InsertarTexto(CajaTexto,"$$Tipo='Italic'$$");
			else
				InsertarTexto(CajaTexto,"$$Tipo='ItalicFin'$$");
			break;
		case "U":
			if(inicio)
				InsertarTexto(CajaTexto,"$$Tipo='Underline'$$");
			else
				InsertarTexto(CajaTexto,"$$Tipo='UnderlineFin'$$");
			break;
		case "V":
			InsertarTexto(CajaTexto,"$$Tipo='Bullet'$$");
			break;
	}
}


//----------------------------------------------------------------------------------------- 

function InsertarTexto(CajaTexto,Texto)
{
	// IE
	if ( (ua_vers >= 4) && is_ie && is_win)
	{
		if (CajaTexto.isTextEdit)
		{
			CajaTexto.focus();
			var sel = document.selection;
			var rng = sel.createRange();
			rng.colapse;
			if((sel.type == "Text" || sel.type == "None") && rng != null)
			{
				rng.text = Texto+rng.text;
			}
		}
	}
	//----------------------------------------
	// It's MOZZY!
	//----------------------------------------
	
	else if ( !isNaN(CajaTexto.selectionEnd) )
	{ 
		var ss = CajaTexto.selectionStart;
		var st = CajaTexto.scrollTop;
		var es = CajaTexto.selectionEnd;
		
		if (es <= 2)
		{
			es = CajaTexto.textLength;
		}
		
		var start  = (CajaTexto.value).substring(0, ss);
		var middle = (CajaTexto.value).substring(ss, es);
		var end    = (CajaTexto.value).substring(es, CajaTexto.textLength);
		
		middle = Texto + middle;
		
		CajaTexto.value = start + middle + end;
		
		var cpos = ss + (middle.length);
		
		CajaTexto.selectionStart = cpos;
		CajaTexto.selectionEnd   = cpos;
		CajaTexto.scrollTop      = st;
	}
	//----------------------------------------
	// It's CRAPPY!
	//----------------------------------------
	else
	{
		CajaTexto.value += Texto;
	}
	
	CajaTexto.focus();

	return true;

}

//----------------------------------------------------------------------------------------- 

function Reloj()
	{ 
		var Hora = Hoy.getHours() 
		var Minutos = Hoy.getMinutes() 
		var Segundos = Hoy.getSeconds() 
		var dn="AM"
		if (Hora==12) dn="PM" 
		if (Hora>12)
		{
			dn="PM"
			Hora=Hora-12
		}
		if (Hora<=9) Hora = "0" + Hora 
		if (Minutos<=9) Minutos = "0" + Minutos 
		if (Segundos<=9) Segundos = "0" + Segundos 
		var Dia = new Array("Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado", "Domingo"); 
		var Mes = new Array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"); 

		// Modificamos para que aparezca la fecha completa
		var Mes_Corto = new Array("Ene","Feb","Mar","Abr","May","Jun","Jul","Ago","Sep","Oct","Nov","Dic"); 
		var Dia_Corto = new Array("Dom", "Lun", "Mar", "Mié", "Jue", "Vie", "Sáb", "Dom"); 

		var Anio = Hoy.getFullYear(); 

		// var Fecha = Dia[Hoy.getDay()] + ", " + Hoy.getDate() + " de " + Mes[Hoy.getMonth()] + " de " + Anio + ", a las "; 
		var Fecha = Dia_Corto[Hoy.getDay()] + ", " + Hoy.getDate() + " " + Mes_Corto[Hoy.getMonth()] + " " + Anio ; 


		var Inicio, Script, Final, Total 
		//Inicio = "<font color=#3399CC> "
		//Script = Hora + "&nbsp;:&nbsp;</font>" + Minutos + ":" + Segundos + dn
		//Final = "</b></font>" 
		//Total2 = Inicio + Script + Final 
		if (standardbrowser)
		{
			if (alternate==0)
				Total=Hora+":"+Minutos+" "+dn
			else
				Total=Hora+" "+Minutos+" "+dn
		}else
		{
			if (alternate==0)
				Total=Fecha+" "+Hora+"<span style='color:#000'>:</span>"+Minutos+""+" "+dn+"</sup>"
			else
				Total=Fecha+" "+Hora+"<span style='color:#d4d4d4'>:</span>"+Minutos+""+" "+dn+"</sup>"
		}
		
		document.getElementById('Fecha_Reloj').innerHTML = Total 
		Hoy.setSeconds(Hoy.getSeconds() +1)
		alternate=(alternate==0)? 1 : 0
	
		setTimeout("Reloj()",1000)  		
} 

function CargarCombo(param,tipo,id)
{
	$("#cargando").show();
	param += "&tipo="+tipo;
	$.ajax({
	   type: "POST",
	   url: "combo_ajax.php",
	   data: param,
	   success: function(msg){
		 $(id).html(msg);
		 $("#cargando").hide();
	   }
	 });

	return true;
}


function CargarComboCiudad(id)
{
	var param = "provinciacod="+$("#provinciacod").val();
	CargarCombo(param,2,id);
	return true;
}

function CargarComboCoberturas(id)
{
	var param = "coberturatipocod="+$("#coberturatipocod").val();
	CargarCombo(param,3,id);
	return true;
}

function CargarComboPlanesCoberturas(id)
{
	var param = "coberturacod="+$("#coberturacod").val();
	CargarCombo(param,4,id);
	return true;
}

function CargarComboConsultorios(id)
{
	var param = "medicocod="+$("#medicocod").val();
	CargarCombo(param,5,id);
	return true;
}



function Solapa(id)
{
	$(id).slideToggle('slow');
	return true;	
}

function addTimeToDate(time,unit,objDate,dateReference){
    var dateTemp=(dateReference)?objDate:new Date(objDate);
     switch(unit){
        case 'y': dateTemp.setFullYear(objDate.getFullYear()+time); break;
	        case 'M': dateTemp.setMonth(objDate.getMonth()+time); break;
	        case 'w': dateTemp.setTime(dateTemp.getTime()+(time*7*24*60*60*1000)); break;
	        case 'd': dateTemp.setTime(dateTemp.getTime()+(time*24*60*60*1000)); break;
	        case 'h': dateTemp.setTime(dateTemp.getTime()+(time*60*60*1000)); break;
	        case 'm': dateTemp.setTime(dateTemp.getTime()+(time*60*1000)); break;
	        case 's': dateTemp.setTime(dateTemp.getTime()+(time*1000)); break;
	        default : dateTemp.setTime(dateTemp.getTime()+time); break;
	    }
	    return dateTemp;
	}


function MostrarOcultarMenu(grupomoduloselect)
{
	$(".menuinferior").hide();
	$(".menugrupomodulos a").removeClass("selected");
	$("#GrupoModulo_"+grupomoduloselect).addClass("selected");
	$(".menuinf_"+grupomoduloselect).show();
}

jQuery(document).ready(function(){
	$.fn.serializeObject = function()
	{ //Prepping for JSON
		var o = {};
		var a = this.serializeArray();
		$.each(a, function() {
			if (o[this.name]) {
				if (!o[this.name].push) {
					o[this.name] = [o[this.name]];
				}
					o[this.name].push(this.value || '');
				} else {
					o[this.name] = this.value || '';
			}
		});
		return o;
	};
});




jQuery.fn.extend({ //indica que está sendo criado um plugin
       
	number_format: function(numero, params) //indica o nome do plugin que será criado com os parametros a serem informados
			{
			//parametros default
			var sDefaults =
					{                      
					numberOfDecimals: 2,
					decimalSeparator: ',',
					thousandSeparator: '.',
					symbol: ''
					}

			//função do jquery que substitui os parametros que não foram informados pelos defaults
			var options = jQuery.extend(sDefaults, params);

			//CORPO DO PLUGIN
			var number = numero;
			var decimals = options.numberOfDecimals;
			var dec_point = options.decimalSeparator;
			var thousands_sep = options.thousandSeparator;
			var currencySymbol = options.symbol;
		   
			var exponent = "";
			var numberstr = number.toString ();
			var eindex = numberstr.indexOf ("e");
			if (eindex > -1)
			{
			exponent = numberstr.substring (eindex);
			number = parseFloat (numberstr.substring (0, eindex));
			}
		   
			if (decimals != null)
			{
			var temp = Math.pow (10, decimals);
			number = Math.round (number * temp) / temp;
			}
			var sign = number < 0 ? "-" : "";
			var integer = (number > 0 ?
			  Math.floor (number) : Math.abs (Math.ceil (number))).toString ();
		   
			var fractional = number.toString ().substring (integer.length + sign.length);
			dec_point = dec_point != null ? dec_point : ".";
			fractional = decimals != null && decimals > 0 || fractional.length > 1 ?
							   (dec_point + fractional.substring (1)) : "";
			if (decimals != null && decimals > 0)
			{
			for (i = fractional.length - 1, z = decimals; i < z; ++i)
			  fractional += "0";
			}
		   
			thousands_sep = (thousands_sep != dec_point || fractional.length == 0) ?
									  thousands_sep : null;
			if (thousands_sep != null && thousands_sep != "")
			{
			for (i = integer.length - 3; i > 0; i -= 3)
			  integer = integer.substring (0 , i) + thousands_sep + integer.substring (i);
			}
		   
			if (options.symbol == '')
			{
			return sign + integer + fractional + exponent;
			}
			else
			{
			return currencySymbol + ' ' + sign + integer + fractional + exponent;
			}
			//FIM DO CORPO DO PLUGIN        
		   
	}
});



/*******************************************************************************
 * AUX FUNCTIONS
 ******************************************************************************/

function formatTextArea()
{
	var textObj = jQuery('textarea#textoCable');

	var sanitizedText = sanitize(jQuery('textarea#textoCable').val());
	
	jQuery('textarea#textoCable').val(sanitizedText);
}


function sanitize(s) 
{
	var lines = s.split("\n");
	var finalLines = [];
	
	for(var i = 0; i < lines.length; i++) 
	{
		var str = new String(lines[i]);
		
		if (lines[i].charAt(0) != '•')
		{
			finalLines[i] = "•" + str;
		}
		else
		{
			finalLines[i] = str;
		}
	}

	return finalLines.join("\n");
}


/**
 * Removes new lines without bullets. It uses to clean lines after deleting a
 * bullet on keypress/input event.
 * 
 * @param {String}
 *            s
 * @returns {String}
 */
function removeOrphanLines(s)
{
	var lines = s.split("\n");
	var result = '';
	
	for (var i = 0; i < lines.length; i++) 
	{
		if (i > 0 && lines[i].charAt(0) == '•')
		{
			result += "\n";
		}
		
		result += lines[i];
	}

	return result;
}


function updateWordCount(id) 
{
    sId = '#' + id;

    var total_words = 0;
    
    words = jQuery(sId).val().split(/[\s\.•\?]+/);

    for (var i = 0; i < words.length; i++)
    {
        if (words[i] != "")
        {
            total_words++;
        }
    }
    
    total_lines = jQuery(sId).val().split(/[\n]/).length;
    jQuery(sId + 'WordCount').html(total_lines + ' linea'+ (total_lines != 1 ? 's' : '') +' / ' + total_words + ' palabra' + (total_words != 1 ? 's' : ''));
}


function updateCharCount(id)
{
	var sId = '#' + id;
	var sCounterId = sId + 'CharCount';
	
	var numChars = jQuery(sId).val().length;
	var maxLength = jQuery(sId).attr('maxlength');
	
	if (maxLength == undefined)
	{
		jQuery(sCounterId + ' .counter').html(numChars);
	}
	else
	{
		jQuery(sCounterId + ' .counter').html(numChars + ' / ' + (maxLength - numChars));
		
		if (numChars < maxLength)
		{
			jQuery(sCounterId).removeClass('charCountWarning');
		}
		else
		{
			jQuery(sCounterId).addClass('charCountWarning');
		}
	}
}


function getMonthStr(monthInt)
{
    switch (monthInt + 1)
    {
    	case 1:
        	return 'Enero';
    	case 2:
        	return 'Febrero';
    	case 3:
        	return 'Marzo';
    	case 4:
        	return 'Abril';
    	case 5:
        	return 'Mayo';
    	case 6:
        	return 'Junio';
    	case 7:
        	return 'Julio';
    	case 8:
        	return 'Agosto';
    	case 9:
        	return 'Septiembre';
    	case 10:
        	return 'Octubre';
    	case 11:
        	return 'Noviembre';
    	case 12:
        	return 'Diciembre';
        default:
        	return '';
    }
}


jQuery.fn.wordCount = function(params)
{
	if(params) 
	{
		jQuery.extend(p, params);
	}
	
	// for each keypress function on textareas
	this.keypress(function() {
		updateWordCount(this.id);
	});
};


jQuery.fn.charCount = function(params)
{
	if(params)
	{
		jQuery.extend(p, params);
	}
	
	// for each keyup event on textareas
	this.keyup(function() {
		updateCharCount(this.id);
	});
	
	this.keydown(function() {
		updateCharCount(this.id);
	});
	
	this.change(function() {
		updateCharCount(this.id);
	});
};




function initTextEditors(mode, elements)
{
	if (typeof(mode) == 'undefined')
	{
		mode = 'specific_textareas';
	}
	
	if (typeof(elements) == 'undefined')
	{
		elements = 'rich-text';
	}
	
	var editorConfig = {
		mode : mode,
        language : "es",
		plugins : 'paste,lists,tabfocus,table,media,link,anchor,table,image',
		spellchecker_languages : "+Spanish=es",
		theme : 'modern',
		menubar: false,
		width : "100%",
		content_css : 'css/texteditor.css?v=1.3',
		theme_advanced_toolbar_location : 'top',
		theme_advanced_toolbar_align : 'left',
		toolbar1 : 'cut,copy,paste,pasteword,|,bold,italic,underline,|,link unlink anchor,|,removeformat,|,formatselect,image',
		toolbar2 : 'hr,outdent,indent,|,table,|,media,|,justifyleft,justifycenter,justifyright,justifyfull,|,blockquote,cite,|,styleselect,|,bullist,numlist,|,code',
		apply_source_formatting : false,
		theme_advanced_styles: "Encabezado Destacado=encabezadodestacado",
		theme_advanced_statusbar_location : "bottom",
		theme_advanced_blockformats : "h2,h3,h4,h5,h6,p,table",
		element_format : 'xhtml',
		style_formats: [
				{
					title: 'Imagen Izquierda',
					selector: 'img',
					styles: {
						'float': 'left', 
						'margin': '10px 10px 10px 0'
					}
				 },
				 {
					 title: 'Imagen Derecha',
					 selector: 'img', 
					 styles: {
						 'float': 'right', 
						 'margin': '10px 0 10px 10px'
					 }
				 }
			],
		theme_advanced_resizing : true,
		forced_root_block : 'p',
		force_p_newlines : true,
		force_br_newlines : false,
		relative_urls: false,
		valid_elements : 'div,br,p[style|class],a[name|href|target=_blank|title],table[*],tr[*],thead[*],tfoot[*],tbody[*],th[*],td[*],img[*],strong/b,em/i,u,span[style=text-decoration:underline;],h2[style|class],h3,h4,h5,h6,ul,ol,li,hr,iframe[*]', 
		paste_auto_cleanup_on_paste : true,
		paste_preprocess : function(pl, o) {
			// Replace <br>s and <div>s by paragraphs and filter the html tags (except <p>s).
			o.content = o.content.replace(/<br(\s[^>]*)?\/?>/ig, '</p><p>')
			                     .replace(/<div(\s[^>]*)?\/?>/ig, '<p>')
			                     .replace(/<\/?(?!p)(\s[^>]*)?\/?>/ig, '');
		}	
	};
	
	if (mode == 'specific_textareas')
	{
		editorConfig.editor_selector = elements;
	}
	else if (mode == 'exact')
	{
		editorConfig.elements = elements;
	}
	tinymce.remove("."+elements);
	tinymce.init(editorConfig);
}


function initTextEditorsAvanzado(mode, elements)
{
	if (typeof(mode) == 'undefined')
	{
		mode = 'specific_textareas';
	}
	
	if (typeof(elements) == 'undefined')
	{
		elements = 'rich-text-avanzado';
	}
	
	var editorConfig = {
		mode : mode,
        language : "es",
		plugins : 'paste,lists,tabfocus,table,media,link,anchor,table',
		spellchecker_languages : "+Spanish=es",
		theme : 'modern',
		menubar: false,
		width : "100%",
		content_css : 'css/texteditor.css?v=1.3',
		theme_advanced_toolbar_location : 'top',
		theme_advanced_toolbar_align : 'left',
		toolbar1 : 'cut,copy,paste,pasteword,|,bold,italic,underline,|,link unlink anchor,|,removeformat,|,formatselect,|,fotos',
		toolbar2 : 'hr,outdent,indent,|,table,|,media,|,justifyleft,justifycenter,justifyright,justifyfull,|,blockquote,cite,|,styleselect,|,bullist,numlist',
		apply_source_formatting : false,
		theme_advanced_styles: "Encabezado Destacado=encabezadodestacado",
		theme_advanced_statusbar_location : "bottom",
		theme_advanced_blockformats : "h2,h3,h4,h5,h6,p,table",
		element_format : 'xhtml',
		setup: function(editor) {
			editor.addButton('fotos', {
						type: 'menubutton',
						text: 'Fotos',
						icon: false,
						menu: [
							{text: 'Foto Centro', onclick: function() {editor.insertContent('@fotoC@');}},
							{text: 'Foto Izquierda', onclick: function() {editor.insertContent('@fotoI@');}},
							{text: 'Foto Derecha', onclick: function() {editor.insertContent('@fotoD@');}}
						]
					});
		},
		theme_advanced_resizing : true,
		forced_root_block : 'p',
		force_p_newlines : true,
		force_br_newlines : false,
		relative_urls: false,
		valid_elements : 'div,br,p[style|class],a[name|href|target=_blank|title],table[*],tr[*],thead[*],tfoot[*],tbody[*],th[*],td[*],img[*],strong/b,em/i,u,span[style=text-decoration:underline;],h2[style|class],h3,h4,h5,h6,ul,ol,li,hr,iframe[*]', 
		paste_auto_cleanup_on_paste : true,
		paste_preprocess : function(pl, o) {
			// Replace <br>s and <div>s by paragraphs and filter the html tags (except <p>s).
			o.content = o.content.replace(/<br(\s[^>]*)?\/?>/ig, '</p><p>')
			                     .replace(/<div(\s[^>]*)?\/?>/ig, '<p>')
			                     .replace(/<\/?(?!p)(\s[^>]*)?\/?>/ig, '');
		}	
	};
	
	if (mode == 'specific_textareas')
	{
		editorConfig.editor_selector = elements;
	}
	else if (mode == 'exact')
	{
		editorConfig.elements = elements;
	}
	tinymce.remove("."+elements);
	tinymce.init(editorConfig);
}



$( document ).ready(function() {
	
	$( "#sidebar-exp" ).click(function() {
	  if ($( "#wrapper" ).hasClass( "sidebar" ))
	  {
		  $("#wrapper" ).removeClass( "sidebar" );
		  $("#body-overlay" ).hide();
		  
	  }
	  else
	  {
		  $("#wrapper" ).addClass( "sidebar" );
		  $("#body-overlay" ).show();
	  }
	});
	$( "#sidebar ul li a.groupMenu" ).click(function() {
		if (!$(this).parent("li").hasClass( "selected" ))
	    {
		  $("#sidebar ul li").removeClass("selected").children("ul").hide();
		  $(this).parent("li").addClass( "selected" ).children("ul").fadeIn();
		}
		else
		{
			$(this).parent("li").removeClass("selected").children("ul").fadeOut();
		}
	});
	$( "#dropdown-usuario" ).click(function() {
		$( "#submenu_usuario" ).toggle();
	});
});