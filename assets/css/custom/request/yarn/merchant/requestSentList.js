$(document).ready(function () {

    getPurchaseRequest();
    // *********************************************************************************************************************************** 
    // YARN REQUEST STARTS HERE 
    // ***********************************************************************************************************************************

    function getPurchaseRequest() {
        var data = new FormData();
        data.append('enquiry_id', enquiry_id);
        data.append('request_id', request_id);
        let request = $.ajax({
            type: "POST",
            url: base_path + 'request/Fabricrequest/getYarnReceivedDetails',
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                sample_requirement_data = JSON.parse(data);
                append_sample_request(sample_requirement_data);
            },
            error: function () {
                console.log("Error");
            }
        });
    }

    function append_sample_request(data) {
        $('#yarnRequest').html('');
        let list = {
            data: data.data,
            columns: data.column,
            minDimensions: [4, 1],
            allowDeleteColumn: false,
            allowInsertRow: false,
            allowInsertColumn: false
        };
        yarnRequest_vm = new Vue({
            el: '#yarnRequest',
            mounted: function () {
                let spreadsheet = jexcel(this.$el, list);
                Object.assign(this, spreadsheet);
            }
        });
    }
    
    // *********************************************************************************************************************************** 
    // YARN REQUEST ENDS HERE 
    // ***********************************************************************************************************************************

});