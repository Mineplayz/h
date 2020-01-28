<?php

// ::ZETA-PRODUCER-NO-COMPRESSION::

require_once('../afx.inc.php');

$loginController = new ZpsBackendServerProjectStatusController();
$viewBag = $loginController->HandleGetOrPost();

/**
 * @var ZpsBackendProjectStateModel $model
 */
$model = $viewBag['model'];

$title = "Projektstatus";

// --

$colLabel = "col-xs-12 col-sm-12 col-md-12";
$colEditor = "col-xs-12 col-sm-12 col-md-12";
$colEditorB = "col-xs-12 col-sm-12 col-sm-offset-0 col-md-12 col-md-offset-0";

$viewBag['additional_header'] = "<script type=\"text/javascript\" src=\"resources/doT.min.js\"></script>";
?>

<?php require_once('_header.php') ?>

<!-- ##################################################################### -->

<script>
    var fetchUrl = '<?=UrlHelper::SetParameters("api.php", array(
                    "action" => "get-project-state")) ?>';
</script>

<!-- ******************************************************** -->

<!-- https://olado.github.io/doT -->
<script id="statusTemplate" type="text/x-dot-template">
    <div class="zpb-table">
        {{ for(var index in it) { }}
            <div class="zpb-table-row">
                {{ for(var item in it[index]) { }}
                    <div class="zpb-table-cell zpb-table-column0">
                        {{=item}}:
                    </div>
                    <div class="zpb-table-cell zpb-table-column1">
                        {{=it[index][item]}}
                    </div>
                {{ } }}
            </div>
        {{ } }}
    </div>
</script>

<!-- ******************************************************** -->

<!-- https://olado.github.io/doT -->
<script id="pingsTemplate" type="text/x-dot-template">
    <div class="zpb-table">
        {{ for(var index in it) { }}
            <div class="ping-info-block">
                <div class="zpb-table">
                    <div class="zpb-table-row">
                        <div class="zpb-table-cell">
                            ID:
                        </div>
                        <div class="zpb-table-cell">
                            {{=it[index].id}}
                        </div>
                    </div>
                    <div class="zpb-table-row">
                        <div class="zpb-table-cell">
                            Erstelldatum:
                        </div>
                        <div class="zpb-table-cell">
                            {{=it[index].dateCreatedFormatted}}
                        </div>
                    </div>
                    <div class="zpb-table-row">
                        <div class="zpb-table-cell">
                            Generiert von Client mit Token:
                        </div>
                        <div class="zpb-table-cell">
                            {{=it[index].generatedByClientToken}}
                        </div>
                    </div>
                    <div class="zpb-table-row">
                        <div class="zpb-table-cell">
                            Generiert von Client mit Instanz-Token:
                        </div>
                        <div class="zpb-table-cell">
                            {{=it[index].generatedByInstanceToken}}
                        </div>
                    </div>
                    <div class="zpb-table-row">
                        <div class="zpb-table-cell">
                            Aktion:
                        </div>
                        <div class="zpb-table-cell">
                            {{=it[index].action}}
                        </div>
                    </div>
                </div>
            </div>
        {{ } }}
    </div>
</script>

<!-- ******************************************************** -->

<script>
    var fetchTimerId = 0;

    $(function () {
        $('#confirm').focus();

        fetchTimerId = window.setTimeout(fetchState, 1000);
        window.setTimeout(hideNotify, 5000);
    });

    function putPingsJsonTabularDataToControl(element, json) {
        element.empty();

        if (!$.isArray(json) || !json.length) {
            $('#pinginfos').hide();
        } else {
            // http://www.stasha.info/index.php?id=170
            var template = doT.template($('#pingsTemplate').html());
            var resultText = template(json);
            element.append(resultText);

            $('#pinginfos').show();
        }
    }

    function putStatusJsonTabularDataToControl(element, json) {
        element.empty();

        if (!$.isArray(json) || !json.length) {
            element.append("Keine Informationen vorhanden.");
        } else {
            // http://www.stasha.info/index.php?id=170
            var template = doT.template($('#statusTemplate').html());
            var resultText = template(json);
            element.append(resultText);
        }
    }

    function fetchState() {
        $.ajax({
            type: "GET",
            url: fetchUrl,
            dataType: "JSON",
            success: function (data) {
                putStatusJsonTabularDataToControl($('#stateControl'), data.state);
                putPingsJsonTabularDataToControl($('#pingStateControl'), data.pings);

                window.clearTimeout(fetchTimerId);
                fetchTimerId = window.setTimeout(fetchState, 1000);
            },
            error: function () {
                window.clearTimeout(fetchTimerId);
                fetchTimerId = window.setTimeout(fetchState, 1000);
            }
        })
    }

    function hideNotify() {
        $('#success-panel').hide();
    }
</script>

<div class="row delta-y-before-2">
    <div class="col-md-8 col-xs-12">
        Sollte sich Ihr Projekt nicht mehr vom Server downloaden lassen oder nicht mehr auf den Server hochladen lassen,
            z.&nbsp;B. weil ein Upload/Download abgebrochen ist, so k&ouml;nnen Sie es hier auf einen definierten Anfangszustand setzen.
    </div>
</div>

<div class="row delta-y-before-2 form-group">
    <div class="<?=$colLabel?>">
        <label>Aktuelles Projekt</label>
    </div>
    <div class="<?=$colEditor?>">
        <div id="stateControl" class="project-status-box">
            Informationen werden abgerufen&hellip;
        </div>
    </div>
</div>

<!-- ######################################################################### -->

<div class="row delta-y-before-1 form-group" id="pinginfos" style="display: none">
    <div class="<?=$colLabel?>">
        <label>Aktuelle Ping-Infos</label>
    </div>
    <div class="<?=$colEditor?>">
        <div id="pingStateControl" class="project-status-box">
        </div>
    </div>
</div>

<!-- ######################################################################### -->

<form method="post" action="<?=UrlHelper::GetCurrentFullUrl()?>">
    <div class="row delta-y-before-2">
        <div class="col-md-12">
            <input class="btn btn-default btn-primary" type="submit" value="Projekt zur&uuml;cksetzen" />
            <a href="<?=$viewBag['cancelUrl']?>" class="btn btn-default">Zur&uuml;ck</a>
        </div>
    </div>
</form>

<!-- ##################################################################### -->
<?php require_once('_footer.php') ?>
