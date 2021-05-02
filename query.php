<?php

$id = $_POST['id'];

require 'vendor/autoload.php';


$adresa="http://localhost:8080/rdf4j-server/repositories/robograph?query=";

// $query = "PREFIX : <http://ispasteodora.ro#> PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#> SELECT ?actor ?movie ?movieTitle ?buget ?director ?image ?releaseDate WHERE { <".$id."> :playedIn ?movie . <".$id."> :hasName/^:hasName ?actor . OPTIONAL {?movie rdfs:label ?movieTitle} OPTIONAL{ ?movie ^:directorOf ?someone } OPTIONAL {?someone :hasName ?director } OPTIONAL { ?movie :releasedAt ?releaseDate } OPTIONAL { ?movie :hasImage ?image } OPTIONAL {?movie :budget ?buget} }";  
// $query = "PREFIX : <http://ispasteodora.ro#> PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#> SELECT ?name ?image ?manufacturer ?skills ?sale ?releaseDate ?mass WHERE {?x a <".$id.">; rdfs:label ?name ; :manufacturer ?manufacturer ; :hasImage ?image ; :hasSkills ?skills ; :forSale ?sale ; :releaseDate ?releaseDate ; :hasMass ?mass } ";  
$query = "PREFIX : <http://ispasteodora.ro#> PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#> 
PREFIX wdt: <http://www.wikidata.org/prop/direct/> 
PREFIX owl: <http://www.w3.org/2002/07/owl#> 
PREFIX wd: <http://www.wikidata.org/entity/> 
SELECT ?id ?numeRobot ?image ?releaseDate ?skills ?manufacturer ?mass WHERE { { SELECT ?id ?robot ?image ?releaseDate ?skills ?manufacturer ?mass WHERE {<".$id."> a :Category; :hasRobots [:idRobot ?id] . ?id owl:sameAs ?robot . ?id :hasImage ?image . ?id :releaseDate ?releaseDate . ?id :hasSkills ?skills . ?id :manufacturer ?manufacturer. ?id :hasMass ?mass} } SERVICE <https://query.wikidata.org/sparql> { ?robot rdfs:label ?numeRobot . FILTER(lang(?numeRobot)=\"en\") } }";


$interogare=urlencode($query);
//  print $interogare;

$clienthttp=new EasyRdf\Http\Client($adresa.$interogare);

$clienthttp->setHeaders("Accept","application/sparql-results+json");

$rezultatJSON=$clienthttp->request()->getBody();

 print $rezultatJSON;

$rezultatRestructurat=new EasyRdf\Sparql\Result($rezultatJSON,"application/sparql-results+json");
// print $rezultatRestructurat;

return $rezultatRestructurat;