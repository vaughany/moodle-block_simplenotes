<?php

require_once('../../config.php');

// where to redirect to when note's been deleted
$redir = required_param('redir', PARAM_INT);
// the id of the note to be deleted
$noteid = required_param('noteid', PARAM_INT);

// basic access control check
require_login($COURSE->id);

// User could modify the URL to delete other notes, but we're using the $USER variable which the user has no control over,
// so the user can only delete *their* notes. we check the record exists for that user and is not deleted, THEN we can delete.
if (!$result = get_record_select('block_simplenotes', 'deleted = \'0\' AND userid = \''.$USER->id.'\' AND id = \''.$noteid.'\'', 'id')) {
    die(get_string('err_delete_check', 'block_simplenotes'));
}

// create a new object to pass to update_record()
$dob_update = new object;
$dob_update->id = $result->id;
$dob_update->deleted = 1;
// leave this date format alone: it's for the db **only** and is quite specific.
$dob_update->updated = date('Y-m-d H:i:s', time());

// everything is as checked as possible so update that table
if (!update_record('block_simplenotes', $dob_update)) {
    // die if error.
    die(get_string('err_delete', 'block_simplenotes').mysql_error());
}

// all done, go home
redirect($CFG->wwwroot.'/course/view.php?id='.$redir);
?>