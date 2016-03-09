<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
	<title>Untitled</title>
	<LINK REL="stylesheet" TYPE="text/css" HREF="Calendar.css">
	<SCRIPT  LANGUAGE="JavaScript1.2" SRC="Calendar1-901.js"></SCRIPT>
<SCRIPT Language="Javascript">
function TryCallFunction() {
	var sd = document.MForm.mydate1.value.split("-");
	document.MForm.iday.value = sd[1];
	document.MForm.imonth.value = sd[0];
	document.MForm.iyear.value = sd[2];
}
var styleVar = "td { font-family:Courier; font-size:12 }";
</SCRIPT>
<!-- A Separate Layer for the Calendar -->
<!-- Make sure to use the name Calendar for this layer -->
<SCRIPT Language="Javascript" TYPE="text/javascript">
Calendar.CreateCalendarLayer(10, 275, "");
</SCRIPT>
</head>

<body>
<form name="MForm" action="tutorial.html">
Date #1 Selected: <input type="text" name="mydate1" value=""><BR>
<input type="button" value="Popup Calendar" name="mpick3" onClick="show_calendar('MForm.mydate1', '','','', null,'')"><BR>
</form>


</body>
</html>
