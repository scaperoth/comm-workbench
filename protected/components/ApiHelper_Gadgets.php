<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class ApiHelper_Gadgets extends CHtml {
// Members
    /**
     * Key which has to be in HTTP USERNAME and PASSWORD headers 
     */

    Const APPLICATION_ID = 'ASCCPE';

    /**
     * Default response format
     * either 'json' or 'xml'
     */
    private $format = 'json';

    const LOGIN_ERROR = "You have insufficient permissions to continue";

    /**
     * 
     * @param type $dbstructure
     * @param type $rootlocation
     * @param type $root_child
     * @param type $bucket_dir
     * @param type $level
     */
    function draw_gadget_location_recursive($dbstructure, $rootlocation, $root_child, $bucket_dir, $accordion = false) {
        if($accordion){
            echo BSHtml::tag("div", array(
                "class"=>"well",
            ), "insert accordion stuff here", true);
        }
        foreach ($rootlocation[$root_child] as $foldername => $folder_array) {
            ApiHTML::draw_location_section($rootlocation[$foldername], $bucket_dir, $foldername);
            if ($root_child == 'root')
                self::draw_gadget_location_recursive($dbstructure, $rootlocation[$foldername], 'subfolder', $bucket_dir, $accordion);

            elseif ($root_child == 'subfolder')
                self::draw_gadget_location_recursive($dbstructure, $rootlocation[$foldername], 'bottomfolder', $bucket_dir, $accordion);

            /*
              foreach ($dbstructure['files'][$foldername]['subfolder'] as $subfoldername => $subfolder_array) {
              draw_location_section($dbstructure['files'][$foldername][$subfoldername], $bucket_dir, $subfoldername);
              } */
        }
    }

}