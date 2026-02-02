
var GlbsubscriberId=$("#subscriber_id").val();
var GlbproformaId=$("#proforma_id").val();
var Glbproforma_status=$("#proforma_status").val();
var disablechkbox=(Glbproforma_status==2)?'disabled':'';
function fnList() {
    $("#DivTotalCntResult").html('');
    GlbSearchParam = "rfrom=1&subscriber_id=" + GlbsubscriberId + "&proforma_id=" + GlbproformaId;
    MakeAsynPostRequest(base_path + GlbBAdminFdr + 'msubscription/manage_dept', GlbSearchParam, 'json', fnListRes);
}
fnList();
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
                        $.each(data.re, function (index, value) {
                            PageContent = PageContent + '<tr>' +
                                '<td style="width:5%"><input style="margin:0px!important" type="checkbox" '+disablechkbox+' class="allcbox" id="' + value.id + '"></td>' +
                                '<td style="width:45%">' + value.usertype + '</td>' +
                                '<td style="width:30%" data-status='+value.status+'>' + value.status + '</td>' +
                                '<td style="width:20%"><a href="' + base_path + GlbBAdminFdr  + 'msubscription/addrole/' + encodeURIComponent(base64_encode(value.id)) + '/' + encodeURIComponent(base64_encode(value.proforma_id)) + '">Edit/View</a></td>';
                            PageContent = PageContent + '</tr>';
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

function fnPagination(VarURL) {
    var Parameters = GlbSearchParam;
    $("#DivTotalCntResult").html('');
    MakeAsynPostRequest(VarURL, Parameters, 'json', fnListRes);
}
function fnChangedeptStatusRes(data) {
    if (data != '') {
        if (data.errcode != undefined) {
            if (data.errcode == '404') {
                fnCallSessionExpire();
                return false;
            } else {
                $('#frmdepItemStatus').prop('selectedIndex',0);
                fnList();
                showuserwisepackagedetail(GlbsubscriberId);
            }
        }
    }
}
$('#deptbtnChangeStatus').on('click', function () {
    var dropdownOption = parseInt($('#frmdepItemStatus').val()) || 0;
    var noOfUsers = parseInt($('#no_of_users_chargeable').val()) || 0;
    var additionalUsers = ($('#additional_users_chargeable').val().toLowerCase() === 'nil') 
                          ? 0 : parseInt($('#additional_users_chargeable').val()) || 0;
    var totalUsers = noOfUsers + additionalUsers;

    // Fetch active user count from the server
    MakeAsynPostRequest(
        base_path + GlbBAdminFdr + "msubscription/getactivedeptcount", 
        "rFrom=1&subscriber_id=" + GlbsubscriberId + "&proforma_id=" + GlbproformaId, 
        "json", 
        function(data) {
            var activeCount = parseInt(data.activeusercnt) || 0;
            console.log("Active User Count from Server:", activeCount);

            // Get selected checkboxes
            var selectedData = commonCheckbox(); // Assuming this returns [data, count]
            var checkBoxLength = selectedData[1];
            var checkBoxData = selectedData[0];
            var status=(dropdownOption==1)?'Activate':'Inactivate';

             // If no checkboxes are selected, show an error and stop further processing
             if (checkBoxLength === 0) {
                showSwalError('Select a record!');
                return; // Stop further processing
            }

            // Ensure only one checkbox is selected
            if (checkBoxLength !== 1) {
                showSwalError('Please select exactly one record!',1);
                return; // Stop further processing
            }

            // If dropdown option is selected, proceed with further validation
            if (dropdownOption > 0) {
                // For dropdownOption = 1 (e.g., activating users)
                if (dropdownOption === 1) {
                    var selectedInactiveCount = countSelectedInactiveUsers();
                    //var selectedActiveCount = countSelectedActiveUsers();

                    // If any selected user is already active, show an informational message
                    // if (selectedActiveCount > 0) {
                    //     showSwalError('This user is already in the active state.');
                    //     return; // Stop further processing
                    // }

                    var resultingActiveCount = activeCount + selectedInactiveCount;

                    // Restrict activation if resulting active user count exceeds totalUsers
                    if (resultingActiveCount > totalUsers) {
                        showSwalError(`Activation restricted! (Total user count exceeds allowed limit of ${totalUsers} numbers).`,1);
                        return; // Stop further processing
                    }
                }

                // For dropdownOption = 2 (e.g., deactivating users)
                if (dropdownOption === 2) {
                   // var selectedInactiveCount = countSelectedInactiveUsers();
                    var selectedActiveCount = countSelectedActiveUsers(); // Count selected active users to be deactivated
                    
                    // If any selected user is already inactive, show an informational message
                    // if (selectedInactiveCount > 0) {
                    //     showSwalError('This user is already in the inactive state.');
                    //     return; // Stop further processing
                    // }
                    var resultingInactiveCount = activeCount + selectedActiveCount;

                    // Restrict deactivation if resulting inactive user count is less than totalUsers
                    if (resultingInactiveCount <= totalUsers) {
                        showSwalError(`Action restricted! Minimum active user cannot be lessthan 1`,1);
                        return; // Stop further processing
                    }
                }

                // Show confirmation dialog for action
                swalWithBootstrapButtons.fire({
                    title: `Do you want to ${status} this record?`,
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
                        // Proceed with server request for status change
                        var searchParam = `type=${dropdownOption}&cid=${JSON.stringify(checkBoxData)}`;
                        MakeAsynPostRequest(base_path + GlbBAdminFdr + 'msubscription/changemdeptStatus', searchParam, 'json', fnChangedeptStatusRes);
                    }else{
                        deselectCheckboxes();
                        $('#frmdepItemStatus').val('');
                    }
                });

            } else {
                // If no option is selected in the dropdown
                showSwalError('Select an option!');
            }
        }
        // ,
        // function(error) {
        //     // Handle error if the request fails
        //     console.error("Error fetching active user count:", error);
        //     showSwalError('Failed to fetch active user count. Please try again later.');
        // }
    );
});

// Function to show Swal error message
function showSwalError(message,defaultval) {
    if(defaultval==1){
        swalWithBootstrapButtons.fire({
            title: message,
            type: 'error',
            icon: 'error',
            width: 460,
            customClass: { confirmButton: 'btn btn-info' }
        }).then(function(result) {
            if (result.value) {
                deselectCheckboxes();
                $('#frmdepItemStatus').val('');
            }
        });

    }else{
        swalWithBootstrapButtons.fire({
            title: message,
            type: 'error',
            icon: 'error',
            width: 460,
            customClass: { confirmButton: 'btn btn-info' }
        });
    }
    

}

// Function to count selected inactive users
function countSelectedInactiveUsers() {
    var selectedInactiveCount = 0;

    $('#tableId tbody tr').each(function () {
        var $row = $(this);
        if ($row.find('input[type="checkbox"]:checked').length > 0 && 
            $row.find('td[data-status="Inactive"]').length > 0) {
            selectedInactiveCount++;
        }
    });

    return selectedInactiveCount;
}

// Function to count selected active users
function countSelectedActiveUsers() {
    var selectedActiveCount = 0;

    $('#tableId tbody tr').each(function () {
        var $row = $(this);
        if ($row.find('input[type="checkbox"]:checked').length > 0 && 
            $row.find('td[data-status="Active"]').length > 0) {
            selectedActiveCount++;
        }
    });

    return selectedActiveCount;
}

function deselectCheckboxes() {
    $('#tableId tbody tr').each(function () {
        // Check if the row's checkbox is selected
        var checkbox = $(this).find('input[type="checkbox"]');
        
        // If the checkbox is checked, uncheck it
        if (checkbox.is(':checked')) {
            checkbox.prop('checked', false);
        }
    });
}




