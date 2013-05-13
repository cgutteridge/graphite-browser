<?php

# Q&D RDF Browser (c) 2010 Christopher Gutteridge & University of Southampton
# Released under GPL 2.0
# cjg@ecs.soton.ac.uk
# No warrenty etc.
# Ask if you want a different license.

$next_id = 0;

if( isset( $_GET["uri"] ) )
{
	$robot = 0;
	if( preg_match( '/Googlebot/', $_SERVER["HTTP_USER_AGENT"] ) ) { $robot=1;}
	if( $robot )
	{
		header("HTTP/1.0 403 We don't serve their type in here" );
		print "You'll have to leave your droids outside.";
		exit; 
	}
}

header( "Content-type: text/html; charset:utf-8" );
require_once( "arc/ARC2.php" );
require_once( "Graphite/Graphite.php" );
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
 "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
   <title>Q&amp;D RDF Browser</title>
   <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
   <link rel="stylesheet" href="browser.css" type="text/css"></link>
</head>
<body>
<script>
function more(id) {
	if (document.getElementById(id+'-more').style.display != 'none') {
		document.getElementById(id+'-more').style.display = 'none';
		document.getElementById(id).style.display = 'inline';
	}
}
</script>

<?php

if( ! isset( $_GET["uri"] ) )
{
?>
<style>
body { text-align: center; }
</style>
<p><strong>RDF Browser</strong> | <a href='/sparqlbrowser/'>SPARQL Browser</a></p>
<h1>Quick and Dirty RDF browser</h1>
<form>

<table width='80%' style='margin:auto'>
<tr><td align='right'>URI:</td><td width='100%'><input id='uri' name='uri' value='' style='width:100%' /></td></tr>
</table>

<input style='margin-top:0.5em' value='Browse RDF' type='submit' /></form>

<p>Here are some suggestions:</p>
<table style='text-align:left; margin:auto'><tr><td>
<?php
	$d = array(
'http://education.data.gov.uk/id/school/118217',
'http://eprints.ecs.soton.ac.uk/id/eprint/10053',
'http://id.southampton.ac.uk/building/59',
'http://data.totl.net/playingcards/',
'http://dbpedia.org/resource/Southampton',
);
	foreach( $d as $URI )
	{
		print "<div style='margin-bottom:0.5em'>&bull; <a href='http://graphite.ecs.soton.ac.uk/browser/?uri=$URI'>$URI</a></div>";
	}
?>
</td></tr></table>
</p>
</div>
</td></tr>
<tr><td style="font-size: 90%;" align="center" valign="bottom">
<p>

<p>
Drag this <a class="bookmarklet" href='javascript:window.location = "http://graphite.ecs.soton.ac.uk/browser/?uri="+encodeURIComponent(window.location.href);'>Q&amp;D-RDF</a> bookmarklet to your bookmarks to create a quick button for sending your current URL to the Q&amp;D RDF browser.
</p>
<p style='font-size: 80%'>Q&amp;D RDF Browser is powered by <a href='/'>Graphite</a> and <a href='http://arc.semsol.org/'>ARC2</a> and hosted by <a href='http://www.ecs.soton.ac.uk/'>ECS</a> at the <a href='http://www.soton.ac.uk/'>University of Southampton</a>.</p>
<p style='font-size: 80%'>GPL Source code: <a href='http://graphite.ecs.soton.ac.uk/download.php/browser.php'>browser.php</a> plus you'll need the <a href='namespaces'>namespaces</a> file and the <a href='http://graphite.ecs.soton.ac.uk/download.php/Graphite_and_ARC2.tgz'>ARC2 &amp; Graphite</a> libraries.</p>
<table style='font-size: 80%; margin: auto; text-align:left'>
<tr><td><tt>
&lt;<a style='text-decoration: none; color: green; ' href='http://graphite.ecs.soton.ac.uk/browser/'>http://graphite.ecs.soton.ac.uk/browser/</a>&gt; 
foaf:maker 
&lt;<a style='text-decoration: none; color: green; ' href='http://id.ecs.soton.ac.uk/person/1248'>http://id.ecs.soton.ac.uk/person/1248</a>&gt; .
</tt></td></tr>
<tr><td><tt>
&lt;<a style='text-decoration: none; color: green; ' href='http://id.ecs.soton.ac.uk/person/1248'>http://id.ecs.soton.ac.uk/person/1248</a>&gt; foaf:name "Christopher Gutteridge" .
</tt></td></tr>

<tr><td><tt>
&lt;<a style='text-decoration: none; color: green; ' href='http://graphite.ecs.soton.ac.uk/browser/'>http://graphite.ecs.soton.ac.uk/browser/</a>&gt; 
rdfs:seeAlso
&lt;<a style='text-decoration: none; color: green; ' href='http://graphite.ecs.soton.ac.uk/sparqlbrowser/'>http://graphite.ecs.soton.ac.uk/sparqlbrowser/</a>&gt; .
</tt></td></tr>

<tr><td><tt>
&lt;<a style='text-decoration: none; color: green; ' href='http://graphite.ecs.soton.ac.uk/browser/'>http://graphite.ecs.soton.ac.uk/browser/</a>&gt; 
rdfs:seeAlso
&lt;<a style='text-decoration: none; color: green; ' href='http://graphite.ecs.soton.ac.uk/checker/'>http://graphite.ecs.soton.ac.uk/checker/</a>&gt; .
</tt></td></tr>

</table>
</table>


</body></html>
<script type="text/javascript">document.getElementById('uri').focus()</script>
<?php
	exit;
}

$uri = $_GET['uri'];
$graph = new Graphite();
$graph->setDebug( true );
foreach( file('namespaces') as $line )
{
	if( preg_match( '/^\s*#/' , $line ) ) { continue; }
	# @prefix foaf:       <http://xmlns.com/foaf/0.1/> .
	if( preg_match( '/^\s*@prefix\s+([^:]+):\s+<([^>]+)/', $line, $bits ) )
	{
		$graph->ns( $bits[1], $bits[2] );
	}
}
$n = $graph->load( $uri );
print "<h1 style='margin:0px'>".mid_trim($uri,80)." <small>[<a href='$uri'>view</a>]</small></h1>";
print "<p style='font-size: 80%'><a href='/browser/'>Q&amp;D RDF Browser</a> is powered by <a href='/'>Graphite</a> and <a href='http://arc.semsol.org/'>ARC2</a> and hosted by <a href='http://www.ecs.soton.ac.uk/'>ECS</a> at the <a href='http://www.soton.ac.uk/'>University of Southampton</a>.</p>";
print "<p style='margin:0px'>$n Triples</p>";

if( $n == 0 )
{
	print "<p>ZERO triples? Chances are your RDF is b0rken!</p>";
	print "<p style='font-size:150%'>Perhaps <a href='http://www.w3.org/RDF/Validator/ARPServlet?URI=".urlencode($uri)."&PARSE=Parse+URI%3A+&TRIPLES_AND_GRAPH=PRINT_TRIPLES&FORMAT=PNG_EMBED'>running it through the W3.org validator</a> would be a good idea?</p>";
	print "</body></html>";
	exit;
}
$dump = dumpGraph( $graph , $uri );
$dump = preg_replace( "/ href='(_:[^']*)'/"," href='#$1'", $dump );

print preg_replace( "/ href='([^#][^']*)'/e",'" href=\'http://graphite.ecs.soton.ac.uk/browser/?uri=".urlencode("$1")."#$1\'"',$dump );
?>
</body></html>






<?php

function LatLonPointUTMtoLL($e, $n )
{
	require_once( "gpoint.php" );

	$myHome =& new gPoint();    // Create an empty point 

    	#$myHome->setUTM( $e, $n, "" );    // Easting/Northing from a GPS 
    	$myHome->setUTM( $e, $n,"U30" );
	$myHome->convertTMtoLL();
	
    echo "Which converts back to: "; $myHome->printLatLong(); echo "<br>"; 
	return array('lat'=>substr($myHome->Lat(),0,8),'lng'=>substr($myHome->Long(),0,8) );
}


# trims out middle characters and returns HTML to insert into a webpage.
function mid_trim( $string, $max )
{
	if( strlen( $string ) > $max )
	{
		return htmlspecialchars(substr($string,0,$max/2)).'<span style="color:#ccc">...</span>'.htmlspecialchars(substr( $string,strlen($string)+3-$max/2,$max/2-3));
	}
	return htmlspecialchars($string);
}


# These functions are copied from the core Graphite code, so we can modify them locally
function dumpGraph( $graph, $uri )
{
	$r1 = array(); # items with $uri as the prefix
	$r2 = array(); 
	$subjects = $graph->t["sp"];
	ksort( $subjects );
		
	foreach( $subjects as $subject_uri=>$dummy )
	{
		$subject = new Graphite_Resource( $graph, $subject_uri );
		if( strpos( $subject_uri, $uri ) === 0)
		{
			# first list items which are the uri, or have the uri
			# as their prefix
			$r1 []= dumpResource( $subject );
		}
		else
		{
			$r2 []= dumpResource( $subject );
		}
	}
	return join("",$r1 ).join( "",$r2);
}
function dumpResource( $resource )
{
	$r = "";
	$plist = array();
	foreach( $resource->relations() as $prop )
	{
		$olist = array();
		$all = $resource->all( $prop );
		foreach( $all as $obj )
		{
			if( is_a( $obj, "Graphite_Literal" ) )
			{
				$olist []= dumpLiteralValue( $obj );
			}
			else
			{
				$olist []= dumpValue( $obj );
			}
		}
		if( is_a( $prop, "Graphite_InverseRelation" ) )
		{
			$pattern = "<span class='arrow'>&larr;</span> is <a class='inverseRelation' title='%s' href='%s'>%s</a> of <span class='arrow'>&larr;</span> %s";
		}
		else
		{
			$pattern = "<span class='arrow'>&rarr;</span> <a title='%s' class='relation' href='%s'>%s</a> <span class='arrow'>&rarr;</span> %s";
		}
		$prop = $prop->toString();
		$MAX_OBJECTS = 4;
		if( sizeof( $olist ) > $MAX_OBJECTS )
		{
			$headlist = array_splice( $olist, 0, $MAX_OBJECTS );
			global $next_id;
			$id = $next_id++;
			
			$values = "".join( ", ",$headlist );
			$values.= ", <a id='{$id}-more' onclick='more(\"$id\")' class='more'> ...show ".sizeof($olist)." more...</a>";
			$values.= "<span id='{$id}' style='display:none'>". join( ", ",$olist )."</span>";
		}
		else
		{	
			$values = join( ", ",$olist );
		}
		$plist []= sprintf( $pattern, htmlentities($prop), htmlentities($prop), htmlentities($resource->g->shrinkURI($prop)), $values );
	}
	$r.= "\n<a name='".htmlentities($resource->uri)."'></a><div class='resourceBox'>\n";
	$label = $resource->label();
	if( $label == "[NULL]" ) { $label = ""; } else { $label = "<strong>".htmlentities($label)."</strong>"; }
	if( $resource->has( "rdf:type" ) )
	{
		if( $resource->get( "rdf:type" )->hasLabel() )
		{
			$typename = $resource->get( "rdf:type" )->label();
		}
		else
		{
			$bits = preg_split( "/[\/#]/", @$resource->get( "rdf:type" )->uri );
			$typename = array_pop( $bits );
			$typename = preg_replace( "/([a-z])([A-Z])/","$1 $2",$typename );
		}
		$r .= preg_replace( "/>a ([AEIOU])/i", ">an $1", "<div style='float:right'>a ".htmlentities($typename)."</div>" );
	}

	if( $label != "" ) { $r.="<div>$label</div>"; }

	$lat = null; $long = null;
	if( $resource->has( "geo:lat" ) ) { $lat = $resource->getString( "geo:lat" ); }
	if( $resource->has( "geo:long" ) ) { $long = $resource->getString( "geo:long" ); }
	if( $resource->has( "vcard:geo" ) )
	{	
		$geo = $resource->get( "vcard:geo" );
		if( $geo->has( "vcard:latitude" ) ) { $lat = $geo->getString( "vcard:latitude" ); }
		if( $geo->has( "vcard:longitude" ) ) { $long = $geo->getString( "vcard:longitude" ); }
	}

	if( false && $resource->has( "spatialrelations:easting" ) && $resource->has( "spatialrelations:northing" ) )
	{
		$ll = LatLonPointUTMtoLL(
			$resource->get( "spatialrelations:easting" )->toString(),
			$resource->get( "spatialrelations:northing" )->toString()
		);
		$lat = $ll["lat"];
		$long = $ll["lng"];
		print_r( $ll );
	}

	if( isset( $lat ) && isset( $long ) )
	{
		$llstr = "$lat,$long";
		$r.= '<img style="margin: 0em 0em 0.5em 1em; float:right;clear:right" width="250" height="250" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="http://maps.google.com/maps/api/staticmap?center='.$llstr.'&size=250x250&maptype=hybrid&sensor=false&markers=color:blue|label:X|'.$llstr.'" />';
	}
	elseif( $resource->has( "foaf:img", "foaf:depiction", "foaf:logo" ) )
	{
		$img = $resource->get( "foaf:img", "foaf:depiction", "foaf:logo" );
		if( $img->has( "foaf:thumbnail" ) ) { $img = $img->get("foaf:thumbnail"); }
		$r.= '<a href="'.$img->toString().'"><img style="margin: 0em 0em 0.5em 1em; border:0px;float:right;clear:right;max-width:200px" src="'.$img->toString().'" /></a>';
	}	

	$r.= "<div><a title='".htmlentities($resource->uri)."' href='".htmlentities($resource->uri)."' style='text-decoration:none'>".htmlentities($resource->g->shrinkURI($resource->uri))."</a></div>\n";
	$r.="  <div class='resourcePropertyRows'>\n  <div class='resourcePropertyRow'>".join( "</div>\n  <div class='resourcePropertyRow'>", $plist )."</div></div><div style='clear:both;height:1px; overflow:hidden'>&nbsp;</div></div>";
	return $r;
}

function dumpLiteralValue( $resource )
{
	$v = htmlspecialchars( $resource->triple["v"],ENT_COMPAT,"UTF-8" );
	$v = preg_replace( "/\t/", "<span class='specialChar'>[tab]</span>", $v );
	$v = preg_replace( "/\n/", "<span class='specialChar'>[nl]</span><br />", $v );
	$v = preg_replace( "/\r/", "<span class='specialChar'>[cr]</span>", $v );
	$v = preg_replace( "/  +/e", "\"<span class='specialChar'>\".str_repeat(\"␣\",strlen(\"$0\")).\"</span>\"", $v );
	$r = '"'.$v.'"';

	if( isset($resource->triple["l"]) && $resource->triple["l"])
	{
		$r.="<span class='lang'>@".$resource->triple["l"].'</span>';
	}
	if( isset($resource->triple["d"]) )
	{
		$r.="<span class='datatype'>^^".$resource->g->shrinkURI($resource->triple["d"]).'</span>';
	}
	return "<span class='literalObject'>$r</span>";
}
function dumpValue( $resource )
{
	$label = $resource->dumpValueText();
	if( $resource->hasLabel() )
	{
		$label = $resource->label();
	}
	$href = $resource->uri;
	#$href = "#".htmlentities($resource->uri);
	return "<a href='".$href."' title='".$resource->uri."' class='resourceObject'>".$label."</a>";
}
