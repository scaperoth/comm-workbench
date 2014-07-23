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
/* $image_locations Returns structure like the following:
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
<script>
    function allowDrop(ev) {
        ev.preventDefault();
    }

    function drag(ev, id) {
        ev.dataTransfer.setData("Text", ev.target.id);
        ev.dataTransfer.setData("root", id);
        

    }

    function drop(ev) {

        ev.stopPropagation();
        ev.preventDefault();
        
        var data = ev.dataTransfer.getData("Text");
        var parent = $(ev.target).parent();
        $(parent).append(document.getElementById(data).cloneNode(true));
        
        //add reaction script
    }
</script>
<div class="col-md-3 leftCol" id="bucket">
    <span id="drag_GWU" class="col-sm-2 bottom10 right5 label label-primary " value ='GWU' draggable="true" ondragstart="drag(event, $(this).attr('id'))">GWU</span>
    <?php foreach ($dbstructure['files']['root'] as $foldername => $folder_array): ?>

        <span id="drag_<?= $foldername ?>" class="col-sm-2 bottom10 right5 label label-primary " value ='<?= $foldername; ?>' draggable="true" ondragstart="drag(event, $(this).attr('id'))"><?= $foldername; ?></span>


        <?php foreach ($dbstructure['files'][$foldername]['subfolder'] as $subfoldername => $subfolder_array): ?>

            <span id="drag_<?= $subfoldername ?>"  class="col-sm-2 bottom10 right5 label label-primary " value ='<?= $subfoldername; ?>' draggable="true" ondragstart="drag(event, $(this).attr('id'))"><?= $subfoldername; ?></span>

            <?php foreach ($dbstructure['files'][$foldername][$subfoldername]['bottomfolder'] as $bottomfoldername => $bottomfolder_array): ?>

                <span id="drag_<?= $bottomfoldername ?>" class="col-sm-2 bottom10 right15 label label-primary " value ='<?= $bottomfoldername; ?>' draggable="true" ondragstart="drag(event, $(this).attr('id'))"><?= $bottomfoldername; ?></span>

            <?php endforeach; ?>
        <?php endforeach; ?>
    </ul>
<?php endforeach; ?>
</ul
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
                <div class="col-sm-2 no-padding bottom15">
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
                                        <option disabled selected value=""></option>
                                    </select>

                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label sr-only" for="AddimageForm_room">Campus</label>
                                <div>
                                    <select displaySize="4" class="location-select form-control" name="AddimageForm[room]" id="AddimageForm_room"  data-group="<?= $counter ?>" data-type="room" >
                                        <option disabled selected  value=""></option>
                                    </select>

                                </div>
                            </div>
                            <button class="btn btn-primary" type="submit" name="yt0">Submit</button>    
                        </fieldset>
                    </form>
                </div>
                <div class="col-sm-12 bottom10 no-padding location" id="list_<?= urlencode($item['name']); ?>" ondrop="drop(event)" ondragover="allowDrop(event)">

                    <?php foreach ($item['location'] as $location): ?>
                        <span class="col-sm-2 right15 bottom10 label label-warning " id="$item['location']" name="Locations['<? = urlencode($item['name']); ?>']" value ='<?= $location; ?>'><?=
                            $location;
                            $counter++;
                            ?>
                        </span>

                    <?php endforeach ?>

                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div><!--end col9-->