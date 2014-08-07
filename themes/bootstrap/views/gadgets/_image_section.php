<script>
</script>
<div class="col-xs-3 leftCol left15" id="sidebar">
    <div data-spy="affix" data-offset-top="220" class="hidden-xs col-sm-12" id="sidebar-affix">
        <div class="bottom30 row">
            <form class="bs-example form-inline " action="<?= Yii::app()->createUrl('gadgets/addlocation'); ?>" method="post">                                       <fieldset>
                    <legend>Filter Campus/Building</legend>

                    <div class="form-group">
                        <label class="control-label sr-only" for="AddimageForm_campus">Campus</label>
                        <div>
                            <select data-toggle="tooltip" data-placement="top" title="Campus" displaySize="4" class="location-select sidebar-select form-control" name="AddimageForm[campus]" id="AddimageForm_campus" data-script="location_load" data-group="0" data-type="campus" data-target="building">
                                <option value=""></option>
                                <?php foreach ($dbstructure['files']['root'] as $foldername => $folder_array): ?>

                                    <option value="<?= $foldername; ?>"><?= $foldername; ?></option>

                                <?php endforeach; ?>
                            </select>

                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label sr-only" for="AddimageForm_building">Building</label>
                        <div>
                            <select displaySize="4" data-toggle="tooltip" data-placement="top" title="Building" class="location-select sidebar-select form-control" name="AddimageForm[building]" id="AddimageForm_building" data-script="location_load" data-group="0" data-type="building" data-target="room" >
                                <option disabled selected value=""></option>
                            </select>

                        </div>
                    </div>
                </fieldset>
            </form>
        </div><!--end filter form-->
        <div class="row">
            <legend>Drag and Drop</legend>
            <div id="bucket_list" data-type="bucket_list" >

                <a href="#?javascript:void(0)"  id="drag_GWU" class=" col-lg-2 col-md-4 col-sm-4 col-xs-10 bottom10 right5 label label-primary medium-font" data-campus ='' draggable="true" >GWU</a>
                <?php foreach ($dbstructure['files']['root'] as $foldername => $folder_array): ?>

                    <a href="#?javascript:void(0)"  id="drag_<?= $foldername ?>" class=" col-lg-2 col-md-4 col-sm-4 col-xs-10 bottom10 right5 label label-primary medium-font" data-campus='<?= $foldername ?>'  draggable="true" ><?= $foldername; ?></a>

                <?php endforeach; ?>
            </div><!--end bucket-->
        </div> <!--end bucket container-->
        <div class="row">
            <legend >Delete</legend>
            <div class="trashcan  row col-sm-4 " >

                <i class="fa fa-folder-o fa-border fa-lg fa-5x"></i> 
            </div>
        </div>
    </div><!-- end affix container-->
</div><!--/left-->

<!--right-->

<div class="col-sm-7 col-xs-10 col-xs-offset-1 col-sm-offset-0">
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

                <div class="col-sm-12 bottom10 no-padding dropper <?= ((count($item['location']) == 0) ? 'well' : ''); ?>" data-image ="<?= $item['name']; ?>" >

                    <?php foreach ($item['location'] as $location): ?>
                        <?php
                        $image_location = ($location == "GWU" ? "" : $location . DIRECTORY_SEPARATOR) . urlencode($item['name']);
                        $linkOptions = array(
                            'href' => "#?javascript:void(0);",
                            'draggable' => "true",
                            'id' => "trashable_" . $location . "_" . urlencode($item['name']),
                            'class' => "col-lg-2 col-md-4 col-sm-4 col-xs-10 right15 bottom10 label label-warning pre-delete medium-font trashable",
                            'data-image' => $image_location,
                            'data-location' => $location,
                        );
                        echo BSHtml::tag('a', $linkOptions, $location);

                        $counter++;
                        ?>
                    <?php endforeach ?>

                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div><!--end col9-->