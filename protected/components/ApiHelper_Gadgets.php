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
    function draw_gadget_location_recursive($dbstructure, $rootlocation, $root_child, $bucket_dir) {

        foreach ($rootlocation[$root_child] as $foldername => $folder_array) {
            self::draw_location_section($rootlocation[$foldername], $bucket_dir, $foldername);
            if ($root_child == 'root')
                self::draw_gadget_location_recursive($dbstructure, $rootlocation[$foldername], 'subfolder', $bucket_dir);

            elseif ($root_child == 'subfolder')
                self::draw_gadget_location_recursive($dbstructure, $rootlocation[$foldername], 'bottomfolder', $bucket_dir);

            /*
              foreach ($dbstructure['files'][$foldername]['subfolder'] as $subfoldername => $subfolder_array) {
              draw_location_section($dbstructure['files'][$foldername][$subfoldername], $bucket_dir, $subfoldername);
              } */
        }
    }

    /**
     * 
     * @param type $dbstructure
     * @param type $rootlocation
     * @param type $root_child
     * @param type $bucket_dir
     * @param type $level
     */
    function draw_gadget_location_one_directory($rootlocation, $root_child, $bucket_dir, $campus = null, $building = null) {
        if ($root_child == 'files') {
            self::draw_location_section($rootlocation[$root_child], $bucket_dir, 'GWU', $root_child);
        } else {
            foreach ($rootlocation[$root_child] as $foldername => $folder_array) {
                self::draw_location_section($rootlocation[$foldername], $bucket_dir, $foldername, $root_child, $campus, $building);
            }
        }
    }

    /**
     * 
     * @param type $image_location
     * @param type $bucket_dir
     * @param type $foldername
     */
    function draw_location_section($image_location, $bucket_dir, $foldername, $root_child = NULL, $campus = NULL, $building = NULL) {
        $linkOptions = array(
            'class' => " col-lg-2 col-md-4 col-sm-4 col-xs-10 bottom10 right5 label label-primary medium-font " 
            . (($root_child == 'bottomfolder') ? '' : 'ajax-drilldown'),
            'href' => "#?javascript:void(0)",
            'data-location' => $foldername,
            'data-root' => ($root_child ? $root_child : ''),
            'data-campus' => ($campus ? $campus : ''),
            'data-building' => ($building ? $building : ''),
        );
        $rowDivOptions = array(
            'class' => "row bottom30 ",
        );

        $innerDivOptions = array(
            'class' => "col-xs-12 ",
        );

        $imageDivOptions = array(
            'class' => "col-xs-1 imager",
        );

        echo BSHtml::openTag("div", $rowDivOptions);

        echo BSHtml::openTag("a", $linkOptions);
        echo $foldername;
        echo BSHtml::closeTag("a");

        echo BSHtml::openTag("div", $innerDivOptions);

        if (isset($image_location['images'])) {
            foreach ($image_location['images'] as $image_index => $value) {
                echo BSHtml::openTag("div", $imageDivOptions);

                echo BSHtml::tag("img", array(
                    "src" => $bucket_dir . DIRECTORY_SEPARATOR . "thumb/thumb_" . $value,
                    "alt" => $value
                ));

                echo BSHtml::closeTag("div");
                echo "<!--close imager-->";
            }
        }

        echo BSHtml::closeTag("div");
        echo "<!--close col-xs-12-->";
        echo BSHtml::closeTag("div");
        echo "<!--close row-->";
    }

}