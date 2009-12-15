<?php 
$prefix = "
PREFIX : <http://martindoubravsky.cz/ctu/musicplayerontology.owl#> .
PREFIX foaf: <http://xmlns.com/foaf/0.1/> .
PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
";
/* if a search field is for interprets */
if($interpret){
    /* Find out a search query kind (artist | band). */
    $artistSparql = $store->query($prefix."
    ASK {
        ?s foaf:name ?name FILTER regex(?name, \"".$interpret."\", \"i\") .
        ?s rdf:type :SoloArtist}
    ");
    $artist = $artistSparql["result"];
    
    $bandSparql = $store->query($prefix."
    ASK {
        ?s foaf:name ?name FILTER regex(?name, \"".$interpret."\", \"i\") .
        ?s rdf:type :Band}
    ");
    $band = $bandSparql["result"];
    
    if($artist OR $band){
        $possibleInterpretsSparql = $store->query($prefix."
            SELECT ?posInt ?posIntName WHERE {
                ?posInt foaf:name ?name FILTER regex(?name, \"".$interpret."\", \"i\") .
                ?posInt rdf:type ?type FILTER regex(?type, \"SoloArtist|Band\") .
                ?posInt foaf:name ?posIntName
            } 
            ");
        $possibleInterprets = $possibleInterpretsSparql["result"]["rows"];
        
        /* List all options if the search result is not unique */
        if(count($possibleInterprets)>1){
            $moreInterprets = TRUE;
        }
        else {
            $theOnlyOneName = $possibleInterprets[0]["posIntName"];
            $theOnlyOne = preg_replace('/ /', '_', $theOnlyOneName);
        }
            
        if($artist){
            $memberSparql = $store->query($prefix."
            SELECT DISTINCT ?memberName WHERE {
                ?band foaf:member ?member FILTER regex(?member, \"$theOnlyOne\") .
                ?band foaf:name ?memberName .
            } 
            ");
            $members = $memberSparql["result"]["rows"];
        } 
        else if($band){
            $memberSparql = $store->query($prefix."
            SELECT DISTINCT ?memberName WHERE {
                ?band foaf:name '$theOnlyOneName' ;
                      foaf:member ?member .
                ?member foaf:name ?memberName
            } 
            ");
            $members = $memberSparql["result"]["rows"];
        }
        
        /* Interpret's genres */
        $genreSparql = $store->query($prefix."
        SELECT DISTINCT ?genre ?genreName WHERE {
            ?name foaf:name '$theOnlyOneName' .
            ?name :hasGenre ?genre .
            ?genre foaf:name ?genreName
        } 
        ");
        $genres = $genreSparql["result"]["rows"];
        
        /* Similar interprets */
        $interpretsSparql = $prefix."
        SELECT ?artistName ?type ?genreName COUNT(?genre) as ?genresNo WHERE
        {
            ?artist rdf:type ?type ;
                    foaf:name ?artistName ;
                    :hasGenre ?genre
                    FILTER regex(?genre, \"";
        if($genres){
            $last = count($genres) - 1;
            foreach ($genres as $i => $g){
                $isLast = ($i == $last);
                $interpretsSparql .= preg_replace('/ /', '_', $g["genreName"]);
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
    }
    else {
     $errorMsg = "D'Oh! There is no artist or band called \"".$interpret."\" out there!";
    }
}
require_once TEMPLATE_DIR . '/base.php';