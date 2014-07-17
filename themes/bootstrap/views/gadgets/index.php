<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

$bucket_files = ApiHelper::_get_bucket_files('gadgets');
$bucket_dir = ApiHelper::_get_bucket_url('gadgets');

foreach ($bucket_files as $image) {
    if (!is_array($image)) {
        $image = urlencode($image);
        $url = Yii::app()->createAbsoluteUrl("api/getdir/gadgets/$image");
        $curl_response = Yii::app()->curl->get($url);
        $image_locations[] = (json_decode($curl_response, true));
    }
}
?>

<div class="container">

    <div class="row">
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
        </div>
    </div>
    <p></p>
    <div class="row">
        <div class="col-md-12">
            <div class="col-md-4">
                <div class="col-md-12 well image-section" id="image-sidebar" style="height:300px;">
                    image
                </div><!--end left sidebar-->

                <div class="col-md-12 well location-section" hidden id="location-sidebar" style="height:300px;">
                    location
                </div><!--end left sidebar-->
            </div><!--end 4 col-->
            <div class="col-md-8 col-xs-12">


                <div id="image-view" class="image-section">

                    <div class="col-md-12">
                        <?php foreach ($image_locations as $item): ?>
                            <div class="row bottom15">
                                <div class="col-sm-12">
                                    <h2><?= $item['name'] ?></h2>
                                    <img src='<?= $bucket_dir . DIRECTORY_SEPARATOR . "thumb/thumb_" . $item['name'] ?>' alt='<?= $item['name'] ?>'>
                                </div>
                                <div class="col-sm-12">
                                    <h3>
                                        <?php foreach ($item['location'] as $location): ?>
                                            <div class="col-sm-3 bottom10 no-padding">
                                                <span class="label label-warning"><?= $location ?></span>
                                            </div>
                                        <?php endforeach ?>
                                    </h3>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                </div><!--end image view-->

                <div id="location-view" hidden class="location-section">
                    <div class="col-md-12 well">
                        location
                    </div>

                </div><!--end location view-->

            </div><!--end 8 col-->
        </div>
    </div><!--end row-->
</div><!--end container-->

