<?php $this->load->view(CNFCOMPANY.'template/pageheader');?>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.23/datatables.min.css"/>
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" />
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<body class="hold-transition layout-top-nav">
<div class="wrapper">
    <?php $this->load->view(CNFCOMPANY . 'template/templateheader'); ?>
    <?php $this->load->view(CNFCOMPANY . 'template/templatefooter'); ?>
<div class="control-sidebar-bg"></div>
</div>
<script src="<?php echo base_url();?>assets/js/datatables.min.js"></script>
<script type="text/javascript">
     window.addEventListener("pageshow", function (event) {
  if (event.persisted || performance.getEntriesByType("navigation")[0]?.type === "back_forward") {
    //fetchData(); // Replace with your real data-fetching logic
    location.reload(true);
  }
});
    
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
</script>
<?php $this->load->view(CNFCOMPANY . 'template/pagefooter'); ?>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
<script src="<?php echo base_url();?>assets/js/loadingoverlay.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/custom/work-in-process.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/custom/datepair.js"></script>
<script src="<?= base_url() ?>assets/ace/node_modules/sweetalert2/dist/sweetalert2.all.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/select2.min.js"></script>
<script>
    $('.date').datepicker({
    format: 'dd-mm-yyyy',
    autoclose: true
});
</script>
