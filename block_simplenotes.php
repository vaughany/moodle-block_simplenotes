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
 * SimpleNotes is a quick and easy way to add a note (in the style of a to-do list) to any Moodle course.
 *
 * SimpleNotes was originally written for Moodle 1.9 and has been superseded in part by the Comments block
 * but also adds functionality on top of it, such as prioritising and cross-Moodle notes.
 *
 * @package    block_simplenotes
 * @copyright  2011 onwards Paul Vaughan, paulvaughan@southdevon.ac.uk
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
SPARE SQL

INSERT INTO `mdl_block_simplenotes` (`id`, `userid`, `courseid`, `title`, `note`, `priority`, `created`, `modified`, `deleted`) VALUES
(1, 2, 611, 'title', 'body content', 3, 1334325302, 1334325317, 0);
*/

class block_simplenotes extends block_base {

    // done!
    public function init() {
        $this->title = get_string('pluginname', 'block_simplenotes');
    }

    // done!
    public function instance_allow_multiple() {
        return false;
    }

    // done!
    public function has_config() {
        // global/site config
        return false;
    }

    // done!
    public function instance_allow_config() {
        return true;
    }

    // done!
    public function applicable_formats() {
        //return array('course-view' => true);
        return array('all' => true);
    }

    // modify the block's CSS class
    public function html_attributes() {
        $attributes = parent::html_attributes();            // get default values
        $attributes['class'] .= ' block_'. $this->name();   // append our class to class attribute
        return $attributes;
    }

    public function specialization() {
        /**
         * Set some default settings, as the block won't work correctly without some setup
         * but we don't want the user to set each block's config manually when added.
         */

        // flag to save $this->config later on if we change it at all
        $saveconfig = false;

        // if not already defined, define $this->config before we start using it
        // if it is defined, chances are we won't need any of these checks
        if (!isset($this->config)) {
            $this->config = new stdClass();
        }

        // if the trim setting's not set, set it (true/false)
        if (!isset($this->config->trim)) {
            $this->config->trim = true;
            $saveconfig = true;
        }

        // if the trim limit's not set, set it (characters)
        if (!isset($this->config->trimlimit)) {
            $this->config->trimlimit = 500;
            $saveconfig = true;
        }

        // if the date format's not set, set it (WHAT ARE THE OPTIONS!?)
        if (!isset($this->config->datetype)) {
            $this->config->datetype = 's';
            $saveconfig = true;
        }

        // if the icon setting's not set, set it
        if (!isset($this->config->icons)) {
            $this->config->icons = true;
            $saveconfig = true;
        }

        /**
         * http://docs.moodle.org/dev/Blocks/Appendix_A#instance_config_commit.28.29
         */
        if ($saveconfig) {
            parent::instance_config_save($this->config);
        }

    }

    /* end of standard configuration options */

/*
    function first_use() {
        $fisrtuse_note = 'Simple Notes is a basic &#039;notepad&#039; block for Moodle.
As default, notes over '.$this->config->block_simplenotes_number.' characters long are trimmed off, but can be seen in full by hovering your mouse over the text (as you may well now be doing).
Notes can have critical (red flag), high (orange flag), medium (yellow flag) and low (green flag) priorities, and the flags themselves can be removed if required.
Critical priority notes are highlighted even more (lots of red).
The date can be changed from the default &#039;short&#039; format to a longer format if required.
Line breaks are preserved and you cannot embed  &lt;acronym title=&quot;HyperText Markup Language&quot;&gt;HTML&lt;acronym&gt;: it will come out as it was typed in.';
    }
*/








    /**
     * Firstly, some functions to do stuff...
     */

    // function to add the 'add new note' image and note as required
    public function get_add_lnk($hr=false) {
        global $CFG, $COURSE;
        //$tmp = '<p class="snadd" title="'.get_string('alt_add', 'block_simplenotes').'"><a href="'.$CFG->wwwroot.'/blocks/simplenotes/note_add.php?redir='.$COURSE->id.'"><img class="addimg" width="16" height="16" src="'.$CFG->wwwroot.'/blocks/simplenotes/img/add.png" alt="'.get_string('alt_add', 'block_simplenotes').'" /></a></p>'."\n";

        // new idea: a 'help' or 'information' link (to a read-me?)
        $tmp  = '<p class="snadd">';
        $tmp .= '<a href="'.$CFG->wwwroot.'/blocks/simplenotes/note_add.php"><img class="addimg" width="16" height="16" src="'.$CFG->wwwroot.'/blocks/simplenotes/img/add.png" alt="'.get_string('alt_add', 'block_simplenotes').'" /></a>'."\n";
        //$tmp .= '<a href="'.$CFG->wwwroot.'/blocks/simplenotes/readme_info.php?redir='.$COURSE->id.'"><img class="infoimg" width="16" height="16" src="'.$CFG->wwwroot.'/blocks/simplenotes/img/info.png" alt="'.get_string('alt_info', 'block_simplenotes').'" /></a>';
        $tmp .= '</p>'."\n";

        // check if a 'hr' is required
        if ($hr) { $tmp .= $this->divider(); }

        return $tmp;
    }

    // abstracted divider as we use it a lot - style it further down the page if you want to
    public function divider() {
        return '<hr />'."\n";
    }

    // Get the right image and alt text for the priority level
    public function get_pri_img($pri, $icon) {
        global $CFG;
        if ($icon == 1) { // if icons are needed
            if ($pri == 1) {
                $alt = get_string('pri1', 'block_simplenotes');
            } else if ($pri == 2) {
                $alt = get_string('pri2', 'block_simplenotes');
            } else if ($pri == 3) {
                $alt = get_string('pri3', 'block_simplenotes');
            } else if ($pri == 4) {
                $alt = get_string('pri4', 'block_simplenotes');
            } else {
                $alt = get_string('pri_error', 'block_simplenotes');
            }
            return '<img class="snpriority" width="16" height="16" src="'.$CFG->wwwroot.'/blocks/simplenotes/img/'.$pri.'.png" alt="'.$alt.'" title="'.$alt.'" />'."\n";
        } else { // if icons are not wanted
            return null;
        }
    }

    // function for printing imgae, javascript (for 'confirm?' box) and link for the delete button
    public function get_del_btn($noteid) {
        global $COURSE, $CFG;
        $tmp  = '<a href="'.$CFG->wwwroot.'/blocks/simplenotes/note_delete.php?noteid='.$noteid.'&amp;redir='.$COURSE->id.'" onclick="if (! confirm(\''.get_string('alt_delete_confirm', 'block_simplenotes').'\')) return false;" title="'.get_string('alt_delete', 'block_simplenotes').'">'."\n";
        $tmp .= '    <img class="deleteimg" width="16" height="16" src="'.$CFG->wwwroot.'/blocks/simplenotes/img/delete.png" alt="'.get_string('alt_delete', 'block_simplenotes').'" />'."\n";
        $tmp .= '</a>'."\n";
        return $tmp;
    }

    public function get_edt_btn($noteid) {
        global $COURSE, $CFG;
        $tmp  = '<a href="'.$CFG->wwwroot.'/blocks/simplenotes/note_edit.php?noteid='.$noteid.'&amp;redir='.$COURSE->id.'" title="'.get_string('alt_edit', 'block_simplenotes').'">'."\n";
        $tmp .= '    <img class="editimg" width="16" height="16" src="'.$CFG->wwwroot.'/blocks/simplenotes/img/edit.png" alt="'.get_string('alt_edit', 'block_simplenotes').'" />'."\n";
        $tmp .= '</a>'."\n";
        return $tmp;
    }

    // Trim the note text if it is more than set in the config
    public function trim_note($note, $trim, $pri) {
        $len = strlen($note);
        if ($len > $trim) {
            // get first 'number' chars and add '...' to the end and return
            $output = '<p class="snnote hover';
            if ($pri == 1) { $output .= ' criticalnote'; }
            $output .= '" title="'.get_string('fullnote', 'block_simplenotes').$note.'">'.substr(nl2br($note), 0, $trim).'...</p>'."\n";
        } else {
            //$output = $note;
            $output = '<p class="snnote';
            if ($pri == 1) { $output .= ' criticalnote'; }
            $output .= '">'.nl2br($note).'</p>'."\n";
        }
        return $output;
    }

    // Return the date in a better format than straight out of the database
    // Moodle has locale-friendly date strings built into it so using those
    public function format_datetime($dtm, $type) {
        // 'short' format
        if ($type == 's') {
            //return date(get_string('config_datetype_short_code', 'block_simplenotes'), strtotime($dtm));
            return strftime(get_string('strftimedatetimeshort'), strtotime($dtm));
        // 'long' format
        } else if ($type == 'l') {
            //return date(get_string('config_datetype_long_code', 'block_simplenotes'), strtotime($dtm));
            return strftime(get_string('strftimedaydatetime'), strtotime($dtm));
        // something went wrong and there's no $type. Could default to 'l' or 's' I suppose
        } else {
            return get_string('date_error', 'block_simplenotes');
        }
    }

    /**
     * End of functions, on to the actual 'doing'
     * We build a string called $notes which ends up being passed to $this->content->text
     */












    // The main function which gets all the content
    function get_content() {
        global $CFG, $DB, $USER, $COURSE;


        // init $notes
        $notes = '';

        // Go get all the notes for this user
        // TODO: consider limiting the number of notes returned if there is a lot?
        $result = $DB->get_records('block_simplenotes', array('deleted' => 0, 'userid' => $USER->id), 'priority ASC, modified DESC', '*');

        // used for counting the number of notes
        $num_notes = '0';

        // wrap all the content in a div.
        $notes .= '<div id="simplenotes">';

        if (empty($result)) {
            // do this if there's no notes to display
            //$notes .= '<p class="snnote">'.get_string('nonotes', 'block_simplenotes').' <a href="'.$CFG->wwwroot.'/blocks/simplenotes/note_add.php?redir='.$COURSE->id.'">'.get_string('addnoteshort', 'block_simplenotes').'</a></p>'."\n";
            $notes .= '<p class="snnote">'.get_string('nonotes', 'block_simplenotes').' <a href="'.$CFG->wwwroot.'/blocks/simplenotes/note_add.php">'.get_string('addnoteshort', 'block_simplenotes').'</a></p>'."\n";
            $notes .= $this->divider();

            // 'add a new note' link
            $notes .= $this->get_add_lnk(false);
        } else {
            // put the 'add a new note' link at the top
            $notes .= $this->get_add_lnk(true);

            // we have notes, so process one at a time
            foreach ($result as $res) {
                // one more note
                $num_notes++;

                // add in a div for critical notes
                if ($res->priority == 1) { $notes .= '<div class="critical">'."\n"; }

                // add the priority flag
                $notes = $this->get_pri_img($res->priority, $this->config->icons);

                // add in the edit and delete icons
                $notes .= $this->get_del_btn($res->id);
                $notes .= $this->get_edt_btn($res->id);

                // print title if not empty, print absolutely nothing if it is empty
                if (!empty($res->title)) {
                    $notes .= '<p class="sntitle';
                    if ($res->priority == 1) { $notes .= ' criticaltitle'; }
                    $notes .= '">'.$res->title.'</p>'."\n";
                }

                // print note body, trimming if required
                if ($this->config->trim) {
                    $notes .= $this->trim_note($res->note, $this->config->trimlimit, $res->priority);
                } else {
                    $notes .= '<p class="snnote">'.$res->note.'</p>'."\n";
                }

                // note updated date
                $notes .= '<p class="sndate';
                if ($res->priority == 1) { $notes .= ' criticaldate'; }
                $notes .= '">'.$this->format_datetime($res->modified, $this->config->datetype).'</p>'."\n";

                // end of the priority 1 div
                if ($res->priority == 1) { $notes .= '</div>'."\n"; }

                // add a hr at the bottom of this note
                $notes .= $this->divider();
            }

            // 'add a new note' link
            $notes .= $this->get_add_lnk(false);
        }

        // end the notes-containing div
        $notes .= '</div>'."\n";

        // the usual suspects
        $this->content = new stdClass;
        $this->content->text = $notes;
        /**
         * Swap these next two lines around for **basic** debugging info.
         * May add some form of onmouseover tooltip with debugging info in it instead.
         */
        //$this->content->footer = $num_notes.' notes, '.number_format(strlen($notes)).' chars.';
        $this->content->footer = '';

        return $this->content;
    } // end function get_content

} // end class
?>
