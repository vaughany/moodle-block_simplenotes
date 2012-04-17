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
 * SimpleNotes instance config form
 *
 * @package    block_simplenotes
 * @copyright  2011 onwards Paul Vaughan, paulvaughan@southdevon.ac.uk
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

class block_simplenotes_edit_form extends block_edit_form {

    protected function specific_definition($mform) {
        global $CFG, $USER;

        // Section header title according to language file.
        $mform->addElement('header', 'configheader', get_string('blocksettings', 'block'));

        // use icons or not
        $mform->addElement('select', 'config_icons', get_string('config_icons', 'block_simplenotes').
            '<br /><img width="16" height="16" src="'.$CFG->wwwroot.'/blocks/simplenotes/img/4.png" />
            <img width="16" height="16" src="'.$CFG->wwwroot.'/blocks/simplenotes/img/3.png" />
            <img width="16" height="16" src="'.$CFG->wwwroot.'/blocks/simplenotes/img/2.png" />
            <img width="16" height="16" src="'.$CFG->wwwroot.'/blocks/simplenotes/img/1.png" />',
            array(  1 => get_string('config_yes', 'block_simplenotes'),
                    0 => get_string('config_no', 'block_simplenotes')));
        $mform->setDefault('config_icons', 1);
        $mform->setType('config_icons', PARAM_BOOL);

        // trim long notes or not
        $mform->addElement('select', 'config_trim', get_string('config_trim', 'block_simplenotes'),
            array(  1 => get_string('config_yes', 'block_simplenotes'),
                    0 => get_string('config_no', 'block_simplenotes')));
        $mform->setDefault('config_trim', 1);
        $mform->setType('config_trim', PARAM_BOOL);

        // limit at which we start trimming
        $numbers = array(10, 20, 50, 75, 100, 150, 200, 300, 400, 500,
            600, 700, 800, 900, 1000, 1500, 2000, 3000, 4000, 5000);
        $trimlimit = array();
        foreach ($numbers as $number) {
            $trimlimit[$number] = number_format($number);
        }
        //unset($numbers);
        $mform->addElement('select', 'config_trimlimit', get_string('config_trimlimit', 'block_simplenotes'), $trimlimit);
        $mform->setDefault('config_trimlimit', 500);
        $mform->setType('config_trimlimit', PARAM_INT);

        // set the datetime format
        $mform->addElement('select', 'config_datetype', get_string('config_datetype', 'block_simplenotes'),
            array(  'strftimedate'          => userdate(time(), get_string('strftimedate'), $USER->timezone),
                    'strftimedatefullshort' => userdate(time(), get_string('strftimedatefullshort'), $USER->timezone),
                    'strftimedateshort'     => userdate(time(), get_string('strftimedateshort'), $USER->timezone),
                    'strftimedatetime'      => userdate(time(), get_string('strftimedatetime'), $USER->timezone),
                    'strftimedatetimeshort' => userdate(time(), get_string('strftimedatetimeshort'), $USER->timezone),
                    'strftimedaydate'       => userdate(time(), get_string('strftimedaydate'), $USER->timezone),
                    'strftimedaydatetime'   => userdate(time(), get_string('strftimedaydatetime'), $USER->timezone),
                    'strftimedayshort'      => userdate(time(), get_string('strftimedayshort'), $USER->timezone),
                    'strftimedaytime'       => userdate(time(), get_string('strftimedaytime'), $USER->timezone),
                    'strftimemonthyear'     => userdate(time(), get_string('strftimemonthyear'), $USER->timezone),
                    'strftimerecent'        => userdate(time(), get_string('strftimerecent'), $USER->timezone),
                    'strftimerecentfull'    => userdate(time(), get_string('strftimerecentfull'), $USER->timezone),
                    'strftimetime'          => userdate(time(), get_string('strftimetime'), $USER->timezone),
                    'pvcustomlang01'        => userdate(time(), get_string('pvcustomlang01', 'block_simplenotes'), $USER->timezone),
                    'pvcustomlang02'        => userdate(time(), get_string('pvcustomlang02', 'block_simplenotes'), $USER->timezone),
                    'pvcustomlang03'        => userdate(time(), get_string('pvcustomlang03', 'block_simplenotes'), $USER->timezone),
                    'pvcustomlang04'        => userdate(time(), get_string('pvcustomlang04', 'block_simplenotes'), $USER->timezone),
                    'pvcustomlang05'        => userdate(time(), get_string('pvcustomlang05', 'block_simplenotes'), $USER->timezone),
                    'pvcustomlang06'        => userdate(time(), get_string('pvcustomlang06', 'block_simplenotes'), $USER->timezone)
                    ));
        $mform->setDefault('config_datetype', 'pvcustomlang01');
        $mform->setType('config_datetype', PARAM_RAW);

        // set the sort order options
        $mform->addElement('select', 'config_sortorder', get_string('config_sortorder', 'block_simplenotes'),
            array(  'this-date-desc'        => get_string('this-date-desc',  'block_simplenotes'),
                    'this-date-asc'         => get_string('this-date-asc', 'block_simplenotes'),
                    'this-crit-date-desc'   => get_string('this-crit-date-desc', 'block_simplenotes'),
                    'this-crit-date-asc'    => get_string('this-crit-date-asc', 'block_simplenotes'),
                    'all-date-desc'         => get_string('all-date-desc', 'block_simplenotes'),
                    'all-date-asc'          => get_string('all-date-asc', 'block_simplenotes'),
                    'all-crit-date-desc'    => get_string('all-crit-date-desc', 'block_simplenotes'),
                    'all-crit-date-asc'     => get_string('all-crit-date-asc', 'block_simplenotes')
                    ));
        $mform->setDefault('config_sortorder', 'this-crit-date-desc');
        $mform->setType('config_sortorder', PARAM_RAW);

        // max munber of notes shown
        $numbers = array(1, 2, 3, 4, 5, 10, 15, 20, 25, 30, 40, 50);
        $viewlimit = array();
        foreach ($numbers as $number) {
            $viewlimit[$number] = $number;
        }
        // Add option for 'all'.
        $viewlimit['null'] = 'All';
        $mform->addElement('select', 'config_viewlimit', get_string('config_viewlimit', 'block_simplenotes'), $viewlimit);
        $mform->setDefault('config_trimlimit', 20);
        $mform->setType('config_trimlimit', PARAM_RAW);

        // date settings
        $mform->addElement('select', 'config_datetime', get_string('config_datetime', 'block_simplenotes'),
            array(  'datetime'  => get_string('dt-datetime',  'block_simplenotes'),
                    'timeago'   => get_string('dt-timeago', 'block_simplenotes'),
                    'both'      => get_string('dt-both', 'block_simplenotes'),
                    'none'      => get_string('dt-none', 'block_simplenotes')
                    ));
        $mform->setDefault('config_sortorder', 'this-crit-date-desc');
        $mform->setType('config_sortorder', PARAM_RAW);
    }
}