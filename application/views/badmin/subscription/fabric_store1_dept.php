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
                                        if(isset($ArrRoleInfo) && count($ArrRoleInfo)==1 && in_array("", $ArrRoleInfo)) { echo ''; } else {
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
                    <input type="hidden" id="proforma_id" value="<?php echo $proforma_id ;?>">
                    <input type="hidden" id="userid" value="">
                    <input type="hidden" id="editvariable" value="<?php  echo (isset($ArrRoleInfo) && count($ArrRoleInfo)==1 && (in_array("", $ArrRoleInfo))) ? '1' : '2';?>"> 
                    <input type="hidden" id="statuscond" value="<?php echo $Arrdeptwiseinfostatus;?>">
                </div>
        </section>     
        </section>
    </div>
    <?php $this->load->view(CNFCOMPANY . 'template/templatefooter'); ?>
    <div class="control-sidebar-bg"></div>
</div>
<?php $this->load->view(CNFCOMPANY . 'template/pagefooter'); ?>
<script>
    var subscriber_id = $("#subscriber_id").val();
    var proforma_id = $("#proforma_id").val();
    
    $('#backbtn').on('click', function() {

    let redirectpath = base_path + GlbBAdminFdr +  'msubscription/detviews/' + encodeURIComponent(base64_encode(subscriber_id)) + '/' + encodeURIComponent(base64_encode(proforma_id));
                 window.location.href = redirectpath;

});

</script>
<!--<script src="<?php echo base_url(); ?>assets/js/company/userpermission.js"></script>-->