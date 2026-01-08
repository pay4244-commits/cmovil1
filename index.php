<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CMovil - Panel de Control y Simulador</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .status-charging { color: green; font-weight: bold; }
        .status-discharging { color: orange; }
    </style>
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="row">
        <div class="col-md-12 mb-4">
            <h1 class="text-center">CMovil Dashboard</h1>
            <p class="text-center text-muted">Monitorización de dispositivos y Simulador Web</p>
        </div>
    </div>

    <!-- Simulador -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Simulador de Dispositivo Android</h5>
        </div>
        <div class="card-body">
            <p>Utiliza este botón para enviar datos de prueba a la API (<code>api.php</code>) como si fuera la App móvil.</p>
            <div class="row g-3 align-items-center">
                <div class="col-auto">
                    <label for="deviceId" class="col-form-label">ID Dispositivo:</label>
                </div>
                <div class="col-auto">
                    <input type="text" id="deviceId" class="form-control" value="SIMULADOR_WEB_01">
                </div>
                <div class="col-auto">
                    <button id="btnSimulate" class="btn btn-success" onclick="simulateData()">
                        <span id="spinner" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                        Generar y Enviar Datos
                    </button>
                </div>
                <div class="col-auto">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="autoSimulate">
                        <label class="form-check-label" for="autoSimulate">Envío Automático (cada 5s)</label>
                    </div>
                </div>
            </div>
            <div id="simResult" class="mt-2"></div>
        </div>
    </div>

    <!-- Tabla de Datos -->
    <div class="card">
        <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Datos Recibidos</h5>
            <button class="btn btn-sm btn-outline-light" onclick="location.reload()">Actualizar Tabla</button>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Dispositivo</th>
                            <th>Modelo</th>
                            <th>Batería</th>
                            <th>Cargando</th>
                            <th>Ubicación (Lat, Lon)</th>
                            <th>Fecha Registro</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        require 'db_connect.php';
                        try {
                            $stmt = $conn->query("SELECT * FROM device_data ORDER BY id DESC LIMIT 20");
                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                $batteryClass = $row['is_charging'] ? 'status-charging' : 'status-discharging';
                                $chargingIcon = $row['is_charging'] ? '⚡' : '';
                                echo "<tr>";
                                echo "<td>{$row['id']}</td>";
                                echo "<td>{$row['device_id']}</td>";
                                echo "<td>{$row['brand']} {$row['model']}</td>";
                                echo "<td><div class='progress' style='height: 20px;'><div class='progress-bar' role='progressbar' style='width: {$row['battery_level']}%;' aria-valuenow='{$row['battery_level']}' aria-valuemin='0' aria-valuemax='100'>{$row['battery_level']}%</div></div></td>";
                                echo "<td class='{$batteryClass}'>{$chargingIcon} " . ($row['is_charging'] ? 'Sí' : 'No') . "</td>";
                                echo "<td>{$row['latitude']}, {$row['longitude']}</td>";
                                echo "<td>{$row['timestamp']}</td>";
                                echo "</tr>";
                            }
                        } catch(PDOException $e) {
                            echo "<tr><td colspan='7' class='text-danger'>Error de conexión: " . $e->getMessage() . "</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    let autoInterval = null;

    document.getElementById('autoSimulate').addEventListener('change', function() {
        if(this.checked) {
            simulateData(); // Send one immediately
            autoInterval = setInterval(simulateData, 5000);
        } else {
            clearInterval(autoInterval);
        }
    });

    function simulateData() {
        const btn = document.getElementById('btnSimulate');
        const spinner = document.getElementById('spinner');
        const resultDiv = document.getElementById('simResult');
        const deviceId = document.getElementById('deviceId').value;

        btn.disabled = true;
        spinner.classList.remove('d-none');

        // Generate random data
        const data = {
            device_id: deviceId,
            phone_number: "555-000-" + Math.floor(Math.random() * 9999),
            model: "Chrome Browser",
            brand: "Google",
            os_version: "Web Simulator",
            battery_level: Math.floor(Math.random() * 100),
            is_charging: Math.random() < 0.5,
            latitude: 19.4326 + (Math.random() - 0.5) * 0.01, // Random variation around Mexico City
            longitude: -99.1332 + (Math.random() - 0.5) * 0.01,
            altitude: 2240 + Math.random() * 10,
            timestamp: new Date().toISOString().slice(0, 19).replace('T', ' ')
        };

        fetch('api.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(data => {
            resultDiv.innerHTML = `<div class="alert alert-success py-1">Datos enviados correctamente! ID: ${deviceId}</div>`;
            // Optional: Auto reload table if not in auto mode to avoid flicker
            if(!document.getElementById('autoSimulate').checked) {
                setTimeout(() => location.reload(), 1000);
            }
        })
        .catch(error => {
            resultDiv.innerHTML = `<div class="alert alert-danger py-1">Error: ${error}</div>`;
        })
        .finally(() => {
            btn.disabled = false;
            spinner.classList.add('d-none');
        });
    }
</script>

</body>
</html>
