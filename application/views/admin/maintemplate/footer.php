<input type="hidden" id="baseurlval" name="baseurlval" value="<?php echo base_url() ?>"/>

<script src="<?php echo base_url() ?>assets/admin/js/validation.js" type="text/javascript"></script>
<script src="<?php echo base_url() ?>assets/admin/js/ajax.js" type="text/javascript"></script>



<!-- BEGIN QUICK SIDEBAR -->
<a href="javascript:;" class="page-quick-sidebar-toggler">
    <i class="icon-login"></i>
</a>

<!-- END QUICK SIDEBAR -->
</div>
<!-- END CONTAINER -->
<!-- BEGIN FOOTER -->
<div class="page-footer">
    <div class="page-footer-inner"> 2016 &copy; Logistic</div>
    <div class="scroll-to-top">
        <i class="icon-arrow-up"></i>
    </div>
</div>
<!-- END FOOTER -->
</div>

<!--Remove value from search box of data table after page reload-->

<script>
    
    jQuery(document).ready(function () {

        $(".filter-cancel").trigger('click');

        $(".confirm").click(function(){
        	alert(1246);
        });

    });


    function confirmation(url) {

        swal({
            title: 'Delete Record',
            text: 'Do you want to delete this record ?',
            type: false,
            allowOutsideClick: true,
            showConfirmButton: true,
            showCancelButton: true,
            confirmButtonClass: 'btn-danger',
            cancelButtonClass: 'btn-primary',
            closeOnConfirm: true,
            closeOnCancel: true,
            confirmButtonText: 'Yes',
            cancelButtonText: "Cancel"
        }, 
        function(t) {

            if(t){
                window.location.href = url;
            }

          //  t ? swal('success title', 'success msg', "success") : swal('', '', "error");

        })

        return false;
    }
</script>

</body>

</html>
