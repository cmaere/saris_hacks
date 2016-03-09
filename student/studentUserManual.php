<?php
#get connected to the database and verfy current session
	require_once('../Connections/sessioncontrol.php');
    require_once('../Connections/zalongwa.php');
	
	# initialise globals
	include('studentMenu.php');
	
	# include the header
	global $szSection, $szSubSection;
	$szSection = 'Help';
	$szSubSection = 'User Manual';
	$szTitle = 'Admission Module User Guide';
	include("studentheader.php");
?>
<h1><a name="_Toc125001739">Module 5: Student Module </a></h1>
<p>The student module displays personalised report for the logged in student. </p>
<p><a name="_Toc125001742">Profile </a></p>
<p>Displays your information details. As a normal user, you can only change your address. 
To change your Address (contacts) click 'Profile' then Click 'Edit Your Contacts' </p>
<p><a name="_Toc125001740">Academic Records </a></p>
<p>This Menu enables a student to register for Exams and to view Exam Results </p>
<p><strong> Course Roster:</strong> This display all courses. Use 'Next Page' command to view more records.
 To search a course type in the 'coursecode'.<br>Click 'pick' to register the exam of that course. 
You must specify the 'year' and 'Semester' while registering a course. </p>
<p><strong> Exam Registered:</strong> This display all the courses you have registered. Use 'Next Page' command to view more records.
Click 'Drop' to unregister in a course (this will not work if the course has exam results!).  </p>
<p><strong> Exam Results:</strong> This display all your Exam Results. </p>
<p><a name="_Toc125001741">Financial Records </a></p>
<p>Display Financial Reports of the candidate </p>
<p><a name="_Toc125001742">E-Learning </a></p>
<p>Displays course registered for the candidate and their underlying lecture notes. </p>
<p><a name="_Toc125001743">Communication </a></p>
<p>This allows a student to send suggestion using a suggestion box and it allows student to read posted news </p>
<h2><a name="_Toc125001728">Sign Out Menu </a></h2>
<p>This ends the current user's session <p><table width="100%"  border="0" cellspacing="2" cellpadding="0">
                        <tr>
                          <th nowrap scope="row"><div align="center">ZALONGWA Software is Supported by </div></th>
                        </tr>
						<tr>
                          <th nowrap scope="row"><div align="center">Juma Hemed Lungo </div></th>
                        </tr>
                        <tr>
                          <th scope="row"><div align="center">P. O. Box 70893, </div></th>
                        </tr>
                        <tr>
                          <th scope="row"><div align="center">Dar es Salaam,</div></th>
                        </tr>
						<tr>
                          <th nowrap scope="row"></th>
                        </tr>
                        <tr>
                          <th nowrap scope="row">Tanzania</th>
                        </tr>
						<tr>
                          <th nowrap scope="row">http://www.zalongwa.com</th>
                        </tr>
						<tr>
                          <th nowrap scope="row">e-post: zalongwa@udsm.ac.tz </th>
                        </tr>
						<tr>
                          <th nowrap scope="row"><span class="style15">The First GPL Licensed Software in Tanzania </span></th>
                        </tr>
                      </table></p>