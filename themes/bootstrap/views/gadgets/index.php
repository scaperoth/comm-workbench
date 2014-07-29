<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
$args = array('image_locations' => $image_locations, 'dbstructure' => $dbstructure, 'bucket_dir' => $bucket_dir, 'bucket_files' => $bucket_files);

$whichpage = 'image';
if (isset($_GET['page_id']))
    $whichpage = $_GET['page_id'];
?>



<div class="row">
    <!--controls-->
    <div class="col-md-12 bottom30">
        <div class="center">
            <?php
            echo BsHtml::buttonGroup(array(
                array(
                    'label' => 'Save',
                    'url' => Yii::app()->createAbsoluteUrl('gadgets/save'),
                    'icon' => 'save fw',
                    'type' => BsHtml::BUTTON_TYPE_LINK
                ),
                array(
                    'label' => 'Load',
                    'url' => Yii::app()->createAbsoluteUrl('gadgets/load'),
                    'icon' => 'download fw',
                    'type' => BsHtml::BUTTON_TYPE_LINK
                ),
                array(
                    'label' => 'Publish',
                    'url' => Yii::app()->createAbsoluteUrl('gadgets/publish'),
                    'icon' => 'check fw',
                    'type' => BsHtml::BUTTON_TYPE_LINK
                )
                    ), array(
                'color' => BsHtml::BUTTON_COLOR_WARNING,
                'type' => BsHtml::BUTTON_TYPE_LINK
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
                ),
                array(
                    'label' => 'Location View',
                    'url' => '?page_id=location',
                    'class' => ' btn btn-lg btn-primary ' . (($whichpage == 'location') ? "active" : ""),
                    'name' => 'options',
                    'type' => BsHtml::BUTTON_TYPE_LINK,
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
</div><!--end row-->

<? ?>