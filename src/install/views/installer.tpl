
<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{$resource_url}css/bootstrap.min.css">
    <link rel="stylesheet" href="{$resource_url}css/installer.css">

    <!--<base href="{$smarty.const.URL_INSTALL}"/>-->

    <title>CircleBilling installation</title>
</head>
<body>

    <div class="container">
        <div class="py-5 text-center">
            <img class="d-block mx-auto mb-4" src="{$resource_url}images/logo.png" alt="" width="200" height="200">
            <h2>CircleBilling Installer</h2>
        </div>

        <noscript>
            <div style="border:red solid 5px; padding: 10px">
                <p> For full functionality of this installer and most of CircleBilling features it is
                    necessary to enable JavaScript.<br/>
                    Here are the <a href="http://www.enable-javascript.com/" target="_blank">
                        instructions how to enable JavaScript in your web browser</a>.
                </p>
                <p>
                    If you can not enable JavaScript for some reason you can follow these steps in
                    order to setup CircleBilling: <br/>
                    * Rename "bb-config-sample.php" file to "bb-config.php", fill in the values and change this file permissions to read only (CHMOD 644).<br/>
                    * Import /install/structure.sql to your database<br/>
                    * Import /install/content.sql to your database<br/>
                    * Open browser <a href="{{constant("BB_URL")}}index.php?_url=/bb-admin">{{constant("BB_URL")}}index.php?_url=/bb-admin</a> to create new admin
                    account.<br/>
                    * Remove /install directory<br/>
                </p>
            </div>
        </noscript>

        <hr />
    </div>

    <div class="container wizard-progress">

        <div class="row steps">
            <div class="col-sm-3 step active" data-step="1">
                <div class="text-center bs-wizard-stepnum">1. Preparation</div>
                <div class="progress mobile-hidden"><div class="progress-bar mobile-hidden"></div></div>
                <a href="#" class="bs-wizard-dot mobile-hidden"></a>
            </div>

            <div class="col-sm-3 step disabled" data-step="2">
                <div class="text-center bs-wizard-stepnum">2. Database</div>
                <div class="progress mobile-hidden"><div class="progress-bar mobile-hidden"></div></div>
                <a href="#" class="bs-wizard-dot mobile-hidden"></a>
            </div>

            <div class="col-sm-3 step disabled" data-step="3">
                <div class="text-center bs-wizard-stepnum">3. Administrator</div>
                <div class="progress mobile-hidden"><div class="progress-bar mobile-hidden"></div></div>
                <a href="#" class="bs-wizard-dot mobile-hidden"></a>
            </div>

            <div class="col-sm-3 step disabled" data-step="4">
                <div class="text-center bs-wizard-stepnum">4. Finish</div>
                <div class="progress mobile-hidden"><div class="progress-bar mobile-hidden"></div></div>
                <a href="#" class="bs-wizard-dot mobile-hidden"></a>
            </div>
        </div>
    </div>

    <div class="spacer-20"></div>

    <main role="main" class="container">

        <div class="row">

            {include file="side.tpl"}

            {include file="content.tpl"}

        </div>

    </main>

    <div class="spacer-20"></div>

    <footer class="footer">
        <div class="container text-center">
            <span class="text-muted">&copy; Copyright {'Y'|date}. All rights reserved. Powered by <a href="http://www.circlebilling.com" title="Invoicing, billing and client management software" target="_blank">CircleBilling {$version}</a></span>
        </div>
    </footer>

    <script src="{$resource_url}js/jquery.min.js"></script>
    <script src="{$resource_url}js/bootstrap.min.js"></script>

    <div id="overlay" style="position: absolute; display: none; z-index: 1000; background-color: whitesmoke; width: 725px; height: 50px; opacity:0.5;"></div>

    <script src="{$resource_url}js/installer.js" type="text/javascript"></script>

    <script type="text/javascript">

        jQuery(document).ready(function() {
            cb.installer.init({
                installUrl: '{{constant("SYSTEM_URL_INSTALL")}}',
                //loadingOverlay: jQuery('#overlay'), --default
            });
        });

    </script>
</body>
</html>