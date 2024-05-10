
<head>
    <!-- ... -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.5.3/jspdf.debug.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.5.0-beta4/html2canvas.min.js"></script>
    <!-- ... -->
</head>
<!-- Formulario 1 -->
<style>
    .button-container {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 10px; /* Espacio entre los botones */
        margin-top: 10px;
        margin-right: 22px;
    }
    input[type="number"]::-webkit-inner-spin-button,
    input[type="number"]::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    input[type="number"] {
        -moz-appearance: textfield;
    }

    .modal-body {
        overflow-x: auto; /* Permite el desplazamiento horizontal */
    }
    .table-responsive {
        min-width: 100%; /* Asegura que la tabla se expanda a todo lo ancho posible */
    }
    #tableCardXR td {
        min-width: 60px; /* Establece el ancho mínimo de las celdas a 60px */
    }
</style>
<div id="modalAgregarFormularioXR" class="modal fade" role="dialog">
    <div class="modal-dialog" id="modalContent">
        <div class="modal-content">
            <form id="formXR" role="form" method="post">
                <div class="modal-header" style="background:#3c8dbc; color:white">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Carta X-R</h4>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <!-- Tabla para los datos -->
                        <div class="button-container">
                            <button id="addSample" type="button" class="btn btn-primary">Agregar muestra</button>
                            <button id="addHour" type="button" class="btn btn-primary">Agregar hora</button>
                        </div>
                        <table id="tableCardXR" class="table table-bordered">
                            <thead>
                            <tr>
                                <th>Muestra / Hora</th>
                                <th>Hora 1</th>
                                <th>Hora 2</th>

                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>Muestra 1</td>
                                <td><input type="number" class="form-control" name="sample_weightXR[]"></td>
                                <td><input type="number" class="form-control" name="sample_weightXR[]"></td>

                            </tr>
                            <!-- Agrega más filas según el número de muestras -->
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Salir</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                    <button type="button" class="btn btn-default pull-right" data-toggle="modal" data-target="#modalGraficas">Abrir gráficas</button>
                    <div class="button-container">
                        <button id="deleteAll" type="button" class="btn btn-danger">Eliminar todo</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para las gráficas -->
<div id="modalGraficas" class="modal fade" role="dialog">
    <div class="modal-dialog" id="modalContent2">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Gráficas</h4>
            </div>
            <div class="modal-body">
                <!-- Aquí va el contenido de las gráficas -->

                <table id="lcTable" class="table table-bordered">
                    <thead>
                    <tr>
                        <th>LCS</th>
                        <th>LC</th>
                        <th>LCI</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td id="lcsValue"></td>
                        <td id="lcValue"></td>
                        <td id="lciValue"></td>
                    </tr>
                    </tbody>
                </table>
                <input type="number" id="hourInput" placeholder="Ingrese el número de hora">
                <canvas id="myChart"></canvas>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                <div class="button-container">
                <button type="button" class="btn btn-primary" id="downloadPDF">PDF</button>
                    </div>
            </div>
        </div>
    </div>
</div>

<script>

    var url_prod = 'https://erpbackend-production-f141.up.railway.app/';

    var constantsTable = {
        1: { a2: 0, a3: 0, d2: 1.128 },
        2: { a2: 1.880, a3: 2.659, d2: 3.686 },
        3: { a2: 1.023, a3: 1.954, d2: 4.358 },
        4: { a2: 0.729, a3: 1.628, d2: 4.698 },
        5: { a2: 0.577, a3: 1.427, d2: 4.918 },
        6: { a2: 0.483, a3: 1.287, d2: 5.079 },
        7: { a2: 0.419, a3: 1.183, d2: 5.204 },
        8: { a2: 0.373, a3: 1.099, d2: 5.307 },
        9: { a2: 0.337, a3: 1.032, d2: 5.394 },
        10: { a2: 0.308, a3: 0.975, d2: 5.469 },
        11: { a2: 0.285, a3: 0.927, d2: 5.535 },
        12: { a2: 0.266, a3: 0.886, d2: 5.594 },
        13: { a2: 0.249, a3: 0.850, d2: 5.647 },
        14: { a2: 0.235, a3: 0.817, d2: 5.696 },
        15: { a2: 0.223, a3: 0.789, d2: 5.740 },
        16: { a2: 0.212, a3: 0.763, d2: 5.782 },
        17: { a2: 0.203, a3: 0.739, d2: 5.820 },
        18: { a2: 0.194, a3: 0.718, d2: 5.856 },
        19: { a2: 0.187, a3: 0.698, d2: 5.889 },
        20: { a2: 0.180, a3: 0.680, d2: 5.921 },
        21: { a2: 0.173, a3: 0.663, d2: 5.951 },
        22: { a2: 0.167, a3: 0.647, d2: 5.979 },
        23: { a2: 0.162, a3: 0.633, d2: 6.006 },
        24: { a2: 0.157, a3: 0.619, d2: 6.032 },
        25: { a2: 0.153, a3: 0.606, d2: 6.056 }
    };
</script>

<script>
    var sampleCountXR = 0;
    var myChart;

    $(document).ready(function() {
        $("#modalContent").draggable({
            handle: ".modal-content"
        });
        $("#modalContent2").draggable({
            handle: ".modal-content"
        });

    });

    $(document).ready(function() {

        $('#addSample').click(function() {
            var table = $('#tableCardXR');
            var numHours = table.find('tr')[0].cells.length - 3;
            var newRow = $('<tr>');
            sampleCountXR++;
            newRow.append('<td>Muestra ' + sampleCountXR + '</td>');

            for (var i = 0; i < numHours; i++) {
                newRow.append('<td><input type="number" class="form-control" name="sample_weightXR[]"></td>');
            }
            newRow.append('<td><input type="number" class="form-control" readonly></td>'); // Campo de Media no editable
            newRow.append('<td><input type="number" class="form-control" readonly></td>'); // Campo de Rango no editable


            table.append(newRow);
        });

        $('#addHour').click(function() {
            var table = $('#tableCardXR');
            var numHours = table.find('tr')[0].cells.length - 3;
            if (numHours >= 10) {
                return;
            }

            table.find('tr').each(function(index, row) {
                if (index == 0) {
                    $('<th>Hora ' + (numHours + 1) + '</th>').insertBefore($(row).find('th').eq(-2)); // Insertamos antes de la celda de Media
                } else {
                    $('<td><input type="number" class="form-control" name="sample_weightXR[]"></td>').insertBefore($(row).find('td').eq(-2)); // Insertamos antes de la celda de Media
                }
            });

        });
    });

    $(document).ready(function() {
        $('#formXR').on('submit', function(event) {
            event.preventDefault();

            var data = [];
            var user_id = 1; // Reemplaza esto con el id de usuario correcto


            $('#tableCardXR tbody tr').each(function() {
                var row = $(this);
                row.find('input[name="sample_weightXR[]"]').each(function(index) {
                    var weight = parseFloat($(this).val());
                    var id = $(this).attr('id');
                    if (!isNaN(weight) && !id) {
                        var card = {
                            hours: index + 1,
                            sample: row.index() + 1,
                            weight: weight
                        };
                        data.push(card);
                    }
                });
            });

            var payload = {
                cards_xr: data,
                user_id: <?php echo $_SESSION['id'] ?>
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

            fetch(url_prod + 'quality/xr/saveAll', { // Reemplaza esto con la URL de tu API
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
        fetch(url_prod + 'quality/xr/getAll?userId=' + <?php echo $_SESSION['id'] ?>, { // Reemplaza esto con la URL de tu endpoint
            method: 'GET',
            headers: {
                'Content-Type': 'application/json'
            }
        })
            .then(response => response.json())
            .then(data => {
                fillModal(data);
                fillGraphModal(data);
            })
            .catch((error) => {
                console.error('Error:', error);
                alert("Hubo un error al procesar la solicitud.");
            });
    });


    function fillModal(data) {
        var table = $('#tableCardXR');
        var tbody = table.find('tbody');
        var thead = table.find('thead');
        tbody.empty(); // Limpiamos el contenido actual de la tabla

        var maxHours = Math.max(...data.map(item => item.hours)); // Obtenemos la cantidad máxima de horas
        var maxSamples = Math.max(...data.map(item => item.sample)); // Obtenemos la cantidad máxima de muestras


        // Creamos el encabezado de la tabla
        var headerRow = $('<tr>');
        headerRow.append('<th>Muestra / Hora</th>');
        for (var h = 1; h <= maxHours; h++) {
            headerRow.append('<th>Hora ' + h + '</th>');
        }
        headerRow.append('<th>Media</th><th>Rango</th>'); // Agregamos encabezados para Media y Rango
        thead.empty().append(headerRow);

        // Creamos las filas de la tabla
        for (var i = 1; i <= maxSamples; i++) {
            var row = $('<tr>');
            sampleCountXR++;
            row.append('<td>Muestra ' + i + '</td>');

            var sampleData = data.filter(item => item.sample === i);
            var average = sampleData[0].average;
            var range = sampleData[0].range;

            for (var j = 1; j <= maxHours; j++) {
                var item = data.find(item => item.sample === i && item.hours === j);
                var weight = item ? item.weight : '';
                var id = item ? item.id : '';
                if(id == '' || id == null){
                    row.append('<td><input type="number" class="form-control" name="sample_weightXR[]"></td>');
                }else{
                    row.append('<td><input type="number" class="form-control" id=sample_weightXR_' + id +' name="sample_weightXR[]" value="' + weight + '"></td>');
                }
            }
            row.append('<td>' + average + '</td><td>' + range + '</td>'); // Agregamos las celdas de Media y Rango
            tbody.append(row);
        }
    }

    $(document).ready(function() {
        // Selecciona el contenedor de los campos de entrada y asigna el controlador de eventos a los campos de entrada actuales y futuros
        $('#tableCardXR').on('change', 'input[id^="sample_weightXR_"]', function() {
            // Recoge el 'id' y el nuevo valor del campo de entrada
            var id = this.id.replace('sample_weightXR_', '');
            var newValue = this.value;

            // Haz una solicitud POST a tu endpoint con el 'id' y el nuevo valor como datos
            fetch(url_prod + 'quality/xr/update', { // Reemplaza esto con la URL de tu API
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ id: id, weight: newValue, user_id: <?php echo $_SESSION['id'] ?> })
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

    function fillGraphModal(data) {
        var lcTable = $('#lcTable');
        var tbody = lcTable.find('tbody');
        tbody.empty(); // Limpiamos el contenido actual de la tabla

        var totalAverage = 0;
        var numSamples = 0;
        var samples = [];
        var averages = [];

        data.forEach(item => {
            totalAverage += item.average;
            numSamples++;
            if (!samples.includes(item.sample)) {
                samples.push(item.sample);
                averages.push(item.average);
            }
        });

        var lc = totalAverage / numSamples;

        // Calcula LCS y LCI aquí
        var lcs = 0; // Reemplaza 0 con el cálculo de LCS
        var lci = 0; // Reemplaza 0 con el cálculo de LCI

        var row = $('<tr>');
        row.append('<td id="lcsValue">' + lcs + '</td><td id="lcValue">' + lc.toFixed(3) + '</td><td id="lciValue">' + lci + '</td>'); // Agregamos las celdas de LCS, LC y LCI
        tbody.append(row);

        var ctx = document.getElementById('myChart').getContext('2d');
        myChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: samples,
                datasets: [{
                    label: 'Promedio de cada muestra',
                    data: averages,
                    fill: false,
                    borderColor: 'rgb(75, 192, 192)',
                    tension: 0.1
                },
                    {
                        label: 'LC',
                        data: Array(samples.length).fill(lc),
                        fill: false,
                        borderColor: 'rgb(0,34,255)',
                        borderDash: [5, 5],
                        tension: 0.1
                    },
                    {
                        label: 'LCS',
                        data: Array(samples.length).fill(lc),
                        fill: false,
                        borderColor: 'rgb(255, 0, 0)',
                        borderDash: [5, 5],
                        tension: 0.1
                    },
                    {
                        label: 'LCI',
                        data: Array(samples.length).fill(lc),
                        fill: false,
                        borderColor: 'rgb(17,255,0)',
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

    // Agrega este código en tu archivo JavaScript
    $(document).ready(function() {
        $('#deleteAll').click(function() {
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
                url: url_prod + "quality/xr/deleteAll?userId=" + <?php echo $_SESSION['id'] ?>, // Cambia esto por la URL de tu API
                type: "DELETE",
                success: function(response){
                    console.log(response);
                    swal.close();
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
                    swal.close();
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Hubo un error al procesar la solicitud.'
                    });
                }
            });
        });
    });

    document.getElementById('downloadPDF').addEventListener('click', function() {
        var doc = new jsPDF('p', 'mm', 'a4'); // A4 size page of PDF

        html2canvas(document.getElementById('modalAgregarFormularioXR'), {
            onrendered: function(canvas1) {
                var img1 = canvas1.toDataURL("image/png");
                var imgProps1 = doc.getImageProperties(img1);
                var pdfWidth1 = doc.internal.pageSize.getWidth();
                var pdfHeight1 = (imgProps1.height * pdfWidth1) / imgProps1.width;
                doc.addImage(img1, 'PNG', 0, 0, pdfWidth1, pdfHeight1);

                html2canvas(document.getElementById('modalGraficas'), {
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
        $('#hourInput').change(function() {
            var hour = $(this).val();
            var constant = constantsTable[hour]; // Busca el valor en la tabla de constantes

            var totalRange = 0;
            var numSamples = 0;
            $('#tableCardXR tbody tr').each(function() {
                var range = parseFloat($(this).find('td:last').text());
                if (!isNaN(range)) {
                    totalRange += range;
                    numSamples++;
                }
            });

            var rBar = totalRange / numSamples; // Calcula R-barra
            var lc = parseFloat($('#lcValue').text());
            var lcs = lc + (constant.a2 * rBar);
            var lci = lc - (constant.a2 * rBar);

            $('#lcsValue').text(lcs.toFixed(3));
            $('#lciValue').text(lci.toFixed(3));

            var existingData = myChart.data.datasets[0].data;

            myChart.data.datasets[2].data = Array(existingData.length).fill(lcs); // Combina el primer elemento con los nuevos valores
            myChart.data.datasets[3].data = Array(existingData.length).fill(lci); // Combina el primer elemento con los nuevos valores

            myChart.update(); // Actualiza la gráfica
        });
    });
</script>