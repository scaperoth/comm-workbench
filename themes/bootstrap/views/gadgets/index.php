<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
$args = array('image_locations' => $image_locations, 'dbstructure' => $dbstructure, 'bucket_dir' => $bucket_dir, 'bucket_files' => $bucket_files);

$whichpage = 'image';
if (isset($_GET['page_id']))
    $whichpage = $_GET['page_id'];

$cs = Yii::app()->clientScript;
//gadgets ajax variables
$cs->registerScript('dbstructure_script', "var dbstructure = " . json_encode($dbstructure) . ";");
$cs->registerScript('bucketdir_script', "var bucketdir =  " . json_encode($bucket_dir) . ";");
$cs->registerScript('gadget_get_ajax_url', ' var getlocationajaxurl ="' . $this->createUrl('getlocationdataajax') . '";');
$cs->registerScript('gadget_add_ajax_url', ' var addlocationajaxurl ="' . $this->createUrl('addlocationajax') . '";');
$cs->registerScript('gadget_remove_ajax_url', ' var removelocationajaxurl ="' . $this->createUrl('removelocationfromimageajax') . '";');
$cs->registerScript('gadget_drilldown_ajax_url', ' var drilldownajaxurl ="' . $this->createUrl('drawlocationsajax') . '";');
$cs->registerScript('gadget_script', file_get_contents('themes/bootstrap/assets/js/gadget_script.js'));
$cs->registerScript('gadget_ajax_functions', file_get_contents('themes/bootstrap/assets/js/gadget_ajax.js'));
?>


<div class="row">
    <!--controls-->
    <div class="col-md-12 bottom30">
        <div class="center">
            <?php
            echo BsHtml::buttonGroup(array(
                array(
                    'label' => 'Save',
                    'url' => Yii::app()->createAbsoluteUrl('site/save') . '?service=gadgets',
                    'icon' => 'save fw',
                    'type' => BsHtml::BUTTON_TYPE_LINK,
                ),
                array(
                    'label' => 'Load',
                    'url' => Yii::app()->createAbsoluteUrl('site/load') . '?service=gadgets',
                    'icon' => 'download fw',
                    'type' => BsHtml::BUTTON_TYPE_LINK,
                ),
                array(
                    'label' => 'Publish',
                    'url' => Yii::app()->createAbsoluteUrl('site/publish') . '?service=gadgets',
                    'icon' => 'check fw',
                    'type' => BsHtml::BUTTON_TYPE_LINK,
                )
                    ), array(
            ));
            ?>
        </div>
    </div><!--/controls-->
    <div class="col-md-12">
        <div class="center">
            <?php
            echo BsHtml::buttonGroup(array(
                array(
                    'label' => 'Image View',
                    'url' => '?page_id=image',
                    'class' => ' btn btn-lg btn-primary ' . (($whichpage == 'image') ? "active" : ""),
                    'name' => 'options',
                    'type' => BsHtml::BUTTON_TYPE_LINK,
                    'color' => BSHtml::BUTTON_COLOR_PRIMARY,
                ),
                array(
                    'label' => 'Location View',
                    'url' => '?page_id=location',
                    'class' => ' btn btn-lg btn-primary ' . (($whichpage == 'location') ? "active" : ""),
                    'name' => 'options',
                    'type' => BsHtml::BUTTON_TYPE_LINK,
                    'color' => BSHtml::BUTTON_COLOR_PRIMARY,
                ),
                    ), array(
            ));
            ?>
        </div>

        <div id="ajax_panel"></div>
    </div>
    <!--left-->
    <?php if ($whichpage != 'location'): ?>
        <div class=" image-section" <?= (($whichpage == 'location') ? "hidden" : ""); ?>>
            <?php $this->renderPartial('_image_section', $args); ?>
        </div><!--end image-section-->
    <?php endif; ?>

    <?php if ($whichpage == 'location'): ?>
        <div class="location-section" <?= (($whichpage == 'image') ? "hidden" : ""); ?> >
            <?php $this->renderPartial('_location_section', $args); ?>
        </div><!--end location-section-->
    <?php endif; ?>
    <div class="col-sm-3 hidden-xs center pull-right ">
        
        <div class="col-sm-12  sidebar-affix" data-spy="affix" data-offset-top="300">
            <legend>Trash</legend>
            <div class="trashcan center trash "  >
            </div>
        </div>
    </div>
</div><!--end row-->

<? ?>