$(document).ready(function() {
//    loadPage('login.html');    
});

function ftn_login(){
    $.ajax({
        type:"POST",
        url: "include/access/loguea.php",
        data: "login=true&"+$("#frm_login").serialize(),
        dataType: "json",
        beforeSend: function(){
            openModal();
        },
        success: function(data){
            closeModal();
            if(data.status == 'ok'){
                window.location = 'index.php';
            }else{
                showModal("Mensaje",data.error.descripcion,"danger");
            }
        },
        error: function(){
            closeModal();
            showModal("Mensaje","Error de comunicación, intente de nuevo.");
        }
    });

}

function showModal(title, body, strType) {
    $.notify({
       message: body,

    },{
        type: strType,
        animate: {
                enter: 'animated fadeInDown',
                exit: 'animated fadeOutUp'
        }
    }); 
}

function loadPage(strPage){
    if(strPage == 'login'){
        $(".main-container").load("views/"+strPage,function(){});
    }else{
        var arrInfo = strPage.split('.');
        $("#li-"+arrInfo[0]).addClass('active').siblings().removeClass('active');
        $("#content").load("views/"+strPage,function(){
            $('.disabled').click(function (e) {
                e.preventDefault();
            });
            
            if(strPage === 'order.php'){ 
                if($("#slc_event").val() > 0){ 
                    loadProductOrder($("#slc_event").val());
                }
            }   
            if(strPage === 'inventory.php'){ 
                if($("#slc_event").val() > 0){ 
                    loadProduct($("#slc_event").val());
                }
            }
            if(strPage === 'report.php'){ 
                if($("#slc_event").val() > 0){ 
                    loadOrders($("#slc_event").val());
                }
            }
        });
    }
       
}

function loadForm(strTabla,intID){
    $.ajax({
        type:"POST",
        url: "include/procesos_ajax.php",
        data: "loadForm=true&tabla="+strTabla+"&id="+intID,
        dataType: "json",
        beforeSend: function(){
            openModal();
        },
        success: function(data){
            closeModal();
            if(data.status == 'ok'){
                $(".row-content").html(data.result);
                
                if($('#'+strTabla+'_company_id').length > 0 && $('#'+strTabla+'_company_id option').length == 2){
                    $('#'+strTabla+'_company_id option:selected').next().attr('selected', 'selected');  
                }
                
                if(strTabla == 'ev_product'){
                    $("#ev_product_category_id").attr("disabled");
                    $("#ev_product_company_id").change(function(){ 
                        loadCategory($(this).val());
                    });
                }else if(strTabla == 'ev_event'){
                    var date = new Date();
                        date.setDate(date.getDate() - 1);
                    $(".date").datepicker({
                        showAnim: 'slideDown',
                        dateFormat: 'dd/mm/yy'
                    });
                    $(".date").datepicker('setDate', new Date());
                }else if(strTabla == 'ev_user' && intID > 0){
                    $("#username").attr("readonly",true);
                }                
                
            }/*else{
                showModal("Mensaje",data.error.descripcion);
            }*/
        },
        error: function(){
            closeModal();
            showModal("Mensaje","Error de comunicación, intente de nuevo.");
        }
    });
}

function saveForm(strTabla){
    
    var strValue = '';
    if(strTabla == 'ev_inventory_detail'){
        strValue = $(".form-detail").serialize();
        $(".modal-backdrop").removeClass();
    }else{        
        var boolValidate = validate_and_check_form("form_"+strTabla); 
        if(!boolValidate) return false;
        strValue = $("#form_"+strTabla).serialize();
    }
    
    $.ajax({
        type:"POST",
        url: "include/procesos_ajax.php",
        data: "saveForm=true&"+strValue,
        dataType: "json",
        beforeSend: function(){
            openModal();
        },
        success: function(data){
            closeModal();
            var arrInfo = strTabla.split('_');
            if(data.status == 'ok'){
                var strMsj = (data.msj) ? data.msj : '';
                showModal("Mensaje","Registro Guardado"+strMsj,"success");
                loadPage(arrInfo[1]+".php");
            }else{
                showModal("Mensaje","Ocurrio un error, intente de nuevo","danger");
            }
            
        },
        error: function(){
            closeModal();
            showModal("Mensaje","Error de comunicación, intente de nuevo.","danger");
        }
    });
}

function eliminarReg(id,strTabla){
    //$('#row-'+tabla+'-'+id).hide('slow');
    //var Mensaje = '&iquest;Desea borrar '+tabla+' Nro. ' + id +'?';
    //jConfirm(Mensaje, 'Eliminar '+tabla, function(r) {
    //    if(r){
            $.ajax({
                type: 'POST',
                url: 'include/procesos_ajax.php',
                data: 'deleteReg=true&id='+id+'&tabla='+strTabla,
                dataType: 'json',
                beforeSend: function() {
                    openModal();

                },
                success: function(data){
                    closeModal();
                    var arrInfo = strTabla.split('_');
                    if(data.status == 'ok'){
                        showModal("Mensaje","Registro actualizado","success");
                    }else{
                        showModal("Mensaje","Ocurrio un error, intente de nuevo","danger");
                    }
                    loadPage(arrInfo[1]+".php");
                },
                error: function(){
                    closeModal();
                    showModal("Mensaje","Error de comunicación, intente de nuevo.","danger");
                }
            });
        //}
    //});
}

function loadCategory(company){
    $("#ev_product_category_id").html("");
    $.ajax({
        type:"POST",
        url: "include/procesos_ajax.php",
        data: "getCategory=true&company="+company,
        dataType: "json",
        beforeSend: function(){
            openModal();
        },
        success: function(data){
            closeModal();
            if(data.status == 'ok'){
                var option = $("<option value='0'>Seleccione</option>");
                    $("#ev_product_category_id").append(option);
                $.each(data.result,function(intKey,arrData){
                    option = $("<option value='"+arrData.category_id+"'>"+arrData.description+"</option>");
                    $("#ev_product_category_id").append(option);
                });
            }else{
                showModal("Mensaje",data.error.descripcion,"danger");
            }
        },
        error: function(){
            closeModal();
            showModal("Mensaje","Error de comunicación, intente de nuevo.");
        }
    });
}

function loadProduct(intVal){ 
    $.ajax({
        type: 'POST',
        url: 'include/procesos_ajax.php',
        data: 'getProduct=true&event='+intVal,
        dataType: 'json',
        beforeSend: function() {
            openModal();
        },
        success: function(data){
            closeModal();
            var strTable =  "<table class='table table-striped' id='tbl_result' name='tbl_result'>"+
                                    "<tr>"+
                                        "<th>Categoria</th>"+
                                        "<th>Producto</th>"+
                                        "<th>Cant. Inicial</th>"+
                                        "<th>Precio Q</th>"+
                                        "<th>Existencia</th>"+
                                        "<th></th>"+
                                    "</tr>"+
                                "</table>";
            var objTabla = $(strTable);
            $("#inventory_id").val(data.result.inventory_id);
            $.each(data.result.detail,function(intKey,arrData){
                var objTr = $("<tr></tr>");
                var objTdCat = $("<td></td>").append(arrData.category);
                var objTdPro = $("<td></td>").append(arrData.description);
                var objTdCan = $("<td></td>");
                var objTdPre = $("<td></td>");
                var objTdExi = $("<td></td>").append(arrData.available);
                var objTdBtn = $("<td></td>");
                if(arrData.detail_id > 0){
                    objTdCan.append();
                    objTdPre.append(arrData.price);
                    if(arrData.status == 'initial'){
                        var objBtn = $('<button  class="btn btn-primary" type="button" data-toggle="modal" onclick="modalReabastecer('+data.result.inventory_id+','+arrData.product_id+');"><i class="fa fa-plus" aria-hidden="true"></i></button>');
                        objTdBtn.append(objBtn);
                    }
                    
                }else{
                    var objInput = $("<input type='text' class='form-control' name='inv[quantity]["+arrData.product_id+"]' onKeyPress='return SoloEntero(event);' value='0' size='5'>");
                    var objInputPre = $("<input type='text' class='form-control' name='inv[price]["+arrData.product_id+"]' onKeyPress='return SoloMonto(event);' value='0' size='5'>");
                    objTdCan.append(objInput);
                    objTdPre.append(objInputPre);
                }
                objTr.append(objTdCat);
                objTr.append(objTdPro);
                objTr.append(objTdCan);
                objTr.append(objTdPre);
                objTr.append(objTdExi);
                objTr.append(objTdBtn);
                objTabla.append(objTr);
            });
            
            $("#div-product").html(objTabla);
            $("#div-product").append('<center><button  class="btn btn-success" type="button" onclick=saveForm("ev_inventory");>Guardar</button></center>');
        },
        error: function(){
            closeModal();
            showModal("Mensaje","Error de comunicación, intente de nuevo.","danger");
        }
    });
}

function loadOrders(intEvent){
    $.ajax({
        type: 'POST',
        url: 'include/procesos_ajax.php',
        data: 'getOrders=true&boolDate=true&event_id='+intEvent,
        dataType: 'json',
        beforeSend: function() {
            openModal();
        },
        success: function(data){
            closeModal();
            if(intEvent > 0){
                var strTable =  "<table class='table table-striped' id='tbl_result' name='tbl_result'>"+
                                        "<tr>"+
                                            "<th>Producto</th>"+
                                            "<th>Cantidad</th>"+
                                            "<th>Monto Uni.</th>"+
                                            "<th>Total Q</th>"+
                                        "</tr>"+
                                    "</table>";
                var objTabla = $(strTable);
                var intTotal = 0;
                $.each(data.result,function(intKey,arrData){
                    var total = parseInt(arrData.quantity)*parseFloat(arrData.unit_price);
                    var objTr = $("<tr></tr>");
                    var objTdNro = $("<td></td>").append(arrData.product);
                    var objTdDat = $("<td></td>").append(arrData.quantity);
                    var objTdUni = $("<td></td>").append(arrData.unit_price);
                    var objTdMon = $("<td></td>").append(total);

                    objTr.append(objTdNro);
                    objTr.append(objTdDat);
                    objTr.append(objTdUni);
                    objTr.append(objTdMon);
                    objTabla.append(objTr);
                    intTotal+= parseFloat(total);
                });
                var objTr = $("<tr></tr>");
                var objTdTotal = $("<th colspan='3'>Total Q:</th>");
                var objTdMonto = $("<th></th>").append(intTotal);
                    objTr.append(objTdTotal);
                    objTr.append(objTdMonto);
                    objTabla.append(objTr);
                
            }else{
                var strTable =  "<table class='table table-striped' id='tbl_result' name='tbl_result'>"+
                                        "<tr>"+
                                            "<th>Nro.</th>"+
                                            "<th>Fecha</th>"+
                                            "<th>Monto Q</th>"+
                                            "<th></th>"+
                                        "</tr>"+
                                    "</table>";
                var objTabla = $(strTable);
                var intTotal = 0;
                $.each(data.result,function(intKey,arrData){
                    var objTr = $("<tr></tr>");
                    var objTdNro = $("<td></td>").append(arrData.order_id);
                    var objTdDat = $("<td></td>").append(arrData.creation_date);
                    var objTdMon = $("<td></td>").append(arrData.order_amount);
                    var objTdReimp = $("<td></td>");
                    
                    var objButton = $('<button class="btn btn-primary" type="button" onclick=\'sendPrinter('+arrData.order_id+',0);\'>Reimprimir</button>');
                        objTdReimp.append(objButton);

                    objTr.append(objTdNro);
                    objTr.append(objTdDat);
                    objTr.append(objTdMon);
                    objTr.append(objTdReimp);
                    objTabla.append(objTr);
                    intTotal+= parseFloat(arrData.order_amount);
                });
                var objTr = $("<tr></tr>");
                var objTdTotal = $("<th colspan='2'>Total Q:</th>");
                var objTdMonto = $("<th></th>").append(intTotal);
                    objTr.append(objTdTotal);
                    objTr.append(objTdMonto);
                    objTabla.append(objTr);
            }    
            $("#div-report").html(objTabla);            
        },
        error: function(){
            closeModal();
            showModal("Mensaje","Error de comunicación, intente de nuevo.","danger");
        }
    });
}

function modalReabastecer(inventory,product){
   $('#modalReabastecer').modal('show');
   $("input[name='inventory_id']").val(inventory);
   $("input[name='product']").val(product);    
}

function loadProductOrder(intVal){ 
    $("#div-product").html("");
    
    if(intVal == 0){
        showModal("Mensaje","Debe seleccionar un evento","danger");
        return false;
    }
    
    $.ajax({
        type: 'POST',
        url: 'include/procesos_ajax.php',
        data: 'getProduct=true&boolOrder=true&event='+intVal,
        dataType: 'json',
        beforeSend: function() {
            openModal();
        },
        success: function(data){ //console.log(data);
            closeModal();
            if(data.status == 'ok'){
                $("#inventory_id").val(data.result.inventory_id);
                $.each(data.result.detail,function(intKey,arrData){
                    var objDiv = $("<div class='col-xs-6 col-sm-6 col-md-4 col-lg-3'></div>");

                    var objCard = $("<div class='card' id='card_"+arrData.product_id+"' onclick='addProductOrder("+arrData.product_id+","+arrData.price+");'></div>");
                    var objCardBlock = $("<div class='card-block'></div>");
                    var objCardTitle = $("<h5 class='card-title'></h5>").append(arrData.description);
                    var objCardSubTitle = $("<h6 class='card-subtitle mb-2 text-muted'>PVP Q.: </h6>").append(arrData.price);
                    var objCardP = $("<h6 class='card-subtitle mb-2 text-muted'>Stock:</h6>").append(arrData.available);
                    //var objCardAdd = $('<button class="btn btn-success" type="button" ><i class="fa fa-plus" aria-hidden="true"></i></button>');

                        objCardBlock.append(objCardTitle);
                        objCardBlock.append(objCardSubTitle);
                        objCardBlock.append(objCardP);
                        //objCardBlock.append(objCardAdd);
                        objCard.append(objCardBlock);

                    objDiv.append(objCard);
                    $("#div-product").append(objDiv);                
                });     
            }else{
                showModal("Mensaje","Es posible que no tenga un inventario creado para este evento","danger");
            }                                                                   
        },
        error: function(){
            closeModal();
            showModal("Mensaje","Error de comunicación, intente de nuevo.","danger");
        }
    });
}

function addProductOrder(product_id , price){
    var elementos = $('.class_tr');
    var sizeActual = elementos.length;
    var size = elementos.length+1;
    
    if($("#prod_"+product_id).length == 1){
        return false;
    }
    
    var objTr = $('<tr class="class_tr" id="tr-row_'+size+'"></tr>');
    
    var title = $('#card_'+product_id).find('.card-title').html();
    var objTd = $("<td></td>").append(title);
        objTr.append(objTd);

    var objInputCant = $('<input type="number" class="form-control" id="cant_'+product_id+'" name="det[cant]['+product_id+']" value="1" onKeyPress="return SoloEntero(event);" min="1" onchange="updateTotal('+product_id+');">');    
    var objInputHid = $('<input type="hidden" id="prod_'+product_id+'" name="det[product][]" value="'+product_id+'">');
    var objInputPriceHid = $('<input type="hidden" id="price_'+product_id+'" name="det[price]['+product_id+']" value="'+price+'">');
    var objTd = $("<td align='right'></td>").append(objInputCant);
        objTd.append(objInputHid);
        objTd.append(objInputPriceHid);
        objTr.append(objTd);

    var intPrice = (price*1).toFixed(2);    
    var objTd = $("<td align='right'></td>").append(intPrice);
        objTr.append(objTd);

    var objTd = $("<td id='total_"+product_id+"' align='right'></td>").append(intPrice);
        objTr.append(objTd);

    var objButton = $('<button class="btn btn-danger" type="button" name="btn_eliminar_'+size+'" id="btn_eliminar_'+size+'" onclick=\'removeTD('+size+');\'><i class="fa fa-trash fa-lg"></i></button>');
    var objTd = $("<td align='center'></td>").append(objButton);
        objTd.append(objButton);
        objTr.append(objTd);
    $("#tbody-order").append(objTr);   

    if(size >= 1){
        $("#btn_order").removeClass("disabled");
    }else{
        $("#btn_order").addClass("disabled");
    } 
    updateTotal();
}

function removeTD(intValue){
    $('#tr-row_'+intValue).remove();
    var elementos = $('.class_tr');
    var sizeActual = elementos.length;
    if(sizeActual >= 1){
        $("#btn_order").removeClass("disabled");
    }else{
        $("#btn_order").addClass("disabled");
    } 
    updateTotal();
}

function updateTotal(){
    var totalPagar = 0;
    $("input[name*='det[cant]']").each(function() {
        var arrId = $(this).attr('id').split('_');
        var intPrice = $('#price_'+arrId[1]).val();
        var totalRow = (parseFloat(intPrice)*parseInt($(this).val())).toFixed(2);
        $("#total_"+arrId[1]).html(totalRow);
        totalPagar+= parseFloat(totalRow);    
    }); 
    $("#total_gen").html(totalPagar.toFixed(2));            
}

function printOrder(){
    var strValue = $("#form_ev_order").serialize();    
    $.ajax({
        type:"POST",
        url: "include/procesos_ajax.php",
        data: "saveOrder=true&"+strValue+'&slc_event='+$("#slc_event").val(),
        dataType: "json",
        beforeSend: function(){
            openModal();
        },
        success: function(data){
            closeModal();
            if(data.status == 'ok'){
                showModal("Mensaje","Se genero la orden Nro."+data.id ,"success");
                $("#tbody-order").html("");
                $("#total_gen").html("");
                $("#btn_order").addClass("disabled");
                //window.open("include/procesos_ajax.php?printOrder=true&id="+data.id);
                sendPrinter(data.id,0);
            }else{
                showModal("Mensaje","Ocurrio un error, intente de nuevo","danger");
            }
            //loadPage(arrInfo[1]+".php");
        },
        error: function(){
            closeModal();
            showModal("Mensaje","Error de comunicación, intente de nuevo.","danger");
        }
    });
}

function sendPrinter(order_id,copia){
    $.ajax({
        type:"POST",
        url: "include/procesos_ajax.php?printOrder=true&id="+order_id+"&copia="+copia,
        //data: "saveOrder=true&"+strValue+'&slc_event='+$("#slc_event").val(),
        dataType: "json",
        beforeSend: function(){
            openModal();
        },
        success: function(data){
            closeModal();
            if(data.status == 'ok'){
                if(copia == 0){
                    showModalCopy(order_id);
                }else{
                    $("#modalReimp").remove();
                    $('.modal-backdrop').remove();
                    if($("#slc_event").val() > 0){ 
                        loadProductOrder($("#slc_event").val());
                    }                    
                }
            }else{
                showModal("Mensaje","Ocurrio un error, intente de nuevo","danger");
            }
            //loadPage(arrInfo[1]+".php");
        },
        error: function(){
            closeModal();
            showModal("Mensaje","Error de comunicación, intente de nuevo.","danger");
        }
    });
}

function showModalCopy(order_id){
    var html = '<div class="modal fade" tabindex="-1" role="dialog" id="modalReimp">' +
        '<div class="modal-dialog">' +
        '<div class="modal-content">' +
        '<div class="modal-header">' +
        '<h4 class="modal-title">Mensaje</h4>' +
        '</div>' +
        '<div class="modal-body">' +
        '<p class="text-center">Imprimir copia comercio</p>' +
        '</div>' +
        '<div class="modal-footer">' +
        '<button type="button" class="btn btn-primary" data-toggle="modal" data-backdrop="static" data-keyboard="false" onclick="sendPrinter('+order_id+',1)">Imprimir</button>' +
        '</div>' +
        '</div>' +
        '</div>' +
        '</div>';

    var modal = $(html);
    modal.modal({
                backdrop: 'static',
                keyboard: false, 
                show: true
               });    
}

function openModal() {
    $("#modal").show();
    $("#fade").show();        
}

function closeModal() {
    $("#modal").hide();
    $("#fade").hide();
}