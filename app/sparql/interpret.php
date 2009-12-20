<?php

/* Find out a search query kind (artist | band) */

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


/* If searched artist or band exists */

if($artist OR $band){
    $possibleInterpretsSparql = $store->query($prefix."
        SELECT ?posInt ?posIntName WHERE {
            ?posInt foaf:name ?name FILTER regex(?name, \"".$interpret."\", \"i\") .
            ?posInt rdf:type ?type FILTER regex(?type, \"SoloArtist|Band\") .
            ?posInt foaf:name ?posIntName
        } 
        ");
    $possibleInterprets = $possibleInterpretsSparql["result"]["rows"];
    
    
    /* Test whether the search result is unique */
    
    if(count($possibleInterprets)>1){
        $moreInterprets = TRUE;
    }
    else {
        $theOnlyOneName = $possibleInterprets[0]["posIntName"];
        $theOnlyOne = preg_replace('/ /', '_', $theOnlyOneName);
    }
       
       
    /* Let's do the main part in case of unique search results */
    
    if($theOnlyOne){
    
        /* Find members of band */
            
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
        
        /* Artist's instruments */
        
        $instrumentSparql = $store->query($prefix."
        SELECT DISTINCT ?name WHERE {
            :$theOnlyOne :playsThe ?i .
            ?i foaf:name ?name
        } 
        ");
        $instruments = $instrumentSparql["result"]["rows"];
        
        /* Artist's album */
        
        $albumSparql = $store->query($prefix."
        SELECT DISTINCT ?albumName WHERE {
            ?artist foaf:name '$theOnlyOneName' .
            ?artist :hasAlbum ?album ;
                    foaf:name ?albumName
        }
        ");
        $album = $albumSparql["result"]["rows"];
        
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
    
    }/* /if theOnlyOne */
}

/* Error messages */
else {
    $errorMsg = "D'Oh! There is no artist or band called <em>".$interpret."</em> out there!";
}