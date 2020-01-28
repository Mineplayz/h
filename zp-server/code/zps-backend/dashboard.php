<?php
// ::ZETA-PRODUCER-NO-COMPRESSION::
// Dashboard als Einstieg, gleich nach der Anmeldung.
require_once('../afx.inc.php');
$dashboardController = new ZpsBackendDashboardController();
$viewBag = $dashboardController->HandleGetOrPost();
$title = "Dashboard";
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
                              "max" => "5")) ?>';

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

                if (firstTime) {
                    firstTime = false;
                    $("#jsGrid").jsGrid("search", jsonElements).done(function () {
                        // to inspect one row, use $("#jsGrid").data().JSGrid.data[0]
                        // orders are in args.data
                    });
                }
            },

            rowClick: function (args) {
                location.href = "backup.php?id=" + args.item.Id + "&from=dashboard";
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

<div class="row">
    <div class="col-sm-6 delta-y-before-2">
        <div class="row">
            <div class="col-md-12">
                <h3>Neueste Backups</h3>
            </div>
        </div><?php if ( $viewBag['isZpHosting']) { ?>
        <div id="grid-panel" class="row delta-y-before-2">
            <div class="col-md-12">
                Server-Backups sind in unserer Testumgebung nicht möglich.
            </div>
        </div><?php } else { ?><?php if ( $viewBag['hasBackups']) { ?>
        <div class="row delta-y-before-1">
            <div class="col-md-12">
                <div id="jsGrid"></div>
            </div>
        </div>
        <div class="row delta-y-before-1">
            <div class="col-md-12">
                <a href="backups.php" class="btn btn-default">
                    Alle Backups anzeigen
                </a>
            </div>
        </div><?php } else { ?>
        <div id="grid-panel" class="row delta-y-before-2">
            <div class="col-md-12">
                Es sind zurzeit keine Backups vorhanden.
            </div>
        </div><?php } ?><?php } ?>
    </div>
</div>

<!-- ##################################################################### -->
<?php require_once('_footer.php') ?>
