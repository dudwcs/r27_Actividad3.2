<?php

function readIniFile($file = __DIR__ . "/db_settings.ini"): array
{
    $settings = parse_ini_file($file, true);

    if ($settings === false) {
        throw new Exception("No se pudo leer el fichero " . $file);
    }

    return $settings;
}

function getConnection(): PDO
{
    $settings = readIniFile();

    $dsn = $settings['database']['driver'] .
        ':host=' . $settings['database']['host'] .
        ((!empty($settings['database']['port']))
            ? (';port=' . $settings['database']['port'])
            : '') .
        ';dbname=' . $settings['database']['schema'] .
        ';charset=utf8mb4';

    $conn = new PDO(
        $dsn,
        $settings['database']['username'],
        $settings['database']['password']
    );

    return $conn;
}