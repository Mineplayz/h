<?php
// ::ZETA-PRODUCER-NO-COMPRESSION::
require_once('../afx.inc.php');
$loginController = new ZpsBackendServerProjectActivityController();
$viewBag = $loginController->HandleGetOrPost();
/**
* @var ZpsBackendProjectStateModel $model
*/
$model = $viewBag['model'];
$title = "Projekt-Aktivitätsprotokoll";
// --
$colLabel = "col-xs-12 col-sm-12 col-md-12";
$colEditor = "col-xs-12 col-sm-12 col-md-12";
$colEditorB = "col-xs-12 col-sm-12 col-sm-offset-0 col-md-12 col-md-offset-0";
?>

<?php require_once('_header.php') ?>

<!-- ##################################################################### -->

<script>
    var gridLoaded = false;
    var reloadGridIntervallSeconds = 60;

    var fetchTimerId = 0;

    $(function () {
        window.setTimeout(hideNotify, 5000);

        $('#showLowPriority').attr("disabled", true);

        // Jedes Ändern der Checkbox soll ein Refill des Grids auslösen.
        $('#showLowPriority').change(function () {
            if (gridLoaded) fillGrid();
        });
    });

    function hideNotify() {
        $('#success-panel').hide();
    }

    function doReloadGrid() {
        fillGrid();
    }

    // https://stackoverflow.com/a/3426956/107625
    function hashCode(str) {
        var hash = 0;
        for (var i = 0; i < str.length; i++) {
            hash = str.charCodeAt(i) + ((hash << 5) - hash);
        }
        return hash;
    }

    // https://stackoverflow.com/a/3426956/107625
    function intToRGB(i) {
        var c = (i & 0x00FFFFFF)
            .toString(16)
            .toUpperCase();

        return "00000".substring(0, 6 - c.length) + c;
    }
</script>

<script>
    var loadDataUrl = '<?=UrlHelper::SetParameters("api.php", array(
                              "action" => "get-project-activities",
                              "max" => "10000")) ?>';
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
            paging: true,
            pagerFormat: "{prev} &nbsp; Seite {pageIndex} von {pageCount} &nbsp; {next}",
            pagePrevText: "Vorige Seite",
            pageNextText: "Nächste Seite",
            pageFirstText: "Erste Seite",
            pageLastText: "Letzte Seite",
            autoload: true,
            pageSize: 20,
            pageButtonCount: 5,
            deleteConfirm: "M&ouml;chten Sie die Adresse wirklich l&ouml;schen?",

            controller: {
                loadData: function (filter) {

                    return $.ajax({
                        type: "GET",
                        url: loadDataUrl + '&showLowPriority=' + ($('#showLowPriority').is(":checked") ? 'true' : 'false'),
                        data: filter,
                        dataType: "JSON"
                    })
                }
            },
            onDataLoaded: function (args) {
                // Default-Sort.
                $("#jsGrid").jsGrid("sort", "timeStampUnix", "desc");

                gridLoaded = true;
                $('#showLowPriority').removeAttr("disabled");

                if (fetchTimerId > 0) window.clearTimeout(fetchTimerId); 
                fetchTimerId = window.setTimeout(doReloadGrid, reloadGridIntervallSeconds * 1000);
            },

            // http://js-grid.com/docs/#fields
            fields: [
                { name: "timeStampFormatted", title: "Datum", type: "text", width: 120 },
                { name: "timeStampUnix", title: "Datum (UNIX)", type: "text", width: 100, visible: true },
                { name: "clientToken", title: "Client", type: "text", width: 80, cellRenderer: function(value, item) {
                          return "<td style='color:#" + intToRGB(hashCode(value)) + "'>" + value + "</td>";
                  }
                },
                { name: "instanceToken", title: "Instanz", type: "text", width: 80, cellRenderer: function(value, item) {
                          return "<td style='color:#" + intToRGB(hashCode(value)) + "'>" + value + "</td>";
                  }
                },
                { name: "projectToken", title: "Projekt-Token", type: "text", width: 130,
                  cellRenderer: function(value, item) {
                          return "<td style='color:#" + intToRGB(hashCode(value)) + "'>" + value + "</td>";
                  }
                },
                { name: "messageCumulated", title: "Text", type: "text", width: 300}
            ]
        });
    }

    $(function() {
        fillGrid();
    });
</script>


<!--<div class="row delta-y-before-2">
    <div class="col-md-12">
        Zu Debugging-Zwecken sehen Sie hier die letzten Aktivitäten beim Projekt-Synchronisieren.
    </div>
</div>-->

<form method="post" action="<?=UrlHelper::GetCurrentFullUrl()?>">
    <div class="row delta-y-before-2">
        <div class="col-md-6">
            <input type="checkbox" id="showLowPriority" name="showLowPriority" class="form-checkbox" <?php if ( $viewBag['showLowPriority'] ) { ?> checked="checked" <?php } ?> />
            <label for="showLowPriority" class="non-bold">PING-Aktivitäten anzeigen</label>
        </div>
        <div class="col-md-6 pull-right text-align-right">
            <a href="javascript:void(0)" onclick="doReloadGrid();" class="btn btn-default">Aktualisieren</a>
            <input class="btn btn-default btn-primary" type="submit" value="Protokoll leeren" />
        </div>
    </div>

    <div id="grid-panel" class="row delta-y-before-2">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-12">
                    <div id="jsGrid"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row delta-y-before-2">
        <div class="col-md-12">
            <input class="btn btn-default btn-primary" type="submit" value="Protokoll leeren" />
            <a href="<?=$viewBag['cancelUrl']?>" class="btn btn-default">Zur&uuml;ck</a>
        </div>
    </div>
</form>

<!-- ##################################################################### -->
<?php require_once('_footer.php') ?>