 
    var managementJSON;
    const activeBtn = document.getElementById('btn-active');
    const inactiveBtn = document.getElementById('btn-inactive');  
$(document).ready(function() {
   

    var managementJSON;
	$.when(getManagementList()).done(function(){
		dispDetails(managementJSON);		
	});

    $(document).ajaxStart(function(a){
        $.LoadingOverlay("show",{image: "../assets/img/fullpage.gif"});
    });
    $(document).ajaxStop(function(){
        $.LoadingOverlay("hide");
    });

	function getManagementList()
	{
		return $.ajax({
			url: base_path+'management/getEnquiryIORList',
			type:'POST',
			success:function(data){
				managementJSON = $.parseJSON(data);
			},		
			error: function() {
				console.log("Error");  
			}
		});
	}

	function dispDetails(managementJSON)
	{
		if ( $.fn.DataTable.isDataTable('#orderEnquiryListTbl') ) {
		  $('#orderEnquiryListTbl').DataTable().destroy();
		}

		$('#orderEnquiryListTbl tbody').empty();	
		$("#orderEnquiryListTbl").dataTable({
			"aaData": managementJSON,
			"pageLength": 50,
			"aoColumns": [			
				{
			      "mDataProp": function(data, type, full, meta) {
			        return '<input type="checkbox" class="allcbox" id="'+data.id+'">';
			      }
			    },		
				{ "mDataProp": "orderenqrefno" },						
				{ "mDataProp": "stylenamerefno" },	
				{ "mDataProp": "brandname" },
				{ 
					"mDataProp": function ( data, type, full, meta) {
                       // commeted by me var d = new Date(data.formattedDateCreated);
                        var d=(data.reqdatetime!==null && data.reqdatetime !=='0000-00-00 00:00:00')?new Date(data.reqdatetime):'';
                        var time = (d!=='')? d.toLocaleString('en-US', { hour: '2-digit', minute: '2-digit', hour12: true }) : '';
                        var dFormat =  (d!=='')? ("0" + (d.getDate())).slice(-2) + '/' + ("0" + (d.getMonth() + 1)).slice(-2) + '/' + d.getFullYear() + ' ' + time:'-';
						return dFormat;
					}
				},
				{ 
					"mDataProp": function ( data, type, full, meta) {
                        return '<a class="boldfont" href="' + base_path + 'management/enquiryview' + '/' + encodeURIComponent(btoa(data.id)) + '">' + data.isr_ior + '</a>';
					}
				},														
				{ "mDataProp": "enquirytype" },	
				{ "mDataProp": "totalcomponents" },		
				{ "mDataProp": "totalcombo" },											
													
				// { 
				// 	"mDataProp": function ( data, type, full, meta) {
                //         var ARRCURRENCYLIST = ['Select', 'INR', 'SGD', 'HKD', 'MYR', 'USD', 'EUR', 'JPY', 'GBP', 'AUD', 'CAD', 'CHF', 'CNH', 'SEK', 'NZD'];
                //         return ARRCURRENCYLIST[data.currency];
				// 	}
				// },							
				{ 
					"mDataProp": function ( data, type, full, meta) {
                        var ORDERENQUIRYSTATUS = ['-', 'PENDING', 'APPROVED', 'DECLINED', 'PENDING-RR'];
                        if(data.orderstatus == '0')
                        return ORDERENQUIRYSTATUS[data.orderstatus];
                        else if(data.orderstatus == '1')
                        return '<span class="text-light knOrangeColor bg-dark"><strong>' + ORDERENQUIRYSTATUS[data.orderstatus] + '</strong></span>';
                        else if(data.orderstatus == '2')
                        return ORDERENQUIRYSTATUS[data.orderstatus];
                        else if(data.orderstatus == '3')
                        return '<span class="knRedColor"><strong>' + ORDERENQUIRYSTATUS[data.orderstatus] + '</strong></span>';
                        else if(data.orderstatus == '4')
                        return '<span class="p-0 mb-0 knOrangeColor text-dark"><strong>' + ORDERENQUIRYSTATUS[data.orderstatus] + '</strong></span>';
					}
				},								
				{ 
					"mDataProp": function ( data, type, full, meta) {
                        var d =(data.dateauthorized !==null && data.dateauthorized !=='0000-00-00 00:00:00')?new Date(data.dateauthorized):''; 
                         var time = (d!=='')?d.toLocaleString('en-US', { hour: '2-digit', minute: '2-digit', hour12: true }):'';
                        var dFormat =(d!=='')?("0" + (d.getDate())).slice(-2) + '/' + ("0" + (d.getMonth() + 1)).slice(-2) + '/' + d.getFullYear() + ' ' + time:'-';
						return dFormat;
					}
				},								
				{ 
					"mDataProp": function ( data, type, full, meta) {
                        if(data.status == "1")
                        return 'Active';
                        else if(data.status == "2")
                        return 'Inactive';
                        else
                        return '';
					}
				},					
			]  						
		});
	}

    $('#searchButton').click(function() {
        var form = $('#searchForm')[0];
        var data = new FormData(form);
        var url = base_path + "management/searchEnquiryList";
        $.ajax({
            url: url,
            method: "POST",
            data: data,
            contentType: false,
            cache: false,
            processData: false,
            success: function(data) {
                managementJSON = $.parseJSON(data);
                dispDetails(managementJSON);   
            }
        });
	});

    $('#refreshBtn').on('click', function () {
        // location.reload();
        var element = document.getElementById('searchForm').reset();
            $('.js-example-basic-single').val(null).trigger('change');
            $('#searchButton').trigger('click');
	});

    let swalWithBootstrapButtons = Swal.mixin({buttonsStyling: false});
    
    $('#btnChangeStatus').on('click', function () {
        var dropdownOpt = $('#frmItemStatus').val();
        console.log(dropdownOpt,'dropdownOpt');
        var SelectedIdObject = commonCheckbox();
        var checkBoxLength   = SelectedIdObject[1];
        if (dropdownOpt > 0) {
            if (checkBoxLength >= 1) {
                var idJson = JSON.stringify(SelectedIdObject[0]);
                var StatusText = "Deactivate";
                if (dropdownOpt == 1) {
                    var StatusText = "Activate";
                }
                
                swalWithBootstrapButtons.fire(
                    {
                       
                        title: 'Do you want to ' + StatusText + ' this record ?',
                        type: 'warning',
                        showCancelButton: true,
                        scrollbarPadding: false,
                        confirmButtonText: 'Yes',
                        cancelButtonText: 'No',
                        reverseButtons: true,
                        width:460,
                        customClass: {'confirmButton': 'btn btn-green mx-2 px-3',  'cancelButton': 'btn btn-red mx-2 px-3'}
                }).then(function(result) {
					if (result.value) {
						MakeAsynPostRequest(base_path + 'dashboard/changeAllListActiveStatus', 'id=' + idJson + '&cs=' + dropdownOpt +
                        '&tblname=kn_order_enquiry', 'json', function (data) {
                            $.when(getManagementList()).done(function(){
                                dispDetails(managementJSON);		
                            });
                        });
					}
                }); 
                
                // if (confirm('Do you want to ' + StatusText + ' this records?')) {
                //     MakeAsynPostRequest(base_path + 'dashboard/changeAllListActiveStatus', 'id=' + idJson + '&cs=' + dropdownOpt +
                //         '&tblname=kn_order_enquiry', 'json', function (data) {
                //         $.when(getManagementList()).done(function(){
                //             dispDetails(managementJSON);		
                //         });
                //     });
                // }
            }
        }
        else {
            // alert('Select a option');
            swalWithBootstrapButtons.fire({
                title: 'Select a option!',
                type: 'error',
                icon: 'error',
                width:460,
                customClass: {'confirmButton': 'btn btn-info'}
            });
        }
        if(checkBoxLength == 0) {
            //alert('Select a record');
            swalWithBootstrapButtons.fire({
                title: 'Select a record!',
                type: 'error',
                icon: 'error',
                width:460,
                customClass: {'confirmButton': 'btn btn-info'}
            });
        }
    });

    $('#refreshBtn').on('click', function () {
        location.reload();
	     });

         $('#btn-active').on('click', function () {
            satusval="1";
            activeBtn.classList.add('active');
            inactiveBtn.classList.remove('active');;
            const managementJSON1 = managementJSON.filter(item => item.status === satusval);
            dispDetails(managementJSON1);
	     });
         $('#btn-inactive').on('click', function () {
            satusval="2";
            inactiveBtn.classList.add('active');
            activeBtn.classList.remove('active');
            const managementJSON1 = managementJSON.filter(item => item.status === satusval);
            dispDetails(managementJSON1);
           
	     });


});