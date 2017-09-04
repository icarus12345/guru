<?php 
$CI =& get_instance();
$user = $CI->session->userdata('dasbboard_user');
?>
<?php if(empty($entry_setting->data['size'])) : ?>
<div class="-modal-header pull-top pull-bottom">
    <h4>
        <?php echo $entry_setting->title; ?> <small><?php echo 'Preview'; ?></small>
    </h4>
</div>

<div class="-modal-body pull-top">
<?php endif; ?>
<?php
    $prefix = $entry_setting->data['prefix'];
    function _addField($column,$entry_detail,$entry_setting){
        // global $prefix;
        $prefix = $entry_setting->data['prefix'];

        $value = $entry_detail->data[$column['name']];
        $name = 'data['.$column['name'].']';
        $id = 'data-'.$column['name'];
        // if(!empty($column['columndata'])){
            if($column['columndata'] == ''){
                $value = $entry_detail->{$column['name']};
                $name = $column['name'];
                $id = $column['name'];
            } else if($column['columndata'] == '0'){
                $name = 'data['.$column['name'].']';
                $id = 'data-'.$column['name'];
                $value = $entry_detail->data[$column['name']];
            } else if($column['columndata'] == '1'){
                $value = $entry_detail->longdata[$column['name']];
                $name = 'longdata['.$column['name'].']';
                $id = 'longdata-'.$column['name'];
            }
        // }
        if ($column['type'] == 'catetree'): ?>
            <div class="col-xs-<?php echo $column['col']; ?> half">
                <div class="pull-bottom">
                    <div><?php echo $column['title']; ?> :</div>
                    <div class="control-group">
                        <div>
                            <div class="pre"><?php echo $value; ?></div>
                        </div>
                    </div>
                </div>
            </div>
        <?php elseif ($column['type'] == 'radio'): ?>
            
            <div class="col-xs-<?php echo $column['col']; ?> half">
                <div class="pull-bottom">
                    <div><?php echo $column['title']; ?> :</div>
                    <div class="control-group">
                        <div class="rdbs">
                            <?php foreach($column['data'] as $c): ?>
                            <label class="rdb">
                                <input 
                                    type="radio" 
                                    class="<?php echo $column['client']; ?>"
                                    data-putto="#frm-err-data-<?php echo $column['name']; ?>"
                                    name="<?php echo $name; ?>" 
                                    <?php if ($c['value'] == $value){echo 'checked="1"';} ?>
                                    value="<?php echo $c['value']; ?>"
                                    >
                                <span><?php echo $c['display']; ?></span>
                            </label>
                            <?php endforeach; ?>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
            </div>
        <?php elseif ($column['type'] == 'map'): ?>
            <?php 
                $latlon_columns = (explode(',',$column['name']));
                if($column['columndata'] == ''){
                    $lat = $entry_detail->{$latlon_columns[0]};
                    $lon = $entry_detail->{$latlon_columns[1]};
                } else if($column['columndata'] == '0'){
                    $lat = $entry_detail->data[$latlon_columns[0]];
                    $lon = $entry_detail->data[$latlon_columns[1]];
                } else if($column['columndata'] == '1'){
                    $lat = $entry_detail->longdata[$latlon_columns[0]];
                    $lon = $entry_detail->longdata[$latlon_columns[1]];
                }
                
            ?>
            <div class="col-xs-<?php echo $column['col']; ?> half">
                <div class="pull-bottom">
                    <div><?php echo $column['title']; ?> :<span data-latlonpreview="<?php echo $latname.$lonname; ?>"><?php echo $lat; ?> <?php echo $lon; ?></span></div>
                    <div class="control-group">
                        <div style="height:200px;background:#ccc"
                            data-googlemap="1" 
                            data-lat="<?php echo $lat; ?>" 
                            data-latcolumn="<?php echo $latlon_columns[0]; ?>" 
                            data-lon="<?php echo $lon; ?>"
                            data-loncolumn="<?php echo $latlon_columns[1] ?>"
                            ></div>
                    </div>
                </div>
            </div>

        <?php elseif ($column['type'] == 'checkbox'): ?>
        
            <div class="col-xs-<?php echo $column['col']; ?> half">
                <div class="pull-bottom">
                    <div><?php echo $column['title']; ?> :</div>
                    <div class="chks">
                        <label class="chk">
                            <input 
                                type="checkbox" 
                                name="<?php echo $name; ?>" 
                                <?php if ($value){echo 'checked="1"';} ?>
                                value="1"
                                >
                            <span>&nbsp;</span>
                        </label>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
        <?php 
        elseif (
            $column['type'] == 'server-dropdown' ||
            $column['type'] == 'group-server-dropdown'
            ): 
        ?>
            <div class="col-xs-<?php echo $column['col']; ?> half">
                <div class="pull-bottom">
                    <div><?php echo $column['title']; ?> :</div>
                    <div class="control-group">
                        <div>
                            <div class="pre">
                                <?php if(is_array($column['sub_data']))  foreach($column['sub_data'] as $c): ?>
                                        <?php if ($c->id == $value){?>
                                        <?php echo $c->title; ?>
                                        <?php } ?>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <div id="frm-err-data-<?php echo $column['name']; ?>"></div>
                    </div>
                </div>
            </div>
        <?php 
        elseif (
            $column['type'] == 'server-multidropdown' ||
            $column['type'] == 'group-server-multidropdown'
            ): 
            ?>
            <div class="col-xs-<?php echo $column['col']; ?> half">
                <div class="pull-bottom">
                    <div><?php echo $column['title']; ?> :</div>
                    <div class="control-group">
                        <div>
                            <div class="pre">
                            <?php
                            $value = explode(',',$value);
                             ?>
                                <?php if(is_array($column['sub_data'])) foreach($column['sub_data'] as $c): ?>
                                        <?php if (in_array($c->id ,$value)){?>
                                        <?php echo '<div>',$c->title,'</div>'; ?>
                                        <?php } ?>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <div id="frm-err-data-<?php echo $column['name']; ?>"></div>
                    </div>
                </div>
            </div>
        <?php 
        elseif (
            $column['type'] == 'string' ||
            $column['type'] == 'date' ||
            $column['type'] == 'datetime' ||
            $column['type'] == 'stringalias'
        ): ?>
            
            <div class="col-xs-<?php echo $column['col']; ?> half">
                <div class="pull-bottom">
                    <div><?php echo $column['title']; ?> :</div>
                    <div class="control-group">
                        <div>
                            <div class="pre"><?php echo $value; ?></div>
                        </div>
                    </div>
                </div>
            </div>
        <?php elseif ($column['type'] == 'privilege'): ?>
            <div class="col-xs-<?php echo $column['col']; ?> half">
                <div class="pull-bottom">
                    <div><?php echo $column['title']; ?> :</div>
                    <div class="control-group">
                        <div class="pre"><?php
                                $authority = explode(',',$value);
                                $user_authority = explode(',',$user->ause_authority);
                                if(empty($authority)) $authority = [];
                                $levels = array(
                                    '1' => 'Admin Superviser',
                                    '2' => 'Superviser',
                                    // '3' => 'User'
                                    );
                                foreach ($levels as $key=> $value) {
                                    // if( in_array($key,$user_authority)){
                                    if(in_array($key,$authority)) echo "<div>$value</div>";
                                    // }
                                } 
                                if(is_array($column['privileges'])) foreach($column['privileges'] as $c):
                                    if( in_array($c->id, $authority) ) echo "<div>{$c->name}</div>"; 
                                endforeach;?></div>
                    </div>
                </div>
            </div>
        <?php elseif ($column['type'] == 'parent'): ?>
            <?php 
                $id = $entry_detail->{$prefix.'id'};
            ?>
            <div class="col-xs-<?php echo $column['col']; ?> half">
                <div class="pull-bottom">
                    <div><?php echo $column['title']; ?> :</div>
                    <div class="control-group">
                        <div class="pre"><?php 
                        if(empty($value)) echo 'Root';
                            if(is_array($column['parents'])) 
                                foreach($column['parents'] as $c):
                                    if ($c->id == $value) echo $c->display; 
                                endforeach; 
                            ?></div>
                    </div>
                </div>
            </div>
        <?php elseif ($column['type'] == 'text'): ?>
            <div class="col-xs-<?php echo $column['col']; ?> half">
                <div class="pull-bottom">
                    <div><?php echo $column['title']; ?> :</div>
                    <div class="control-group">
                        <div class="pre" style="max-height:240px;overflow:auto"><?php echo $value; ?></div>
                    </div>
                </div>
            </div>
        <?php elseif ($column['type'] == 'html'): ?>
            <div class="col-xs-<?php echo $column['col']; ?> half">
                <div class="pull-bottom">
                    <div><?php echo $column['title']; ?> :</div>
                    <div class="control-group">
                        <div class="pre" style="max-height:240px;overflow:auto"><div class="cke_editable"><?php echo $value; ?></div></div>
                    </div>
                </div>
            </div>
        <?php elseif ($column['type'] == 'image'): ?>
            <div class="col-xs-<?php echo $column['col']; ?> half">
                <div><?php echo $column['title']; ?> :</div>
                <div class="pre">
                    <div class="pull-bottom" style="position: relative;">
                       <div style="margin:auto;width: 120px;height: 120px;">
                            <div id="see-preview-common-thumb<?php echo $id; ?>" 
                                style="padding-bottom: 100%;border:1px solid #ccc;background-image:url(<?php echo $value; ?>)" class="cover"></div>
                        </div>
                    </div>
                </div>
            </div>
        <?php elseif ($column['type'] == 'multidropdown'): ?>
            <div class="col-xs-<?php echo $column['col']; ?> half">
                <div class="pull-bottom">
                    <div><?php echo $column['title']; ?> :</div>
                    <div class="control-group">
                        <div class="pre"><?php echo $value; ?></div>
                    </div>
                </div>
            </div>
        <?php elseif ($column['type'] == 'dropdown'): ?>
            <div class="col-xs-<?php echo $column['col']; ?> half">
                <div class="pull-bottom">
                    <div><?php echo $column['title']; ?> :</div>
                    <div class="control-group">
                        <div class="pre"><?php echo $value; ?></div>
                    </div>
                </div>
            </div>
        <?php elseif ($column['type'] == 'list'): ?>
            <div class="col-xs-<?php echo $column['col']; ?> half">
                <div class="pull-bottom">
                    <div><?php echo $column['title']; ?> :</div>
                    
                    <div>
                        <div class="pre">
                        <?php 
                         if($value) foreach ($value as $subitem) {
                                ?>
                                <div class="pre"><?php echo $subitem['title']; ?></div>
                                <?php
                            }
                        ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    <?php
    }
?>
<form>
    <?php if($entry_setting->data['groups']): ?>
    <ul class="nav nav-tabs" role="tablist" <?php if(count($entry_setting->data['groups'])<=1 && $entry_setting->data['debug'] != 1) echo 'style="display:none"'; ?>>
        <?php 
        $num = 0;
        foreach ($entry_setting->data['groups'] as $key => $g) :
            ?>
        <li role="presentation" class="<?php echo $key==0?'active':''; ?>">
            <a href="#see-entry-tab-<?php echo $key; ?>" aria-controls="see-entry-tab-<?php echo $key; ?>" role="tab" data-toggle="tab">
            <?php echo $g['name']; ?>
            </a>
        </li>
        <?php
        $entry_setting->data['groups'][$key]['data'] = array();
        for($i=0;$i<$g['length'];$i++){
            $entry_setting->data['groups'][$key]['data'][] = $entry_setting->data['columns'][$num++];
        }
        endforeach;
        if($entry_setting->data['debug'] == 1){
            ?>
        <li role="presentation" class="">
            <a href="#see-entry-tab-debug" aria-controls="see-entry-tab-debug" role="tab" data-toggle="tab">
            Serialize
            </a>
        </li>
        <?php
        }
        ?>
    </ul>
    <!-- Tab panes -->
    <div class="tab-content">
        <?php foreach ($entry_setting->data['groups'] as $key => $g) { ?>
        <div role="tabpanel" class="tab-pane <?php echo $key==0?'active':''; ?>" id="see-entry-tab-<?php echo $key; ?>">
            <div class="row half pull-top">
            <?php 
            if(
                $key==0 && 
                $entry_setting->data['companyrequired']  == 'true' &&
                empty($user->ause_company_id)
            ) : 
            ?>
                <div class="col-xs-12 half">
                    <div class="pull-bottom">
                        <div>Company :</div>
                        <div class="control-group">
                            <div class="pre"><?php 
                                foreach($entry_setting->data['companies'] as $c){ 
                                    if ($c->id == $entry_detail->company_id){
                                        echo $c->title;
                                    }
                                }
                                ?></div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
            <?php 
            foreach($g['data'] as $i => $column): 
                _addField($column,$entry_detail,$entry_setting);
            endforeach;
            ?>
            </div>
        </div>
        <div role="tabpanel" class="tab-pane " id="see-entry-tab-debug">
            <div class="pre"><?php 
            if($entry_setting->data['debug'] == 1){ 
                echo $debug_table;
            } 
            ?>
            </div>
        </div>
        <?php } ?>
    </div>
    <?php else: ?>
    
            <div class="row half info-box pull-top">
                <div class="col-xs-<?php echo empty($entry_setting->data['catetype'])?'12':'6'; ?> half">
                    <div class="pull-bottom">
                        <div>Title :(*)</div>
                        <div class="control-group">
                            <div class="pre"><?php echo $entry_detail->{$prefix.'title'}; ?></div>
                        </div>
                    </div>
                </div>
                <?php if(!empty($entry_setting->data['categories'])): ?>
                <div class="col-xs-6 half">
                    <div class="pull-bottom">
                        <div>Category :(*)</div>
                        <div class="control-group">
                            <div class="pre">
                                <?php foreach($entry_setting->data['categories'] as $c): ?>
                                    <?php if ($c->id == $entry_detail->category) echo $c->title; ?>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
                <?php 
                if($entry_setting->data['columns']):

                foreach($entry_setting->data['columns'] as $i => $column): 
                _addField($column,$entry_detail,$entry_setting);
                endforeach; 
                endif;
                ?>
            </div>
    <?php endif; ?>
</form>
<?php if($entry_detail): ?>
<div class="pre">
Author:<?php echo $users[$entry_detail->{$prefix.'author'}]->ause_name; ?><br/>
Created: <?php echo $entry_detail->{$prefix.'created'}; ?><br/>
Modified: <?php echo $entry_detail->{$prefix.'modified'}; ?>
</div>
<?php endif; ?>
<?php if(empty($entry_setting->data['size'])) : ?>
</div>

<div class="-modal-footer pull-top text-center">
    <?php if($entry_detail) : ?>
    <button class="btn btn-outline-secondary" onclick="App.Common.ShowDetailDialog('<?php echo $entry_detail->{$prefix.'id'}; ?>')">Edit</button>
    <?php endif; ?>
    <button class="btn btn-link" onclick="App.Common.BackSee()">Back</button>
</div>
<?php endif; ?>