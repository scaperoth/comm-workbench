

<!--left-->

<div class="col-md-3 leftCol" style="height:300px">
    <ul class="nav nav-stacked nav-collapse sidebar" id="location_sidebar">
    </ul>
</div><!--/left-->

<div class="col-md-6" style="min-height:50px">
    <div class="pull-right">
        <span id="location-nav" class="hidden" data-location data-campus data-building data-root class="fa-stack fa-lg" data-toggle="tooltip" data-placement="bottom" title="Navigate Up">
            <i class="fa fa-arrow-circle-up fa-3x fade-bg buff"></i>
        </span>
    </div>
</div>

<!--right-->
<div class="col-md-9 show-locations" >
    <?php ApiHelper_Gadgets::draw_gadget_location_one_directory($dbstructure, 'files', $bucket_dir); ?>



</div><!--end col9-->

