function format(obj,withfunc){
    setTimeout(function(){
		obj.value = withfunc(obj.value);					
	},1)
}

function data_format(v){
	v=v.replace(/\D/g,"");  
	v=v.replace(/^(\d{2})(\d)/,"$1/$2");            
	v=v.replace(/(\d{2})(\d)/,"$1/$2");   
return v
}

function reverse(s){
	var splitext = s.split("");
	var revertext = splitext.reverse();
	return revertext.join("");
}

function formatGridDateField(o){
	format(o,data_format)	
	setTimeout(function(){
		
		var hidden = document.getElementById("date_hidden_" + o.id.replace(/^date_/,""));
		// o.value = 10/10/1991  to  hidden.value = 1991-10-10
		var arr = o.value.split("/");
		if(arr.length == 1) hidden.value = "0000-00-" + arr[0];
		else if(arr.length == 2) hidden.value = "0000-" + arr[1] + "-" + arr[0];
		else hidden.value = arr[2] + "-" + arr[1] + "-" + arr[0] + "";
	
	},50);
}