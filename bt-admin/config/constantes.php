<?php  
define("SYSTEMVERSION","1.0.0");
define("PAGINAR",20);

define("TAMANIOTHUMB",120);
define("TAMANIOTHUMBXL",420);
define("TAMANIONORMAL",1200);
	
define("TAMANIOMB",20);
$sizeLimit = TAMANIOMB * 1024 * 1024;
define("TAMANIOARCHIVOS",$sizeLimit);

define("TAMANIOAUDIOMB",8);
$sizeLimitAudio = TAMANIOAUDIOMB * 1024 * 1024;
define("TAMANIOARCHIVOSAUDIO",$sizeLimitAudio);

define("CARPETA_SERVIDOR_MULTIMEDIA","/multimedia/");
define("CARPETA_SERVIDOR_MULTIMEDIA_FISICA",DOCUMENT_ROOT.CARPETA_SERVIDOR_MULTIMEDIA);

//CARPETA QUE SE ENCUENTRA DENTRO DE MULTIMEDIA
define("CARPETAFILEMANAGER","files/");
define("CARPETA_SERVIDOR_MULTIMEDIA_ARCHIVOS","archivos/");

define("CARPETA_SERVIDOR_MULTIMEDIA_FONDOS","fondos/");
define("CARPETA_SERVIDOR_MULTIMEDIA_FONDOS_N","N/");
define("CARPETA_SERVIDOR_MULTIMEDIA_FONDOS_THUMB","Thumbs/");
define("TAMANIOFONDOSTHUMB",250);

define("CARPETA_SERVIDOR_MULTIMEDIA_ORIGINALES","N/");
define("CARPETA_SERVIDOR_MULTIMEDIA_THUMBS","Thumbs/");
define("CARPETA_SERVIDOR_MULTIMEDIA_THUMBSXL","ThumbsXL/");

define("CARPETA_SERVIDOR_MULTIMEDIA_TMP","tmp/");
define("CARPETA_SERVIDOR_MULTIMEDIA_TMP_FISICA",CARPETA_SERVIDOR_MULTIMEDIA_FISICA.CARPETA_SERVIDOR_MULTIMEDIA_TMP);

define("CARPETA_SERVIDOR_MULTIMEDIA_NOTICIAS","noticias/");

define("DOMINIO_SERVIDOR_MULTIMEDIA","/multimedia/");
define("DOMINIOADMIN",DOMINIOPORTAL.RAIZPORTAL."bt-admin/");
define("DOMINIOWEB",DOMINIOPORTAL.RAIZPORTAL);

define("CARPETA_SERVIDOR_MULTIMEDIA_TAPAS","N/");
define("CARPETA_SERVIDOR_MULTIMEDIA_TAPAS_THUMBS","Thumbs/");
define("CROPEATHUMBTAPA",1);
define("TAPAMAXANCHOTHUMB",250);
define("TAPAMAXALTOTHUMB",300);


define("PUBLICA",DOCUMENT_ROOT."/public/");
define("CARPETARSS",DOCUMENT_ROOT."/rss/");
define("CARPETAMOBILEXML",DOCUMENT_ROOT."/xmlmobile/");
define("CARPETAJSON",DOCUMENT_ROOT."/json/");
define("CARPETASITEMAP",DOCUMENT_ROOT."/");
define("PUBLICADEADMIN","../");

define("TAMANIOAVATARL",200);
define("TAMANIOAVATARM",55);
define("TAMANIOAVATARS",30	);
define("CARPETA_SERVIDOR_MULTIMEDIA_AVATAR","usuarios/");
define("CARPETA_SERVIDOR_MULTIMEDIA_AVATAR_L","avatar-l/");
define("CARPETA_SERVIDOR_MULTIMEDIA_AVATAR_M","avatar-m/");
define("CARPETA_SERVIDOR_MULTIMEDIA_AVATAR_S","avatar-s/");

define("DOMINIORAIZSITE","/");

define("CLAVEENCRIPTACION","dDtp2d$23#43");

/*GOOGLE CAPTCHA*/
define("LOGINCAPTCHA",0);
define("PUBLICKEYCAPTCHA","6LeARRsUAAAAAJ-pveqzhd4cdkCB6t089hrK1TI5");
define("PRIVATEKEYCAPTCHA","6LeARRsUAAAAAPgNXfAWlKyu5ra4wVAMbi7H15-G");
define("GOOGLEAPIKEY","AIzaSyC5bvqOvZNZqiTLLVPe8ZEOgMgsDqy5Y_M");

define("CARPETA_SERVIDOR_MULTIMEDIA_ALBUM","ALBUM/");

/*CONFIGURACION DE MAIL SALIENTE*/
//ENVIAREMAIL 1 - SI
//ENVIAREMAIL 0 - NO
define("ENVIAREMAIL",'1');
define("ENVIAMAILEXTERNO",'1');
define("EMAIL_FROMNAME","Bigtree Studio SRL");
define("EMAIL_FROM","xxx@gmail.com");
define("SMTP","1");
define("SMTP_HOST","smtp.gmail.com");
define("SMTP_USER","");
define("SMTP_PASSW","");
define("SMTP_PORT","465");
define("SMTP_SSL","1");
define("SMTP_TLS","0");

define("YOUTUBE_EMBED","https://www.youtube.com/embed/");
?>