<?php
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header('Content-type: application/xml');

require "config.php";

function isLive($status,$app,$stream)
{
	/* Checks if stream is live on an nginx stat page
	 * @inputs: $status(xml),$app(str),$stream(str)
	 * @outputs: returns 1 if stream is live, 0 if not*/
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
function streamViewers($status,$app,$stream)
{
	/* Checks if stream is live on an nginx stat page
	 * @inputs: $status(xml),$app(str),$stream(str)
	 * @outputs: returns stream view count*/
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
$statXML = simplexml_load_file($serverStat);
echo '<?xml version="1.0" encoding="UTF-8"?>';
echo "\n<ChannelList>\n";

foreach($streamlist as $stream)
{
	echo "\t<channel id=\"".$stream["id"]."\" default=\"".$stream["default"]."\">\n";
	if(isLive($statXML,"live",$stream["id"]))
	{
		echo "\t\t<ChannelName>".$stream["name"]." (".streamViewers($statXML,"live",$stream["id"]).")</ChannelName>\n";
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
		echo "\t\t<Live>".isLive($statXML,"live",$stream["id"])."</Live>\n";
	}
	if($stream["type"]=="Outlink")
	{
		echo "\t\t<Link>".$stream["url"]."</Link>\n";
		echo "\t\t<Target>".$stream["target"]."</Target>\n";
	}
	echo "\t</channel>\n";
}
echo "</ChannelList>";