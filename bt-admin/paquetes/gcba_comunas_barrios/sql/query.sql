SET FOREIGN_KEY_CHECKS=0;
SET AUTOCOMMIT = 0;
START TRANSACTION;


-- INSERT INTO stored_procedures (spnombre, spoperacion, sptabla, spsqlstring, spobserv, ultmodusuario, ultmodfecha) VALUES ('sel_gcba_comunas_combo_comunanumero','SEL','GCBA_COMUNAS','SELECT * FROM gcba_comunas WHERE comunaestado =10','','1','2011-11-07 16:35:43');
INSERT INTO stored_procedures (spnombre, spoperacion, sptabla, spsqlstring, spobserv, ultmodusuario, ultmodfecha) VALUES ('sel_gcba_barrios_combo_barrionombre','SEL','GCBA_BARRIOS','SELECT * FROM gcba_barrios WHERE barrioestado =10','','1','2011-11-07 16:35:43');
INSERT INTO stored_procedures (spnombre, spoperacion, sptabla, spsqlstring, spobserv, ultmodusuario, ultmodfecha) VALUES ('sel_gcba_comunas_barrios_xcomunabarriocod','SEL','GCBA_COMUNAS_BARRIOS','SELECT * FROM gcba_comunas_barrios WHERE comunabarriocod="#pcomunabarriocod#"','','1','2011-11-07 16:35:43');
INSERT INTO stored_procedures (spnombre, spoperacion, sptabla, spsqlstring, spobserv, ultmodusuario, ultmodfecha) VALUES ('sel_gcba_comunas_barrios_busqueda_avanzada','SEL','GCBA_COMUNAS_BARRIOS','SELECT a.*, b.comunanumero as comunacoddesc, c.barrionombre as barriocoddesc FROM gcba_comunas_barrios AS a 
LEFT JOIN gcba_comunas AS b ON a.comunacod = b.comunacod 
LEFT JOIN gcba_barrios AS c ON a.barriocod = c.barriocod 
WHERE 
IF("#pxcomunabarriocod#",a.comunabarriocod="#pcomunabarriocod#",1) 
AND 
IF("#pxcomunacod#",a.comunacod="#pcomunacod#",1) 
AND 
IF("#pxbarriocod#",a.barriocod="#pbarriocod#",1) 
ORDER BY #porderby# #plimit#','','1','2011-11-07 16:35:43');
INSERT INTO stored_procedures (spnombre, spoperacion, sptabla, spsqlstring, spobserv, ultmodusuario, ultmodfecha) VALUES ('ins_gcba_comunas_barrios','INS','GCBA_COMUNAS_BARRIOS','INSERT INTO gcba_comunas_barrios (
	comunacod,
	barriocod,
	ultmodfecha,
	ultmodusuario
) VALUES (
	"#pcomunacod#",
	"#pbarriocod#",
	"#pultmodfecha#",
	"#pultmodusuario#"
)
','','1','2011-11-07 16:35:43');
INSERT INTO stored_procedures (spnombre, spoperacion, sptabla, spsqlstring, spobserv, ultmodusuario, ultmodfecha) VALUES ('upd_gcba_comunas_barrios_xcomunabarriocod','UPD','GCBA_COMUNAS_BARRIOS','UPDATE gcba_comunas_barrios SET 
	comunacod="#pcomunacod#",
	barriocod="#pbarriocod#",
	ultmodfecha="#pultmodfecha#",
	ultmodusuario="#pultmodusuario#"
 WHERE comunabarriocod="#pcomunabarriocod#"','','1','2011-11-07 16:35:43');
INSERT INTO stored_procedures (spnombre, spoperacion, sptabla, spsqlstring, spobserv, ultmodusuario, ultmodfecha) VALUES ('del_gcba_comunas_barrios_xcomunabarriocod','DEL','GCBA_COMUNAS_BARRIOS','DELETE FROM gcba_comunas_barrios WHERE comunabarriocod="#pcomunabarriocod#"','','1','2011-11-07 16:35:43');



SET FOREIGN_KEY_CHECKS=1;
COMMIT;
