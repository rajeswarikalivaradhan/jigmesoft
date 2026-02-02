<?php $this->load->view(CNFCOMPANY . 'template/pageheader'); ?>
<style>
   .jexcel {
    border-right: 1px solid #f7f7f7 !important;
    border-bottom: 1px solid #f7f7f7 !important; 
    /*border-right: 1px solid #D9D9D9 !important;*/
   }
   
   .jexcel > tbody > tr > td:first-child,.jexcel > thead > tr > td,.jexcel > tfoot > tr > td {
      background-color:#D9D9D9!important;
   }
   .jexcel > thead > tr > td,.jexcel > tbody > tr > td,.jexcel > tfoot > tr > td {
    border: 0.01em solid #f7f7f7 !important;
   }
   .jexcel > tfoot > tr > td{
       height: 37px!important;
   }
   .b-0{
       border-top:none!important;
   }
   .table-responsive {
    overflow-x: unset !important;
}
.jdropdown-focus {
    position: inherit !important;
}
.content{
    padding-top:50px!important;
}
.ord-procs-cell {
    width: 25%;
}

.tbl-procs-border {
    border: 1px solid #ddd!important;
}
.table > tbody > tr > td {
    border-top:0px!important;
}
td.process-value,
td.process-title,
.process-main-value,
td.process-main-head {
    font-size: 12px;
}

td.process-main-heads {
    font-size: 12px;
}

td.process-title {
    background: #f3f3f3;
    width: 25% !important;
    text-align: right;
}
tfoot td:first-child, tfoot td:nth-child(2){
     display: table-cell!important; 
}
td.process-main-head {
    background: #022b61;
    color: #ffffff;
    text-align: center;
}

td.process-main-heads {
    background: #e8e8e8;
    color: #050505;
    text-align: left;
}
.tables{
    margin-bottom: 5px!important;
}
.card-body{
    margin:6px!important;
}
table.table.tbl-procs-border {
    margin-bottom: 0;
}
.table {
    background: #F7F7F7!important;
}
.swal2-content {
    font-size: 18px!important;
}
.swal2-titles {
    color: red!important;
    font-weight: 500!important;
}
.swal2-icon.swal2-warning{
    border-color: #FFCC00!important;
    color: #FFCC00!important;
    border: 2px solid #FFCC00!important;
}
   </style>
<body class="layout-top-nav">
<div class="wrapper">
    <?php $this->load->view(CNFCOMPANY . 'template/badmintemplateheader');?>
    <div class="content-wrapper">
        <section class="content">
            <section class="invoice form-horizontal">
                <div class="row"> 
                    <div class="col-xs-12">
                <h2 class="page-header text-royal-black mx-4" style="border-bottom:0px"><?php  echo $ArrUserType[$usertype_id].'-';?>  View / Edit User Roles
                  <div class="pull-right">
                                    <div class="ml-auto pr-3">
                                    <a id="backbtn" class="btn custbtn btn-sm mx-3 btn-royal-blue btn-text-slide-x">Back</a>
                                    <?php  
                                        if(isset($ArrRoleInfo) && count($ArrRoleInfo)==1 && in_array("", $ArrRoleInfo) && $ArrListExsist==0) { echo ''; } else {
                                    ?>
                                   <?php if($proformastatus==2 || $confirmstatus==1) {?>
                                    <a id="editEnable_disabled" disabled class="btn custbtn btn-royal-blue btn-sm px-3" >Edit</a>
                                    <?php } else { ?>
                                    <a id="editEnable"  class="btn custbtn btn-royal-blue btn-sm px-3" >Edit</a>
                                    <?php }} ?>
                                    </div>
                                </div>
                <div class="col-sm-12 " style="padding: 7px 25px;border-bottom: 1px solid #022B61;"></div>
                </h2>
                <h4 class="mr-2 py-2 text-royal-blue">
                </h4>
            </div><!-- /.col -->
                </div>
                <div class="row no-rad-form add-form-mar" id="custom_form">
                    <input type="hidden" id="subscriber_id" value="<?php echo $subscriber_id ;?>">
                    <input type="hidden" id="dept_id" value="<?php echo $usertype_id ;?>">
                    <input type="hidden" id="proforma_id" value="<?php echo $proforma_id ;?>">
                    <input type="hidden" id="userid" value="">
                    <input type="hidden" id="editvariable" value="<?php  echo (isset($ArrRoleInfo) && count($ArrRoleInfo)==1 && (in_array("", $ArrRoleInfo)) && $ArrListExsist==0) ? '1' : '2';?>"> 
                    <input type="hidden" id="statuscond" value="<?php echo $Arrdeptwiseinfostatus;?>">
                    <div class="col-md-12">
                        <div class="col-md-2">
                            <div class="form-group">
                                    <label for="id-form-field-focus-1" class="col-sm-9 control-label labelclr">
                                    Request Received List
                                    </label>
                                <div class="col-sm-3">
                                   <input type="checkbox" name="title" value="Request Received List" <?php if(isset($ArrRoleInfo) && count($ArrRoleInfo)>0 && in_array('Request Received List',$ArrRoleInfo)) { echo 'checked';}?>>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                    <label for="id-form-field-focus-1" class="col-sm-9 control-label labelclr">
                                    Queue List
                                    </label>
                                <div class="col-sm-3">
                                   <input type="checkbox" name="title" value="Queue List" <?php if(isset($ArrRoleInfo) && count($ArrRoleInfo)>0 && in_array('Queue List',$ArrRoleInfo)) { echo 'checked';}?>>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                    <label for="id-form-field-focus-1" class="col-sm-9 control-label labelclr">
                                    Request Sent List
                                    </label>
                                <div class="col-sm-3">
                                   <input type="checkbox" name="title" value="Request Sent List" <?php if(isset($ArrRoleInfo) && count($ArrRoleInfo)>0 && in_array('Request Sent List',$ArrRoleInfo)) { echo 'checked';}?>>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                    <label for="id-form-field-focus-1" class="col-sm-9 control-label labelclr">
                                    P.I. List
                                    </label>
                                <div class="col-sm-3">
                                   <input type="checkbox" name="title" value="P.I. List" <?php if(isset($ArrRoleInfo) && count($ArrRoleInfo)>0 && in_array('P.I. List',$ArrRoleInfo)) { echo 'checked';}?>>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                                     <div class="form-group">
                                    <label for="id-form-field-focus-1" class="col-sm-9 control-label labelclr">
                                   Bill Paid List
                                    </label>
                                <div class="col-sm-3">
                                   <input type="checkbox" name="title" value="Bill Paid List" <?php if(isset($ArrRoleInfo) && count($ArrRoleInfo)>0 && in_array('Bill Paid List',$ArrRoleInfo)) { echo 'checked';}?>>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                             <div class="form-group">
                                    <label for="id-form-field-focus-1" class="col-sm-9 control-label labelclr">
                                    Stock Transfer Memo List
                                    </label>
                                <div class="col-sm-3">
                                   <input type="checkbox" name="title" value="Stock Transfer Memo List" <?php if(isset($ArrRoleInfo) && count($ArrRoleInfo)>0 && in_array('Stock Transfer Memo List',$ArrRoleInfo)) { echo 'checked';}?>>
                                </div>
                            </div>
                        </div>
                    </div>
                    </div>
                <div class="row" style="padding:10px 27px;">
                    <div class="purch_details" id="purch_details"></div>
                </div>
                <div class="row">
                    <div class="col-xs-12 py-4" style="padding-right:30px">
                        <button class="btn btn-info pull-right mx-2" id="svbtn"  disabled="true">Save</button>
                    </div>
                </div>
        </section>     
        </section>
    </div>
    <?php $this->load->view(CNFCOMPANY . 'template/templatefooter'); ?>
    <div class="control-sidebar-bg"></div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.6.10/vue.min.js"></script>
<script src="<?= base_url() ?>assets/js/jexcelNew/jexcel.js"></script>
<script src="<?= base_url() ?>assets/js/jexcelNew/jsuites.js"></script>
<link rel="stylesheet" href="<?= base_url() ?>assets/css/jexcelNew/jspreadsheet.css" type="text/css" />
<link rel="stylesheet" href="<?= base_url() ?>assets/css/jexcelNew/jsuites.css" type="text/css" />
<script src="<?= base_url() ?>assets/ace/node_modules/interactjs/dist/interact.js"></script>
<script src="<?php echo base_url();?>assets/js/jexcel/numeral.min.js"></script>
<?php $this->load->view(CNFCOMPANY . 'template/pagefooter'); ?>
<script>
    let swalWithBootstrapButtons = Swal.mixin({buttonsStyling: false});
    var subscriber_id = $("#subscriber_id").val();
    var department_id = $("#dept_id").val();
    var proforma_id = $("#proforma_id").val();
    
    $('#backbtn').on('click', function() {

    let redirectpath = base_path + GlbBAdminFdr +  'msubscription/detviews/' + encodeURIComponent(base64_encode(subscriber_id)) + '/' + encodeURIComponent(base64_encode(proforma_id));
                 window.location.href = redirectpath;

});
showpurchasedetail(subscriber_id);
function showpurchasedetail(subscriber_id)
 {    
       
        $("#purch_details").html("");
        const datasend = {
                            dept_id: department_id,
                            subscriber_id:subscriber_id,
                            proforma_id:proforma_id,   
                        };
        MakeAsynPostRequest(base_path + "badmin/msubscription/showpurchasedepartment", datasend , "json", function(data) {
         //   console.log("draft" + data.data);
        let min_dimensions = data.column.length;
        let statuschanged=false;
        var changed = function(instance, cell, x, y, value) {
        document.getElementById('svbtn').disabled=false;
        statuschanged=true;
        };
        let statusexsist=$('#editvariable').val();
        var statuscond=$('#statuscond').val(); 
        let options = {
                data: data.data,
                editable:true,
                columns: data.column,
                minDimensions: [min_dimensions, 1],
                allowDeleteColumn: false,
                allowInsertRow: false,
                allowInsertColumn: false,
                allowDeleteRow: false,
                contextMenu: function() {
                    return false;
                },
                //footers:[['', '', '','','','', 'Total : ','=SUMCOL(TABLE(), COLUMN())','=SUMCOL(TABLE(), COLUMN())','=SUMCOL(TABLE(), COLUMN())']],
                updateTable: function (instance, cell, col, row, val, label, cellName) { 
                if (col==2 && (statusexsist==2||statuscond==2)) {
                    if(!statuschanged){
                        cell.classList.add('readonly');
                    }
                }      
                },
                 onchange: changed
            };
            
             let k = new Vue({
                    el: '#purch_details',
                    mounted: function () {
                        let spreadsheet = jspreadsheet(this.$el, options);
                        Object.assign(this, spreadsheet);
                    },
                    methods: {
                        submitData: function () {
                            let data = this.getData();
                            let checkstatus=[];
                            for(i=0;i<data.length;i++){ // status value in seperate array from data array of !empty condition 
                              if(data[i][2]!=''){
                                  checkstatus[i]=data[i][2];
                              }
                            }
                            //console.log(checkstatus);
                            var items = document.getElementsByName('title');
                            var deptid = $("#dept_id").val();
                            var selectedItems=[];
                               for(var i=0; i<items.length; i++){
                                    if(items[i].type=='checkbox' && items[i].checked==true){
                                        selectedItems.push(items[i].value);
                                    } 
                                    }
                                    //console.log(selectedItems.join(','));
                                     const allEmptyOrZero = checkstatus.every(value => value == '' || value == 0);
                                     const dataToSend = {
                                                    rfrom: 1,
                                                    dept_id: deptid,
                                                    subscriber_id:subscriber_id,
                                                    proforma_id:proforma_id,
                                                    statuscheck:(!allEmptyOrZero)?checkstatus.join(','):'',
                                                    object:JSON.stringify(data),
                                                    title: selectedItems.join(','), // Convert array to a comma-separated string
                                    };
                             MakeAsynPostRequest(base_path + GlbBAdminFdr + "msubscription/updateInfo",dataToSend, "json",function (data) {
                                    if (data != '') {
                                        if (data.errcode == '404') {
                                        fnCallSessionExpire();
                                        return false;
                                    } else if (data.errcode == -1) {
                                        swalWithBootstrapButtons.fire({
                                            title: data.msg,type: 'warning',
                                            icon: 'warning',
                                            customClass: {'confirmButton': 'btn btn-info'}
                                        });
                                        return false;
                                    } else if (data.errcode == 1 || data.errcode == 2) {
                                            swalWithBootstrapButtons.fire({
                                                        title: 'Saved!',type: 'success',
                                                        icon: 'success',
                                                        customClass: {'confirmButton': 'btn btn-info'}
                                            }).then((result) => {
                                                // let redirectpath = base_path + GlbBAdminFdr + 'msubscription/detviews/'+ encodeURIComponent(base64_encode(subscriber_id));
                                                // window.location.href = redirectpath;
                                                 location.reload();
                                               
                                                
                                                //  showcaddetail(subscriber_id);
                                                //  $('#mydiv').load(location.href +  ' #mydiv');
                                                 
                                                
                                            });
                                        
                                    }
                                }
                            });
                        },
                    }
                });
              $('#svbtn').click(function (){
                    swalWithBootstrapButtons.fire(
                        {
                            title: 'Do you want to save the details ?',
                            type: 'warning',
                            showCancelButton: true,
                            scrollbarPadding: false,
                            confirmButtonText: 'Yes',
                            cancelButtonText: 'No',
                            reverseButtons: true,
                             width:460,
                            customClass: {'confirmButton': 'btn btn-green mx-2 px-3',  'cancelButton': 'btn btn-red mx-2 px-3'}
                        }
                    ).then(function(result) {
                        if (result.value) {
                            k.submitData();
                        }
                        // commented by me on 20/03/23
                        else if (result.dismiss === Swal.DismissReason.cancel) {
                            // commented by myself regards to retain last state 
                            // WithinStateGrid(enquiry_id)
                            
                        }
                    });
                });  
                $('#editEnable').click(function () { // Enable readonly columns
                // Assuming you want to make the third column editable (index 2)
                var columnIndexToEdit = 3;
            
               // Get all the rows in the jExcel table
                var rows = k.table.rows;
            
                // Loop through each row and remove the 'readonly' class from the cells in the specified column
                for (var i = 0; i < rows.length; i++) {
                    var cell = k.table.rows[i].cells[columnIndexToEdit];
                    cell.classList.remove('readonly');
                }
            
                // Now the cells in the third column should be editable
                });      
        });
        
    }
</script>
<script src="<?php echo base_url(); ?>assets/js/badmin/rolepermission.js"></script>