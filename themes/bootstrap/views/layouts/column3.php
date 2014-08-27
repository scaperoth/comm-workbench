<?php /* @var $this Controller */ ?>
<?php $this->beginContent('//layouts/main'); ?>
<div class="container-fluid">
    <div class="col-lg-3">
    </div>
    <div class="col-lg-6">
        <div id="content">
            <?php echo $content; ?>
        </div><!-- content -->
    </div>
    <div class="col-lg-3">
        <div id="sidebar">
            <div>
                <div class="well">avail users section</div>
            </div>
        </div><!-- sidebar -->
    </div>

</div>
<?php $this->endContent(); ?>