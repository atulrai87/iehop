<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------
| MIME TYPES
| -------------------------------------------------------------------
| This file contains an array of mime types.  It is used by the
| Upload class to help identify allowed file types.
|
*/

$mimes = array(	'hqx'	=>	'application/mac-binhex40',
				'cpt'	=>	'application/mac-compactpro',
				'csv'	=>	array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel'),
				'bin'	=>	'application/macbinary',
				'dms'	=>	'application/octet-stream',
				'lha'	=>	'application/octet-stream',
				'lzh'	=>	'application/octet-stream',
				'exe'	=>	'application/octet-stream',
				'class'	=>	'application/octet-stream',
				'psd'	=>	array('application/x-photoshop', 'image/vnd.adobe.photoshop', 'application/octet-stream'),
				'so'	=>	'application/octet-stream',
				'sea'	=>	'application/octet-stream',
				'dll'	=>	'application/octet-stream',
				'oda'	=>	'application/oda',
				'pdf'	=>	array('application/pdf', 'application/x-download'),
				'ai'	=>	array('application/postscript', 'application/pdf', 'application/octet-stream'),
				'eps'	=>	array('application/postscript', 'image/x-eps', 'application/octet-stream'),
				'ps'	=>	'application/postscript',
				'smi'	=>	'application/smil',
				'smil'	=>	'application/smil',
				'mif'	=>	'application/vnd.mif',
				'wbxml'	=>	'application/wbxml',
				'wmlc'	=>	'application/wmlc',
				'dcr'	=>	'application/x-director',
				'dir'	=>	'application/x-director',
				'dxr'	=>	'application/x-director',
				'dvi'	=>	'application/x-dvi',
				'gtar'	=>	'application/x-gtar',
				'gz'	=>	array('application/x-gzip', 'application/gzip'),
				'php'	=>	'application/x-httpd-php',
				'php4'	=>	'application/x-httpd-php',
				'php3'	=>	'application/x-httpd-php',
				'phtml'	=>	'application/x-httpd-php',
				'phps'	=>	'application/x-httpd-php-source',
				'js'	=>	'application/x-javascript',
				'swf'	=>	'application/x-shockwave-flash',
				'sit'	=>	'application/x-stuffit',
				'tar'	=>	array('application/x-tar', 'application/octet-stream'),
				'tgz'	=>	array('application/x-tar', 'application/octet-stream'),
				'rar'	=>	array('application/rar', 'application/octet-stream', 'application/x-rar'),
				'7z'	=>	array('application/x-7z-compressed', 'application/octet-stream'),
				'xhtml'	=>	'application/xhtml+xml',
				'xht'	=>	'application/xhtml+xml',
				'zip'	=>  array('application/x-zip', 'application/zip', 'application/x-zip-compressed', 'application/octet-stream'),
				'mid'	=>	'audio/midi',
				'midi'	=>	'audio/midi',
				'mpga'	=>	'audio/mpeg',
				'mp2'	=>	'audio/mpeg',
				'mp3'	=>	array('audio/mpeg', 'audio/mpg'),
				'aif'	=>	'audio/x-aiff',
				'aiff'	=>	'audio/x-aiff',
				'aifc'	=>	'audio/x-aiff',
				'ram'	=>	'audio/x-pn-realaudio',
				'rm'	=>	'audio/x-pn-realaudio',
				'rpm'	=>	'audio/x-pn-realaudio-plugin',
				'ra'	=>	'audio/x-realaudio',
				'rv'	=>	array('video/vnd.rn-realvideo', 'vnd.rn-realmedia', 'application/vnd.rn-realmedia'),
    			'wav'	=>	array('audio/x-wav', 'audio/wav'),
				'bmp'	=>	'image/bmp',
				'gif'	=>	'image/gif',
				'jpeg'	=>	array('image/jpeg', 'image/pjpeg'),
				'jpg'	=>	array('image/jpeg', 'image/pjpeg'),
				'jpe'	=>	array('image/jpeg', 'image/pjpeg'),
				'png'	=>	array('image/png',  'image/x-png'),
				'tiff'	=>	array('image/tiff', 'image/x-tiff'),
				'tif'	=>	array('image/tiff', 'image/x-tiff'),
				'css'	=>	array('text/css', 'application/x-pointplus'),
				'html'	=>	'text/html',
				'htm'	=>	'text/html',
				'shtml'	=>	'text/html',
				'txt'	=>	'text/plain',
				'text'	=>	'text/plain',
				'log'	=>	array('text/plain', 'text/x-log'),
				'rtx'	=>	array('text/richtext', 'application/rtf'),
				'rtf'	=>	array('text/rtf', 'application/x-rtf', 'application/rtf', 'text/richtext'),
				'xml'	=>	array('text/xml', 'application/xml', 'application/xml-sitemap'),
				'xsl'	=>	'text/xml',
				'mpeg'	=>	'video/mpeg',
				'mpg'	=>	'video/mpeg',
				'mpe'	=>	'video/mpeg',
				'qt'	=>	'video/quicktime',
				'mov'	=>	'video/quicktime',
				'avi'	=>	array('video/x-msvideo', 'video/avi', 'video/msvideo', 'application/x-troff-msvideo'),
				'movie'	=>	'video/x-sgi-movie',
				'doc'	=>	'application/msword',
				'docx'	=>	array('application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/zip'),
				'xlsx'	=>	array('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/zip'),
				'xls'	=>	array('application/excel', 'application/vnd.ms-excel', 'application/vnd.ms-office', 'application/msexcel'),
				'ppt'	=>	array('application/powerpoint', 'application/vnd.ms-powerpoint', 'application/mspowerpoint'),
				'word'	=>	array('application/msword'),
				'xl'	=>	'application/excel',
				'eml'	=>	'message/rfc822',
				'asf'	=>	'video/x-ms-asf',
				'wmv'	=>	array('audio/x-ms-wmv', 'video/x-ms-wmv'),
				'flv'	=>	array('video/x-flv', 'video/flv', 'application/octet-stream'),
				'mkv'	=>	array('video/x-matroska', 'application/octet-stream'),
				'mp4'	=>	'video/mp4',
			);


/* End of file mimes.php */
/* Location: ./system/application/config/mimes.php */