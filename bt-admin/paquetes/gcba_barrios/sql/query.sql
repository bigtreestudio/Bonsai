SET FOREIGN_KEY_CHECKS=0;
SET AUTOCOMMIT = 0;
START TRANSACTION;


INSERT INTO stored_procedures (spnombre, spoperacion, sptabla, spsqlstring, spobserv, ultmodusuario, ultmodfecha) VALUES ('sel_gcba_barrios_xbarriocod','SEL','GCBA_BARRIOS','SELECT * FROM gcba_barrios WHERE barriocod="#pbarriocod#"','','1','2011-11-07 16:35:43');
INSERT INTO stored_procedures (spnombre, spoperacion, sptabla, spsqlstring, spobserv, ultmodusuario, ultmodfecha) VALUES ('sel_gcba_barrios_busqueda_avanzada','SEL','GCBA_BARRIOS','SELECT a.* FROM gcba_barrios AS a 
WHERE 
IF("#pxbarriocod#",a.barriocod="#pbarriocod#",1) 
AND 
IF("#pxbarrionombre#", LCASE(a.barrionombre) LIKE LCASE("%#pbarrionombre#%"),1) 
AND 
IF("#pxbarrioestado#",a.barrioestado IN (#pbarrioestado#),1) 
ORDER BY #porderby# #plimit#','','1','2011-11-07 16:35:43');
INSERT INTO stored_procedures (spnombre, spoperacion, sptabla, spsqlstring, spobserv, ultmodusuario, ultmodfecha) VALUES ('ins_gcba_barrios','INS','GCBA_BARRIOS','INSERT INTO gcba_barrios (
	barrionombre,
	barriodesc,
	barrioestado,
	ultmodfecha,
	ultmodusuario
) VALUES (
	"#pbarrionombre#",
	"#pbarriodesc#",
	"#pbarrioestado#",
	"#pultmodfecha#",
	"#pultmodusuario#"
)
','','1','2011-11-07 16:35:43');
INSERT INTO stored_procedures (spnombre, spoperacion, sptabla, spsqlstring, spobserv, ultmodusuario, ultmodfecha) VALUES ('upd_gcba_barrios_xbarriocod','UPD','GCBA_BARRIOS','UPDATE gcba_barrios SET 
	barrionombre="#pbarrionombre#",
	barriodesc="#pbarriodesc#",
	ultmodfecha="#pultmodfecha#",
	ultmodusuario="#pultmodusuario#"
 WHERE barriocod="#pbarriocod#"','','1','2011-11-07 16:35:43');
INSERT INTO stored_procedures (spnombre, spoperacion, sptabla, spsqlstring, spobserv, ultmodusuario, ultmodfecha) VALUES ('del_gcba_barrios_xbarriocod','DEL','GCBA_BARRIOS','DELETE FROM gcba_barrios WHERE barriocod="#pbarriocod#"','','1','2011-11-07 16:35:43');
INSERT INTO stored_procedures (spnombre, spoperacion, sptabla, spsqlstring, spobserv, ultmodusuario, ultmodfecha) VALUES ('upd_gcba_barrios_barrioestado_xbarriocod','UPD','GCBA_BARRIOS','UPDATE gcba_barrios SET barrioestado="#pbarrioestado#",
ultmodfecha="#pultmodfecha#",
ultmodusuario="#pultmodusuario#" WHERE barriocod="#pbarriocod#"','','1','2011-11-07 16:35:43');



SET FOREIGN_KEY_CHECKS=1;
COMMIT;
