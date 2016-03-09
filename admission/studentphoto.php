<?php 
require_once('../Connections/sessioncontrol.php');
# include the header
	include('admissionMenu.php');
	global $szSection, $szSubSection, $szTitle, $additionalStyleSheet;
	$szSection = 'Nominal Roll';
	$szTitle = 'Upload Student Photo';
	$szSubSection = 'Search Student';
	include('admissionheader.php');
	

$CourseCode = addslashes($_GET['RegNo']);
?>
<style type="text/css">
<!--
.style1 {color: #FFFFFF}
-->
</style>


            <form enctype="multipart/form-data" action="studentphotoupload.php?<?php echo $CourseCode?>" method="post">
              <p>&nbsp;              </p>
                  <p><span class="style1">..</span></p>
                  <table width="200" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <th nowrap scope="row"><div align="right">
      <input type="hidden" name="MAX_FILE_SIZE" value="5646039">
      Choose Photo: </div></th>
                      <td nowrap><input name="userfile" type="file" size="40">
                      <input name="studentregno" type="hidden" id="studentregno" value="<?php echo $CourseCode; ?>"></td>
                    </tr>
                    <tr>
                      <th scope="row">&nbsp;</th>
                      <td nowrap><input type="submit" value="Send File">
                      <span class="style64 style1">.....................</span> <span class="style64 style1">.......</span>
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