<?php
/*
 * Esse arquivo foi criado para resolver um problema específico. Servidores Linux são case sensitive e arquivos podem ter nomes identicos, variando apenas letras maiúsculas e minúsculas e isso pode ser um problema quando é necessário fazer o download para uma máquina local que não diferencia maiúscula de minúscula.
 * Espero que isso poupe o tempo de alguém :D
 */


$diretorio = "pasta_com_arquivos_repetidos/";

//Essa função lista todos os arquivos de uma pasta
function arquivos($dir)
{
    $diretorio = dir($dir);

    while ($file = $diretorio->read()) {
        $check[] = $file;
    }

    $diretorio->close();

    return $check;
}

//O retorno da função a cima é uma array, por isso, jogo ela dentro de uma variável para ser usada posteriormente
$check = arquivos($diretorio);


//Essa função limpa a array e deixa apenas os arquivos com nomes iguais
function array_not_unique($raw_array)
{
    $dupes = array();
    natcasesort($raw_array);
    reset($raw_array);

    $old_key = NULL;
    $old_value = NULL;
    foreach ($raw_array as $key => $value) {
        if ($value === NULL) {
            continue;
        }
        if (strcasecmp($old_value, $value) == 0) {
            $dupes[$old_key] = $old_value;
            $dupes[$key] = $value;
        }
        $old_value = $value;
        $old_key = $key;
    }
    return $dupes;
}

//Jogo novamente o retorno da função em uma variável
$arquivos = array_not_unique($check);

$zip = new ZipArchive();

//Aqui eu crio um zip com todos os arquivos que possuem nomes parecidos.
if($zip->open('nome_do_arquivo.zip', ZIPARCHIVE::CREATE) == TRUE){
    foreach ($arquivos as $arquivo){
        $zip->addFile($diretorio.$arquivo, ''.$arquivo);
    }
    exit('Arquivo criado com sucesso');
}else{
    exit('O Arquivo não pode ser criado.');
}

$zip->close();