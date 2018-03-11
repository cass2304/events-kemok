<?php
session_start();
$ruta = '../';
include_once $ruta . 'class/clases.php';

if(!boolAccess('inventory')){
    header('Location:../index.php');
}

$tabla = 'ev_inventory';
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
        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-3"><h3>Inventario</h3></div>
    </div>
    <div class="row row-content">
        <form id="form_ev_inventory" name="form_ev_inventory">
            <input type="hidden" name="tabla" value="<?php print $tabla; ?>">
            <input type="hidden" name="inventory_id" id="inventory_id" value="">
            <?php if($intEvent > 0){ ?>
            <input type="hidden" id="slc_event" name="slc_event" value="<?php print $intEvent; ?>"/>
            <?php }else{ ?>
            <div class="row">    
            <div class="col-sm-4">
                <select class="form-control" id="slc_event" name="slc_event" onchange="loadProduct(this.value);">
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
                <div class="col-sm-12" id="div-product">

                </div>
            </div>
        </form>    
    </div>
</div>


<!-- Modal -->
<div class="modal fade" id="modalReabastecer" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                       <span aria-hidden="true">&times;</span>
                       <span class="sr-only">Close</span>
                </button>
                <h4 class="modal-title" id="myModalLabel"> Reabastecer producto</h4>
            </div>
            
            <!-- Modal Body -->
            <div class="modal-body">
                <form class="form-horizontal form-detail" role="form" id="ev_inventory_detail" name="ev_inventory_detail">
                  <input type="hidden" name="modo" value="supply"/>  
                  <input type="hidden" name="inventory_id" value=""/>
                  <input type="hidden" name="product" value=""/>  
                  <input type="hidden" name="tabla" value="ev_inventory_detail"/> 
                  <div class="form-group">
                    <label  class="col-sm-2 control-label" for="quantity">Cantidad</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="quantity" name="quantity" placeholder="Cantidad"/>
                    </div>
                  </div>
                  <div class="form-group">
                    <!--<label class="col-sm-2 control-label" for="price">Precio</label>-->
                    <div class="col-sm-10">
                        <input type="hidden" class="form-control" id="price" name="price" placeholder="Precio" value="0"/>
                    </div>
                  </div>
                </form>
            </div>
            
            <!-- Modal Footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" onclick="saveForm('ev_inventory_detail');">Reabastecer</button>
            </div>
        </div>
    </div>
</div>

