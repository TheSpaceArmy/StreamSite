//Initializing global variables and strings
var streamXML="";
var currentStream="";
var bannerHeight="";

//Sets the channel
function setChannel(Channel)
{
	if(Channel!=="DEFAULT")
	{
		currentStream=Channel;
		window.location.hash = $(streamXML).find('channel[id="'+Channel+'"]').attr('id');
		$('.Button').each(function(button){
			$(this).removeClass("selected");
		});
		$('#'+Channel).addClass("selected");
		redrawPlayer();
		$('#PlayingTitle').html( $(streamXML).find('channel[id="'+Channel+'"]').find('ChannelMessage').text() );
	} else {
		setChannel( $(streamXML).find('channel[default="1"]').attr('id') );
	}
}
//Redraws Player
function redrawPlayer()
{
	var embed = $(streamXML).find('channel[id="'+currentStream+'"]').find('StreamEmbed').text();
	//add sizing stuff
	var width = $( "#Player" ).innerWidth();
	var height = $( "#Player" ).innerHeight();
	embed=embed.replace(/WIDVAR/g, width);
	if( $("#Banner").css('display') == 'none'){
		embed=embed.replace(/HEIVAR/g, height);
	} else {
		embed=embed.replace(/HEIVAR/g, height + bannerHeight);
	}
	$('#Player').html( embed );
}

//redraws the directory at the top
function redrawList()
{
	$('.ButtonContainer').html("");
	$(streamXML).find('channel').each(function() {
		if($(this).find('Status').text()=="Public")
		{
			if($(this).find('Type').text()== "Stream")
			{
				$('.ButtonContainer').append("<a class=\"Button\" id=\""+$(this).attr('id')+"\">"+$(this).find('ChannelName').text()+"</a>");
			}
			else if($(this).find('Type').text()== "Outlink")
			{
				$('.ButtonContainer').append("<a class=\"Button\" id=\""+$(this).attr('id')+"\" href=\""+$(this).find('Link').text()+"\" target=\"_blank\" style=\"color:inherit;\">"+$(this).find('ChannelName').text()+"</a>");
			}
		}
		
		if($(this).find('Type').text()=="Stream")
		{
			$( "#"+$(this).attr('id') ).click(function(){setChannel( $(this).attr('id') );});
		}
	});
}
//turns XML to string
//from IBM's Aleksandar Kolundzija. http://www.ibm.com/developerworks/xml/tutorials/x-processxmljquerytut/index.html
function getXmlAsString(xmlDom){
      return (typeof XMLSerializer!=="undefined") ? 
           (new window.XMLSerializer()).serializeToString(xmlDom) : 
           xmlDom.xml;
}
//constantly checks the XML for changes and acts in the event of changes
function updateXMLAutomaton()
{
	$.get('resources/data/streamlist.xml', function (data) {
		if(getXmlAsString(data)!==getXmlAsString(streamXML))
		{
			streamXML = data;
			redrawList();
		}
	});
	setTimeout(updateXMLAutomaton, 3000);
}
//Main Routine	
$(document).ready(function(){
	bannerHeight = $("#Banner").innerHeight();
	//give the banner it's special magic
	$("#Banner").hide();
	$("#Header").hover(function(){
		$("#Banner").toggle();
	});
	
	//set the channel list
	$.get('resources/data/streamlist.xml', function (data) {
		streamXML = data;
		redrawList();
		updateXMLAutomaton(3000);
		if(window.location.hash) {
			setTimeout(setChannel(window.location.hash.substr(1)),500);
		} else {
			setTimeout(setChannel("DEFAULT"),500);
		}
	});
	
	
});
$( window  ).resize(function(){
	redrawPlayer();
});
