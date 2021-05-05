<?php

require 'vendor/autoload.php';

$category = $_POST['category'];
$uriNou = $_POST['uriNou']; //denumire noua
$label = $uriNou;
$manufacturer = $_POST['manufacturer'];
$image = $_POST['image'];
$skills = $_POST['skills'];
$sale = $_POST['sale'];
$releaseDate = $_POST['releaseDate'];
$mass = $_POST['mass'];


$adresa="http://localhost:8080/rdf4j-server/repositories/robograph?query=";

$query = "PREFIX : <http://ispasteodora.ro#> ASK WHERE{ BIND(REPLACE(\"".$uriNou."\",' ','') AS ?numeFaraSpatii) BIND(CONCAT('http://ispasteodora.ro#',?numeFaraSpatii) AS ?uriString) BIND(IRI(?uriString) AS ?idNou) ?idNou ?x ?y}";  

$interogare=urlencode($query);

$clienthttp=new EasyRdf\Http\Client($adresa.$interogare);

$clienthttp->setHeaders("Accept","application/sparql-results+json");

$rezultatJSON=$clienthttp->request()->getBody();


$rezultatRestructurat=new EasyRdf\Sparql\Result($rezultatJSON,"application/sparql-results+json");

if($rezultatRestructurat == "true") {
    $response_array = array("error"=> false,
                            "status" => false,
                            "message" => 'Acest URI exita deja in baza de cunostinte');
    header('Content-Type: application/json');
    echo json_encode($response_array);die();
    
} else {
    $client=new EasyRdf\Sparql\Client("http://localhost:8080/rdf4j-server/repositories/robograph/statements");

    $interogare="PREFIX : <http://ispasteodora.ro#> 
    PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>  
    INSERT {GRAPH :robots { ?idNou a :Robot ; rdfs:label \"".$label."\" ; :manufacturer \"".$manufacturer."\" ; 
        :hasImage \"".$image."\" ;  :hasSkills  \"".$skills."\" ; :forSale \"".$sale."\" ; :releaseDate \"".$releaseDate."\"^^xsd:date ; :hasMass \"".$mass."\"^^xsd:decimal } 
        GRAPH :categories {<".$category."> :hasRobots [:idRobot ?idNou]}
    } 
    WHERE { 
        BIND(REPLACE(\"".$uriNou."\",' ','') AS ?numeFaraSpatii) 
        BIND(CONCAT(\"http://ispasteodora.ro#\",?numeFaraSpatii) AS ?uriString) 
        BIND(IRI(?uriString) AS ?idNou) MINUS {?idNou ?x ?y} }";

    $rezultat=$client->update($interogare);

    $response_array = array("success"=> true,
                                "status" => true,
                                "message" => 'Inserat cu succes');
        header('Content-Type: application/json');
        echo json_encode($response_array);die();
           

    
}