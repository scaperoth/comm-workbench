<?php
/* @var $this GadgetsController */
/* @var $model UploadForm */
/* @var $form CActiveForm  */
?>



<fieldset>


    <form id="login-form" action="<?= Yii::app()->createUrl('gadgets/upload'); ?>" method="post" enctype="multipart/form-data">    
        <div class="container">
            <div class=" col-sm-5 col-sm-offset-3">
                <legend><h1>Gadgets Upload</h1></legend>

                <div class="form-group"><div>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-folder">

                                </i>
                            </span>
                            <input type="file" name="file" id="file" class="form-control" placeholder="File Name" type="text" />                                 
                        </div>
                        <p id="UploadForm_file_em_" style="display:none" class="help-block">

                        </p>
                    </div>
                </div>

                <button class="btn btn-primary" type="submit" name="yt0">Submit</button>
                </form>
            </div>
        </div>
</fieldset>
