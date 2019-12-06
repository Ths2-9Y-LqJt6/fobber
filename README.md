# fobber

## Overview

Log POST results to a text file.  Using AJAX, show most recent 10 POSTs.

## History

I wanted a way to show recent instances of members of my hackerspace
unlocking the front door with their fob. The security system does a webhook
POST to this software.  The software then saves the POST to a file.  Any
GET requests grab the last N lines (default 6) from the log file and 
show this list, along with a logo.

## Install

`git clone` this repo into a web accessible directory.  Ensure that
the directory is writable by the web user.  Copy the `config.dist.php`
to `config.php` and change any values you see fit:

```php
// where you want to save the file
$file = 'fobbers.txt';

// valid fields you accept
$requiredVars = array('user', 'handle', 'color');

// optionally include a path to logo
$logo = null;

// number of recent events to show
$eventCount = 6;
```

# Use

The assumption is that you're going to use the three fields we use
and that the `color` will be an HTML color, possibly two separated by
a comma. Have something do a `POST` with the values you defined
in `$requiredVars`.  Have a device, we use a Raspberry Pi attached
to big TV, show this web page.  Enjoy! 

If you have another use of the events on an external system,
you access JSON feed of events by doing a `GET` request to `/ajax.php`. 

## Development

Not much to say here, but I use a local PHP web server:

```bash
php -S localhost:8001
```

And then pull up a browser pointing at `localhost:8001` to test `GET` events. 

I use cURL to mimic fob `POST` events:

```bash
curl -d "user=foo2&handle=bar&color=#000,#fff" http://localhost:8001
```

Pull requests welcome!!