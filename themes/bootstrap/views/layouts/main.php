<?php /* @var $this Controller */ ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="language" content="en" />

        <?php
        $cs = Yii::app()->clientScript;
        $themePath = Yii::app()->theme->baseUrl;

        /**
         * StyleSHeets
         */
        $cs->registerCssFile('http://fonts.googleapis.com/css?family=Abril+Fatface|Open+Sans:300italic,400italic,400,300'); //google fonts
        $cs->registerCssFile($themePath . '/assets/css/bootstrap.min.css');
        $cs->registerCssFile('//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css');
        $cs->registerCssFile('//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap-theme.min.css');
        //$cs->registerCssFile($themePath . '/assets/css/bootstrap-theme.min.css');
        $cs->registerCssFile($themePath . '/assets/fractionslider/fractionslider.css');
        $cs->registerCssFile($themePath . '/assets/css/style.css');
        $cs->registerCssFile($themePath . '/assets/font-awesome-4.1.0/css/font-awesome.min.css');


        /**
         * JavaScripts
         */
        $cs->registerScriptFile($themePath . '/assets/js/jquery-migrate-1.2.1.min.js', CClientScript::POS_END);
        //$cs->registerCoreScript('jquery', CClientScript::POS_BEGIN);
        $cs->registerScriptFile('//code.jquery.com/ui/1.10.4/jquery-ui.js', CClientScript::POS_END);

        $cs->registerScriptFile($themePath . '/assets/js/bootstrap.min.js', CClientScript::POS_END);

        $cs->registerScriptFile($themePath . '/assets/fractionslider/jquery.fractionslider.js', CClientScript::POS_END);
        $cs->registerScriptFile($themePath . '/assets/nicescroll/jquery.nicescroll.min.js', CClientScript::POS_END);
        $cs->registerScriptFile($themePath . '/assets/js/script.js', CClientScript::POS_END);
        $cs->registerScript('tooltip', "$('[data-toggle=\"tooltip\"]').tooltip();$('[data-toggle=\"popover\"]').tooltip()", CClientScript::POS_READY);
        //variables used for javascript calls
        $cs->registerScript('images', 'var images = "' . $themePath . '/assets/images/";');
        
        ?>
        <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
            <script src="<?php
        echo $themePath . '/assets/js/html5shiv.js';
        ?>"></script>
            <script src="<?php
        echo $themePath . '/assets/js/respond.min.js';
        ?>"></script>
        <![endif]-->
        <link rel="stylesheet" href="//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">

    </head>
    <body>

        <?php
        $this->widget('bootstrap.widgets.BsNavbar', array(
            'collapse' => true,
            'position' => BsHtml::NAVBAR_POSITION_FIXED_TOP,
            'brandLabel' => '<img src="' . Yii::app()->theme->baseUrl . '/assets/images/at_logo.svg" alt="Academic Technologies\"/>',
            'brandUrl' => Yii::app()->homeUrl,
            'items' => array(
                array(
                    'class' => 'bootstrap.widgets.BsNav',
                    'type' => 'navbar',
                    'activateParents' => true,
                    'htmlOptions' => array(
                        'pull' => BSHtml::PULL_RIGHT,
                    ),
                    'items' => array(
                        BSHtml::navbarMenuDividerVertical(array('class' => 'hidden-xs')),
                        array(
                            'label' => 'Home',
                            //'icon' => 'dashboard fw',
                            'url' => array('site/index'),
                            'icon' => 'home fw',
                        ),
                        array(
                            'label' => 'Gadgets',
                            //'icon' => 'dashboard fw',
                            'url' => array('gadgets/index'),
                            'icon' => 'gears fw',
                        ),
                        array(
                            'label' => 'WEPA',
                            //'icon' => 'dashboard fw',
                            'url' => array('wepa/index'),
                            'icon' => 'print fw',
                        ),
                        array(
                            'label' => 'Upload',
                            //'icon' => 'dashboard fw',
                            'url' => array('site/upload'),
                            'icon' => 'folder fw',
                        ),
                        array(
                            'label' => 'Logout',
                            //'icon' => 'dashboard fw',
                            'url' => array('site/logout'),
                            'icon' => 'power-off fw',
                            'iconColor' => 'red',
                            'visible' => !Yii::app()->user->isGuest
                        ),
                        array(
                            'label' => 'Login',
                            //'icon' => 'dashboard fw',
                            'url' => array('site/login'),
                            'icon' => 'power-off fw',
                            'iconColor' => 'green',
                            'visible' => Yii::app()->user->isGuest
                        ),
                        BSHtml::navbarMenuDividerVertical(array('class' => 'hidden-xs')),
                    ),
                )
            )
        ));
        ?>
        <main role="main">
            <div class="container-fluid bs-docs-container"  id="page">

                <?php if (isset($this->breadcrumbs)): ?>
                    <?php
                    /*
                      $this->widget('bootstrap.widgets.BsBreadcrumb', array(
                      'links' => $this->breadcrumbs,
                      ));*
                     */
                    ?><!-- breadcrumbs -->

                <?php endif ?>
                <div class="container-fluid top-message">
                    <?php
                    $flashMessages = Yii::app()->user->getFlashes();
                    if ($flashMessages) {
                        echo '<ul class="flashes">';
                        foreach ($flashMessages as $key => $message) {
                            echo '<li><div class="alert alert-' . $key . '">' . $message . "</div></li>\n";
                        }
                        echo '</ul>';
                    }
                    ?>
                </div>

                <div class="center">
                    <legend><h2>Communications Dashboard</h2></legend>
                </div>
                <?php echo $content; ?>

                <div class="clearfix top30"></div>



            </div><!-- page -->
        </main>
        <footer id="footer">
            <div class="container-fluid footer">
                <hr class="" style="border-color:#bbb;"></hr>
                <div class="row center">

                    <img src="<?= Yii::app()->theme->baseUrl; ?>/assets/images/GW_horizontal_blue.fw.png" alt="Academic Technologies"/>
                </div>
                <hr class="" style="border-color:#bbb;"></hr>
            </div>

        </footer> <!--footer -->

    </body>
    <?php
    Yii::app()->clientScript->registerScript(
            'myHideEffect', '$(".alert").animate({opacity: 0.20}, 6000).fadeOut("slow");', CClientScript::POS_READY
    );
    ?>
</html>
