<?php
/* @var $this SiteController */

$this->pageTitle = Yii::app()->name;
?>

<div class="container">
    <div class="row">
        <div class="col-xs-12">
            <div class="col-sm-4 col-sm-offset-2">
                <div class="col-sm-12 no-margin center">
                    <a class="home-icons" href="<?= $this->createUrl('gadgets/'); ?>">
                        <div class="row">
                            <span class="fa-stack large">
                                <i class="fa  fade-bg fa-square-o buff fa-stack-2x " style="text-align:center;"></i>
                                <i class="fa fa-image fa-stack-1x"></i>
                            </span>
                        </div>
                        <div class="row">
                            <h1 class="close-text-top">Gadgets</h1>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="col-sm-12 center no-margin">
                    <a class="home-icons" href="<?= $this->createUrl('wepa/'); ?>">
                        <div class="row">
                            <span class="fa-stack large">
                                <i class="fa fa-square-o buff fa-stack-2x " style="text-align:center;"></i>
                                <i class="fa fa-print fa-stack-1x"></i>
                                
                            </span>
                        </div>
                        <div class="row">
                            <h1 class="close-text-top">WEPA</h1>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>