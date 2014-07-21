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
    
    
}

?>
