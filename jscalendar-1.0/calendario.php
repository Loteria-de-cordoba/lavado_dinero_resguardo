<?php 
function abrir_calendario($nombrecampo,$nombreformulario,$valor ){
	echo '
	  <input type="text" name="'.$nombrecampo.'" id="'.$nombrecampo.'" value="'.$valor.'" class="small" readonly="true" />
	  <img id="trigger'.$nombrecampo.'" onMouseOver="CambiarImagen(this); 
	  								  Calendar.setup(
													  {
														  inputField  : \''.$nombrecampo.'\',         
														  ifFormat    : \'%d/%m/%Y\',    
														  button      : \'trigger'.$nombrecampo.'\',       
														  timeFormat  : \'24\',
														  showOthers  : true,
														  showsTime   : true,
														  range		  : [1900,2099],
														  weekNumbers : true
														}  
													);"
						 onMouseOut="RegresarImagen(this);"						
						 border="0" " src="jscalendar-1.0/imagen.png" alt="Abrir Calendario"/>';	
}
function abrir_calendario_submit($nombrecampo,$nombreformulario,$valor ){
	echo '
	  <input type="text" name="'.$nombrecampo.'" id="'.$nombrecampo.'" value="'.$valor.'" class="small" readonly="true" onChange="document.form_fecha.submit()"/>
	  <img id="trigger'.$nombrecampo.'" onMouseOver="CambiarImagen(this); 
	  								  Calendar.setup(
													  {
														  inputField  : \''.$nombrecampo.'\',         
														  ifFormat    : \'%d/%m/%Y\',    
														  button      : \'trigger'.$nombrecampo.'\',       
														  timeFormat  : \'24\',
														  showOthers  : true,
														  showsTime   : true,
														  range		  : [1900,2099],
														  weekNumbers : true
														}  
													);"
						 onMouseOut="RegresarImagen(this);"						
						 border="0" " src="jscalendar-1.0/imagen.png" alt="Abrir Calendario"/>';	
}
function abrir_calendario_ajax_get($nombrecampo,$nombreformulario,$valor){
	echo '
	  <input type="text" name="'.$nombrecampo.'" id="'.$nombrecampo.'" value="'.$valor.'" class="small" readonly="true" onChange="ajax_get(\'contenido\',\'adm_premio.php\',\'fecha=\'+this.value);"/>
	  <img id="trigger'.$nombrecampo.'" onMouseOver="CambiarImagen(this); 
	  								  Calendar.setup(
													  {
														  inputField  : \''.$nombrecampo.'\',         
														  ifFormat    : \'%d/%m/%Y\',    
														  button      : \'trigger'.$nombrecampo.'\',       
														  timeFormat  : \'24\',
														  showOthers  : true,
														  showsTime   : true,
														  range		  : [1900,2099],
														  weekNumbers : true
														}  
													);"
						 onMouseOut="RegresarImagen(this);"						
						 border="0" " src="jscalendar-1.0/imagen.png" alt="Abrir Calendario"/>';	
}
?>