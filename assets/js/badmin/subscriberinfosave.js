//let swalWithBootstrapButtons = Swal.mixin({buttonsStyling: false});
function fnSavesubscinfo() {
    $('.form-control').css("border", "1px solid #cccccc");
    $('div.herr').text('');
    var Company = $("#companyname").val();
    var BusinessType = $("#businesstype").val();
    var Contactperson = $("#contactperson").val();
    var Designation = $("#designation").val();
    var EmailId = $("#email_id").val();
    var MobileNo = $("#mobile_no").val();
    var Gstno = $("#gst_no").val();
    var Iecode = $("#iecode_no").val();
    var Address = $("#address").val();
    var City = $("#city").val();
    var State = $("#state").val();
    var Country = $("#country").val();
    var Pincode = $("#pincode").val();
    var Subcription_Category = $("#subscription_category").val();
    var Pckgdet_id = $("#package_id").val();
    var Purchase_type = $("#purchasetype").val();
    var Additional_users = $("#additional_users").val();
    var Data_storage_limit = $("#data_storage_limit").val();
    var File_storage_limit = $("#file_storage_limit").val();
    var Request_status = ($('#request_status').val()===null)?0:$('#request_status').val();
    var Remarks = $("#remarks").val();
    var Mrkt_dept_userid = $("#mrkt_dept_userid").val();
    var GlbsubscrId=$("#subscriber_id").val();
    var proforma_id=$("#proforma_id").val();
    
    if (jsTrim(Company) == "") {
        $('#Errcompanyname').text("Enter Company Name");
        $('#companyname').focus();
        $('#companyname').css("border", "1px solid #B94A48");
        return false;
    }
    if (BusinessType == "" || BusinessType===null) {
        $('#Errbusinesstype').text("Select Business Type");
        $('#businesstype').focus();
        $('#businesstype').css("border", "1px solid #B94A48");
        return false;
    }
    if (jsTrim(Contactperson) == "") {
        $('#Errcontactperson').text("Enter Contact Person");
        $('#contactperson').focus();
        $('#contactperson').css("border", "1px solid #B94A48");
        return false;
    }
    if (jsTrim(Designation) == "") {
        $('#Errdesignation').text("Enter Designation");
        $('#designation').focus();
        $('#designation').css("border", "1px solid #B94A48");
        return false;
    }
    if (EmailId!='' && IsEmailid(EmailId) == false) {
        $('#Erremail_id').text("Invalid E-mail Id,Please Enter Valid One");
        $('#email_id').focus();
        $('#email_id').css("border", "1px solid #B94A48");
        return false;
    }
    if (EmailId == "") {
        $('#Erremail_id').text("Enter Email ID");
        $('#email_id').focus();
        $('#email_id').css("border", "1px solid #B94A48");
        return false;
    }
    if (jsTrim(MobileNo) == "") {
        $('#Errmobile_no').text("Enter Mobile No.");
        $('#mobile_no').focus();
        $('#mobile_no').css("border", "1px solid #B94A48");
        return false;
    }
    if (jsTrim(Gstno) == "") {
        $('#Errgst_no').text("Enter GST No");
        $('#gst_no').focus();
        $('#gst_no').css("border", "1px solid #B94A48");
        return false;
    }
     if (jsTrim(Iecode) == "") {
        $('#Erriecode_no').text("Enter IE Code No");
        $('#iecode_no').focus();
        $('#iecode_no').css("border", "1px solid #B94A48");
        return false;
    }
    if (jsTrim(Address) == "") {
        $('#Erraddress').text("Enter Address");
        $('#address').focus();
        $('#address').css("border", "1px solid #B94A48");
        return false;
    }
    if (jsTrim(City) == "") {
        $('#Errcity').text("Enter City");
        $('#city').focus();
        $('#city').css("border", "1px solid #B94A48");
        return false;
    }
    if (jsTrim(State) == "") {
        $('#Errstate').text("Enter State");
        $('#state').focus();
        $('#state').css("border", "1px solid #B94A48");
        return false;
    }
    if (jsTrim(Country) == "") {
        $('#Errcountry').text("Enter Country");
        $('#country').focus();
        $('#country').css("border", "1px solid #B94A48");
        return false;
    }
    if (jsTrim(Pincode) == "") {
        $('#Errpincode').text("Enter Pin Code");
        $('#pincode').focus();
        $('#pincode').css("border", "1px solid #B94A48");
        return false;
    }
    if (Subcription_Category== "" || Subcription_Category==null) {
        $('#Errsubscription_category').text("Select Subcription Category");
        $('#subscription_category').focus();
        $('#subscription_category').css("border", "1px solid #B94A48");
        return false;
    }
    if (Pckgdet_id == "" || Pckgdet_id==null) {
        $('#Errpackage_id').text("Select Package Detail");
        $('#package_id').focus();
        $('#package_id').css("border", "1px solid #B94A48");
        return false;
    }
    if (Purchase_type == "" || Purchase_type==null) {
        $('#Errpurchasetype').text("Select Purchase Type");
        $('#purchasetype').focus();
        $('#purchasetype').css("border", "1px solid #B94A48");
        return false;
    }
    if (jsTrim(Additional_users) == "") {
        $('#Erradditional_users').text("Enter No. of Additional Users (Chargeable)");
        $('#additional_users').focus();
        $('#additional_users').css("border", "1px solid #B94A48");
        return false;
    }
    if (Data_storage_limit == "" || Data_storage_limit==null) {
        $('#Errdata_storage_limit').text("Select Data Storage Limit");
        $('#data_storage_limit').focus();
        $('#data_storage_limit').css("border", "1px solid #B94A48");
        return false;
    }
    if (File_storage_limit == "" || File_storage_limit==null) {
        $('#Errfile_storage_limit').text("Select File Storage Limit");
        $('#file_storage_limit').focus();
        $('#file_storage_limit').css("border", "1px solid #B94A48");
        return false;
    }
    if (jsTrim(Remarks) == "") {
        $('#Errremarks').text("Enter Remarks");
        $('#remarks').focus();
        $('#remarks').css("border", "1px solid #B94A48");
        return false;
    }
            swalWithBootstrapButtons.fire(
            {
               // title: 'Are you sure want to save the details ?',
               // text: "If you save You won't be able to revert this!",
                title: 'Do you want to save the details ?',
                type: 'warning',
                showCancelButton: true,
                scrollbarPadding: false,
                confirmButtonText: 'Yes',
                cancelButtonText: 'No',
                reverseButtons: true,
                width:460,
                customClass: {'confirmButton': 'btn btn-green mx-2 px-3',  'cancelButton': 'btn btn-red mx-2 px-3'}
            }
	        ).then(function(result) {
				if (result.value) {
                    MakeAsynPostRequest(base_path + GlbBAdminFdr + "msubscriber/updatesubscriberInfo",
                    "rfrom=1&draftstatus=2&cmpny=" + Company + "&bt=" + BusinessType + "&cp=" + Contactperson + "&desgn=" + Designation + "&em=" + EmailId + "&mbno=" + MobileNo + "&gstno=" + Gstno +"&iecode=" + Iecode +
                    "&addrs=" + Address + "&cty=" + City + "&st=" + State + "&ctry=" + Country + "&pin=" + Pincode + "&subctgy=" + Subcription_Category + "&pckdetid=" + Pckgdet_id + 
                    "&purchtype=" + Purchase_type + "&additionalusers=" + Additional_users + "&datastrlimit=" + Data_storage_limit + "&filestrlimit=" + File_storage_limit + "&mrkt_dept_userid=" + Mrkt_dept_userid +
                    "&remarks=" + Remarks + "&proforma_id=" + proforma_id +"&id=" + GlbsubscrId, "json",function (data) {
                        if (data != '') {
                        if (data.errcode == '404') {
                            fnCallSessionExpire();
                            return false;
                        } else if (data.errcode == -1) {
                            //$('#AnyErrElse').text(data.msg);
                            swalWithBootstrapButtons.fire({
                                title: data.msg,type: 'warning',
                                icon: 'warning',
                                customClass: {'confirmButton': 'btn btn-info'}
                            });
                            return false;
                        } else if (data.errcode == 1) {
                            //console.log(data,'data');
                            GlbsubscrId = data.id;
                            if(data.mode == 'inserted'){
                                swalWithBootstrapButtons.fire({
                                            title: 'Saved!',type: 'success',
                                            icon: 'success',
                                            customClass: {'confirmButton': 'btn btn-info'}
                                }).then((result) => {
                                                    let redirectpath = base_path + GlbBAdminFdr + 'msubscription/manage';
                                                    window.location.href = redirectpath;
                                });
                            }else{
                                location.reload();
                                $("#savesubscinfo").hide();
                                $("#custom_form input").prop("disabled", true);
                                $("#custom_form select").prop("disabled", true);
                                $("textarea").prop("disabled", true);
                            }
                        }
                    }
                });
				}else if (result.dismiss === Swal.DismissReason.cancel) {
					$("#savesubscinfo").hide();	
					$("#custom_form input").prop("disabled", true);
                    $("#custom_form select").prop("disabled", true);
                    $("textarea").prop("disabled", true);
				}
            }); 

}
function onlyNumbernodecimal(evt) {  /// for allowing only number 

    // Only ASCII charactar in that range allowed
    var ASCIICode = (evt.which) ? evt.which : evt.keyCode
    // console.log(ASCIICode);

    if (ASCIICode>46 && ASCIICode<58) {
        return true; 
    }

    return false; 
} 
    
function IsEmailid(email) {
var regex =/^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
if (!regex.test(email)) {
    return false;
}
else {
    return true;
}
}
////////// package detail information /////////////////////////
$("#package_id").change(function () {
    var PckgId = $(this).val();
    $("#no_of_users").val('');
    $("#data_limit").val('');
    $("#file_limit").val('');
    MakeAsynPostRequest(base_path + GlbBAdminFdr + "msubscriber/getPackageInfoByPckgId", "rFrom=1&id=" + PckgId, "json", function (data) {
        if (data.no_of_users !== ''){
        $("#no_of_users").val(data.no_of_users);
        }
        if(data.data_limit !== ''){
        $("#data_limit").val(data.data_limit); 
        }    
        if(data.file_limit !== ''){
        $("#file_limit").val(data.file_limit); 
        }   
    });
});