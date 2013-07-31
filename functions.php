<?php

/**
 * The number of the image or html file to use for the sample.
 * Starts at 1.
 */
define('PART_FOR_SAMPLE', 1);


/**
 * Output at the top of both /edition/index.php and /sample/index.php
 */
function page_header() {
	?><!DOCTYPE html>
<meta charset="utf-8">
<html>
<head>
  <title>Little Printer Publication</title>

  <style type="text/css">
    body {
      background: #fff;
      color: #000;
      width: 384px;
      padding: 0;
      margin: 0;
      font-family: Arial, sans-serif;
      font-size: 16px;
    }
  </style>

</head>
<body>
<?php
}


/**
 * Output at the bottom of both /edition/index.php and /sample/index.php
 */
function page_footer() {
	?>
</body>
</html><?php
}


/**
 * Generates the HTML for the whole page, for both /edition/ and /sample/.
 *
 * It will display either the image, or include the HTML file in /parts/.
 *
 * If there is no more content to display for this delivery in the /parts/ directory, 
 * we return a status of 410 to show that this partwork is finished - the subscriber 
 * will be unsubscribed from this publication.
 *
 * If called from /edition/ then we expect to receive a `delivery_count` 
 * parameter in the URL, which counts up from 0. This determines which image or 
 * HTML file we display. eg, if delivery_count is 0, we display /parts/1.png or
 * /parts/1.html
 */
function display_page() {
	// Should be either 'edition' or 'sample'.
	$directory_name = basename(getcwd());

	if ( ! in_array($directory_name, array('edition', 'sample'))) {
		show_publication_error("This can only be run from either the 'edition' or 'sample' directories, but this is in '$directory_name'.");
	}

	if ($directory_name == 'edition') {
		$part_number = (int) $_GET['delivery_count'] + 1;
		header('ETag: "' . md5($part_number . gmdate('dmY')) . '"');

	} else { // 'sample'
		$part_number = PART_FOR_SAMPLE;
		header('ETag: "' . md5('sample' . gmdate('dmY')) . '"');
	}

	// If we have an image/file available for this part, get its path.
	$file_path_data = get_part_file_path($part_number);

	if ($file_path_data === FALSE) {
		// No part is available for this part_number. End the subscription.
		status_code_header(410, 'Gone');
	
	} else {
		// We have content to display!

		page_header();

		if ($file_path_data[0] == 'image') {
			echo '<img src="' . $file_path_data[1] . '" />';
		} else { // 'file'
			require $file_path_data[1];
		}

		page_footer();
	}
}


/**
 * Output an HTTP status code.
 * Because `http_response_code()` is only for PHP >= 5.4.
 * From http://stackoverflow.com/a/12018482/250962
 *
 * @param int $status_code The HTTP status code, eg 404.
 * @param string $status_string The message, eg "Not Found".
 */
function status_code_header($status_code, $status_string) {
	$sapi_type = php_sapi_name();
	if (substr($sapi_type, 0, 3) == 'cgi') {
		header("Status: $status_code $status_string");
	} else {
		header("HTTP/1.1 $status_code $status_string");
	}
}


/**
 * Generate the path to the part file we want to display.
 *
 * @param int $part_number The 1-based number of the part we're displaying.
 * @returns mixed FALSE if there's no file for this $part_number, or an array.
 *		The array will have a first element of either 'image' or 'file', and a
 *		second element of either the image's URL, or the path to the file.
 */
function get_part_file_path($part_number) {

	// eg '/lp-php-partwork/edition/../'
	$directory_path = dirname($_SERVER['PHP_SELF']) . "/../";
	// eg '/users/home/phil/web/public/lp-php-partwork/edition/../'
	$publication_path = $_SERVER['DOCUMENT_ROOT'] . $directory_path;
	// eg 'parts/1'
	$file_path = "parts/$part_number";

	if (file_exists($publication_path.$file_path.'.png')) {
		return array('image',
					"http://".$_SERVER['SERVER_NAME'].$directory_path.$file_path);

	} else if (file_exists($publication_path.$file_path.'.html')) {
		return array('file', $file_path.'.html');

	} else {
		return FALSE;
	}
}


/**
 * Displays an error message, ends the HTML, and finishes script execution.
 *
 * @param string $message The error message to display.
 */
function show_publication_error($message) {
	?>
	<p class="error"><?php echo $message; ?></p>
<?php
	page_footer();
	exit;
}



?>
