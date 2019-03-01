function GetXmlHttpObject()
		{
			var xmlHttp=null;
			try
			{
			 // Firefox, Opera 8.0+, Safari
			 xmlHttp=new XMLHttpRequest();
			}
			catch(e)
			 {
			 //Internet Explorer
				 try
				  {
				  xmlHttp=new ActiveXObject("Msxml2.XMLHTTP");
				  }
				 catch (e)
				  {
				  xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
				  }
			  }
			 return xmlHttp;
		} 

function showState(str){
	//alert(str);
	//showMethod(str);
	xmlhttp=GetXmlHttpObject();	
	document.getElementById("statename").innerHTML= '';
			xmlhttp.onreadystatechange=function()
	{
		if (xmlhttp.readyState==4)
		{	
			//alert(xmlhttp.responseText);
			document.getElementById("statename").innerHTML=xmlhttp.responseText;
		}
	}
	//alert("pass.php?action=showState&countryId="+str);
	xmlhttp.open("GET","pass.php?action=showState&countryId="+str,true);
	xmlhttp.send(null);
}

function showStateMultiple(str){
	//alert(str);
	//showMethod(str);
	xmlhttp=GetXmlHttpObject();	
	document.getElementById("statename").innerHTML= '';
			xmlhttp.onreadystatechange=function()
	{
		if (xmlhttp.readyState==4)
		{	
			//alert(xmlhttp.responseText);
			document.getElementById("statename").innerHTML=xmlhttp.responseText;
		}
	}
	//alert("pass.php?action=showState&countryId="+str);
	xmlhttp.open("GET","pass.php?action=showStateMULTIPLE&countryId="+str,true);
	xmlhttp.send(null);
}



