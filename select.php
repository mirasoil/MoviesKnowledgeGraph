<?php

require 'vendor/autoload.php';

$adresa="http://localhost:8080/rdf4j-server/repositories/movies?query=";

$interogare=urlencode("PREFIX : <http://ispasteodora.ro#> SELECT DISTINCT ?subiect ?nume WHERE {?subiect :playedIn ?movie; :hasName ?nume}");

$clienthttp=new EasyRdf\Http\Client($adresa.$interogare);

$clienthttp->setHeaders("Accept","application/sparql-results+json");

$rezultatJSON=$clienthttp->request()->getBody();

print $rezultatJSON;

$rezultatRestructurat=new EasyRdf\Sparql\Result($rezultatJSON,"application/sparql-results+json");


return $rezultatRestructurat;

// $date=json_decode($rezultatJSON)->results->bindings;

// foreach ($date as $inregistrare)
// {
//     print "<li>Subiectul ".$inregistrare->subiect->value." are numele ".$inregistrare->nume->value."</li>";
// }

// print "<br/><br/><b>Afisam lista subiectelor si numelor mai comod parcurgand raspunsul deserializat de EasyRDF: </b><br/>";

// //Deserializare automata - EasyRDF
// foreach ($rezultatRestructurat as $inregistrare)
// {
//     print "<li>Subiectul ".$inregistrare->subiect." are numele ".$inregistrare->nume."</li>";
// }
