<?php
$bucket_files = ApiHelper::_get_bucket_files('gadgets');
$bucket_dir = ApiHelper::_get_bucket_url('gadgets');
$dbstructure = ApiHelper::_get_db_structure('gadgets');

foreach ($bucket_files as $image) {
    if (!is_array($image)) {
        $image = urlencode($image);
        $url = Yii::app()->createAbsoluteUrl("api/getdir/gadgets/$image");
        $curl_response = Yii::app()->curl->get($url);
        $image_locations[] = (json_decode($curl_response, true));
    }
}
/*$image_locations Returns structure like the following:
Array
(
    ...
    [3] => Array
        (
            [name] => 13110130p9-widget-Lecture Capture.jpg
            [location] => Array
                (
                    [0] => FB\AC0\B152
                    [1] => FB\AC0\B156
                    [2] => FB\CO0\0101
                    [3] => FB\DUQ\0151
 */


?>
<div class="col-md-3 leftCol">
    <ul class="nav nav-stacked nav-collapse sidebar droptrue sortable" id="image_sidebar">
        <!--<?php foreach ($dbstructure['files'] as $folder => $item): ?>
                                                                                                                                                                                            <li class="bottom10 right15 label label-primary"><?= $folder ?></li>
            <?php //ApiHelper_Gadgets::_generate_sidebar_nav($item);  ?>
        <?php endforeach; ?>-->
    </ul>
</div><!--/left-->

<!--right-->


<div class="col-md-6">
    <?php $counter = -1; ?>
    <?php foreach ($image_locations as $item): ?>
        <div class="row bottom15 no-padding">
            <div class="col-sm-12">
                <h2>
                    <?=
                    $item['name'];
                    $counter+=1;
                    ?>
                </h2>
                <div class="col-sm-2 no-padding">
                    <img src='<?= $bucket_dir . DIRECTORY_SEPARATOR . "thumb/thumb_" . $item['name'] ?>' alt='<?= $item['name'] ?>'>
                </div>
                <div class="col-sm-10">
                    <form class="bs-example form-inline" action="<?= Yii::app()->createUrl('gadgets/addlocation'); ?>" method="post">                                       <fieldset>
                            <legend>Add new</legend>

                            <div class="form-group">
                                <input hidden name="AddimageForm[image_name]" value="<?= $item['name'] ?>"/>
                                <label class="control-label sr-only" for="AddimageForm_campus">Campus</label>
                                <div>
                                    <select displaySize="4" class="location-select form-control" name="AddimageForm[campus]" id="AddimageForm_campus" data-script="location_load" data-group="<?= $counter ?>"data-type="campus" data-target="building">
                                        <option value=""></option>
                                        <?php foreach ($dbstructure['files']['root'] as $foldername => $folder_array): ?>

                                            <option value="<?= $foldername; ?>"><?= $foldername; ?></option>

                                        <?php endforeach; ?>
                                    </select>

                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label sr-only" for="AddimageForm_building">Campus</label>
                                <div>
                                    <select displaySize="4" class="location-select form-control" name="AddimageForm[building]" id="AddimageForm_building" data-script="location_load" data-group="<?= $counter ?>" data-type="building" data-target="room" >
                                        <option value=""></option>
                                    </select>

                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label sr-only" for="AddimageForm_room">Campus</label>
                                <div>
                                    <select displaySize="4" class="location-select form-control" name="AddimageForm[room]" id="AddimageForm_room"  data-group="<?= $counter ?>" data-type="room" >
                                        <option value=""></option>
                                    </select>

                                </div>
                            </div>
                            <button class="btn btn-primary" type="submit" name="yt0">Submit</button>    
                        </fieldset>
                    </form>
                </div>
                <div class="col-sm-12 no-padding">
                    <h3>
                        <ul class="bottom10 sortable droptrue no-padding" id="list_<?= urlencode($item['name']); ?>">
                            <?php foreach ($item['location'] as $location): ?>

                                <li class="col-sm-3 bottom10 right15 label label-warning " name="Locations['<? = urlencode($item['name']); ?>']" value ='<?= $location; ?>'><?=
                                    $location;
                                    $counter++;
                                    ?></li>

                            <?php endforeach ?>
                        </ul>
                    </h3>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div><!--end col9-->