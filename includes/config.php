<?php

/**
 * Should be either 'numbered' or 'dated'.
 */
$PUBLICATION_TYPE = 'numbered';


/**
 * The number of the image or html file to use for the sample.
 * Starts at 1.
 * (Or, if $PUBLICATION_TYPE = 'dated', this should be a date
 * in the format 'YYYY-MM-DD', eg, '2013-12-31'.)
 */
$EDITION_FOR_SAMPLE = 1;


/**
 * The days of the week deliveries occur on.
 * Only used for the 'numbered' $PUBLICATION_TYPE.
 * Comment out, or delete, any days on which there should be no delivery.
 */
$DELIVERY_DAYS = array(
	'monday',
	'tuesday',
	'wednesday',
	'thursday',
	'friday',
	'saturday',
	'sunday',
);

?>
