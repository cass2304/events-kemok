<?php
session_start();
$ruta = '../';
include_once $ruta . 'class/clases.php';

if(!boolAccess('report')){
    header('Location:../index.php');
}

$arrEvent = $qrys->getEvent();
$intEvent = 0;
if(sizeof($arrEvent) == 1){
    $intKey = key($arrEvent);
    $arrEvent = $arrEvent[$intKey];
    $intEvent = $arrEvent['event_id'];
}
?>
<div class="div-wrappe">
    <div class="row row-header">
        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-3"><h3>Reporte Ordenes</h3></div>
    </div>
    <div class="row row-content">
        <?php if($intEvent > 0){ ?>
        <input type="hidden" id="slc_event" name="slc_event" value="<?php print $intEvent; ?>"/>
        <?php }else{ ?>
        <div class="row">    
        <div class="col-sm-4">
            <select class="form-control" id="slc_event" name="slc_event" onchange="loadOrders(this.value);">
                <option>Seleccione Evento</option>
                <?php
                foreach($arrEvent as $event) {
                    print "<option value='{$event['event_id']}'>".$event['description']."</option>";
                }
                ?>
            </select>
            <br>
        </div>
        </div>
        <?php } ?>
        <div class="row">
            <div class="col-sm-12 " id="div-report">

            </div>
        </div> 
    </div>
</div>