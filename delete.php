<?php

require 'vendor/autoload.php';

$uri = $_POST['uri'];


$client=new EasyRdf\Sparql\Client("http://localhost:8080/rdf4j-server/repositories/robograph/statements");

$interogare="PREFIX : <http://ispasteodora.ro#>  
DELETE WHERE {
  GRAPH :robots {
    <".$uri."> ?x ?y 
  }
  GRAPH :categories {
    ?o ?w ?p. 
    ?p :idRobot <".$uri.">
  }
}";
print $interogare;

$rezultat=$client->update($interogare);

print $rezultat;