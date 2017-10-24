if (typeof (url_cache) == "undefined") var url_cache = "http://ep00.epimg.net";

ACCIONES = function () { 

    // comun.js móvil

    /*var modo_dislexia = false;
    var boton_activar_dislexia;*/
    var modo_solo_texto = false;
    
    var boton_solo_texto;    

    accionesOnLoad = function () {


        inicializaMenuDesplegable("secciones");
    }


    gestionaOpcion = function (opcion) { // guarda o recupera opciones del localstorage

        if (typeof (localStorage) != 'undefined') {
            if (opcion.accion == "SAVE") {

                localStorage.setItem(opcion.nombre, opcion.valor); //saves to the database, “key”, “value”  
            } else {
                var valor = localStorage.getItem(opcion.nombre); //saves to the database, “key”, “value”    
                return (valor);
            }
        }
    }

    activaHerramientas = function () {
    }



    inicializaMenuDesplegable = function (nombre) { // asigna evento para desplegar y plegar menú de secciones


        if (!document.getElementById(nombre)) return;

        // el menu desplegable consta de :
        // div#NOMBRE y dentro un div#NOMBRE_contenido, que es el que se pliega y despliega

        var menu_objeto = {};
        menu_objeto.nombre = nombre;
        menu_objeto.contenido = document.getElementById(menu_objeto.nombre + "_contenido");
        menu_objeto.menu = document.getElementById(menu_objeto.nombre);
        menu_objeto.menu.style.height = "auto";
        menu_objeto.menu.style.maxHeight = "0";
        menu_objeto.menu.style.display = "block";
        if (!menu_objeto.menu.className.match("menu_plegado")) menu_objeto.menu.className += " menu_plegado";
        menu_objeto.boton = document.getElementById("boton_" + menu_objeto.nombre);


        //if ( ! menu_objeto.boton.className.match("boton_desplegar") ) menu_objeto.boton.className += " boton_desplegar";


        despliegaSecciones = function (menu_objeto) {

            var enlaces = menu_objeto.contenido.getElementsByTagName("a");
            if (!menu_objeto.menu.className.match("menu_desplegado")) {
                var alto_menu = menu_objeto.contenido.offsetHeight;                
                menu_objeto.menu.className = menu_objeto.menu.className.replace(" menu_plegado", "");    
                menu_objeto.menu.className += " menu_desplegado";
                // ponemos el focus en la primera opción
                enlaces[0].focus();
                menu_objeto.boton_desplegar.className = "boton_plegar";

            } else { // plegar menú
                menu_objeto.menu.className = menu_objeto.menu.className.replace(" menu_desplegado", "");    
                menu_objeto.menu.className += " menu_plegado";        
                menu_objeto.boton_desplegar.focus();
                menu_objeto.boton_desplegar.className = "boton_desplegar";
            }
        }

        var boton_cerrar = document.createElement("a");
        boton_cerrar.className = "boton_cerrar_desplegable boton_cerrar_" + menu_objeto.nombre;
        boton_cerrar.setAttribute("href", "javascript:void(0)");
        
        boton_cerrar.innerHTML = "Cerrar";
        menu_objeto.contenido.appendChild(boton_cerrar);        
        var boton_secciones = document.getElementById("boton_" + menu_objeto.nombre);
//        document.getElementById("boton_" + menu_objeto.nombre).setAttribute("href","javascript:void(0)");

       if (menu_objeto.menu.className.match("boton_desplegar_independiente") ) {        
            var boton_desplegar = document.createElement("a");
            boton_desplegar.className = "boton_desplegar";
            boton_desplegar.setAttribute ( "href" , "javascript:void(0)" );
            boton_secciones.parentNode.insertBefore(boton_desplegar,boton_secciones.nextSibling);
            menu_objeto.boton_desplegar = boton_desplegar;
        }
        else
        {
            menu_objeto.boton_desplegar = boton_secciones;
        }


        menu_objeto.boton_desplegar.onclick = function (menu_objeto) {
            return function () {
                despliegaSecciones(menu_objeto)
            }
        }(menu_objeto);
        boton_cerrar.onclick = function (menu_objeto) {
            return function () {
                despliegaSecciones(menu_objeto)
            }
        }(menu_objeto);


    }



    // añadimos un evento de onload al body, sin afectar a otros que ya existan
    if (document.addEventListener) {
        window.addEventListener("DOMContentLoaded", accionesOnLoad, false);
    } else {
        window.attachEvent('onload', accionesOnLoad); //IE
    }

    /*

window.onresize = function() {
    var alto_menu = document.getElementById("secciones_contenido").offsetHeight ;
    document.getElementById("secciones").style.height = alto_menu + "px" ;
    

}

*/


}

ACCIONES(); 


function AbrirExtendible(extendible)
{
	$(extendible).toggle();		
}

