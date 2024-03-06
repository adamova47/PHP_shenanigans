<?php
//DB::
//$database_host = "localhost:3306";
//$database_user = "root";
//$database_pass = "root";
//$database_name = "cnc";
$database_host = "db:3306";
$database_user = "root";
$database_pass = "root";
$database_name = "cnc";

//DATA::
$userTable = "users";
$publiTable = "publications";
$projTable = "projects";

$projxpub = "projectxpublication";
$userxproj = "userxproject";
$userxpub = "userxpublication";

$publiTableFields = array("id", "vis", "ptype", "name", "address", "author", "booktitle", "edition", "editor", "institution", "journal", 
"month", "note", "number", "organization", "pages", "publisher", "school", "series", "title", "volume", "year", "url");
$publiTableString = "id, vis, ptype, name, address, author, booktitle, edition, editor, institution, journal, month, note, number, organization, pages, publisher, school, series, title, volume, year, url";
$projTableFields = array("id", "tag", "projectname", "description", "vis");
$projTableString = "id, tag, projectname, description, vis";
$aiSeminarFields = array("id", "datetime", "lecturer", "lecturerfrom", "url", "title", "abstract", "note");

$entrylimit = 20;

//BIBTEX
$bibfieldsSorted = array('author', 'title', 'year', 'pages', 'month', 'note',  'journal', 'volume', 'number', 'editor', 'publisher', 
'series', 'address', 'edition', 'booktitle', 'organization', 'school', 'institution');
$bibTypes = array("article", "book", "conference", "inbook", "incollection", "inproceedings", "techreport", "mastersthesis", "misc", "phdthesis", "unpublished");

$bibTypesArr = array(
	"article"		=> array("author", "title", "year", "pages", "month", "note", "journal", "volume", "number"),
	"book"			=> array("author", "title", "year", "editor", "publisher", "series", "address", "edition", "pages", "note"),
	"inbook"		=> array("author", "title", "year", "editor", "publisher", "series", "address", "edition", "pages", "note"),
	"conference"	=> array("author", "title", "booktitle", "year", "editor", "publisher", "series", "address", "organization", "pages", "note"),
	"incollection"	=> array("author", "title", "year", "editor", "series", "address", "edition", "booktitle", "organization", "pages", "note"),
	"inproceedings" => array("author", "title", "booktitle", "year", "editor", "publisher", "series", "address", "organization", "pages", "note"),
	"mastersthesis" => array("author", "title", "school", "year", "pages", "note"),
	"phdthesis"		=> array("author", "title", "school", "year", "pages", "note"),
	"techreport"	=> array("author", "title", "institution", "year", "pages", "note"),
	"misc"			=> array("author", "title", "year", "month", "note"),
	"unpublished"	=> array("author", "title", "year", "month", "note")
);

$bibtablesize = 16;

// PROJECTS
$visiblePubl = 3;

//GENERIC TEXTS
$errormessage = "<br/><p>An error ocurred during page generation.</p>"."\n";
$errorlogin = "Wrong username / password."."\n";
$errordatabase = '<p class="warning">Unable to establish connection with the database</p>'."\n";
$errordbupload = "Database error";
?>
