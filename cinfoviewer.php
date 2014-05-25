<style>
#wmt_permnav td.selected {
	border-top: 5px solid #FFB40A; /* third template color */
}

#wmt_permnav a {
	color: #FFF;
	font-weight:100;
}

#wmt_permnav_search #wmt_searchgo {
	background-color: #FFF; 
	border: 2px solid #8A7B64; /* first template color */
	color: #000;
}

#wmt_banner {
	background-image: url('images/banner.jpg');
}

#wmt_menubar {
	background-color: #8A7B64;
	color: #FFF;
	background-image:url('/assets/styles/images/heading-gradient.gif');
}

#wmt_menubar td {
	color: #FFF;
}

#wmt_menubar td.selected {
	border-top: 5px solid #F0D200;
}

#wmt_menubar a {
	color: #FFF;
}

#wmt_vmenubar ul ul li {
	/*background-image: url('/assets/images/vmenu-bullet.gif') */
	/*list-style-type: square;*/
	color: #000;
}

.wmt_featitem {
	background-color: #FFF;
	border: 1px solid #CCC;
}

.wmt_featitem_alt {
	background-color: #FFF;
	border: 1px solid #CCC;
}

.wmt_featitem_alt .featitem_heading, .wmt_featitem .featitem_heading  {
	color: #000;
}

.wmt_featitem_alt .featitem_text, .wmt_featitem .featitem_text {
	color: #000;
}

.wmt_featitem_alt .featitem_link, .wmt_featitem .featitem_link {
	background-color: #FFF;
	border-top:1px solid #CCC;
}

.wmt_featitem_alt .featitem_link a, .wmt_featitem .featitem_link a {
	color: #000 !IMPORTANT;
	background-image: url('/assets/styles/images/featured-link.gif');	
}

#wmt_deakin_news {	
}

#wmt_deakin_news .wmt_heading {
	border-bottom:3px solid #39919D;
}

#wmt_deakin_news .news_article .news_heading {
	color: #000; 
}

#wmt_deakin_news .news_article .news_heading a {
	color: #075296; 
}

.wmt_infodiv, #course_search {
	 background-color:#FFF !important;
 
}

.wmt_infodiv .list_heading, .wmt_linkdiv .list_heading, .wmt_textdiv .text_heading, #usefulLinkHeader {
	
}

.wmt_infodiv li {
	
}

.wmt_infodiv ul ul li, .wmt_linkdiv li, .wmt_linkdiv ul ul li, #usefulLinks li {
	
}

.wmt_heading {
	color: #000;
}

.wmt_heading img{
	display:none;
}

/************************/
/*** Standard classes ***/
/************************/

#usefulLinkHeader {
	background-color: #FFF;
	color: #000;
}

tr.wmt_tr_heading {
	background-color: #8A7B64;  /* Primary template colour */
	color: #FFF;
	font-weight: bold;
}

tr.wmt_tr_heading a {
	color: #FFF  !IMPORTANT;
	text-decoration: underline;
}

tr.wmt_tr {
	background-color: #39919D; /* Second template color */
	color: #FFF;
}

tr.wmt_tr a {
	color: #FFF !IMPORTANT;
	text-decoration: underline;
}

td.wmt_td_heading {
	background-color: #8A7B64;  /* Primary template colour */
	color: #FFF;
	font-weight: bold;
}

td.wmt_td_heading a {
	color: #FFF !IMPORTANT;
	text-decoration: underline;
}

td.wmt_td {
	background-color: #39919D; /* Second template color */
	color: #FFF;
}

td.wmt_td a {
	color: #FFF !IMPORTANT;
	text-decoration: underline;
}

th.wmt_th {
	background-color: #8A7B64;  /* Primary template colour */
	color: #FFF;
	font-weight: bold;
	text-align: left;
}

th.wmt_th a {
	color: #FFF !IMPORTANT;
	text-decoration: underline;
}

td.wmt_td_box {
	background-color: #8A7B64;  /* Primary template colour */
	color: #FFF;
	font-weight: bold;
	text-align: center;
	height: 100px;
	width: 100px;
}

td.wmt_td_box a {
	color: #FFF !IMPORTANT;
	text-decoration: underline;
}

td.wmt_td_box_empty {
	background-color: #FFF;
	height: 100px;
	width: 100px;
}

div.wmt_div {
	background-color: #39919D; /* Second template color */
	color: #FFF;
}

div.wmt_div a {
	color: #FFF !IMPORTANT;
	text-decoration: underline;
}

div.wmt_div_heading {
	background-color: #8A7B64;  /* Primary template colour */
	color: #FFF;
	font-weight: bold;
	text-align: left;
}

div.wmt_div_heading a {
	color: #FFF !IMPORTANT;
	text-decoration: underline;
}

span.wmt_span {
	background-color: #39919D; /* Second template color */
	color: #FFF;
}

span.wmt_span a {
	color: #FFF !IMPORTANT;
	text-decoration: underline;
}

span.wmt_span_heading {
	background-color: #8A7B64;  /* Primary template colour */
	color: #FFF;
	font-weight: bold;
	text-align: left;
}

span.wmt_span_heading a {
	color: #FFF !IMPORTANT;
	text-decoration: underline;
}

fieldset.wmt_fieldset, fieldset.wmt_sub_fieldset
{
	border:1px solid #8A7B64;
}

fieldset.wmt_dotted_fieldset
{
	border:1px dotted #8A7B64;
}

legend.wmt_bg_legend
{
	background-color:#8A7B64;
	color: #FFF;
	padding:2px;
}
table.course_summary
{
    margin-top: 1em;
    border-collapse: collapse;
    border: 2px solid;
}

body {
	margin: 0px;
	padding: 0px;
	font-family: Verdana, Arial, sans-serif;
	font-size:62.5%;
}

table.course_summary td, table.course_summary th
{
	vertical-align: middle;
	line-height: 100%;	
	padding: 4px;
	border: 1px solid;
}

* table {
	font-size: 100%;
}

table.course_summary th
{
    font-weight: bold;
    text-align: left;
}

</style>
<?php
echo "<link rel='stylesheet' type='text/css' href='/template.css' />";  
include_once 'simple_html_dom.php';

if(isset($_GET["code"])){
	$url = "http://www.deakin.edu.au/future-students/courses/unit.php?unit=".strtoupper($_GET["code"]);
		
	$html = file_get_html($url);
	$usefullinks = $html->find('#usefulLinks');
	if ($usefullinks != null)
		$usefullinks[0]->innertext = "";
	echo ($html->find('#wmt_content')[0]);
}
?>