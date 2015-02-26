<?php
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header('Content-type: application/xml');

/*Global Variables*/
//status: the location of the NGINX RTMP status page
$serverStat = "http://localhost/servstat";
$streamlist = array
(
	/* formats:
	 * stream format:(id, default, name, type, status, message, embed)
	 * out-link format:(id, default, name, type, status, URL, target)
	 */
	array("id"=>"ch1",
	      "default"=>1,
		  "name"=>"Channel One",
		  "type"=>"Stream",
		  "status"=>"Public",
		  "message"=>"Channel One: It's what's cooking.",
		  "embed"=>"<![CDATA[ <iframe width=\"100%\" height=\"100%\" src=\"http://www.youtube.com/embed/yaqe1qesQ8c?rel=0&autoplay=1\" frameborder=\"0\" allowfullscreen></iframe>]]>"),
	array("id"=>"ch2",
	      "default"=>0,
	      "name"=>"Channel Two",
		  "type"=>"Stream",
		  "status"=>"Public",
		  "message"=>"Perhaps Breakfast",
		  "embed"=>"<![CDATA[ <img src=\"http://placehold.it/WIDVARxHEIVAR\">]]>"),
	array("id"=>"ch3",
	      "default"=>0,
		  "name"=>"Channel Three",
		  "type"=>"Stream",
		  "status"=>"Public",
		  "message"=>"Lunch, maybe?",
		  "embed"=>"<![CDATA[<iframe width=\"100%\" height=\"100%\" src=\"http://www.youtube.com/embed/SDmuzMgMsc0?rel=0&autoplay=1\" frameborder=\"0\" allowfullscreen></iframe>]]>"),
	array("id"=>"lnk1",
	      "default"=>0,
		  "name"=>"example.com",
		  "type"=>"Outlink",
		  "status"=>"Public",
		  "url"=>"http://www.example.com",
		  "target"=>"_blank")
);

function isLive($url,$app,$stream)
{
	/* Checks if stream is live on an nginx stat page
	 * @inputs: $url(str),$app(str),$stream(str)
	 * @outputs: returns 1 if stream is live, 0 if not*/
	 $status = simplexml_load_file($url);
	foreach($status->server->application as $application)
	{
		if ($application->name==$app)
		{
			foreach($application->live->stream as $str)
			{
				if ($str->name == $stream)
				{
					if($str->active){return 1;}
						else{return 0;}
				}
			}
		}
	}
}
function streamViewers($url,$app,$stream)
{
	/* Checks if stream is live on an nginx stat page
	 * @inputs: $url(str),$app(str),$stream(str)
	 * @outputs: returns stream view count*/
	 $status = simplexml_load_file($url);
	foreach($status->server->application as $application)
	{
		if ($application->name==$app)
		{
			foreach($application->live->stream as $str)
			{
				if ($str->name == $stream)
				{
					//1 is subtracted because one of the connected clients will be the broadcaster
					return $str->nclients-1;
				}
			}
		}
	}
}


echo '<?xml version="1.0" encoding="UTF-8"?>';
echo "\n<ChannelList>\n";

foreach($streamlist as $stream)
{
	echo "\t<channel id=\"".$stream["id"]."\" default=\"".$stream["default"]."\">\n";
	if(isLive($serverStat,"live",$stream["id"]))
	{
		echo "\t\t<ChannelName>".$stream["name"]." (".streamViewers($serverStat,"live",$stream["id"]).")</ChannelName>\n";
	}
	else
	{
		echo "\t\t<ChannelName>".$stream["name"]."</ChannelName>\n";
	}
	echo "\t\t<Type>".$stream["type"]."</Type>\n";
	echo "\t\t<Status>".$stream["status"]."</Status>\n";
	if($stream["type"]=="Stream")
	{
		echo "\t\t<ChannelMessage>".$stream["message"]."</ChannelMessage>\n";
		echo "\t\t<StreamEmbed>".$stream["embed"]."</StreamEmbed>\n";
		echo "\t\t<Live>".isLive($serverStat,"live",$stream["id"])."</Live>\n";
	}
	if($stream["type"]=="Outlink")
	{
		echo "\t\t<Link>".$stream["url"]."</Link>\n";
		echo "\t\t<Target>".$stream["target"]."</Target>\n";
	}
	echo "\t</channel>\n";
}
echo "</ChannelList>";