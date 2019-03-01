<?php

class Showerr {

    var $CI;

    function __construct() 
    {
        $this->CI = & get_instance();
    }

    
    public function blockModules() 
    {
        $modules = array('reports');

        // Block Modules.
        if (in_array(strtolower(get_class($this->CI)), $modules)) {

            //show_error(get_class($this->CI) . " module is under construction.", 500, "Under Construction");
        }
    }

}
