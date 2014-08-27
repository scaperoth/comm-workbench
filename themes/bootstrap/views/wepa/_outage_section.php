<?php ?>

<!--left-->
<div class="col-xs-3 leftCol " id="sidebar">
    <div data-spy="affix" data-offset-top="300" class="hidden-xs col-sm-12 sidebar-affix" >
        <div class="bottom30 ">
            <form class="bs-example form-inline  " action="<?= Yii::app()->createUrl('gadgets/addlocation'); ?>" method="post">                                       <fieldset>
                    <legend>Bucket Images</legend>

                    <div class="form-group" id="bucket_list">
                        <div>
                            <?php foreach ($outage_bucket_files as $image): ?>
                                <?php if (!is_array($image) && !empty($image)): ?>
                                    <div class="col-sm-5 no-padding bottom15 ">
                                        <a href="#?javascript:void(0)" data-image="<?= $image ?>" class="col-xs-1 imager" id="link_<?= $image ?>" draggable="true">
                                            <?php
                                            $imageHtmlOptions = array(
                                                'data-image' => urlencode($image),
                                                'id' => "drag_" . $image,
                                                'src' => $bucket_dir . "thumb/thumb_" . $image,
                                                'alt' => $image,
                                                'draggable' => 'true',
                                            );
                                            echo BSHtml::tag('img', $imageHtmlOptions);
                                            ?>
                                        </a>
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </fieldset>
            </form>
        </div><!--end filter form-->
    </div><!-- end affix container-->
</div><!--/left-->

<div class="col-sm-5 col-xs-10 col-xs-offset-1 col-sm-offset-1">
    <div class="col-sm-12 pull-right" style="min-height:50px">
        <div class="pull-left">
            <span id="location-nav" class="hidden" data-location data-campus data-building data-root class="fa-stack fa-lg" data-toggle="tooltip" data-placement="bottom" title="Navigate Up">
                <i class="fa fa-arrow-circle-left fa-3x fade-bg buff"></i>
            </span>
        </div>
    </div>

    <!--right-->
    <div class="col-sm-12 container show-locations " >
        <?php
        ApiHTML::draw_location_one_directory($dbstructure['files'], 'root', $bucket_dir);
        ?>
    </div><!--end show-locations-->
</div><!--end col-sm-7-->
