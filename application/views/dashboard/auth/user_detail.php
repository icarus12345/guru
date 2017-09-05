<?php 
$CI =& get_instance();
$user = $CI->session->userdata('dasbboard_user');
$user_privileges = explode(',',$user->ause_authority);
?>
<form name="user-detail-frm" id="user-detail-frm" target="integration_asynchronous" class="validation-frm">
    <input 
        type="hidden" 
        name="ause_id" 
        id="ause_id" 
        value="<?php echo $user_detail->ause_id; ?>" 
        >
    <div class="row half">
    <?php 
    $privileges = array('1');
    $check = !!array_intersect($user_privileges,$privileges);
    if($check){
    ?>
        <div class="col-xs-5 half">
            <div class="pull-bottom">
                <div>Company :(*)</div>
                <div class="control-group">
                    <select 
                        name="ause_company_id" 
                        class="form-control selectpicker "
                        data-putto="#frm-err-data-company"
                        data-live-search="true"
                        data-size="10"
                        >
                        <option value="">Nothing Selected</option>
                        <?php foreach ($companys as $c) {
                            ?>
                        <option value="<?php echo $c->id; ?>" <?php echo $user_detail->ause_company_id==$c->id?'selected=1':'' ; ?>>
                            <?php echo $c->title; ?>
                        </option>
                            <?php
                        } ?>
                    </select>
                </div>
                <div id="frm-err-data-company"></div>
            </div>
        </div>
        <?php }else{ ?>
        <input 
            type="hidden" 
            name="ause_company_id" 
            value="<?php echo $CI->session->userdata('dasbboard_user')->ause_company_id; ?>" 
            >
        <?php } ?>
        <div class="col-xs-4 half">
            <div class="pull-bottom">
                <div>Authority :(*)</div>
                <div class="control-group">
                    <select multiple="" 
                        name="ause_authority" 
                        class="form-control selectpicker "
                        data-putto="#frm-err-data-company"
                        data-live-search="true"
                        data-size="10"
                        >
                        <option value="">Nothing Selected</option>
                        <?php 
                        $authority = explode(',', $user_detail->ause_authority);
                        $levels = array(
                            '1' => 'Admin Superviser',
                            '2' => 'Superviser',
                            '3' => 'File Management',
                            // '3' => 'User'
                            );
                        foreach ($levels as $key=> $value) {
                            if($user->ause_authority<=$key){
                            ?>
                        <option value="<?php echo $key; ?>" <?php echo in_array($key,$authority)?'selected=1':'' ; ?>>
                            <?php echo $value; ?>
                        </option>
                            <?php
                            }
                        } 
                        ?>
                        <?php
                        foreach ($privileges as $key => $p) {
                            echo '<option '.(in_array($p->id,$authority)?'selected=1':'').' value="'.$p->id.'">';
                            echo $p->name;
                            echo '</option>';
                        }
                        ?>
                    </select>
                </div>
                <div id="frm-err-data-company"></div>
            </div>
        </div>
        <div class="col-xs-3 half">
            <div class="pull-bottom">
                <div>Status :(*)</div>
                <div class="control-group">
                    <select 
                        name="ause_status" 
                        class="form-control selectpicker "
                        data-putto="#frm-err-data-status"
                        data-live-search="true"
                        data-size="10"
                        >
                        <option value="false" <?php echo $user_detail->ause_status=='0'?'selected=1':'' ; ?>>Block</option>
                        <option value="true" <?php echo $user_detail->ause_status=='true'?'selected=1':'' ; ?>>Actived</option>
                    </select>
                </div>
                <div id="frm-err-data-status"></div>
            </div>
        </div>
        <div class="col-xs-6 half">
            <div class="pull-bottom">
                <div>Username :(*)</div>
                <div class="control-group">
                    <input 
                        type="text"
                        class="form-control validate[required,minSize[4],maxSize[50]]" 
                        placeholder=""
                        name="ause_username"
                        value="<?php echo $user_detail->ause_username ; ?>" >
                </div>
            </div>
        </div>

        <div class="col-xs-6 half">
            <div class="pull-bottom">
                <div>Display :(*)</div>
                <div class="control-group">
                    <input 
                        type="text"
                        class="form-control validate[required,minSize[4],maxSize[50]]" 
                        placeholder=""
                        name="ause_name"
                        value="<?php echo $user_detail->ause_name ; ?>" >
                </div>
            </div>
        </div>
        
        
        
        <div class="col-xs-6 half">
            <div class="pull-bottom">
                <div>New Password :(*)</div>
                <div class="control-group">
                    <input 
                        type="password" id="ause_password"
                        class="form-control validate[minSize[4],maxSize[50]]" 
                        placeholder=""
                        name="ause_password"
                        value="" >
                </div>
            </div>
        </div>
        <div class="col-xs-6 half">
            <div class="">
                <div>Confirm Password :(*)</div>
                <div class="control-group">
                    <input 
                        type="password"
                        class="form-control validate[equals[ause_password]]" 
                        placeholder=""
                        name="confirmpassword"
                        value="" >
                </div>
            </div>
        </div>
        <div class="col-xs-12 half">
            <div class="pull-bottom">
                <div>Email :(*)</div>
                <div class="control-group">
                    <input 
                        type="text"
                        class="form-control validate[required,custom[email]]" 
                        placeholder=""
                        name="ause_email"
                        value="<?php echo $user_detail->ause_email ; ?>" >
                </div>
            </div>
        </div>
        <div class="col-xs-12 half">
            <div class="pull-bottom">
                <div>Avatar :(*)</div>
                <div class="control-group">
                    <div class="input-append">
                        <input type="text" 
                            class="form-control" 
                            data-putto="#frm-err-ause_picture"
                            name="ause_picture"
                            id="ause_picture"
                            value="<?php echo $user_detail->ause_picture ; ?>"
                            >
                        <span class="add-on" onclick="App.KCFinder.BrowseServer('#ause_picture')">
                            <i class="fa fa-image"></i>
                        </span>
                    </div>
                    <div id="frm-err-ause_picture"></div>
                </div>
            </div>
        </div>
    </div>
</form>