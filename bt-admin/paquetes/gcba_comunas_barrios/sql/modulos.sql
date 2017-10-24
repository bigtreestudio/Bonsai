SET FOREIGN_KEY_CHECKS=0;
SET AUTOCOMMIT = 0;
START TRANSACTION;


SET @Modulo = "4502";
INSERT INTO archivos (archivonom, ultmodusuario, ultmodfecha)  VALUES ('gcba_comunas_barrios.php',1,'2017-08-07 16:54:48');
INSERT INTO archivos (archivonom, ultmodusuario, ultmodfecha)  VALUES ('gcba_comunas_barrios_upd.php',1,'2017-08-07 16:54:48');
INSERT INTO archivos (archivonom, ultmodusuario, ultmodfecha)  VALUES ('gcba_comunas_barrios_am.php',1,'2017-08-07 16:54:48');
INSERT INTO archivos (archivonom, ultmodusuario, ultmodfecha)  VALUES ('gcba_comunas_barrios_lst_ajax.php',1,'2017-08-07 16:54:48');
INSERT INTO modulos (modulocod, modulodesc, modulotextomenu, archivocod, modulosec, modulomostrar, moduloimg, modulodash, moduloacciones, ultmodusuario, ultmodfecha)( SELECT @Modulo,'comuna_barrio', 'comuna_barrio', archivocod, '10', 'S', '', '0', '0', '1', '2017-08-07 16:54:48' FROM archivos WHERE archivonom="gcba_comunas_barrios.php");
INSERT INTO modulos_archivos (modulocod, archivocod, ultmodusuario, ultmodfecha) (SELECT @Modulo ,archivocod, '1', '2017-08-07 16:54:48' FROM archivos WHERE archivonom="gcba_comunas_barrios.php");
INSERT INTO modulos_archivos (modulocod, archivocod, ultmodusuario, ultmodfecha) (SELECT @Modulo ,archivocod, '1', '2017-08-07 16:54:48' FROM archivos WHERE archivonom="gcba_comunas_barrios_upd.php");
INSERT INTO modulos_archivos (modulocod, archivocod, ultmodusuario, ultmodfecha) (SELECT @Modulo ,archivocod, '1', '2017-08-07 16:54:48' FROM archivos WHERE archivonom="gcba_comunas_barrios_am.php");
INSERT INTO modulos_archivos (modulocod, archivocod, ultmodusuario, ultmodfecha) (SELECT @Modulo ,archivocod, '1', '2017-08-07 16:54:48' FROM archivos WHERE archivonom="gcba_comunas_barrios_lst_ajax.php");
INSERT INTO gruposmod_modulos (grupomodcod, modulocod, ultmodusuario, ultmodfecha) VALUES(43,@Modulo,'1','2017-08-07 16:54:48');
INSERT INTO roles_modulos (rolcod, modulocod, ultmodusuario, ultmodfecha) VALUES ('10' ,@Modulo, '1', '2017-08-07 16:54:48');



SET FOREIGN_KEY_CHECKS=1;
COMMIT;
