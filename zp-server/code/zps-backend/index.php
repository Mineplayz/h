<?php

// ::ZETA-PRODUCER-NO-COMPRESSION::

// Anmeldung.

require_once('../afx.inc.php');

$loginController = new ZpsBackendLoginController();
$viewBag = $loginController->HandleGetOrPost();

$title = "Anmeldung";
?>

<?php require_once('_header.php') ?>

<!-- ##################################################################### -->

<script>
    $(function () {
        $('#pw').focus();
    });
</script>

<form method="post" action="<?=UrlHelper::GetCurrentFullUrl()?>">

    <div class="row delta-y-before-2 <?=ArrayHelper::GetValue($viewBag, 'pw.class')?> form-group">
        <div class="col-md-12">
            <label for="pw">Kennwort</label>
        </div>
        <div class="col-md-12">
            <input type="password" placeholder="Kennwort" id="pw" name="pw" class="form-control" style="max-width: 300px;" />
        </div>
        <?php if ( ArrayHelper::HasValue($viewBag, 'pw.error') ) { ?>
        <div class="col-md-12 error-text">
            <?=$viewBag['pw.error']?>
        </div>
        <?php } ?>
    </div>

    <div class="row delta-y-before-2">
        <div class="col-md-12">
            <input class="btn btn-default btn-primary" type="submit" value="Anmelden" />
            &nbsp;
            <a href="lost-pwd.php">Kennwort vergessen?</a>
        </div>
    </div>
</form>

<!-- ##################################################################### -->

<?php require_once('_footer.php') ?>