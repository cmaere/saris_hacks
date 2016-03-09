<?php
#get connected to the database and verfy current session
	require_once('../Connections/sessioncontrol.php');
    require_once('../Connections/zalongwa.php');
	
	# initialise globals
	include('lecturerMenu.php');
	
	# include the header
	global $szSection, $szSubSection;
	$szSection = 'Help';
	$szSubSection = 'User Manual';
	$szTitle = 'Academic Module User Guide';
	include('lecturerheader.php');
?>
<h1></h1><a name="_Toc125001720">Module 1: Examination Module </a></h1>
<p>NOTE: While this guide explain all menus, you may not see some menus because of your 'Access Privilege Level' (your rights given to the system). </p>
<p>The Examination Module has eight (8) Menus namely Profile, Policy Setup, Administration, Examination, E-Learning, Communication, Security, and Sign Out. </p>
<p>Each Menu consists of several Sub-Menus. The sub-menus are listed below the main menu when Menu becomes active (selected). </p>
<h2><a name="_Toc125001721">Profile Menu </a></h2>
<p>Profile Menu display information of the user who logged in. </p>
<h2><a name="_Toc125001722">Policy Setup Menu </a></h2>
<p>This Menu has seven (6) sub-menus which are now explained: </p>
<p>&#149;&nbsp; Institution: Allows listing and Adding a Institution </p>
<p>&#149;&nbsp; Faculty: allows listing and Adding a Faculty </p>
<p>&#149;&nbsp; Department: allows listing and adding a Department </p>
<p>&#149;&nbsp; Programme: allows listing and adding a degree programme </p>
<p>&#149;&nbsp; Subject: allows listing and adding a subject, see Figure 6 </p>
<p>&#149;&nbsp; Combination: allows listing and adding course combination </p>
<p><strong>Note: </strong> While adding or editing a subject, the Capacity Values tells ZALONGWA how many students can register in that particular course. Usually Exam Officers will need to edit this value in each academic year.</p>
<h2><a name="_Toc125001723">Administration Menu </a></h2>
<p>This Menu has four (4) sub-menus explained as follows: </p>
<p>&#149;&nbsp; Course Allocation: allows Exam Officer to allocate each a lecturer a course. As a result each lecturer will see class roster of all students who have registered the course. </p>
<p>&#149;&nbsp; Change Semester: while registering, some students can register a course for semester one and others may register the same course for semester two. This sub-menu allows the exam officer to combine the two student groups into one. </p>
<p>&#149;&nbsp; Publish Exam: allows Exam Officer to “ <strong>publish” </strong> and “ <strong>un-publish” </strong> exam results. If Publish action is selected, students will see the exam results, and the respective course lecturer will not be able to change the exam results. If Un-Publish action is selected, students will not see their results, and the respective lecturer will be able to update the exam results. </p>
<p>&#149;&nbsp; Exam Marker: allows adding new Exam Marker.</p>
<h2><a name="_Toc125001724">Examination Menu </a></h2>
<p>This menu three sub-menus name Search, Course Result, Annual Report, Cumulative Points, Gradebook, and Transcript. </p>
<p>&#149;&nbsp; Search: allows listing of all exam result of a particular student. If a serial number (S/N) of a course result is clicked, a result update form will popup. Note: each Faculty Exam Officer can only edit exam results of corresponding courses from that Faculty. </p>
<p>&#149;&nbsp; Grade Book: prints data entry form for capturing or editing exam results </p>
<p>&#149;&nbsp; Transcript: displays a ready only summary of annual exam result of a student. </p>
<p>&#149;&nbsp; Course Result: prints course based exam results </p>
<p>&#149;&nbsp; Annual Report: prints course codes, grades, total units, total points and gpa of students in a particular cohort </p>
<p>&#149;&nbsp; Cumulative Points: prints total units, total points and gpa of students in a particular cohort</p>
<p>Note: when searching a student, you must enter his/her full RegNo</p>
<h2><a name="_Toc125001725">E-Learning Menu </a></h2>
<p>This menu has only one sub-menu namely Lecture Notes. </p>
<p>&#149;&nbsp; Lecture Notes allows lecturers to upload lecturer notes. </p>
<h2><a name="_Toc125001726">Communication Menu </a></h2>
<p>This menu has two sub-menus: Check Message, and News &amp; Events </p>
<p>&#149;&nbsp; Check Message: allows listing and replying of student messages </p>
<p>&#149;&nbsp; News &amp; Events: allows listing and publishing News and Events </p>
<h2><a name="_Toc125001727">Security Menu </a></h2>
<p>This menu has two sub-menu: Change Password and Login History </p>
<p>&#149;&nbsp; Change Password: allows the current user to change a password </p>
<p>&#149;&nbsp; Login History: lists the last 10 login history. This allows a user to detect if someone has used his password </p>
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