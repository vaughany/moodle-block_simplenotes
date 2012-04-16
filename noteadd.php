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

class simplenotes_add_form extends moodleform {

    function definition() {

        $mform =& $this->_form;

        // header
        $mform->addElement('header', 'header', get_string('addnote_header', 'block_simplenotes'));

        // note title
        $attrib_title = array('size' => '40');
        $mform->addElement('text', 'title', get_string('addnote_title', 'block_simplenotes'), $attrib_title);
        $mform->addRule('title', null, 'maxlength', 50);
        $mform->setType('title', PARAM_NOTAGS);

        // note itself
        $attrib_note = array('wrap' => 'virtual', 'rows' => 5, 'cols' => 40);
        $mform->addElement('textarea', 'note', get_string('addnote_text', 'block_simplenotes'), $attrib_note);
        $mform->addRule('note', null, 'required');
        $mform->applyFilter('note', 'trim');
        $mform->setType('note', PARAM_RAW);

        // priority
        $priorities = array(
            '1' => get_string('pri1', 'block_simplenotes'),
            '2' => get_string('pri2', 'block_simplenotes'),
            '3' => get_string('pri3', 'block_simplenotes'),
            '4' => get_string('pri4', 'block_simplenotes'),
        );
        $mform->addElement('select', 'priority', get_string('addnote_pri', 'block_simplenotes'), $priorities);
        $mform->setDefault('priority', 3);

        // buttons
        $buttonarray = array(
            $mform->createElement('submit', 'submitbutton', get_string('savechanges')),
            $mform->createElement('reset', 'resetbutton', get_string('revert')),
            $mform->createElement('cancel')
        );
        $mform->addGroup($buttonarray, 'buttonar', '', array(' '), false);

    }
}

// require login
require_login();
if (isguestuser()) {
    print_error('guestsarenotallowed');
}

// required parameters
$courseid = required_param('cid', PARAM_INTEGER);

// get context
if ($courseid) {
    $course = $DB->get_record('course', array('id' => $courseid), '*', MUST_EXIST);
    $PAGE->set_course($course);
    $context = $PAGE->context;
} else {
    $context = get_context_instance(CONTEXT_SYSTEM);
    $PAGE->set_context($context);
}

// page stuff
$PAGE->set_url(new moodle_url('/blocks/simplenotes/noteadd.php', array('cid' => $courseid)));

// actual form stuff
$mform = new simplenotes_add_form($PAGE->url, true);

// if the form is cancelled
if ($mform->is_cancelled()) {
    redirect($CFG->wwwroot.'/course/view.php?id='.$courseid);

// if the form is not cancelled, process it
} else if ($data = $mform->get_data()) {

    // create a new object to pass to insert_record()
    $insertnote = new object;

    // check all the variables and pass them to new object if okay, die of not.
    // probably shouldn't be dying, should pass to an error function
    if (empty($USER->id) || !is_numeric($USER->id)) {
        die('$USER->id '.get_string('err_empty_nan', 'block_simplenotes'));
    } else {
        $insertnote->userid = $USER->id;
    }

    if (empty($COURSE->id) || !is_numeric($COURSE->id)) {
        die('$COURSE->id '.get_string('err_empty_nan', 'block_simplenotes'));
    } else {
        $insertnote->courseid = $COURSE->id;
    }

    // title can be left empty, so no need to check: just assign it to the object
    $insertnote->title = htmlentities($data->title, ENT_QUOTES, 'UTF-8');

    if (empty($data->note)) {
        die('$data->note '.get_string('err_empty', 'block_simplenotes'));
    } else {
        $insertnote->note = htmlentities($data->note, ENT_QUOTES, 'UTF-8');
    }

    if (empty($data->priority) || !is_numeric($data->priority)) {
        die('$data->priority '.get_string('err_empty_nan', 'block_simplenotes'));
    } else if ($data->priority >= 1 && $data->priority <= 4) {
        $insertnote->priority = $data->priority;
    } else {
        die('$data->priority '.get_string('err_range', 'block_simplenotes'));
    }

    // created and modified dates are the same at this point
    $time = time();
    $insertnote->created = $time;
    $insertnote->modified = $time;

    // everything is as okay as we can get it so chuck it in the db
    if (!$DB->insert_record('block_simplenotes', $insertnote, true)) {
        // die if errror.
        die(get_string('err_insert', 'block_simplenotes').mysql_error());
    }

    redirect($CFG->wwwroot.'/course/view.php?id='.$courseid);

} else {
    // lang string
    $newnote = get_string('addnote_navtitle', 'block_simplenotes');

    // adds the 'add note' text to the nav bar.
    $navlinks = array(  'name' => $newnote,
                        'link' => null,
                        'type' => 'misc');

    print_header($newnote, $COURSE->fullname, build_navigation($navlinks), $mform->focus());
    $OUTPUT->heading($newnote);
    $mform->display();
    $OUTPUT->footer($COURSE);
}
