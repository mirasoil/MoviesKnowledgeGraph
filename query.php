<?php

$id = $_POST['id'];

require 'vendor/autoload.php';


$adresa="http://localhost:8080/rdf4j-server/repositories/robograph?query=";

// $query = "PREFIX : <http://ispasteodora.ro#> PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#> 
// PREFIX wdt: <http://www.wikidata.org/prop/direct/> 
// PREFIX owl: <http://www.w3.org/2002/07/owl#> 
// PREFIX wd: <http://www.wikidata.org/entity/> 
// SELECT ?id ?numeRobot ?image ?releaseDate ?skills ?manufacturer ?mass WHERE { { SELECT ?id ?robot ?image ?releaseDate ?skills ?manufacturer ?mass WHERE {<".$id."> a :Category; :hasRobots [:idRobot ?id] . 
//     ?id owl:sameAs ?robot . ?id :hasImage ?image . ?id :releaseDate ?releaseDate . 
//     ?id :hasSkills ?skills . ?id :manufacturer ?manufacturer. ?id :hasMass ?mass} } 
//     SERVICE <https://query.wikidata.org/sparql> { ?robot rdfs:label ?numeRobot . FILTER(lang(?numeRobot)=\"en\") } }";


$query = "PREFIX : <http://ispasteodora.ro#>  
PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>  
PREFIX wdt: <http://www.wikidata.org/prop/direct/>  
PREFIX owl: <http://www.w3.org/2002/07/owl#>  
PREFIX wd: <http://www.wikidata.org/entity/>  

SELECT ?id ?numeRobot ?image ?releaseDate ?skills ?manufacturer ?mass 
FROM :robots FROM :samenessgraph FROM :categories  WHERE {
    <".$id."> a :Category; :hasRobots [:idRobot ?id] .                            
  ?id :hasImage ?image .   
  ?id :releaseDate ?releaseDate .  
  ?id :hasSkills ?skills .   
  ?id :manufacturer ?manufacturer .   
    ?id :hasMass ?mass . 
  OPTIONAL { ?id owl:sameAs ?robot } 
  BIND(COALESCE(?robot, \"unknown\") as ?input) OPTIONAL {     
    SERVICE <https://query.wikidata.org/sparql> {               
      ?input rdfs:label ?wdName .              
      FILTER(lang(?numeRobot)=\"en\")          
    }          
  }     
  OPTIONAL { ?id rdfs:label ?label }     
  BIND(COALESCE(?wdName, ?label, strafter(str(?id), str(:))) as ?numeRobot) }";

$interogare=urlencode($query);
//  print $interogare;

$clienthttp=new EasyRdf\Http\Client($adresa.$interogare);

$clienthttp->setHeaders("Accept","application/sparql-results+json");

$rezultatJSON=$clienthttp->request()->getBody();

 print $rezultatJSON;

$rezultatRestructurat=new EasyRdf\Sparql\Result($rezultatJSON,"application/sparql-results+json");
// print $rezultatRestructurat;

return $rezultatRestructurat;