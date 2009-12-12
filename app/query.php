<?php 

//Interpret's genres
$genreSparql = $store->query("
PREFIX : <http://martindoubravsky.cz/ctu/musicplayerontology.owl#> .
PREFIX foaf: <http://xmlns.com/foaf/0.1/>
SELECT DISTINCT ?genre ?genreName WHERE {
    ?name foaf:name ?interpret
        FILTER regex(?interpret, '$interpret', 'i') .
    ?name :hasGenre ?genre .
    ?genre foaf:name ?genreName
} 
");
$genres = $genreSparql["result"]["rows"];

//Interprets which have at least one genre in common 

$intSparql = "
PREFIX : <http://martindoubravsky.cz/ctu/musicplayerontology.owl#> .
PREFIX foaf: <http://xmlns.com/foaf/0.1/>
SELECT DISTINCT ?artistName WHERE {
{}";
if($genres){
foreach ($genres as $genre) {
    $intSparql .= "
    UNION
    {
        ?artist foaf:name ?artistName .
        ?artist :hasGenre ".preg_replace('/([a-zA-Z:\.\/\-]+)#([A-Za-z0-9_\/]+)/', ':$2', $genre['genre'])."
    }
    "; // SMAZAT 'DISTINCT' -> SROVNAT PODLE POCTU VYSKYTU JEDNOTL. INTERPRETU -> TO UZ BUDE VYSLEDEK!
};
}
$intSparql .= "}";
    
$intQuery = $store->query($intSparql);
$interprets = $intQuery["result"]["rows"];

require_once TEMPLATE_DIR . '/base.php';