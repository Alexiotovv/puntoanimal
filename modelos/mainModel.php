<?php 

	if ($peticionAjax) {
		// desde carpeta ajax
	    require_once "../config/SERVER.php";
	} else {
		// desde index.php
	    require_once "./config/SERVER.php";
	}

	class mainModel{

		/* Funcion conectar a base de datos con PDO
		*  @return: $conexion
		*/
		protected static function conectar(){
			// SGBD,USER,PASS: contantes en /config/SERVER.php
			$conexion = new PDO(SGBD,USER,PASS);
        	$conexion->exec("SET CHARACTER SET utf8");
        	return $conexion;	
    	}

    	/* Ejecutar Consulta simple
	    *  @param:$consulta, sentencia sql 
	    *  @return: respuesta de la consulta $sql
	    */
	    protected static function ejecutar_consulta_simple($consulta){
		     $sql = self::conectar()->prepare($consulta);
		     $sql->execute();
		     return $sql;
		}

		/*  ENCRIPTAR CADENA DE TEXTO: MEDIANTE NUMERO Y SIMBOLOS 
	    *   @param: cadena de texto
	    *   @return: cadena encriptada
	    */
	    public function encryption($string){
	        $output=FALSE;
	        $key=hash('sha256', SECRET_KEY);
	        $iv=substr(hash('sha256', SECRET_IV),0,16);
	        $output=openssl_encrypt($string, METHOD, $key, 0, $iv);
	        $output=base64_encode($output);
	        return $output;

	    }

	    /*  DESENCRIPTAR CADENA DE TEXTO 
	    *   @param: cadena de texto encriptada
	    *   @return: cadena en su texto original
	    */
	    protected static function decryption($string){
	        $key=hash('sha256', SECRET_KEY);
	        $iv=substr(hash('sha256', SECRET_IV),0,16);
	        $output=openssl_decrypt(base64_decode($string), METHOD, $key, 0, $iv);
	        return $output;
	    }

	    /* Generar numeros aleatorios, identificar mascotas entre otro
	    *  @param: letra: , longitud: numeros de caracteres, num: numero correlativo
	    *  @retun: codigo generado ejm: CM12345-1
	    */
	    protected function generar_codigo_aleatorio($letra,$longitud,$num){
	        for ($i=1; $i<= $longitud ;$i++){
	            // rand: seleccionar un numero aleatorio entre 0 - 9
	            $numero = rand(0,9);
	            $letra.= $numero;

	        }
	        return $letra."-".$num;
	        // return $letra.$num;
	    }

	    /* Generar numeros aleatorios, identificar historial clinico
	    *  @param: letra: , longitud: numeros de caracteres
	    *  @retun: codigo generado ejm: HM-55567-1
	    */
	    public function generar_codigo_aleatorio_historial($letra,$longitud){
            $consulta=mainModel::ejecutar_consulta_simple("SELECT codHistorialM FROM historialmascota ");
			$num=($consulta->rowCount())+1;

	        for ($i=1; $i<= $longitud ;$i++){
	            // rand: seleccionar un numero aleatorio entre 0 - 9
	            $numero = rand(0,9);
	            $letra.= $numero;

	        }
	        return $letra."-".$num;
	    }
	    /* Convertir numero string, a decimal. para precios
	    *  @param: $num: string , numero en string
	    *  @param: numero en floatval
	    */
	    protected function tofloat($num) {
		    $dotPos = strrpos($num, '.');
		    $commaPos = strrpos($num, ',');
		    $sep = (($dotPos > $commaPos) && $dotPos) ? $dotPos :
		        ((($commaPos > $dotPos) && $commaPos) ? $commaPos : false);
		  
		    if (!$sep) {
		        return floatval(preg_replace("/[^0-9]/", "", $num));
		    }

		    return floatval(
		        preg_replace("/[^0-9]/", "", substr($num, 0, $sep)) . '.' .
		        preg_replace("/[^0-9]/", "", substr($num, $sep+1, strlen($num)))
		    );
		}

		/* Generar fecha en Castellano
		*  @param: $fecha , ejm dd-mm-aaaa. $tipo:INT formato a mostrar
		*  @return: $fechaFormato. con formato
		*/
		public static function fecha_castellano($fecha,$tipo){
			$fecha = substr($fecha, 0,10);
			$numeroDia = date('d', strtotime($fecha));
			$dia = date('l', strtotime($fecha));
			$mes = date('F', strtotime($fecha));
			$anio = date('Y', strtotime($fecha));
			$anio2 = date('y', strtotime($fecha));

			$dias_ES = array("Lunes","Martes","Miércoles","Jueves","Viernes","Sabado","Domingo");
			$dias_EN = array("Monday","Tuesday","Wednesday","Thursday","Friday","Saturday","Sunday");
			$nombredia = str_replace($dias_EN, $dias_ES, $dia);
			
			$meses_ES = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
			$meses_EN = array("January","February","March","April","May","June","July","August","September","October","November","December");
			$nombreMes = str_replace($meses_EN, $meses_ES, $mes);

			switch ($tipo){
				case 1:
					// Sabado 30 de Abril de 2020
					$fechaFormato=$nombredia." ".$numeroDia." de ".$nombreMes." de ".$anio;
					break;
				case 2:
					// Sabado 30 de Abril
					$fechaFormato=$nombredia." ".$numeroDia." de ".$nombreMes;
					break;
				case 3:
					// 30 Abril '20
					$fechaFormato=$numeroDia." ".$nombreMes." '".$anio2;
					break;
			}
			return $fechaFormato;

		}
		/* Calcular edad a partir de fecha de nacimiento
		*  @param: $fecha: fecha de nacimiento
		*  @return: $edad_final: 
		*/
		public static function calcular_edad($fecha){
			$fecha_nac = new DateTime(date('Y/m/d',strtotime($fecha)));
			$fecha_hoy =  new DateTime(date('Y/m/d',time()));
			$edad = date_diff($fecha_hoy,$fecha_nac);
			if($edad->format('%Y')=="00"){
				$edad_final = $edad->format('%m')." meses";
			}else{
				$edad_final = $edad->format('%Y')." año/s y ".$edad->format('%m')." mes/es";
			}
			return $edad_final;

		}	 

        /* Limpiar las entradas en los formulario, para BD
        *  @param: cadena de texto string
	    *  @return: cadena limpia
	    */
	    protected static function limpiar_cadena($cadena){
	        $cadena=trim($cadena);
	        $cadena=stripslashes($cadena);
	        $cadena=str_ireplace("<script>","", $cadena);
	        $cadena=str_ireplace("</script>","", $cadena);
	        $cadena=str_ireplace("<script src","", $cadena);
	        $cadena=str_ireplace("<script type=","", $cadena);
	        $cadena=str_ireplace("SELECT * FROM","", $cadena);
	        $cadena=str_ireplace("DELETE FROM","", $cadena);
	        $cadena=str_ireplace("INSERT INTO","", $cadena);
	        $cadena=str_ireplace("DROP TABLE","", $cadena);
	        $cadena=str_ireplace("DROP DATABASE","", $cadena);
	        $cadena=str_ireplace("DROP DATABASE","", $cadena);
	        $cadena=str_ireplace("TRUNCATE TABLE","", $cadena);
	        $cadena=str_ireplace("SHOW TABLES","", $cadena);
	        $cadena=str_ireplace("SHOW DATABASES","", $cadena);
	        $cadena=str_ireplace("<?php","", $cadena);
	        $cadena=str_ireplace("?>","", $cadena);
	        $cadena=str_ireplace("--","", $cadena);
	        $cadena=str_ireplace("<","", $cadena);
	        $cadena=str_ireplace(">","", $cadena);
	        $cadena=str_ireplace("^","", $cadena);
	        $cadena=str_ireplace("[","", $cadena);
	        $cadena=str_ireplace("]","", $cadena);
	        $cadena=str_ireplace("==","", $cadena);
	        $cadena=str_ireplace(";","", $cadena);
	        $cadena=str_ireplace("::","", $cadena);
	        $cadena=stripslashes($cadena);
	        $cadena=trim($cadena);
	        return $cadena;
	    }

	    /* verificar datos de los input de entrada 
	    * @param: $filtro: de input ejm: [a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,35},$cadena: campo a verificar
	    * @return: boolean true: con error
	    */
	    protected static function verificar_datos($filtro,$cadena){
	    	if(preg_match("/^".$filtro."$/", $cadena)){
	    		// sin error
	    		return false;
	    	}else{
	    		return true;
	    	}
	    }
	    /* Verificar file de foto
	    * @param: $foto: file de foto a verificar
	    * @return: boolean
	    */
	    protected static function verificar_foto($foto){
	    	// formato de imagen permitidos
			$permitidos = array("image/jpg","image/jpeg","image/png");
		
	    	if(in_array($foto["type"], $permitidos)){
	    		return true;
	    	}else{
	    		return false;
	    	}
	    }
	    /*  guardar foto segun formato PNG,JPEG
	    *	@param: destino_url: carpeta destino, $foto: files de archivo foto
	    *	@return: boolean: true o false
	    */
	    protected static function guardar_foto($destino_url,$foto){
	    	list($ancho, $alto) = getimagesize($foto["tmp_name"]);

			$nuevoAncho = 500;
			$nuevoAlto = 500;
			// -- GUARDAR SEGUN FORMATO --JPEG->
			if($foto["type"] == "image/jpeg"){

				// $aleatorio = md5($foto["tmp_name"]);
				
				// ruta guardar
				$ruta = "../".$destino_url;

				$origen = imagecreatefromjpeg($foto["tmp_name"]);						
				$destino = imagecreatetruecolor($nuevoAncho, $nuevoAlto);

				imagecopyresized($destino, $origen, 0, 0, 0, 0, $nuevoAncho, $nuevoAlto, $ancho, $alto);

				imagejpeg($destino, $ruta);
				
				return true;

			}else if($foto["type"] == "image/png"){
			// -----PNG FORMATO ------>
				// $ruta_db = $destino_url.".png";

				$ruta = "../".$destino_url;
				$origen = imagecreatefrompng($foto["tmp_name"]);						
				$destino = imagecreatetruecolor($nuevoAncho, $nuevoAlto);

				imagecopyresized($destino, $origen, 0, 0, 0, 0, $nuevoAncho, $nuevoAlto, $ancho, $alto);

				imagepng($destino, $ruta);

				return true;

			}else{
				return false;
			}
	    }

	    /* verificar formato correcto de fecha yyyy/mm/dd
	    *  @param: array de fecha
	    *  @return: false: sin error, true: no coincide formato solicitado
	    */
	    protected static function verificar_fecha($fecha){
	    	$valores=explode('-',$fecha);
	    	if(count($valores)==3 && checkdate($valores[1], $valores[2], $valores[0])){
	    		// sin errores
	    		return false;

	    	}else{
	    		return true;
	    	}
	    }
	    /* 	Crear Paginador de tablas
	    *	@param: $pagina: pagina actual, $npagina: numero de  
	    *   paginas,$url: posicion en url, $botones: numero de botones a mostrar
	    * 	@return: paginador final
	    */
	    protected static function paginador_tablas($pagina,$Npaginas,$url,$botones){
	    	$tabla='<nav aria-label="Page navigation example">
          <ul class="pagination justify-content-center">';

          if($pagina==1){
          	$tabla.='<li class="page-item disabled"><a class="page-link"><i class="fas fa-angle-double-left"></i></a>
            </li>';
          }else{
          	// enviar a la pagina 1, o anterior
          	$tabla.='
          	<li class="page-item"><a class="page-link" href="'.$url.'1/"><i class="fas fa-angle-double-left"></i></a>
            </li>
            <li class="page-item"><a class="page-link" href="'.$url.($pagina-1).'/">Anterior</a>
            </li>
            ';
          }
          // contador iteraciones
          $ci=0;
          // crear botones de paginas
          for ($i=$pagina; $i <= $Npaginas; $i++) { 
          	if($ci>=$botones){
          		break;
          	}
          	if($pagina==$i){
          		$tabla.='<li class="page-item"><a class="page-link active" href="'.$url.$i.'/">'.$i.'</a></li>';
          	}else{
          		$tabla.='<li class="page-item"><a class="page-link" href="'.$url.$i.'/">'.$i.'</a></li>';
          	}
          	$ci++;	
          }

          // ultima pagina, y siguiente
          if($pagina==$Npaginas){
          	$tabla.='<li class="page-item disabled"><a class="page-link"><i class="fas fa-angle-double-right"></i></a>
            </li>';
          }else{
          	// enviar a la pagina siguiente pagina, o ultima
          	$tabla.='
          	<li class="page-item"><a class="page-link" href="'.$url.($pagina+1).'/">Siguiente</a>
            </li>
          	<li class="page-item"><a class="page-link" href="'.$url.$Npaginas.'/"><i class="fas fa-angle-double-right"></i></a>
            </li>
            
            ';
          }

          $tabla.='</ul></nav>';
          return $tabla;
	    }

	    /* 	Dar formato a numero de factura 00000001
	    *	@param: $numero INT: numero de factura, idVenta
	    * 	@return: $num String, con numero con formato, 00000001
	    */
	    public static function generar_numero_factura($numero){
	    		$num = "";
	           if(($numero>=10000000) || ($numero<100000000)) 
	           {
	               $num = "".$numero; 
	           }
	           if(($numero>=1000000) || ($numero<10000000)) 
	           {
	               $num = "0".$numero; 
	           }
	           if(($numero>=100000) || ($numero<1000000)) 
	           {
	               $num = "00".$numero; 
	           }
	           if(($numero>=10000) || ($numero<100000)) 
	           {
	               $num = "000".$numero; 
	           }
	           if(($numero>=1000) || ($numero<10000)) 
	           {
	               $num = "0000".$numero; 
	           }
	           if(($numero>=100) || ($numero<1000))
	           {
	               $num = "00000".$numero; 
	           }
	           if(($numero>=9) || ($numero<100)) 
	           {
	               $num = "000000".$numero; 
	           }
	           if ($numero<9)
	           {
	               $num = "0000000".$numero; 
	           }

	           return $num;
	          
	    } // fin generar_numero_factura

	    
	} // class
