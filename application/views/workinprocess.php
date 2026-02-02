<?php $this->load->view(CNFCOMPANY.'template/pageheader');?>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/css/datatables.min.css">
    <style>
        /* Dropdown Button */
        .dropbtn {
            /*border: none;*/
        }

        /* The container <div> - needed to position the dropdown content */
        .dropdown {
            /*position: relative;
            display: inline-block;*/
        }

        /* Dropdown Content (Hidden by Default) */
        .dropdown-content {
            /*display: none;
            position: relative;
            background-color: #f1f1f1;
            box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
            z-index: 1;*/
        }

        /* Links inside the dropdown */
        .dropdown-content a {
            /*color: black;
            padding: 5px;
            text-decoration: none;
            display: block;*/
        }

        /* Change color of dropdown links on hover */
        .dropdown-content a:hover {
            /*background-color: #ddd;*/
        }

        /* Show the dropdown menu on hover */
        .dropdown:hover .dropdown-content {
            /*display: block;*/

        }

    </style>
    <body class="hold-transition layout-top-nav">
<div class="wrapper">
    <?php $this->load->view(CNFCOMPANY . 'template/templateheader'); ?>

    <div class="content-wrapper bgn-white">
        <div class="col-sm-12" style="display: block ruby; padding: 10px 25px">
            <span class="header-title"><b style="font-size: 20px !important; font-family: Arial">WORK IN PROGRESS LIST</b></span>
            <div class="btn-toolbar pull-right " style="padding-top: 4px" role="toolbar" aria-label="Toolbar with button groups">
                <div class="btn-group mr-2v text-right" role="group" aria-label="First group">
                    <i class="fa fa-search-plus btn  btn-royal-blue" style="padding: 6px 12px;font-size: 16px;" onclick="
                            $(this).toggleClass('fa-search-plus fa-search')
                            $('.search_area').toggleClass('hide');"></i>
                </div>
                <div class="btn-group px-3" role="group" aria-label="Third group">
                    <select name="frmItemStatus" title="activate / deactivate" id="frmItemStatus" class="input-sm form-control" style="">
                        <option value="">Select</option>
                        <option value="1">Active</option>
                        <option value="2">Inactive</option>
                    </select>
                </div>
                <div class="btn-group mr-2v text-right" role="group" aria-label="Second group">
                    <button name="btnChangeStatus" id="btnChangeStatus" class="btn btn-sm btn-royal-blue">
                        Update
                    </button>
                </div>
            </div>
        </div>
        <section class="content-header p-0">
            <div class="col-sm-12 p-0" >
                <div class=" px-4 w-90" style="margin-left: 3px; width: 99.8%;">
                    <div class="col-sm-12 px-4" style="border-bottom: 1px solid #022B61;"></div>
                </div>
                <div class="search_area col-sm-12 px-5 hide">
                    <div class="col-sm-12 text-royal-blue" style="padding: 10px 5px">
                        SEARCH
                    </div>

                    <div class="col-sm-12 text-royal-blue" style="background-color: #f7f7f7;padding: 12px 0 6px 0px;">
                        <div class="col-sm-2 text-royal-blue">
                            &nbsp;&nbsp;Order / Enquiry Ref. No.<br>
                            <input class="input-sm form-control form-control-sm mt-2 pt-3">
                        </div>
                        <div class="col-sm-2">
                            &nbsp;&nbsp;Request Date & Time<br>
                            <input class="input-sm form-control form-control-sm mt-2 pt-3">
                        </div>
                        <div class="col-sm-2">
                            &nbsp;&nbsp;Brand<br>
                            <input class="input-sm form-control form-control-sm mt-2 pt-3">
                        </div>
                        <div class="col-sm-2">
                            &nbsp;&nbsp;Style Ref. No. / Name
                            <input class="input-sm form-control form-control-sm mt-2 pt-3">
                        </div>
                        <div class="col-sm-2">&nbsp;&nbsp;Auth. Status<br>
                            <input class="input-sm form-control form-control-sm mt-2 pt-3">
                        </div>
                        <div class="col-sm-2">
                            &nbsp;&nbsp;Auth. Date & Time<br>
                            <input class="input-sm form-control form-control-sm mt-2 pt-3">
                        </div>
                        <div class="col-sm-8">&nbsp;</div>
                    </div>
                    <div class="col-sm-12 " style="padding:13px 0">
                        <div class="btn-toolbar pull-right py-0" role="toolbar" aria-label="Toolbar with button groups">
                            <div class="btn-group mr-2 px-4 text-right" role="group" aria-label="First group">
                                <button class="btn btn-sm btn-royal-blue">
                                    <i class="fa fa-search"></i> Search
                                </button>
                            </div>
                            <div class="btn-group" role="group" aria-label="Third group">
                                <button class="btn btn-sm btn-royal-blue">
                                    <i class="fa fa-refresh"></i> Refresh
                                </button>
                            </div>

                        </div>
                    </div>
                </div>
                <div class=" px-4 w-90" style="margin-left: 3px; width: 99.8%;">
                    <div class="col-sm-12 px-4" style="border-bottom: 1px solid #022B61;"></div>
                </div>
            </div>
        </section>
        <section class="content">
            <div class="row">
                <div class=" px-4 w-90" style="margin-left: 3px; width: 99.8%;">
                    <div class="col-sm-12 px-4" style="border-bottom: 0 solid #022B61;"></div>
                </div>
                <?php
                $ArrUserLoggedInfo = fnGetUserLoggedInfo('1');
                $ArrUt             = unserialize(ARRUSERTYPE);
                ?>
                <div class="col-md-12">
                    <div class="box box-info">
                        <div class="box-body no-padding">
                            <!--<table id="workInProgressTbl" class="table table-bordered table-hover" style="width:1410px; background-color: #fdc6d0 !important">-->
                            <table id="workInProgressTbl" class="table table-bordered table-hover" style="padding: 0 2px">
                                <thead>
                                <tr>
                                    <th style=""></th>
                                    <th style="">WIP Ref. No.</th>
                                    <th style="">Date</th>
                                    <th style="">Brand</th>
                                    <th style="">Style Ref. No. /<br /> Name</th>
                                    <th style="">Order / Enq. Ref. No.</th>
                                    <th style="">P.O. / Sample Ref. No.</th>
                                    <th style="">P.O. / Sam. Qty.</th>
                                    <th style="">Pcs. / Set</th>
                                    <th style="">Ship. / Subn. Date</th>
                                    <th style="">Current <br />Status</th>
                                    <th style="">Recent <br />Update </th>
                                    <th style="">Status</th>
                                    <!--<th style="width: 1px"></th>
                                    <th style="width: 100px">WIP Ref. No.</th>
                                    <th style="width: 60px">Date</th>
                                    <th style="width: 60px">Brand / Buyer</th>
                                    <th style="width: 80px">Style Ref. No. /<br /> Name</th>
                                    <th style="width: 80px">Order / Enq. Ref. No.</th>
                                    <th style="width: 125px">P.O. / Sample Ref. No.</th>
                                    <th style="width: 60px">P.O. / Sam. Qty.</th>
                                    <th style="width: 40px">Pcs. / Set</th>
                                    <th style="width: 70px">Ship. / Subn. Date</th>
                                    <?php
                                    /*                                    if ($ArrUserLoggedInfo['usertype'] == '3') {
                                                                            echo '<th class=" " id="9">Merchant Name / Code </th>';
                                                                        }
                                                                        */?>
                                    <th style="width: 80px">Current <br />Status</th>
                                    <th style="width: 70px">Recent <br />Update </th>
                                    <th style="width: 70px">Status</th>-->
                                </tr>
                                </thead>
                            </table>
                        </div><!-- /.box-body -->
                    </div><!-- /.box -->
                </div><!-- /.col -->
            </div>
        </section>
    </div><!-- /.content-wrapper -->
    <?php $this->load->view(CNFCOMPANY . 'template/templatefooter'); ?>
    <div class="control-sidebar-bg"></div>
</div><!-- ./wrapper -->
<script src="<?php echo base_url();?>assets/js/datatables.min.js"></script>
<script type="text/javascript">
    /*$("tbody").find("tr").each(function() { //get all rows in table
        var tot = 0;
        var qty = $(this).find('td.poqty').text();
        alert(qty);
        var sum = qty + tot;
        console.log(sum);
    });*/
    var url = window.location;
    // Will only work if string in href matches with location
    $('ul.navbar-nav a[href="' + url + '"]').parent().addClass('active');
    // Will also work for relative and absolute hrefs
    $('ul.navbar-nav a').filter(function () {
        return this.href == url;
    }).parent().addClass('active');
    /!*menu handler*!/;
    $(function () {
        var url = window.location.pathname;
        //console.log(url,'url');
        var activePage = url.substring(url.lastIndexOf('/') + 1);
        //console.log(activePage, 'activePage');
        $('li.treeview a').each(function () {
            var currentPage = this.href.substring(this.href.lastIndexOf('/') + 1);
            //console.log(currentPage, 'currentPage9999');
            if (activePage == currentPage) {
                //console.log($(this).parent(), 'parent');
                $(this).parent().addClass('active');
            }
        });
    });

    var dataTbl = '';
    $(document).ready(function() {
        dataTbl = $('#workInProgressTbl').DataTable({
            "processing": true,
            "searching": false,
            "serverSide": true,
            "ajax": {
                "data": {"rFrom":1},
                "url": "<?php echo base_url('merchant/manageWip')?>",
                "type": "POST",
                "dataSrc": function (json) {
                    var urlIdPart,Budget,orderEntryLink,bomLink,fabricProgramLink,cadRequestLink,sampleRequestLink,bomPurchaseRequestLink = '';
                    console.log(json.data,'final data');
                    for ( var i=0, ien=json.data.length ; i<ien ; i++ ) {
                        urlIdPart = encodeURIComponent(base64_encode(json.data[i].id));
                        if(json.data[i].show==1) {
                            Budget = '<a href="' + base_path + 'budgetCosting/index/' + urlIdPart + '" target="_blank">' +
                                'Budget</a>';
                            orderEntryLink = '<a href="' + base_path + 'orderentryvtwo/entry/' + urlIdPart + '" target="_blank">' +
                                'Order Entry</a>';
                            bomLink = '<a href="' + base_path + 'billofmaterials/article_1/' + urlIdPart + '" target="_blank">' +
                                'BOM Program</a>';
                            fabricProgramLink = '<a href="' + base_path + 'fabricprogram/home/' + urlIdPart + '" target="_blank">' +
                                'Fabric Program</a>';
                            cadRequestLink = '<a href="' + base_path + 'merchant/addcadrequest/' + urlIdPart + '" target="_blank">' +
                                'CAD Request</a>';
                            sampleRequestLink = '<a href="' + base_path + 'msamplerequest/addeditsamplerequest/' + urlIdPart + '" target="_blank">' +
                                'Sample Request</a>';
                            bomPurchaseRequestLink = '<a href="' + base_path + 'mpurchase/addeditbompurchase/' + urlIdPart + '" target="_blank">' +
                                'BOM Request</a>';
                        }
                        else {
                            Budget = '';
                            orderEntryLink = '<label class="text-muted" style="padding: 5px">Order Entry</label>';
                            bomLink = '<label class="text-muted" style="padding: 5px">BOM Program</label>';
                            fabricProgramLink = '<label class="text-muted" style="padding: 5px">Fabric Program</label>';
                            cadRequestLink = '<label class="text-muted" style="padding: 5px">CAD Request</label>';
                            sampleRequestLink = '<label class="text-muted" style="padding: 5px">Sample Request</label>';
                            bomPurchaseRequestLink = '<label class="text-muted" style="padding: 5px">BOM Request</label>';
                        }

                        json.data[i][0] = '<tr><td><input type="checkbox" id="'+json.data[i].id+'" class="allcbox"></td>';
                        json.data[i][1] = '<td><a href="' + base_path + 'merchant/enquiryDetail/' + encodeURIComponent(base64_encode(json.data[i].id)) + '">' + json.data[i].isriorcode + '</a></td>';
                        json.data[i][2] = '<td>' + json.data[i].date + '</td>';
                        json.data[i][3] = '<td><div class="dropdown">' +
                            '<button id="dLabel" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' +
                            ''+json.data[i].brn+'' +
                            '<span class="caret"></span>' +
                            '</button>' +
                            '<ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">' +
                            '<li>'+Budget+'</li>' +
                            '<li>'+orderEntryLink+'</li>' +
                            '<li>'+bomLink+'</li>' +
                            '<li>'+fabricProgramLink+'</li>' +
                            '<li>'+cadRequestLink+'</li>' +
                            '<li>'+sampleRequestLink+'</li>' +
                            '<li>'+bomPurchaseRequestLink+'</li>' +
                            '</ul></div><td>';
                        /*json.data[i][3] = '<td><div class="dropdown" style="">' + json.data[i].brn + '</a><div class="dropdown-content" style="">'+orderEntryLink+fabricProgramLink+cadRequestLink+sampleRequestLink+bomPurchaseRequestLink+'\n' +
                            '                    </div>\n' +
                            '                </div>' +
                            '</td>';*/
                        json.data[i][4] = '<td>' + json.data[i].styref + '</td>';
                        json.data[i][5] = '<td>' + json.data[i].orderEnqRefNo + '</td>';
                        json.data[i][6] = '<td><a href="' + base_path + 'dashboard/wipDetailPage/' + encodeURIComponent(base64_encode(json.data[i].id)) + '/' +
                            encodeURIComponent(json.data[i].poNoEnqRefNo) + '/' + encodeURIComponent(json.data[i].ids) + '">' +
                            '' + json.data[i].poNoEnqRefNo + '</a></td>';
                        json.data[i][7] = '<td>' + json.data[i].poQtySampleQty + '</td>';
                        json.data[i][8] = '<td>' + json.data[i].pcsorset + '</td>';
                        json.data[i][9] = '<td>' + json.data[i].shipmentSubDate + ' </td>';
                        json.data[i][10] = '<td>-</td>';
                        json.data[i][11] = '<td>' + json.data[i].reupd + '</td>';
                        json.data[i][12] = '<td>'+json.data[i].s+'</td></tr>';
                    }
                    return json.data;
                }
            },
            'columnDefs': [{
                'targets': [0], // column index (start from 0)
                'orderable': false, // set orderable false for selected columns
            }],
            "order": [11,"desc"]
        });
    });

    $('#btnChangeStatus').on('click', function () {
        var dropdownOpt = $('#frmItemStatus').val();
        console.log(dropdownOpt,'dropdownOpt');
        var SelectedIdObject = commonCheckbox();
        var checkBoxLength   = SelectedIdObject[1];
        if (dropdownOpt > 0) {
            if (checkBoxLength >= 1) {
                var idJson = JSON.stringify(SelectedIdObject[0]);
                var StatusText = "Deactivate";
                if (dropdownOpt == 1) {
                    var StatusText = "Activate";
                }
                if (confirm('Do you want to ' + StatusText + ' this records?')) {
                    MakeAsynPostRequest(base_path + 'dashboard/changeWipStatus', 'id=' + idJson + '&cs=' + dropdownOpt, 'json', function (data) {
                        console.log(data, 'data');
                        dataTbl.ajax.url("<?php echo base_url('merchant/manageWip') ?>").load();
                    });
                }
            }
        }
        else {
            alert('Select a option');
        }
        if(checkBoxLength == 0) {
            alert('Select a record');
        }
    });
</script>
<?php $this->load->view(CNFCOMPANY . 'template/pagefooter'); ?>
