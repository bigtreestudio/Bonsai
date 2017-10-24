<?php 

class cWEATHER {
	
//	ftp://telam:an_telam@ftp.smn.gov.ar/PronCiu.txt
	const TIMEZONE = 'America/Argentina/Buenos_Aires';
	const FTP_SERVER = 'ftp.smn.gov.ar';
	const FTP_PORT = 21;
	const FTP_USER = 'telam';
	const FTP_PASSWORD = 'an_telam';
	const REMOTE_STATUS_FILE = 'total.txt';
	const REMOTE_FORECAST_FILE = 'PronCiu.txt';
	const STATUS_FILE_PATH ='total.txt';
	const FORECAST_FILE_PATH = 'PronCiu.txt';
	const XML_FILE_PATH = '../../xml/clima.xml';
	const ICONS_PATH = 'imagenes/clima';
	const LOCATIONS_FOLDER = '../../xml/localidades/';
	
	
	public static function getRemoteWheaterFiles()
	{
		putenv("TZ=".self::TIMEZONE);
		
		$statusfp = fopen(self::STATUS_FILE_PATH, 'w+');
		
		if ($statusfp === false)
		{
			echo "Error, no se pudo abrir el archivo de Estado Actual : ".self::STATUS_FILE_PATH;
			die();
		}
		
		$forecastfp = fopen(self::FORECAST_FILE_PATH, 'w+');
		
		if ($forecastfp === false)
		{
			echo "Error, no se pudo abrir el archivo de Pronostico : ".self::FORECAST_FILE_PATH;
			die();
		}
		
		$ftp = ftp_connect(self::FTP_SERVER, self::FTP_PORT);
		
		if (!$ftp)
		{
			echo "Error, no conecta a : ".self::FTP_SERVER;
			die();
		}
		
		$login = @ftp_login($ftp, self::FTP_USER, self::FTP_PASSWORD);
		
		if (!$login)
		{
			echo "Error, login incorrecto en el FTP : ".self::FTP_SERVER;
			die();
		}
		
		ftp_pasv($ftp, true);
		
		if (!ftp_get($ftp, self::STATUS_FILE_PATH, self::REMOTE_STATUS_FILE, FTP_BINARY, 0))
		{
			echo "Error, no se pudo leer el archivo remoto de Pronostico : ".self::REMOTE_STATUS_FILE;
			die();
		}
		
		if (!ftp_get($ftp, self::FORECAST_FILE_PATH, self::REMOTE_FORECAST_FILE, FTP_BINARY, 0))
		{
			echo "Error, no se pudo leer el archivo remoto de Pronostico : ".self::REMOTE_FORECAST_FILE;
			die();
		}
		
		ftp_close($ftp);
		
		fclose($statusfp);
		fclose($forecastfp);
		cWEATHER::importLocalidades();
	}
	
	public static function getWeatherIcon($iconCode)
	{
		static $clima_icons = null;
		
		if (is_null($clima_icons))
		{
			$clima_icons = array(
				0 => self::ICONS_PATH.'/sol.jpg',
				1 => self::ICONS_PATH.'/sol_nublado.jpg',
				2 => self::ICONS_PATH.'/nublado.jpg',
				3 => self::ICONS_PATH.'/nublado.jpg',
				4 => self::ICONS_PATH.'/tormenta.jpg',
				5 => self::ICONS_PATH.'/nieve.gif',
				6 => self::ICONS_PATH.'/lluvia.jpg',
				7 => self::ICONS_PATH.'/nieve.gif',
				8 => self::ICONS_PATH.'/lluvia.jpg',
				9 => self::ICONS_PATH.'/nublado.jpg',
				10 => self::ICONS_PATH.'/nublado.jpg',
				11 => self::ICONS_PATH.'/inestable.gif',
				12 => self::ICONS_PATH.'/inestable.gif',
				13 => self::ICONS_PATH.'/lluvia.jpg',
				14 => self::ICONS_PATH.'/inestable.gif',
				15 => self::ICONS_PATH.'/nublado.jpg',
				16 => self::ICONS_PATH.'/sol_nublado.jpg',
			);
		}
		
		return $clima_icons[$iconCode];
	}

	public static function parseWeatherStatusFile($filePath)
	{
		$result = array();
		
		$file_content = iconv('ISO-8859-1', 'UTF-8', file_get_contents($filePath));
		
		$records = explode('/', $file_content);
		
		foreach ($records as $record)
		{
			$fields = explode(';', $record);
			
			$result[] = array(
				'estacion' => trim($fields[0]),
				'fecha' => trim($fields[1]),
				'hora' => trim($fields[2]),
				'fenomenos' => trim($fields[3]),
				'visibilidad' => trim($fields[4]),
				'temperatura' => trim($fields[5]),
				'sensacion_termica' => trim($fields[6]),
				'humedad' => trim($fields[7]),
				'viento' => trim($fields[8]),
				'presion' => trim($fields[9]),
				'icono' => trim($fields[10]),
			);
		}
		
		return $result;
	}
	
	
	public static function findWeatherStatus(&$statusRecords, $station)
	{
		foreach ($statusRecords as $record)
		{
			if ($record['estacion'] == $station)
			{
				return $record;
			}
		}
		
		return null;
	}
	
	
	public static function parseWeatherForecastFile($filePath)
	{
		$result = array();
		
		$fileContent = iconv('ISO-8859-1', 'UTF-8', file_get_contents($filePath));
		$fileContent = substr($fileContent, strpos($fileContent, "\n"));
		
		$records = explode('/', $fileContent);
		
		foreach ($records as $record)
		{
			$fields = explode('|', $record);
			
			$provincia = trim($fields[0]);
			$hora = trim($fields[3]);
			$idDia = trim($fields[4]);
			$esTarde = (date('G', strtotime($hora)) > 12);
			
			if ($esTarde && $idDia == 0)
			{
				$newRow = array(
					'provincia' => $provincia,
					'ciudad' => trim($fields[1]),
					'fecha' => trim($fields[2]),
					'hora' => trim($fields[3]),
					'id_dia' => trim($fields[4]),
					'nombre_dia' => trim($fields[5]),
					'icono_tarde' => trim($fields[6]),
					'texto_tarde' => trim($fields[7]),
					'temperatura' => trim($fields[8]),
//					'hay_minima_suburbana' => null,
//					'minima_suburbana' => null,
//					'hay_radiacion' => null,
//					'radiacion' => null,
				);
				
//				if ($provincia == 'CAPITAL FEDERAL')
//				{
//					// Define fields minima_suburbana & radiacion
//				}
			}
			else
			{
				$newRow = array(
					'provincia' => $provincia,
					'ciudad' => trim($fields[1]),
					'fecha' => trim($fields[2]),
					'hora' => trim($fields[3]),
					'id_dia' => trim($fields[4]),
					'nombre_dia' => trim($fields[5]),
					'icono_maniana' => trim($fields[6]),
					'texto_maniana' => trim($fields[7]),
					'icono_tarde' => trim($fields[8]),
					'texto_tarde' => trim($fields[9]),
					'temperatura_minima' => trim($fields[10]),
					'temperatura_maxima' => trim($fields[11]),
//					'hay_minima_suburbana' => null,
//					'minima_suburbana' => null,
//					'hay_radiacion' => null,
//					'radiacion' => null,
				);
				
//				if ($provincia == 'CAPITAL FEDERAL')
//				{
//					// Define fields minima_suburbana & radiacion
//				}
			}
			
			$result[] = $newRow;
		}
		
		return $result;
	}
	
	
	public static function findWeatherForecast(&$forecastRecords, $state, $city, $dayId)
	{
		foreach ($forecastRecords as $record)
		{
			if ($record['provincia'] == $state && $record['ciudad'] == $city &&  $record['id_dia'] == $dayId)
			{
				return $record;
			}
		}
		
		return null;
	}
	
	
	public static function translateWeatherForecastIndex($string)
	{
		static $replacement = array(
			'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'A', 'Å' => 'A',
			'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'a', 'å' => 'a',
			'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O', 'Ö' => 'O', 'Ø' => 'O',
			'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o', 'ö' => 'o', 'ø' => 'o',
			'È' => 'E', 'É' => 'E', 'Ê' => 'E', 'Ë' => 'E',
			'è' => 'e', 'é' => 'e', 'ê' => 'e', 'ë' => 'e',
			'Ì' => 'I', 'Í' => 'I', 'Î' => 'I', 'Ï' => 'I',
			'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i',
			'Ù' => 'U', 'Ú' => 'U', 'Û' => 'U', 'Ü' => 'U',
			'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ü' => 'u',
			'Ç' => 'C', 'ç' => 'c', 'ÿ' => 'y', 'Ñ' => 'N', 'ñ' => 'n',
		);
		
		$translatedString = strip_tags($string);
		$translatedString = strtr($translatedString, $replacement);
		$translatedString = strtoupper($translatedString);
		
		return $translatedString;
	}
        
        public static function getProvincias(){
            $result = array(
                array("prov_nombre" => "Buenos Aires"),
                array("prov_nombre" => "Capital Federal"),
                array("prov_nombre" => "Catamarca"),
                array("prov_nombre" => "Chaco"),
                array("prov_nombre" => "Chubut"),
                array("prov_nombre" => "Córdoba"),
                array("prov_nombre" => "Corrientes"),
                array("prov_nombre" => "Entre Ríos"),
                array("prov_nombre" => "Formosa"),
                array("prov_nombre" => "Jujuy"),
                array("prov_nombre" => "La Pampa"),
                array("prov_nombre" => "La Rioja"),
                array("prov_nombre" => "Mendoza"),
                array("prov_nombre" => "Misiones"),
                array("prov_nombre" => "Neuquén"),
                array("prov_nombre" => "Río Negro"),
                array("prov_nombre" => "Salta"),
                array("prov_nombre" => "San Juan"),
                array("prov_nombre" => "San Luis"),
                array("prov_nombre" => "Santa Cruz"),
                array("prov_nombre" => "Santa Fe"),
                array("prov_nombre" => "Santiago del Estero"),
                array("prov_nombre" => "Tierra del Fuego"),
                array("prov_nombre" => "Tucumán"),
            );
            
            return $result;
        }
        
        public static function getProvinciaJson($provincia){
            $fileName = PROJECT_PATH . substr(self::LOCATIONS_FOLDER, 2) . self::deepLower($provincia) . ".json";
            $fileContent = file_get_contents($fileName);
            return $fileContent;
        }
        
        public static function importLocalidades(){
            $provincias = self::getProvincias();
            $localidades = array();
            
            foreach ($provincias as $prov) {
                $localidades[$prov["prov_nombre"]] = self::getLocalidadesByProvincia($prov["prov_nombre"]);
            }
            
            return $localidades;
            
        }
        
        public static function deepLower($texto){
            //Letras minúsculas com acentos 
            $texto = strtr($texto, " 
            ÁÉÍÓÚÑ 
            ", " 
            áéíóúñ
            "); 
            return strtolower($texto); 
 
        }
        
        public static function getLocalidadesByProvincia($provincia){
            setlocale(LC_ALL, 'es_AR');
            $fileContent = iconv('ISO-8859-1', 'UTF-8', file_get_contents(self::REMOTE_FORECAST_FILE));
            $fileContent = substr($fileContent, strpos($fileContent, "\n"));

            $records = explode('/', $fileContent);
            $result = array();
            $ciudades = array();
            
            foreach ($records as $record)
            {
                $record = str_replace("\r\n", "", $record);
                $fields = explode('|', $record);
                $provNombre = self::deepLower((trim($fields[0])));
                $provincia = self::deepLower(trim($provincia));
                if($provNombre == $provincia){
                    $ciudades[$provincia][$fields[1]]["pronostico"][] = $fields;
                }
            }
            //Aca abrir el otro archivo y ver si existe la localidad
		
            $fileContent = iconv('ISO-8859-1', 'UTF-8', file_get_contents(self::REMOTE_STATUS_FILE));
            $fileContent = substr($fileContent, strpos($fileContent, "\n"));

            $records = explode('/', $fileContent);
            
            foreach ($ciudades as $provincia => $provinciaInfo) {
                foreach ($provinciaInfo as $ciudadNombre => $ciudadData) {
                    $ciudad = self::deepLower(trim($ciudadNombre));
                    foreach ($records as $record) {
                        $record = str_replace("\r\n", "", $record);
                        $fields = explode(';', $record);
                        $ciudadNombre = self::deepLower(trim($fields[0]));
                        if($ciudadNombre==$ciudad){
                            $result[$ciudad] = array("pronostico" => $ciudadData["pronostico"],
                                                "actual" => $fields);
                        }
                    }
                }
            }		
			$provincia=str_replace(array('á','é','í','ó','ú'),array('a','e','i','o','u'),$provincia);
            cFILES::guardarArchivo(self::LOCATIONS_FOLDER . utf8_decode($provincia) . ".json", json_encode($result));
        }

	public static function getCapitals()
	{
		static $capitals = array(
			array(
				'provincia' => 'Capital Federal',
				'ciudad' => 'Buenos Aires',
				'estadoEstacion' => 'Buenos Aires',
				'pronosticoProvincia' => 'CAPITAL FEDERAL',
				'pronosticoCiudad' => 'Buenos Aires',
			),
			array(
				'provincia' => 'Buenos Aires',
				'ciudad' => 'La Plata',
				'estadoEstacion' => 'La Plata',
				'pronosticoProvincia' => 'BUENOS AIRES',
				'pronosticoCiudad' => 'LA PLATA',
			),
			array(
				'provincia' => 'Catamarca',
				'ciudad' => 'San Fernando del Valle de Catamarca',
				'estadoEstacion' => 'Catamarca',
				'pronosticoProvincia' => 'CATAMARCA',
				'pronosticoCiudad' => 'CATAMARCA',
			),
			array(
				'provincia' => 'Chaco',
				'ciudad' => 'Resistencia',
				'estadoEstacion' => 'Resistencia',
				'pronosticoProvincia' => 'CHACO',
				'pronosticoCiudad' => 'RESISTENCIA',
			),
			array(
				'provincia' => 'Chubut',
				'ciudad' => 'Trelew',
				'estadoEstacion' => 'Trelew',
				'pronosticoProvincia' => 'CHUBUT',
				'pronosticoCiudad' => 'TRELEW',
			),
			array(
				'provincia' => 'Corrientes',
				'ciudad' => 'Ciudad de Corrientes',
				'estadoEstacion' => 'Corrientes',
				'pronosticoProvincia' => 'CORRIENTES',
				'pronosticoCiudad' => 'CORRIENTES',
			),
			array(
				'provincia' => 'Córdoba',
				'ciudad' => 'Ciudad de Córdoba',
				'estadoEstacion' => 'Córdoba',
				'pronosticoProvincia' => 'CÓRDOBA',
				'pronosticoCiudad' => 'CÓRDOBA',
			),
			array(
				'provincia' => 'Entre Ríos',
				'ciudad' => 'Paraná',
				'estadoEstacion' => 'Paraná',
				'pronosticoProvincia' => 'ENTRE RÍOS',
				'pronosticoCiudad' => 'PARANÁ',
			),
			array(
				'provincia' => 'Formosa',
				'ciudad' => 'Ciudad de Formosa',
				'estadoEstacion' => 'Formosa',
				'pronosticoProvincia' => 'FORMOSA',
				'pronosticoCiudad' => 'FORMOSA',
			),
			array(
				'provincia' => 'Jujuy',
				'ciudad' => 'San Salvador de Jujuy',
				'estadoEstacion' => 'Jujuy',
				'pronosticoProvincia' => 'JUJUY',
				'pronosticoCiudad' => 'JUJUY',
			),
			array(
				'provincia' => 'La Pampa',
				'ciudad' => 'Santa Rosa',
				'estadoEstacion' => 'Santa Rosa',
				'pronosticoProvincia' => 'LA PAMPA',
				'pronosticoCiudad' => 'SANTA ROSA',
			),
			array(
				'provincia' => 'La Rioja',
				'ciudad' => 'Ciudad de La Rioja',
				'estadoEstacion' => 'La Rioja',
				'pronosticoProvincia' => 'LA RIOJA',
				'pronosticoCiudad' => 'LA RIOJA',
			),
			array(
				'provincia' => 'Mendoza',
				'ciudad' => 'Ciudad de Mendoza',
				'estadoEstacion' => 'Mendoza',
				'pronosticoProvincia' => 'MENDOZA',
				'pronosticoCiudad' => 'MENDOZA',
			),
			array(
				'provincia' => 'Misiones',
				'ciudad' => 'Posadas',
				'estadoEstacion' => 'Posadas',
				'pronosticoProvincia' => 'MISIONES',
				'pronosticoCiudad' => 'POSADAS',
			),
			array(
				'provincia' => 'Neuquén',
				'ciudad' => 'Ciudad de Neuquén',
				'estadoEstacion' => 'Neuquén',
				'pronosticoProvincia' => 'NEUQUÉN',
				'pronosticoCiudad' => 'NEUQUÉN',
			),
			array(
				'provincia' => 'Río Negro',
				'ciudad' => 'Viedma',
				'estadoEstacion' => 'Viedma',
				'pronosticoProvincia' => 'RÍO NEGRO',
				'pronosticoCiudad' => 'VIEDMA',
			),
			array(
				'provincia' => 'Salta',
				'ciudad' => 'Ciudad de Salta',
				'estadoEstacion' => 'Salta',
				'pronosticoProvincia' => 'SALTA',
				'pronosticoCiudad' => 'SALTA',
			),
			array(
				'provincia' => 'San Juan',
				'ciudad' => 'Ciudad de San Juan',
				'estadoEstacion' => 'San Juan',
				'pronosticoProvincia' => 'SAN JUAN',
				'pronosticoCiudad' => 'SAN JUAN',
			),
			array(
				'provincia' => 'San Luis',
				'ciudad' => 'Ciudad de San Luis',
				'estadoEstacion' => 'San Luis',
				'pronosticoProvincia' => 'SAN LUIS',
				'pronosticoCiudad' => 'SAN LUIS',
			),
			array(
				'provincia' => 'Santa Cruz',
				'ciudad' => 'Río Gallegos',
				'estadoEstacion' => 'Río Gallegos',
				'pronosticoProvincia' => 'SANTA CRUZ',
				'pronosticoCiudad' => 'RÍO GALLEGOS',
			),
			array(
				'provincia' => 'Santa Fe',
				'ciudad' => 'Ciudad de Santa Fe',
				'estadoEstacion' => 'Santa Fe',
				'pronosticoProvincia' => 'SANTA FE',
				'pronosticoCiudad' => 'SANTA FE',
			),
			array(
				'provincia' => 'Santiago del Estero',
				'ciudad' => 'Ciudad de Santiago del Estero',
				'estadoEstacion' => 'Santiago del Estero',
				'pronosticoProvincia' => 'SANTIAGO DEL ESTERO',
				'pronosticoCiudad' => 'SANTIAGO DEL ESTERO',
			),
			array(
				'provincia' => 'Tierra del Fuego',
				'ciudad' => 'Ushuaia',
				'estadoEstacion' => 'Ushuaia',
				'pronosticoProvincia' => 'TIERRA DEL FUEGO',
				'pronosticoCiudad' => 'USHUAIA',
			),
			array(
				'provincia' => 'Tucumán',
				'ciudad' => 'San Miguel de Tucumán',
				'estadoEstacion' => 'Tucumán',
				'pronosticoProvincia' => 'TUCUMÁN',
				'pronosticoCiudad' => 'TUCUMÁN',
			),
		);
		
		return $capitals;
	}
	
	
	public static function getWeatherInfo()
	{
		self::getRemoteWheaterFiles();
		
		$weatherStatus = self::parseWeatherStatusFile(self::STATUS_FILE_PATH);
		$weatherForecast = self::parseWeatherForecastFile(self::FORECAST_FILE_PATH);
		
		$capitals = self::getCapitals();
		
		$result = array();
		
		foreach ($capitals as $capital)
		{
			$weatherStatusRecord = self::findWeatherStatus($weatherStatus, $capital['estadoEstacion']);
			$weatherForecastRecord = self::findWeatherForecast($weatherForecast, $capital['pronosticoProvincia'], $capital['pronosticoCiudad'], 1);
			
			$result[] = array(
				'provincia' => $capital['provincia'],
				'temperatura' => $weatherStatusRecord['temperatura'],
				'humedad' => $weatherStatusRecord['humedad'],
				'temperatura_maxima' => isset($weatherForecastRecord['temperatura_maxima']) ? $weatherForecastRecord['temperatura_maxima'] : '',
				'temperatura_minima' => isset($weatherForecastRecord['temperatura_minima']) ? $weatherForecastRecord['temperatura_minima'] : '',
				'icono' => $weatherStatusRecord['icono'],
				'texto_maniana' => isset($weatherForecastRecord['texto_maniana']) ? $weatherForecastRecord['texto_maniana'] : '',
				'icono_maniana' => isset($weatherForecastRecord['icono_maniana']) ? $weatherForecastRecord['icono_maniana'] : '',
				'texto_tarde' => isset($weatherForecastRecord['texto_tarde']) ? $weatherForecastRecord['texto_tarde'] : '',
				'icono_tarde' => isset($weatherForecastRecord['icono_tarde']) ? $weatherForecastRecord['icono_tarde'] : '',
				'fenomenos' => isset($weatherStatusRecord['fenomenos']) ? $weatherStatusRecord['fenomenos'] : '',
				'visibilidad' => isset($weatherStatusRecord['visibilidad']) ? $weatherStatusRecord['visibilidad'] : '',
				'sensacion_termica' => isset($weatherStatusRecord['sensacion_termica']) ? $weatherStatusRecord['sensacion_termica'] : '',
				'viento' => isset($weatherStatusRecord['viento']) ? $weatherStatusRecord['viento'] : '',
				'presion' => isset($weatherStatusRecord['presion']) ? $weatherStatusRecord['presion'] : '',
				'hora' => isset($weatherStatusRecord['hora']) ? $weatherStatusRecord['hora'] : ''
			);
		}
		
		return $result;
	}
}

class cFILES {
    
    
    public static function guardarArchivo($fileName, $content){
        $result = false;
        if(file_exists($fileName)){
            unlink($fileName);
        }
        $handle = fopen($fileName, 'a');

        // Write $somecontent to our opened file.
        if (fwrite($handle, $content) != FALSE) {
            $result = true;
        }
        fclose($handle);
        return $result;
    }
    
}
