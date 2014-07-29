<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class ApiHTML extends CHtml {

    public static function _generate_sidebar_nav($root, $output = '') {

        if (is_array($root)) {
            foreach ($root as $subfolder => $subitem) {
                echo BSHtml::openTag('li');
                echo BSHtml::openTag('a', array('href' => '#'));
                echo $subfolder;
                echo BSHtml::closeTag('a');
                echo BSHtml::closeTag('li');
                self::_generate_sidebar_nav($subitem, $output);
            }
        } else {
            echo BSHtml::openTag('ul');
            echo BSHtml::openTag('li');
            echo BSHtml::openTag('a', array('href' => '#'));
            echo $root;
            echo BSHtml::closeTag('a');
            echo BSHtml::closeTag('li');
            BSHtml::closeTag('ul', $output);
        }

        return;
    }


    /**
     * 
     * @param type $image_location
     * @param type $bucket_dir
     * @param type $foldername
     */
    function draw_location_section($image_location, $bucket_dir, $foldername) {
        $linkOptions = array(
            'class' => " col-lg-2 col-md-4 col-sm-4 col-xs-10 bottom10 right5 label label-primary medium-font",
            'href' => "#?javascript:void(0)",
        );
        $rowDivOptions = array(
            'class' => "row bottom30",
        );

        $innerDivOptions = array(
            'class' => "col-xs-12",
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

?>
