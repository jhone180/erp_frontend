<?php

if($_SESSION["perfil"] == "Vendedor"){

  echo '<script>

    window.location = "inicio";

  </script>';

  return;

}

?>

<div class="content-wrapper">

  <section class="content-header">
    
    <h1>
      
      Administrar productos
    
    </h1>

    <ol class="breadcrumb">
      
      <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
      
      <li class="active">Administrar productos</li>
    
    </ol>

  </section>

  <section class="content">

    <div class="box">

      <div class="box-header with-border">
  
        <button class="btn btn-primary" data-toggle="modal" data-target="#modalAgregarProducto">
          
          Agregar producto

        </button>

      </div>

      <div class="box-body">
        
       <table class="table table-bordered table-striped dt-responsive tablaProductos" width="100%" >
         
        <thead>
         
          <tr>
           
           <th style="width:10px">#</th>
           <th>Imagen</th>
           <th>Cod</th>
           <th>Descripción</th>
           <th>Categoría</th>
           <th>Stock</th>
           <th>Ubicacion</th>
           <th>Peso</th>
           <th>Largo</th>
           <th>Ancho</th>
           <th>Alto</th>
           <th>Und medida</th>
           <th>Lote</th>
           <th>Inventario</th>
           <th>INV SEG</th>
           <th>Pto Pedido</th>
           <th>IAS</th>
           <th>IAS max</th>
           <th>Centro costos</th>
           <th>Costo produccion</th>
           <th>Costo venta</th>
           <th>Costo estandar</th>
           <th>Agregado</th>
           <th>Acciones</th>
           
         </tr> 

        </thead>

      <tbody>

          <?php 

          $item = null;
          $valor = null;

          $productos = ControladorProductos::ctrMostrarProductos($item, $valor);

          foreach ($productos as $key => $value){

            echo '<tr>
                    <td>'.($key+1).'</td>
                    <td><img src="vistas/img/productos/default/anonymous.png" class="img-thumbnail" width="40px">
                      </td>
                    <td>'.$value["codigo"].'</td>
                    <th>'.$value["descripcion"].'</th>';

                    $item = "id";
                    $valor = $value["id_categoria"];

                    $categoria = ControladorCategorias::ctrMostrarCategorias($item, $valor);

                    echo'<th>'.$categoria["categoria"].'</th>
                    <th>'.$value["stock"].'</th>
                    <th>'.$value["ubicacion"].'</th>
                    <th>'.$value["peso"].'</th>
                    <th>'.$value["largo"].'</th>
                    <th>'.$value["ancho"].'</th>
                    <th>'.$value["alto"].'</th>
                    <th>'.$value["uni_medida"].'</th>
                    <th>'.$value["lote"].'</th>
                    <th>'.$value["modelo_inventario"].'</th>
                    <th>'.$value["inventario_seguridad"].'</th>
                    <th>'.$value["pto_pedido"].'</th>
                    <th>'.$value["ias"].'</th>
                    <th>'.$value["ias_maxima"].'</th>
                    <th>'.$value["centro_costos"].'</th>
                    <th>'.$value["costo_produccion"].'</th>
                    <th>'.$value["costo_venta"].'</th>
                    <th>'.$value["costo_estandar"].'</th>
                    <th>'.$value["fecha"].'</th>
                    <td>

                      <div class="btn-group">
                        
                          <button class="btn btn-warning"><i class="fa fa-pencil"></i></button>

                          <button class="btn btn-danger"><i class="fa fa-times"></i></button>

                      </div>  

                  </td>

                </tr>';

          }

          ?>
        
        </tbody>

       </table>

        <input type="hidden" value="<?php echo $_SESSION['perfil']; ?>" id="perfilOculto">

      </div>

    </div>

  </section>

</div>
<!--=====================================
MODAL AGREGAR PRODUCTO
======================================-->

    <div id="modalAgregarProducto" class="modal fade" role="dialog">
      
      <div class="modal-dialog">

        <div class="modal-content">

          <form role="form" method="post" enctype="multipart/form-data">

            <!--=====================================
            CABEZA DEL MODAL
            ======================================-->

            <div class="modal-header" style="background:#3c8dbc; color:white">

              <button type="button" class="close" data-dismiss="modal">&times;</button>

              <h4 class="modal-title">Agregar producto</h4>

            </div>

            <!--=====================================
            CUERPO DEL MODAL
            ======================================-->
            

            <div class="modal-body">

              <div class="box-body">
                <!-- ENTRADA PARA SELECCIONAR CATEGORÍA -->

                <div class="form-group">
                  
                  <div class="input-group">
                  
                    <span class="input-group-addon"><i class="fa fa-th"></i></span> 

                    <select class="form-control input-lg" id="nuevaCategoria" name="nuevaCategoria" required>
                      
                      <option value="">Selecionar categoría</option>
                      <?php

                        $item = null;
                        $valor = null;

                        $categorias = ControladorCategorias::ctrMostrarCategorias($item, $valor);

                        foreach ($categorias as $key => $value) {
                          
                          echo '<option value="'.$value["id"].'">'.$value["categoria"].'</option>';
                        }

                      ?>

                    </select>

                  </div>

                </div>

                <!-- ENTRADA PARA EL CÓDIGO -->
                
                <div class="form-group">
                  
                  <div class="input-group">
                  
                    <span class="input-group-addon"><i class="fa fa-code"></i></span> 

                    <input type="text" class="form-control input-lg" id="nuevoCodigo" name="nuevoCodigo" placeholder="Ingresar código" required>

                  </div>

                </div>

                <!-- ENTRADA PARA LA DESCRIPCIÓN -->

                 <div class="form-group">
                  
                  <div class="input-group">
                  
                    <span class="input-group-addon"><i class="fa fa-product-hunt"></i></span> 

                    <input type="text" class="form-control input-lg" name="nuevaDescripcion" placeholder="Ingresar descripción"  required>

                  </div>

                </div>
                <!-- ENTRADA PARA SELECCIONAR INSTALACION -->

                <div class="form-group">
                  
                  <div class="input-group">
                  
                    <span class="input-group-addon"><i class="fa fa-th"></i></span> 

                    <select class="form-control input-lg" name="nuevaInstalacion">
                      
                      <option value="">Selecionar instalacion</option>

                      <option value="bogota">Bogota</option>

                      <option value="medellin">Medellin</option>

                      <option value="cali">Cali</option>

                    </select>

                  </div>

                </div>

                
                <!-- ENTRADA PARA STOCK -->

                 <div class="form-group">
                  
                  <div class="input-group">
                  
                    <span class="input-group-addon"><i class="fa fa-check"></i></span> 

                    <input type="number" class="form-control input-lg" name="nuevoStock" min="0" placeholder="Stock" required>

                  </div>

                </div>

                <div class="row">

                <!-- ENTRADAS PARA LA DESCRIPCIÓN - Alineadas a la izquierda -->

                <div class="col-xs-6">

                  <!-- ENTRADA PARA PESO NETO -->

                  <div class="form-group">

                    <div class="input-group">

                      <span class="input-group-addon"><i class="fa fa-product-hunt"></i></span>

                      <input type="text" class="form-control input-lg" name="nuevoPeso" placeholder="Peso neto" required>

                    </div>

                  </div>

                  <div class="form-group">

                    <!-- ENTRADA PARA ANCHO -->

                    <div class="input-group">

                      <span class="input-group-addon"><i class="fa fa-product-hunt"></i></span>

                      <input type="text" class="form-control input-lg" name="nuevoAncho" placeholder="Ancho" required>

                    </div>

                  </div>

                </div>

                <!-- ENTRADAS PARA PESO NETO Y LARGO - Alineadas a la derecha -->

                <div class="col-xs-6">

                  <!-- ENTRADA PARA LARGO -->

                  <div class="form-group">

                    <div class="input-group">

                      <span class="input-group-addon"><i class="fa fa-product-hunt"></i></span>

                      <input type="text" class="form-control input-lg" name="nuevoLargo" placeholder="Largo" required>

                    </div>

                  </div>

                  <!-- ENTRADA PARA ALTO -->

                  <div class="form-group">

                    <div class="input-group">

                      <span class="input-group-addon"><i class="fa fa-product-hunt"></i></span>

                      <input type="text" class="form-control input-lg" name="nuevoAlto" placeholder="Alto" required>

                    </div>

                  </div>

                </div>

              </div>

               <div class="row">   

                <!-- ENTRADAS PARA LA DESCRIPCIÓN - Alineadas a la izquierda -->

                <div class="col-xs-6">

                  <!-- ENTRADA PARA UNIDAD DE MEDIDA -->

                  <div class="form-group">

                    <div class="input-group">

                      <span class="input-group-addon"><i class="fa fa-product-hunt"></i></span>

                      <input type="text" class="form-control input-lg" name="nuevaMedida" placeholder="Uni Medida" required>

                    </div>

                  </div>

                  <div class="form-group">

                    <!-- ENTRADA PARA ANCHO -->

                    <div class="input-group">

                      <span class="input-group-addon"><i class="fa fa-product-hunt"></i></span>

                      <input type="text" class="form-control input-lg" name="" placeholder="Ancho" readonly required>

                    </div>

                  </div>

                </div>

                <!-- ENTRADAS PARA PESO NETO Y LARGO - Alineadas a la derecha -->

                <div class="col-xs-6">

                  <!-- ENTRADA PARA LOTE -->

                  <div class="form-group">

                    <div class="input-group">

                      <span class="input-group-addon"><i class="fa fa-product-hunt"></i></span>

                      <input type="text" class="form-control input-lg" name="nuevoLote" placeholder="Lote" required>

                    </div>

                  </div>

                  <!-- ENTRADA PARA ALTO -->

                  <div class="form-group">

                    <div class="input-group">

                      <span class="input-group-addon"><i class="fa fa-product-hunt"></i></span>

                      <input type="text" class="form-control input-lg" name="nuevoModeloInventario" placeholder="Modelo de inventario" required>

                    </div>

                  </div>

                </div>

              </div>

              <div class="row">   

                <!-- ENTRADAS PARA LA DESCRIPCIÓN - Alineadas a la izquierda -->

                <div class="col-xs-6">

                  <!-- ENTRADA PARA INVETARIO DE SEGURIDAD -->

                  <div class="form-group">

                    <div class="input-group">

                      <span class="input-group-addon"><i class="fa fa-product-hunt"></i></span>

                      <input type="text" class="form-control input-lg" name="nuevoInventarioSeguridad" placeholder="Inventario de seguridad" required>

                    </div>

                  </div>

                  <div class="form-group">

                    <!-- ENTRADA PARA IAS -->

                    <div class="input-group">

                      <span class="input-group-addon"><i class="fa fa-product-hunt"></i></span>

                      <input type="text" class="form-control input-lg" name="nuevaIAS" placeholder="IAS" required>

                    </div>

                  </div>

                </div>

                <!-- ENTRADAS PARA PUNTO DE PEDIDO - Alineadas a la derecha -->

                <div class="col-xs-6">

                  <!-- ENTRADA PARA LOTE -->

                  <div class="form-group">

                    <div class="input-group">

                      <span class="input-group-addon"><i class="fa fa-product-hunt"></i></span>

                      <input type="text" class="form-control input-lg" name="nuevoPuntoPedido" placeholder="Punto de pedido" required>

                    </div>

                  </div>

                  <!-- ENTRADA PARA IAS MAXIMO -->

                  <div class="form-group">

                    <div class="input-group">

                      <span class="input-group-addon"><i class="fa fa-product-hunt"></i></span>

                      <input type="text" class="form-control input-lg" name="nuevoIasMaximo" placeholder="IAS MAXIMO" required>

                    </div>

                  </div>

                </div>

              </div>

            <div class="row">   

                    <!-- ENTRADAS PARA LA DESCRIPCIÓN - Alineadas a la izquierda -->

                    <div class="col-xs-6 col-sm-6">

                      <div class="form-group">

                        <!-- ENTRADA PARA CENTRO COSTO -->

                        <div class="input-group">

                          <span class="input-group-addon"><i class="fa fa-arrow-down"></i></span>

                          <input type="number" class="form-control input-lg" name="nuevoCentroCosto" min="0" step="any" placeholder="Centro de costos" required>

                        </div>

                      </div>

                    </div>

                    <!-- ENTRADAS PARA PUNTO DE PEDIDO - Alineadas a la derecha -->

                    <div class="col-xs-12 col-sm-6">

                      <!-- ENTRADA PARA COSTO PRODUCCION -->

                      <div class="form-group">

                        <div class="input-group">

                          <span class="input-group-addon"><i class="fa fa-arrow-down"></i></span>

                          <input type="number" class="form-control input-lg" name="nuevoCostoProduccion" min="0" step="any" placeholder="Costo de producción" required>

                        </div>

                      </div>

                    </div>

                  </div>

             <!-- ENTRADA PARA COSTO ESTANDAR-->

             <div class="form-group row">

                <div class="col-xs-12 col-sm-6">
                
                  <div class="input-group">
                  
                    <span class="input-group-addon"><i class="fa fa-arrow-up"></i></span> 

                    <input type="number" class="form-control input-lg" id="nuevoCostoEstandar" name="nuevoCostoEstandar" min="0" step="any" placeholder="Costo estandar " required>

                  </div>

                </div>

                <!-- ENTRADA PARA COSTO DE VENTA -->

                <div class="col-xs-12 col-sm-6">
                
                  <div class="input-group">
                  
                    <span class="input-group-addon"><i class="fa fa-arrow-down"></i></span> 

                    <input type="number" class="form-control input-lg" id="nuevoCostoVenta" name="nuevoCostoVenta" min="0" step="any" placeholder="Costo de venta" required>

                  </div>
                
                  <br>

                  <!-- CHECKBOX PARA PORCENTAJE -->

                  <div class="col-xs-6">
                    
                    <div class="form-group">
                      
                      <label>
                        
                        <input type="checkbox" class="minimal porcentaje" checked>
                        Utilizar procentaje
                      </label>

                    </div>

                  </div>

                  <!-- ENTRADA PARA PORCENTAJE -->

                  <div class="col-xs-6" style="padding:0">
                    
                    <div class="input-group">
                      
                      <input type="number" class="form-control input-lg nuevoPorcentaje" min="0" value="40" required>

                      <span class="input-group-addon"><i class="fa fa-percent"></i></span>

                    </div>

                  </div>

                </div>

            </div>

            <!-- ENTRADA PARA SUBIR FOTO -->

             <div class="form-group">
              
              <div class="panel">SUBIR IMAGEN</div>

              <input type="file" class="nuevaImagen" name="nuevaImagen">

              <p class="help-block">Peso máximo de la imagen 2MB</p>

              <img src="vistas/img/productos/default/anonymous.png" class="img-thumbnail previsualizar" width="100px">

            </div>

          </div>

        </div>

        <!--=====================================
        PIE DEL MODAL
        ======================================-->

        <div class="modal-footer">

          <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Salir</button>

          <button type="submit" class="btn btn-primary">Guardar producto</button>

        </div>

      </form>

        <?php  

          $crearProducto = new ControladorProductos();
          $crearProducto -> ctrCrearProducto();

        ?>

    </div>

  </div>

</div>
<!--=====================================
MODAL EDITAR PRODUCTO
======================================-->

    <div id="modalEditarProducto" class="modal fade" role="dialog">
      
      <div class="modal-dialog">

        <div class="modal-content">

          <form role="form" method="post" enctype="multipart/form-data">

            <!--=====================================
            CABEZA DEL MODAL
            ======================================-->

            <div class="modal-header" style="background:#3c8dbc; color:white">

              <button type="button" class="close" data-dismiss="modal">&times;</button>

              <h4 class="modal-title">Editar producto</h4>

            </div>

            <!--=====================================
            CUERPO DEL MODAL
            ======================================-->
            

            <div class="modal-body">

              <div class="box-body">
                <!-- ENTRADA PARA SELECCIONAR CATEGORÍA -->

                <div class="form-group">
                  
                  <div class="input-group">
                  
                    <span class="input-group-addon"><i class="fa fa-th"></i></span> 

                    <select class="form-control input-lg" name="editarCategoria" readonly required>
                      
                      <option id="editarCategoria"> </option>

                    </select>

                  </div>

                </div>

                <!-- ENTRADA PARA EL CÓDIGO -->
                
                <div class="form-group">
                  
                  <div class="input-group">
                  
                    <span class="input-group-addon"><i class="fa fa-code"></i></span> 

                    <input type="text" class="form-control input-lg" id="editarCodigo" name="editarCodigo" readonly required>

                  </div>

                </div>

                <!-- ENTRADA PARA LA DESCRIPCIÓN -->

                 <div class="form-group">
                  
                  <div class="input-group">
                  
                    <span class="input-group-addon"><i class="fa fa-product-hunt"></i></span> 

                    <input type="text" class="form-control input-lg" id="editarDescripcion"name="editarDescripcion"   required>

                  </div>

                </div>
                <!-- ENTRADA PARA SELECCIONAR INSTALACION -->

                <div class="form-group">
                  
                  <div class="input-group">
                  
                    <span class="input-group-addon"><i class="fa fa-th"></i></span> 

                    <select class="form-control input-lg" id="editarInstalacion" name="editarInstalacion" readonly>
                      
                      <option value="">Selecionar instalacion</option>

                      <option value="bogota">Bogota</option>

                      <option value="medellin">Medellin</option>

                      <option value="cali">Cali</option>

                    </select>

                  </div>

                </div>

                
                <!-- ENTRADA PARA STOCK -->

                 <div class="form-group">
                  
                  <div class="input-group">
                  
                    <span class="input-group-addon"><i class="fa fa-check"></i></span> 

                    <input type="number" class="form-control input-lg" id="editarStock" name="editarStock" min="0"  required>

                  </div>

                </div>

                <div class="row">

                <!-- ENTRADAS PARA LA DESCRIPCIÓN - Alineadas a la izquierda -->

                <div class="col-xs-6">

                  <!-- ENTRADA PARA PESO NETO -->

                  <div class="form-group">

                    <div class="input-group">

                      <span class="input-group-addon"><i class="fa fa-product-hunt"></i></span>

                      <input type="text" class="form-control input-lg" id="editarPeso" name="editarPeso" required>

                    </div>

                  </div>

                  <div class="form-group">

                    <!-- ENTRADA PARA ANCHO -->

                    <div class="input-group">

                      <span class="input-group-addon"><i class="fa fa-product-hunt"></i></span>

                      <input type="text" class="form-control input-lg" id="editarAncho" name="editarAncho" required>

                    </div>

                  </div>

                </div>

                <!-- ENTRADAS PARA PESO NETO Y LARGO - Alineadas a la derecha -->

                <div class="col-xs-6">

                  <!-- ENTRADA PARA LARGO -->

                  <div class="form-group">

                    <div class="input-group">

                      <span class="input-group-addon"><i class="fa fa-product-hunt"></i></span>

                      <input type="text" class="form-control input-lg" id="editarLargo" name="editarLargo"  required>

                    </div>

                  </div>

                  <!-- ENTRADA PARA ALTO -->

                  <div class="form-group">

                    <div class="input-group">

                      <span class="input-group-addon"><i class="fa fa-product-hunt"></i></span>

                      <input type="text" class="form-control input-lg" id="editarAlto" name="editarAlto" required>

                    </div>

                  </div>

                </div>

              </div>

               <div class="row">   

                <!-- ENTRADAS PARA LA DESCRIPCIÓN - Alineadas a la izquierda -->

                <div class="col-xs-6">

                  <!-- ENTRADA PARA UNIDAD DE MEDIDA -->

                  <div class="form-group">

                    <div class="input-group">

                      <span class="input-group-addon"><i class="fa fa-product-hunt"></i></span>

                      <input type="text" class="form-control input-lg" id="editarMedida" name="editarMedida"  required>

                    </div>

                  </div>

                  <div class="form-group">

                    <!-- ENTRADA PARA ANCHO -->

                    <div class="input-group">

                      <span class="input-group-addon"><i class="fa fa-product-hunt"></i></span>

                      <input type="text" class="form-control input-lg" name="" placeholder="Ancho" readonly required>

                    </div>

                  </div>

                </div>

                <!-- ENTRADAS PARA PESO NETO Y LARGO - Alineadas a la derecha -->

                <div class="col-xs-6">

                  <!-- ENTRADA PARA LOTE -->

                  <div class="form-group">

                    <div class="input-group">

                      <span class="input-group-addon"><i class="fa fa-product-hunt"></i></span>

                      <input type="text" class="form-control input-lg" id="editarLote" name="editarLote" required>

                    </div>

                  </div>

                  <!-- ENTRADA PARA ALTO -->

                  <div class="form-group">

                    <div class="input-group">

                      <span class="input-group-addon"><i class="fa fa-product-hunt"></i></span>

                      <input type="text" class="form-control input-lg" id="editarModeloInventario" name="editarModeloInventario" required>

                    </div>

                  </div>

                </div>

              </div>

              <div class="row">   

                <!-- ENTRADAS PARA LA DESCRIPCIÓN - Alineadas a la izquierda -->

                <div class="col-xs-6">

                  <!-- ENTRADA PARA INVETARIO DE SEGURIDAD -->

                  <div class="form-group">

                    <div class="input-group">

                      <span class="input-group-addon"><i class="fa fa-product-hunt"></i></span>

                      <input type="text" class="form-control input-lg" id="editarInventarioSeguridad" name="editarInventarioSeguridad" required>

                    </div>

                  </div>

                  <div class="form-group">

                    <!-- ENTRADA PARA IAS -->

                    <div class="input-group">

                      <span class="input-group-addon"><i class="fa fa-product-hunt"></i></span>

                      <input type="text" class="form-control input-lg" id="editarIAS" name="editarIAS" required>

                    </div>

                  </div>

                </div>

                <!-- ENTRADAS PARA PUNTO DE PEDIDO - Alineadas a la derecha -->

                <div class="col-xs-6">

                  <!-- ENTRADA PARA LOTE -->

                  <div class="form-group">

                    <div class="input-group">

                      <span class="input-group-addon"><i class="fa fa-product-hunt"></i></span>

                      <input type="text" class="form-control input-lg" id="editarPuntoPedido" name="editarPuntoPedido" required>

                    </div>

                  </div>

                  <!-- ENTRADA PARA IAS MAXIMO -->

                  <div class="form-group">

                    <div class="input-group">

                      <span class="input-group-addon"><i class="fa fa-product-hunt"></i></span>

                      <input type="text" class="form-control input-lg" id="editarIasMaximo" name="editarIasMaximo" required>

                    </div>

                  </div>

                </div>

              </div>

            <div class="row">   

                    <!-- ENTRADAS PARA LA DESCRIPCIÓN - Alineadas a la izquierda -->

                    <div class="col-xs-6 col-sm-6">

                      <div class="form-group">

                        <!-- ENTRADA PARA CENTRO COSTO -->

                        <div class="input-group">

                          <span class="input-group-addon"><i class="fa fa-arrow-down"></i></span>

                          <input type="number" class="form-control input-lg" id="editarCentroCosto" name="editarCentroCosto" min="0" step="any"  required>

                        </div>

                      </div>

                    </div>

                    <!-- ENTRADAS PARA PUNTO DE PEDIDO - Alineadas a la derecha -->

                    <div class="col-xs-12 col-sm-6">

                      <!-- ENTRADA PARA COSTO PRODUCCION -->

                      <div class="form-group">

                        <div class="input-group">

                          <span class="input-group-addon"><i class="fa fa-arrow-down"></i></span>

                          <input type="number" class="form-control input-lg" id="editarCostoProduccion" name="editarCostoProduccion" min="0" step="any"  required>

                        </div>

                      </div>

                    </div>

                  </div>

             <!-- ENTRADA PARA COSTO ESTANDAR-->

             <div class="form-group row">

                <div class="col-xs-12 col-sm-6">
                
                  <div class="input-group">
                  
                    <span class="input-group-addon"><i class="fa fa-arrow-up"></i></span> 

                    <input type="number" class="form-control input-lg" id="editarCostoEstandar" name="editarCostoEstandar" min="0" step="any" required>

                  </div>

                </div>

                <!-- ENTRADA PARA COSTO DE VENTA -->

                <div class="col-xs-12 col-sm-6">
                
                  <div class="input-group">
                  
                    <span class="input-group-addon"><i class="fa fa-arrow-down"></i></span> 

                    <input type="number" class="form-control input-lg" id="editarCostoVenta" name="editarCostoVenta" min="0" step="any" readonly required>

                  </div>
                
                  <br>

                  <!-- CHECKBOX PARA PORCENTAJE -->

                  <div class="col-xs-6">
                    
                    <div class="form-group">
                      
                      <label>
                        
                        <input type="checkbox" class="minimal porcentaje" checked>
                        Utilizar procentaje
                      </label>

                    </div>

                  </div>

                  <!-- ENTRADA PARA PORCENTAJE -->

                  <div class="col-xs-6" style="padding:0">
                    
                    <div class="input-group">
                      
                      <input type="number" class="form-control input-lg nuevoPorcentaje" min="0" value="40" required>

                      <span class="input-group-addon"><i class="fa fa-percent"></i></span>

                    </div>

                  </div>

                </div>

            </div>

            <!-- ENTRADA PARA SUBIR FOTO -->

             <div class="form-group">
              
              <div class="panel">SUBIR IMAGEN</div>

              <input type="file" class="nuevaImagen" name="editarImagen">

              <p class="help-block">Peso máximo de la imagen 2MB</p>

              <img src="vistas/img/productos/default/anonymous.png" class="img-thumbnail previsualizar" width="100px">

              <input type="hidden" name="imagenActual" id="imagenActual">

            </div>

          </div>

        </div>

        <!--=====================================
        PIE DEL MODAL
        ======================================-->

        <div class="modal-footer">

          <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Salir</button>

          <button type="submit" class="btn btn-primary">Guardar cambios</button>

        </div>

      </form>

      <?php    

          $editarProducto = new ControladorProductos();
          $editarProducto -> ctrEditarProducto();

      ?>

    </div>

  </div>

</div>
<?php    

  $eliminarProducto = new ControladorProductos();
  $eliminarProducto -> ctrEliminarProducto();

?>