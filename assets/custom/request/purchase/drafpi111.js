$(document).ready(function () {

    let parts = window.location.href.split('/');
    let request_id = parts[parts.length - 1];
    let req_id = atob(decodeURIComponent(request_id));
    var selectCount = 0;
    

    //$('input[type=radio][name=p_type]').attr('disabled',true);
    $('#vendorOption').attr('disabled',true);
    getDraftPIRequest();
    getPurchaseRequestImages();
    get_draft_value();
    
    //$('#mode').html('WITHIN STATE');
    $('#withinStateDetails').hide();
    $('#interStateDetails').hide();
    $('#importsStateDetails').hide();
    $('.surplus').hide();
    //$('#saveasdraft').hide();
    
    $('input[name=p_type]').attr("disabled",true);

    var swalWithBootstrapButtons = Swal.mixin({
        buttonsStyling: false
    });
    
    function alertMessageFunction(mode) {
        if(mode === 'confirmation_save') {
            return {
                title: 'Are you sure want to \n save the details ?',
                text: "If you save You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                scrollbarPadding: false,
                confirmButtonText: 'Yes, do it!',
                cancelButtonText: 'No, cancel!',
                reverseButtons: true,
                customClass: {
                    'confirmButton': 'btn btn-green mx-2 px-3',
                    'cancelButton': 'btn btn-red mx-2 px-3'
                }
            }
        }
        
        // if(mode === 'clear') {
        //     return {
        //         title: 'Are you sure want to \n clear the details ?',
        //         text: "If you clear You won't be able to revert this!",
        //         type: 'warning',
        //         showCancelButton: true,
        //         scrollbarPadding: false,
        //         confirmButtonText: 'Yes, do it!',
        //         cancelButtonText: 'No, cancel!',
        //         reverseButtons: true,
        //         customClass: {
        //             'confirmButton': 'btn btn-green mx-2 px-3',
        //             'cancelButton': 'btn btn-red mx-2 px-3'
        //         }
        //     }
        // }
        
        if(mode == "clear") {
            return {
                title: 'Clear',
                text: "If you clear You won't be able to revert this!",
                type: 'error',
                icon: 'error',
                customClass: {
                    'confirmButton': 'btn btn-secondary px-5'
                }
            }
        }
         
        if(mode == "saved") {
            return {
                title: 'Saved!',
                text: 'Operation completed successfully.',
                type: 'success',
                icon: 'success',
                customClass: {
                    'confirmButton': 'btn btn-info px-5'
                }
            }
        }
        
        if(mode == "cancelled") {
            return {
                title: 'Cancelled',
                text: 'Cancelled successfully.',
                type: 'error',
                icon: 'error',
                customClass: {
                    'confirmButton': 'btn btn-secondary px-5'
                }
            }
        }
        
        if(mode == "validation_error") {
            return {
                title: 'Warning',
                text: "Please fill all free text and select fields",
                icon: 'warning',
                confirmButtonText: 'OK',
                customClass: {
                    'confirmButton': 'btn btn-secondary px-5'
                }
            }
        }
        
        if(mode == "selecterror") {
            return {
                title: 'Warning',
                text: "Please select atleast one requirement",
                icon: 'warning',
                confirmButtonText: 'OK',
                customClass: {
                    'confirmButton': 'btn btn-secondary px-5'
                }
            }
        }
    }
    
    //$('#within').prop('checked',true);
    //$('#new_purchase').prop('checked',true);

    //let purchase_mode = 'within';

    $('input[type=radio][name=mode]').change(function() {
        $('input[name=p_type]').attr("disabled",false);
        $('#vendorOption').attr('disabled',true);
        $('#purchase_type').val('');
        //$("input:radio").attr("checked", false);
        let purchase_mode = $(this).val();
        let p_type = $('input[name="p_type"]:checked').val();
        $('#saveasdraft').show();
        $('#cleardraft').hide();
        if(purchase_mode != '' && p_type != undefined) {
            swalWithBootstrapButtons.fire(
            alertMessageFunction('clear')
            ).then(function (result) {
                var data = new FormData();
            data.append('reqId', req_id);
            data.append('enquiry_id', enquiry_id);
        
        let request = $.ajax({
            type: "POST",
            url: base_path + 'request/Bomrequest/getDraftPIRequestDetails',
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                let purchaseVendorDatas = '';
                bom_requirement_data = JSON.parse(data);
                console.log(bom_requirement_data);
                append_within_state(bom_requirement_data);
                append_inter_state(bom_requirement_data);
                append_imports_state(bom_requirement_data);
                append_purchase_request(bom_requirement_data, purchaseVendorDatas);
                append_payment_request_bill(bom_requirement_data);
                append_payment_paid_request(bom_requirement_data);
                append_request_payment_log(bom_requirement_data);
                appendAddressField(bom_requirement_data.vendor_data);
            },
            error: function () {
                console.log("Error");
            }
        });
        // $('#vendorAddress').html('');
        // $('#vendorContact').html('');
        // $('#vendorEmail').html('');
        // $('#vendorGst').html('');
        // $('#vendorIeCode').html('');
        
        vendor_change();
        
            append_within_state(bom_requirement_data);
            });
        }
        
        if(purchase_mode == 'within') {
            $('#within').prop('checked',true);
            //document.getElementById('spreadsheet').jexcel.setData(data);  
            $('#mode').html('WITHIN STATE');
            $('#withinStateDetails').hide();
            $('#interStateDetails').hide();
            $('#importsStateDetails').hide();
            
            var data = new FormData();
            data.append('reqId', req_id);

        // let request = $.ajax({
        //     type: "POST",
        //     url: base_path + 'request/Bomrequest/getWithinSateDetails',
        //     data: data,
        //     processData: false,
        //     contentType: false,
        //     cache: false,
        //     success: function (data) {
        //         bom_requirement_data = JSON.parse(data);
        //         append_within_state(bom_requirement_data);
        //         append_inter_state(bom_requirement_data);
        //         append_imports_state(bom_requirement_data);
        //     },
        //     error: function () {
        //         console.log("Error");
        //     }
        // });
            append_within_state(bom_requirement_data);
            let purchaseVendorDatas = '';
            append_purchase_request(bom_requirement_data, purchaseVendorDatas);
            
        }
        else if(purchase_mode == 'inter') {
            $('#inter').prop('checked',true);
            $('#mode').html('INTER STATE');
            $('#withinStateDetails').hide();
            $('#interStateDetails').hide();
            $('#importsStateDetails').hide();
            append_inter_state(bom_requirement_data);
            let purchaseVendorDatas = '';
            append_purchase_request(bom_requirement_data, purchaseVendorDatas);
        }
        else if(purchase_mode == 'imports') {
            $('#imports').prop('checked',true);
            $('#mode').html('IMPORTS');
            $('#withinStateDetails').hide();
            $('#interStateDetails').hide();
            $('#importsStateDetails').hide();
            append_imports_state(bom_requirement_data);
            let purchaseVendorDatas = '';
            append_purchase_request(bom_requirement_data, purchaseVendorDatas);
            
        }
        $('input[name=p_type]').attr("checked",false);
        
        
    });


    $('input[type=radio][name=p_type]').change(function() {
        $('#saveasdraft').show();
         $('#cleardraft').hide();
        p_type = $(this).val();
        if(p_type == 'new_purchase') {
            p_types = 'NEW PURCHAE';
        } else {
            p_types = 'SURPLUS STOCK';
        }
        $('#purchase_type').val(p_types);
        let purchase_mode = $('input[name="mode"]:checked').val();
        //purchase_mode = $this.val();
        if(purchase_mode == '' || purchase_mode == undefined) {
            alert('Enter Purchase Type');
        } else {
            if(p_type == 'new_purchase') {
                $('.surplus').hide();
                $('.to').show();
                $('#vendorOption').attr('disabled',false);
                let purchaseVendorDatas = '';
                append_purchase_request(bom_requirement_data, purchaseVendorDatas);
                append_request_payment_log(bom_requirement_data);
                if(purchase_mode == 'within') {
                   
                    append_within_state(bom_requirement_data);
                      $('#withinStateDetails').show();
                } else if(purchase_mode == 'inter') {
                    append_inter_state(bom_requirement_data);
                     $('#interStateDetails').show();
                } else if(purchase_mode == 'imports') {
                    append_imports_state(bom_requirement_data);
                     $('#importsStateDetails').show();
                }
                
            }
            else if(p_type == 'surplus') {
                $('.surplus').show();
                $('.to').hide();
               
                let purchaseVendorDatas = '';
                append_purchase_request(bom_requirement_data, purchaseVendorDatas);
                append_request_payment_log(bom_requirement_data);
                append_within_state(bom_requirement_data);
                if(purchase_mode == 'within') {
                    append_within_state(bom_requirement_data);
                       $('#withinStateDetails').show();
                   
                } else if(purchase_mode == 'inter') {
                    append_inter_state(bom_requirement_data);
                      $('#interStateDetails').show();
                } else if(purchase_mode == 'imports') {
                    append_imports_state(bom_requirement_data);
                     $('#importsStateDetails').show();
                }
                vendor_change();
            }
        }
        
    });
    
    $('#purchase_type').change(function() {
        $('#saveasdraft').show();
        $('#cleardraft').hide();
    });
    
    $('#exp_dod').change(function() {
        $('#saveasdraft').show();
        $('#cleardraft').hide();
    });

    function get_draft_value() {
        var data = new FormData();
        data.append('reqId', reqId);
        let request = $.ajax({
            type: "POST",
            url: base_path + 'request/Bomrequest/get_pi_draft_value',
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                draft_val = JSON.parse(data);
                if(draft_val > 0) {
                    $('#saveasdraft').hide();
                    $('#cleardraft').show();
                } else {
                    $('#saveasdraft').show();
                    $('#cleardraft').hide();
                }
                
            },
            error: function () {
                console.log("Error");
            }
        });
    }

    SUMCOL = function(instance, columnId, twoColumn) {
        var total = 0;
        var id = 1;
        for (var j = 0; j < instance.options.data.length; j++) {
            if(twoColumn == 'twoColumn')
            {
                id = 2;
            }
            if (Number(instance.records[j][columnId - id].innerHTML)) {
                total += Number(instance.records[j][columnId - id].innerHTML);
            }
        }
        total = numeral(total).format('0');
        total = (total > 0) ? total : '';
        return total;
    }


    GPWSUMCOL = function(instance, columnId) {
        var total = 0;
        for (var j = 0; j < instance.options.data.length; j++) {
            if (Number(instance.records[j][columnId - 1].innerHTML)) {
                total += Number(instance.records[j][columnId - 1].innerHTML);
            }
        }
        total = numeral(total).format('0.00');
        total = (total > 0) ? total : ''
        return total;
    }
    
    SUBSUMCOL = function(instance, columnId) {
        var total = 0;
        for (var j = 0; j < instance.options.data.length; j++) {
            if (Number(instance.records[j][columnId - 1].innerHTML)) {
                total += Number(instance.records[j][columnId - 1].innerHTML);
            }
        }
        total = numeral(total).format('0.00');
        total = (total > 0) ? total : ''
        if(total > 0) {
            
             get_amt();
        }
        return total;
    }
    
    IMPSUMCOL = function(instance, columnId) {
        var total = 0;
        for (var j = 0; j < instance.options.data.length; j++) {
            if (Number(instance.records[j][columnId - 1].innerHTML)) {
                total += Number(instance.records[j][columnId - 1].innerHTML);
            }
        }
        total = numeral(total).format('0.00');
        total = (total > 0) ? total : ''
        if(total > 0) {
            
        }
        return total;
    }
     
    function footer(grid_name)
    {
        if(grid_name == 'within')
        {
            return [[ '', '', '', '', '', '', '', '', '', '', '', 'Total:', '=GPWSUMCOL(TABLE(), COLUMN(), "")',  '=GPWSUMCOL(TABLE(), COLUMN(), "")', '', '=GPWSUMCOL(TABLE(), COLUMN(), "")', '=SUBSUMCOL(TABLE(), COLUMN(), "")' ]];
        }
        else if(grid_name == 'inter')
        {
            return [[ '', '', '', '', '', '', '', '', '', '', '', '', 'Total:', '=GPWSUMCOL(TABLE(), COLUMN(), "")', '', '=GPWSUMCOL(TABLE(), COLUMN(), "")', '=SUBSUMCOL(TABLE(), COLUMN(), "")' ]];
        }
        else if(grid_name == 'import')
        {
            return [[ '', '', '', '', '', '', '', '', '', '', '', '', '', 'Total:', '=SUBSUMCOL(TABLE(), COLUMN(), "")' ]];
        }
    
    }

    // *********************************************************************************************************************************** 
    // Purchase REQUEST STARTS HERE 
    // ***********************************************************************************************************************************

    let bom_requirement_data = '';

    function getDraftPIRequest() {
        var data = new FormData();
        data.append('reqId', req_id);
         data.append('enquiry_id', enquiry_id);

        let request = $.ajax({
            type: "POST",
            url: base_path + 'request/Bomrequest/getDraftPIRequestDetails',
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                bom_requirement_data = JSON.parse(data);
                append_within_state(bom_requirement_data);
                append_purchase_request(bom_requirement_data);
                append_payment_request_bill(bom_requirement_data);
                append_payment_paid_request(bom_requirement_data);
                append_request_payment_log(bom_requirement_data);
                append_inter_state(bom_requirement_data);
                append_imports_state(bom_requirement_data);
                appendAddressField(bom_requirement_data.vendor_data);
                //  console.log(bom_requirement_data.pi_data); 
                 if(bom_requirement_data.pi_data.length != 0) {
                    $('#'+bom_requirement_data.pi_data[0].p_type).prop('checked',true);    
                    $('#'+bom_requirement_data.pi_data[0].mode).prop('checked',true);
                    if(bom_requirement_data.pi_data[0].mode == 'within') {
                    $('#withinStateDetails').show();
                    $('#interStateDetails').hide();
                    $('#importsStateDetails').hide();
                } else if(bom_requirement_data.pi_data[0].mode == 'inter') {
                    $('#interStateDetails').show();
                    $('#withinStateDetails').hide();
                    $('#importsStateDetails').hide();
                } else if(bom_requirement_data.pi_data[0].mode == 'imports') {
                    $('#importsStateDetails').show();
                    $('#interStateDetails').hide();
                    $('#withinStateDetails').hide();
                } else {
                    $('#mode').html('WITHIN STATE');
                    $('#withinStateDetails').show();
                    $('#interStateDetails').hide();
                    $('#importsStateDetails').hide();
                }
                 }
                
                
                
                
            },
            error: function () {
                console.log("Error");
            }
        });
    }

    function getPurchaseRequestImages()
    {
        $('.ImageView').html('');
        var data = new FormData();
        data.append('enquiry_id', enquiry_id);
        data.append('reqId', reqId);
        data.append('type', 'purchase_dept');
        let request = $.ajax({
            type: "POST",
            url: base_path + 'MerchantRequestSent/getbomrequestImages',
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                imageJSON = $.parseJSON(data);
                  let subscriberId = imageJSON.subscriber_id;
                
                if((imageJSON.images.length) > 0) {
                for (let i = 0; i < imageJSON.images.length; i++) {
                    $('.ImageView').append(
                        '<li class="file-viwer-jig">'+
                            '<div style="padding: 10px 0;">'+
                                '<div class="ajax-file-upload-filename">'+imageJSON.images[i].image_url+'</div>'+
                                '<div class="upload-action-btn">'+
                                    '<a href='+base_path+'uploads/request/bom/purchase/'+subscriberId+'/'+imageJSON.images[i].image_url+' title="Download" download>'+
                                        '<i class="fa fa-download fa-lg" aria-hidden="true"></i>'+
                                    '</a>'+
                                    '<a href='+base_path+'uploads/request/bom/purchase/'+subscriberId+'/'+imageJSON.images[i].image_url+' target="_blank" title="Open in New Tab">'+
                                        '<i class="fa fa-file fa-lg" aria-hidden="true"></i>'+
                                    '</a>'+
                                    '<a href="javascript:void(0);" data-id='+subscriberId+'/'+imageJSON.images[i].image_url+' class="deleteImg" title="Delete">'+
                                        '<i class="fa fa-trash fa-lg" aria-hidden="true"></i>'+
                                    '</a>'+
                                '</div>'+
                            '</div>'+
                        '</li>'
                    );               
                } 
                $('#purchaseImageUpload').hide();
                } else {
                    
                        $('#purchaseImageUpload').show();
                }
            },
            error: function () {
                console.log("Error");
            }
        });
    }
    
    $(document).on('click','.deleteImg',function(){
        var id = $(this).attr('data-id');

        if (confirm('Are you sure you want to delete the file')) {
            MakeAsynPostRequest(base_path + "WorkInProcess/deleteImageDetails", "&id=" + id, "json", function(data) {
                if(data.status == 'success')
                {
                    getPurchaseRequestImages();
                }
            });
        }

    });

    let purchaseVendorData = [];

    function append_purchase_request(data, tblData = []) {
    
        $('#paymentRequest').html('');
        let p_type = $('input[name="p_type"]:checked').val();
        let paymentData = data.requestPaymentData;
        let vendorOption = $('#vendorOption').val();
        let list = {
            data: purchaseVendorData,
            columns: [
                { title:'id', width:'0%',align:'center',type:'hidden'},
                { type: 'dropdown', title: 'Vendor Name', width: '6%', align: 'left', source: data.vendor_data },
                { title: "Vendor's Bank Name", width: '10%', align: 'left', readOnly: true},
                { title: 'Account Number', width: '8%', align: 'center', readOnly: true},
                { title: 'IFSC Code / \n SWIFT Code', width: '7%', align: 'center', readOnly: true},
                //{ title: 'SWIFT Code', width: '7%', align: 'center', readOnly: true},
                { type: 'text', title: 'Proforma Invoice No.', width: '6%', align: 'left', readOnly: true},
                { type: 'calendar', title: 'Proforma\n Invoice Date', width: '7%', align: 'left', options: { format:'DD-MM-YYYY'}, readOnly: true },
                { type: 'text', title: 'Proforma\n Invoice Value', width: '6%', align: 'right', readOnly: true},
                { type: 'dropdown', title: 'Quoted\n Currency', width: '6%', align: 'left', source: data.currencyList, readOnly: true},
                { type: 'dropdown', title: 'Accepted Mode\n of Payment', width: '7%', align: 'center', source: data.modeOfShipment, readOnly: true},
                //{ type: 'calendar', title: 'Pay by Date', width: '7%', align: 'center'},
                { title: 'Pay by\n Date & Time', width: '8%', align: 'center', type: 'calendar', options: { format:'DD-MM-YYYY HH12:MI AM/PM', time:1 } , readOnly: true },
                { title: 'Amount\n Payable', width: '6%', align: 'right', readOnly: true},
                { type: 'dropdown', title: 'Currency', width: '6%', align: 'left', source: data.currencyList, },
                { title: "Payment ID", width: '10%', align: 'left', readOnly: true,type:'hidden'}
                //{ title:'payment_id', width:'0%',align:'center',type:'text',readOnly: true},
            ],
            minDimensions: [4, 1],
            allowDeleteColumn: false,
            allowInsertRow: false,
            allowInsertColumn: false,
            onchange: function(instance, cell, col, row, val, label, cellName) {
                
                if(col == 5) 
                {
                    $('#saveasdraft').show();
                    $('#cleardraft').hide();
                }
                if(col == 6) 
                {
                    $('#saveasdraft').show();
                    $('#cleardraft').hide();
                }
                if(col == 7) 
                {
                    $('#saveasdraft').show();
                    $('#cleardraft').hide();
                }
                if(col == 8) 
                {
                    $('#saveasdraft').show();
                    $('#cleardraft').hide();
                }
                if(col == 9) 
                {
                    $('#saveasdraft').show();
                    $('#cleardraft').hide();
                }
                if(col == 10) 
                {
                    $('#saveasdraft').show();
                    $('#cleardraft').hide();
                }
                if(col == 11) 
                {
                    $('#saveasdraft').show();
                    $('#cleardraft').hide();
                }
                if(col == 12) 
                {
                    $('#saveasdraft').show();
                    $('#cleardraft').hide();
                }
                
            },

            updateTable: function(instance, cell, col, row, val, label, cellName) {
                if(col == 1) 
                {
                    if(p_type == 'surplus') {
                        //$(cell).text('');
                        //instance.jexcel.options.data[row][col] = '';
                        //$(cell).addClass('readonly');
                    } else {
                        if(vendorOption != '') {
                            
                            vendorId = val;
                            $(cell).removeClass('readonly');
                        } else {
                            vendorId = '';
                            //$(cell).text('');
                            //instance.jexcel.options.data[row][col] = '';
                            //$(cell).removeClass('readonly');
                        }
                        
                    }
                    
                }
                if(col == 2)
                {
                    if(p_type == 'surplus') {
                        $(cell).text('');
                        instance.jexcel.options.data[row][col] = '';
                        $(cell).addClass('readonly');
                    } else {
                    if(vendorId != '')
                    {
                        if(vendorOption != '') {
                        $(cell).removeClass('readonly');
                        let index = data.vendor_data.findIndex(p => p.id == vendorId);
                        // let fil_vendor = [];
                        fil_vendor = data.vendor_data[index];
                        
                        if(fil_vendor != undefined)
                        {
                            $(cell).text(fil_vendor.bankname);
                            instance.jexcel.options.data[row][col] = fil_vendor.bankname;
                        }
                        else {
                            $(cell).text('');
                            instance.jexcel.options.data[row][col] = '';
                        }
                        } else {
                        $(cell).text('');
                        instance.jexcel.options.data[row][col] = '';
                    }
                    }
                    }
                }
                if(col == 3)
                {
                    if(p_type == 'surplus') {
                        $(cell).text('');
                        instance.jexcel.options.data[row][col] = '';
                        $(cell).addClass('readonly');
                    } else {
                    if(vendorId != '')
                    {
                        if(vendorOption != '')
                    {
                        $(cell).removeClass('readonly');
                        let index = data.vendor_data.findIndex(p => p.id == vendorId);
                        // let fil_vendor = [];
                        fil_vendor = data.vendor_data[index];
                        
                        if(fil_vendor != undefined)
                        {
                            $(cell).text(fil_vendor.accountno);
                            instance.jexcel.options.data[row][col] = fil_vendor.accountno;
                        }
                        else {
                            $(cell).text('');
                            instance.jexcel.options.data[row][col] = '';
                        }
                    } else {
                        $(cell).text('');
                        instance.jexcel.options.data[row][col] = '';
                    }
                    }
                    }
                }
                if(col == 4)
                {
                    if(p_type == 'surplus') {
                        $(cell).text('');
                        instance.jexcel.options.data[row][col] = '';
                        $(cell).addClass('readonly');
                    } else {
                    if(vendorId != '')
                    {
                        if(vendorOption != '')
                    {
                        $(cell).removeClass('readonly');
                        let index = data.vendor_data.findIndex(p => p.id == vendorId);
                        // let fil_vendor = [];
                        fil_vendor = data.vendor_data[index];
                        
                        if(fil_vendor != undefined)
                        {
                            $(cell).text(fil_vendor.ifscode);
                            instance.jexcel.options.data[row][col] = fil_vendor.ifscode;
                        }
                        else {
                            $(cell).text('');
                            instance.jexcel.options.data[row][col] = '';
                        }
                    } else {
                        $(cell).text('');
                        instance.jexcel.options.data[row][col] = '';
                    }
                    }
                    }
                }
                
                if(paymentData[0][1] != '') {

                if(col == 5)
                {
                    if(vendorOption != '')
                    {
                        $(cell).removeClass('readonly');
                    }
                    if(p_type == 'surplus') {
                        $(cell).text('');
                        instance.jexcel.options.data[row][col] = '';
                        $(cell).addClass('readonly');
                    } else {
                    $(cell).removeClass('readonly');
                    if(paymentData[5] != '')
                    {
                        if(val != '') {
                            $(cell).text(val);
                            instance.jexcel.options.data[row][col] = val;
                        } else {
                            $(cell).text(paymentData[5]);
                            instance.jexcel.options.data[row][col] = paymentData[5];    
                        }
                    } 
                    else if(paymentData[5] == undefined)
                    {
                        $(cell).text('');
                        instance.jexcel.options.data[row][col] = '';
                    }
                    else {
                        $(cell).text('');
                        instance.jexcel.options.data[row][col] = '';
                    }
                }

                }

                if(col == 6)
                {
                    if(vendorOption != '')
                    {
                        $(cell).removeClass('readonly');
                    }
                    if(p_type == 'surplus') {
                        $(cell).text('');
                        instance.jexcel.options.data[row][col] = '';
                        $(cell).addClass('readonly');
                    } else {
                    $(cell).removeClass('readonly');
                    if(paymentData[6] != '')
                    {
                        if(val != '') {
                            $(cell).text(val);
                            instance.jexcel.options.data[row][col] = val;
                        } else {
                            $(cell).text(paymentData[6]);
                            instance.jexcel.options.data[row][col] = paymentData[6];    
                        }
                        
                    } 
                    else if(paymentData[6] == undefined)
                    {
                        $(cell).text('');
                        instance.jexcel.options.data[row][col] = '';
                    }
                    else {
                        $(cell).text('');
                        instance.jexcel.options.data[row][col] = '';
                    }
                }

                } 

                if(col == 7)
                {
                    if(vendorOption != '')
                    {
                        $(cell).removeClass('readonly');
                    }
                    if(p_type == 'surplus') {
                        $(cell).text('');
                        instance.jexcel.options.data[row][col] = '';
                        $(cell).addClass('readonly');
                    } else {
                        $(cell).removeClass('readonly');
                    if(paymentData[7] != '')
                    {
                        if(val > 0) {
                            txtValue = numeral(val).format('0.00');
                            $(cell).text(txtValue);
                            instance.jexcel.options.data[row][col] = txtValue;
                        } else {
                            txtValue = numeral(paymentData[7]).format('0.00');
                            $(cell).text(txtValue);
                            instance.jexcel.options.data[row][col] = txtValue;    
                        }
                        
                    } 
                    else if(paymentData[7] == undefined)
                    {
                        $(cell).text('');
                        instance.jexcel.options.data[row][col] = '';
                    }
                    else {
                        $(cell).text('');
                        instance.jexcel.options.data[row][col] = '';
                    }
                    }

                } 

                if(col == 8)
                {
                    if(vendorOption != '')
                    {
                        $(cell).removeClass('readonly');
                    }
                    if(p_type == 'surplus') {
                        $(cell).text('');
                        instance.jexcel.options.data[row][col] = '';
                        $(cell).addClass('readonly');
                    } else {
                        $(cell).removeClass('readonly');
                    if(paymentData[8] != '')
                    {
                        if(val != '') {
                            $(cell).text(val);
                            instance.jexcel.options.data[row][col] = val;
                        } else {
                            $(cell).text(paymentData[8]);
                            instance.jexcel.options.data[row][col] = paymentData[8];    
                        }
                    } 
                    else if(paymentData[8] == undefined)
                    {
                        $(cell).text('');
                        instance.jexcel.options.data[row][col] = '';
                    }
                    else {
                        $(cell).text('');
                        instance.jexcel.options.data[row][col] = '';
                    }
                    }

                } 

                if(col == 9)
                {
                    if(vendorOption != '')
                    {
                        $(cell).removeClass('readonly');
                    }
                    if(p_type == 'surplus') {
                        $(cell).text('');
                        instance.jexcel.options.data[row][col] = '';
                        $(cell).addClass('readonly');
                    } else {
                        $(cell).removeClass('readonly');
                    if(paymentData[9] != '')
                    {
                        if(val != '') {
                            $(cell).text(val);
                            instance.jexcel.options.data[row][col] = val;
                        } else {
                            $(cell).text(paymentData[9]);
                            instance.jexcel.options.data[row][col] = paymentData[9];    
                        }
                    } 
                    else if(paymentData[9] == undefined)
                    {
                        $(cell).text('');
                        instance.jexcel.options.data[row][col] = '';
                    }
                    else {
                        $(cell).text('');
                        instance.jexcel.options.data[row][col] = '';
                    }
                }

                } 

                if(col == 10)
                {
                    if(vendorOption != '')
                    {
                        $(cell).removeClass('readonly');
                    }
                    if(p_type == 'surplus') {
                        $(cell).text('');
                        instance.jexcel.options.data[row][col] = '';
                        $(cell).addClass('readonly');
                    } else {
                        $(cell).removeClass('readonly');
                    if(paymentData[10] != '')
                    {
                        if(val != '') {
                            $(cell).text(val);
                            instance.jexcel.options.data[row][col] = val;
                        } else {
                            $(cell).text(paymentData[10]);
                            instance.jexcel.options.data[row][col] = paymentData[10];    
                        }
                    } 
                    else if(paymentData[10] == undefined)
                    {
                        $(cell).text('');
                        instance.jexcel.options.data[row][col] = '';
                    }
                    else {
                        $(cell).text('');
                        instance.jexcel.options.data[row][col] = '';
                    }
                }

                } 

                if(col == 11)
                {
                    if(vendorId != '')
                    {
                        $(cell).removeClass('readonly');
                    }
                    if(p_type == 'surplus') {
                        $(cell).text('');
                        instance.jexcel.options.data[row][col] = '';
                        $(cell).addClass('readonly');
                    } else {
                        $(cell).removeClass('readonly');
                    if(paymentData[11] != '')
                    {
                        if(val != '') {
                            $(cell).text(val);
                            instance.jexcel.options.data[row][col] = val;
                        } else {
                            $(cell).text(paymentData[11]);
                            instance.jexcel.options.data[row][col] = paymentData[11];    
                        }
                    } 
                    else if(paymentData[11] == undefined)
                    {
                        $(cell).text('');
                        instance.jexcel.options.data[row][col] = '';
                    }
                    else {
                        $(cell).text('');
                        instance.jexcel.options.data[row][col] = '';
                    }
                }

                } 

                if(col == 12)
                {
                    if(vendorOption != '')
                    {
                        $(cell).removeClass('readonly');
                    }
                    if(p_type == 'surplus') {
                        $(cell).text('');
                        instance.jexcel.options.data[row][col] = '';
                        $(cell).addClass('readonly');
                    } else {
                        $(cell).removeClass('readonly');
                    if(paymentData[12] != '')
                    {
                        if(val != '') {
                            $(cell).text(val);
                            instance.jexcel.options.data[row][col] = val;
                        } else {
                            $(cell).text(paymentData[12]);
                            instance.jexcel.options.data[row][col] = paymentData[12];    
                        }
                    } 
                    else if(paymentData[12] == undefined)
                    {
                        $(cell).text('');
                        instance.jexcel.options.data[row][col] = '';
                    }
                    else {
                        $(cell).text('');
                        instance.jexcel.options.data[row][col] = '';
                    }
                }

                }  
                
                if(col == 13)
                {
                    
                    if(p_type == 'surplus') {
                        $(cell).text('');
                        instance.jexcel.options.data[row][col] = '';
                        $(cell).addClass('readonly');
                    } else {
                        $(cell).removeClass('readonly');
                    if(paymentData[13] != '')
                    {
                        if(val != '') {
                            $(cell).text(val);
                            instance.jexcel.options.data[row][col] = val;
                        } else {
                            $(cell).text(paymentData[13]);
                            instance.jexcel.options.data[row][col] = paymentData[13];    
                        }
                    } 
                    else if(paymentData[13] == undefined)
                    {
                        $(cell).text('');
                        instance.jexcel.options.data[row][col] = '';
                    }
                    else {
                        $(cell).text('');
                        instance.jexcel.options.data[row][col] = '';
                    }
                }

                } 

                }  else {
                    if(col == 5)
                    {
                        if(p_type == 'surplus') {
                            $(cell).text('');
                            instance.jexcel.options.data[row][col] = '';
                            $(cell).addClass('readonly');
                        } else {
                            $(cell).removeClass('readonly');
                        }
                    }
                    if(col == 6)
                    {
                        if(p_type == 'surplus') {
                            $(cell).text('');
                            instance.jexcel.options.data[row][col] = '';
                            $(cell).addClass('readonly');
                        } else {
                            $(cell).removeClass('readonly');
                        }
                    }
                     if(col == 7)
                     {
                        if(p_type == 'surplus') {
                            $(cell).text('');
                            instance.jexcel.options.data[row][col] = '';
                            $(cell).addClass('readonly');
                        } else {
                            $(cell).removeClass('readonly');
                        }
                        txtValue = numeral(val).format('0.00');
                        $(cell).text(txtValue);
                        instance.jexcel.options.data[row][col] = txtValue;
                     }
                     if(col == 8)
                    {
                        if(p_type == 'surplus') {
                            $(cell).text('');
                            instance.jexcel.options.data[row][col] = '';
                            $(cell).addClass('readonly');
                        } else {
                            $(cell).removeClass('readonly');
                        }
                    }
                    if(col == 9)
                    {
                        if(p_type == 'surplus') {
                            $(cell).text('');
                            instance.jexcel.options.data[row][col] = '';
                            $(cell).addClass('readonly');
                        } else {
                            $(cell).removeClass('readonly');
                        }
                    }
                    
                    if(col == 10)
                    {
                        if(p_type == 'surplus') {
                            $(cell).text('');
                            instance.jexcel.options.data[row][col] = '';
                            $(cell).addClass('readonly');
                        } else {
                            $(cell).removeClass('readonly');
                        }
                    }

                     if(col == 11)
                     {
                         if(p_type == 'surplus') {
                            $(cell).text('');
                            instance.jexcel.options.data[row][col] = '';
                            $(cell).addClass('readonly');
                        } else {
                            $(cell).removeClass('readonly');
                        }
                        txtValue = numeral(val).format('0.00');
                        $(cell).text(txtValue);
                        instance.jexcel.options.data[row][col] = txtValue;
                     }
                     if(col == 12)
                    {
                        if(p_type == 'surplus') {
                            $(cell).text('');
                            instance.jexcel.options.data[row][col] = '';
                            $(cell).addClass('readonly');
                        } else {
                            $(cell).removeClass('readonly');
                        }
                    }
                    if(col == 13)
                    {
                        if(p_type == 'surplus') {
                            $(cell).text('');
                            instance.jexcel.options.data[row][col] = '';
                            $(cell).addClass('readonly');
                        } else {
                            $(cell).removeClass('readonly');
                        }
                    }
                }   
         

                // if(col == 6)
                // {
                //     if(paymentData[6] != '')
                //         {
                //             $(cell).text(paymentData[6]);
                //             instance.jexcel.options.data[row][col] = paymentData[6];
                //         }
                //         else {
                //             $(cell).text('');
                //             instance.jexcel.options.data[row][col] = '';
                //         }

                // }

               
            }
        };

        paymentRequest_vm = new Vue({
            el: '#paymentRequest',
            mounted: function () {
                let spreadsheet = jexcel(this.$el, list);
                Object.assign(this, spreadsheet);
            }
        });
    }

    // *********************************************************************************************************************************** 
    // Purchase REQUEST ENDS HERE 
    // ***********************************************************************************************************************************
    
    
    // *********************************************************************************************************************************** 
    // ATTACHMENT REFERENCE STARTS HERE 
    // **********************************************************************************************************************************
    
    function append_payment_request_bill(data) {


        $('#paymentRequestBill').html('');
        let list = {
            data: data.paymentRequestBill,
            columns: [
                { title:'id', width:'0%',align:'center',type:'hidden'},
                { type: 'dropdown', title: 'Vendor Name', width: '6%', align: 'left', source: data.vendor_data, readOnly: true },
                { type: 'text', title: "Vendor's Bank Name", width: '10%', align: 'left', readOnly: true},
                { type: 'text', title: 'Account Number', width: '8%', align: 'center', readOnly: true},
                { type: 'text', title: 'IFSC Code', width: '6%', align: 'center', readOnly: true},
                { type: 'text', title: 'SWIFT Code', width: '6%', align: 'center', readOnly: true},
                { type: 'text', title: 'Invoice No.', width: '6%', align: 'left', readOnly: true},
                { type: 'calendar', title: 'Invoice \n Date', width: '6%', align: 'left', readOnly: true},
                { type: 'text', title: 'Invoice Value', width: '6%', align: 'left', readOnly: true},
                { type: 'text', title: 'Advance Paid', width: '6%', align: 'left', readOnly: true},
                { type: 'text', title: 'Debits If Any', width: '6%', align: 'left', readOnly: true},
                { type: 'dropdown', title: 'Acc. Mode\n of Payment', width: '7%', align: 'center', source: data.modeOfShipment, readOnly: true},
                { type: 'calendar', title: 'Pay by Date', width: '7%', align: 'center', readOnly: true},
                { title: 'Amount\n Payable', width: '6%', align: 'right', readOnly: true},
                { type: 'dropdown', title: 'Currency', width: '6%', align: 'center', source: data.currencyList, readOnly: true},
    
            ],
            minDimensions: [4, 1],
            allowDeleteColumn: false,
            allowInsertRow: false,
            allowInsertColumn: false,
        };

        tblPaymentRequestBill = new Vue({
            el: '#paymentRequestBill',
            mounted: function () {
                let spreadsheet = jexcel(this.$el, list);
                Object.assign(this, spreadsheet);
            },
        });
    }
    
    function append_payment_paid_request(data) {

        $('#paymentPaidDetails').html('');
        let list = {
            data: data.paymentPaidDetails,
            columns: [
                { title:'id', width:'0%',align:'center',type:'hidden'},
                { title: 'Paid in Favour of', width: '7%', align: 'left', readOnly: true },
                { title: 'Bank Name', width: '12%', align: 'left', readOnly: true },
                { title: 'Account Number', width: '10%', align: 'left', readOnly: true },
                { title: 'Mode of\n Payment', width: '6%', align: 'left', readOnly: true },
                { title: 'Transaction ID / Code', width: '6%', align: 'left', readOnly: true },
                { title: 'Transaction\n Date', width: '6%', align: 'left', readOnly: true },
                { title: 'Cheque No.', width: '8%', align: 'left', readOnly: true },
                { title: 'Cheque Date', width: '6%', align: 'left', readOnly: true },
                { title: "Paid Towards", width: '6%', align: 'left', readOnly: true },
                { title: "Amount", width: '5%', align: 'left', readOnly: true },
                { title: "Currency", width: '6%', align: 'left', readOnly: true },
            ],
            minDimensions: [4, 1],
            allowDeleteColumn: false,
            allowInsertRow: false,
            allowInsertColumn: false,
        };

        sourceDetailsReference_vm = new Vue({
            el: '#paymentPaidDetails',
            mounted: function () {
                let spreadsheet = jexcel(this.$el, list);
                Object.assign(this, spreadsheet);
            },
        });
    }
    
    function append_request_payment_log(data) {

        const requestDD = [ "P.I.APPROVAL", "PAYMENTS" ];
        const paymentDD = [ "ADV. PAYMENT", "BILL PAYMENT", "NIL" ];
        const statusDD = [ 
            { id: 0, name: "PENDING" },
            { id: 1, name: "RR - PENDING" },
            { id: 2, name: "APPROVED" },
            { id: 3, name: "DECLINED" },
        ];
        const approvalDD = [ "REGULAR", "PRIORITY", "HIGH PRIORITY", "IMMEDIATE" ];
        const paymentStatusDD = [ "PENDING", "DISCREPANCY", "PART PAID", "FULL PAID" ];
        let p_type = $('input[name="p_type"]:checked').val();

        $('#requestPaymentLog').html('');
        let list = {
            data: data.requestPaymentLog,
            columns: [
                { title:'id', width:'0%',align:'center',type:'hidden'},
                { type: 'text', title: 'Request For', width: '7%', align: 'left', readOnly: true },
                { type: 'dropdown', title: 'Request Type', width: '7%', align: 'left', source: approvalDD },
                { title: 'Req. Date & Time', width: '7%', align: 'left', readOnly: true },
                { type: 'dropdown', title: 'Payment\n Requirement', width: '7%', align: 'left', source: paymentDD },
                { type: 'dropdown', title: 'Approval Status', width: '7%', align: 'left', source: statusDD, readOnly: true },
                { type: 'dropdown', title: 'Approval Type', width: '7%', align: 'left', source: approvalDD, readOnly: true },
                { type: 'text', title: 'Approval By', width: '7%', align: 'left',  readOnly: true },
                { title: 'Approval \n Date  & Time', width: '7%', align: 'left', readOnly: true },
                // { type: 'dropdown', title: 'Request Status', width: '8%', align: 'left', source: statusDD, readOnly: true },
                // { title: 'Req. Status Update Date & Time', width: '6%', align: 'left', readOnly: true },
                { type: 'dropdown', title: "Payment Paid\n Status", width: '6%', align: 'left', source: paymentStatusDD, readOnly: true },
                { title: "Payment Paid Status\n Update Date  & Time", width: '8%', align: 'left', readOnly: true },
                { title:'status_id', width:'0%',align:'center',type:'hidden'},
            ],
            minDimensions: [4, 1],
            allowDeleteColumn: false,
            allowInsertColumn: false,
            onchange: function(instance, cell, col, row, val, label, cellName) {
                if(col == 4) 
                {
                    $('#saveasdraft').show();
                    $('#cleardraft').hide();
                }
            },
            updateTable: function(instance, cell, col, row, val, label) {
                if(col == 1 && row != 0)
                {
                    $(cell).text("PAYMENTS");
                    instance.jexcel.options.data[row][col] = 'PAYMENTS';
                }
                if(col == 4)
                {
                    if(p_type == 'surplus') {
                        $(cell).text("NIL");
                        instance.jexcel.options.data[row][col] = 'NIL';
                        $(cell).addClass('readonly');    
                    } else {
                        $(cell).removeClass('readonly');
                        //  $(cell).text("");
                        //  instance.jexcel.options.data[row][col] = '';    
                    }

                    
                }
            },
        };

        tblRequestPaymentLog = new Vue({
            el: '#requestPaymentLog',
            mounted: function () {
                let spreadsheet = jexcel(this.$el, list);
                Object.assign(this, spreadsheet);
            },
        });
    }
    
    function append_within_state(data) {
            //console.log('pavin state111');
       
         const itemsData = data.withinStateDetails;

            const sizeLabelMap = data.itemcodes;

// Step 1: Get allowed size codes from sizeLabelMap (column 1)
const allowedSizes = sizeLabelMap.map(row => row[1]);

const allowedSizes2 = itemsData.map(row => row[6]);

// Step 2: Filter itemsData where column 6 matches allowedSizes
//const filteredItems = itemsData.filter(allowedSizes2);
const filteredItems = itemsData.filter(item =>
  allowedSizes.includes(String(item[6]).trim())
);

 
        let p_type = $('input[name="p_type"]:checked').val();

        if(p_type == 'surplus') {
            filteredItems_data = filteredItems;
        } else {
            filteredItems_data = data.withinStateDetails;;
        }
        console.log(p_type);
        $('#withinStateDetails').html('');
        let list = {
            data: filteredItems_data,
            columns: [
                { title:'mode', width:'0%',align:'center',type:'hidden'},
                { title:'id', width:'0%',align:'center',type:'hidden'},
                { type: 'checkbox', title: 'Mark', width: '5%', align: 'left' },
                { title: 'Item\n Description', width: '7%', align: 'left', readOnly: true },
                { title: 'Blend (%) / Content /\n Material', width: '12%', align: 'left', readOnly: true },
                { title: 'Garment\n Size', width: '10%', align: 'left', readOnly: true },
                { title: 'Item Code', width: '6%', align: 'left', readOnly: true },
                { title: 'Item Colour Code', width: '6%', align: 'left', readOnly: true },
                { title: 'Size / Dim. (L*W*H)', width: '6%', align: 'center', readOnly: true },
                { title: 'UOM', width: '8%', align: 'left', readOnly: true },
                { title: 'Qty.', width: '6%', align: 'right', readOnly: true },
                { title: "UOM", width: '6%', align: 'left', readOnly: true },
                { title: "Unit Rate (Rs.)", width: '5%', align: 'right' },
                { title: "Amount\n (Rs.)", width: '6%', align: 'right', readOnly: true },
                { title: "GST\n (%)", width: '6%', align: 'right' },
                { title: "GST\n VALUE", width: '6%', align: 'right', readOnly: true },
                //{ title: "CGST\n (%)", width: '6%', align: 'right', readOnly: true },
                //{ title: "CGST\n VALUE", width: '6%', align: 'right', readOnly: true },
                //{ title: "SGST\n (%)", width: '6%', align: 'right', readOnly: true },
                //{ title: "SGST\n VALUE", width: '6%', align: 'right', readOnly: true },
                { title: "Sub Total\n (Rs.)", width: '6%', align: 'right', readOnly: true },
                 { title: "idddd", width: '6%', align: 'right', type:'hidden' },
            ],
            minDimensions: [4, 1],
            allowDeleteColumn: false,
            allowInsertRow: false,
            allowInsertColumn: false,
            footers: footer('within'),
            
            onchange: function(instance, cell, col, row, val, label, cellName) {
                if(col == 2) 
                {
                    // if(p_type == '' || p_type == undefined) {
                    //     $(cell).addClass('readonly');
                    // } else {
                    //     checkval = val; 
                    //     $(cell).removeClass('readonly');
                    // }
                    
                    if(val === true) {
                        
                        $('#cleardraft').hide();
                        $('#saveasdraft').show();
                    } else {

                        $('#cleardraft').hide();
                        $('#saveasdraft').show();
                        $('#amount_in_words').val('');
                    } 
                    
                }
                if(col == 12) 
                {
                    $('#cleardraft').hide();
                    $('#saveasdraft').show();
                }
                if(col == 14) 
                {
                    $('#cleardraft').hide();
                    $('#saveasdraft').show();
                }
            },
            updateTable: function(instance, cell, col, row, val, label) {
                
                
                if(col == 2)
                {
                    checkval = false;
                    
                    if(p_type == '' || p_type == undefined) {
                        $(cell).addClass('readonly');
                    } else {
                        checkval = val; 
                        $(cell).removeClass('readonly');
                    }
                }
                if(col == 10)
                {
                    
                    qty = val;
                }
                if(col == 12) {
                    if(checkval == true) {
                        $(cell).removeClass('readonly');
                        if(val > 0) {
                            txtValue = numeral(val).format('0.00');
                            unit_rate = txtValue;
                            $(cell).text(unit_rate);
                            instance.jexcel.options.data[row][col] = unit_rate;
                        } else {
                            unit_rate = 0;
                            $(cell).text(unit_rate);
                            instance.jexcel.options.data[row][col] = unit_rate;
                        }
                    } else {
                        unit_rate = 0;
                        $(cell).text(unit_rate);
                        instance.jexcel.options.data[row][col] = unit_rate;
                        $(cell).addClass('readonly');
                    }
                }
                if(col == 13) {
                    amount = parseFloat(qty) * parseFloat(unit_rate);
                    txtValue = numeral(amount).format('0.00');
                    $(cell).text(txtValue);
                    instance.jexcel.options.data[row][col] = txtValue;
                }
                if(col == 14) {
                    gst = 0;
                    if(checkval == true) {
                        $(cell).removeClass('readonly');
                        if(val > 0) {
                            txtValue = numeral(val).format('0.00');
                            $(cell).text(txtValue);
                            instance.jexcel.options.data[row][col] = txtValue;
                            gst = txtValue;
                            
                        }
                    } else {
                        txtValue = 0;
                        $(cell).text(txtValue);
                        instance.jexcel.options.data[row][col] = txtValue;
                        gst = txtValue;
                        $(cell).addClass('readonly');
                    }
                }
                if(col == 15) {
                    gst_value = parseFloat(amount) * parseFloat(gst) / 100;
                    txtValue = numeral(gst_value).format('0.00');
                    $(cell).text(txtValue);
                    instance.jexcel.options.data[row][col] = txtValue;
                }
                
                if(col == 16) {
                    sub_total = parseFloat(amount) + parseFloat(gst_value);
                    txtValue = numeral(sub_total).format('0.00');
                    $(cell).text(txtValue);
                    instance.jexcel.options.data[row][col] = txtValue;
                }
            }
        };

        withinStateReference_vm = new Vue({
            el: '#withinStateDetails',
            mounted: function () {
                let spreadsheet = jexcel(this.$el, list);
                Object.assign(this, spreadsheet);
            },
        });
    }
    
    function append_inter_state(data) {

        
        
        let p_type = $('input[name="p_type"]:checked').val();
         const itemsData = data.interStateDetails;

            const sizeLabelMap = data.itemcodes;

// Step 1: Get allowed size codes from sizeLabelMap (column 1)
const allowedSizes = sizeLabelMap.map(row => row[1]);

const allowedSizes2 = itemsData.map(row => row[6]);

// Step 2: Filter itemsData where column 6 matches allowedSizes
//const filteredItems = itemsData.filter(allowedSizes2);
const filteredItems = itemsData.filter(item =>
  allowedSizes.includes(String(item[6]).trim())
);

 
        //let p_type = $('input[name="p_type"]:checked').val();

        if(p_type == 'surplus') {
            filteredItems_data = filteredItems;
        } else {
            filteredItems_data = data.interStateDetails;;
        }
        $('#interStateDetails').html('');
        let list = {
            data: data.filteredItems_data,
            columns: [
                { title:'mode', width:'0%',align:'center',type:'hidden'},
                { title:'id', width:'0%',align:'center',type:'hidden'},
                { type: 'checkbox', title: 'Mark', width: '5%', align: 'left' },
                { title: 'Item\n Description', width: '7%', align: 'left', readOnly: true },
                { title: 'Blend (%) / Content /\n Material', width: '12%', align: 'left', readOnly: true },
                { title: 'Garment\n Size', width: '10%', align: 'left', readOnly: true },
                { title: 'Item Code', width: '6%', align: 'left', readOnly: true },
                { title: 'Item Colour Code', width: '6%', align: 'left', readOnly: true },
                { title: 'Size / Dim. (L*W*H)', width: '6%', align: 'center', readOnly: true },
                { title: 'UOM', width: '8%', align: 'center', readOnly: true },
                { title: 'Qty.', width: '6%', align: 'right', readOnly: true },
                { title: "UOM", width: '6%', align: 'center', readOnly: true },
                { title: "Unit Rate (Rs.)", width: '5%', align: 'right' },
                { title: "Amount\n (Rs.)", width: '6%', align: 'right', readOnly: true },
                { title: "IGST\n (%)", width: '6%', align: 'right' },
                { title: "IGST\n VALUE", width: '6%', align: 'right', readOnly: true },
                { title: "Sub Total\n (Rs.)", width: '6%', align: 'right', readOnly: true },
            ],
            minDimensions: [4, 1],
            allowDeleteColumn: false,
            allowInsertRow: false,
            allowInsertColumn: false,
            footers: footer('inter'),
            onchange: function(instance, cell, col, row, val, label, cellName) {
                if(col == 2) 
                {

                    if(val === true) {
                        $('#cleardraft').hide();
                        $('#saveasdraft').show();
                    } else {
                        $('#cleardraft').hide();
                        $('#saveasdraft').show();
                        $('#amount_in_words').val('');
                    }
                }
                if(col == 12) 
                {
                    $('#cleardraft').hide();
                    $('#saveasdraft').show();
                }
                if(col == 14) 
                {
                    $('#cleardraft').hide();
                    $('#saveasdraft').show();
                }
            },
            updateTable: function(instance, cell, col, row, val, label) {
                
                if(col == 2)
                {
                    checkval = false;
                    if(p_type == '' || p_type == undefined) {
                        $(cell).addClass('readonly');
                    } else {
                        checkval = val; 
                        $(cell).removeClass('readonly');
                    }
                }
                if(col == 10)
                {
                    qty = val;
                }
                if(col == 12) {
                    if(checkval == true) {
                        $(cell).removeClass('readonly');
                        if(val > 0) {
                            let txtValue = numeral(val).format('0.00');
                            unit_rate = txtValue;
                            $(cell).text(unit_rate);
                            instance.jexcel.options.data[row][col] = unit_rate;
                        } else {
                            unit_rate = 0;
                            $(cell).text(unit_rate);
                            instance.jexcel.options.data[row][col] = unit_rate;
                        }
                    } else {
                        unit_rate = 0;
                        $(cell).text(unit_rate);
                        instance.jexcel.options.data[row][col] = unit_rate;
                        $(cell).addClass('readonly');
                    }
                    
                }
                if(col == 13) {
                    amount = parseFloat(qty) * parseFloat(unit_rate);
                    txtValue = numeral(amount).format('0.00');
                    $(cell).text(txtValue);
                    instance.jexcel.options.data[row][col] = txtValue;
                }
                if(col == 14) {
                    igst = 0;
                    if(checkval == true) {
                        $(cell).removeClass('readonly');
                        if(val > 0) {
                            let txtValue = numeral(val).format('0.00');
                            $(cell).text(txtValue);
                            instance.jexcel.options.data[row][col] = txtValue;
                            igst = txtValue;
                            
                        }
                    } else {
                        let txtValue = 0;
                        $(cell).text(txtValue);
                        instance.jexcel.options.data[row][col] = txtValue;
                        igst = txtValue;
                        $(cell).addClass('readonly');
                    }
                }
                if(col == 15) {
                    igst_value = parseFloat(amount) * parseFloat(igst) / 100;
                    txtValue = numeral(igst_value).format('0.00');
                    $(cell).text(txtValue);
                    instance.jexcel.options.data[row][col] = txtValue;
                }
                if(col == 16) {
                    sub_total = parseFloat(amount) + parseFloat(igst_value);
                    txtValue = numeral(sub_total).format('0.00');
                    $(cell).text(txtValue);
                    instance.jexcel.options.data[row][col] = txtValue;
                }
            }
        };

        interStateReference_vm = new Vue({
            el: '#interStateDetails',
            mounted: function () {
                let spreadsheet = jexcel(this.$el, list);
                Object.assign(this, spreadsheet);
            },
        });
    }
    
    function append_imports_state(data) {
        let p_type = $('input[name="p_type"]:checked').val();
        $('#importsStateDetails').html('');

         const itemsData1 = data.importStateDetails;
        console.log(itemsData1);

            const sizeLabelMap1 = data.itemcodes;
             console.log(sizeLabelMap1);

// Step 1: Get allowed size codes from sizeLabelMap (column 1)
const allowedSizes1 = sizeLabelMap1.map(row => row[1]);



// Step 2: Filter itemsData where column 6 matches allowedSizes
//const filteredItems = itemsData.filter(allowedSizes2);
const filteredItems = itemsData1.filter(item =>
  allowedSizes1.includes(String(item[6]).trim())
);

 
        //let p_type = $('input[name="p_type"]:checked').val();

        if(p_type == 'surplus') {
            filteredItems_data1 = filteredItems1;
        } else {
            filteredItems_data1 = data.importStateDetails;;
        }
        let list = {
            data: data.importStateDetails,
            columns: [
                { title:'mode', width:'0%',align:'center',type:'hidden'},
                { title:'id', width:'0%',align:'center',type:'hidden'},
                { type: 'checkbox', title: 'Mark', width: '5%', align: 'left' },
                { title: 'Item\n Description', width: '7%', align: 'left', readOnly: true },
                { title: 'Blend (%) / Content /\n Material', width: '12%', align: 'left', readOnly: true },
                { title: 'Garment\n Size', width: '10%', align: 'left', readOnly: true },
                { title: 'Item Code', width: '6%', align: 'left', readOnly: true },
                { title: 'Item Colour Code', width: '6%', align: 'left', readOnly: true },
                { title: 'Size / Dim. (L*W*H)', width: '6%', align: 'center', readOnly: true },
                { title: 'UOM', width: '8%', align: 'center', readOnly: true },
                { title: 'Qty.', width: '6%', align: 'right', readOnly: true },
                { title: "UOM", width: '6%', align: 'center', readOnly: true },
                { title: "Currency", width: '5%', align: 'left', type: 'dropdown', source: data.currencyList },
                { title: "Unit Rate (Rs.)", width: '5%', align: 'right' },
                { title: "Amount\n (Rs.)", width: '6%', align: 'right', readOnly: true },
            ],
            minDimensions: [4, 1],
            allowDeleteColumn: false,
            allowInsertRow: false,
            allowInsertColumn: false,
            footers: footer('import'),
            onchange: function(instance, cell, col, row, val, label, cellName) {
                if(col == 2) 
                {
                    if(val === true) {
                        
                        $('#cleardraft').hide();
                        $('#saveasdraft').show();
                    } else {

                        $('#cleardraft').hide();
                        $('#saveasdraft').show();
                        $('#amount_in_words').val('');
                    }
                }
            },
            updateTable: function(instance, cell, col, row, val, label) {
                if(col == 2)
                {
                    CheckVal1 = false;
                    if(p_type == '' || p_type == undefined) {
                        $(cell).addClass('readonly');
                    } else {
                        CheckVal1 = val; 
                        $(cell).removeClass('readonly');
                    }
                }
                if(col == 10)
                {
                    qty = val;
                }
                if(col == 12)
                {
                    if(CheckVal1 == true) {
                        $(cell).removeClass('readonly');
                    } else {
                        $(cell).html('');
                        instance.jexcel.options.data[row][col] = '';
                        $(cell).addClass('readonly');
                    }
                    
                }
                
                if(col == 13) {
                    if(CheckVal1 == true) {
                        $(cell).removeClass('readonly');
                        if(val > 0) {
                            txtValue = numeral(val).format('0.00');
                            unit_rate = txtValue;
                            $(cell).text(unit_rate);
                            instance.jexcel.options.data[row][col] = unit_rate;
                        } else {
                            val = 0
                            txtValue = numeral(val).format('0.00');
                            unit_rate = txtValue;
                            $(cell).text(unit_rate);
                            instance.jexcel.options.data[row][col] = unit_rate;
                        }
                    } else {
                        unit_rate = 0;
                        $(cell).text(unit_rate);
                        instance.jexcel.options.data[row][col] = unit_rate;
                        $(cell).addClass('readonly');
                    }
                }
                if(col == 14) {
                    amount = parseFloat(qty) * parseFloat(unit_rate);
                    txtValue = numeral(amount).format('0.00');
                    $(cell).text(txtValue);
                    instance.jexcel.options.data[row][col] = txtValue;
                }
            }
        };

        importStateReference_vm = new Vue({
            el: '#importsStateDetails',
            mounted: function () {
                let spreadsheet = jexcel(this.$el, list);
                Object.assign(this, spreadsheet);
            },
        });
    }


    $('#cleardraft').click(function () {
               
            swalWithBootstrapButtons.fire(
                // *** CONFIRMATION MESSAGE *** //
                alertMessageFunction('confirmation_save')
            ).then(function (result) {
                if (result.value) {
                    clearFunction('clear');
                } 
                else if (result.dismiss === Swal.DismissReason.cancel) {
                    // *** CANCELLED MESSAGE *** //
                    swalWithBootstrapButtons.fire(
                        alertMessageFunction('cancelled')
                    );
                }
            });
    });

    function clearFunction(type) {
        let dataform = new FormData();

        //let bom_tbl_data = sampleRequest_vm.getData();

        
        dataform.append('request_id', reqId);
        dataform.append('type', type);

        let request = $.ajax({
            type: "POST",
            url: base_path + 'request/Bomrequest/clearPiDraft',
            data: dataform,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                val = JSON.parse(data);
                if(val.status == "Success")
                {
                     window.location.href = base_path + 'request/Bomrequest/draftpi'  + '/' + encodeURIComponent(btoa(enquiry_id)) + '/reqId/' + encodeURIComponent(btoa(reqId)) + '';
                }
            },
            error: function () {
                console.log("Error");
            }
        });
    }

    $('#saveasdraft').click(function () {
        let purchase_mode = $('input[name="mode"]:checked').val();
        swalWithBootstrapButtons.fire(
            // *** CONFIRMATION MESSAGE *** //
            alertMessageFunction('confirmation_save')
        ).then(function (result) {
            if (result.value) {
                let req_data = paymentRequest_vm.getData();
                let log_data = tblRequestPaymentLog.getData();
                let purchaseIndentData = '';
                if(purchase_mode == 'within') {
                    purchaseIndentData = withinStateReference_vm.getData();
                }
                else if(purchase_mode == 'inter') {
                    purchaseIndentData = interStateReference_vm.getData();
                }
                else if(purchase_mode == 'imports') {
                    purchaseIndentData = importStateReference_vm.getData();
                }
                updateFunction(req_data, purchaseIndentData, log_data,'draft');
            } 
            else if (result.dismiss === Swal.DismissReason.cancel) {
                // *** CANCELLED MESSAGE *** //
                swalWithBootstrapButtons.fire(
                    alertMessageFunction('cancelled')
                );
            }
        });
    });

    
    // $('#getValues').click(function () {
    //     swalWithBootstrapButtons.fire(
    //         // *** CONFIRMATION MESSAGE *** //
    //         alertMessageFunction('confirmation_save')
    //     ).then(function (result) {
    //         if (result.value) {
    //             let req_data = paymentRequest_vm.getData();
    //             let log_data = tblRequestPaymentLog.getData();
    //             let purchaseIndentData = '';
    //             if(purchase_mode == 'within') {
    //                 purchaseIndentData = withinStateReference_vm.getData();
    //             }
    //             else if(purchase_mode == 'inter') {
    //                 purchaseIndentData = interStateReference_vm.getData();
    //             }
    //             else if(purchase_mode == 'imports') {
    //                 purchaseIndentData = importStateReference_vm.getData();
    //             } 
    //             else if($('#supply_lead_time').val() == "" || $('#supply_lead_time').val() == null ) {
    //                 swalWithBootstrapButtons.fire(
    //                 alertMessageFunction('validation_error')
    //             )
    //             } else {
    //               updateFunction(req_data, purchaseIndentData, log_data,'save'); 
    //             }
                
    //         } 
    //         else if (result.dismiss === Swal.DismissReason.cancel) {
    //             // *** CANCELLED MESSAGE *** //
    //             swalWithBootstrapButtons.fire(
    //                 alertMessageFunction('cancelled')
    //             );
    //         }
    //     });
    // });
    
    // $('#getValues').click(function () {
    //     let selectCount = 1;

    //     if (jQuery('#purchaseImageUpload').val() == '') {
    //         //console.log('Empty');
    //     }
    //     let req_data = paymentRequest_vm.getData();
    //     let log_data = tblRequestPaymentLog.getData();
    //     let purchase_mode = $('input[name="mode"]:checked').val();
    //     //console.log(req_data);
    //     let purchaseIndentData = '';
    //     if(purchase_mode == 'within') {
    //         purchaseIndentData = withinStateReference_vm.getData();
    //     }
    //     else if(purchase_mode == 'inter') {
    //         purchaseIndentData = interStateReference_vm.getData();
    //     }
    //     else if(purchase_mode == 'imports') {
    //         purchaseIndentData = importStateReference_vm.getData();
    //     }

    //     let validateField = [2,12,14];
    //     let validatedErrorCount = validateForm(validateField, purchaseIndentData);
    //     let validatedlogErrorCount=0;
        
    //     if(log_data[0][4] != 'NIL') {
    //         let validate_fields = [2, 3, 4, 5, 6, 7, 8, 9, 10,11, 12];
    //         validatedlogErrorCount = validateFormLog(validate_fields, req_data);
    //     } else {
    //         validatedlogErrorCount = 0;
    //     }
        
    //     if(selectCount <= 0 ) {
    //         swalWithBootstrapButtons.fire(
    //             alertMessageFunction('selecterror')
    //         );
    //     }
    //     else {
            
    //         if(validatedErrorCount > 0)
    //         { 
    //          swalWithBootstrapButtons.fire(
    //                 alertMessageFunction('validation_error')
    //             )
    //         } else if(validatedlogErrorCount > 0) {
    //             //alert(validatedlogErrorCount);
    //              swalWithBootstrapButtons.fire(
    //                 alertMessageFunction('validation_error')
    //             )
    //         }

    //         else if($('#purchase_type').val() == "" || $('#purchase_type').val() == null ) {
    //         //alert('Fill All Fields');
    //         swalWithBootstrapButtons.fire(
    //                 alertMessageFunction('validation_error')
    //             )
    //     }
    //     else if($('#payment_terms').val() == "" || $('#payment_terms').val() == null ) {
    //         swalWithBootstrapButtons.fire(
    //                 alertMessageFunction('validation_error')
    //             )
    //     }
    //     else if($('#amount_in_words').val() == "" || $('#amount_in_words').val() == null ) {
    //         swalWithBootstrapButtons.fire(
    //                 alertMessageFunction('validation_error')
    //             )
    //     }
    //     else if($('#exp_dod').val() == "" || $('#exp_dod').val() == null ) {
    //         swalWithBootstrapButtons.fire(
    //                 alertMessageFunction('validation_error')
    //             )
    //     } 
    //     else {
    //         swalWithBootstrapButtons.fire(
    //             // *** CONFIRMATION MESSAGE *** //
    //             alertMessageFunction('confirmation_save')
    //         ).then(function (result) {
    //             if (result.value) {
    //                 updateFunction(req_data, purchaseIndentData, log_data,'save');
    //             } 
    //             else if (result.dismiss === Swal.DismissReason.cancel) {
    //                 // *** CANCELLED MESSAGE *** //
    //                 swalWithBootstrapButtons.fire(
    //                     alertMessageFunction('cancelled')
    //                 );
    //             }
    //         });
    //     }
            
    //     }
    // });

    $('#getValues').click(function () {
    // Disable the button to prevent double click
    var $this = $(this);
    $this.prop('disabled', true);

    let selectCount = 1;

    if (jQuery('#purchaseImageUpload').val() == '') {
        //console.log('Empty');
    }
    let req_data = paymentRequest_vm.getData();
    let log_data = tblRequestPaymentLog.getData();
    let purchase_mode = $('input[name="mode"]:checked').val();
    //console.log(req_data);
    let purchaseIndentData = '';
    if(purchase_mode == 'within') {
        purchaseIndentData = withinStateReference_vm.getData();
    }
    else if(purchase_mode == 'inter') {
        purchaseIndentData = interStateReference_vm.getData();
    }
    else if(purchase_mode == 'imports') {
        purchaseIndentData = importStateReference_vm.getData();
    }

    let validateField = [2,12,14];
    let validatedErrorCount = validateForm(validateField, purchaseIndentData);
    let validatedlogErrorCount = 0;
    
    if(log_data[0][4] != 'NIL') {
        let validate_fields = [2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12];
        validatedlogErrorCount = validateFormLog(validate_fields, req_data);
    } else {
        validatedlogErrorCount = 0;
    }
    
    if(selectCount <= 0 ) {
        swalWithBootstrapButtons.fire(
            alertMessageFunction('selecterror')
        );
        $this.prop('disabled', false); // Enable the button again if validation fails
    }
    else {
        
        if(validatedErrorCount > 0)
        { 
            swalWithBootstrapButtons.fire(
                alertMessageFunction('validation_error')
            );
            $this.prop('disabled', false); // Enable the button again if validation fails
        } else if(validatedlogErrorCount > 0) {
            //alert(validatedlogErrorCount);
            swalWithBootstrapButtons.fire(
                alertMessageFunction('validation_error')
            );
            $this.prop('disabled', false); // Enable the button again if validation fails
        }
        else if($('#purchase_type').val() == "" || $('#purchase_type').val() == null ) {
            //alert('Fill All Fields');
            swalWithBootstrapButtons.fire(
                alertMessageFunction('validation_error')
            );
            $this.prop('disabled', false); // Enable the button again if validation fails
        }
        else if($('#payment_terms').val() == "" || $('#payment_terms').val() == null ) {
            swalWithBootstrapButtons.fire(
                alertMessageFunction('validation_error')
            );
            $this.prop('disabled', false); // Enable the button again if validation fails
        }
        else if($('#amount_in_words').val() == "" || $('#amount_in_words').val() == null ) {
            swalWithBootstrapButtons.fire(
                alertMessageFunction('validation_error')
            );
            $this.prop('disabled', false); // Enable the button again if validation fails
        }
        else if($('#exp_dod').val() == "" || $('#exp_dod').val() == null ) {
            swalWithBootstrapButtons.fire(
                alertMessageFunction('validation_error')
            );
            $this.prop('disabled', false); // Enable the button again if validation fails
        } 
        else {
            swalWithBootstrapButtons.fire(
                // *** CONFIRMATION MESSAGE *** //
                alertMessageFunction('confirmation_save')
            ).then(function (result) {
                if (result.value) {
                    updateFunction(req_data, purchaseIndentData, log_data, 'save');
                } 
                else if (result.dismiss === Swal.DismissReason.cancel) {
                    // *** CANCELLED MESSAGE *** //
                    swalWithBootstrapButtons.fire(
                        alertMessageFunction('cancelled')
                    );
                }

                // Re-enable the button after the action is complete (success or cancel)
                $this.prop('disabled', false);
            });
        }
    }
});

    
    function get_amt()
    {
        let purchaseIndentData = '';
        let purchase_mode = $('input[name="mode"]:checked').val();
        let p_type = $('input[name="p_type"]:checked').val();
        if(purchase_mode != '' && p_type != '') {
            if(purchase_mode == 'within') {
                purchaseIndentData = withinStateReference_vm.getData();
            }
            else if(purchase_mode == 'inter') {
                purchaseIndentData = interStateReference_vm.getData();
            }
            else if(purchase_mode == 'imports') {
                purchaseIndentData = importStateReference_vm.getData();
            }
        
            getAmtToWords(purchaseIndentData,purchase_mode);
        }
    }
    
    
    function getAmtToWords(purchaseIndentData,purchase_mode)
    {
        let tot = 0;
        let curr = '';
        if(purchase_mode == 'imports' ) {
            for (let i = 0; i < purchaseIndentData.length; i++) {
                if(purchaseIndentData[i][14] > 0) {
                    curr = purchaseIndentData[i][12];
                    tot = parseFloat(tot) +parseFloat(purchaseIndentData[i][14]) ;
                }
            }
            
            if(tot > 0) {
            var num =numeral(tot).format('0.00');;
            var splittedNum =num.toString().split('.')
            var nonDecimal=splittedNum[0]
            var decimal=splittedNum[1]
            //console.log(num);
            if(decimal > 0) {
                var value=curr+"  "+price_in_words(Number(nonDecimal))+" point "+price_in_words1(decimal)+" "+"Only";
            } else {
                var value= curr+"  "+price_in_words(Number(nonDecimal))+" "+"Only";
            }
            
            
            $('#amount_in_words').val(value);
        }
        
        } else {
            for (let i = 0; i < purchaseIndentData.length; i++) {
                if(purchaseIndentData[i][16] > 0) {
                    tot = parseFloat(tot) +parseFloat(purchaseIndentData[i][16]) ;
                }
            }
            
            if(tot > 0) {
            var num =numeral(tot).format('0.00');;
            var splittedNum =num.toString().split('.')
            var nonDecimal=splittedNum[0]
            var decimal=splittedNum[1]
            //console.log(num);
            if(decimal > 0) {
                var value="Rupees"+" "+price_in_words(Number(nonDecimal))+" and Paise"+price_in_words(decimal)+" "+"Only";
            } else {
                var value="Rupees"+" "+price_in_words(Number(nonDecimal))+" "+"Only";
            }
            
            
            $('#amount_in_words').val(value);
        }
        }
        
        
        
    }
    
function price_in_words(price) {
    var sglDigit = ["Zero", "One", "Two", "Three", "Four", "Five", "Six", "Seven", "Eight", "Nine"],
    dblDigit = ["Ten", "Eleven", "Twelve", "Thirteen", "Fourteen", "Fifteen", "Sixteen", "Seventeen", "Eighteen", "Nineteen"],
    tensPlace = ["", "Ten", "Twenty", "Thirty", "Forty", "Fifty", "Sixty", "Seventy", "Eighty", "Ninety"],
    handle_tens = function(dgt, prevDgt) {
      return 0 == dgt ? "" : " " + (1 == dgt ? dblDigit[prevDgt] : tensPlace[dgt])
    },
    handle_utlc = function(dgt, nxtDgt, denom) {
      return (0 != dgt && 1 != nxtDgt ? " " + sglDigit[dgt] : "") + (0 != nxtDgt || dgt > 0 ? " " + denom : "")
    };

  var str = "",
    digitIdx = 0,
    digit = 0,
    nxtDigit = 0,
    words = [];
  if (price += "", isNaN(parseInt(price))) str = "";
  else if (parseInt(price) > 0 && price.length <= 10) {
    for (digitIdx = price.length - 1; digitIdx >= 0; digitIdx--) switch (digit = price[digitIdx] - 0, nxtDigit = digitIdx > 0 ? price[digitIdx - 1] - 0 : 0, price.length - digitIdx - 1) {
      case 0:
        words.push(handle_utlc(digit, nxtDigit, ""));
        break;
      case 1:
        words.push(handle_tens(digit, price[digitIdx + 1]));
        break;
      case 2:
        words.push(0 != digit ? " " + sglDigit[digit] + " Hundred" + (0 != price[digitIdx + 1] && 0 != price[digitIdx + 2] ? " and" : "") : "");
        break;
      case 3:
        words.push(handle_utlc(digit, nxtDigit, "Thousand"));
        break;
      case 4:
        words.push(handle_tens(digit, price[digitIdx + 1]));
        break;
      case 5:
        words.push(handle_utlc(digit, nxtDigit, "Lakh"));
        break;
      case 6:
        words.push(handle_tens(digit, price[digitIdx + 1]));
        break;
      case 7:
        words.push(handle_utlc(digit, nxtDigit, "Crore"));
        break;
      case 8:
        words.push(handle_tens(digit, price[digitIdx + 1]));
        break;
      case 9:
        words.push(0 != digit ? " " + sglDigit[digit] + " Hundred" + (0 != price[digitIdx + 1] || 0 != price[digitIdx + 2] ? " and" : " Crore") : "")
    }
    str = words.reverse().join("")
  } else str = "";
  return str

}

function price_in_words1(price) {
    var sglDigit = ["Zero", "One", "Two", "Three", "Four", "Five", "Six", "Seven", "Eight", "Nine"],
    handle_utlc = function(dgt, nxtDgt, denom) {
      return ( 1 != nxtDgt ? " " + sglDigit[dgt] : "") 
    };

  var str = "",
    digitIdx = 0,
    digit = 0,
    nxtDigit = 0,
    words = [];
  if (price += "", isNaN(parseInt(price))) str = "";
  else if (parseInt(price) > 0 && price.length <= 2) {
    for (digitIdx = price.length - 1; digitIdx >= 0; digitIdx--) switch (digit = price[digitIdx] - 0, nxtDigit = digitIdx > 0 ? price[digitIdx - 1] - 0 : 0, price.length - digitIdx - 1) {
      case 0:
        words.push(handle_utlc(digit, nxtDigit, ""));
        break;
      case 1:
        words.push(handle_utlc(digit, price[digitIdx + 1]));
        break;
    }
    str = words.reverse().join("")
  } else str = "";
  return str

}


    function validateForm(validateField, dataValue ) {
        let errorCount = 0;
        for (let i = 0; i < dataValue.length; i++) {
            for(let j = 0; j < validateField.length; j++) {
                let col = validateField[j]
                if(col == 2) {
                    if(dataValue[i][col] == true) {
                        if(dataValue[i][12] == "" || dataValue[i][12] == 0) {
                            errorCount++;
                        }
                        if(dataValue[i][14] == "" || dataValue[i][14] == 0) {
                            errorCount++;
                        } 
                    }
                }
                
            }
        }
        return errorCount;
    }
    
    function validateFormLog(validateField, dataValue ) {
        let errorCount = 0;
        for (let i = 0; i < dataValue.length; i++) {
                for(let j = 0; j < validateField.length; j++) {
                    let col = validateField[j]
                    if(dataValue[i][col] == "") {
                        errorCount++;
                    }
                }
            }
        return errorCount;
    }

    function updateFunction(data, purchaseData, log_data,types) {
        let dataform = new FormData();
        
        let p_type = $('input[name="p_type"]:checked').val();
        let purchase_indent_id = $('#purchase_indent_id').val();
        let purchase_mode = $('input[name="mode"]:checked').val();
        dataform.append('data', JSON.stringify(data));
        dataform.append('log_data', JSON.stringify(log_data));
        dataform.append('purchaseIndentData', JSON.stringify(purchaseData));
        dataform.append('enquiry_id', enquiry_id);
        dataform.append('reqId', req_id);
        dataform.append('mode', purchase_mode);
        dataform.append('p_type', p_type);
        dataform.append('type', types);
        dataform.append('purchase_indent_id', purchase_indent_id);
        dataform.append('pi_cutoff_dt', $('#pi_cutoff_dt').val());
        dataform.append('purchase_dept_note', $('#purchase_dept_note').val());
        dataform.append('vendorOption', $('#vendorOption').val());
        dataform.append('purchase_type', $('#purchase_type').val());
        dataform.append('payment_terms', $('#payment_terms').val());
        dataform.append('amount_in_words', $('#amount_in_words').val());
        dataform.append('exp_dod', $('#exp_dod').val());

        let request = $.ajax({
            type: "POST",
            url: base_path + 'request/Bomrequest/updatePurchaseIndent',
            data: dataform,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                // getDraftPIRequest();
                // // *** SAVED MESSAGE *** //
                purchaseUpload.startUpload();
                swalWithBootstrapButtons.fire(
                    alertMessageFunction('saved')
                ).then(okay => {
                    if(okay)
                    {
                        //window.location.href = base_path + 'company/mpurchaseuser/bomPurchaseReqQueueList';
                         window.location.href = base_path + 'company/mpurchaseuser/purchasesentlist';
                    }
                });
            },
            error: function () {
                console.log("Error");
            }
        });
    }

    function appendAddressField(vendorData) {
        vendorResponse = vendorData;
        if(bom_requirement_data.fullData[0]) {
            var vendorId = bom_requirement_data.fullData[0].vendor_id;
        }

        
        var vendorOption = "<option value='' data-index=''>Select</option>";
        for(let i=0; i < vendorData.length; i++) {
            var selected = '';
            if(vendorId != '') {
                if(vendorId == vendorData[i].id) {
                    var vendorIds = vendorId;
                    selected = 'selected';
                    if(vendorResponse[i].id == vendorId) {
                        var vendorDet = vendorResponse[i];
                        
                    }
                    
                    purchaseVendorData[0][1] = vendorDet.id;
                        append_purchase_request(bom_requirement_data, purchaseVendorData);
                    $("#vendorAddress").html(vendorDet.address);
                    $("#vendorContact").html(vendorDet.phone);
                    $("#vendorEmail").html(vendorDet.emailid);
                    $("#vendorGst").html(vendorDet.gstno);
                    $("#vendorIeCode").html(vendorDet.iecode);
                } 
            } 
            vendorOption = vendorOption + "<option value='"+vendorData[i].id+"' data-index='"+i+"' "+selected+">"+vendorData[i].vendorname+"</option>";
                
        }
        $("#vendorOption").html(vendorOption);
    }
    
    function vendor_change()
    {
        $('#vendorOption').val('');
        let optionValue = $('option:selected', this).attr('data-index');
        if(optionValue !="") {
            $("#vendorAddress").html("");
            $("#vendorContact").html("");
            $("#vendorEmail").html("");
            $("#vendorGst").html("");
            $("#vendorIeCode").html("");   
        }
        else {
            $("#vendorAddress").html("");
            $("#vendorContact").html("");
            $("#vendorEmail").html("");
            $("#vendorGst").html("");
            $("#vendorIeCode").html("");   
        }
    }

    $("#vendorOption").change(function() {
        let optionValue = $('option:selected', this).attr('data-index');
        if(optionValue !="") {
            let vendorDet = vendorResponse[optionValue];
            purchaseVendorData[0][1] = vendorDet.id;
            append_purchase_request(bom_requirement_data, purchaseVendorData);
            $("#vendorAddress").html(vendorDet.address);
            $("#vendorContact").html(vendorDet.phone);
            $("#vendorEmail").html(vendorDet.emailid);
            $("#vendorGst").html(vendorDet.gstno);
            $("#vendorIeCode").html(vendorDet.iecode);   
        }
        else {
            $("#vendorAddress").html("");
            $("#vendorContact").html("");
            $("#vendorEmail").html("");
            $("#vendorGst").html("");
            $("#vendorIeCode").html("");   
        }
    });

    let purchaseUpload = $("#purchaseImageUpload").uploadFile({
        dragDrop: true,
        multiple: true,
        url:base_path+'request/Bomrequest/purchaseImageUploadDetails',
        returnType: "json",
        fileName: "myFile",
        allowedTypes: allowedFileTypes,
        dynamicFormData:function () {
            return {
                enquiry_id: enquiry_id,
                request_id: reqId,
            };
        },
        afterUploadAll: function () {
            
        },
        autoSubmit: false
    });

    // *********************************************************************************************************************************** 
    // SAMPLE REQUEST ENDS HERE 
    // ***********************************************************************************************************************************
    
});