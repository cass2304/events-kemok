<?php
    session_start();
    $ruta = '../';
    include_once $ruta.'class/clases.php';
    
    if(!boolAccess('order')){
        header('Location:../index.php');
    }
    
    $tabla = 'ev_order';
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
        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-3"><h3>Orden</h3></div>
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-9 text-right">
          <button type="button" class="btn btn-success" onclick="loadOrders(0);"><i class="fa fa-list" aria-hidden="true"></i> Ordenes del dia</button>
        </div>
    </div>
    <div class="row row-content" id="div-report">
        <?php if($intEvent > 0){ ?>
            <input type="hidden" id="slc_event" name="slc_event" value="<?php print $intEvent; ?>"/>
        <?php }else{ ?>
        <div class="row">    
        <div class="col-sm-4">
            <select class="form-control" id="slc_event" name="slc_event" onchange="loadProductOrder(this.value);">
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
            <div class="col-xs-12 col-sm-6" id="div-product">
            </div>
            <div class="col-xs-12 col-sm-6" id="div-order">
               <form id="form_ev_order" name="form_ev_order">
                <table class="table table-striped">
                    <thead>
                    <tr class="active">
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Precio Uni Q.</th>
                        <th>Total Q.</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody id="tbody-order">

                    </tbody>
                    <tfoot>
                        <tr class="active">
                            <td><b>Total Q.</b></td>
                            <td id="total_gen" colspan="3" align="right"></td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
               </form> 
               <button class="btn btn-success disabled" type="button" id="btn_order" onclick="printOrder();"><i class="fa fa-print"></i> Imprimir Orden</button>
            </div>
        </div>           
    </div>
</div>