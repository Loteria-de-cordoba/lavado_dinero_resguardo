//Funcion seleccionar un campo
function SeleccionarCampo(campo) {
		document.getElementById(campo).select();
	}

//desactivo la tecla enter para todo el sistema (cross browser)
document.onkeypress = stopRKey; 
function stopRKey(evt) {
	var evt = (evt) ? evt : ((event) ? event : null);
	var node = (evt.target) ? evt.target : ((evt.srcElement) ? evt.srcElement : null);
	if ((evt.keyCode == 13) && (node.type=="text")) {return false;}
}

function validar_eliminar_cliente(div,pagina_destino,formulario){
	if (formulario.observacion.value==''){
		Sexy.info('<h1>Eliminar Deuda</h1><br/><p>Debe ingresar la OBSERVACION de eliminacion</p>');
		return false;
		}

	ajax_post(div,pagina_destino,formulario);
}

function confirmacion_get(div, pagina, cadena, mensaje, titulo) {
	Sexy.confirm('<h1>'+titulo+',</h1><p>'+mensaje+'</p><p>Pulsa "Ok" para continuar, o pulsa "Cancelar" para salir.</p>', { onComplete: 
		function(titulo) {
			if(titulo){
				ajax_get(div,pagina,cadena);
				} else {
					return false;
					}
			}
		});
	}
/*document.all = TeclaPulsada(event)
function TeclaPulsada(evento) {
  // averiguamos el código de la tecla pulsada (keyCode para IE y which para Firefox)
  tecla = (document.all) ? evento.keyCode :evento.which;
  // si la tecla no es 13 devuelve verdadero,  si es 13 devuelve false y la pulsación no se ejecuta
  if (tecla==13) {
	  return false;
  }
}
*/
//var ns4 = (document.layers)? true:false
/*var ie4 = (document.all)? true:false
document.onkeydown = TeclaPulsada
function TeclaPulsada () {
	if(ie4) {
		var teclaCodigo = event.keyCode
	} else {
		var teclaCodigo = event.which
		alert(teclaCodigo)
	}
	if (teclaCodigo==13) {
		return false;
	}	
}
/*
/*function TeclaPulsada ()
{
	var teclaCodigo = event.keyCode
	var teclaReal   = String.fromCharCode (teclaCodigo)
	alert("Código de la tecla: " + teclaCodigo + "\nTecla pulsada: " + teclaReal)
}
*/
/*function click() 
	{
		if (event.button==2) 
		{
		alert ('Este boton esta desabilitado.')
		}
	}
document.onmousedown=click*/
function redondear(cantidad, decimales) {
	var Cantidad_ = parseFloat(cantidad);
	var Decimales_ = parseFloat(decimales);
	Decimales_ = (!Decimales_ ? 2 : Decimales_);
	return Math.round(Cantidad_ * Math.pow(10, Decimales_)) / Math.pow(10, Decimales_);
} 
function anyoBisiesto(ano)
  {
    if ((ano % 4 == 0) || ((ano % 100 == 0) && (ano % 400 == 0)))
       {
   	     return true;
       }
       else
       {
         return false;
       }
   }
function validar_fecha(dia,mes,ano)
	{
		if (anyoBisiesto(ano.value))
	   	  {
          var febrero=29;
		  }
       	  else
	   	  {
          var febrero=28;
		  }
	  /*si el mes introducido es febrero y el dia es mayor que el correspondiente, al año introducido > alertamos y detenemos ejecucion*/
	  if ((mes.value==2) && ((dia.value<1) || (dia.value>febrero)))
	     {
		 alert("Dia Invalido.");
	     return false;
	     }
      if (((mes.value==1) || (mes.value==3) || (mes.value==5) || (mes.value==7) || (mes.value==8) || (mes.value==10) || (mes.value==12)) && ((dia.value<1) || (dia.value>31)))
	   	 {
		 alert("Dia Invalido.");
		 return false;
	     }
	if (((mes.value==4) || (mes.value==6) || (mes.value==9) || (mes.value==11)) && ((dia.value<1) || (dia.value>30)))
		 {
	     alert("Dia Invalido.");
		 return false;
	     }
	return true;
   	  /*si el mes introducido es de 30 dias y el dia introducido es mayor de 30 > alertamos y detenemos ejecucion*/
		 /*en caso de que todo sea correcto > enviamos los datos del formulario, para ello debeis descomentar la ultima sentencia	*/
	}
function comparar_fechas(dia1,mes1,ano1,dia2,mes2,ano2) 
	{
	var fecha_desde = new Date(ano1.value,mes1.value-1/*los meses cuentan entre cero y 11, por eso se suma 1*/,dia1.value);
   	var fecha_hasta = new Date(ano2.value,mes2.value-1/*los meses cuentan entre cero y 11, por eso se suma 1*/,dia2.value);
if (fecha_desde.getTime()>fecha_hasta.getTime())
   		{
   		alert('La fecha desde debe ser menor o igual que la fecha hasta');
   		//for (n=0;n<=fecha_hasta_dia.length-1;n++)
   		//{
   		//	if (fecha_hasta_dia.options[n].value==10)
		//	{
		//		fecha_hasta_dia.options[n].selected = true ;
		//	}
		//}
   		//fecha_hasta_mes.selectedIndex=fecha_desde_mes.selectedIndex;
   		//fecha_hasta_ano.selectedIndex=fecha_desde_ano.selectedIndex;
   		return false ;
  		}
   		else
   		{
   		return true ;
   		}
	}
function comparar_fechas_mensaje(dia1,mes1,ano1,dia2,mes2,ano2,mensaje) 
	{
	var fecha_desde = new Date(ano1.value,mes1.value-1/*los meses cuentan entre cero y 11, por eso se suma 1*/,dia1.value);
   	var fecha_hasta = new Date(ano2.value,mes2.value-1/*los meses cuentan entre cero y 11, por eso se suma 1*/,dia2.value);
if (fecha_desde.getTime()>fecha_hasta.getTime())
   		{
   		alert(mensaje);
   		//for (n=0;n<=fecha_hasta_dia.length-1;n++)
   		//{
   		//	if (fecha_hasta_dia.options[n].value==10)
		//	{
		//		fecha_hasta_dia.options[n].selected = true ;
		//	}
		//}
   		//fecha_hasta_mes.selectedIndex=fecha_desde_mes.selectedIndex;
   		//fecha_hasta_ano.selectedIndex=fecha_desde_ano.selectedIndex;
   		return false ;
  		}
   		else
   		{
   		return true ;
   		}
	}
function comparar_fechas_calendario(fecha_desde,fecha_hasta) 
	{
	var fecha1 = fecha_desde.value;
	var fecha2 = fecha_hasta.value;
	var dia1 = fecha1.substr(0,2)
	var mes1 = fecha1.substr(3,2)
	var ano1 = fecha1.substr(6,4)
	var hora1 = fecha1.substr(11,2)
	var minutos1 = fecha1.substr(14,2)
	var segundos1 = fecha1.substr(17,2)

	var dia2 = fecha2.substr(0,2)
	var mes2 = fecha2.substr(3,2)
	var ano2 = fecha2.substr(6,4)
	var hora2 = fecha2.substr(11,2)
	var minutos2 = fecha2.substr(14,2)
	var segundos2 = fecha2.substr(17,2)
//	alert(dia1+mes1+ano1+"  "+dia2+mes2+ano2)
	var fecha_desde = new Date(ano1,mes1-1/*los meses cuentan entre cero y 11, por eso se suma 1*/,dia1,hora1,minutos1,segundos1);
   	var fecha_hasta = new Date(ano2,mes2-1/*los meses cuentan entre cero y 11, por eso se suma 1*/,dia2,hora2,minutos2,segundos2);
	if (fecha_desde.getTime()>fecha_hasta.getTime())
   		{
   		alert('La fecha desde debe ser menor o igual que la fecha hasta');
  		//for (n=0;n<=fecha_hasta_dia.length-1;n++)
   		//{
   		//	if (fecha_hasta_dia.options[n].value==10)
		//	{
		//		fecha_hasta_dia.options[n].selected = true ;
		//	}
		//}
   		//fecha_hasta_mes.selectedIndex=fecha_desde_mes.selectedIndex;
   		//fecha_hasta_ano.selectedIndex=fecha_desde_ano.selectedIndex;
  		return false ;
  		}
   		else
   		{
   		return true ;
   		}
	}
function fecha_rango(dia,mes,ano,dia1,mes1,ano1,dia2,mes2,ano2) 
	{
		var fecha = new Date(ano.value,mes.value-1/*los meses cuentan entre cero y 11, por eso se suma 1*/,dia.value);
		var fecha_desde = new Date(ano1.value,mes1.value-1/*los meses cuentan entre cero y 11, por eso se suma 1*/,dia1.value);
		var fecha_hasta = new Date(ano2.value,mes2.value-1/*los meses cuentan entre cero y 11, por eso se suma 1*/,dia2.value);
		if ((fecha.getTime()<fecha_desde.getTime()) || (fecha.getTime()>fecha_hasta.getTime()))
		   {
		   alert('La fecha desde se encuentra fuera del rango de la reserva');
		   dia.focus()
		   return false ;
		   }
		   else
		   {
		   return true;
		   }
	}
function validar_hora(campo_hora)  {
	var hora = campo_hora.value;
	var horas = hora.substr(0,2);
	var minutos = hora.substr(3,2);
	//alert(minutos);
	//return false;
	if (!isNaN(horas) && !isNaN(minutos) && hora!="") {
		if ((horas>=0) && (horas<=23) && (minutos>=0) && (minutos<=59)) {
			return true;
		}
		else
		{
		alert("Hora invalida!.....");
		campo_hora.value="00:00";
		return false;
		}
	}
	else
	{
	alert("Hora invalida!...");
	campo_hora.value="00:00";
	return false;
	}
}
/////////////////////////////
//Validacion para campo vacio
/////////////////////////////
function IsEmpty(campo) {
       for (LargoCampoAValidar = 0; LargoCampoAValidar < campo.length; LargoCampoAValidar++) {   
                if (campo.charAt(LargoCampoAValidar) != " ") {  // charAt() evalua cada caracter
                        return true;   
                }   
        }   
        return false;   
}   
  
function validar_campo(aTextField) {
   if ((aTextField.value.length==0) || !IsEmpty(aTextField.value)) {
	   if (aTextField.name=="descripcion") {
		  var alerta = "Debe asignar una " + aTextField.name;
	   } else {
	   	  var alerta = "Debe asignar un " + aTextField.name;
	   }
 	  alert(alerta);
	  aTextField.focus();
      return false;
   } else { 
   	  return true; 
   }
}

function validar_campo_silencioso(aTextField) {
   if ((aTextField.value.length==0) || !IsEmpty(aTextField.value)) {
	  aTextField.focus();
      return false;
   } else { 
   	  return true; 
   }
}

//function validar_campo(campo) 
//	{
//		if ((campo.value==0) || (campo.value==""))
//			{
//			var alerta = "Debe asignar un "+campo.name;
//			alert(alerta);
//			campo.focus();
//			return false;
//			}
//			else
//			{
//			return true;
//			}
//	}	
function validar_solo_numerico(campo) 
	{
		if (isNaN(campo.value)==true || (campo.value=="")) /*tambien se puede usar (isNaN(parseFloat(campo.value) Ej: ver agregar_movim.php*/
			{
			var alerta = campo.name+" Debe ser numerico.";
			alert(alerta);
			campo.focus();
			return false;
			}
			else
			{
			return true;
			}
	}	
function validar_distintos_cero(campo1,campo2) 
	{
	if ((parseFloat(campo1.value)==0) && (parseFloat(campo2.value)==0))
		{
		var alerta = campo1.name+" y "+campo2.name+" deben ser distintos de cero!...";
		alert(alerta);
		campo1.focus();
		return false;
		}
		else
		{
		return true;
		}
	}
function validar_distinto_cero(campo) 
	{
	if ((parseFloat(campo.value)==0))
		{
		var alerta = campo.name+" debe ser distinto de cero!...";
		alert(alerta);
		campo.focus();
		return false;
		}
		else
		{
		return true;
		}
	}
function validar_mayor_cero(campo) 
	{
	if ((parseFloat(campo.value)<=0))
		{
		var alerta = campo.name+" debe ser mayor a cero!...";
		alert(alerta);
		campo.focus();
		return false;
		}
		else
		{
		return true;
		}
	}
function cerrar_ventana2() 
	{
		window.close()
	}
function ir_a_pagina(objeto,restore){ //v3.0
  eval("self.location='"+objeto.options[objeto.selectedIndex].value+"'");
  if (restore) objeto.selectedIndex=0;
}
function confirmSubmit(valor)
	{
	var si=confirm("Desea realmente eliminar "+valor+" ?");
	if (si)
		return true ;
	else
		return false ;
}
function confirmSubmitSinValor(valor)
	{
	var si=confirm(valor);
	if (si)
		return true ;
	else
		return false ;
}
function VerificarEmail(campo_mail){
	if(campo_mail.value.indexOf('@',0)==-1 || campo_mail.value.indexOf(';',0)!=-1
		|| campo_mail.value.indexOf(' ',0)!=-1 || campo_mail.value.indexOf('/',0)!=-1
		|| campo_mail.value.indexOf(';',0)!=-1 || campo_mail.value.indexOf('<',0)!=-1
		|| campo_mail.value.indexOf('>',0)!=-1 || campo_mail.value.indexOf('*',0)!=-1
		|| campo_mail.value.indexOf('|',0)!=-1 || campo_mail.value.indexOf('`',0)!=-1
		|| campo_mail.value.indexOf('&',0)!=-1 || campo_mail.value.indexOf('$',0)!=-1
		|| campo_mail.value.indexOf('!',0)!=-1 || campo_mail.value.indexOf('"',0)!=-1
		|| campo_mail.value.indexOf(':',0)!=-1) {
		alert("Direccion de mail incorrecta!...");
		campo_mail.focus();
		return false;
	} else {
		return true;
	}
}
/*
*Esta libreria es una libreria AJAX creada por Javier Mellado
*y descargada del portal AJAX Hispano http://www.ajaxhispano.com
*contacto javiermellado@gmail.com
*
*Puede ser utilizada, pasada, modificada pero no olvides mantener
*el espiritu del software libre y respeta GNU-GPL
*/ 
function objetus() 
	{
	var objetus=false;
	try {
		objetus = new ActiveXObject("Msxml2.XMLHTTP");
		} catch (e) {
					try {
						objetus = new ActiveXObject("Microsoft.XMLHTTP");
						} catch (E) {
									objetus = false;
									}
						}
		if (!objetus && typeof XMLHttpRequest!='undefined') 
			{
			objetus = new XMLHttpRequest();
			}
		return objetus;
	}
var enviar = false
function ajax_get(_target,archivo,variables) {
	document.getElementById('carga').innerHTML = '<table border=\"0\" cellspacing=\"0\"><tr><td align=\"center\" class=\"div_carga\" scope=\"col\">Procesando </td></tr><tr><td><img src=\"image/Error.png\" width=\"76\" height=\"76\"></td></tr></table>';
	if (enviar) {
		alert("Aguerde un instante por favor. El servidor esta ocupado!...")
	} else {
		enviar = true
		if(ajax_tooltipObj) {
			ajax_hideTooltip();
		}
		ImagenIndicador = new Image()
		ImagenIndicador.src = "indicator.gif"
		ImagenClock_off = new Image()
		ImagenClock_off.src = "clock_off.gif"
		ImagenClock_on = new Image()
		ImagenClock_on.src = "clock_on.gif"
		//alert(archivo+variables)
		_objetus=objetus()
		_values_send_get=variables
		var tiempo = new Date();
		//alert(tiempo.getTime());
		archivo = archivo+"?jsfecha="+tiempo.getTime();
		if (variables==""){
			_URL_=archivo
		} else {
			_URL_=archivo+"&"
		}
		//alert(_URL_+_values_send_get)
		_objetus.open("GET",_URL_+_values_send_get,true);
		_objetus.onreadystatechange=function(){
		if (_objetus.readyState==0){
			var mensaje_carga = "Inicializando......"	
			document.getElementById(_target).innerHTML=mensaje_carga
		} else if (_objetus.readyState==1){
			//var mensaje_carga = "<div id='carga'><img src=\"engranaje-22.gif\" width=\"88\" height=\"67\"></div>";//no toma foco control ingreso si lo descomento
			//var mensaje_carga = "Cargando...";//no toma foco control ingreso si lo descomento
			//var mensaje_carga = "<img src='clock_on.gif' name='ImagenClock_on' /><img src='clock_off.gif' name='ImagenClock_off' /><img src='indicator.gif' name='ImagenIndicador' />Cargando..."//no toma foco control ingreso si lo descomento
			//document.getElementById(_target).innerHTML=mensaje_carga
			document.getElementById('carga').innerHTML = '<table border=\"0\" cellspacing=\"0\"><tr><td align=\"center\" class=\"div_carga\" scope=\"col\">Procesando </td></tr><tr><td><img src=\"image/Error.png\" width=\"76\" height=\"76\"></td></tr></table>';
		} else if (_objetus.readyState==4){
					if (_objetus.status==200){
						//txt=unescape(_objetus.responseText);
						//txt2=txt.replace(/\+/gi," ");
						//tambien se puede probar un replace del signo mas si fuera necesario
						//ademas en el codigo php se debe colocar esto
						//$variable = urlencode($variable);
						//document.getElementById(_target).innerHTML=txt2;
						document.getElementById('carga').innerHTML = '<table border=\"0\" cellspacing=\"0\"><tr><td align=\"center\" class=\"div_carga\" scope=\"col\">Listo </td></tr><tr><td><img src=\"image/Good.png\" width=\"50\" height=\"50\"></td></tr></table>';
						document.getElementById(_target).innerHTML=_objetus.responseText;
						document.location.hash="posicion1"
						//document.location.hash=_target
						setFocus()//da foco si existe un formulario con name="formulario_foco" y un campo name="text_foco o bien solamente con los id"
						enviar = false
					}else if (_objetus.status==404){
						var mensaje_carga = "Documento no encontrado ("+archivo+")"	
						document.getElementById(_target).innerHTML=mensaje_carga
					}else{
						var mensaje_carga = "Error :"+_objetus.responseText;	
						document.getElementById(_target).innerHTML=mensaje_carga
					}
		}
	}
		_objetus.send(null);
	}
}
function ajax_post(_target,archivo,formulario) {
	document.getElementById('carga').innerHTML = '<table border=\"0\" cellspacing=\"0\"><tr><td align=\"center\" class=\"div_carga\" scope=\"col\">Procesando </td></tr><tr><td><img src=\"image/Error.png\" width=\"76\" height=\"76\"></td></tr></table>';
	if (enviar) {
		alert("Aguerde un instante por favor. El servidor esta ocupado!...")
		return false
	} else {
		enviar = true
		if(ajax_tooltipObj) {
			ajax_hideTooltip();
		}
		_values_send_post=""
		if (formulario.elements.length>1)//desde la seguna variable en adelante
			{
			//for(i=1;i<formulario.elements.length;i++)
			for(i=0;i<formulario.elements.length;i++)
				{
				if (formulario.elements[i].type=="checkbox" || formulario.elements[i].type=="radio") // si el objeto es radio o check
					{
					if (formulario.elements[i].checked==true)
						{
						//alert(formulario.elements[i].name+' '+formulario.elements[i].checked+' '+formulario.elements[i].value)
						//eval("_values_send_post=_values_send_post+'&"+formulario.elements[i].name+"="+formulario.elements[i].value+"'")		
						//eval("_values_send_post=_values_send_post+'&"+formulario.elements[i].name+"="+escape(formulario.elements[i].value)+"'")		
						//eval("_values_send_post=_values_send_post+'&"+formulario.elements[i].name+"="+unescape(formulario.elements[i].value)+"'")		
						//eval("_values_send_post=_values_send_post+'&"+formulario.elements[i].name+"="+encodeURI(formulario.elements[i].value)+"'")
						if (i==0) {
								eval("_values_send_post=_values_send_post+'"+formulario.elements[i].name+"="+encodeURIComponent(formulario.elements[i].value)+"'")//traduce los enter en las objetos textarea			
							} else {
								eval("_values_send_post=_values_send_post+'&"+formulario.elements[i].name+"="+encodeURIComponent(formulario.elements[i].value)+"'")//traduce los enter en las objetos textarea			
							}
						//eval("_values_send_post=_values_send_post+'&"+formulario.elements[i].name+"="+formulario.elements[i].value+"'")		
						}
					}
					else
					{
					//alert(formulario.elements[i].name+'='+formulario.elements[i].value)
					//eval("_values_send_post=_values_send_post+'&"+formulario.elements[i].name+"="+formulario.elements[i].value+"'")		
					//eval("_values_send_post=_values_send_post+'&"+formulario.elements[i].name+"="+encodeURI(formulario.elements[i].value)+"'")		
					//eval("_values_send_post=_values_send_post+'&"+formulario.elements[i].name+"="+escape(formulario.elements[i].value)+"'")		
					//eval("_values_send_post=_values_send_post+'&"+formulario.elements[i].name+"="+unescape(formulario.elements[i].value)+"'")		
					if (i==0) {
							eval("_values_send_post=_values_send_post+'"+formulario.elements[i].name+"="+encodeURIComponent(formulario.elements[i].value)+"'")//traduce los enter en las objetos textarea			
						} else {
							eval("_values_send_post=_values_send_post+'&"+formulario.elements[i].name+"="+encodeURIComponent(formulario.elements[i].value)+"'")//traduce los enter en las objetos textarea			
					}
					//eval("_values_send_post=_values_send_post+'&"+formulario.elements[i].name+"="+formulario.elements[i].value+"'")
					}
				}
			}
			//alert(_values_send_post);
		_objetus=objetus()
		_URL_=archivo
		_objetus.open("POST",_URL_,true);
		_objetus.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
		_objetus.setRequestHeader('Accept-Charset', 'UTF-8');
		_objetus.send(_values_send_post);
		//0 - Sin inicializar, siempre será:
		//1 - Abierto (acaba de llamar open)
		//2 - Enviado
		//3 - Recibiendo
		//4 - A punto
		_objetus.onreadystatechange=function(){
		if (_objetus.readyState==2) { 
			document.getElementById('carga').innerHTML = '<table border=\"0\" cellspacing=\"0\"><tr><td align=\"center\" class=\"div_carga\" scope=\"col\">Procesando </td></tr><tr><td><img src=\"image/Error.png\" width=\"76\" height=\"76\"></td></tr></table>';
		}
		if (_objetus.readyState==3) { 
			document.getElementById('carga').innerHTML = '<table border=\"0\" cellspacing=\"0\"><tr><td align=\"center\" class=\"div_carga\" scope=\"col\">Recibiendo </td></tr><tr><td><img src=\"image/Error.png\" width=\"76\" height=\"76\"></td></tr></table>';
		}
		if (_objetus.readyState==1)	{
			//var mensaje_carga = 'Abriendo comunicacion <img src=\"image/Good.png\" width=\"76\" height=\"76\">';
			//no toma foco control ingreso si lo descomento
			//var mensaje_carga = "Cargando..."//no toma foco control ingreso si lo descomento
			document.getElementById('carga').innerHTML = '<table border=\"0\" cellspacing=\"0\"><tr><td align=\"center\" class=\"div_carga\" scope=\"col\">Abriendo </td></tr><tr><td><img src=\"image/Good.png\" width=\"50\" height=\"50\"></td></tr></table>';
			} 
			else if (_objetus.readyState==4)
					{
					if (_objetus.status==200)
						{
						//document.getElementById('carga').innerHTML = 'Listo <img src=\"image/Good.png\" width=\"76\" height=\"76\">';
						//txt=unescape(_objetus.responseText);
						//txt2=txt.replace(/\+/gi," ");
						//tambien se puede probar un replace del signo mas si fuera necesario
						//ademas en el codigo php se debe colocar esto
						//$variable = urlencode($variable);
						//document.getElementById(_target).innerHTML=txt2;
						document.getElementById('carga').innerHTML = '<table border=\"0\" cellspacing=\"0\"><tr><td align=\"center\" class=\"div_carga\" scope=\"col\">Listo </td></tr><tr><td><img src=\"image/Good.png\" width=\"50\" height=\"50\"></td></tr></table>';
						document.getElementById(_target).innerHTML=_objetus.responseText;
						//ocument.location.hash=_target
						document.location.hash="posicion1"
						setFocus()//da foco si existe un formulario con name="formulario_foco" y un campo name="text_foco o bien solamente con los id"
						enviar = false
						}
						else if (_objetus.status==404)
						{
						var mensaje_carga = "Documento no encontrado ("+archivo+")"		
						document.getElementById(_target).innerHTML=mensaje_carga
						}
						else
						{
						var mensaje_carga = "Error :"+_objetus.status;	
						document.getElementById(_target).innerHTML=mensaje_carga
						}
					}
			}
	}
}
function ajax_get_tooltip(_target,archivo,variables) {
	if (enviar) {
		alert("Aguerde un instante por favor. El servidor esta ocupado!...")
	} else {
		enviar = true
		ImagenIndicador = new Image()
		ImagenIndicador.src = "indicator.gif"
		ImagenClock_off = new Image()
		ImagenClock_off.src = "clock_off.gif"
		ImagenClock_on = new Image()
		ImagenClock_on.src = "clock_on.gif"
		//alert(archivo+variables)
		_objetus=objetus()
		_values_send_get=variables
		var tiempo = new Date();
		//alert(tiempo.getTime());
		archivo = archivo+"?jsfecha="+tiempo.getTime();
		if (variables=="")
			{
			_URL_=archivo
			}
			else
			{
			_URL_=archivo+"&"
			}
		//alert(_URL_+_values_send_get)
		_objetus.open("GET",_URL_+_values_send_get,true);
		_objetus.onreadystatechange=function(){
		if (_objetus.readyState==0)
			{
			var mensaje_carga = "Inicializando......"	
			document.getElementById(_target).innerHTML=mensaje_carga
			} 
		else if (_objetus.readyState==1)
			{
			var mensaje_carga = "Cargando...";//no toma foco control ingreso si lo descomento
			//var mensaje_carga = "<img src='clock_on.gif' name='ImagenClock_on' /><img src='clock_off.gif' name='ImagenClock_off' /><img src='indicator.gif' name='ImagenIndicador' />Cargando..."//no toma foco control ingreso si lo descomento
			document.getElementById(_target).innerHTML=mensaje_carga
			} 
			else if (_objetus.readyState==4)
					{
					if (_objetus.status==200)
						{
						//txt=unescape(_objetus.responseText);
						//txt2=txt.replace(/\+/gi," ");
						//tambien se puede probar un replace del signo mas si fuera necesario
						//ademas en el codigo php se debe colocar esto
						//$variable = urlencode($variable);
						//document.getElementById(_target).innerHTML=txt2;
						document.getElementById(_target).innerHTML=_objetus.responseText;
						document.location.hash="posicion1"
						//document.location.hash=_target
						setFocus()//da foco si existe un formulario con name="formulario_foco" y un campo name="text_foco o bien solamente con los id"
						enviar = false
						}
						else if (_objetus.status==404)
						{
						var mensaje_carga = "Documento no encontrado ("+archivo+")"	
						document.getElementById(_target).innerHTML=mensaje_carga
						}
						else
						{
						var mensaje_carga = "Error :"+_objetus.responseText;	
						document.getElementById(_target).innerHTML=mensaje_carga
						}
					}
			}
			_objetus.send(null);
		}
	}
function ajax_post_tooltip(_target,archivo,formulario) {
	if (enviar) {
		alert("Aguerde un instante por favor. El servidor esta ocupado!...")
		return false
	} else {
		enviar = true
		_values_send_post=""
		if (formulario.elements.length>1)//desde la seguna variable en adelante
			{
			//for(i=1;i<formulario.elements.length;i++)
			for(i=0;i<formulario.elements.length;i++)
				{
				if (formulario.elements[i].type=="checkbox" || formulario.elements[i].type=="radio") // si el objeto es radio o check
					{
					if (formulario.elements[i].checked==true)
						{
						//alert(formulario.elements[i].name+' '+formulario.elements[i].checked+' '+formulario.elements[i].value)
						//eval("_values_send_post=_values_send_post+'&"+formulario.elements[i].name+"="+formulario.elements[i].value+"'")		
						//eval("_values_send_post=_values_send_post+'&"+formulario.elements[i].name+"="+escape(formulario.elements[i].value)+"'")		
						//eval("_values_send_post=_values_send_post+'&"+formulario.elements[i].name+"="+unescape(formulario.elements[i].value)+"'")		
						//eval("_values_send_post=_values_send_post+'&"+formulario.elements[i].name+"="+encodeURI(formulario.elements[i].value)+"'")
						if (i==0) {
								eval("_values_send_post=_values_send_post+'"+formulario.elements[i].name+"="+encodeURIComponent(formulario.elements[i].value)+"'")//traduce los enter en las objetos textarea			
							} else {
								eval("_values_send_post=_values_send_post+'&"+formulario.elements[i].name+"="+encodeURIComponent(formulario.elements[i].value)+"'")//traduce los enter en las objetos textarea			
							}
						//eval("_values_send_post=_values_send_post+'&"+formulario.elements[i].name+"="+formulario.elements[i].value+"'")		
						}
					}
					else
					{
					//alert(formulario.elements[i].name+'='+formulario.elements[i].value)
					//eval("_values_send_post=_values_send_post+'&"+formulario.elements[i].name+"="+formulario.elements[i].value+"'")		
					//eval("_values_send_post=_values_send_post+'&"+formulario.elements[i].name+"="+encodeURI(formulario.elements[i].value)+"'")		
					//eval("_values_send_post=_values_send_post+'&"+formulario.elements[i].name+"="+escape(formulario.elements[i].value)+"'")		
					//eval("_values_send_post=_values_send_post+'&"+formulario.elements[i].name+"="+unescape(formulario.elements[i].value)+"'")		
					if (i==0) {
							eval("_values_send_post=_values_send_post+'"+formulario.elements[i].name+"="+encodeURIComponent(formulario.elements[i].value)+"'")//traduce los enter en las objetos textarea			
						} else {
							eval("_values_send_post=_values_send_post+'&"+formulario.elements[i].name+"="+encodeURIComponent(formulario.elements[i].value)+"'")//traduce los enter en las objetos textarea			
					}
					//eval("_values_send_post=_values_send_post+'&"+formulario.elements[i].name+"="+formulario.elements[i].value+"'")
					}
				}
			}
			//alert(_values_send_post);
		_objetus=objetus()
		_URL_=archivo
		_objetus.open("POST",_URL_,true);
		_objetus.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
		_objetus.setRequestHeader('Accept-Charset', 'UTF-8');
		_objetus.send(_values_send_post);
		_objetus.onreadystatechange=function(){
		if (_objetus.readyState==1)
			{
			var mensaje_carga = "Cargando..."//no toma foco control ingreso si lo descomento
			document.getElementById(_target).innerHTML=mensaje_carga
			} 
			else if (_objetus.readyState==4)
					{
					if (_objetus.status==200)
						{
						//txt=unescape(_objetus.responseText);
						//txt2=txt.replace(/\+/gi," ");
						//tambien se puede probar un replace del signo mas si fuera necesario
						//ademas en el codigo php se debe colocar esto
						//$variable = urlencode($variable);
						//document.getElementById(_target).innerHTML=txt2;
						document.getElementById(_target).innerHTML=_objetus.responseText;
						//ocument.location.hash=_target
						document.location.hash="posicion1"
						setFocus()//da foco si existe un formulario con name="formulario_foco" y un campo name="text_foco o bien solamente con los id"
						enviar = false
						}
						else if (_objetus.status==404)
						{
						var mensaje_carga = "Documento no encontrado ("+archivo+")"		
						document.getElementById(_target).innerHTML=mensaje_carga
						}
						else
						{
						var mensaje_carga = "Error :"+_objetus.status;	
						document.getElementById(_target).innerHTML=mensaje_carga
						}
					}
			}
	}
}
function setFocus() 
{
  var f = null;
  if (document.getElementById("formulario_foco")) 
  		{ 
   		f = document.getElementById("formulario_foco");
				if (f.text_foco)
					{
	      			f.text_foco.focus();
					}
					else
					{
					alert('No existe elemento text con name = text_foco')
					}
		}
}
var ventanaLista=false
function abrir_ventana_cabecera(pagina_destino,variables_get,nombre_formulario_destino,campo_codigo_destino,campo_descripcion_destino){
	if (typeof ventanaLista.document == "object") {
		ventanaLista.close()
	}
	ventanaLista = window.open(pagina_destino+"?nombre_formulario_destino="+nombre_formulario_destino+"&campo_codigo_destino="+campo_codigo_destino+"&campo_descripcion_destino="+campo_descripcion_destino+"&"+variables_get,"Consulta", "height=500, width=700, left=0, top=0, toolbar=no, menubar=no, titlebar=no, resizable=no, scrollbars=yes");
}
function devuelve_valores(codigo,descripcion,nombre_formulario_destino,campo_codigo_destino,campo_descripcion_destino,campo_codigo_blanquear,campo_descripcion_blanquear,boton){
	//Se encarga de escribir en el formulario adecuado los valores seleccionados
	//también debe cerrar la ventana del calendario
	//eval("opener.document." + nombre_formulario_destino + ".Submit"+campo_descripcion_destino + ".disabled='true'")
	//alert("opener.document." + nombre_formulario_destino + "." + campo_codigo_destino + ".value='" + codigo + "'")
	eval ("opener.document." + nombre_formulario_destino + "." + campo_codigo_destino + ".value='" + codigo + "'")
	eval ("opener.document." + nombre_formulario_destino + "." + campo_descripcion_destino + ".value='" + descripcion + "'")
	//eval ("opener.document." + nombre_formulario_destino + ".submit()")
	window.close()
}
function validar_mail(div,pagina_destino,formulario,direccion,nombre,asunto,mensaje)
	{
	if 	(!validar_campo(direccion) || !validar_campo(nombre) || !validar_campo(asunto) || !validar_campo(mensaje)) 
		{
		return false
		}
		else
		{
		ajax_post(div,pagina_destino,formulario)
		}
	}
//Para tooltip javascript
var theObj = ""; 
function toolTip(text,me) {
	theObj=me;
	theObj.onmousemove=updatePos;
	document.getElementById('toolTipBox').innerHTML=text;
	document.getElementById('toolTipBox').style.display="block";
	window.onscroll=updatePos;
}
function updatePos() {
	var ev = arguments[0]?arguments[0]:event;
	var x  = ev.clientX;
	var y  = ev.clientY;
	diffX = 24;
	diffY = 0;
	document.getElementById('toolTipBox').style.top = y-2 + diffY + document.body.scrollTop + 'px';
	document.getElementById('toolTipBox').style.left = x-2 + diffX + document.body.scrollLeft + 'px';
	theObj.onmouseout=hideMe;
}
function hideMe() {
	document.getElementById('toolTipBox').style.display = "none";
}	
//Devualeve posicion en pantalla
var PosicionX=0
var PosicionY=0
function CapturarPosicionEnPantalla(objeto) {
	theObj=objeto;
	theObj.onmousemove=VerificarPosicion;
	//alert(PosicionX+' - '+PosicionY)
}
function VerificarPosicion() {
	var ev = arguments[0]?arguments[0]:event;
	PosicionX = ev.clientX
	PosicionY = ev.clientY
}

function IsEmpty(campo) {
       for (LargoCampoAValidar = 0; LargoCampoAValidar < campo.length; LargoCampoAValidar++) {   
                if (campo.charAt(LargoCampoAValidar) != " ") {  // charAt() evalua cada caracter
                        return true;   
                }   
        }   
        return false;   
}   

function validar_campo(TextField){
		if(TextField.value.lenght==0 || !IsEmpty(TextField.value)){
			alert("Debe asignar un "+ TextField.name);
			TextField.focus;
			return false;
		} else {
			return true;
		}
}

function validar_ganador(div,destino,formulario){
	//alert(formulario.id_tipo_pago.value);
	if (formulario.valor_premio.value<50001) {
		alert ('El VALOR del PREMIO debe ser mayor a $50000');
		return false;
	}
	if (formulario.id_tipo_pago.value=='2'){
		//alert('entrooo');
		if(!validar_campo(formulario.cheque_nro)){
			//alert('Debe ingresar Numero de Cheque');
		return false;
		}	
	
	}
	
	if (formulario.politico.value=='SELECCIONE'){
	alert ('Seleccione SI o NO en Persona Politicamente Expuesta');
			return false;
	}
	if (formulario.ddjj.value==''){
	alert ('Ingrese numero de DDJJ');
			return false;
	}
	
	if(!validar_campo(formulario.documento) || !validar_campo(formulario.nombre) ||!validar_campo(formulario.apellido) ||!validar_campo(formulario.nacionalidad) || !validar_campo(formulario.calle)|| !validar_campo(formulario.numero)  || !validar_solo_numerico(formulario.valor_premio) || !validar_campo(formulario.nro_ticket)|| !validar_campo(formulario.domicilio_pago)){
		
		return false;
		// alert('se pira');
	} else {
		
		if (formulario.chk_extranjero.checked && (formulario.nacionalidad.value.toUpperCase()=='ARGENTINA' || formulario.nacionalidad.value.toUpperCase()=='ARGENTINO')) {
			alert ('Figura como extranjero y nacionalidad Argentina/o');
			return false;
		} else {
			//alert('se pira');
		 	ajax_post(div,destino,formulario);
		}		 
	}
}

function validar_modificar_ganador(div,destino,formulario){
	//alert(formulario.valor_premio);
	if (formulario.valor_premio.value<=10000) {
		alert ('El VALOR del PREMIO debe ser mayor a $10000');
		return false;
	}
	if (formulario.politico.value=='SELECCIONE'){
	alert ('Seleccione SI o NO en Persona Politicamente Expuesta');
			return false;
	}
	if (formulario.ddjj.value==''){
	alert ('Ingrese numero de DDJJ');
			return false;
	}
	// ACORDATE DE PONER || !validar_campo(formulario.numero) <--- ALTURA DE LA CALLE DEL DOMICILIO
	if(!validar_campo(formulario.documento) || !validar_campo(formulario.nombre) ||!validar_campo(formulario.apellido) ||!validar_campo(formulario.nacionalidad) || !validar_campo(formulario.calle) || !validar_solo_numerico(formulario.valor_premio) || !validar_campo(formulario.nro_ticket)|| !validar_campo(formulario.domicilio_pago)){
			 return false;
	} else {
		if (formulario.chk_extranjero.checked && (formulario.nacionalidad.value.toUpperCase()=='ARGENTINA' || formulario.nacionalidad.value.toUpperCase()=='ARGENTINO')) {
			alert ('Figura como extranjero y nacionalidad Argentina/o');
			return false;
		} else {
		 	ajax_post(div,destino,formulario);
		}
	}
}

function validar_nota(div,destino,formulario){
	if(!validar_campo(formulario.nota)){
			 return false;
	} else {
		ajax_post(div,destino,formulario);
		}
}

function validar_modificacion_nombre(div,destino,formulario){
	if(!validar_campo(formulario.nombre)){
			 return false;
	} else {
		ajax_post(div,destino,formulario);
		}
}

function confirmar_eliminar_planilla(div,pagina_destino, imagen){	 
	var si=confirm("Esta seguro de eliminar la Planilla?");
	if (si)	
	{ ajax_get(div,pagina_destino,imagen);
		return true ;
	}
	else
	{ 
		return false ;
	}
}


function confirmar_eliminar_imagen(div,pagina_destino, imagen){
	 
	var si=confirm("Esta seguro de eliminar la imagen ?");
	if (si)	
	{ ajax_get(div,pagina_destino,imagen);
		return true ;
	}
	else
	{ 
		return false ;
	}
}


function confirmar_eliminar_ganador(div,pagina_destino, ganador){
	 
	var si=confirm("Esta seguro de eliminar el ganador ?");
	if (si)	
	{ ajax_get(div,pagina_destino,ganador);
		return true ;
	}
	else
	{ 
		return false ;
	}
}

function ajax_get_refresca(_target,archivo,variables) {
	document.getElementById('carga').innerHTML = '<table border=\"0\" cellspacing=\"0\"><tr><td align=\"center\" class=\"div_carga\" scope=\"col\">Procesando </td></tr><tr><td align=\"center\"><img src=\"image/spinner-0.gif\" width=\"16\" height=\"16\"></td></tr></table>';
		//if(ajax_tooltipObj) {
			//ajax_hideTooltip();
		//}
		ImagenIndicador = new Image()
		ImagenIndicador.src = "indicator.gif"
		ImagenClock_off = new Image()
		ImagenClock_off.src = "clock_off.gif"
		ImagenClock_on = new Image()
		ImagenClock_on.src = "clock_on.gif"
		//alert(archivo+variables)
		_objetus=objetus()
		_values_send_get=variables
		var tiempo = new Date();
		//alert(tiempo.getTime());
		archivo = archivo+"?jsfecha="+tiempo.getTime();
		if (variables=="")
			{
			_URL_=archivo
			}
			else
			{
			_URL_=archivo+"&"
			}
		//alert(_URL_+_values_send_get)
		_objetus.open("GET",_URL_+_values_send_get,true);
		_objetus.onreadystatechange=function(){
		if (_objetus.readyState==0)
			{
			var mensaje_carga = "Inicializando......"	
			document.getElementById(_target).innerHTML=mensaje_carga
			} 
		else if (_objetus.readyState==1)
			{
			//var mensaje_carga = "<div id='carga'><img src=\"engranaje-22.gif\" width=\"88\" height=\"67\"></div>";//no toma foco control ingreso si lo descomento
			//var mensaje_carga = "Cargando...";//no toma foco control ingreso si lo descomento
			//var mensaje_carga = "<img src='clock_on.gif' name='ImagenClock_on' /><img src='clock_off.gif' name='ImagenClock_off' /><img src='indicator.gif' name='ImagenIndicador' />Cargando..."//no toma foco control ingreso si lo descomento
			//document.getElementById(_target).innerHTML=mensaje_carga
			document.getElementById('carga').innerHTML = '<table border=\"0\" cellspacing=\"0\"><tr><td align=\"center\" class=\"div_carga\" scope=\"col\">Procesando </td></tr><tr><td  align=\"center\"><img src=\"image/spinner-0.gif\" width=\"16\" height=\"16\"></td></tr></table>';
			} 
			else if (_objetus.readyState==4)
					{
					if (_objetus.status==200)
						{
						//txt=unescape(_objetus.responseText);
						//txt2=txt.replace(/\+/gi," ");
						//tambien se puede probar un replace del signo mas si fuera necesario
						//ademas en el codigo php se debe colocar esto
						//$variable = urlencode($variable);
						//document.getElementById(_target).innerHTML=txt2;
						document.getElementById('carga').innerHTML = '<table border=\"0\" cellspacing=\"0\"><tr><td align=\"center\" class=\"div_carga\" scope=\"col\">Listo </td></tr><tr><td align=\"center\"><img src=\"image/A75TGI2CAL4XLCXCARW61D9CA57QKVDCAB5NO6GCAMFP7P3CADSPJ08CA9C1YM2CA2KA2D4CANTCELCCACJ0T4OCAUXGLGOCAFJOIARCAQ3QYQUCAN4YTFCCASBXU2LCAPO250NCAR6K5ZQ.jpg\" width=\"32\" height=\"32\"></td></tr></table>';
						document.getElementById(_target).innerHTML=_objetus.responseText;
						//document.location.hash="posicion1"
						//document.location.hash=_target
						//setTimeout("setFocus()",100);
						//setFocus()//da foco si existe un formulario con name="formulario_foco" y un campo name="text_foco o bien solamente con los id"
						enviar = false
						}
						else if (_objetus.status==404)
						{
						var mensaje_carga = "Documento no encontrado ("+archivo+")"	
						document.getElementById(_target).innerHTML=mensaje_carga
						}
						else
						{
						var mensaje_carga = "Error :"+_objetus.responseText;	
						document.getElementById(_target).innerHTML=mensaje_carga
						}
					}
			}
			_objetus.send(null);
	}

function refresca(refrescar, tiempo){
	if (refrescar==0) {
		clearInterval(paramatar);
	} else {
		paramatar=setInterval("ajax_get_refresca('refresca','refresca.php','')",tiempo);
	}
}
function xml_marcar(nombre, valor){
	for (i=0;i<document.getElementById(nombre).elements.length;i++) {
		tipo=document.getElementById(nombre).elements[i].type;

      	if(document.getElementById(nombre).elements[i].type == "checkbox") {
			//alert(document.getElementById(nombre).elements[i].name.substring(0,4));
			var nombre1=document.getElementById(nombre).elements[i].name.substring(0,4);
	  	  	if(nombre1=='xml_'){
				document.getElementById(nombre).elements[i].checked=valor; 			  
				}
			}
		}
	} 
/** TEST DE GRAFICAS */
function vercharts(idchart,file){
	
	//alert('DEBUG DE CODIGO: '+document.getElementById(idchart).id);
	document.getElementById(idchart).width=950;
	document.getElementById(idchart).height=300;
	document.getElementById(idchart).contentWindow.location.href=file;
	//'f_reportes/rep_chart.php'
}
/** CIERRE DEL TEST DE GRAFICAS */
function verchartscierre(idchart,file){
	
	//alert('DEBUG DE CODIGO: '+document.getElementById(idchart).id);
	document.getElementById(idchart).width=0;
	document.getElementById(idchart).height=0;
	document.getElementById(idchart).contentWindow.location.href=file;
	//'f_reportes/rep_chart.php'
}

function animarLibro(){
	$( "#book" ).animate({
	opacity: 0.25,
	left: "+=50",
	height: "toggle"
	}, 5000, function() {
	// Animation complete.
	});
}

function ampliarImagen(){
	if($( "#book" ).width()==20)
	{
	$( "#book" ).animate({
	opacity: 0.60,
	width: "21", 
	height: "20",
	left: "+=450"
	//text('Control de Operacion Sospechosa')
	//height: "toggle"
	//height: "toggle"
	}, 5000, function() {
	// Animation complete.
	})}
	else
	{
	$( "#book" ).animate({
	opacity: 1.00,
	width: "20", 
	height: "20",
	left: "-=450"
	//height: "toggle"
	}, 5000, function() {
	// Animation complete.
	})};
}

function ampliarImagen1(){
	if($( "#book" ).width()==20)
	{
	$( "#book" ).animate({
	opacity: 0.60,
	width: "40", 
	height: "40",
	left: "+=450"
	//text('Control de Operacion Sospechosa')
	//height: "toggle"
	//height: "toggle"
	}, 5000, function() {
	// Animation complete.
	})}
	else
	{
	$( "#book" ).animate({
	opacity: 1.00,
	width: "20", 
	height: "20",
	left: "-=450"
	//height: "toggle"
	}, 5000, function() {
	// Animation complete.
	})};
}
function toggleTr_mod(id){	
	$('#tr_'+id).toggle('slow');
	ajax_get('tr_'+id,'denegado/modificar_informado.php','id_id='+id);
}

function toggleTr_eli(id){	
	$('#tr_'+id).toggle('slow');
	ajax_get('tr_'+id,'denegado/controla_eliminacion_informado.php','id_id='+id);
}
function toggleTrd(id){	
	$('#trd_'+id).toggle('slow');	
}
function toggleTrdd(id){	
	$('#trdd_'+id).toggle('slow');
	ajax_get('trdd_'+id,'denegado/modificar_informado.php','id_id='+id);
}

function ver_historico_2(destino,archivo,parametros,id){
	
	var table_tr_div = document.getElementById('div2_'+id);
	//var table_tr_div = destino;
		
	if(table_tr_div.style.display == "block"){
		table_tr_div.style.display = "none";
	}else{
		table_tr_div.style.display = "block";
	}
	
	ajax_get(destino,archivo,parametros); return false;
	
}
function ver_eliminar(destino,archivo,parametros,id){
	
	var table_tr_dive = document.getElementById('dive_'+id);
	//var table_tr_div = destino;
		
	if(table_tr_dive.style.display == "block"){
		table_tr_dive.style.display = "none";
	}else{
		table_tr_dive.style.display = "block";
	}
	
	ajax_get(destino,archivo,parametros); return false;
	
}
function esconder(ff)
{
	if(ff=='0')
	{
		oo=document.getElementById('control');
		oo.style.visibility='hidden';
		//$('#tablita').change($('#control').toggle());
		//$('#control').toggle();
	}
}
function mostrar(ff)
{
	if(ff=='0')
	{
		oo=document.getElementById('control');
		oo.style.visibility='visible';
		//$('#tablita').change($('#control').toggle());
		//$('#control').toggle();
	}
}
function llevar_a_cero(campo)
{
	if(campo=='0')
	{
		rr=document.getElementById('controlpesos');
		rr.style.display="none";
		//rr=document.getElementById('celda');
		//rr.style.display="none";
		if($('#oculto').val()=="1")
			{
				oo=document.getElementById('control');
				oo.style.visibility='hidden';
			}
		if($('#oculto').val()=="0")
			{
				oo=document.getElementById('control');
				oo.style.visibility='visible';
			}
		//$('#tablita').toggle();
		$('#pesitos').focus();
		$('#pesitos').val('0.00');		
	}
}
//'controlpesos','alerta/ctrlpesos.php','estad','this'
function validapeso(div,pagina,formulario,cadena)
{
	if(document.getElementById('control').style.visibility=='visible')
		{
			var xx=document.getElementById('controlpesos');
			xx.style.display='inline-block';
			//alert(div+pagina+formulario+cadena);
			//esconder('0');
			ajax_get(div,pagina,cadena);
			//llevar_a_cero('0');
		}
		else
		{
				var oo=document.getElementById('control');
				var xx=document.getElementById('controlpesos');
				if(oo.style.visibility=='hidden' || $('#oculto').val()==1)
					{
						oo.style.visibility='visible';
					}		
			xx.style.display='none';
			//mostrar('0');
		}
	
}

//esto limpia espacios de un objeto
function LimpiaEspacios(Obj)
{
var texto = Obj.value;
//limpiamos de espacios en blanco el texto
var texto_limpio = texto.replace(/^\s+|\s+$/g,"");

		if (texto_limpio=="")
		{
		Obj.value = "";
		//Obj.focus();
		alert(texto_limpio);
		return true;
		}
		else
		{		
		return false;
		}
}

function center(elemento){
	$(elemento).css("position","absolute");
	$(elemento).css("display","block");

    $(elemento).css("top", Math.max(0, (($(window).height() - $(elemento).outerHeight()) / 2) + 
                                                $(window).scrollTop() + 40) + "px");
    $(elemento).css("left", Math.max(0, (($(window).width() - $(elemento).outerWidth()) / 2) + 
                                                $(window).scrollLeft()) + "px");
}
