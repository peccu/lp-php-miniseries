# PHP Little Printer miniseries template

v1.2.3

This is a PHP website to make it easy to create a [Little Printer](http://bergcloud.com/littleprinter/) miniseries publication: one that delivers your content to subscribers on a regular basis. For example, an image every day for 30 days, or a short story twice a week, or specific content to all subscribers on certain dates.

In addition to this code you will need:

* A webserver that you can upload PHP files to (most shared hosting accounts will be fine). It needs to run PHP v5.1 or greater (see "Setup" below).
* An account on the [BERG Cloud Remote](http://remote.bergcloud.com/) website.
* Some content for your publication: a series of small black-and-white images and/or some text in small daily chunks.

A Little Printer isn't required, although access to one is useful to check that output looks how you want it.

To keep up-to-date with changes to this code, follow [@lpdevelopers](https://twitter.com/lpdevelopers) on Twitter.

Release notes for this and past versions are on the [GitHub Releases page](https://github.com/bergcloud/lp-php-miniseries/releases).

We'll first look at the basic configuration and setup, and then at how to create a publication that shows an image every day. Then we'll look at variations on this basic publication, such as only delivering on certain days or creating publications containing HTML text. Finally we'll look at how to take things even further if you're comfortable with writing some PHP.


###################################################################################
## Setup ##########################################################################

If you don't know which version of PHP your webserver is using, then create a new text file called something like `test.php`. In that put this:

    <?php phpinfo(); ?>

Upload that file to your webserver and then view it in a browser. You should see which version of PHP is running. You'll need at least version 5.1.

Before adding your own content to the publication we'll set it up using the dummy content provided.

Download the publication's code from https://github.com/bergcloud/lp-php-miniseries . If you're comfortable with Git, you can clone the files, otherwise, click the "Download ZIP" button. After unzipping the download you should have a folder called something like `lp-php-miniseries-master`.


### meta.json #####################################################################

You'll need to edit one file in this folder: `meta.json`. This is the file that tells BERG Cloud information about your publication. Open it in a text editor, eg TextEdit on a Mac or Notepad on Windows. For the moment you'll only need to change two lines:

    "name": "Your Publication Title Here",
    "description": "Your Description Here",

Replace those capitalised words with the title and description of your publication. You can change them again later if needs be. Be sure to keep the "quote" marks and commas in place. Save the file.


### Upload the files ##############################################################

Now upload the folder to your webserver. You can rename the folder to something meaningful, but subscribers to your publication will never see it, so call it whatever makes sense to you. Throughout this example we'll call our folder `littleprinter-pub` and put it at the top of our imaginary website. It will be at this URL:

	http://www.example.com/littleprinter-pub/

This is called the "Endpoint" for the publication.

You should now be able to visit the `/sample/` folder with your web browser. Our example would be at this URL:

	http://www.example.com/littleprinter-pub/sample/

If everything's working you should see a black-and-white image that reads "Edition 1". You can also try some other URLs; these URLs would generate each daily edition of the publication:

	http://www.example.com/littleprinter-pub/edition/?delivery_count=0
	http://www.example.com/littleprinter-pub/edition/?delivery_count=1
	http://www.example.com/littleprinter-pub/edition/?delivery_count=2
	http://www.example.com/littleprinter-pub/edition/?delivery_count=3

Note that the `delivery_count` value starts at 0 for the first edition.


### Create your BERG Cloud publication ############################################

Now sign in to [BERG Cloud Remote](http://remote.bergcloud.com/) -- create an account if you don't have one already.

Once you're logged in then go to the [BERG Cloud Developers](http://remote.bergcloud.com/developers/) website.

Click "Your publications" in the links at the top, and then the "New Publication" button.

Enter the Endpoint URL of your publication, check the checkbox (after you've read all of Terms of Service of course...) and submit the form. For our publication we'd enter this URL:

    http://www.example.com/littleprinter-pub/

BERG Cloud should now fetch information from the `meta.json` file you edited earlier, and will list your publication. If the site says it can't find the `meta.json` file, make sure you've used the correct URL. If you visit that URL in your browser you should see a message saying:

> This is a Little Printer publication.

If you don't, you've probably got a typo in the URL.

Otherwise, we're ready to go, and you can start putting your content in (read on...). Note that your publication is currently in "developer test" mode, so no one else knows it exists.


###################################################################################
## A daily publication of images ##################################################

As a first example, we'll make a publication that contains images. Every day the subscriber will receive a single image. You can see some examples of this, such as [Stick & Spell](http://remote.bergcloud.com/publications/170) and [What Am I?](http://remote.bergcloud.com/publications/167).

Initially there are four example files in the `/editions/` folder:

	littleprinter-pub/editions/1.png
	littleprinter-pub/editions/2.png
	littleprinter-pub/editions/3.html
	littleprinter-pub/editions/4.png

As this suggests, you create a single image for each daily edition of your publication. Each file should be named with only a number, followed by the file extension, which in this case is `.png`. (There's also a `.html` file in there; we'll come on to those soon.) Be sure to use only a plain number for the file names. These are OK:

	1.png
	10.png
	387968.png

But these won't work:

	01.png
	10_funny.png
	part-387968.png

Each image should be 384 pixels wide, PNG format, and black-and-white (no greys). You can read more about these details in the [Style Guide](http://remote.bergcloud.com/developers/style_guide/).

So, delete all four of those example files, and replace them with your numbered images. (Don't forget to upload them to your webserver!) There's no need to stop at 4; use as many as you like, numbered sequentially. You can look at the URLs we tried earlier to see if everything's OK. For example, these URLs

	http://www.example.com/littleprinter-pub/edition/?delivery_count=0
	http://www.example.com/littleprinter-pub/edition/?delivery_count=1
	...

should show your new files `1.png` and `2.png`.

If you have a Little Printer you can also see how these look by entering one of the URLs in the Rapid Prototyper in the "Tools" section of the BERG Cloud Developers site.

If everything looks OK, and you do have a Little Printer, you can subscribe to your publication and the first edition should appear at the next scheduled time. Only you can subscribe to your publication while it's in "developer test" mode.

Once a subscriber has received all of the available editions for your publication then BERG Cloud will automatically unsubscribe them. The subscriber can subscribe again to start at the beginning.

We're nearly done, with only two small things to go before we go live -- an icon and a sample.


### Add a publication icon ########################################################

If you view your publication on the BERG Cloud Developers site you'll see it has an Icon which is a black circle with a white centre. This is the `icon.png` file which is in your publication's folder.

You can either edit this or create a new one from scratch (but keep it the same size), and then upload it to your server to replace the old one at:

	http://www.example.com/littleprinter-pub/icon.png

Next, view your publication on the BERG Cloud Developers site and click the "Edit" link. Next to the plain example icon is a button that will let you "Reload from your server". Click this... you might have to refresh the page again, but your new icon should appear.


### Update sample #################################################################

You also need to create a sample, to show people what they're subscribing to. On the same "Edit" page, click the "Create Sample" button. You may have to wait a few minutes, but the first of your edition images, `1.png` should soon appear. (You can change which image is used as a sample; see below.)


### Go live! ######################################################################

Once you've got all of your edition images in place, and you're ready for people to subscribe, go to your publication's BERG Cloud Developers "Edit" page.

There is a series of things to check before changing your publication's status, but if you've got this far then most of them should already be fine. The `lp-php-miniseries` code takes care of the "ETags" for you (these help make requests from BERG Cloud to your publication more efficient). The `validate_config` and `configure` points are only relevant to publications that are more complex than a miniseries. To double-check everything is OK, then enter your "Endpoint" URL into the [BERG Cloud Remote Validator](http://remote.bergcloud.com/developers/tools/validations).

Once you're ready, on the "Edit" page, set the status of your publication to "live" and click the "Update status" button. 

That's it, you're published!

We'll now look at some variations on this basic publication.


###################################################################################
## Using HTML files ###############################################################

You might have an idea for a publication that's better displayed as text, rather than images. In that case you can use HTML files instead of PNG files. You would put each of these files in the `/editions/` folder, like this:

	littleprinter-pub/editions/1.html
	littleprinter-pub/editions/2.html
	littleprinter-pub/editions/3.html
	littleprinter-pub/editions/4.html
	...

Each file can contain whatever HTML you like, although it will all be rendered 384 pixels wide and in black-and-white. It's possible to use PNG images on some days and HTML files on others, so your files might look like this:

	littleprinter-pub/editions/1.html
	littleprinter-pub/editions/2.png
	littleprinter-pub/editions/3.png
	littleprinter-pub/editions/4.html
	...

If you're handy with PHP, and have more complicated or dynamic content, you can use PHP files instead of (or as well as) HTML files. Just name them similarly: `1.php`, `2.php`, etc.


###################################################################################
## Adding headers and footers #####################################################

Whether you're using images or HTML files you might want to have a common header or footer appear on every edition. You could manually put these into each image or HTML file, but you can also do it in one place.

Inside the `/includes/` folder there are five files:

	littleprinter-pub/includes/config.php
	littleprinter-pub/includes/footer.php
	littleprinter-pub/includes/functions.php
	littleprinter-pub/includes/header.php
	littleprinter-pub/includes/index.php

The contents of two of these, `header.php` and `footer.php`, will be displayed at the top and bottom of every one of your editions. By default they have nothing in them except an invisible HTML comment. You can put any HTML you like in them, and this will appear on all of your editions, whether they're images, HTML or PHP.

Visit some of your edition URLs to check if it's looking how you want:

	http://www.example.com/littleprinter-pub/edition/?delivery_count=0
	http://www.example.com/littleprinter-pub/edition/?delivery_count=1
	...


###################################################################################
## Changing CSS styles ############################################################

If you're using HTML files and/or headers and footers then you may want to add to the very minimal default CSS styles. These are in `littleprinter-pub/style.css`. You can read more about the available fonts and appropriate sizes in the [Style Guide](http://remote.bergcloud.com/developers/style_guide/fonts).


###################################################################################
## Changing sample output #########################################################

By default the sample that potential subscribers see on BERG Cloud Remote is the first edition of your publication. If you'd rather use a different edition then open the `littleprinter-pub/includes/config.php` file in your text editor and change this line:

	$EDITION_FOR_SAMPLE = 1;

Replace the `1` with whatever edition number you want to use as your sample. Once you've uploaded the saved `config.php` to your server, you'll need to go to the "Edit" page for your publication in BERG Cloud Developers site and click the "Regnerate" button next to your existing sample.


###################################################################################
## Changing delivery days #########################################################

By default your publication will be delivered every day of the week. You might want it to only arrive on weekdays, or only weekends, or only on Fridays, or...

To change the delivery days, open the `littleprinter-pub/includes/config.php` file and you can see this code:

	$DELIVERY_DAYS = array(
		'monday',
		'tuesday',
		'wednesday',
		'thursday',
		'friday',
		'saturday',
		'sunday',
	);

Every day that's listed there is a day of the week that subscribers will receive an edition. You can delete lines, or comment them out with PHP comment marks (`//`), to change the days. For example, to deliver an edition every Monday, Wednesday and Friday, you would do this:

	$DELIVERY_DAYS = array(
		'monday',
		//'tuesday',
		'wednesday',
		//'thursday',
		'friday',
		//'saturday',
		//'sunday',
	);

Tuesday, Thursday, Saturday and Sunday are commented out. So someone subscribing on Monday, with their first delivery that day, would get edition 1 on Monday, 2 on Wednesady, 3 on Friday, 4 the following Monday, etc.

You'll need to upload `config.php` to your server, but there's also one more step. In the `littleprinter-pub/meta.json` file is a line like this:

	"delivered_on": "every day",

This doesn't affect how the publication works, but is used to describe the publication to potential subscribers. You should change `"every day"` so that it makes sense for your new delivery days. Whatever you write will appear after the words "Delivered on" on the BERG Cloud Remote website. So, for our example above we'd change that line to this:

	"delivered_on": "Mondays, Wednesdays and Fridays",

Subscribers will then see:

> Delivered on Mondays, Wednesdays and Fridays

Once that's done, upload `config.php` and `meta.json` to your server. Then, on your publication's BERG Cloud Developers "Edit" page, click the "Reload from your server" button in the "Metadata" section.

There are two things to be aware of with delivery days...

First, the days are based on the timezone of the subscribing Little Printers. Which means that people around the world will reach your delivery day(s) before or after you do. Someone who subscribes to your publication in New Zealand, and has it delivered at 1am, will receive a Thursdays-only publication when it's still 2pm on Wednesday in London, or 6am on Wednesday morning in San Francisco.

Second, if you try to access the page for an edition directly, and it's currently a day on which there's no delivery, then you'll see no content. In fact, the page won't visibly refresh at all; whatever page you were already viewing will remain there. The way around this is to convince the publication that it's a different day. Previously we've tried out our editions by using a URL like this:

	http://www.example.com/littleprinter-pub/edition/?delivery_count=0

In reality, BERG Cloud will also send the local time wherever the subscribing Little Printer is located. So the URLs will have an extra bit on the end, and look more like this:

	http://www.example.com/littleprinter-pub/edition/?delivery_count=0&delivery_time=2013-07-22T12%3A00%3A00%2B00%3A00

It's a bit of a mouthful but buried in there is the date, in this case, `2013-07-22`. You can add that extra part to your own URL, and change the year-month-day to be a day of the week on which there should be a delivery. Don't worry if you get it wrong; the worst that will happen is you (personally, right now) see no edition when there should be one, or vice versa.


###################################################################################
## Dated editions #################################################################

So far our publication has the same beginning and set of editions for every subscriber. No matter when someone subscribes, their Little Printer will first print out edition 1, followed by edition 2, etc.

An alternative would be to have editions that are specific to particular dates, and all subscribers receive the same content on the same date. New subscribers will never see any earlier editions but will receive subsequent editions on the same days as existing subscribers. Editions don't need to come out every day -- you choose how frequently, and on which days, they appear.

To make a publication work like this we need to do a couple of things differently.

First, open the `littleprinter-pub/includes/config.php` file and change this line:

	$PUBLICATION_TYPE = 'numbered';

to this:

	$PUBLICATION_TYPE = 'dated';

Don't forget to upload `config.php` to your server.

This change ensures we deliver the correct files as our editions. Previously all our files in `/editions/` were numbered sequentially. Any files named like this will now be ignored, and instead the code will look for files named with dates in "YYYY-MM-DD" format:

	littleprinter-pub/editions/2013-09-17.html
	littleprinter-pub/editions/2013-09-18.png
	littleprinter-pub/editions/2013-09-30.png
	littleprinter-pub/editions/2013-11-12.php

As before, you can mix HTML, PNG and PHP files, or only use one kind.

A file will be delivered to all current subscribers on the date specified. The files do not need to be dated sequentially; you can leave gaps between them and nothing will be delivered on those "missing" days. Note that most subscribers will probably be in a timezone different to your own, so be sure to prepare content in time for the first people in the world to reach your chosen date.

Before this works, you will also need to change this line in `littleprinter-pub/includes/config.php`:

	$EDITION_FOR_SAMPLE = 1;

Change it so that it has the same date as one of your new files. It should look something like this (note the quotes around the date):

	$EDITION_FOR_SAMPLE = "2013-09-30";

You can check this is working (once you've uploaded it to your server again) by visiting the `/sample/` folder with your web browser. For our example, it's at this URL:

	http://www.example.com/littleprinter-pub/sample/

You can check your editions by using a URL with the correct `local_delivery_time` in the URL. For example, to view the first edition from our directory listing above, we'd use a URL like this:

	http://www.example.com/littleprinter-pub/edition/?delivery_count=1&local_delivery_time=2013-09-17T00:00:00+00:00

It's a bit of a mouthful, but you can see the date, `2013-09-17` in there, which matches the `2013-09-17.html` file we have in our `/editions/` directory.

While you're in the `config.php` file, we should point out that the `$DELIVERY_DAYS` setting is ignored for publications of this type. The editions are delivered on any day on which you have dated files available.

You might also want to edit the description of your delivery schedule in the `littleprinter-pub/meta.json` file. This is discussed above in more detail, but you should change the line that's like this:

	"delivered_on": "every day",

so that it makes more sense for your new customised schedule. Maybe something like "every other Thursday", "once or twice a week", or "on days that Ipswich Town F.C. are playing". And then, of course, upload the file to your server.

Finally... what do you do when you've ended a dated publication, or just had enough? With a conventionally-numbered publication subscribers are automatically unsubscribed when there are no more edition files remaining. But with a dated publication there's no automatic way to know when, or if, it's finished.

If you decide the publication is over, and all subscribers should be automatically unsubscribed, then add a text file named `end.html` to your `/editions/` directory, like this:

	littleprinter-pub/editions/2013-09-17.html
	littleprinter-pub/editions/2013-09-18.png
	littleprinter-pub/editions/2013-09-30.png
	littleprinter-pub/editions/2013-11-12.php
	littleprinter-pub/editions/end.html

The file doesn't need to have anything in it; it's empty. If this file is present, all subscribers will be unsubscribed. So be careful! You don't have to use this feature, but it's there if you need it.


###################################################################################
## Creating infinite, numbered, publications ######################################

We can already create a wide variety of publications without writing any code. But if you're comfortable with writing a little PHP, then it's possible to take things further.

As a first step, we can make a numbered publication that lasts indefinitely, but without having to create an infinite amount of images or files in the `/editions/` folder.

If the `$PUBLICATION_TYPE` setting in `config.php` is set to `numbered`, but there is no file available for a particular edition number, then the code checks for the presence of an `/editions/all.php` file. If this exists, that file is used for every other edition. For example, if we have these edition files

	littleprinter-pub/editions/1.html
	littleprinter-pub/editions/2.png
	littleprinter-pub/editions/3.png
	littleprinter-pub/editions/all.php

then the first three editions will display the HTML and two PNG files as normal. All subsequent requests for editions will use the `all.php` file, and the publication will never end.

In reality you probably wouldn't mix these methods, and your `/editions/` folder would look like this:

	littleprinter-pub/editions/all.php

Within `all.php` you can do whatever you like to output content for each edition. Anything in `/includes/header.php` or `/includes/footer.php` will, as usual, appear before and after your content. Requests are still filtered depending on your `$DELIVERY_DAYS`, so `all.php` will only be reached on days for which content should appear.

You have access to two important variables:

* `$edition_number` will be an integer, counting up from 1 for each edition.
* `$local_delivery_time` will be a string representing the current time at the requesting Little Printer, for example `2013-07-31T19:20:30-07:00`.

(Alternatively, you could access `$_GET['delivery_count']` (which is zero-based) and `$_GET['local_delivery_time']` directly if you prefer.)

So, if your `/editions/all.php` file looked like this

	<p>Welcome to edition #<?php echo $edition_number ?>!</p>

	<?php
	$parsed = date_parse($local_delivery_time);
	$timestamp = mktime($parsed['hour'], 0, 0, $parsed['month'], $parsed['day'], $parsed['year'], (int)$parsed['is_dst']);
	$weekday = date('l', $timestamp);
	$tz_name = timezone_name_from_abbr('', (-1 * $parsed['zone'] * 60), false);
	?>

	<p>It is currently a <?php echo $weekday ?> and you're in the <?php echo $tz_name; ?> timezone.</p>

then the edition's output might contain:

> Welcome to edition #3!
>
> It is currently a Wednesday and you're in the America/Denver timezone. 

It's not the most interesting publication, but improving this is left as an exercise for the reader.

If you want your publication to end at some point, rather than to go on forever, then you will need to indicate to BERG Cloud when there are no more editions. When the value of `$edition_number` is higher than the number of editions you wish to publish, then use this PHP, rather than outputting any content:

		http_response_code(410);
		exit;

Be careful not to ouptput any HTML, including blank lines, before doing this. Subscribers will then be automatically unsubscribed, having reached the end of your publication.


###################################################################################
## Further development ############################################################

It is possible to use this code as a base on which to build a more complicated publication that involves customised content for different users. As a simple example, if you want users to enter their name when they subscribe, so you can display it in the publication, there are a few steps.

First, edit your publication's `meta.json` and change the `config` entry to this:

	"config": {
		"fields": [
			{
				"type": "text",
				"name": "firstname",
				"label": "Your first name"
			}
		]	
	}

This means that when users subscribe to your publication on BERG Cloud they will be shown a form field asking for their first name. Read more about `meta.json` on [BERG Cloud Developers](http://remote.bergcloud.com/developers/reference/metajson).

Second, we need to provide some validation for what the user enters. Validation requests come to a publication's `/validate_config/` URL, so we need to add a file at `/littleprinter-pub/validate_config/index.php` containing something like this:

	<?php
	$config = json_decode($_POST['config'], true);

	$errors = array();

	if ( ! isset($config['firstname']) || $config['firstname'] == '') {
		$errors[] = "Please enter your first name.";
	}

	if (empty($errors)) {
		$return = array('valid' => 'true');
	} else {
		$return = array('valid' => 'false', 'errors' => $errors);
	}

	header('Content-Type: application/json');
	die(json_encode($return));	
	?>

You can read more about config validation on [BERG Cloud Developers](http://remote.bergcloud.com/developers/reference/validate_config). All we're doing here is checking the submitted `firstname` field has something in it, and returning an error message if not. This message is displayed to the user, along with the form.

When BERG Cloud makes a request for an edition it will pass on this `firstname` field, along with the subscribed user's input, in the GET string. So in our `/editions/all.php` we could add this:

	<p>This edition is for <?php htmlentities($_GET['firstname']); ?>!</p>

However, this kind of customisation requires a further step, and one that requires tweaking the existing code. When BERG Cloud requests an edition, an [ETag](http://en.wikipedia.org/wiki/HTTP_ETag) header is returned by the publication which is unique for that particular content. If BERG Cloud receives a duplicate ETag it knows the content won't have changed, and can avoid fetching it multiple times.

With our regular miniseries we can return the same ETag for each edition, because an edition is always the same. (In fact, we've made the ETags change each day to avoid anything being cached for too long.) But when we start providing content that is unique to each user we need to change these generic ETags.

In `/includes/functions.php` find the `lp_display_page()` function, and this part of the code:

	if ($directory_name == 'edition') {
		$edition_number = (int) $_GET['delivery_count'] + 1;
		lp_etag_header($edition_number, $local_delivery_time);

You can see that we generate an ETag header using the edition number and the delivery time. When generating user-specific content we need to change this. Using our above example we could change the code to:

	if ($directory_name == 'edition') {
		$edition_number = (int) $_GET['delivery_count'] + 1;
		lp_etag_header($edition_number + $_GET['firstname'], $local_delivery_time);

Only the third line has changed: We've changed the ETag header so that, as well as `$edition_number`, it uses the `firstname` which is our only unique field. If we had other user-supplied fields we'd use them to ensure that an ETag was always specific to a particular edition's content.

As you can see, we're starting to reach the limits of this example code as it stands, and more complicated uses are likely to require further customisation. If you've understood things this far then maybe this is something you're ready for. Good luck!

