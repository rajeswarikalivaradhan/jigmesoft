<?php $this->load->view(CNFCOMPANY . 'template/pageheader'); ?>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/css/datatables.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>assets/plugins/datepicker/datepicker3.css">
    <style>
        /* Dropdown Button */
        .dropbtn {
            border: none;
        }

        /* The container <div> - needed to position the dropdown content */
        .dropdown {
            position: relative;
            display: inline-block;
        }

        /* Dropdown Content (Hidden by Default) */
        .dropdown-content {
            display: none;
            position: relative;
            background-color: #f1f1f1;
            box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
            z-index: 1;
        }

        /* Links inside the dropdown */
        .dropdown-content a {
            color: black;
            padding: 5px;
            text-decoration: none;
            display: block;
        }

        /* Change color of dropdown links on hover */
        .dropdown-content a:hover {
            background-color: #ddd;
        }

        /* Show the dropdown menu on hover */
        .dropdown:hover .dropdown-content {
            display: block;
        }
    </style>
    <body class="sidebar-mini skin-black wysihtml5-supported sidebar-collapse">
<div class="wrapper">
    <?php $this->load->view(CNFCOMPANY . 'template/templateheader'); ?>
    <aside class="main-sidebar">
        <?php $this->load->view(CNFCOMPANY . 'template/templateleftmenu'); ?>
    </aside>
    <div class="content-wrapper">
        <section class="content-header">
            <div class="col-md-6" style="padding-left: 20px">
                <h1 style="margin: 0; font-size: 20px; font-weight: 700">WORK IN PROGRESS LIST</h1>
            </div>
            <div class="col-md-6" style="padding-bottom: 10px">
                <div class="col-md-6"></div>
                <div class="col-md-6">
                    <div class="col-md-8">
                        <select name="frmItemStatus" title="activate / deactivate" id="frmItemStatus" class="form-control" style="">
                            <option value="">Select</option>
                            <option value="1">Active</option>
                            <option value="2">Inactive</option>
                        </select>
                    </div>
                    <div class="col-md-2" style="padding-right: 0; float: right;">
                        <input type="button" name="btnChangeStatus" id="btnChangeStatus" class="btn btn-info pull-right" value="Update">
                    </div>
                </div>

            </div>
        </section>
        <section class="content">
            <div class="row">
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
                                    <th style="">Brand / Buyer</th>
                                    <th style="">Style Ref. No. /<br /> Name</th>
                                    <th style="">Order / Enq. Ref. No.</th>
                                    <th style="">P.O. / Sample Ref. No.</th>
                                    <th style="">P.O. / Sam. Qty.</th>
                                    <th style="">Pcs. / Set</th>
                                    <th style="">Ship. / Subn. Date</th>
                                    <?php
                                    if ($ArrUserLoggedInfo['usertype'] == '3' || $ArrUserLoggedInfo['usertype'] == '15' ) {
                                        echo '<th class=" " id="9">Merchant Name / Code </th>';
                                    }
                                    ?>
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
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <?php $this->load->view(CNFCOMPANY . 'template/templatefooter'); ?>
    <div class="control-sidebar-bg"></div>
</div>
<script src="<?php echo base_url(); ?>assets/plugins/datepicker/bootstrap-datepicker.js"></script>
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
    /!*menu handler*!/
    $(function () {
        var url = window.location.pathname;
        //console.log(url,'url');
        var activePage = url.substring(url.lastIndexOf('/') + 1);
        console.log(activePage, 'activePage');
        $('li.treeview a').each(function () {
            var currentPage = this.href.substring(this.href.lastIndexOf('/') + 1);
            console.log(currentPage, 'currentPage9999');
            if (activePage == currentPage) {
                console.log($(this).parent(), 'parent');
                $(this).parent().addClass('active');
            }
        });
    });

    var dataTbl = ''; var GlbUsertype = '<?php echo $_SESSION['UI']['usertype'] ?>';
    $(document).ready(function() {
        dataTbl = $('#workInProgressTbl').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "data": {"rFrom":1},
                "url": "<?php echo base_url('company/workinprogress/managewip')?>",
                "type": "POST",
                "dataSrc": function (json) {
                    //console.log(json,'json');
                    console.log(GlbUsertype,'GlbUsertype');
                    var PageContent = ''; var griddatas = ''; var sumpoq = ''; var pcsorset = ''; var shipmentDate = ''; var cstatus = '';
                    if(GlbUsertype == 3) {
                        for ( var i=0, ien=json.data.length ; i<ien ; i++ ) {
                            griddatas = '<table>';
                            sumpoq = '<table>';
                            pcsorset = '<table>';
                            cstatus = '<table>';
                            $.each(json.data[i].griddatas,function (ind,val) {
                                if(val!='') {
                                    griddatas = griddatas + '<tr><td><a href="' + base_path + GlbCompanyFdr + 'workinprogress/wip_detail/' +
                                        val.ids + '/' + encodeURIComponent(val.pono) + '/' + encodeURIComponent(val.poids) + '">' + val.pono +
                                        '</a></td></tr>';
                                    sumpoq = sumpoq + '<tr><td>' + val.sumpoq + '</td>';
                                    pcsorset = pcsorset + '<tr><td>' + json.data[i].pcsorset + '</td>';
                                }
                                else {
                                    griddatas = griddatas + '<tr><td></td></tr>';
                                    sumpoq = sumpoq + '<tr><td></td>';
                                    pcsorset = pcsorset + '<tr><td></td>';
                                }
                            });
                            var shipment = json.data[i].shipdate;
                            console.log(shipment,'shipment');
                            $.each(shipment,function (shipInd,shipVal) {
                                console.log(shipInd,'shipInd');
                                console.log(shipVal,'shipVal');
                            });
                            $.each(json.data[i].cs,function (i,v) {
                                if(v != '') {
                                    cstatus = cstatus +'<tr><td>'+v+'</td>';
                                }
                                else {
                                    cstatus = cstatus +'<tr><td></td>';
                                }
                            });
                            //[["2020-03-30 00:00:00", "2020-03-30 00:00:00", "", "", "", "", "", "2020-03-30 00:00:00", "2020-03-30 00:00:00", "2020-03-30 00:00:00", "Port of Kochi", "INDIA", "Port of Hamburg ", "GERMANY"], ["2020-03-31 00:00:00", "2020-03-31 00:00:00", "", "", "", "", "", "2020-03-31 00:00:00", "2020-03-31 00:00:00", "2020-03-31 00:00:00", "Port of Mumbai", "INDIA", "Port of Hamburg ", "GERMANY"]]
                            griddatas += '</table>';
                            sumpoq += '</table>';
                            pcsorset += '</table>';
                            cstatus += '</table>';
                            json.data[i][0] ='<tr><td><input type="checkbox" id="'+json.data[i].id+'" class="allcbox"></td>';
                            json.data[i][1] = '<td><a href="' + base_path + 'merchant/enquiryview/' + encodeURIComponent(base64_encode(json.data[i].id)) + '">' + json.data[i].isriorno + '</a></td>';
                            json.data[i][2] = '<td>' + json.data[i].date + '</td>';
                            json.data[i][3] = '<td>' + json.data[i].bb + '</td>';
                            json.data[i][4] = '<td>' + json.data[i].styref + '</td>';
                            json.data[i][5] = '<td>' + json.data[i].oenqrefno + '</td>';
                            json.data[i][6] = '<td>' + griddatas + '</td>';
                            json.data[i][7] = '<td>'+sumpoq+'</td>';
                            json.data[i][8] = '<td>' + pcsorset + '</td>';
                            json.data[i][9] = '<td>Test</td>';
                            json.data[i][10] = '<td>' + json.data[i].merchant + '</td>';
                            json.data[i][11] = '<td>'+cstatus+'</td>';
                            json.data[i][12] = '<td>'+json.data[i].reupd+'</td>';
                            json.data[i][13] = '<td>'+json.data[i].s+'</td></tr>';
                        }
                    }
                    else if(GlbUsertype == 4) {

                        console.log(json.data.length,'json.data.length');
                        for ( var i=0, ien=json.data.length ; i<ien ; i++ ) {
                            console.log(json.data[i],'VIGNESH ');
                            console.log(json.data[i].shipdate,'json.data ');
                            griddatas = '<table>';
                            sumpoq = '<table>';
                            pcsorset = '<table>';
                            shipmentDate = '<table>';
                            cstatus = '<table>';
                            $.each(json.data[i].shipdate,function (ind,val) {
                                if(val!='') {
                                    shipmentDate = shipmentDate + '<tr><td><a href="">'+val+'</a></td></tr>';
                                }
                                else {

                                }
                            });
                            $.each(json.data[i].griddatas,function (ind,val) {
                                console.log(val,'c val');
                                if(val!='') {
                                    griddatas = griddatas + '<tr><td><a href="' + base_path + GlbCompanyFdr + 'workinprogress/wip_detail/' + val.ids + '/' + val.pono + '/' + encodeURIComponent(val.poids) + '">' + val.pono + '</a></td></tr>';
                                    sumpoq = sumpoq + '<tr><td>' + val.sumpoq + '</td>';
                                    pcsorset = pcsorset + '<tr><td>' + json.data[i].pcsorset + '</td>';
                                }
                                else {
                                    griddatas = griddatas + '<tr><td></td></tr>';
                                    sumpoq = sumpoq + '<tr><td></td>';
                                    pcsorset = pcsorset + '<tr><td></td>';
                                }
                            });
                            $.each(json.data.cs,function (i,v) {
                                if(v != '') {
                                    cstatus = cstatus +'<tr><td>'+v+'</td>';
                                }
                                else {
                                    cstatus = cstatus +'<tr><td></td>';
                                }
                            });
                            griddatas += '</table>';
                            sumpoq += '</table>';
                            pcsorset += '</table>';
                            shipmentDate += '</table>';
                            cstatus += '</table>';
                            json.data[i][0] = '<tr><td><input type="checkbox" id="'+json.data[i].id+'" class="allcbox"></td>';
                            json.data[i][1] = '<td><a href="' + base_path + 'merchant/enquiryview/' + encodeURIComponent(base64_encode(json.data[i].id)) + '">' + json.data[i].isriorno + '</a></td>';
                            json.data[i][2] = '<td>' + json.data[i].date + '</td>';
                            json.data[i][3] = '<td><div class="dropdown">\n' +
                                '                    <a href="#" class="dropbtn">' + json.data[i].bb + '</a>\n' +
                                '                    <div class="dropdown-content">\n' +
                                '                        <a href="' + base_path + 'orderentryvtwo/entry/' + encodeURIComponent(base64_encode(json.data[i].id)) + '" target="_blank">Order Entry</a>\n' +
                                '                        <a href="' + base_path + 'fabricprogram/home/' + encodeURIComponent(base64_encode(json.data[i].id)) + '" target="_blank">Fabric Program</a>\n' +
                                '                        <a href="' + base_path + 'merchant/addcadrequest/' + encodeURIComponent(base64_encode(json.data[i].id)) + '" target="_blank">CAD Request</a>\n' +
                                '                        <a href="' + base_path +'msamplerequest/addeditsamplerequest/' + encodeURIComponent(base64_encode(json.data[i].id)) + '" target="_blank">Sample Request</a>\n' +
                                '                        <a href="' + base_path +'mpurchase/addeditbompurchase/' + encodeURIComponent(base64_encode(json.data[i].id)) + '" target="_blank">BOM Request</a>\n' +
                                '                    </div>\n' +
                                '                </div>' +
                                '</td>';
                            json.data[i][4] = '<td>' + json.data[i].styref + '</td>';
                            json.data[i][5] = '<td>' + json.data[i].oenqrefno + '</td>';
                            json.data[i][6] = griddatas;
                            json.data[i][7] = '<td>'+sumpoq+'</td>';
                            json.data[i][8] = '<td>' + pcsorset + '</td>';
                            json.data[i][9] = '<td>' + shipmentDate + '</td>';
                            json.data[i][10] = '<td>'+cstatus+'</td>';
                            json.data[i][11] = '<td>' + json.data[i].reupd + '</td>';
                            json.data[i][12] = '<td>'+json.data[i].s+'</td></tr>';
                        }
                    }
                    return json.data;
                    //$('tbody').empty();
                    //$('#workInProgressTbl').append(PageContent);
                }
            },
            'columnDefs': [ {
                'targets': [0,6,7,8,9,10,11], // column index (start from 0)
                'orderable': false, // set orderable false for selected columns
            }],
            "order": [2,"desc"]
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
                    MakeAsynPostRequest(base_path + 'dashboard/changeAllListActiveStatus', 'id=' + idJson + '&cs=' + dropdownOpt +
                        '&tblname=kn_order_enquiry', 'json', function (data) {
                        console.log(data, 'data');
                        dataTbl.ajax.url("<?php echo base_url('company/workinprogress/wip_ajaxdata') ?>").load();

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