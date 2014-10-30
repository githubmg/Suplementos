<?php

    // singleton es un patron de dise&Ntilde;o que impide que un mismo objeto no pueda ser instanciado mas de una vez
    ini_set('error_reporting', E_ALL);
    ini_set( 'display_errors', 1 );
    ini_set('max_execution_time', 500);
    class DB {

        static private $db;

        static private $dsn = 'mysql:host=localhost;dbname=ux000486_suplementos';
        static private $user = 'ux000486_suple';
        static private $password = 'Cantghost2707';
        static private $options = array(PDO::MYSQL_ATTR_INIT_COMMAND=>'SET NAMES utf8'); 
        static public $pageSize = 30;
		  /*
            para terminar de armar el Singleton impido que puedas hacer
            new DB.
            PHP permite constructores privados
        */
        private function __construct(){
            
        }
		
        private static function getConnection() { // al hacerlo estatico se puede acceder desde afuera sin instanciarla


                if ( empty(self::$db) ) {

                    self::$db = new PDO ( self::$dsn , self::$user , self::$password , self::$options );
                }

               return self::$db;

        }
        
		private static function getStatement($sql) { // debe devolver un PDO statement

               // $db = self::getConnection();

               // return $db->prepare($sql);

               return self::getConnection()->prepare($sql); // lo hago en una sola linea
        }
        
		public static function getItemsVentaOrderByFecha(){
            $sql = 'SELECT id_item_venta,fecha from item_venta iv INNER JOIN venta v ON iv.id_venta = v.id_venta ORDER BY v.fecha, iv.id_item_venta';
            $stmt = DB::getStatement($sql);
            $stmt->execute();

            $objeto = $stmt->fetchAll();
            return $objeto;
        }
        
		public static function reasignarCostoItemsVenta(){
                
                DB::blanquearStockCompras();
                DB::blanquearCostoVentas();
                $itemsPorFecha = DB::getItemsVentaOrderByFecha();
                foreach( $itemsPorFecha as $mi_item_venta ){
                        $iv = new ItemVentaDB($mi_item_venta['id_item_venta']);
                        $iv->reasignarCostoItem();

                }
                
        }
        
		public static function getCostoMasReciente($id_producto, $fecha){
        //FRAN Agrego la fecha 
                    $sql = 'SELECT monto_total,cantidad from item_compra ic
                                            INNER JOIN compra c ON ic.id_compra = c.id_compra
                                            WHERE ic.id_producto = :id_producto AND c.fecha <= "'.$fecha.'" '.'ORDER BY c.fecha DESC,ic.id_item_compra DESC LIMIT 1';

                    $stmt = DB::getStatement($sql);
                    $stmt->bindParam(':id_producto',$id_producto,PDO::PARAM_INT); 
                    $stmt->execute();
                    $obj = $stmt->fetchObject();
                    if ($obj){
                            $costo = ($obj->monto_total)/($obj->cantidad);
                                    if ($costo){
                                            return ($costo);
                                            }
                            return 0;
                    }else {
                            return 0;
                    }
            }

        public static function blanquearCostoVentas(){
                $sql = 'UPDATE item_venta set costo = 0';
    
                $stmt = DB::getStatement($sql);
                $rdo = $stmt->execute();
            
                if (!$rdo) {
                    print_r($stmt->errorInfo());
                    echo "<br>";
                    throw new Exception ("Error al guardar en la base de datos: ".$stmt->errorCode());
                }   
                
                
        }
        
		public static function blanquearStockCompras(){
                $sql = 'UPDATE item_compra set unidades_no_asignadas = cantidad';
    
                $stmt = DB::getStatement($sql);
                $rdo = $stmt->execute();
            
                if (!$rdo) {
                    print_r($stmt->errorInfo());
                    echo "<br>";
                    throw new Exception ("Error al guardar en la base de datos: ".$stmt->errorCode());
                }   
                
                
        }
        
		public static function getItemsVentaByProducto($producto){
                $sql = 'SELECT * from item_venta iv
                        INNER JOIN venta v ON iv.id_venta = v.id_venta
                        WHERE iv.id_producto = :id_producto  ORDER BY v.fecha,iv.id_item_venta';

                $stmt = DB::getStatement($sql);
                $id = $producto->getID();
            
                $stmt->bindParam(':id_producto',$id,PDO::PARAM_INT); 
                $stmt->execute();
                $obj = $stmt->fetchAll();
                if ($obj){
                        return ($obj);
                }else {
                        return 0;
                }
                
        }
        
		public static function getDeudores(){
            $sql = 'SELECT c.id_cliente, SUM(v.total_abonado) as "abonado", SUM(costo_venta.monto) as "monto" FROM cliente c INNER JOIN venta v ON v.id_cliente = c.id_cliente
                    INNER JOIN (SELECT SUM(monto_total) AS monto,
                    id_venta FROM item_venta GROUP BY id_venta) AS costo_venta
                    ON costo_venta.id_venta = v.id_venta
                    WHERE costo_venta.monto > v.total_abonado
                    GROUP BY c.id_cliente';
            $stmt = DB::getStatement($sql);
            $stmt->execute();
            $obj = $stmt->fetchAll();
            return $obj;
        }
      
        public static function getIdsVenta($indexMenu){
            $sql = 'SELECT id_venta FROM venta where activo = 1 ORDER BY id_venta desc LIMIT '.self::$pageSize.' OFFSET '.(($indexMenu-1)*self::$pageSize);
            $stmt = DB::getStatement($sql); 
            $stmt->execute(); 
            $obj = $stmt->fetchAll();
            return $obj;
        }

		public static function getIdsComisionesByParam($fecha_desde, $fecha_hasta, $id_cliente, $indexMenu) {

            $sql = "SELECT id
            FROM comision com 
			INNER JOIN cliente cli on cli.id_cliente = com.id_cliente
			";
            $sql = $sql." WHERE com.activo = 1 ";
           
            if ($fecha_desde!='') {
                $sql = $sql."AND com.fecha >= :fecha_desde ";
            }

            if ($fecha_hasta!='') {
                $sql = $sql." AND com.fecha <= :fecha_hasta ";
            }
			if ($id_cliente!='') {
                $sql = $sql." AND com.id_cliente = :id_cliente ";
            }
			
            $sql = $sql." ORDER BY com.fecha DESC";
            $sql = $sql.' LIMIT '.self::$pageSize." OFFSET ".(($indexMenu-1)*self::$pageSize);
            	
			$stmt = DB::getStatement($sql);

            if ($fecha_desde!='') {
                $stmt->bindParam(':fecha_desde',$fecha_desde,PDO::PARAM_STR);
            }

            if ($fecha_hasta!='') {
                $stmt->bindParam(':fecha_hasta',$fecha_hasta,PDO::PARAM_STR);
            }
			
			if ($id_cliente!='') {
                $stmt->bindParam(':id_cliente',$id_cliente,PDO::PARAM_STR);
            }
			
            $stmt->execute();

            $objeto = $stmt->fetchAll();
            return $objeto;
        }

		public static function getCantidadDePaginasComisionByParam($fecha_desde, $fecha_hasta, $id_cliente) {
            
            $sql = "SELECT count(1) as cantidad
            FROM comision com
			INNER JOIN cliente cli on cli.id_cliente = com.id_cliente
			WHERE com.activo = 1 ";
            
            if ($fecha_desde!='') {
                $sql = $sql." AND com.fecha >= :fecha_desde ";
            }
            if ($fecha_hasta!='') {
                $sql = $sql." AND com.fecha <= :fecha_hasta ";
            }
			if ($id_cliente!='') {
			    $sql = $sql." AND com.id_cliente = :id_cliente ";
            }
			$stmt = DB::getStatement($sql);

            if ($fecha_desde!='') {
                $stmt->bindParam(':fecha_desde',$fecha_desde,PDO::PARAM_STR);
            }
			if ($fecha_hasta!='') {
                $stmt->bindParam(':fecha_hasta',$fecha_hasta,PDO::PARAM_STR);
            }
			if ($id_cliente!='') {
                $stmt->bindParam(':id_cliente',$id_cliente,PDO::PARAM_STR);
            }
			$stmt->execute();

            $objeto = $stmt->fetchObject();
			
			if ($objeto){
				return (intval(($objeto->cantidad)/self::$pageSize) +1);
			}else {
				return 1;
			}
        }
        
        public static function getIdsItemVentasByParam($fecha_desde, $fecha_hasta, $id_cliente, $id_producto, $id_venta, $subempresa, $indexMenu) {

            $sql = "SELECT id_item_venta
            FROM item_venta iv 
            INNER JOIN venta v on iv.id_venta = v.id_venta
            INNER JOIN cliente c on v.id_cliente = c.id_cliente
            ";
            $sql = $sql." WHERE v.activo = 1 ";
           
            if ($fecha_desde!='') {
                $sql = $sql."AND v.fecha >= :fecha_desde ";
            }

            if ($fecha_hasta!='') {
                $sql = $sql." AND v.fecha <= :fecha_hasta ";
            }
            if ($id_cliente!='') {
                $sql = $sql." AND v.id_cliente = :id_cliente ";
            }
            if ($id_producto!='') {
                $sql = $sql." AND iv.id_producto = :id_producto ";
            }
        
            if ($id_venta!='') {
                $sql = $sql." AND v.id_venta = :id_venta ";
            }

            if ($subempresa!='') {
                $sql = $sql." AND iv.subempresa= :subempresa ";
            }
            $sql = $sql." ORDER BY v.fecha DESC,v.id_venta DESC";
            $sql = $sql.' LIMIT '.self::$pageSize." OFFSET ".(($indexMenu-1)*self::$pageSize);
                
            $stmt = DB::getStatement($sql);

            if ($fecha_desde!='') {
                $stmt->bindParam(':fecha_desde',$fecha_desde,PDO::PARAM_STR);
            }

            if ($fecha_hasta!='') {
                $stmt->bindParam(':fecha_hasta',$fecha_hasta,PDO::PARAM_STR);
            }
            
            if ($id_cliente!='') {
                $stmt->bindParam(':id_cliente',$id_cliente,PDO::PARAM_STR);
            }
            
            if ($id_producto!='') {
                $stmt->bindParam(':id_producto',$id_producto,PDO::PARAM_STR);
            }

            if ($id_venta!='') {
                $stmt->bindParam(':id_venta',$id_venta,PDO::PARAM_STR);
            }
            if ($subempresa!='') {
                $stmt->bindParam(':subempresa',$subempresa,PDO::PARAM_STR);
            }

            $stmt->execute();

            $objeto = $stmt->fetchAll();
            return $objeto;
        }
        
		public static function getIdsItemVentasByParamGanancia($fecha_desde, $fecha_hasta, $id_producto, $subempresa, $indexMenu) {

            $sql = "SELECT id_item_venta
            FROM item_venta iv 
            INNER JOIN venta v on iv.id_venta = v.id_venta
            INNER JOIN cliente c on v.id_cliente = c.id_cliente
            ";
            $sql = $sql." WHERE v.activo = 1 ";
           
            if ($fecha_desde!='') {
                $sql = $sql."AND v.fecha >= :fecha_desde ";
            }

            if ($fecha_hasta!='') {
                $sql = $sql." AND v.fecha <= :fecha_hasta ";
            }
            if ($id_producto!='') {
                $sql = $sql." AND iv.id_producto = :id_producto ";
            }

            if ($subempresa!='') {
                $sql = $sql." AND c.subempresa= :subempresa ";
            }
            $sql = $sql." ORDER BY v.fecha DESC,v.id_venta DESC";
            $sql = $sql.' LIMIT '.self::$pageSize." OFFSET ".(($indexMenu-1)*self::$pageSize);
                
            $stmt = DB::getStatement($sql);

            if ($fecha_desde!='') {
                $stmt->bindParam(':fecha_desde',$fecha_desde,PDO::PARAM_STR);
            }

            if ($fecha_hasta!='') {
                $stmt->bindParam(':fecha_hasta',$fecha_hasta,PDO::PARAM_STR);
            }
            
            if ($id_producto!='') {
                $stmt->bindParam(':id_producto',$id_producto,PDO::PARAM_STR);
            }
            if ($subempresa!='') {
                $stmt->bindParam(':subempresa',$subempresa,PDO::PARAM_STR);
            }

            $stmt->execute();

            $objeto = $stmt->fetchAll();
            return $objeto;
        }
		
		public static function deleteItemVenta (ItemVenta $item){
                $sql = 'DELETE FROM item_venta WHERE id_item_venta = :id';
    
                $stmt = DB::getStatement($sql);
                $id = $item->getId();
                $stmt->bindParam(':id',$id);
                $rdo = $stmt->execute();
            
                if (!$rdo) {
                    print_r($stmt->errorInfo());
                    echo "<br>";
                    throw new Exception ("Error al guardar en la base de datos: ".$stmt->errorCode());
                }
        }
        
		public static function deleteItemCompra (ItemCompra $item){
                $sql = 'DELETE FROM item_compra WHERE id_item_compra = :id';
    
                $stmt = DB::getStatement($sql);
                $id = $item->getId();
                $stmt->bindParam(':id',$id);
                $rdo = $stmt->execute();
            
                if (!$rdo) {
                    print_r($stmt->errorInfo());
                    echo "<br>";
                    throw new Exception ("Error al guardar en la base de datos: ".$stmt->errorCode());
                }
        }
        
		public static function getCantidadItemsCompra(Compra $compra) {
            
            $sql = 'select count(*) from item_compra where id_compra = :id_compra';
            $stmt = DB::getStatement($sql);
            $id_compra = $compra->getId();
            $stmt->bindParam(':id_compra',$id_compra,PDO::PARAM_STR);
            if ($stmt->execute()) {

                $rdo = $stmt->fetch(PDO::FETCH_NUM);
                return $rdo[0];
            } else {
                    return false;
                
            }
        }
       
  	    public static function getCantidadItemsVenta(Venta $venta) {
            
            $sql = 'select count(*) from item_venta where id_venta = :id_venta';
            $stmt = DB::getStatement($sql);
            $id_venta = $venta->getId();
            $stmt->bindParam(':id_venta',$id_venta,PDO::PARAM_STR);
            if ($stmt->execute()) {

                $rdo = $stmt->fetch(PDO::FETCH_NUM);
                return $rdo[0];
            } else {
                    return false;
                
            }
        }
        
		public static function getIdsCliente($indexMenu){
            
            if ($indexMenu==null) {
                $sql = 'SELECT c.id_cliente as id_cliente FROM cliente c INNER JOIN persona p on p.id_persona = c.id_persona WHERE c.activo = 1 ORDER BY p.nombre';
            }
            else {
                $sql = 'SELECT c.id_cliente as id_cliente FROM cliente c INNER JOIN persona p on p.id_persona = c.id_persona WHERE c.activo = 1 ORDER BY p.nombre
                    LIMIT '.self::$pageSize.' OFFSET '.(($indexMenu-1)*self::$pageSize);
            }
           
            $stmt = DB::getStatement($sql); 
            $stmt->execute(); 
            $obj = $stmt->fetchAll();
            return $obj;
        }
        
		public static function getCostoVenta($id_venta){
                     
            $sql = 'SELECT SUM(iv.costo) as costo FROM item_venta iv INNER JOIN venta v ON v.id_venta = iv.id_venta WHERE v.id_venta = '.$id_venta;
            
           
            $stmt = DB::getStatement($sql); 
            $stmt->execute(); 
            $obj = $stmt->fetchObject();
            return $obj->costo;
        }
        
        public static function getIdsLocalidades($idProvincia){
            if ($idProvincia==null) {
                $sql = 'SELECT l.id_localidad as id_localidad FROM localidad l WHERE activo = 1 ORDER BY l.descripcion';
            }
            else {
                $sql = 'SELECT l.id_localidad as id_localidad FROM localidad l  WHERE l.activo = 1 AND l.id_provincia =  :id_provincia 
                        ORDER BY l.descripcion';
                
            }
            $stmt = DB::getStatement($sql); 
            if ($idProvincia!=null) {
            $stmt->bindParam(':id_provincia',$idProvincia,PDO::PARAM_INT); 
            }
            $stmt->execute(); 
            $obj = $stmt->fetchAll();
            return $obj;
        }
        
		public static function getIdsProvincias(){
            
            $sql = 'SELECT id_provincia FROM provincia WHERE activo = 1 ORDER BY id_provincia';
            $stmt = DB::getStatement($sql); 
            $stmt->execute(); 
            $obj = $stmt->fetchAll();
            return $obj;
        }
        
		public static function getIdsLab(){
            
            $sql = 'SELECT id_laboratorio FROM laboratorio WHERE activo = 1';
            $stmt = DB::getStatement($sql); 
            $stmt->execute(); 
            $obj = $stmt->fetchAll();
            return $obj;
        }
        
		public static function getIdsProducto($indexMenu){
            if ($indexMenu==null) {
                $sql = 'SELECT id_producto FROM producto WHERE activo = 1 ORDER BY descripcion ASC';
            }
            else {
                $sql = 'SELECT id_producto FROM producto WHERE activo = 1 ORDER BY descripcion ASC LIMIT '.self::$pageSize.' OFFSET '.(($indexMenu-1)*self::$pageSize);
            }
            $stmt = DB::getStatement($sql); 
            $stmt->execute(); 
            $obj = $stmt->fetchAll();
            return $obj;
        }
        
		public static function getClienteById($id_cliente){
            $sql = 'SELECT * FROM cliente WHERE id_cliente = :id_cliente';

            $stmt = DB::getStatement($sql); 
            $stmt->bindParam(':id_cliente',$id_cliente,PDO::PARAM_INT); 
                      
            $stmt->execute(); 

            return $stmt->fetchObject();
        }
		
		public static function getComsionById($id){
            $sql = 'SELECT * FROM comision WHERE id=:id';

            $stmt = DB::getStatement($sql); 
            $stmt->bindParam(':id',$id,PDO::PARAM_INT); 
                      
            $stmt->execute(); 

            return $stmt->fetchObject();
	}			
		
		public static function getLaboratorioById($id_laboratorio){
            $sql = 'SELECT * FROM laboratorio WHERE id_laboratorio = :id_laboratorio';

            $stmt = DB::getStatement($sql); 
            $stmt->bindParam(':id_laboratorio',$id_laboratorio,PDO::PARAM_INT); 
                      
            $stmt->execute(); 

            return $stmt->fetchObject();
        }
                     
        public static function getPersonaById($id_persona) {
            $sql = 'SELECT * FROM persona WHERE id_persona = :id_persona';

            $stmt = DB::getStatement($sql); 
            $stmt->bindParam(':id_persona',$id_persona,PDO::PARAM_INT); 
            $stmt->execute(); 

            return $stmt->fetchObject();
        }
	  
		public static function getComisionById($id) {
            $sql = 'SELECT * FROM comision WHERE id = :id';

            $stmt = DB::getStatement($sql); 
            $stmt->bindParam(':id',$id,PDO::PARAM_INT); 
            $stmt->execute(); 

            return $stmt->fetchObject();
        }
        
        public static function getDomicilioById($id_domicilio) {
            $sql = 'SELECT * FROM domicilio WHERE id_domicilio = :id_domicilio';

            $stmt = DB::getStatement($sql); 
            $stmt->bindParam(':id_domicilio',$id_domicilio,PDO::PARAM_INT); 
            $stmt->execute(); 

            return $stmt->fetchObject();
        }
        
        public static function getStockProducto($id_producto){
             $sql = 'select (COALESCE(q1.cantidad, 0) - COALESCE(q2.cantidad, 0)) as cantidad FROM 
                    (select sum(c.cantidad) as cantidad, c.id_producto  from item_compra c  group by c.id_producto )
                    as q1 LEFT JOIN 
                    (select sum(v.cantidad) as cantidad, v.id_producto  from item_venta v group by v.id_producto )
                    as q2 ON q1.id_producto = q2.id_producto
                    WHERE q1.id_producto = :id_producto';
                    $stmt = DB::getStatement($sql); 
                    $stmt->bindParam(':id_producto',$id_producto,PDO::PARAM_INT); 
                    $stmt->execute(); 
                    $obj = $stmt->fetchObject();
                    if ($obj){
                        return $obj-> cantidad;
                    }else{
                        return 0;
                    }
                    
        }
        
		public static function getStockProductoSM($id_producto){
             $sql = "select (COALESCE(q1.cantidad, 0) - COALESCE(q2.cantidad, 0)) as cantidad FROM 
                    (select sum(c.cantidad) as cantidad, c.id_producto  from item_compra c WHERE c.subempresa = 'SM' group by c.id_producto )
                    as q1 LEFT JOIN 
                    (select sum(v.cantidad) as cantidad, v.id_producto  from item_venta v WHERE v.subempresa = 'SM' group by v.id_producto )
                    as q2 ON q1.id_producto = q2.id_producto
                    WHERE q1.id_producto = :id_producto";
                    $stmt = DB::getStatement($sql); 
                    $stmt->bindParam(':id_producto',$id_producto,PDO::PARAM_INT); 
                    $stmt->execute(); 
                    $obj = $stmt->fetchObject();
                    if ($obj){
                        return $obj-> cantidad;
                    }else{
                        return 0;
                    }
                    
        }
        
		public static function getUnidadesNoAsignadas($id_item_compra){
                    $sql = 'Select unidades_no_asignadas from item_compra where id_item_compra = :id_item_compra';
                    $stmt = DB::getStatement($sql);
                    $stmt->bindParam(':id_item_compra',$id_item_compra,PDO::PARAM_INT); 
                    $stmt->execute(); 
                    $obj = $stmt->fetchObject();
                    if ($obj){
                        return $obj-> unidades_no_asignadas;
                    }else{
                        return 0;
                    }
                    
        }

        public static function getTotalCompraById($id_compra) {
            $sql = 'select sum(monto_total) as "total" from item_compra where id_compra = :id_compra';
            $stmt = DB::getStatement($sql);
            $stmt->bindParam(':id_compra', $id_compra, PDO::PARAM_INT);
            $stmt->execute();
            $obj = $stmt->fetchObject();
            if($obj) {
                return $obj->total;
            } else {
                return "Error";
            } 
        }
        
		public static function existenVentasPosteriores($venta){
                    $sql = 'SELECT * from venta WHERE fecha > "'.$venta->getFecha().'"';
                    $stmt = DB::getStatement($sql);
                    $stmt->execute();
                    $obj = $stmt->fetchAll();
                    if ($obj){
                        return true;
                    } else {
                        return false;
                    }
                    
            }
    
        public static function getItemsComprasNoAsignadasOrdenadasPorFechaByProducto($id_producto, $fecha){
                    $sql = 'SELECT * from item_compra ic
                            INNER JOIN compra c ON ic.id_compra = c.id_compra
                            WHERE ic.id_producto = :id_producto AND c.fecha <= "'.$fecha.
                            '" ORDER BY c.fecha,ic.id_item_compra';

                    $stmt = DB::getStatement($sql);
                    
                
                    $stmt->bindParam(':id_producto',$id_producto,PDO::PARAM_INT); 
                    $stmt->execute();
                    $obj = $stmt->fetchAll();
                    if ($obj){
                            return ($obj);
                    }else {
                            return 0;
                    }
                    
            }
        
		public static function getItemsComprasNoAsignadasOrdenadasPorFechaByProductoAjax($producto){
                    $sql = 'SELECT * from item_compra ic
                            INNER JOIN compra c ON ic.id_compra = c.id_compra
                            WHERE ic.id_producto = :id_producto ORDER BY c.fecha,ic.id_item_compra';

                    $stmt = DB::getStatement($sql);
                    $id = $producto->getID();
                
                    $stmt->bindParam(':id_producto',$id,PDO::PARAM_INT); 
                    $stmt->execute();
                    $obj = $stmt->fetchAll();
                    if ($obj){
                            return ($obj);
                    }else {
                            return 0;
                    }
                    
            }   
        
        public static function asignarUnidACompra($id_item_compra,$cantidad){
    
            $unidades = self::getUnidadesNoAsignadas($id_item_compra);
            $unidadesAAsignar = intval($unidades) - intval($cantidad);
        
            $sql = 'UPDATE item_compra SET unidades_no_asignadas = '.$unidadesAAsignar.' WHERE id_item_compra = '.$id_item_compra;

            $stmt = DB::getStatement($sql); 
            // $stmt->bindParam(':id_item_compra',intval($id_item_compra)); 
            // $stmt->bindParam(':cantidad',intval($unidadesAAsignar)); 
            $rdo = $stmt->execute(); 
    

            if (!$rdo) {
                echo "<br> ERROR FUNCIÓN:";
                print_r($stmt->errorInfo());
                echo "<br> ";
                throw new Exception ("Error al guardar en la base de datos: ".$stmt->errorCode());
            }
            
            return $rdo;
        }
        
        public static function getTotalVentasSMByFechayProductoConCosto($fecha_desde, $fecha_hasta, $producto) {
            $sql = 'SELECT sum(iv.monto_total) as "monto_total"
                FROM item_venta iv
                INNER JOIN venta v ON v.id_venta = iv.id_venta
                INNER JOIN cliente c on v.id_cliente = c.id_cliente
                WHERE v.activo = 1
                        AND iv.costo <> 0
                ';

                $sql.=' AND c.subempresa = "SM" ';
            if ($producto != '') {
                $sql.=' AND iv.id_producto = :id_producto ';
            }
 
            if($fecha_desde != '') {
                $sql.=' AND v.fecha >= :fecha_desde ';
            }

            if($fecha_desde != '') {
                $sql.=' AND v.fecha <= :fecha_hasta ';
            }

            $stmt = DB::getStatement($sql);

             if ($producto != '') {
                $stmt->bindParam(':id_producto',$id_producto,PDO::PARAM_INT);
            }

            if($fecha_desde != '') {
                $stmt->bindParam(':fecha_desde',$fecha_desde,PDO::PARAM_STR);
            }

            if($fecha_desde != '') {
                $stmt->bindParam(':fecha_hasta',$fecha_hasta,PDO::PARAM_STR);
            }

            $stmt->execute();

            return $stmt->fetchObject();

        }

        public static function getTotalVentasByFechayProductoConCosto($fecha_desde, $fecha_hasta, $producto) {
            $sql = 'SELECT sum(iv.monto_total) as "monto_total"
                FROM item_venta iv
                INNER JOIN venta v ON v.id_venta = iv.id_venta
                INNER JOIN cliente c on v.id_cliente = c.id_cliente
                WHERE v.activo = 1
                        AND iv.costo <> 0';

            if ($producto != '') {
                $sql.=' AND iv.id_producto = :id_producto ';
            }

            if($fecha_desde != '') {
                $sql.=' AND v.fecha >= :fecha_desde ';
            }

            if($fecha_desde != '') {
                $sql.=' AND v.fecha <= :fecha_hasta ';
            }

            $stmt = DB::getStatement($sql);

             if ($producto != '') {
                $stmt->bindParam(':id_producto',$producto,PDO::PARAM_INT);
            }

            if($fecha_desde != '') {
                $stmt->bindParam(':fecha_desde',$fecha_desde,PDO::PARAM_STR);
            }

            if($fecha_desde != '') {
                $stmt->bindParam(':fecha_hasta',$fecha_hasta,PDO::PARAM_STR);
            }

            $stmt->execute();

            return $stmt->fetchObject();
        }

        public static function getTotalCantidadVentasSMByFechayProducto($fecha_desde, $fecha_hasta, $producto) {
            $sql = 'SELECT sum(iv.cantidad) as "cantidad"
                FROM item_venta iv
                INNER JOIN venta v ON v.id_venta = iv.id_venta
                INNER JOIN cliente c on v.id_cliente = c.id_cliente
                WHERE v.activo = 1 ';

                $sql.=' AND c.subempresa = "SM" ';
            if ($producto != '') {
                $sql.=' AND iv.id_producto = :id_producto ';
            }

            if($fecha_desde != '') {
                $sql.=' AND v.fecha >= :fecha_desde ';
            }

            if($fecha_desde != '') {
                $sql.=' AND v.fecha <= :fecha_hasta ';
            }

            $stmt = DB::getStatement($sql);

             if ($producto != '') {
                $stmt->bindParam(':id_producto',$producto,PDO::PARAM_INT);
            }

            if($fecha_desde != '') {
                $stmt->bindParam(':fecha_desde',$fecha_desde,PDO::PARAM_STR);
            }

            if($fecha_desde != '') {
                $stmt->bindParam(':fecha_hasta',$fecha_hasta,PDO::PARAM_STR);
            }

            $stmt->execute();

            return $stmt->fetchObject();

        }

        public static function getTotalCantidadVentasByFechayProducto($fecha_desde, $fecha_hasta, $producto) {
            $sql = 'SELECT sum(iv.cantidad) as "cantidad"
                FROM item_venta iv 
                INNER JOIN venta v ON v.id_venta = iv.id_venta
                INNER JOIN cliente c on v.id_cliente = c.id_cliente
                WHERE v.activo = 1 ';

            if ($producto != '') {
                $sql.=' AND iv.id_producto = :id_producto ';
            }

            if($fecha_desde != '') {
                $sql.=' AND v.fecha >= :fecha_desde ';
            }

            if($fecha_desde != '') {
                $sql.=' AND v.fecha <= :fecha_hasta ';
            }

            $stmt = DB::getStatement($sql);

             if ($producto != '') {
                $stmt->bindParam(':id_producto',$producto,PDO::PARAM_INT);
            }

            if($fecha_desde != '') {
                $stmt->bindParam(':fecha_desde',$fecha_desde,PDO::PARAM_STR);
            }

            if($fecha_desde != '') {
                $stmt->bindParam(':fecha_hasta',$fecha_hasta,PDO::PARAM_STR);
            }

            $stmt->execute();

            return $stmt->fetchObject();
        }

        public static function getTotalCostoVentasSMByFechayProducto($fecha_desde, $fecha_hasta, $producto) {
            $sql = 'SELECT sum(iv.costo) as "costo"
                FROM item_venta iv
                INNER JOIN venta v ON v.id_venta = iv.id_venta
                INNER JOIN cliente c on v.id_cliente = c.id_cliente
                WHERE v.activo = 1 ';

                $sql.=' AND c.subempresa = "SM" ';
            if ($producto != '') {
                $sql.=' AND iv.id_producto = :id_producto ';
            }

            if($fecha_desde != '') {
                $sql.=' AND v.fecha >= :fecha_desde ';
            }

            if($fecha_desde != '') {
                $sql.=' AND v.fecha <= :fecha_hasta ';
            }

            $stmt = DB::getStatement($sql);

             if ($producto != '') {
                $stmt->bindParam(':id_producto',$producto,PDO::PARAM_INT);
            }

            if($fecha_desde != '') {
                $stmt->bindParam(':fecha_desde',$fecha_desde,PDO::PARAM_STR);
            }

            if($fecha_desde != '') {
                $stmt->bindParam(':fecha_hasta',$fecha_hasta,PDO::PARAM_STR);
            }

            $stmt->execute();

            return $stmt->fetchObject();

        }

        public static function getTotalCostoVentasByFechayProducto($fecha_desde, $fecha_hasta, $producto) {
            $sql = 'SELECT sum(iv.costo) as "costo"
                FROM item_venta iv
                INNER JOIN venta v ON v.id_venta = iv.id_venta
                INNER JOIN cliente c on v.id_cliente = c.id_cliente
                WHERE v.activo = 1 ';

            if ($producto != '') {
                $sql.=' AND iv.id_producto = :id_producto ';
            }

            if($fecha_desde != '') {
                $sql.=' AND v.fecha >= :fecha_desde ';
            }

            if($fecha_desde != '') {
                $sql.=' AND v.fecha <= :fecha_hasta ';
            }

            $stmt = DB::getStatement($sql);

            if ($producto != '') {
                $stmt->bindParam(':id_producto',$producto,PDO::PARAM_INT);
            }

            if($fecha_desde != '') {
                $stmt->bindParam(':fecha_desde',$fecha_desde,PDO::PARAM_STR);
            }

            if($fecha_desde != '') {
                $stmt->bindParam(':fecha_hasta',$fecha_hasta,PDO::PARAM_STR);
            }

            $stmt->execute();

            return $stmt->fetchObject();
        }
		
		public static function getTotalComisionesByFecha($fecha_desde, $fecha_hasta) {
            $sql = 'SELECT sum(importe) as "importe"
                FROM comision
                WHERE activo = 1 ';

            
            if($fecha_desde != '') {
                $sql.=' AND fecha >= :fecha_desde ';
            }

            if($fecha_hasta != '') {
                $sql.=' AND fecha <= :fecha_hasta ';
            }

            $stmt = DB::getStatement($sql);

            if($fecha_desde != '') {
                $stmt->bindParam(':fecha_desde',$fecha_desde,PDO::PARAM_STR);
            }

            if($fecha_hasta != '') {
                $stmt->bindParam(':fecha_hasta',$fecha_hasta,PDO::PARAM_STR);
            }

            $stmt->execute();

            return $stmt->fetchObject();
        }

        public static function getIdsItemsVentasByVenta($id_venta) {
            
            $sql = "SELECT id_item_venta FROM item_venta WHERE";
            $sql = $sql." id_venta = ".$id_venta;
       
            $sql = $sql." ORDER BY id_item_venta";
            $stmt = DB::getStatement($sql);
            
            
            
            $stmt->execute();

            $objeto = $stmt->fetchAll();
            return $objeto;
        }
        
		public static function getIdsItemsVentasByVentaAndParam($id_venta,$id_producto,$subempresa) {
            
            $sql = "SELECT id_item_venta FROM item_venta WHERE";
            $sql = $sql." id_venta = ".$id_venta;
            if ($id_producto != '') {
                $sql.=' AND id_producto = :id_producto ';
            }
            if ($subempresa != '') {
                $sql.=' AND subempresa = :subempresa ';
            }
            $sql = $sql." ORDER BY id_item_venta";
            
            $stmt = DB::getStatement($sql);
            if ($id_producto != '') {
                $stmt->bindParam(':id_producto',$id_producto,PDO::PARAM_INT);
            }
            if ($subempresa != '') {
                $stmt->bindParam(':subempresa',$subempresa,PDO::PARAM_INT);
            }
            $stmt->execute();

            $objeto = $stmt->fetchAll();
            return $objeto;
        }
        
        public static function getVentaById($id_venta) {
            $sql = 'SELECT * FROM venta WHERE id_venta = :id_venta';

            $stmt = DB::getStatement($sql); 
            $stmt->bindParam(':id_venta',$id_venta,PDO::PARAM_INT); 
            $stmt->execute(); 

            return $stmt->fetchObject();
        }
        
		public static function getItemVentaById($id) {
            $sql = 'SELECT * FROM item_venta WHERE id_item_venta = :id';

            $stmt = DB::getStatement($sql); 
            $stmt->bindParam(':id',$id,PDO::PARAM_INT); 
            $stmt->execute(); 

            return $stmt->fetchObject();
        }
        
		public static function getItemVentaDBById($id) {
            $sql = 'SELECT iv.id_producto,iv.cantidad,iv.monto_total,v.fecha,iv.costo,iv.subempresa  FROM item_venta iv INNER JOIN venta v ON iv.id_venta = v.id_venta  WHERE id_item_venta = :id';

            $stmt = DB::getStatement($sql); 
            $stmt->bindParam(':id',$id,PDO::PARAM_INT); 
            $stmt->execute(); 

            return $stmt->fetchObject();
        }
        
		public static function getProvinciaById($id_provincia) {
            $sql = 'SELECT * FROM provincia WHERE id_provincia = :id_provincia';

            $stmt = DB::getStatement($sql); 
            $stmt->bindParam(':id_provincia',$id_provincia,PDO::PARAM_INT); 
            $stmt->execute(); 

            return $stmt->fetchObject();
        }
        
        public static function getLocalidadById($id_localidad) {
            $sql = 'SELECT * FROM localidad WHERE id_localidad = :id_localidad';

            $stmt = DB::getStatement($sql); 
            $stmt->bindParam(':id_localidad',$id_localidad,PDO::PARAM_INT); 
            $stmt->execute(); 

            return $stmt->fetchObject();
        }

        public static function getCompraById($id_compra) {
            $sql = 'SELECT * FROM compra WHERE id_compra = :id_compra';

            $stmt = DB::getStatement($sql); 
            $stmt->bindParam(':id_compra',$id_compra,PDO::PARAM_INT); 
            $stmt->execute(); 

            return $stmt->fetchObject();
        }
        
        public static function getItemCompraById($id_item_compra) {
            $sql = 'SELECT * FROM item_compra WHERE id_item_compra = :id_item_compra';

            $stmt = DB::getStatement($sql); 
            $stmt->bindParam(':id_item_compra',$id_item_compra,PDO::PARAM_INT); 
            $stmt->execute(); 

            return $stmt->fetchObject();
        }
        
        public static function getProductoById($id_producto) {
            $sql = 'SELECT * FROM producto WHERE id_producto = :id_producto';

            $stmt = DB::getStatement($sql); 
            $stmt->bindParam(':id_producto',$id_producto,PDO::PARAM_INT); 
            $stmt->execute(); 

            return $stmt->fetchObject();
        }
        
        public static function getProveedorById($id_proveedor) {
            $sql = 'SELECT * FROM proveedor WHERE id_proveedor = :id_proveedor';
            $stmt = DB::getStatement($sql); 
            $stmt->bindParam(':id_proveedor',$id_proveedor,PDO::PARAM_INT); 
            $stmt->execute(); 
            return $stmt->fetchObject();
        }
		
		public static function getCantidadDePaginas($tabla) {
            $sql = 'SELECT COUNT(1) as cantidad FROM '.$tabla.' WHERE activo = 1';
            $stmt = DB::getStatement($sql); 
            $stmt->execute(); 
            $objeto = $stmt->fetchObject();
            return (intval(($objeto->cantidad)/self::$pageSize) +1);
                    
        }
              
        public static function getIdsComprasByParam($fecha_desde, $fecha_hasta,$id_proveedor, $indexMenu) {
            
            $sql = "SELECT id_compra
            FROM compra
            WHERE activo = 1 ";
            if ($fecha_desde!='') {
                $sql = $sql." AND fecha >= :fecha_desde ";
            }
            if ($fecha_hasta!='') {
                $sql = $sql." AND fecha <= :fecha_hasta ";
            }
            if ($id_proveedor!='') {
                $sql = $sql." AND id_proveedor = :id_proveedor ";
            }

            $sql = $sql." ORDER BY id_compra desc";
            $sql = $sql." LIMIT ".self::$pageSize.' OFFSET '.(($indexMenu-1)*self::$pageSize);
            $stmt = DB::getStatement($sql);
            if ($fecha_desde!='') {
                $stmt->bindParam(':fecha_desde',$fecha_desde,PDO::PARAM_STR);
            }
            if ($id_proveedor !='') {
                $stmt->bindParam(':id_proveedor',$id_proveedor,PDO::PARAM_STR);
            }
            if ($fecha_hasta!='') {
                $stmt->bindParam(':fecha_hasta',$fecha_hasta,PDO::PARAM_STR);
            }
            
            $stmt->execute();

            $objeto = $stmt->fetchAll();
            return $objeto;
        }
        
        public static function getIdsItemsComprasByParam($fecha_desde, $fecha_hasta, $id_proveedor, $subempresa, $id_compra, $id_producto, $indexMenu) {
            
            $sql = "SELECT id_item_compra
            FROM item_compra ic INNER JOIN compra c ON ic.id_compra = c.id_compra
            WHERE c.activo = 1 ";
            if ($fecha_desde!='') {
                $sql = $sql." AND c.fecha >= :fecha_desde ";

            }
            if ($fecha_hasta!='') {
                $sql = $sql." AND c.fecha <= :fecha_hasta ";
            }
            if ($id_proveedor!='') {
                $sql = $sql." AND c.id_proveedor = :id_proveedor ";
            }
            if ($id_compra!='') {
                $sql = $sql." AND c.id_compra = :id_compra ";
            }
            if ($id_producto!='') {
                $sql = $sql." AND ic.id_producto = :id_producto ";
            }
            if ($subempresa!='') {
                $sql = $sql." AND ic.subempresa= :subempresa ";
            }

            $sql = $sql." ORDER BY c.id_compra desc";
            $sql = $sql." LIMIT ".self::$pageSize.' OFFSET '.(($indexMenu-1)*self::$pageSize);
            $stmt = DB::getStatement($sql);
            if ($fecha_desde!='') {
                $stmt->bindParam(':fecha_desde',$fecha_desde,PDO::PARAM_STR);
            }
            if ($id_proveedor !='') {
                $stmt->bindParam(':id_proveedor',$id_proveedor,PDO::PARAM_STR);
            }
            if ($fecha_hasta!='') {
                $stmt->bindParam(':fecha_hasta',$fecha_hasta,PDO::PARAM_STR);
            }
            if ($id_compra!='') {
                $stmt->bindParam(':id_compra',$id_compra,PDO::PARAM_STR);
            }
            if ($id_producto!='') {
                $stmt->bindParam(':id_producto',$id_producto,PDO::PARAM_STR);
            }
            if ($subempresa!='') {
                $stmt->bindParam(':subempresa',$subempresa,PDO::PARAM_STR);
            }

            $stmt->execute();

            $objeto = $stmt->fetchAll();
            return $objeto;
        }
        
		public static function getIdsItemsComprasByCompraAndParam($id_compra,$subempresa) {
            
            $sql = "SELECT id_item_compra FROM item_compra WHERE";
            $sql = $sql." id_compra = ".$id_compra;
            if ($subempresa!='') {
                $sql = $sql." AND subempresa= :subempresa ";
            }
            $sql = $sql." ORDER BY id_item_compra";

            //echo $sql;
            //die();
            
            $stmt = DB::getStatement($sql);
            //$stmt->bindParam(':id_compra',$id_compra,PDO::PARAM_STR);
            if ($subempresa!='') {
                $stmt->bindParam(':subempresa',$subempresa,PDO::PARAM_STR);
            }
            
            $stmt->execute();

            $objeto = $stmt->fetchAll();
            return $objeto;
        }
        
		public static function getIdsItemsComprasByCompra($id_compra, $id_producto,$subempresa) {
            
            $sql = "SELECT id_item_compra FROM item_compra WHERE";
            $sql = $sql." id_compra = ".$id_compra;

            if ($id_producto!='') {
                $sql = $sql." AND id_producto = :id_producto ";
            }
            
            if ($subempresa!='') {
                $sql = $sql." AND subempresa= :subempresa ";
            }
            $sql = $sql." ORDER BY id_item_compra";

            //echo $sql;
            //die();
            
            $stmt = DB::getStatement($sql);
            //$stmt->bindParam(':id_compra',$id_compra,PDO::PARAM_STR);
            if ($id_producto!='') {
                $stmt->bindParam(':id_producto',$id_producto,PDO::PARAM_STR);
            }
            if ($subempresa!='') {
                $stmt->bindParam(':subempresa',$subempresa,PDO::PARAM_STR);
            }
            $stmt->execute();

            $objeto = $stmt->fetchAll();
            return $objeto;
        }
        
        public static function getCantidadDePaginasICByParam($fecha_desde, $fecha_hasta,$subempresa, $id_proveedor, $id_compra, $id_producto) {
            
            $sql = "SELECT count(1) as cantidad
            FROM item_compra ic INNER JOIN compra c ON ic.id_compra = c.id_compra INNER JOIN proveedor p ON p.id_proveedor = c.id_proveedor
            WHERE c.activo = 1 ";
            
            if ($fecha_desde!='') {
                $sql = $sql." AND c.fecha >= :fecha_desde ";
                
            }
            if ($fecha_hasta!='') {
                $sql = $sql." AND c.fecha <= :fecha_hasta ";
            }
            if ($id_proveedor!='') {
                $sql = $sql." AND c.id_proveedor = :id_proveedor ";
            }
            if ($id_compra!='') {
                $sql = $sql." AND c.id_compra = :id_compra ";
            }
            if ($id_producto!='') {
                $sql = $sql." AND ic.id_producto = :id_producto ";
            }
            if ($subempresa!='') {
                $sql = $sql." AND ic.subempresa = :subempresa ";
            }
            $stmt = DB::getStatement($sql);

            if ($fecha_desde!='') {
                $stmt->bindParam(':fecha_desde',$fecha_desde,PDO::PARAM_STR);
            }
            if ($fecha_hasta!='') {
                $stmt->bindParam(':fecha_hasta',$fecha_hasta,PDO::PARAM_STR);
            }
            if ($id_proveedor!='') {
                $stmt->bindParam(':id_proveedor',$id_proveedor,PDO::PARAM_STR);
            }
            if ($id_compra!='') {
                $stmt->bindParam(':id_compra',$id_compra,PDO::PARAM_STR);
            }
            if ($id_producto!='') {
                $stmt->bindParam(':id_producto',$id_producto,PDO::PARAM_STR);
            }
            if ($subempresa!='') {
                $stmt->bindParam(':subempresa',$subempresa,PDO::PARAM_STR);
            }
            $stmt->execute();

            $objeto = $stmt->fetchObject();
            
            if ($objeto){
                return (intval(($objeto->cantidad)/self::$pageSize) +1);
            }else {
                return 1;
            }
        }
        
        public static function getItemsDeVentaById($id_venta) {
            
            $sql = "SELECT *
            FROM item_venta inner join venta v 
            WHERE v.id_venta= ".$id_venta;
            $stmt = DB::getStatement($sql);
            $stmt->execute();

            $objeto = $stmt->fetchAll();
            return $objeto;
        }
        
		public static function getIdsTabla($tabla, $fecha_desde, $fecha_hasta,$id_cliente,$id_proveedor,$id_producto,$subempresa) {
			if ($tabla == 'comision') {
				$sql = "SELECT id";
			} else {
				$sql = "SELECT id_".$tabla;
			}
            $sql .= " FROM ".$tabla." "."
            WHERE activo = 1 ";
            if ($fecha_desde!='') {
                $sql = $sql." AND fecha >= :fecha_desde ";
            }
            if ($fecha_hasta!='') {
                $sql = $sql." AND fecha <= :fecha_hasta ";
            }
             if ($id_cliente!='') {
                $sql = $sql." AND id_cliente = :id_cliente ";
            }
             if ($id_proveedor!='') {
                $sql = $sql." AND id_proveedor = :id_proveedor ";
            }
            if ($subempresa!='') {
                $sql = $sql." AND subempresa = :subempresa ";
            }
			if ($tabla == 'comision') {
				$sql .= "order by id desc";
			} else {
				$sql .= "order by id_".$tabla." desc";
			}
            
            $stmt = DB::getStatement($sql);
            if ($fecha_desde!='') {
                $stmt->bindParam(':fecha_desde',$fecha_desde,PDO::PARAM_STR);
            }
            if ($fecha_hasta!='') {
                $stmt->bindParam(':fecha_hasta',$fecha_hasta,PDO::PARAM_STR);
            }
            if ($id_cliente!='') {
                $stmt->bindParam(':id_cliente',$id_cliente,PDO::PARAM_STR);
            }
            if ($id_proveedor!='') {
                $stmt->bindParam(':id_proveedor',$id_proveedor,PDO::PARAM_STR);
            }
            if ($subempresa!='') {
                $stmt->bindParam(':subempresa',$subempresa,PDO::PARAM_STR);
            }
            $stmt->execute();

            $objeto = $stmt->fetchAll();
			//print_r($tabla);
            return $objeto;
        }
        
		public static function getCantidadDePaginasIVByParam($fecha_desde, $fecha_hasta, $id_cliente, $id_producto, $id_venta, $subempresa) {
            
            $sql = "SELECT count(1) as cantidad
            FROM item_venta iv INNER JOIN venta v on iv.id_venta = v.id_venta INNER JOIN cliente c on c.id_cliente = v.id_cliente
            WHERE v.activo = 1 ";
            
            if ($fecha_desde!='') {
                $sql = $sql." AND v.fecha >= :fecha_desde ";
            }
            if ($fecha_hasta!='') {
                $sql = $sql." AND v.fecha <= :fecha_hasta ";
            }
            if ($id_cliente!='') {
                $sql = $sql." AND v.id_cliente = :id_cliente ";
            }
            if ($id_producto!='') {
                $sql = $sql." AND iv.id_producto = :id_producto ";
            }
            if ($id_venta!='') {
                $sql = $sql." AND v.id_venta = :id_venta ";
            }
            if ($subempresa !='') {
                $sql = $sql." AND iv.subempresa = :subempresa";
               }
            $stmt = DB::getStatement($sql);

            if ($fecha_desde!='') {
                $stmt->bindParam(':fecha_desde',$fecha_desde,PDO::PARAM_STR);
            }
            if ($subempresa!='') {
                $stmt->bindParam(':subempresa',$subempresa,PDO::PARAM_STR);
            }
            if ($fecha_hasta!='') {
                $stmt->bindParam(':fecha_hasta',$fecha_hasta,PDO::PARAM_STR);
            }
            if ($id_cliente!='') {
                $stmt->bindParam(':id_cliente',$id_cliente,PDO::PARAM_STR);
            }
            if ($id_producto!='') {
                $stmt->bindParam(':id_producto',$id_producto,PDO::PARAM_STR);
            }
            if ($id_venta!='') {
                $stmt->bindParam(':id_venta',$id_venta,PDO::PARAM_STR);
            }
            $stmt->execute();

            $objeto = $stmt->fetchObject();
            
            if ($objeto){
                return (intval(($objeto->cantidad)/self::$pageSize) +1);
            }else {
                return 1;
            }
        }
        
		public static function getCantidadDePaginasIVByParamGanancia($fecha_desde, $fecha_hasta, $id_producto, $subempresa) {
            
            $sql = "SELECT count(1) as cantidad
            FROM item_venta iv INNER JOIN venta v on iv.id_venta = v.id_venta INNER JOIN cliente c on c.id_cliente = v.id_cliente
            WHERE v.activo = 1 ";
            
            if ($fecha_desde!='') {
                $sql = $sql." AND v.fecha >= :fecha_desde ";
            }
            if ($fecha_hasta!='') {
                $sql = $sql." AND v.fecha <= :fecha_hasta ";
            }
            if ($id_producto!='') {
                $sql = $sql." AND iv.id_producto = :id_producto ";
            }
            if ($subempresa !='') {
                $sql = $sql." AND c.subempresa = :subempresa";
               }
            $stmt = DB::getStatement($sql);

            if ($fecha_desde!='') {
                $stmt->bindParam(':fecha_desde',$fecha_desde,PDO::PARAM_STR);
            }
            if ($subempresa!='') {
                $stmt->bindParam(':subempresa',$subempresa,PDO::PARAM_STR);
            }
            if ($fecha_hasta!='') {
                $stmt->bindParam(':fecha_hasta',$fecha_hasta,PDO::PARAM_STR);
            }
            if ($id_producto!='') {
                $stmt->bindParam(':id_producto',$id_producto,PDO::PARAM_STR);
            }
            $stmt->execute();

            $objeto = $stmt->fetchObject();
            
            if ($objeto){
                return (intval(($objeto->cantidad)/self::$pageSize) +1);
            }else {
                return 1;
            }
        }
        
		public static function getCantidadDePaginasByParam($tabla, $fecha_desde, $fecha_hasta, $id_cliente, $id_proveedor, $subempresa) {
            
            $sql = "SELECT count(1) as cantidad
            FROM ".$tabla;
            if ($fecha_desde!='' || $fecha_hasta!='' ) {
                $sql = $sql." WHERE activo = 1 ";
            }
            if ($id_proveedor!='') {
                $sql = $sql." AND id_proveedor) >= :id_proveedor) ";
            }
            if ($fecha_desde!='') {
                $sql = $sql." AND fecha >= :fecha_desde ";
            }
            if ($fecha_hasta!='') {
                $sql = $sql." AND fecha <= :fecha_hasta ";
            }
            if ($id_cliente!='') {
                $sql = $sql." AND id_cliente = :id_cliente ";
            }
            if ($subempresa !='') {
                $sql = $sql." AND subempresa = :subempresa";
               }
            $stmt = DB::getStatement($sql);

            if ($fecha_desde!='') {
                $stmt->bindParam(':fecha_desde',$fecha_desde,PDO::PARAM_STR);
            }
            if ($subempresa!='') {
                $stmt->bindParam(':subempresa',$subempresa,PDO::PARAM_STR);
            }
            if ($fecha_hasta!='') {
                $stmt->bindParam(':fecha_hasta',$fecha_hasta,PDO::PARAM_STR);
            }
            if ($id_cliente!='') {
                $stmt->bindParam(':id_cliente',$id_cliente,PDO::PARAM_STR);
            }
            if ($id_proveedor!='') {
                $stmt->bindParam(':id_proveedor',$id_proveedor,PDO::PARAM_STR);
            }
            $stmt->execute();

            $objeto = $stmt->fetchObject();
            
            if ($objeto){
                return (intval(($objeto->cantidad)/self::$pageSize) +1);
            }else {
                return 1;
            }
        }

        public static function getIdsCompra($indexMenu){
        /* Agregado por GGRIN */
        $sql = 'SELECT id_compra FROM compra WHERE activo = 1 ORDER BY id_compra desc LIMIT '.self::$pageSize.' OFFSET '.(($indexMenu-1)*self::$pageSize);

        $stmt = DB::getStatement($sql);
        $stmt->execute();
    $obj = $stmt->fetchAll();
    return $obj;
        }
        
        public static function getIdsProveedor($indexMenu){
        
                $sql = 'SELECT pr.id_proveedor
                    FROM proveedor pr
                    INNER JOIN persona pe ON pe.id_persona = pr.id_persona
                    WHERE pr.activo = 1
                    ORDER BY  pe.nombre ASC ';
            if ($indexMenu != NULL) {
                /* Agregado por GGRIN */
                $sql = $sql.' LIMIT '.self::$pageSize.' OFFSET '.(($indexMenu-1)*self::$pageSize);
            }

        $stmt = DB::getStatement($sql);
        $stmt->execute();
    $obj = $stmt->fetchAll();
    return $obj;
        }
        
        public static function getMaxIdFromTabla($tabla) {
            
            $sql = 'SELECT coalesce(MAX(id_'.$tabla.'), 0) FROM '.$tabla;
            $stmt = DB::getStatement($sql);
            
            if ($stmt->execute()) {
                
                $rdo = $stmt->fetch(PDO::FETCH_NUM);
                return $rdo[0];
                
            } else {
                $sql2 = 'SELECT count(1) FROM '.$tabla;
                $stmt2 = DB::getStatement($sql2);
                $stmt2->execute();
                $rdo2 = $stmt2->fetch(PDO::FETCH_NUM);

                if ($rdo2[0] == 0 or $rdo2==NULL) {
                    return 0;
                } else {
                    return false;
                }
            }
        }
        
        public static function updateItemVenta(ItemVenta $item) {
                $sql = 'UPDATE item_venta SET `monto_total` = :monto_total ,`id_producto` = :id_producto ,`cantidad` = :cantidad, costo = :costo, subempresa = :subempresa
                                                WHERE id_item_venta = :id';
                    
                $stmt = DB::getStatement($sql);
                $id =  $item->getId();

                $monto_total = $item->getMontoTotal();
                $id_prod = $item->getProducto()->getId();
                $cantidad = $item->getCantidad();
                $costo = $item->getCosto();
                $subempresa = $item->getSubempresa();
                $stmt->bindParam(':id',$id);
                $stmt->bindParam(':monto_total',$monto_total );
                $stmt->bindParam(':id_producto', $id_prod);
                $stmt->bindParam(':cantidad',$cantidad );
                $stmt->bindParam(':costo',$costo );
                $stmt->bindParam(':subempresa',$subempresa );

                $rdo = $stmt->execute();

                if (!$rdo) {
                        print_r($stmt->errorInfo());
                        echo "<br>";
                        throw new Exception ("Error al guardar en la base de datos: ".$stmt->errorCode());
                }
            }
        
		public static function updateItemVentaDB(ItemVentaDB $item) {
                $sql = 'UPDATE item_venta SET costo = :costo
                                                WHERE id_item_venta = :id';
                    
                $stmt = DB::getStatement($sql);
                $id =  $item->getId();
                $costo = $item->getCosto();
                $stmt->bindParam(':id',$id);
                $stmt->bindParam(':costo',$costo );

                $rdo = $stmt->execute();

                if (!$rdo) {
                        print_r($stmt->errorInfo());
                        echo "<br>";
                        throw new Exception ("Error al guardar en la base de datos: ".$stmt->errorCode());
                }
            }
        
		public static function updateItemCompra(ItemCompra $item) {
            
            $sql = 'UPDATE item_compra 
                    SET `monto_total` = :monto_total,
                    `id_producto` = :id_producto,
                    `cantidad` = :cantidad,
                    `unidades_no_asignadas` = :unidades_no_asignadas,
                    subempresa = :subempresa,
                    precio_unitario = :precio_unitario
                    WHERE id_item_compra = :id';

            $stmt = DB::getStatement($sql);
            $id =  $item->getId();

            $monto_total = $item->getMontoTotal();
            $id_prod = $item->getProducto()->getId();
            $cantidad = $item->getCantidad();
            $subempresa = $item->getSubempresa();
            
            $unidades_no_asignadas = $item->getUnidadesNoAsignadas();
            echo $unidades_no_asignadas;
            $precio_unitario = $item->getPrecioUnitario();
            
            $stmt->bindParam(':id',$id);
            $stmt->bindParam(':monto_total',$monto_total );
            $stmt->bindParam(':id_producto', $id_prod);
            $stmt->bindParam(':cantidad',$cantidad );
            $stmt->bindParam(':subempresa',$subempresa );
            $stmt->bindParam(':unidades_no_asignadas',$unidades_no_asignadas );
            $stmt->bindParam(':precio_unitario',$precio_unitario );
            $rdo = $stmt->execute();

            if (!$rdo) {
                    print_r($stmt->errorInfo());
                    echo "<br>";
                    throw new Exception ("Error al guardar en la base de datos: ".$stmt->errorCode());
            }
        }
            
        public static function addItemCompra (ItemCompra $item){
                $maxId = DB::getMaxIdFromTabla('item_compra');
                if ($maxId == NULL) {
                    throw new Exception ("Error al obtener max id");
                }
                
                $sql = 'INSERT INTO item_compra (`id_item_compra`,`id_compra`,`monto_total`,`id_producto`,`cantidad`,unidades_no_asignadas, precio_unitario, subempresa)
                        VALUES(:id_item_compra, :id_compra, :monto_total, :id_producto, :cantidad,:unidades_no_asignadas, :precio_unitario, :subempresa)';
                
                $stmt = DB::getStatement($sql);
                $id =  intval($maxId+1);
                $id_compra = $item->getCompra()->getId();
                $monto_total = $item->getMontoTotal();
                $id_prod = $item->getProducto()->getId();
                $cantidad = $item->getCantidad();
                $unidades_no_asignadas = $item->getUnidadesNoAsignadas();
                $precio_unitario = $item->getPrecioUnitario();
                $subempresa = $item->getSubempresa();
                $stmt->bindParam(':id_item_compra',$id);
                $stmt->bindParam(':id_compra',$id_compra );
                $stmt->bindParam(':monto_total',$monto_total );
                $stmt->bindParam(':id_producto', $id_prod);
                $stmt->bindParam(':cantidad',$cantidad );
                $stmt->bindParam(':unidades_no_asignadas',$unidades_no_asignadas );
                $stmt->bindParam(':precio_unitario',$precio_unitario);

                $stmt->bindParam(':subempresa',$subempresa);
                $rdo = $stmt->execute();
            
                if (!$rdo) {
                        print_r($stmt->errorInfo());
                        echo "<br>";
                        throw new Exception ("Error al guardar en la base de datos: ".$stmt->errorCode());
                }
            }
        
		public static function addComision (Comision $comision){
                              
                $sql = 'INSERT INTO comision (`id_cliente`,`fecha`,`producto`,`nro_venta`,importe, observaciones,activo)
                        VALUES(:id_cliente, :fecha, :producto, :nro_venta, :importe,:observaciones, :activo)';
                
                $stmt = DB::getStatement($sql);
                $id_cliente = $comision->getCliente()->getId();
                $fecha = $comision->getFecha();
                $producto = $comision->getProducto();
                $nro_venta = $comision->getNroVenta();
                $importe = $comision->getImporte();
                $observaciones = $comision->getObservaciones();
				$activo = $comision->getActivo();
                $stmt->bindParam(':id_cliente',$id_cliente);
                $stmt->bindParam(':fecha',$fecha );
                $stmt->bindParam(':producto',$producto );
                $stmt->bindParam(':nro_venta', $nro_venta);
                $stmt->bindParam(':importe',$importe );
                $stmt->bindParam(':observaciones',$observaciones );
                $stmt->bindParam(':activo',$activo);
				


                $rdo = $stmt->execute();
            
                if (!$rdo) {
                        print_r($stmt->errorInfo());
                        echo "<br>";
                        throw new Exception ("Error al guardar en la base de datos: ".$stmt->errorCode());
                }
            }
		
		public static function updateComision (Comision $comision){
                              
                $sql = 'UPDATE comision SET id_cliente = :id_cliente ,fecha = :fecha, producto = :producto,nro_venta = :nro_venta, importe = :importe, observaciones = :observaciones,activo = :activo WHERE id = :id';
                        
                
                $stmt = DB::getStatement($sql);
                $id_cliente = $comision->getCliente()->getId();
                $fecha = $comision->getFecha();
                $producto = $comision->getProducto();
                $nro_venta = $comision->getNroVenta();
                $importe = $comision->getImporte();
                $observaciones = $comision->getObservaciones();
				$activo = $comision->getActivo();
				$id = $comision->getId();
				$stmt->bindParam(':id',$id);
                $stmt->bindParam(':id_cliente',$id_cliente);
                $stmt->bindParam(':fecha',$fecha );
                $stmt->bindParam(':producto',$producto );
                $stmt->bindParam(':nro_venta', $nro_venta);
                $stmt->bindParam(':importe',$importe );
                $stmt->bindParam(':observaciones',$observaciones );
                $stmt->bindParam(':activo',$activo);
				
                $rdo = $stmt->execute();
            
                if (!$rdo) {
                        print_r($stmt->errorInfo());
                        echo "<br>";
                        throw new Exception ("Error al guardar en la base de datos: ".$stmt->errorCode());
                }
            }    						
            
        public static function addItemVenta (ItemVenta $item){
                $maxId = DB::getMaxIdFromTabla('item_venta');
                if ($maxId==NULL) {
                    throw new Exception ("Error al obtener max id");
                }
                
                $sql = 'INSERT INTO item_venta (`id_item_venta`,`id_venta`,`monto_total`,`id_producto`,`cantidad`,`costo`,`subempresa`)
                        VALUES(:id_item_venta, :id_venta, :monto_total, :id_producto, :cantidad, :costo, :subempresa)';
                
                $stmt = DB::getStatement($sql);
                $id =  intval($maxId+1);
                $id_venta = $item->getVenta()->getId();
                $monto_total = $item->getMontoTotal();
                $id_prod = $item->getProducto()->getId();
                $cantidad = $item->getCantidad();
                $costo = $item->getCosto();
                $subempresa = $item->getSubempresa();
                $stmt->bindParam(':id_item_venta',$id);
                $stmt->bindParam(':id_venta',$id_venta );
                $stmt->bindParam(':monto_total',$monto_total );
                $stmt->bindParam(':id_producto', $id_prod);
                $stmt->bindParam(':cantidad',$cantidad );
                $stmt->bindParam(':costo',$costo );
                $stmt->bindParam(':subempresa',$subempresa );
                $rdo = $stmt->execute();
            
                if (!$rdo) {
                    print_r($stmt->errorInfo());
                    echo "<br>";
                    throw new Exception ("Error al guardar en la base de datos: ".$stmt->errorCode());
                }
        }
        
		public static function savePersona(Persona $persona) {
            
            $domicilio = $persona->getDomicilio();
            $domicilio->save();
            
            if ($persona->getId() == NULL) {
                
                $maxId = DB::getMaxIdFromTabla('persona');
                if ($maxId==NULL) {
                    throw new Exception ("Error al obtener max id");
                }
                
                $sql = 'INSERT INTO persona (`id_persona`,`nombre`,`email`,`telefono`,`id_domicilio`,`Observaciones`,`activo`)
                        VALUES(:id_persona, :nombre, :email, :telefono, :id_domicilio, :observaciones, 1)';
                
                $stmt = DB::getStatement($sql);
                $stmt->bindParam(':id_persona', intval($maxId+1));
                $persona->setId($maxId+1);
                
            } else {
                $sql = 'UPDATE persona
                        SET nombre = :nombre,
                            email = :email,
                            telefono = :telefono,
                            observaciones = :observaciones,
                            id_domicilio = :id_domicilio
                        WHERE id_persona = :id_persona;';
                
                $stmt = DB::getStatement($sql);
                $stmt->bindParam(':id_persona', $persona->getId());
            }
            
            $stmt->bindParam(':nombre', $persona->getNombre());
            $stmt->bindParam(':email', $persona->getEmail());
            $stmt->bindParam(':telefono', $persona->getTelefono());
            $stmt->bindParam(':observaciones', $persona->getObservaciones());
            $stmt->bindParam(':id_domicilio', $domicilio->getId());

            $rdo = $stmt->execute();
            
            if (!$rdo) {
                print_r($stmt->errorInfo());
                echo "<br>";
                throw new Exception ("Error al guardar en la base de datos: ".$stmt->errorCode());
            }

        }
		
		public static function saveLaboratorio(Laboratorio $lab) {

            $descripcion = $lab->getDescripcion();
            $activo = $lab->getActivo();
            $maxId = DB::getMaxIdFromTabla('laboratorio');
                if ($maxId==NULL) {
                    throw new Exception ("Error al obtener max id");
                }
                
                $sql = "INSERT INTO laboratorio
                        (id_laboratorio,
                        descripcion,
                        activo)
                        VALUES(:id_laboratorio, :descripcion, :activo)"; 
            $stmt = DB::getStatement($sql);
            $id_laboratorio = intval($maxId+1);
            $stmt->bindParam(':id_laboratorio', $id_laboratorio);
            $stmt->bindParam(':descripcion', $descripcion);
            $stmt->bindParam(':activo', $activo);
            
            $rdo = $stmt->execute();
            
            if (!$rdo) {
                print_r($stmt->errorInfo());
                echo "<br>";
                throw new Exception ("Error al guardar en la base de datos: ".$stmt->errorCode());
            }
            
        }
        
		public static function saveProducto(Producto $producto) {

            $descripcion = $producto->getDescripcion();
            $observaciones = $producto->getObservaciones();
            $sabor = $producto->getSabor();
            $tamanio = $producto->getTamanio();
            $activo = $producto->getActivo();
            $laboratorio = $producto->getLaboratorio();
            if ($laboratorio){
                $id_laboratorio = $producto->getLaboratorio()->getID();
            }
            
            if ($producto->getId() == NULL) {

                $maxId = DB::getMaxIdFromTabla('producto');
                if ($maxId==NULL) {
                    throw new Exception ("Error al obtener max id");
                }
                
                $sql = "INSERT INTO producto
                        (id_producto,
                        descripcion,
                        observaciones,
                        sabor,
                        tamanio,
                        id_laboratorio,
                        activo)
                        VALUES(:id_producto, :descripcion, :observaciones,:sabor,:tamanio,:id_laboratorio, :activo)"; 
                $stmt = DB::getStatement($sql);
                $id_producto = intval($maxId+1);
                
            } else {
                $sql = "UPDATE producto
                        SET descripcion = :descripcion, 
                        observaciones = :observaciones , 
                        sabor = :sabor,
                        tamanio = :tamanio,
                        id_laboratorio = :id_laboratorio,
                        activo = :activo 
                        WHERE id_producto = :id_producto"; 
                $stmt = DB::getStatement($sql);
                $id_producto = $producto->getId();
                
                                                    
            }
                $stmt->bindParam(':id_producto', $id_producto);
                $stmt->bindParam(':descripcion', $descripcion);
                $stmt->bindParam(':observaciones', $observaciones);
                $stmt->bindParam(':sabor', $sabor);
                $stmt->bindParam(':tamanio', $tamanio);
                $stmt->bindParam(':id_laboratorio', $id_laboratorio);
                $stmt->bindParam(':activo', $activo);

            $rdo = $stmt->execute();
            
            if (!$rdo) {
                print_r($stmt->errorInfo());
                echo "<br>";
                throw new Exception ("Error al guardar en la base de datos: ".$stmt->errorCode());
            }
            
        }
        
        public static function saveDomicilio(Domicilio $domicilio) {
        
            if ($domicilio->getId() == NULL) {
                $maxId = DB::getMaxIdFromTabla('domicilio');
                if ($maxId==NULL) {
                    throw new Exception ("Error al obtener max id");
                } else {
                    $sql = "INSERT INTO domicilio
                                (id_domicilio,descripcion, id_localidad)
                            VALUES
                                (:id_domicilio, :descripcion, :id_localidad)";
                    
                    $stmt = DB::getStatement($sql);
                    $stmt->bindParam(':id_domicilio', intval($maxId+1));
                    $domicilio->setId($maxId+1);
                }
            } else {
                $sql = "UPDATE domicilio
                        SET descripcion = :descripcion,
                        id_localidad = :id_localidad
                        WHERE id_domicilio = :id_domicilio"; 
                
                $stmt = DB::getStatement($sql);
                $stmt->bindParam(':id_domicilio', $domicilio->getId());
            }
            $stmt->bindParam(':descripcion', $domicilio->getDescripcion());
            $stmt->bindParam(':id_localidad', $domicilio->getLocalidad()->getId());
            
            $rdo = $stmt->execute();
            
            if (!$rdo) {
                print_r($stmt->errorInfo());
                echo "<br>";
                throw new Exception ("Error al guardar en la base de datos: ".$stmt->errorCode());
            }
            
            
        }
        
        public static function saveProveedor(Proveedor $proveedor) {
            
            $persona = $proveedor->getPersona();
            $persona->save();
            
            if ($proveedor->getId() == NULL) {
                $maxId = DB::getMaxIdFromTabla('proveedor');
                if ($maxId==NULL) {
                    throw new Exception ("Error al obtener max id");
                
                } else {
                    
                    $sql = "
                        INSERT INTO proveedor(id_proveedor,id_persona, activo)
                        VALUES(:id_proveedor, :id_persona, :activo)"; 
                    
                    $stmt = DB::getStatement($sql);
                    $stmt->bindParam(':id_proveedor', intval($maxId+1));
                    $stmt->bindParam(':id_persona', $persona->getId());
                    $stmt->bindParam(':activo', $proveedor->getActivo());
                }
            } else {
                $sql = 'UPDATE proveedor SET activo = :activo WHERE id_proveedor = :id_proveedor';
                $stmt = DB::getStatement($sql);
                $stmt->bindParam(':id_proveedor', $proveedor->getId());
                $stmt->bindParam(':activo', $proveedor->getActivo());
            }
            $rdo = $stmt->execute();
            
            if (!$rdo) {
                print_r($stmt->errorInfo());
                echo "<br>";
                throw new Exception ("Error al guardar en la base de datos: ".$stmt->errorCode());
            }
        }
        
        public static function saveCliente(Cliente $cliente) {
            
            $persona = $cliente->getPersona();
            $persona->save();
            
            if ($cliente->getId() == NULL) {
                
                $maxId = DB::getMaxIdFromTabla('cliente');
                if ($maxId==NULL) {
                    throw new Exception ("Error al obtener max id");
                
                } else {
                    
                    $sql= "
                        INSERT INTO cliente (id_cliente,id_persona,subempresa, activo)
                        VALUES(:id_cliente, :id_persona,:subempresa, :activo)"; 
                    
                    $stmt = DB::getStatement($sql);
                    $proxID = $maxId+1;
                    $stmt->bindParam(':id_cliente', $proxID);
                    $stmt->bindParam(':subempresa', $cliente->getSubempresa());
                    $stmt->bindParam(':id_persona', $persona->getId());
                }
            } else {
                $sql = 'UPDATE cliente SET activo = :activo,subempresa = :subempresa WHERE id_cliente = :id_cliente';
                $stmt = DB::getStatement($sql);
                $stmt->bindParam(':id_cliente', $cliente->getId());
            }
            $stmt->bindParam(':activo', $cliente->getActivo());
            $stmt->bindParam(':subempresa', $cliente->getSubempresa());
            
      
            
            $rdo = $stmt->execute();
            
            if (!$rdo) {
                print_r($stmt->errorInfo());
                echo "<br>";
                throw new Exception ("Error al guardar en la base de datos: ".$stmt->errorCode());
            }
        }
        
        public static function saveCompra(Compra $compra) {
            
            if ($compra->getId() == NULL) {
                
                $maxId = DB::getMaxIdFromTabla('compra');
                if ($maxId == NULL) {
                    throw new Exception ("Error al obtener max id");
                
                } else {
                    
                    $sql = 'INSERT INTO compra (id_compra, id_proveedor, fecha, activo)
                            VALUES (:id_compra, :id_proveedor, :fecha, :activo)';
                    
                    
                    $stmt = DB::getStatement($sql);
                    $id =  intval($maxId+1);
                    $stmt->bindParam(':id_compra', $id);
                    
                }
            } else {
                $sql = 'UPDATE compra SET id_proveedor = :id_proveedor, fecha = :fecha, activo = :activo
                        WHERE id_compra = :id_compra';
            
                $stmt = DB::getStatement($sql);
                $idParam = intval($compra->getId());
                $stmt->bindParam(':id_compra', $idParam);
            }
            


            $idProveedorCompra = $compra->getProveedor()->getId();

            $fechaCompra = $compra->getFecha();
            $activoCompra = $compra->getActivo() ;  
            $stmt->bindParam(':id_proveedor',$idProveedorCompra );
            $stmt->bindParam(':fecha',$fechaCompra );
            $stmt->bindParam(':activo',$activoCompra ); 
                
            $rdo = $stmt->execute();
            
            if (!$rdo) {
                print_r($stmt->errorInfo());
                echo "<br>";
                throw new Exception ("Error al guardar en la base de datos: ".$stmt->errorCode());
            }
        }

        public static function saveVenta(Venta $venta) {
            if ($venta->getId() == NULL) {

                $maxId = DB::getMaxIdFromTabla('venta');
                if ($maxId==NULL) {
                    throw new Exception ("Error al obtener max id");

                } else {

                    $sql = 'INSERT INTO venta (id_venta, id_cliente, total_abonado, fecha, activo)
                            VALUES (:id_venta, :id_cliente, :total_abonado, :fecha, :activo)';

                    $stmt = DB::getStatement($sql);
                    $id_venta = $maxId + 1;
                }
            } else {

                $sql = 'UPDATE venta SET id_cliente = :id_cliente,fecha = :fecha, total_abonado = :total_abonado, activo = :activo
                        WHERE id_venta = :id_venta';
                $stmt = DB::getStatement($sql);
                $id_venta = $venta->getId();
        
            }
            
            $cliente=$venta->getCliente();
            $fecha =  $venta->getFecha();
            $stmt->bindParam(':id_venta', $id_venta);
            $id_cliente = $venta->getCliente()->getId();
            $total = $venta->getTotalAbonado();
            $activo = $venta->getActivo();
            $stmt->bindParam(':id_cliente', $id_cliente);
            $stmt->bindParam(':fecha',$fecha);
            $stmt->bindParam(':total_abonado',$total  );
            $stmt->bindParam(':activo', $activo);
            

            $rdo = $stmt->execute();

            if (!$rdo) {
                print_r($stmt->errorInfo());
                echo "<br>";
                echo "<br>$sql<br>";
                throw new Exception ("Error al guardar en la base de datos: ".$stmt->errorCode());
            }
            return $id_venta;
        }
        
        public static function getUsuarioById($id_usuario) {
            $sql = 'SELECT * FROM usuario WHERE id_usuario = :id_usuario';
            
            $stmt = DB::getStatement($sql); 
            $stmt->bindParam(':id_usuario',$id_usuario,PDO::PARAM_INT); 
            $stmt->execute(); 

            return $stmt->fetchObject();
        }
        
        public static function saveUsuario(Usuario $usuario) {
            $id_usuario = $usuario->getId();
            $login = $usuario->getLogin();
            $hash = $usuario->getHash();
            $apellido = $usuario->getApellido();
            $nombre = $usuario->getNombre();
            $activo = $usuario->getActivo();
            
            if ($id_usuario == NULL) {
                $id_usuario = intval(DB::getMaxIdFromTabla('usuario')+1);
                $sql = 'INSERT INTO usuario (id_usuario, login, hash, nombre, apellido, activo) 
                        VALUES (:id_usuario, :login, :hash, :nombre, :apellido, :activo)';
            } else {
                $sql = 'UPDATE usuario SET login = :login, hash = :hash, nombre = :nombre, apellido = :apellido, activo = :activo
                        WHERE id_usuario = :id_usuario';
            }
            

            $stmt = DB::getStatement($sql);
            $stmt->bindParam(':id_usuario', $id_usuario);
            $stmt->bindParam(':login', $login);
            $stmt->bindParam(':hash', $hash);
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':apellido', $apellido, PDO::PARAM_STR);
            $stmt->bindParam(':activo', $activo, PDO::PARAM_INT);
            
            $rdo = $stmt->execute();
            
            if (!$rdo) {
                print_r($stmt->errorInfo());
                echo "<br><br>$sql<br><br>";
                print_r($usuario);
                throw new Exception ("Error al guardar en la base de datos: ".$stmt->errorCode());
            }            
        }
        
        public static function getIdUsuarioByLogin($login) {
            $sql = 'SELECT id_usuario FROM usuario WHERE login = :login LIMIT 1';
            
            $stmt = DB::getStatement($sql); 
            $stmt->bindParam(':login',$login,PDO::PARAM_STR); 
            $stmt->execute(); 
            $rdo = $stmt->fetchObject();
            if($rdo) {
                return $rdo->id_usuario;
            } else {
                return null;
            }
        }
        
        public static function getIdsUsuarios() {
            $sql = 'select id_usuario from usuario';
            
            $stmt = DB::getStatement($sql);
            $stmt->execute();
            $rdo = $stmt->fetchAll();
            
            return $rdo;
        }

        public static function getRolesByIdUsuario($id_usuario) {
            $sql = 'select id_rol from usuario_rol where id_usuario = :id_usuario';

            $stmt = DB::getStatement($sql);
            $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
            $stmt->execute();
            $rdo = $stmt->fetchAll();

            return $rdo;
        }

        public static function validarPermisoByIdUsuario($id_usuario, $id_permiso) {
            $sql = 'SELECT 1
                    FROM usuario_rol
                    INNER JOIN rol_permiso
                    ON usuario_rol.id_rol = rol_permiso.id_rol
                    WHERE id_usuario = :id_usuario
                    AND id_permiso = :id_permiso
                    AND usuario_rol.habilitado = 1';
            $stmt = DB::getStatement($sql);
            $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
            $stmt->bindParam(':id_permiso', $id_permiso, PDO::PARAM_INT);
            $stmt->execute();
            $rdo = $stmt->fetchObject();
            if($rdo) {

                return true;
            } else {
                
                return false;
            }
        }
        
    }      
?>
