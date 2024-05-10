
<div id="modalAgregarFormularioP" class="modal fade" role="dialog">
    <div class="modal-dialog" id="modalContentP">
        <div class="modal-content">
            <form id="formP" role="form" method="post">
                <div class="modal-header" style="background:#3c8dbc; color:white">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Carta P</h4>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <!-- Tabla para los datos -->
                        <div class="button-container">
                            <button id="addSampleP" type="button" class="btn btn-primary">Agregar muestra</button>
                        </div>
                        <table id="tableCardP" class="table table-bordered">
                            <thead>
                            <tr>
                                <th>Muestra</th>
                                <th>Tamaño lote / ni</th>
                                <th>Articulos defectuosos / di</th>
                                <th>Proporción / pi</th>

                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>Muestra 1</td>
                                <td><input type="number" class="form-control" name="sample_lot_size_new[]"></td>
                                <td><input type="number" class="form-control" name="sample_defective_items_new[]"></td>
                                <td><input type="number" class="form-control" name="sample_proportion_new[]" readonly></td>

                            </tr>
                            <!-- Agrega más filas según el número de muestras -->
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Salir</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                    <button type="button" class="btn btn-default" data-toggle="modal" data-target="#modalGraficasP">Abrir gráficas</button>
                    <div class="button-container">
                        <button id="deleteAllP" type="button" class="btn btn-danger">Eliminar todo</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para las gráficas -->
<div id="modalGraficasP" class="modal fade" role="dialog">
    <div class="modal-dialog" id="modalContentP2">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Gráficas</h4>
            </div>
            <div class="modal-body">
                <!-- Aquí va el contenido de las gráficas -->
                <table id="lcTableP" class="table table-bordered">
                    <thead>
                    <tr>
                        <th>LCS</th>
                        <th>LC</th>
                        <th>LCI</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td id="lcsValueP"></td>
                        <td id="lcValueP"></td>
                        <td id="lciValueP"></td>
                    </tr>
                    </tbody>
                </table>
                <canvas id="proportionChart"></canvas>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                <div class="button-container">
                <button type="button" class="btn btn-primary" id="downloadPDFP">PDF</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>

    $(document).ready(function() {
        $("#modalContentP").draggable({
            handle: ".modal-content"
        });
        $("#modalContentP2").draggable({
            handle: ".modal-content"
        });

    });

    $(document).ready(function() {
        function calculateProportion(lotSizeInput, defectiveItemsInput, proportionInput) {
            var lotSize = parseFloat(lotSizeInput.val());
            var defectiveItems = parseFloat(defectiveItemsInput.val());
            if (!isNaN(lotSize) && !isNaN(defectiveItems) && lotSize !== 0) {
                var proportion = (defectiveItems / lotSize).toFixed(3);
                proportionInput.val(proportion).change();
            } else {
                proportionInput.val('').change();
            }
        }

        $('#addSampleP').click(function() {
            var table = $('#tableCardP');
            var totalRow = table.find('tr:last'); // Encuentra la fila de totales
            var newRow = $('<tr>');
            newRow.append('<td>Muestra ' + (table.find('tr').length - 1) + '</td>'); // Asume que el número de la muestra es el número de filas existentes
            var lotSizeInput = $('<input type="number" class="form-control" name="sample_lot_size_new[]">');
            var defectiveItemsInput = $('<input type="number" class="form-control" name="sample_defective_items_new[]">');
            var proportionInput = $('<input type="number" class="form-control" name="sample_proportion_new[]" readonly>');
            newRow.append($('<td>').append(lotSizeInput));
            newRow.append($('<td>').append(defectiveItemsInput));
            newRow.append($('<td>').append(proportionInput));
            table.append(newRow);

            totalRow.before(newRow); // Agrega la nueva fila antes de la fila de totales

            // Controlador de eventos para actualizar la proporción cuando se cambian los valores de tamaño lote y artículos defectuosos
            lotSizeInput.add(defectiveItemsInput).on('input', function() {
                calculateProportion(lotSizeInput, defectiveItemsInput, proportionInput);
            });
        });

        // Aplica el controlador de eventos a los campos de entrada existentes
        $('input[name="sample_lot_size[]"], input[name="sample_defective_items[]"]').on('input', function() {
            var row = $(this).closest('tr');
            var lotSizeInput = row.find('input[name="sample_lot_size[]"]');
            var defectiveItemsInput = row.find('input[name="sample_defective_items[]"]');
            var proportionInput = row.find('input[name="sample_proportion[]"]');
            calculateProportion(lotSizeInput, defectiveItemsInput, proportionInput);
        });
        $('#tableCardP').on('input', 'input[name="sample_lot_size[]"], input[name="sample_defective_items[]"]', function() {
            var row = $(this).closest('tr');
            var lotSizeInput = row.find('input[name="sample_lot_size[]"]');
            var defectiveItemsInput = row.find('input[name="sample_defective_items[]"]');
            var proportionInput = row.find('input[name="sample_proportion[]"]');
            calculateProportion(lotSizeInput, defectiveItemsInput, proportionInput);
        });
    });

    $(document).ready(function() {
        $('#formP').on('submit', function(event) {
            event.preventDefault();

            var data = [];
            var user_id = <?php echo $_SESSION['id'] ?>; // Reemplaza esto con el id de usuario correcto

            $('#tableCardP tbody tr').each(function() {
                var row = $(this);
                if (row.find('input:not([id])').length > 0) {
                    var sample = row.index() + 1;
                }
                var lotSize = parseFloat(row.find('input[name="sample_lot_size_new[]"]').val());
                var defectiveItems = parseFloat(row.find('input[name="sample_defective_items_new[]"]').val());
                var proportion = parseFloat(row.find('input[name="sample_proportion_new[]"]').val());
                if (!isNaN(lotSize) && lotSize !== 0 && !isNaN(defectiveItems) && defectiveItems !== 0 && !isNaN(proportion) && proportion !== 0) {
                    var card = {
                        sample: sample,
                        lot_size: lotSize,
                        defective_items: defectiveItems,
                        proportion: proportion
                    };
                    data.push(card);
                }
            });

            var payload = {
                cards_p: data,
                user_id: user_id
            };

            Swal.fire({
                title: 'Cargando...',
                allowEscapeKey: false,
                allowOutsideClick: false,
                showConfirmButton: false,
                onOpen: () => {
                    Swal.showLoading();
                }
            });
            fetch(url_prod + 'quality/p/saveAll', { // Reemplaza esto con la URL de tu API
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(payload)
            })
                .then(response => response.json())
                .then(data => {
                    console.log(data);
                    Swal.close();
                    Swal.fire({
                        icon: 'success',
                        title: '¡Buen trabajo!',
                        text: 'Los datos se han guardado correctamente.'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            location.reload();
                        }
                    });
                })
                .catch((error) => {
                    console.error('Error:', error);
                    Swal.close();
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Hubo un error al procesar la solicitud.'
                    });
                });
        });
    });

    $(document).ready(function() {
        fetch(url_prod + 'quality/p/getAll?userId=' + <?php echo $_SESSION['id'] ?>, { // Reemplaza esto con la URL de tu API
            method: 'GET',
            headers: {
                'Content-Type': 'application/json'
            }
        })
            .then(response => response.json())
            .then(data => {
                fillTableP(data);
                calculateLCSLCILCI();
                fillProportionChart(data);
            })
            .catch((error) => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Hubo un error al procesar la solicitud.'
                });
            });
    });

    function fillTableP(data) {
        var table = $('#tableCardP');
        var tbody = table.find('tbody');
        tbody.empty(); // Limpiamos el contenido actual de la tabla

        var maxSamples = Math.max(...data.map(item => item.sample)); // Obtenemos la cantidad máxima de muestras
        var totalLotSize = 0;
        var totalDefectiveItems = 0;
        var totalProportion = 0;
        // Creamos las filas de la tabla
        for (var i = 1; i <= maxSamples; i++) {
            var sampleData = data.filter(item => item.sample === i);
            var newRow = $('<tr>');
            newRow.append('<td id="sample_id[]" value="' + sampleData[0].id + '">Muestra ' + i + '</td>');
            var lotSize = sampleData[0].lot_size;
            var defectiveItems = sampleData[0].defective_items;
            var proportion = sampleData[0].proportion;
            var lotSizeInput = $('<input type="number" class="form-control" id="sample_lot_size[]" name="sample_lot_size[]">').val(sampleData[0].lot_size);
            var defectiveItemsInput = $('<input type="number" class="form-control" id="sample_defective_items[]" name="sample_defective_items[]">').val(sampleData[0].defective_items);
            var proportionInput = $('<input type="number" class="form-control" id="sample_proportion[]" name="sample_proportion[]" readonly>').val(sampleData[0].proportion);
            newRow.append($('<td>').append(lotSizeInput));
            newRow.append($('<td>').append(defectiveItemsInput));
            newRow.append($('<td>').append(proportionInput));
            tbody.append(newRow);

            totalLotSize += lotSize;
            totalDefectiveItems += defectiveItems;
            totalProportion += proportion;
        }

        var totalRow = $('<tr>');
        totalRow.append('<td>Total</td>');
        totalRow.append('<td>' + totalLotSize.toFixed(2) + '</td>');
        totalRow.append('<td>' + totalDefectiveItems.toFixed(2) + '</td>');
        totalRow.append('<td>' + totalProportion.toFixed(3) + '</td>');
        tbody.append(totalRow);

    }

    $(document).ready(function() {
        $('#tableCardP').on('change', 'input[id^="sample_"]', function() {
            var row = $(this).closest('tr');
            var sampleText = row.find('td:first').first().text();
            var sample = parseInt(sampleText.replace('Muestra ', '')); // Extrae el número de la muestra del texto
            var id = parseInt(row.find('td:first').attr('value'));
            var lotSizeInput = row.find('input[name="sample_lot_size[]"]');
            var defectiveItemsInput = row.find('input[name="sample_defective_items[]"]');
            var proportionInput = row.find('input[name="sample_proportion[]"]');
            var user_id = 1; // Reemplaza esto con el id de usuario correcto

            var payload = {
                id: id,
                sample: sample,
                user_id: <?php echo $_SESSION['id'] ?>
            };
            if (this === lotSizeInput[0]) {
                payload.lot_size = parseFloat(lotSizeInput.val());
            } else if (this === defectiveItemsInput[0]) {
                payload.defective_items = parseFloat(defectiveItemsInput.val());
            } else if (this === proportionInput[0]) {
                payload.proportion = parseFloat(proportionInput.val());
            }

                fetch(url_prod + 'quality/p/update', { // Reemplaza esto con la URL de tu API
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(payload)
                })
                    .then(response => response.json())
                    .then(data => {
                        console.log(data);
                    })
                    .catch((error) => {
                        console.error('Error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Hubo un error al procesar la solicitud.'
                        });
                    });
        });
    });

    var lc;
    var lcs;
    var lci;



    function calculateLCSLCILCI() {
        var totalLotSize = 0;
        var totalDefectiveItems = 0;
        var numSamples = 0;

        $('#tableCardP tbody tr').each(function() {
            var row = $(this);
            var lotSize = parseFloat(row.find('input[name="sample_lot_size[]"]').val());
            var defectiveItems = parseFloat(row.find('input[name="sample_defective_items[]"]').val());
            if (!isNaN(lotSize) && !isNaN(defectiveItems)) {
                totalLotSize += lotSize;
                totalDefectiveItems += defectiveItems;
                numSamples++;
            }
        });

        lc = totalDefectiveItems / totalLotSize;
        lcs = lc + (3 * Math.sqrt((lc * (1 - lc)) / (totalLotSize / numSamples)));
        lci = lc - (3 * Math.sqrt((lc * (1 - lc)) / (totalLotSize / numSamples)));
        $('#lcsValueP').text(lcs.toFixed(3));
        $('#lcValueP').text(lc.toFixed(3));
        $('#lciValueP').text(lci.toFixed(3));
    }

    var proportionChart;

    function fillProportionChart(data) {


        var samples = data.map((item, index) => 'Muestra ' + (index + 1));
        var proportions = data.map(item => item.proportion);

        var lcsData = Array(samples.length).fill(lcs);
        var lcData = Array(samples.length).fill(lc);
        var lciData = Array(samples.length).fill(lci);

        var ctxp = document.getElementById('proportionChart').getContext('2d');
        if (proportionChart) {
            proportionChart.destroy();
        }
        proportionChart = new Chart(ctxp, {
            type: 'line',
            data: {
                labels: samples,
                datasets: [{
                    label: 'Proporción de cada muestra',
                    data: proportions,
                    fill: false,
                    borderColor: 'rgb(75, 192, 192)',
                    borderDash: [5, 5],
                    tension: 0.1
                },
                    {
                        label: 'LCS',
                        data: lcsData,
                        fill: false,
                        borderColor: 'rgb(255, 200, 200)',
                        borderDash: [5, 5],
                        tension: 0.1
                    },
                    {
                        label: 'LC',
                        data: lcData,
                        fill: false,
                        borderColor: 'rgb(200, 255, 200)',
                        borderDash: [5, 5],
                        tension: 0.1
                    },
                    {
                        label: 'LCI',
                        data: lciData,
                        fill: false,
                        borderColor: 'rgb(200, 200, 255)',
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
                            text: 'Proporción'
                        }
                    }
                }
            }
        });
    }

    // Agrega este código en tu archivo JavaScript
    $(document).ready(function() {
        $('#deleteAllP').click(function() {
            Swal.fire({
                title: 'Cargando...',
                allowEscapeKey: false,
                allowOutsideClick: false,
                showConfirmButton: false,
                onOpen: () => {
                    Swal.showLoading();
                }
            });
            $.ajax({
                url: url_prod + "quality/p/deleteAll?userId=" + <?php echo $_SESSION['id'] ?>, // Cambia esto por la URL de tu API
                type: "DELETE",
                success: function(response){
                    console.log(response);
                    Swal.close();
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
                    Swal.close();
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Hubo un error al procesar la solicitud.'
                    });
                }
            });
        });
    });

    document.getElementById('downloadPDFP').addEventListener('click', function() {
        var doc = new jsPDF('p', 'mm', 'a4'); // A4 size page of PDF

        html2canvas(document.getElementById('modalAgregarFormularioP'), {
            onrendered: function(canvas1) {
                var img1 = canvas1.toDataURL("image/png");
                var imgProps1 = doc.getImageProperties(img1);
                var pdfWidth1 = doc.internal.pageSize.getWidth();
                var pdfHeight1 = (imgProps1.height * pdfWidth1) / imgProps1.width;
                doc.addImage(img1, 'PNG', 0, 0, pdfWidth1, pdfHeight1);

                html2canvas(document.getElementById('modalGraficasP'), {
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

</script>