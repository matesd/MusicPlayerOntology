<!DOCTYPE html>
<html>
    <meta charset="utf-8" />
    <title>Ontology Design for Music Player | Martin Doubravsky | Bachelor Thesis @ DCGI, FEE, CTU, Prague</title>
    <meta name="robots" content="noindex,nofollow"/>
    <link rel="stylesheet" type="text/css" media="screen,projection" href="http://localhost/MusicPlayerOntology/document_root/css/mpo.css" />
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
<head>
</head>
<body>
<h1><span style="color:rgb(223, 13, 13);">[WORKING DRAFT]</span> Ontology design for music player</h1>
<h2>Author: Martin Doubravský</h2>

<form action="index.php" method="get">
    <fieldset>
        <label for="interpret">Interpret</label> <input type="text" id="interpret" name="interpret" />
        <input type="submit" value="Search" />
        <p>(For instance Michael Jackson, The Beatles, ..)</p>
    </fieldset>
</form>
<?php if($artist OR $band){
    if($moreInterprets){ ?>
        <h4>Who do you want to find?:</h4> 
        <?php
        foreach($possibleInterprets as $pos){
            print htmlspecialchars($pos["posIntName"])."<br/>";
        }
    } else {
        echo "<h2>".$theOnlyOneName."</h2>";
    ?>
    <div id="results">
        <div>
            <section>
                <h4><?php echo $theOnlyOneName ?>'s genres:</h4>
                <?php 
                if ($genres){
                    foreach ($genres as $g){
                        print htmlspecialchars($g["genreName"])."<br/>";
                    }
                }
                ?>
            </section>
            <section>
            <?php
                if($artist && $members){
                ?><h4>Member Of:</h4><?php
                    foreach ($members as $m){
                        print htmlspecialchars($m["memberName"])."<br/>";
                    }
                }
                if($band && $members){
                ?><h4>Members:</h4><?php
                    foreach ($members as $m){
                        print htmlspecialchars($m["memberName"])."<br/>";
                    }
                }
            ?>
            </section>
        </div>
        <div>
            <section>
                <h4>Similar artists:</h4>
                <?php
                if ($interprets){
                    foreach ($interprets as $i){
                        if(preg_match ('/SoloArtist$/',$i["type"])){?>
                            <a href="?interpret=<?php echo htmlspecialchars($i["artistName"])?>"><?php print htmlspecialchars($i["artistName"])?></a>
                            <?php
                            print htmlspecialchars($i["genresNo"])." mutual genres, ".$i["genresNo"]*100/count($genres)."% similarity]<br/>";
                        }
                    }
                }
                ?>
            </section>
            <section>
                <h4>Similar bands:</h4>
                <?php
                if ($interprets){
                    foreach ($interprets as $i){
                        if(preg_match ('/Band$/',$i["type"])){?>
                            <a href="?interpret=<?php echo htmlspecialchars($i["artistName"])?>"><?php print htmlspecialchars($i["artistName"])?></a>
                            <?php
                            print htmlspecialchars($i["genresNo"])." mutual genres, ".$i["genresNo"]*100/count($genres)."% similarity]<br/>";
                        }
                    }
                }
                ?>
            </section>
        </div>
    </div>
<?php }
} else echo $errorMsg;?>
<h3>Links</h3>

<ul>
    <li><a href="http://owl.cs.manchester.ac.uk/browser/manage/?action=load&clear=true&uri=http://martindoubravsky.cz/ctu/musicplayerontology.owl">Ontology online browser</a></li>
    <li><a href="http://martindoubravsky.cz/ctu/musicplayerontology.owl">Ontology source code</a></li>
    <li><a href="http://martindoubravsky.cz/ctu/musicplayerontology_h.png">Ontology image (horizontal)</a></li>
    <li><a href="http://martindoubravsky.cz/ctu/musicplayerontology_v.png">Ontology image (vertical)</a></li>
</ul>

<p>You can browse project on <a href="http://github.com/matesd/MusicPlayerOntology/tree/master">github</a> or install a git client and clone the repository with command <pre><code>git clone git://github.com/matesd/MusicPlayerOntology.git</code></pre></p>
<p>The ontology uses <a href="http://arc.semsol.org/">ARC</a> parser.</p>
 
<h3><a href="http://dcgi.felk.cvut.cz">Department of Computer Graphics and Interaction</a></h3>
<h3><a href="http://fel.cvut.cz">Faculty of Electrical Engineering</a></h3>
<h3><a href="http://www.cvut.cz">Czech Technical University in Prague</a></h3>

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