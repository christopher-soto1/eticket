<?php

// Ruta dinámica al archivo dentro de public/uploads
$ruta = __DIR__ . "/../../public/uploads/plantilla_desarrollo_proyecto.docx";

if (!file_exists($ruta)) {
    die("El archivo no existe en el servidor.");
}

$nombreDescarga = "plantilla_desarrollo_proyecto.docx";

header("Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document");
header("Content-Disposition: attachment; filename=\"" . $nombreDescarga . "\"");
header("Content-Length: " . filesize($ruta));

readfile($ruta);
exit;
