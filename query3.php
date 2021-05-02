<?php

$id = $_POST['id'];

require 'vendor/autoload.php';


$adresa="http://localhost:8080/rdf4j-server/repositories/grafexamen?query=";

//actor = on insert - uri, movie = on delete - uri, movieTitle = FE, buget = FE, director = FE, image = FE, releaseDate = FE
// $query = "PREFIX : <http://ispasteodora.ro#> PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#> SELECT ?actor ?movie ?movieTitle ?buget ?director ?image ?releaseDate WHERE { <".$id."> :playedIn ?movie . <".$id."> :hasName/^:hasName ?actor . OPTIONAL {?movie rdfs:label ?movieTitle} OPTIONAL{ ?movie ^:directorOf ?someone } OPTIONAL {?someone :hasName ?director } OPTIONAL { ?movie :releasedAt ?releaseDate } OPTIONAL { ?movie :hasImage ?image } OPTIONAL {?movie :budget ?buget} }";  
$query = "PREFIX : <http://ispasteodora.ro#> PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#> SELECT ?name ?image ?manufacturer ?skills ?sale ?releaseDate ?mass WHERE {?x a <".$id.">; rdfs:label ?name ; :manufacturer ?manufacturer ; :hasImage ?image ; :hasSkills ?skills ; :forSale ?sale ; :releaseDate ?releaseDate ; :hasMass ?mass }";  


$interogare=urlencode($query);

$clienthttp=new EasyRdf\Http\Client($adresa.$interogare);

$clienthttp->setHeaders("Accept","application/sparql-results+json");

$rezultatJSON=$clienthttp->request()->getBody();

print $rezultatJSON;

$rezultatRestructurat=new EasyRdf\Sparql\Result($rezultatJSON,"application/sparql-results+json");


return $rezultatRestructurat;

