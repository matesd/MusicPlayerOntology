<?php

/* Split a search result into particular genres */

$genreArray = preg_split("/[,;.]/", $genre);

/* Find out whether the user typed genre(s) that is(are) unique OR whether he used fulltext */
foreach ($genreArray as $g){
    $genreUniqueTestSparql = $prefix."
        SELECT ?genreName WHERE {
            ?artist :hasGenre ?genre FILTER regex(?genre, \"".preg_replace('/^[ ]*/', '', $g)."\",\"i\") .
            ?genre foaf:name ?genreName
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
        $unknownGenre[] = $g;
    }
}

/* Find genres for each ambiguous input entry - TODO :
    it should return st. like this:
    
    Artist with po,rock genre.
    
    'Po' represents some of these genres: AM Pop, Contemporary Pop (..)
    'rock' represents some of these genres: soft-rock, rock & roll (..)
    
    --searching is already ok.
*/



/* Make an array with the genres specified by user except for unknown genres */

$existingGenres = array_merge((array)$moreGenres, (array)$uniqueGenre);

/* Find all suitable artists */

$artistGenreSparql = $prefix."
    SELECT DISTINCT ?artistName ?type WHERE {";
    $i = 0;
    foreach($existingGenres as $g){
        $artistGenreSparql .= "
        ?artist rdf:type ?type ;
                :hasGenre ?genre".$i." .
        ?genre".$i." foaf:name ?name".$i." FILTER regex(?name".$i.", \"".$g."\", \"i\") .";
        $i++;
    }
    $artistGenreSparql .= "
        ?artist foaf:name ?artistName
    } 
";
$artistGenreQuery = $store->query($artistGenreSparql);
$artistGenre = $artistGenreQuery["result"]["rows"];

/* Improved genre output */

if ($uniqueGenre){
    $last = count($uniqueGenre) - 1;
    foreach ($uniqueGenre as $i => $u){
        $genreOutput .= $u;    
        $isLast = ($i== $last);
        if(!$isLast){
            $genreOutput .= ", ";
        }
    }
    if($moreGenres) $genreOutput .= " and ";
}
if($moreGenres){
    $last = count($moreGenres) - 1;
    foreach ($moreGenres as $i => $m){
        $genreOutput .= $m;    
        $isLast = ($i == $last);
        if(!$isLast){
            $genreOutput .= ", ";
        }
    }
}

/* Set plural constant for better english text */

$pluralGenre = ((count($uniqueGenre)+count($moreGenres))>1)?1:'';


/* Error message */

if(count($artistGenre)==0){
    $artistGenreMismatch = 1;
    $errorMsg = "D'Oh! There is no artist or band with <em>".$genre."</em> genres out there!";
}