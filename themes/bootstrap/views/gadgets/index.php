<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

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
            <div class="btn-group " data-toggle="buttons">
                <label class="btn btn-lg btn-primary active"  id="image-view-button">
                    <input type="radio" name="options"  checked> Image View
                </label>
                <label class="btn btn-lg btn-primary" hidden id="location-view-button" >
                    <input type="radio" name="options" > Location View
                </label>
            </div>
        </div>

        <div id="ajax_panel"></div>
    </div>
    <!--left-->
    <div class=" image-section">
        <?php $this->renderPartial('_image_section'); ?>

    </div><!--end image-section-->

    <div class="location-section" hidden>
        <?php $this->renderPartial('_location_section'); ?>
    </div><!--end location-section-->

</div><!--end row-->

<?
?>