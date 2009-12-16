<?php

/* Split a search result into particular genres */

$genreArray = preg_split("/[,;.]/", $genre);

/* Find suitable artists */

$artistGenreSparql = $prefix."
    SELECT DISTINCT ?artistName ?type WHERE {";
    $i = 0;
    foreach($genreArray as $g){
        $artistGenreSparql .= "
        ?artist rdf:type ?type ;
                :hasGenre ?genre".$i." .
        ?genre".$i." foaf:name ?name".$i." FILTER regex(?name".$i.", \"".preg_replace('/^[ ]*/', '', $g)."\", \"i\") .";
        $i++;
    }
    $artistGenreSparql .= "
        ?artist foaf:name ?artistName
    } 
";
$artistGenreQuery = $store->query($artistGenreSparql);
$artistGenre = $artistGenreQuery["result"]["rows"];

/* Error message */

if(count($artistGenre)==0){
    $artistGenreMismatch = 1;
    $errorMsg = "D'Oh! There is no artist or band with <em>".$genre."</em> genres out there!";
}