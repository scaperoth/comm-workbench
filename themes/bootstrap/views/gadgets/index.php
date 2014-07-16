<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


$themePath = Yii::app()->theme->baseUrl;
$bucket = $themePath . 'assets/images/gadget_images/';

/*
 * Yii::app()->createAbsoluteUrl('api/bucketdir/'.$this->service)
 * 
 */
//next example will insert new conversation
$url = Yii::app()->createAbsoluteUrl('api/filestructure/gadgets');
$curl_response = Yii::app()->curl->get($url);
$files = json_decode($curl_response);
if (isset($files->response->status) && $files->response->status == 'ERROR') {
    die('error occured: ' . $decoded->response->errormessage);
}

//echo '<pre>';
//print_r($files);
//echo '</pre>';


$url = Yii::app()->createAbsoluteUrl('api/bucketfiles/gadgets');
$curl_response = Yii::app()->curl->get($url);
$bucket = json_decode($curl_response);
if (isset($bucket->response->status) && $bucket->response->status == 'ERROR') {
    die('error occured: ' . $decoded->response->errormessage);
}

//var_dump($bucket);

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
    <div id="accordion" class="panel-group">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">1. What is HTML?</a>
                </h4>
            </div>
            <div id="collapseOne" class="panel-collapse collapse">
                <div class="panel-body">
                    <p>HTML stands for HyperText Markup Language. HTML is the main markup language for describing the structure of Web pages. <a href="http://www.tutorialrepublic.com/html-tutorial/" target="_blank">Learn more.</a></p>
                </div>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">2. What is Twitter Bootstrap?</a>
                </h4>
            </div>
            <div id="collapseTwo" class="panel-collapse collapse in">
                <div class="panel-body">
                    <p>Twitter Bootstrap is a powerful front-end framework for faster and easier web development. It is a collection of CSS and HTML conventions. <a href="http://www.tutorialrepublic.com/twitter-bootstrap-tutorial/" target="_blank">Learn more.</a></p>
                </div>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseThree">3. What is CSS?</a>
                </h4>
            </div>
            <div id="collapseThree" class="panel-collapse collapse">
                <div class="panel-body">
                    <p>CSS stands for Cascading Style Sheet. CSS allows you to specify various style properties for a given HTML element such as colors, backgrounds, fonts etc. <a href="http://www.tutorialrepublic.com/css-tutorial/" target="_blank">Learn more.</a></p>
                </div>
            </div>
        </div>
    </div>
</div><!--end container-->
