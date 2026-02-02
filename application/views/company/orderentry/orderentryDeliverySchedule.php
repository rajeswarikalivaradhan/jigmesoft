<?php
$this->load->view(CNFCOMPANY . 'template/pageheader');
?>
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/jexcel4/jsuites.css"/>
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/jexcel4/jexcel.css"/>
<body class="sidebar-mini skin-black wysihtml5-supported sidebar-collapse">
<div class="wrapper" >
    <?php $this->load->view(CNFCOMPANY . 'template/templateheader'); ?>

    <aside class="main-sidebar">
        <?php $this->load->view(CNFCOMPANY . 'template/templateleftmenu'); ?>
    </aside>

    <div class="content-wrapper order-entry" >
        <div class="col-md-12">
            <section class="content-header">
                <h1 class="firstHeading">Order Entry</h1>
            </section>
        </div>
        <section class="content">
            <div class="dropdown">

            </div>
            <div class="row">
                <div class="col-md-12">
                    <div id="DivContBasicInfo">
                        <div class="box box-info">
                            <div class="box-header with-border">

                            </div>
                            <div class="box-body" style="padding: 0px;">
                                <form class="form-horizontal" name="frmBasicInfo" id="frmOrderProcess" method="post">
                                    <div class="col-md-12 pd0 no-padding">
                                        <?php $this->load->view(CNFCOMPANY."orderentry/orderentrycommondetails"); ?>
                                        <div style="background-color: rgb(210 253 249); padding-top: 10px; padding-left: 10px">
                                            <strong>P.O. WISE DELIVERY SCHEDULE</strong>
                                        </div>
                                        <?php
                                        $ArrPcsSet = unserialize(ARRPCSSET);
                                        if($ArrPcsSet[$ArrCommonHeaderData['ArrEnquiryDetails']['pcsorset']]) {
                                            $VarPcsOrSet = $ArrPcsSet[$ArrCommonHeaderData['ArrEnquiryDetails']['pcsorset']];
                                        }
                                        else {
                                            $VarPcsOrSet = 0;
                                        }
                                        ?>
                                    </div>
                                    <div class="col-sm-12" style="padding: 5px !important;">
                                        <div class="form-group">
                                        <div id="fourthtbl"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-12" style="padding: 5px">
                                        <div class="form-group" style="margin-bottom: 0">
                                            <label for="tblRemarks">Remarks</label>
                                            <textarea id="frmBasicRemarks" name="tblRemarks" title="Remarks" class="form-control"></textarea>
                                        </div>
                                    </div>
                                </form>
                                <div class="col-md-12" style="padding: 5px">
                                    <!--<button type="button" class="" onclick="setShipDate()">Set Ship Date</button>-->
                                    <?php $this->load->view(CNFCOMPANY . "orderentry/footerNavSave"); ?>
                                </div>
                                <div class="col-md-12" style="padding: 5px">
                                    <div class="alert alert-success alert-dismissable hide" id="divSuccessBasicInfoMsg"> </div>
                                    <div class="alert alert-danger alert-dismissable hide" id="ErrOrderEntry"> </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <?php $this->load->view(CNFCOMPANY . 'template/templatefooter'); ?>
    <div class="control-sidebar-bg"></div>
</div>
<script src="<?php echo base_url(); ?>assets/js/jexcel4/jsuites.js"></script>
<script src="<?php echo base_url(); ?>assets/js/jexcel4/jexcel.js"></script>
<script type="text/javascript">
    var enquiryid = '<?php echo $VarEnquiryId ?>';
    var GlbParam = 'rfrom=1';
    var GlbModeOfShipment = [], GlbDeliverySchedule = [], GlbSecondTblData = [];
    unsaved = false; var GlbArrFromFourthTbl = 0;

    function fnPopulateValueArray(ArrName, KeyValue, InsertVal) {
        if (jQuery.inArray(KeyValue, ArrName)) {
            if(InsertVal !== "") {
                ArrName[KeyValue] = ArrName[KeyValue]+"|#|"+InsertVal;
            }
        }
        return ArrName;
    }

    MakePostRequest(base_path+'orderentryvtwo/deliverySchedule',GlbParam+"&enqId="+enquiryid,'json',getCommonFourthTblDataRes);

    function getCommonFourthTblDataRes(data) {
        console.log(data,'data');
        if(data.remarks !== "") {
            document.getElementById("frmBasicRemarks").innerText = data.remarks;
        }
        GlbPortCity = data.ArrPortNameCity;
        GlbPortCountry = data.ArrPortCountry;
        GlbModeOfShipment = data.ArrModeOfShipment;

        if(data.ArrFromSecondTbl != "") {
            secondTbl = data.ArrFromSecondTbl;
            console.log(secondTbl,'secondTbl');
            if(data.ArrFromFourthTbl != "") {
                GlbDeliverySchedule = data.ArrFromFourthTbl;
                console.log(GlbDeliverySchedule,'GlbDeliverySchedule');
                Arr2nd = data.Arr2nd;
                for (let ii = 0; ii < GlbDeliverySchedule.length; ii++) {
                    console.log(Arr2nd[ii],'Arr2nd[ii]');
                    GlbDeliverySchedule[ii].splice(2,0,Arr2nd[ii][0],Arr2nd[ii][1],Arr2nd[ii][2],Arr2nd[ii][3]);
                }
            }
            else {
                //Initially
                GlbDeliverySchedule = secondTbl;
            }
        }
    }
    console.log(GlbDeliverySchedule,'GlbDeliverySchedule after push');
    // A custom method to SUM all the cells in the current column
    SUMCOL = function(instance, columnId) {
        var total = 0;
        for (var j = 0; j < instance.options.data.length; j++) {
            if (Number(instance.records[j][columnId-1].innerHTML)) {
                total += Number(instance.records[j][columnId-1].innerHTML);
            }
        }
        return total;
    };

    delSchdle = jexcel(document.getElementById('fourthtbl'), {
        columns:[ //colWidths: [90, 90, 120, 100, 90, 50, 60, 90, 90, 90, 90, 90, 90, 90],
            { type:'calendar',title:'P.O. / Enq. Date', width: 80 },
            { type:'calendar',title:'P.O. / Enq. Recd. Date', width:80 },
            { type:'text',title:'Combo / Colour', width: 145, readOnly: true },
            { type:'text',title:'P.O. No. / Enq. Ref. No.', width:130, readOnly: true },
            { type:'text',title:'P.O. Qty. / Sample Qty.', width:100, readOnly: true },
            { type:'text',title:'Pcs. / Set', width:75, readOnly: true },
            { type:'dropdown',title:'Mode of Shipment', width:97, source: GlbModeOfShipment },
            { type:'calendar',title:'Ship. Date / Subn. Date', width:80, options: { format:'DD/MM/YYYY' /*fullscreen: true*/ } },
            { type:'dropdown',title:'Loading Port & City', width:150, source: GlbPortCity },
            { type:'dropdown',title:'Loading Country', width:130, source: GlbPortCountry },
            { type:'dropdown',title:'Destination Port & City', width:150, source: GlbPortCity },
            { type:'dropdown',title:'Destination Country', width:130, source: GlbPortCountry },
        ],
        footers: [['','','','Total','=SUMCOL(TABLE(),COLUMN())','<?php echo $VarPcsOrSet ?>']],
        allowInsertRow:false,
        allowInsertColumn:false,
        onchange: function() {
            unsaved = true;
        },
        data:GlbDeliverySchedule,
        columnDrag:true,
        updateTable:function(instance, cell, col, row, val, label, cellName) {
            if(col === 3) {
                poNo = val;
            }
            if(col === 4) {
                cell.style.textAlign = "right";
            }
            if(col === 7) {
                /*console.log(row,'row');
                thisDate = $(cell).html();
                poNoandDate = poNo+"##"+thisDate;
                console.log(poNoandDate,'poNoandDate');
                poNoOnly = poNoandDate.substring(0,poNoandDate.indexOf('##'));
                console.log(poNo,'poNo');
                console.log(poNoOnly,'poNoOnly');
                if(poNo == poNoOnly) {
                    console.log(poNoOnly,'poNoOnly INSIDE IF');
                    $(cell).text(thisDate);
                    instance.jexcel.options.data[row][col] = thisDate;
                }*/
            }
        }
    });
    function getAllIndexes(arr, val) {
        let indexes = [], i = -1;
        while ((i = arr.indexOf(val, i+1)) != -1) {
            indexes.push(i);
        }
        return indexes;
    }

    function setShipDate() {
        dateOfPoNo = {};
        let fullData = delSchdle.getData();
        ArrPoNo = []; poNoPlaces = {};
        for (let ii = 0; ii < fullData.length; ii++) {
            let poNo = fullData[ii][3];
            let shipDate = fullData[ii][7];
            console.log(shipDate,'shipDate');
            //console.log(poNo,'poNo');
            //if(jQuery.inArray(poNo,poNoPlaces) === -1) {
                //console.log(ii,'ii');
            ArrPoNo.push(poNo);

            dateOfPoNo = fnPopulateValueArray(dateOfPoNo,poNo,shipDate);
            //}
            //else {
                //console.log(ii,'ii IN ELSE');
            //}
        }
        console.log(dateOfPoNo,'dateOfPoNo');
        console.log(ArrPoNo,'ArrPoNo');
        for(let ii = 0; ii < ArrPoNo.length; ii++) {
            let ind = getAllIndexes(ArrPoNo,ArrPoNo[ii]);
            console.log(ind,'ind');
            poNoPlaces[ArrPoNo[ii]] = ind;
        }
        console.log(poNoPlaces,'poNoPlaces');
        for(let prop in poNoPlaces) {
            console.log(prop,'prop');
            let pos = poNoPlaces[prop];
            let setThisDate = dateOfPoNo[prop].replace('undefined|#|','');
            for(let ii = 0; ii < pos.length; ii++) {
                console.log(pos[ii],'pos');
                console.log(setThisDate,'setThisDate');
                $("#fourthtbl").jexcel('setValueFromCoords',7,pos[ii],setThisDate);
            }

            //delSchdle.setValueFromCoords(7,pos,setThisDate);
        }

        //myTable.getValueFromCoords([integer], [integer], [string], [bool]);
    }

    function fnSaveChanges() {
        console.log(GlbDeliverySchedule,'GlbPoWiseDeliSc');
        let neededData = $("#fourthtbl").jexcel('getData');
        let allData = $("#fourthtbl").jexcel('getData');
        let delColCount = 4;
        for(let ii = 0; ii < neededData.length; ii++) {
            neededData[ii].splice(2,delColCount);
        }
        console.log(neededData,'neededData');
        let tblRemarks = $("#frmBasicRemarks").val();
        var param = GlbParam+"&ft_data_only="+JSON.stringify(neededData)+"&enqId="+enquiryid+"&e="+encodeURIComponent(tblRemarks)+"&all_data="+JSON.stringify(allData);
        MakeAsynPostRequest(base_path+'orderentryvtwo/saveDeliveryScheduleSixthTbl',param,'json',fnSaveTableRes);
    }
    function fnSaveTableRes(data) {
        console.log(data,'data');
        if(data!='') {
            if(data.errcode==-1){
                $("#ErrOrderEntry").removeClass('hide');
                $('#ErrOrderEntry').text(data.msg);
                return false;
            } else if(data.errcode == 1) {
                unsaved = false;
                $("#divSuccessBasicInfoMsg").removeClass('hide');
                $('#divSuccessBasicInfoMsg').text("Saved Successfully");
            }
        }
    }
    function unloadPage() {
        if(unsaved){
            return "You have unsaved changes on this page. Do you want to leave this page and discard your changes or stay on this page?";
        }
    }
    window.onbeforeunload = unloadPage;
</script>
<?php $this->load->view(CNFCOMPANY . 'template/pagefooter'); ?>