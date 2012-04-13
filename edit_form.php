<?php
 
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
            array(1 => 'Yes', 0 => 'No'));
        $mform->setDefault('config_icons', 1);
        $mform->setType('config_icons', PARAM_BOOL);
        
        // trim long notes or not
        $mform->addElement('select', 'config_trim', get_string('config_trim', 'block_simplenotes'), 
            array(  1 => get_string('config_yes', 'block_simplenotes'),
                    0 => get_string('config_no', 'block_simplenotes')));
        $mform->setDefault('config_trim', 0);
        $mform->setType('config_trim', PARAM_BOOL);        
        
        // limit at which we start trimming
        $numbers = array(10, 20, 50, 75, 100, 150, 200, 300, 400, 500,
            600, 700, 800, 900, 1000, 1500, 2000, 3000, 4000, 5000);
        $trimlimit = array();
        foreach ($numbers as $number) {
            $trimlimit[$number] = number_format($number);
        }
        $mform->addElement('select', 'config_trimlimit', get_string('config_trimlimit', 'block_simplenotes'), $trimlimit);
        $mform->setDefault('config_trimlimit', 0);
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
                    'strftimetime'          => userdate(time(), get_string('strftimetime'), $USER->timezone)
                    ));
        $mform->setDefault('config_datetype', 0);
        $mform->setType('config_datetype', PARAM_BOOL);  
    }
}