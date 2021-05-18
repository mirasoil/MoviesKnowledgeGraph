<?php

require 'vendor/autoload.php';

$adresa = "http://localhost:8080/rdf4j-server/repositories/grafexamen?query=";

$interogare = urlencode("PREFIX : <http://narosispas.ro#> SELECT ?categorie ?nume WHERE { GRAPH :categories {?categorie a :Category; rdfs:label ?nume} }");

$clienthttp = new EasyRdf\Http\Client($adresa.$interogare);

$clienthttp->setHeaders("Accept","application/sparql-results+json");

$rezultatJSON = $clienthttp->request()->getBody();

print $rezultatJSON;

$rezultatRestructurat = new EasyRdf\Sparql\Result($rezultatJSON,"application/sparql-results+json");


return $rezultatRestructurat;