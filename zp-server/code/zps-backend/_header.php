<?php
// ::ZETA-PRODUCER-NO-COMPRESSION::
$appName = 'Server Backend';

$webName = ZpsInternalSettingController::GetValue( KnownInternalSettings::WebName );
$webName = $webName ? $webName : '';

$headerController = new ZpsBackendHeaderController();
$viewBagHeader = $headerController->HandleGetOrPost();

// Auslesen und merken.
$error = $viewBagHeader['session']->Backend->Error;
$warn = $viewBagHeader['session']->Backend->Warn;
$success = $viewBagHeader['session']->Backend->Success;

// Gleich resetten, so dass nur für diesen Aufruf gilt.
$viewBagHeader['session']->Backend->Error = null;
$viewBagHeader['session']->Backend->Warn = null;
$viewBagHeader['session']->Backend->Success = null;
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <!-- Zeta Producer Backend, Version <?= $viewBagHeader['version'] ?> -->

    <title><?=isset($title) ? "$title &ndash; $appName $webName" : "$appName $webName" ?></title>

    <link rel="icon" type="image/png" href="resources/favicon.png" />

    <script src="https://code.jquery.com/jquery-3.3.1.min.js"
            integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
            crossorigin="anonymous"></script>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" 
          integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" 
          crossorigin="anonymous">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" 
            integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" 
            crossorigin="anonymous"></script>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/jsgrid/1.5.3/jsgrid.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/jsgrid/1.5.3/jsgrid-theme.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jsgrid/1.5.3/jsgrid.min.js"></script>
    <!--<script src="https://cdnjs.cloudflare.com/ajax/libs/jsgrid/1.5.3/i18n/jsgrid-de.js"></script>-->

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.3/css/bootstrap-select.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.3/js/bootstrap-select.min.js"></script>

    <link href="resources/styles.min.css" rel="stylesheet" type="text/css" />

    <?=ArrayHelper::GetValue($viewBag, 'additional_header')?>

<script>
    $(function () {
        // Alles selektieren beim Klick in Textbox.
        $('.select-all').on("click",
            function () {
                this.focus();
                this.select();
            });
    });
</script>
</head>

<body<?=isset($bodyclass) ? " class=\"$bodyclass\"" : ''?>>
    <div class="navbar navbar-default">
        <div class="container">
            <div class="navbar-header">
                <a href="dashboard.php" class="navbar-brand"><span class="WebName">Zeta Producer Server Backend</span></a>
                <?php if ( $viewBagHeader['session']->Backend->IsLoggedIn ) { ?>
                <button class="navbar-toggle" type="button" data-toggle="collapse" data-target="#navbar-main">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <?php } ?>
            </div>
            <div class="navbar-collapse collapse" id="navbar-main">
                <ul class="nav navbar-nav navbar-right">
                    <?php if ( $viewBagHeader['session']->Backend->IsLoggedIn ) { ?>
                        <?php if ( $viewBagHeader['wantProjectMenu'] ) { ?>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Projekt <span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li><a href="server-project-status.php">Status</a></li>
                                <li><a href="server-project-activity.php">Aktivitätsprotokoll</a></li>
                            <li role="separator" class="divider"></li>
                                <li><a href="server-project-download-info.php">Download-Informationen</a></li>
                            </ul>
                        </li>
                        <?php } ?>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Aktionen <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="dashboard.php">Dashboard</a></li>
                            <li role="separator" class="divider"></li>
                            <li><a href="backups.php">Backups</a></li>
                            <li><a href="settings.php">Einstellungen</a></li>
                            <li role="separator" class="divider"></li>
                            <?php if ( !StringHelper::IsNullOrEmpty($viewBagHeader['wwwUrl'])) { ?>
                            <li><a href="<?=$viewBagHeader['wwwUrl']?>" target="_blank">Zur Website</a></li>
                            <li role="separator" class="divider"></li>
                            <?php } ?>
                            <li><a href="change-pwd.php">Kennwort ändern</a></lí>
                            <li><a href="index.php?logout=true">Abmelden</a></li>
                        </ul>
                    </li>
                    <?php } ?>
                    <li><a href="https://zeta.li/zp15-servercomponent-live-dashboard-help-menu" target="_blank">Hilfe</a></li>
                </ul>
            </div>
        </div>
    </div>

    <div class="container">
        <div id="error-panel" class="alert alert-danger" 
            <?php if ( isset($error) && $error!='' ) { ?>
                style="display:block"
            <?php } else { ?>
                style="display:none"
            <?php } ?>
            role="alert"><?=$error?></div>

        <div id="warning-panel" class="alert alert-warning" 
            <?php if ( isset($warn) && $warn!='' ) { ?>
                style="display:block"
            <?php } else { ?>
                style="display:none"
            <?php } ?>
             role="alert"><?=$warn?></div>

        <div id="success-panel" class="alert alert-success" 
            <?php if ( isset($success) && $success!='' ) { ?>
                style="display:block"
            <?php } else { ?>
                style="display:none"
            <?php } ?>
             role="alert"><?=$success?></div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1><?=isset($title) ? "$title" : ''?></h1>
            </div>
        </div>
