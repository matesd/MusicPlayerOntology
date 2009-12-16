<?php

/* Split a search result into particular genres */

$instrumentArray = preg_split("/[,;.]/", $instrument);

/* Find suitable artists */

$instrumentSparql = $prefix."
    SELECT DISTINCT ?artistName ?type WHERE {";
    $i = 0;
    foreach($instrumentArray as $in){
        $instrumentSparql .= "
        ?artist rdf:type ?type ;
                :playsThe ?instrument".$i." .
        ?instrument".$i." foaf:name ?name".$i." FILTER regex(?name".$i.", \"".preg_replace('/^[ ]*/', '', $in)."\", \"i\") .";
        $i++;
    }
    $instrumentSparql .= "
        ?artist foaf:name ?artistName
    } 
";
$instrumentQuery = $store->query($instrumentSparql);
$instruments = $instrumentQuery["result"]["rows"];

/* Error message */

if(count($instruments)==0){
    $instrumentsMismatch = 1;
    $errorMsg = "D'Oh! There is no artist or band who plays <em>".$instrument."</em>.";
}