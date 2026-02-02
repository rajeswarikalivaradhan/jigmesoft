

    $(document).ready(function() {

        var reqRequestJSON;
        $.when(getReqRequestList()).done(function(){
            dispDetails(reqRequestJSON);		
        });

        $(document).ajaxStart(function(a){
            $.LoadingOverlay("show",{image: "../assets/img/fullpage.gif"});
        });
        $(document).ajaxStop(function(){
            $.LoadingOverlay("hide");
        });

        function getReqRequestList()
        {
            return $.ajax({
                url: base_path+'company/Mstoreuser/getDClist',
                type:'POST',
                success:function(data){
                    reqRequestJSON = $.parseJSON(data);
                },		
                error: function() {
                    console.log("Error");  
                }
            });
        }

        function dispDetails(reqRequestJSON)
        {
            if ( $.fn.DataTable.isDataTable('#mCadQueueList') ) {
                $('#mCadQueueList').DataTable().destroy();
            }
            var i = 1;
            $('#mCadQueueList tbody').empty();	
            $("#mCadQueueList").dataTable({
                "aaData": reqRequestJSON,
                "aaSorting": [],
                "aoColumns": [		
                    { 
                        "mDataProp": function ( data, type, full, meta) {
                            return i++;
                        }
                    },
                    { "mDataProp": "isriorcode" },	
                    { "mDataProp": "brandname" },	
                    { "mDataProp": "ref_queue_no" },
                    {
                        "mDataProp": function ( data, type, full, meta) {
                            return '<span >BOM</span>';
                        }
                    },
                    // { "mDataProp": "mi_id" },
                    {
                        "mDataProp": function ( data, type, full, meta) {
                            return "M.I."+data.mi_ref_no;
                        }
                    },
                    {
                        "mDataProp": function ( data, type, full, meta) {
                            return data.req_date;
                        }
                    },
                    {
                        "mDataProp": function ( data, type, full, meta) {
                            return data.cutoff_date;
                        }
                    },
                    {
                        "mDataProp": function ( data, type, full, meta) {
                            return '<a class="bold" href="' + base_path +'request/Bomrequest/bomDCDetails/'+ encodeURIComponent(btoa(data.enquiry_id)) + '/reqId/' + encodeURIComponent(btoa(data.request_id)) + '/miId/' + encodeURIComponent(btoa(data.mi_id)) + '/dc/' + encodeURIComponent(btoa(data.dc_no)) + '">' + data.dc_no + '</a>';
                        }
                    },
                    {
                        "mDataProp": function ( data, type, full, meta) {
                            return data.dc_dt;
                        }
                    },
                    { "mDataProp": "bom_dept" },
                    { 
                        "mDataProp": function ( data, type, full, meta) {
                            var Status = ['PENDING', 'RECEIVED', 'DISCREPANCY'];
                            if(data.item_received_status == '0')
                            return '<span class="text-light knOrangeColor bg-dark"><strong>'+Status[data.item_received_status]+'</strong></span>';
                            if(data.item_received_status == '1')
                            return '<span class="text-light knGreenColor bg-dark"><strong>'+Status[data.item_received_status]+'</strong></span>';
                            else
                            return '<span class="text-light knRedColor bg-dark"><strong>'+Status[data.item_received_status]+'</strong></span>';
                        }
                    },								
                    {
                        "mDataProp": function ( data, type, full, meta) {
                            var d = new Date(data.logs); 
                            var time = d.toLocaleString('en-US', { hour: '2-digit', minute: '2-digit', hour12: true });
                            var dFormat = ("0" + (d.getDate())).slice(-2) + '/' + ("0" + (d.getMonth() + 1)).slice(-2) + '/' + d.getFullYear() + ' ' +time;
                            return dFormat;
                        }
                    },								
                    { 
                        "mDataProp": function ( data, type, full, meta) {
                            if(data.flag == "1")
                            return 'Active';
                            else if(data.flag == "2")
                            return 'Inactive';
                            else
                            return 'Active';
                        }
                    },							
                ]  						
            });
        }
    });