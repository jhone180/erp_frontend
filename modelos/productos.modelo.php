<?php

require_once "conexion.php";

class ModeloProductos{

	/*=============================================
	MOSTRAR PRODUCTOS
	=============================================*/

	static public function mdlMostrarProductos($tabla, $item, $valor){

		if($item != null){

			$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE $item = :$item ORDER BY id DESC");

			$stmt -> bindParam(":".$item, $valor, PDO::PARAM_STR);

			$stmt -> execute();

			return $stmt -> fetch();

		}else{

			$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla");

			$stmt -> execute();

			return $stmt -> fetchAll();

		}

		$stmt -> close();

		$stmt = null;

	}

	/*=============================================
	REGISTRO DE PRODUCTO
	=============================================*/
	static public function mdlIngresarProducto($tabla, $datos){

		$stmt = Conexion::conectar()->prepare("INSERT INTO $tabla(id_categoria, codigo, descripcion, imagen, stock, ubicacion, peso, largo, ancho, alto, uni_medida, lote, modelo_inventario, inventario_seguridad, pto_pedido, ias, ias_maxima, centro_costos, costo_produccion, costo_venta, costo_estandar) VALUES (:id_categoria, :codigo, :descripcion, :imagen, :stock, :ubicacion, :peso, :largo, :ancho, :alto, :uni_medida, :lote, :modelo_inventario, :inventario_seguridad, :pto_pedido, :ias, :ias_maxima, :centro_costos, :costo_produccion, :costo_venta, :costo_estandar)");

		$stmt->bindParam(":id_categoria", $datos["id_categoria"], PDO::PARAM_INT);
		$stmt->bindParam(":codigo", $datos["codigo"], PDO::PARAM_STR);
		$stmt->bindParam(":descripcion", $datos["descripcion"], PDO::PARAM_STR);
		$stmt->bindParam(":imagen", $datos["imagen"], PDO::PARAM_STR);
		$stmt->bindParam(":stock", $datos["stock"], PDO::PARAM_STR);
		$stmt->bindParam(":ubicacion", $datos["ubicacion"], PDO::PARAM_STR);
		$stmt->bindParam(":peso", $datos["peso"], PDO::PARAM_STR);
		$stmt->bindParam(":largo", $datos["largo"], PDO::PARAM_STR);
		$stmt->bindParam(":ancho", $datos["ancho"], PDO::PARAM_STR);
		$stmt->bindParam(":alto", $datos["alto"], PDO::PARAM_STR);
		$stmt->bindParam(":uni_medida", $datos["uni_medida"], PDO::PARAM_STR);
		$stmt->bindParam(":lote", $datos["lote"], PDO::PARAM_STR);
		$stmt->bindParam(":modelo_inventario", $datos["modelo_inventario"], PDO::PARAM_STR);
		$stmt->bindParam(":inventario_seguridad", $datos["inventario_seguridad"], PDO::PARAM_STR);
		$stmt->bindParam(":pto_pedido", $datos["pto_pedido"], PDO::PARAM_STR);
		$stmt->bindParam(":ias", $datos["ias"], PDO::PARAM_STR);
		$stmt->bindParam(":ias_maxima", $datos["ias_maxima"], PDO::PARAM_STR);
		$stmt->bindParam(":centro_costos", $datos["centro_costos"], PDO::PARAM_STR);
		$stmt->bindParam(":costo_produccion", $datos["costo_produccion"], PDO::PARAM_STR);
		$stmt->bindParam(":costo_venta", $datos["costo_venta"], PDO::PARAM_STR);
		$stmt->bindParam(":costo_estandar", $datos["costo_estandar"], PDO::PARAM_STR);

		if($stmt->execute()){

			return "ok";

		}else{

			return "error";
		
		}

		$stmt->close();
		$stmt = null;

	}

	/*=============================================
	EDITAR DE PRODUCTO
	=============================================*/
	static public function mdlEditarProducto($tabla, $datos){

		$stmt = Conexion::conectar()->prepare("UPDATE $tabla SET id_categoria = :id_categoria, descripcion = :descripcion, imagen = :imagen, stock = :stock, ubicacion = :ubicacion, peso = :peso, largo = :largo, ancho = :ancho, alto = :alto, uni_medida = :uni_medida, lote = :lote, modelo_inventario = :modelo_inventario, inventario_seguridad = :inventario_seguridad, pto_pedido = :pto_pedido, ias = :ias, ias_maxima = :ias_maxima, centro_costos = :centro_costos = :centro_costos, costo_produccion = :costo_produccion, costo_venta = :costo_venta, costo_estandar = :costo_estandar WHERE codigo = :codigo");

		$stmt->bindParam(":id_categoria", $datos["id_categoria"], PDO::PARAM_INT);
		$stmt->bindParam(":codigo", $datos["codigo"], PDO::PARAM_STR);
		$stmt->bindParam(":descripcion", $datos["descripcion"], PDO::PARAM_STR);
		$stmt->bindParam(":imagen", $datos["imagen"], PDO::PARAM_STR);
		$stmt->bindParam(":stock", $datos["stock"], PDO::PARAM_STR);
		$stmt->bindParam(":ubicacion", $datos["ubicacion"], PDO::PARAM_STR);
		$stmt->bindParam(":peso", $datos["peso"], PDO::PARAM_STR);
		$stmt->bindParam(":largo", $datos["largo"], PDO::PARAM_STR);
		$stmt->bindParam(":ancho", $datos["ancho"], PDO::PARAM_STR);
		$stmt->bindParam(":alto", $datos["alto"], PDO::PARAM_STR);
		$stmt->bindParam(":uni_medida", $datos["uni_medida"], PDO::PARAM_STR);
		$stmt->bindParam(":lote", $datos["lote"], PDO::PARAM_STR);
		$stmt->bindParam(":modelo_inventario", $datos["modelo_inventario"], PDO::PARAM_STR);
		$stmt->bindParam(":inventario_seguridad", $datos["inventario_seguridad"], PDO::PARAM_STR);
		$stmt->bindParam(":pto_pedido", $datos["pto_pedido"], PDO::PARAM_STR);
		$stmt->bindParam(":ias", $datos["ias"], PDO::PARAM_STR);
		$stmt->bindParam(":ias_maxima", $datos["ias_maxima"], PDO::PARAM_STR);
		$stmt->bindParam(":centro_costos", $datos["centro_costos"], PDO::PARAM_STR);
		$stmt->bindParam(":costo_produccion", $datos["costo_produccion"], PDO::PARAM_STR);
		$stmt->bindParam(":costo_venta", $datos["costo_venta"], PDO::PARAM_STR);
		$stmt->bindParam(":costo_estandar", $datos["costo_estandar"], PDO::PARAM_STR);

		if($stmt->execute()){

			return "ok";

		}else{

			return "error";
		
		}

		$stmt->close();
		$stmt = null;

	}

	/*=============================================
	ELIMINAR DE PRODUCTO
	=============================================*/

	static public function mdlEliminarProducto($tabla, $datos){

		$stmt = Conexion::conectar()->prepare("DELETE FROM $tabla WHERE id = :id");

		$stmt->bindParam(":id", $datos, PDO::PARAM_INT);

		if($stmt->execute()){

			return "ok";

		}else{

			return "error";
		
		}

		$stmt->close();
		$stmt = null;

	}

	/*=============================================
	ACTUALIZAR PRODUCTO
	=============================================*/

	static public function mdlActualizarProducto($tabla, $item1, $valor1, $valor){

		$stmt = Conexion::conectar()->prepare("UPDATE $tabla SET $item1 = :$item1 WHERE id = :id");

		$stmt -> bindParam(":".$item1, $valor1, PDO::PARAM_STR);
		$stmt -> bindParam(":id", $valor, PDO::PARAM_STR);

		if($stmt -> execute()){

			return "ok";
		
		}else{

			return "error";	

		}

		$stmt -> close();

		$stmt = null;

	}

	/*=============================================
	MOSTRAR SUMA VENTAS
	=============================================*/	

	static public function mdlMostrarSumaVentas($tabla){

		$stmt = Conexion::conectar()->prepare("SELECT SUM(ventas) as total FROM $tabla");

		$stmt -> execute();

		return $stmt -> fetch();

		$stmt -> close();

		$stmt = null;
	}

}
