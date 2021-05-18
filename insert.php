<?php

require 'vendor/autoload.php';

$data = json_decode(file_get_contents('php://input'), true);
$category = $data['category'];
$uriNou = $data['uriNou'];       // New label
$label = $uriNou;
$manufacturer = $data['manufacturer'];
$image = $data['image'];
$skills = $data['skills'];
$sale = $data['sale'];
$releaseDate = $data['releaseDate'];
$mass = $data['mass'];


$adresa = "http://localhost:8080/rdf4j-server/repositories/grafexamen?query=";

$query = "PREFIX : <http://narosispas.ro#> ASK WHERE{ BIND(REPLACE(\"".$uriNou."\",' ','') AS ?numeFaraSpatii) BIND(CONCAT('http://narosispas.ro#',?numeFaraSpatii) AS ?uriString) BIND(IRI(?uriString) AS ?idNou) ?idNou ?x ?y}";  

$interogare = urlencode($query);

$clienthttp = new EasyRdf\Http\Client($adresa.$interogare);

$clienthttp->setHeaders("Accept","application/sparql-results+json");

$rezultatJSON = $clienthttp->request()->getBody();


$rezultatRestructurat = new EasyRdf\Sparql\Result($rezultatJSON,"application/sparql-results+json");

if($rezultatRestructurat == "true") {
    $response_array = array("error"=> false,
                            "status" => false,
                            "message" => 'This URI already exists in the knowledge graph!');
    header('Content-Type: application/json');
    echo json_encode($response_array);
    die();
    
} else {
    $client = new EasyRdf\Sparql\Client("http://localhost:8080/rdf4j-server/repositories/grafexamen/statements");

    $interogare = "PREFIX : <http://narosispas.ro#> 
    PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>  
    INSERT {GRAPH :robots { ?idNou a :Robot ; rdfs:label \"".$label."\" ; :manufacturer \"".$manufacturer."\" ; 
        :hasImage \"".$image."\" ;  :hasSkills  \"".$skills."\" ; :forSale \"".$sale."\" ; :releaseDate \"".$releaseDate."\"^^xsd:date ; :hasMass \"".$mass."\"^^xsd:decimal } 
        GRAPH :categories {<".$category."> :hasRobots [:idRobot ?idNou]}
    } 
    WHERE { 
        BIND(REPLACE(\"".$uriNou."\",' ','') AS ?numeFaraSpatii) 
        BIND(CONCAT(\"http://narosispas.ro#\",?numeFaraSpatii) AS ?uriString) 
        BIND(IRI(?uriString) AS ?idNou) MINUS {?idNou ?x ?y} }";

    $rezultat = $client->update($interogare);

    $response_array = array("success"=> true,
                                "status" => true,
                                "message" => 'Successfully inserted!');
        header('Content-Type: application/json');
        echo json_encode($response_array);
        die();
  
}