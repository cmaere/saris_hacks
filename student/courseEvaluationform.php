<?php
#get connected to the database and verfy current session
require_once('../Connections/sessioncontrol.php');
require_once('../Connections/zalongwa.php');
	# initialise globals
	//include('admissionMenu.php');
	# include the header
	/*global $szSection, $szSubSection;
	$szSection = 'Admission Process';
	$szSubSection = 'Registration Form';
	$szTitle = 'Student Registration Form';
	include('admissionheader.php');*/
	
	include('studentMenu.php');
	global $szSection, $szSubSection, $szTitle, $additionalStyleSheet;
	$szSection = 'Course Evaluation';
	$szTitle = 'Course Evaluation';
	$szSubSection = 'Course Evaluation';
	include("studentheader.php");
	//include("studentcourseevaluation.php");
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

.formfield2
{
text-align:Left;
text-decoration:norwap;
}
.error
{
font-size:8pt;
}

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

.formfield2{
border-bottom:1px dotted #CCC;
border-left:1px dotted #CCC;
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
   //PICKING CURRENTACADEMIC YEAR AND  SEMESTER
		   
           $query_ayear = "SELECT AYear, Semister_status FROM academicyear WHERE Status='1'";
		   
		   $resultAyear=mysql_query($query_ayear); 
            while ($line = mysql_fetch_array($resultAyear, MYSQL_ASSOC)) 
                {
                    $AYear = $line["AYear"];
                    $semester = $line["Semister_status"];
				}
	//PICKING CURRENT PROGRAMME AND PROGRAMME COURSES  
												
			$query_regcourses= " SELECT DISTINCT examregister.CourseCode, course.programme
          FROM examregister
          INNER JOIN course ON ( examregister.CourseCode = course.CourseCode )
          WHERE (examregister.RegNo = '$RegNo'
          AND examregister.semester = '$semester'
          AND examregister.AYear = '$AYear')";
		    
			$result_regcourses=mysql_query($query_regcourses); 
            while ($line2 = mysql_fetch_array($result_regcourses, MYSQL_ASSOC)) 
                {
                    //$firstcourse = $line2["CourseCode"];
					$programme = $line2["programme"];
					
                }
               
if(isset($_POST['save']))
{ 
     
      //$state=1;
        $ayear=$_POST['ayear'];
        $courseCode=addslashes($_POST['courseCode']);
	    $unitname=addslashes($_POST['unitname']);
		$lectureID=addslashes($_POST['lectureID']);
        $lname=addslashes($_POST['lname']);
        $campus=addslashes($_POST['campus']);
        $regno = addslashes($_POST['regno']);		
		$stdid = addslashes($_POST['stdid']);		
	    $q1 = addslashes($_POST['q1']);   
		$q2 = addslashes($_POST['q2']);
		$q3 = addslashes($_POST['q3']);
		$q4 = addslashes($_POST['q4']);
		$q5 = addslashes($_POST['q5']);
		$q6 = addslashes($_POST['q6']);
		$q7 = addslashes($_POST['q7']);
		$q8 = addslashes($_POST['q8']);
		$q9 = addslashes($_POST['q9']);
		$q10 = addslashes($_POST['q10']);
		$q11 = addslashes($_POST['q11']);
		$q12 = addslashes($_POST['q12']);
		$q13 = addslashes($_POST['q13']); 
		$q14 = addslashes($_POST['q14']);
        $q15 = addslashes($_POST['q15']); 
        $q16 = addslashes($_POST['q16']); 
        $q17 = addslashes($_POST['q17']); 
        $q18 = addslashes($_POST['q18']);
        $q19 = addslashes($_POST['q19']); 
        $q20 = addslashes($_POST['q20']); 
        $year = addslashes($_POST['year']); 
        $programme = addslashes($_POST['programme']); 		
		
//*************

//FORMATING ERRORS
if(!$courseCode||!$ayear||!$unitname||!$lname||!$campus||!$regno||!$q1||!$q2||!$q3||!$q4||!$q5||!$q6||!$q7||!$q8||!$q9||!$q10||!$q11||!$q12||!$q13|| !$q14||!$q15||!$q16||!$q16||!$q17||!$q18||!$q19||!$q20)
{
   if(!$campus)
    {
     $campus_error="<font color='red'>* Please select Campus!</font>";      
     }
   
   if(!$q1||!$q2||!$q3||!$q4||!$q5||!$q6||!$q7||!$q8)
   { 
    $sectionA="<font color='red'>* Please answer all questions in section A</font>";
    }
       
   if(!$q9||!$q10||!$q11||!$q12||!$q13)
   {
    $sectionB="<font color='red'>*Please answer all questions in section B</font>";
    }
	
    if(!$q14||!$q15||!$q16||!$q16||!$q17||!$q18||!$q19||!$q20)
    {
     $sectionC="<font color='red'>*Please answer all questions in section C</font>";
   }

form();



}
else
{

$qRegNo = "SELECT distinct coursecode, unitname, regno FROM studentcourseevaluations WHERE regno = '$regno' and coursecode ='$courseCode' and unitname='$unitname'";
$dbRegNo = mysql_query($qRegNo);
$total = mysql_num_rows($dbRegNo);
if ($total==1) 
{
//echo "System discovered that Registration Number ". $regno. " Already evaluated the course ".$courseCode.":".$unitname."<br>";
//echo "<br> year form eveluation form".$year; 
//echo "<br> programme eveluation form".$programme;
?>
<br> <br>

<form method="post" action="studentcourseevaluation.php">
<fieldset>
<table>
<tr>
<td align="center"> You already evaluated the course <?php echo $courseCode.":".$unitname."Lecturer:".$lname; ?></td>
</tr>
<tr>
<td align="center">
<input name="year" type="hidden"  value ="<?php echo $year; ?>"  />
<input name="programme" type="hidden"  value = "<?php echo $programme;?>"  />
<input name="add" type="submit"  value = "Click here to choose another course!"  />
</tr>
<table>
</fieldset>
</form>
<?php
}
else
{  
//ADING STUDENT RESPONCES INTO DATABASE studentcourseevaluations TABLE
$count=1;
	do {
		
		$scount="q".$count;
		addslashes($_POST['q1']); 
		
		
		
		$sql="INSERT INTO studentcourseevaluations 
(ayear, coursecode, unitname, regno, campus, question, score,lname) VALUES ('$ayear', '$courseCode','$unitname','$regno','$campus', '$count','$_POST[$scount]', '$lname')";   	

        $dbstudent = mysql_query($sql)or die(mysql_error());
       $count++;
	   
	  } while ($count < 21);

?>
<form method="post" action="studentcourseevaluation.php">
<fieldset>
<table>
<tr>
<td align="center"> You successfully evaluated the course <?php echo $courseCode.":".$unitname."Lecturer:".$lname; ?></td>
</tr>
<tr>
<td align="center">
<input name="year" type="hidden"  value ="<?php echo $year; ?>"  />
<input name="programme" type="hidden"  value = "<?php echo $programme;?>"  />
<input name="add" type="submit"  value = "Click here to choose another course!"  />
</tr>
<table>
</fieldset>
</form>
<?php
//echo"success and save";
//echo $ayear.":". $courseCode .":". $unitname .":". $lname .":".   $campus.":". $regno.":". $q1 .":". $q2 .":". $q3 .":". $q4 .":". $q5 .":". $q6 .":". $q7.":". $q8.":". $q9.":". $q10.":". $q11.":". $q12.":". $q13.":". $q14.":". $q15.":". $q16.":". $q17.":". $q18.":". $q19.":". $q20;  
//form();
}	
}

}
else
{
//echo"save not clicked loading form";
form($AYear);	
}
	
	
?>
<?php


//*************REGISTRATION FORM
function form()
{
	global $RegNo, $year, $programme, $AYear, $CourseCode, $unitname, $lname, $campus, $regno, $stdid, $q1, $q2, $q3, $q4, $q5, $q6, $q7, $q8, $q9, $q10, $q11, $q12, $q13, $q14, $q15, $q16, $q17, $q18, $q19, $q20;    
	global $sectionA, $sectionB, $sectionC, $campus_error, $semester;
//global $state1,$state2,$state3,$label_edit,$state4,$AdmissionNo,$stdid;

?> 
<form action=" <?php echo $_SERVER['PHP_SELF'] ?>" method="POST" name='courseEvaluation'>
<fieldset>

<table align="center" cellspacing='2' >
<tr> <td colspan="2"> <?php  echo $campus_error;   ?> </td>  </tr>
<tr> <td colspan="2"> <?php  echo $sectionA;   ?> </td>  </tr>
<tr> <td colspan="2"> <?php  echo $sectionB;    ?> </td>  </tr>
<tr> <td colspan="2"> <?php  echo $sectionC;    ?> </td>  </tr>
<tr>
<td> 
<?php echo $label_edit;?>&nbsp;
</td>
<td class='zatable'>

         <input name="save" type="submit" value="SaveRecord" onmouseover="this.style.background='#DEFEDE'"
         onmouseout="this.style.background='#CFCFCF'" title="Click to Save Record" >
</td>
</tr>
</table>

<table  cellpadding='0' cellspacing='0' class='ztable' width='900px'>

  <tr>
    <td colspan="4" nowrap="nowrap" class="hseparator">
	Course Information    </td>
    </tr>
  
    <tr>
    <td nowrap="nowrap" class='formfield'>Course Code:<span class="style2">*</span></td>
	 	
   <td class='ztable'> <b><select name="coursecode" size="1"><option value="0">[Click here to pick course]</option>
     <?php 
	  $query_coursecode = "SELECT DISTINCT CourseCode, CourseName FROM course WHERE  YearOffered LIKE '$semester' AND Programme= '$programme' ORDER BY coursecode ASC";
                $resultb=mysql_query($query_coursecode);
                while ($coursedetail = mysql_fetch_array($resultb, MYSQL_ASSOC)) 
                {
                    $coursecode = $coursedetail["CourseCode"];
                    $coursename = $coursedetail["CourseName"];
                   
                  ?>
                 
				 <option value="<?php echo $coursecode; ?>"><?php echo $coursecode." : ".$coursename;?></option>
    <?php
                                  
                }
	  ?>
   </b></td>
   <td nowrap="nowrap" class="formfield">Academic Year:<span class="style2">*</span>	</td>
   <td class='ztable'>
   
   <input name="ayear" type="text" id="ayear" value = "<?php /*if(isset($_GET['year'])) { echo $_GET['year'];  } else {*/ echo $AYear; ?> " readonly />
   </td>
   </tr>

<tr>
<td nowrap="nowrap" class='formfield'>Unit name:<span class="style2">*</span></td> 
<td class='ztable'><input name="unitname" type="text" id="unit" value = "<?php if(isset($_GET['unitname'])) { echo $_GET['unitname'];  } else { echo $unitname;} ?> " readonly />
</td>  
<td nowrap="nowrap" class="formfield" >Lecturer Name:</td>
<td class='ztable'><input name="lname" type="text" id="name" align="left" value = "<?php if(isset($_GET['name'])) { echo $_GET['name'];  } else { echo $lname;}  ?> " readonly />
 </td>

</tr>
<tr>
<td nowrap="nowrap" class='formfield'>Campus:<span class="style2">*</span></td>
<td class='ztable'>
<?php 
if(!$campus)
{  ?>
<select name="campus"  title="Select Campus">
<?php

echo"<option value=''>[Select Campus]</option>";



$query_campus1 = mysql_query("SELECT CampusID, Campus FROM campus where CampusID='$campus'");
$camp=mysql_fetch_array($query_campus1);
echo"<option value='$campus'>$camp[Campus]</option>";
 
$query_campus = "SELECT CampusID, Campus FROM campus ORDER BY Campus ASC";
$nm=mysql_query($query_campus);
while($show = mysql_fetch_array($nm) )
{  										 
echo"<option  value='$show[Campus]'>$show[Campus]</option>";      
}
}
else
{  ?>
<input name="campus" type="text" id="campus" value = "<?php echo $campus;  ?>" readonly />	
<?php }	
	
?>										                                        												 
</select>
<?php echo $campus_error;  ?></td>
  <td class='formfield'>Student No:<span class="style2">*</span></td>
  <td class='ztable'>
  <input name="regno" type="text" id="regno" value = "<?php if(isset($_GET['RegNo'])) { echo $_GET['RegNo'];  } else { echo $RegNo;}  ?>" readonly /> 

  </td>
</tr>

  <tr>
    <td colspan="4" nowrap="nowrap" class="hseparator">
	SECTION A.	Design and Delivery of the course    </td>
    </tr>
	<tr><td colspan="4" >
	    <table  cellpadding='0' cellspacing='0' class='ztable' >
		 <tr>
		 <td class='formfield2'>Q &nbsp;&nbsp;</td>
		 <td class='formfield2' >Question</td>
		 <td class='formfield2'>Strongly disagree</td>
		 <td class='formfield2' >Disagree &nbsp;&nbsp;</td>
		 <td class='formfield2' valign="top">Neutral&nbsp;&nbsp;</td>
		 <td class='formfield2' valign="top">Agree&nbsp;&nbsp;</td>
		 <td class='formfield2' >Strongly Agree &nbsp;&nbsp;</td>
		 <td class='formfield2'>Not Applicable &nbsp;&nbsp;</td>
		</tr>
		 <tr>
		  <td class='formfield2'>1</td>
		 <td class='formfield2'>The course outline was provided with clear aim, objectives and modes of assessment.</td>
		 <td class='formfield2'><input type="radio" name="q1" value="1" <?php if($q1=="1") { echo "checked";  } ?> /></td>
		 <td class='formfield2'><input type="radio" name="q1" value="2" <?php if($q1=="2") { echo "checked";  } ?> /></td>
		 <td class='formfield2'><input type="radio" name="q1" value="3" <?php if($q1=="3") { echo "checked";  } ?> /></td>
		 <td class='formfield2'><input type="radio" name="q1" value="4" <?php if($q1=="4") { echo "checked";  } ?> /></td>
		 <td class='formfield2'><input type="radio" name="q1" value="5" <?php if($q1=="5") { echo "checked";  } ?> /></td>
		 <td class='formfield2'><input type="radio" name="q1" value="6" <?php if($q1=="6") { echo "checked";  } ?> /></td>
		</tr>
		<tr>
		  <td class='formfield2'>2</td>
		 <td class='formfield2'>The presentation was clear and included explanation of difficult concepts, or expressions.</td>
		 <td class='formfield2'><input type="radio" name="q2" value="1" <?php if($q2=="1") { echo "checked";  } ?> /></td>
		 <td class='formfield2'><input type="radio" name="q2" value="2" <?php if($q2=="2") { echo "checked";  } ?> /></td>
		 <td class='formfield2'><input type="radio" name="q2" value="3" <?php if($q2=="3") { echo "checked";  } ?> /></td>
		 <td class='formfield2'><input type="radio" name="q2" value="4" <?php if($q2=="4") { echo "checked";  } ?> /></td>
		 <td class='formfield2'><input type="radio" name="q2" value="5" <?php if($q2=="5") { echo "checked";  } ?> /></td>
		 <td class='formfield2'><input type="radio" name="q2" value="6" <?php if($q2=="6") { echo "checked";  } ?> /></td>
		</tr>
		<tr>
		  <td class='formfield2'>3</td>
		 <td class='formfield2'>I was satisfied with the pace of the lectures.</td>
		 <td class='formfield2'><input type="radio" name="q3" value="1" <?php if($q3=="1") { echo "checked";  } ?> /></td>
		 <td class='formfield2'><input type="radio" name="q3" value="2" <?php if($q3=="2") { echo "checked";  } ?> /></td>
		 <td class='formfield2'><input type="radio" name="q3" value="3" <?php if($q3=="3") { echo "checked";  } ?> /></td>
		 <td class='formfield2'><input type="radio" name="q3" value="4" <?php if($q3=="4") { echo "checked";  } ?> /></td>
		 <td class='formfield2'><input type="radio" name="q3" value="5" <?php if($q3=="5") { echo "checked";  } ?> /></td>
		 <td class='formfield2'><input type="radio" name="q3" value="6" <?php if($q3=="6") { echo "checked";  } ?> /></td>
		</tr>
		<tr>
		  <td class='formfield2'>4</td>
		 <td class='formfield2'>The discussion of various topics was relevant to the overall objectives of the course.</td>
		 <td class='formfield2'><input type="radio" name="q4" value="1" <?php if($q4=="1") { echo "checked";  } ?> /></td>
		 <td class='formfield2'><input type="radio" name="q4" value="2" <?php if($q4=="2") { echo "checked";  } ?> /></td>
		 <td class='formfield2'><input type="radio" name="q4" value="3" <?php if($q4=="3") { echo "checked";  } ?> /></td>
		 <td class='formfield2'><input type="radio" name="q4" value="4" <?php if($q4=="4") { echo "checked";  } ?> /></td>
		 <td class='formfield2'><input type="radio" name="q4" value="5" <?php if($q4=="5") { echo "checked";  } ?> /></td>
		 <td class='formfield2'><input type="radio" name="q4" value="6" <?php if($q4=="6") { echo "checked";  } ?> /></td>
		</tr>
		<tr>
		  <td class='formfield2'>5</td>
		 <td class='formfield2'>We were given adequate opportunities for practice during the course.</td>
		 <td class='formfield2'><input type="radio" name="q5" value="1" <?php if($q5=="1") { echo "checked";  } ?> /></td>
		 <td class='formfield2'><input type="radio" name="q5" value="2" <?php if($q5=="2") { echo "checked";  } ?> /></td>
		 <td class='formfield2'><input type="radio" name="q5" value="3" <?php if($q5=="3") { echo "checked";  } ?> /></td>
		 <td class='formfield2'><input type="radio" name="q5" value="4" <?php if($q5=="4") { echo "checked";  } ?> /></td>
		 <td class='formfield2'><input type="radio" name="q5" value="5" <?php if($q5=="5") { echo "checked";  } ?> /></td>
		 <td class='formfield2'><input type="radio" name="q5" value="6" <?php if($q5=="6") { echo "checked";  } ?> /></td>
		</tr>
		<tr>
		  <td class='formfield2'>6</td>
		 <td class='formfield2'>We were given the opportunity to critically reflect on important issues, theories, concepts, or arguments, to enhance learning.</td>
		 <td class='formfield2'><input type="radio" name="q6" value="1" <?php if($q6=="1") { echo "checked";  } ?> /></td>
		 <td class='formfield2'><input type="radio" name="q6" value="2" <?php if($q6=="2") { echo "checked";  } ?> /></td>
		 <td class='formfield2'><input type="radio" name="q6" value="3" <?php if($q6=="3") { echo "checked";  } ?> /></td>
		 <td class='formfield2'><input type="radio" name="q6" value="4" <?php if($q6=="4") { echo "checked";  } ?> /></td>
		 <td class='formfield2'><input type="radio" name="q6" value="5" <?php if($q6=="5") { echo "checked";  } ?> /></td>
		 <td class='formfield2'><input type="radio" name="q6" value="6" <?php if($q6=="6") { echo "checked";  } ?> /></td>
		</tr>
		<tr>
		  <td class='formfield2'>7</td>
		 <td class='formfield2'>1 was satisfied with the overall workload including assignments of the course.</td>
		 <td class='formfield2'><input type="radio" name="q7" value="1" <?php if($q7=="1") { echo "checked";  } ?> /></td>
		 <td class='formfield2'><input type="radio" name="q7" value="2" <?php if($q7=="2") { echo "checked";  } ?> /></td>
		 <td class='formfield2'><input type="radio" name="q7" value="3" <?php if($q7=="3") { echo "checked";  } ?> /></td>
		 <td class='formfield2'><input type="radio" name="q7" value="4" <?php if($q7=="4") { echo "checked";  } ?> /></td>
		 <td class='formfield2'><input type="radio" name="q7" value="5" <?php if($q7=="5") { echo "checked";  } ?> /></td>
		 <td class='formfield2'><input type="radio" name="q7" value="6" <?php if($q7=="6") { echo "checked";  } ?> /></td>
		</tr>
		<tr>
		  <td class='formfield2'>8</td>
		 <td class='formfield2'>Overall I was impressed with the delivery of the course.</td>
		 <td class='formfield2'><input type="radio" name="q8" value="1" <?php if($q8=="1") { echo "checked";  } ?> /></td>
		 <td class='formfield2'><input type="radio" name="q8" value="2" <?php if($q8=="2") { echo "checked";  } ?> /></td>
		 <td class='formfield2'><input type="radio" name="q8" value="3" <?php if($q8=="3") { echo "checked";  } ?> /></td>
		 <td class='formfield2'><input type="radio" name="q8" value="4" <?php if($q8=="4") { echo "checked";  } ?> /></td>
		 <td class='formfield2'><input type="radio" name="q8" value="5" <?php if($q8=="5") { echo "checked";  } ?> /></td>
		 <td class='formfield2'><input type="radio" name="q8" value="6" <?php if($q8=="6") { echo "checked";  } ?> /></td>
		 
		</tr>
	    </table>
	</td></tr>
    


<tr>
<td colspan="4" nowrap="nowrap" class="hseparator">

SECTION B.	Your perceptions on the outcomes of the Course

</td>
</tr>
    <tr><td colspan="4" >
	    <table  cellpadding='0' cellspacing='0' class='ztable'>
		 <tr>
		 <td class='formfield2'> 		Q &nbsp;&nbsp;</td>
		 <td class='formfield2' >&nbsp;&nbsp;</td><td class='formfield2'>Strongly disagree</td>
		 <td class='formfield2' >Disagree &nbsp;&nbsp;</td><td class='formfield2' valign="top">Neutral&nbsp;&nbsp;</td><td class='formfield2' valign="top">Agree&nbsp;&nbsp;</td>
		 <td class='formfield2' >Strongly Agree &nbsp;&nbsp;</td><td class='formfield2'>Not Applicable &nbsp;&nbsp;</td>
		</tr>
		 <tr>
		  <td class='formfield2'>9</td>
		 <td class='formfield2'>Having attended the course, I now fully understand the key theories/principles/ concepts/issues.</td>
		 <td class='formfield2'><input type="radio" name="q9" value="1" <?php if($q9=="1") { echo "checked";  } ?> /></td>
		 <td class='formfield2'><input type="radio" name="q9" value="2" <?php if($q9=="2") { echo "checked";  } ?> /></td>
		 <td class='formfield2'><input type="radio" name="q9" value="3" <?php if($q9=="3") { echo "checked";  } ?> /></td>
		 <td class='formfield2'><input type="radio" name="q9" value="4" <?php if($q9=="4") { echo "checked";  } ?> /></td>
		 <td class='formfield2'><input type="radio" name="q9" value="5" <?php if($q9=="5") { echo "checked";  } ?> /></td>
		 <td class='formfield2'><input type="radio" name="q9" value="6" <?php if($q9=="6") { echo "checked";  } ?> /></td>
		</tr>
		<tr>
		  <td class='formfield2'>10</td>
		 <td class='formfield2'>The course has developed my skills as stated in its aims and objectives.</td>
		<td class='formfield2'><input type="radio" name="q10" value="1" <?php if($q10=="1") { echo "checked";  } ?> /></td>
		 <td class='formfield2'><input type="radio" name="q10" value="2" <?php if($q10=="2") { echo "checked";  } ?> /></td>
		 <td class='formfield2'><input type="radio" name="q10" value="3" <?php if($q10=="3") { echo "checked";  } ?> /></td>
		 <td class='formfield2'><input type="radio" name="q10" value="4" <?php if($q10=="4") { echo "checked";  } ?> /></td>
		 <td class='formfield2'><input type="radio" name="q10" value="5" <?php if($q10=="5") { echo "checked";  } ?> /></td>
		 <td class='formfield2'><input type="radio" name="q10" value="6" <?php if($q10=="6") { echo "checked";  } ?> /></td>
		</tr>
		<tr>
		  <td class='formfield2'>11</td>
		 <td class='formfield2'>I am confident to apply the knowledge and/or skills acquired in this course.</td>
		 <td class='formfield2'><input type="radio" name="q11" value="1" <?php if($q11=="1") { echo "checked";  } ?> /></td>
		 <td class='formfield2'><input type="radio" name="q11" value="2" <?php if($q11=="2") { echo "checked";  } ?> /></td>
		 <td class='formfield2'><input type="radio" name="q11" value="3" <?php if($q11=="3") { echo "checked";  } ?> /></td>
		 <td class='formfield2'><input type="radio" name="q11" value="4" <?php if($q11=="4") { echo "checked";  } ?> /></td>
		 <td class='formfield2'><input type="radio" name="q11" value="5" <?php if($q11=="5") { echo "checked";  } ?> /></td>
		 <td class='formfield2'><input type="radio" name="q11" value="6" <?php if($q11=="6") { echo "checked";  } ?> /></td>
		</tr>
		<tr>
		  <td class='formfield2'>12</td>
		 <td class='formfield2'>Overall, the course met its learning objectives.</td>
		 <td class='formfield2'><input type="radio" name="q12" value="1" <?php if($q12=="1") { echo "checked";  } ?> /></td>
		 <td class='formfield2'><input type="radio" name="q12" value="2" <?php if($q12=="2") { echo "checked";  } ?> /></td>
		 <td class='formfield2'><input type="radio" name="q12" value="3" <?php if($q12=="3") { echo "checked";  } ?> /></td>
		 <td class='formfield2'><input type="radio" name="q12" value="4" <?php if($q12=="4") { echo "checked";  } ?> /></td>
		 <td class='formfield2'><input type="radio" name="q12" value="5" <?php if($q12=="5") { echo "checked";  } ?> /></td>
		 <td class='formfield2'><input type="radio" name="q12" value="6" <?php if($q12=="6") { echo "checked";  } ?> /></td>
		</tr>
		<tr>
		  <td class='formfield2'>13</td>
		 <td class='formfield2'>We were given adequate opportunities for practice during the course.</td>
		 <td class='formfield2'><input type="radio" name="q13" value="1" <?php if($q13=="1") { echo "checked";  } ?> /></td>
		 <td class='formfield2'><input type="radio" name="q13" value="2" <?php if($q13=="2") { echo "checked";  } ?> /></td>
		 <td class='formfield2'><input type="radio" name="q13" value="3" <?php if($q13=="3") { echo "checked";  } ?> /></td>
		 <td class='formfield2'><input type="radio" name="q13" value="4" <?php if($q13=="4") { echo "checked";  } ?> /></td>
		 <td class='formfield2'><input type="radio" name="q13" value="5" <?php if($q13=="5") { echo "checked";  } ?> /></td>
		 <td class='formfield2'><input type="radio" name="q13" value="6" <?php if($q13=="6") { echo "checked";  } ?> /></td>
		</tr>
		<tr>
        </table>
	</td></tr>

<tr>
<td colspan="4" nowrap="nowrap" class="hseparator">
SECTION C. Questions about the Lecturer</td>
</tr>
<tr><td colspan="4" >
	    <table  cellpadding='0'  class='ztable'>
		 <tr>
		 <td class='formfield2'>Q &nbsp;&nbsp;</td>
		 <td class='formfield2' >Question</td>
		 <td class='formfield2'>Strongly disagree</td>
		 <td class='formfield2' >Disagree &nbsp;&nbsp;</td>
		 <td class='formfield2' valign="top">Neutral&nbsp;&nbsp;</td>
		 <td class='formfield2' valign="top">Agree&nbsp;&nbsp;</td>
		 <td class='formfield2' >Strongly Agree &nbsp;&nbsp;</td>
		 <td class='formfield2'>Not Applicable &nbsp;&nbsp;</td>
		</tr>
		 <tr>
		  <td class='formfield2'>14</td>
		 <td class='formfield2'>The Lecturer was well organized.</td>
		 <td class='formfield2'><input type="radio" name="q14" value="1" <?php if($q14=="1") { echo "checked";  } ?> /></td>
		 <td class='formfield2'><input type="radio" name="q14" value="2" <?php if($q14=="2") { echo "checked";  } ?> /></td>
		 <td class='formfield2'><input type="radio" name="q14" value="3" <?php if($q14=="3") { echo "checked";  } ?> /></td>
		 <td class='formfield2'><input type="radio" name="q14" value="4" <?php if($q14=="4") { echo "checked";  } ?> /></td>
		 <td class='formfield2'><input type="radio" name="q14" value="5" <?php if($q14=="5") { echo "checked";  } ?> /></td>
		 <td class='formfield2'><input type="radio" name="q14" value="6" <?php if($q14=="6") { echo "checked";  } ?> /></td>
		</tr>
		<tr>
		  <td class='formfield2'>15</td>
		 <td class='formfield2'>The Lecturer did not unnecessarily miss classes.</td>
		 <td class='formfield2'><input type="radio" name="q15" value="1" <?php if($q15=="1") { echo "checked";  } ?> /></td>
		 <td class='formfield2'><input type="radio" name="q15" value="2" <?php if($q15=="2") { echo "checked";  } ?> /></td>
		 <td class='formfield2'><input type="radio" name="q15" value="3" <?php if($q15=="3") { echo "checked";  } ?> /></td>
		 <td class='formfield2'><input type="radio" name="q15" value="4" <?php if($q15=="4") { echo "checked";  } ?> /></td>
		 <td class='formfield2'><input type="radio" name="q15" value="5" <?php if($q15=="5") { echo "checked";  } ?> /></td>
		 <td class='formfield2'><input type="radio" name="q15" value="6" <?php if($q15=="6") { echo "checked";  } ?> /></td>
		</tr>
		<tr>
		  <td class='formfield2'>16</td>
		 <td class='formfield2'>The Lecturer allowed students to actively and meaningfully get involved in the lecture.</td>
		 <td class='formfield2'><input type="radio" name="q16" value="1" <?php if($q16=="1") { echo "checked";  } ?> /></td>
		 <td class='formfield2'><input type="radio" name="q16" value="2" <?php if($q16=="2") { echo "checked";  } ?> /></td>
		 <td class='formfield2'><input type="radio" name="q16" value="3" <?php if($q16=="3") { echo "checked";  } ?> /></td>
		 <td class='formfield2'><input type="radio" name="q16" value="4" <?php if($q16=="4") { echo "checked";  } ?> /></td>
		 <td class='formfield2'><input type="radio" name="q16" value="5" <?php if($q16=="5") { echo "checked";  } ?> /></td>
		 <td class='formfield2'><input type="radio" name="q16" value="6" <?php if($q16=="6") { echo "checked";  } ?> /></td>
		</tr>
		<tr>
		  <td class='formfield2'>17</td>
		 <td class='formfield2'>The Lecturer was readily available for consultation outside the schedule  Lecture hours.</td>
		 <td class='formfield2'><input type="radio" name="q17" value="1" <?php if($q17=="1") { echo "checked";  } ?> /></td>
		 <td class='formfield2'><input type="radio" name="q17" value="2" <?php if($q17=="2") { echo "checked";  } ?> /></td>
		 <td class='formfield2'><input type="radio" name="q17" value="3" <?php if($q17=="3") { echo "checked";  } ?> /></td>
		 <td class='formfield2'><input type="radio" name="q17" value="4" <?php if($q17=="4") { echo "checked";  } ?> /></td>
		 <td class='formfield2'><input type="radio" name="q17" value="5" <?php if($q17=="5") { echo "checked";  } ?> /></td>
		 <td class='formfield2'><input type="radio" name="q17" value="6" <?php if($q17=="6") { echo "checked";  } ?> /></td>
		</tr>
		<tr>
		  <td class='formfield2'>18</td>
		 <td class='formfield2'>The Lecturer stimulated my interest in the subject matter/content of the course.</td>
		 <td class='formfield2'><input type="radio" name="q18" value="1" <?php if($q18=="1") { echo "checked";  } ?> /></td>
		 <td class='formfield2'><input type="radio" name="q18" value="2" <?php if($q18=="2") { echo "checked";  } ?> /></td>
		 <td class='formfield2'><input type="radio" name="q18" value="3" <?php if($q18=="3") { echo "checked";  } ?> /></td>
		 <td class='formfield2'><input type="radio" name="q18" value="4" <?php if($q18=="4") { echo "checked";  } ?> /></td>
		 <td class='formfield2'><input type="radio" name="q18" value="5" <?php if($q18=="5") { echo "checked";  } ?> /></td>
		 <td class='formfield2'><input type="radio" name="q18" value="6" <?php if($q18=="6") { echo "checked";  } ?> /></td>
		</tr>
		
		<tr>
		  <td class='formfield2'>119</td>
		 <td class='formfield2'>The Lecturer effectively managed his/her time in this course.</td>
		 <td class='formfield2'><input type="radio" name="q19" value="1" <?php if($q19=="1") { echo "checked";  } ?> /></td>
		 <td class='formfield2'><input type="radio" name="q19" value="2" <?php if($q19=="2") { echo "checked";  } ?> /></td>
		 <td class='formfield2'><input type="radio" name="q19" value="3" <?php if($q19=="3") { echo "checked";  } ?> /></td>
		 <td class='formfield2'><input type="radio" name="q19" value="4" <?php if($q19=="4") { echo "checked";  } ?> /></td>
		 <td class='formfield2'><input type="radio" name="q19" value="5" <?php if($q19=="5") { echo "checked";  } ?> /></td>
		 <td class='formfield2'><input type="radio" name="q19" value="6" <?php if($q19=="6") { echo "checked";  } ?> /></td>
		</tr>
		<tr>
		  <td class='formfield2'>20</td>
		 <td class='formfield2'>Overall, the lecturer is an effective teacher.</td>
		 <td class='formfield2'><input type="radio" name="q20" value="1" <?php if($q20=="1") { echo "checked";  } ?> /></td>
		 <td class='formfield2'><input type="radio" name="q20" value="2" <?php if($q20=="2") { echo "checked";  } ?> /></td>
		 <td class='formfield2'><input type="radio" name="q20" value="3" <?php if($q20=="3") { echo "checked";  } ?> /></td>
		 <td class='formfield2'><input type="radio" name="q20" value="4" <?php if($q20=="4") { echo "checked";  } ?> /></td>
		 <td class='formfield2'><input type="radio" name="q20" value="5" <?php if($q20=="5") { echo "checked";  } ?> /></td>
		 <td class='formfield2'><input type="radio" name="q20" value="6" <?php if($q20=="6") { echo "checked";  } ?> /></td>
		</tr>
        </table>
	</td></tr>
<tr>
<td colspan="4" >
<div align="center">
 
<input name="save" type="submit" value="SaveRecord" onmouseover="this.style.background='#DEFEDE'" onmouseout="this.style.background='#CFCFCF'" title="Click to Save " > 
</div>
</td>
</td>
</tr>
</table>
</fieldset>
    
</form>

<?php 
}




//***********END OF REGISTRATION FORM******************
?>

