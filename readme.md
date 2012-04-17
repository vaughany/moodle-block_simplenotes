# Simple Notes for Moodle 2.x

A block and to-do list which enables users to record and prioritise per-course notes/tasks.

## Introduction

The purpose of Simple Notes block is somewhat similar to the Comments block, but Notes are private to the user. The user is able to prioritise Notes and sort their notes in a variety of ways.

Simple Notes block is different to the Comments block as the notes are private to the user, whereas comments are specific to a course.

## Licence

Simple Notes block for Moodle 2.x, copyright &copy; 2009-2012 Paul Vaughan

This program is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program.  If not, see <http://www.gnu.org/licenses/>.

## Purpose

I wanted a to-do list somewhere accessible for a long time. Back in 2009 when I started on the 1.9 version, there were no free to-do list type websites with smartphone app integration, so I wrote a block for Moodle instead. For various reasons the 1.9 version was never finished to my satisfaction so I'm amending that issue now.

You can add a note (unlimited length, but can be shown trimmed) with an optional title. Notes are defaulted to a 'normal' priority, but there are 'low', 'high' and 'critical' options too. (Notes flagged as critical are shown differently.)

More information is available at the [project's GitHub page](https://github.com/vaughany/moodle-block_simplenotes).

## Installation

Installation is a matter of copying files to the correct locations within your Moodle installation, but it is always wise to test new plugins in a sandbox environment first, and have the ability to roll back changes.

Download the archive and extract the files, or [clone the repository from GitHub](https://github.com/vaughany/moodle-block_simplenotes). You should see the following files and structure:

    simplenotes/
    |-- block_simplenotes.php
    |-- db
    |   `-- install.xml
    |-- edit_form.php
    |-- img
    |   `-- 0.png
    |   `-- 1.png
    |   `-- 2.png
    |   `-- 3.png
    |   `-- 4.png
    |   `-- add.png
    |   `-- delete.png
    |   `-- edit.png
    |   `-- help.png
    |   `-- info.png
    |   `-- trim.png
    |   `-- untrim.png
    |-- lang
    |   `-- en
    |       `-- block_simplenotes.php
    |-- noteadd.php
    |-- notedelete.php
    |-- noteedit.php
    |-- readme.md
    |-- styles.css
    `-- version.php

Copy the 'simplenotes' folder into your Moodle installation's blocks folder.

Log in to your Moodle as Admin and click on Notifications on the Admin menu.

The block should successfully install. If you receive any error messages, please [raise an issue on GitHub](https://github.com/vaughany/moodle-block_simplenotes/issues).

Add the block to a page. The block is able to be placed anywhere within Moodle, however is only shown to logged-in users.

## Use

When the block has been added to a course (just once), you can add a note by clicking the icon or the text link. This brings up a form whereby an optional title, the note itself (required) and a priority (defaults to 'normal') can be chosen. 

> **Note:** Plain text only: no HTML.

Click Save to save the note and return the the course.

The block will now show the note according to the default configuration options (see Instance Configuration, below). A coloured flag next to the note gives a visual cue as to the note's priority setting:

* **Green**: *low* priority
* **Yellow**: *normal* priority
* **Orange**: *high* priority
* **Red**: *critical* priority

> **Note:** Notes with *critical* priority will be highlighted in the block, and the text coloured red.

Each note has two icons directly next to it: one to edit the note, the other to delete it (with a confirmation screen, just in case). Icons to add another note are shown at the top and bottom of the block.

### Notes on adding a title

* Plain text only: HTML is stripped out completely.

### Notes on adding a note

* HTML is preserved exactly as written: if you write &lt;hr /&gt;, that's what you'll get on-screen, not a horizontal line.
* Line breaks are preserved.



## Global Configuration

This block has none.

## Instance Configuration

This block has quite a lot!  I have tried to choose the best defaults, however there is a lot of choice about how the notes are displayed, ordered, and so on.

### Display priority icons next to notes?

Defaults to Nes. Change to No to remove the coloured flags next to notes. (All note priorities except *critical* will look identical.)

### Trim note text after 'n' characters?

You can store a tonne of text in a note (if you want to!) but you don't necessarily want to see it all in the block. As default, notes over a certain length are trimmed. (If a note is trimmed, simply hover your pointer over the trimmed note and the full text will appear in a tool tip.)

### ...choose number of characters to trim after:

The number of characters to show before trimming takes place. The default is 500 characters. (For reference, those last two sentences were just less than 100 characters.)

### Choose a date/time format:

Date and time formats can be very subjective: everyone has their favourite. Personally I dislike the native Moodle ones and create my own, but to show consistency, this menu provides all the available Moodle-native date/time formats as well as six of my own. You can be as vague as **17 April** to as specific as **Tuesday 17/04/2012, 11:27pm**. 

> **Note:** Wordpress has a feature whereby users can use their own date/time formatting string (with a link to [PHP's date function docs](http://php.net/manual/en/function.date.php)). That's probably a little excessive here, but I like the flexibility.

### Choose what date/time options appear at the foot of each note:

The footer of each note can display four different options:

* The date/time (according to the selection made in the previous setting)
* How many minutes/hours/days ago the comment was added
* Both of the above (quite nice, but can take up too much room)
* Nothing at all!

### Choose which notes to show, and in what order:

This option is also really subjective: how to display the notes to make them most useful to the user. The options are descriptive (wordy!) but basically boil down to:

* descending date order (recent first) (default)
* ascending date order (oldest first)
* descending priority order (highest priority first)
* ascending priority order (lowest priority first)

### Choose the maximum number of notes to show:

This value is the maximum number of notes which will be shown in the block. The number of notes is basically unlimited (have tested with 5,000), but you probably don't want them all on-screen at the same time. The default is 10. If you have more notes than are being displayed, either change this setting or delete some notes, or both.

The block footer contains a short phrase along the lines of "Showing *x* of *y* notes". 

## Known Issues

None at this time, that I know of. :)

Should you find a bug, have an issue, feature request or new language pack, please [log an issue in the tracker](https://github.com/vaughany/moodle-block_simplenotes/issues) or fork the repo, fix the problem and submit a pull request.

## To do

In no particular order:

* Currently the block pulls notes from just the course you are on. The longer term plan is to pull notes from the current course first, then pull notes from other courses.
* Moodle logging: add/edit/delete.
* Click to pop-open a long, trimmed note.
* Ensure save buttons in forms state the right sorrt of thing: save new, save edits etc
* Markdown?
* Sample notes auto-added on first use to demo features:
  * priorities
  * no title
  * trim function
  * hover effect
  * line breaks
* Ensure HTML stripping and entity replacement is happening exactly as it should. (It isn't.)

## Acknowledgements

Icons used in this block came from <http://www.famfamfam.com/lab/icons/silk/>

The 'time ago' script came from <http://www.zachstronaut.com/posts/2009/01/20/php-relative-date-time-string.html> (There were a lot of choices, this one was the simplest and most concise.)

**April 17th, 2012**

* Version 2.0 for Moodle 2.x
* Build 2012041700

Still in beta at this time.
