<div id="modalAgregarFormularioNP" class="modal fade" role="dialog">
    <div class="modal-dialog" id="modalContentNP">
        <div class="modal-content">
            <form id="formNP" role="form" method="post">
                <div class="modal-header" style="background:#3c8dbc; color:white">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Carta np</h4>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <!-- Tabla para los datos -->
                        <div class="button-container">
                            <button id="addLotNP" type="button" class="btn btn-primary">Agregar lote</button>
                        </div>
                        <table id="tableCardNP" class="table table-bordered">
                            <thead>
                            <tr>
                                <th>Lote</th>
                                <th>Articulos defectuosos en la muestra</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>Lote 1</td>
                                <td><input type="number" class="form-control" name="defective_items_sample_new[]"></td>
                            </tr>
                            <!-- Agrega más filas según el número de muestras -->
                            </tbody>
                            <tfoot>
                            <tr>
                                <th>Total</th>
                                <th id="totalDefectiveItems"></th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Salir</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                    <button type="button" class="btn btn-default" data-toggle="modal" data-target="#modalGraficasNP">Abrir gráficas</button>
                    <div class="button-container">
                        <button id="deleteAllNP" type="button" class="btn btn-danger">Eliminar todo</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para las gráficas -->
<div id="modalGraficasNP" class="modal fade" role="dialog">
    <div class="modal-dialog" id="modalContentNP2">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Gráficas</h4>
            </div>
            <div class="modal-body">
                <!-- Aquí va el contenido de las gráficas -->
                <table id="lcTableNP" class="table table-bordered">
                    <thead>
                    <tr>
                        <th>LCS</th>
                        <th>LC</th>
                        <th>LCI</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td id="lcsValueNP"></td>
                        <td id="lcValueNP"></td>
                        <td id="lciValueNP"></td>
                    </tr>
                    </tbody>
                </table>
                <input type="number" id="inputTamañoMuestra" placeholder="Ingrese el número de muestras / piezas">
                <canvas id="ChartNP"></canvas>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                <div class="button-container">
                <button type="button" class="btn btn-primary" id="downloadPDFNP">PDF</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>

    $(document).ready(function() {
        $("#modalContentNP").draggable({
            handle: ".modal-content"
        });
        $("#modalContentNP2").draggable({
            handle: ".modal-content"
        });

    });

    $(document).ready(function(){
        var lotNumber = 2; // Comienza en 2 porque ya tienes un lote en tu tabla

        $("#addLotNP").click(function(){
            var newRow = '<tr><td>Lote ' + lotNumber + '</td><td><input type="number" class="form-control" name="defective_items_sample_new[]"></td></tr>';
            $("#tableCardNP tbody").append(newRow);
            lotNumber++;
        });
    });

    $("#formNP").submit(function(e){
        e.preventDefault();

        var cards_np = [];
        $("#tableCardNP tbody tr.new-lot").each(function(){
            var lot = $(this).find("td:first").text().split(" ")[1]; // Obtiene el número del lote
            var defective_items_sample = $(this).find("input").val(); // Obtiene el valor del campo numérico

            cards_np.push({
                "lot": parseInt(lot),
                "defective_items_sample": parseInt(defective_items_sample)
            });
        });

        var data = {
            "cards_np": cards_np,
            "user_id": <?php echo $_SESSION['id'] ?> // Cambia esto por el id del usuario actual
        };

        $.ajax({
            url: url_prod + "quality/np/saveAll", // Cambia esto por la URL de tu API
            type: "POST",
            data: JSON.stringify(data),
            contentType: "application/json",
            success: function(response){
                console.log(response);
                Swal.fire({
                    icon: 'success',
                    title: '¡Buen trabajo!',
                    text: 'Los datos se han guardado correctamente.'
                }).then((result) => {
                    if (result.isConfirmed) {
                        location.reload();
                    }
                });
            },
            error: function(jqXHR, textStatus, errorThrown){
                console.error(textStatus, errorThrown);
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Hubo un error al procesar la solicitud.'
                });
            }
        });
    });

    $(document).ready(function(){
        var lotNumber = 1; // Inicializa lotNumber en 1 por defecto

        $.ajax({
            url: url_prod + "quality/np/getAll?userId=" + <?php echo $_SESSION['id'] ?>, // Cambia esto por la URL de tu API
            type: "GET",
            success: function(response){
                $("#tableCardNP tbody").empty(); // Vacía el cuerpo de la tabla
                response.forEach(function(item){
                    var newRow = '<tr data-id="' + item.id + '"><td>Lote ' + item.lot + '</td><td><input type="number" class="form-control loaded-lot" name="defective_items_sample_new[]" value="' + item.defective_items_sample + '"></td></tr>';
                    $("#tableCardNP tbody").append(newRow);
                    lotNumber = item.lot + 1; // Actualiza lotNumber al número del último lote cargado más uno
                });
                updateTotalDefectiveItems();
                fillGraphModalNP(response);
            },
            error: function(jqXHR, textStatus, errorThrown){
                console.error(textStatus, errorThrown);
            }
        });

        $("#addLotNP").off('click').click(function(){
            var newRow = '<tr class="new-lot"><td>Lote ' + lotNumber + '</td><td><input type="number" class="form-control" name="defective_items_sample_new[]"></td></tr>';
            $("#tableCardNP tbody").append(newRow);
            lotNumber++;
            updateTotalDefectiveItems();
        });
    });

    $(document).ready(function(){
        $("#tableCardNP").on('change', 'input.loaded-lot', function(){
            var row = $(this).closest('tr');
            var id = row.data('id');
            var lot = row.find("td:first").text().split(" ")[1]; // Obtiene el número del lote
            var defective_items_sample = $(this).val(); // Obtiene el valor del campo numérico

            var data = {
                "id": id,
                "lot": parseInt(lot),
                "defective_items_sample": parseInt(defective_items_sample),
                "user_id": <?php echo $_SESSION['id'] ?> // Cambia esto por el id del usuario actual
            };

            $.ajax({
                url: url_prod + "quality/np/update", // Cambia esto por la URL de tu API
                type: "POST",
                data: JSON.stringify(data),
                contentType: "application/json",
                success: function(response){
                    console.log(response);
                },
                error: function(jqXHR, textStatus, errorThrown){
                    console.error(textStatus, errorThrown);
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Hubo un error al procesar la solicitud.'
                    });
                }
            });
            updateTotalDefectiveItems();
        });
    });

    function updateTotalDefectiveItems() {
        var total = 0;
        $("#tableCardNP tbody tr").each(function(){
            var defective_items_sample = $(this).find("input").val(); // Obtiene el valor del campo numérico
            if (defective_items_sample) {
                total += parseInt(defective_items_sample);
            }
        });
        $("#totalDefectiveItems").text(total);
    }

    // Agrega este código en tu archivo JavaScript
    $(document).ready(function() {
        $('#deleteAllNP').click(function() {
            $.ajax({
                url: url_prod + "quality/np/deleteAll?userId=" + <?php echo $_SESSION['id'] ?>, // Cambia esto por la URL de tu API
                type: "DELETE",
                success: function(response){
                    console.log(response);
                    Swal.fire({
                        icon: 'success',
                        title: '¡Buen trabajo!',
                        text: 'Los datos se han eliminado correctamente.'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            location.reload();
                        }
                    });
                },
                error: function(jqXHR, textStatus, errorThrown){
                    console.error(textStatus, errorThrown);
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Hubo un error al procesar la solicitud.'
                    });
                }
            });
        });
    });

    document.getElementById('downloadPDFNP').addEventListener('click', function() {
        var doc = new jsPDF('p', 'mm', 'a4'); // A4 size page of PDF

        html2canvas(document.getElementById('modalAgregarFormularioNP'), {
            onrendered: function(canvas1) {
                var img1 = canvas1.toDataURL("image/png");
                var imgProps1 = doc.getImageProperties(img1);
                var pdfWidth1 = doc.internal.pageSize.getWidth();
                var pdfHeight1 = (imgProps1.height * pdfWidth1) / imgProps1.width;
                doc.addImage(img1, 'PNG', 0, 0, pdfWidth1, pdfHeight1);

                html2canvas(document.getElementById('modalGraficasNP'), {
                    onrendered: function(canvas2) {
                        var img2 = canvas2.toDataURL("image/png");
                        var imgProps2 = doc.getImageProperties(img2);
                        var pdfWidth2 = doc.internal.pageSize.getWidth();
                        var pdfHeight2 = (imgProps2.height * pdfWidth2) / imgProps2.width;
                        doc.addPage();
                        doc.addImage(img2, 'PNG', 0, 0, pdfWidth2, pdfHeight2);

                        doc.save('modals.pdf');
                    }
                });
            }
        });
    });


    $(document).ready(function() {
        $('#inputTamañoMuestra').change(function() {
            var muestra = $(this).val();

            var totalDefectiveItems = 0;
            var numSamples = 0;
            $('#tableCardNP tbody tr').each(function() {
                var defectiveItems = parseFloat($(this).find('td input').val());
                var rowData = [];
                $(this).find('td').each(function() {
                    rowData.push($(this).text());
                });

                if (!isNaN(defectiveItems)) {
                    totalDefectiveItems += defectiveItems;
                    numSamples++;
                }
            });

            var p = totalDefectiveItems / (numSamples * muestra); // Calcula la desviación estándar promedio
            var np = muestra * p;
            var value = np * (1 - p);
            if (value >= 0) {
                var lcs = np + 3 * (Math.sqrt(value));
                var lci = np - 3 * (Math.sqrt(value));
            } else {
                var lcs = 0;
                var lci = 0;
            }

            var lc = p;

            $('#lcsValueNP').text(lcs.toFixed(3));
            $('#lcValueNP').text(lc.toFixed(3));
            $('#lciValueNP').text(lci.toFixed(3));

            var existingData = myChartNP.data.datasets[0].data;

            myChartNP.data.datasets[1].data = Array(existingData.length).fill(lc); // Combina el primer elemento con los nuevos valores
            myChartNP.data.datasets[2].data = Array(existingData.length).fill(lcs); // Combina el primer elemento con los nuevos valores
            myChartNP.data.datasets[3].data = Array(existingData.length).fill(lci); // Combina el primer elemento con los nuevos valores

            myChartNP.update(); // Actualiza la gráfica
        });
    });

    var myChartNP;

    function fillGraphModalNP(response){

        var lots = [];
        var averages = [];

        response.forEach(function(item){
            lots.push("Lote " + item.lot);
            averages.push(item.defective_items_sample);
        });


        var ctxNP = document.getElementById('ChartNP').getContext('2d');
        if (myChartNP) {
            myChartNP.destroy();
        }
        myChartNP = new Chart(ctxNP, {
            type: 'line',
            data: {
                labels: lots,
                datasets: [{
                    label: 'Promedio de cada muestra',
                    data: averages,
                    fill: false,
                    borderColor: 'rgb(75, 192, 192)',
                    tension: 0.1
                },
                    {
                        label: 'LC',
                        data: Array(lots.length).fill(lc),
                        fill: false,
                        borderColor: 'rgb(255, 0, 0)',
                        borderDash: [5, 5],
                        tension: 0.1
                    },
                    {
                        label: 'LCS',
                        data: Array(lots.length).fill(0),
                        fill: false,
                        borderColor: 'rgb(0,21,255)',
                        borderDash: [5, 5],
                        tension: 0.1
                    },
                    {
                        label: 'LCI',
                        data: Array(lots.length).fill(0),
                        fill: false,
                        borderColor: 'rgb(55,255,0)',
                        borderDash: [5, 5],
                        tension: 0.1
                    }]
            },
            options: {
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Número de muestra'
                        }
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Promedio'
                        }
                    }
                }
            }
        });
    }

</script>