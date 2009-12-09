<?php

require_once APP_DIR . '/config.php';

/* store instantiation */

$store = ARC2::getStore($arc_config);

if (!$store->isSetUp()) {
  $store->setUp(); /* create MySQL tables */
}

//load ontology only once or after update! 
//$store->query("LOAD <http://martindoubravsky.cz/ctu/musicplayerontology.owl>");

$genre = ucfirst($_GET['genre']);

$result = $store->query("
PREFIX : <http://martindoubravsky.cz/ctu/musicplayerontology.owl#> .
PREFIX foaf:   <http://xmlns.com/foaf/0.1/>
SELECT ?name WHERE { 
    ?x foaf:name ?name . 
    ?x :hasGenre :$genre
}
");

require_once TEMPLATE_DIR . '/base.php';//casem na base.phtml -> + udelat presenter s logikou
?>