<?php

// ::ZETA-PRODUCER-NO-COMPRESSION::

require_once('../afx.inc.php');

$accountController = new ZpsBackendBackupController();
$viewBag = $accountController->HandleGetOrPost();

/**
 * @var ZpsBackendBackupModel $model
 */
$model = $viewBag['backup'];

$title = $model->BeautifiedFileName . " &ndash; Backup";

// --

$colLabel = "col-xs-12 col-sm-2 col-md-1";
$colEditor = "col-xs-12 col-sm-10 col-md-11";
$colEditorB = "col-xs-12 col-sm-10 col-sm-offset-2 col-md-11 col-md-offset-1";
?>

<?php require_once('_header.php') ?>

<!-- ##################################################################### -->

<form method="post" action="<?=UrlHelper::GetCurrentFullUrl()?>">

    <div class="row delta-y-before-2 form-group">
        <div class="<?=$colLabel?>">
            <label>Name</label>
        </div>
        <div class="<?=$colEditor?>">
            <input type="text" readonly="readonly" class="form-control" value="<?=$model->BeautifiedFileName?>" />
        </div>
    </div>
    <div class="row delta-y-before-0 form-group">
        <div class="<?=$colLabel?>">
            <label>Gr&ouml;&szlig;e</label>
        </div>
        <div class="<?=$colEditor?>">
            <input type="text" readonly="readonly" class="form-control" value="<?=$model->PrintableFileSize?>" />
        </div>
    </div>
    <div class="row delta-y-before-0 form-group">
        <div class="<?=$colLabel?>">
            <label>Erstellt</label>
        </div>
        <div class="<?=$colEditor?>">
            <input type="text" readonly="readonly" class="form-control" value="<?=$model->ModifiedAgo?>" style="cursor: help" title="<?=$model->ModifiedFormatted?>" />
        </div>
    </div>


    <div class="row delta-y-before-2">
        <div class="col-md-12">
            <label class="pseudo">
                <a class="btn btn-default btn-primary" href="<?=UrlHelper::SetParameters('api.php',
                         array(
                            'action' => 'download-backup',
                            'id' => UrlHelper::GetParameter(UrlHelper::GetCurrentFullUrl(), 'id' )))?>">
                    <span class="glyphicon glyphicon-download" aria-hidden="true"></span>
                    Backup downloaden
                </a>
            </label>

            <label class="pseudo">
                <a href="<?=$viewBag['cancelUrl']?>" class="btn btn-default">&laquo; Zur&uuml;ck zur &Uuml;bersicht</a>
            </label>
        </div>
    </div>
</form>

<!-- ##################################################################### -->
<?php require_once('_footer.php') ?>
