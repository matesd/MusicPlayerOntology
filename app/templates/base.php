<!DOCTYPE html>
<html>
    <meta charset="utf-8" />
    <title>Ontology Design for Music Player | Martin Doubravsky | Bachelor Thesis @ DCGI, FEE, CTU, Prague</title>
    <meta name="robots" content="noindex,nofollow"/>
    <link rel="stylesheet" type="text/css" media="screen,projection" href="css/mpo.css" />
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
<head>
</head>
<body>
<h1><span style="color:rgb(223, 13, 13);">[WORKING DRAFT]</span> Ontology design for music player</h1>
<h2>Author: Martin Doubravský</h2>
<h3><a href="http://dcgi.felk.cvut.cz">Department of Computer Graphics and Interaction</a></h3>
<h3><a href="http://fel.cvut.cz">Faculty of Electrical Engineering</a></h3>
<h3><a href="http://www.cvut.cz">Czech Technical University in Prague</a></h3>

<form action="index.php" method="get">
    <fieldset>
        <label for="interp">Interpret</label> <input type="text" id="interp" name="interp" />
        <input type="submit" value="Search" />
        <p>(For instance Michael Jackson, The Beatles, ..)</p>
    </fieldset>
</form>
<?php if($interp) { ?>
<div>
<h4><?php echo $interp ?>'s genres:</h4>
<?php 
if ($genres) {
    foreach ($genres as $genre) {
        print htmlspecialchars($genre["genreName"])."<br/>";
    }
}
?>
<h4>Interprets with at least one of <?php echo $interp ?>'s genres:</h4>
<?php 
print "<div>SPARQL interprets:<br/>".$interpretsSparql."</div><br/>";

if ($interprets) {
    foreach ($interprets as $interpret) {
        print htmlspecialchars($interpret["artistName"])." : ".htmlspecialchars($interpret["genreName"])."<br/>";
    }
}
?>

</div>
<?php } ?>
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
var pageTracker = _gat._getTracker("UA-11847658-2");
pageTracker._trackPageview();
} catch(err) {}</script>
</body>
</html>