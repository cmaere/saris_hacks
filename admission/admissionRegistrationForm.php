<?php
#get connected to the database and verfy current session
require_once('../Connections/sessioncontrol.php');
require_once('../Connections/zalongwa.php');
	# initialise globals
	include('admissionMenu.php');
	# include the header
	global $szSection, $szSubSection;
	$szSection = 'Admission Process';
	$szSubSection = 'Registration Form';
	$szTitle = 'Student Registration Form';
	include('admissionheader.php');
?>

<script type="text/javascript">
var A_TCALDEF = {
	'months' : ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
	'weekdays' : ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'],
	'yearscroll': true, // show year scroller
	'weekstart': 0, // first day of week: 0-Su or 1-Mo
	'centyear'  : 70, // 2 digit years less than 'centyear' are in 20xx, othewise in 19xx.
	'imgpath' : 'img/' // directory with calendar images
}
// date parsing function
function f_tcalParseDate (s_date) {

	var re_date = /^\s*(\d{2,4})\-(\d{1,2})\-(\d{1,2})\s*$/;
	if (!re_date.exec(s_date))
		return alert ("Invalid date: '" + s_date + "'.\nAccepted format is yyyy-mm-dd.")
	var n_day = Number(RegExp.$3),
		n_month = Number(RegExp.$2),
		n_year = Number(RegExp.$1);
	
	if (n_year < 100)
		n_year += (n_year < this.a_tpl.centyear ? 2000 : 1900);
	if (n_month < 1 || n_month > 12)
		return alert ("Invalid month value: '" + n_month + "'.\nAllowed range is 01-12.");
	var d_numdays = new Date(n_year, n_month, 0);
	if (n_day > d_numdays.getDate())
		return alert("Invalid day of month value: '" + n_day + "'.\nAllowed range for selected month is 01 - " + d_numdays.getDate() + ".");

	return new Date (n_year, n_month - 1, n_day);
}
// date generating function
function f_tcalGenerDate (d_date) {
	return (
		d_date.getFullYear() + "-"
		+ (d_date.getMonth() < 9 ? '0' : '') + (d_date.getMonth() + 1) + "-"
		+ (d_date.getDate() < 10 ? '0' : '') + d_date.getDate()
	);
}

// implementation
function tcal (a_cfg, a_tpl) {

	// apply default template if not specified
	if (!a_tpl)
		a_tpl = A_TCALDEF;

	// register in global collections
	if (!window.A_TCALS)
		window.A_TCALS = [];
	if (!window.A_TCALSIDX)
		window.A_TCALSIDX = [];
	
	this.s_id = a_cfg.id ? a_cfg.id : A_TCALS.length;
	window.A_TCALS[this.s_id] = this;
	window.A_TCALSIDX[window.A_TCALSIDX.length] = this;
	
	// assign methods
	this.f_show = f_tcalShow;
	this.f_hide = f_tcalHide;
	this.f_toggle = f_tcalToggle;
	this.f_update = f_tcalUpdate;
	this.f_relDate = f_tcalRelDate;
	this.f_parseDate = f_tcalParseDate;
	this.f_generDate = f_tcalGenerDate;
	
	// create calendar icon
	this.s_iconId = 'tcalico_' + this.s_id;
	this.e_icon = f_getElement(this.s_iconId);
	if (!this.e_icon) {
		document.write('<img src="' + a_tpl.imgpath + 'cal.gif" id="' + this.s_iconId + '" onclick="A_TCALS[\'' + this.s_id + '\'].f_toggle()" class="tcalIcon" alt="Open Calendar" />');
		this.e_icon = f_getElement(this.s_iconId);
	}
	// save received parameters
	this.a_cfg = a_cfg;
	this.a_tpl = a_tpl;
}

function f_tcalShow (d_date) {

	// find input field
	if (!this.a_cfg.controlname)
		throw("TC: control name is not specified");
	if (this.a_cfg.formname) {
		var e_form = document.forms[this.a_cfg.formname];
		if (!e_form)
			throw("TC: form '" + this.a_cfg.formname + "' can not be found");
		this.e_input = e_form.elements[this.a_cfg.controlname];
	}
	else
		this.e_input = f_getElement(this.a_cfg.controlname);

	if (!this.e_input || !this.e_input.tagName || this.e_input.tagName != 'INPUT')
		throw("TC: element '" + this.a_cfg.controlname + "' does not exist in "
			+ (this.a_cfg.formname ? "form '" + this.a_cfg.controlname + "'" : 'this document'));

	// dynamically create HTML elements if needed
	this.e_div = f_getElement('tcal');
	if (!this.e_div) {
		this.e_div = document.createElement("DIV");
		this.e_div.id = 'tcal';
		document.body.appendChild(this.e_div);
	}
	this.e_shade = f_getElement('tcalShade');
	if (!this.e_shade) {
		this.e_shade = document.createElement("DIV");
		this.e_shade.id = 'tcalShade';
		document.body.appendChild(this.e_shade);
	}
	this.e_iframe =  f_getElement('tcalIF')
	if (b_ieFix && !this.e_iframe) {
		this.e_iframe = document.createElement("IFRAME");
		this.e_iframe.style.filter = 'alpha(opacity=0)';
		this.e_iframe.id = 'tcalIF';
		this.e_iframe.src = this.a_tpl.imgpath + 'pixel.gif';
		document.body.appendChild(this.e_iframe);
	}
	
	// hide all calendars
	f_tcalHideAll();

	// generate HTML and show calendar
	this.e_icon = f_getElement(this.s_iconId);
	if (!this.f_update())
		return;

	this.e_div.style.visibility = 'visible';
	this.e_shade.style.visibility = 'visible';
	if (this.e_iframe)
		this.e_iframe.style.visibility = 'visible';

	// change icon and status
	this.e_icon.src = this.a_tpl.imgpath + 'no_cal.gif';
	this.e_icon.title = 'Close Calendar';
	this.b_visible = true;
}

function f_tcalHide (n_date) {
	if (n_date)
		this.e_input.value = this.f_generDate(new Date(n_date));

	// no action if not visible
	if (!this.b_visible)
		return;

	// hide elements
	if (this.e_iframe)
		this.e_iframe.style.visibility = 'hidden';
	if (this.e_shade)
		this.e_shade.style.visibility = 'hidden';
	this.e_div.style.visibility = 'hidden';
	
	// change icon and status
	this.e_icon = f_getElement(this.s_iconId);
	this.e_icon.src = this.a_tpl.imgpath + 'cal.gif';
	this.e_icon.title = 'Open Calendar';
	this.b_visible = false;
}

function f_tcalToggle () {
	return this.b_visible ? this.f_hide() : this.f_show();
}

function f_tcalUpdate (d_date) {
	
	var d_client = new Date();
	d_client.setHours(0);
	d_client.setMinutes(0);
	d_client.setSeconds(0);
	d_client.setMilliseconds(0);
	
	var d_today = this.a_cfg.today ? this.f_parseDate(this.a_cfg.today) : d_client;
	var d_selected = this.e_input.value == ''
		? (this.a_cfg.selected ? this.f_parseDate(this.a_cfg.selected) : d_today)
		: this.f_parseDate(this.e_input.value);

	// figure out date to display
	if (!d_date)
		// selected by default
		d_date = d_selected;
	else if (typeof(d_date) == 'number')
		// get from number
		d_date = new Date(d_date);
	else if (typeof(d_date) == 'string')
		// parse from string
		this.f_parseDate(d_date);
		
	if (!d_date) return false;

	// first date to display
	var d_firstday = new Date(d_date);
	d_firstday.setDate(1);
	d_firstday.setDate(1 - (7 + d_firstday.getDay() - this.a_tpl.weekstart) % 7);
	
	var a_class, s_html = '<table class="ctrl"><tbody><tr>'
		+ (this.a_tpl.yearscroll ? '<td' + this.f_relDate(d_date, -1, 'y') + ' title="Previous Year"><img src="' + this.a_tpl.imgpath + 'prev_year.gif" /></td>' : '')
		+ '<td' + this.f_relDate(d_date, -1) + ' title="Previous Month"><img src="' + this.a_tpl.imgpath + 'prev_mon.gif" /></td><th>'
		+ this.a_tpl.months[d_date.getMonth()] + ' ' + d_date.getFullYear()
			+ '</th><td' + this.f_relDate(d_date, 1) + ' title="Next Month"><img src="' + this.a_tpl.imgpath + 'next_mon.gif" /></td>'
		+ (this.a_tpl.yearscroll ? '<td' + this.f_relDate(d_date, 1, 'y') + ' title="Next Year"><img src="' + this.a_tpl.imgpath + 'next_year.gif" /></td></td>' : '')
		+ '</tr></tbody></table><table><tbody><tr class="wd">';

	// print weekdays titles
	for (var i = 0; i < 7; i++)
		s_html += '<th>' + this.a_tpl.weekdays[(this.a_tpl.weekstart + i) % 7] + '</th>';
	s_html += '</tr>' ;

	// print calendar table
	var d_current = new Date(d_firstday);
	while (d_current.getMonth() == d_date.getMonth() ||
		d_current.getMonth() == d_firstday.getMonth()) {
	
		// print row heder
		s_html +='<tr>';
		for (var n_wday = 0; n_wday < 7; n_wday++) {

			a_class = [];
			// other month
			if (d_current.getMonth() != d_date.getMonth())
				a_class[a_class.length] = 'othermonth';
			// weekend
			if (d_current.getDay() == 0 || d_current.getDay() == 6)
				a_class[a_class.length] = 'weekend';
			// today
			if (d_current.valueOf() == d_today.valueOf())
				a_class[a_class.length] = 'today';
			// selected
			if (d_current.valueOf() == d_selected.valueOf())
				a_class[a_class.length] = 'selected';

			s_html += '<td onclick="A_TCALS[\'' + this.s_id + '\'].f_hide(' + d_current.valueOf() + ')"' + (a_class.length ? ' class="' + a_class.join(' ') + '">' : '>') + d_current.getDate() + '</td>'
			d_current.setDate(d_current.getDate() + 1);
		}
		// print row footer
		s_html +='</tr>';
	}
	s_html +='</tbody></table>';
	
	// update HTML, positions and sizes
	this.e_div.innerHTML = s_html;

	var n_width  = this.e_div.offsetWidth;
	var n_height = this.e_div.offsetHeight;
	var n_top  = f_getPosition (this.e_icon, 'Top') + this.e_icon.offsetHeight;
	var n_left = f_getPosition (this.e_icon, 'Left') - n_width + this.e_icon.offsetWidth;
	if (n_left < 0) n_left = 0;
	
	this.e_div.style.left = n_left + 'px';
	this.e_div.style.top  = n_top + 'px';

	this.e_shade.style.width = (n_width + 8) + 'px';
	this.e_shade.style.left = (n_left - 1) + 'px';
	this.e_shade.style.top = (n_top - 1) + 'px';
	this.e_shade.innerHTML = b_ieFix
		? '<table><tbody><tr><td rowspan="2" colspan="2" width="6"><img src="' + this.a_tpl.imgpath + 'pixel.gif"></td><td width="7" height="7" style="filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(src=\'' + this.a_tpl.imgpath + 'shade_tr.png\', sizingMethod=\'scale\');"><img src="' + this.a_tpl.imgpath + 'pixel.gif"></td></tr><tr><td height="' + (n_height - 7) + '" style="filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(src=\'' + this.a_tpl.imgpath + 'shade_mr.png\', sizingMethod=\'scale\');"><img src="' + this.a_tpl.imgpath + 'pixel.gif"></td></tr><tr><td width="7" style="filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(src=\'' + this.a_tpl.imgpath + 'shade_bl.png\', sizingMethod=\'scale\');"><img src="' + this.a_tpl.imgpath + 'pixel.gif"></td><td style="filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(src=\'' + this.a_tpl.imgpath + 'shade_bm.png\', sizingMethod=\'scale\');" height="7" align="left"><img src="' + this.a_tpl.imgpath + 'pixel.gif"></td><td style="filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(src=\'' + this.a_tpl.imgpath + 'shade_br.png\', sizingMethod=\'scale\');"><img src="' + this.a_tpl.imgpath + 'pixel.gif"></td></tr><tbody></table>'
		: '<table><tbody><tr><td rowspan="2" width="6"><img src="' + this.a_tpl.imgpath + 'pixel.gif"></td><td rowspan="2"><img src="' + this.a_tpl.imgpath + 'pixel.gif"></td><td width="7" height="7"><img src="' + this.a_tpl.imgpath + 'shade_tr.png"></td></tr><tr><td background="' + this.a_tpl.imgpath + 'shade_mr.png" height="' + (n_height - 7) + '"><img src="' + this.a_tpl.imgpath + 'pixel.gif"></td></tr><tr><td><img src="' + this.a_tpl.imgpath + 'shade_bl.png"></td><td background="' + this.a_tpl.imgpath + 'shade_bm.png" height="7" align="left"><img src="' + this.a_tpl.imgpath + 'pixel.gif"></td><td><img src="' + this.a_tpl.imgpath + 'shade_br.png"></td></tr><tbody></table>';
	
	if (this.e_iframe) {
		this.e_iframe.style.left = n_left + 'px';
		this.e_iframe.style.top  = n_top + 'px';
		this.e_iframe.style.width = (n_width + 6) + 'px';
		this.e_iframe.style.height = (n_height + 6) +'px';
	}
	return true;
}

function f_getPosition (e_elemRef, s_coord) {
	var n_pos = 0, n_offset,
		e_elem = e_elemRef;

	while (e_elem) {
		n_offset = e_elem["offset" + s_coord];
		n_pos += n_offset;
		e_elem = e_elem.offsetParent;
	}
	// margin correction in some browsers
	if (b_ieMac)
		n_pos += parseInt(document.body[s_coord.toLowerCase() + 'Margin']);
	else if (b_safari)
		n_pos -= n_offset;
	
	e_elem = e_elemRef;
	while (e_elem != document.body) {
		n_offset = e_elem["scroll" + s_coord];
		if (n_offset && e_elem.style.overflow == 'scroll')
			n_pos -= n_offset;
		e_elem = e_elem.parentNode;
	}
	return n_pos;
}

function f_tcalRelDate (d_date, d_diff, s_units) {
	var s_units = (s_units == 'y' ? 'FullYear' : 'Month');
	var d_result = new Date(d_date);
	d_result['set' + s_units](d_date['get' + s_units]() + d_diff);
	if (d_result.getDate() != d_date.getDate())
		d_result.setDate(0);
	return ' onclick="A_TCALS[\'' + this.s_id + '\'].f_update(' + d_result.valueOf() + ')"';
}

function f_tcalHideAll () {
	for (var i = 0; i < window.A_TCALSIDX.length; i++)
		window.A_TCALSIDX[i].f_hide();
}	

f_getElement = document.all ?
	function (s_id) { return document.all[s_id] } :
	function (s_id) { return document.getElementById(s_id) };

if (document.addEventListener)
	window.addEventListener('scroll', f_tcalHideAll, false);
if (window.attachEvent)
	window.attachEvent('onscroll', f_tcalHideAll);
	
// global variables
var s_userAgent = navigator.userAgent.toLowerCase(),
	re_webkit = /WebKit\/(\d+)/i;
var b_mac = s_userAgent.indexOf('mac') != -1,
	b_ie5 = s_userAgent.indexOf('msie 5') != -1,
	b_ie6 = s_userAgent.indexOf('msie 6') != -1 && s_userAgent.indexOf('opera') == -1;
var b_ieFix = b_ie5 || b_ie6,
	b_ieMac  = b_mac && b_ie5,
	b_safari = b_mac && re_webkit.exec(s_userAgent) && Number(RegExp.$1) < 500;

 function setField(what) {
 if (what.confirmed.checked)
 what.regno.value = what.hiddenregno.value;
 else
 what.regno.value = '';

 }


 </script>
<style>

.formfield
{
text-align:right;
text-decoration:norwap;
}
.error
{
font-size:8pt;
}

.style2 {color: #FF0000}
* calendar icon */
img.tcalIcon {
	cursor: pointer;
	margin-left: 1px;
	vertical-align: middle;
}
/* calendar container element */
div#tcal {
	position: absolute;
	visibility: hidden;
	z-index: 100;
	width: 158px;
	padding: 2px 0 0 0;
}
/* all tables in calendar */
div#tcal table {
	width: 100%;
	border: 1px solid silver;
	border-collapse: collapse;
	background-color: white;
}
/* navigation table */
div#tcal table.ctrl {
	border-bottom: 0;
}
/* navigation buttons */
div#tcal table.ctrl td {
	width: 15px;
	height: 20px;
}
/* month year header */
div#tcal table.ctrl th {
	background-color: white;
	color: black;
	border: 0;
}
/* week days header */
div#tcal th 
{
	border: 1px solid silver;
	border-collapse: collapse;
	text-align: center;
	padding: 3px 0;
	font-family: tahoma, verdana, arial;
	font-size: 10px;
	background-color: #D7D7FF;
	color: #054d87;
}

div#tcal th:hover 
{
background-color: silver;
color: white;
}
/* date cells */
div#tcal td 
{
	border:1px solid #D7D7FF;
	border-collapse: collapse;
	text-align: center;
	padding: 2px 0;
	font-family: tahoma, verdana, arial;
	font-size: 11px;
	width: 22px;
	cursor: pointer;
}
 div#tcal td:hover
 {
background-color: #D7D7FF;
color: #054d87;
 }

/* date highlight
   in case of conflicting settings order here determines the priority from least to most important */
div#tcal td.othermonth {
	color: silver;
}
div#tcal td.weekend {
	background-color: #d2d2d2;
}
div#tcal td.today {
	border: 1px solid #fcfcfcfc;
	background-color: lightblue;
}
div#tcal td.selected {
	background-color: #FFB3BE;
}
/* iframe element used to suppress windowed controls in IE5/6 */
iframe#tcalIF {
	position: absolute;
	visibility: hidden;
	z-index: 98;
	border: 0;
}
/* transparent shadow */
div#tcalShade {
	position: absolute;
	visibility: hidden;
	z-index: 99;
}
div#tcalShade table {
	border: 0;
	border-collapse: collapse;
	width: 100%;
}
div#tcalShade table td {
	border: 0;
	border-collapse: collapse;
	padding: 0;
}
table.ztable{
border:0px solid green;
border-left:0px dotted #CCC;
border-top:0px dotted #CCC;
border-bottom:0px dotted #CCC;
}
.ztable{
border-bottom:1px dotted #CCC;
border-right:0px dotted #CCC;
background-color: #F7F7F7; 
}
.formfield{
border-bottom:1px dotted #CCC;
}

.hseparator {
border:1px solid #CCC;
 width:98%;
 background-color:#CCC;
 font-weight:bold;
 }
form { 
margin:0px; 
padding:0px;

} 
a{
text-decoration:none;
}
img { border:0px;}

/* form elements */
	
label {
	display:block;
	font-weight:bold;
	margin:50px 0;
	}
	
input {
	border:1px solid #ccc;
	margin-bottom: 2px;
	font-family: Verdana,Helvetica,Sans-Serif;
	font-size: 12px;
	color:#777;
	background-color:#fff;
	}
	
textarea {
	width:300px;
	padding:2px;
	font-family: Verdana,Helvetica,Sans-Serif;
	height:70px;
	display:block;
	color:#777;
	border: 1px solid #ccc;
	}
	
select { 
	border:1px solid #ccc;
	margin-bottom: 2px;
	} 

option { 
	/*width: 200px;*/
	border:0px solid #ccc;   
	}

fieldset {
	padding: 1em;
	border:1px solid #CCC;
	background-color: #F7F7F7; 
	}
  
legend {
	margin-bottom: 8px;
	padding: 5px 5px 5px 5px;
	border:1px solid #CCC;
	background-color: #F7F7F4; 
	}


</style>


<?php
if (isset($_GET['id'])) 
{
#get post variables
$id = addslashes($_GET['id']);
$reg = addslashes($_GET['RegNo']);
$edit=$_GET['edit'];
if($edit==yes)
{
$state1="submit";
$state2="hidden";
$state3="readonly";
$state4="";
$label_edit="";
}
else
{
$state1="hidden";
$state2="hidden";
$state3="readonly";
$state4="disabled";
$label_edit="<a href='$_SERVER[PHP_SELF]?id=$id&RegNo=$reg&edit=yes'><img src='./img/edit.png' alt='Click to Edit This Record'>Edit</a>";
}
$sql = "SELECT * FROM student WHERE Id ='$id' and RegNo='$reg'"; 
$update = mysql_query($sql) or die(mysql_error());
$update_row = mysql_fetch_array($update)or die(mysql_error());
$totalRows_update = mysql_num_rows($update)or die(mysql_error());
	$regno = $update_row['RegNo'];
	$stdid = $update_row['Id'];
	$AdmissionNo = $update_row['AdmissionNo'];     
	$degree = $update_row['ProgrammeofStudy'];
	$faculty = $update_row['Faculty'];
	$ayear = $update_row['EntryYear'];
	$combi = $update_row['Subject'];
	$campus = $update_row['Campus'];
	$manner = $update_row['MannerofEntry'];
	$rawname = $update_row['Name'];
		$expsurname = explode(",",$rawname);
		$surname = strtoupper($expsurname[0]);
		$othername = $expsurname[1];
		$expothername = explode(" ", $othername);
		$firstname = $expothername[1];
		$middlename = $expothername[2].' '.$expothername[3];
	$dtDOB = $update_row['DBirth'];
	$age = $update_row['age'];
	$sex = $update_row['Sex'];
	$sponsor = $update_row['Sponsor'];
	$country = $update_row['Nationality'];
	$district =$update_row['District'];
	$region =$update_row['Region'];
	$maritalstatus = $update_row['MaritalStatus'];
	$address = $update_row['Address'];
	$religion = $update_row['Religion'];
	$denomination = $update_row['Denomination'];
	$postaladdress =$update_row['postaladdress'];
	$residenceaddress = addslashes($update_row['residenceaddress']);
	$disabilityCategory = $update_row['disabilityCategory'];
	$status = $update_row['Status'];
	$gyear = $update_row['GradYear'];
	$phone1 = $update_row['Phone'];
	$email1 = $update_row['Email'];
	$formsix = $update_row['formsix'];
	$formfour = $update_row['formfour'];
	$diploma = $update_row['diploma'];
	$School_attended_olevel = $update_row['School_attended_olevel'];
	$School_attended_alevel = $update_row['School_attended_alevel'];
	$name = $surname.", ".$firstname." ".$middlename;
//Added fields
$village=$update_row['village'];
$account_number=$update_row['account_number'];
$bank_branch_name=$update_row['bank_branch_name'];
$bank_name=$update_row['bank_name'];
$form4no=$update_row['form4no'];
$form4name=$update_row['form4name'];
$form6name=$update_row['form6name'];
$form6no=$update_row['form6no'];
$form7name=$update_row['form7name'];
$form7no=$update_row['form7no'];
$paddress=$update_row['paddress'];
$currentaddaress=$update_row['currentaddaress'];
$f4year=$update_row['f4year'];
$f6year=$update_row['f6year'];
$f7year=$update_row['f7year'];
#next of kin info
$kin_email=$update_row['kin_email'];
$kin=$update_row['kin'];
$kin_phone=$update_row['kin_phone'];
$kin_address=$update_row['kin_address'];
$kin_job=$update_row['kin_job'];
$studylevel=$update_row['studylevel'];
$kin_relationship=$update_row['kin_relationship'];
//***********             
}
else
{
$state1="hidden";
$state2="submit";
$state3="";
$state4="";
}


if (isset($_POST['save']))
{       $disabilityCategory=$_POST['disabilityCategory'];
        $kin=addslashes($_POST['kin']);
        $kin_phone=addslashes($_POST['kin_phone']);
        $kin_address=addslashes($_POST['kin_address']);
        $kin_job=addslashes($_POST['kin_job']);
        $kin_email = $_POST['kin_email'];
	    $regno = addslashes($_POST['regno']);
		$stdid = addslashes($_POST['stdid']);
	    $AdmissionNo = addslashes($_POST['AdmissionNo']);   
		$degree = addslashes($_POST['degree']);
		$faculty = addslashes($_POST['faculty']);
		$ayear = addslashes($_POST['ayear']);
		$combi = addslashes($_POST['combi']);
		$campus = addslashes($_POST['campus']);
		$manner = addslashes($_POST['manner']);
		$byear = addslashes($_POST['txtYear']);
		$bmon = addslashes($_POST['txtMonth']);
		$bday = addslashes($_POST['txtDay']);
		$dtDOB = $bday . " - " . $bmon . " - " . $byear;
		$surname = strtoupper(addslashes($_POST['surname']));
		$firstname = addslashes($_POST['firstname']);
		$middlename = addslashes($_POST['middlename']);
		$dtDOB = $_POST['dtDOB'];
		$age = $_POST['age'];
		$sex = $_POST['sex'];
		$sponsor = $_POST['txtSponsor'];
		$country = $_POST['country'];
		$district = addslashes($_POST['district']);
		$region = addslashes($_POST['region']);
		$maritalstatus = $_POST['maritalstatus'];
		$address = strtoupper($_POST['address']);
		$religion = $_POST['religion'];
		$denomination = $_POST['denomination'];
		$postaladdress = strtoupper(addslashes($_POST['postaladdress']));
		$residenceaddress = strtoupper(addslashes($_POST['residenceaddress']));
		$disability = $_POST['disability'];
		$status = $_POST['status'];
		$gyear = $_POST['dtDate'];
		$phone1 = $_POST['phone'];
		$email1 = $_POST['email'];
		$formsix = $_POST['formsix'];
		$formfour = $_POST['formfour'];
		$diploma = $_POST['diploma'];
		$studylevel= $_POST['studylevel'];
		$f4year= $_POST['f4year'];
		$f6year= $_POST['f6year'];
		$f7year= $_POST['f7year'];
		$denomination= $_POST['denomination'];
		$name = $surname.", ".$firstname." ".$middlename;


//Added fields
$village=$_POST['village'];
$account_number=$_POST['account_number'];
$bank_branch_name=$_POST['bank_branch_name'];
$bank_name=$_POST['bank_name'];
$form4no=$_POST['form4no'];
$form4name=$_POST['form4name'];
$form6name=$_POST['form6name'];
$form6no=$_POST['form6no'];
$form7name=$_POST['form7name'];
$form7no=$_POST['form7no'];
$paddress=$_POST['paddress'];
$currentaddaress=$_POST['currentaddaress'];
$kin_relationship=$_POST['kin_relationship'];
//*************
//FORMATING ERRORS
if(!$formsix||!$formfour||!$diploma||!$phone1||!$email1||!$regno||!$degree||!$faculty||!$ayear||!$combi||!$campus||!$manner||!$byear||!$bmon||!$bday||!$dtDOB||!$surname||!$firstname||!$dtDOB|| !$age||!$sex||!$sponsor||!$country||!$maritalstatus||!$address||!$religion||!$denomination||!$postaladdress||!$residenceaddress||!$status||!$gyear||!$name||!$village)
{ 
if(!$regno)
{
$regno_error="<font color='red'>*Registration Number Must be Filled</font>";
}
if(!$phone1)
{
//$phone1_error="<font color='red'>*Phone Number Must be Filled</font>";
}
if(!$studylevel)
{
//$studylevel_error="<font color='red'>*Study Level Must be Filled</font>";
}
if(!$email1)
{
//$email1_error="<font color='red'>*Email Must be Filled</font>";
}
if(!$formsix)
{
//$formsix_error="<font color='red'>*Form Six Necta Number Must be Filled</font>";
}
if(!$formfour)
{
$formfour_error="<font color='red'>*Form Four Necta Number Must be Filled</font>";
}
	 
if(!$diploma)
{
//$diploma_error="<font color='red'>*Diploma Necta Number Must be Filled</font>";
}
if(!$degree)
{
$degree="<font color='red'>*Degree Must be Filled</font>";
}
 	 
	 
if(!$faculty)
{
$faculty_error="<font color='red'>*Faculty  Must be Filled</font>";
}
	  
if(!$ayear)
{
$ayear_error="<font color='red'>*Date Must be Filled</font>";
}
	 
if(!$combi)
{
$combination_error="<font color='red'>*Combination  Must be Filled</font>";
} 	 
if(!$campus)
{
$campus_error="<font color='red'>*Campus Name Must be Filled</font>";
}	 
if(!$manner)
{
$manner_error="<font color='red'>*Manner Must be Filled</font>";
}
	 
	 
if(!$byear)
{
$byear_error="<font color='red'>*Birth Date Must be Filled</font>";
}
if(!$bmon)
{
$bmon_error="<font color='red'>*Month Must be Filled</font>";
}
if(!$sex)
{
$sex_error="<font color='red'>*Gender Must be Filled</font>";
}
	 
if(!$bday)
{
$bday_error="<font color='red'>*Birth day Must be Filled</font>";
}	
if(!$dtDOB)
{
$dtDOB_error="<font color='red'>*Date of birth Must be Filled</font>";
}
	 
if(!$surname)
{
$surname_error="<font color='red'>*Surname Must be Filled</font>";
}	 
	
if(!$firstname)
{
$firstname_error="<font color='red'>*First Name Must be Filled</font>";
}
/* 
if(!$middlename)
{
$middlename_error="<font color='red'>*Middle Name Must be Filled</font>";
}
*/
if(!$dtDOB)
{
$dtDOB_error="<font color='red'>*Date of Birth Must be Filled</font>";
}
if(!$age)
{
$age_error="<font color='red'>*Age Must be Filled</font>";
}
	 
if(!$sponsor)
{
$sponsor_error="<font color='red'>*Sponsor Must be Filled</font>";
}
if(!$country)
{
$country_error="<font color='red'>*Country Must be Filled</font>";
}
/* 
if(!$district)
{
$district_error="<font color='red'>*District Must be Filled</font>";
}
*/
if(!$region)
{
$region_error="<font color='red'>*Region Must be Filled</font>";
}
if(!$maritalstatus)
{
$maritalstatus_error="<font color='red'>*Marital Status Must be Filled</font>";
}
if(!$address)
{
$address_error="<font color='red'>*Address Must be Filled</font>";
}
if(!$religion)
{
$religion_error="<font color='red'>*Religion Must be Filled</font>";
}
if(!$village)
{
$village_error="<font color='red'>*Village Must be Filled</font>";
}
if(!$denomination)
{
$denomination_error="<font color='red'>*Denomination Must be Filled</font>";
}
if(!$postaladdress)
{
$postaladdress_error="<font color='red'>*Postal Address Must be Filled</font>";
}
if(!$residenceaddress)
{
$residenceaddress_error="<font color='red'>*Residentaddress Address Must be Filled</font>";
}
/*
if(!$disability)
{
$disability_error="<font color='red'>*Disability Must be Filled</font>";
}
*/
if(!$status)
{
$status_error="<font color='red'>*Status Address Must be Filled</font>";
}
if(!$gyear)
{
$gyear_error="<font color='red'>*Gyear Address Must be Filled</font>";
}
if(!$name)
{
$name_error="<font color='red'>*Name Address Must be Filled</font>";
}

}
form();
#check if RegNo Exist
$qRegNo = "SELECT RegNo FROM student WHERE RegNo = '$regno'";
$dbRegNo = mysql_query($qRegNo);
$total = mysql_num_rows($dbRegNo);
if ($total==1)
{
echo"
<table>
<tr><td><img src='./img/error.gif'></td>
<td>
ZALONGWA Database System discovered,<br> 
Registration Number $regno Already exist!
<br><a href='./admissionRegistrationForm.php'>Go Back and Insert New One!</a>
</td></tr></table>";
}
else
{
#insert record
$sql="INSERT INTO student
(Name,AdmissionNo,
Sex,DBirth,
MannerofEntry,MaritalStatus,
Campus,ProgrammeofStudy,
Faculty,
Sponsor,GradYear,
EntryYear,Status,
Address,Nationality,
Region,District,Country,
Received,user,
Denomination, Religion,
Disability,f7year,
kin,kin_phone,
kin_address,kin_job,
disabilityCategory,Subject,
account_number,
bank_branch_name,
bank_name,
form7name,
form7no,
paddress,
Phone,
Email,
currentaddaress,
RegNo,
studylevel,
kin_relationship,
village,
kin_email
) 
VALUES
('$name','$AdmissionNo',
'$sex','$dtDOB',
'$manner','$maritalstatus',
'$campus','$degree',
'$faculty',' $sponsor',
'$gyear','$ayear',
'$status','$paddress',
'$country',
'$region','$district',
'$country',now(),
'$username','$denomination', 
'$religion','$disability','$f7year',
'$kin','$kin_phone',
'$kin_address','$kin_job',
'$disabilityCategory','$Subject',
'$account_number',
'$bank_branch_name',
'$bank_name',
'$form7name',
'$form7no',
'$paddress',
'$phone1',
'$email1',
'$currentaddaress',
'$regno',
'$studylevel',
'$kin_relationship',
'$village',
'$kin_email'
)";   
//echo $sql;
$dbstudent = mysql_query($sql)or die(mysql_error());
if(!$dbstudent)
{
echo "Admision Record Cant be Saved";
}
else
{
echo "Admision Record Saved Successfuly";
}		
}
}
else
{
form();	
}
	
	
?>
<?php


//*************REGISTRATION FORM
function form()
{
global $state1,$state2,$state3,$label_edit,$state4,$AdmissionNo,$stdid, $studylevel,$kin_relationship;
global $disabilityCategory,$studylevel,$father,$father_job,$mother,$mother_job,$father_address,
$father_phone,$mother_address,$mother_phone,
$brother,$brother_phone,$brother_address,$brother_job,
$sister,$sister_phone,$sister_address,$sister_job,
$spouse,$spouse_phone,$spouse_address,$spouse_job,
$kin,$kin_phone,$kin_address,$kin_job,$School_attended_olevel,$School_attended_to_alevel,$School_attended_alevel,$School_attended_from_alevel,
$relative,$relative_phone,$relative_address,$relative_job,$School_attended_to_olevel,$School_attended_from_olevel,
$txtYear,$txtDay,$txtMonth,$sponsor,$maritalstatus,$country,$formsix,$formfour,$diploma,$phone1,$email1,$regno,$degree,$faculty,$ayear,$combi,$campus,$manner,$byear,$bmon,$bday,$dtDOB,$surname,$firstname,$middlename,$dtDOB,$age,$sex,$sponsor,$country,$district,$region,$maritalstatus,$address,$religion,$denomination,$postaladdress,$residenceaddress,$disability,$status,$gyear,$name,$ayear,$campus,
$kin,$kin_phone,$kin_address,$kin_job,$f4year,$f6year,$f7year,$kin_email;
global $disabilityCategory_erro,$studylevel_error,$maritalstatus_error,$country_error,$ayear_error,$formsix_error,$formfour_error,$diploma_error,$phone1_error,$email1_error,$regno_error,$degree_error,$faculty_error,$ayear_error,$combi_error,$campus_error,$manner_error,$byear_error,$bmon_error,$bday_error,$dtDOB_error,$surname_error,$firstname_error,$middlename_error,$dtDOB_error,$age_error,$sex_error,$sponsor_error,$country_error,$district_error,$region_error,$maritalstatus_error,$address_error,$religion_error,$denomination_error,$postaladdress_error,$residenceaddress_error,$disability_error,$status_error,$gyear_error,$name_error;
global $account_number,$bank_branch_name,$bank_name,$form4no,$form4name,$form6name,$form6no,$form7name,$form7no,$paddress,$currentaddaress,$village;
?> 
<form action=" <?php echo $_SERVER['PHP_SELF'] ?>" method="POST" name='admission'>
<fieldset>
<table align="center" cellspacing='2' >
<tr>
<td> 
<?php echo $label_edit;?>&nbsp;
</td>
<td class='zatable'>
<input name="actionupdate" type="<?php echo $state1;?>" value="Save Changes"  onmouseover="this.style.background='#DEFEDE'"
onmouseout="this.style.background='#CFCFCF'" title="Click to Save Changes">
<input name="save" type="<?php echo $state2;?>" value="Save Record"
 onmouseover="this.style.background='#DEFEDE'"
onmouseout="this.style.background='#CFCFCF'" title="Click to Save Record" >
</td>
</tr>
</table>

<table  cellpadding='0' cellspacing='0' class='ztable'>

  <tr>
    <td colspan="4" nowrap="nowrap" class="hseparator">
	Study Programme Information    </td>
    </tr>
  
  
  <tr>
    <td nowrap="nowrap" class='formfield'>Year of Admission:<span class="style2">*</span></td>
   <td class='ztable'>
<select name="ayear" id="select" class="vform" <?php echo $state4;?> title="Select Year of Admission">
<?php
if(!$ayear)
{
echo"<option value=''>[Select Academic Year]</option>";
}else
{
echo"<option value='$ayear'>$ayear</option>";
}
$nm=mysql_query("SELECT AYear FROM academicyear where AYear!='$ayear' ORDER BY AYear DESC");
while($show = mysql_fetch_array($nm) )
{  										 
echo"<option  value='$show[AYear]'>$show[AYear]</option>";      
}
	
	
?>										                                        												 
</select>
<?php echo $ayear_error; 
?>
</td>
<td nowrap="nowrap" class="formfield">Admission No:<span class="style2">*</span>	</td>
<td class='ztable'>
<input name="hiddenregno" type="hidden" id="hiddenregno" value = "7070"    <?php echo $state3;?> <?php echo $state4;?> />
<input name="stdid" type="hidden" id="stdid" value = "<?php echo $stdid;?>" <?php echo $state3;?> <?php echo $state4;?> />
<input name="AdmissionNo" type="text" id="AdmissionNo" value = "<?php echo $AdmissionNo;?>"    <?php echo $state3;?> <?php echo $state4;?>
title="Type here Admission Number" />
 
<?php echo $regno_error;?> </td>
</tr>
<tr>
<td nowrap="nowrap" class='formfield'>Campus:<span class="style2">*</span></td>
<td class='ztable'>
<select name="campus" <?php echo $state4;?> title="Select Campus">
<?php
if(!$campus)
{
echo"<option value=''>[Select Campus]</option>";
}
else
{
$query_campus1 = mysql_query("SELECT CampusID, Campus FROM campus where CampusID='$campus'");
$camp=mysql_fetch_array($query_campus1);
echo"<option value='$campus'>$camp[Campus]</option>";
} 
$query_campus = "SELECT CampusID, Campus FROM campus ORDER BY Campus ASC";
$nm=mysql_query($query_campus);
while($show = mysql_fetch_array($nm) )
{  										 
echo"<option  value='$show[Campus]'>$show[Campus]</option>";      
}
	
	
?>										                                        												 
</select>
<?php echo $campus_error;  ?></td>
  <td class='formfield'>Registration No:<span class="style2">*</span></td>
  <td class='ztable'>
  <input name="regno" type="text" id="regno" value = "<?php echo $regno;?>" <?php echo $state4;?> <?php echo $state;?> 
title="Type here Registration Number">
  <?php ?>
  </td>
</tr>
<tr>
<td nowrap="nowrap" class='formfield'>Program Registered:<span class="style2">*</span></td> 
<td class='ztable'>
<select name="degree" id="degree"  <?php echo $state3;?> <?php echo $state4;?> title="Select Program Registered">
<?php
if(!$degree)
{
echo"<option value=''>[Select Programme]</option>";
}else
{
$take=mysql_query("select * from programme where ProgrammeCode='$degree'")or die(mysql_error());
$t=mysql_fetch_array($take);
echo"<option value='$degree'>$t[ProgrammeName]</option>";
}  
$query_degree = "SELECT ProgrammeCode,ProgrammeName,Faculty FROM programme ORDER BY ProgrammeName";
$nm=mysql_query($query_degree);
while($show = mysql_fetch_array($nm) )
{  										 
echo"<option  value='$show[ProgrammeCode]'>$show[ProgrammeName]</option>";      
     
}
	
	
?> 
</select>
<?php 
echo $degree_error;  
?></td>  
<td nowrap="nowrap" class="formfield" >Graduation Date:</td>
<td class='ztable'>
<input type="text" name="dtDate" value="<?php echo $gyear;?>" <?php echo $state4;?> title="Click the Calender icon to select Graduation Date" />
  <script type="text/javascript" src="./calendars.js"></script>
  <script language="JavaScript">
	new tcal ({'formname': 'admission','controlname': 'dtDate'});
	   </script>
  <?php echo $date_error;  ?> </td>

</tr>
<tr><td class='formfield'>Faculty:<span class="style2">*</span>
</td><td class='ztable'>
<select name="faculty" id="faculty" <?php echo $state4;?> title="Type faculty Name">
  <?php
if(!$faculty)
{
echo"<option value=''>[Select Faculty]</option>";
}else
{

echo"<option value='$faculty'>$faculty</option>";
}  
$query_faculty = "SELECT FacultyName FROM faculty ORDER BY FacultyName DESC";
$nm=mysql_query($query_faculty);
while($show = mysql_fetch_array($nm) )
{  										 
echo"<option  value='$show[FacultyName]'>$show[FacultyName]</option>";      
        
}
	
	
?>
   </select>
       <?php 
echo $faculty_error;
?></td>
     <td nowrap="nowrap" class="formfield">Sponsorship:	 </td>
    <td class='ztable'>
	 <select name="txtSponsor" id="txtSponsor" <?php echo $state4;?> title="Select Sponsorship">
       <?php
if(!$sponsor)
{
echo"<option value=''>[Select Sponsor]</option>";
}else
{
echo"<option value='$sponsor'>$sponsor</option>";
}  
$query_sponsor = "SELECT Name FROM sponsors ORDER BY SponsorID ASC";
$nm=mysql_query($query_sponsor);
while($show = mysql_fetch_array($nm) )
{  										 
echo"<option  value='$show[Name]'>$show[Name]</option>";      
    
}
	
	
?>
     </select>
       <?php 
echo $sponsor_error;
?></td>
   </tr>
<tr>
<td nowrap="nowrap" class="formfield">Level of Study Registered for:</td>
  <td class='ztable'>
 
<select name="studylevel" id="studylevel" <?php echo $state4;?> title="Select Study Level">
<?php
if(!$studylevel)
{
echo"<option value=''>[Select Level of Study]</option>";
}else
{
$take=mysql_query("select * from programmelevel where Code='$studylevel'");
$t=mysql_fetch_array($take);
echo"<option value='$studylevel'>$t[StudyLevel]</option>";
}  
$query_studylevel = "SELECT * FROM programmelevel";
$nm=mysql_query($query_studylevel);
while($show = mysql_fetch_array($nm) )
{  										 
echo"<option  value='$show[Code]'>$show[StudyLevel]</option>";      
 
}
	
	
?>										                                        						 
</select>
<?php ?>
</td>
<td nowrap="nowrap"class="formfield">Manner of Entry:</td>
<td class='ztable'>
<select name="manner" id="manner"  title="Select Mannner of Entry" <?php echo $state4;?>
<?php
if(!$manner)
{
echo"<option value=''>[Select Manner of Entry]</option>";
}else
{
$query_Manner =mysql_query("SELECT ID, MannerofEntry FROM mannerofentry where ID='$manner'");
$mana=mysql_fetch_array($query_Manner);
echo"<option value='$manner'>$mana[MannerofEntry]</option>";
}  
$query_MannerofEntry = "SELECT ID, MannerofEntry FROM mannerofentry ORDER BY MannerofEntry ASC";
$nm=mysql_query($query_MannerofEntry);
while($show = mysql_fetch_array($nm) )
{  										 
echo"<option  value='$show[ID]'>$show[MannerofEntry]</option>";      
}
	
	
?>
     </select>
<?php 
echo $manner_error;
?></td>  
  
</tr>
  <tr>
    <td colspan="4" nowrap="nowrap" class="hseparator">
	Personal Information    </td>
    </tr>
    <tr><td class='formfield'>Surname:<span class="style2">*</span></td><td class='ztable'>
	<input name="surname" type="text" id="surname" value = "<?php echo $surname;?>" size="30"  <?php //echo $state3;?> 
<?php echo $state4;?>
title="Type here Surname"/>
      <?php echo $surname_error;  ?></td>
     <td class="formfield">Religion:     </td>
     <td class='ztable'>
<?php
 echo"<select name='denomination' id='denomination'  $state4  $state4  title='Select here Denomination'>";
if(!$denomination)
{
echo"<option value=''>[Select Sect of Religion ]</option>";
}
else
{
	
	
?>
<option value="<?php echo $denomination;?>"><?php echo $denomination;?></option>
<?php
}  

$query_denomination2 = "SELECT * FROM religion";
$nr=mysql_query($query_denomination2);
while($l=mysql_fetch_array($nr))
{
//echo"<optgroup label='$l[Religion]'>";
//$query_denomination = "SELECT * FROM denomination where ReligionID='$l[ReligionID]' ORDER BY denomination ASC";
$query_denomination = "SELECT * FROM religion where ReligionID='$l[ReligionID]' ORDER BY Religion ASC";
$nm=mysql_query($query_denomination);
while($show = mysql_fetch_array($nm) )
{  										 
echo"<option  value='$show[Religion]'>$show[Religion]</option>";      
}
//echo"</optgroup>";
}
	
	
?>
      </select>
        <?php 
echo $denomination_error; 
?></td>
    </tr>
    
   <tr>
   <td class='formfield' norwap>Middlename:</td> 
  <td class='ztable'>
<input name="middlename" type="text" id="middlename" value="<?php echo $middlename;?>" size="15" maxlength="50"  <?php //echo $state3;?> <?php echo $state4;?> title="Type here Middle Name"/>
     <?php echo $middlename_error;  ?> </td>
  <td class="formfield">Marital Status:</td>
  <td class='ztable'>
   <select name="maritalstatus" id="maritalstatus" <?php echo $state4;?> title="Type here Marital Status">
     <?php
if($maritalstatus)
{
echo "<option value='$maritalstatus'>$maritalstatus</option>";
}else
{
echo "<option value=''>[Select Marital Status]</option>";
}
	
	
?>
     <option value="Single">Single</option>
     <option value="Married">Married</option>
     <option value="Divorced">Divorced</option>
     <option value="Widowed">Widowed</option>
   </select>
<?php    
echo $maritalstatus_error; 
?></td>
</tr>
<tr>
<td class='formfield'>Firstname:<span class="style2">*</span></td>
<td class='ztable'>
<input name="firstname" type="text" value="<?php echo $firstname;?>" id="firstname" size="30" <?php echo $state4;?>
title="Type here First Name"/>
<?php echo $firstname_error;  ?> 
</td>
<td class="formfield">Disability:</td> 

<td class='ztable'>
<select name='disabilityCategory' <?php echo $state4; ?> title="Select here Disability type">
     <?php
if(!$disabilityCategory)
{
	
	
?>
     <option value="<?php echo $disabilityCategory; ?>"> <?php echo $disabilityCategory;?></option>
     <?
}
else
{
echo"<option value='None'>[Select Disability]</option>";
}
$query_disability3 = "SELECT * FROM disability"; 
$nm3=mysql_query($query_disability3);
while($s= mysql_fetch_array($nm3))
{
echo"<optgroup label='$s[disability]'>";      
$query_disability2 = "SELECT * FROM disabilitycategory where DisabilityCode='$s[DisabilityCode]'";
$nm2=mysql_query($query_disability2);
while($show=mysql_fetch_array($nm2))
{ 	 
echo"<option  value='$show[disabilityCategory]'>$show[disabilityCategory]</option>";      
}
echo"<optgroup>";
}
	
	
?>
</select>
<?php 
echo $disability_error; 
?>
</td>
</tr>
   
<tr><td class='formfield' nowrap>Sex:<span class="style2">*</span></td>
<td class='ztable'>
<select name="sex" id="sex" <?php echo $state4; ?> title="Select Gender">
     <?php
if(!$sex)
{
echo"<option value=''>[Select Gender]</option>";
}
else
{
    if($sex=='F')
    {	
	
?>
     <option value="<?php echo $sex;?>">Female</option>
     <?php
    }else
     {
	
	
?>
     <option value="<?php echo $sex;?>">Male</option>
     <?php
}
}
	
	
?>
     <option value="M">Male</option>
     <option value="F">Female</option>
</select>
<?php 
echo $sex_error;
?>
</td>
<td nowrap="nowrap" class="formfield">Permanent Address:<span class="style2">*</span>  </td>
<td nowrap="nowrap" class="ztable">
<input name="paddress" type="text" id="paddress" size="30" value = "<?php echo $paddress;?>" <?php echo $state4;?> 
title="Type here Permanent Address"/>
<?php ?>
</td>
</tr>
<tr><td class='formfield'>Date of Birth:</td>
<td class='ztable'>
<input type="text" name="dtDOB" value="<?php echo $dtDOB;?>" <?php echo $state4;?> 
title="Click the Calender Icon to select Date of Birth "/>
<script language="JavaScript">
	new tcal ({'formname': 'admission','controlname': 'dtDOB'});
</script>
<?php echo $dbirth_error;?> 
</td>
<td class="formfield">Current Address:<span class="style2">*</span></td>
<td class='ztable'>
<input name="currentaddaress" type="text" id="currentaddaress" size="30" value = "<?php echo $currentaddaress;?>" <?php echo $state4;?> 
title="Type here Current Address"/>
<?php ?>
</td>
</tr>

<tr>
<td class='formfield'>Home District:<span class="style2">*</span></td> 
<td class='ztable'>
<input name="district" type="text" id="district" value = "<?php echo $district;?>" size="30" <?php echo $state4;?> 
title="Type here District"/>
<?php echo $district_error;  ?> </td>
<td class="formfield">Phone:</td>
<td class='ztable'>
<input name="phone" type="text"  size="30" value = "<?php echo $phone1;?>" <?php echo $state4;?> 
title="Type here Phone Number"/>
<?php 
echo $phone1_error; 
?>
</td>
</tr>

<tr>
<td class='formfield'>Traditional Authority:<span class="style2">*</span></td> 
<td class='ztable'>
<input name="region" type="text" id="region" size="30" value = "<?php echo $region;?>" <?php echo $state4;?>
title="Type here T.A" />
<?php echo $region_error;?></td>
<td class="formfield">E-mail:</td>
<td class='ztable'>
<input name="email" type="text"  size="30" value = "<?php echo $email1;?>" <?php echo $state4;?> 
title="Type here Email"/>
<?php 
echo $email1_error; 
?>
</td>
</tr>


<tr> 
<td class="formfield">Home Village:<span class="style2">*</span></td>
<td class='ztable'>
<input name="village" type="text"  size="30" value = "<?php echo $village;?>" <?php echo $state4;?> 
title="Type here Village"/>
<?php 
echo $village_error; 
?>
</td>
<!-- <td class='formfield'>Home Village:<span class="style2">*</span></td> 
<td class='ztable'>

<select name="select" <?php echo $state4;?> title="Select here Country of Birth">
<?php
if($country)
{
echo "<option value='$country'>$country</option>";
}
else
{
echo "<option value='Tanzania'>Tanzania</option>";
}
$query_country = "SELECT szCountry FROM country ORDER BY szCountry";
$countrys = mysql_query($query_country) or die(mysql_error());
while ($row_country = mysql_fetch_array($countrys))
{
	
	
?>
  <option value="<?php echo $row_country['szCountry']?>"> <?php echo $row_country['szCountry']?></option>
  <?php
}
	
	
?>
</select>
  <?php 
echo $country_error;   ?></td> -->
<td class="formfield">Name of Bank:</td>
<td class='ztable'>
<input name="bank_name" size="30" value = "<?php echo $bank_name;?>" <?php echo $state4;?> title="Type here Bank Name"/>
<?php ?>
</td>
</tr>

<tr>
<td class='formfield'>Nationality:<span class="style2">*</span></td>
<td class='ztable'>
<select name="country" <?php echo $state4;?> title="Select Nationality">
  <?php
if($country)
{
echo "<option value='$country'>$country</option>";
}
else
{
echo "<option value='Tanzania'>Tanzania</option>";
}
$query_country = "SELECT szCountry FROM country ORDER BY szCountry";
$countrys = mysql_query($query_country) or die(mysql_error());
while ($row_country = mysql_fetch_array($countrys))
{
	
	
?>
  <option value="<?php echo $row_country['szCountry']?>"> <?php echo $row_country['szCountry']?></option>
  <?php
}
	
	
?>
</select>
  <?php 
echo $country_error;   ?></td>
<td class="formfield">Name of Branch:</td>
<td class='ztable'>
<input name="bank_branch_name" size="30" value = "<?php echo $bank_branch_name;?>" <?php echo $state4;?>
title="Type here Branch Name" />
<?php ?>
</td>
</tr> 

<tr><td class='formfield'>Student Status:<span class="style2">*</span></td>
<td class='ztable'>
<select name="status" id="status" <?php echo $state4;?> title="Select Student Status">
  <?php
if(!$status)
{
echo"<option value=''>[Select Status]</option>";
}else
{
$query_studentStatus1 = mysql_query("SELECT StatusID,Status FROM studentstatus where StatusID='$status'");
$stat=mysql_fetch_array($query_studentStatus1);
echo"<option value='$status'>$stat[Status]</option>";
}  
$query_studentStatus = "SELECT StatusID,Status FROM studentstatus ORDER BY StatusID";
$nm=mysql_query($query_studentStatus);
while($show = mysql_fetch_array($nm) )
{  										 
echo"<option  value='$show[StatusID]'>$show[Status]</option>";      
      
}
	
	
?>
</select>
  <?php 
echo $status_error; 
?></td>
<td class="formfield">Account Number:</td>
<td class='ztable'>
<input name="account_number" size="30" value = "<?php echo $account_number;?>"  <?php echo $state4;?>
title="Type here Account Number"/>
  <?php ?></td>
</tr>

<tr>
<td colspan="4" nowrap="nowrap" class="hseparator">

Information About your Parents/Guardian(If Applicable)

</td>
</tr>

<tr><td nowrap="nowrap" class='formfield'>Name:</td> 
<td class='ztable'>
<input name="kin" size="30" value = "<?php echo $kin;?>" <?php echo $state4;?> 
title="Type here Name"/>
<?php ?>
</td>
<td class="formfield">Relationship:</td>
<td class='ztable'>
<input name="kin_relationship" size="30" value = "<?php echo $kin_relationship;?>" <?php echo $state4;?>
title="Type here Relationship" />
<?php ?>
</td>
</tr>
<tr>
<td nowrap="nowrap" class='formfield'>Occupation:</td> 
<td class='ztable'>
<input name="kin_job" size="30" value = "<?php echo $kin_job;?>" <?php echo $state4;?>
title="Type here Occupation"/>
<?php ?></td>
<td class="formfield">Contact Address:</td>
<td class='ztable'>
<input name="kin_address" size="30" value = "<?php echo $kin_address;?>" <?php echo $state4;?>
title="Type here Contact Address" />
<?php ?>
</td>
</tr>
<tr><td nowrap="nowrap" class='formfield'>Email:</td> 
<td class='ztable'>
<input name="kin_email" size="30" value = "<?php echo $kin_email;?>" <?php echo $state4;?>
title="Type here Email" />
<?php ?>
</td>
<td class="formfield">Phone:</td>
<td class='ztable'>
<input name="kin_phone" size="30" value = "<?php echo $kin_phone;?>" <?php echo $state4;?>
title="Type here Phone Number" />
<?php ?>
</td>

</tr>

<tr>
<td colspan="4" nowrap="nowrap" class="hseparator">
Entry Qualifications Information</td>
</tr>

<tr>
<!-- <td class="formfield">Form IV School Name:</td> 
<td class='ztable'>
<input name="form4name" type="text" id="form4name" size="30" value ="<?php echo $form4name;?>" <?php echo $state4;?> 
title="Type here Form Four School Name" />
<?php  ?>
</td>
<!--<td nowrap="nowrap" class="formfield">Form IV Examination No:<span class="style2">*</span></span></div></td> 
<td nowrap="nowrap" class='ztable' >
<input name="form4no" type="text" id="formfour3"  value ="<?php echo $form4no;?>" <?php echo $state4;?>
title="Type here Form Four Examination Number"/>
<?php
echo"<select name='f4year' $state4>";
if($f4year)
{
echo"<option value='$f4year'>$f4year</option>";
}
echo"<option value='None'>None</option>";
for($k=date('Y');$k>=1960;$k--)
{
echo"<option value='$k'>$k</option>";
}
echo"</select>";
?>
</td>
</tr> -->
<tr><td nowrap="nowrap" class='formfield'>Secondary School Name:<span class="style2"></span></td> 
<td class='ztable'>
<input name="form7name" type="text" id="diploma" size="30" value ="<?php echo $form7name;?>"  <?php echo $state4;?> 
title="Type here Secondary School Name"/>
<?php  ?>
</td>
<td nowrap="nowrap"  class="formfield">Examination No:</td>
<td nowrap="nowrap" class='ztable' >
<input name="form7no" type="text" id="diploma"  value ="<?php echo $form7no;?>" <?php echo $state4;?> title="Type here Examination Number"
/>
<?php
echo"Year Completed: <select name='f7year' $state4>";
if($f7year)
{

echo"<option value='$f7year'>$f7year</option>";
}
echo"<option value='None'>None</option>";
for($k=date('Y');$k>=1960;$k--)
{
echo"<option value='$k'>$k</option>";
}
echo"</select>";
?>
<?php echo $diploma_error;   ?></td>
</tr>
<!--
<tr>
  <td nowrap="nowrap" class='formfield'>Equivalent Institute Name:<span class="style2"></span>
<td class='ztable'>
<input name="form7name" type="text" id="formfour" size="30" value ="<?php echo $form7name;?>" <?php echo $state4;?> title="Type here Equivalent Instistution Name"/>
<?php ?>
</td> 
<td nowrap="nowrap" class="formfield"> Secondary School Name: </td>
<td nowrap="nowrap" class='ztable' >
<input name="form7name" type="text" id="diploma" value ="<?php echo $form7name;?>" <?php echo $state4;?> title="Type here Secondary School Information"/>
Examination No:<input name="form7no" type="text" id="diploma" value ="<?php echo $form7no;?>" <?php echo $state4;?> title="Type here Secondary School Information"/> -->
 <?php /*
echo"Year Completed: <select name='f7year' $state4>";
if($f7year)
{

echo"<option value='$f7year'>$f7year</option>";
}
echo"<option value='None'>None</option>";
for($k=date('Y');$k>=1960;$k--)
{
echo"<option value='$k'>$k</option>";
}
echo"</select>";
?>
<?php
 echo $diploma_error;   */
?> 
</td>
</tr> 

<tr>
<td colspan="4" >
<div align="center">
 
<input name="actionupdate" type="<?php echo $state1;?>" value="Save Changes"   
onmouseover="this.style.background='#DEFEDE'" onmouseout="this.style.background='#CFCFCF'" title="Click to Save Changes">
<input name="save" type="<?php echo $state2;?>" value="Save Record" onmouseover="this.style.background='#DEFEDE'" onmouseout="this.style.background='#CFCFCF'" title="Click to Save Record" > 
</div>
</td>
</td>
</tr>
</table>
</fieldset>
    
</form>

<?php 
}


#Updating Records
if(isset($_POST['actionupdate']))
{
    $disabilityCategory=$_POST['disabilityCategory'];
        $kin=$_POST['kin'];
        $kin_phone=$_POST['kin_phone'];
        $kin_address=$_POST['kin_address'];
 $kin_job=$_POST['kin_job'];
$regno = addslashes($_POST['regno']);
$AdmissionNo = addslashes($_POST['AdmissionNo']);   
		$stdid = $_POST['stdid'];
		$degree = $_POST['degree'];
		$faculty = $_POST['faculty'];
		$ayear = $_POST['ayear'];
		$combi = $_POST['combi'];
		$campus = $_POST['campus'];
		$manner = $_POST['manner'];
		$byear = addslashes($_POST['txtYear']);
		$bmon = addslashes($_POST['txtMonth']);
		$bday = addslashes($_POST['txtDay']);
		$dtDOB = $bday . " - " . $bmon . " - " . $byear;
		$surname = strtoupper(addslashes($_POST['surname']));
		$firstname = addslashes($_POST['firstname']);
		$middlename = addslashes($_POST['middlename']);
		$dtDOB = $_POST['dtDOB'];
		$age = $_POST['age'];
		$sex = $_POST['sex'];
		$sponsor = $_POST['txtSponsor'];
		$country = $_POST['country'];
		$district = addslashes($_POST['district']);
		$region = addslashes($_POST['region']);
		$maritalstatus = $_POST['maritalstatus'];
		$address = strtoupper($_POST['address']);
		$religion = $_POST['religion'];
		$denomination = $_POST['denomination'];
		$postaladdress = strtoupper(addslashes($_POST['postaladdress']));
		$residenceaddress = strtoupper(addslashes($_POST['residenceaddress']));
		$disability = $_POST['disability'];
		$status = $_POST['status'];
		$gyear = $_POST['dtDate'];
		$phone = $_POST['phone'];
		$email = $_POST['email'];
		$formsix = $_POST['formsix'];
		$formfour = $_POST['formfour'];
		$diploma = $_POST['diploma'];
		$studylevel= $_POST['studylevel'];
		$f4year= $_POST['f4year'];
		$f6year= $_POST['f6year'];
		$f7year= $_POST['f7year'];
		$denomination= $_POST['denomination'];
		$name = $surname.", ".$firstname." ".$middlename;

//Added fields
$village=$_POST['village'];
$account_number=$_POST['account_number'];
$bank_branch_name=$_POST['bank_branch_name'];
$bank_name=$_POST['bank_name'];
$form7name=$_POST['form7name'];
$form7no=$_POST['form7no'];
$paddress=$_POST['paddress'];
$currentaddaress=$_POST['currentaddaress'];
$kin_relationship=$_POST['kin_relationship'];

$qRegNo = "SELECT RegNo FROM student WHERE RegNo = '$regno'";
$dbRegNo = mysql_query($qRegNo);
$total = mysql_num_rows($dbRegNo);
if ($total>1) 
{
echo "ZALONGWA Database System discovered,<br> Registration Number ". $regno. " Already exist";
echo "<br> Go Back and Insert New One!<hr><br>";
}
else
{
#update record
$sql="update student set Name='$name',
Sex='$sex',DBirth='$dtDOB',
MannerofEntry='$manner',
MaritalStatus='$maritalstatus',
Campus='$campus',ProgrammeofStudy='$degree',
Faculty='$faculty',Sponsor='$sponsor',
GradYear='$gyear',EntryYear='$ayear',
Status='$status',
Address='$paddress',Nationality='$country',
Region='$region',District='$district',
Country='$country',
Received=now(),user='$username',
Denomination='$denomination',
Religion='$religion',Disability='$disability',f7year='$f7year',
kin='$kin',kin_phone='$kin_phone',
kin_address='$kin_address',kin_job='$kin_job',
disabilityCategory='$disabilityCategory',
Subject='$combi',
account_number='$account_number',
bank_branch_name='$bank_branch_name',
bank_name='$bank_name',
form7name='$form7name',
form7no='$form7no',
paddress='$paddress',
Phone='$phone',
Email='$email',
AdmissionNo='$AdmissionNo',
RegNo='$regno',
currentaddaress='$currentaddaress',
kin_relationship='$kin_relationship',
village='$village',
kin_email='$kin_email',
studylevel='$studylevel'
where Id='$stdid'";
$dbstudent = mysql_query($sql) or die(mysql_error());
if(!$dbstudent)
{
echo "Admision Record Cant be Updated";
}else
{
echo "Admision Record Updated Successfuly";
}		
}
}
}
//***********END OF REGISTRATION FORM******************
?>

