<form name="detail-setting-frm" id="detail-setting-frm" target="integration_asynchronous" class="validation-frm">
    <input 
        type="hidden" 
        name="id" 
        value="<?php echo $entry_detail->id; ?>" 
        >
    <input 
        type="hidden" 
        name="alias" 
        value="<?php echo $entry_detail->alias; ?>" 
        id="detail-setting-alias">
    <input 
        type="hidden" 
        name="type" 
        value="<?php echo $type; ?>" >
    <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class="active">
            <a href="#module-tab-info" aria-controls="module-tab-info" role="tab" data-toggle="tab">
                Module Info
            </a>
        </li>
        <li role="presentation" class="">
            <a href="#module-tab-grid" aria-controls="module-tab-grid" role="tab" data-toggle="tab">
                Grid
            </a>
        </li>
        <li role="presentation" class="">
            <a href="#module-tab-bind" aria-controls="module-tab-bind" role="tab" data-toggle="tab">
                Binds
            </a>
        </li>
    </ul>
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="module-tab-info">
            <div class="row half pull-top">
                <div class="col-xs-6 half">
                    <div class="pull-bottom">
                        <div>Title :(*)</div>
                        <div class="control-group">
                            <input 
                                type="text"
                                class="form-control validate[required,minSize[2],maxSize[50]]" 
                                placeholder=""
                                id="detail-setting-title"
                                name="title"
                                onblur="App.Helper.Alias(this)"
                                value="<?php echo $entry_detail->title; ?>" >
                        </div>
                    </div>
                </div>
                <div class="col-xs-6 half">
                    <div class="pull-bottom">
                        <div>Group :(*)</div>
                        <div class="control-group">
                            <input 
                                type="text"
                                class="form-control validate[required,minSize[2],maxSize[50]]" 
                                placeholder=""
                                id="detail-setting-site"
                                name="data[group]"
                                value="<?php echo $entry_detail->data['group']; ?>" >
                        </div>
                    </div>
                </div>
            </div>
            <div class="row half">
                <div class="col-xs-4 half">
                    <div class="pull-bottom">
                        <div>Size :(*)</div>
                        <div class="control-group">
                            <div>
                                <select 
                                    name="data[size]" 
                                    class="form-control selectpicker"
                                    data-putto="#frm-err-data-size"
                                    data-live-search="true"
                                    data-size="10"
                                    >
                                    <option value="">Open page inside</option>
                                    <optgroup label="Open on Popup">
                                        <option value="240" 
                                            <?php echo $entry_detail->data['size'] == '240'?'selected="1"':''; ?>
                                            >Small (240)</option>
                                        <option value="320" 
                                            <?php echo $entry_detail->data['size'] == '320'?'selected="1"':''; ?>
                                            >Smaller (320)</option>
                                        <option value="480" 
                                            <?php echo $entry_detail->data['size'] == '480'?'selected="1"':''; ?>
                                            >Normal (480)</option>
                                        <option value="520" 
                                            <?php echo $entry_detail->data['size'] == '520'?'selected="1"':''; ?>
                                            >Big (520)</option>
                                        <option value="640" 
                                            <?php echo $entry_detail->data['size'] == '640'?'selected="1"':''; ?>
                                            >Bigger (640)</option>
                                        <option value="720" 
                                            <?php echo $entry_detail->data['size'] == '720'?'selected="1"':''; ?>
                                            >Verry big (720)</option>
                                    </optgroup>
                                    <!--
                                    <?php if($setting_data): ?>
                                    <?php foreach ($setting_data as $key => $foo): ?>
                                        <option 
                                            <?php echo $foo->id == $item->data.id ? 'selected="1"' : ''; ?>
                                            value="<?php echo  $foo->id; ?>">
                                                <?php echo  $foo->title; ?>
                                        </option>
                                    <?php endforeach; ?>
                                    <?php endif; ?>
                                    -->
                                </select>
                            </div>
                            <div id="frm-err-data-size"></div>
                        </div>
                    </div>
                </div>
                <div class="col-xs-4 half">
                    <div class="pull-bottom">
                        <div>Storage at :(*)</div>
                        <div class="control-group">
                            <div>
                                <input 
                                    type="text" 
                                    name="data[storage]" 
                                    class="form-control validate[required]"
                                    data-putto="#frm-err-data-storage"
                                    value="<?php echo $entry_detail->data['storage']; ?>" 
                                    />
                            </div>
                            <div id="frm-err-data-storage"></div>
                        </div>
                    </div>
                </div>
                <div class="col-xs-4 half">
                    <div class="pull-bottom">
                        <div>Prefix :(*)</div>
                        <div class="control-group">
                            <div>
                                <input 
                                    type="text" 
                                    name="data[prefix]" 
                                    class="form-control"
                                    data-putto="#frm-err-data-storage"
                                    value="<?php echo $entry_detail->data['prefix']; ?>" 
                                    />
                            </div>
                            <div id="frm-err-data-storage"></div>
                        </div>
                    </div>
                </div>
                <div class="col-xs-3 half">
                    <div class="pull-bottom">
                        <div>Privilege :(*)</div>
                        <div class="control-group">
                            <div>
                                <select multiple="" 
                                    name="data[privieges]" 
                                    class="form-control selectpicker"
                                    >
                                    <option value="">All</option>
                                    <?php
                                    $authority = $entry_detail->data['privieges'];
                                    if(empty($authority)) $authority = [];
                                    $levels = array(
                                        '1' => 'Admin Superviser',
                                        '2' => 'Superviser',
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
                                    foreach ($privileges as $key => $p) {
                                        // $authority = explode(',', $entry_detail->data['privieges']);
                                        echo '<option '.(in_array($p->id,$authority)?'selected=1':'').' value="'.$p->id.'">';
                                        echo $p->name;
                                        echo '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xs-8 half">
                    <div class="pull-bottom">
                        <div>Functions :</div>
                        <div class="chks">
                            <!-- <select 
                                name="data[add]" 
                                class="form-control selectpicker validate[required]"
                                >
                                <option value="1">Enable</option>
                                <option value="0">Disable</option>
                            </select> -->
                            <label class="chk">
                                <input 
                                    type="checkbox" name="data[add]" 
                                    <?php echo $entry_detail->data['add']=="true"?'checked':''; ?>
                                    >
                                <span>Add</span>
                            </label>
                            <label class="chk">
                                <input 
                                    type="checkbox" name="data[edit]"
                                    <?php echo $entry_detail->data['edit']=="true"?'checked':''; ?>
                                    >
                                <span>Edit</span>
                            </label>
                            <label class="chk">
                                <input 
                                    type="checkbox" name="data[delete]"
                                    <?php echo $entry_detail->data['delete']=="true"?'checked':''; ?>
                                    >
                                <span>Delete</span>
                            </label>
                            <label class="chk">
                                <input 
                                    type="checkbox" name="data[companyrequired]"
                                    <?php echo $entry_detail->data['companyrequired']=="true"?'checked':''; ?>
                                    >
                                <span>Company Require</span>
                            </label>
                            <!-- <label class="chk">
                                <input 
                                    type="checkbox" name="data[unique]"
                                    <?php echo $entry_detail->data['unique']=="true"?'checked':''; ?>
                                    >
                                <span>Unique</span>
                            </label>
                            <label class="chk">
                                <input 
                                    type="checkbox" name="data[showthumb]"
                                    <?php echo $entry_detail->data['showthumb']=="true"?'checked':''; ?>
                                    >
                                <span>Show Thumb</span>
                            </label> -->
                        </div>
                    </div>
                </div>
                
            </div>
            <div class="row half">
                <div class="col-xs-4 half">
                    <div class="pull-bottom">
                        <div>Type :(*)</div>
                        <div class="control-group">
                            <input 
                                type="text" 
                                class="form-control validate[minSize[4],maxSize[50]]" 
                                placeholder="" name="data[type]" 
                                value="<?php echo $entry_detail->data['type']; ?>" >
                        </div>
                    </div>
                </div>
                <div class="col-xs-2 half">
                    <div class="pull-bottom">
                        <div>View :</div>
                        <div class="control-group">
                            <div>
                                <select 
                                    name="data[typeviewer]" 
                                    class="form-control selectpicker"
                                    data-putto="#frm-err-data-typeviewer"
                                    >
                                    <option value="">None</option>
                                    <option value="string"
                                        <?php echo $entry_detail->data['typeviewer'] == 'string'?'selected="1"':''; ?>
                                        >String</option>
                                    <option value="list" 
                                        <?php echo $entry_detail->data['typeviewer'] == 'list'?'selected="1"':''; ?>
                                        >List</option>
                                </select>
                            </div>
                            <div id="frm-err-data-typeviewer"></div>
                        </div>
                    </div>
                </div>
                <div class="col-xs-4 half">
                    <div class="pull-bottom">
                        <div>Category Type :</div>
                        <div class="control-group">
                            <input 
                                type="text" 
                                class="form-control validate[maxSize[50]]" 
                                placeholder="" name="data[catetype]" 
                                value="<?php echo $entry_detail->data['catetype']; ?>" >
                        </div>
                    </div>
                </div>
                <div class="col-xs-2 half">
                    <div class="pull-bottom">
                        <div>View :</div>
                        <div class="control-group">
                            <div>
                                <select 
                                    name="data[cateviewer]" 
                                    class="form-control selectpicker"
                                    data-putto="#frm-err-data-cateviewer"
                                    >
                                    <option value="">None</option>
                                    <option value="list"
                                        <?php echo $entry_detail->data['cateviewer'] == 'list'?'selected="1"':''; ?>
                                        >List</option>
                                    <option value="tree" 
                                        <?php echo $entry_detail->data['cateviewer'] == 'tree'?'selected="1"':''; ?>
                                        >Tree</option>
                                </select>
                            </div>
                            <div id="frm-err-data-cateviewer"></div>
                        </div>
                    </div>
                </div>
            </div>
            <ul class="nav nav-tabs tabs-actions nav-tabs-up" role="tablist" id="tabs-for-group">
                <li role="presentation">
                    <a href="JavaScript:void(0)" id="click-to-add-new-tab"><span class="icon-plus"></span> Add</a></a>
                </li>
            </ul>
            <div class="tab-content" id="tabcontents-for-group"></div>
        </div>
        
    <!-- Tab panes -->
        <div role="tabpanel" class="tab-pane" id="module-tab-grid">
            
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active">
                    <a href="#module-grid-tab-datafield" aria-controls="module-grid-tab-datafield" role="tab" data-toggle="tab">
                        Datafield
                    </a>
                </li>
                <li role="presentation" class="">
                    <a href="#module-grid-tab-columns" aria-controls="module-grid-tab-columns" role="tab" data-toggle="tab">
                        Columns
                    </a>
                </li>
                <li role="presentation" class="">
                    <a href="#module-grid-tab-options" aria-controls="module-grid-tab-options" role="tab" data-toggle="tab">
                        Grid Setting
                    </a>
                </li>
            </ul>
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="module-grid-tab-datafield">
                    <div class="pull-bottom pull-top">
                        <div class="control-group">
                            <textarea 
                                row="10" 
                                type="text" 
                                class="form-control code"
                                placeholder="" 
                                name="data[grid_datafields]" 
                                 ><?php echo ($entry_detail)?$entry_detail->data['grid_datafields']:'[
    {name: "id", type: "int"},
    {name: "title"},
    {name: "status" , type: "bool"},
    {name: "created" , type: "date"},
    {name: "modified" , type: "date"}
]'; ?></textarea>
                        </div>
                    </div>
                </div>
                <div role="tabpanel" class="tab-pane" id="module-grid-tab-columns">
                    <div class="pull-bottom pull-top">
                        <div class="control-group">
                            <textarea 
                                row="10" 
                                type="text" 
                                class="form-control code" 
                                placeholder="" 
                                name="data[grid_columns]" 
                                 ><?php echo ($entry_detail)?$entry_detail->data['grid_columns']:'[{
    text: "Title", datafield: "title", minWidth: 180, sortable: true, columntype: "textbox", filtertype: "textbox", filtercondition: "CONTAINS",
},{
    text: "Status"    , datafield: "status" , cellsalign: "center", width: 44, columntype: "checkbox", threestatecheckbox: false, filtertype: "bool", filterable: true, sortable: true,editable: true
},{
    text: "Created" , datafield: "created", width: 120, cellsalign: "right", filterable: true, columntype: "datetimeinput", filtertype: "range", cellsformat: "yyyy-MM-dd HH:mm:ss", sortable: true,editable: false
}]'; ?></textarea>
                        </div>
                    </div>

                </div>
                <div role="tabpanel" class="tab-pane" id="module-grid-tab-options">
                    <div class="pull-bottom pull-top">
                        <div class="control-group">
                            <textarea 
                                row="10" 
                                type="text" 
                                class="form-control code" 
                                placeholder="" 
                                name="data[grid_options]" 
                                 ><?php echo $entry_detail->data['grid_options']; ?></textarea>
                        </div>
                    </div>

                </div>
            </div>
            <div class="pull-bottom pull-top">
                <div>Debug</div>
                <div class="control-group">
                    <select 
                            name="data[debug]" 
                            class="form-control selectpicker"
                        >
                        <option value="0">No</option>
                        <option value="1" <?php echo $entry_detail->data['debug'] == '1'?'selected="1"':''; ?>>Yes</option>
                    </select>
                </div>
            </div>
        </div>
        <div role="tabpanel" class="tab-pane" id="module-tab-bind">
            <div class="pull-bottom pull-top">
                <div>Binding Url</div>
                <div class="control-group">
                    <input 
                        type="text" 
                        class="form-control validate[minSize[4],maxSize[50]]" 
                        placeholder="" 
                        name="data[binding_url]" 
                        value="<?php echo ($entry_detail)?$entry_detail->data['binding_url']:'/dashboardapi/common/custom_bind'; ?>" >
                </div>
            </div>
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active">
                    <a href="#module-bind-tab-select" aria-controls="module-bind-tab-select" role="tab" data-toggle="tab">
                        Select
                    </a>
                </li>
                <li role="presentation" class="">
                    <a href="#module-bind-tab-from" aria-controls="module-bind-tab-from" role="tab" data-toggle="tab">
                        From
                    </a>
                </li>
                <li role="presentation" class="">
                    <a href="#module-bind-tab-where" aria-controls="module-bind-tab-where" role="tab" data-toggle="tab">
                        Where
                    </a>
                </li>
                <li role="presentation" class="">
                    <a href="#module-bind-tab-order" aria-controls="module-bind-tab-order" role="tab" data-toggle="tab">
                        Order By
                    </a>
                </li>
                <li role="presentation" class="">
                    <a href="#module-bind-tab-map" aria-controls="module-bind-tab-map" role="tab" data-toggle="tab">
                        Columns Map
                    </a>
                </li>
                
            </ul>
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="module-bind-tab-select">
                    <div class="pull-bottom pull-top">
                        <div class="control-group">
                            <textarea 
                                row="10" 
                                type="text" 
                                class="form-control code"
                                placeholder="" 
                                name="data[bind_select]" 
                                 ><?php echo ($entry_detail)?$entry_detail->data['bind_select']:'*'; ?></textarea>
                        </div>
                    </div>
                </div>
                <div role="tabpanel" class="tab-pane" id="module-bind-tab-from">
                    <div class="pull-bottom pull-top">
                        <div class="control-group">
                            <textarea 
                                row="10" 
                                type="text" 
                                class="form-control code" 
                                placeholder="" 
                                name="data[bind_from]" 
                                 ><?php echo ($entry_detail)?$entry_detail->data['bind_from']:''; ?></textarea>
                        </div>
                    </div>

                </div>
                <div role="tabpanel" class="tab-pane" id="module-bind-tab-where">
                    <div class="pull-bottom pull-top">
                        <div class="control-group">
                            <textarea 
                                row="10" 
                                type="text" 
                                class="form-control code" 
                                placeholder="" 
                                name="data[bind_where]" 
                                 ><?php echo $entry_detail->data['bind_where']; ?></textarea>
                        </div>
                    </div>

                </div>
                <div role="tabpanel" class="tab-pane" id="module-bind-tab-map">
                    <div class="pull-bottom pull-top">
                        <div class="control-group">
                            <textarea 
                                row="10" 
                                type="text" 
                                class="form-control code" 
                                placeholder="" 
                                name="data[bind_columns_map]" 
                                 ><?php echo $entry_detail->data['bind_columns_map']; ?></textarea>
                        </div>
                    </div>

                </div>
                <div role="tabpanel" class="tab-pane" id="module-bind-tab-order">
                    <div class="pull-bottom pull-top">
                        <div class="control-group">
                            <textarea 
                                row="10" 
                                type="text" 
                                class="form-control code" 
                                placeholder="" 
                                name="data[bind_order_by]" 
                                 ><?php echo $entry_detail->data['bind_order_by']; ?></textarea>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</form>
<div style="display: none" id="column-detail-dialog">
    <form name="column-detail-frm" id="column-detail-frm" target="integration_asynchronous" class="validation-frm">
        <div class="row half">
            
            <div class="col-xs-4 half">
                <div class="pull-bottom">
                    <div>Field type :</div>
                    <div class="">
                        <select 
                            name="type" 
                            class="form-control selectpicker validate[required]"
                            >
                            <option value="stringalias">String & Alias</option>
                            <option value="string">String</option>
                            <option value="text">Text</option>
                            <option value="html">HTML</option>
                            <option value="image">Image</option>
                            <option value="radio">Radio</option>

                            <option value="privilege">Privilege</option>
                            <option value="checkbox">Checkbox</option>

                            <option value="date">Date</option>
                            <option value="datetime">Date Time</option>
                            <option value="dropdown">Dropdown</option>
                            <option value="multidropdown">Multi Dropdown</option>
                            <option value="parent">Parent Tree</option>
                            <option value="treedropdown">Tree Dropdown</option>
                            <option value="group-server-dropdown">Group Server Dropdown</option>
                            <option value="group-server-multidropdown">Group Server Multi Dropdown</option>
                            <option value="server-dropdown">Server Dropdown</option>
                            <option value="server-multidropdown">Server Multi Dropdown</option>
                            <option value="map">Map</option>

                            <option value="catelist">List Category</option>
                            <option value="catetree">Tree Category</option>

                            <option value="list">List</option>
                            <option value="grid">Grid</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-xs-4 half" -data-box="col">
                <div class="pull-bottom">
                    <div>Col :</div>
                    <div class="">
                        <input 
                            type="number" 
                            class="form-control validate[required,min[1],max[12]]" 
                            placeholder="" name="col" 
                            value="" >
                    </div>
                </div>
            </div>
            
            <div class="col-xs-4 half">
                <div class="pull-bottom">
                    <div>Column Data :</div>
                    <div class="">
                        <select 
                            name="columndata"
                            class="form-control selectpicker"
                            >
                            <option value="">Field</option>
                            <option value="0">Data</option>
                            <option value="1">Long Data</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-xs-6 half">
                <div class="pull-bottom">
                    <div>Field name :(*)</div>
                    <div class="control-group">
                        <input 
                            type="text" 
                            class="form-control validate[required,maxSize[50]]" 
                            placeholder="" name="name" 
                            value="" >
                    </div>
                </div>
            </div>
            <div class="col-xs-6 half">
                <div class="pull-bottom">
                    <div>Display text :(*)</div>
                    <div class="control-group">
                        <input 
                            type="text" 
                            class="form-control validate[required,maxSize[50]]" 
                            placeholder="" name="title" 
                            value="" >
                    </div>
                </div>
            </div>
        </div>
        <div data-box="data" class="pull-bottom">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Value</th>
                        <th>Display</th>
                        <th width="20">
                            <a href="JavaScript:App.Module.ShowColumnDataItemDialog()" class="icon-plus"></a>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    
                </tbody>
            </table>
        </div>
        <fieldset data-box="valid">
            <legend>Validation</legend>
            <div class="pull-bottom">
                <div>Client validation :</div>
                <div class="control-group">
                    <input 
                        type="text" 
                        class="form-control " 
                        placeholder="" name="client" 
                        value="" >
                </div>
            </div>
            <div class="pull-bottom">
                <div>Server validation :</div>
                <div class="control-group">
                    <input 
                        type="text" 
                        class="form-control " 
                        placeholder="" name="server" 
                        value="" >
                </div>
            </div>
        </fieldset>
        <div class="pull-bottom" data-box="sid">
            <div>Setting Entry :</div>
            <div class="">
                <select 
                    name="sid" 
                    class="form-control selectpicker"
                    >
                    <option value="">[   Nothing   ]</option>
                    <?php foreach($setting_list as $foo): ?>
                    <option 
                        data-content="<?php echo $foo->title; ?> - <small><i><?php echo $foo->data['group']; ?></i></small>"
                        value="<?php echo $foo->id; ?>"
                        ><?php echo $foo->title; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="pull-bottom">
            <div>Onchange :</div>
            <div class="">
                <input 
                    type="text" 
                    class="form-control " 
                    placeholder="" name="onchange" 
                    value="" >
            </div>
        </div>
        <div class="pull-bottom" data-box="group-dropdown">
            <div>Group Field :</div>
            <div class="">
                <input 
                    type="text" 
                    class="form-control " 
                    placeholder="" name="group_field" 
                    value="" >
            </div>
        </div>
        <div class="pull-bottom" data-box="group-dropdown">
            <div>Group ID :</div>
            <div class="">
                <input 
                    type="text" 
                    class="form-control " 
                    placeholder="" name="group_id" 
                    value="" >
            </div>
        </div>
        <div class="pull-bottom" data-box="group-dropdown">
            <div>Group Join :</div>
            <div class="">
                <input 
                    type="text" 
                    class="form-control " 
                    placeholder="" name="group_join" 
                    value="" >
            </div>
        </div>
        <div class="pull-bottom" data-box="group-dropdown">
            <div>Group on :</div>
            <div class="">
                <input 
                    type="text" 
                    class="form-control " 
                    placeholder="" name="group_on" 
                    value="" >
            </div>
        </div>
    </form>
</div>
<div style="display: none" id="column-data-item-dialog">
    <form 
        name="column-data-item-frm" 
        id="column-data-item-frm" 
        target="integration_asynchronous" 
        class="validation-frm">
        <div class="pull-bottom">
            <div>Value :</div>
            <div class="control-group">
                <input 
                    type="text" 
                    class="form-control " 
                    placeholder="" name="value" 
                    value="" >
            </div>
        </div>
        <div class="">
            <div>Display :</div>
            <div class="control-group">
                <input 
                    type="text" 
                    class="form-control " 
                    placeholder="" name="display" 
                    value="" >
            </div>
        </div>
    </form>
</div>