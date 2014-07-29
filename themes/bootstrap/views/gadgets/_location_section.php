

<!--left-->

<div class="col-md-3 leftCol" >
    <ul class="nav nav-stacked nav-collapse sidebar" id="location_sidebar">
    </ul>
</div><!--/left-->


<!--right-->
<div class="col-md-9 " >
    <?php
    /*
      echo "<pre>";
      print_r($dbstructure);
      echo "</pre>";
      die()*
     */
    ?>
    <?php ApiHTML::draw_location_section($dbstructure['files'], $bucket_dir, "GWU"); ?>

    <?php ApiHelper_Gadgets::draw_gadget_location_recursive($dbstructure, $dbstructure['files'], 'root', $bucket_dir, true); ?>

</div><!--end col9-->

