<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
$bucket_files = ApiHelper::_get_bucket_files('gadgets');
$bucket_dir = ApiHelper::_get_bucket_url('gadgets');
$dbstructure = ApiHelper::_get_db_structure('gadgets')[ApiHelper::TOPHOLDER];


foreach ($bucket_files as $image) {
    if (!is_array($image)) {
        $image = urlencode($image);
        $url = Yii::app()->createAbsoluteUrl("api/getdir/gadgets/$image");
        $curl_response = Yii::app()->curl->get($url);
        $image_locations[] = (json_decode($curl_response, true));
    }
}
?>



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
    <!--left-->
    <div class="col-md-3 leftCol image-section ">
        <ul class="nav nav-stacked nav-collapse sidebar droptrue sortable" id="image_sidebar">
            <!--<?php foreach ($dbstructure['files'] as $folder => $item): ?>
                                                                                                                                <li class="bottom10 right15 label label-primary"><?= $folder ?></li>
                <?php //ApiHelper_Gadgets::_generate_sidebar_nav($item); ?>
            <?php endforeach; ?>-->
        </ul>
    </div><!--/left-->

    <!--right-->
    <div class="col-md-9 image-section">
        <?php $counter = -1;?>
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
                        <form class="bs-example form-inline" action="/comm-workbench/index.php/gadgets" method="post">                                       <fieldset>
                                <legend>Add new</legend>
                                <div class="form-group">
                                    <label class="control-label sr-only" for="AddimageForm_campus">Campus</label>
                                    <div>
                                        <select displaySize="4" name="AddimageForm[campus]" id="AddimageForm_campus" data-group="<?=$counter?>"data-type="gadgetcampus">
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
                                        <select displaySize="4" name="AddimageForm[building]" id="AddimageForm_building" data-group="<?=$counter?>" data-type="gadgetbuilding">
                                            <option value=""></option>
                                            <?php foreach ($dbstructure['files']['FB']['subfolder'] as $foldername => $folder_array): ?>

                                                <option value="<?= $foldername; ?>"><?= $foldername; ?></option>

                                            <?php endforeach; ?>
                                        </select>

                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label sr-only" for="AddimageForm_room">Campus</label>
                                    <div>
                                        <select displaySize="4" name="AddimageForm[room]" id="AddimageForm_room" data-group="<?=$counter?>" data-type="gadgetroom">
                                            <option value=""></option>
                                            <?php foreach ($dbstructure['files']['FB']['AC0']['bottomfolder'] as $foldername => $folder_array): ?>

                                                <option value="<?= $foldername; ?>"><?= $foldername; ?></option>

                                            <?php endforeach; ?>
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

                                    <li class="col-sm-3 bottom10 right15 label label-warning " name="Locations['<?= urlencode($item['name']); ?>']" value ='<?= $location; ?>'><?=
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

    <!--left-->
    <div class="col-md-3 leftCol location-section">
        <ul class="nav nav-stacked nav-collapse sidebar" id="location_sidebar">
        </ul>
    </div><!--/left-->

    <!--right-->
    <div class="col-md-9 location-section" hidden>


    </div><!--end col9-->

</div><!--end row-->

