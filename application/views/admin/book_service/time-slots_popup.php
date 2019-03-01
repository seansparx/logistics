
<div id="modal_time_slots" class="modal fade modal-scroll" tabindex="-1" data-replace="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Available Slots - <b class="modal-resource_name">Resource Name</b></h4>
                <p class="modal-sub_title">The following slots are available</p>
            </div>
            <div class="modal-body">
                <input type="checkbox" id="full_day" name="full_day" value="1"/> Full day
                <div class="table-scrollable">
                    <?php echo form_open('', array("id" => "form_time_slots", "name" => "form_time_slots")); ?>
                    <table id="table_slots" class="table table-hover">
                        <thead>
                            <tr>
                                <th> Start </th>
                                <th> End </th>
                                <th> Book </th>
                                <th> Customer/ Service/ Contract/ Department</th>
                            </tr>
                        </thead>
                        <tbody>
                            
                        </tbody>
                    </table>
                    <?php echo form_close(); ?>
                </div>

            </div>
        </div>
    </div>
</div>

<script>

    $(document).ready(function(){
        
        init_full_day();
    });
    
    
    function init_full_day()
    {
        $("#full_day").click(function() {

            var checked = $(this).is(":checked");

            $(".shift_timing").prop("checked", checked);
           
        });
    }

</script>
