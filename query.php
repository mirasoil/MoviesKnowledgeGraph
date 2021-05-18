<?php
require 'vendor/autoload.php';

$id = file_get_contents('php://input'); 

$adresa = "http://localhost:8080/rdf4j-server/repositories/grafexamen?query=";

$query = "PREFIX : <http://narosispas.ro#>  
PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>  
PREFIX wdt: <http://www.wikidata.org/prop/direct/>  
PREFIX owl: <http://www.w3.org/2002/07/owl#>  
PREFIX wd: <http://www.wikidata.org/entity/>  

SELECT ?id ?numeRobot ?image ?releaseDate ?skills ?manufacturer ?mass ?sale
FROM :robots FROM :samenessgraph FROM :categories  WHERE {
    <".$id."> a :Category; :hasRobots [:idRobot ?id] ; rdfs:label ?label .                          

    ?id :hasImage ?image .   
    ?id :releaseDate ?releaseDate .  
    ?id :hasSkills ?skills .   
    ?id :manufacturer ?manufacturer .   
    ?id :hasMass ?mass . 
    ?id :forSale ?sale . 
    OPTIONAL { ?id owl:sameAs ?robot } 
  BIND(COALESCE(?robot, \"unknown\") as ?input) 
  OPTIONAL {     
    SERVICE <https://query.wikidata.org/sparql> {               
      ?input rdfs:label ?wdName .              
      FILTER(lang(?numeRobot)=\"en\")          
    }          
  }     
  OPTIONAL { ?id rdfs:label ?localLabel }     
  BIND(COALESCE(?wdName, ?localLabel, strafter(str(?id), str(:))) as ?numeRobot) }";
 

$interogare = urlencode($query);

$clienthttp = new EasyRdf\Http\Client($adresa.$interogare);

$clienthttp->setHeaders("Accept","application/sparql-results+json");

$rezultatJSON = $clienthttp->request()->getBody();

 print $rezultatJSON;

$rezultatRestructurat = new EasyRdf\Sparql\Result($rezultatJSON,"application/sparql-results+json");
// print $rezultatRestructurat;

return $rezultatRestructurat;