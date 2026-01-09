<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instalar CMovil</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; padding: 20px; background-color: #f0f2f5; }
        .container { background: white; max-width: 500px; margin: 0 auto; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { color: #333; }
        #qrcode { margin: 20px auto; display: flex; justify-content: center; }
        .file-list { text-align: left; margin-top: 20px; }
        .file-item { padding: 10px; border-bottom: 1px solid #eee; cursor: pointer; display: flex; justify-content: space-between; align-items: center; }
        .file-item:hover { background-color: #f9f9f9; }
        .btn { background-color: #007bff; color: white; border: none; padding: 5px 10px; border-radius: 5px; cursor: pointer; }
        .alert { background-color: #fff3cd; color: #856404; padding: 10px; border-radius: 5px; margin-bottom: 20px; font-size: 0.9em; }
    </style>
</head>
<body>

<div class="container">
    <h1>Instalar App CMovil</h1>
    
    <div class="alert">
        <strong>Instrucciones:</strong><br>
        1. Descarga el APK desde GitHub Actions.<br>
        2. Coloca el archivo .apk en la carpeta <code>cmovil1/apk/</code>.<br>
        3. Escanea el código QR abajo para instalar.
    </div>

    <div id="qrcode"></div>
    <h3 id="current-file">Selecciona un archivo abajo</h3>

    <div class="file-list">
        <h3>Archivos Disponibles:</h3>
        <?php
        $dir = "apk/";
        $files = glob($dir . "*.apk");
        
        // Function to get local IP
        function getLocalIp() {
            $ip = getHostByName(getHostName());
            // If it returns localhost, try a different method or default to a placeholder
            if ($ip == '127.0.0.1' || $ip == '::1') {
                // Try to get from server var, though often 127.0.0.1 in local env
                $ip = $_SERVER['SERVER_ADDR'];
            }
            return $ip;
        }
        
        $serverIp = getLocalIp();
        $port = $_SERVER['SERVER_PORT'];
        $baseUrl = "http://$serverIp" . ($port != 80 ? ":$port" : "") . "/cmovil1/";

        if (count($files) > 0) {
            foreach ($files as $file) {
                $filename = basename($file);
                $fullUrl = $baseUrl . $file;
                echo "<div class='file-item' onclick='generateQR(\"$fullUrl\", \"$filename\")'>";
                echo "<span>$filename</span>";
                echo "<button class='btn'>Generar QR</button>";
                echo "</div>";
            }
        } else {
            echo "<p>No hay archivos .apk en la carpeta /apk</p>";
        }
        ?>
    </div>
</div>

<script>
    var qrcode = new QRCode(document.getElementById("qrcode"), {
        width: 200,
        height: 200
    });

    function generateQR(url, filename) {
        qrcode.clear();
        qrcode.makeCode(url);
        document.getElementById("current-file").innerText = "Escanea para descargar: " + filename;
        console.log("QR generado para: " + url);
    }

    // Auto-select first file if available
    <?php if (count($files) > 0): ?>
        generateQR("<?php echo $baseUrl . $files[0]; ?>", "<?php echo basename($files[0]); ?>");
    <?php endif; ?>
</script>

</body>
</html>
