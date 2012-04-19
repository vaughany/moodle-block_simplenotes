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
 * SimpleNotes edit note form
 *
 * @package    block_simplenotes
 * @copyright  2011 onwards Paul Vaughan, paulvaughan@southdevon.ac.uk
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(dirname(__FILE__).'/../../config.php');
require_once($CFG->libdir.'/formslib.php');

/**
 * 'Edit form' class for adding notes, based very much on the 'add' form.
 *
 * TODO: Make one definition for both adding and editing? Keeping it DRY.
 *
 * @copyright   2011 onwards Paul Vaughan
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class simplenotes_edit_form extends moodleform {

    public function definition() {

        $mform =& $this->_form;

        // Header.
        $mform->addElement('header', 'header', get_string('editnote_header', 'block_simplenotes'));

        // Note title.
        $attrib_title = array('size' => '40');
        $mform->addElement('text', 'title', get_string('editnote_title', 'block_simplenotes'), $attrib_title);
        $mform->addRule('title', null, 'maxlength', 50);
        $mform->setType('title', PARAM_NOTAGS);

        // Note itself.
        $attrib_note = array('wrap' => 'virtual', 'rows' => 5, 'cols' => 40);
        $mform->addElement('textarea', 'note', get_string('editnote_text', 'block_simplenotes'), $attrib_note);
        $mform->addRule('note', null, 'required');
        $mform->applyFilter('note', 'trim');
        $mform->setType('note', PARAM_RAW);

        // Priority.
        $priorities = array(
            '1' => get_string('pri1', 'block_simplenotes'),
            '2' => get_string('pri2', 'block_simplenotes'),
            '3' => get_string('pri3', 'block_simplenotes'),
            '4' => get_string('pri4', 'block_simplenotes'),
        );
        $mform->addElement('select', 'priority', get_string('editnote_pri', 'block_simplenotes'), $priorities);
        $mform->setDefault('priority', 3);

        // Buttons.
        $buttonarray = array(
            $mform->createElement('submit', 'submitbutton', get_string('savechanges')),
            $mform->createElement('reset', 'resetbutton', get_string('revert')),
            $mform->createElement('cancel')
        );
        $mform->addGroup($buttonarray, 'buttonar', '', array(' '), false);

    }
}

// Require login.
require_login();
if (isguestuser()) {
    print_error('guestsarenotallowed');
}

// Required parameters.
$courseid = required_param('cid', PARAM_INTEGER);
$noteid = required_param('nid', PARAM_INTEGER);

// Get context.
if ($courseid) {
    $course = $DB->get_record('course', array('id' => $courseid), '*', MUST_EXIST);
    $PAGE->set_course($course);
    $context = $PAGE->context;
} else {
    $context = get_context_instance(CONTEXT_SYSTEM);
    $PAGE->set_context($context);
}

// Page stuff.
$PAGE->set_url(new moodle_url('/blocks/simplenotes/noteedit.php', array('cid' => $courseid, 'nid' => $noteid)));

// Get the note's details.
$notedetails = $DB->get_record('block_simplenotes', array('id' => $noteid, 'courseid' => $courseid, 'userid' => $USER->id), '*', MUST_EXIST);

// Actual form stuff.
$mform = new simplenotes_edit_form($PAGE->url, 'poopy');
$mform->set_data($notedetails);

// If the form is cancelled.
if ($mform->is_cancelled()) {
    redirect($CFG->wwwroot.'/course/view.php?id='.$courseid);

} else if ($data = $mform->get_data()) {
    // If the form is not cancelled, process it.

    // Create a new object to pass to update_record().
    $updatenote = new object;

    // ID needed for update statement.
    $updatenote->id = $noteid;

    // Check all the variables and pass them to new object if okay, die of not.
    // Probably shouldn't be dying, should pass to an error function.
    if (empty($USER->id) || !is_numeric($USER->id)) {
        die('$USER->id '.get_string('err_empty_nan', 'block_simplenotes'));
    } else {
        $updatenote->userid = $USER->id;
    }

    if (empty($COURSE->id) || !is_numeric($COURSE->id)) {
        die('$COURSE->id '.get_string('err_empty_nan', 'block_simplenotes'));
    } else {
        $updatenote->courseid = $COURSE->id;
    }

    // Title can be left empty, so no need to check: just assign it to the object.
    $updatenote->title = htmlentities($data->title, ENT_QUOTES, 'UTF-8');

    if (empty($data->note)) {
        die('$data->note '.get_string('err_empty', 'block_simplenotes'));
    } else {
        $updatenote->note = htmlentities($data->note, ENT_QUOTES, 'UTF-8');
    }

    if (empty($data->priority) || !is_numeric($data->priority)) {
        die('$data->priority '.get_string('err_empty_nan', 'block_simplenotes'));
    } else if ($data->priority >= 1 && $data->priority <= 4) {
        $updatenote->priority = $data->priority;
    } else {
        die('$data->priority '.get_string('err_range', 'block_simplenotes'));
    }

    // Set the time the note mas modified.
    $updatenote->modified = time();

    // Everything is as okay as we can get it so chuck it in the db.
    if (!$DB->update_record('block_simplenotes', $updatenote)) {
        // Die if errror.
        die(get_string('err_update', 'block_simplenotes').mysql_error());
    }

    redirect($CFG->wwwroot.'/course/view.php?id='.$courseid);

} else {
    // Lang string.
    $editnote = get_string('editnote_navtitle', 'block_simplenotes');

    // Adds the 'edit note' text to the nav bar.
    $navlinks[] = array(  'name' => $editnote,
                        'link' => null,
                        'type' => 'misc');

    print_header($editnote, $COURSE->fullname, build_navigation($navlinks), $mform->focus());
    $OUTPUT->heading($editnote);
    $mform->display();
    $OUTPUT->footer($COURSE);
}
