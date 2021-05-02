<?php

require 'vendor/autoload.php';

$client=new EasyRdf\Sparql\Client("http://localhost:8080/rdf4j-server/repositories/movies");

$interogare="PREFIX : <http://ispasteodora.ro#> SELECT ?subiect ?nume WHERE {?subiect :playedIn ?movie; :hasName ?nume}";

$rezultate=$client->query($interogare);

var_dump($rezultate);
