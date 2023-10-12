<?php session_start(); 
include("../db_conecta_adodb.inc.php");
//include("../zipArchive.lib.php");
include("../funcion.inc.php");
/*
function ConectarFTP(){
	$servidor = "172.16.10.90";	
	$puerto = 21;
	$timeout = 1000;
	$user = "web";	
	$pass = "webpwd";
	$id_ftp = ftp_connect($servidor, $puerto, $timeout);	//Obtiene un manejador del Servidor FTP
	ftp_login($id_ftp, $user, $pass); 	//Logueo al Servidor FTP
	return $id_ftp; 
	}
*/

$array_fecha = FechaServer();
$fecha = $array_fecha["year"].str_pad($array_fecha["mon"],2,'0',STR_PAD_LEFT).str_pad($array_fecha["mday"],2,'0',STR_PAD_LEFT).
	str_pad($array_fecha["hours"],2,'0',STR_PAD_LEFT).str_pad($array_fecha["minutes"],2,'0',STR_PAD_LEFT).
	str_pad($array_fecha["seconds"],2,'0',STR_PAD_LEFT);

$sql= "select gdor.id_ganador, gdor.valor_premio, gdor.concepto, gdor.fecha_alta, gdor.domicilio_pago, 
		gdor.cuenta_bancaria_salida, gdor.cheque_nro, info.sucursal, gdor.nro_premio, 
		gdor.documento, gdor.apellido, gdor.nombre, gdor.nacionalidad, gdor.sexo, gdor.cuit,
		gdor.calle, gdor.numero, gdor.profesion, gdor.cod_postal, 
		gdor.documento2, gdor.apellido2, gdor.nombre2, gdor.fecha_alta, gdor.nro_premio, gdor.politico, gdor.cargo,
		upper(gdor.estado_civil) estado_civil,
		mo.descripcion as moneda, jue.juegos, 
		pago.descripcion medio_pago, pago.id_tipo_pago, 
		td.descripcion as tipo_documento, 
		lo.n_localidad, pro.n_provincia, pa.n_pais, su.nombre as sucursal_delegacion
	from PLA_AUDITORIA.t_ganador gdor, PLA_AUDITORIA.t_moneda mo, juegos.juegos jue, 
		PLA_AUDITORIA.t_tipo_pago pago, PLA_AUDITORIA.t_info_direcciones info, 
		PLA_AUDITORIA.t_tipo_documento td, utilidades.t_localidades lo, utilidades.t_provincias pro,
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
	//echo "**".$xml[$j]."-".$row->APELLIDO."<br>";
	$buffer='';
	$buffer='<?xml version="1.0" encoding="utf-8"?>';
	$buffer.='<Operacion><ROS>';
	$buffer.='<Refiere_Art92culo_Period92stico>false</Refiere_Art92culo_Period92stico>';
	$buffer.='<Operaci93n>Realizada</Operaci93n>';
	$buffer.='<Persona_F92sica>';
	$buffer.='<Apellido88Persona_Fisica>'.$row->APELLIDO.'</Apellido88Persona_Fisica>';
	$buffer.='<Nombre88Persona_Fisica>'.$row->NOMBRE.'</Nombre88Persona_Fisica>';
	$buffer.='<Nacionalidad88Persona_Fisica>'.$row->NACIONALIDAD.'</Nacionalidad88Persona_Fisica>';
	$buffer.='<Sexo88Persona_Fisica>'.ucfirst($row->SEXO).'</Sexo88Persona_Fisica>';
	$buffer.='<Tipo_Documento88Persona_Fisica>'.$row->TIPO_DOCUMENTO.'</Tipo_Documento88Persona_Fisica>';
	$buffer.='<N94mero_Documento88Persona_Fisica>'.$row->DOCUMENTO.'</N94mero_Documento88Persona_Fisica>';
	$buffer.='<CUIT_CDI88Persona_Fisica>'.$row->CUIT.'</CUIT_CDI88Persona_Fisica>';	
	$buffer.='<Calle88Persona_Fisica>'.$row->CALLE.'</Calle88Persona_Fisica>';
	$buffer.='<Nro88Persona_Fisica>'.$row->NUMERO.'</Nro88Persona_Fisica>';
	$buffer.='<Localidad88Persona_Fisica>'.$row->N_LOCALIDAD.'</Localidad88Persona_Fisica>';
	$buffer.='<Provincia88Persona_Fisica>'.$row->N_PROVINCIA.'</Provincia88Persona_Fisica>';	
	$buffer.='<Pa92s88Persona_Fisica>'.$row->N_PAIS.'</Pa92s88Persona_Fisica>';
	$buffer.='<Persona_F92sica_relacionada_con_Para92so_Fiscal>Ninguno/a</Persona_F92sica_relacionada_con_Para92so_Fiscal>';
	$buffer.='<Persona_F92sica_relacionada_con_Triple_Frontera>Ninguno/a</Persona_F92sica_relacionada_con_Triple_Frontera>';
	$buffer.='<El_reportado_es_Cliente88Persona_Fisica>false</El_reportado_es_Cliente88Persona_Fisica>';
	if($row->POLITICO=='SI'){
		$buffer.='<Es_Peps>true</Es_Peps>';
		$buffer.='<Cargo88Persona_fisica>'.$row->CARGO.'</Cargo88Persona_fisica>';
		$buffer.='<Dependencia88Persona_Fisica>'.'***???***'.'</Dependencia88Persona_Fisica>';//QUE HACEMOS??
	} else {$buffer.='<Es_Peps>false</Es_Peps>';}
	$buffer.='<Actividad88Persona_Fisica>'.$row->PROFESION.'</Actividad88Persona_Fisica>';
	$buffer.='<Estado_Civil>'.$row->ESTADO_CIVIL.'</Estado_Civil>';
	if($row->ESTADO_CIVIL=='SOLTERO'){
		$buffer.='<Apellido_C93nyugue>'.$row->APELLIDO_CONYUGUE.'</Apellido_C93nyugue>';
		$buffer.='<Nombre_C93nyugue>'.$row->NOMBRE_CONYUGUE.'</Nombre_C93nyugue>';
		$buffer.='<N94mero_Documento_C93nyugue>'.$row->DOCUMENTO_CONYUGUE.'</N94mero_Documento_C93nyugue>';
		$buffer.='<CUIT_CDI_C93nyugue>'.$row->CUIT_CONYUGUE.'</CUIT_CDI_C93nyugue>';
	}	
	$buffer.='</Persona_F92sica>';
	
	$buffer.='<Operaciones_y_Productos>';
	$buffer.='<Inicio_de_la_Operaci93n_Reportada>2011-06-27T00:00:00-03:00</Inicio_de_la_Operaci93n_Reportada>';
	$buffer.='<Fin_de_la_Operaci93n_Reportada>2011-06-28T00:00:00-03:00</Fin_de_la_Operaci93n_Reportada>';
	$buffer.='<Localidad_1_Donde_se_Producen_los_Hechos>uu</Localidad_1_Donde_se_Producen_los_Hechos>';
	$buffer.='<Provincia_1>Cordoba</Provincia_1>';
	$buffer.='<Es_Zona_de_Frontera_1>false</Es_Zona_de_Frontera_1>';
	$buffer.='<Pa92s_Donde_se_Producen_los_Hechos_1>Argentina</Pa92s_Donde_se_Producen_los_Hechos_1>';
	$buffer.='<Operaci93n_relacionada_con_Para92so_Fiscal>Ninguno/a</Operaci93n_relacionada_con_Para92so_Fiscal>';
	$buffer.='<Operaci93n_relacionada_con_Triple_Frontera>Ninguno/a</Operaci93n_relacionada_con_Triple_Frontera>';
	$buffer.='<Tipo_de_Inusualidad>Los montos, tipos, frecuencia y naturaleza de las operaciones que realicen los clientes que no guarden relacion con los antecedentes y la actividad economica de ellos</Tipo_de_Inusualidad>';
	
	$buffer.=' <N94mero_de_Identificaci93n>Prueba</N94mero_de_Identificaci93n>';//QUE HACEMOS??
	$buffer.='<Moneda_de_Origen_del_Producto>Peso Argentino</Moneda_de_Origen_del_Producto>';
	$buffer.='<Monto_Operado_en_el_Producto_en_Moneda_de_Origen>'.$row->VALOR_PREMIO.'</Monto_Operado_en_el_Producto_en_Moneda_de_Origen>';
	$buffer.='<Monto_Operado_en_el_Producto_en_Pesos>'.$row->VALOR_PREMIO.'</Monto_Operado_en_el_Producto_en_Pesos>';
	$buffer.='<Monto_en_Letras>Prueba</Monto_en_Letras>';//QUE HACEMOS??
	$buffer.='<Relaci93n_del_Producto_con_el_Hecho_Reportado>Directa</Relaci93n_del_Producto_con_el_Hecho_Reportado>';//QUE HACEMOS??
	$buffer.='<Descripci93n_de_la_Operatoria>Prueba</Descripci93n_de_la_Operatoria>';//QUE HACEMOS??
	$buffer.='<Descripci93n_del_An90lisis_efectuado_por_el_Sujeto_Obligado>Prueba</Descripci93n_del_An90lisis_efectuado_por_el_Sujeto_Obligado>';//QUE HACEMOS??
	$buffer.='<Informe_de_Documentaci93n_de_Respaldo_que_Posee>Prueba</Informe_de_Documentaci93n_de_Respaldo_que_Posee>';//QUE HACEMOS??
	$buffer.='<Informe_de_Conclusiones_para_Emitir_Reporte>Prueba</Informe_de_Conclusiones_para_Emitir_Reporte>';//QUE HACEMOS??
	$buffer.='</Operaciones_y_Productos>';
	$buffer.='</ROS></Operacion>';
	
	$archivo= $xml[$j]."_".$fecha."_".$row->CUIT.".xml";
	
	$file=fopen($archivo, "w+"); 
  	fwrite ($file,$buffer); 
  	fclose($file); 	
/*
	$archivo_local= $archivo;
	$archivo_remoto="/home/web/PLA_AUDITORIA/".$archivo;
	
	//Sube archivo de la maquina Cliente al Servidor (Comando PUT)
	//$id_ftp=ConectarFTP(); //Obtiene un manejador y se conecta al Servidor FTP 
	
	//ftp_put($id_ftp,$archivo_remoto,$archivo_local,FTP_BINARY);
	
	$termino='S';
	//Sube un archivo al Servidor FTP en modo Binario
	ftp_close($id_ftp); //Cierra la conexion FTP
	*/
	}

include ("xml_zipear.php");
chdir('..');
?>


 
