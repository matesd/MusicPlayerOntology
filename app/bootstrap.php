<?php

/* Instal ARC System */
require_once APP_DIR . '/config.php';

/* Enable next command if you want to load ontology update. */
//$store->query("LOAD <http://martindoubravsky.cz/ctu/mpo.owl>");

$interpret = isset($_GET['interpret'])?htmlspecialchars($_GET['interpret']):'';
$genre = isset($_GET['genre'])?htmlspecialchars($_GET['genre']):'';
$instrument = isset($_GET['instrument'])?htmlspecialchars($_GET['instrument']):'';
$song = isset($_GET['song'])?htmlspecialchars($_GET['song']):'';
$album = isset($_GET['album'])?htmlspecialchars($_GET['album']):'';

/* testing for an appropriate layout */
$hp = (!$interpret && !$genre && !$instrument && !$song && !$album)?'1':'';


/* SPARQL queries */
require_once APP_DIR . 'query.php';

/* Call for an output template */
require_once TEMPLATE_DIR . 'base.phtml';

/* Debug */
//require_once APP_DIR . '/endpoint.php';