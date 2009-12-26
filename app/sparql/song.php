<?php

$songSparql = $prefix."
SELECT ?artistName ?albumName ?trackName WHERE {
{
    ?artist :hasAlbum ?album ;
            foaf:name ?artistName .
    ?album :hasTrack ?track ;
            foaf:name ?albumName .
    ?track foaf:name ?trackName FILTER regex(?trackName, \"".htmlspecialchars($song)."\", \"i\")
}
UNION {
    ?artist :hasAlbum ?album ;
            foaf:name ?artistName .
    ?album foaf:name ?albumName FILTER regex(?albumName, \"".htmlspecialchars($song)."\", \"i\")
}
}
";
$songQuery = $store->query($songSparql);
$songs = $songQuery["result"]["rows"];

/* Error message */
if (count($songs)==0) {
    $songMismatch = 1;
    $errorMsg = "<h2>Whoa!</h2> You made it up? No artist sing <em>\"".htmlspecialchars($song)."\"</em> song.";	
}