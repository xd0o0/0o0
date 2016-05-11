	$(function() {
        $("#toTop").scrollToTop(1000);
    });		
	
	function SetCookie(sName, sValue) 
    { 
        date = new Date(); 
        s = date.getDate(); 
        date.setDate(s+1);            //expire time is one month late!, and can't be current date! 
        document.cookie = sName + "=" + escape(sValue) + "; expires=" + date.toGMTString(); 
    } 
    function GetCookie(sName) 
    { 
        // cookies are separated by semicolons 
        var aCookie = document.cookie.split("; "); 
        for (var i=0; i < aCookie.length; i++) 
        { 
        // a name/value pair (a crumb) is separated by an equal sign 
        var aCrumb = aCookie[i].split("="); 
        if (sName == aCrumb[0]) { 
            return unescape(aCrumb[1]);} 
        } 
         
        // a cookie with the requested name does not exist 
        return null; 
    } 

    function fnLoad() 
    { 
        document.documentElement.scrollLeft = GetCookie("scrollLeft"); 
        document.documentElement.scrollTop = GetCookie("scrollTop"); 
    } 

    function fnUnload() 
    { 
        SetCookie("scrollLeft", document.documentElement.scrollLeft) 
        SetCookie("scrollTop", document.documentElement.scrollTop) 
    } 

    window.onload = fnLoad; 
    window.onunload = fnUnload; 
