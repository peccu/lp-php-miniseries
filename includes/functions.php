<?php
$VERSION = 'v1.2.1';

require 'config.php';


/**
 * Output at the top of both /edition/index.php and /sample/index.php
 */
function lp_page_header() {
	?><!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Little Printer Publication</title>

	<style type="text/css">
<?php
	// Include styles inline as it's more reliable when rendering publications.
	require ('../style.css');
	?>
	</style>

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
	global $PUBLICATION_TYPE, $DELIVERY_DAYS, $EDITION_FOR_SAMPLE, $VERSION;

	// Check everything's OK.
	lp_pre_flight_check();

	// We ignore timezones, but have to set a timezone or PHP will complain.
	date_default_timezone_set('UTC');

	// We should always receive local_delivery_time but in case we don't,
	// we'll set one. This also makes testing each edition's URL easier.
	if (array_key_exists('local_delivery_time', $_GET)) {
		$local_delivery_time = $_GET['local_delivery_time'];
	} else {
		$local_delivery_time = gmdate('Y-m-d\TH:i:s.0+00:00');
	}

	// Should be OK for most likely publishers at the moment.
	header("Content-Type: text/html; charset=utf-8");

	// So we can tell if a problem publication is created by this code.
	header("LP-PHP-Miniseries: $VERSION");

	// Will be either 'edition' or 'sample'.
	$directory_name = basename(getcwd());

	// Work out whether this is a regular edition, or the sample, and what 
	// edition to show (if any).
	if ($directory_name == 'edition') {
		$edition_number = (int) $_GET['delivery_count'] + 1;
		lp_etag_header($edition_number, $local_delivery_time);

		if ($PUBLICATION_TYPE == 'numbered') {
			// Which weekday is this Little Printer on?
			$weekday = lp_day_of_week($local_delivery_time);

			if ( ! in_array($weekday, $DELIVERY_DAYS)) {
				// This is a day that there's no delivery.
				http_response_code(204);
				// It would be nice to output an error message here, so that 
				// developers trying /edition/ URLs on a non-delivery day see 
				// what's happening, but a 204 status won't return any content 
				// anyway.
				exit;
			}
		}
		// 'dated' publications can appear on any day of the week,
		// assuming there's a file available for today.

		$file_path_data = lp_get_edition_file_path(
										$edition_number, $local_delivery_time);

	} else { // 'sample'
		lp_etag_header('sample', $local_delivery_time);

		if ($PUBLICATION_TYPE == 'numbered') {
			// Get path for the sample edition..
			$file_path_data = lp_get_edition_file_path(
									$EDITION_FOR_SAMPLE, $local_delivery_time);
		} else {
			// Make a fake local_delivery_time based on sample's date.
			$file_path_data = lp_get_edition_file_path(
									1, $EDITION_FOR_SAMPLE . 'T00:00:00.0+00');
		}
	}

	if ($file_path_data === 410) {
		// No edition is available for this edition_number, or the 'dated' 
		// publication has ended.
		// End the subscription.
		http_response_code(410);
		if ($PUBLICATION_TYPE == 'numbered') {
			$message = "There is no edition number '" . $edition_number . "'.";
		} else {
			$message = "This publication has ended.";
		}
		lp_fatal_error(
			$message,
			"If BERG Cloud made this request, the subscriber would now be unsubscribed from the publication."	
		);

	} else if ($file_path_data === 204) {
		// No dated edition available for today.
		// Return nothing.
		http_response_code(204);
		exit;
	
	} else {
		// We have content to display!

		lp_page_header();

		require lp_directory_path().'includes/header.php';	

		if ($file_path_data['type'] == 'image') {
			echo '<img src="' . $file_path_data['url'] . '" />';
		} else { // 'file'
			require $file_path_data['file'];
		}

		require lp_directory_path().'includes/footer.php';	

		lp_page_footer();
	}
}


/**
 * Some basic checking to make sure everything's roughly OK before going 
 * any further.
 * Script execution will end here with an error if anything's not OK.
 */
function lp_pre_flight_check() {
	lp_check_config();
	lp_check_parameters();
}


/**
 * Check config globals.
 * Script execution will end here with an error if anything's not OK.
 */
function lp_check_config() {
	global $PUBLICATION_TYPE, $EDITION_FOR_SAMPLE, $DELIVERY_DAYS;

	// PUBLICATION_TYPE.

	if ( ! isset($PUBLICATION_TYPE)) {
		// This wasn't in <= v1.1.5 so set a default. 
		$PUBLICATION_TYPE = 'numbered';	
	} else if ( ! in_array($PUBLICATION_TYPE, array('numbered', 'dated'))) {
		lp_fatal_error('$PUBLICATION_TYPE should be either "numbered" or "dated", but it is currently "' . $PUBLICATION_TYPE . "'.");
	}

	// EDITION_FOR_SAMPLE.

	if ( ! isset($EDITION_FOR_SAMPLE)) {
		if ($PUBLICATION_TYPE == 'numbered') {
			lp_fatal_error('Please set $EDITION_FOR_SAMPLE to an integer value, eg 1.');
		} else {
			lp_fatal_error('Please set $EDITION_FOR_SAMPLE to a date in YYYY-MM-DD format, that matches an edition image or file.');
		}
	} else {
		// We have an EDITION_FOR_SAMPLE, but make sure it's a valid format.
		if ($PUBLICATION_TYPE == 'numbered') {
			if ( ! is_int($EDITION_FOR_SAMPLE)) {
				lp_fatal_error('$EDITION_FOR_SAMPLE should be an integer, but is currently "' . $EDITION_FOR_SAMPLE . '".');
			}	
		} else {
			preg_match('/^(\d\d\d\d)-(\d\d)-(\d\d)$/', $EDITION_FOR_SAMPLE, $matches);
			if (count($matches) !== 4) {
				lp_fatal_error('$EDITION_FOR_SAMPLE should be a date in YYYY-MM-DD format, but is currently "' . $EDITION_FOR_SAMPLE . '".');			
			} else {
				$year = intval($matches[1]);
				$month = intval($matches[2]);
				$day = intval($matches[3]);
				if ( ! checkdate($month, $day, $year)) {
					lp_fatal_error('$EDITION_FOR_SAMPLE should be a valid date, but is currently "' . $EDITION_FOR_SAMPLE . '".');			
				}
			}
		}
	}

	// DELIVERY_DAYS

	if ($PUBLICATION_TYPE == 'numbered') {
		if ( ! isset($DELIVERY_DAYS)) {
			lp_fatal_error('$DELIVERY_DAYS is not set. Please see the README for an explanation.');
		} else if ( ! is_array($DELIVERY_DAYS)) {
			lp_fatal_error('$DELIVERY_DAYS should be an array. Please see the README for an explanation.');
		} else {
			$valid_days = array('monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday');
			foreach($DELIVERY_DAYS as $day) {
				if ( ! in_array($day, $valid_days)) {
					lp_fatal_error('$DELIVERY_DAYS contains "' . $day . '" which is not a valid day.',
					'Valid days are: "' . implode('", "', $valid_days) . '".');
				}
			}	
		}
	}
}


/**
 * Checks directories etc are valid.
 * Script execution will end here with an error if anything's not OK.
 */
function lp_check_parameters() {
	// 'edition' or 'sample'.
	$directory_name = basename(getcwd());

	// Some checking of parameters first...
	if ( ! in_array($directory_name, array('edition', 'sample'))) {
		lp_fatal_error("This can only be run from either the 'edition' or 'sample' directories, but this is in '$directory_name'.");
	}
	if ($directory_name == 'edition') {
		if ( ! array_key_exists('delivery_count', $_GET)) {
			// Sending an ETag shouldn't really be necessary at /edition/ with 
			// no parameters, but the publication validator currently queries 
			// that URL and expects an ETag.
			lp_etag_header('delivery_count_error', gmdate('Y-m-d\TH:i:s.0+00:00'));
			lp_fatal_error(
				"Requests for /edition/ need a delivery_count, eg '?delivery_count=0'",
				"Make sure 'send_delivery_count' is set to true in meta.json"	
			);
		}
	}
}


/**
 * Gets rid of the timezone part of a date string.
 * @param string $time eg, "2013-07-31T19:20:30.45+01:00".
 * @return string eg "2013-07-31T19:20:30.45".
 */
function lp_local_time($time) {
	return substr($time, 0, -6);
}


/**
 * Get the day of the week from a time_string.
 * @param string $time_string eg, "2013-07-31T19:20:30.45+01:00".
 * @return string Lowercased weekday name. eg 'monday'.
 */
function lp_day_of_week($time_string) {
	// We don't care about the timezone, so get rid of it.
	$time_string = lp_local_time($time_string);

	return strtolower(date('l', strtotime($time_string)));
}


/**
 * Send an ETag header, based on a string and a time.
 * @param mixed $id Probably either an edition number (eg, 1) or 'sample'.
 * @param string $time eg, "2013-07-31T19:20:30.45+01:00".
 */
function lp_etag_header($id, $time) {
	header('ETag: "' . md5($id . date('dmY', strtotime(lp_local_time($time)))) . '"');
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
 * Generate the path to the edition file we want to display.
 *
 * @param int $edition_number The 1-based number of the edition we're displaying.
 * @param string $local_delivery_time eg, "2013-07-31T19:20:30.45+01:00".
 * @returns mixed Either a number (204 or 410) if there's no file for this edition/date, or a hash.
 *		The hash will have a `type` element of either `image' or 'file'.
 *		`image` hashes will have a `url` element.
 *		`file` hashes will have a `file` element.
 */
function lp_get_edition_file_path($edition_number, $local_delivery_time) {
	global $PUBLICATION_TYPE;

	if ($PUBLICATION_TYPE == 'numbered') {
		if (file_exists(lp_directory_path()."editions/$edition_number.png")) {
			return array(
				'type' => 'image',
				'url' => "http://".$_SERVER['SERVER_NAME'].lp_directory_url()."editions/$edition_number.png"
			);

		} else if (file_exists(lp_directory_path()."editions/$edition_number.html")) {
			return array(
				'type' => 'file',
				'file' => lp_directory_path()."editions/$edition_number.html"
			);

		# We'll be nice and make it work for PHP files too:
		} else if (file_exists(lp_directory_path()."editions/$edition_number.php")) {
			return array(
				'type' => 'file',
				'file' => lp_directory_path()."editions/$edition_number.php"
			);

		# If there's a dynamic file to handle all days, use that
		} else if (file_exists(lp_directory_path()."editions/all.php")) {
			return array(
				'type' => 'file',
				'file' => lp_directory_path()."editions/all.php"
			);

		} else {
			return 410;
		}

	} else {
		// 'dated' publications.

		$date = date('Y-m-d', strtotime(lp_local_time($local_delivery_time)));

		if (file_exists(lp_directory_path()."editions/end.html")) {
			return 410;

		} else if (file_exists(lp_directory_path()."editions/$date.png")) {
			return array(
				'type' => 'image',
				'url' => "http://".$_SERVER['SERVER_NAME'].lp_directory_url()."editions/$date.png"
			);

		} else if (file_exists(lp_directory_path()."editions/$date.html")) {
			return array(
				'type' => 'file',
				'file' => lp_directory_path()."editions/$date.html"
			);
		} else {
			return 204;
		}
	}
}


/**
 * Displays an error message, ends the HTML, and finishes script execution.
 *
 * @param string $message The error message to display.
 * @param string $explanation An optional extra bit of helpful text.
 */
function lp_fatal_error($message, $explanation=FALSE) {
	?>
	<p><strong><?php echo $message; ?></strong></p>
<?php
	if ($explanation !== FALSE) {
		?>
		<p><?php echo $explanation; ?></p>
<?php
	}
	lp_page_footer();
	exit;
}


/**
 * For 4.3.0 <= PHP <= 5.4.0
 * PHP >= 5.4 already has a http_response_code() function.
 */
if ( ! function_exists('http_response_code')) {
	function http_response_code($newcode = NULL) {
		static $code = 200;
		if ($newcode !== NULL) {
			header('X-PHP-Response-Code: '.$newcode, true, $newcode);
			if ( ! headers_sent()) {
				$code = $newcode;
			}
		}
		return $code;
	}
}

?>
