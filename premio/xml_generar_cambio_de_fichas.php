<?php session_start(); 
include("../db_conecta_adodb.inc.php");
//include("../zipArchive.lib.php");
include("../funcion.inc.php");
//die(print_r($_POST));

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


function cambio_texto($texto){
	
	$retorno = ""; $longitud = strlen($texto);
	 for($x=0; $x<$longitud; $x++){
               $letra = substr($texto, $x, 1); 
			   $reemplazo = $letra;
				
				if($letra == 'á') {$reemplazo = "&#224";}
				if($letra == 'é') {$reemplazo = "&#233";}
				if($letra == 'í') {$reemplazo = "&#237";}
				if($letra == 'ó') {$reemplazo = "&#243";}
				if($letra == 'ú') {$reemplazo = "&#250";}
				
				  $n_texto = $n_texto . $reemplazo;
               }
				
	/*
	$n_texto=ereg_replace("Á","&#193;",$n_texto);
	$n_texto=ereg_replace("É","&#201;",$n_texto);
	$n_texto=ereg_replace("Í","&#205;",$n_texto);
	$n_texto=ereg_replace("Ó","&#211;",$n_texto);
	$n_texto=ereg_replace("Ú","&#218;",$n_texto);
	$n_texto=ereg_replace("ñ","&#241;",$n_texto);
	$n_texto=ereg_replace("Ñ","&#209;",$n_texto);
	$n_texto=ereg_replace("¿","&#191;",$n_texto);	*/
	return $n_texto;
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
		mo.descripcion as moneda, jue.juegos, jue.id_juegos,
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
	
	$buffer='<?xml version="1.0" encoding="UTF-8"?>';
	
	$buffer.='<Operacion>';
	
	if($row->ID_JUEGOS==25){
	
		$buffer.='<Apostadores_cambio_de_fichas_por_montos_mayores_a_50000 Version="1.1">';
			$palabra=quitar_acentos($row->APELLIDO);
			$buffer.='<Apellido>'.$palabra.'</Apellido>';
			$palabra=quitar_acentos($row->NOMBRE);
			$buffer.='<Nombre>'.$palabra.'</Nombre>';			
			$palabra=quitar_acentos($row->NACIONALIDAD);		
			$buffer.='<Nacionalidad>'.$palabra.'</Nacionalidad>';
			//$palabra=cambio_texto($row->TIPO_DOCUMENTO);
			$buffer.='<Tipo_Documento>'.$row->TIPO_DOCUMENTO.'</Tipo_Documento>';
			$buffer.='<N94mero_documento>'.$row->DOCUMENTO.'</N94mero_documento>';
			//$buffer.='<Nro_de_CUIT_CUIL>'.$row->CUIT.'</Nro_de_CUIT_CUIL>';	
			$palabra=quitar_acentos($row->CALLE);
			$buffer.='<Calle>'.$palabra.'</Calle>';
			$buffer.='<Nro>'.$row->NUMERO.'</Nro>';
			$buffer.='<Piso>'.$row->PISO.'</Piso>';
			$buffer.='<Departamento>'.$row->DPTO.'</Departamento>';
			//$buffer.='<C93digo_Postal>'.$row->COD_POSTAL.'</C93digo_Postal>';
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
			$buffer.='<Monto_Total>'.round($row->VALOR_PREMIO).'</Monto_Total>';
			$buffer.='<Monto_Total_en_Pesos>'.round($row->VALOR_PREMIO).'</Monto_Total_en_Pesos>';
			$buffer.=' <Pago>';
			
			if($row->MEDIO_PAGO=='Cheque'){
				$buffer.='<Forma_de_Pago>Cheques</Forma_de_Pago>';
				$buffer.='<Porcentaje_del_pago_total>100</Porcentaje_del_pago_total>';
				$buffer.='<Fecha_de_pago>'.$row->FECHA_ALTA.'T00:00:00-03:00</Fecha_de_pago>';//QUE HACEMOS??
				/*$cta=explode('/',$row->CUENTA_BANCARIA);
				$buffer.='<N94mero_de_cuenta>'.$cta[0].$cta[1].'</N94mero_de_cuenta>';*/
				//$buffer.='<N94mero_de_cheque>'.$row->CHEQUE_NRO.'</N94mero_de_cheque>';
			} /*
			elseif($row->MEDIO_PAGO=='En Especie'){
				$buffer.='<Forma_de_Pago>Efectivo</Forma_de_Pago>';
			}*/
			else {$buffer.='<Forma_de_Pago>'.$row->MEDIO_PAGO.'</Forma_de_Pago>';
				  $buffer.='<Porcentaje_del_pago_total>100</Porcentaje_del_pago_total>';
				  $buffer.='<Fecha_de_pago>'.$row->FECHA_ALTA.'T00:00:00-03:00</Fecha_de_pago>';
					//$buffer.='<Baco_emisor/>';
					//$buffer.='<N94mero_de_cuenta>0</N94mero_de_cuenta>';
					//$buffer.='<N94mero_de_cheque>0</N94mero_de_cheque>';}//QUE HACEMOS?? ********************
			}
			$buffer.=' </Pago>';
			
      	$buffer.='</Apostadores_cambio_de_fichas_por_montos_mayores_a_50000>';
		}
		else {
		
				$buffer.='<Apostadores_cobranza_de_premios_mayores_a_50000 Version="1.1">';
				$palabra=quitar_acentos($row->APELLIDO);
				$buffer.='<Apellido>'.$palabra.'</Apellido>';
				$palabra=quitar_acentos($row->NOMBRE);
				$buffer.='<Nombre>'.$palabra.'</Nombre>';			
				$palabra=quitar_acentos($row->NACIONALIDAD);		
				$buffer.='<Nacionalidad>'.$palabra.'</Nacionalidad>';
				//$palabra2=cambio_texto($row->TIPO_DOCUMENTO);
				$buffer.='<Tipo_Documento>'.$row->TIPO_DOCUMENTO.'</Tipo_Documento>';
				//$buffer.='<Tipo_Documento>'.$row->TIPO_DOCUMENTO.'</Tipo_Documento>';
				$buffer.='<N94mero_Documento>'.$row->DOCUMENTO.'</N94mero_Documento>';
				//$buffer.='<Nro_de_CUIT_CUIL>'.$row->CUIT.'</Nro_de_CUIT_CUIL>';	
				$palabra=quitar_acentos($row->CALLE);
				$buffer.='<Calle>'.$palabra.'</Calle>';
				$buffer.='<Nro>'.$row->NUMERO.'</Nro>';
				$buffer.='<Piso>'.$row->PISO.'</Piso>';
				$buffer.='<Departamento>'.$row->DPTO.'</Departamento>';
				$buffer.='<Localidad>'.$row->N_LOCALIDAD.'</Localidad>';
				//$buffer.='<C93digo_Postal>'.$row->COD_POSTAL.'</C93digo_Postal>';
				$buffer.='<Provincia>'.$row->N_PROVINCIA.'</Provincia>';
				$buffer.='<Pa92s>'.$row->N_PAIS.'</Pa92s>';
				$buffer.='<Radicada_en_el_Exterior>false</Radicada_en_el_Exterior>'; //OJOOOO!!!
				$buffer.='<Radicada_en_Para92so_Fiscal>false</Radicada_en_Para92so_Fiscal>';//OJOOOO!!!!
				if($row->POLITICO=='SI'){
					$buffer.='<Es_Peps>true</Es_Peps>';
				} else {$buffer.='<Es_Peps>false</Es_Peps>';}				
				$buffer.='<Fecha_de_Operaci93n>'.$row->FECHA_ALTA.'T00:00:00-03:00</Fecha_de_Operaci93n>';
				$buffer.='<Tipo_de_Moneda>Peso Argentino</Tipo_de_Moneda>';//***********************				
				$buffer.='<Monto_Total>'.round($row->VALOR_PREMIO).'</Monto_Total>';
				$buffer.='<Monto_Total_en_Pesos>'.round($row->VALOR_PREMIO).'</Monto_Total_en_Pesos>';
				$buffer.=' <Pago_en_favor_de_Terceros>false</Pago_en_favor_de_Terceros>';
				$buffer.=' <Pago>';
				
				if($row->MEDIO_PAGO=='Cheque'){
					$buffer.='<Forma_de_Pago>Cheques</Forma_de_Pago>';
					$buffer.='<Porcentaje_del_pago_total>100</Porcentaje_del_pago_total>';
					$buffer.='<Fecha_de_pago>'.$row->FECHA_ALTA.'T00:00:00-03:00</Fecha_de_pago>';//QUE HACEMOS??
					/*$cta=explode('/',$row->CUENTA_BANCARIA);
					$buffer.='<N94mero_de_cuenta>'.$cta[0].$cta[1].'</N94mero_de_cuenta>';*/
					//$buffer.='<N94mero_de_cheque>'.$row->CHEQUE_NRO.'</N94mero_de_cheque>';
				} /*
				elseif($row->MEDIO_PAGO=='En Especie'){
					$buffer.='<Forma_de_Pago>Efectivo</Forma_de_Pago>';
				}*/
				else {$buffer.='<Forma_de_Pago>'.$row->MEDIO_PAGO.'</Forma_de_Pago>';
					  $buffer.='<Porcentaje_del_pago_total>100</Porcentaje_del_pago_total>';
					  $buffer.='<Fecha_de_pago>'.$row->FECHA_ALTA.'T00:00:00-03:00</Fecha_de_pago>';
						//$buffer.='<Baco_emisor/>';
						//$buffer.='<N94mero_de_cuenta>0</N94mero_de_cuenta>';
						//$buffer.='<N94mero_de_cheque>0</N94mero_de_cheque>';}//QUE HACEMOS?? ********************
				}
				$buffer.=' </Pago>';
				//$buffer.='<Forma_de_Pago>'.$row->MEDIO_PAGO.'</Forma_de_Pago>';
				
				
				/*
				$buffer.='<Persona_Jur92dica_Terceros>';
				
				$buffer.=' <Denominaci93n88Terceros_Juridica>Prueba</Denominaci93n88Terceros_Juridica>';
				$buffer.='<Tipo_Sociedad88Terceros_Juridica>Sociedad colectiva</Tipo_Sociedad88Terceros_Juridica>';
				$buffer.='<CUIT_CDI88Terceros_Juridica>23292556519</CUIT_CDI88Terceros_Juridica>';
				$buffer.='<Calle88Terceros_Juridica>Prueba</Calle88Terceros_Juridica>';
				$buffer.='<Nro88Terceros_Juridica>0</Nro88Terceros_Juridica>';
				$buffer.='<Piso88Terceros_Juridica></Piso88Terceros_Juridica>';
				$buffer.='<Departamento88Terceros_Juridica></Departamento88Terceros_Juridica>';
				$buffer.='<Localidad88Terceros_Juridica></Localidad88Terceros_Juridica>';
				$buffer.='<C93digo_Postal88Terceros_Juridica></C93digo_Postal88Terceros_Juridica>';
				$buffer.='<Provincia88Terceros_Juridica>CABA</Provincia88Terceros_Juridica>';
				$buffer.='<Pa92s88Terceros_Juridica>Argentina</Pa92s88Terceros_Juridica>';
				$buffer.='<Prefijo88Terceros_Juridica>0</Prefijo88Terceros_Juridica>';

				
				$buffer.='</Persona_Jur92dica_Terceros>';				
				*/
				
				/*
				$buffer.='<Persona_F92sica_Terceros>';
				
				$buffer.='<Apellido88Terceros_Fisica>Prueba</Apellido88Terceros_Fisica>';
				$buffer.='<Segundo_Apellido88Terceros_Fisica></Segundo_Apellido88Terceros_Fisica>';
				$buffer.='<Nombre88Terceros_Fisica>Prueba</Nombre88Terceros_Fisica>';
				$buffer.='<Segundo_Nombre88Terceros_Fisica></Segundo_Nombre88Terceros_Fisica>';
				$buffer.='<Nacionalidad88Terceros_Fisica>Argentina</Nacionalidad88Terceros_Fisica>';
				$buffer.='<Tipo_Documento88Terceros_Fisica>Documento Nacional de Identidad</Tipo_Documento88Terceros_Fisica>';
				$buffer.='<N94mero_Documento88Terceros_Fisica>Prueba</N94mero_Documento88Terceros_Fisica>';
				$buffer.='<Nro_de_CUIT_CUIL88Terceros_Fisica></Nro_de_CUIT_CUIL88Terceros_Fisica>';
				$buffer.='<Calle88Terceros_Fisica>Prueba</Calle88Terceros_Fisica>';
				$buffer.='<Nro88Terceros_Fisica>0</Nro88Terceros_Fisica>';
				$buffer.='<Piso88Terceros_Fisica></Piso88Terceros_Fisica>';
				$buffer.='<Departamento88Terceros_Fisica></Departamento88Terceros_Fisica>';
				$buffer.='<Localidad88Terceros_Fisica>Prueba</Localidad88Terceros_Fisica>';
				$buffer.='<C93digo_Postal88Terceros_Fisica></C93digo_Postal88Terceros_Fisica>';
				$buffer.='<Provincia88Terceros_Fisica>CABA</Provincia88Terceros_Fisica>';
				$buffer.='<Pa92s88Terceros_Fisica>Argentina</Pa92s88Terceros_Fisica>';
				$buffer.='<Fecha_de_Nacimiento88Terceros_Fisica>2011-05-04T00:00:00-03:00</Fecha_de_Nacimiento88Terceros_Fisica>';
				$buffer.='<Prefijo88Terceros_Fisica>0</Prefijo88Terceros_Fisica>';
				$buffer.='<Tel91fono88Terceros_Fisica>0</Tel91fono88Terceros_Fisica>';
				$buffer.='<Email88Terceros_Fisica></Email88Terceros_Fisica>';
				$buffer.='<Radicada_en_el_Exterior88Terceros_Fisica>false</Radicada_en_el_Exterior88Terceros_Fisica>';
				$buffer.='<Radicada_en_Para92so_Fiscal_88Terceros_Fisica>false</Radicada_en_Para92so_Fiscal_88Terceros_Fisica>';
				$buffer.='<Es_Peps88Terceros_Fisica>true</Es_Peps88Terceros_Fisica>';
				
				$buffer.='</Persona_F92sica_Terceros>';
				*/
								
				$buffer.='</Apostadores_cobranza_de_premios_mayores_a_50000>';
						
		
		}
	$buffer.='</Operacion>';
	
	$archivo= $xml[$j]."_".$fecha."_".$row->CUIT.".xml";
	//die($buffer);
	$file=fopen($archivo, "w+"); 
  	fwrite ($file,$buffer); 
  	fclose($file); 
	
	try {$rs = $db ->Execute("update PLA_AUDITORIA.t_ganador
								set xml=?
								where id_ganador=?", array(1,$row->ID_GANADOR));}
	catch (exception $e) {die ($db->ErrorMsg()); } 	
	
	
		
	}

include ("xml_zipear.php");
chdir('..');
?>


 
