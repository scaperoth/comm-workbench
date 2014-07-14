<?php
/* @var $this SiteController */

$this->pageTitle = Yii::app()->name;

$themePath = Yii::app()->theme->baseUrl;
$bucket = $themePath . 'assets/images/gadget_images/';

/*
//next example will insert new conversation
$service_url = 'http://localhost/comm-workbench/index.php/api/imagedir/all';
$curl = curl_init($service_url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
$curl_response = curl_exec($curl);
curl_close($curl);
$decoded = json_decode($curl_response);
if (isset($decoded->response->status) && $decoded->response->status == 'ERROR') {
    die('error occured: ' . $decoded->response->errormessage);
}


var_dump($decoded);

$cursor = Yii::app()->mongodb->gadgets->find();


foreach ($cursor as $doc) {
    echo '<pre>';
    print_r($doc);
    echo '</pre>';
}
 *  
 */
 
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

                    <div class="col-md-12 well">
                        image
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