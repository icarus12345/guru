<?php 
$this->CI =& get_instance();
$this->load->view('dashboard/inc/meta');
?>
    <body class="full-page" style="padding:0">
        <!-- <?php $this->load->view('dashboard/inc/header'); ?> -->
        <!-- <div class="container page-container"> -->
            <?php // $this->load->view('dashboard/inc/nav'); ?>
            <!-- <div class="page-content"> -->
                <div class="flex fluid">
                    <div class="modal-overlay open"></div>
                    <div class="login-box secondary-box" >
                        <div class="modal-header">
                            <h4>Error <small>Access denied</small></h4>
                        </div>
                        <div class="modal-body" id="login-dialog">
                            <div>You don't have permission to access this page.</div>
                        </div>
                    </div>
                </div>
            <!-- </div> -->
        <!-- </div> -->
        <?php $this->load->view('dashboard/inc/footer'); ?>
        <script type="text/javascript">
            $(document).ready(function(){
                $('#login-dialog .validation-frm').validationEngine({
                    'scroll': false,
                    'isPopup' : true,
                    validateNonVisibleFields:true
                });
            })

        </script>
        