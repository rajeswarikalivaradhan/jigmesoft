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
            url: base_path + 'request/Bomrequest/getNewItemDetails',
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                bom_requirement_data = JSON.parse(data);
                append_in_house_details(bom_requirement_data);
                append_material_indent_received_details(bom_requirement_data);
                append_material_issued_details(bom_requirement_data);
                append_shipment_order_closure_details(bom_requirement_data);
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
                { type: 'text', title: 'Item Description', width: '8%', align: 'left', readOnly: true },
                { type: 'text', title: 'Blend (%) / Content /\n Material', width: '12%', align: 'left', readOnly: true },
                { type: 'text', title: 'Garment\n Size', width: '8%', align: 'left', readOnly: true },
                { type: 'text', title: 'Approved\n Item Code', width: '8%', align: 'left', readOnly: true },
                { type: 'text', title: 'Approved Item\n Colour Code', width: '8%', align: 'left', readOnly: true },
                { title: 'Size / Dim.\n (L*W*H)', width: '7%', align: 'center', readOnly: true },
                { title: 'UOM', width: '7%', align: 'center', readOnly: true },
                { title: 'P.I. Ref. No.', width: '8%', align: 'right', readOnly: true },
                { title: 'Received Qty.', width: '8%', align: 'right' },
                { title: 'UOM', width: '5%', align: 'center', type: 'dropdown', source: data.uomData },
                { title: 'Received Date & Time', width: '6%', align: 'center', type: 'calendar' },
                { title: 'Storage Bin /\n Rack Ref. No.', width: '8%', align: 'right' },
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

    function append_material_indent_received_details(data) {

        $('#materialIndentReceived').html('');
        let list = {
            data: [],
            columns: [
                { title:'id', width:'0%',align:'center',type:'hidden'},
                { type: 'text', title: 'M.I. Ref. No.', width: '6%', align: 'left', readOnly: true },
                { type: 'text', title: 'M.I. Request\n Date & Time', width: '6%', align: 'left', readOnly: true },
                { type: 'text', title: 'M.I. Cutoff\n Date & Time', width: '6%', align: 'left', readOnly: true },
                { type: 'text', title: 'P.O. No.', width: '6%', align: 'left', readOnly: true },
                { title: 'Combo', width: '6%', align: 'right', readOnly: true },
                { title: 'Component', width: '6%', align: 'right', readOnly: true },
                { title: 'Colour', width: '6%', align: 'right', readOnly: true },
                { title: 'Size Spec Code', width: '6%', align: 'right', readOnly: true },
                { title: 'Purpose', width: '8%', align: 'center', readOnly: true },
                { title: 'M.I. Type\n Interna / External', width: '8%', align: 'center', readOnly: true },
                { title: 'Issued to Dept. / Vendor Name', width: '8%', align: 'center', readOnly: true },
                { title: 'M.I. Qty.', width: '6%', align: 'center', type: 'calendar', readOnly: true },
                { title: 'UOM', width: '5%', align: 'center', readOnly: true },
            ],
            minDimensions: [4, 1],
            allowDeleteColumn: false,
            allowInsertRow: false,
            allowInsertColumn: false
        };

        itemAcceptStatusReference_vm = new Vue({
            el: '#materialIndentReceived',
            mounted: function () {
                let spreadsheet = jexcel(this.$el, list);
                Object.assign(this, spreadsheet);
            },
        });
    }

    function append_material_issued_details(data) {

        $('#materialIssuedDetails').html('');
        let list = {
            data: [],
            columns: [
                { title:'id', width:'0%',align:'center',type:'hidden'},
                { type: 'text', title: 'M.I. Ref. No.', width: '6%', align: 'left', readOnly: true },
                { type: 'text', title: 'Issued to Dept. /\n Vendor Name', width: '6%', align: 'left', readOnly: true },
                { type: 'text', title: 'D.C. No.', width: '6%', align: 'left', readOnly: true },
                { type: 'text', title: 'D.C.\n Date & Time', width: '6%', align: 'left', readOnly: true },
                { title: 'Issued Qty.', width: '6%', align: 'right', readOnly: true },
                { title: 'Issued in\n Part / Full', width: '6%', align: 'right', readOnly: true },
                { title: 'M.I.\n Pending Qty.', width: '6%', align: 'right', readOnly: true },
                { title: 'Returned\n Defective Qty.', width: '6%', align: 'right', readOnly: true },
                { title: 'Replaced\n Defective Qty.', width: '8%', align: 'center', readOnly: true },
                { title: 'Returned\n Excess Qty.', width: '8%', align: 'center', readOnly: true },
                { title: 'Total\n Available Qty.', width: '8%', align: 'center', readOnly: true },
                { title: 'UOM', width: '6%', align: 'center', type: 'calendar', readOnly: true },
                { title: 'Issued By', width: '5%', align: 'center', readOnly: true },
                { title: 'Received By', width: '5%', align: 'center', readOnly: true },
                { title: 'Select', width: '5%', align: 'center', readOnly: true },
            ],
            minDimensions: [4, 1],
            allowDeleteColumn: false,
            allowInsertRow: false,
            allowInsertColumn: false,
        };

        inHouseConsolidatedReference_vm = new Vue({
            el: '#materialIssuedDetails',
            mounted: function () {
                let spreadsheet = jexcel(this.$el, list);
                Object.assign(this, spreadsheet);
            },
        });
    }

    function append_shipment_order_closure_details(data) {

        let finalShipmentData = [
           { 'id': "0", 'name': 'PENDING' },
           { 'id': "1", 'name': 'DISCREPANCY' },
           { 'id': "2", 'name': 'SHIPPED' }
        ];

        let orderClosureData = [
           { 'id': "0", 'name': 'PENDING' },
           { 'id': "1", 'name': 'DISCREPANCY' },
           { 'id': "2", 'name': 'CLOSED' }
        ];

        let availableStockData = [
           { 'id': "0", 'name': 'PENDING' },
           { 'id': "1", 'name': 'MOVED TO SSL' }
        ];

        $('#shipmentOrderClosure').html('');
        let list = {
            data: [],
            columns: [
                { title:'id', width:'0%',align:'center',type:'hidden'},
                { title: 'Final Shipment Status', width: '8%', align: 'left', type: 'dropdown', source: finalShipmentData },
                { title: 'Final Shipment\n Ex-factory Date & Time', width: '6%', align: 'left', type: 'calendar' },
                { title: 'Total\n Received Qty.', width: '6%', align: 'left', readOnly: true },
                { title: 'Total\n Issued Qty.', width: '6%', align: 'left', readOnly: true },
                { title: 'Total\n Defective Qty.', width: '5%', align: 'center', readOnly: true },
                { title: '_', width: '5%', align: 'center', readOnly: true },
                { title: 'Total Excess\n Qty. Returned ', width: '5%', align: 'right', readOnly: true },
                { title: 'Total\n Available Qty.', width: '5%', align: 'center', readOnly: true },
                { title: 'UOM', width: '5%', align: 'right', readOnly: true },
                { title: 'Order Closure\n Status', width: '5%', align: 'center', type: 'dropdown', source: orderClosureData },
                { title: 'Order Closure\n Date & Time', width: '5%', align: 'center', type: 'calendar' },
                { title: 'Available Stock\n Status', width: '5%', align: 'center', type: 'dropdown', source: availableStockData },
                { title: 'Status Update\n Date & Time', width: '5%', align: 'center', readOnly: true },
            ],
            minDimensions: [4, 1],
            allowDeleteColumn: false,
            allowInsertRow: false,
            allowInsertColumn: false,
        };

        inHouseConsolidatedReference_vm = new Vue({
            el: '#shipmentOrderClosure',
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
            url: base_path + 'request/Bomrequest/updateStorePiDetails',
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
                    window.location.href = base_path + 'request/Bomrequest/bompurchaseindentlist';
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