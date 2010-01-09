<?php 
/* SPARQL prefixes */
$prefix = "
PREFIX : <http://martindoubravsky.cz/ctu/musicplayerontology.owl#> .
PREFIX foaf: <http://xmlns.com/foaf/0.1/> .
PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
";

/* For interpret search field */
if($interpret && !($album || $song)){
    include SPARQL_DIR . 'interpret.php';
}

/* For genre search field */
else if($genre){
    include SPARQL_DIR . 'genre.php';
}

/* For instrument search field */
else if($instrument){
    include SPARQL_DIR . 'instrument.php';
}

/* For song search field */
else if($album || $song){
    include SPARQL_DIR . 'song.php';
}