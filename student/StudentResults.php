<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<?php 

$browser  =  $_SERVER["HTTP_USER_AGENT"];   

$ip  =  $_SERVER["REMOTE_ADDR"];   
include 'conn/conn.php';$sql  =  "INSERT INTO stats(ip,browser,received,page) VALUES('$ip','$browser',now(),'<a href=\"studentResults.php\"> student Search Engine </a>')" ;   
$sqldel = "delete from stats where (YEAR(CURRENT_DATE)-YEAR(received))- (RIGHT(CURRENT_DATE,5)<RIGHT(received,5))>1";
$results  =  mysql_query ( $sql); 
$results  =  mysql_query ( $sqldel); 

?> 
<html>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<meta http-equiv="Content-Language" content="pt">
<meta name="VI60_defaultClientScript" content="JavaScript">
<meta name="GENERATOR" content="Microsoft FrontPage 4.0">
<meta name="ProgId" content="FrontPage.Editor.Document">
<title>Search Hits</title>
<link rel="stylesheet" type="text/css" href="/master.css">
<style type="text/css">
<!--
.style17 {font-size: small; color: #000066;}
.style21 {font-size: 11px}
.style22 {color: #CC9999}
-->
</style>
</head>

<body bgcolor="#FFFFCC">
<div align="center">
  <center>
    <tr> 
      <td width="100%" height="48"></td>
    </tr>
  </center>
</div>
<div align="center">
  <center>
    <table border="0" cellspacing="0" cellpadding="0" width="100%" align="centre">
      <tr> 
        <td height="68" valign="middle" colspan="7" bgcolor="006699" align="center"> 
          <img src="/images/Nkurumah.gif" width="766" height="69" align="absmiddle"></td>
      </tr>
      <tr> 
        <td colspan="3" valign="middle" bgcolor="#FFFF00" height="52" align="center" nowrap> 
          <div align="left"><font face="Verdana" color="#FFFF00"><b><a href="/administrator/adminstart.html"><font color="#006699">Welcome</font></a><font color="#006699"> 
            -&gt; Search Results</font></b></font></div>
        </td>
        <td valign="top" width="133" bgcolor="#FFFF00" align="left">&nbsp;</td>
        <td valign="middle" colspan="3" align="center" bgcolor="#FFFF00"> 
          <form method="get" action="/Results.php">
            <div align="right"><font color="#FFFF00" face="Verdana"><b><font color="006699">Search</font></b></font> 
              <font color="006699" face="Verdana"><b> 
              <input type="text" name="content" size="15">
              </b></font><font color="#FFFF00" face="Verdana"><b> 
              <input type="submit" value="GO" name="go">
              </b></font> 
              <?php $today = date("F j, Y, g:i a");   
print  $today;
?>
            </div>
          </form>
        </td>
      </tr>
      <tr> 
        <td valign="top" width="143" rowspan="4"><table width="100%" border="0" cellpadding="0" cellspacing="0" bordercolor="#FFFFCC" bgcolor="#FFFFFF">
          <tr>
            <td width="19" height="0"></td>
            <td width="4"></td>
            <td width="109"></td>
            <td></td>
            <td width="4"></td>
          </tr>
          <tr>
            <td valign="middle" align="right" height="20"><a href="/events.php"><img height=10 alt=Events 
                  hspace=4 src="/images/Arrow-content.gif" width=10 
                  vspace=5 border=0></a></td>
            <td colspan="2" align="left" valign="middle" class="style17"><font face="Verdana" class="normaltext">News</font> </td>
            <td></td>
            <td></td>
          </tr>
          <tr>
            <td valign="middle" align="right" height="20"><a href="/student/studentlist.php"><img height=10 alt=students 
                  hspace=4 src="/images/Arrow-content.gif" width=10 
                  vspace=5 border=0></a></td>
            <td colspan="2" align="left" valign="middle" class="style14 style9 normaltext"><span class="normaltext">About USAB</span></td>
            <td></td>
            <td></td>
          </tr>
          <tr>
            <td valign="middle" align="right" height="20"><a href="/student/studentlist.php"><img height=10 alt=students 
                  hspace=4 src="/images/Arrow-content.gif" width=10 
                  vspace=5 border=0></a></td>
            <td colspan="2" align="left" valign="middle" class="normaltext">Contact Us </td>
            <td></td>
            <td></td>
          </tr>
          <tr>
            <td height="20" colspan="5" align="right" valign="middle"><div align="left">
                <p>&nbsp;</p>
                <p><font face="Verdana" class="normaltext"><strong>Room Allocation</strong></font></p>
            </div></td>
          </tr>
          <tr bgcolor="#FFCCCC">
            <td height="20" align="right" valign="middle"><a href="/HISPNetwork.php" class="style22"><img height=10 alt=HISPNetwork Network 
                  hspace=4 src="/images/Arrow-content.gif" width=10 
                  vspace=5 border=0></a></td>
            <td colspan="2" align="left" valign="middle" nowrap><span class="normaltext style22"><a href="/HallOne.php">Hall One </a></span></td>
            <td><span class="style22"></span></td>
            <td><span class="style22"></span></td>
          </tr>
          <tr>
            <td height="0"></td>
            <td class="style14 style9 normaltext"></td>
            <td class="style14 style9 normaltext"></td>
            <td></td>
            <td></td>
          </tr>
          <tr>
            <td valign="middle" align="right" height="20"><a href="/AlumniList.php"><img height=10 alt=Alumni 
                  hspace=4 src="/images/Arrow-content.gif" width=10 
                  vspace=5 border=0></a></td>
            <td colspan="2" align="left" valign="middle" class="normaltext"><span class="style21"><font face="Verdana"><a href="/HallTwo.php">Hall Two </a></font></span></td>
            <td></td>
            <td></td>
          </tr>
          <tr>
            <td height="23" valign="middle" align="right"><a href="/ReferenceList.php"><img height=10 alt=ContactUs Our Guest Book 
                  hspace=4 src="/images/Arrow-content.gif" width=10 
                  vspace=5 border=0 align="bottom"></a></td>
            <td colspan="2" align="left" valign="middle" class="normaltext"><a href="/HallThree.php">Hall Three </a></td>
            <td></td>
            <td></td>
          </tr>
          <tr>
            <td height="23" valign="middle" align="right"><a href="/ReferenceList.php"><img height=10 alt=ContactUs Our Guest Book 
                  hspace=4 src="/images/Arrow-content.gif" width=10 
                  vspace=5 border=0 align="bottom"></a></td>
            <td colspan="2" align="left" valign="middle" class="normaltext"><a href="/HallFour.php">Hall Four </a></td>
            <td></td>
            <td></td>
          </tr>
          <tr>
            <td height="23" valign="middle" align="right"><a href="/ReferenceList.php"><img height=10 alt=ContactUs Our Guest Book 
                  hspace=4 src="/images/Arrow-content.gif" width=10 
                  vspace=5 border=0 align="bottom"></a></td>
            <td colspan="2" align="left" valign="middle" class="normaltext"><a href="/HallFive.php">Hall Five </a></td>
            <td></td>
            <td></td>
          </tr>
          <tr>
            <td height="23" valign="middle" align="right"><a href="/ReferenceList.php"><img height=10 alt=ContactUs Our Guest Book 
                  hspace=4 src="/images/Arrow-content.gif" width=10 
                  vspace=5 border=0 align="bottom"></a></td>
            <td colspan="2" align="left" valign="middle" class="normaltext"><a href="/HallFive.php">Hall Six </a></td>
            <td></td>
            <td></td>
          </tr>
          <tr>
            <td height="23" valign="middle" align="right"><a href="/ReferenceList.php"><img height=10 alt=ContactUs Our Guest Book 
                  hspace=4 src="/images/Arrow-content.gif" width=10 
                  vspace=5 border=0 align="bottom"></a></td>
            <td colspan="2" align="left" valign="middle" class="normaltext"><a href="/HallSeven.php">Hall Seven </a></td>
            <td></td>
            <td></td>
          </tr>
          <tr>
            <td height="23" valign="middle" align="right"><a href="/ReferenceList.php"><img height=10 alt=ContactUs Our Guest Book 
                  hspace=4 src="/images/Arrow-content.gif" width=10 
                  vspace=5 border=0 align="bottom"></a></td>
            <td colspan="2" align="left" valign="middle" class="normaltext"><a href="/Ubungo.php">Ubungo Hostel</a> </td>
            <td></td>
            <td></td>
          </tr>
          <tr>
            <td height="23" valign="middle" align="right"><a href="/ReferenceList.php"><img height=10 alt=ContactUs Our Guest Book 
                  hspace=4 src="/images/Arrow-content.gif" width=10 
                  vspace=5 border=0 align="bottom"></a></td>
            <td colspan="2" align="left" valign="middle" class="normaltext"><a href="/Kijitonyama.php">Kijitonyama</a></td>
            <td></td>
            <td></td>
          </tr>
          <tr>
            <td height="23" valign="middle" align="right"><a href="/ReferenceList.php"><img height=10 alt=ContactUs Our Guest Book 
                  hspace=4 src="/images/Arrow-content.gif" width=10 
                  vspace=5 border=0 align="bottom"></a></td>
            <td colspan="2" align="left" valign="middle" class="normaltext"><a href="/Mabibo.php">Mabibo Hostel </a></td>
            <td></td>
            <td></td>
          </tr>
          <tr>
            <td height="23" valign="middle" align="right"><a href="/ContactUs.php"><img height=10 alt=ContactUs Our Guest Book 
                  hspace=4 src="/images/Arrow-content.gif" width=10 
                  vspace=5 border=0 align="bottom"></a></td>
            <td colspan="2" align="left" valign="middle" nowrap class="style17">
              <div align="left" class="normaltext">Hall Managers </div></td>
            <td></td>
            <td></td>
          </tr>
        </table></td>
        <td height="18" valign="top" colspan="6"> 
          <div align="center"><b><font color="006699">SEARCH RESULTS</font></b></div>
        </td>
      </tr>
      <tr> 
        <td width="16" height="18"></td>
        <td valign="top" colspan="4" bgcolor="#FFFFFF" align="left"> 
          <?php

/* Connecting, Selecting database */

include 'conn/conn.php';
$today = date("Y");   

$content = @$_GET['content'];
/* performing sql query*/
$query = "SELECT student.Name, Allocation.RegNo, [Hall/Hostel].HName AS HOSTEL, Allocation.RNumber AS ROOM, Allocation.AYear AS YEAR
FROM student RIGHT JOIN (Allocation INNER JOIN [Hall/Hostel] ON Allocation.HID = [Hall/Hostel].HID) ON student.RegNo = Allocation.RegNo
WHERE student.RegNo LIKE '%$content%' OR Name LIKE '$content' AND AYear LIKE '%$today%' order by Name, AYear";
$result = odbc_Exec($link, $query) or die("Query failed");
		  odbc_result_all($result, "border = 1");
/* closing connection	*/
odbc_close( $link);
?>

        <td width="62"> 
      </tr>
      <tr> 
        <td height="127"></td>
        <td width="145"> 
        <td> 
        <td width="468"> 
        <td width="36"> 
        <td> 
      </tr>
      <tr> 
        <td height="46"></td>
        <td colspan="3" valign="middle" align="left" bgcolor="#FFFF33"> 
          <h3><b><font size="2"></font></b></h3>
        <td></td>
        <td></td>
      </tr>
      <tr> 
        <td height="0"></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
      </tr>
      <tr> 
        <td height="1"><img height="1" width="143" src="/images/spacer.gif"></td>
        <td><img height="1" width="16" src="/images/spacer.gif"></td>
        <td><img height="1" width="145" src="/images/spacer.gif"></td>
        <td><img height="1" width="133" src="/images/spacer.gif"></td>
        <td><img height="1" width="468" src="/images/spacer.gif"></td>
        <td></td>
        <td></td>
      </tr>
    </table>
  </center>
</div>
<div align="center">
  <center>
    <tr> 
      <td width="523" height="41">&nbsp; </td>
    </tr>
  </center>
</div>

</body>

</html>
