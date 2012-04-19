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
 * Simple Notes block class.
 *
 * @copyright   2011 onwards Paul Vaughan
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_simplenotes extends block_base {

    public function init() {
        $this->title = get_string('pluginname', 'block_simplenotes');
    }

    public function instance_allow_multiple() {
        return false;
    }

    public function has_config() {
        return false;
    }

    public function instance_allow_config() {
        return true;
    }

    public function applicable_formats() {
        return array('all' => true);
    }

    public function html_attributes() {
        $attributes = parent::html_attributes();            // Get default values.
        $attributes['class'] .= ' block_'. $this->name();   // Append our class to class attribute.
        return $attributes;
    }

    public function specialization() {
        // Flag to save $this->config later on if we change it at all.
        $saveconfig = false;
        // If not already defined, define $this->config before we start using it.
        // If it is defined, chances are we won't need any of these checks.
        if (!isset($this->config)) {
            $this->config = new stdClass();
        }
        // If the trim setting's not set, set it (true/false).
        if (!isset($this->config->trim)) {
            $this->config->trim = true;
            $saveconfig = true;
        }
        // If the trim limit's not set, set it (characters).
        if (!isset($this->config->trimlimit)) {
            $this->config->trimlimit = 500;
            $saveconfig = true;
        }
        // If the date format's not set, set it.
        if (!isset($this->config->datetype)) {
            $this->config->datetype = 'pvcustomlang01';
            $saveconfig = true;
        }
        // If the footer format's not set, set it.
        if (!isset($this->config->datetime)) {
            $this->config->datetime = 'both';
            $saveconfig = true;
        }
        // If the icon setting's not set, set it.
        if (!isset($this->config->icons)) {
            $this->config->icons = true;
            $saveconfig = true;
        }
        // If the sort order setting's not set, set it.
        if (!isset($this->config->sortorder)) {
            $this->config->sortorder = 'this-date-desc';
            $saveconfig = true;
        }
        // If the view limit's not set, set it (notes).
        if (!isset($this->config->viewlimit)) {
            $this->config->viewlimit = 10;
            $saveconfig = true;
        }
        // http://docs.moodle.org/dev/Blocks/Appendix_A#instance_config_commit.28.29
        if ($saveconfig) {
            parent::instance_config_save($this->config);
        }
    } // End specialization.

    /**
     * End of standard configuration options. Now some functions to do stuff.
     */

    /**
     * Create an 'add' link.
     *
     * @param   bool    $hr     If true, add a 'hr' tag.
     * @return  string
     */
    public function get_add_lnk($hr=false) {
        global $CFG, $COURSE;
        // TODO: New idea: a 'help' or 'information' link (to a read-me?).
        $tmp  = '<p class="snadd">';
        $tmp .= '<a href="'.$CFG->wwwroot.'/blocks/simplenotes/noteadd.php?cid='.$COURSE->id.'">';
        $tmp .= '<img class="addimg" width="16" height="16" src="'.$CFG->wwwroot.'/blocks/simplenotes/img/add.png" alt="'.get_string('alt_add', 'block_simplenotes');
        $tmp .= '" /></a></p>'."\n";

        // Check if a 'hr' is required.
        if ($hr) {
            $tmp .= $this->divider();
        }

        return $tmp;
    }

    /**
     * A shortcut to the hr tag with appropriate extra css/html.
     *
     * @param   int     $pri    If priority is 1, return extra formatting.
     * @return  string
     */
    public function divider($pri = null) {
        if ($pri == 1) {
            return '<hr class="priority" />'."\n";
        } else {
            return '<hr />'."\n";
        }
    }

    /**
     * Get the right image and alt text for the priority level.
     *
     * @param   int     $pri    Defines priority value to return specific text/images.
     * @return  string
     */
    public function get_pri_img($pri) {
        global $CFG;
        switch ($pri) {
            case 1:
                $alt = get_string('pri1', 'block_simplenotes');
                break;
            case 2:
                $alt = get_string('pri2', 'block_simplenotes');
                break;
            case 3:
                $alt = get_string('pri3', 'block_simplenotes');
                break;
            case 4:
                $alt = get_string('pri4', 'block_simplenotes');
                break;
            default:
                $alt = get_string('err_pri', 'block_simplenotes');
                $pri = 0;
        }
        return '<img class="snpriority" width="16" height="16" src="'.$CFG->wwwroot.'/blocks/simplenotes/img/'.
            $pri.'.png" alt="'.$alt.'" title="'.$alt.'" />'."\n";
    }

    /**
     * Function for printing imgae, javascript (for 'confirm?' box) and link for the delete button.
     *
     * @param   int     $noteid     ID number of the note.
     * @return  string
     */
    public function get_del_btn($noteid) {
        global $COURSE, $CFG;
        $tmp  = '<a href="'.$CFG->wwwroot.'/blocks/simplenotes/notedelete.php?nid='.$noteid.'&amp;cid='.$COURSE->id.'" ';
        $tmp .= 'onclick="if (! confirm(\''.get_string('alt_delete_confirm', 'block_simplenotes').'\')) return false;" ';
        $tmp .= 'title="'.get_string('alt_delete', 'block_simplenotes').'">'."\n";
        $tmp .= '    <img class="deleteimg" width="16" height="16" src="'.$CFG->wwwroot.'/blocks/simplenotes/img/delete.png" ';
        $tmp .= 'alt="'.get_string('alt_delete', 'block_simplenotes').'" />'."\n";
        $tmp .= '</a>'."\n";
        return $tmp;
    }

    /**
     * Function for printing imgae and link for the edit button.
     *
     * @param   int     $noteid     ID number of the note.
     * @return  string
     */
    public function get_edt_btn($noteid) {
        global $COURSE, $CFG;
        $tmp  = '<a href="'.$CFG->wwwroot.'/blocks/simplenotes/noteedit.php?nid='.$noteid.'&amp;cid='.$COURSE->id.'" ';
        $tmp .= 'title="'.get_string('alt_edit', 'block_simplenotes').'">'."\n";
        $tmp .= '    <img class="editimg" width="16" height="16" src="'.$CFG->wwwroot.'/blocks/simplenotes/img/edit.png" ';
        $tmp .= 'alt="'.get_string('alt_edit', 'block_simplenotes').'" />'."\n";
        $tmp .= '</a>'."\n";
        return $tmp;
    }

    /**
     * Trim the note text if it is more than as set in the config.
     *
     * @param   string  $note       Note text.
     * @param   int     $trim       The value at which to trim the note.
     * @param   int     $pri        Priority value.
     * @return  string
     */
    public function trim_note($note, $trim, $pri) {
        $len = strlen($note);
        if ($len > $trim) {
            // Get first 'number' chars and add '...' to the end and return.
            $output = '<p class="snnote hover';
            if ($pri == 1) {
                $output .= ' criticalnote';
            }
            $output .= '" title="'.get_string('fullnote', 'block_simplenotes').$note.'">'.substr(nl2br($note), 0, $trim).'...</p>'."\n";
        } else {
            $output = '<p class="snnote';
            if ($pri == 1) {
                $output .= ' criticalnote';
            }
            $output .= '">'.nl2br($note).'</p>'."\n";
        }
        return $output;
    }

    /**
     * Format the date nicely, taking into account timezone.
     *
     * @param   int     $datetime   The date/time of the note expressed as a Unix epoch.
     * @return  string
     */
    public function get_datetime($datetime) {
        global $USER;

        // Get the 'datetime' string.
        // If the strings starts with pv (my initials) then look for it in the block's lang pack,
        // otherwise specify no lang pack and Moodle will get it from the default lang packs.
        if (substr($this->config->datetype, 0, 2) == 'pv') {
            $dt = userdate($datetime, get_string($this->config->datetype, 'block_simplenotes'), $USER->timezone);
        } else {
            $dt = userdate($datetime, get_string($this->config->datetype), $USER->timezone);
        }

        // get the 'time ago' string
        $ta = $this->timeago($datetime);

        switch ($this->config->datetime) {
            case 'datetime':
                $res = $dt;
                break;
            case 'timeago':
                $res = $ta;
                break;
            case 'both':
                $res = $dt.' ('.$ta.')';
                break;
            case 'none':
                $res = null;
                break;
        }

        return $res;
    }

    /**
     * Function for doing the 'time ago' thing a-la-Facebook/Twitter.
     * Credit goes to: http://www.zachstronaut.com/posts/2009/01/20/php-relative-date-time-string.html
     *
     * @param   int     $int        The date/time of the note expressed as a Unix epoch.
     * @return  string
     */
    public function timeago($int) {
        $elapsed = time() - $int;

        if ($elapsed < 10) {
            return 'now';
        }

        $a = array( 86400 * 365 => 'year',
                    86400 * 30  => 'month',
                    86400 * 7   => 'week',
                    86400       => 'day',
                    3600        => 'hour',
                    60          => 'minute',
                    1           => 'second'
                    );

        foreach ($a as $secs => $str) {
            $d = $elapsed / $secs;
            if ($d >= 1) {
                $r = round($d);
                return $r.' '.$str.($r > 1 ? 's' : '').' ago';
            }
        }
    }

    /**
     * Counts the number of live (not deleted) notes for a user.
     *
     * @return  int
     */
    public function count_notes() {
        global $USER, $COURSE, $DB;
        $sql = "SELECT COUNT(*) AS cnt FROM mdl_block_simplenotes WHERE deleted = '0' AND userid = '".$USER->id."' AND courseid = '".$COURSE->id."' LIMIT 1;";
        $res = $DB->get_record_sql($sql);
        return $res->cnt;
    }

    /**
     * End of functions, on to the actual 'doing'
     * We build a string called $notes which ends up being passed to $this->content->text
     */

    public function get_content() {
        global $CFG, $DB, $USER, $COURSE;
        $notes = '';

        // Go get all the notes for this user.

        switch ($this->config->sortorder) {
            case 'this-date-desc':
                $sortsql = 'modified DESC';
                break;
            case 'this-date-asc':
                $sortsql = 'modified ASC';
                break;
            case 'this-crit-date-desc':
                $sortsql = 'priority ASC, modified DESC';
                break;
            case 'this-crit-date-asc':
                $sortsql = 'priority ASC, modified ASC';
                break;

            default:
                $sortsql = 'priority ASC, modified DESC';
        }

        $result = $DB->get_records('block_simplenotes', array('deleted' => 0, 'userid' => $USER->id), $sortsql, '*', 0, $this->config->viewlimit);

        // Used for counting the number of notes.
        $num_notes = '0';

        // Wrap all the content in a div.
        $notes .= '<div id="simplenotes">';

        if (empty($result)) {
            // Do this if there's no notes to display.
            $notes .= '<p class="snnote">'.get_string('nonotes', 'block_simplenotes').' <a href="'.$CFG->wwwroot.'/blocks/simplenotes/noteadd.php?cid='.
                $COURSE->id.'">'.get_string('addnoteshort', 'block_simplenotes').'</a></p>'."\n";
            $notes .= $this->divider();

            // Add a new note link.
            $notes .= $this->get_add_lnk(false);
        } else {
            // Put the 'add a new note' link at the top.
            $notes .= $this->get_add_lnk(true);

            // We have notes, so process one at a time.
            foreach ($result as $res) {
                // One more note.
                $num_notes++;

                // Add in a div for critical notes.
                if ($res->priority == 1) {
                    $notes .= '<div class="critical">'."\n";
                }

                // Add the priority flag.
                if ($this->config->icons) {
                    $notes .= $this->get_pri_img($res->priority);
                }

                // Add in the edit and delete icons.
                $notes .= $this->get_del_btn($res->id);
                $notes .= $this->get_edt_btn($res->id);

                // Print title if not empty, print absolutely nothing if it is empty.
                if (!empty($res->title)) {
                    $notes .= '<p class="sntitle';
                    if ($res->priority == 1) {
                        $notes .= ' criticaltitle';
                    }
                    $notes .= '">'.$res->title.'</p>'."\n";
                }

                // Print note body, trimming if required.
                if ($this->config->trim) {
                    $notes .= $this->trim_note($res->note, $this->config->trimlimit, $res->priority);
                } else {
                    $notes .= '<p class="snnote">'.$res->note.'</p>'."\n";
                }

                // Note updated date.
                $notes .= '<p class="sndate';
                if ($res->priority == 1) {
                    $notes .= ' criticaldate';
                }
                $notes .= '">'.$this->get_datetime($res->modified).'</p>'."\n";

                // End of the priority 1 div.
                if ($res->priority == 1) {
                    $notes .= '</div>'."\n";
                }

                // Add a hr at the bottom of this note.
                $notes .= $this->divider($res->priority);
            }

            // Display x of y notes.
            $actualnotes    = $this->count_notes();
            $viewlimit      = $this->config->viewlimit;
            if ($actualnotes < $viewlimit) {
                $viewlimit = $actualnotes;
            }
            $notes .= '<div class="footer">Showing '.$viewlimit.' of '.$actualnotes.' notes.<br />';
            // Add a new note link.
            $notes .= $this->get_add_lnk(false);
            $notes .= "</div>\n";
        }

        // End the notes-containing div.
        $notes .= '</div>'."\n";

        // The usual suspects.
        $this->content = new stdClass;
        $this->content->text = $notes;
        $this->content->footer = '';

        return $this->content;
    } // End function get_content.
}
