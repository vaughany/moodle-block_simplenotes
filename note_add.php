<?php

require_once('../../config.php');
require_once($CFG->libdir.'/formslib.php');
//this is the file with the form definition in it
require_once('note_add_form.php');

// where to redirect to if form is cancelled
// changed from optional_param())
$redir = required_param('redir', PARAM_INT);

// basic access control check
// can we do more here? do we need to?
require_login($COURSE->id);

// first create the form from the class included earlier 
$editform = new simplenotes_add_form();

/**
 * Main processing *if* structure
 */

// if the form is cancelled
if ($editform->is_cancelled()) {
    redirect($CFG->wwwroot.'/course/view.php?id='.$redir);

// if the form is not cancelled, process it
} else if ($data = $editform->get_data()) {

    // create a new object to pass to insert_record()
    $dob_insert = new object;

    // check all the variables and pass them to new object if okay, die of not.
    // probably shouldn't be dying, should pass to an error function
    if (empty($USER->id) || !is_numeric($USER->id)) {
        die('$USER->id '.get_string('err_empty_nan', 'block_simplenotes'));
    } else {
        $dob_insert->userid = $USER->id;
    }

    if (empty($COURSE->id) || !is_numeric($COURSE->id)) {
        die('$COURSE->id '.get_string('err_empty_nan', 'block_simplenotes'));
    } else {
        $dob_insert->courseid = $COURSE->id;
    }

    // title can be left empty, so no need to check: just assign it to the object
    //$dob_insert->title = $data->title;
    $dob_insert->title = htmlentities($data->title, ENT_QUOTES, 'UTF-8');

    if (empty($data->note)) {
        die('$data->note '.get_string('err_empty', 'block_simplenotes'));
    } else {
        //$dob_insert->note = $data->note;
        $dob_insert->note = htmlentities($data->note, ENT_QUOTES, 'UTF-8');
    }

    if (empty($data->priority) || !is_numeric($data->priority)) {
        die('$data->priority '.get_string('err_empty_nan', 'block_simplenotes'));
    } else if ($data->priority >= 1 && $data->priority <= 4) {
        $dob_insert->priority = $data->priority;
    } else {
        die('$data->priority '.get_string('err_range', 'block_simplenotes'));
    }

    // leave this date format alone: it's for the db **only** and is quite specific.
    $dob_insert->updated = date('Y-m-d H:i:s', time());

    // everything is as okay as we can get it so chuck it in the db
    if (!insert_record('block_simplenotes', $dob_insert, true)) {
        // die if errror.
        die(get_string('err_insert', 'block_simplenotes').mysql_error());
    }

    redirect($CFG->wwwroot.'/course/view.php?id='.$data->redir);

} else {
    // this branch is executed on the first display of the form or:
    // if the form is submitted but the data doesn't validate and the form should be redisplayed

    // Print the form
    $site = get_site();
    $newnote = get_string('addnote', 'block_simplenotes');
    $navlinks = array();

    // adds the 'add note' text to the nav bar.
    $navlinks[] = array('name' => $newnote,
                        'link' => null,
                        'type' => 'misc');

    $title = $newnote; // page title
    $fullname = $COURSE->fullname; // in header of page
    $navigation = build_navigation($navlinks); // as generated above
    print_header($title, $fullname, $navigation, $editform->focus());
    print_heading($newnote); // at top of page content (below header)

    $editform->display();

    print_footer($COURSE);

}
?>