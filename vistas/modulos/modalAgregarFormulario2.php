
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
    #tableCardXS td {
        min-width: 60px; /* Establece el ancho mínimo de las celdas a 60px */
    }
</style>
<div id="modalAgregarFormularioXS" class="modal fade" role="dialog">
    <div class="modal-dialog" id="modalContentXS">
        <div class="modal-content">
            <form id="formXS" role="form" method="post">
                <div class="modal-header" style="background:#3c8dbc; color:white">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Carta X-S</h4>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <!-- Tabla para los datos -->
                        <div class="button-container">
                            <button id="addSampleXS" type="button" class="btn btn-primary">Agregar muestra</button>
                            <button id="addHourXS" type="button" class="btn btn-primary">Agregar hora</button>
                        </div>
                        <table id="tableCardXS" class="table table-bordered">
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
                                <td><input type="number" class="form-control" name="sample_weightXS[]"></td>
                                <td><input type="number" class="form-control" name="sample_weightXS[]"></td>

                            </tr>
                            <!-- Agrega más filas según el número de muestras -->
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Salir</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                    <button type="button" class="btn btn-default" data-toggle="modal" data-target="#modalGraficasXS">Abrir gráficas</button>
                    <div class="button-container">
                        <button id="deleteAllXS" type="button" class="btn btn-danger">Eliminar todo</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para las gráficas -->
<div id="modalGraficasXS" class="modal fade" role="dialog">
    <div class="modal-dialog" id="modalContentXS2">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Gráficas</h4>
            </div>
            <div class="modal-body">
                <!-- Aquí va el contenido de las gráficas -->
                <table id="lcTableXS" class="table table-bordered">
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
                <input type="number" id="hourInputXS" placeholder="Ingrese el número de hora">
                <canvas id="myChartXS"></canvas>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                <div class="button-container">
                <button type="button" class="btn btn-primary" id="downloadPDFXS">PDF</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    var sampleCountXS = 0;

    $(document).ready(function() {
        $("#modalContentXS").draggable({
            handle: ".modal-content"
        });
        $("#modalContentXS2").draggable({
            handle: ".modal-content"
        });

    });

    $(document).ready(function() {

        $('#addSampleXS').click(function() {
            var table = $('#tableCardXS');
            var numHours = table.find('tr')[0].cells.length - 4;
            var newRow = $('<tr>');
            sampleCountXS++;
            newRow.append('<td>Muestra ' + sampleCountXS + '</td>');

            for (var i = 0; i < numHours; i++) {
                newRow.append('<td><input type="number" class="form-control" name="sample_weightXS[]"></td>');
            }
            newRow.append('<td><input type="number" class="form-control" readonly></td>'); // Campo de Media no editable
            newRow.append('<td><input type="number" class="form-control" readonly></td>'); // Campo de Rango no editable


            table.append(newRow);
        });

        $('#addHourXS').click(function() {
            var table = $('#tableCardXS');
            var numHours = table.find('tr')[0].cells.length - 4;
            if (numHours >= 10) {
                return;
            }

            table.find('tr').each(function(index, row) {
                if (index == 0) {
                    $('<th>Hora ' + (numHours + 1) + '</th>').insertBefore($(row).find('th').eq(-3)); // Insertamos antes de la celda de Media
                } else {
                    $('<td><input type="number" class="form-control" name="sample_weightXS[]"></td>').insertBefore($(row).find('td').eq(-2)); // Insertamos antes de la celda de Media
                }
            });

        });
    });

    $(document).ready(function() {
        $('#formXS').on('submit', function(event) {
            event.preventDefault();

            var data = [];
            var user_id = 1; // Reemplaza esto con el id de usuario correcto


            $('#tableCardXS tbody tr').each(function() {
                var row = $(this);
                row.find('input[name="sample_weightXS[]"]').each(function(index) {
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
                cards_xs: data,
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
            fetch(url_prod + 'quality/xs/saveAll', { // Reemplaza esto con la URL de tu API
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
        fetch(url_prod + 'quality/xs/getAll?userId=' + <?php echo $_SESSION['id'] ?>, { // Reemplaza esto con la URL de tu endpoint
            method: 'GET',
            headers: {
                'Content-Type': 'application/json'
            }
        })
            .then(response => response.json())
            .then(data => {
                fillModalXS(data);
                fillGraphModalXS(data);
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


    function fillModalXS(data) {
        var table = $('#tableCardXS');
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
        headerRow.append('<th>Media</th><th>Desviación</th><th>Rango</th>'); // Agregamos encabezados para Media y Rango
        thead.empty().append(headerRow);

        // Creamos las filas de la tabla
        for (var i = 1; i <= maxSamples; i++) {
            var row = $('<tr>');
            sampleCountXS++;
            row.append('<td>Muestra ' + i + '</td>');

            var sampleData = data.filter(item => item.sample === i);
            var average = sampleData[0].average;
            var stddev = sampleData[0].deviations.toFixed(3);
            var range = sampleData[0].range;

            for (var j = 1; j <= maxHours; j++) {
                var item = data.find(item => item.sample === i && item.hours === j);
                var weight = item ? item.weight : '';
                var id = item ? item.id : '';
                if(id == '' || id == null){
                    row.append('<td><input type="number" class="form-control" name="sample_weightXS[]"></td>');
                }else{
                    row.append('<td><input type="number" class="form-control" id=sample_weightXS_' + id +' name="sample_weightXS[]" value="' + weight + '"></td>');
                }
            }
            row.append('<td>' + average + '</td><td>' + stddev + '</td><td>' + range + '</td>'); // Agregamos las celdas de Media, Desviación y Rango
            tbody.append(row);
        }
    }

    $(document).ready(function() {
        // Selecciona el contenedor de los campos de entrada y asigna el controlador de eventos a los campos de entrada actuales y futuros
        $('#tableCardXS').on('change', 'input[id^="sample_weightXS_"]', function() {
            // Recoge el 'id' y el nuevo valor del campo de entrada
            var id = this.id.replace('sample_weightXS_', '');
            var newValue = this.value;

            // Haz una solicitud POST a tu endpoint con el 'id' y el nuevo valor como datos
            fetch(url_prod + 'quality/xs/update', { // Reemplaza esto con la URL de tu API
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

    var myChartXS;

    function fillGraphModalXS(data) {
        var lcTableXS = $('#lcTableXS');
        var tbody = lcTableXS.find('tbody');
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
        row.append('<td id="lcsValueXS">' + lcs + '</td><td id="lcValueXS">' + lc + '</td><td id="lciValueXS">' + lci + '</td>'); // Agregamos las celdas de LCS, LC y LCI
        tbody.append(row);

        var ctxs = document.getElementById('myChartXS').getContext('2d');
        if (myChartXS) {
            myChartXS.destroy();
        }
        myChartXS = new Chart(ctxs, {
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
                        data: Array(samples.length).fill(lc),
                        fill: false,
                        borderColor: 'rgb(0,42,255)',
                        borderDash: [5, 5],
                        tension: 0.1
                    },
                    {
                        label: 'LCI',
                        data: Array(samples.length).fill(lc),
                        fill: false,
                        borderColor: 'rgb(26,255,0)',
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
        $('#deleteAllXS').click(function() {
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
                url: url_prod + "quality/xs/deleteAll?userId=" + <?php echo $_SESSION['id'] ?>, // Cambia esto por la URL de tu API
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

    document.getElementById('downloadPDFXS').addEventListener('click', function() {
        var doc = new jsPDF('p', 'mm', 'a4'); // A4 size page of PDF

        html2canvas(document.getElementById('modalAgregarFormularioXS'), {
            onrendered: function(canvas1) {
                var img1 = canvas1.toDataURL("image/png");
                var imgProps1 = doc.getImageProperties(img1);
                var pdfWidth1 = doc.internal.pageSize.getWidth();
                var pdfHeight1 = (imgProps1.height * pdfWidth1) / imgProps1.width;
                doc.addImage(img1, 'PNG', 0, 0, pdfWidth1, pdfHeight1);

                html2canvas(document.getElementById('modalGraficasXS'), {
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
        $('#hourInputXS').change(function() {
            var hour = $(this).val();
            var constant = constantsTable[hour]; // Busca el valor en la tabla de constantes

            var totalStdDev = 0;
            var numSamples = 0;
            $('#tableCardXS tbody tr').each(function() {
                var stddev = parseFloat($(this).find('td:nth-last-child(3)').text()); // Asume que la desviación estándar está en la tercera última columna
                if (!isNaN(stddev)) {
                    totalStdDev += stddev;
                    numSamples++;
                }
            });

            var avgStdDev = totalStdDev / numSamples; // Calcula la desviación estándar promedio

            var lc = parseFloat($('#lcValueXS').text());
            var lcs = lc + (constant.a2 * avgStdDev);
            var lci = lc - (constant.a2 * avgStdDev);

            $('#lcsValueXS').text(lcs.toFixed(3));
            $('#lciValueXS').text(lci.toFixed(3));

            var existingData = myChartXS.data.datasets[0].data;

            myChartXS.data.datasets[2].data = Array(existingData.length).fill(lcs); // Combina el primer elemento con los nuevos valores
            myChartXS.data.datasets[3].data = Array(existingData.length).fill(lci); // Combina el primer elemento con los nuevos valores

            myChartXS.update(); // Actualiza la gráfica
        });
    });

</script>