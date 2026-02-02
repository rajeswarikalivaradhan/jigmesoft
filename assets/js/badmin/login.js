let swalWithBootstrapButtons = Swal.mixin({buttonsStyling: false});
function fnSave() {
    $('.form-control').css("border", "1px solid #cccccc");
    $('div.herr').text('');
    var login_prefix = $("#login_prefix").val();
    var login_suffix = $("#login_suffix").val();
    var username = $("#username").val();
    var subscriber_id = $("#subscriber_id").val();
    var password = $("#password").val();
    var companyPrefix = $('#company_prefix').val();
    
    
    if (jsTrim(login_prefix) == "") {
        $('#Errloginid').text("Enter Loginid1111");
        $('#login_prefix').focus();
        $('#login_prefix').css("border", "1px solid #B94A48");
        return false;
    }
    if (jsTrim(companyPrefix) === "") {
    $('#Errcompany_prefix').text("Enter Company Prefix");
    $('#company_prefix').focus();
    $('#company_prefix').css("border", "1px solid #B94A48");
    return false;
}

// Check for non-alphabet characters
if (!/^[a-zA-Z]+$/.test(companyPrefix)) {
    $('#Errcompany_prefix').text("Only letters are allowed");
    $('#company_prefix').focus();
    $('#company_prefix').css("border", "1px solid #B94A48");
    return false;
}

// Check for length less than 1 or between 1–3 only
if (companyPrefix.length < 1 || companyPrefix.length > 3) {
    $('#Errcompany_prefix').text("Prefix must be 1 to 3 characters only");
    $('#company_prefix').focus();
    $('#company_prefix').css("border", "1px solid #B94A48");
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
            MakeAsynPostRequest(base_path + GlbBAdminFdr + "msubscription/updateloginInfo",
            "rfrom=1&id=" + GlbId + "&subscriber_id=" + subscriber_id +"&companyprefix=" + companyPrefix + "&username=" + username + "&login_prefix=" + login_prefix + "&login_suffix=" + login_suffix + "&password=" + password, "json",function (data) {
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
                    GlbId = data.id;
                   // if(data.mode == 'inserted'){
                        swalWithBootstrapButtons.fire({
                                    title: 'Saved!',type: 'success',
                                    icon: 'success',
                                    customClass: {'confirmButton': 'btn btn-info'}
                        }).then((result) => {
                                            let redirectpath = base_path + GlbBAdminFdr + 'msubscription/manage';
                                            window.location.href = redirectpath;
                        });
                    // }else{
                    //     location.reload();
                    //     $("#savereqbtn").prop("disabled", false);
                    //     $("#enqsvbtn").hide();
                    //     $("#custom_form input").prop("disabled", true);
                    //     $("#custom_form select").prop("disabled", true);
                    //     $("textarea").prop("disabled", true);
                    // }
                }
            }
        });
		}else if (result.dismiss === Swal.DismissReason.cancel) {
		// 	$("#enqsvbtn").hide();	
		// 	$("#savereqbtn").prop("disabled", false);
		// 	$("#custom_form input").prop("disabled", true);
//                 $("#custom_form select").prop("disabled", true);
//                 $("textarea").prop("disabled", true);
		}
    }); 
}
     $('#editEnable').on('click', function() {
     $('#login_prefix').prop("disabled", false);
     $('#company_prefix').prop("disabled", false);
    // $("#custom_form input").prop("disabled", false);
    // $("#custom_form select").prop("disabled", false);
    // $("textarea").prop("disabled", false);
    // $("#savereqbtn").prop("disabled", true);
});
