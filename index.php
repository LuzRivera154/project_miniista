<?php

function leer_carpeta($ruta_relativa, $tipo)
{
    $lista   = [];
    $carpeta = opendir(__DIR__ . $ruta_relativa);

    while ($nombre = readdir($carpeta)) {
        if ($nombre === "." || $nombre === "..") {
            continue;
        }
        if (str_ends_with($nombre, '.txt')) {
            continue;
        }

        $partes = explode('-', $nombre, 3); // [fecha, autor, nombreoriginal]

        if (count($partes) === 3) {
        
            $ruta_txt    = __DIR__ . $ruta_relativa . "/$nombre.txt";
            $descripcion = file_exists($ruta_txt) ? trim(file_get_contents($ruta_txt)) : "";

            $lista[] = [
                "fecha"       => $partes[0],
                "autor"       => $partes[1],
                "nombre"      => $partes[2],
                "archivo"     => $nombre,
                "tipo"        => $tipo,
                "src"         => ltrim($ruta_relativa, '/') . "/$nombre",
                "descripcion" => $descripcion,
            ];
        }
    }

    closedir($carpeta);
    return $lista;
}

$fotos  = leer_carpeta("/uploads/photos", "foto");
$videos = leer_carpeta("/uploads/videos", "video");

$publicaciones = array_merge($fotos, $videos);
usort($publicaciones, function ($a, $b) {
    return strcmp($b["fecha"], $a["fecha"]);
});

?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mini Insta</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <header>
        <h1>MINI INSTA</h1>
    </header>

    <main>
        <div class="galeria">
            <?php foreach ($publicaciones as $pub): ?>
                <div class="tarjeta-foto">
                    <div class="tarjeta-autor"><?= htmlspecialchars($pub["autor"]) ?></div>

                    <?php if ($pub["tipo"] === "video"): ?>
                        <video controls>
                            <source src="<?= htmlspecialchars($pub["src"]) ?>">
                        </video>
                    <?php else: ?>
                        <img src="<?= htmlspecialchars($pub["src"]) ?>" alt="<?= htmlspecialchars($pub["nombre"]) ?>">
                    <?php endif; ?>

                    <p class="tarjeta-nombre"><?= htmlspecialchars($pub["archivo"]) ?></p>

                    <p class="tarjeta-fecha">
                        <?= htmlspecialchars($pub["fecha"]) ?> —
                        <?= date_create_from_format('YmdHis', $pub["fecha"])->format('d/m/Y H:i:s') ?>
                    </p>
                    <?php if ($pub["descripcion"]): ?>
                        <p class="tarjeta-descripcion"><?= htmlspecialchars($pub["descripcion"]) ?></p>
                    <?php endif; ?>

                </div>
            <?php endforeach; ?>
        </div>
    </main>

    <nav class="nav-bottom">
        <a href="index.php" class="nav-icono activo" title="Accueil">
            <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="currentColor">
                <path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z" />
            </svg>
        </a>
        <a href="upload-photo.php" class="nav-icono" title="Ajouter une photo">
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

</body>

</html>