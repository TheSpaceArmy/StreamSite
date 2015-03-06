//Initializing global variables and strings
var streamXML="";
var currentStream="";
var bannerHeight="";
var streamList='resources/data/dynamicStreamlist.php';

//Sets the channel
function setChannel(Channel)
{
	if(Channel!=="DEFAULT")
	{
		if(typeof $(streamXML).find('channel[id="'+Channel+'"]').attr('id') === "undefined")
		{
			setChannel("DEFAULT");
		}
		else
		{
			currentStream=Channel;
			window.location.hash = $(streamXML).find('channel[id="'+Channel+'"]').attr('id');
			$('.Button').each(function(button){
				$(this).removeClass("selected");
			});
			$('#'+Channel).addClass("selected");
			redrawPlayer();
			$('#PlayingTitle').html( $(streamXML).find('channel[id="'+Channel+'"]').find('ChannelMessage').text() );
		}
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
				if($(this).find('Live').text()=="1")
				{
					$("#"+$(this).attr('id')).addClass('live');
				}
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
	if(currentStream !== "")
	{
		$('#'+currentStream).addClass("selected");
	}
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
	$.get(streamList, function (data) {
		if(getXmlAsString(data)!==getXmlAsString(streamXML))
		{
			streamXML = data;
			redrawList();
			$('#PlayingTitle').html( $(streamXML).find('channel[id="'+currentStream+'"]').find('ChannelMessage').text());
		}
	});
	
	setTimeout(updateXMLAutomaton, 10000);
}

//Main Routine	
$(document).ready(function(){
	//set the channel list
	$.get(streamList, function (data) {
		streamXML = data;
		redrawList();
		updateXMLAutomaton(10000);
		if(window.location.hash) {
			setChannel(window.location.hash.substr(1));
		} else {
			setChannel("DEFAULT");
		}
	});
});