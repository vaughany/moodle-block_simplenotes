<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * SimpleNotes en language file.
 *
 * @package    block_simplenotes
 * @copyright  2011 onwards Paul Vaughan, paulvaughan@southdevon.ac.uk
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['pluginname'] = 'Simple Notes';

$string['config_icons']         = 'Display priority icons next to notes?';
$string['config_trim']          = 'Trim note text after \'n\' characters?';
$string['config_trimlimit']     = 'If Yes, above, choose number of characters to trim after:';
$string['config_yes']           = 'Yes';
$string['config_no']            = 'No';
$string['config_datetype']      = 'Choose a date/time format:';
$string['config_sortorder']     = 'Choose which notes to show, and in what order:';
$string['config_viewlimit']     = 'Choose the maximum number of notes to show:';
$string['config_datetime']      = 'Choose what date/time options appear at the foot of each note:';

$string['this-date-desc']       = 'Notes from this course only, most recent first';
$string['this-date-asc']        = 'Notes from this course only, oldest first';
$string['this-crit-date-desc']  = 'Notes from this course only, critical first, most recent first';
$string['this-crit-date-asc']   = 'Notes from this course only, critical first, oldest first';
$string['all-date-desc']        = 'Notes from all courses, most recent first';
$string['all-date-asc']         = 'Notes from all courses, oldest first';
$string['all-crit-date-desc']   = 'Notes from all courses, critical first, most recent first';
$string['all-crit-date-asc']    = 'Notes from all courses, critical first, oldest first';

$string['dt-datetime']      = 'Use \'date/time\' selection';
$string['dt-timeago']       = 'Use \'time ago\' option';
$string['dt-both']          = 'Use both \'date/time\' and \'time ago\' options';
$string['dt-none']          = 'No date/time information';


$string['nonotes']          = 'No notes.';
$string['addnoteshort']     = 'Add one?';

$string['alt_add']          = 'Add a note TEST TEST TEST TEST';
$string['alt_info']         = 'Information and Assistance';
$string['alt_delete']       = 'Delete this note';
$string['alt_delete_confirm'] = 'Are you sure you want to delete this note?';
$string['alt_edit']         = 'Edit this note';

$string['fullnote'] = 'Full note reads: ';

$string['addnote']          = 'Add a note';
$string['addnote_navtitle'] = 'Simple Notes: Add a note';
$string['addnote_header']   = 'Complete this form to add a note';
$string['addnote_title']    = 'Note title:';
$string['addnote_text']     = 'Note text:';
$string['addnote_pri']      = 'Note priority:';

$string['footer-showing']   = 'Showing ';
$string['footer-of']        = ' of ';
$string['footer-ntoes']     = ' notes.';

$string['editnote']         = 'Edit a note';
$string['editnote_navtitle'] = 'Simple Notes: Edit this note';
$string['editnote_header']  = 'Complete this form to modify this note';
$string['editnote_title']   = $string['addnote_title'];
$string['editnote_text']    = $string['addnote_text'];
$string['editnote_pri']     = $string['addnote_pri'];

$string['pri1']             = 'Critical';
$string['pri2']             = 'High';
$string['pri3']             = 'Normal';
$string['pri4']             = 'Low';

$string['pvcustomlang01']   = '%d/%m/%Y, %l:%M%P';
$string['pvcustomlang02']   = '%a %d/%m/%Y, %l:%M%P';
$string['pvcustomlang03']   = '%A %d/%m/%Y, %l:%M%P';
$string['pvcustomlang04']   = '%d/%m/%Y, %l:%M %P';
$string['pvcustomlang05']   = '%a %d/%m/%Y, %l:%M %P';
$string['pvcustomlang06']   = '%A %d/%m/%Y, %l:%M %P';

$string['err_pri']          = 'ERROR in function get_pri_img()';
$string['err_empty_nan']    = 'is empty or is not a number. Cannot continue. Please report to developer.';
$string['err_empty']        = 'is empty. Cannot continue. Please report to developer.';
$string['err_range']        = 'is not within the allowed range. Cannot continue. Please report to developer.';
$string['err_insert']       = 'Error adding note. Cannot continue. Please report to developer.<br />MySQL error: ';
$string['err_update']       = 'Error updating note. Cannot continue. Please report to developer.<br />MySQL error: ';
$string['err_delete']       = 'Error deleting note. Cannot continue. Please report to developer.<br />MySQL error: ';
//$string['err_delete_check'] = 'Cannot find note to delete, or you tried to delete a note which isn\'t yours.';
