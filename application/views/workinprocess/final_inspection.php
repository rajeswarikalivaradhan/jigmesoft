<ul class="nav nav-tabs final-inspection d-flex">
    <li class="active"><a data-toggle="tab" href="#final_inspection_standards">FINAL INSPECTION  DETAILS</a></li>
</ul>

<div class="tab-content p-t20">
    <div id="final_inspection_standards" class="tab-pane fade in active">
    <div class="mb-3 pl-0 ml-2 text-royal-blue f-14 ">FINAL INSPECTION  DETAILS</div>
        <div id="finalInspectionStandardsDetails" class="table-responsive"></div>
        
        <div class="col-12 text-right pr-3 py-3">
            <button class="btn btn-sm btn-royal-blue px-3 pag-active" style="color: white!important;">1</button>
            <span class="mar-lr-10 bf-af-pg">...</span>
            <a class="btn btn-sm btn-royal-blue final-inspection btnNext">
                Next <i class="btn-text-2 move-right fa fa-arrow-right text-120 align-text-bottom mr-2"></i>
            </a>
            <button class="btn btn-sm btn-info mar-l-5rem">OFFER F.I.</button>
            <button class="btn btn-sm btn-info mar-l-5rem" id="final_inspection_standard_btn">SAVE</button>
        </div>

    </div>
</div>

<!-- COMMON TABLES STARTS HERE -->
<div class="card border-0">
    <div class="card-header p-3 border-0 bgc-white tbl-head mb-1">
        <div class="card-title text-white f-14 text-500">FINAL INSPECTION STATUS REPORT</div>
    </div>
    <div class="card-body border-0 p-0 collapse show">
        <div class="table-responsive pad-b-20">
            <div id="finalInspectionStatusDetails" class="col-12 p-0"></div>
        </div>
    </div>
    <div class="card-footer clearfix bgc-white border-0 p-3">
        <button class="btn btn-info btn-sm mar-l-5rem pull-right">SAVE</button>
    </div>
</div>


<!-- FORM DETAILS STARTS HERE -->
<div class="col-12 pr-3 py-3">
     <?php if ($loguserid == 3) { ?>
    <label class="pull-left cus-label"> Remarks </label>
    <textarea id="packingRemarks" name="packingRemarks" class="form-control custom-textarea final_remarks"
        placeholder="Free Text"></textarea>
        <?php } ?>
</div>

<div class="col-12 pr-3 py-3">
    <label class="cus-label"> Attachment </label>
    <?php if ($loguserid == 3) { ?>
    <div id="fianlImageUpload" class="pdt10"></div>
    <?php } ?>  
</div>
<div class="row">
    <ul class="upload-list-view orderEntryImageView" style="list-style: none;">
    </ul>
</div>

<div class="col-12 pr-3 py-3 text-right">
    <button class="btn btn-info mar-l-5rem" id="final_orderEntryFormSubmit">SAVE</button>
</div>
<!-- FORM DETAILS ENDS HERE -->

<script>
   
    // ******************************************************************************** 
    // ROAD TRASPORT DETAILS STARTS HERE 
    // ********************************************************************************

    let final_inspection_status = {
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
                title: 'Sample Size\n Taken for FI',
                width: '8%',
                align: 'center',
            },
            {
                title: 'Pcs. / Set',
                width: '8%',
                align: 'center',
                readOnly: true
            },
            {
                title: 'Critical\n Mistakes',
                width: '8%',
                align: 'center',
            },
            {
                title: 'Major\n Mistakes',
                width: '8%',
                align: 'center',
            },
            {
                title: 'Minor\n Mistakes',
                width: '7%',
                align: 'center',
            },
            {
                title: 'FI Status',
                width: '7%',
                align: 'center',
                type: 'dropdown',
                source: [],
            },  
            {
                title: 'FI Done By',
                width: '7%',
                align: 'center',
                type: 'dropdown',
                source: [],
            },
            {
                title: 'FI Completion Date',
                width: '10%',
                align: 'center',
                type: 'calendar'
            },
            {
                title: 'FI Pass / Fail\n Attempt',
                width: '7%',
                align: 'center',
                readOnly: true
            },
            {
                title: 'Remarks / Action Taken Report',
                width: '12%',
                align: 'center',
            }
        ],
        minDimensions: [4, 3],
        allowDeleteColumn: false,
        allowInsertRow: false,
        allowInsertColumn: false,
    };

    var final_inspection_status_vm = new Vue({
        el: '#finalInspectionStatusDetails',
        mounted: function () {
            let spreadsheet = jexcel(this.$el, final_inspection_status);
            Object.assign(this, spreadsheet);
        }
    });


    // ******************************************************************************** 
    // ROAD TRASPORT DETAILS ENDS HERE 
    // ********************************************************************************

</script>

