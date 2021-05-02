<?php

$id = $_POST['id'];

require 'vendor/autoload.php';


$client=new EasyRdf\Sparql\Client("http://localhost:8080/rdf4j-server/repositories/movies");

$interogare="PREFIX : <http://ispasteodora.ro#> SELECT ?movie WHERE { <".$id."> :playedIn ?movie}";

//print $interogare;

$rezultate=$client->query($interogare);

print json_decode(file_get_contents($rezultate));




SELECT ?movie ?buget WHERE {
    :SamWorth :playedIn ?movie .
     ?movie :budget ?buget
    
    }



    PREFIX : <http://ispasteodora.ro#>
    PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
    
    SELECT ?movieTitle ?buget ?director ?image ?releaseDate WHERE {
            ?x :playedIn ?movie .
      ?movie rdfs:label ?movieTitle .
      ?movie :releasedAt ?releaseDate .
             ?movie :budget ?buget .
              ?movie :hasImage ?image .
              ?movie ^:directorOf ?someone .
              ?someone :hasName ?director
            
            }



ASK 
WHERE{ 
  BIND(REPLACE("hello world",' ','') AS ?numeFaraSpatii) 
      BIND(CONCAT('http://ispasteodora.ro#',?numeFaraSpatii) AS ?uriString) 
      BIND(IRI(?uriString) AS ?idNou) 
  ?idNou ?x ?y
}


//RETURNS YES
ASK 
WHERE{ 
  BIND(REPLACE("Arnold",' ','') AS ?numeFaraSpatii) 
      BIND(CONCAT('http://ispasteodora.ro#',?numeFaraSpatii) AS ?uriString) 
      BIND(IRI(?uriString) AS ?idNou) 
  ?idNou ?x ?y
}



INSERT {GRAPH :mymoviegraph { ?idNou a :Movie} } WHERE
{ 
BIND(REPLACE("Hello World"," ","") AS ?numeFaraSpatii)
BIND(CONCAT("http://ispasteodora.ro#",?numeFaraSpatii) AS ?uriString)
BIND(IRI(?uriString) AS ?idNou)
}