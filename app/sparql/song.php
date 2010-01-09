<?php

/* Searching based on query from search input (search for song or album) */

if(!$interpret){

    /* If only a song is questioned */
    
    if($song && !$album){
        $songSparql = $prefix."
        SELECT ?artistName ?albumName ?trackName WHERE {
        {
            ?artist :hasAlbum ?album ;
                    foaf:name ?artistName .
            ?album :hasTrack ?track ;
                    foaf:name ?albumName .
            ?track foaf:name ?trackName FILTER regex(?trackName, \"".$song."\", \"i\")
        }
        UNION {
            ?artist :hasAlbum ?album ;
                    foaf:name ?artistName .
            ?album foaf:name ?albumName FILTER regex(?albumName, \"".$song."\", \"i\")
        } 
        }";
            
        $songQuery = $store->query($songSparql);
        $songs = $songQuery["result"]["rows"];
    }
    
    /* If song & album are questioned [not in search options now, but query is possible] */
    
    else {
    
    $songSparql = $prefix."
    SELECT DISTINCT ?artistName ?albumName ?trackName WHERE {
        ?artist :hasAlbum ?album ;
                foaf:name ?artistName .
        ?album :hasTrack ?track ;
                foaf:name ?albumName ";
        if($album) {
            $songSparql.="FILTER regex(?albumName, \"".$album."\", \"i\")";
        } else $songSparql.=".";
        
    $songSparql.="?track foaf:name ?trackName ";
        if($song) {
            $songSparql.="FILTER regex(?trackName, \"".$song."\", \"i\")";
        } else $songSparql.=".";
    
    $songSparql.="}";

    $songQuery = $store->query($songSparql);
    $songs = $songQuery["result"]["rows"];
    }
    
    
    /* Output */
    
    $songOutput = "Artists ";
    if($song) $songOutput .= "who sing <em>".$song."</em> ";
    if($album) {
        $song?$songOutput.="in":$songOutput.="with";
        $songOutput .= " album <em>".$album."</em>";
    }
    
    /* Error message */
    if (count($songs)==0) {
        $songMismatch = 1;
        $errorMsg = "<h2>Whoa!</h2> No artist ";
        if($song) $errorMsg.="sings <em>".$song."</em> ";
        if($album) {
            $song?$errorMsg.="in":$errorMsg.="with";
            $errorMsg.=" album <em>".$album."<em>.";
        }	
    }
}

/* Searching based on a link from already found song/album */

else if($interpret) {
    $songSparql = $prefix."
    SELECT ?artistName ?albumName ?trackName WHERE {
        ?artist :hasAlbum ?album ;
                foaf:name ?artistName FILTER regex(?artistName, \"".$interpret."\", \"i\")
        ?album :hasTrack ?track ;
                foaf:name ?albumName FILTER regex(?albumName, \"".$album."\", \"i\")
        ?track foaf:name ?trackName
    }
    ";
    $songQuery = $store->query($songSparql);
    $albums = $songQuery["result"]["rows"];

    $songOutput = "Album <em>".$album."</em> by ".$interpret;
    
    /* Error message */
    if (count($albums)==0) {
        $songMismatch = 1;
        $errorMsg = "<h2>Whoa!</h2> No artist <em>".$interpret."</em> has album <em>".$album."</em>.";	
    }
} 