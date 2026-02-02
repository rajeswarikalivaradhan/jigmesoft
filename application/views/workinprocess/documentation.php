<ul class="nav nav-tabs documentation d-flex">
    <li class="active"><a data-toggle="tab" href="#details_of_consignee">DOCUMENTATION DETAILS </a></li>
</ul>

<div class="tab-content p-t20">
    <div id="details_of_consignee" class="tab-pane fade in active">
        <div class="mb-3 pl-0 ml-2 text-royal-blue f-14 ">DOCUMENTATION DETAILS</div>
        <div id="consignee_details" class="table-responsive"></div>
        
        <div class="col-12 text-right pr-3 py-3">
            <button class="btn btn-sm btn-royal-blue px-3 pag-active" style="color: white!important;">1</button>
            <span class="mar-lr-10 bf-af-pg">...</span>
            <a class="btn btn-sm btn-royal-blue documentation btnNext">
                Next <i class="btn-text-2 move-right fa fa-arrow-right text-120 align-text-bottom mr-2"></i>
            </a>
            <button class="btn btn-sm btn-info mar-l-5rem" id="consignee_details_btn">SAVE</button>
        </div>
    </div>
</div>

<!-- COMMON TABLES STARTS HERE -->
<div class="card border-0">
    <div class="card-header p-3 border-0 bgc-white tbl-head mb-1">
        <div class="card-title text-white f-14 text-500">ROAD TRANSPORT DETAILS - PORT CONNECTIVITY</div>
    </div>
    <div class="card-body border-0 p-0 collapse show">
        <div class="table-responsive">
            <div id="road_transportDetails" class="col-12 p-0"></div>
        </div>
    </div>
    <div class="card-footer clearfix bgc-white border-0 p-3">
        <button class="btn btn-sm btn-info mar-l-5rem pull-right">SAVE</button>
    </div>
</div>

<div class="card border-0">
    <div class="card-header p-3 border-0 bgc-white tbl-head mb-1">
        <div class="card-title text-white f-14 text-500">GOODS RECEIVED DETAILS FROM CLEARING & FORWARDING AGENTS</div>
    </div>
    <div class="card-body border-0 p-0 collapse show">
        <div class="table-responsive">
            <div id="goods_receivedDetails" class="col-12 p-0"></div>
        </div>
    </div>
    <div class="card-footer clearfix bgc-white border-0 p-3">
        <button class="btn btn-sm btn-info mar-l-5rem pull-right">SAVE</button>
    </div>
</div>

<div class="card border-0">
    <div class="card-header p-3 border-0 bgc-white tbl-head mb-1">
        <div class="card-title text-white f-14 text-500">DOCUMENTS FOR PAYMENT CLEARANCE</div>
    </div>
    <div class="card-body border-0 p-0 collapse show">
        <div class="table-responsive">
            <div id="documents_forPaymentDetails" class="col-12 p-0"></div>
        </div>
    </div>
    <div class="card-footer clearfix bgc-white border-0 p-3">
        <button class="btn btn-sm btn-info mar-l-5rem pull-right">SAVE</button>
    </div>
</div>

<div class="card border-0">
    <div class="card-header p-3 border-0 bgc-white tbl-head mb-1">
        <div class="card-title text-white f-14 text-500">SHIPPING DOCUMENTATION DETAILS (FOR CLEARING & FORWARDING)</div>
    </div>
    <div class="card-body border-0 p-0 collapse show">
        <div class="table-responsive">
            <div id="shipping_documentationDetails" class="col-12 p-0"></div>
        </div>
    </div>
    <div class="card-footer clearfix bgc-white border-0 p-3">
        <button class="btn btn-sm btn-info mar-l-5rem pull-right">SAVE</button>
    </div>
</div>

<!-- COMMON TABLES ENDS HERE -->

<!-- FORM DETAILS STARTS HERE -->
<div class="col-12 pr-3 py-3">
      <?php if ($loguserid == 3) { ?>
    <label class="pull-left cus-label"> Remarks </label>
    <textarea id="packingRemarks" name="packingRemarks" class="form-control custom-textarea documentation_remarks"
        placeholder="Free Text"></textarea>
        <?php } ?>
</div>

<div class="col-12 pr-3 py-3">
    <label class="cus-label"> Attachment </label>
      <?php if ($loguserid == 3) { ?>
    <div id="documentImageUpload" class="pdt10"></div>
     <?php } ?> 
</div>
<div class="row">
    <ul class="upload-list-view orderEntryImageView" style="list-style: none;">
    </ul>
</div>

<div class="col-12 pr-3 py-3 text-right">
    <button class="btn btn-info mar-l-5rem" id="document_orderEntryFormSubmit">SAVE</button>
</div>
<!-- FORM DETAILS ENDS HERE -->

<script>

    // ******************************************************************************** 
    // ROAD TRASPORT DETAILS STARTS HERE 
    // ********************************************************************************

    let road_transport_detailss = {
        data: [],
        columns: [{
                title: 'P.O. No. /\n Enq. Ref. No.',
                width: '8%',
                align: 'center',
                readOnly: true
            },
            {
                title: 'Combo / Colour',
                width: '6%',
                align: 'center',
                readOnly: true
            },
            {
                title: 'P.O. Qty. /\n Sample Qty.',
                width: '6%',
                align: 'center',
                readOnly: true
            },
            {
                title: 'Shipment\n Qty.',
                width: '6%',
                align: 'center',
                readOnly: true
            },
            {
                title: 'Pcs. / Set',
                width: '6%',
                align: 'center',
                readOnly: true
            },
            {
                title: 'Transporter: Name / Address\n / Cont. Person  / Mobile No.',
                width: '14%',
                align: 'center',
                readOnly: true
            },
            {
                title: 'Vehicle No.',
                width: '7%',
                align: 'center',
                readOnly: true
            },
            {
                title: 'Driver & Co-driver\n Name',
                width: '7%',
                align: 'center',
                readOnly: true
            },
            {
                title: 'Driver & Co-driver\n Mobile No.',
                width: '7%',
                align: 'center',
                readOnly: true
            },  
            {
                title: 'Connecting Port\n Name & City',
                width: '7%',
                align: 'center',
                readOnly: true
            },
            {
                title: 'Vehicle Start from Factory\n Date & Time',
                width: '10%',
                align: 'center',
                readOnly: true
            },
            {
                title: 'Goods Handover to Agent\n Date & Time',
                width: '10%',
                align: 'center',
                readOnly: true
            }
        ],
        minDimensions: [4, 3],
        allowDeleteColumn: false,
        allowInsertRow: false,
        allowInsertColumn: false,
    };

    var road_transport_details_vm = new Vue({
        el: '#road_transportDetails',
        mounted: function () {
            let spreadsheet = jexcel(this.$el, road_transport_detailss);
            Object.assign(this, spreadsheet);
        }
    });


    // ******************************************************************************** 
    // ROAD TRASPORT DETAILS ENDS HERE 
    // ********************************************************************************

    // ******************************************************************************** 
    // GOODS RECEIVED DETAILS STARTS HERE 
    // ********************************************************************************

    let goods_received_detailss = {
        data: [],
        columns: [{
                title: 'P.O. No. /\n Enq. Ref. No.',
                width: '8%',
                align: 'center',
                readOnly: true
            },
            {
                title: 'Combo / Colour',
                width: '8%',
                align: 'center',
                readOnly: true
            },
            {
                title: 'P.O. Qty. /\n Sample Qty.',
                width: '6%',
                align: 'center',
                readOnly: true
            },
            {
                title: 'Shipment\n Qty.',
                width: '6%',
                align: 'center',
                readOnly: true
            },
            {
                title: 'Pcs. / Set',
                width: '6%',
                align: 'center',
                readOnly: true
            },
            {
                title: 'Goods Received Note\n Reference No. / Date',
                width: '8%',
                align: 'center',
                readOnly: true
            },
            {
                title: 'GRN Received From',
                width: '8%',
                align: 'center',
                readOnly: true
            },
            {
                title: 'E Mail ID / Mobile No.',
                width: '7%',
                align: 'center',
                readOnly: true
            },
            {
                title: 'Bill of Lading\n Reference No. / Date',
                width: '7%',
                align: 'center',
                readOnly: true
            },  
            {
                title: 'BL Received From',
                width: '7%',
                align: 'center',
                readOnly: true
            },
            {
                title: 'E Mail ID / Mobile No.',
                width: '7%',
                align: 'center',
                readOnly: true
            }
        ],
        minDimensions: [4, 3],
        allowDeleteColumn: false,
        allowInsertRow: false,
        allowInsertColumn: false,
    };

    var goods_received_details_vm = new Vue({
        el: '#goods_receivedDetails',
        mounted: function () {
            let spreadsheet = jexcel(this.$el, goods_received_detailss);
            Object.assign(this, spreadsheet);
        }
    });


    // ******************************************************************************** 
    // GOODS RECEIVED DETAILS ENDS HERE 
    // ********************************************************************************

    // ******************************************************************************** 
    // DOCUMENTS FOR PAYMENT DETAILS STARTS HERE 
    // ********************************************************************************

    let documents_for_paymentt = {
        data: [],
        columns: [{
                title: 'P.O. No. /\n Enq. Ref. No.',
                width: '8%',
                align: 'center',
                readOnly: true
            },
            {
                title: 'Combo / Colour',
                width: '8%',
                align: 'center',
                readOnly: true
            },
            {
                title: 'P.O. Qty. /\n Sample Qty.',
                width: '6%',
                align: 'center',
                readOnly: true
            },
            {
                title: 'Shipment\n Qty.',
                width: '6%',
                align: 'center',
                readOnly: true
            },
            {
                title: 'Pcs. / Set',
                width: '6%',
                align: 'center',
                readOnly: true
            },
            {
                title: 'Document\n Submission Mode',
                width: '8%',
                align: 'center',
                readOnly: true
            },
            {
                title: 'List of Documents\n Sent or Submitted',
                width: '12%',
                align: 'center',
                readOnly: true
            },
            {
                title: 'Airway Bill No.',
                width: '7%',
                align: 'center',
                readOnly: true
            },
            {
                title: 'Date',
                width: '7%',
                align: 'center',
                readOnly: true
            },  
            {
                title: 'Bill Cleared Status\n By Consignee',
                width: '7%',
                align: 'center',
                readOnly: true
            },
            {
                title: 'Date',
                width: '7%',
                align: 'center',
                readOnly: true
            }
        ],
        minDimensions: [4, 3],
        allowDeleteColumn: false,
        allowInsertRow: false,
        allowInsertColumn: false,
    };

    var documents_for_payment_vm = new Vue({
        el: '#documents_forPaymentDetails',
        mounted: function () {
            let spreadsheet = jexcel(this.$el, documents_for_paymentt);
            Object.assign(this, spreadsheet);
        }
    });


    // ******************************************************************************** 
    // DOCUMENTS FOR PAYMENT DETAILS ENDS HERE 
    // ********************************************************************************

    // ******************************************************************************** 
    // SHIPPING DOCUMENT DETAILS STARTS HERE 
    // ********************************************************************************

    let shipping_document_details = {
        data: [],
        columns: [{
                title: 'P.O. No. /\n Enq. Ref. No.',
                width: '8%',
                align: 'center',
                readOnly: true
            },
            {
                title: 'Combo / Colour',
                width: '8%',
                align: 'center',
                readOnly: true
            },
            {
                title: 'P.O. Qty. /\n Sample Qty.',
                width: '6%',
                align: 'center',
                readOnly: true
            },
            {
                title: 'Shipment\n Qty.',
                width: '6%',
                align: 'center',
                readOnly: true
            },
            {
                title: 'Pcs. / Set',
                width: '6%',
                align: 'center',
                readOnly: true
            },
            {
                title: 'Invoice No. / Date',
                width: '8%',
                align: 'center',
                readOnly: true
            },
            {
                title: 'Packing List Details',
                width: '8%',
                align: 'center',
                readOnly: true
            },
            {
                title: 'E. Invoice No. / Date',
                width: '7%',
                align: 'center',
                readOnly: true
            },
            {
                title: 'E. Way Bill No. / Date',
                width: '7%',
                align: 'center',
                readOnly: true
            },  
            {
                title: 'Freight Insurance\n Policy No. / Date',
                width: '7%',
                align: 'center',
                readOnly: true
            }
        ],
        minDimensions: [4, 3],
        allowDeleteColumn: false,
        allowInsertRow: false,
        allowInsertColumn: false,
    };

    var shipping_document_details_vm = new Vue({
        el: '#shipping_documentationDetails',
        mounted: function () {
            let spreadsheet = jexcel(this.$el, shipping_document_details);
            Object.assign(this, spreadsheet);
        }
    });


    // ******************************************************************************** 
    // SHIPPING DOCUMENT DETAILS ENDS HERE 
    // ********************************************************************************

</script>

