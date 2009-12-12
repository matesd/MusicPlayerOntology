<?php

require_once APP_DIR . '/config.php';

/* store instantiation */

$store = ARC2::getStore($arc_config);

if (!$store->isSetUp()) {
  $store->setUp(); /* create MySQL tables */
}

//load only after ontology update 
//$store->query("LOAD <http://martindoubravsky.cz/ctu/musicplayerontology.owl>");

$genre = $_GET['genre'];
$interpret = $_GET['interpret'];


// $result = $store->query("
// PREFIX : <http://martindoubravsky.cz/ctu/musicplayerontology.owl#> .
// PREFIX foaf:   <http://xmlns.com/foaf/0.1/>
// SELECT ?genre WHERE {
//     
// }
// ");
//debugging:
require_once APP_DIR . '/query.php';//casem na base.phtml -> + udelat presenter s logikou

//require_once APP_DIR . '/endpoint.php';
