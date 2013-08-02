# PHP Little Printer miniseries example

This is a PHP site to make it easy to create a [Little Printer](http://bergcloud.com/littleprinter/) miniseries publication: one that delivers your content to subscribers on a regular basis. For example, an image every day for 30 days, or a short story twice a week.

In addition to this code you will need:

* A webserver that you can upload PHP files to (most shared hosting accounts will be fine).
* An account on the [BERG Cloud Remote](http://remote.bergcloud.com/) website.
* Some content for your publication: a series of small black-and-white images and/or some text in small daily chunks.

A Little Printer isn't required, although access to one is useful to check that output looks how you want it.

We'll first look at the basic configuration and setup, and then at how to create a publication that shows an image every day. Then we'll look at variations on this basic publication.


###################################################################################
## Setup ##########################################################################

Before adding your own content to the publication we'll set it up using the dummy content provided.

Download the publication's code from https://github.com/bergcloud/lp-php-miniseries . If you're comfortable with Git, you can clone the files, otherwise, click the "Download ZIP" button. After unzipping the download you should have a folder called something like `lp-php-miniseries-master`.


### meta.json #####################################################################

You'll need to edit one file in this folder: `meta.json`. This is the file that tells BERG Cloud information about your publication. Open it in a text editor, eg TextEdit on a Mac or Notepad on Windows. For the moment you'll only need to change two lines:

    "name": "YOUR PUBLICATION TITLE HERE",
    "description": "YOUR DESCRIPTION HERE",

Replace those capitalised words with the title and description of your publication. You can change them again later if needs be. Be sure to keep the "quote" marks and commas in place. Save the file.


### Upload the files ##############################################################

Now upload the folder to your webserver. You can rename the folder to something meaningful, but subscribers to your publication will never see it. We'll call our folder `littleprinter-pub` and put it at the top of our imaginary website. It will be at this URL:

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

	littleprinter-pub/edition/1.png
	littleprinter-pub/edition/2.png
	littleprinter-pub/edition/3.html
	littleprinter-pub/edition/4.png

As this suggests, you create a single image for each daily edition of your publication. (There's also a `.html` file in there; we'll come on to those soon.)

Each image should be 384 pixels wide, PNG format, and black-and-white (no greys). You can read more about these details in the [Style Guide](http://remote.bergcloud.com/developers/style_guide/).

So, delete those four example files, and replace them with your numbered images. There's no need to stop at 4; use as many as you like, numbered sequentially. You can look at the URLs we tried earlier to see if everything's OK. For example, these URLs

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

	littleprinter-pub/edition/1.html
	littleprinter-pub/edition/2.html
	littleprinter-pub/edition/3.html
	littleprinter-pub/edition/4.html
	...

Each file can contain whatever HTML you like, although it will all be rendered 384 pixels wide and in black-and-white. It's possible to use PNG images on some days and HTML files on others, so your files might look like this:

	littleprinter-pub/edition/1.html
	littleprinter-pub/edition/2.png
	littleprinter-pub/edition/3.png
	littleprinter-pub/edition/4.html
	...

If you're handy with PHP, and have more complicated or dynamic content, you can use PHP files instead of (or as well as) HTML files. Just name them similarly: `1.php`, `2.php`, etc.


###################################################################################
## Adding headers and footers #####################################################

Whether you're using images or HTML files you might want to have a common header or footer appear on every edition. You could manually put these into each image or HTML file, but you can also do it in one place.

Inside the `includes` folder there are five files:

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



