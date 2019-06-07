#!/usr/bin/php
<?php

$collection = 'mhealthevidence';

//load export using query below

//query below executed in mysqlworkbench.   needed to convert & and < to &amp; and &lt; 
$qry = 'use mhealthevidence;
select nid,
body_value, /* body_format, */
/* field_author_photo_value,  field_author_photo_format, */
field_bio_value , /* field_bio_format, */
/*field_countries_value,  field_countries_format, */
/*field_country_value,  field_country_format,*/
/* field_cover_image_fid, */
   ci.filename as cover_image_filename, ci.uri as cover_image_uri, ci.filemime as cover_image_mime, ci.filesize as cover_image_size,  ci.timestamp as cover_image_timestamp, ci.type as cover_image_type,
/*field_files_value,  field_files_format,*/
field_first_name_value,  /* field_first_name_format, */
/*field_data_field_guestfield_guest_value,  field_guest_format,*/
/*field_image_field_fid, */
    ifld.filename as image_field_filename, ifld.uri as image_field_uri, ifld.filemime as image_field_mime, ifld.filesize as image_field_size, ifld.timestamp as image_field_timestamp, ifld.type as image_field_type,
/*field_image_file_fid, */
    ifile.filename as image_file_filename, ifile.uri as image_file_uri, ifile.filemime as image_file_mime, ifile.filesize as image_file_size, ifile.timestamp as image_file_timestamp, ifile.type as image_file_type,
/* field_language_tid, */ lang.name as lang, 
field_last_name_value,  /*field_last_name_format, */
field_link_url,  field_link_title,
field_link_linkedin_url,  field_link_linkedin_title,
field_link_website_url,  field_link_website_title,
/*field_mterg_terms_tid, */ mterg.name as mterg_terms , 
field_organization_title_value, /* field_organization_title_format, */
field_outside_link_url,  field_outside_link_title,
field_post_author_nid,
/* field_post_tags_tid , */ ptags.name as post_tags ,
/* field_publication_year_tid, */ pyear.name as publication_year,
field_resource_file_description as resource_file_description, /*  field_resource_file_fid, */
   rf.filename as resource_file_filename, rf.uri  as resource_file_uri, rf.filemime as resource_file_mime, rf.filesize as resource_file_size, rf.timestamp as resource_file_timestamp, rf.type as resource_file_type,
/*field_resource_type_tid, */  rtype.name as resource_type,
/* field_tags_tid, */ tags.name as tags


FROM node
LEFT JOIN field_data_body ON field_data_body.entity_id = node.nid
 LEFT JOIN field_data_field_author_photo ON field_data_field_author_photo.entity_id = node.nid
 LEFT JOIN field_data_field_bio ON field_data_field_bio.entity_id = node.nid
 LEFT JOIN field_data_field_countries ON field_data_field_countries.entity_id = node.nid
 LEFT JOIN field_data_field_country ON field_data_field_country.entity_id = node.nid
 LEFT JOIN field_data_field_cover_image ON field_data_field_cover_image.entity_id = node.nid
 LEFT JOIN field_data_field_files ON field_data_field_files.entity_id = node.nid
 LEFT JOIN field_data_field_first_name ON field_data_field_first_name.entity_id = node.nid
 LEFT JOIN field_data_field_guest ON field_data_field_guest.entity_id = node.nid
 LEFT JOIN field_data_field_image_date ON field_data_field_image_date.entity_id = node.nid
 LEFT JOIN field_data_field_image_field ON field_data_field_image_field.entity_id = node.nid
 LEFT JOIN field_data_field_image_file ON field_data_field_image_file.entity_id = node.nid
 LEFT JOIN field_data_field_language ON field_data_field_language.entity_id = node.nid
 LEFT JOIN field_data_field_last_name ON field_data_field_last_name.entity_id = node.nid
 LEFT JOIN field_data_field_link ON field_data_field_link.entity_id = node.nid
 LEFT JOIN field_data_field_link_linkedin ON field_data_field_link_linkedin.entity_id = node.nid
 LEFT JOIN field_data_field_link_website ON field_data_field_link_website.entity_id = node.nid
 LEFT  JOIN field_data_field_mterg_terms ON field_data_field_mterg_terms.entity_id = node.nid
 LEFT JOIN field_data_field_organization_title ON field_data_field_organization_title.entity_id = node.nid
 LEFT JOIN field_data_field_outside_link ON field_data_field_outside_link.entity_id = node.nid
 LEFT JOIN field_data_field_post_author ON field_data_field_post_author.entity_id = node.nid
 LEFT JOIN field_data_field_post_tags ON field_data_field_post_tags.entity_id = node.nid
 LEFT JOIN field_data_field_publication_year ON field_data_field_publication_year.entity_id = node.nid
 LEFT JOIN field_data_field_resource_file ON field_data_field_resource_file.entity_id = node.nid
 LEFT JOIN field_data_field_resource_type ON field_data_field_resource_type.entity_id = node.nid
 LEFT JOIN field_data_field_tags ON field_data_field_tags.entity_id = node.nid
 LEFT JOIN file_managed as rf ON  field_data_field_resource_file.field_resource_file_fid = rf.fid
 LEFT JOIN file_managed as ifile ON  field_data_field_image_file.field_image_file_fid = ifile.fid
 LEFT JOIN file_managed as ifld ON  field_data_field_image_field.field_image_field_fid = ifld.fid
LEFT JOIN file_managed as ci ON  field_data_field_cover_image.field_cover_image_fid = ci.fid
LEFT JOIN taxonomy_term_data as tags on field_tags_tid =  tags.tid
 LEFT JOIN taxonomy_term_data as rtype on  field_resource_type_tid  = rtype.tid
  LEFT JOIN taxonomy_term_data as pyear on  field_publication_year_tid = pyear.tid
   LEFT JOIN taxonomy_term_data as ptags on  field_post_tags_tid = ptags.tid
   LEFT JOIN taxonomy_term_data  as mterg on field_mterg_terms_tid =  mterg.tid
   LEFT JOIN taxonomy_term_data  as lang on field_language_tid = lang.tid
order by nid

';


$doc = new DOMDocument();
$in_file = $collection . '_export.xml';
if (!$doc->load($in_file)) {die("bad file: $in_file.  Please execute the following in mysqlworkbench and export as excel? or xml?\n$qry");}


$xpath = new DOMXPath($doc);
$xpath->registerNameSpace("o","urn:schemas-microsoft-com:office:office");
$xpath->registerNameSpace("x","urn:schemas-microsoft-com:office:excel");
$xpath->registerNameSpace("","urn:schemas-microsoft-com:office:spreadsheet");
$xpath->registerNameSpace("ss","urn:schemas-microsoft-com:office:spreadsheet");
$xpath->registerNameSpace("html","http://www.w3.org/TR/REC-html40");

$rows = $xpath->query('//ss:Workbook/ss:Worksheet/ss:Table/ss:Row');
$data =array();
$header = false;
$headers = array();


$hexes = array('body_value','field_bio_value');
$filenames = array('cover_image_filename','image_field_filename','image_file_filename','resource_file_filename');
$mults = array('tags','resource_type','publication_year','post_tags','mterg_terms','lang');

$furl = 'https://www.mhealthknowledge.org/sites/default/files/';
$got = array();

set_error_handler(function($errno, $errstr, $errfile, $errline ) {
    });
$nid = false;

foreach ($rows as $row) {
    if (!$header) {
	$header =true;
	foreach ($xpath->query('./ss:Cell/ss:Data',$row) as $head) {
	    $headers[] = $head->textContent;
	}
    } else {
	foreach ($xpath->query('./ss:Cell/ss:Data',$row) as $i=>$cell) {
	    $d = $cell->textContent;
	    $c =  $headers[$i];
	    if ($c == 'nid') {
		if ( $d != $nid) { //assumes it is first column!!!!!
		    echo "New Entry: $d\n";
		    //new entry
		    if ($nid) {
			//was a previous one
			$data[$nid] = $r;
//			if ($nid == '7') {   var_dump($r); die();	}
		    }
		    $nid = $d;
		    $r = array();
		    foreach ($headers as $h) {
			if ($h != 'nid') {
			    $r[$h] = array();
			}
		    }
		}
		continue;
	    }
	    if (in_array($c,$filenames) && strlen($d) > 0 && !$got[$d]) {
		echo "\tretrieving $d from $furl$d\n";
		file_put_contents('files/' . $d, file_get_contents($furl . $d));
		$got[$d] = true;
	    }
	    if ( in_array($c,$hexes)  && (strlen($d) > 0) ) {
		$d = hex2bin($d);
		//echo $d;
		$d = str_replace('"/sites/default/files/','files/',$d); //fixup site specific URLs
//		$t_doc = new DOMDocument();
//		if ( !$t_doc->loadHTML($d) ) {
		    $d = strip_tags($d);
//		}
	    }
	    if (strlen($d) > 0 && ! in_array($d,$r[$c])) {
		$r[$c][] = $d;
		if (strlen($d) > 40) {
		    $d = substr($d,0,40) . "...";
		}
		$d = str_replace(array("\n\r", "\n", "\r")," ", $d);
		echo "\tAdding to $c $d for $prev\n";
	    }
	}
    }
    if ($nid > 206) {break;}
}
$data[$prev] = $r;


$lang_map = array(
    'English' => 'en'
    );

//want to output SAF https://wiki.duraspace.org/display/DSDOC5x/Importing+and+Exporting+Items+via+Simple+Archive+Format

$zip = new ZipArchive;
$zipfile = $collection . '_dspace.zip';
if (! $zip->open($zipfile, ZipArchive::CREATE))   { die("could not create zip file $zipfile\n");}

foreach ($data as $nid => $item) {
    $dir = 'item_' . $nid;
    echo "Adding $dir to zip file\n";
    $lang = 'en';
    $year = false;
    $title = false;
    $desc = false;
    $mime = false;
    $url =false;
    $file_list =array();
    if (count($item['resource_file_filename']) > 0) {
	$title = $item['resource_file_filename'];
	$mime = $item['resource_file_mime'];
    } elseif (count($item['field_link_title']) > 0) {
	$title = $item['field_link_title'];
	$rurl =  $item['field_link_url'];
	echo "\tretrieving $d from $furl$d\n";
	$rfile = file_get_contents($rurl);
	if ($rfile) {
	    echo "\tWARNING: Could not retrieve $rurl\n";
	} else {
	    $rfilename = 'file/' . basename(parse_url($rurl, PHP_URL_PATH));
	    file_put_contents($rfilename,$rfile);
	    $mime = mime_content_type($rfilename);
	    $file_list[] = $rfilename;
	}
    }
    if (!$mime) {
	echo "\tWARNING: no mime typefor $nid\n";	
	continue;
    }
    if (!$title) {
	echo "\tWARNING: no title found for $nid\n";
	continue;
    }
    if ((count($item['lang']) > 0) && ($t_lang = $item['lang'][0]) && (array_key_exists($lang,$lang_map))){
	$lang =$lang_map[$t_lang];
    }
    if (count($item['publication_year']) > 0) {
	$year = $item['publication_year'][0];
    }
    if (count($item['body_value']) > 0) {
	$desc = $item['body_value'][0];
    }
    $zip->addEmptyDir($dir);
    //see https://wiki.duraspace.org/display/DSDOC5x/Metadata+and+Bitstream+Format+Registries for dublin core fields
    $dc = '<dublin_core> 
  <dcvalue element="title" qualifier="none" language="' . $lang . '">' . $title .'</dcvalue>
' . ($year != false ? '  <dcvalue element="date" qualifier="issued">' . $year . '</dcvalue>' : '') .'
' . ($desc != false ? '  <dcvalue element="description" qualifier="abstract">' . $desc . '</dcvalue>' : '') .'
</dublin_core>
';
    $zip->addFromString($dir . '/dublin_core.xml', $dc);
    $zip->addFromString($dir . '/collection', $collection . "\n");



    foreach ($filenames as $filename) {
	$file_list = array_merge($file_list,$item[$filename]);
    }
    $file_list = array_unique($file_list);
    $zip->addFromString($dir . '/contents',implode($file_list,"\n") . "\n");
    foreach ($file_list as $f_name ) {
	echo "\tAdding $f_name\n";
	if (!file_exists("files/" . $f_name)) { echo "WARNING: $f_name disappeared\n";}
	if (! $zip->addFile("files/" . $f_name, $dir . '/' . $f_name )) {
	    echo "\tWARNING: could not add $f_name\n";
	}
    }
    

}
$zip->close();



