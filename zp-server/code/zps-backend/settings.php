<?php

// ::ZETA-PRODUCER-NO-COMPRESSION::

require_once('../afx.inc.php');

$accountController = new ZpsBackendSettingsController();
$viewBag = $accountController->HandleGetOrPost();

$title = "Einstellungen";

// --

$colLabel = "col-xs-12 col-sm-2 col-md-2";
$colEditor = "col-xs-12 col-sm-10 col-md-10";
$colEditorB = "col-xs-12 col-sm-10 col-sm-offset-2 col-md-10 col-md-offset-2";
?>

<?php require_once('_header.php') ?>

<!-- ##################################################################### -->

<form method="post" action="<?=UrlHelper::GetCurrentFullUrl()?>" autocomplete="off" role="presentation"><?php require_once('_password-autocomplete-hider.php') ?>

    <div class="row delta-y-before-2">
        <div class="col-xs-12">
            <h3>Primäre E-Mail-Adresse</h3>
            <div>
                <em>Diese E-Mail-Adresse wird beispielsweise verwendet, um Ihr Kennwort
                zurück zu setzen.</em>
            </div>
        </div>
    </div>

    <div class="row delta-y-before-1 <?=ArrayHelper::GetValue($viewBag, 'primary-email.class')?>">
        <div class="<?=$colLabel?>">
            <label for="primary-email" class="non-bold">
                E-Mail
            </label>
        </div>
        <div class="<?=$colEditor?>">
            <input type="text" style="max-width:300px" class="form-control" id="primary-email" name="primary-email" data-lpignore="true"
                   value="<?=$viewBag['settings']['primary-email']?>" />
        </div><?php if ( ArrayHelper::HasValue($viewBag, 'primary-email.error') ) { ?>
        <div class="col-md-12 error-text"><?=$viewBag['primary-email.error']?>
        </div><?php } ?>
    </div>


    <!-- --------------------------------------------- -->

    <div class="row delta-y-before-2">
        <div class="col-xs-12">
            <h3>E-Mail-Versand</h3>
            <p><em>Diese Einstellungen gelten momentan nur für Bewertungen</em></p>
        </div>
    </div>

    <div class="row delta-y-before-2 <?=ArrayHelper::GetValue($viewBag, 'email-use-smtp.class')?>">
        <div class="<?=$colLabel?>">
            <label class="non-bold">
                Optionen
            </label>
        </div>
        <div class="<?=$colEditor?>">
            <label for="email-use-smtp" class="non-bold">
                <input type="checkbox" id="email-use-smtp" name="email-use-smtp" value="1"
                       <?=$viewBag['settings']['email-use-smtp'] ? 'checked="checked"' : ''?> />
                Per SMTP senden
            </label>
        </div><?php if ( ArrayHelper::HasValue($viewBag, 'email-use-smtp.error') ) { ?>
        <div class="col-md-12 error-text"><?=$viewBag['email-use-smtp.error']?>
        </div><?php } ?>
    </div>

    <div class="row delta-y-before-0 <?=ArrayHelper::GetValue($viewBag, 'email-smtp-debug.class')?>">
        <div class="<?=$colEditorB?>">
            <label for="email-smtp-debug" class="non-bold">
                <input type="checkbox" id="email-smtp-debug" name="email-smtp-debug" value="1"
                       <?=$viewBag['settings']['email-smtp-debug'] ? 'checked="checked"' : ''?> />
                Debug-Modus
            </label>
        </div><?php if ( ArrayHelper::HasValue($viewBag, 'email-smtp-debug.error') ) { ?>
        <div class="col-md-12 error-text"><?=$viewBag['email-smtp-debug.error']?>
        </div><?php } ?>
    </div>

    <div class="row delta-y-before-2 <?=ArrayHelper::GetValue($viewBag, 'email-smtp-servername.class')?>">
        <div class="<?=$colLabel?>">
            <label for="email-smtp-servername" class="non-bold">
                Server
            </label>
        </div>
        <div class="<?=$colEditor?>">
            <input type="text" style="max-width:300px" class="form-control" id="email-smtp-servername" name="email-smtp-servername" data-lpignore="true"
                   value="<?=$viewBag['settings']['email-smtp-servername']?>" />
        </div><?php if ( ArrayHelper::HasValue($viewBag, 'email-smtp-servername.error') ) { ?>
        <div class="col-md-12 error-text"><?=$viewBag['email-smtp-servername.error']?>
        </div><?php } ?>
    </div>

    <div class="row delta-y-before-1 <?=ArrayHelper::GetValue($viewBag, 'email-smtp-port.class')?>">
        <div class="<?=$colLabel?>">
            <label for="email-smtp-port" class="non-bold">
                Anschluss
            </label>
        </div>
        <div class="<?=$colEditor?>">
            <input type="number" style="max-width:100px" class="form-control" id="email-smtp-port" name="email-smtp-port" maxlength="5"
                   value="<?=$viewBag['settings']['email-smtp-port']?>" />
        </div><?php if ( ArrayHelper::HasValue($viewBag, 'email-smtp-port.error') ) { ?>
        <div class="col-md-12 error-text"><?=$viewBag['email-smtp-port.error']?>
        </div><?php } ?>
    </div>

    <div class="row delta-y-before-2 <?=ArrayHelper::GetValue($viewBag, 'email-smtp-username.class')?>">
        <div class="<?=$colLabel?>">
            <label for="email-smtp-username" class="non-bold">
                Benutzername
            </label>
        </div>
        <div class="<?=$colEditor?>">
            <input type="text" style="max-width:300px" class="form-control" id="email-smtp-emanresu" name="email-smtp-emanresu" data-lpignore="true"
                   value="<?=$viewBag['settings']['email-smtp-username']?>" />
        </div><?php if ( ArrayHelper::HasValue($viewBag, 'email-smtp-username.error') ) { ?>
        <div class="col-md-12 error-text"><?=$viewBag['email-smtp-username.error']?>
        </div><?php } ?>
    </div>

    <div class="row delta-y-before-1 <?=ArrayHelper::GetValue($viewBag, 'email-smtp-password.class')?>">
        <div class="<?=$colLabel?>">
            <label for="email-smtp-password" class="non-bold">
                Kennwort
            </label>
        </div>
        <div class="<?=$colEditor?>">
            <input type="password" style="max-width:300px" class="form-control" id="email-smtp-drowssap" name="email-smtp-drowssap" data-lpignore="true"
                   value="<?=$viewBag['settings']['email-smtp-password']?>" />
        </div><?php if ( ArrayHelper::HasValue($viewBag, 'email-smtp-password.error') ) { ?>
        <div class="col-md-12 error-text"><?=$viewBag['email-smtp-password.error']?>
        </div><?php } ?>
    </div>

    <div class="row delta-y-before-2 <?=ArrayHelper::GetValue($viewBag, 'email-smtp-sslmode.class')?>">
        <div class="<?=$colLabel?>">
            <label for="email-smtp-sslmode" class="non-bold">
                SSL-Modus
            </label>
        </div>
        <div class="<?=$colEditor?>">
            <select class="form-control" style="max-width:120px" id="email-smtp-sslmode" name="email-smtp-sslmode">
                <option <?=$viewBag['settings']['email-smtp-sslmode']=='none' ? 'selected="selected"' : ''?> value="none">Kein</option>
                <option <?=$viewBag['settings']['email-smtp-sslmode']=='autotls' ? 'selected="selected"' : ''?> value="autotls">Auto-TLS</option>
                <option <?=$viewBag['settings']['email-smtp-sslmode']=='ssl' ? 'selected="selected"' : ''?> value="ssl">SSL</option>
                <option <?=$viewBag['settings']['email-smtp-sslmode']=='tls' ? 'selected="selected"' : ''?> value="tls">TLS</option>
            </select>
        </div><?php if ( ArrayHelper::HasValue($viewBag, 'email-smtp-sslmode.error') ) { ?>
        <div class="col-md-12 error-text"><?=$viewBag['email-smtp-sslmode.error']?>
        </div><?php } ?>
    </div>

    <div class="row delta-y-before-1 <?=ArrayHelper::GetValue($viewBag, 'email-smtp-ignore-ssl-errors.class')?>">
        <div class="<?=$colEditorB?>">
            <label for="email-smtp-ignore-ssl-errors" class="non-bold">
                <input type="checkbox" id="email-smtp-ignore-ssl-errors" name="email-smtp-ignore-ssl-errors" value="1"
                       <?=$viewBag['settings']['email-smtp-ignore-ssl-errors'] ? 'checked="checked"' : ''?> />
                SSL-Fehler ignorieren
            </label>
        </div><?php if ( ArrayHelper::HasValue($viewBag, 'email-smtp-ignore-ssl-errors.error') ) { ?>
        <div class="col-md-12 error-text"><?=$viewBag['email-smtp-ignore-ssl-errors.error']?>
        </div><?php } ?>
    </div>

	<!-- ##################################################################### -->

    <div class="row delta-y-before-2">
        <div class="col-xs-12">
            <h3>Bewertungs-Widget</h3>
        </div>
    </div>

    <div class="row delta-y-before-2 <?=ArrayHelper::GetValue($viewBag, 'widget-ratings-email-sender-name.class')?>">
        <div class="<?=$colLabel?>">
            <label for="widget-ratings-email-sender-name" class="non-bold">
                Absendername
            </label>
        </div>
        <div class="<?=$colEditor?>">
            <input type="text" style="max-width:300px" class="form-control" id="widget-ratings-email-sender-name" name="widget-ratings-email-sender-name" data-lpignore="true"
                   value="<?=$viewBag['settings']['widget-ratings-email-sender-name']?>" />
        </div><?php if ( ArrayHelper::HasValue($viewBag, 'widget-ratings-email-sender-name.error') ) { ?>
        <div class="col-md-12 error-text"><?=$viewBag['widget-ratings-email-sender-name.error']?>
        </div><?php } ?>
    </div>

    <div class="row delta-y-before-1 <?=ArrayHelper::GetValue($viewBag, 'widget-ratings-email-sender-email.class')?>">
        <div class="<?=$colLabel?>">
            <label for="widget-ratings-email-sender-email" class="non-bold">
                Absender-E-Mail
            </label>
        </div>
        <div class="<?=$colEditor?>">
            <input type="text" style="max-width:300px" class="form-control" id="widget-ratings-email-sender-email" name="widget-ratings-email-sender-email" data-lpignore="true"
                   value="<?=$viewBag['settings']['widget-ratings-email-sender-email']?>" />
        </div><?php if ( ArrayHelper::HasValue($viewBag, 'widget-ratings-email-sender-email.error') ) { ?>
        <div class="col-md-12 error-text"><?=$viewBag['widget-ratings-email-sender-email.error']?>
        </div><?php } ?>
    </div>

	<!-- ##################################################################### -->

    <div class="row delta-y-before-2">
        <div class="col-xs-12">
            <p><strong>E-Mail-Vorlage: Konto-Bestätigung (neuer Benutzer)</strong></p>
        </div>
    </div>

    <div class="row delta-y-before-2 <?=ArrayHelper::GetValue($viewBag, 'widget-ratings-email-confirm-new-account-subject.class')?>">
        <div class="<?=$colLabel?>">
            <label for="widget-ratings-email-confirm-new-account-subject" class="non-bold">
                E-Mail-Betreff
            </label>
        </div>
        <div class="<?=$colEditor?>">
            <input type="text" style="max-width:500px" class="form-control" id="widget-ratings-email-confirm-new-account-subject" name="widget-ratings-email-confirm-new-account-subject" data-lpignore="true"
                   value="<?=$viewBag['settings']['widget-ratings-email-confirm-new-account-subject']?>" />
        </div><?php if ( ArrayHelper::HasValue($viewBag, 'widget-ratings-email-confirm-new-account-subject.error') ) { ?>
        <div class="col-md-12 error-text"><?=$viewBag['widget-ratings-email-confirm-new-account-subject.error']?>
        </div><?php } ?>
    </div>

    <div class="row delta-y-before-1 <?=ArrayHelper::GetValue($viewBag, 'widget-ratings-email-confirm-new-account-body.class')?>">
        <div class="<?=$colLabel?>">
            <label for="widget-ratings-email-confirm-new-account-body" class="non-bold">
                E-Mail-Nachricht
            </label>
        </div>
        <div class="<?=$colEditor?>">
            <textarea style="max-width:500px" rows="8" class="form-control" id="widget-ratings-email-confirm-new-account-body" name="widget-ratings-email-confirm-new-account-body"><?=
                $viewBag['settings']['widget-ratings-email-confirm-new-account-body']
            ?></textarea>
        </div><?php if ( ArrayHelper::HasValue($viewBag, 'widget-ratings-email-confirm-new-account-body.error') ) { ?>
        <div class="col-md-12 error-text"><?=$viewBag['widget-ratings-email-confirm-new-account-body.error']?>
        </div><?php } ?>
    </div>

	<!-- ##################################################################### -->

    <div class="row delta-y-before-2">
        <div class="col-xs-12">
            <p><strong>E-Mail-Vorlage: Neue Bewertung</strong></p>
        </div>
    </div>

    <div class="row delta-y-before-2 <?=ArrayHelper::GetValue($viewBag, 'widget-ratings-email-confirm-new-rating-subject.class')?>">
        <div class="<?=$colLabel?>">
            <label for="widget-ratings-email-confirm-new-rating-subject" class="non-bold">
                E-Mail-Betreff
            </label>
        </div>
        <div class="<?=$colEditor?>">
            <input type="text" style="max-width:500px" class="form-control" id="widget-ratings-email-confirm-new-rating-subject" name="widget-ratings-email-confirm-new-rating-subject" data-lpignore="true"
                   value="<?=$viewBag['settings']['widget-ratings-email-confirm-new-rating-subject']?>" />
        </div><?php if ( ArrayHelper::HasValue($viewBag, 'widget-ratings-email-confirm-new-rating-subject.error') ) { ?>
        <div class="col-md-12 error-text"><?=$viewBag['widget-ratings-email-confirm-new-rating-subject.error']?>
        </div><?php } ?>
    </div>

    <div class="row delta-y-before-1 <?=ArrayHelper::GetValue($viewBag, 'widget-ratings-email-confirm-new-rating-body.class')?>">
        <div class="<?=$colLabel?>">
            <label for="widget-ratings-email-confirm-new-rating-body" class="non-bold">
                E-Mail-Nachricht
            </label>
        </div>
        <div class="<?=$colEditor?>">
            <textarea style="max-width:500px" rows="8" class="form-control" id="widget-ratings-email-confirm-new-rating-body" name="widget-ratings-email-confirm-new-rating-body"><?=
                $viewBag['settings']['widget-ratings-email-confirm-new-rating-body']
            ?></textarea>
        </div><?php if ( ArrayHelper::HasValue($viewBag, 'widget-ratings-email-confirm-new-rating-body.error') ) { ?>
        <div class="col-md-12 error-text"><?=$viewBag['widget-ratings-email-confirm-new-rating-body.error']?>
        </div><?php } ?>
    </div>

	<!-- ##################################################################### -->

    <div class="row delta-y-before-4">
        <div class="col-md-12">
            <input class="btn btn-default btn-primary" type="submit" value="Speichern" />
            <a href="<?=$viewBag['cancelUrl']?>" class="btn btn-default">Abbrechen</a>

            <?php if ( $viewBag['hasLogFiles'] ) { ?>
                <a href="<?=$viewBag['downloadLogfilesUrl']?>" class="btn btn-default pull-right">Protokolldateien downloaden</a>
            <?php } ?>
        </div>
    </div>
</form>

<!-- ##################################################################### -->
<?php require_once('_footer.php') ?>
