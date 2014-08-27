<?php /* @var $this Controller */ ?>
<?php $this->beginContent('//layouts/main'); ?>
<div class="container-fluid">
    <div class="col-lg-8 col-lg-offset-2">
        <div id="content">
            <?php echo $content; ?>
        </div><!-- content -->
    </div>
    <div class="col-md-2">
        <div id="sidebar">
            <div>
                <div class="well">avail users section</div>
            </div>
        </div><!-- sidebar -->
    </div>

</div>
<?php $this->endContent(); ?>