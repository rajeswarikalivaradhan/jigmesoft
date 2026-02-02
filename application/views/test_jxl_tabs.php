<html>
<script src="https://bossanova.uk/jspreadsheet/v4/jexcel.js"></script>
<script src="https://jsuites.net/v4/jsuites.js"></script>
<link rel="stylesheet" href="https://jsuites.net/v4/jsuites.css" type="text/css" />
<link rel="stylesheet" href="https://bossanova.uk/jspreadsheet/v4/jexcel.css" type="text/css" />
 
<div id="spreadsheet"></div>
 
<input type="button" value="Add new tab" onclick="add()" style="width:150px;">
<input type="button" value="Download selected tab" onclick="download()" style="width:150px;">
<script src="<?php echo base_url();?>assets/plugins/jQuery/jquery-2.2.4.js?rn=<?php echo CNFJSCSSRANDNO?>"></script>
<script>
/**
 * Add new worksheet
 */
var add = function() {
    var sheets = [];
    //
    let ArrPoNO = ["PO - 001","PO - 002","PO - 003"];
    var url = $(location).attr('href'); var lasturlpart = url.substr(url.lastIndexOf('/') + 1);
    console.log(lasturlpart,'lasturlpart');
    let poNOPage = lasturlpart;
    var poNo = '';
    for(let ii = 0; ii < ArrPoNO.length; ii++) {
        console.log(ArrPoNO[ii],'poNOPage[ii]');
        poNo = ArrPoNO[ii];
    }
    console.log(poNo,'poNo');
    //
    sheets.push({
        sheetName: prompt('Create a new tab', 'New tab ' + poNo),
        minDimensions:[10,10]
    });
 
    jspreadsheet.tabs(document.getElementById('spreadsheet'), sheets);
}
 
/**
 * Download current worksheet
 */
var download = function() {
    // Get selected tab
    var worksheet = document.getElementById('spreadsheet').children[0].querySelector('.selected').getAttribute('data-spreadsheet');
    // Download
    document.getElementById('spreadsheet').jexcel[worksheet].download();
}
 
/**
 * Create worksheet container with two jexcel instances
 */
var sheets = [
    {
        sheetName: 'Countries',
        minDimensions:[10,10]
    },
    {
        sheetName: 'Cities',
        minDimensions:[10,10]
    }
];
 
jspreadsheet.tabs(document.getElementById('spreadsheet'), sheets);
</script>
</html>