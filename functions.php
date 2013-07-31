<?php

/**
 * The number of the image or html file to use for the sample.
 * Starts at 1.
 */
$PART_FOR_SAMPLE = 1;


/**
 * Output at the top of both /edition/index.php and /sample/index.php
 */
function lp_page_header() {
	?><!DOCTYPE html>
<meta charset="utf-8">
<html>
<head>
	<title>Little Printer Publication</title>

	<link rel="stylesheet" type="text/css" href="../style.css" />

</head>
<body>
	<div id="lp-container">
<?php
}


/**
 * Output at the bottom of both /edition/index.php and /sample/index.php
 */
function lp_page_footer() {
	?>
	</div> <!-- #lp-container -->
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
function lp_display_page() {
	global $PART_FOR_SAMPLE;

	// Should be either 'edition' or 'sample'.
	$directory_name = basename(getcwd());

	if ( ! in_array($directory_name, array('edition', 'sample'))) {
		show_publication_error("This can only be run from either the 'edition' or 'sample' directories, but this is in '$directory_name'.");
	}

	if ($directory_name == 'edition') {
		$part_number = (int) $_GET['delivery_count'] + 1;
		header('ETag: "' . md5($part_number . gmdate('dmY')) . '"');

	} else { // 'sample'
		$part_number = $PART_FOR_SAMPLE;
		//header('ETag: "' . md5('sample' . gmdate('dmY')) . '"');
	}

	// If we have an image/file available for this part, get its path.
	$file_path_data = lp_get_part_file_path($part_number);

	if ($file_path_data === FALSE) {
		// No part is available for this part_number. End the subscription.
		status_code_header(410, 'Gone');
	
	} else {
		// We have content to display!

		lp_page_header();

		if ($file_path_data[0] == 'image') {
			echo '<img src="' . $file_path_data[1] . '" />';
		} else { // 'file'
			if (file_exists(lp_directory_path().'header.php')) {
				require lp_directory_path().'header.php';	
			}

			require $file_path_data[1];

			if (file_exists(lp_directory_path().'footer.php')) {
				require lp_directory_path().'footer.php';	
			}
		}

		lp_page_footer();
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
function lp_status_code_header($status_code, $status_string) {
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
function lp_get_part_file_path($part_number) {

	if (file_exists(lp_directory_path()."parts/$part_number.png")) {
		return array(
			'image',
			"http://".$_SERVER['SERVER_NAME'].lp_directory_url()."parts/$part_number.png");

	} else if (file_exists(lp_directory_path()."parts/$part_number.html")) {
		return array('file', "parts/$part_number.html");

	} else {
		return FALSE;
	}
}

/**
 * Gets the URL path (without domain) to this directory.
 * @return string eg, '/lp-php-partwork/edition/../'
 */
function lp_directory_url() {
	return dirname($_SERVER['PHP_SELF']) . "/../";
}

/**
 * Gets the full filesystem path to this directory.
 * @return string eg, '/users/home/phil/web/public/lp-php-partwork/edition/../'
 */
function lp_directory_path() {
	return $_SERVER['DOCUMENT_ROOT'] . lp_directory_url();
}


/**
 * Displays an error message, ends the HTML, and finishes script execution.
 *
 * @param string $message The error message to display.
 */
function lp_show_publication_error($message) {
	?>
	<p class="error"><?php echo $message; ?></p>
<?php
	page_footer();
	exit;
}



?>
