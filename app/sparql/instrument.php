<?php

/* Split a search result into particular genres */

$instrumentArray = preg_split("/[,;.]/", $instrument);

/* Find out whether the user typed instrument(s) that is(are) unique OR whether he used fulltext */
foreach ($instrumentArray as $g){
    $instrumentUniqueTestSparql = $prefix."
        SELECT ?instrumentName WHERE {
            ?artist :playsThe ?instrument .
            ?instrument foaf:name ?instrumentName FILTER regex(?instrumentName, \"".preg_replace('/^[ ]*/', '', $g)."\",\"i\")
        } GROUP BY ?instrument
    ";
    $instrumentUniqueTestQuery = $store->query($instrumentUniqueTestSparql);
    $instrumentUniqueTest = $instrumentUniqueTestQuery["result"]["rows"];
    
    /* If the inquired instrument matches more than one instrument in the ontology */
    
    if(count($instrumentUniqueTest)>1){
        $moreInstruments[] = htmlspecialchars($g);    
    }
    
    /* If the inquired instrument matches exactly one instrument in the ontology */
    
    else if(count($instrumentUniqueTest)==1){
        $uniqueInstrument[] = htmlspecialchars($instrumentUniqueTest[0]["instrumentName"]);
    }
    
    /* If the inquired instrument matches no instrument in the ontology */
    
    else {
        $unknownInstrument[] = htmlspecialchars($g);
    }
}

/* Make an array with the genres specified by user except for unknown genres */

$existingInstruments = array_merge((array)$moreInstruments, (array)$uniqueInstrument);


/* Find all suitable artists */

$instrumentSparql = $prefix."
    SELECT DISTINCT ?artistName ?type WHERE {";
    $i = 0;
    foreach($existingInstruments as $in){
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

/* Set plural constants for better english output */

$pluralMoreInstruments = (count($moreInstruments)>1)?'s':'';
$pluralUnknownInstruments = (count($unknownInstrument)>1)?'s':'';

/* Improved instrument output */

if ($uniqueInstrument){
    $last = count($uniqueInstrument) - 1;
    foreach ($uniqueInstrument as $i => $u){
        $instrumentOutput .= "<em>".$u."</em>";    
        $isLast = ($i== $last);
        if(!$isLast){
            $instrumentOutput .= ", ";
        }
    }
    if($moreInstruments) $instrumentOutput .= " and";
}
if($moreInstruments){
    $i = 0;
    $last = count($moreInstruments) - 1;
    foreach ($moreInstruments as $m){
        if($i==0){ // a first loop
            if($moreInstruments) $instrumentOutput .= " <span>instrument".$pluralMoreInstruments." containing</span> ";
        }
        $instrumentOutput .= "<em>".preg_replace('/^[ ]*/', '', $m)."</em>";    
        if($i != $last){ // if not a last loop
            $instrumentOutput .= ", ";
        }
        $i++;
    }
    
    /* Write out instruments in which $moreInstruments will search */
    
    $moreInstSearchSparql = $prefix."
    SELECT DISTINCT ?instName WHERE {
        {}";
        foreach ($moreInstruments as $m){
            $moreInstSearchSparql .= "
            UNION {
                ?inst foaf:name ?instName FILTER regex(?instName, \"".preg_replace('/^[ ]*/', '', $m)."\",\"i\") .
                ?artist :playsThe ?inst
            }
            ";
        }
    $moreInstSearchSparql .= "}";
    $moreInstSearchQuery = $store->query($moreInstSearchSparql);
    $moreInstSearch = $moreInstSearchQuery["result"]["rows"];
}


/* Error messages */

if($unknownInstrument){
    $last = count($unknownInstrument) - 1;
    foreach($unknownInstrument as $i => $u){
         $unknownInstrumentMsg .= "\"".$u."\"";
         if($i != $last) $unknownInstrumentMsg .= ", ";
    }
    $unknownInstrumentMsg .= " found, so let's seek without that, yup?";
}

if(count($instruments)==0){
    $instrumentsMismatch = 1;
    $errorMsg = "<h2>D'Oh!</h2> There is no artist or band who plays <em>".$instrument."</em>.";
}