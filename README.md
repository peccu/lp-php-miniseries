# PHP Little Printer partwork example

This is a PHP site to make it easy to create a [Little Printer](http://bergcloud.com/littleprinter/) partwork publication: one that delivers your content to subscribers.

In addition to this code you will need:

* A webserver that you can upload PHP files to (most shared hosting will be fine).
* An account on the [BERG Cloud Remote](http://remote.bergcloud.com/) website.
* Some content for your publications: a series of small black-and-white images and/or some text in small daily chunks.

A Little Printer isn't required, although access to one is useful to check that output looks correct.

We'll first look at the basic configuration and setup, and then at how to create a publication that shows an image every day. Then we'll look at some further variations on this.

## Setup

Download the site's code from https://github.com/bergcloud/lp-php-partwork . If you're comfortable with Git, you can clone it, otherwise, click the "Download ZIP" button. After unzipping the download you should have a folder called `lp-php-partwork-master`.

### meta.json

You'll need to edit one file in this folder: `meta.json`. Open this in a text editor, eg TextEdit on a Mac or Notepad on Windows. For the moment you'll only need to change two lines:

    "name": "YOUR PUBLICATION TITLE HERE",
    "description": "YOUR DESCRIPTION HERE",

Replace those capitalised words with the title and description of your publication. You can change them again later. Be sure to keep the "quote" marks. Save the file.

### Upload the files

Now upload the folder to your webserver. You can rename the folder to something meaningful; subscribers to your publication will never see it. We'll call our folder `littleprinter-pub` and put it at the top of our imaginary website.

You should now be able to visit the `/sample/` directory in your web browser:

	http://www.example.com/littleprinter-pub/sample/

If everything's working you should see a black-and-white image that reads "Edition 1". You can also try some other URLs, which would generate each daily edition:

	http://www.example.com/littleprinter-pub/edition/?delivery_count=0
	http://www.example.com/littleprinter-pub/edition/?delivery_count=1
	http://www.example.com/littleprinter-pub/edition/?delivery_count=2
	http://www.example.com/littleprinter-pub/edition/?delivery_count=3

Note that the `delivery_count` value starts at 0 for the first edition.

### Set up your BERG Cloud publication

Now sign in to [BERG Cloud Remote](http://remote.bergcloud.com/) -- create an account if you don't have one already.

Once you're logged in then go to the [BERG Cloud Developers](http://remote.bergcloud.com/developers/) website.

Click "Your publications" in the links at the top, and then the "New Publication" button.

Enter the URL of your publication, check the checkbox (after you've read all of Terms of Service of course...) and submit the form. For our publication we'll enter this URL:

    http://www.example.com/littleprinter-pub/

It should now fetch information from the `meta.json` file you edited earlier, and will list your publication. If the site says it can't find the `meta.json` file, make sure you've got the correct URL. If you visit that URL in your browser you should see a message saying:

> This is a Little Printer publication.

If you don't, you've probably got a typo in the URL.

Otherwise, we're ready to go, and you can start putting your content in. Note that your publication is currently in "developer test" mode, so no one else knows it exists.


## A daily publication of images

As a first example, we'll make a publication that contains images. Every day the subscriber will receive a single image. You can see some examples of this already, such as [Stick & Spell](http://remote.bergcloud.com/publications/170) and [What Am I?](http://remote.bergcloud.com/publications/167).

Initially there are four example files in the `/editions/` directory:

	1.png
	2.png
	3.html
	4.png

As this suggests, you create a single image for each daily edition of your publication. (There's also a `.html` file in there; we'll come on to those soon.)

Each image should be 384 pixels wide, PNG format, and black-and-white (no greys). There's some more about this kind of thing in the [Style Guide](http://remote.bergcloud.com/developers/style_guide/).

So, delete those four example files, and put your numbered images in their place. You can look at the URLs we tried earlier to see if everything's OK, eg:

	http://www.example.com/littleprinter-pub/edition/?delivery_count=0
	http://www.example.com/littleprinter-pub/edition/?delivery_count=1

should show the files `1.png` and `2.png`.

If it's looking OK, and you have a Little Printer, you should be able to subscribe to your publication and the first edition should appear at the next scheduled time.

Once a subscriber has seen all of the available editions for your publication then they'll automatically be unsubscribed. They can subscribe again to start at the beginning.

We're nearly done, with only two small things to go before we go live -- an icon and a sample.

### Add a publication icon

If you view your publication in BERG Cloud Remote you'll see it has an Icon which is a black circle with a white centre. This is the `icon.png` file which is in your publication's folder.

You can either edit this or create a new one from scratch (but keep it the same size), and then upload it to your server to replace the old one at:

	http://www.example.com/littleprinter-pub/icon.png

Then view your publication in BERG Cloud Remote and click the "Edit" link. Next to the plain example icon is a button that will let you "Reload from your server". Click this... you might have to refresh the page again, but your new icon should appear.


## Update sample

You also need to create a sample, to show people what they're subscribing to. On the same "Edit" page, click the "Create Sample" button. You may have to wait a few minutes, but the first of your edition images, `1.png` should soon appear. (You can change which image is used as a sample; see below.)


## Go live!

Once you've got all of your edition images in place, and you're ready for people to subscribe, go to your publication's BERG Cloud Remote "Edit" page, set the status to "live" and click the "Update status" button. That's it!


## Using HTML files

## Adding headers and footers

## Changing styles

## Changing delivery days




## Changing sample output
