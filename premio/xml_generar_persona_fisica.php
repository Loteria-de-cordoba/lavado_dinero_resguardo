<?php session_start(); 
include("../db_conecta_adodb.inc.php");
//include("../zipArchive.lib.php");
include("../funcion.inc.php");

function quitar_acentos ($cadena){
       $valor = "F"; $retorno = ""; $longitud = strlen($cadena);
       
       for($x=0; $x<$longitud; $x++){
               $letra = substr($cadena, $x, 1); 
			   $reemplazo = $letra;
               
               if($letra == 'á') {$reemplazo = "a";}
               if($letra == 'é') {$reemplazo = "e";}
               if($letra == 'í') {$reemplazo = "i";}
               if($letra == 'ó') {$reemplazo = "o";}
               if($letra == 'ú') {$reemplazo = "u";}

               if($letra == 'Á') {$reemplazo = "A";}
               if($letra == 'É') {$reemplazo = "E";}
               if($letra == 'Í') {$reemplazo = "I";}
               if($letra == 'Ó') {$reemplazo = "O";}
               if($letra == 'Ú') {$reemplazo = "U";}

               if($letra == 'ñ') {$reemplazo = "n";}
               if($letra == 'Ñ') {$reemplazo = "N";}

               $retorno = $retorno . $reemplazo;
               }

       return $retorno;
       }



$array_fecha = FechaServer();
$fecha = $array_fecha["year"].str_pad($array_fecha["mon"],2,'0',STR_PAD_LEFT).str_pad($array_fecha["mday"],2,'0',STR_PAD_LEFT).
	str_pad($array_fecha["hours"],2,'0',STR_PAD_LEFT).str_pad($array_fecha["minutes"],2,'0',STR_PAD_LEFT).
	str_pad($array_fecha["seconds"],2,'0',STR_PAD_LEFT);

$sql= "select gdor.id_ganador, gdor.valor_premio, gdor.concepto, gdor.fecha_alta, gdor.domicilio_pago, 
		gdor.cuenta_bancaria_salida, gdor.cheque_nro, info.sucursal, info.cuenta_bancaria, gdor.nro_premio, 
		gdor.documento, gdor.apellido, gdor.nombre, initcap(gdor.nacionalidad) nacionalidad, gdor.sexo, gdor.cuit,
		gdor.calle, gdor.numero, gdor.piso, gdor.dpto, gdor.profesion, gdor.cod_postal, 
		gdor.documento2, gdor.apellido2, gdor.nombre2, gdor.fecha_alta, gdor.nro_premio, gdor.politico, gdor.cargo,
		upper(gdor.estado_civil) estado_civil,
		mo.descripcion as moneda, jue.juegos, 
		pago.descripcion medio_pago, pago.id_tipo_pago, 
		td.descripcion as tipo_documento, 
		lo.n_localidad, pro.n_provincia, initcap(pa.n_pais) n_pais, su.nombre as sucursal_delegacion
	from PLA_AUDITORIA.t_ganador gdor, PLA_AUDITORIA.t_moneda mo, juegos.juegos jue, 
		PLA_AUDITORIA.t_tipo_pago pago, PLA_AUDITORIA.t_info_direcciones info, 
		PLA_AUDITORIA.t_tipo_documento td, 
		administrativo.t_localidades lo, 
		administrativo.t_provincias pro,
		utilidades.t_paises pa, juegos.sucursal su
	where gdor.id_moneda=mo.id_moneda
		and gdor.juego= jue.id_juegos
		and gdor.id_tipo_pago= pago.id_tipo_pago
		and info.suc_ban(+)= gdor.suc_ban
		and gdor.id_tipo_documento=td.id_tipo_documento
		and gdor.id_localidad= lo.id_localidad(+)
		and lo.id_provincia= pro.id_provincia(+)
		and pro.id_pais=pa.id_pais(+)
		and gdor.suc_ban=su.suc_ban
		and gdor.fecha_baja is null
		and gdor.id_ganador= ?";


$i= 0;
foreach($_POST as $nombre_campo => $valor){
	if (substr($nombre_campo, 0, 3) == "xml") {
		$i++;
		$xml[$i] = $valor; 
		}
	} 

$archivo= "*.*";
borrar_archivos_directorio("xml_temp");
chdir('xml_temp');

for($j=1;$j <= $i;$j++){
	try {$rs_xml= $db ->Execute($sql,array($xml[$j]));	}
	catch (exception $e) {die ($db->ErrorMsg()); } 	
	$row = $rs_xml->FetchNextObject($toupper=true);
	
	$buffer='';
	$buffer='<?xml version="1.0" encoding="utf-8"?>';
	$buffer.='<Operacion>';
		$buffer.='<Juegos_Azar_Mayor_50000>';
			$palabra=quitar_acentos($row->APELLIDO);
			$buffer.='<Apellido>'.$palabra.'</Apellido>';
			$palabra=quitar_acentos($row->NOMBRE);
			$buffer.='<Nombre>'.$palabra.'</Nombre>';
			$buffer.='<Nro_de_CUIT_CUIL>'.$row->CUIT.'</Nro_de_CUIT_CUIL>';	
			$palabra=quitar_acentos($row->NACIONALIDAD);		
			$buffer.='<Nacionalidad>'.$palabra.'</Nacionalidad>';
			$buffer.='<Tipo_Documento>'.$row->TIPO_DOCUMENTO.'</Tipo_Documento>';
			$buffer.='<N94mero_documento>'.$row->DOCUMENTO.'</N94mero_documento>';
			$palabra=quitar_acentos($row->CALLE);
			$buffer.='<Calle>'.$palabra.'</Calle>';
			$buffer.='<Nro>'.$row->NUMERO.'</Nro>';
			$buffer.='<Piso>'.$row->PISO.'</Piso>';
			$buffer.='<Departamento>'.$row->DPTO.'</Departamento>';
			$buffer.='<Localidad>'.$row->N_LOCALIDAD.'</Localidad>';
			$buffer.='<Provincia>'.$row->N_PROVINCIA.'</Provincia>';	
			

			$buffer.='<Pa92s>'.$row->N_PAIS.'</Pa92s>';
			$buffer.='<Radicada_en_el_Exterior>false</Radicada_en_el_Exterior>'; //OJOOOO!!!
			$buffer.='<Radicada_en_Para92so_Fiscal>false</Radicada_en_Para92so_Fiscal>';//OJOOOO!!!!
			if($row->POLITICO=='SI'){
				$buffer.='<Es_Peps>true</Es_Peps>';
			} else {$buffer.='<Es_Peps>false</Es_Peps>';}				
			$buffer.='<Fecha_de_Operaci93n>'.$row->FECHA_ALTA.'T00:00:00-03:00</Fecha_de_Operaci93n>';
			$buffer.='<Tipo_de_Moneda>Peso Argentino</Tipo_de_Moneda>';//***********************				
			$buffer.='<Monto>'.round($row->VALOR_PREMIO).'</Monto>';
			$buffer.='<Monto_en_Pesos>'.round($row->VALOR_PREMIO).'</Monto_en_Pesos>';
			$buffer.='<Fecha_de_pago>'.$row->FECHA_ALTA.'T00:00:00-03:00</Fecha_de_pago>';//QUE HACEMOS??
			
			if($row->MEDIO_PAGO=='Cheque'){
				$buffer.='<Forma_de_Pago>Cheques</Forma_de_Pago>';
				$buffer.='<Baco_emisor>Banco de la Provincia de Cordoba</Baco_emisor>';
				$cta=explode('/',$row->CUENTA_BANCARIA);
				$buffer.='<N94mero_de_cuenta>'.$cta[0].$cta[1].'</N94mero_de_cuenta>';
				$buffer.='<N94mero_de_cheque>'.$row->CHEQUE_NRO.'</N94mero_de_cheque>';
			} 
			if($row->MEDIO_PAGO=='En Especie'){
				$buffer.='<Forma_de_Pago>Efectivo</Forma_de_Pago>';
			}
			else {$buffer.='<Forma_de_Pago>'.$row->MEDIO_PAGO.'</Forma_de_Pago>';
					//$buffer.='<Baco_emisor/>';
					//$buffer.='<N94mero_de_cuenta>0</N94mero_de_cuenta>';
					//$buffer.='<N94mero_de_cheque>0</N94mero_de_cheque>';}//QUE HACEMOS?? ********************
			}
			$buffer.='<Vendedor>';
				$buffer.='<Apellido88Vendedor>Prueba</Apellido88Vendedor>';
				$buffer.='<Nombre88Vendedor>Prueba</Nombre88Vendedor>';
				$buffer.='<Nacionalidad88Vendedor>Argentina</Nacionalidad88Vendedor>';
				$buffer.='<Tipo_Documento88Vendedor>Documento Nacional de Identidad</Tipo_Documento88Vendedor>';
				$buffer.='<N94mero_documento88Vendedor>Prueba</N94mero_documento88Vendedor>';
				$buffer.='<Calle88Vendedor>Prueba</Calle88Vendedor>';
				$buffer.='<Nro88Vendedor>0</Nro88Vendedor>';
				$buffer.='<Piso88Vendedor>Prueba</Piso88Vendedor>';
				$buffer.='<Departamento88Vendedor>Prueba</Departamento88Vendedor>';
				$buffer.='<Localidad88Vendedor>Prueba</Localidad88Vendedor>';
				$buffer.='<C93digo_Postal88Vendedor>Prueba</C93digo_Postal88Vendedor>';
				$buffer.='<Provincia88Vendedor>CABA</Provincia88Vendedor>';
				$buffer.='<Pa92s88Vendedor>Argentina</Pa92s88Vendedor>';
				$buffer.='<Radicada_en_el_Exterior88Vendedor>false</Radicada_en_el_Exterior88Vendedor>';
				$buffer.='<Radicada_en_Para92so_Fiscal88Vendedor>false</Radicada_en_Para92so_Fiscal88Vendedor>';
				$buffer.='<Es_Peps88Vendedor>false</Es_Peps88Vendedor>';
				$buffer.='<Prefijo88Vendedor>0</Prefijo88Vendedor>';
			$buffer.='</Vendedor>';
      	$buffer.='</Juegos_Azar_Mayor_50000>';
	$buffer.='</Operacion>';
	
	$archivo= $xml[$j]."_".$fecha."_".$row->CUIT.".xml";
	
	$file=fopen($archivo, "w+"); 
  	fwrite ($file,$buffer); 
  	fclose($file); 
	/*
	try {$rs = $db ->Execute("update PLA_AUDITORIA.t_ganador
								set xml=?
								where id_ganador=?", array(1,$row->ID_GANADOR));	}
	catch (exception $e) {die ($db->ErrorMsg()); } 	
	*/
	
		
	}

include ("xml_zipear.php");
chdir('..');
?>


 
