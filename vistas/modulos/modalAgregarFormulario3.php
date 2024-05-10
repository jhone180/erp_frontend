
<!-- Formulario 1 -->
<style>
    .button-container {
        display: flex;
        justify-content: center;
        gap: 10px; /* Espacio entre los botones */
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
    #tableCardXRM td {
        min-width: 60px; /* Establece el ancho mínimo de las celdas a 60px */
    }
</style>
<div id="modalAgregarFormularioXRM" class="modal fade" role="dialog">
    <div class="modal-dialog" id="modalContentXRM">
        <div class="modal-content">
            <form id="formXRM" role="form" method="post">
                <div class="modal-header" style="background:#3c8dbc; color:white">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Carta X-Rm</h4>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <!-- Tabla para los datos -->
                        <div class="button-container">
                            <button id="addSampleXRM" type="button" class="btn btn-primary">Agregar muestra</button>
                            <button id="addHourXRM" type="button" class="btn btn-primary">Agregar hora</button>
                        </div>
                        <table id="tableCardXRM" class="table table-bordered">
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
                                <td><input type="number" class="form-control" name="sample_weightXRM[]"></td>
                                <td><input type="number" class="form-control" name="sample_weightXRM[]"></td>

                            </tr>
                            <!-- Agrega más filas según el número de muestras -->
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Salir</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                    <button type="button" class="btn btn-default" data-toggle="modal" data-target="#modalGraficasXRM">Abrir gráficas</button>
                    <div class="button-container">
                        <button id="deleteAllXRM" type="button" class="btn btn-danger">Eliminar todo</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para las gráficas -->
<div id="modalGraficasXRM" class="modal fade" role="dialog">
    <div class="modal-dialog" id="modalContentXRM2">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Gráficas</h4>
            </div>
            <div class="modal-body">
                <!-- Aquí va el contenido de las gráficas -->
                <table id="lcTableXRM" class="table table-bordered">
                    <thead>
                    <tr>
                        <th>LCS</th>
                        <th>LC</th>
                        <th>LCI</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td id="lcsValueXRM"></td>
                        <td id="lcValueXRM"></td>
                        <td id="lciValueXRM"></td>
                    </tr>
                    </tbody>
                </table>
                <input type="number" id="hourInputXRM" placeholder="Ingrese el número de hora">
                <canvas id="myChartXRM"></canvas>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                <div class="button-container">
                <button type="button" class="btn btn-primary" id="downloadPDFXRM">PDF</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    var sampleCountXRM = 0;

    $(document).ready(function() {
        $("#modalContentXRM").draggable({
            handle: ".modal-content"
        });
        $("#modalContentXRM2").draggable({
            handle: ".modal-content"
        });

    });

    $(document).ready(function() {

        $('#addSampleXRM').click(function() {
            var table = $('#tableCardXRM');
            var numHours = table.find('tr')[0].cells.length - 3;
            var newRow = $('<tr>');
            sampleCountXRM++;
            newRow.append('<td>Muestra ' + sampleCountXRM + '</td>');

            for (var i = 0; i < numHours; i++) {
                newRow.append('<td><input type="number" class="form-control" name="sample_weightXRM[]"></td>');
            }
            newRow.append('<td><input type="number" class="form-control" readonly></td>'); // Campo de Media no editable
            newRow.append('<td><input type="number" class="form-control" readonly></td>'); // Campo de Rango no editable


            table.append(newRow);
        });

        $('#addHourXRM').click(function() {
            var table = $('#tableCardXRM');
            var numHours = table.find('tr')[0].cells.length - 3;
            if (numHours >= 10) {
                return;
            }

            table.find('tr').each(function(index, row) {
                if (index == 0) {
                    $('<th>Hora ' + (numHours + 1) + '</th>').insertBefore($(row).find('th').eq(-2)); // Insertamos antes de la celda de Media
                } else {
                    $('<td><input type="number" class="form-control" name="sample_weightXRM[]"></td>').insertBefore($(row).find('td').eq(-2)); // Insertamos antes de la celda de Media
                }
            });

        });
    });

    $(document).ready(function() {
        $('#formXRM').on('submit', function(event) {
            event.preventDefault();

            var data = [];
            var user_id = 1; // Reemplaza esto con el id de usuario correcto


            $('#tableCardXRM tbody tr').each(function() {
                var row = $(this);
                row.find('input[name="sample_weightXRM[]"]').each(function(index) {
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
                cards_xrm: data,
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
            fetch(url_prod + 'quality/xrm/saveAll', { // Reemplaza esto con la URL de tu API
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
        fetch(url_prod + 'quality/xrm/getAll?userId=' + <?php echo $_SESSION['id'] ?>, { // Reemplaza esto con la URL de tu endpoint
            method: 'GET',
            headers: {
                'Content-Type': 'application/json'
            }
        })
            .then(response => response.json())
            .then(data => {
                fillModalXRM(data);
                fillGraphModalXRM(data);
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


    function fillModalXRM(data) {
        var table = $('#tableCardXRM');
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
            sampleCountXRM++;
            row.append('<td>Muestra ' + i + '</td>');

            var sampleData = data.filter(item => item.sample === i);
            var average = sampleData[0].average;
            var range = sampleData[0].range;

            for (var j = 1; j <= maxHours; j++) {
                var item = data.find(item => item.sample === i && item.hours === j);
                var weight = item ? item.weight : '';
                var id = item ? item.id : '';
                if(id == '' || id == null){
                    row.append('<td><input type="number" class="form-control" name="sample_weightXRM[]"></td>');
                }else{
                    row.append('<td><input type="number" class="form-control" id=sample_weightXRM_' + id +' name="sample_weightXRM[]" value="' + weight + '"></td>');
                }
            }
            row.append('<td>' + average + '</td><td>' + range + '</td>'); // Agregamos las celdas de Media y Rango
            tbody.append(row);
        }
    }

    $(document).ready(function() {
        // Selecciona el contenedor de los campos de entrada y asigna el controlador de eventos a los campos de entrada actuales y futuros
        $('.table').on('change', 'input[id^="sample_weightXRM_"]', function() {
            // Recoge el 'id' y el nuevo valor del campo de entrada
            var id = this.id.replace('sample_weightXRM_', '');
            var newValue = this.value;

            // Haz una solicitud POST a tu endpoint con el 'id' y el nuevo valor como datos
            fetch(url_prod + 'quality/xrm/update', { // Reemplaza esto con la URL de tu API
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

    var myChartXRM;

    function fillGraphModalXRM(data) {
        var lcTableXRM = $('#lcTableXRM');
        var tbody = lcTableXRM.find('tbody');
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
        row.append('<td id="lcsValueXRM">' + lcs + '</td><td id="lcValueXRM">' + lc + '</td><td id="lciValueXRM">' + lci + '</td>'); // Agregamos las celdas de LCS, LC y LCI
        tbody.append(row);

        var ctxrm = document.getElementById('myChartXRM').getContext('2d');
        if (myChartXRM) {
            myChartXRM.destroy();
        }
        myChartXRM = new Chart(ctxrm, {
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
                        borderColor: 'rgb(255, 0, 0)',
                        borderDash: [5, 5],
                        tension: 0.1
                    },
                    {
                        label: 'LCS',
                        data: Array(samples.length).fill(0),
                        fill: false,
                        borderColor: 'rgb(0,21,255)',
                        borderDash: [5, 5],
                        tension: 0.1
                    },
                    {
                        label: 'LCI',
                        data: Array(samples.length).fill(0),
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

    // Agrega este código en tu archivo JavaScript
    $(document).ready(function() {
        $('#deleteAllXRM').click(function() {
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
                url: url_prod + "quality/xrm/deleteAll?userId=" + <?php echo $_SESSION['id'] ?>, // Cambia esto por la URL de tu API
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

    document.getElementById('downloadPDFXRM').addEventListener('click', function() {
        var doc = new jsPDF('p', 'mm', 'a4'); // A4 size page of PDF

        html2canvas(document.getElementById('modalAgregarFormularioXRM'), {
            onrendered: function(canvas1) {
                var img1 = canvas1.toDataURL("image/png");
                var imgProps1 = doc.getImageProperties(img1);
                var pdfWidth1 = doc.internal.pageSize.getWidth();
                var pdfHeight1 = (imgProps1.height * pdfWidth1) / imgProps1.width;
                doc.addImage(img1, 'PNG', 0, 0, pdfWidth1, pdfHeight1);

                html2canvas(document.getElementById('modalGraficasXRM'), {
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
        $('#hourInputXRM').change(function() {
            var hour = $(this).val();
            var constant = constantsTable[hour]; // Busca el valor en la tabla de constantes

            var totalRange = 0;
            var numSamples = 0;
            $('#tableCardXRM tbody tr').each(function() {
                var range = parseFloat($(this).find('td:last').text());
                if (!isNaN(range)) {
                    totalRange += range;
                    numSamples++;
                }
            });

            var rBar = totalRange / numSamples; // Calcula R-barra
            var lc = parseFloat($('#lcValueXRM').text());
            var lcs = lc + 3 * (rBar / constant.d2);
            var lci = lc - 3 * (rBar / constant.d2);

            $('#lcsValueXRM').text(lcs.toFixed(3));
            $('#lciValueXRM').text(lci.toFixed(3));

            var existingData = myChartXRM.data.datasets[0].data;

            myChartXRM.data.datasets[2].data = Array(existingData.length).fill(lcs);
            myChartXRM.data.datasets[3].data = Array(existingData.length).fill(lci);

            myChartXRM.update();
        });
    });

</script>