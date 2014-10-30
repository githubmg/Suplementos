/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
 // JavaScript Document
    function agregarItem() {

        var combo = document.getElementById('comboboxpro');
        var id_producto = combo.options[combo.selectedIndex].value;
        var combo2 = document.getElementById('comboboxcli');
        var id_cliente = combo2.options[combo2.selectedIndex].value;
        var cantidad = document.getElementById('cantidad').value;
        var monto = document.getElementById('monto').value;
        var subempresa = 'MM'
		if (document.getElementById('subempresaForm').checked) {
			var subempresa = 'SM'
		}
        
        
        if (id_cliente == "" || id_producto == "" || cantidad == "" || monto == "" || (isNaN(cantidad)) || (isNaN(monto))) {
            alert ("Asegúrese de haber seleccionado cliente y producto, y completado monto y cantidad con valores numéricos");
            return 0;
        }
        
        var xhr = new XMLHttpRequest();
        xhr.open('GET','agregarItemAjax.php?id_producto=' + id_producto.toString()+'&cantidad=' + cantidad.toString() + '&monto=' + monto.toString() +'&subempresa=' + subempresa.toString());
        xhr.onreadystatechange = 
        function(){
            if (xhr.readyState == 4 && xhr.status == 200) {
                    var caja = document.getElementById('tablaItems');
                    caja.innerHTML = xhr.responseText;
            
                   }
            }
            xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');	
            xhr.send(null); // =)
	
        return 0;
        }
        
        function agregarItemCompra() {
        
        var combo = document.getElementById('comboboxpro');
        var id_producto = combo.options[combo.selectedIndex].value;
        
        var combo2 = document.getElementById('comboboxprove');
        var id_proveedor = combo2.options[combo2.selectedIndex].value;
        
        var cantidad = document.getElementById('cantidad').value;
        var monto = document.getElementById('monto').value;
        var precio = document.getElementById('precio').value;
        var subempresa = 'MM';
		if (document.getElementById('subempresaForm').checked) {
			var subempresa = 'SM';
		}
        if (id_proveedor == "" || id_producto == "" || cantidad == "" || monto == "" || (isNaN(cantidad)) || (isNaN(monto)) || precio == "" || (isNaN(precio))) {
            alert ("Asegúrese de haber seleccionado proveedor y producto, y completado precio unitario, monto y cantidad con valores numéricos");
            return 0;
        }
        
        
        var xhr = new XMLHttpRequest();
        xhr.open('GET','agregarItemCompraAjax.php?id_producto=' + 
		id_producto.toString()+'&cantidad=' + cantidad.toString() +
		'&monto=' + monto.toString() + '&precio=' + precio.toString() + 
		'&subempresa=' + subempresa.toString() );
        
		xhr.onreadystatechange = 
        function(){
            if (xhr.readyState == 4 && xhr.status == 200) {
                    var caja = document.getElementById('tablaItems');
                    caja.innerHTML = xhr.responseText;
            
                   }
            }
        xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');	
        xhr.send(null); // =)
	
	document.getElementById('monto').value = "";
        document.getElementById('cantidad').value = "";
        document.getElementById('precio').value = "";
        }
        
        
		function borrarItem(id) {

		var xhr = new XMLHttpRequest();
        xhr.open('GET','agregarItemAjax.php?id='+id.toString());
        xhr.onreadystatechange = 
        function(){
            if (xhr.readyState == 4 && xhr.status == 200) {
                    var caja = document.getElementById('tablaItems');
                    caja.innerHTML = xhr.responseText;
            
                   }
            }
            xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');	
            xhr.send(null); // =)
			
        }
		
		function borrarItemsSesionAjax() {
		
		var xhr = new XMLHttpRequest();
        xhr.open('GET','borrarItemsSesionAjax.php');
        xhr.onreadystatechange = 
        function(){
            if (xhr.readyState == 4 && xhr.status == 200) {
                 var caja = document.getElementById('tablaItems'); 
            }
			}
            xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');	
            xhr.send(null); // =)
        }
	
        function traerStock() {

            var combo = document.getElementById('comboboxpro');
            var id_producto = combo.options[combo.selectedIndex].value;
            
            var xhr = new XMLHttpRequest();
            xhr.open('GET','traerStockDisponible.php?id_producto=' + id_producto.toString());
            xhr.onreadystatechange = 
            function(){
            if (xhr.readyState == 4 && xhr.status == 200) {
                    var caja = document.getElementById('respuesta');
                    caja.innerHTML = xhr.responseText;
            
                   }
            }
            xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');	
            xhr.send(null); // =)
        }
		
		
		function traerComboLocalidad() {
		var combo = document.getElementById('comboboxprov');
		var id_provincia = combo.options[combo.selectedIndex].value;
		var xhr = new XMLHttpRequest();
        xhr.open('GET','traerComboLocalidad.php?id_provincia=' + id_provincia.toString());
        xhr.onreadystatechange = 
        function(){
            if (xhr.readyState == 4 && xhr.status == 200) {
                    var caja = document.getElementById('respuestaComboLoc');
                    caja.innerHTML = xhr.responseText;
					ALoc();
					BLoc();
					$( "#comboboxloc" ).toggle();
					           
                   }
            }
            xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');	
            xhr.send(null); // =)
			
        }
		function agregarProducto() {
			var descripcion = document.getElementById('descripcion').value;
			var tamanio = document.getElementById('tamanio').value;
			var sabor = document.getElementById('sabor').value;
			var combo = document.getElementById('comboboxlab');
			var id_laboratorio = combo.options[combo.selectedIndex].value;
			var obs = document.getElementById('observaciones').value;
			var xhr = new XMLHttpRequest();
			xhr.open('GET','agregarProductoAjax.php?descripcion=' + descripcion.toString() + '&tamanio='+ tamanio.toString() + '&sabor='+sabor.toString() + '&id_laboratorio='+id_laboratorio+ '&obs='+obs.toString());
			xhr.onreadystatechange = 
			function(){
				if (xhr.readyState == 4 && xhr.status == 200) {
						var caja = document.getElementById('respuestaProducto');
						caja.innerHTML = xhr.responseText;
						APro();
						BPro();
						$( "#comboboxpro" ).toggle();
						mostrarAgregarProducto();				           
					   }
				}
			xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');	
			xhr.send(null); // =)
			
        }
		function agregarLaboratio() {
	
			var laboratorio = document.getElementById('laboratorio').value;
			var xhr = new XMLHttpRequest();
			xhr.open('GET','agregarLaboratorioAjax.php?laboratorio=' + laboratorio.toString());
			xhr.onreadystatechange = 
			function(){
				if (xhr.readyState == 4 && xhr.status == 200) {
						var caja = document.getElementById('respuestaLaboratorio');
						caja.innerHTML = xhr.responseText;
						ALab();
						BLab();
						$( "#comboboxlab" ).toggle();
						mostrarLaboratorio();				           
					   }
				}
			xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');	
			xhr.send(null); // =)
			
        }
        
        function traerCosto() {
            var cantidad = document.getElementById('cantidad').value;
            var combo = document.getElementById('comboboxpro');
            var id_producto = combo.options[combo.selectedIndex].value;
            
            if (id_producto == "" || cantidad == "" || (isNaN(cantidad))) 
            {
                return 0;
            }
            
            
            var xhr = new XMLHttpRequest();
            url = 'ajax/traerCosto.php?id_producto=' + id_producto.toString() + '&cantidad='+ cantidad.toString();
            xhr.open('GET',url);
            xhr.onreadystatechange = 
            function(){
            if (xhr.readyState == 4 && xhr.status == 200) {
                    var caja = document.getElementById('costo');
                    caja.innerHTML = xhr.responseText;
                   }
            }
            xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');	
            xhr.send(null); // =)
        }
