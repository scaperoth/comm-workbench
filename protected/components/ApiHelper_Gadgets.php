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
        $new_array = $rootlocation[$root_child];
        array_multisort(array_values($new_array), SORT_DESC, array_keys($new_array), SORT_ASC, $new_array);
        
        foreach ($new_array as $foldername => $folder_array) {
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
        $new_array = $rootlocation[$root_child];
        array_multisort(array_values($new_array), SORT_DESC, array_keys($new_array), SORT_ASC, $new_array);
        if ($root_child == 'files') {
            $foldername = 'GWU';
            self::draw_location_section($new_array, $bucket_dir, $foldername, $root_child);
        } else {

            foreach ($new_array as $foldername => $folder_array) {

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

        $datalocation = $foldername;
        $dataroot = ($root_child ? $root_child : '');
        $datacampus = ($campus ? $campus : '');
        $databuilding = ($building ? $building : '');
        $location = '';
        $next_step = 'root';
        $well = ((empty($image_location['images'])) ? 'well' : '');

        if ($foldername != 'GWU') {
            switch ($dataroot) {
                case 'root':
                    $campus = $datalocation;
                    $building = $databuilding;
                    $room = '';
                    $next_step = 'subfolder';
                    break;
                case 'subfolder':
                    $campus = $datacampus;
                    $building = $datalocation;
                    $room = '';
                    $next_step = 'bottomfolder';
                    break;
                case 'bottomfolder':
                    $campus = $datacampus;
                    $building = $databuilding;
                    $room = $datalocation;
                    $next_step = '#';
                    break;
                default:
                    $campus = $datalocation;
                    $building = $databuilding;
                    $room = '';
                    break;
            }

            $location = ($building ? $campus . "/" : $campus) . ($room ? $building . "/" : $building) . $room . "/";
        }

        $linkOptions = array(
            'class' => " col-lg-2 col-md-4 col-sm-4 col-xs-10 bottom10 right5 label label-primary medium-font "
            . (($root_child == 'bottomfolder') ? '' : 'ajax-drilldown'),
            'href' => "#?javascript:void(0)",
            'data-drilldown' => $next_step,
            'data-location' => $datalocation,
            'data-root' => $dataroot,
            'data-campus' => $datacampus,
            'data-building' => $databuilding,
        );
        $rowDivOptions = array(
            'class' => "row bottom30 ",
        );

        $innerDivOptions = array(
            'class' => "col-xs-10 dropper no-padding $well",
            'data-location' => $foldername,
            'data-root' => ($root_child ? $root_child : ''),
            'data-campus' => ($campus ? $campus : ''),
            'data-building' => ($building ? $building : ''),
        );

        $imageDivOptions = array(
        );

        $imageLinkOptions = array(
            'class' => "col-xs-2 imager pre-delete no-padding",
            'href' => '#?javascript:void(0)',
            'draggable' => 'true',
        );

        echo BSHtml::openTag("div", $rowDivOptions);

        echo BSHtml::openTag("a", $linkOptions);
        echo $foldername;
        echo BSHtml::closeTag("a");

        echo BSHtml::openTag("div", $innerDivOptions);

        if (isset($image_location['images'])) {
            foreach ($image_location['images'] as $image_index => $value) {
                $imageLinkOptions['data-image'] = $location . urlencode($value);
                $imageLinkOptions['id'] = 'trashable_' . urlencode($value);

                //echo BSHtml::openTag("div", $imageDivOptions);
                echo BSHtml::openTag("a", $imageLinkOptions);
                echo BSHtml::tag("img", array(
                    "src" => $bucket_dir . "thumb/thumb_" . $value,
                    "alt" => $value,
                    'class' => 'image',
                    'draggable' => 'true',
                    'id' => $foldername . "_" . urlencode($value)
                ));
                echo BSHtml::closeTag("a");
                //echo BSHtml::closeTag("div");
                echo "<!--close imager-->";
            }
        }

        echo BSHtml::closeTag("div");
        echo "<!--close col-xs-12-->";
        echo BSHtml::closeTag("div");
        echo "<!--close row-->";
    }

}