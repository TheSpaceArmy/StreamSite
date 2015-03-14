<?php
//server statistics page
$serverStat = "http://localhost/stat";

//channel information
$streamlist = array
(
	/* formats:
	 * stream format:(id, nginxApps, password, default, name, type, status, message, embed)
	 * out-link format:(id, default, name, type, status, URL, target)
	 *
	 * Note: nginxApps is a string containing the different nginx-RTMP apps that house
	 * public-viewable streams delimited by semi-colons, and nopass on password indicates
	 * that the stream does not need a password
	 */
	array("id"=>"ch1",
	      "password"=>"Chan1Password",
	      "default"=>1,
	      "name"=>"Channel One",
	      "type"=>"Stream",
	      "status"=>"Public",
	      "message"=>"Channel One: It's what's cooking.",
	      "embed"=>"<![CDATA[ <iframe width=\"100%\" height=\"100%\" src=\"http://www.youtube.com/embed/yaqe1qesQ8c?rel=0&autoplay=1\" frameborder=\"0\" allowfullscreen></iframe>]]>"),
	array("id"=>"ch2",
	      "password"=>"Chan2Password",
	      "default"=>0,
	      "name"=>"Channel Two",
	      "type"=>"Stream",
	      "status"=>"Public",
	      "message"=>"Perhaps Breakfast",
	      "embed"=>"<![CDATA[ <img src=\"http://placehold.it/WIDVARxHEIVAR\">]]>"),
	array("id"=>"ch3",
	      "password"=>"Chan3Password",
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