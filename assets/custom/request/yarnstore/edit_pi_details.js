$(document).ready(function () {

    let parts = window.location.href.split('/');
    let request_id = parts[parts.length - 1];
    let req_id = atob(decodeURIComponent(request_id));

    getQABomRequest();

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

    // *********************************************************************************************************************************** 
    // Purchase REQUEST STARTS HERE 
    // ***********************************************************************************************************************************

    function getQABomRequest() {
        var data = new FormData();
        data.append('enquiry_id', enquiry_id);
        data.append('reqId', req_id);
        let request = $.ajax({
            type: "POST",
            url: base_path + 'request/myarnstore/getMerchantBomQueueDetails',
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                bom_requirement_data = JSON.parse(data);
                append_in_house_details(bom_requirement_data);
                append_item_accept_status(bom_requirement_data);
                append_in_house_consolidated_qty(bom_requirement_data);
            },
            error: function () {
                console.log("Error");
            }
        });
    }

    function append_in_house_details(data) {
        $('#inHouseStatus').html('');
        let list = {
            data: data.inhousestatusdetails,
            columns: [
                { title:'id', width:'0%',align:'center',type:'hidden'},
                { type: 'text', title: 'Yarn\n Vendor', width: '8%', align: 'left', readOnly: true },
                { type: 'text', title: "Yarn Product\n Code (Vendor's)", width: '8%', align: 'left', readOnly: true },
                { type: 'text', title: 'Yarn Blend (%) /\n Content', width: '8%', align: 'left', readOnly: true },
                { type: 'text', title: 'Yarn Count', width: '8%', align: 'left', readOnly: true },
                { title: 'Yarn Special\n Request.', width: '7%', align: 'center', readOnly: true },
                { title: 'Yarn Colour', width: '7%', align: 'center', readOnly: true },
                { title: 'P.I. Ref. No.', width: '8%', align: 'right', readOnly: true },
                { title: 'D.C. No.', width: '8%', align: 'right' },
                { title: 'D.C. Date', width: '8%', align: 'right', type: 'calendar' },
                { title: 'D.C. Qty.', width: '8%', align: 'right' },
                { title: 'Invoice No.', width: '8%', align: 'right' },
                { title: 'Invoice Date', width: '8%', align: 'right', type: 'calendar' },
                { title: 'Invoice Qty.', width: '8%', align: 'right' },
                { title: 'Received Qty.', width: '8%', align: 'right' },
                { title: 'UOM', width: '5%', align: 'center', type: 'dropdown', source: data.uomData },
                { title: 'Received Date', width: '6%', align: 'center', type: 'calendar' },
            ],
            minDimensions: [4, 1],
            allowDeleteColumn: false,
            allowInsertRow: false,
            allowInsertColumn: false,
        };

        inHouseStatusReference_vm = new Vue({
            el: '#inHouseStatus',
            mounted: function () {
                let spreadsheet = jexcel(this.$el, list);
                Object.assign(this, spreadsheet);
            },
        });
    }

    function append_item_accept_status(data) {

        let approvalStatusData = [
           { 'id': "0", 'name': 'PENDING' },
           { 'id': "1", 'name': 'APPROVED' },
           { 'id': "2", 'name': 'DISCREPANCY' }
        ];

        let qaStatusData = [
           { 'id': "0", 'name': 'PENDING' },
           { 'id': "1", 'name': 'APPROVED' },
           { 'id': "2", 'name': 'REJECTED' }
        ];

        $('#itemAcceptStatus').html('');
        let list = {
            data: data.itemacceptstatus,
            columns: [
                { title:'id', width:'0%',align:'center',type:'hidden'},
                { type: 'text', title: 'Yarn\n Vendor', width: '8%', align: 'left', readOnly: true },
                { type: 'text', title: "Yarn Product\n Code (Vendor's)", width: '8%', align: 'left', readOnly: true },
                { type: 'text', title: 'Yarn Blend (%) /\n Content', width: '8%', align: 'left', readOnly: true },
                { type: 'text', title: 'Yarn Count', width: '8%', align: 'left', readOnly: true },
                { title: 'Yarn Special\n Request.', width: '7%', align: 'center', readOnly: true },
                { title: 'Yarn Colour', width: '7%', align: 'center', readOnly: true },
                { title: 'D.C. No.', width: '6%', align: 'right', readOnly: true },
                { title: 'D.C. Date', width: '6%', align: 'right', readOnly: true },
                { title: 'Invoice No.', width: '6%', align: 'right', readOnly: true },
                { title: 'Invoice Date', width: '6%', align: 'right', readOnly: true },
                { title: 'Merchant Item\n Approval Status', width: '8%', align: 'center', type: 'dropdown', source: approvalStatusData, readOnly: true },
                { title: 'Merchant Appl.\n Date & Time', width: '8%', align: 'center', type: 'calendar', readOnly: true },
                { title: 'Q.A. Status', width: '8%', align: 'center', type: 'dropdown', source: approvalStatusData },
                { title: 'Q.A. Status Update\n Date & Time', width: '8%', align: 'center', type: 'calendar', readOnly: true },
                { title: 'Management\n Overriding Status', width: '8%', align: 'center', readOnly: true, type: 'dropdown', source: approvalStatusData },
                { title: 'Management Status\n Update Date & Time', width: '8%', align: 'center', type: 'calendar', readOnly: true },
            ],
            minDimensions: [4, 1],
            allowDeleteColumn: false,
            allowInsertRow: false,
            allowInsertColumn: false
        };

        itemAcceptStatusReference_vm = new Vue({
            el: '#itemAcceptStatus',
            mounted: function () {
                let spreadsheet = jexcel(this.$el, list);
                Object.assign(this, spreadsheet);
            },
        });
    }

    function append_in_house_consolidated_qty(data) {

        let supplyClosureData = [
           { 'id': "0", 'name': 'PENDING' },
           { 'id': "1", 'name': 'DISCREPANCY' },
           { 'id': "2", 'name': 'SUPPLY CLOSED' }
        ];

        let itemRTIData = [
           { 'id': "0", 'name': 'PENDING' },
           { 'id': "1", 'name': 'DISCREPANCY' },
           { 'id': "2", 'name': 'READY TO ISSUE' }
        ];

        $('#inHouseConsolidatedQty').html('');
        let list = {
            data: data.inhouseconsolidatedqtydetails,
            columns: [
                { title:'id', width:'0%',align:'center',type:'hidden'},
                
                { type: 'text', title: 'Yarn\n Vendor', width: '8%', align: 'left', readOnly: true },
                { type: 'text', title: "Yarn Product\n Code (Vendor's)", width: '8%', align: 'left', readOnly: true },
                { type: 'text', title: 'Yarn Blend (%) /\n Content', width: '8%', align: 'left', readOnly: true },
                { type: 'text', title: 'Yarn Count', width: '8%', align: 'left', readOnly: true },
                { title: 'Yarn Special\n Request.', width: '7%', align: 'center', readOnly: true },
                { title: 'Yarn Colour', width: '7%', align: 'center', readOnly: true },
                
                { title: 'Planned Qty.', width: '5%', align: 'right', readOnly: true },
                { title: 'UOM', width: '5%', align: 'center', readOnly: true },
                { title: 'P.I. Qty.', width: '5%', align: 'right', readOnly: true },
                { title: 'UOM', width: '5%', align: 'center', readOnly: true },
                { title: 'Received Qty.', width: '5%', align: 'center', readOnly: true },
                { title: 'UOM', width: '5%', align: 'center', readOnly: true },
                { title: 'Difference Qty.', width: '5%', align: 'center', readOnly: true },
                { title: 'UOM', width: '5%', align: 'center', readOnly: true },
                { title: 'Supply Closure\n Status', width: '8%', align: 'center', type: 'dropdown', source: supplyClosureData },
                { title: 'BOM Store - Item\n RTI Status', width: '8%', align: 'center', type: 'dropdown', source: itemRTIData },
            ],
            minDimensions: [4, 1],
            allowDeleteColumn: false,
            allowInsertRow: false,
            allowInsertColumn: false,
        };

        inHouseConsolidatedReference_vm = new Vue({
            el: '#inHouseConsolidatedQty',
            mounted: function () {
                let spreadsheet = jexcel(this.$el, list);
                Object.assign(this, spreadsheet);
            },
        });
    }

    
    $('#getValues').click(function () {
        swalWithBootstrapButtons.fire(
            // *** CONFIRMATION MESSAGE *** //
            alertMessageFunction('confirmation_save')
        ).then(function (result) {
            if (result.value) {
                let inHouseData = inHouseStatusReference_vm.getData();
                let itemAccept = itemAcceptStatusReference_vm.getData();
                let inHouseConsolidate = inHouseConsolidatedReference_vm.getData();
                updateFunction(inHouseData, itemAccept, inHouseConsolidate);
            } 
            else if (result.dismiss === Swal.DismissReason.cancel) {
                // *** CANCELLED MESSAGE *** //
                swalWithBootstrapButtons.fire(
                    alertMessageFunction('cancelled')
                );
            }
        });
    });

    function updateFunction(inHouseData, itemAccept, inHouseConsolidate) {
        let dataform = new FormData();
        dataform.append('inHouseData', JSON.stringify(inHouseData));
        dataform.append('itemAccept', JSON.stringify(itemAccept));
        dataform.append('inHouseConsolidate', JSON.stringify(inHouseConsolidate));
        dataform.append('enquiry_id', enquiry_id);

        let request = $.ajax({
            type: "POST",
            url: base_path + 'request/myarnstore/updateStorePiDetails',
            data: dataform,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                getQABomRequest();
                // *** SAVED MESSAGE *** //
                swalWithBootstrapButtons.fire(
                    alertMessageFunction('saved')
                );
                setTimeout(() => {
                    window.location.href = base_path + 'request/myarnstore/purchaseindentlist';
                }, 1000);
            },
            error: function () {
                console.log("Error");
            }
        });
    }

    // *********************************************************************************************************************************** 
    // SAMPLE REQUEST ENDS HERE 
    // ***********************************************************************************************************************************
    
});