<ul class="nav nav-tabs lab d-flex">
<li class="active"><a data-toggle="tab" href="#lab_testing_acceptance_internal_details">Lab Testing (Internal)</a></li>
    <li><a data-toggle="tab" href="#lab_testing_acceptance_external_details">Lab Testing (External)</a></li>
    <li><a data-toggle="tab" href="#external_lab_testing_authority_details">External Lab - Contact Details</a></li>
</ul>

<div class="tab-content p-t20">
    <div id="lab_testing_acceptance_internal_details" class="tab-pane fade in active">
        <div class="mb-3 pl-0 ml-2 text-royal-blue f-14 txt-upcs">LAB TESTING & ACCEPTANCE STANDARDS - BASIC PARAMETERS (INTERNAL)</div>
        <div id="lab_testing_acceptance_internal"></div>

        <div class="col-12 text-right pr-3 py-3">
            <button class="btn btn-info btn-sm mar-l-5rem">MATEIAL INDENT</button>
            <button class="btn btn-info btn-sm mar-l-5rem">LAB REQUEST</button>
            <button class="btn btn-sm btn-royal-blue px-3 pag-active mar-l-5rem"style="color: white!important;">1</button>
            <span class="mar-lr-10 bf-af-pg">...</span>
            <a class="btn btn-sm btn-royal-blue lab btnNext">
                Next <i class="btn-text-2 move-right fa fa-arrow-right text-120 align-text-bottom mr-2"></i>
            </a>
            <button class="btn btn-info btn-sm mar-l-5rem" id="lab_testing_acceptance_internal_btn">SAVE</button>
        </div>

    </div>
    <div id="lab_testing_acceptance_external_details" class="tab-pane fade in">
        <div class="mb-3 pl-0 ml-2 text-royal-blue f-14 txt-upcs">LAB TESTING & ACCEPTANCE STANDARDS - SPECIAL PARAMETERS (EXTERNAL)</div>
        <div id="lab_testing_acceptance_external"></div>
        
        <div class="col-12 text-right pr-3 py-3">
            <button class="btn btn-info btn-sm mar-l-5rem">MATEIAL INDENT</button>
            <button class="btn btn-info btn-sm mar-l-5rem">LAB REQUEST</button>
            <a class="btn btn-sm btn-royal-blue lab btnPrevious">
                <i class="btn-text-2  move-left fa fa-arrow-left text-120 align-text-bottom mr-2"></i> Previous
            </a>
            <span class="mar-lr-10 bf-af-pg">...</span>
            <button class="btn btn-sm btn-royal-blue px-3 pag-active"style="color: white!important;">2</button>
            <span class="mar-lr-10 bf-af-pg">...</span>
            <a class="btn btn-sm btn-royal-blue lab btnNext">
                Next <i class="btn-text-2 move-right fa fa-arrow-right text-120 align-text-bottom mr-2"></i>
            </a>
            <button class="btn btn-info btn-sm mar-l-5rem" id="lab_testing_acceptance_external_btn">SAVE</button>
        </div>
    </div>

    <div id="external_lab_testing_authority_details" class="tab-pane fade in">
        <div class="mb-3 pl-0 ml-2 text-royal-blue f-14 txt-upcs">EXTERNAL LAB TESTING AUTHORITY AND CONTACT DETAILS</div>
        <div id="external_lab_testing_authority"></div>
        
        <div class="col-12 text-right pr-3 py-3">
            <a class="btn btn-sm btn-royal-blue lab btnPrevious">
                <i class="btn-text-2  move-left fa fa-arrow-left text-120 align-text-bottom mr-2"></i> Previous
            </a>
            <span class="mar-lr-10 bf-af-pg">...</span>
            <button class="btn btn-sm btn-royal-blue px-3 pag-active">3</button>
            <button class="btn btn-info btn-sm mar-l-5rem" id="external_lab_testing_authority_btn">SAVE</button>
        </div>

    </div>
    
</div>



<!-- COMMON TABLES STARTS HERE -->
<div class="card border-0 mb-4">
    <div class="card-header p-3 border-0 bgc-white tbl-head mb-1">
        <div class="card-title text-white f-14 text-500">LAB REQUEST DETAILS - BASIC PARAMETERS (INTERNAL)</div>
    </div>
    <div class="card-body border-0 p-0 collapse show">
        <div class="table-responsive">
            <div id="lab_request_internal" class="col-12 p-0"></div>
        </div>
    </div>
</div>

<div class="card border-0 mb-4">
    <div class="card-header p-3 border-0 bgc-white tbl-head mb-1">
        <div class="card-title text-white f-14 text-500">LAB REPORT - BASIC PARAMETERS (INTERNAL)</div>
    </div>
    <div class="card-body border-0 p-0 collapse show">
        <div class="table-responsive">
            <div id="lab_report_internal" class="col-12 p-0"></div>
        </div>
    </div>
</div>

<div class="card border-0 mb-2">
    <div class="card-header p-3 border-0 bgc-white tbl-head mb-1">
        <div class="card-title text-white f-14 text-500">LAB REQUEST DETAILS - SPECIAL PARAMETERS (EXTERNAL)</div>
    </div>
    <div class="card-body border-0 p-0 collapse show">
        <div class="table-responsive">
            <div id="lab_request_external" class="col-12 p-0"></div>
        </div>
    </div>
    <div class="card-footer clearfix bgc-white border-0 p-3">
        <button class="btn btn-sm mar-l-5rem pull-right">SAVE</button>
    </div>
</div>

<div class="card border-0 mb-2">
    <div class="card-header p-3 border-0 bgc-white tbl-head mb-1">
        <div class="card-title text-white f-14 text-500">LAB REPORT - SPECIAL PARAMETERS (EXTERNAL)</div>
    </div>
    <div class="card-body border-0 p-0 collapse show">
        <div class="table-responsive">
            <div id="lab_report_external" class="col-12 p-0"></div>
        </div>
    </div>
    <div class="card-footer clearfix bgc-white border-0 p-3">
        <button class="btn btn-sm mar-l-5rem pull-right">SAVE</button>
    </div>
</div>

<!-- COMMON TABLES ENDS HERE -->

<script>

    // ******************************************************************************** 
    // EXTERNAL LAB TESTING AUTHORITY STARTS HERE 
    // ********************************************************************************

    let external_lab_testing_authority_wise = {
        data: [],
        columns: [
            {
                title: 'Lab Testing Authority -\n Name',
                width: '8%',
                align: 'center',
                readOnly: true
            },
            {
                title: 'Address',
                width: '8%',
                align: 'center',
                readOnly: true
            },
            {
                title: 'GST No.',
                width: '8%',
                align: 'center',
                readOnly: true
            },
            {
                title: 'Contact Person Name',
                width: '8%',
                align: 'center',
                readOnly: true
            },
            {
                title: 'e-mail ID',
                width: '8%',
                align: 'center',
                readOnly: true
            },
            {
                title: 'Phone / Mobile No.',
                width: '8%',
                align: 'center',
                readOnly: true
            },
            {
                title: 'If On-line Booking System -\n Web Site ID',
                width: '10%',
                align: 'center',
                readOnly: true
            },
            {
                title: 'User ID / Pass Word',
                width: '7%',
                align: 'center',
                readOnly: true
            },
            {
                title: 'Password Expiry\n Date & Time',
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

    var external_lab_testing_authority_vm = new Vue({
        el: '#external_lab_testing_authority',
        mounted: function () {
            let spreadsheet = jexcel(this.$el, external_lab_testing_authority_wise);
            Object.assign(this, spreadsheet);
        }
    });


    // ******************************************************************************** 
    // EXTERNAL LAB TESTING AUTHORITY ENDS HERE 
    // ********************************************************************************


    // ******************************************************************************** 
    // LAB REQUEST INTERNAL STARTS HERE 
    // ********************************************************************************

    let lab_request_internal_wise = {
        data: [],
        columns: [
            {
                title: 'Combo / Colour',
                width: '8%',
                align: 'center',
                readOnly: true
            },
            {
                title: 'Item Description',
                width: '8%',
                align: 'center',
                readOnly: true
            },
            {
                title: 'Reqirement',
                width: '8%',
                align: 'center',
                readOnly: true
            },
            {
                title: 'Request Date & Time',
                width: '8%',
                align: 'center',
                readOnly: true
            },
            {
                title: 'Cutoff Date & Time',
                width: '8%',
                align: 'center',
                readOnly: true
            },
            {
                title: 'Authorization\n Status',
                width: '8%',
                align: 'center',
                readOnly: true
            },
            {
                title: 'Request Status',
                width: '8%',
                align: 'center',
                readOnly: true
            },
            {
                title: 'LAB Queue No.',
                width: '8%',
                align: 'center',
                readOnly: true
            },
            {
                title: 'Queue No. Assigned\n Date & Time',
                width: '8%',
                align: 'center',
                readOnly: true
            }
        ],
        minDimensions: [4, 3],
        allowDeleteColumn: false,
        allowInsertRow: false,
        allowInsertColumn: false,
    };

    var lab_request_internal_vm = new Vue({
        el: '#lab_request_internal',
        mounted: function () {
            let spreadsheet = jexcel(this.$el, lab_request_internal_wise);
            Object.assign(this, spreadsheet);
        }
    });


    // ******************************************************************************** 
    // LAB REQUEST INTERNAL ENDS HERE 
    // ********************************************************************************


    // ******************************************************************************** 
    // LAB REPORT INTERNAL STARTS HERE 
    // ********************************************************************************

    let lab_report_internal_wise = {
        data: [],
        columns: [
            {
                title: 'Combo / Colour',
                width: 120,
                align: 'center',
                readOnly: true
            },
            {
                title: 'Item Description',
                width: 120,
                align: 'center',
                readOnly: true
            },
            {
                title: 'Lot No.',
                width: 120,
                align: 'center',
                readOnly: true
            },
            {
                title: 'Req. GSM',
                width: 120,
                align: 'center',
                readOnly: true
            },
            {
                title: 'Actual GSM',
                width: 120,
                align: 'center',
                readOnly: true
            },
            {
                title: 'Req. DIA /\n DIM (Inches)',
                width: 120,
                align: 'center',
                readOnly: true
            },
            {
                title: 'Actual DIA /\n DIM (Inches)',
                width: 120,
                align: 'center',
                readOnly: true
            },
            {
                title: 'Shrink. Acc.\n Level (Length)',
                width: 120,
                align: 'center',
                readOnly: true
            },
            {
                title: 'Shrink. Actual\n (Length)',
                width: 120,
                align: 'center',
                readOnly: true
            },
            {
                title: 'Shrink. Acc.\n Level (Width)',
                width: 120,
                align: 'center',
                readOnly: true
            },
            {
                title: 'Shrink.\ Actual (Width)',
                width: 120,
                align: 'center',
                readOnly: true
            },
            {
                title: 'Spirality Acc.\n Level',
                width: 120,
                align: 'center',
                readOnly: true
            },
            {
                title: 'Spirality\n Actual',
                width: 120,
                align: 'center',
                readOnly: true
            },
            {
                title: 'Crocking Acc.\n Level (Dry)',
                width: 120,
                align: 'center',
                readOnly: true
            },
            {
                title: 'Crocking\n Actual (Dry)',
                width: 120,
                align: 'center',
                readOnly: true
            },
            {
                title: 'Crocking Acc.\n Level (Wet)',
                width: 120,
                align: 'center',
                readOnly: true
            },
            {
                title: 'Crocking\n Actual (Wet)',
                width: 120,
                align: 'center',
                readOnly: true
            },
            {
                title: 'Fastness Acc.\n Level (Shade)',
                width: 120,
                align: 'center',
                readOnly: true
            },
            {
                title: 'Fastness\n Actual (Shade)',
                width: 120,
                align: 'center',
                readOnly: true
            },
            {
                title: 'Fastness Acc.\n Level (Stain)',
                width: 120,
                align: 'center',
                readOnly: true
            },
            {
                title: 'Fastness\n Actual (Stain)',
                width: 120,
                align: 'center',
                readOnly: true
            },
            {
                title: 'Report Status',
                width: 120,
                align: 'center',
                readOnly: true
            },
            {
                title: 'Report Status Update\n Date & Time',
                width: 120,
                align: 'center',
                readOnly: true
            },
            {
                title: 'Management\n Appl. Status',
                width: 120,
                align: 'center',
                readOnly: true
            },
            {
                title: 'Management Appl. Status\n Update Date & Time',
                width: 120,
                align: 'center',
                readOnly: true
            },
        ],
        minDimensions: [4, 3],
        allowDeleteColumn: false,
        allowInsertRow: false,
        allowInsertColumn: false,
        tableOverflow: true,
    };

    var lab_report_internal_vm = new Vue({
        el: '#lab_report_internal',
        mounted: function () {
            let spreadsheet = jexcel(this.$el, lab_report_internal_wise);
            Object.assign(this, spreadsheet);
        }
    });


    // ******************************************************************************** 
    // LAB REPORT INTERNAL ENDS HERE 
    // ********************************************************************************


    // ******************************************************************************** 
    // LAB REQUEST EXTERNAL STARTS HERE 
    // ********************************************************************************

    let lab_request_external_wise = {
        data: [],
        columns: [
            {
                title: 'Combo / Colour',
                width: '8%',
                align: 'center',
                readOnly: true
            },
            {
                title: 'Item Description',
                width: '8%',
                align: 'center',
                readOnly: true
            },
            {
                title: 'Lab Testing Parameters',
                width: '8%',
                align: 'center',
                readOnly: true
            },
            {
                title: 'Qty.\n Issued',
                width: '8%',
                align: 'center',
                readOnly: true
            },
            {
                title: 'UOM',
                width: '8%',
                align: 'center',
                readOnly: true
            },
            {
                title: 'D.C. No',
                width: '8%',
                align: 'center',
                readOnly: true
            },
            {
                title: 'D.C. Date & Time',
                width: '8%',
                align: 'center',
                readOnly: true
            },
            {
                title: 'Testing Authority',
                width: '8%',
                align: 'center',
                readOnly: true
            },
            {
                title: 'Test Ref. No.',
                width: '8%',
                align: 'center',
                readOnly: true
            },
            {
                title: 'Test Ref. No. Date & Time',
                width: '8%',
                align: 'center',
                readOnly: true
            },
            {
                title: 'Test Report Expected\n Date & Time',
                width: '8%',
                align: 'center',
                readOnly: true
            }
        ],
        minDimensions: [4, 3],
        allowDeleteColumn: false,
        allowInsertRow: false,
        allowInsertColumn: false,
    };

    var lab_request_external_vm = new Vue({
        el: '#lab_request_external',
        mounted: function () {
            let spreadsheet = jexcel(this.$el, lab_request_external_wise);
            Object.assign(this, spreadsheet);
        }
    });


    // ******************************************************************************** 
    // LAB REQUEST EXTERNAL ENDS HERE 
    // ********************************************************************************


    // ******************************************************************************** 
    // LAB REPORT EXTERNAL STARTS HERE 
    // ********************************************************************************

    let lab_report_external_wise = {
        data: [],
        columns: [
            {
                title: 'Combo / Colour',
                width: '8%',
                align: 'center',
                readOnly: true
            },
            {
                title: 'Item Description',
                width: '8%',
                align: 'center',
                readOnly: true
            },
            {
                title: 'Lab Testing Parameters',
                width: '8%',
                align: 'center',
                readOnly: true
            },
            {
                title: 'Acceptance\n Level',
                width: '8%',
                align: 'center',
                readOnly: true
            },
            {
                title: 'Actual',
                width: '8%',
                align: 'center',
                readOnly: true
            },
            {
                title: 'Report\n Status',
                width: '8%',
                align: 'center',
                readOnly: true
            },
            {
                title: 'Report Ref. No.',
                width: '8%',
                align: 'center',
                readOnly: true
            },
            {
                title: 'Report Date & Time',
                width: '8%',
                align: 'center',
                readOnly: true
            },
            {
                title: 'Report Approval\n Status',
                width: '8%',
                align: 'center',
                readOnly: true
            },
            {
                title: 'Approved By',
                width: '8%',
                align: 'center',
                readOnly: true
            },
            {
                title: 'Approval Status Update\n Date & Time',
                width: '8%',
                align: 'center',
                readOnly: true
            }
        ],
        minDimensions: [4, 3],
        allowDeleteColumn: false,
        allowInsertRow: false,
        allowInsertColumn: false,
    };

    var lab_report_external_vm = new Vue({
        el: '#lab_report_external',
        mounted: function () {
            let spreadsheet = jexcel(this.$el, lab_report_external_wise);
            Object.assign(this, spreadsheet);
        }
    });


    // ******************************************************************************** 
    // LAB REPORT EXTERNAL ENDS HERE 
    // ********************************************************************************


</script>

