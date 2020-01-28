<?php

// ::ZETA-PRODUCER-NO-COMPRESSION::

// Anmeldung.

require_once('../afx.inc.php');

$loginController = new ZpsBackendLostPasswordController();
$viewBag = $loginController->HandleGetOrPost();

$title = "Kennwort vergessen";

$viewBag['error']

?>

<?php require_once('_header.php') ?>

<!-- ##################################################################### -->

<?php if ( $viewBag['has-primary-email']) { ?>

    <div class="row delta-y-before-2">
        <div class="col-md-8 col-xs-12">
            Es wurde eine Kennwort-Zur&uuml;cksetzen-E-Mail an Sie gesendet. Bitte &uuml;berpr&uuml;fen Sie Ihr E-Mail-Postfach.
        </div>
    </div>

<?php } else { ?>

    

    <div class="row delta-y-before-2">
        <div class="col-md-8 col-xs-12">
            <a href="https://zeta.li/zp15-servercomponent-lost-password" target="_blank">
                Bitte wenden Sie sich an den Zeta-Producer-Support
            </a>, um Ihr Kennwort zur&uuml;ck zu setzen.
        </div>
    </div>

<?php } ?>


<div class="row delta-y-before-2">
    <div class="col-md-12">
        <a href="<?=$viewBag['cancelUrl']?>" class="btn btn-default">Zur&uuml;ck</a>
    </div>
</div>

<!-- ##################################################################### -->

<?php require_once('_footer.php') ?>