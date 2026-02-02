let swalWithBootstrapButtons = Swal.mixin({buttonsStyling: false});
const activeBtn = document.getElementById('btn-active');
const inactiveBtn = document.getElementById('btn-inactive'); 
function fnSave() {
    $('.form-control').css("border", "1px solid #cccccc");
    $('div.herr').text('');
    var dept = $("#department_id").val();
    var deptcount = $("#dept_usercount").val();
    var designation = $("#designation").val();
    var contactname = $("#contactname").val(); // username
    var address = $("#address").val();
    var login_id = $("#username").val(); // loginid 
    var password = $("#password").val();
    var emailId = $("#emailid").val();
    var mobileno = $("#mobile").val();
    var doj = $("#doj").val();
    var curr_salpckg = $("#curr_salarypackage").val();
    var bankname = $("#bankname").val();
    var accountname = $("#accountname").val();
    var accountno = $("#accountno").val();
    var ifsccode = $("#ifsccode").val();
    var swiftcode = $("#swiftcode").val();
    var status = $("#status").val();
   
    if (jsTrim(dept) == "") {
        $('#Errdepartment_id').text("Select Department");
        $('#department_id').focus();
        $('#department_id').css("border", "1px solid #B94A48");
        return false;
    }
    if (jsTrim(designation) == "") {
        $('#Errdesignation').text("Enter Designation");
        $('#designation').focus();
        $('#designation').css("border", "1px solid #B94A48");
        return false;
    }
    if (jsTrim(contactname) == "") {
        $('#Errcontactname').text("Enter User Name");
        $('#contactname').focus();
        $('#contactname').css("border", "1px solid #B94A48");
        return false;
    }
    if (jsTrim(address) == "") {
        $('#Erraddress').text("Enter Address");
        $('#address').focus();
        $('#address').css("border", "1px solid #B94A48");
        return false;
    }
    if (jsTrim(login_id) == "") {
        $('#Errusername').text("Enter Login ID");
        $('#username').focus();
        $('#username').css("border", "1px solid #B94A48");
        return false;
    } 
    if (jsTrim(password) == "") {
        $('#Errpassword').text("Enter Password");
        $('#password').focus();
        $('#password').css("border", "1px solid #B94A48");
        return false;
    } 
    if (emailId == "") {
        $('#Erremailid').text("Enter Email ID");
        $('#emailid').focus();
        $('#emailid').css("border", "1px solid #B94A48");
        return false;
    }
    if (emailId!='' && IsEmailid(emailId) == false) {
        $('#Erremailid').text("Invalid E-mail Id,Please Enter Valid One");
        $('#emailid').focus();
        $('#emailid').css("border", "1px solid #B94A48");
        return false;
    }
    if (jsTrim(mobileno) == "") {
        $('#Errmobile').text("Enter Mobile No.");
        $('#mobile').focus();
        $('#mobile').css("border", "1px solid #B94A48");
        return false;
    }
    if (jsTrim(doj) == "") {
        $('#Errdoj').text("Select Date of Joining");
        $('#doj').focus();
        $('#doj').css("border", "1px solid #B94A48");
        return false;
    }
    if (jsTrim(curr_salpckg) == "") {
        $('#Errcurr_salarypackage').text("Enter Current Salary Package");
        $('#curr_salarypackage').focus();
        $('#curr_salarypackage').css("border", "1px solid #B94A48");
        return false;
    }
    if (jsTrim(bankname) == "") {
        $('#Errbankname').text("Enter Bank Name");
        $('#bankname').focus();
        $('#bankname').css("border", "1px solid #B94A48");
        return false;
    }
    if (jsTrim(accountname) == "") {
        $('#Erraccountname').text("Enter Account Name");
        $('#accountname').focus();
        $('#accountname').css("border", "1px solid #B94A48");
        return false;
    }
    if (jsTrim(accountno) == "") {
        $('#Erraccountno').text("Enter Account No");
        $('#accountno').focus();
        $('#frmBasic').css("border", "1px solid #B94A48");
        return false;
    }
    if (jsTrim(ifsccode) == "") {
        $('#Errifsccode').text("Enter IFSC Code");
        $('#ifsccode').focus();
        $('#ifsccode').css("border", "1px solid #B94A48");
        return false;
    }
    if (jsTrim(swiftcode) == "") {
        $('#Errswiftcode').text("Enter SWIFT Code");
        $('#frmswiftcode').focus();
        $('#frmswiftcode').css("border", "1px solid #B94A48");
        return false;
    }
    if (status == "") {
        $('#Errstatus').text("Select Status");
        $('#status').focus();
        $('#status').css("border", "1px solid #B94A48");
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
                     MakeAsynPostRequest(base_path + GlbCompanyFdr + "muser/updateusertInfo",
                    "rfrom=1&dept=" + dept + "&deptcount=" + deptcount + "&dsgn=" + designation + "&username=" + contactname + "&addr=" + address + 
                    "&loginid=" + login_id +  "&pwd=" + password + "&em=" + emailId + "&mbno=" + mobileno + "&doj=" + doj +
                    "&curr_salpckg=" + curr_salpckg + "&bnk=" + bankname + "&actn=" + accountname + "&actno=" + accountno + 
                    "&ifsc=" + ifsccode + "&swift=" + swiftcode + "&s=" + status + "&id=" + GlbId, "json",function (data) {
                        if (data != '') {
                        if (data.errcode == '404') {
                            fnCallSessionExpire();
                            return false;
                        } else if (data.errcode == -1) {
                           // $('#AnyErrElse').text(data.msg);
                            swalWithBootstrapButtons.fire({
                                title: data.msg,type: 'warning',
                                icon: 'warning',
                                customClass: {'confirmButton': 'btn btn-info'}
                            });
                            return false;
                        } else if (data.errcode == 1) {
                            //console.log(data,'data');
                            GlbId = data.id;
                            
                            // $("#divSuccessBasicInfoMsg").removeClass('hide');
                            // $("#divSuccessBasicInfoMsg").text("Updated successfully!");
                            // fnRedirectPageTimeOut(base_path + GlbCompanyFdr + 'muser/addedit/' + data.eid);
                            
                            swalWithBootstrapButtons.fire({
                                            title: 'Saved!',text: data.message,type: 'success',
                                            icon: 'success',
                                            customClass: {'confirmButton': 'btn btn-info'}
                            }).then((result) => {
                                                let redirectpath = base_path + GlbCompanyFdr + 'muser/manage';
                                                window.location.href = redirectpath;
                            });
                            
                        }
                    }
                });
				}
            }); 

}
function fnList() {
    $("#DivTotalCntResult").html('');
    GlbSearchParam = "rfrom=1";
    MakeAsynPostRequest(base_path + GlbCompanyFdr + 'muser/manage', GlbSearchParam, 'json', fnListRes);
}
function fnListRes(data) {
    console.log(data.re, 'data');
    if (data != '') {
        if (data.errcode != undefined) {
            if (data.errcode == '404') {
                fnCallSessionExpire();
                return false;
            } else {
                var PageContent = '';
                if (data.cn > 0) {
                    ListCount = '<div style="font-weight:bold;">Number of Record(s) : ' + data.cn + '</div>';
                    if (data.ct > 0) {
                        let selestatus1 = "all"; // default
                        if ($('#btn-active').hasClass('active')) {
                               selestatus1 = "Active";
                             
                           } else if ($('#btn-inactive').hasClass('active')) {
                               selestatus1 = "Inactive";
                             
                           }
                        $.each(data.re, function (index, value) {
                            if (selestatus1 === "all" || value.s === selestatus1) {
                            PageContent = PageContent + '<tr>' +
                                '<td><input type="checkbox" class="allcbox" id="' + value.id + '"></td>' +
                                '<td><a href="' + base_path + GlbCompanyFdr + 'muser/addedit_user/' + encodeURIComponent(base64_encode(value.id)) + '/edit' + '">' + value.dept + '</a></td>' +
                                '<td>' + value.usercount + '</td>' +
                                '<td>' + value.desgn + '</td>' +
                                '<td>' + value.username + '</td>' +
                                '<td>' + value.loginid + '</td>' +
                                '<td>' + value.em + '</td>' +
                                '<td>' + value.mobno + '</td>' +
                                '<td>' + value.s + '</td>' +
                                '<td>' + value.ub + '</td>' +
                                '<td>' + value.du + '</td>' +
                                '<td><a href="' + base_path + GlbCompanyFdr + 'muser/addedit/' + encodeURIComponent(base64_encode(value.id)) + '/edit' + '">View</a></td>';
                            ;
                            PageContent = PageContent + '</tr>';
                            }
                        });
                    }
                    $("#DivTotalCntResult").html(ListCount);
                } else {
                    PageContent = PageContent + '<tr><td colspan="12" class="pdl15 herr text-center" style="padding-left:10px;">No Records(s) found</td></tr>';
                    $("#DivTotalCntResult").html('');
                }
                if (data.pa != undefined) {
                    console.log(base64_decode(data.pa))
                    $("#ResPagination").html(base64_decode(data.pa));
                }
                
                // $('tbody').empty();
                // $('#brandTblList').append(PageContent);
                //tableId
                $('#tableId tbody').empty();
                $('#tableId').append(PageContent).DataTable();
            }
        }
    }
}
var GlbSearchParam = '';
var GlbSortOrder = '';
var GlbColumnId = '';
function fnSearch() {
    var frmSrchDept = $("#frmSrchDept").val();
    var frmSrchUserName = $("#frmSrchUserName").val();
    var frmSrchDesignation = $("#frmSrchDesignation").val();
    var frmSrchLoginid = $("#frmSrchLoginid").val();
    var frmSrchEmailid = $("#frmSrchEmailid").val();
    var frmSrchMobNo = $("#frmSrchMobNo").val();
    var frmSrchstatuss = $("#frmSrchStatus").val();
    
    GlbSearchParam = "rfrom=1&dept=" + frmSrchDept + "&contactname=" + frmSrchUserName + "&dsgn=" + frmSrchDesignation + "&loginid=" + frmSrchLoginid +  "&em=" + frmSrchEmailid + "&mobno=" + frmSrchMobNo + "&s=" + frmSrchstatuss;
    $("#DivTotalCntResult").html('');
    MakeAsynPostRequest(base_path + GlbCompanyFdr + 'muser/manage', GlbSearchParam, 'json', fnListRes);
}
function fnChangeStatusRes(data) {
    if (data != '') {
        if (data.errcode != undefined) {
            if (data.errcode == '404') {
                fnCallSessionExpire();
                return false;
            } else {
                fnSearch();
            }
        }
    }
}
// $('#brandTblList').on('click', 'th.sortable', function () {
//     var ReturnVal = commonTableSorting(this);
//     GlbSortOrder = ReturnVal[1];
//     GlbColumnId = ReturnVal[0];
//     var frmSrchBrand = $("#frmSrchBrand").val();
//     var Status = $("#frmSrchBrandStatus").val();
//     GlbSearchParam = "rfrom=1&br=" + frmSrchBrand + "&s=" + Status + "&columnId=" + GlbColumnId + "&sortorder=" + GlbSortOrder;
//     MakeAsynPostRequest(base_path + GlbCompanyFdr + 'muser/manage', GlbSearchParam, 'json', fnListRes);
// });
$('#btnChangeStatus').on('click', function () {
    var dropdownOpt = $('#frmItemStatus').val();
    if (dropdownOpt > 0) {
        var SewTypeIdObject = commonCheckbox();
        var checkBoxLength = SewTypeIdObject[1];
        var cboxObj = SewTypeIdObject[0];
        if (checkBoxLength == 0) {
            // alert("Select Brand");
             swalWithBootstrapButtons.fire({
                title: 'Select a record!',
                type: 'error',
                icon: 'error',
                width:460,
                customClass: {'confirmButton': 'btn btn-info'}
            });
        }
        if (checkBoxLength >= 1) {
            var idJson = JSON.stringify(cboxObj);
            
            swalWithBootstrapButtons.fire(
                {
                   
                    title: 'Do you want to ' + GlbStatusForMaster[dropdownOpt] + ' this record ?',
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
				    GlbSearchParam = "rfrom=1&status=" + dropdownOpt + "&cid=" + idJson;
                    MakeAsynPostRequest(base_path + GlbCompanyFdr + 'muser/changeStatus', GlbSearchParam, 'json', fnChangeStatusRes);
				}
                });
            
            
            // if (confirm('Do you want to ' + GlbStatusForMaster[dropdownOpt] + ' this record?')) {
            //     GlbSearchParam = "actdeactFabType=" + dropdownOpt + "&cid=" + idJson;
            //     MakeAsynPostRequest(base_path + GlbCompanyFdr + 'muser/changemStatus', GlbSearchParam, 'json', fnChangeStatusRes);
            // }
        }
    }
    else {
        // alert('Select either ' + GlbStatusForMaster['1'] + ' or ' + GlbStatusForMaster['2']);
        swalWithBootstrapButtons.fire({
                title: 'Select a option!',
                type: 'error',
                icon: 'error',
                width:460,
                customClass: {'confirmButton': 'btn btn-info'}
        });
    }
});
function fnPaginationBrand(VarURL) {
    var Parameters = GlbSearchParam;
    $("#DivTotalCntResult").html('');
    MakeAsynPostRequest(VarURL, Parameters, 'json', fnListRes);
}

$('#editEnable').on('click', function() {
    $("#custom_form input").prop("disabled", false);
    $("#custom_form select").prop("disabled", false);
    $("#custom_form textarea").prop("disabled", false);
    $("#department_id").prop("disabled", true);
    $("#dept_usercount").prop("disabled", true);
});

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

$("#department_id").change(function () {
    // console.log($(this).val(), '$(this).val()');
    var Dept_id = $(this).val();
    MakeAsynPostRequest(base_path + GlbCompanyFdr +"muser/getDeptcount", "rFrom=1&dept_id=" + Dept_id, "json", function (data) {
        // console.log(data.usercnt);
         $("#dept_usercount").val(parseInt(data.usercnt)+1);
        // console.log(data.companyId, 'companyId');
        // if (data.buyername != ''){
        //     $("#buyername").val(data.buyername);
        // }else{
        //      $("#buyername").val('');
        // }
          
    });
});

$('#btn-active').on('click', function () {
    //satusval="Active";
    activeBtn.classList.add('active');
    inactiveBtn.classList.remove('active');
    selestatus = "Inactive"; 
    
    fnList();
   
   
    
 });
 $('#btn-inactive').on('click', function () {
   
    inactiveBtn.classList.add('active');
    activeBtn.classList.remove('active');
    selestatus = "Inactive";
    fnList();
   
 });