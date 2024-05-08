
<div id="modalAgregarFormularioC" class="modal fade" role="dialog">
    <div class="modal-dialog" id="modalContentC">
        <div class="modal-content">
            <form id="formC" role="form" method="post">
                <div class="modal-header" style="background:#3c8dbc; color:white">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Carta C</h4>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <!-- Tabla para los datos -->
                        <div class="button-container">
                            <button id="addMonthC" type="button" class="btn btn-primary">Agregar mes</button>
                        </div>
                        <table id="tableCardC" class="table table-bordered">
                            <thead>
                            <tr>
                                <th>Mes</th>
                                <th>Intoxicados / ci</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>Mes 1</td>
                                <td><input type="number" class="form-control" name="month_new[]"></td>
                            </tr>
                            <!-- Agrega más filas según el número de muestras -->
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Salir</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                    <button type="button" class="btn btn-default" data-toggle="modal" data-target="#modalGraficasC">Abrir gráficas</button>
                    <div class="button-container">
                        <button id="deleteAllC" type="button" class="btn btn-danger">Eliminar todo</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para las gráficas -->
<div id="modalGraficasC" class="modal fade" role="dialog">
    <div class="modal-dialog" id="modalContentC2">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Gráficas</h4>
            </div>
            <div class="modal-body">
                <!-- Aquí va el contenido de las gráficas -->
                <table id="lcTableC" class="table table-bordered">
                    <thead>
                    <tr>
                        <th>LCS</th>
                        <th>LC</th>
                        <th>LCI</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td id="lcsValueC"></td>
                        <td id="lcValueC"></td>
                        <td id="lciValueC"></td>
                    </tr>
                    </tbody>
                </table>
                <canvas id="ChartC"></canvas>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                <div class="button-container">
                <button type="button" class="btn btn-primary" id="downloadPDFC">PDF</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>

    $(document).ready(function() {
        $("#modalContentC").draggable({
            handle: ".modal-content"
        });
        $("#modalContentC2").draggable({
            handle: ".modal-content"
        });

    });

    $(document).ready(function() {
        $('#addMonthC').click(function() {
            var table = $('#tableCardC');
            var newRow = $('<tr>');
            var monthNumber = table.find('tr').length - 1; // Restamos 1 para excluir la fila del promedio
            newRow.append('<td>Mes ' + monthNumber + '</td>');
            var intoxicatedInput = $('<input type="number" class="form-control new-field" name="month_new[]">');
            newRow.append($('<td>').append(intoxicatedInput));
            table.find('tr:last').before(newRow); // Insertamos la nueva fila antes de la última fila (la fila del promedio)
        });
    });

    $(document).ready(function() {
        $('#formC').on('submit', function(event) {
            event.preventDefault();

            var data = [];
            var user_id = <?php echo $_SESSION['id'] ?>; // Reemplaza esto con el id de usuario correcto

            $('#tableCardC tbody tr').each(function() {
                var row = $(this);
                var monthText = row.find('td:first').first().text();
                var month = parseInt(monthText.replace('Mes ', '')); // Extrae el número del mes del texto
                var intoxicated = parseFloat(row.find('input.new-field').val()); // Solo recoge los datos de los campos de entrada con la clase 'new-field'
                if (!isNaN(month) && !isNaN(intoxicated)) {
                    var card = {
                        month: month,
                        intoxicated: intoxicated
                    };
                    data.push(card);
                }
            });

            var payload = {
                cards_c: data,
                user_id: user_id
            };
            fetch('http://localhost:8080/quality/c/saveAll', { // Reemplaza esto con la URL de tu API
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(payload)
            })
                .then(response => response.json())
                .then(data => {
                    console.log(data);
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
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Hubo un error al procesar la solicitud.'
                    });
                });
        });
    });

    $(document).ready(function() {
        fetch('http://localhost:8080/quality/c/getAll?userId=' + <?php echo $_SESSION['id'] ?>, { // Replace this with the URL of your API
            method: 'GET',
            headers: {
                'Content-Type': 'application/json'
            }
        })
            .then(response => response.json())
            .then(data => {
                var table = $('#tableCardC');
                table.find('tbody').empty();
                var total = 0;
                var count = 0;
                var months = [];
                var intoxications = [];
                data.forEach(item => {
                    var newRow = $('<tr>');
                    newRow.append('<td>Mes ' + item.month + '</td>'); // Assumes that the lot number is the month number
                    newRow.append('<td><input type="number" class="form-control" id="intoxicated_' + item.id + '" name="month_new[]" value="' + item.intoxicated + '"></td>');
                    table.append(newRow);
                    total += item.intoxicated;
                    count++;
                    months.push('Mes ' + item.month);
                    intoxications.push(item.intoxicated);
                });
                var average = total / count;
                var averageRow = $('<tr>');
                averageRow.append('<td>Promedio</td>');
                averageRow.append('<td>' + average.toFixed(2) + '</td>');
                table.append(averageRow);
                fillGraphModalC(data, months, intoxications);
            })
            .catch((error) => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'There was an error processing the request.'
                });
            });
    });

    $(document).ready(function() {
        // Select the container of the input fields and assign the event handler to current and future input fields
        $('#tableCardC').on('change', 'input[id^="intoxicated_"]', function() {
            // Collect the 'id' and new value of the input field
            var id = this.id.replace('intoxicated_', '');
            var newValue = this.value;

            // Make a POST request to your endpoint with the 'id' and new value as data
            fetch('http://localhost:8080/quality/c/update', { // Replace this with the URL of your API
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ id: id, intoxicated: newValue, user_id: <?php echo $_SESSION['id'] ?> }) // Replace 1 with the correct user id
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
                        text: 'There was an error processing the request.'
                    });
                });
        });
    });

    function fillGraphModalC(data, months, intoxications) {
        var lcTableXS = $('#lcTableC'); // Cambia a la tabla correcta
        var tbody = lcTableXS.find('tbody');
        tbody.empty(); // Limpiamos el contenido actual de la tabla

        var totalAverage = 0;
        var numSamples = 0;
        var samples = [];
        var averages = [];

        data.forEach(item => {
            totalAverage += item.intoxicated; // Asegúrate de que estás sumando el campo correcto
            numSamples++;
            if (!samples.includes(item.month)) { // Asegúrate de que estás utilizando el campo correcto
                samples.push(item.month);
                averages.push(item.intoxicated); // Asegúrate de que estás utilizando el campo correcto
            }
        });

        var average = totalAverage / numSamples;

        // Calcula LCS y LCI aquí
        var lcs = average + (3 * Math.sqrt(average)); // Reemplaza 0 con el cálculo de LCS
        var lci = average - (3 * Math.sqrt(average)); // Reemplaza 0 con el cálculo de LCI

        var row = $('<tr>');
        row.append('<td>' + lcs.toFixed(2) + '</td><td>' + average.toFixed(2) + '</td><td>' + lci.toFixed(2) + '</td>'); // Agregamos las celdas de LCS, LC y LCI
        tbody.append(row);

        var ctx = document.getElementById('ChartC').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: months,
                datasets: [{
                    label: 'Intoxicados',
                    data: intoxications,
                    borderColor: 'rgb(75, 192, 192)',
                    fill: false
                }, {
                    label: 'LCS',
                    data: Array(months.length).fill(lcs),
                    borderColor: 'rgb(255, 0, 0)',
                    borderDash: [5, 5],
                    fill: false
                }, {
                    label: 'LC',
                    data: Array(months.length).fill(average),
                    borderColor: 'rgb(0, 0, 255)',
                    borderDash: [5, 5],
                    fill: false
                }, {
                    label: 'LCI',
                    data: Array(months.length).fill(lci),
                    borderColor: 'rgb(0, 255, 0)',
                    borderDash: [5, 5],
                    fill: false
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
        // Resto del código para generar el gráfico...
    }
    // Agrega este código en tu archivo JavaScript
    $(document).ready(function() {
        $('#deleteAllC').click(function() {
            $.ajax({
                url: "http://localhost:8080/quality/c/deleteAll?userId=" + <?php echo $_SESSION['id'] ?>, // Cambia esto por la URL de tu API
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

    document.getElementById('downloadPDFC').addEventListener('click', function() {
        var doc = new jsPDF('p', 'mm', 'a4'); // A4 size page of PDF

        html2canvas(document.getElementById('modalAgregarFormularioC'), {
            onrendered: function(canvas1) {
                var img1 = canvas1.toDataURL("image/png");
                var imgProps1 = doc.getImageProperties(img1);
                var pdfWidth1 = doc.internal.pageSize.getWidth();
                var pdfHeight1 = (imgProps1.height * pdfWidth1) / imgProps1.width;
                doc.addImage(img1, 'PNG', 0, 0, pdfWidth1, pdfHeight1);

                html2canvas(document.getElementById('modalGraficasC'), {
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