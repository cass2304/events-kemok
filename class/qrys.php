<?php
class qrys extends BD{		

    function getAccess($usertype){
        $arrReturn = array();
        $strQuery = "SELECT * FROM ev_access WHERE usertype = '{$usertype}'";
        $res = $this->consulta($strQuery);
        if($this->num_rows()>0){
            while($row = $this->fetch_assoc()){
                //if(!isset($arrReturn[$row['view_access']])) $arrReturn[$row['view_access']] = $row['view_access'];
                array_push($arrReturn,$row['view_access']);
            }
        }
        return $arrReturn;
    }
    
    function getEvent(){
        $filterEmpresa = "";
        if($_SESSION['session']['usertype'] != 'superadmin'){
            $filterEmpresa = " AND company_id = {$_SESSION['session']['company_id']}";
        }
        
        $arrReturn = array();
        $strQuery = "SELECT * FROM ev_event WHERE active ='Y' {$filterEmpresa}";
        $res = $this->consulta($strQuery);
        if($this->num_rows()>0){
            while($row = $this->fetch_assoc()){
                array_push($arrReturn,$row);
            }
        }
        return $arrReturn;
    }
    
    function getCategory($intCompany=0){ 
        $filterEmpresa = "";
        if($_SESSION['session']['usertype'] != 'superadmin'){
            $filterEmpresa = " AND company_id = {$_SESSION['session']['company_id']}";
        }elseif($intCompany > 0){
            $filterEmpresa = " AND company_id = {$intCompany}";
        }
        
        $arrReturn = array();
        $strQuery = "SELECT category_id, description FROM ev_category WHERE active ='Y' {$filterEmpresa}";
        $res = $this->consulta($strQuery);
        if($this->num_rows()>0){
            while($row = $this->fetch_assoc()){
                array_push($arrReturn,$row);
            }
        }
        return $arrReturn;
    }

    function getProductInventory($intEvent,$boolOrder=false){
        $arrReturn = array();
        $filterEmpresa = "";
        $intInventory = 0;
        /*if($_SESSION['session']['usertype'] != 'superadmin'){
            $filterEmpresa = " AND P.company_id = {$_SESSION['session']['company_id']}";
        }else{*/
            $this->query = "SELECT E.company_id, IFNULL(I.inventory_id,0) AS inventory_id
                            FROM ev_event E
                            LEFT JOIN ev_inventory I ON I.event_id = E.event_id
                            WHERE E.event_id = {$intEvent} ";
            $this->consulta($this->query);
            if ($this->num_rows() > 0) { 
                $this->rs01 = $this->fetch_assoc();
                $intCompany = $this->rs01['company_id'];
                $intInventory = $this->rs01['inventory_id'];
                $filterEmpresa = " AND P.company_id = {$intCompany}";
            }
        //}
        $strGroupBy = "";
        $strInner = "LEFT ";
        $strField = " IFNULL(D.quantity,0) ";
        $available = ", (D.quantity - D.quantity_sold) AS available ";
        $strOrder = " ORDER BY D.status ";
        $strHaving = "HAVING status <> 'supply'";
        $strWhere = " ";
        $strLeft = ($intInventory == 0) ? " AND ISNULL(D.inventory_id) " : " AND D.inventory_id = {$intInventory} ";
        if($boolOrder){
            $strField = "  D.quantity "; 
            $available = " , (D.quantity - D.quantity_sold) AS available ";
            $strInner = "INNER ";
            $strLeft = "";
            //$strGroupBy = " GROUP BY D.product_id ";
            $strOrder = " ORDER BY P.description ";
            $strHaving = " HAVING available > 0";
            $strWhere = " AND D.status = 'initial' ";
        }
        
        $strQuery = "SELECT P.*, C.description AS category, IFNULL(D.detail_id,0) AS detail_id, IFNULL(I.inventory_id,0) AS inventory_id, 
                            IFNULL(D.price,0) AS price, {$strField} AS quantity, IFNULL(D.status,'no_inventory') AS status , I.event_id, I.active AS active_inv , D.quantity_sold, D.quantity_initial     
                            {$available}
                     FROM ev_product P
                     INNER JOIN ev_category C ON P.category_id = C.category_id
                     {$strInner} JOIN ev_inventory_detail D ON D.product_id = P.product_id {$strLeft}
                     {$strInner} JOIN ev_inventory I ON I.inventory_id = D.inventory_id AND I.event_id = {$intEvent}
                     WHERE P.active = 'Y' {$strWhere} {$filterEmpresa}
                     {$strGroupBy}  
                     {$strHaving}    
                     {$strOrder} "; //print $strQuery;
        $res = $this->consulta($strQuery);
        if($this->num_rows()>0){
            $arrReturn['inventory_id'] = 0;
            $arrReturn['event_id'] = $intEvent;
            while($row = $this->fetch_assoc()){
                if($row['inventory_id'] > 0){
                    $arrReturn['inventory_id'] = $row['inventory_id'];
                }else{
                    $row['detail_id'] = 0; 
                    $row['quantity'] = 0;
                    $row['quantity_sold'] = 0;
                    $row['quantity_initial'] = 0;
                    $row['available'] = 0;   
                    $row['price'] = 0; 
                    $row['status'] = 'initial'; 
                }
                if(!isset($arrReturn['detail'])) $arrReturn['detail'] = array();
                $arrTMP = array(
                    'category' => $row['category'],
                    'description' => $row['description'],
                    'detail_id' => $row['detail_id'],
                    'product_id' => $row['product_id'],
                    'quantity' => $row['quantity'],
                    'quantity_sold' => $row['quantity_sold'],
                    'quantity_initial' => $row['quantity_initial'],
                    'available' => $row['available'],
                    'price' => $row['price'],
                    'status' => $row['status']
                );
                array_push($arrReturn['detail'],$arrTMP);                
            }
        }
        return $arrReturn;
    }
    
    function getOrder($intOrder,$boolDia=false,$intEvent=0){
        $arrReturn = array();
        
        $filterEmpresa = "";
        if($_SESSION['session']['usertype'] != 'superadmin'){
            $filterEmpresa = " AND E.company_id = {$_SESSION['session']['company_id']}";
        }
        
        $strFilter = "";
        $strGroups = "";
        $strField = "D.quantity";
        if($intOrder > 0){
          $strFilter = " AND O.order_id = {$intOrder}";  
        }else if($boolDia){
          $strFilter = " AND DATE_FORMAT(O.creation_date,'%Y-%m-%d') = '".date('Y-m-d')."' ";    
        }else if($intEvent > 0){
            $strFilter = " AND O.event_id = {$intEvent}";  
            $strField = "SUM(D.quantity) AS quantity";
            $strGroups = "GROUP BY D.product_id";
        }
        
        $strQuery = "SELECT O.*, D.detail_id, {$strField}, D.unit_price, P.product_id, P.description AS product, E.description AS event, C.name AS company, C.nit, C.address, U.name AS user_name
                     FROM ev_order O
                     INNER JOIN ev_order_detail D ON D.order_id = O.order_id
                     INNER JOIN ev_product P ON P.product_id = D.product_id
                     INNER JOIN ev_event E ON E.event_id = O.event_id
                     INNER JOIN ev_company C ON C.company_id = E.company_id
                     INNER JOIN ev_user U ON U.user_id = O.uid_creator
                     WHERE 1 {$strFilter} {$filterEmpresa}
                     {$strGroups} ";
                     
        $res = $this->consulta($strQuery);
        if($this->num_rows()>0){
            while($row = $this->fetch_assoc()){
                if($intEvent > 0){
                    $arrTMP = array(
                        'product' => $row['product'],
                        'quantity' => $row['quantity'],
                        'unit_price' => $row['unit_price']
                    );
                    array_push($arrReturn,$arrTMP);
                }else{
                    if(!isset($arrReturn[$row['order_id']])) $arrReturn[$row['order_id']] = array();
                    $arrReturn[$row['order_id']]['order_id'] = $row['order_id'];
                    $arrReturn[$row['order_id']]['event'] = $row['event'];
                    $arrReturn[$row['order_id']]['creation_date'] = $row['creation_date'];
                    $arrReturn[$row['order_id']]['order_amount'] = $row['order_amount'];
                    $arrReturn[$row['order_id']]['company'] = $row['company'];
                    $arrReturn[$row['order_id']]['nit'] = $row['nit'];
                    $arrReturn[$row['order_id']]['address'] = $row['address'];
                    $arrReturn[$row['order_id']]['user_name'] = $row['user_name'];
                    if(!isset($arrReturn[$row['order_id']]['detail'])) $arrReturn[$row['order_id']]['detail'] = array();
                    $arrTMP = array(
                        'product' => $row['product'],
                        'quantity' => $row['quantity'],
                        'unit_price' => $row['unit_price']
                    );
                    array_push($arrReturn[$row['order_id']]['detail'],$arrTMP);
                }
            }
        }
        return $arrReturn;    
    }

}//Fin clase

$qrys = new qrys();
?>