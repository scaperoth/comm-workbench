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
</script>
<div class="col-md-3 leftCol" id="bucket">
    <span id="drag_GWU" class="col-sm-2 bottom10 right5 label label-primary" data-campus ='' draggable="true" >GWU</span>
    <?php foreach ($dbstructure['files']['root'] as $foldername => $folder_array): ?>

        <span id="drag_<?= $foldername ?>" class="col-sm-2 bottom10 right5 label label-primary" data-campus='<?= $foldername ?>'  draggable="true" ><?= $foldername; ?></span>


        <?php foreach ($dbstructure['files'][$foldername]['subfolder'] as $subfoldername => $subfolder_array): ?>

            <span id="drag_<?= $subfoldername ?>"  class="col-sm-2 bottom10 right5 label label-primary" data-campus='<?= $foldername ?>' data-building="<?=$subfoldername?>" draggable="true" ><?= $subfoldername; ?></span>

            <?php foreach ($dbstructure['files'][$foldername][$subfoldername]['bottomfolder'] as $bottomfoldername => $bottomfolder_array): ?>

                <span id="drag_<?= $bottomfoldername ?>" class="col-sm-2 bottom10 right15 label label-primary" data-campus ='<?= $foldername ?>' data-building="<?=$subfoldername?>" data-room="<?=$bottomfoldername?>"  draggable="true"><?= $bottomfoldername; ?></span>

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
                
                <div class="col-sm-12 bottom10 no-padding location" data-image ="<?=$item['name'];?>" >

                    <?php foreach ($item['location'] as $location): ?>
                        <?php $image_location = ($location=="GWU"?"":$location.DIRECTORY_SEPARATOR).urlencode($item['name'])?>
                        <span data-toggle="tooltip" data-placement="top" title="Click to delete" class="col-sm-2 right15 bottom10 label label-warning image-location" data-image='<?=$image_location?>'><?=
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