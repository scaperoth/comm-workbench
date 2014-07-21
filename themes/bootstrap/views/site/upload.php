<?php
/* @var $this GadgetsController */
/* @var $model UploadForm */
/* @var $form CActiveForm  */
?>



<fieldset>


    <form id="login-form" action="<?= Yii::app()->createUrl('site/upload'); ?>" method="post" enctype="multipart/form-data">    
        <div class="container">
            <div class=" col-sm-5 col-sm-offset-3">
                <legend><h1>Upload</h1></legend>

                <div class="form-group"><div>
                        <div class="form-group">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-cubes">

                                    </i>
                                </span>
                                <select name="service" id="service" class="form-control">
                                    <option value="gadgets">Gadgets</option>
                                    <option value="wepa">WEPA</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-folder">

                                    </i>
                                </span>
                                <input type="file" name="file[]" multiple id="file" class="form-control"  />                                 
                            </div>
                            <p id="UploadForm_file_em_" style="display:none" class="help-block">

                            </p>
                        </div>
                    </div>
                </div>

                <button class="btn btn-primary" type="submit" name="Submit">Submit</button>
                </form>
            </div>
        </div>
</fieldset>
