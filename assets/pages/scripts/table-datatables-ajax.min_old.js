var tbl_config1;
var tbl_config;
 var tbl_config4;
  var tbl_config3;
  var tbl_config_brand_opti;
  var loaderhtmldata = '<div id="loaderovrly"><div class="loader" style="display:block;"><center><div class="loading-image"></div></center></div><div class="loader-overlayer"></div></div>';
$(document).ready(function() {
    
    
    //FOR BRAND OPTIMIZATION ///////
    
    $('#date_filter_campaign_variants_brand_opti').val(moment().subtract("days", 29) + "###" + moment());

    tbl_config_brand_opti = {
        "processing": true,
        "serverSide": true,
        //"scrollX": true,
        "pageLength": 10,
        "buttons": [
            {
                extend: 'collection',
                className: 'btn-success',
                text: 'Export',
                buttons: ['pdfHtml5', 'csvHtml5', 'copyHtml5', 'excelHtml5']
            },
            {
				extend : "colvis",
				columns: ':not(:first-child)'
				}
        ],
        "dom": '<"top"fB>rt<"bottom"ilp>',
        "columnDefs": [
            {"orderable": false, "targets": 0},
            //{"orderable": false, "targets": 8},
        ],
        "order": [[6, "desc"]],
        "ajax": {
            "url": base_url + "admin/brandoptimization/ajaxTableDataVariants",
            "type": "POST",
            "data": function(d) {
                d.dayfilter = $('#date_filter_campaign_variants_brand_opti').val(),
                        d.customdate = $('#custom_date_variants').val(),
                        d.device = $('#show_device_variants_brand_opti').val();
                d.brand = $('#brand_select').val();
                d.creative_typ_ajx = $('#brand_creative_typ_hdn').val();
                d.creative_element_ajx = $('#brand_creative_element_hdn').val();
                d.creative_name_ajx = $('#brand_creative_name_hdn').val();
                d.creative_text_ajx = $('#brand_creative_text_hdn').val();
            }
        },
         "footerCallback": function( tfoot, data, start, end, display ) {
    var api = this.api();
   
   // Remove the formatting to get integer data for summation
            var intVal = function ( i ) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '')*1 :
                    typeof i === 'number' ?
                        i : 0;
            };
        
        // Total click this page
            clickTotal = api
                .column( 6, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
       
       // Total convert over this page
            clickConvert = api
                .column( 7, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
                
       // Update footer
            $( api.column( 6 ).footer() ).html(clickTotal);
            $( api.column( 7 ).footer() ).html(clickConvert);
            if(clickTotal!= 0 && clickConvert!= 0)
            {
                crc = (clickConvert/clickTotal)*100;
                $( api.column( 8 ).footer() ).html(crc.toPrecision(4)+"%");
            }else{
                $( api.column( 8 ).footer() ).html(0);
            }
            
             
            
   
  }
    };
    
    var brand_opti_tbl = $('#brandoptimization_datatable_variants').DataTable(tbl_config_brand_opti);
    
    //*  Custom Date Range  *//

    jQuery().daterangepicker && ($("#data-report-range-camp-variant-brand-opti").daterangepicker({
        ranges: {
            Today: [moment(), moment()], Yesterday: [moment().subtract("days", 1), moment().subtract("days", 1)], "Last 7 Days": [moment().subtract("days", 6), moment()], "Last 30 Days": [moment().subtract("days", 29), moment()], "This Month": [moment().startOf("month"), moment().endOf("month")], "Last Month": [moment().subtract("month", 1).startOf("month"), moment().subtract("month", 1).endOf("month")], "This Year": [moment().startOf("year"), moment().endOf("year")],"Last Year": [moment().subtract("year", 1).startOf("year"), moment().subtract("year", 1).endOf("year")],"All Time": [moment().subtract("year", 46).startOf("year"), moment().endOf("year")]
        }
        , locale: {
            format: "MM/DD/YYYY", separator: " - ", applyLabel: "Apply", cancelLabel: "Cancel", fromLabel: "From", toLabel: "To", customRangeLabel: "Custom", daysOfWeek: ["Su", "Mo", "Tu", "We", "Th", "Fr", "Sa"], monthNames: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"], firstDay: 1
        }
        , opens: App.isRTL() ? "right" : "left"
    }
    , function(e, t, a) {

          $('#date_filter_campaign_variants_brand_opti').val(e + "###" + t); //call datatable init
        $('#brandoptimization_datatable_variants').dataTable().fnDraw(tbl_config_brand_opti);
        
        "0" != $("#data-report-range-camp-variant-brand-opti").attr("data-display-range") && $("#data-report-range-camp-variant-brand-opti span").html(e.format("MMMM D, YYYY") + " - " + t.format("MMMM D, YYYY"))
    }

    ), "0" != $("#data-report-range-camp-variant-brand-opti").attr("data-display-range") && $("#data-report-range-camp-variant-brand-opti span").html(moment().subtract("days", 29).format("MMMM D, YYYY") + " - " + moment().format("MMMM D, YYYY")), $("#data-report-range-camp-variant-brand-opti").show())

//*  Custom Date Range  *//
     
    $('#show_device_variants_brand_opti').on('change', function(e) {
        $('#date_filter_campaign_variants_brand_opti').val(moment().subtract("days", 29) + "###" + moment());
       $('#brandoptimization_datatable_variants').dataTable().fnDraw(tbl_config_brand_opti);
    });
    
    $('#brand_select').on('change', function(e) {
        $('#date_filter_campaign_variants_brand_opti').val(moment().subtract("days", 29) + "###" + moment());
       $('#brandoptimization_datatable_variants').dataTable().fnDraw(tbl_config_brand_opti);
    });
    
    
    //* Custom Column Search Campaign Variant *//

    $('#creative_type_ajax_search_brnd').on('keyup change', function() {
        var id = $(this).val();
        $.ajax({
            type: "POST",
            url: base_url + "admin/brandoptimization/getCreativeType_forcolumn",
            data: {"id": id},
            success: function(response) {
                $('#brand_creative_typ_hdn').val(id);
                $('#brand_creative_element_hdn').val('');
                $('select#brand_creative_element_ajax_search').html(response);
                $('#date_filter_campaign_variants_brand_opti').val(moment().subtract("days", 29) + "###" + moment());
                $('#brandoptimization_datatable_variants').dataTable().fnDraw(tbl_config_brand_opti);
            }
        });

        //alert($(this).data('type'))
    });

    $('#brand_creative_element_ajax_search').on('keyup change', function() {
        var id = $(this).val();
        $('#brand_creative_element_hdn').val(id);
        $('#date_filter_campaign_variants_brand_opti').val(moment().subtract("days", 29) + "###" + moment());
       $('#brandoptimization_datatable_variants').dataTable().fnDraw(tbl_config_brand_opti);

        //alert($(this).data('type'))
    });

    $('#brand_creative_name_ajax_search').on('keyup change', function() {
        var id = $(this).val();
        $('#brand_creative_name_hdn').val(id);
        $('#date_filter_campaign_variants_brand_opti').val(moment().subtract("days", 29) + "###" + moment());
       $('#brandoptimization_datatable_variants').dataTable().fnDraw(tbl_config_brand_opti);

        //alert($(this).data('type'))
    });

    $('#brand_creative_text_ajax_search').on('keyup change', function() {
        var id = $(this).val();
        $('#brand_creative_text_hdn').val(id);
         $('#date_filter_campaign_variants_brand_opti').val(moment().subtract("days", 29) + "###" + moment());
       $('#brandoptimization_datatable_variants').dataTable().fnDraw(tbl_config_brand_opti);

        //alert($(this).data('type'))
    });


//* Custom Column Search Campaign Variant *//
    
    
    
    
    
    
    
   //FOR BRAND OPTIMIZATION /////////// 
    
    
    //FOR FINANCE OPTIMIZATION
    
     $('#date_filter_campaign_variants_finance').val(moment().subtract("days", 29) + "###" + moment());

    tbl_config_finace = {
        "processing": true,
        "serverSide": true,
        //"scrollX": true,
         "ordering": false,
        "pageLength": 10,
        "dom": '<"top">rt<"bottom">',
        "ajax": {
            "url": base_url + "admin/finance/ajaxTableDataVariants",
            "type": "POST",
            "data": function(d) {
                d.dayfilter = $('#date_filter_campaign_variants_finance').val(),
                        d.customdate = $('#custom_date_variants').val(),
                        d.device = $('#show_device_variants_finance').val();
                        d.brand = $('#brand_select').val();
                        d.source = $('#source_select').val();
                
            }
        }
    };
    
    var brand_opti_tbl = $('#finance_datatable_variants').DataTable(tbl_config_finace);
    
    //*  Custom Date Range  *//

    jQuery().daterangepicker && ($("#data-report-range-camp-variant-finance").daterangepicker({
        ranges: {
            Today: [moment(), moment()], Yesterday: [moment().subtract("days", 1), moment().subtract("days", 1)], "Last 7 Days": [moment().subtract("days", 6), moment()], "Last 30 Days": [moment().subtract("days", 29), moment()], "This Month": [moment().startOf("month"), moment().endOf("month")], "Last Month": [moment().subtract("month", 1).startOf("month"), moment().subtract("month", 1).endOf("month")], "This Year": [moment().startOf("year"), moment().endOf("year")],"Last Year": [moment().subtract("year", 1).startOf("year"), moment().subtract("year", 1).endOf("year")],"All Time": [moment().subtract("year", 46).startOf("year"), moment().endOf("year")]
        }
        , locale: {
            format: "MM/DD/YYYY", separator: " - ", applyLabel: "Apply", cancelLabel: "Cancel", fromLabel: "From", toLabel: "To", customRangeLabel: "Custom", daysOfWeek: ["Su", "Mo", "Tu", "We", "Th", "Fr", "Sa"], monthNames: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"], firstDay: 1
        }
        , opens: App.isRTL() ? "right" : "left"
    }
    , function(e, t, a) {

          $('#date_filter_campaign_variants_finance').val(e + "###" + t); //call datatable init
        $('#finance_datatable_variants').dataTable().fnDraw(tbl_config_finace);
        
        "0" != $("#data-report-range-camp-variant-finance").attr("data-display-range") && $("#data-report-range-camp-variant-finance span").html(e.format("MMMM D, YYYY") + " - " + t.format("MMMM D, YYYY"))
    }

    ), "0" != $("#data-report-range-camp-variant-finance").attr("data-display-range") && $("#data-report-range-camp-variant-finance span").html(moment().subtract("days", 29).format("MMMM D, YYYY") + " - " + moment().format("MMMM D, YYYY")), $("#data-report-range-camp-variant-finance").show())

//*  Custom Date Range  *//
     
    $('#show_device_variants_finance,#brand_select,#source_select').on('change', function(e) {
        $('#date_filter_campaign_variants_finance').val(moment().subtract("days", 29) + "###" + moment());
       $('#finance_datatable_variants').dataTable().fnDraw(tbl_config_finace);
    });
    
    
    
    
    //* Custom Column Search Campaign Variant *//

    $('#creative_type_ajax_search_brnd').on('keyup change', function() {
        var id = $(this).val();
        $.ajax({
            type: "POST",
            url: base_url + "admin/brandoptimization/getCreativeType_forcolumn",
            data: {"id": id},
            success: function(response) {
                $('#brand_creative_typ_hdn').val(id);
                $('#brand_creative_element_hdn').val('');
                $('select#brand_creative_element_ajax_search').html(response);
                $('#date_filter_campaign_variants_finance').val(moment().subtract("days", 29) + "###" + moment());
                $('#finance_datatable_variants').dataTable().fnDraw(tbl_config_finace);
            }
        });

        //alert($(this).data('type'))
    });

    $('#brand_creative_element_ajax_search').on('keyup change', function() {
        var id = $(this).val();
        $('#brand_creative_element_hdn').val(id);
        $('#date_filter_campaign_variants_finance').val(moment().subtract("days", 29) + "###" + moment());
       $('#finance_datatable_variants').dataTable().fnDraw(tbl_config_finace);

        //alert($(this).data('type'))
    });

    $('#brand_creative_name_ajax_search').on('keyup change', function() {
        var id = $(this).val();
        $('#brand_creative_name_hdn').val(id);
        $('#date_filter_campaign_variants_finance').val(moment().subtract("days", 29) + "###" + moment());
       $('#finance_datatable_variants').dataTable().fnDraw(tbl_config_finace);

        //alert($(this).data('type'))
    });

    $('#brand_creative_text_ajax_search').on('keyup change', function() {
        var id = $(this).val();
        $('#brand_creative_text_hdn').val(id);
         $('#date_filter_campaign_variants_finance').val(moment().subtract("days", 29) + "###" + moment());
       $('#finance_datatable_variants').dataTable().fnDraw(tbl_config_finace);

        //alert($(this).data('type'))
    });


//* Custom Column Search Campaign Variant *//
    
    
    

    // FOR FINANCE OPTIMIZATION
    
    
    
    
    
    
    

    $('#date_filter').val(moment().subtract("days", 29) + "###" + moment());
    // alert($('#date_filter').val());
    tbl_config = {
        "processing": true,
        "serverSide": true,
        "pageLength": 10,
        buttons: [
            {
                extend: 'collection',
                className: 'btn-success',
                text: 'Export',
                buttons: ['pdfHtml5', 'csvHtml5', 'copyHtml5', 'excelHtml5']
            }
        ],
        "dom": '<"top"fB>rt<"bottom"ilp>',
        "columnDefs": [
            {"orderable": false, "targets": 0},
            {"orderable": false, "targets": 1},
            {"orderable": false, "targets": 2},
            {"orderable": false, "targets": 3},
            {"orderable": false, "targets": 4},
            {"orderable": false, "targets": 5},
        ],
        "order": [[3, "desc"]],
        "ajax": {
            "url": base_url + "admin/brand/ajaxTableData",
            "type": "POST",
            "data": function(d) {
                d.dayfilter = $('#date_filter').val()
                d.ajax_column_data = $('#img_hdn_column_data').val()
                // d.customdate = $('#custom_date').val()
            }
        },
        "fnDrawCallback": function(oSettings) {
            $('.make-switch').bootstrapSwitch()
            $(".delete1").confirmation().on("confirmed.bs.confirmation", function() {
                delete_image($(this).attr('id'))
            });

            $("#bulk_delete").confirmation().on("confirmed.bs.confirmation", function() {
                bulk_delete()
            });
        }

    };



    var img_tbl = $('#datatable_ajax_2').DataTable(tbl_config);

    $('#date_filter').on('change', function(e) {
        var campaign = $('#date_filter').val();
        if (campaign == "custom_date") {
            $('.custom-date').css("display", "block");
        }
        else {
            $("#custom_date").val('');
            $('.custom-date').css("display", "none");
            $('#datatable_ajax_2').dataTable().fnDraw(tbl_config);
        }

    });

    //*  Custom Date Range  *//

    jQuery().daterangepicker && ($("#data-report-range").daterangepicker({
        ranges: {
            Today: [moment(), moment()], Yesterday: [moment().subtract("days", 1), moment().subtract("days", 1)], "Last 7 Days": [moment().subtract("days", 6), moment()], "Last 30 Days": [moment().subtract("days", 29), moment()], "This Month": [moment().startOf("month"), moment().endOf("month")], "Last Month": [moment().subtract("month", 1).startOf("month"), moment().subtract("month", 1).endOf("month")], "This Year": [moment().startOf("year"), moment().endOf("year")],"Last Year": [moment().subtract("year", 1).startOf("year"), moment().subtract("year", 1).endOf("year")],"All Time": [moment().subtract("year", 46).startOf("year"), moment().endOf("year")]
        }
        , locale: {
            format: "MM/DD/YYYY", separator: " - ", applyLabel: "Apply", cancelLabel: "Cancel", fromLabel: "From", toLabel: "To", customRangeLabel: "Custom", daysOfWeek: ["Su", "Mo", "Tu", "We", "Th", "Fr", "Sa"], monthNames: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"], firstDay: 1
        }
        , opens: App.isRTL() ? "right" : "left"
    }
    , function(e, t, a) {


        $('#date_filter').val(e + "###" + t); //call datatable init
        $('#datatable_ajax_2').dataTable().fnDraw(tbl_config);


        "0" != $("#data-report-range").attr("data-display-range") && $("#data-report-range span").html(e.format("MMMM D, YYYY") + " - " + t.format("MMMM D, YYYY"))
    }

    ), "0" != $("#data-report-range").attr("data-display-range") && $("#data-report-range span").html(moment().subtract("days", 29).format("MMMM D, YYYY") + " - " + moment().format("MMMM D, YYYY")), $("#data-report-range").show())

//*  Custom Date Range  *//


//* Custom Column Search *//

    $('.imguploader_table_ajax_filter').on('keyup change', function() {
        $('#img_hdn_column_data').val($(this).val())
        $('#datatable_ajax_2').dataTable().fnDraw(tbl_config);
    })


//* Custom Column Search *//



    $('#custom_date').datepicker({
        rtl: App.isRTL(),
        orientation: "left",
        autoclose: !0
    }).on('changeDate', function() {

        $('#datatable_ajax_2').dataTable().fnDraw(tbl_config);
    });

//    jQuery('body').on('click', '.delete1', function() {
//        if (confirm('Are you sure to want to delete')) {
//            var id = $(this).attr('id');
//
//            $.ajax({
//                type: "POST",
//                url: base_url + "admin/brand/image_delete",
//                data: {"id": id},
//                success: function(response) {
//                    $('#datatable_ajax_2').dataTable().fnDraw(tbl_config);
//                }
//            });
//        }
//        //$('#datatable_ajax_2').dataTable().fnDraw(tbl_config);
//    });

//    jQuery('body').on('click', '#bulk_delete', function () {
//        var checkbox = document.getElementsByTagName("input");
//        var counter = 0;
//        for (var i = 0; i < checkbox.length; i++)
//        {
//            if (checkbox[i].checked)
//            {
//                counter++;
//            }
//        }
//        if (counter > 0) {
//            if (confirm('Are you sure to want to delete')) {
//                var bulk = [];
//                i = 0;
//                $('.bulk_check_box1').each(function () {
//                    if (this.checked) {
//                        bulk[i] = this.id;
//                        i++;
//                    }
//
//                });
//                $.ajax({
//                    type: "POST",
//                    url: base_url + "admin/brand/delete_bulk",
//                    data: {"bulk_del": bulk},
//                    success: function (response) {
//                        $('#datatable_ajax_2').dataTable().fnDraw(tbl_config);
//                    }
//                });
//            }
//        }
//        else {
//            alert("Please select image");
//        }
//        //$('#datatable_ajax_2').dataTable().fnDraw(tbl_config);
//    });

    $('#select_all_existent').change(function() {
        var cells = img_tbl.cells( ).nodes();
        $(cells).find(':checkbox').prop('checked', $(this).is(':checked'));
    });

});

// Delete Single Image 

function delete_image(id)
{
    var id = id;
    $.ajax({
        type: "POST",
        url: base_url + "admin/brand/image_delete",
        data: {"id": id},
        success: function (response) {
            $('#date_filter').val(moment().subtract("days", 29) + "###" + moment());
            $('#datatable_ajax_2').dataTable().fnDraw(tbl_config);
        }
    });
}
// Delete Single Image

// Delete bulk image 

function bulk_delete() {
    var checkbox = document.getElementsByTagName("input");
    var counter = 0;
    for (var i = 0; i < checkbox.length; i++)
    {
        if (checkbox[i].checked)
        {
            counter++;
        }
    }
    if (counter > 0) {
        var bulk = [];
        i = 0;
        $('.bulk_check_box1').each(function() {
            if (this.checked) {
                bulk[i] = this.id;
                i++;
            }

        });
        $.ajax({
            type: "POST",
            url: base_url + "admin/brand/delete_bulk",
            data: {"bulk_del": bulk},
            success: function(response) {
                $('#date_filter').val(moment().subtract("days", 29) + "###" + moment());
                $('#datatable_ajax_2').dataTable().fnDraw(tbl_config);
            }
        });
    }
    else {
        swal({
            title: "Please Select Images !", text: "", type: "warning"
        })
    }
}
// Delete bulk image 

/* FOr All campaign*/

$(document).ready(function() {

    $('#date_filter_campaign').val(moment().subtract("days", 29) + "###" + moment());
    //alert($('#date_filter_campaign').val());

    tbl_config1 = {
        "processing": true,
        "serverSide": true,
        "pageLength": 10,
        buttons: [
            {
                extend: 'collection',
                className: 'btn-success',
                text: 'Export',
                buttons: ['pdfHtml5', 'csvHtml5', 'copyHtml5', 'excelHtml5']
            }
        ],
        "dom": '<"top"fB>rt<"bottom"ilp>',
        "aoColumnDefs": [{"bSortable": false, "aTargets": [0,3,8]}],
        "order": [[1, "desc"], [2, "desc"]],
        "ajax": {
            "url": base_url + "admin/optimization/ajaxTableDataCompaign",
            "type": "POST",
            "data": function(d) {
                d.dayfilter = $('#date_filter_campaign').val(),
                        d.status = $('#show_campaign').val(),
                d.campaign_name = $('#campaign_hdn_column_data').val(),
                d.device = $("#show_device").val(),
                d.brand = $('#brand_select_campaign').val(),
                d.source = $('#source_select_campaign').val()
            }
        },
        "fnDrawCallback": function(oSettings) {
            $('.make-switch-campaign').bootstrapSwitch()
            $('.make-switch-campaign-homepage').bootstrapSwitch()
            $(".duplicate_confirmation").on("click", function() {
                duplicate_campaign($(this).attr('id'))
            });

            $(".delete_campaign").confirmation().on("confirmed.bs.confirmation", function() {
                delete_campaign($(this).attr('id'))
            });

        },
         "footerCallback": function( tfoot, data, start, end, display ) {
    var api = this.api();
   
   // Remove the formatting to get integer data for summation
            var intVal = function ( i ) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '')*1 :
                    typeof i === 'number' ?
                        i : 0;
            };
        
        // Total click this page
            clickTotal = api
                .column( 4, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
       
       // Total convert over this page
            clickConvert = api
                .column( 5, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
                
       // Update footer
            $( api.column( 4 ).footer() ).html(clickTotal);
            $( api.column( 5 ).footer() ).html(clickConvert);
            if(clickTotal!= 0 && clickConvert!= 0)
            {
                crc = (clickConvert/clickTotal)*100;
                $( api.column( 6 ).footer() ).html(crc.toPrecision(4)+"%");
            }else{
                $( api.column( 6 ).footer() ).html(0);
            }
            
             
            
   
  }
    };

    /// Confirmation boxes ///
    $("#bulk_delete_campaign").confirmation().on("confirmed.bs.confirmation", function() {
                bulk_delete_campaign()
            });

//            $("#duplicate_bulk_campaign").confirmation().on("confirmed.bs.confirmation", function() {
//                duplicate_bulk_campaign()
//            });

            $("#active_bulk_campaign").on("click", function() {
                active_bulk_campaign()
            });

            $("#passive_bulk_campaign").on("click", function() {
                passive_bulk_campaign()
            });
     
    
    /// Confirmation Boxes //
    


    var img_tbl = $('#datatable_campaign').DataTable(tbl_config1);

    $('#select_all_existent').change(function() {
        var cells = img_tbl.cells( ).nodes();
        $(cells).find(':checkbox').prop('checked', $(this).is(':checked'));
    });
    
     $('#show_device').on('change', function(e) {
        $("#custom_date_campaign").val('');
         $('#date_filter_campaign').val(moment().subtract("days", 29) + "###" + moment());
        $('#datatable_campaign').dataTable().fnDraw(tbl_config1);
    });
    $('#date_filter_campaign').on('change', function(e) {
        var campaign = $('#date_filter_campaign').val();
        if (campaign == "custom_date") {
            $('.custom').css("display", "block");
        }
        else {
            $("#custom_date_campaign").val('');
            $('.custom').css("display", "none");
             $('#date_filter_campaign').val(moment().subtract("days", 29) + "###" + moment());
            $('#datatable_campaign').dataTable().fnDraw(tbl_config1);
        }

    });
    $('#show_campaign').on('change', function(e) {
        $("#custom_date_campaign").val('');
         $('#date_filter_campaign').val(moment().subtract("days", 29) + "###" + moment());
        $('#datatable_campaign').dataTable().fnDraw(tbl_config1);
    });
     $('#brand_select_campaign').on('change', function(e) {
        $("#custom_date_campaign").val('');
         $('#date_filter_campaign').val(moment().subtract("days", 29) + "###" + moment());
        $('#datatable_campaign').dataTable().fnDraw(tbl_config1);
    });
     $('#source_select_campaign').on('change', function(e) {
        $("#custom_date_campaign").val('');
         $('#date_filter_campaign').val(moment().subtract("days", 29) + "###" + moment());
        $('#datatable_campaign').dataTable().fnDraw(tbl_config1);
    });

    $('#custom_date_campaign').datepicker({
        rtl: App.isRTL(),
        orientation: "left",
        autoclose: !0
    }).on('changeDate', function() {
         $('#date_filter_campaign').val(moment().subtract("days", 29) + "###" + moment());
        $('#datatable_campaign').dataTable().fnDraw(tbl_config1);
    });


    //*  Custom Date Range  *//

    jQuery().daterangepicker && ($("#data-report-range-variant").daterangepicker({
        ranges: {
            Today: [moment(), moment()], Yesterday: [moment().subtract("days", 1), moment().subtract("days", 1)], "Last 7 Days": [moment().subtract("days", 6), moment()], "Last 30 Days": [moment().subtract("days", 29), moment()], "This Month": [moment().startOf("month"), moment().endOf("month")], "Last Month": [moment().subtract("month", 1).startOf("month"), moment().subtract("month", 1).endOf("month")], "This Year": [moment().startOf("year"), moment().endOf("year")],"Last Year": [moment().subtract("year", 1).startOf("year"), moment().subtract("year", 1).endOf("year")],"All Time": [moment().subtract("year", 46).startOf("year"), moment().endOf("year")]
        }
        , locale: {
            format: "MM/DD/YYYY", separator: " - ", applyLabel: "Apply", cancelLabel: "Cancel", fromLabel: "From", toLabel: "To", customRangeLabel: "Custom", daysOfWeek: ["Su", "Mo", "Tu", "We", "Th", "Fr", "Sa"], monthNames: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"], firstDay: 1
        }
        , opens: App.isRTL() ? "right" : "left"
    }
    , function(e, t, a) {


        $('#date_filter_campaign').val(e + "###" + t); //call datatable init
        $('#datatable_campaign').dataTable().fnDraw(tbl_config1);


        "0" != $("#data-report-range-variant").attr("data-display-range") && $("#data-report-range-variant span").html(e.format("MMMM D, YYYY") + " - " + t.format("MMMM D, YYYY"))
    }

    ), "0" != $("#data-report-range-variant").attr("data-display-range") && $("#data-report-range-variant span").html(moment().subtract("days", 29).format("MMMM D, YYYY") + " - " + moment().format("MMMM D, YYYY")), $("#data-report-range-variant").show())

//*  Custom Date Range  *//


//* Custom Column Search *//

    $('.campaign_table_ajax_filter').on('keyup change', function() {
        $('#campaign_hdn_column_data').val($(this).val())
        $('#date_filter_campaign').val(moment().subtract("days", 29) + "###" + moment());
        $('#datatable_campaign').dataTable().fnDraw(tbl_config1);
    })


//* Custom Column Search *//




    /* Delete Campaign Name */

//    jQuery('body').on('click', '.delete_campaign', function () {
//        if (confirm('Are you sure to want to delete')) {
//            var id = $(this).attr('id');
//
//            $.ajax({
//                type: "POST",
//                url: base_url + "admin/optimization/delete_campaign",
//                data: {"id": id},
//                success: function (response) {
//                    $('#datatable_campaign').dataTable().fnDraw(tbl_config1);
//                }
//            });
//        }
//        $('#datatable_campaign').dataTable().fnDraw(tbl_config1);
//    });

    /* Delete Campaign Name */

    /* Change Status of Campaign Name */

    $('#datatable_campaign').on('switchChange.bootstrapSwitch', '.make-switch-campaign', function(event, state) {
        //console.log(state); // true | false
       // $(this).bootstrapSwitch('toggleState', true, true);
        var target_id = $(this).attr('id');
        var n = target_id.lastIndexOf('_');
        var id = target_id.substring(n + 1);
        var state;
        $.ajax({
            type: "POST",
            url: base_url + "admin/optimization/changeStatus",
            data: {"id": id, "status": state},
            success: function(response) {
                
                if(response == 0){
                    $("#"+target_id).bootstrapSwitch('state', !state, true);
                    swal({
                        title: "Please Assign atleast one landing page !", text: "", type: "warning"
                    })
                }
                //$('#datatable_campaign').dataTable().fnDraw(tbl_config1);
            }
        });
    });

    $('#datatable_campaign').on('switchChange.bootstrapSwitch', '.make-switch-campaign-homepage', function(event, state) {
        var id = $(this).data('campaign_id');
        var target_id = $(this).attr('id');
        $.ajax({
            type: "POST",
            url: base_url + "admin/optimization/changeHome",
            data: {"id": id},
            success: function(response) {
               // alert(response);
               if(response == 0){
                    $("#"+target_id).bootstrapSwitch('state', !state, true);
                    swal({
                        title: "Please check the status of the Campaign !", text: "", type: "warning"
                    })
                }
                $('#date_filter_campaign').val(moment().subtract("days", 29) + "###" + moment());
                $('#datatable_campaign').dataTable().fnDraw(tbl_config1);
            }
        });
    });


  //  jQuery('body').on('click', '.make-switch-campaign', function() {

        //alert('');

//         alert($(".status").val());
//        if (confirm('Are you sure to want to change status')) {
//            var ids = $(this).attr('id');
//            //alert(ids);
//            var id = ids.split("_");
//            $.ajax({
//                type: "POST",
//                url: base_url + "admin/optimization/changeStatus",
//                data: {"id": id[0], "status": id[1]},
//                success: function (response) {
//                    $('#datatable_campaign').dataTable().fnDraw(tbl_config1);
//                }
//            });
//        }

  //  });

    /* Change Status of Campaign Name */

    /* Change home status of Campaign Name */

    jQuery('body').on('click', '.home', function() {
        if (confirm('Are you sure to want to change home status')) {
            var ids = $(this).attr('id');
            var id = ids.split("_");
            $.ajax({
                type: "POST",
                url: base_url + "admin/optimization/changeHome",
                data: {"id": id[0], "home": id[1]},
                success: function(response) {
                     $('#date_filter_campaign').val(moment().subtract("days", 29) + "###" + moment());
                    $('#datatable_campaign').dataTable().fnDraw(tbl_config1);
                }
            });
        }

    });

    /* Change home Status of Campaign Name */

    /* Duplicate Campaign Name */



    /* Duplicate Campaign Name */
    $('.btn-popups').on('click', function() {
        $("#campaign_name").val('');
    });

    /* Ajax for add new Campaign */
    //$("#campaign_modal_form_div").on("submit","#responsive", function(){
//    $("body").on("click", "#save_campaign", function() {
//        var name = $("#campaign_name").val();
//        var dataString = 'campaign_name=' + name;
//        if (name == '')
//        {
//            //alert("Please enter campaign name");
//            swal({
//            title: "Please enter campaign name !", text: "", type: "warning"
//            })
//        }
//        else
//        {
//// AJAX Code To Submit Form.
//            $.ajax({
//                type: "POST",
//                url: base_url + "admin/optimization/campaign_add",
//                data: dataString,
//                cache: false,
//                success: function(result) {
//                    $('.btn-closedata').click();
//                     $('#date_filter_campaign').val(moment().subtract("days", 29) + "###" + moment());
//                    $('#datatable_campaign').dataTable().fnDraw(tbl_config1);
//                }
//            });
//        }
//        return false;
//        
//    });

    /* Ajax for add new Campaign */

    /* Bulk delete Campaign */

//    jQuery('body').on('click', '#bulk_delete_campaign', function () {
//        var checkbox = $('.bulk_check_box1');//document.getElementsByTagName("input");
//        var counter = 0;
//        for (var i = 0; i < checkbox.length; i++)
//        {
//            if (checkbox[i].checked)
//            {
//                counter++;
//            }
//        }
//        if (counter > 0) {
//            if (confirm('Are you sure to want to delete')) {
//                var bulk = [];
//                i = 0;
//                $('.bulk_check_box1').each(function () {
//                    if (this.checked) {
//                        bulk[i] = this.id;
//                        i++;
//                    }
//
//                });
//                $.ajax({
//                    type: "POST",
//                    url: base_url + "admin/optimization/delete_bulk_campaign",
//                    data: {"bulk_del": bulk},
//                    success: function (response) {
//                        $('#datatable_campaign').dataTable().fnDraw(tbl_config1);
//                    }
//                });
//            }
//        }
//        else {
//            alert("Please select Campaign");
//        }
//        //$('#datatable_ajax_2').dataTable().fnDraw(tbl_config);
//    });

    /* Bulk delete Campaign */

    /* Bulk Active Campaign */

//    jQuery('body').on('click', '#active_bulk_campaign', function () {
//        var checkbox = document.getElementsByTagName("input");
//        var counter = 0;
//        var value = 'Active';
//
//        for (var i = 0; i < checkbox.length; i++)
//        {
//            if (checkbox[i].checked)
//            {
//                counter++;
//            }
//        }
//        if (counter > 0) {
//            if (confirm('Are you sure to want to change campaign status')) {
//                var bulk = [];
//                var active = [];
//                i = 0;
//                $('.bulk_check_box1').each(function () {
//                    if (this.checked) {
//                        bulk[i] = $(this).attr('id');
//                        i++;
//                    }
//
//                });
//                $.ajax({
//                    type: "POST",
//                    url: base_url + "admin/optimization/active_bulk_campaign",
//                    data: {"bulk_active": bulk, "status": value},
//                    success: function (response) {
//                        $('#datatable_campaign').dataTable().fnDraw(tbl_config1);
//                    }
//                });
//            }
//        }
//        else {
//            alert("Please select Campaign");
//        }
//        //$('#datatable_ajax_2').dataTable().fnDraw(tbl_config);
//    });

    /* Bulk Active Campaign */

    /* Bulk Passive Campaign */

//    jQuery('body').on('click', '#passive_bulk_campaign', function () {
//        var checkbox = document.getElementsByTagName("input");
//        var counter = 0;
//        var value = 'Passive';
//
//        for (var i = 0; i < checkbox.length; i++)
//        {
//            if (checkbox[i].checked)
//            {
//                counter++;
//            }
//        }
//        if (counter > 0) {
//            if (confirm('Are you sure to want to change campaign status')) {
//                var bulk = [];
//                var active = [];
//                i = 0;
//                $('.bulk_check_box1').each(function () {
//                    if (this.checked) {
//                        bulk[i] = $(this).attr('id');
//                        i++;
//                    }
//
//                });
//                $.ajax({
//                    type: "POST",
//                    url: base_url + "admin/optimization/active_bulk_campaign",
//                    data: {"bulk_active": bulk, "status": value},
//                    success: function (response) {
//                        $('#datatable_campaign').dataTable().fnDraw(tbl_config1);
//                    }
//                });
//            }
//        }
//        else {
//            alert("Please select Campaign");
//        }
//        //$('#datatable_ajax_2').dataTable().fnDraw(tbl_config);
//    });

    /* Bulk Passive Campaign */

    /* Bulk delete Campaign */

//    jQuery('body').on('click', '#duplicate_bulk_campaign', function () {
//        var checkbox = document.getElementsByTagName("input");
//        var counter = 0;
//        for (var i = 0; i < checkbox.length; i++)
//        {
//            if (checkbox[i].checked)
//            {
//                counter++;
//            }
//        }
//        if (counter > 0) {
//            if (confirm('Are you sure to want to duplicate campaign name')) {
//                var bulk = [];
//                i = 0;
//                $('.bulk_check_box1').each(function () {
//                    if (this.checked) {
//                        bulk[i] = this.id;
//                        i++;
//                    }
//
//                });
//                $.ajax({
//                    type: "POST",
//                    url: base_url + "admin/optimization/duplicate_bulk_campaign",
//                    data: {"bulk_dupl": bulk},
//                    success: function (response) {
//                        $('#datatable_campaign').dataTable().fnDraw(tbl_config1);
//                    }
//                });
//            }
//        }
//        else {
//            alert("Please select Campaign");
//        }
//        //$('#datatable_ajax_2').dataTable().fnDraw(tbl_config);
//    });

    /* Bulk delete Campaign */

});


/* function for duplicate campaign name */

function duplicate_campaign(id)
{
    var id = id;
    //alert(id);
    $.ajax({
        type: "POST",
        url: base_url + "admin/optimization/duplicateCampaign",
        data: {"id": id},
        success: function(response) {
            //console.log(response);
            var obj = jQuery.parseJSON(response);
            
            $('#campaign_name_edit').val(obj.name);
            $('#test_id_edit').val(obj.id);
//             $('#date_filter_campaign').val(moment().subtract("days", 29) + "###" + moment());
//            $('#datatable_campaign').dataTable().fnDraw(tbl_config1);
        }
    });

}
/* function for duplicate campaign name */

/* function for delete single campaign name */

function delete_campaign(id) {
    var id = id;
    $.ajax({
        type: "POST",
        url: base_url + "admin/optimization/delete_campaign",
        data: {"id": id},
        success: function(response) {
             $('#date_filter_campaign').val(moment().subtract("days", 29) + "###" + moment());
            $('#datatable_campaign').dataTable().fnDraw(tbl_config1);
        }
    });
}

/* function for delete single campaign name */

// Delete Bulk Campaign

function bulk_delete_campaign() {
    var checkbox = $('.bulk_check_box1');//document.getElementsByTagName("input");
    var counter = 0;
    for (var i = 0; i < checkbox.length; i++)
    {
        if (checkbox[i].checked)
        {
            counter++;
        }
    }
    if (counter > 0) {
        var bulk = [];
        i = 0;
        $('.bulk_check_box1').each(function() {
            if (this.checked) {
                bulk[i] = this.id;
                i++;
            }

        });
        $.ajax({
            type: "POST",
            url: base_url + "admin/optimization/delete_bulk_campaign",
            data: {"bulk_del": bulk},
            success: function(response) {
                 $('#date_filter_campaign').val(moment().subtract("days", 29) + "###" + moment());
                $('#datatable_campaign').dataTable().fnDraw(tbl_config1);
            }
        });
    }
    else {
        swal({
            title: "Please Select Campaign !", text: "", type: "warning"
        })
    }
}

// Duplicate bulk campaign

function duplicate_bulk_campaign() {
   // alert("amit");
    var checkbox = $('.bulk_check_box1');//document.getElementsByTagName("input");
    var counter = 0;
    for (var i = 0; i < checkbox.length; i++)
    {
        if (checkbox[i].checked)
        {
            counter++;
        }
    }
    if (counter > 0) {

        var bulk = [];
        i = 0;
        $('.bulk_check_box1').each(function() {
            if (this.checked) {
                bulk[i] = this.id;
                i++;
            }

        });
        
        $.ajax({
            type: "POST",
            url: base_url + "admin/optimization/duplicate_bulk_campaign",
            data: {"bulk_dupl": bulk},
            success: function(response) {
                //console.log(tbl_config1);
                //alert(tbl_config1);
                 $('#date_filter_campaign').val(moment().subtract("days", 29) + "###" + moment());
                $('#datatable_campaign').dataTable().fnDraw(tbl_config1);
            }
        });

    }
    else {
        swal({
            title: "Please Select Campaign !", text: "", type: "warning"
        })
    }
}
// Duplicate bulk campaign

// Active bulk campaign
function active_bulk_campaign() {
   // alert("dfadk");
    var checkbox = $('.bulk_check_box1');//document.getElementsByTagName("input");
    var counter = 0;
    var value = 'Active';

    for (var i = 0; i < checkbox.length; i++)
    {
        if (checkbox[i].checked)
        {
            counter++;
        }
    }
    if (counter > 0) {

        var bulk = [];
        var active = [];
        i = 0;
        $('.bulk_check_box1').each(function() {
            if (this.checked) {
                bulk[i] = $(this).attr('id');
                i++;
            }

        });
        $.ajax({
            type: "POST",
            url: base_url + "admin/optimization/active_bulk_campaign",
            data: {"bulk_active": bulk, "status": value},
            success: function(response) {
                 $('#date_filter_campaign').val(moment().subtract("days", 29) + "###" + moment());
                  swal({
                        title: "If you want to change status of test please assign atleast one landing page !", text: "", type: "warning"
                    })
                $('#datatable_campaign').dataTable().fnDraw(tbl_config1);
            }
        });

    }
    else {
        swal({
            title: "Please Select Campaign !", text: "", type: "warning"
        })
    }
}
// Active bulk campaign

// Passive bulk campaign
function passive_bulk_campaign() {
    var checkbox = $('.bulk_check_box1');//document.getElementsByTagName("input");
    var counter = 0;
    var value = 'Passive';

    for (var i = 0; i < checkbox.length; i++)
    {
        if (checkbox[i].checked)
        {
            counter++;
        }
    }
    if (counter > 0) {
        var bulk = [];
        var active = [];
        i = 0;
        $('.bulk_check_box1').each(function() {
            if (this.checked) {
                bulk[i] = $(this).attr('id');
                i++;
            }

        });
        $.ajax({
            type: "POST",
            url: base_url + "admin/optimization/active_bulk_campaign",
            data: {"bulk_active": bulk, "status": value},
            success: function(response) {
                 $('#date_filter_campaign').val(moment().subtract("days", 29) + "###" + moment());
                 swal({
                        title: "If you want to change status of test please assign atleast one landing page !", text: "", type: "warning"
                    })
                $('#datatable_campaign').dataTable().fnDraw(tbl_config1);
            }
        });
    }
    else {
        swal({
            title: "Please Select Campaign !", text: "", type: "warning"
        })
    }
}
// Passive bulk campaign

function add_new_campaign_edit()
{
    $('#date_filter_campaign').val(moment().subtract("days", 29) + "###" + moment());

    //return;
    var campaign_name = $("#campaign_name_edit").val();
    var test_id = $("#test_id_edit").val();   
    var dataString = 'campaign_name=' + campaign_name + '&id=' + test_id;


// AJAX Code To Submit Form.
    $.ajax({
        type: "POST",
        url: base_url + "admin/optimization/campaign_add",
        data: dataString,
        async: false,
        success: function(result) {
            //alert(result);
            $('.btn-closedata').click();
            if ($.trim(result) == 'success')
            {
                $('#date_filter_campaign').val(moment().subtract("days", 29) + "###" + moment());
                $('#datatable_campaign').dataTable().fnDraw(tbl_config1);
                $('#responsive_edit').trigger("reset");
            }
        }
    });

}

function add_new_campaign()
{
    $('#date_filter_campaign').val(moment().subtract("days", 29) + "###" + moment());

    //return;
    var campaign_name = $("#campaign_name").val();
    var test_id = $("#test_id").val();  
    //alert(test_id)
    var dataString = 'campaign_name=' + campaign_name + '&id=' + test_id;

    //alert(campaign_name);
// AJAX Code To Submit Form.
    $.ajax({
        type: "POST",
        url: base_url + "admin/optimization/campaign_add",
        data: dataString,
        async: false,
        success: function(result) {
            //alert(result);
            $('.btn-closedata').click();
            if ($.trim(result) == 'success')
            {
                $('#date_filter_campaign').val(moment().subtract("days", 29) + "###" + moment());
                $('#datatable_campaign').dataTable().fnDraw(tbl_config1);
                $('#responsive').trigger("reset");
            }
        }
    });

}

/* FOr variants campaign*/

$(document).ready(function() {

    $('#date_filter_campaign_variants').val(moment().subtract("days", 29) + "###" + moment());

    tbl_config2 = {
        "processing": true,
        "serverSide": true,
        //"scrollX": true,
        "pageLength": 10,
        "buttons": [
            {
                extend: 'collection',
                className: 'btn-success',
                text: 'Export',
                buttons: ['pdfHtml5', 'csvHtml5', 'copyHtml5', 'excelHtml5']
            },
            {
				extend : "colvis",
				columns: ':not(:first-child)'
				}
        ],
        "dom": '<"top"fB>rt<"bottom"ilp>',
        "columnDefs": [
            {"orderable": false, "targets": 0},
            {"orderable": false, "targets": 10},
        ],
        "order": [[1, "desc"]],
        "ajax": {
            "url": base_url + "admin/optimization/ajaxTableDataVariants",
            "type": "POST",
            "data": function(d) {
                d.dayfilter = $('#date_filter_campaign_variants').val(),
                        d.customdate = $('#custom_date_variants').val(),
                        d.status = $('#show_campaign_variants').val(),
                        d.test_campaign_id = $('#test_campaign_id').val();
                        d.device = $('#show_device_variants').val();
                d.creative_typ_ajx = $('#creative_typ_hdn').val();
                d.creative_element_ajx = $('#creative_element_hdn').val();
                d.creative_name_ajx = $('#creative_name_hdn').val();
                d.creative_text_ajx = $('#creative_text_hdn').val();
                d.brand = $('#brand_select_campaign_variant').val();
                d.source = $('#source_select_campaign_variant').val();
            }
        },
        "fnDrawCallback": function(oSettings) {

            //alert('dfsdfsd');
            $.fn.editable.defaults.mode = 'inline';
            $('.make-switch-campaign-variant').bootstrapSwitch()
            $('.editable_creative_text').editable({
                url: base_url + "admin/optimization/edit_creative_text",
                type: "text",
                data: {id: $(this).data('content_id'), content_type: 'creative_text'}
            });
            $('.editable_creative_name').editable({
                url: base_url + "admin/optimization/edit_creative_name",
                type: "text"});
            $(".delete_campaign_variants").confirmation().on("confirmed.bs.confirmation", function() {
                delete_campaign_variants($(this).attr('id'))
            });
            $(".delete_fromall_campaign").confirmation().on("confirmed.bs.confirmation", function() {
                delete_fromall_campaign($(this).attr('id'))
            });
            
        },
         "footerCallback": function( tfoot, data, start, end, display ) {
    var api = this.api();
   
   // Remove the formatting to get integer data for summation
            var intVal = function ( i ) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '')*1 :
                    typeof i === 'number' ?
                        i : 0;
            };
        
        // Total click this page
            clickTotal = api
                .column( 6, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
       
       // Total convert over this page
            clickConvert = api
                .column( 7, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
                
       // Update footer
            $( api.column( 6 ).footer() ).html(clickTotal);
            $( api.column( 7 ).footer() ).html(clickConvert);
            if(clickTotal!= 0 && clickConvert!= 0)
            {
                crc = (clickConvert/clickTotal)*100;
                $( api.column( 8 ).footer() ).html(crc.toPrecision(4)+"%");
            }else{
                $( api.column( 8 ).footer() ).html(0);
            }
            
             
            
   
  }
    };
    
    /// Confirmatiion Boxes ////
    
    $("#duplicate_bulk_campaign_variants").confirmation().on("confirmed.bs.confirmation", function() {
              duplicate_bulk_campaign_variants_function()
            });
     
    
            $("#bulk_delete_campaign_variants").confirmation().on("confirmed.bs.confirmation", function() {
                bulk_delete_campaign_variants()
            });
            $("#bulk_delete_from_campaign").confirmation().on("confirmed.bs.confirmation", function() {
                bulk_delete_from_campaign()
            });            

            $("#active_bulk_campaign_variants").on("click", function() {
                active_bulk_campaign_variants()
            });

            $("#passive_bulk_campaign_variants").on("click", function() {
                passive_bulk_campaign_variants()
            });
            
    /// Confirmation Boxes ////        

    var img_tbl = $('#datatable_variants').DataTable(tbl_config2);


    // Custom Active Passive ** Assign variant as well change status too  //

    $('#datatable_variants').on('switchChange.bootstrapSwitch', '.make-switch-campaign-variant', function(event, state) {
        //console.log(state); // true | false
        var campaign_id = $(this).data('campaign_id');
        var creative_id = $(this).data('creative_id');
        var variant_id = $(this).data('variant_id');
        var variant_type_id = $(this).data('variant_type_id');
        var assigned_creative_id = $('#act_' + creative_id).val();
        // alert(assigned_creative_id)
        var test_campaign_id = $('#test_campaign_id').val();
        $.ajax({
            type: "POST",
            url: base_url + "admin/optimization/assign_variant",
            data: {"campaign_id": campaign_id, "creative_id": creative_id, "variant_id": variant_id, "variant_type_id": variant_type_id, 'state': state, "assigned_creative_id": assigned_creative_id, "test_campaign_id": test_campaign_id},
            success: function(response) {

                $('#act_' + creative_id).val($.trim(response));
                //alert($('#act_'+creative_id).val())

                //$('#datatable_campaign').dataTable().fnDraw(tbl_config1);
            }
        });
    });

    // Custom Active Passive //


    $('#select_all_existent').change(function() {
        var cells = img_tbl.cells( ).nodes();
        $(cells).find(':checkbox').prop('checked', $(this).is(':checked'));
    });
    
     $('#show_device_variants').on('change', function(e) {
        $("#custom_date_campaign").val('');
        $('#date_filter_campaign_variants').val(moment().subtract("days", 29) + "###" + moment());
        $('#datatable_variants').dataTable().fnDraw(tbl_config2);
    });
    
     $('#brand_select_campaign_variant').on('change', function(e) {
        $("#custom_date_campaign").val('');
        $('#date_filter_campaign_variants').val(moment().subtract("days", 29) + "###" + moment());
        $('#datatable_variants').dataTable().fnDraw(tbl_config2);
    });
     $('#source_select_campaign_variant').on('change', function(e) {
        $("#custom_date_campaign").val('');
        $('#date_filter_campaign_variants').val(moment().subtract("days", 29) + "###" + moment());
        $('#datatable_variants').dataTable().fnDraw(tbl_config2);
    });

    $('#date_filter_campaign_variants').on('change', function(e) {
        var campaign = $('#date_filter_campaign_variants').val();
        if (campaign == "custom_date") {
            $('.custom-date-variants').css("display", "block");
        }
        else {
            $("#custom_date_campaign").val('');
            $('.custom-date-variants').css("display", "none");
            $('#datatable_variants').dataTable().fnDraw(tbl_config2);
        }

    });
    $('#show_campaign_variants').on('change', function(e) {
        $("#custom_date_campaign").val('');
        $('#date_filter_campaign_variants').val(moment().subtract("days", 29) + "###" + moment());
        $('#datatable_variants').dataTable().fnDraw(tbl_config2);
    });

    $('#custom_date_variants').datepicker({
        rtl: App.isRTL(),
        orientation: "left",
        autoclose: !0
    }).on('changeDate', function() {
        $('#date_filter_campaign_variants').val(moment().subtract("days", 29) + "###" + moment());
        $('#datatable_variants').dataTable().fnDraw(tbl_config2);
    });

    /* Delete Campaign Name */

//    jQuery('body').on('click', '.delete_campaign_variants', function() {
//        if (confirm('Are you sure to want to delete')) {
//            var id = $(this).attr('id');
//
//            $.ajax({
//                type: "POST",
//                url: base_url + "admin/optimization/delete_campaign_variants",
//                data: {"id": id, "test_campaign_id": $('#test_campaign_id').val()},
//                success: function(response) {
//                    $('#datatable_variants').dataTable().fnDraw(tbl_config2);
//                }
//            });
//        }
//
//    });

    /* Delete Campaign Name */

    /* Change Status of Campaign Name */

    jQuery('body').on('click', '.status_variants', function() {
        if (confirm('Are you sure to want to change status')) {
            var ids = $(this).attr('id');
            //alert(ids);
            var id = ids.split("_");
            $.ajax({
                type: "POST",
                url: base_url + "admin/optimization/changeStatusVariants",
                data: {"id": id[0], "status": id[1]},
                success: function(response) {
                    $('#date_filter_campaign_variants').val(moment().subtract("days", 29) + "###" + moment());
                    $('#datatable_variants').dataTable().fnDraw(tbl_config2);
                }
            });
        }

    });

    /* Change Status of Campaign Name */

    /* Duplicate Campaign Name */

//    jQuery('body').on('click', '.duplicate_variant', function() {
//        //var id = $(this).data('campaignname');
//        if (confirm('Are you sure to want to duplicate campaign name')) {
//            var id = $(this).attr('id');
//            $.ajax({
//                type: "POST",
//                url: base_url + "admin/optimization/duplicateCampaignVariants",
//                data: {"id": id},
//                success: function(response) {
//                    $('#date_filter_campaign_variants').val(moment().subtract("days", 29) + "###" + moment());
//                    $('#datatable_variants').dataTable().fnDraw(tbl_config2);
//                }
//            });
//        }
//    });

    /* Duplicate Campaign Name */
    $('.btn-popups').on('click', function() {
        $("#campaign_name").val('');
    });

    /* Bulk delete Campaign */

//    jQuery('body').on('click', '#bulk_delete_campaign_variants', function () {
//        var checkbox = document.getElementsByTagName("input");
//        var counter = 0;
//        for (var i = 0; i < checkbox.length; i++)
//        {
//            if (checkbox[i].checked)
//            {
//                counter++;
//            }
//        }
//        if (counter > 0) {
//            if (confirm('Are you sure to want to delete')) {
//                var bulk = [];
//                i = 0;
//                $('.bulk_check_box1').each(function () {
//                    if (this.checked) {
//                        bulk[i] = this.id;
//                        i++;
//                    }
//
//                });
//                $.ajax({
//                    type: "POST",
//                    url: base_url + "admin/optimization/bulk_delete_campaign_variants",
//                    data: {"bulk_del": bulk, "test_campaign_id": $('#test_campaign_id').val()},
//                    success: function (response) {
//                        $('#datatable_variants').dataTable().fnDraw(tbl_config2);
//                    }
//                });
//            }
//        }
//        else {
//            alert("Please select Campaign");
//        }
//        //$('#datatable_ajax_2').dataTable().fnDraw(tbl_config);
//    });

    /* Bulk delete Campaign */

    /* Bulk Active Campaign */
//
//    jQuery('body').on('click', '#active_bulk_campaign_variants', function () {
//        var checkbox = document.getElementsByTagName("input");
//        var counter = 0;
//        var value = $('#active_bulk_campaign').val();
//
//        for (var i = 0; i < checkbox.length; i++)
//        {
//            if (checkbox[i].checked)
//            {
//                counter++;
//            }
//        }
//        if (counter > 0) {
//            if (confirm('Are you sure to want to change campaign status')) {
//                var bulk = [];
//                var active = [];
//                i = 0;
//                $('.bulk_check_box1').each(function () {
//                    if (this.checked) {
//                        bulk[i] = $(this).attr('id');
//                        i++;
//                    }
//
//                });
//                $.ajax({
//                    type: "POST",
//                    url: base_url + "admin/optimization/active_bulk_campaign_variants",
//                    data: {"bulk_active": bulk, "status": value, "test_campaign_id": $('#test_campaign_id').val()},
//                    success: function (response) {
//                        $('#datatable_variants').dataTable().fnDraw(tbl_config2);
//                        $('#select_all_existent').prop('checked', false)
//                    }
//                });
//            }
//        }
//        else {
//            alert("Please select Campaign");
//        }
//        //$('#datatable_ajax_2').dataTable().fnDraw(tbl_config);
//    });

    /* Bulk Active Campaign */

    /* Bulk Passive Campaign */

//    jQuery('body').on('click', '#passive_bulk_campaign_variants', function () {
//
//        var checkbox = document.getElementsByTagName("input");
//        var counter = 0;
//        var value = $('#passive_bulk_campaign').val();
//
//        for (var i = 0; i < checkbox.length; i++)
//        {
//            if (checkbox[i].checked)
//            {
//                counter++;
//            }
//        }
//        if (counter > 0) {
//            if (confirm('Are you sure to want to change campaign status')) {
//                var bulk = [];
//                var active = [];
//                i = 0;
//                $('.bulk_check_box1').each(function () {
//                    if (this.checked) {
//                        bulk[i] = $(this).attr('id');
//                        i++;
//                    }
//
//                });
//                $.ajax({
//                    type: "POST",
//                    url: base_url + "admin/optimization/passive_bulk_campaign_variants",
//                    data: {"bulk_active": bulk, "status": value, "test_campaign_id": $('#test_campaign_id').val()},
//                    success: function (response) {
//                        $('#select_all_existent').prop('checked', false)
//                        $('#datatable_variants').dataTable().fnDraw(tbl_config2);
//                    }
//                });
//            }
//        }
//        else {
//            alert("Please select Campaign");
//        }
//        //$('#datatable_ajax_2').dataTable().fnDraw(tbl_config);
//    });

    /* Bulk Passive Campaign */

    /* Bulk delete Campaign */

//    jQuery('body').on('click', '#duplicate_bulk_campaign_variants', function () {
//        var checkbox = document.getElementsByTagName("input");
//        var counter = 0;
//        for (var i = 0; i < checkbox.length; i++)
//        {
//            if (checkbox[i].checked)
//            {
//                counter++;
//            }
//        }
//        if (counter > 0) {
//            if (confirm('Are you sure to want to duplicate campaign name')) {
//                var bulk = [];
//                i = 0;
//                $('.bulk_check_box1').each(function () {
//                    if (this.checked) {
//                        bulk[i] = this.id;
//                        i++;
//                    }
//
//                });
//                $.ajax({
//                    type: "POST",
//                    url: base_url + "admin/optimization/duplicate_bulk_campaign_variants",
//                    data: {"bulk_dupl": bulk},
//                    success: function (response) {
//                        $('#datatable_variants').dataTable().fnDraw(tbl_config2);
//                    }
//                });
//            }
//        }
//        else {
//            alert("Please select Campaign");
//        }
//        //$('#datatable_ajax_2').dataTable().fnDraw(tbl_config);
//    });

    /* Bulk delete Campaign */



    //*  Custom Date Range  *//

    jQuery().daterangepicker && ($("#data-report-range-camp-variant").daterangepicker({
        ranges: {
            Today: [moment(), moment()], Yesterday: [moment().subtract("days", 1), moment().subtract("days", 1)], "Last 7 Days": [moment().subtract("days", 6), moment()], "Last 30 Days": [moment().subtract("days", 29), moment()], "This Month": [moment().startOf("month"), moment().endOf("month")], "Last Month": [moment().subtract("month", 1).startOf("month"), moment().subtract("month", 1).endOf("month")], "This Year": [moment().startOf("year"), moment().endOf("year")],"Last Year": [moment().subtract("year", 1).startOf("year"), moment().subtract("year", 1).endOf("year")],"All Time": [moment().subtract("year", 46).startOf("year"), moment().endOf("year")]
        }
        , locale: {
            format: "MM/DD/YYYY", separator: " - ", applyLabel: "Apply", cancelLabel: "Cancel", fromLabel: "From", toLabel: "To", customRangeLabel: "Custom", daysOfWeek: ["Su", "Mo", "Tu", "We", "Th", "Fr", "Sa"], monthNames: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"], firstDay: 1
        }
        , opens: App.isRTL() ? "right" : "left"
    }
    , function(e, t, a) {


        $('#date_filter_campaign_variants').val(e + "###" + t); //call datatable init
        $('#datatable_variants').dataTable().fnDraw(tbl_config2);


        "0" != $("#data-report-range-camp-variant").attr("data-display-range") && $("#data-report-range-camp-variant span").html(e.format("MMMM D, YYYY") + " - " + t.format("MMMM D, YYYY"))
    }

    ), "0" != $("#data-report-range-camp-variant").attr("data-display-range") && $("#data-report-range-camp-variant span").html(moment().subtract("days", 29).format("MMMM D, YYYY") + " - " + moment().format("MMMM D, YYYY")), $("#data-report-range-camp-variant").show())

//*  Custom Date Range  *//



//* Custom Column Search Campaign Variant *//

    $('#creative_type_ajax_search').on('keyup change', function() {
        var id = $(this).val();
        $.ajax({
            type: "POST",
            url: base_url + "admin/optimization/getCreativeType_forcolumn",
            data: {"id": id},
            success: function(response) {
                $('#creative_typ_hdn').val(id);
                $('#creative_element_hdn').val('');
                $('select#creative_element_ajax_search').html(response);
                $('#date_filter_campaign_variants').val(moment().subtract("days", 29) + "###" + moment());
                $('#datatable_variants').dataTable().fnDraw(tbl_config2);
            }
        });

        //alert($(this).data('type'))
    });

    $('#creative_element_ajax_search').on('keyup change', function() {
        var id = $(this).val();
        $('#creative_element_hdn').val(id);
        $('#date_filter_campaign_variants').val(moment().subtract("days", 29) + "###" + moment());
        $('#datatable_variants').dataTable().fnDraw(tbl_config2);

        //alert($(this).data('type'))
    });

    $('#creative_name_ajax_search').on('keyup change', function() {
        var id = $(this).val();
        $('#creative_name_hdn').val(id);
        $('#date_filter_campaign_variants').val(moment().subtract("days", 29) + "###" + moment());
        $('#datatable_variants').dataTable().fnDraw(tbl_config2);

        //alert($(this).data('type'))
    });

    $('#creative_text_ajax_search').on('keyup change', function() {
        var id = $(this).val();
        $('#creative_text_hdn').val(id);
        $('#date_filter_campaign_variants').val(moment().subtract("days", 29) + "###" + moment());
        $('#datatable_variants').dataTable().fnDraw(tbl_config2);

        //alert($(this).data('type'))
    });


//* Custom Column Search Campaign Variant *//




    /* On change  creative type */

    jQuery('body').on('change', '#creative_type', function() {
        var id = $(this).val();
        //alert(id);
        if (id == '8') {
           // alert("ram");
            $('.creativetext').css("display", "none");
            $('.textinfo').css("display", "none");
            $("#creative_text").val('NULL');
        }
        else {
            
            $('.creativetext').css("display", "block");
            $('#css_buttons').css("display", "none");
            $("#creative_text").val('');
            $('#style_css').val("");
            $('#responsive_css').val("");
             $('.textinfo').css("display", "none");
        }
        $.ajax({
            type: "POST",
            url: base_url + "admin/optimization/getCreativeType",
            data: {"id": id},
            success: function(response) {
                $('.creative').css("display", "block");
                $('.variants_types').html(response);
            }
        });
    });
    
    jQuery('body').on('change', '#variant_type', function() {
        var id = $(this).val();
        var creative_type = $("#creative_type").val();
        //alert(creative_type);
        if ($.trim(id) == '0')
        {
            $('#css_buttons').css("display", "none")
            $('#style_css').val("")
            $('#responsive_css').val("")
            $('.textinfo').css("display", "none");
            //return;
        }
        if($.trim(creative_type) != '8'){
             $('#css_buttons').css("display", "none")
            $('#style_css').val("")
            $('#responsive_css').val("")
           // $('.textinfo').css("display", "none");
            //return;
        }
        
        if ($.trim(id) == '23') {
            $('.textinfo').css("display", "block");
            //$("#creative_text").val('NULL');
        }
        if($.trim(id)=='22'){
              $('.textinfo').css("display", "none");
        }
        if($.trim(creative_type) == '8'){
          
        $.ajax({
            type: "POST",
            url: base_url + "admin/optimization/getLayoutCss",
            data: {"id": id},
            success: function(response) {
                var obj = jQuery.parseJSON(response);
                $('#style_css').val(obj.style)
                $('#responsive_css').val(obj.responsive)

                $('#css_buttons').css("display", "block");
            }
        });
        }
    });
    
    
      jQuery('body').on('change', '#variant_type_edit', function() {
        var id = $(this).val();

        if ($.trim(id) == '0')
        {
            $('#css_buttons_edit').css("display", "none")
            $('#style_css_edit').val("")
            $('#responsive_css_edit').val("")
            $('.textinfoedit').css("display", "none");
            return;
        }
        
        if ($.trim(id) == '23') {
            $('.textinfoedit').css("display", "block");
            //$("#creative_text").val('NULL');
        }
        if($.trim(id)=='22'){
              $('.textinfoedit').css("display", "none");
        }


        $.ajax({
            type: "POST",
            url: base_url + "admin/optimization/getLayoutCss",
            data: {"id": id},
            success: function(response) {
                var obj = jQuery.parseJSON(response);
                $('#style_css_edit').val(obj.style)
                $('#responsive_css_edit').val(obj.responsive)

                $('#css_buttons_edit').css("display", "block");
            }
        });
    });


    /* On change creative type */
});


// Duplicate Campain variant

function duplicate_variant(id){
    var id = id;
            $.ajax({
                type: "POST",
                url: base_url + "admin/optimization/duplicateCampaignVariants",
                data: {"id": id},
                success: function(response) {
                    //console.log(response);
//                    $('#date_filter_campaign_variants').val(moment().subtract("days", 29) + "###" + moment());
//                    $('#datatable_variants').dataTable().fnDraw(tbl_config2);
                        var obj = jQuery.parseJSON(response);
                        $('.creative_variant_type_edit').html(obj.select1);
                        $('.variants_types_edit').html(obj.select2);
                        $("#creative_type_name_edit").val(obj.creative_name);
                        $("#creative_text_edit").val(obj.creative_text);
                        $("#edit_id").val(obj.id);
                        
                        //alert(obj.textinfo);
                        if($.trim(obj.textinfo)!=''){
                            
                           $('#text_info_edit').val(obj.textinfo);
                             $('.textinfoedit').css("display", "block");
                        }
                        if($.trim(obj.style_css)!=''){
                            
                            $('#style_css').val(obj.style_css);
                            $('#responsive_css_edit').val(obj.responsive_css);
                            $('.textinfoedit').val('');
                             $('#creative_text_edit').val('');
                            $('.textinfoedit').css("display", "none");
                            
                            $('.creativetextedit').css("display", "none");
                            $('.creativetextcss').css("display", "block");
                        }
                }
            });
}
// Duplicate Campain variant

// Delete Single Campain variant

function delete_campaign_variants(id){
    var id = id;

            $.ajax({
                type: "POST",
                url: base_url + "admin/optimization/delete_campaign_variants",
                data: {"id": id, "test_campaign_id": $('#test_campaign_id').val()},
                success: function(response) {
                    $('#date_filter_campaign_variants').val(moment().subtract("days", 29) + "###" + moment());
                    $('#datatable_variants').dataTable().fnDraw(tbl_config2);
                }
            });
}
// Delete Single Campain variant
// Delete from Campain variant

function delete_fromall_campaign(id){
    var id = id;

            $.ajax({
                type: "POST",
                url: base_url + "admin/optimization/delete_fromall_campaign",
                data: {"id": id, "test_campaign_id": $('#test_campaign_id').val()},
                success: function(response) {
                    $('#date_filter_campaign_variants').val(moment().subtract("days", 29) + "###" + moment());
                    $('#datatable_variants').dataTable().fnDraw(tbl_config2);
                }
            });
}
// Delete from Campain variant
 
// Bulk delete campaign variants

function bulk_delete_campaign_variants() {
    var checkbox = $('.bulk_check_box1');//document.getElementsByTagName("input");
    var counter = 0;
    for (var i = 0; i < checkbox.length; i++)
    {
        if (checkbox[i].checked)
        {
            counter++;
        }
    }
    if (counter > 0) {
        var bulk = [];
        i = 0;
        $('.bulk_check_box1').each(function() {
            if (this.checked) {
                bulk[i] = this.id;
                i++;
            }

        });
        $.ajax({
            type: "POST",
            url: base_url + "admin/optimization/bulk_delete_campaign_variants",
            data: {"bulk_del": bulk, "test_campaign_id": $('#test_campaign_id').val()},
            success: function(response) {
                $('#date_filter_campaign_variants').val(moment().subtract("days", 29) + "###" + moment());
                $('#datatable_variants').dataTable().fnDraw(tbl_config2);
            }
        });
    }
    else {
        swal({
            title: "Please Select Campaign !", text: "", type: "warning"
        })
    }
}

// Bulk delete from  campaign 

// Bulk delete from all campaign 

function bulk_delete_from_campaign() {
    var checkbox = $('.bulk_check_box1');//document.getElementsByTagName("input");
    var counter = 0;
    for (var i = 0; i < checkbox.length; i++)
    {
        if (checkbox[i].checked)
        {
            counter++;
        }
    }
    if (counter > 0) {
        var bulk = [];
        i = 0;
        $('.bulk_check_box1').each(function() {
            if (this.checked) {
                bulk[i] = this.id;
                i++;
            }

        });
        $.ajax({
            type: "POST",
            url: base_url + "admin/optimization/bulk_delete_from_campaign",
            data: {"bulk_del": bulk, "test_campaign_id": $('#test_campaign_id').val()},
            success: function(response) {
                $('#date_filter_campaign_variants').val(moment().subtract("days", 29) + "###" + moment());
                $('#datatable_variants').dataTable().fnDraw(tbl_config2);
            }
        });
    }
    else {
        swal({
            title: "Please Select Campaign !", text: "", type: "warning"
        })
    }
}
//delete from all campaign 

// Duplicate bulk campaign variants

function duplicate_bulk_campaign_variants_function() {
   // alert('amit')
    var checkbox = $('.bulk_check_box1');//document.getElementsByTagName("input");
    var counter = 0;
    for (var i = 0; i < checkbox.length; i++)
    {
        if (checkbox[i].checked)
        {
            counter++;
        }
    }
   // alert(counter);
    if (counter > 0) {

        var bulk = [];
        i = 0;
        $('.bulk_check_box1').each(function() {
            if (this.checked) {
                bulk[i] = this.id;
                i++;
            }

        });
        $.ajax({
            type: "POST",
            url: base_url + "admin/optimization/duplicate_bulk_campaign_variants",
            data: {"bulk_dupl": bulk},
            success: function(response) {
                $('#date_filter_campaign_variants').val(moment().subtract("days", 29) + "###" + moment());
                $('#datatable_variants').dataTable().fnDraw(tbl_config2);
            }
        });
    }
    else {
        swal({
            title: "Please Select Campaign !", text: "", type: "warning"
        })
    }
}
// Duplicate bulk campaign variants

// Active bulk campaign variants
function active_bulk_campaign_variants() {
    var checkbox = $('.bulk_check_box1');//document.getElementsByTagName("input");
    var counter = 0;
    var value = $('#active_bulk_campaign').val();

    for (var i = 0; i < checkbox.length; i++)
    {
        if (checkbox[i].checked)
        {
            counter++;
        }
    }
    
    if (counter > 0) {
        var bulk = [];
        var active = [];
        i = 0;
        $('.bulk_check_box1').each(function() {
            if (this.checked) {
                bulk[i] = $(this).attr('id');
                i++;
            }

        });
        $.ajax({
            type: "POST",
            url: base_url + "admin/optimization/active_bulk_campaign_variants",
            data: {"bulk_active": bulk, "status": value, "test_campaign_id": $('#test_campaign_id').val()},
            success: function(response) {
                $('#date_filter_campaign_variants').val(moment().subtract("days", 29) + "###" + moment());
                $('#datatable_variants').dataTable().fnDraw(tbl_config2);
                $('#select_all_existent').prop('checked', false)
            }
        });
    }
    else {
        swal({
            title: "Please Select Campaign !", text: "", type: "warning"
        })
    }
}
// Active bulk campaign variants

// Passive bulk campaign variants
function passive_bulk_campaign_variants() {
    var checkbox = $('.bulk_check_box1');//document.getElementsByTagName("input");
    var counter = 0;
    var value = $('#passive_bulk_campaign').val();

    for (var i = 0; i < checkbox.length; i++)
    {
        if (checkbox[i].checked)
        {
            counter++;
        }
    }
    if (counter > 0) {
        var bulk = [];
        var active = [];
        i = 0;
        $('.bulk_check_box1').each(function() {
            if (this.checked) {
                bulk[i] = $(this).attr('id');
                i++;
            }

        });
        $.ajax({
            type: "POST",
            url: base_url + "admin/optimization/passive_bulk_campaign_variants",
            data: {"bulk_active": bulk, "status": value, "test_campaign_id": $('#test_campaign_id').val()},
            success: function(response) {
                $('#select_all_existent').prop('checked', false);
                $('#date_filter_campaign_variants').val(moment().subtract("days", 29) + "###" + moment());
                $('#datatable_variants').dataTable().fnDraw(tbl_config2);
            }
        });
    }
    else {
        swal({
            title: "Please Select Campaign !", text: "", type: "warning"
        })
    }
}
// Passive bulk campaign variants

$('#filter').on('click', function(e) {
    e.preventDefault();
    var startDate = $('#start').val(),
            endDate = $('#end').val();

    filterByDate(1, startDate, endDate); // We call our filter function

    $tableSel.dataTable().fnDraw(); // Manually redraw the table after filtering
});



function add_new_creative()
{
    $('#date_filter_campaign_variants').val(moment().subtract("days", 29) + "###" + moment());

    //return;
    var creative_type = $("#creative_type").val();
    var variant_type = $("#variant_type").val();
    var creative_type_name = $("#creative_type_name").val();
    var creative_text = $("#creative_text").val();
    var style_css = $('#style_css').val();
    var responsive_css = $('#responsive_css').val();
    var textinfo = $('#text_info').val();
    var dataString = 'creative_type=' + creative_type + '&variant_type=' + variant_type + '&creative_type_name=' + creative_type_name + '&creative_text=' + creative_text + '&responsive_css=' + responsive_css + '&style_css=' + style_css + '&textinfo='+ textinfo;


// AJAX Code To Submit Form.
    $.ajax({
        type: "POST",
        url: base_url + "admin/optimization/campaign_variants_add",
        data: dataString,
        async: false,
        success: function(result) {
            $('.btn-closedata').click();
            if ($.trim(result) == 'success')
            {
                $('#date_filter_campaign_variants').val(moment().subtract("days", 29) + "###" + moment());
                $('#datatable_variants').dataTable().fnDraw(tbl_config2);
                $('#variants_add').trigger("reset");
            }
        }
    });

}

function edit_new_creative()
{
    $('#date_filter_campaign_variants').val(moment().subtract("days", 29) + "###" + moment());

    //return;
    var creative_type = $("#duplicate_creative_type").val();
    var variant_type = $("#duplicate_variant_type").val();
    var creative_type_name = $("#duplicate_creative_type_name").val();
    var creative_text = $("#duplicate_creative_text").val();
    var style_css = $('#duplicate_style_css_edit').val();
    var responsive_css = $('#duplicate_responsive_css_edit').val();
    var textinfo = $('#duplicate_text_info').val();
    //alert(textinfo); 
    var dataString = 'creative_type=' + creative_type + '&variant_type=' + variant_type + '&creative_type_name=' + creative_type_name + '&creative_text=' + creative_text + '&responsive_css=' + responsive_css + '&style_css=' + style_css + '&textinfo='+ textinfo;


// AJAX Code To Submit Form.
    $.ajax({
        type: "POST",
        url: base_url + "admin/optimization/campaign_variants_add",
        data: dataString,
        async: false,
        success: function(result) {
            $('.btn-closedata').click();
            if ($.trim(result) == 'success')
            {
                $('#date_filter_campaign_variants').val(moment().subtract("days", 29) + "###" + moment());
                $('#datatable_variants').dataTable().fnDraw(tbl_config2);
                
            }
        }
    });

}


$(document).ready(function() {
    var $modal = $('#load_popup_modal_show_id');
    jQuery('body').on('click', '.click_to_load_modal_popup', function() {
        $modal.load(base_url + "admin/optimization/css_modal_forms", {
            'id': $(this).data('content_id'),
            'type': $(this).data('css_type')
        },
        function() {
            $modal.modal('show');
        });
    });
    
    var $duplicate_modal = $('#load_popup_modal_show_duplicate');
    jQuery('body').on('click', '.click_to_load_duplicate_modal_popup', function() {
        $duplicate_modal.load(base_url + "admin/optimization/duplicate_modal_forms", {
            'id': $(this).data('content_id')
        },
        function() {
            $duplicate_modal.modal('show');
        });
    });
});


/* FOr All Main DB*/

$(document).ready(function() {

    $('#date_filter_main_db').val(moment().subtract("days", 29) + "###" + moment());
    //alert($('#date_filter_campaign').val());

    tbl_config3 = {
        "processing": true,
        "serverSide": true,
        "pageLength": 10,
        buttons: [
            {
                extend: 'collection',
                className: 'btn-success',
                text: 'Export',
                buttons: ['pdfHtml5', 'csvHtml5', 'copyHtml5', 'excelHtml5']
            },{
				extend : "colvis",
				columns: ':not(:first-child)'
				}
        ],
        "dom": '<"top"fB>rt<"bottom"ilp>',
        "columnDefs": [
            {"orderable": false, "targets": 0},
            {"orderable": false, "targets": 4},
            {"visible": false, "targets": 1},
            {"visible": false, "targets": 5},
            {"visible": false, "targets": 8},
            {"visible": false, "targets": 9},
            {"visible": false, "targets": 11},{"visible": false, "targets": 13},
            {"visible": false, "targets": 14},
            {"visible": false, "targets": 15},{"visible": false, "targets": 16},
            {"visible": false, "targets": 17},
            {"visible": false, "targets": 18},
            {"visible": false, "targets": 19},
           
            {"visible": false, "targets": 21},{"visible": false, "targets": 22},{"visible": false, "targets": 23},
            {"visible": false, "targets": 24}, {"visible": false, "targets": 27},{"visible": false, "targets": 28},
            {"visible": false, "targets": 29}, {"visible": false, "targets": 30}, {"visible": false, "targets": 31}, {"visible": false, "targets": 32}, {"visible": false, "targets": 33},
            {"visible": false, "targets": 34}, {"visible": false, "targets": 35}, {"visible": false, "targets": 36}, {"visible": false, "targets": 37}, {"visible": false, "targets": 38},
            {"visible": false, "targets": 39}, {"visible": false, "targets": 40}, {"visible": false, "targets": 41}, {"visible": false, "targets": 42},
            {"visible": false, "targets": 43}, {"visible": false, "targets": 44}, {"visible": false, "targets": 45}, {"visible": false, "targets": 46}, {"visible": false, "targets": 47},
            {"visible": false, "targets": 48}, {"visible": false, "targets": 49}, {"visible": false, "targets": 50}, {"visible": false, "targets": 51}, {"visible": false, "targets": 52},
            {"visible": false, "targets": 53}, {"visible": false, "targets": 54}, {"visible": false, "targets": 55}, {"visible": false, "targets": 56},{"visible": false, "targets": 57},
            {"visible": false, "targets": 58},{"visible": false, "targets": 59},
        ],
        "order": [[1, "desc"]],
        "ajax": {
            "url": base_url + "admin/network/ajaxTableDataMainDB",
            "type": "POST",
            "data": function(d) {
                d.dayfilter = $('#date_filter_main_db').val()
                d.main_dbid = $('#main_db_id_ajax_search').val()
                d.main_dbsold = $('#main_db_soldid_ajax_search').val()
                d.network_name = $('#main_db_network_ajax_search').val()
                d.device = $('#show_device_maindb').val()
                d.brand = $('#brand_select_maindb').val()
                d.source = $('#source_select_maindb').val()
                d.error = $('#main_db_error_ajax_search').val()
                d.keyword = $('#main_db_keyword_ajax_search').val()
                d.source = $('#main_db_source_ajax_search').val()
                d.brand = $('#main_db_brand_ajax_search').val()
                d.brand_name = $('#brand_select_maindb').val()
                d.source_name = $('#source_select_maindb').val()
                d.status_maindb = $('#status_maindb').val()
                //d.device = $('#main_db_device_ajax_search').val()
                d.matchtype = $("#main_db_match_type_ajax_search").val()
                d.adposition = $("#main_db_adposition_ajax_search").val()
                d.devicemodel = $("#main_db_device_model_ajax_search").val()
                d.ip = $('#main_db_ip_ajax_search').val()
                d.cover_for = $('#main_db_cover_for_ajax_search').val()
                d.type = $("#main_db_type_ajax_search").val()
                d.cover_amount = $("#main_db_amount_ajax_search").val()
                d.cover_length = $("#main_db_length_ajax_search").val()
                d.first_name = $("#main_db_first_name_ajax_search").val()
                d.last_name = $("#main_db_last_name_ajax_search").val()
                d.address = $("#main_db_address_ajax_search").val()
                d.postcode = $("#main_db_postcode_ajax_search").val()
                d.dob = $("#main_db_dob_ajax_search").val()
                d.smoke = $("#main_db_smoke_ajax_search").val()
                d.phone_no = $("#main_db_phone_no_ajax_search").val()
                d.email = $("#main_db_email_ajax_search").val()
                
                d.lp_id = $("#main_db_lp_id_ajax_search").val()
                d.main_headline = $("#main_db_main_headline_ajax_search").val()
                d.main_sub_heading = $("#main_db_main_sub_headline_ajax_search").val()
                d.form_headline = $("#main_db_form_headline_ajax_search").val()
                d.form_sub_headline = $("#main_db_form_sub_headline_ajax_search").val()
                d.step_headline = $("#main_db_step_headline_ajax_search").val()
                d.step1_maintext = $("#main_db_step1_maintext_ajax_search").val()
                d.step1_subtext = $("#main_db_step1_subtext_ajax_search").val()
                d.step2_maintext = $("#main_db_step2_maintext_ajax_search").val()
                d.step2_subtext = $("#main_db_step2_subtext_ajax_search").val()
                d.step3_maintext = $("#main_db_step3_maintext_ajax_search").val()
                d.step3_subtext = $("#main_db_step3_subtext_ajax_search").val()
                d.bullet_points1 = $("#main_db_bullet_points1_ajax_search").val()
                d.bullet_points2 = $("#main_db_bullet_points2_ajax_search").val()
                d.bullet_points3 = $("#main_db_bullet_points3_ajax_search").val()
                d.cta1_maintext = $("#main_db_cta1_maintext_ajax_search").val()
                d.cta1_sub_maintext = $("#main_db_cta1_sub_maintext_ajax_search").val()
                d.cta2_maintext = $("#main_db_cta2_maintext_ajax_search").val()
                
                d.cta2_sub_maintext = $("#main_db_cta2_sub_maintext_ajax_search").val()
                d.about_headline = $("#main_db_about_headline_ajax_search").val()
                d.about_text = $("#main_db_about_text_ajax_search").val()
                d.compare_headline = $("#main_db_compare_headline_ajax_search").val()
                d.cta3_maintext = $("#main_db_cta3_maintext_ajax_search").val()
                d.cta3_subtext = $("#main_db_cta3_subtext_ajax_search").val()
                
                d.testimonial_headline = $("#main_db_testimonial_headline_ajax_search").val()
                d.testimonial_text = $("#main_db_testimonial_text_ajax_search").val()
                d.testimonial_info = $("#main_db_testimonial_info_ajax_search").val()
                d.cta_dropdown_maintext = $("#main_db_cta_dropdown_maintext_ajax_search").val()
                d.cta_dropdown_subtext = $("#main_db_cta_dropdown_subtext_ajax_search").val()
                d.congratulations_headline = $("#main_db_congratulations_headline_ajax_search").val()
                d.congratulations_sub_headline = $("#main_db_congratulations_sub_headline_ajax_search").val()
                d.congratulations_filled = $("#main_db_congratulations_filled_ajax_search").val()
                d.second_form_headline = $("#main_db_second_form_headline_ajax_search").val()
            }
        },
        "fnDrawCallback": function(oSettings) {
            $('.make-switch-campaign').bootstrapSwitch()
            $('.make-switch-campaign-homepage').bootstrapSwitch()
            $(".delete_maindb_variants").confirmation().on("confirmed.bs.confirmation", function() {
                //alert("fdsfds");
                delete_maindb_variants($(this).attr('id'))
            });
            $(".send_uplead_network").confirmation().on("confirmed.bs.confirmation", function() {
                $('.wrapper_loader').append(loaderhtmldata);
                var id = $(this).data('content_id');
                send_maindbdata_network(id, 'Uplead');
                //delete_maindb_variants($(this).attr('id'))
                 
            });
            $(".send_vm_network").confirmation().on("confirmed.bs.confirmation", function() {
                $('.wrapper_loader').append(loaderhtmldata);
                var id = $(this).data('content_id');
                send_maindbdata_network(id, 'VM');
                //delete_maindb_variants($(this).attr('id'))
            });
            $(".reject_uplead_network").confirmation().on("confirmed.bs.confirmation", function() {
                $('.wrapper_loader').append(loaderhtmldata);
                var id = $(this).data('content_id');
                reject_maindbdata(id);
                //delete_maindb_variants($(this).attr('id'))
            });
        }
    };

    var img_tbl = $('#datatable_maindb').DataTable(tbl_config3);

    $('#select_all_existent').change(function() {
        var cells = img_tbl.cells( ).nodes();
        $(cells).find(':checkbox').prop('checked', $(this).is(':checked'));
    });

    $('#date_filter_main_db').on('change', function(e) {
        var campaign = $('#date_filter_main_db').val();
        if (campaign == "custom_date") {
            $('.custom-date-main-db').css("display", "block");
        }
        else {
            $("#custom_date_main_db").val('');
            $('.custom-date-main-db').css("display", "none");
            $('#date_filter_main_db').val(moment().subtract("days", 29) + "###" + moment());
            $('#datatable_maindb').dataTable().fnDraw(tbl_config3);
        }

    });
   
   

    $('#custom_date_main_db').datepicker({
        rtl: App.isRTL(),
        orientation: "left",
        autoclose: !0
    }).on('changeDate', function() {
        $('#date_filter_main_db').val(moment().subtract("days", 29) + "###" + moment());
        $('#datatable_maindb').dataTable().fnDraw(tbl_config3);
    });

// Search on device change

     $('#show_device_maindb').on('change', function(e) {
        $("#custom_date_campaign").val('');
        $('#date_filter_main_db').val(moment().subtract("days", 29) + "###" + moment());
        $('#datatable_maindb').dataTable().fnDraw(tbl_config3);
    });

// Search on device change

// Search on Brand

$("#brand_select_maindb").on('change', function(e){
    $("#custom_date_main_db").val('');
    $('#date_filter_main_db').val(moment().subtract("days", 29) + "###" + moment());
    $('#datatable_maindb').dataTable().fnDraw(tbl_config3);
});
//Search on Brand
//// Search on Source

$("#source_select_maindb").on('change', function(e){
    $("#custom_date_main_db").val('');
    $('#date_filter_main_db').val(moment().subtract("days", 29) + "###" + moment());
    $('#datatable_maindb').dataTable().fnDraw(tbl_config3);
});
//Search on Source
//// Search on Status

$("#status_maindb").on('change', function(e){
    $("#custom_date_main_db").val('');
    $('#date_filter_main_db').val(moment().subtract("days", 29) + "###" + moment());
    $('#datatable_maindb').dataTable().fnDraw(tbl_config3);
});
//Search on Status
//* Custom Column Search *//

    $('.maindb_soldid').on('keyup change', function() {
        $('#main_db_soldid_ajax_search').val($(this).val())
        $('#datatable_maindb').dataTable().fnDraw(tbl_config3);
    })
//* Custom Column Search *//

//* Custom Column Search for network name *//

    $('.maindb_network').on('keyup change', function() {
        $('#main_db_network_ajax_search').val($(this).val())
        $('#datatable_maindb').dataTable().fnDraw(tbl_config3);
    })

//* Custom Column Search *//

//* Custom Column Search for last name *//

    $('.maindb_data_last_name').on('keyup change', function() {
        $('#maindb_last_db_hdn_column_data').val($(this).val())
        $('#datatable_maindb').dataTable().fnDraw(tbl_config3);
    })

//* Custom Column Search *//
//* Custom Column Search *//

    $('.maindb_id').on('keyup change', function() {
        $('#main_db_id_ajax_search').val($(this).val())
        $('#datatable_maindb').dataTable().fnDraw(tbl_config3);
    })


//* Custom Column Search *//
//* Custom search for error
$('.maindb_error').on('keyup change', function(){
    $("#main_db_error_ajax_search").val($(this).val());
    $('#datatable_maindb').dataTable().fnDraw(tbl_config3);
})
//* Custom search for error
//* Custom search for keyword
$('.maindb_keyword').on('keyup change', function(){
    $("#main_db_keyword_ajax_search").val($(this).val());
    $('#datatable_maindb').dataTable().fnDraw(tbl_config3);
})
//* Custom search for keyword

//* Custom search for Source
$('.maindb_source').on('keyup change', function(){
    $("#main_db_source_ajax_search").val($(this).val());
    $('#datatable_maindb').dataTable().fnDraw(tbl_config3);
})
//* Custom search for Source
//* Custom search for Brand
$('.maindb_brand').on('keyup change', function(){
    $("#main_db_brand_ajax_search").val($(this).val());
    $('#datatable_maindb').dataTable().fnDraw(tbl_config3);
})
//* Custom search for Brand
//* Custom search for IP
$('.maindb_ip').on('keyup change', function(){
    $("#main_db_ip_ajax_search").val($(this).val());
    $('#datatable_maindb').dataTable().fnDraw(tbl_config3);
})
//* Custom search for IP

//* Custom search for Cover for
$('.maindb_cover_for').on('keyup change', function(){
    $("#main_db_cover_for_ajax_search").val($(this).val());
    $('#datatable_maindb').dataTable().fnDraw(tbl_config3);
})
//* Custom search for Cover for

//* Custom search for Type
$('.maindb_type').on('keyup change', function(){
    $("#main_db_type_ajax_search").val($(this).val());
    $('#datatable_maindb').dataTable().fnDraw(tbl_config3);
})
//* Custom search for Type
//* Custom search for Amount
$('.maindb_amount').on('keyup change', function(){
    $("#main_db_amount_ajax_search").val($(this).val());
    $('#datatable_maindb').dataTable().fnDraw(tbl_config3);
})
//* Custom search for Amount
//* Custom search for Amount
$('.maindb_length').on('keyup change', function(){
    $("#main_db_length_ajax_search").val($(this).val());
    $('#datatable_maindb').dataTable().fnDraw(tbl_config3);
})
//* Custom search for Amount
//* Custom search for first name
$('.maindb_first_name').on('keyup change', function(){
    $("#main_db_first_name_ajax_search").val($(this).val());
    $('#datatable_maindb').dataTable().fnDraw(tbl_config3);
})
//* Custom search for first name
//* Custom search for last name
$('.maindb_last_name').on('keyup change', function(){
    $("#main_db_last_name_ajax_search").val($(this).val());
    $('#datatable_maindb').dataTable().fnDraw(tbl_config3);
})
//* Custom search for last name
//* Custom search for first name
$('.maindb_address').on('keyup change', function(){
    $("#main_db_address_ajax_search").val($(this).val());
    $('#datatable_maindb').dataTable().fnDraw(tbl_config3);
})
//* Custom search for first name
//* Custom search for Post code
$('.maindb_postcode').on('keyup change', function(){
    $("#main_db_postcode_ajax_search").val($(this).val());
    $('#datatable_maindb').dataTable().fnDraw(tbl_config3);
})
//* Custom search for Post code
//* Custom search for dob
$('.maindb_dob').on('keyup change', function(){
    $("#main_db_dob_ajax_search").val($(this).val());
    $('#datatable_maindb').dataTable().fnDraw(tbl_config3);
})
//* Custom search for dob
//* Custom search for smoke
$('.maindb_smoke').on('keyup change', function(){
    $("#main_db_smoke_ajax_search").val($(this).val());
    $('#datatable_maindb').dataTable().fnDraw(tbl_config3);
})
//* Custom search for smoke
//* Custom search for Phone no
$('.maindb_phone_no').on('keyup change', function(){
    $("#main_db_phone_no_ajax_search").val($(this).val());
    $('#datatable_maindb').dataTable().fnDraw(tbl_config3);
})
//* Custom search for Phone no
//* Custom search for Email
$('.maindb_email').on('keyup change', function(){
    $("#main_db_email_ajax_search").val($(this).val());
    $('#datatable_maindb').dataTable().fnDraw(tbl_config3);
})
//* Custom search for Email
//* Custom search for match type
$('.maindb_match_type').on('keyup change', function(){
    $("#main_db_match_type_ajax_search").val($(this).val());
    $('#datatable_maindb').dataTable().fnDraw(tbl_config3);
})
//* Custom search for match type
//* Custom search for adposition
$('.maindb_adposition').on('keyup change', function(){
    $("#main_db_adposition_ajax_search").val($(this).val());
    $('#datatable_maindb').dataTable().fnDraw(tbl_config3);
})
//* Custom search for adposition
//* Custom search for device model
$('.maindb_device_model').on('keyup change', function(){
    $("#main_db_device_model_ajax_search").val($(this).val());
    $('#datatable_maindb').dataTable().fnDraw(tbl_config3);
})
//* Custom search for device model

//* Custom search for lp id
$('.maindb_lp_id').on('keyup change', function(){
    $("#main_db_lp_id_ajax_search").val($(this).val());
    $('#datatable_maindb').dataTable().fnDraw(tbl_config3);
})
//* Custom search for lp id

//* Custom search for main headline
$('.maindb_main_headline').on('keyup change', function(){
    $("#main_db_main_headline_ajax_search").val($(this).val());
    $('#datatable_maindb').dataTable().fnDraw(tbl_config3);
})
//* Custom search for main headline

//* Custom search for main sub headline
$('.maindb_main_sub_headline').on('keyup change', function(){
    $("#main_db_main_sub_headline_ajax_search").val($(this).val());
    $('#datatable_maindb').dataTable().fnDraw(tbl_config3);
})
//* Custom search for main sub headline

//* Custom search for form headline
$('.maindb_form_headline').on('keyup change', function(){
    $("#main_db_form_headline_ajax_search").val($(this).val());
    $('#datatable_maindb').dataTable().fnDraw(tbl_config3);
})
//* Custom search for form headline

//* Custom search for form sub headline
$('.maindb_form_sub_headline').on('keyup change', function(){
    $("#main_db_form_sub_headline_ajax_search").val($(this).val());
    $('#datatable_maindb').dataTable().fnDraw(tbl_config3);
})
//* Custom search for form sub headline

//* Custom search for step headline
$('.maindb_step_headline').on('keyup change', function(){
    $("#main_db_step_headline_ajax_search").val($(this).val());
    $('#datatable_maindb').dataTable().fnDraw(tbl_config3);
})
//* Custom search for step headline

//* Custom search for step1 maintext
$('.maindb_step1_maintext').on('keyup change', function(){
    $("#main_db_step1_maintext_ajax_search").val($(this).val());
    $('#datatable_maindb').dataTable().fnDraw(tbl_config3);
})
//* Custom search for step1 maintext

//* Custom search for step1 subtext
$('.maindb_step1_subtext').on('keyup change', function(){
    $("#main_db_step1_subtext_ajax_search").val($(this).val());
    $('#datatable_maindb').dataTable().fnDraw(tbl_config3);
})
//* Custom search for step1 subtext

//* Custom search for step2 maintext
$('.maindb_step2_maintext').on('keyup change', function(){
    $("#main_db_step2_maintext_ajax_search").val($(this).val());
    $('#datatable_maindb').dataTable().fnDraw(tbl_config3);
})
//* Custom search for step2 maintext

//* Custom search for step2 subtext
$('.maindb_step2_subtext').on('keyup change', function(){
    $("#main_db_step2_subtext_ajax_search").val($(this).val());
    $('#datatable_maindb').dataTable().fnDraw(tbl_config3);
})
//* Custom search for step2 subtext

//* Custom search for step3 maintext
$('.maindb_step3_maintext').on('keyup change', function(){
    $("#main_db_step3_maintext_ajax_search").val($(this).val());
    $('#datatable_maindb').dataTable().fnDraw(tbl_config3);
})
//* Custom search for step3 maintext

//* Custom search for step3 subtext
$('.maindb_step3_subtext').on('keyup change', function(){
    $("#main_db_step3_subtext_ajax_search").val($(this).val());
    $('#datatable_maindb').dataTable().fnDraw(tbl_config3);
})
//* Custom search for step3 subtext

//* Custom search for bullet points1
$('.maindb_bullet_points1').on('keyup change', function(){
    $("#main_db_bullet_points1_ajax_search").val($(this).val());
    $('#datatable_maindb').dataTable().fnDraw(tbl_config3);
})
//* Custom search for bullet points1

//* Custom search for bullet points2
$('.maindb_bullet_points2').on('keyup change', function(){
    $("#main_db_bullet_points2_ajax_search").val($(this).val());
    $('#datatable_maindb').dataTable().fnDraw(tbl_config3);
})
//* Custom search for bullet points2

//* Custom search for bullet points3
$('.maindb_bullet_points3').on('keyup change', function(){
    $("#main_db_bullet_points3_ajax_search").val($(this).val());
    $('#datatable_maindb').dataTable().fnDraw(tbl_config3);
})
//* Custom search for bullet points3

//* Custom search for cta1 maintext
$('.maindb_cta1_maintext').on('keyup change', function(){
    $("#main_db_cta1_maintext_ajax_search").val($(this).val());
    $('#datatable_maindb').dataTable().fnDraw(tbl_config3);
})
//* Custom search for cta1 maintext

//* Custom search for cta1 subtext
$('.maindb_cta1_sub_maintext').on('keyup change', function(){
    $("#main_db_cta1_sub_maintext_ajax_search").val($(this).val());
    $('#datatable_maindb').dataTable().fnDraw(tbl_config3);
})
//* Custom search for cta1 subtext

//* Custom search for cta2 maintext
$('.maindb_cta2_maintext').on('keyup change', function(){
    $("#main_db_cta2_maintext_ajax_search").val($(this).val());
    $('#datatable_maindb').dataTable().fnDraw(tbl_config3);
})
//* Custom search for cta2 maintext

//* Custom search for cta2 subtext
$('.maindb_cta2_sub_maintext').on('keyup change', function(){
    $("#main_db_cta2_sub_maintext_ajax_search").val($(this).val());
    $('#datatable_maindb').dataTable().fnDraw(tbl_config3);
})
//* Custom search for cta2 subtext

//* Custom search for about headline
$('.maindb_about_headline').on('keyup change', function(){
    $("#main_db_about_headline_ajax_search").val($(this).val());
    $('#datatable_maindb').dataTable().fnDraw(tbl_config3);
})
//* Custom search for about headline
//* Custom search for about text
$('.maindb_about_text').on('keyup change', function(){
    $("#main_db_about_text_ajax_search").val($(this).val());
    $('#datatable_maindb').dataTable().fnDraw(tbl_config3);
})
//* Custom search for about text

//* Custom search for compare headline
$('.maindb_compare_headline').on('keyup change', function(){
    $("#main_db_compare_headline_ajax_search").val($(this).val());
    $('#datatable_maindb').dataTable().fnDraw(tbl_config3);
})
//* Custom search for compare headline

//* Custom search for cta3 maintext
$('.maindb_cta3_maintext').on('keyup change', function(){
    $("#main_db_cta3_maintext_ajax_search").val($(this).val());
    $('#datatable_maindb').dataTable().fnDraw(tbl_config3);
})
//* Custom search for cta3 maintext

//* Custom search for cta3 subtext
$('.maindb_cta3_subtext').on('keyup change', function(){
    $("#main_db_cta3_subtext_ajax_search").val($(this).val());
    $('#datatable_maindb').dataTable().fnDraw(tbl_config3);
})
//* Custom search for cta3 subtext

//* Custom search for testimonial headline
$('.maindb_testimonial_headline').on('keyup change', function(){
    $("#main_db_testimonial_headline_ajax_search").val($(this).val());
    $('#datatable_maindb').dataTable().fnDraw(tbl_config3);
})
//* Custom search for testimonial headline

//* Custom search for testimonial text
$('.maindb_testimonial_text').on('keyup change', function(){
    $("#main_db_testimonial_text_ajax_search").val($(this).val());
    $('#datatable_maindb').dataTable().fnDraw(tbl_config3);
})
//* Custom search for testimonial text

//* Custom search for testimonial info
$('.maindb_testimonial_info').on('keyup change', function(){
    $("#main_db_testimonial_info_ajax_search").val($(this).val());
    $('#datatable_maindb').dataTable().fnDraw(tbl_config3);
})
//* Custom search for testimonial info

//* Custom search for cta dropdown maintext
$('.maindb_cta_dropdown_maintext').on('keyup change', function(){
    $("#main_db_cta_dropdown_maintext_ajax_search").val($(this).val());
    $('#datatable_maindb').dataTable().fnDraw(tbl_config3);
})
//* Custom search for cta dropdown maintext

//* Custom search for cta dropdown subtext
$('.maindb_cta_dropdown_subtext').on('keyup change', function(){
    $("#main_db_cta_dropdown_subtext_ajax_search").val($(this).val());
    $('#datatable_maindb').dataTable().fnDraw(tbl_config3);
})
//* Custom search for cta dropdown subtext

//* Custom search for congratulations headline
$('.maindb_congratulations_headline').on('keyup change', function(){
    $("#main_db_congratulations_headline_ajax_search").val($(this).val());
    $('#datatable_maindb').dataTable().fnDraw(tbl_config3);
})
//* Custom search for congratulations headline

//* Custom search for congratulations subheadline
$('.maindb_congratulations_sub_headline').on('keyup change', function(){
    $("#main_db_congratulations_sub_headline_ajax_search").val($(this).val());
    $('#datatable_maindb').dataTable().fnDraw(tbl_config3);
})
//* Custom search for congratulations subheadline

//* Custom search for congratulations filled
$('.maindb_congratulations_filled').on('keyup change', function(){
    $("#main_db_congratulations_filled_ajax_search").val($(this).val());
    $('#datatable_maindb').dataTable().fnDraw(tbl_config3);
})
//* Custom search for congratulations filled

//* Custom search for second form headline
$('.maindb_second_form_headline').on('keyup change', function(){
    $("#main_db_second_form_headline_ajax_search").val($(this).val());
    $('#datatable_maindb').dataTable().fnDraw(tbl_config3);
})
//* Custom search for second form headline
    /* Delete MainDB Record */

//    jQuery('body').on('click', '.delete_maindb_data', function() {
//        if (confirm('Are you sure to want to delete')) {
//            var id = $(this).attr('id');
//
//            $.ajax({
//                type: "POST",
//                url: base_url + "admin/network/delete_maindb_data",
//                data: {"id": id},
//                success: function(response) {
//                    $('#datatable_maindb').dataTable().fnDraw(tbl_config3);
//                }
//            });
//        }
//        $('#datatable_maindb').dataTable().fnDraw(tbl_config3);
//    });

    /* Delete Main DB Record */

    /* Change Status of Campaign Name */

    $('#datatable_maindb').on('switchChange.bootstrapSwitch', '.make-switch-campaign', function(event, state) {
        //console.log(state); // true | false
        var id = $(this).attr('id');
        var state;
        $.ajax({
            type: "POST",
            url: base_url + "admin/optimization/changeStatusd",
            data: {"id": id, "status": state},
            success: function(response) {
                //$('#datatable_maindb').dataTable().fnDraw(tbl_config1);
            }
        });
    });

    $('#datatable_maindb').on('switchChange.bootstrapSwitch', '.make-switch-campaign-homepage', function(event, state) {
        var id = $(this).data('campaign_id');
        $.ajax({
            type: "POST",
            url: base_url + "admin/optimization/changeHomes",
            data: {"id": id},
            success: function(response) {
                $('#datatable_maindb').dataTable().fnDraw(tbl_config3);
            }
        });
    });




    //*  Custom Date Range  *//

    jQuery().daterangepicker && ($("#data-report-range-maindb").daterangepicker({
        ranges: {
            Today: [moment(), moment()], Yesterday: [moment().subtract("days", 1), moment().subtract("days", 1)], "Last 7 Days": [moment().subtract("days", 6), moment()], "Last 30 Days": [moment().subtract("days", 29), moment()], "This Month": [moment().startOf("month"), moment().endOf("month")], "Last Month": [moment().subtract("month", 1).startOf("month"), moment().subtract("month", 1).endOf("month")], "This Year": [moment().startOf("year"), moment().endOf("year")],"Last Year": [moment().subtract("year", 1).startOf("year"), moment().subtract("year", 1).endOf("year")],"All Time": [moment().subtract("year", 46).startOf("year"), moment().endOf("year")]
        }
        , locale: {
            format: "MM/DD/YYYY", separator: " - ", applyLabel: "Apply", cancelLabel: "Cancel", fromLabel: "From", toLabel: "To", customRangeLabel: "Custom", daysOfWeek: ["Su", "Mo", "Tu", "We", "Th", "Fr", "Sa"], monthNames: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"], firstDay: 1
        }
        , opens: App.isRTL() ? "right" : "left"
    }
    , function(e, t, a) {


        $('#date_filter_main_db').val(e + "###" + t); //call datatable init
        $('#datatable_maindb').dataTable().fnDraw(tbl_config3);


        "0" != $("#data-report-range-maindb").attr("data-display-range") && $("#data-report-range-maindb span").html(e.format("MMMM D, YYYY") + " - " + t.format("MMMM D, YYYY"))
    }

    ), "0" != $("#data-report-range-maindb").attr("data-display-range") && $("#data-report-range-maindb span").html(moment().subtract("days", 29).format("MMMM D, YYYY") + " - " + moment().format("MMMM D, YYYY")), $("#data-report-range-maindb").show())

//*  Custom Date Range  *//


    jQuery('body').on('click', '.make-switch-campaign', function() {

        //alert('');

//         alert($(".status").val());
//        if (confirm('Are you sure to want to change status')) {
//            var ids = $(this).attr('id');
//            //alert(ids);
//            var id = ids.split("_");
//            $.ajax({
//                type: "POST",
//                url: base_url + "admin/optimization/changeStatus",
//                data: {"id": id[0], "status": id[1]},
//                success: function (response) {
//                    $('#datatable_maindb').dataTable().fnDraw(tbl_config1);
//                }
//            });
//        }

    });

    /* Change Status of Campaign Name */

    /* Change home status of Campaign Name */

    jQuery('body').on('click', '.homes', function() {
        if (confirm('Are you sure to want to change home status')) {
            var ids = $(this).attr('id');
            var id = ids.split("_");
            $.ajax({
                type: "POST",
                url: base_url + "admin/optimization/changeHome",
                data: {"id": id[0], "home": id[1]},
                success: function(response) {
                    $('#datatable_maindb').dataTable().fnDraw(tbl_config3);
                }
            });
        }

    });

    /* Change home Status of Campaign Name */

    /* Duplicate Campaign Name */

    jQuery('body').on('click', '.duplicate_maindb', function() {
        //var id = $(this).data('campaignname');
        if (confirm('Are you sure to want to duplicate campaign name')) {
            var id = $(this).attr('id');
            $.ajax({
                type: "POST",
                url: base_url + "admin/optimization/duplicateCampaign",
                data: {"id": id},
                success: function(response) {
                    $('#datatable_maindb').dataTable().fnDraw(tbl_config3);
                }
            });
        }
    });

    /* Duplicate Campaign Name */
    $('.btn-popups').on('click', function() {
        $("#campaign_name").val('');
    });

    /* Ajax for add new Campaign */
    //$("#campaign_modal_form_div").on("submit","#responsive", function(){
    $("body").on("click", "#save_campaign3", function() {
        var name = $("#campaign_name").val();
        var dataString = 'campaign_name=' + name;
        if (name == '')
        {
            alert("Please enter campaign name");
        }
        else
        {
// AJAX Code To Submit Form.
            $.ajax({
                type: "POST",
                url: base_url + "admin/optimization/campaign_addds",
                data: dataString,
                cache: false,
                success: function(result) {
                    $('.btn-closedata').click();
                    $('#datatable_maindb').dataTable().fnDraw(tbl_config3);
                }
            });
        }
        return false;
    });

    /* Ajax for add new Campaign */

    /* Bulk delete Main DB Records */

    jQuery('body').on('click', '#bulk_delete_main_db', function() {
        var checkbox = document.getElementsByTagName("input");
        var counter = 0;
        for (var i = 0; i < checkbox.length; i++)
        {
            if (checkbox[i].checked)
            {
                counter++;
            }
        }
        if (counter > 0) {
            if (confirm('Are you sure to want to delete')) {
                var bulk = [];
                i = 0;
                $('.bulk_check_box1').each(function() {
                    if (this.checked) {
                        bulk[i] = this.id;
                        i++;
                    }

                });
                $.ajax({
                    type: "POST",
                    url: base_url + "admin/network/delete_bulk_main_db",
                    data: {"bulk_del": bulk},
                    success: function(response) {
                        $('#datatable_maindb').dataTable().fnDraw(tbl_config3);
                    }
                });
            }
        }
        else {
            alert("Please select Campaign");
        }
        //$('#datatable_ajax_2').dataTable().fnDraw(tbl_config);
    });

    /* Bulk delete Main db records */

    /* Bulk Active Main db records */

    jQuery('body').on('click', '#active_bulk_maindb', function() {
        var checkbox = document.getElementsByTagName("input");
        var counter = 0;
        var value = 'Active';

        for (var i = 0; i < checkbox.length; i++)
        {
            if (checkbox[i].checked)
            {
                counter++;
            }
        }
        if (counter > 0) {
            if (confirm('Are you sure to want to change campaign status')) {
                var bulk = [];
                var active = [];
                i = 0;
                $('.bulk_check_box1').each(function() {
                    if (this.checked) {
                        bulk[i] = $(this).attr('id');
                        i++;
                    }

                });
                $.ajax({
                    type: "POST",
                    url: base_url + "admin/optimization/active_bulk_campaigns",
                    data: {"bulk_active": bulk, "status": value},
                    success: function(response) {
                        $('#datatable_maindb').dataTable().fnDraw(tbl_config3);
                    }
                });
            }
        }
        else {
            alert("Please select Campaign");
        }
        //$('#datatable_ajax_2').dataTable().fnDraw(tbl_config);
    });

    /* Bulk Active Main db records  */

    /*  Bulk Passive Main db records  */

    jQuery('body').on('click', '#passive_bulk_maindb', function() {
        var checkbox = document.getElementsByTagName("input");
        var counter = 0;
        var value = 'Passive';

        for (var i = 0; i < checkbox.length; i++)
        {
            if (checkbox[i].checked)
            {
                counter++;
            }
        }
        if (counter > 0) {
            if (confirm('Are you sure to want to change campaign status')) {
                var bulk = [];
                var active = [];
                i = 0;
                $('.bulk_check_box1').each(function() {
                    if (this.checked) {
                        bulk[i] = $(this).attr('id');
                        i++;
                    }

                });
                $.ajax({
                    type: "POST",
                    url: base_url + "admin/optimization/active_bulk_campaignss",
                    data: {"bulk_active": bulk, "status": value},
                    success: function(response) {
                        $('#datatable_maindb').dataTable().fnDraw(tbl_config3);
                    }
                });
            }
        }
        else {
            alert("Please select Campaign");
        }
        //$('#datatable_ajax_2').dataTable().fnDraw(tbl_config);
    });

    /*  Bulk Passive Main db records */

    /* Bulk delete Campaign */

    jQuery('body').on('click', '#duplicate_bulk_campaign1', function() {
        var checkbox = document.getElementsByTagName("input");
        var counter = 0;
        for (var i = 0; i < checkbox.length; i++)
        {
            if (checkbox[i].checked)
            {
                counter++;
            }
        }
        if (counter > 0) {
            if (confirm('Are you sure to want to duplicate campaign name')) {
                var bulk = [];
                i = 0;
                $('.bulk_check_box1').each(function() {
                    if (this.checked) {
                        bulk[i] = this.id;
                        i++;
                    }

                });
                $.ajax({
                    type: "POST",
                    url: base_url + "admin/optimization/duplicate_bulk_campaign1",
                    data: {"bulk_dupl": bulk},
                    success: function(response) {
                        $('#datatable_maindb').dataTable().fnDraw(tbl_config3);
                    }
                });
            }
        }
        else {
            alert("Please select Campaign");
        }
        //$('#datatable_ajax_2').dataTable().fnDraw(tbl_config);
    });

    /* Bulk delete Campaign */

});

$(document).ready(function() {
   
    var $duplicate_modal = $('#load_popup_modal_maindb_show_edit');
    jQuery('body').on('click', '.click_to_load_maindb_edit_modal_popup', function() {
        $duplicate_modal.load(base_url + "admin/network/edit_maindb_modal_forms", {
            'id': $(this).data('content_id'), "action":"edit"
        },
        function() {
            $duplicate_modal.modal('show');
        });
    });
});

$(document).ready(function() {
   
    var $duplicate_modal = $('#load_popup_modal_maindb_show_edit');
    jQuery('body').on('click', '.click_to_load_maindb_view_modal_popup', function() {
        $duplicate_modal.load(base_url + "admin/network/edit_maindb_modal_forms", {
            'id': $(this).data('content_id'), "action":"view"
        },
        function() {
            $duplicate_modal.modal('show');
        });
    });
});

function edit_maindb_variants()
{
    $('#date_filter_main_db').val(moment().subtract("days", 29) + "###" + moment());

    //return;
    var id = $("#maindb_id").val();
    var first_name = $("#first_name").val();
    var last_name = $("#last_name").val();
    var phoneno = $("#phoneno").val();
    var email = $("#email").val();
    var address = $('#address').val();
    var postcode = $('#postcode').val();
    var textinfo = $('#text_info').val();
     var dob = $("#dob").val();
    var cover_amount = $("#cover_amount").val();
    var cover_length = $("#cover_length").val();
    var required = $("#required").val();
    var insurance = $("#insurance").val();
    var dataString = 'id=' + id + '&first_name=' + first_name + '&last_name=' + last_name + '&phoneno=' + phoneno + '&email=' + email + '&address=' + address + '&postcode=' + postcode + '&dob=' + dob + '&cover_amount=' + cover_amount + '&cover_length=' + cover_length + '&required=' + required + '&insurance=' + insurance;
    
// AJAX Code To Submit Form.
    $.ajax({
        type: "POST",
        url: base_url + "admin/network/maindb_variants_edit",
        data: dataString,
        async: false,
        success: function(result) {
            $('.btn-closedata').click();
            if ($.trim(result) == 'success')
            {
                $('#date_filter_main_db').val(moment().subtract("days", 29) + "###" + moment());
                $('#datatable_maindb').dataTable().fnDraw(tbl_config3);
                $('#maindb_variants_edit').trigger("reset");
            }
        }
    });

}

function send_maindbdata_network(id, network){
    var id = id; 
    var network = network;

            $.ajax({
                type: "POST",
                url: base_url + "admin/network/sendMaindbdatanetwork",
                data: {"id": id, "network_name": network},
                success: function(response) {
                    $('#date_filter_main_db').val(moment().subtract("days", 29) + "###" + moment());
                    $('#datatable_maindb').dataTable().fnDraw(tbl_config3);
                    $("#loaderovrly").remove();
                }
            });
}

// Reject main db data network
function reject_maindbdata(id){
    var id = id; 
      $.ajax({
                type: "POST",
                url: base_url + "admin/network/rejectMaindbdatanetwork",
                data: {"id": id},
                success: function(response) {
                    $('#date_filter_main_db').val(moment().subtract("days", 29) + "###" + moment());
                    $('#datatable_maindb').dataTable().fnDraw(tbl_config3);
                }
            });
}

//Reject main db data network


// Delete Single Campain variant

function delete_maindb_variants(id){
    var id = id;

            $.ajax({
                type: "POST",
                url: base_url + "admin/network/delete_maindb_variants",
                data: {"id": id},
                success: function(response) {
                     $('#date_filter_main_db').val(moment().subtract("days", 29) + "###" + moment());
                     $('#datatable_maindb').dataTable().fnDraw(tbl_config3);
                }
            });
}
// Delete Single Campain variant


/* FOr All Missing Leads*/

$(document).ready(function() {

    $('#date_filter_missing_leads').val(moment().subtract("days", 29) + "###" + moment());
    //alert($('#date_filter_campaign').val());
    

    tbl_config4 = {
        "processing": true,
        "serverSide": true,
        "pageLength": 10,
        buttons: [
            {
                extend: 'collection',
                className: 'btn-success',
                text: 'Export',
                buttons: ['pdfHtml5', 'csvHtml5', 'copyHtml5', 'excelHtml5']
            },{
				extend : "colvis",
				columns: ':not(:first-child)'
				}
        ],
        "dom": '<"top"fB>rt<"bottom"ilp>',
        "columnDefs": [
            {"orderable": false, "targets": 0},
            {"visible": false, "targets": 1},
            {"visible": false, "targets": 2},{"visible": false, "targets": 5},{"visible": false, "targets": 6},
            {"visible": false, "targets": 8},{"visible": false, "targets": 10},
            {"visible": false, "targets": 11},{"visible": false, "targets": 12},{"visible": false, "targets": 13},
            {"visible": false, "targets": 14},{"visible": false, "targets": 15},{"visible": false, "targets": 16},{"visible": false, "targets": 18},{"visible": false, "targets": 20},
            {"visible": false, "targets": 21},{"visible": false, "targets": 24},{"visible": false, "targets": 25},
            {"visible": false, "targets": 26},{"visible": false, "targets": 27},{"visible": false, "targets": 28},{"visible": false, "targets": 29},{"visible": false, "targets": 30},
            {"visible": false, "targets": 31},{"visible": false, "targets": 32},{"visible": false, "targets": 33},{"visible": false, "targets": 34},{"visible": false, "targets": 35},
            {"visible": false, "targets": 36},{"visible": false, "targets": 37},{"visible": false, "targets": 38},{"visible": false, "targets": 39},{"visible": false, "targets": 40},
            {"visible": false, "targets": 41},{"visible": false, "targets": 42},{"visible": false, "targets": 43},{"visible": false, "targets": 44},{"visible": false, "targets": 45},
            {"visible": false, "targets": 46},{"visible": false, "targets": 47},{"visible": false, "targets": 48},{"visible": false, "targets": 49},{"visible": false, "targets": 50},
            {"visible": false, "targets": 51},{"visible": false, "targets": 52},{"visible": false, "targets": 53},{"visible": false, "targets": 54},{"visible": false, "targets": 55},
            {"visible": false, "targets": 56}
        ],
        "order": [[3, "desc"]],
        "ajax": {
            "url": base_url + "admin/network/ajaxTableDataMissingleads",
            "type": "POST",
            "data": function(d) {
                d.dayfilter = $('#date_filter_missing_leads').val()
                d.device = $('#show_device_missing_lead').val()
                d.id = $('#missing_db_id_ajax_search').val()
                d.created_at = $('#missing_db_created_at_ajax_search').val()
                d.error = $('#missing_db_error_ajax_search').val()
                d.keyword = $('#missing_db_keyword_ajax_search').val()
                d.source = $('#missing_db_source_ajax_search').val()
                d.brand = $('#missing_db_brand_ajax_search').val()
                d.brand_name = $('#brand_select_missing').val()
                d.source_name = $('#source_select_missing').val()
                //d.device = $('#main_db_device_ajax_search').val()
                d.matchtype = $("#missing_db_match_type_ajax_search").val()
                d.adposition = $("#missing_db_adposition_ajax_search").val()
                d.devicemodel = $("#missing_db_device_model_ajax_search").val()
                d.ip = $('#missing_db_ip_ajax_search').val()
                d.cover_for = $('#missing_db_cover_for_ajax_search').val()
                d.type = $("#missing_db_type_ajax_search").val()
                d.cover_amount = $("#missing_db_amount_ajax_search").val()
                d.cover_length = $("#missing_db_length_ajax_search").val()
                d.first_name = $("#missing_db_first_name_ajax_search").val()
                d.last_name = $("#missing_db_last_name_ajax_search").val()
                d.address = $("#missing_db_address_ajax_search").val()
                d.postcode = $("#missing_db_postcode_ajax_search").val()
                d.dob = $("#missing_db_dob_ajax_search").val()
                d.smoke = $("#missing_db_smoke_ajax_search").val()
                d.phone_no = $("#missing_db_phone_no_ajax_search").val()
                d.email = $("#missing_db_email_ajax_search").val()
                
                d.lp_id = $("#missing_db_lp_id_ajax_search").val()
                d.main_headline = $("#missing_db_main_headline_ajax_search").val()
                d.main_sub_heading = $("#missing_db_main_sub_headline_ajax_search").val()
                d.form_headline = $("#missing_db_form_headline_ajax_search").val()
                d.form_sub_headline = $("#missing_db_form_sub_headline_ajax_search").val()
                d.step_headline = $("#missing_db_step_headline_ajax_search").val()
                d.step1_maintext = $("#missing_db_step1_maintext_ajax_search").val()
                d.step1_subtext = $("#missing_db_step1_subtext_ajax_search").val()
                d.step2_maintext = $("#missing_db_step2_maintext_ajax_search").val()
                d.step2_subtext = $("#missing_db_step2_subtext_ajax_search").val()
                d.step3_maintext = $("#missing_db_step3_maintext_ajax_search").val()
                d.step3_subtext = $("#missing_db_step3_subtext_ajax_search").val()
                d.bullet_points1 = $("#missing_db_bullet_points1_ajax_search").val()
                d.bullet_points2 = $("#missing_db_bullet_points2_ajax_search").val()
                d.bullet_points3 = $("#missing_db_bullet_points3_ajax_search").val()
                d.cta1_maintext = $("#missing_db_cta1_maintext_ajax_search").val()
                d.cta1_sub_maintext = $("#missing_db_cta1_sub_maintext_ajax_search").val()
                d.cta2_maintext = $("#missing_db_cta2_maintext_ajax_search").val()
                
                d.cta2_sub_maintext = $("#missing_db_cta2_sub_maintext_ajax_search").val()
                d.about_headline = $("#missing_db_about_headline_ajax_search").val()
                d.about_text = $("#missing_db_about_text_ajax_search").val()
                d.compare_headline = $("#missing_db_compare_headline_ajax_search").val()
                d.cta3_maintext = $("#missing_db_cta3_maintext_ajax_search").val()
                d.cta3_subtext = $("#missing_db_cta3_subtext_ajax_search").val()
                
                d.testimonial_headline = $("#missing_db_testimonial_headline_ajax_search").val()
                d.testimonial_text = $("#missing_db_testimonial_text_ajax_search").val()
                d.testimonial_info = $("#missing_db_testimonial_info_ajax_search").val()
                d.cta_dropdown_maintext = $("#missing_db_cta_dropdown_maintext_ajax_search").val()
                d.cta_dropdown_subtext = $("#missing_db_cta_dropdown_subtext_ajax_search").val()
                d.congratulations_headline = $("#missing_db_congratulations_headline_ajax_search").val()
                d.congratulations_sub_headline = $("#missing_db_congratulations_sub_headline_ajax_search").val()
                d.congratulations_filled = $("#missing_db_congratulations_filled_ajax_search").val()
                d.second_form_headline = $("#missing_db_second_form_headline_ajax_search").val()
            }
        },
        "fnDrawCallback": function(oSettings) {
            $('.make-switch-campaign').bootstrapSwitch()
            $('.make-switch-campaign-homepage').bootstrapSwitch()
            $(".delete_missinglead_variants").confirmation().on("confirmed.bs.confirmation", function() {
                //alert("fdsfds");
                delete_missinglead_variants($(this).attr('id'))
            });
             $(".send_missinglead_vm_network").confirmation().on("confirmed.bs.confirmation", function() {
                   $('.wrapper_loader').append(loaderhtmldata);
                var id = $(this).data('content_id');
                send_missinglead(id, 'VM');
            });
            $(".send_missinglead_uplead_network").confirmation().on("confirmed.bs.confirmation", function() {
                $('.wrapper_loader').append(loaderhtmldata);
                var id = $(this).data('content_id');
                send_missinglead(id, 'Uplead');
                //delete_maindb_variants($(this).attr('id'))
            });
        }
    };

    var img_tbl = $('#datatable_missingleads').DataTable(tbl_config4);

    $('#select_all_existent').change(function() {
        var cells = img_tbl.cells( ).nodes();
        $(cells).find(':checkbox').prop('checked', $(this).is(':checked'));
    });

    $('#date_filter_missing_leads').on('change', function(e) {
        var campaign = $('#date_filter_missing_leads').val();
        if (campaign == "custom_date") {
            $('.custom-date-missing-lead').css("display", "block");
        }
        else {
            $("#custom_date_missing_leads").val('');
            $('.custom-date-missing-lead').css("display", "none");

            $('#datatable_missingleads').dataTable().fnDraw(tbl_config4);
        }

    });
    $('#show_campaign').on('change', function(e) {
        $("#custom_date_missing_leads").val('');
        $('#datatable_missingleads').dataTable().fnDraw(tbl_config4);
    });

    $('#custom_date_missing_leads').datepicker({
        rtl: App.isRTL(),
        orientation: "left",
        autoclose: !0
    }).on('changeDate', function() {
        $('#date_filter_missing_leads').val(moment().subtract("days", 29) + "###" + moment());
        $('#datatable_missingleads').dataTable().fnDraw(tbl_config4);
    });

 //*  Custom Date Range  *//

    jQuery().daterangepicker && ($("#data-report-range-missinglead").daterangepicker({
        ranges: {
            Today: [moment(), moment()], Yesterday: [moment().subtract("days", 1), moment().subtract("days", 1)], "Last 7 Days": [moment().subtract("days", 6), moment()], "Last 30 Days": [moment().subtract("days", 29), moment()], "This Month": [moment().startOf("month"), moment().endOf("month")], "Last Month": [moment().subtract("month", 1).startOf("month"), moment().subtract("month", 1).endOf("month")], "This Year": [moment().startOf("year"), moment().endOf("year")],"Last Year": [moment().subtract("year", 1).startOf("year"), moment().subtract("year", 1).endOf("year")],"All Time": [moment().subtract("year", 46).startOf("year"), moment().endOf("year")]
        }
        , locale: {
            format: "MM/DD/YYYY", separator: " - ", applyLabel: "Apply", cancelLabel: "Cancel", fromLabel: "From", toLabel: "To", customRangeLabel: "Custom", daysOfWeek: ["Su", "Mo", "Tu", "We", "Th", "Fr", "Sa"], monthNames: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"], firstDay: 1
        }
        , opens: App.isRTL() ? "right" : "left"
    }
    , function(e, t, a) {


        $('#date_filter_missing_leads').val(e + "###" + t); //call datatable init
        $('#datatable_missingleads').dataTable().fnDraw(tbl_config4);


        "0" != $("#data-report-range-missinglead").attr("data-display-range") && $("#data-report-range-missinglead span").html(e.format("MMMM D, YYYY") + " - " + t.format("MMMM D, YYYY"))
    }

    ), "0" != $("#data-report-range-missinglead").attr("data-display-range") && $("#data-report-range-missinglead span").html(moment().subtract("days", 29).format("MMMM D, YYYY") + " - " + moment().format("MMMM D, YYYY")), $("#data-report-range-missinglead").show())

//*  Custom Date Range  *//
   
// Search on device change

     $('#show_device_missing_lead').on('change', function(e) {
        $("#custom_date_missing_leads").val('');
         $('#date_filter_missing_leads').val(moment().subtract("days", 29) + "###" + moment());
        $('#datatable_missingleads').dataTable().fnDraw(tbl_config4);
    });

// Search on device change


//* Custom Column Search *//

    $('.missing_db_id').on('keyup change', function() {
        $('#missing_db_id_ajax_search').val($(this).val())
        $('#datatable_missingleads').dataTable().fnDraw(tbl_config4);
    })


//* Custom Column Search *//
// Search on Brand

$("#brand_select_missing").on('change', function(e){
    $("#custom_date_missing_leads").val('');
    $('#date_filter_missing_leads').val(moment().subtract("days", 29) + "###" + moment());
    $('#datatable_missingleads').dataTable().fnDraw(tbl_config4);
});
//Search on Brand
//// Search on Source

$("#source_select_missing").on('change', function(e){
   $("#custom_date_missing_leads").val('');
    $('#date_filter_missing_leads').val(moment().subtract("days", 29) + "###" + moment());
    $('#datatable_missingleads').dataTable().fnDraw(tbl_config4);
});
//Search on Source
//* Custom Column Search *//

    $('.missing_db_created_at').on('keyup change', function() {
        $('#missing_db_created_at_ajax_search').val($(this).val())
        $('#datatable_missingleads').dataTable().fnDraw(tbl_config4);
    })


//* Custom Column Search *//
//* Custom search for error
$('.missing_db_error').on('keyup change', function(){
    $("#missing_db_error_ajax_search").val($(this).val());
    $('#datatable_missingleads').dataTable().fnDraw(tbl_config4);
})
//* Custom search for error
//* Custom search for keyword
$('.missing_db_keyword').on('keyup change', function(){
    $("#missing_db_keyword_ajax_search").val($(this).val());
    $('#datatable_missingleads').dataTable().fnDraw(tbl_config4);
})
//* Custom search for keyword

//* Custom search for Source
$('.missing_db_source').on('keyup change', function(){
    $("#missing_db_source_ajax_search").val($(this).val());
    $('#datatable_missingleads').dataTable().fnDraw(tbl_config4);
})
//* Custom search for Source
//* Custom search for Brand
$('.missing_db_brand').on('keyup change', function(){
    $("#missing_db_brand_ajax_search").val($(this).val());
    $('#datatable_missingleads').dataTable().fnDraw(tbl_config4);
})
//* Custom search for Brand
//* Custom search for IP
$('.missing_db_ip').on('keyup change', function(){
    $("#missing_db_ip_ajax_search").val($(this).val());
    $('#datatable_missingleads').dataTable().fnDraw(tbl_config4);
})
//* Custom search for IP

//* Custom search for Cover for
$('.missing_db_cover_for').on('keyup change', function(){
    $("#missing_db_cover_for_ajax_search").val($(this).val());
    $('#datatable_missingleads').dataTable().fnDraw(tbl_config4);
})
//* Custom search for Cover for

//* Custom search for Type
$('.missing_db_type').on('keyup change', function(){
    $("#missing_db_type_ajax_search").val($(this).val());
   $('#datatable_missingleads').dataTable().fnDraw(tbl_config4);
})
//* Custom search for Type
//* Custom search for Amount
$('.missing_db_amount').on('keyup change', function(){
    $("#missing_db_amount_ajax_search").val($(this).val());
    $('#datatable_missingleads').dataTable().fnDraw(tbl_config4);
})
//* Custom search for Amount
//* Custom search for Amount
$('.missing_db_length').on('keyup change', function(){
    $("#missing_db_length_ajax_search").val($(this).val());
    $('#datatable_missingleads').dataTable().fnDraw(tbl_config4);
})
//* Custom search for Amount
//* Custom search for first name
$('.missing_db_first_name').on('keyup change', function(){
    $("#missing_db_first_name_ajax_search").val($(this).val());
    $('#datatable_missingleads').dataTable().fnDraw(tbl_config4);
})
//* Custom search for first name
//* Custom search for last name
$('.missing_db_last_name').on('keyup change', function(){
    $("#missing_db_last_name_ajax_search").val($(this).val());
    $('#datatable_missingleads').dataTable().fnDraw(tbl_config4);
})
//* Custom search for last name
//* Custom search for first name
$('.missing_db_address').on('keyup change', function(){
    $("#missing_db_address_ajax_search").val($(this).val());
    $('#datatable_missingleads').dataTable().fnDraw(tbl_config4);
})
//* Custom search for first name
//* Custom search for Post code
$('.missing_db_postcode').on('keyup change', function(){
    $("#missing_db_postcode_ajax_search").val($(this).val());
   $('#datatable_missingleads').dataTable().fnDraw(tbl_config4);
})
//* Custom search for Post code
//* Custom search for dob
$('.missing_db_dob').on('keyup change', function(){
    $("#missing_db_dob_ajax_search").val($(this).val());
    $('#datatable_missingleads').dataTable().fnDraw(tbl_config4);
})
//* Custom search for dob
//* Custom search for smoke
$('.missing_db_smoke').on('keyup change', function(){
    $("#missing_db_smoke_ajax_search").val($(this).val());
   $('#datatable_missingleads').dataTable().fnDraw(tbl_config4);
})
//* Custom search for smoke
//* Custom search for Phone no
$('.missing_db_phone_no').on('keyup change', function(){
    $("#missing_db_phone_no_ajax_search").val($(this).val());
   $('#datatable_missingleads').dataTable().fnDraw(tbl_config4);
})
//* Custom search for Phone no
//* Custom search for Email
$('.missing_db_email').on('keyup change', function(){
    $("#missing_db_email_ajax_search").val($(this).val());
    $('#datatable_missingleads').dataTable().fnDraw(tbl_config4);
})
//* Custom search for Email
//* Custom search for match type
$('.missing_db_match_type').on('keyup change', function(){
    $("#missing_db_match_type_ajax_search").val($(this).val());
    $('#datatable_missingleads').dataTable().fnDraw(tbl_config4);
})
//* Custom search for match type
//* Custom search for adposition
$('.missing_db_adposition').on('keyup change', function(){
    $("#missing_db_adposition_ajax_search").val($(this).val());
    $('#datatable_missingleads').dataTable().fnDraw(tbl_config4);
})
//* Custom search for adposition
//* Custom search for device model
$('.missing_db_device_model').on('keyup change', function(){
    $("#missing_db_device_model_ajax_search").val($(this).val());
    $('#datatable_missingleads').dataTable().fnDraw(tbl_config4);
})
//* Custom search for device model
//* Custom search for lp id
$('.missing_db_lp_id').on('keyup change', function(){
    $("#missing_db_lp_id_ajax_search").val($(this).val());
    $('#datatable_missingleads').dataTable().fnDraw(tbl_config4);
})
//* Custom search for lp id

//* Custom search for main headline
$('.missing_db_main_headline').on('keyup change', function(){
    $("#missing_db_main_headline_ajax_search").val($(this).val());
    $('#datatable_missingleads').dataTable().fnDraw(tbl_config4);
})
//* Custom search for main headline

//* Custom search for main sub headline
$('.missing_db_main_sub_headline').on('keyup change', function(){
    $("#missing_db_main_sub_headline_ajax_search").val($(this).val());
    $('#datatable_missingleads').dataTable().fnDraw(tbl_config4);
})
//* Custom search for main sub headline

//* Custom search for form headline
$('.missing_db_form_headline').on('keyup change', function(){
    $("#missing_db_form_headline_ajax_search").val($(this).val());
    $('#datatable_missingleads').dataTable().fnDraw(tbl_config4);
})
//* Custom search for form headline

//* Custom search for form sub headline
$('.missing_db_form_sub_headline').on('keyup change', function(){
    $("#missing_db_form_sub_headline_ajax_search").val($(this).val());
    $('#datatable_missingleads').dataTable().fnDraw(tbl_config4);
})
//* Custom search for form sub headline

//* Custom search for step headline
$('.missing_db_step_headline').on('keyup change', function(){
    $("#missing_db_step_headline_ajax_search").val($(this).val());
    $('#datatable_missingleads').dataTable().fnDraw(tbl_config4);
})
//* Custom search for step headline

//* Custom search for step1 maintext
$('.missing_db_step1_maintext').on('keyup change', function(){
    $("#missing_db_step1_maintext_ajax_search").val($(this).val());
    $('#datatable_missingleads').dataTable().fnDraw(tbl_config4);
})
//* Custom search for step1 maintext

//* Custom search for step1 subtext
$('.missing_db_step1_subtext').on('keyup change', function(){
    $("#missing_db_step1_subtext_ajax_search").val($(this).val());
    $('#datatable_missingleads').dataTable().fnDraw(tbl_config4);
})
//* Custom search for step1 subtext

//* Custom search for step2 maintext
$('.missing_db_step2_maintext').on('keyup change', function(){
    $("#missing_db_step2_maintext_ajax_search").val($(this).val());
    $('#datatable_missingleads').dataTable().fnDraw(tbl_config4);
})
//* Custom search for step2 maintext

//* Custom search for step2 subtext
$('.missing_db_step2_subtext').on('keyup change', function(){
    $("#missing_db_step2_subtext_ajax_search").val($(this).val());
    $('#datatable_missingleads').dataTable().fnDraw(tbl_config4);
})
//* Custom search for step2 subtext

//* Custom search for step3 maintext
$('.missing_db_step3_maintext').on('keyup change', function(){
    $("#missing_db_step3_maintext_ajax_search").val($(this).val());
    $('#datatable_missingleads').dataTable().fnDraw(tbl_config4);
})
//* Custom search for step3 maintext

//* Custom search for step3 subtext
$('.missing_db_step3_subtext').on('keyup change', function(){
    $("#missing_db_step3_subtext_ajax_search").val($(this).val());
    $('#datatable_missingleads').dataTable().fnDraw(tbl_config4);
})
//* Custom search for step3 subtext

//* Custom search for bullet points1
$('.missing_db_bullet_points1').on('keyup change', function(){
    $("#missing_db_bullet_points1_ajax_search").val($(this).val());
    $('#datatable_missingleads').dataTable().fnDraw(tbl_config4);
})
//* Custom search for bullet points1

//* Custom search for bullet points2
$('.missing_db_bullet_points2').on('keyup change', function(){
    $("#missing_db_bullet_points2_ajax_search").val($(this).val());
    $('#datatable_missingleads').dataTable().fnDraw(tbl_config4);
})
//* Custom search for bullet points2

//* Custom search for bullet points3
$('.missing_db_bullet_points3').on('keyup change', function(){
    $("#missing_db_bullet_points3_ajax_search").val($(this).val());
    $('#datatable_missingleads').dataTable().fnDraw(tbl_config4);
})
//* Custom search for bullet points3

//* Custom search for cta1 maintext
$('.missing_db_cta1_maintext').on('keyup change', function(){
    $("#missing_db_cta1_maintext_ajax_search").val($(this).val());
    $('#datatable_missingleads').dataTable().fnDraw(tbl_config4);
})
//* Custom search for cta1 maintext

//* Custom search for cta1 subtext
$('.missing_db_cta1_sub_maintext').on('keyup change', function(){
    $("#missing_db_cta1_sub_maintext_ajax_search").val($(this).val());
    $('#datatable_missingleads').dataTable().fnDraw(tbl_config4);
})
//* Custom search for cta1 subtext

//* Custom search for cta2 maintext
$('.missing_db_cta2_maintext').on('keyup change', function(){
    $("#missing_db_cta2_maintext_ajax_search").val($(this).val());
    $('#datatable_missingleads').dataTable().fnDraw(tbl_config4);
})
//* Custom search for cta2 maintext

//* Custom search for cta2 subtext
$('.missing_db_cta2_sub_maintext').on('keyup change', function(){
    $("#missing_db_cta2_sub_maintext_ajax_search").val($(this).val());
    $('#datatable_missingleads').dataTable().fnDraw(tbl_config4);
})
//* Custom search for cta2 subtext

//* Custom search for about headline
$('.missing_db_about_headline').on('keyup change', function(){
    $("#missing_db_about_headline_ajax_search").val($(this).val());
    $('#datatable_missingleads').dataTable().fnDraw(tbl_config4);
})
//* Custom search for about headline
//* Custom search for about text
$('.missing_db_about_text').on('keyup change', function(){
    $("#missing_db_about_text_ajax_search").val($(this).val());
    $('#datatable_missingleads').dataTable().fnDraw(tbl_config4);
})
//* Custom search for about text

//* Custom search for compare headline
$('.missing_db_compare_headline').on('keyup change', function(){
    $("#missing_db_compare_headline_ajax_search").val($(this).val());
    $('#datatable_missingleads').dataTable().fnDraw(tbl_config4);
})
//* Custom search for compare headline

//* Custom search for cta3 maintext
$('.missing_db_cta3_maintext').on('keyup change', function(){
    $("#missing_db_cta3_maintext_ajax_search").val($(this).val());
    $('#datatable_missingleads').dataTable().fnDraw(tbl_config4);
})
//* Custom search for cta3 maintext

//* Custom search for cta3 subtext
$('.missing_db_cta3_subtext').on('keyup change', function(){
    $("#missing_db_cta3_subtext_ajax_search").val($(this).val());
    $('#datatable_missingleads').dataTable().fnDraw(tbl_config4);
})
//* Custom search for cta3 subtext

//* Custom search for testimonial headline
$('.missing_db_testimonial_headline').on('keyup change', function(){
    $("#missing_db_testimonial_headline_ajax_search").val($(this).val());
    $('#datatable_missingleads').dataTable().fnDraw(tbl_config4);
})
//* Custom search for testimonial headline

//* Custom search for testimonial text
$('.missing_db_testimonial_text').on('keyup change', function(){
    $("#missing_db_testimonial_text_ajax_search").val($(this).val());
    $('#datatable_missingleads').dataTable().fnDraw(tbl_config4);
})
//* Custom search for testimonial text

//* Custom search for testimonial info
$('.missing_db_testimonial_info').on('keyup change', function(){
    $("#missing_db_testimonial_info_ajax_search").val($(this).val());
    $('#datatable_missingleads').dataTable().fnDraw(tbl_config4);
})
//* Custom search for testimonial info

//* Custom search for cta dropdown maintext
$('.missing_db_cta_dropdown_maintext').on('keyup change', function(){
    $("#missing_db_cta_dropdown_maintext_ajax_search").val($(this).val());
    $('#datatable_missingleads').dataTable().fnDraw(tbl_config4);
})
//* Custom search for cta dropdown maintext

//* Custom search for cta dropdown subtext
$('.missing_db_cta_dropdown_subtext').on('keyup change', function(){
    $("#missing_db_cta_dropdown_subtext_ajax_search").val($(this).val());
    $('#datatable_missingleads').dataTable().fnDraw(tbl_config4);
})
//* Custom search for cta dropdown subtext

//* Custom search for congratulations headline
$('.missing_db_congratulations_headline').on('keyup change', function(){
    $("#missing_db_congratulations_headline_ajax_search").val($(this).val());
    $('#datatable_missingleads').dataTable().fnDraw(tbl_config4);
})
//* Custom search for congratulations headline

//* Custom search for congratulations subheadline
$('.missing_db_congratulations_sub_headline').on('keyup change', function(){
    $("#missing_db_congratulations_sub_headline_ajax_search").val($(this).val());
    $('#datatable_missingleads').dataTable().fnDraw(tbl_config4);
})
//* Custom search for congratulations subheadline

//* Custom search for congratulations filled
$('.missing_db_congratulations_filled').on('keyup change', function(){
    $("#missing_db_congratulations_filled_ajax_search").val($(this).val());
    $('#datatable_missingleads').dataTable().fnDraw(tbl_config4);
})
//* Custom search for congratulations filled

//* Custom search for second form headline
$('.missing_db_second_form_headline').on('keyup change', function(){
    $("#missing_db_second_form_headline_ajax_search").val($(this).val());
    $('#datatable_missingleads').dataTable().fnDraw(tbl_config4);
})
//* Custom search for second form headline


    /* Delete Missing Leads Record */

    jQuery('body').on('click', '.delete_missing_leads_data', function() {
        if (confirm('Are you sure to want to delete')) {
            var id = $(this).attr('id');

            $.ajax({
                type: "POST",
                url: base_url + "admin/network/delete_missing_leads_data",
                data: {"id": id},
                success: function(response) {
                    $('#datatable_missingleads').dataTable().fnDraw(tbl_config4);
                }
            });
        }
        $('#datatable_missingleads').dataTable().fnDraw(tbl_config4);
    });

    /* Delete Missing Leads Record */

    /* Change Status of Campaign Name */

    $('#datatable_maindb').on('switchChange.bootstrapSwitch', '.make-switch-campaign', function(event, state) {
        //console.log(state); // true | false
        var id = $(this).attr('id');
        var state;
        $.ajax({
            type: "POST",
            url: base_url + "admin/optimization/changeStatus",
            data: {"id": id, "status": state},
            success: function(response) {
                //$('#datatable_maindb').dataTable().fnDraw(tbl_config1);
            }
        });
    });

    $('#datatable_maindb').on('switchChange.bootstrapSwitch', '.make-switch-campaign-homepage', function(event, state) {
        var id = $(this).data('campaign_id');
        $.ajax({
            type: "POST",
            url: base_url + "admin/optimization/changeHome2",
            data: {"id": id},
            success: function(response) {
                $('#datatable_missingleads').dataTable().fnDraw(tbl_config4);
            }
        });
    });


    jQuery('body').on('click', '.make-switch-campaign', function() {

        //alert('');

//         alert($(".status").val());
//        if (confirm('Are you sure to want to change status')) {
//            var ids = $(this).attr('id');
//            //alert(ids);
//            var id = ids.split("_");
//            $.ajax({
//                type: "POST",
//                url: base_url + "admin/optimization/changeStatus",
//                data: {"id": id[0], "status": id[1]},
//                success: function (response) {
//                    $('#datatable_maindb').dataTable().fnDraw(tbl_config1);
//                }
//            });
//        }

    });

    /* Change Status of Campaign Name */

    /* Change home status of Campaign Name */

    jQuery('body').on('click', '.home1', function() {
        if (confirm('Are you sure to want to change home status')) {
            var ids = $(this).attr('id');
            var id = ids.split("_");
            $.ajax({
                type: "POST",
                url: base_url + "admin/optimization/changeHome3",
                data: {"id": id[0], "home": id[1]},
                success: function(response) {
                    $('#datatable_missingleads').dataTable().fnDraw(tbl_config4);
                }
            });
        }

    });

    /* Change home Status of Campaign Name */

    /* Duplicate Campaign Name */

    jQuery('body').on('click', '.duplicate_missingleads', function() {
        //var id = $(this).data('campaignname');
        if (confirm('Are you sure to want to duplicate campaign name')) {
            var id = $(this).attr('id');
            $.ajax({
                type: "POST",
                url: base_url + "admin/optimization/duplicateCampaign2",
                data: {"id": id},
                success: function(response) {
                    $('#datatable_missingleads').dataTable().fnDraw(tbl_config4);
                }
            });
        }
    });

    /* Duplicate Campaign Name */
    $('.btn-popups').on('click', function() {
        $("#campaign_name").val('');
    });

    /* Ajax for add new Campaign */
    //$("#campaign_modal_form_div").on("submit","#responsive", function(){
    $("body").on("click", "#save_campaign4", function() {
        var name = $("#campaign_name").val();
        var dataString = 'name=' + name;
        if (name == '')
        {
            alert("Please enter campaign name");
        }
        else
        {
// AJAX Code To Submit Form.
            $.ajax({
                type: "POST",
                url: base_url + "admin/optimization/campaign_add",
                data: dataString,
                cache: false,
                success: function(result) {
                    $('.btn-closedata').click();
                    $('#datatable_missingleads').dataTable().fnDraw(tbl_config4);
                }
            });
        }
        return false;
    });

    /* Ajax for add new Campaign */

    /* Bulk delete Main DB Records */

    jQuery('body').on('click', '#bulk_delete_missing_leads', function() {
        var checkbox = document.getElementsByTagName("input");
        var counter = 0;
        for (var i = 0; i < checkbox.length; i++)
        {
            if (checkbox[i].checked)
            {
                counter++;
            }
        }
        if (counter > 0) {
            if (confirm('Are you sure to want to delete')) {
                var bulk = [];
                i = 0;
                $('.bulk_check_box1').each(function() {
                    if (this.checked) {
                        bulk[i] = this.id;
                        i++;
                    }

                });
                $.ajax({
                    type: "POST",
                    url: base_url + "admin/network/bulk_delete_missing_leads",
                    data: {"bulk_del": bulk},
                    success: function(response) {
                        $('#datatable_missingleads').dataTable().fnDraw(tbl_config4);
                    }
                });
            }
        }
        else {
            alert("Please select Campaign");
        }
        //$('#datatable_ajax_2').dataTable().fnDraw(tbl_config);
    });

    /* Bulk delete Main db records */

    /* Bulk Active Campaign */

    jQuery('body').on('click', '#active_bulk_missing_leads', function() {
        var checkbox = document.getElementsByTagName("input");
        var counter = 0;
        var value = 'Active';

        for (var i = 0; i < checkbox.length; i++)
        {
            if (checkbox[i].checked)
            {
                counter++;
            }
        }
        if (counter > 0) {
            if (confirm('Are you sure to want to change campaign status')) {
                var bulk = [];
                var active = [];
                i = 0;
                $('.bulk_check_box1').each(function() {
                    if (this.checked) {
                        bulk[i] = $(this).attr('id');
                        i++;
                    }

                });
                $.ajax({
                    type: "POST",
                    url: base_url + "admin/optimization/active_bulk_campaignss",
                    data: {"bulk_active": bulk, "status": value},
                    success: function(response) {
                        $('#datatable_missingleads').dataTable().fnDraw(tbl_config4);
                    }
                });
            }
        }
        else {
            alert("Please select Campaign");
        }
        //$('#datatable_ajax_2').dataTable().fnDraw(tbl_config);
    });

    /* Bulk Active Campaign */

    /* Bulk Passive Campaign */

    jQuery('body').on('click', '#passive_bulk_missing_leads', function() {
        var checkbox = document.getElementsByTagName("input");
        var counter = 0;
        var value = 'Passive';

        for (var i = 0; i < checkbox.length; i++)
        {
            if (checkbox[i].checked)
            {
                counter++;
            }
        }
        if (counter > 0) {
            if (confirm('Are you sure to want to change campaign status')) {
                var bulk = [];
                var active = [];
                i = 0;
                $('.bulk_check_box1').each(function() {
                    if (this.checked) {
                        bulk[i] = $(this).attr('id');
                        i++;
                    }

                });
                $.ajax({
                    type: "POST",
                    url: base_url + "admin/optimization/active_bulk_campaignss",
                    data: {"bulk_active": bulk, "status": value},
                    success: function(response) {
                        $('#datatable_missingleads').dataTable().fnDraw(tbl_config4);
                    }
                });
            }
        }
        else {
            alert("Please select Campaign");
        }
        //$('#datatable_ajax_2').dataTable().fnDraw(tbl_config);
    });

    /* Bulk Passive Campaign */

    /* Bulk delete Campaign */

    jQuery('body').on('click', '#duplicate_bulk_campaign12', function() {
        var checkbox = document.getElementsByTagName("input");
        var counter = 0;
        for (var i = 0; i < checkbox.length; i++)
        {
            if (checkbox[i].checked)
            {
                counter++;
            }
        }
        if (counter > 0) {
            if (confirm('Are you sure to want to duplicate campaign name')) {
                var bulk = [];
                i = 0;
                $('.bulk_check_box1').each(function() {
                    if (this.checked) {
                        bulk[i] = this.id;
                        i++;
                    }

                });
                $.ajax({
                    type: "POST",
                    url: base_url + "admin/optimization/duplicate_bulk_campaign13",
                    data: {"bulk_dupl": bulk},
                    success: function(response) {
                        $('#datatable_missingleads').dataTable().fnDraw(tbl_config4);
                        
                    }
                });
            }
        }
        else {
            alert("Please select Campaign");
        }
        //$('#datatable_ajax_2').dataTable().fnDraw(tbl_config);
    });

    /* Bulk delete Campaign */

});

$(document).ready(function() {
   
    var $duplicate_modal = $('#load_popup_modal_missinglead_show_edit');
    jQuery('body').on('click', '.click_to_load_missinglead_edit_modal_popup', function() {
        $duplicate_modal.load(base_url + "admin/network/edit_missinglead_modal_forms", {
            'id': $(this).data('content_id'), "action":"edit"
        },
        function() {
            $duplicate_modal.modal('show');
        });
    });
});
$(document).ready(function() {
   
    var $duplicate_modal = $('#load_popup_modal_missinglead_show_edit');
    jQuery('body').on('click', '.click_to_load_missinglead_view_modal_popup', function() {
        $duplicate_modal.load(base_url + "admin/network/edit_missinglead_modal_forms", {
            'id': $(this).data('content_id'), "action":"view"
        },
        function() {
            $duplicate_modal.modal('show');
        });
    });
});

function missinglead_variants()
{
   $('#date_filter_missing_leads').val(moment().subtract("days", 29) + "###" + moment());

    //return;
    var id = $("#maindb_id").val();
    var first_name = $("#first_name").val();
    var last_name = $("#last_name").val();
    var phoneno = $("#phoneno").val();
    var email = $("#email").val();
    var address = $('#address').val();
    var postcode = $('#postcode').val();
    var textinfo = $('#text_info').val();
    var dob = $("#dob").val();
    var cover_amount = $("#cover_amount").val();
    var cover_length = $("#cover_length").val();
    var required = $("#required").val();
    var insurance = $("#insurance").val();
    //alert(insurance);
    
    var dataString = 'id=' + id + '&first_name=' + first_name + '&last_name=' + last_name + '&phoneno=' + phoneno + '&email=' + email + '&address=' + address + '&postcode=' + postcode + '&dob=' + dob + '&cover_amount=' + cover_amount + '&cover_length=' + cover_length + '&required=' + required + '&insurance=' + insurance;
    
// AJAX Code To Submit Form.
    $.ajax({
        type: "POST",
        url: base_url + "admin/network/missinglead_variants_edit",
        data: dataString,
        async: false,
        success: function(result) {
            $('.btn-closedata').click();
            if ($.trim(result) == 'success')
            {
                $('#date_filter_missing_leads').val(moment().subtract("days", 29) + "###" + moment());
                $('#datatable_missingleads').dataTable().fnDraw(tbl_config4);
                $('#maindb_variants_edit').trigger("reset");
            }
        }
    });

}
// Delete Single Campain variant

function delete_missinglead_variants(id){
    var id = id;

            $.ajax({
                type: "POST",
                url: base_url + "admin/network/delete_missinglead_variants",
                data: {"id": id},
                success: function(response) {
                    $('#date_filter_missing_leads').val(moment().subtract("days", 29) + "###" + moment());
                    $('#datatable_missingleads').dataTable().fnDraw(tbl_config4);
                }
            });
}
// Delete Single Campain variant

// Send Missing lead on network

function send_missinglead(id, network){
    var id = id; 
    var network = network;
    

            $.ajax({
                type: "POST",
                url: base_url + "admin/network/assignNetwork",
                data: {"id": id, "network_name": network},
                success: function(response) {
                    $('#date_filter_missing_leads').val(moment().subtract("days", 29) + "###" + moment());
                    $('#datatable_missingleads').dataTable().fnDraw(tbl_config4);
                     $("#loaderovrly").remove();
                }
            });
}

// Send Missing lead on network


