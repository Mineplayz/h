<?php

// ::ZETA-PRODUCER-NO-COMPRESSION::

// Anmeldung.

require_once('../afx.inc.php');

$loginController = new ZpsBackendServerProjectDownloadInfoController();
$viewBag = $loginController->HandleGetOrPost();

$title = "Projekt-Download-Informationen";
?>

<?php require_once('_header.php') ?>

<!-- ##################################################################### -->

<script>
    $(function () {
    });
</script>

<?php if ( $viewBag['has-sync-activity']) { ?>

    <div class="row delta-y-before-2">
        <div class="col-md-8 col-xs-12">
            &Ouml;ffnen Sie diese Website zur Bearbeitung in Zeta Producer auf einem beliebigen Computer,
            indem Sie <a href="https://zeta.li/zp15-servercomponent-download-project" target="_blank">wie
            in der Dokumentation beschrieben</a> vorgehen, und dabei die nachfolgenden Zugangsdaten verwenden.
        </div>
    </div>

    <div class="row delta-y-before-2">
        <div class="col-md-12">
            <label for="pw">URL</label>
        </div>
        <div class="col-md-8 col-xs-12">
            <input type="text" readonly="readonly" id="url" name="url" class="form-control select-all" style="max-width: 100%;"
                data-lpignore="true" value="<?=$viewBag['dl-url']?>" />
        </div>
    </div>
    <div class="row delta-y-before-1">
        <div class="col-md-12">
            <label for="pw">Kennwort</label>
        </div>
        <div class="col-md-12">
            <input type="password" readonly="readonly" id="pw" name="pw" class="form-control" style="max-width: 300px;"
                data-lpignore="true" value="<?=$viewBag['dl-password-dummy']?>" />
            <a href="<?=$viewBag['change-pw-url']?>">Jetzt &auml;ndern</a>
        </div>
    </div>

    <div class="row delta-y-before-2">
        <div class="col-md-12">
            <a href="<?=$viewBag['cancelUrl']?>" class="btn btn-default">Zur&uuml;ck</a>
        </div>
    </div>

    <div class="row delta-y-before-3">
        <div class="col-md-8 col-xs-12">
            Alternativ k&ouml;nnen Sie auch <a href="<?=$viewBag['zp-scheme-open-project-url']?>" target="_blank">das Projekt
            direkt in Zeta Producer &ouml;ffnen</a>, sofern Sie Zeta Producer lokal installiert haben.
        </div>
    </div>

<?php } else { ?>

    <div class="row delta-y-before-2">
        <div class="col-md-8 col-xs-12">
            Das Projekt dieser Website wurde noch nicht auf dem Server gespeichert, deshalb k&ouml;nnen Sie es nicht downloaden. 
        </div>
    </div>

    <div class="row delta-y-before-1">
        <div class="col-md-8 col-xs-12">
            Lesen Sie 
            <a href="https://zeta.li/zp15-servercomponent-download-project" target="_blank">in der Dokumentation</a>,
            wie Sie vorgehen m&uuml;ssen.
        </div>
    </div>

    <div class="row delta-y-before-2">
        <div class="col-md-12">
            <a href="<?=$viewBag['cancelUrl']?>" class="btn btn-default">Zur&uuml;ck</a>
        </div>
    </div>

<?php } ?>

<!-- ##################################################################### -->

<?php require_once('_footer.php') ?>