<?php
require_once APP_DIR . '/config.php';

/* store instantiation */

$store = ARC2::getStore($arc_config);

if (!$store->isSetUp()) {
  $store->setUp(); /* create MySQL tables */
}

/* Enable next command if you want to load ontology update. */
//$store->query("LOAD <http://martindoubravsky.cz/ctu/musicplayerontology.owl>");

$interpret = isset($_GET['interpret'])?$_GET['interpret']:'';
$genre = isset($_GET['genre'])?$_GET['genre']:'';
$instrument = isset($_GET['instrument'])?$_GET['instrument']:'';
$song = isset($_GET['song'])?$_GET['song']:'';


/* SPARQL queries */
require_once APP_DIR . 'query.php';

/* Call for an output template */
require_once TEMPLATE_DIR . 'base.phtml';

/* Debug */
//require_once APP_DIR . '/endpoint.php';