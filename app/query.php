<?php 

//Interpret's genres
$genreSparql = $store->query("
PREFIX : <http://martindoubravsky.cz/ctu/musicplayerontology.owl#> .
PREFIX foaf: <http://xmlns.com/foaf/0.1/>
SELECT DISTINCT ?genre ?genreName WHERE {
    ?name foaf:name ?interpret
        FILTER regex(?interpret, '$interp', 'i') .
    ?name :hasGenre ?genre .
    ?genre foaf:name ?genreName
} 
");
$genres = $genreSparql["result"]["rows"];

//Interprets which have at least one genre in common 

$interpretsSparql = "
PREFIX : <http://martindoubravsky.cz/ctu/musicplayerontology.owl#> .
PREFIX foaf: <http://xmlns.com/foaf/0.1/>
SELECT ?artistName ?genreName COUNT(?genre) as ?genresNo WHERE
{
    ?artist foaf:name ?artistName ;
            :hasGenre ?genre
            FILTER regex(?genre, \"";
if($genres){
    $last = count($genres) - 1;
    foreach ($genres as $i => $genre){
        $isLast = ($i == $last);
        $interpretsSparql .= preg_replace('/ /', '_', $genre["genreName"]);
        if(!$isLast){
            $interpretsSparql .= "|";
        }
    }
} 
$interpretsSparql .= "\", \"i\") .
    ?genre foaf:name ?genreName
} GROUP BY ?artist
ORDER BY DESC(?genresNo)";
$interpretsQuery = $store->query($interpretsSparql);
$interprets = $interpretsQuery["result"]["rows"];

// $resultSparql = "
// PREFIX : <http://martindoubravsky.cz/ctu/musicplayerontology.owl#> .
// PREFIX foaf: <http://xmlns.com/foaf/0.1/>
// SELECT ?artistName ?genreName WHERE {
// {}";
// if($interprets){
// foreach ($interprets as $i) {
//     $resultSparql .= "
//     UNION
//     {
//         ?artist foaf:name ?artistName ;
//                 :hasGenre ?genre . 
//         ?genre foaf:name '".$i["genreName"]."' ;
//                foaf:name ?genreName
//     }
//     ";
// };
// }
// $resultSparql .= "}";
// $resultQuery = $store->query($resultSparql);
// $results = $resultQuery["result"]["rows"];

require_once TEMPLATE_DIR . '/base.php';