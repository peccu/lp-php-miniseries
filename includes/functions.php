<?php

/**
 * The number of the image or html file to use for the sample.
 * Starts at 1.
 */
$EDITION_FOR_SAMPLE = 1;


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
 * It will display either the image, or include the HTML file in /editions/.
 *
 * If there is no more content to display for this delivery in the /editions/
 * directory, we return a status of 410 to show that this partwork is finished -
 * the subscriber will be unsubscribed from this publication.
 *
 * If called from /edition/ then we expect to receive two parameters in the 
 * URL:
 *
 * `delivery_count` 
 * This counts up from 0, and indicates which edition should be published. BERG 
 * Cloud increments this every time we return content. So if we don't deliver 
 * an edition on a particular day, deliver_count will be the same the next day.
 * This value determines which image or * HTML file we display.
 * eg, if delivery_count is 0, we display /editions/1.png or /editions/1.html
 *
 * `local_delivery_time`
 * This will contain the time in the timezone where the Little Printer we're
 * delivering to is based, eg "2013-07-31T19:20:30.45+01:00".
 * We use this to determine if it's the correct day for a delivery.
 */
function lp_display_page() {
	global $EDITION_FOR_SAMPLE;

	// Should be either 'edition' or 'sample'.
	$directory_name = basename(getcwd());

	// Some checking of parameters first...
	if ( ! in_array($directory_name, array('edition', 'sample'))) {
		lp_fatal_error("This can only be run from either the 'edition' or 'sample' directories, but this is in '$directory_name'.");
	}
	if ($directory_name == 'edition' && ! array_key_exists('delivery_count', $_GET)) {
		lp_fatal_error("Requests for /edition/ need a delivery_count, eg '?delivery_count=0'");
	}

	// Work out whether this is a regular edition, or the sample, and what 
	// edition to show.
	if ($directory_name == 'edition') {
		$edition_number = (int) $_GET['delivery_count'] + 1;
		header('ETag: "' . md5($edition_number . gmdate('dmY')) . '"');

	} else { // 'sample'
		$edition_number = $EDITION_FOR_SAMPLE;
		header('ETag: "' . md5('sample' . gmdate('dmY')) . '"');
	}

	// If we have an image/file available for this edition, get its path.
	$file_path_data = lp_get_edition_file_path($edition_number);

	if ($file_path_data === FALSE) {
		// No edition is available for this edition_number. End the subscription.
		lp_status_code_header(410, 'Gone');
	
	} else {
		// We have content to display!

		lp_page_header();

		require lp_directory_path().'includes/header.php';	

		if ($file_path_data[0] == 'image') {
			echo '<img src="' . $file_path_data[1] . '" />';
		} else { // 'file'
			require $file_path_data[1];
		}

		require lp_directory_path().'includes/footer.php';	

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
 * Generate the path to the edition file we want to display.
 *
 * @param int $edition_number The 1-based number of the edition we're displaying.
 * @returns mixed FALSE if there's no file for this $edition_number, or an array.
 *		The array will have a first element of either 'image' or 'file', and a
 *		second element of either the image's URL, or the path to the file.
 */
function lp_get_edition_file_path($edition_number) {

	if (file_exists(lp_directory_path()."editions/$edition_number.png")) {
		return array(
			'image',
			"http://".$_SERVER['SERVER_NAME'].lp_directory_url()."editions/$edition_number.png");

	} else if (file_exists(lp_directory_path()."editions/$edition_number.html")) {
		return array('file', lp_directory_path()."editions/$edition_number.html");

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
function lp_fatal_error($message) {
	?>
	<p><strong>ERROR: <?php echo $message; ?></strong></p>
<?php
	lp_page_footer();
	exit;
}



?>
