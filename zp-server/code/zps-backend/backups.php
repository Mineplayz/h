<?php

// ::ZETA-PRODUCER-NO-COMPRESSION::

require_once('../afx.inc.php');

$accountController = new ZpsBackendBackupsController();
$viewBag = $accountController->HandleGetOrPost();

$title = "Backups";

// --

$colLabel = "col-xs-12 col-sm-2 col-md-1";
$colEditor = "col-xs-12 col-sm-10 col-md-11";
$colEditorB = "col-xs-12 col-sm-10 col-sm-offset-2 col-md-11 col-md-offset-1";
?>

<?php require_once('_header.php') ?>

<!-- ##################################################################### -->

<?php
$params = UrlHelper::GetAllParameters(UrlHelper::GetCurrentFullUrl());
$json = count($params)>0 ? JsonHelper::ConvertObjectToJsonString($params) : '';
?>

<script>
    var loadDataUrl = '<?=UrlHelper::SetParameters("api.php", array(
                              "action" => "get-backups",
                              "max" => "10000")) ?>';

    var wantSearchFirstTime = <?= StringHelper::IsNullOrEmpty($json) ? 'false' : 'true'?>;
    var jsonElements = <?= StringHelper::IsNullOrEmpty($json) ? '{}' : $json?>;
    var hasBackups = <?= $viewBag['hasBackups'] ? 'true' : 'false' ?>;
</script>

<script>
    var firstTime = true;

    function fillGrid() {
        /*jsGrid.locale('de');*/

        $("#jsGrid").jsGrid({
            //height: "70%",
            noDataContent: "Nichts gefunden",
            loadMessage: "Lade. Bitte warten\u2026",
            width: "100%",
            filtering: false,
            sorting: true,
            paging: false,
            pagerFormat: "{prev} &nbsp; Seite {pageIndex} von {pageCount} &nbsp; {next}",
            pagePrevText: "Vorige Seite",
            pageNextText: "Nächste Seite",
            pageFirstText: "Erste Seite",
            pageLastText: "Letzte Seite",
            autoload: true,
            pageSize: 10,
            pageButtonCount: 5,
            deleteConfirm: "M&ouml;chten Sie die Adresse wirklich l&ouml;schen?",

            controller: {
                loadData: function (filter) {

                    return $.ajax({
                        type: "GET",
                        url: loadDataUrl,
                        data: filter,
                        dataType: "JSON"
                    })
                }
            },
            onDataLoaded: function (args) {
                // Default-Sort.
                $("#jsGrid").jsGrid("sort", "ModifiedUnix", "desc");

                if (wantSearchFirstTime && firstTime) {
                    firstTime = false;
                    $("#jsGrid").jsGrid("search", jsonElements).done(function () {
                    });
                }
            },

            rowClick: function (args) {
                location.href = "backup.php?id=" + args.item.Id + "&from=backups";
            },

            // http://js-grid.com/docs/#fields
            fields: [
                { name: "BeautifiedFileName", title: "Name", type: "text", width: 150 },
                { name: "PrintableFileSize", title: "Gr&ouml;&szlig;e", type: "text", width: 50, align: "right", headercss: "align-right"},
                { name: "ModifiedAgo", title: "Erstellt", type: "text", width: 100 },
                { name: "ModifiedUnix", title: "Erstellt (UNIX)", type: "text", width: 100, visible: false }
            ]
        });
    }

    $(function () {
        if (hasBackups) {
            fillGrid();
        }
    });
</script>

<form method="post" action="<?=UrlHelper::GetCurrentFullUrl()?>">

    <?php if ( $viewBag['isZpHosting']) { ?>
    <div id="grid-panel" class="row delta-y-before-2">
        <div class="col-md-12">
            Server-Backups sind in unserer Testumgebung nicht möglich.
        </div>
    </div>
    <?php } else { ?>
        <?php if ( $viewBag['hasBackups']) { ?>
            <div id="grid-panel" class="row delta-y-before-2">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-12">
                            <div id="jsGrid"></div>
                        </div>
                    </div>
                </div>
            </div>
        <?php } else { ?>
        <div id="grid-panel" class="row delta-y-before-2">
            <div class="col-md-12">
                Es sind zurzeit keine Backups vorhanden.
            </div>
        </div>
        <?php } ?>
    <?php } ?>

    <div class="row delta-y-before-2" id="button-panel">
        <div class="col-md-12">
            <a href="<?=$viewBag['cancelUrl']?>" class="btn btn-default">&laquo; Zur&uuml;ck zur &Uuml;bersicht</a>
        </div>
    </div>
</form>

<!-- ##################################################################### -->
<?php require_once('_footer.php') ?>
