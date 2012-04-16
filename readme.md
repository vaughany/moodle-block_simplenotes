# Simple Notes for Moodle 2.x

A block which enables users to record and prioritise per-course and site-wide notes.

## Introduction

The Moodle 2 comments block gives the user the ability to record a comment against a course, and even participate in IM-style conversations with other users on that course (although that's not the block's intention). The purpose of Simple Notes is somewhat similar, but the 'comments' are private to the user, but can be seen across courses if required.

This is different to the comments block as the notes are private to the user, whereas comments are specific to a course.





## Three elements:

Title       (optional)
Note        (required)
Priority    1/critical, 2/high, 3/normal (default), 4/low

## Instance Options (no global settings)

'Turn on and off flags' ability. :DONE
Chop note text after n characters :DONE
    'n' = list :DONE
Short or long date type :DONE

## Operation:

* There's a body for notes and a title. Title is optional.
* If the body is over a certain length, chop to n characters and add '...' onto the end.
 * If the body text is chopped, allow the whole message to appear as 'mouse-over' hover text when the mouse is placed over the text.

## Notes:

HTML not allowed: all tags are now escaped into their entities.
Line breaks are now preserved.
Don't forget to produce a install.xml file as well as the mysql.sql file.
If user is using notes block for the first time, add in some sample notes to show
    1. no title, 2. trim function and 3. hover effect 4. line breaks

## To do?

'this course', 'this course and general' and 'all' notes ability.
Moodle logging add/edit/delete I reckon
consider limiting the number of notes returned if there is a lot, or different pages (ajax?)
See all your notes or just notes for this course.
Use the built-in javascript to click-open an alert with the note in it if it's long+trimmed?
Ensure save buttons in forms state the right sorrt of thing: save new, save edits etc

## Errors:

For some reason, doesn't like being the first block in the right-hand column!