<?php

require 'vendor/autoload.php';

$uri = $_POST['uri'];


$client=new EasyRdf\Sparql\Client("http://localhost:8080/rdf4j-server/repositories/robograph/statements");

// $interogare="PREFIX : <http://ispasteodora.ro#> DELETE WHERE {GRAPH :robots {<".$uri."> ?x ?y} GRAPH :categories {?o :hasRobots [:idRobot  <".$uri.">] } }";
$interogare="PREFIX : <http://ispasteodora.ro#> DELETE WHERE {GRAPH :robots {<".$uri."> ?x ?y} }";
print $interogare;

$rezultat=$client->update($interogare);

//atentie, rezultatul operatiilor de scriere e o simpla confirmare a succesului inserarii (cod HTTP 204)
print $rezultat;