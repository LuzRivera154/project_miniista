<?php

$subida_exitosa = false;
$archivo_subido = [];
$codigo_error   = null;

if (isset($_FILES["archivo"]) && isset($_POST["autor"])) {

    $codigo_error    = $_FILES["archivo"]["error"];
    $autor           = $_POST["autor"];
    $descripcion     = isset($_POST["descripcion"]) ? substr($_POST["descripcion"], 0, 150) : "";
    $ruta_temporal   = $_FILES["archivo"]["tmp_name"];
    $nombre_original = $_FILES["archivo"]["name"];
    $fecha           = date("YmdHis");

    // Formato: 20250601144419-pierre-chat.png
    $nuevo_nombre    = "$fecha-$autor-$nombre_original";
    $carpeta_destino = __DIR__ . "/uploads/photos/";

    if ($codigo_error === UPLOAD_ERR_OK) {
        $subida_exitosa = move_uploaded_file($ruta_temporal, $carpeta_destino . $nuevo_nombre);

        if ($subida_exitosa) {
            // Guardamos descripción en .txt hermano para poder leerla en index.php
            if ($descripcion !== "") {
                file_put_contents($carpeta_destino . $nuevo_nombre . ".txt", $descripcion);
            }

            $archivo_subido = [
                "archivo"       => $nuevo_nombre,
                "nombre"        => $nombre_original,
                "autor"         => $autor,
                "descripcion"   => $descripcion,
                "fecha_raw"     => $fecha,
                "fecha_legible" => date_create_from_format('YmdHis', $fecha)->format('d/m/Y H:i:s'),
                "src"           => "uploads/photos/$nuevo_nombre",
            ];
        }
    }
}

$mensajes_error = [
    UPLOAD_ERR_INI_SIZE   => "Archivo muy grande (límite php.ini)",
    UPLOAD_ERR_FORM_SIZE  => "Archivo muy grande (límite formulario)",
    UPLOAD_ERR_PARTIAL    => "Upload incompleto",
    UPLOAD_ERR_NO_FILE    => "No se seleccionó ningún archivo",
    UPLOAD_ERR_NO_TMP_DIR => "Falta carpeta temporal de PHP",
    UPLOAD_ERR_CANT_WRITE => "No se pudo escribir en disco",
];
$texto_error = isset($mensajes_error[$codigo_error]) ? $mensajes_error[$codigo_error] : "Error $codigo_error";

?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Photo – Mini Insta</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <header>
        <h1>MINI INSTA</h1>
    </header>

    <main class="pagina-upload">

        <?php if ($subida_exitosa): ?>

            <h2 class="titulo-exito">Upload réussi !</h2>
            <div class="tarjeta-foto">
                <img src="<?= htmlspecialchars($archivo_subido["src"]) ?>" alt="<?= htmlspecialchars($archivo_subido["archivo"]) ?>">
                <p class="tarjeta-nombre"><?= htmlspecialchars($archivo_subido["archivo"]) ?></p>
                <p class="tarjeta-fecha">
                    <?= htmlspecialchars($archivo_subido["fecha_raw"]) ?> —
                    <?= htmlspecialchars($archivo_subido["fecha_legible"]) ?>
                </p>
                <?php if ($archivo_subido["descripcion"]): ?>
                    <p class="tarjeta-descripcion"><?= htmlspecialchars($archivo_subido["descripcion"]) ?></p>
                <?php endif; ?>
            </div>


            <a class="btn-accion" href="index.php">Retour à l'accueil</a>

        <?php else: ?>

            <h2 class="titulo-form">Ajouter une photo</h2>

            <form action="upload-photo.php" method="post" enctype="multipart/form-data" id="form-foto">
                <input id="campo-foto" type="file" name="archivo" accept="image/*">
                <label id="label-foto" class="btn-archivo" for="campo-foto">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z" />
                        <circle cx="12" cy="13" r="4" />
                    </svg>
                    Choisir une photo
                </label>
                <span id="nombre-foto" class="nombre-archivo">Aucun fichier</span>

                <input type="text" name="autor" placeholder="Votre nom" required>
                
                <textarea name="descripcion" class="campo-descripcion" placeholder="Description (optionnel)..." maxlength="150"></textarea>
                <span class="contador-chars"><span id="contador">0</span> / 150</span>

                <button type="submit" class="btn-accion">Envoyer</button>
            </form>

        <?php endif; ?>

    </main>

    <nav class="nav-bottom">
        <a href="index.php" class="nav-icono" title="Accueil">
            <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="currentColor">
                <path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z" />
            </svg>
        </a>
        <a href="upload-photo.php" class="nav-icono activo" title="Ajouter une photo">
            <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z" />
                <circle cx="12" cy="13" r="4" />
            </svg>
        </a>
        <a href="upload-video.php" class="nav-icono" title="Ajouter une vidéo">
            <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polygon points="23 7 16 12 23 17 23 7" />
                <rect x="1" y="5" width="15" height="14" rx="2" ry="2" />
            </svg>
        </a>
    </nav>

    <script>
        var inputFoto = document.getElementById("campo-foto");
        var nombreFoto = document.getElementById("nombre-foto");
        var formFoto = document.getElementById("form-foto");
        var textarea = document.querySelector("textarea[name='descripcion']");
        var contadorSpan = document.getElementById("contador");

        // Contador de caracteres del textarea
        if (textarea && contadorSpan) {
            textarea.addEventListener("input", function() {
                contadorSpan.textContent = textarea.value.length;
            });
        }

        // Muestra el nombre del archivo elegido y loguea toda la info
        if (inputFoto) {
            inputFoto.addEventListener("change", function() {
                var archivo = inputFoto.files[0];
                if (archivo) {
                    nombreFoto.textContent = archivo.name;
                    console.log("--- Archivo seleccionado ---");
                    console.log("Archivo:", archivo);
                    console.log("Nombre:", archivo.name);
                    console.log("Tamaño:", archivo.size, "bytes");
                    console.log("Tipo MIME:", archivo.type);
                    console.log("Última modificación:", new Date(archivo.lastModified).toLocaleString());
                } else {
                    nombreFoto.textContent = "Aucun fichier";
                }
            });
        }

        if (formFoto) {
            formFoto.addEventListener("submit", function(e) {
                if (!inputFoto.files || inputFoto.files.length === 0) {
                    e.preventDefault();
                    console.error("Formulario bloqueado: no se seleccionó ninguna foto");
                    alert("Elegi una foto !");
                    return;
                }
                var archivo = inputFoto.files[0];
                var autor = document.querySelector("input[name='autor']").value;
                console.log("--- Enviando formulario ---");
                console.log("Archivo:", archivo.name);
                console.log("Autor:", autor);
                console.log("Nombre nuevo que PHP generará:", new Date().toISOString().replace(/\D/g, "").slice(0, 14) + "-" + autor + "-" + archivo.name);
            });
        }

        <?php if (!$subida_exitosa && $codigo_error !== null): ?>
            console.error("--- Upload fallido (PHP) ---");
            console.error("Código de error:", <?= json_encode($codigo_error) ?>, "→ <?= $texto_error ?>");
            console.log("$_FILES completo:", <?= json_encode($_FILES) ?>);
            console.log("$_POST completo:", <?= json_encode($_POST) ?>);
        <?php endif; ?>
        <?php if ($subida_exitosa): ?>
            console.log("--- Upload exitoso (PHP) ---");
            console.log("Nombre nuevo:", <?= json_encode($archivo_subido["archivo"]) ?>);
            console.log("Ruta web:", <?= json_encode($archivo_subido["src"]) ?>);
            console.log("Autor:", <?= json_encode($archivo_subido["autor"]) ?>);
        <?php endif; ?>
    </script>

</body>

</html>