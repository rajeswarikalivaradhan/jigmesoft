var loading_div = ('<div class="bs-card-loading-overlay"><i class="bs-card-loading-icon fa fa-spinner fa-spin fa-2x text-white"></i></div>');
function MakePostRequest(url,parameters,datatype,FnManageList) {
	$.ajax({
		type: 'POST',
		cache: false,
		async: false,
		contentType: 'application/x-www-form-urlencoded',
		url: url,
		data: parameters,
		dataType: datatype,
		success:FnManageList,
		error:FnErrcallback
	});
}

function MakeGetRequest(url,parameters,datatype,FnManageList) {
	$.ajax({
		type: 'GET',
		cache: false,
		async: false,
		contentType: 'application/x-www-form-urlencoded',
		url: url,
		data: parameters,
		dataType: datatype,
		success:FnManageList,
		error:FnErrcallback
	});
}

function MakeAsynPostRequest(url,parameters,datatype,FnManageList,loader) {
	$.ajax({
		type: 'POST',
		url: url,
		beforeSend: function(){
			if(loader)
				$("#"+loader).append(loading_div);
		},
		complete: function(){
			if(loader)
				$("#"+loader+' > div.bs-card-loading-overlay').remove();
		},
		data: parameters,
		dataType: datatype,
		success:FnManageList,
		error:FnErrcallback
	});
}

function FnErrcallback(jqXHR, textStatus, errorThrown) {
//alert('err');
	 console.log(jqXHR);
	 //console.log(textStatus, errorThrown);
}
