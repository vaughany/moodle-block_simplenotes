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
 * SimpleNotes add note form
 *
 * @package    block_simplenotes
 * @copyright  2011 onwards Paul Vaughan, paulvaughan@southdevon.ac.uk
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(dirname(__FILE__).'/../../config.php');
require_once($CFG->libdir.'/formslib.php');

// Require login.
require_login();
if (isguestuser()) {
    print_error('guestsarenotallowed');
}

// Required parameters.
$courseid = required_param('cid', PARAM_INTEGER);
$noteid = required_param('nid', PARAM_INTEGER);

// Get the note's details.
$notedetails = $DB->get_record('block_simplenotes', array('id' => $noteid, 'courseid' => $courseid, 'userid' => $USER->id), '*', MUST_EXIST);

if ($notedetails) {

    // Create a new object to pass to update_record().
    $deletenote = new object;

    // ID needed for update statement.
    $deletenote->id = $noteid;

    // We don't delete, we just *flag as deleted*.
    $deletenote->deleted = true;

    // Set the time the note mas deleted (modified).
    $deletenote->modified = time();

    // Everything is as okay as we can get it so chuck it in the db.
    if (!$DB->update_record('block_simplenotes', $deletenote)) {
        // Die if errror.
        die(get_string('err_delete', 'block_simplenotes').mysql_error());
    }

} else {
    die('Couldn\'t find a block to delete.');
}

redirect($CFG->wwwroot.'/course/view.php?id='.$courseid);
