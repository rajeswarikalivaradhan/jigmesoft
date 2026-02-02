<?php 
    $packingHead = $packingDetails['packingDetails'];
    // echo sizeof($packingDetails['packingDetails']);
    // echo "<pre>";
    // print_r($packingHead);
    // echo "</pre>";
 ?>

<div class="tab-content p-t20">

    <div class="d-flex packing-head">
        <h1 class="flex-space"></h1>
        <h1 class="text-royal-blue">Packing</h1>
        <h1><i class="fa fa-angle-double-right text-90 " style="color: #FF9900"></i></h1>
        <?php if(sizeof($packingHead) > 0) { ?>
        <h1 class="text-cyan-br"><a href="<?php echo base_url(); ?>WorkInProcess/assortmentType/<?php echo urlencode(base64_encode($VarEnqId)) ?>/edit">Edit Assortment type</a></h1>
        <?php } else { ?>
        <h1 class="text-cyan-br"><a href="<?php echo base_url(); ?>WorkInProcess/assortmentType/<?php echo urlencode(base64_encode($VarEnqId)) ?>">Add Assortment type</a></h1>
        <?php }  ?>
    </div>
</div>

<ul class="nav nav-tabs documentation d-flex">
    <?php
        foreach ($packingHead as $key => $valueHead) {
            $assort_typee = $valueHead['assortment_type'];
            if( $assort_typee == "1" || $assort_typee == "2" || $assort_typee == "3" || $assort_typee == "4") {
        ?>
            <li class="<?php if($key == 0) echo ' active'?>">
                <a data-toggle="tab" href="#dying_cost_grid_<?php echo $valueHead['pck_id']; ?>"><?php echo $valueHead['po_enq']; ?></a>
            </li>

    <?php  } else { ?>
        <li class="<?php if($key == 0) echo ' active'?>">
            <a data-toggle="tab" href="#dying_cost_grid_a<?php echo $valueHead['assortment_type']; ?>"><?php echo $valueHead['po_enq']; ?></a>
        </li>
    <?php  } }  ?>
</ul>

<div class="tab-content p-t20">
    <?php 
        foreach ($packingHead as $key => $valueHead) 
        { 
            $assort_typee = $valueHead['assortment_type'];
            if( $assort_typee == "1" || $assort_typee == "2" || $assort_typee == "3" || $assort_typee == "4") {
    ?>
        
        <div id="dying_cost_grid_<?php echo $valueHead['pck_id']; ?>" class="tab-pane fade <?php if($key == 0) echo ' in active'?>">
            <div class="mb-3 pl-0 ml-2 text-royal-blue f-14 ">
                <span class="text-bold text-royal-blue">PO.NO : </span><?php echo $valueHead['po_enq']; ?> 
                <span class="text-bold text-royal-blue">&nbsp;&nbsp;/&nbsp;&nbsp;  ASSORTMENT TYPE: </span> <?php echo $valueHead['assort_type_name']; ?>
            </div>
            <div id="packingTables<?php echo $valueHead['pck_id']; ?>"></div>
            <div class="card-footer clearfix bgc-white border-0 p-3">
                <button class="btn btn-info btn-sm mar-l-5rem pull-right" id="savePck<?php echo $valueHead['pck_id']; ?>_btn">SAVE</button>
            </div>
        </div> 
    <?php } 
        else if( $assort_typee == "5" || $assort_typee == "6" || $assort_typee == "7" || $assort_typee == "8") 
        { 
    ?>
    <div id="dying_cost_grid_a<?php echo $valueHead['assortment_type'] ?>" class="tab-pane fade <?php if($key == 0) echo ' in active'?>">
    <?php 
        foreach ($valueHead['packingTableData'] as $key1 => $itemsPacking) 
        {
            $assort_idd = 'aa'.$itemsPacking['details']['pck_id']. $itemsPacking['details']['enquiry_id']. $itemsPacking['details']['pck_combo_color_id'];
    ?>   
        <div class="mb-3 pl-0 ml-2 text-royal-blue f-14 ">
                <span class="text-bold text-royal-blue">PO.NO : </span><?php echo $valueHead['po_enq']; ?> 
                <span class="text-bold text-royal-blue">&nbsp;&nbsp;/&nbsp;&nbsp;  ASSORTMENT TYPE - <?php echo $key1+1?>: </span> <?php echo $valueHead['assort_type_name']; ?>
        </div>
        <div id="packingTables<?php echo $assort_idd ?>"></div>
        <div class="card-footer clearfix bgc-white border-0 p-3">
            <button class="btn btn-info btn-sm mar-l-5rem pull-right" id="savePckAss<?php echo $assort_idd; ?>_btn">SAVE</button>
        </div>
         
    <?php } echo '</div>'; } } ?>   
</div>



<!-- COMMON TABLES STARTS HERE -->
<div class="card border-0">
    <div class="card-header p-3 border-0 bgc-white tbl-head mb-1">
        <div class="card-title text-white f-14 text-500">COMPONENT WISE PACKING CODE DETAILS</div>
    </div>
    <div class="card-body border-0 p-0 collapse show">
        <div class="table-responsive">
            <div id="componentWisePackingCodeDetails" class="col-12 p-0"></div>
        </div>
    </div>
    <div class="card-footer clearfix bgc-white border-0 p-3">
        <button class="btn btn-info btn-sm mar-l-5rem pull-right" id=component_wise_packing_btn>SAVE</button>
    </div>
</div>

<!-- COMMON TABLES ENDS HERE -->

<!-- FORM DETAILS STARTS HERE -->
<div class="col-12 pr-3 py-3">
    <label class="pull-left cus-label"> Remarks </label>
    <textarea id="packingRemarks" name="packingRemarks" class="form-control custom-textarea"
        placeholder="Free Text"></textarea>
</div>

<div class="col-12 pr-3 py-3">
    <label class="cus-label"> Attachment </label>
    <div id="packingImageUpload" class="pdt10"></div>
</div>

<div class="col-12 pr-3 py-3 text-right">
    <button class="btn btn-info mar-l-5rem" id="cadFormSubmit">SAVE</button>
</div>
<!-- FORM DETAILS ENDS HERE -->

<script>
    // ******************************************************************************** 
    // DETAILS OF CONSIGNEE STARTS HERE 
    // ********************************************************************************

    // let details_of_consigneee = {
    //     data: [],
    //     columns: [{
    //             title: 'assort type 1',
    //             width: '8%',
    //             align: 'center',
    //             readOnly: true
    //         },
    //         {
    //             title: 'assort type 1',
    //             width: '8%',
    //             align: 'center',
    //             type: 'dropdown',
    //             source: []
    //         },
    //     ],
    //     minDimensions: [4, 3],
    //     allowDeleteColumn: false,
    //     allowInsertRow: false,
    //     allowInsertColumn: false,
    // };

    // var details_of_consignee_vm = new Vue({
    //     el: '#assortType1',
    //     mounted: function () {
    //         let spreadsheet = jexcel(this.$el, details_of_consigneee);
    //         Object.assign(this, spreadsheet);
    //     }
    // });
    
    // *******************************************************
    // let details_of_consigneee_2 = {
    //     data: [],
    //     columns: [{
    //             title: 'assort type 2',
    //             width: '8%',
    //             align: 'center',
    //             readOnly: true
    //         },
    //         {
    //             title: 'assort type 2',
    //             width: '8%',
    //             align: 'center',
    //             type: 'dropdown',
    //             source: []
    //         },
    //     ],
    //     minDimensions: [4, 3],
    //     allowDeleteColumn: false,
    //     allowInsertRow: false,
    //     allowInsertColumn: false,
    // };

    // var details_of_consignee_vm_2 = new Vue({
    //     el: '#assortType2',
    //     mounted: function () {
    //         let spreadsheet = jexcel(this.$el, details_of_consigneee_2);
    //         Object.assign(this, spreadsheet);
    //     }
    // });
    
    // *******************************************************
    // let details_of_consigneee_3 = {
    //     data: [],
    //     columns: [{
    //             title: 'assort type 3',
    //             width: '8%',
    //             align: 'center',
    //             readOnly: true
    //         },
    //         {
    //             title: 'assort type 3',
    //             width: '8%',
    //             align: 'center',
    //             type: 'dropdown',
    //             source: []
    //         },
    //     ],
    //     minDimensions: [4, 3],
    //     allowDeleteColumn: false,
    //     allowInsertRow: false,
    //     allowInsertColumn: false,
    // };

    // var details_of_consignee_vm_3 = new Vue({
    //     el: '#assortType3',
    //     mounted: function () {
    //         let spreadsheet = jexcel(this.$el, details_of_consigneee_3);
    //         Object.assign(this, spreadsheet);
    //     }
    // });
    
    // *******************************************************
    // let details_of_consigneee_4 = {
    //     data: [],
    //     columns: [{
    //             title: 'assort type 4',
    //             width: '8%',
    //             align: 'center',
    //             readOnly: true
    //         },
    //         {
    //             title: 'assort type 4',
    //             width: '8%',
    //             align: 'center',
    //             type: 'dropdown',
    //             source: []
    //         },
    //     ],
    //     minDimensions: [4, 3],
    //     allowDeleteColumn: false,
    //     allowInsertRow: false,
    //     allowInsertColumn: false,
    // };

    // var details_of_consignee_vm_4 = new Vue({
    //     el: '#assortType4',
    //     mounted: function () {
    //         let spreadsheet = jexcel(this.$el, details_of_consigneee_4);
    //         Object.assign(this, spreadsheet);
    //     }
    // });


    // ******************************************************************************** 
    // DETAILS OF CONSIGNEE ENDS HERE 
    // ********************************************************************************

    // ******************************************************************************** 
    // ROAD TRASPORT DETAILS STARTS HERE 
    // ********************************************************************************

    let road_transport_details = {
        data: [],
        columns: [{
                title: 'P.O. No. / Enq. Ref. No.',
                width: '10%',
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
                width: '8%',
                align: 'center',
                readOnly: true
            },
            {
                title: 'Pcs. / Set',
                width: '7%',
                align: 'center',
                readOnly: true
            },
            {
                title: 'Transporter: Name / Address\n / Cont. Person  / Mobile No.',
                width: '10%',
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
            let spreadsheet = jexcel(this.$el, road_transport_details);
            Object.assign(this, spreadsheet);
        }
    });


    // ******************************************************************************** 
    // ROAD TRASPORT DETAILS ENDS HERE 
    // ********************************************************************************

    // ******************************************************************************** 
    // GOODS RECEIVED DETAILS STARTS HERE 
    // ********************************************************************************

    let goods_received_details = {
        data: [],
        columns: [{
                title: 'P.O. No. / Enq. Ref. No.',
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
                width: '8%',
                align: 'center',
                readOnly: true
            },
            {
                title: 'Pcs. / Set',
                width: '8%',
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
            let spreadsheet = jexcel(this.$el, goods_received_details);
            Object.assign(this, spreadsheet);
        }
    });


    // ******************************************************************************** 
    // GOODS RECEIVED DETAILS ENDS HERE 
    // ********************************************************************************

    // ******************************************************************************** 
    // DOCUMENTS FOR PAYMENT DETAILS STARTS HERE 
    // ********************************************************************************

    let documents_for_payment = {
        data: [],
        columns: [{
                title: 'P.O. No. / Enq. Ref. No.',
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
                width: '8%',
                align: 'center',
                readOnly: true
            },
            {
                title: 'Pcs. / Set',
                width: '8%',
                align: 'center',
                readOnly: true
            },
            {
                title: 'Document Submission Mode',
                width: '8%',
                align: 'center',
                readOnly: true
            },
            {
                title: 'List of Documents Sent or Submitted',
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
                title: 'Bill Cleared Status By\n Consignee',
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
            let spreadsheet = jexcel(this.$el, documents_for_payment);
            Object.assign(this, spreadsheet);
        }
    });


    // ******************************************************************************** 
    // DOCUMENTS FOR PAYMENT DETAILS ENDS HERE 
    // ********************************************************************************

    // ******************************************************************************** 
    // COMPONENT WISE PACKING DETAILS STARTS HERE 
    // ********************************************************************************

    let compoent_wise_pacing_details = {
        data: [],
        columns: [{
                title: 'P.O. No. / Enq. Ref. No.',
                width: '8%',
                align: 'center',
                readOnly: true
            },
            {
                title: 'Combo',
                width: '8%',
                align: 'center',
                readOnly: true
            },
            {
                title: 'Component',
                width: '8%',
                align: 'center',
                readOnly: true
            },
            {
                title: 'Colour',
                width: '8%',
                align: 'center',
                readOnly: true
            },
            {
                title: 'Intake Qty. Per\n Comp. (Nos.)',
                width: '8%',
                align: 'center',
                readOnly: true
            },
            {
                title: 'Component Wise\n Packing Code',
                width: '8%',
                align: 'center',
                type: 'dropdown',
                source: [],
            },
            {
                title: 'Packing\n Type',
                width: '7%',
                align: 'center',
                readOnly: true
            },
        ],
        minDimensions: [4, 3],
        allowDeleteColumn: false,
        allowInsertRow: false,
        allowInsertColumn: false,
    };

    var compoent_wise_pacing_details_vm = new Vue({
        el: '#componentWisePackingCodeDetails',
        mounted: function () {
            let spreadsheet = jexcel(this.$el, compoent_wise_pacing_details);
            Object.assign(this, spreadsheet);
        }
    });


    // ******************************************************************************** 
    // COMPONENT WISE PACKING DETAILS ENDS HERE 
    // ********************************************************************************
</script>