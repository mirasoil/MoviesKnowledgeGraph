<?php

require 'vendor/autoload.php';

$uri = file_get_contents('php://input');

$client = new EasyRdf\Sparql\Client("http://localhost:8080/rdf4j-server/repositories/grafexamen/statements");

$query = "PREFIX : <http://narosispas.ro#>  
DELETE WHERE {
  GRAPH :robots {
    <".$uri."> ?x ?y 
  }
  GRAPH :categories {
    ?o ?w ?p. 
    ?p :idRobot <".$uri.">
  }
}";
print $query;

$result = $client->update($query);

print $result;