function toUrlString(obj){
	var k=0;
    var url = "";
    for (var i in obj) {
        if (obj.hasOwnProperty(i)) {
            if(k>0){
               url += "&";
            }
            url += i + "="+obj[i];
            k++;
        }
    }
    return url;
}

function createRequestObject() {
  var reqObj;
  var browser = navigator.appName;
  if(browser == "Microsoft Internet Explorer"){ 
    reqObj = new ActiveXObject("Microsoft.XMLHTTP");
  }
  else{
    reqObj = new XMLHttpRequest();
  }

  return reqObj;
}

function requestHttp(request,method,url,param,response,asynch){
   request.open(method,url,asynch);
   request.onreadystatechange = function(){
      if(request.readyState == 4){
         
		 response(request);
	  }
   };
   request.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
   request.send(param);
}

function ajaxRequest(obj){
   requestHttp(createRequestObject(),obj.method,obj.url,toUrlString(obj.params),obj.success?obj.success:function(){},typeof(obj.asynch)!="undefined"?obj.asynch:true);
}