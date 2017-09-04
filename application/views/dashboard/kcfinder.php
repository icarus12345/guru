<?php 
$this->CI =& get_instance();
?>
<!-- <div class=" fluid"> -->
    <div class="modal-body">
        <!-- <h3 class="page-title">
            Dashboard <small><?php echo $settings[$sid]->title; ?></small>
        </h3> -->
        <div class="page-bar">
            <ul class="breadcrumb">
                <li>
                    <i class="icon-home"></i>
                    <a href="<?php echo base_url('dashboard') ?>">Dashboard</a>
                </li>
                <li>
                    <a href="#">KCFinder</a>
                </li>
            </ul>
        </div>
        <div class="secondary-box" id="entrys-list">
            <div class="modal-header">
                <h4>
                    KCFinder <small>File Management</small>
                </h4>
                <div class="modal-action">
                    <div class="dropdown pull-right">
                        <a href="JavaScript:" class="icon-options-vertical" data-toggle="dropdown" title="Show more action"></a>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                            <li><a href="#"><span class="icon-refresh"></span> Refresh</a></li>
                            <li><a href="#"><span class="icon-settings"></span> Setting</a></li>
                            <li role="separator" class="divider"></li>
                            <li><a href="#"><span class="icon-question"></span> Help</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <iframe src="/lib/kcfinder/browse.php?lang=en" class="kcfinder-frame" id="kcfinder-frame" style="border:0"></iframe>
            <script type="text/javascript">
            $(document).ready(function(){
                $('#kcfinder-frame').css({
                    height: $(window).height() - 12*2 - 90 - 46 - 30 - 20,
                    width:'100%'
                })
            })
            </script>
        </div>
    </div>
<!-- </div> -->