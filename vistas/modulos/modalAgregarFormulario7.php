<div id="modalAgregarFormularioU" class="modal fade" role="dialog">
    <div class="modal-dialog" id="modalContentU">
        <div class="modal-content">
            <form id="formU" role="form" method="post">
                <div class="modal-header" style="background:#3c8dbc; color:white">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Carta C</h4>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <!-- Tabla para los datos -->
                        <div class="button-container">
                            <button id="addLotU" type="button" class="btn btn-primary">Agregar lote</button>
                        </div>
                        <table id="tableCardU" class="table table-bordered">
                            <thead>
                            <tr>
                                <th>Lote</th>
                                <th>Tamaño de muestra / ni</th>
                                <th>Total de defectos / ci</th>
                                <th># Promedio de defectos por circuito / ui</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Salir</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                    <button type="button" class="btn btn-default" data-toggle="modal" data-target="#modalGraficasU">Abrir gráficas</button>
                    <div class="button-container">
                        <button id="deleteAllU" type="button" class="btn btn-danger">Eliminar todo</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para las gráficas -->
<div id="modalGraficasU" class="modal fade" role="dialog">
    <div class="modal-dialog" id="modalContentU2">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Gráficas</h4>
            </div>
            <div class="modal-body">
                <!-- Aquí va el contenido de las gráficas -->
                <table id="lcTableU" class="table table-bordered">
                    <thead>
                    <tr>
                        <th>LCS</th>
                        <th>LC</th>
                        <th>LCI</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td id="lcsValueU"></td>
                        <td id="lcValueU"></td>
                        <td id="lciValueU"></td>
                    </tr>
                    </tbody>
                </table>
                <canvas id="ChartU"></canvas>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                <div class="button-container">
                    <button type="button" class="btn btn-primary" id="downloadPDFU">PDF</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>

    $(document).ready(function() {
        $("#modalContentU").draggable({
            handle: ".modal-content"
        });
        $("#modalContentU2").draggable({
            handle: ".modal-content"
        });

    });

    $(document).ready(function() {

        // Evento de cambio para los campos 'sample_size_new' y 'totally_defective_new'
        $('#tableCardU').on('change', '.sample_size_new, .totally_defective_new', function() {
            var $row = $(this).closest('tr');
            var sampleSize = parseFloat($row.find('.sample_size_new').val());
            var totalDefects = parseFloat($row.find('.totally_defective_new').val());

            if (!isNaN(sampleSize) && !isNaN(totalDefects) && sampleSize !== 0) {
                var averageDefects = totalDefects / sampleSize;
                $row.find('.average_number_defects_circuit_new').val(averageDefects.toFixed(3));
            }
        });
    });

    $(document).ready(function() {
        // ... el resto de tu código ...

        $('#formU').on('submit', function(e) {
            e.preventDefault();

            var cards_u = [];
            $('#tableCardU tbody tr.new_row').each(function() {
                var lot = $(this).find('td:first').text().split(' ')[1];
                var sample_size = $(this).find('.sample_size_new').val();
                var totally_defective = $(this).find('.totally_defective_new').val();
                var average_number_defects_circuit = $(this).find('.average_number_defects_circuit_new').val();


                if (!isNaN(lot) && !isNaN(sample_size) && !isNaN(totally_defective) && !isNaN(average_number_defects_circuit)) {
                    cards_u.push({
                        lot: parseInt(lot),
                        sample_size: parseInt(sample_size),
                        totally_defective: parseInt(totally_defective),
                        average_number_defects_circuit: parseFloat(average_number_defects_circuit)
                    });
                }
            });

            var data = {
                cards_u: cards_u,
                user_id: <?php echo $_SESSION['id'] ?> // reemplaza esto con el ID de usuario real
            };

            $.ajax({
                url: url_prod + 'quality/u/saveAll', // reemplaza esto con la URL de tu API
                type: 'POST',
                data: JSON.stringify(data),
                contentType: 'application/json',
                success: function(response) {
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
                error: function(jqXHR, textStatus, errorThrown) {
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

    $(document).ready(function() {
        var lotNumber;
        $.ajax({
            url: url_prod + 'quality/u/getAll?userId=' + <?php echo $_SESSION['id'] ?>, // reemplaza esto con la URL de tu API
            type: 'GET',
            success: function(response) {
                response.forEach(function(item) {
                    var newRow = '<tr data-id="' + item.id + '">' +
                        '<td>Lote ' + (item.lot || 'N/A') + '</td>' +
                        '<td><input type="number" class="form-control sample_size_new existing_field" name="sample_size_new[]" value="' + (item.sample_size || '') + '"></td>' +
                        '<td><input type="number" class="form-control totally_defective_new existing_field" name="totally_defective_new[]" value="' + (item.totally_defective || '') + '"></td>' +
                        '<td><input type="number" class="form-control average_number_defects_circuit_new existing_field" name="average_number_defects_circuit_new[]" value="' + (item.average_number_defects_circuit || '') + '" readonly></td>' +
                        '</tr>';

                    $('#tableCardU tbody').append(newRow);
                });

                lotNumber = $('#tableCardU tbody tr').length + 1;
                recogerDatos();
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error(textStatus, errorThrown);
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Hubo un error al cargar los datos.'
                });
            }
        });

        $('#addLotU').click(function() {
            var newRow = '<tr class="new_row">' + // Agrega la clase 'new_row' a las filas nuevas
                '<td>Lote ' + lotNumber + '</td>' +
                '<td><input type="number" class="form-control sample_size_new" name="sample_size_new[]"></td>' +
                '<td><input type="number" class="form-control totally_defective_new" name="totally_defective_new[]"></td>' +
                '<td><input type="number" class="form-control average_number_defects_circuit_new" name="average_number_defects_circuit_new[]" readonly></td>' +
                '</tr>';

            $('#tableCardU tbody').append(newRow);
            lotNumber++;
        });
    });

    $(document).ready(function() {
        // ... el resto de tu código ...

        // Evento de cambio para los campos 'sample_size_new', 'totally_defective_new' y 'average_number_defects_circuit_new'
        $('#tableCardU').on('change', '.sample_size_new, .totally_defective_new, .average_number_defects_circuit_new', function() {
            if ($(this).hasClass('existing_field')) {
            var $row = $(this).closest('tr');
            var id = $row.data('id'); // Asegúrate de que cada fila tenga un atributo de datos 'data-id' con el ID correspondiente
            var lot = $row.find('td:first').text().split(' ')[1];
            var sample_size = $row.find('.sample_size_new').val();
            var totally_defective = $row.find('.totally_defective_new').val();
            var average_number_defects_circuit = $row.find('.average_number_defects_circuit_new').val();

            var data = {
                id: parseInt(id),
                lot: parseInt(lot),
                sample_size: parseInt(sample_size),
                totally_defective: parseInt(totally_defective),
                average_number_defects_circuit: parseFloat(average_number_defects_circuit),
                user_id: <?php echo $_SESSION['id'] ?> // reemplaza esto con el ID de usuario real
            };

            $.ajax({
                url: url_prod + 'quality/u/update', // reemplaza esto con la URL de tu API
                type: 'POST',
                data: JSON.stringify(data),
                contentType: 'application/json',
                success: function (response) {
                    console.log(response);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.error(textStatus, errorThrown);
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Hubo un error al actualizar los datos.'
                    });
                }
            });
        }
        });
    });

    function recogerDatos() {
        // Recoge los datos de la tabla
        var data = [];
        var lotNumbers = [];
        var countChartU = 0;
        var averageDefects = [];
        $('#modalAgregarFormularioU #tableCardU tbody tr').each(function() {
            var sampleSize = parseFloat($(this).find('.sample_size_new').val());
            var lotNumber = "Lote " + countChartU++; // Asegúrate de que este selector coincida con el campo del número de lote
            var totalDefects = parseFloat($(this).find('.totally_defective_new').val());
            var averageDefectsData = parseFloat($(this).find('.average_number_defects_circuit_new').val());
            if (!isNaN(sampleSize) && !isNaN(totalDefects)) {
                data.push({ defects: totalDefects, units: sampleSize });
                lotNumbers.push(lotNumber);
                averageDefects.push(averageDefectsData);
            }
        });

        var totalDefects = 0;
        var totalUnits = 0;

        data.forEach(function(item) {
            totalDefects += item.defects;
            totalUnits += item.units;
        });

        var uBar = totalDefects / totalUnits;
        var n = data.length;

        var LC = uBar;
        var LCS = uBar + 3 * Math.sqrt(uBar / n);
        var LCI = Math.max(0, uBar - 3 * Math.sqrt(uBar / n)); // LCI no puede ser negativo

        // Actualiza la tabla
        $('#modalGraficasU #lcValueU').text(LC.toFixed(3));
        $('#modalGraficasU #lcsValueU').text(LCS.toFixed(3));
        $('#modalGraficasU #lciValueU').text(LCI.toFixed(3));
        var ctxU = document.getElementById('ChartU').getContext('2d');
        new Chart(ctxU, {
            type: 'line',
            data: {
                labels: lotNumbers,
                datasets: [{
                    label: 'Promedio de defectos por circuito',
                    data: averageDefects,
                    borderColor: 'rgb(75, 192, 192)',
                    fill: false
                }, {
                    label: 'LCS',
                    data: Array(lotNumbers.length).fill(LCS),
                    borderColor: 'rgb(255, 0, 0)',
                    borderDash: [5, 5],
                    fill: false
                }, {
                    label: 'LC',
                    data: Array(lotNumbers.length).fill(LC),
                    borderColor: 'rgb(0, 0, 255)',
                    borderDash: [5, 5],
                    fill: false
                }, {
                    label: 'LCI',
                    data: Array(lotNumbers.length).fill(LCI),
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
    }

    document.getElementById('downloadPDFU').addEventListener('click', function() {
        var doc = new jsPDF('p', 'mm', 'a4'); // A4 size page of PDF

        html2canvas(document.getElementById('modalAgregarFormularioU'), {
            onrendered: function(canvas1) {
                var img1 = canvas1.toDataURL("image/png");
                var imgProps1 = doc.getImageProperties(img1);
                var pdfWidth1 = doc.internal.pageSize.getWidth();
                var pdfHeight1 = (imgProps1.height * pdfWidth1) / imgProps1.width;
                doc.addImage(img1, 'PNG', 0, 0, pdfWidth1, pdfHeight1);

                html2canvas(document.getElementById('modalGraficasU'), {
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
        $('#deleteAllU').click(function() {
            $.ajax({
                url: url_prod + "quality/u/deleteAll?userId=" + <?php echo $_SESSION['id'] ?>, // Cambia esto por la URL de tu API
                type: "DELETE",
                success: function(response){
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

</script>