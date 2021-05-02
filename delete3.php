<?php

require 'vendor/autoload.php';

$uri = $_POST['uri'];

//atentie, adresa pentru operatii de scriere difera (are la final cuvantul statements)
$client=new EasyRdf\Sparql\Client("http://localhost:8080/rdf4j-server/repositories/movies/statements");

//atentie, interogarea contine si declararea prefixelor non-standard din interogare (valabil si la SELECT)!
$interogare="PREFIX : <http://ispasteodora.ro#> DELETE WHERE {GRAPH :mymoviegraph {<".$uri."> ?x ?y}}";
print $interogare;
//atentie, operatiile de scriere se executa cu update(), nu cu query()
$rezultat=$client->update($interogare);

//atentie, rezultatul operatiilor de scriere e o simpla confirmare a succesului inserarii (cod HTTP 204)
print $rezultat;