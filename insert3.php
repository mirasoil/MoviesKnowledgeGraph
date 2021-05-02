<?php

$uri = $_POST['uri'];
$uriNou = $_POST['uriNou'];
$buget = $_POST['buget'];

require 'vendor/autoload.php';
print $uri;

$adresa="http://localhost:8080/rdf4j-server/repositories/movies?query=";

//actor = on insert - uri, movie = on delete - uri, movieTitle = FE, buget = FE, director = FE, image = FE, releaseDate = FE
$query = "PREFIX : <http://ispasteodora.ro#> ASK WHERE{ BIND(REPLACE(\"".$uriNou."\",' ','') AS ?numeFaraSpatii) BIND(CONCAT('http://ispasteodora.ro#',?numeFaraSpatii) AS ?uriString) BIND(IRI(?uriString) AS ?idNou) ?idNou ?x ?y}";  

$interogare=urlencode($query);
// print $interogare;
$clienthttp=new EasyRdf\Http\Client($adresa.$interogare);

$clienthttp->setHeaders("Accept","application/sparql-results+json");

$rezultatJSON=$clienthttp->request()->getBody();

// print $rezultatJSON;

$rezultatRestructurat=new EasyRdf\Sparql\Result($rezultatJSON,"application/sparql-results+json");
// print $rezultatRestructurat;
if($rezultatRestructurat === true) {
    print 'Acest URI exita deja in baza de cunostinte';
    return false;
} else {
    // print 'ok';
    $client=new EasyRdf\Sparql\Client("http://localhost:8080/rdf4j-server/repositories/movies/statements");

    $interogare="PREFIX : <http://ispasteodora.ro#> INSERT {GRAPH :mymoviegraph { <".$uri."> :playedIn ?idNou . ?idNou :budget \" ".$buget." \"} } WHERE { BIND(REPLACE(\"".$uriNou."\",' ','') AS ?numeFaraSpatii) BIND(CONCAT(\"http://ispasteodora.ro#\",?numeFaraSpatii) AS ?uriString) BIND(IRI(?uriString) AS ?idNou) MINUS {?idNou ?x ?y} }";

    $rezultat=$client->update($interogare);

    print 'Rezultat '.json_encode($rezultat);
}