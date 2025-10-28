<?php

$templates = [];
$folder_path = __DIR__ . "/templates-base";

foreach (glob("$folder_path/*.html") as $filename) {
	$position = str_replace('.html', '', basename($filename));
	$content = preg_replace( "/\r|\n/", "", file_get_contents($filename));
	$templates[$position] = $content;
}

$target_filename = 'templates.json';
$json = json_encode($templates);
file_put_contents(__DIR__ . "/$target_filename", $json);
printf("Aqruivo %s gerado/atualizado com sucesso!\n", $target_filename);
