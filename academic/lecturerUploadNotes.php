<?php 
require_once('../Connections/sessioncontrol.php');
# include the header
include('lecturerMenu.php');
	global $szSection, $szSubSection, $szTitle, $additionalStyleSheet;
	$szSection = 'E-Learning';
	$szTitle = 'Publish Lecturer Notes and Assignments';
	$szSubSection = '';
	include("lecturerheader.php");
	

$CourseCode = $_GET['CourseCode'];
?>
<style type="text/css">
<!--
.style1 {color: #FFFFFF}
-->
</style>


            <form enctype="multipart/form-data" action="UploadLectureNotes.php?<?php echo $CourseCode?>" method="post">
              <p>&nbsp;              </p>
                  <p><span class="style1">..</span></p>
                  <table width="200" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <th nowrap scope="row"><div align="right">
      <input type="hidden" name="MAX_FILE_SIZE" value="5646039">
  Send this file: </div></th>
                      <td nowrap><input name="userfile" type="file" size="40">
                      <input name="CourseCode" type="hidden" id="CourseCode" value="<?php echo $CourseCode; ?>"></td>
                    </tr>
                    <tr>
                      <th nowrap scope="row"><div align="right">Title of the file:</div></th>
                      <td><input type="text" name="url" size="50"></td>
                    </tr>
                    <tr>
                      <th scope="row">&nbsp;</th>
                      <td><input type="submit" value="Send File">
                      <span class="style64 style1">.........................</span> <span class="style64 style1">.......</span>
                      <input type="reset" name="Reset" value="Reset"></td>
                    </tr>
                  </table>
                  <p>&nbsp;              </p>
                  <p>&nbsp;              </p>
			</form>
			<?php
				# include the footer
	include('../footer/footer.php');
	?>