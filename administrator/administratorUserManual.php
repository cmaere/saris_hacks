<?php
#get connected to the database and verfy current session
	require_once('../Connections/sessioncontrol.php');
    require_once('../Connections/zalongwa.php');
	
	# initialise globals
	include('administratorMenu.php');
	
	# include the header
	global $szSection, $szSubSection;
	$szSection = 'Help';
	$szSubSection = 'User Manual';
	$szTitle = 'Database Administrator User Guide';
	include('administratorheader.php');
?>
<style type="text/css">
<!--
.style4 {color: #0000FF}
-->
</style>

<h1><a name="_Toc125001736">Module 4: Administrator Module </a></h1>
<p>The Administrator Module allows the database administrator to manage the database in general and to manage the system users in particular.</p>
<p>The most important commands in this module are <span class="style4"><em>Policy Setup, Manage Unser, </em>and<em> Database Maintenance</em></span> which are now explained:</p>
<h2><a name="_Toc125001737">Policy Setup </a></h2>
<p>To set current academic year, Enter the Year and select the Check Box for Current year then Click Update.</p>
<h2><a name="_Toc125001738">Manage Users </a></h2>
<p>In Zalongwa Software, Users create their own accounts. If a lecturer for example, wants to create account, first he must be registered in the database through the use Admission Module. Then he can use his Database Code to create account. </p>
<p>Through the use of Manage User command, a list of all users is generated. To edit a user privilege, Click Edit, to delete a user account Click Delete, to see the frequent of users visit to the system, click the Name of the user.<br>
</p>
<p><strong>Setting User Privileges </strong></p>
<p><strong>Database Administrator:</strong> Position - 'Webmaster', Module - 'Webmaster', Privilege - 'Manager', Department - 'option', Faculty - 'option'</p>
<p><strong>Examination Officer:</strong> Position - 'Lecturer', Module - 'Examination', Privilege - 'Manager', Department - '[Faculty Exam Officer]', Faculty - 'option'</p>
<p><strong>Lecturer/Instructor:</strong> Position - 'Lecturer', Module - 'Examination', Privilege - 'Operator', Department - 'option', Faculty - 'option'</p>
<p><strong>Registrar Officer:</strong> Position - 'Administrator', Module - 'Admission', Privilege - 'Manager', Department - 'option', Faculty - 'option'</p>
<p><strong>Finance Officer:</strong> Position - 'Administrator', Module - 'Accounting', Privilege - 'Manager', Department - 'option', Faculty - 'option'</p>
<p><strong>Student:</strong> Position - 'student', Module - 'student', Privilege - 'student', Department - 'option', Faculty - 'option' (this is deafult setting when one create account)</p>
<p><strong>Blocking User:</strong> Position - 'option', Module - 'Blocked', Privilege - 'Blocked', Department - 'option', Faculty - 'option'</p>
Note: 'option' means you can select anything you want.
<h2><a name="_Toc125001738">Database Maintenance </a></h2>
<p>The system administrator module have a panel called ‘Query Database’ which allows execution of adhoc reports. for example, in this case. The system administrator can just run a query like: 
</p><p>SELECT CourseCode, ExamDate, AYear FROM  examresult WHERE Marker = ‘5’</p><p>
This will simply retrieve a table of coursecode, examdate and academic year of all courses marked by ExamMarker identified by IDNo. 5. we will design user friendly report.
</p>
<p><strong>Import Data: </strong>zalongwa data import routine accepts 'sql' files. The algorithm reads and execute any sql commands listed in a text file with extention '.sql'. Thus, to prepare an import file, for example, importing old exam results, you need to import the data on a mysql tables in local server, that is you import all the data in the examresult table of a local database on your computer. Then you 'dump sql file' to get .sql file. Edit your dump file to remove the 'create table' command. If you suspect that there might be some repeating records and hence will cause an error, use Find --&gt; Replace to replace all INSERT words with REPLACE. After you are satisfied that all the sql commands on the file are the ones you want, use the Import Data Menu to upload the data file to the system. Again if you suspect that the file is too big to be uploaded on internet form, ZIP it first. zalongwa can only unzip .zip files NEITHER Winrar NOR any other format will be accepted. a .zip file will be uploaded and unzipped to extract the sql commands automatically.</p>
<p><strong>Unknown Students:</strong> This routine checks for ghost exam results.if you have imported exam results but the regnos are not matching any student regno in the student table, that record is marked as ghost record and will be detected by this algorithm. It is recommended that everytime  you import examresults by using the Import Data routine, you  check with this routine.</p>
<p><strong>Index Student:</strong> This routine, re-index student records in the student table. It is important that everytime you import student records by using the Import Data routine, you index the student table. </p>
<hr>
<h1><a name="_Toc125001717">Creating Account: </a></h1>
<p>In order to create account you need a <strong>“Database Code” </strong>. All students use their RegNos as their Database Codes and for Lecturers and Administrative Staff, they need to be registered in the database first so that they are assigned a Database Code.</p>
<p>The point is not everyone can register in the Database unless he/she is a university Community member and is already registered in the database.</p>
<p>The convention for assigning database codes to lecturers is as follows: for example lecturers from the Faculty of FASS can have FASS/0001, FASS/0002, etc.</p>
<p>Staff from Admission Office (Registrar) can have ADMN/0001, ADMN/0002, etc.</p>
<p>To create account, click <strong>“Create Account” </strong>, then complete the online registration form.</p>
<h1><a name="_Toc125001718">Login on the System: </a></h1>
<h2><a name="_Toc125001719">Get Help </a></h2>
<p>Click “ <strong>Get Help” </strong>if you experience a problem in Login</p>
<p> To recover forgotten Password, Enter a RegNo and a Birth Date </p>
<p> If this cannot help, use the contacts given under the heading Contact Administrator<strong><br>
</strong></p>