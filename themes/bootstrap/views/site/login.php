<?php
/* @var $this SiteController */
/* @var $model LoginForm */
/* @var $form CActiveForm  */

$this->pageTitle = Yii::app()->name . ' - Login';
$this->breadcrumbs = array(
    'Login',
);
?>
<fieldset>
    
    <?php
    $form = $this->beginWidget('BsActiveForm', array(
        'id' => 'login-form',
        'enableAjaxValidation' => true,
        'enableClientValidation' => true,
    ));
    ?>
    <div class="container">
        <div class=" col-sm-3 col-sm-offset-4">
            <legend><h1>Login</h1></legend>
            <?php
            echo $form->textFieldControlGroup($model, 'username', array(
                'placeholder' => 'Username',
                'prepend' => BSHTML::icon('user'),
            ));
            ?>


            <?php
            echo $form->passwordFieldControlGroup($model, 'password', array(
                'placeholder' => 'Password',
                'prepend' => BSHTML::icon('lock'),
            ));
            ?>

            <?php echo $form->checkBoxControlGroup($model, 'rememberMe'); ?>

            <?php
            echo BSHtml::submitButton('Submit', array(
                'color' => BSHtml::BUTTON_COLOR_PRIMARY
            ));
            ?>

            <?php $this->endWidget(); ?>
        </div>
    </div>
</fieldset>

