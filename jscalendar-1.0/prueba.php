<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin t&iacute;tulo</title>
<link rel="stylesheet" type="text/css" media="all" href="calendar-blue.css" title="blue" />
<!--<link rel="stylesheet" type="text/css" media="all" href="skins/aqua/theme.css" title="Aqua" />-->
<!--<link rel="alternate stylesheet" type="text/css" media="all" href="calendar-blue.css" title="winter" />-->
<!--<link rel="alternate stylesheet" type="text/css" media="all" href="calendar-blue2.css" title="blue" />-->
<!--<link rel="alternate stylesheet" type="text/css" media="all" href="calendar-brown.css" title="summer" />-->
<!--<link rel="alternate stylesheet" type="text/css" media="all" href="calendar-green.css" title="green" />-->
<!--<link rel="alternate stylesheet" type="text/css" media="all" href="calendar-win2k-1.css" title="win2k-1" />-->
<!--<link rel="alternate stylesheet" type="text/css" media="all" href="calendar-win2k-2.css" title="win2k-2" />-->
<!--<link rel="alternate stylesheet" type="text/css" media="all" href="calendar-win2k-cold-1.css" title="win2k-cold-1" />-->
<!--<link rel="alternate stylesheet" type="text/css" media="all" href="calendar-win2k-cold-2.css" title="win2k-cold-1" />-->
<!--<link rel="alternate stylesheet" type="text/css" media="all" href="calendar-system.css" title="system" />-->
<!--<style type="text/css">@import url(calendar-win2k-1.css);</style>-->
<script type="text/javascript" src="calendar.js"></script>
<script type="text/javascript" src="lang/calendar-es.js"></script>
<script type="text/javascript" src="calendar-setup.js"></script>

</head>

<body>

<form id="form1" name="form1" method="post" action="">
  <input type="text" name="data" id="data" value="25/01/2008"/>
  <button id="trigger">...</button>
  <script type="text/javascript">
  Calendar.setup(
		{
		  inputField  : "data",         // ID of the input field
		  ifFormat    : "%d/%m/%Y %H:%M:%S",    // the date format
		  button      : "trigger",       // ID of the button
		  timeFormat  : "24",
		  showOthers  : true,
		  showsTime   : true,
		  range		  : [1900,2099],
		  weekNumbers : true
		}
	);
  </script>
</form>
</body>
</html>
