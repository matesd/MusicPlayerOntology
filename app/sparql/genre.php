<?php

/* Split a search result into particular genres */

$genreArray = preg_split("/[,;.]/", preg_replace('/[ ]*&amp;[ ]*/', '&', $genre));

/* SEARCH IN ONTOLOGY CLASSES */

foreach ($genreArray as $g){
    $classGenreSparql = $prefix."
        SELECT DISTINCT ?genreName WHERE {
        {
            ?classGenre rdfs:subClassOf :Genre FILTER regex(?classGenre,\"".preg_replace('/^[_]*/','',preg_replace('/[ ]/', '_', $g))."\",\"i\")
            ?genre rdf:type ?classGenre ;
                   foaf:name ?genreName .     
        } 
        UNION {
            ?classGenre rdfs:subClassOf ?class .
            ?class rdfs:subClassOf :Genre FILTER regex(?classGenre,\"".preg_replace('/^[_]*/','',preg_replace('/[ ]/', '_', $g))."\",\"i\")
            ?genre rdf:type ?classGenre ;
                   foaf:name ?genreName . 
        }
    }";
    $classGenreQuery = $store->query($classGenreSparql);
    $classGenre = $classGenreQuery["result"]["rows"];
    
    if(count($classGenre)==0) {
        $instanceGenres[] = $g;    
    } else
    if($classGenre){
        $classGenreGroup[] = $g;
        foreach ($classGenre as $c){
            $classGenres[] = $c["genreName"];
        }
    }
}


/* /SEARCH IN CLASSES IN ONTOLOGY */


if($instanceGenres) {/* SEARCH IN INSTANCES */
    
    /* Find out whether the user typed genre(s) that is(are) unique OR whether he used fulltext */
    foreach ($instanceGenres as $g){
        $genreUniqueTestSparql = $prefix."
            SELECT ?genreName WHERE {
                ?artist :hasGenre ?genre .
                ?genre foaf:name ?genreName FILTER regex(?genreName, \"".preg_replace('/^[ ]*/', '', $g)."\",\"i\")
            } GROUP BY ?genre
        ";
        $genreUniqueTestQuery = $store->query($genreUniqueTestSparql);
        $genreUniqueTest = $genreUniqueTestQuery["result"]["rows"];
        
        
        /* If the inquired genre matches more than one genre in the ontology */
        
        if(count($genreUniqueTest)>1){
            $moreGenres[] = $g;
        }
        
        /* If the inquired genre matches exactly one genre in the ontology */
        
        else if(count($genreUniqueTest)==1){
            $uniqueGenre[] = $genreUniqueTest[0]["genreName"];
        }
        
        /* If the inquired genre matches no genre in the ontology */
        
        else {
            if($genreUniqueTest!="") {
                $unknownGenre[] = htmlspecialchars($g);
            }
        }
    }
    
    
    /* Make an array with the genres specified by user except for unknown genres */
    
    $existingGenres = array_merge((array)$moreGenres, (array)$uniqueGenre);

}/* /SEARCH IN INSTANCES */


/* Find all suitable artists */

$artistGenreSparql = $prefix."
    SELECT DISTINCT ?artistName ?type WHERE {";
            $artistGenreSparql .= "
            ?artist rdf:type ?type ;
                    foaf:name ?artistName ;
                    :hasGenre ?genre .";
            /* if genres in instances */
            if($existingGenres){
                $i = 0;
                foreach($existingGenres as $g){
                    $artistGenreSparql .= "
                ?artist :hasGenre ?genre".$i." .
                ?genre".$i." foaf:name ?name".$i." FILTER regex(?name".$i.", \"".preg_replace('/^[ ]*/', '', $g)."\", \"i\") .";
                $i++;
                }
            }/* /if genres in instances */
            
            /* if genres in classes */
            if($classGenres){$artistGenreSparql .= "
                    ?genre foaf:name ?name FILTER regex(?name, \"";
                $last = count($classGenres) - 1;
                foreach ($classGenres as $i => $g){
                    $isLast = ($i == $last);
                    $artistGenreSparql .= $g;
                    if(!$isLast){
                        $artistGenreSparql .= "|";
                    }
                }
            $artistGenreSparql .= "\")";
            }/* /if genres in classes */
    
    $artistGenreSparql .= "}";
    $artistGenreQuery = $store->query($artistGenreSparql);
    $artistGenre = $artistGenreQuery["result"]["rows"];
    

/* Set plural constants for better english output */

$pluralUniqueGenres = (count($uniqueGenre)>1)?'s':'';
$pluralMoreGenres = (count($moreGenres)>1)?'s':'';
$pluralUnknownGenres = (count($unknownGenre)>1)?'s':'';



/* Improved genre output */

if ($uniqueGenre){
    $first = 0;
    $last = count($uniqueGenre) - 1;
    foreach ($uniqueGenre as $i => $u){
        $first==0?$genreOutput .= "with ":'';
        $genreOutput .= "<em>".$u."</em>";    
        $isLast = ($i== $last);
        if(!$isLast){
            $genreOutput .= ", ";
        }
        else {
            $genreOutput .= " genre".$pluralUniqueGenres."";
        }
        $first++;
    }
    if($moreGenres) $genreOutput .= " <span>and</span>";
}
if($moreGenres){
    $i = 0;
    $last = count($moreGenres) - 1;
    foreach ($moreGenres as $m){
        if($i==0){ // a first loop
            if(!$uniqueGenre) $genreOutput .= "<span> who</span>";
            if($moreGenres) $genreOutput .= "<span> meet the</span> ";
        }
        $genreOutput .= "<em>".preg_replace('/^[ ]*/', '', $m)."</em>";    
        if($i != $last){ // if not a last loop
            $genreOutput .= ", ";
        } else {
            $genreOutput .= " genre".$pluralMoreGenres;
        }
        $i++;
    }
    
    /* Write out genres in which $moreGenres will search */
    
    $moreGenreSearchSparql = $prefix."
    SELECT DISTINCT ?genreName WHERE {
        {}";
        foreach ($moreGenres as $m){
            $moreGenreSearchSparql .= "
            UNION {
                ?genre foaf:name ?genreName FILTER regex(?genreName, \"".preg_replace('/^[ ]*/', '', $m)."\",\"i\") .
                ?artist :hasGenre ?genre
            }
            ";
        }
    $moreGenreSearchSparql .= "}";
    $moreGenreSearchQuery = $store->query($moreGenreSearchSparql);
    $moreGenreSearch = $moreGenreSearchQuery["result"]["rows"];
}
if($classGenres){
    $i = 0;
    $last = count($classGenreGroup) - 1;
    foreach ($classGenreGroup as $m){
        if($i==0){ // a first loop
            if($moreGenres) {
                $genreOutput .= "<span> and </span>";
            } else if($uniqueGenre) {
                $genreOutput .= "<span> and meet the </span>";
            } else {
                $genreOutput .= "<span> who meet the </span>";
            }
        }
        $genreOutput .= "<em>".preg_replace('/^[ ]*/', '', $m)."</em>";    
        if($i != $last){ // if not a last loop
            $genreOutput .= ", ";
        } else {
            //$genreOutput .= " genre".$pluralMoreGenres;
        }
        $i++;
    }    
}
/* Error messages */

if($unknownGenre){
    $unknownGenreMsg = "Whops, no artist found with genre".$pluralUnknownGenres." ";
    
    $last = count($unknownGenre) - 1;
    foreach($unknownGenre as $i => $u){
         $unknownGenreMsg .= "<em>".$u."</em>";
         if($i != $last) $unknownGenreMsg .= ", ";
    }
    $unknownGenreMsg .= ", so let's seek without that, yup?";
}

if(count($artistGenre)==0){
    $artistGenreMismatch = 1;
    $errorMsg = "<h2>D'Oh!</h2> There is no artist or band with <em>".$genre."</em> genres out there!";
}