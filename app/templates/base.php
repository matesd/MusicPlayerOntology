<!DOCTYPE html>
<html>
    <meta charset="utf-8" />
    <title>The Ontology Design for Music Player | Martin Doubravsky | Bachelor Thesis @ FEE, CTU, Prague</title>
    <meta name="robots" content="noindex,nofollow"/>
    <link rel="stylesheet" type="text/css" media="screen,projection" href="css/mpo.css" />
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
<head>
</head>
<body>
<h1>Music Player Ontology</h1>
<h2>Author: Martin Doubravský</h2>

<form action="index.php" method="get">
    <fieldset>
        <label for="genre">Genre</label><input type="text" id="genre" name="genre" />
        <input type="submit" value="Find interprets with this genre!" />
        <p>(For instance pop,pop/rock,grunge)</p>
    </fieldset>
</form>
<div>

<?php 

$rows = $result["result"]["rows"];
if ($rows) {
    foreach ($rows as $row) {
        print "<div>". htmlspecialchars($row["name"])."</div>";
    }
}

?>

</div>

<h3>Links</h3>
<ul>
    <li><a href="http://owl.cs.manchester.ac.uk/browser/manage/?action=load&clear=true&uri=http://martindoubravsky.cz/ctu/musicplayerontology.owl">Ontology online browser</a></li>
    <li><a href="http://martindoubravsky.cz/ctu/musicplayerontology.owl">Ontology source code</a></li>
    <li><a href="http://martindoubravsky.cz/ctu/musicplayerontology_h.png">Ontology image (horizontal)</a></li>
    <li><a href="http://martindoubravsky.cz/ctu/musicplayerontology_v.png">Ontology image (vertical)</a></li>
</ul>

<p>You can browse project on <a href="http://github.com/matesd/MusicPlayerOntology/tree/master">github</a> or install a git client and clone the repository with command <pre><code>git clone git://github.com/matesd/MusicPlayerOntology.git</code></pre></p>
<p>The ontology uses <a href="http://arc.semsol.org/">ARC</a> parser.</p>
 
 
<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
try {
var pageTracker = _gat._getTracker("UA-8703001-2");
pageTracker._trackPageview();
} catch(err) {}</script>
</body>
</html>