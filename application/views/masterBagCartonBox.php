<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->

    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/jexcel4/jsuites.css"/>
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/jexcel4/jexcel.css"/>
    <title>Hello, world!</title>
</head>
<body>
<h1>Master Bag & Carton Assortment Ratio</h1>
<div id="test"></div>

<!-- JavaScript -->
<script src="<?php echo base_url();?>assets/plugins/jQuery/jquery-2.2.4.js?rn=<?php echo CNFJSCSSRANDNO?>"></script>
<script src="<?php echo base_url();?>assets/plugins/jQueryUI/jquery-ui.js"></script>
<script src="<?php echo base_url();?>assets/js/ajax.js?rn=<?php echo CNFJSCSSRANDNO?>"></script>
<script src="<?php echo base_url();?>assets/js/commonfunctions.js?rn=<?php echo CNFJSCSSRANDNO?>"></script>
<script src="<?php echo base_url(); ?>assets/js/jexcel4/jsuites.js"></script>
<script src="<?php echo base_url(); ?>assets/js/jexcel4/jexcel.js"></script>

<script>
    let ArrPoNO = ["PO - 001","PO - 002","PO - 003"];
    testData = [];
    for(let ii = 0; ii < ArrPoNO.length; ii++) {
        console.log(ArrPoNO[ii],'ArrPoNO[ii]');
        var url = '<a href="'+base_path+"login/masterBagCartonBox/"+ArrPoNO[ii]+'">'+ArrPoNO[ii]+'</a';
        testData.push([url]);

    }

    fnFilter = function () {
        var url = $(location).attr('href'); var lasturlpart = url.substr(url.lastIndexOf('/') + 1);
        console.log(lasturlpart,'lasturlpart');
        let poNOPage = lasturlpart;
        var poNo = '';

        console.log(poNo,'poNo');
        return [poNo];
    };

    jexcel(document.getElementById("test"), {
        data: testData,
        //data: [['<a href="https://www.google.com/">TESTLINK</a>']],
        columns:[
            { title:'P.O. No', type:'html', width:100 },
        ]
    });


</script>

</body>
</html>