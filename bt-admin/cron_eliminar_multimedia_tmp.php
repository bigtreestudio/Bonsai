<?php  
require('./config/include.php');

//----------------------------------------------------------------------------------------- 	
// Cron que borra los archivos de la carpeta multimedia 
//(Solo los temporales, los archivos los cuales fueron subidos y cancelados)
$dir = CARPETA_SERVIDOR_MULTIMEDIA_FISICA.CARPETA_SERVIDOR_MULTIMEDIA_TMP;
if (is_dir($dir)) {
    if ($dh = opendir($dir)) {
		while (($file = readdir($dh)) !== false) {
			if($file!='.' && $file!='..')
			{
				$datefile = filectime($dir . $file);
				$now = strtotime("now");
				if (($now-TIEMPOSESION)>$datefile)
					unlink($dir . $file);
			}
        }
        closedir($dh);	}
}
?>