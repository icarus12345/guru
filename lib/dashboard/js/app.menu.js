$(document).ready(function(){
    App.Menu = (function(){
        var prefix;
        var me= {}
        var gridElm;
        var URI = {
            binding : App.BaseUrl + 'dashboardapi/menu/bind',
            detail  : App.BaseUrl + 'dashboardapi/menu/detail',
            see  : App.BaseUrl + 'dashboardapi/menu/see',
            subdetail  : App.BaseUrl + 'dashboardapi/menu/subdetail',
            commit  : App.BaseUrl + 'dashboardapi/menu/commit',
            sendlatest  : App.BaseUrl + 'dashboardapi/menu/sendlatest',
            sendoldest  : App.BaseUrl + 'dashboardapi/menu/sendoldest',
            update  : App.BaseUrl + 'dashboardapi/menu/update',
            delete  : App.BaseUrl + 'dashboardapi/menu/delete',
            catebinding : App.BaseUrl + 'dashboardapi/menu/bind',
        };

        var theme = 'metro';
        me.cate = {}
        me.bindingCate = function(){
            me.cate.datafields = [
                {name: 'id'   , type: 'int'},
                {name: 'title'    },
                {name: 'status' , type: 'bool'},
                {name: 'insert' , type: 'date'},
                {name: 'update' , type: 'date'},
            ];
            me.cate.source = {
                datatype    : "json",
                type        : "POST",
                datafields  : me.cate.datafields,
                url         : URI.catebinding,
                id          :'id',
                root        : 'rows'
            };
            me.cate.dataAdapter = new $.jqx.dataAdapter(me.cate.source, {
                autoBind: true,
                formatData: function (data) {
                    data.type = App.Menu.settings[App.Menu.sid].data.catetype || '';
                    return data;
                },
                beforeLoadComplete: function (records) {
                    var data = new Array();
                    for (var i = 0; i < records.length; i++) {
                        records[i].value = records[i].id;
                        records[i].label = records[i].title;
                        records[i].category = records[i].id;
                        records[i].title = records[i].title;
                        data.push(records[i]);
                    }
                    return data;
                },
                loadComplete: function(records){
                    me.bindingEntry();
                },
                loadError: function(xhr, status, error) {
                    toastr.error("<b>Status</b>:" + xhr.status + "<br/><b>ThrownError</b>:" + error + "<br/>",'Error');
                }
            });
        };
        me.bindingEntry = function(){
            prefix = App.Menu.settings[App.Menu.sid].data.prefix;
            if(App.Menu.settings[App.Menu.sid].data.binding_url){
                URI.binding = App.Menu.settings[App.Menu.sid].data.binding_url;
            }
            me.datafields = [
                // {name: 'id', type: 'int'},
                // {name: 'title'},
                // {name: 'category'},
                // {name: 'status' , type: 'bool'},
                // {name: 'created' , type: 'date'},
                // {name: 'modified' , type: 'date'},
                // {name: 'lock' , type: 'bool'},
            ];
            if(App.Menu.settings[App.Menu.sid].data.grid_datafields){
                var extend_datafields = eval(App.Menu.settings[App.Menu.sid].data.grid_datafields);
                if(extend_datafields) for(var i in extend_datafields){
                    var f = extend_datafields[i];
                    var flag = false;
                    for(var j in me.datafields){
                        if(me.datafields[j].name == f.name){
                            flag = true;
                        }
                    }
                    if(flag){
                        $.extend(me.datafields[j],f);
                    } else{
                        me.datafields.push(f);
                    }
                }
            }
            console.log(me.datafields)
            if(!!App.Menu.settings[App.Menu.sid].data.catetype){
               me.datafields.push({name: 'cattitle',value:'category',values : { source: me.cate.dataAdapter.records, value: 'id', name: 'title' }});
            }
            
            me.source = {
                datatype    : "json",
                type        : "POST",
                datafields  : me.datafields,
                url         : URI.binding,
                id          :'id',
                root        : 'rows',
                filter: function() {
                    $(gridElm).jqxGrid('updatebounddata', 'filter');
                },
                sort: function() {
                    $(gridElm).jqxGrid('updatebounddata');
                }
            };
            me.dataAdapter = new $.jqx.dataAdapter(me.source, {
                formatData: function (data) {
                    data.sid = App.Menu.sid;
                    return data;
                },
                loadError: function(xhr, status, error) {
                    toastr.error("<b>Status</b>:" + xhr.status + "<br/><b>ThrownError</b>:" + error + "<br/>",'Error');
                }
            });
            me.columns = [{
                text: '', datafield: prefix + 'id', width: 52, filterable: false, sortable: true,editable: false,
                cellsrenderer: function (row, columnfield, value, defaulthtml, columnproperties) {
                    var str = "";
                    if (value && value > 0) {
                        try {
                            str += "<a href='JavaScript:void(0)'"+
                            "style='padding: 5px; float: left;margin-top: 2px;' " +
                            'data-toggle="dropdown" data-target="#common-menu"'+
                            " "+ 
                            "title='Action :" + value + "'><i class=\"icon-options-vertical\"></i></a>\
                            ";
                            str += "<a href='JavaScript:void(0)'"+
                            "style='padding: 5px; float: left;margin-top: 2px;' " +
                            "onclick=\"App.Menu.ShowViewDialog(" + value + ");\" "+ 
                            "title='View :" + value + "'><i class=\"fa fa-eye\"></i></a>\
                            ";
                            // str += "<a href='JavaScript:void(0)'"+
                            // "style='padding: 5px; float: left;margin-top: 2px;' " +
                            // "onclick=\"App.Menu.Delete(" + value + ","+row+");\" "+ 
                            // "title='Delete :" + value + "'><i class=\"fa fa-trash-o\"></i></a>\
                            // ";
                        } catch (e) {
                        }
                    }
                    return str;
                }
            },{
                text: 'Id'    , dataField: 'id2' , displayfield: prefix + 'id',cellsalign: 'right', 
                width: 60, columntype: 'numberinput', filtertype: 'number',
                filterable: false, sortable: false,editable: false,hidden:true
            }];
            
            // if(App.Menu.settings[App.Menu.sid].data.showthumb=='true'){
            //     me.columns.push({
            //         text: 'Image'    , dataField: 'image' ,width: 48,
            //         filterable: false, sortable: false,editable: false,hidden:true,
            //         cellsrenderer: function (row, column, value) {
            //             var html = [
            //                 '<div style="width:48px;height:28px;background-image:url('+App.Helper.ImageUrl(value)+')" class="contain">',
            //                 '</div>',
            //             ];
            //             return html.join('');
            //         }
            //     })
            // }
            console.log(me.columns,'me.columns')
            if(!!App.Menu.settings[App.Menu.sid].data.catetype){
                me.columns.push({
                    text: 'Category', width: 120, cellsalign: 'left',
                    hidden: !!!App.Menu.settings[App.Menu.sid].data.catetype,
                    columntype: 'dropdownlist',filtertype: 'list', filtercondition: 'EQUAL',
                    datafield: 'category', displayfield:'cattitle',
                    // value:'id',
                    // values: { source: me.cate.dataAdapter.records, name: 'title' },
                    // map:"title",
                    editable: false,
                    filteritems: me.cate.dataAdapter.records, 
                    // createeditor: function(row, cellvalue, editor, cellText, width, height) {
                    //     console.log(row, cellvalue, editor, cellText)
                    //     // construct the editor. 
                    //     editor.jqxDropDownList({
                    //         source: new $.jqx.dataAdapter(me.cate.source),
                    //         displayMember: "title", valueMember: "id",
                    //         width: width, height: height, theme: theme,
                    //         // selectionRenderer: function() {
                    //         //     return "Please Choose:";
                    //         // }
                    //     });
                    //     console.log(me.cate.dataAdapter.records)
                    // },
                    initeditor: function(row, cellvalue, editor, celltext, pressedkey) {
                    },
                    // geteditorvalue: function(row, cellvalue, editor) {
                    //     // return the editor's value.
                    //     return editor.val();
                    // }
                })
            }
            if(App.Menu.settings[App.Menu.sid].data.grid_columns){
                var extend_columns = eval(App.Menu.settings[App.Menu.sid].data.grid_columns)
                if(extend_columns) for(var i in extend_columns){
                    var c = extend_columns[i];
                    me.columns.push(c);
                }
            }
            // me.columns.push({
            //     text: 'Status'    , dataField: 'status' , cellsalign: 'center',
            //     width: 44, columntype: 'checkbox', threestatecheckbox: false, filtertype: 'bool',
            //     filterable: true, sortable: true,editable: true
            // });
            // me.columns.push({
            //     text: 'Created' , dataField: 'created', width: 120, cellsalign: 'right',
            //     filterable: true, columntype: 'datetimeinput', filtertype: 'range', cellsformat: 'yyyy-MM-dd HH:mm:ss',
            //     sortable: true,editable: false
            // });
            // me.columns.push({
            //     text: 'Modifield' , dataField: 'modified', width: 120, cellsalign: 'right',
            //     filterable: true, columntype: 'datetimeinput', filtertype: 'range', cellsformat: 'yyyy-MM-dd HH:mm:ss',
            //     sortable: true,editable: false, hidden: true
            // });
            init();
        }
        var editingItem;
        function addItem(column, sid, data){
            var li = $('<li/>')
                .addClass('col-xs-12')
                .html('<div><span>'+data.title+'</span></div>')
                .data('cdata',data)
            $( '#data-' + column ).append(li)
            li.find('>div').append([
                '<div class="dropdown pull-right">',
                    '<a href="JavaScript:" class="icon-options-vertical" data-toggle="dropdown" title="Show more action"></a>',
                    '<ul class="dropdown-menu" aria-labelledby="dropdownMenu1">',
                        '<li><a data-action="edit" href="JavaScript:"><span class="fa fa-pencil"></span> Edit</a></li>',
                        '<li><a data-action="delete" href="JavaScript:"><span class="fa fa-trash-o"></span> Delete</a></li>',
                    '</ul>',
                '</div>'
                ].join(''))
            li.find('a[data-action="edit"]').click(function(){
                var cdata = li.data('cdata');
                editingItem = li;
                App.Menu.ShowSubDetailDialog(column,sid,cdata);
            })
            li.find('a[data-action="delete"]').click(function(){
                li.remove();
            })
        }
        function init(){
            $('#entry-detail').hide();
            gridElm = $('#jqxGrid');
            
            var h = $(window).height() - 12*2 - 90 - 46 - 30;
            var grid_option = {
                width               : '100%', //
                //autoheight:true,
                rowsheight:28,
                height              : Math.max(240, h),
                source              : me.dataAdapter,
                theme               : theme,
                columns             : me.columns,
                selectionmode       : 'singlecell',
                filterable          : true,
                autoshowfiltericon  : true,
                showfilterrow       : true,
                sortable            : false,
                virtualmode         : false,
                // groupable            : true,
                // groups              : ['author_name','topic_title'],
                editable            : true,
                editmode            : 'dblclick',
                pageable            : true,
                pagesize            : 100,
                pagesizeoptions     : [20,100, 200, 500, 1000],
                rendergridrows      : function(){ 
                    return me.dataAdapter.records; 
                },
                ready: function(){
                    // pendingOff();
                },
                handlekeyboardnavigation: function(event)
                {
                    var key = event.charCode ? event.charCode : event.keyCode ? event.keyCode : 0;
                    if (key == 27) {
                        me.cancel=true;
                    }
                },
            }
            if(App.Menu.settings[App.Menu.sid].data.grid_options){
                var extend_grid_options = eval(App.Menu.settings[App.Menu.sid].data.grid_options)
                if(extend_grid_options) $.extend(grid_option,extend_grid_options);
            }
            $(gridElm).jqxGrid(grid_option);
            $('body').append('<ul id="common-menu" class="dropdown-menu">\
                <li><a data-action="add" href="#"><i class="fa fa-plus"></i> Add new Record</a></li>\
                <li><a data-action="edit" href="#"><i class="fa fa-pencil"></i> Edit Selected Row</a></li>\
                <li><a data-action="delete" href="#"><i class="fa fa-trash-o"></i> Delete Selected Row</a></li>\
                <li role="separator" class="divider"></li>\
                <li><a data-action="status" href="#"><i class="fa fa-toggle-off"></i> Invisible</a></li>\
                <li><a data-action="sendlatest" href="#"><i class="fa fa-long-arrow-up"></i> Send to Latest</a></li>\
                <li><a data-action="sendoldest" href="#"><i class="fa fa-long-arrow-down"></i> Send to Oldest</a></li>\
                <li role="separator" class="divider"></li>\
                <li><a data-action="view" href="#"><i class="fa fa-eye"></i> View Selected Row</a></li>\
                <li><a data-action="chart" href="#"><i class="fa fa-line-chart"></i> Chart</a></li>\
            </ul>')
            $('#common-menu').on('contextmenu',function(){
                event.preventDefault();
                event.stopPropagation();
                return false;
            });
            $('#common-menu a').click(function(){
                var action = $(this).data('action');
                var cell = $(gridElm).jqxGrid('getselectedcell');
                var rowIndex = cell.rowindex;
                if(rowIndex>=0){
                    var rowData = $(gridElm).jqxGrid('getrowdata', rowIndex);
                    switch(action){
                        case 'add':
                            App.Menu.ShowDetailDialog();
                            break;
                        case 'edit':
                            App.Menu.ShowDetailDialog(rowData.id);
                            break;
                        case 'delete':
                            App.Menu.Delete(rowData.id);
                            break;
                        case 'view':
                            App.Menu.ShowViewDialog(rowData.id);
                            break;
                        case 'status':
                            App.Menu.onCommit(
                                   {status: rowData.status=='1'?'0':'1'}, rowData.id, App.Menu.Refresh
                                );
                            console.log(rowData.id)
                            break;
                        case 'statuson':
                            App.Menu.onCommit(
                                   {status: '1'}, rowData.id, App.Menu.Refresh
                                );
                            console.log(rowData.id)
                            break;
                        case 'statusoff':
                            App.Menu.onCommit(
                                   {status: '0'}, rowData.id, App.Menu.Refresh
                                );
                            break;
                        case 'sendlatest':
                            App.Menu.SendLatest(rowData.id);
                            break;
                        case 'sendoldest':
                            App.Menu.SendOldest(rowData.id);
                            break;
                    }
                    console.log(action)
                }
            })

            $(gridElm).bind('cellbeginedit', function (event) {
                me.cancel = false;
            }).bind('cellendedit', function (event) {
                if(me.cancel) return;
                try{
                    var args = event.args;
                    var _column = args.datafield, 
                        _row = args.rowindex, 
                        _value = args.value;
                    var _data = $(gridElm).jqxGrid('getrowdata', _row);
                    var _id = _data.id;
                    if (_id == undefined || _id == "") {
                        return;
                    }
                    var updateCell = function(){
                        me.onRefresh();
                    };
                    console.log(_column)
                    for (var i in me.columns) {
                        var c = me.columns[i];
                        if(c.editable != false && c.datafield == _column){
                            if(c.filtertype == 'bool'){
                                var params = {};
                                params[c.datafield] = _value?'true':'false'
                                App.Menu.onCommit( params, _id );
                            } else {
                                if(_value != _data[c.datafield]){
                                    var params = {};
                                    params[c.datafield] = _value
                                    App.Menu.onCommit( params, _id );
                                }
                            }
                        }
                    }
                    
                } catch (e) {
                    toastr.error(e.message, 'Error');
                }
            })
            $(gridElm).on('contextmenu',function(){
                event.preventDefault();
                event.stopPropagation();
                
                return false;
            }).on('cellclick', function (event) {

                var getTouches = function (e) {
                    if (e.originalEvent) {
                        if (e.originalEvent.touches && e.originalEvent.touches.length) {
                            return e.originalEvent.touches;
                        } else if (e.originalEvent.changedTouches && e.originalEvent.changedTouches.length) {
                            return e.originalEvent.changedTouches;
                        }
                    }
                    if (!e.touches) {
                        e.touches = new Array();
                        e.touches[0] = e.originalEvent;
                    }
                    return e.touches;
                };
                var rowIndex = event.args.rowindex;
                var rowData = $(gridElm).jqxGrid('getrowdata', rowIndex);
                var openContextMenu = false;
                if(event.args.rightclick) openContextMenu = true;
                if($.jqx.mobile.isTouchDevice() && event.args.originalEvent.type=='touchend' && event.args.datafield=='id') 
                    openContextMenu = true;
                if(rowIndex>=0){
                    $(gridElm).jqxGrid('selectcell', rowIndex,event.args.datafield);
                    var dataRow = $(gridElm).jqxGrid('getrowdata', rowIndex);
                    $('#common-menu a').removeAttr('disabled')
                    if(App.Menu.settings[App.Menu.sid].data.add != 'true'){
                        $('#common-menu a[data-action="add"]').attr('disabled','1')
                    }
                    if(App.Menu.settings[App.Menu.sid].data.edit != 'true'){
                        $('#common-menu a[data-action="edit"]').attr('disabled','1')
                        $('#common-menu a[data-action="status"]').attr('disabled','1')
                        $('#common-menu a[data-action="delete"]').attr('disabled','1')
                    }
                    if(App.Menu.settings[App.Menu.sid].data.delete != 'true'){
                        $('#common-menu a[data-action="delete"]').attr('disabled','1')
                    }
                    if(rowData.status==1){
                        $('#common-menu a[data-action="status"]').html('<i class="fa fa-toggle-off"></i> Invisible')
                    } else {
                        $('#common-menu a[data-action="status"]').html('<i class="fa fa-toggle-on"></i> Visible')
                    }

                    var scrollTop = $(window).scrollTop();
                    var scrollLeft = $(window).scrollLeft();
                    var menuWidth = $('#common-menu').width();
                    var menuHeight = $('#common-menu').height();
                    var clientX = (event.args.originalEvent.clientX) + scrollLeft,
                    clientY = (event.args.originalEvent.clientY) + scrollTop;
                    if(event.args.originalEvent.type=='touchend'){
                        var touches = getTouches(event.args.originalEvent);
                        var touch = touches[0];
                        clientX = touch.pageX;
                        clientY = touch.pageY;
                    }
                    var x = parseInt(clientX);
                    var y = parseInt(clientY);
                    var windowWidth = $(window).width() + scrollLeft;
                    var windowHeight = $(window).height() + scrollTop;
                    if( x + menuWidth > windowWidth){
                        x = windowWidth - menuWidth -4;
                    }
                    if( y + menuHeight > windowHeight){
                        y = windowHeight - menuHeight -4;
                    }
                    $('#common-menu').css({
                            position:'fixed',
                            top:y+'px',
                            left:x+'px',
                        })
                    if (openContextMenu) {
                        setTimeout(function(){
                            $('#common-menu').addClass('open')
                        },100)
                        // event.stopPropagation();
                        // event.preventDefault();
                        event.preventDefault();
                        event.stopPropagation();
                        return false;
                    }else{
                        
                    }
                }
            })
            $(gridElm).on('filter', function (event) {
                // get filter information.
                var filterInformation = $(gridElm).jqxGrid('getfilterinformation');
                var filterdata = {};
                var filterslength = 0;
                var data = {};
                for (var x = 0; x < filterInformation.length; x++) {
                    // column's data field.
                    var filterdatafield = filterInformation[x].datafield;
                    // column's filter group.
                    var filterGroup = filterInformation[x].filter;
                    var filterColumn = filterInformation[x].filtercolumn;
                    // column's filters.
                    var filters = filterGroup.getfilters();
                    // filter group's operator.
                    data[filterdatafield + "operator"] = filterGroup.operator;
                    for (var m = 0; m < filters.length; m++) {
                        filters[m].datafield = filterdatafield;
                        // filter's value.
                        var filtervalue = filters[m].value;
                        data["filtervalue" + filterslength] = filtervalue.toString();
                        // filter's id.
                        if (filters[m].id) {
                            data["filterid" + filterslength] = filters[m].id.toString();
                        }
                        // filter's condition.
                        data["filtercondition" + filterslength] = filters[m].condition;
                        // filter's operator.
                        data["filteroperator" + filterslength] = filters[m].operator;
                        // filter's data field.
                        data["filterdatafield" + filterslength] = filterdatafield;
                        filterslength++;
                    }
                    // for(var c in me.columns){
                    //     if(me.columns[c].datafield == filterColumn){
                    //         if(me.columns[c].filtertype == 'bool'){
                    //             filters[x].value = filters[x].value?1:0;
                    //         }
                    //     }
                    // }
                }
                            // console.log(filterGroup)
                // console.log(JSON.stringify(data));
                console.log(filterInformation,filters);

            });
        }
        var Lists = {};
        var Grids = {};
        var seedialog;
        return {
            onCommit: function(data,id,callback){
                new App.Request({
                    url: URI.update,
                    data: {
                        id: id,
                        sid: App.Menu.sid,
                        data: data
                    },
                }).done(function(res){
                    if(res.code < 0){
                        toastr.warning(res.message,'Warning');
                    } else {
                        toastr.success(res.message,'Success');
                        if(typeof callback == 'function') callback()
                    }
                })
            },
            SendLatest: function(id){
                new App.Request({
                    url: URI.sendlatest,
                    data: {
                        id: id,
                        sid: App.Menu.sid
                    },
                }).done(function(res){
                    if(res.code < 0){
                        toastr.warning(res.message,'Warning');
                    } else {
                        toastr.success(res.message,'Success');
                        App.Menu.Refresh()
                    }
                })
            },
            SendOldest: function(id){
                new App.Request({
                    url: URI.sendoldest,
                    data: {
                        id: id,
                        sid: App.Menu.sid
                    },
                }).done(function(res){
                    if(res.code < 0){
                        toastr.warning(res.message,'Warning');
                    } else {
                        toastr.success(res.message,'Success');
                        App.Menu.Refresh()
                    }
                })
            },
            Grid: function(){
                if(!!!App.Menu.settings[App.Menu.sid].data.catetype){
                    me.bindingEntry();
                }else me.bindingCate();
            },
            CreateList: function(data){
                var frm = $('#entry-detail-frm');
                frm.find('.add-sortable-item').click(function(){
                    editingItem = null;
                    var column = $(this).data('column');
                    var sid = $(this).data('sid');
                    App.Menu.ShowSubDetailDialog(column,sid);
                });

                frm.find('.sortable').each(function(){
                    var column = $(this).data('column');
                    var sid = $(this).data('sid');
                    var __ = 'data';
                    var columns = App.Menu.settings[App.Menu.sid].data.columns
                    for(var i =0;i<columns.length;i++){
                        if(columns[i].name == column){
                            if(+columns[i].biz==1){
                                __ = 'longdata';
                            }
                        }
                    }
                    console.log(data,__)
                    if(data && data[__] && data[__][column]){
                        data[__][column].map(function(cdata){
                            addItem(column,sid,cdata)
                        })

                    }
                    $(this).sortable({
                        placeholder: "placeholder",
                        start: function(e, ui){
                            console.log(ui)
                            ui.placeholder.addClass(ui.item.attr('class'))
                        }
                    });
                });

            },
            Refresh: function() {
                $(gridElm).jqxGrid('updatebounddata');
            },
            Back: function(){
                if(!!App.Menu.settings[App.Menu.sid].data.size){
                    $('#entry-detail').dialog("close");
                } else {
                    $('#entry-detail').html('<div class="loading"><span>Loading...</span></div>').hide();
                    $('#entrys-list').show();
                }
            },
            Duplicate: function(){
                var frm = $('#entry-detail-frm');
                if(frm.length==0) return;
                var data = frm.serializeObject();
                if(!data.id && !JSON.parse(App.Menu.settings[App.Menu.sid].data.add)){
                    toastr.warning('Access denied','Warning');
                     return;
                }
                frm.find('input[name="id"]').val('');
                frm.find('input[name="title"]').val(frm.find('input[name="title"]').val() + '(copy)');
                frm.find('input[name="alias"]').val(frm.find('input[name="alias"]').val() + '-copy');
                frm.find('input[name="id"]').val('');
                App.Menu.Save();
            },
            Delete: function(id,row){
                // toastr.warning('This function to requires an administrative account.<br/>Please check your authority, and try again.','Warning');
                var _data = $(gridElm).jqxGrid('getrowdata', row);
                if(_data.lock == 1){
                    toastr.warning('You can not delete this Item.','Warning');
                    return; 
                }
                var itemName = _data.title;
                App.Confirm(
                    'Delete item ?',
                    'Do you want delete "'+itemName+'".<br/>These items will be permanently deleted and cannot be recovered. Are you sure ?',
                    function(){
                        new App.Request({
                            'url': URI.delete,
                            'data': {
                                'id': id,
                                sid: App.Menu.sid,
                            },
                        }).done(function(res) {
                            if (res.code < 0) {
                                toastr.error(res.message,'Error');
                            } else {
                                toastr.success(res.message,'Success');
                                App.Menu.Refresh();
                            }
                        })
                    }
                );
            },
            Save: function(only){
                var frm = $('#entry-detail-frm');
                if(frm.length==0) return;
                if( frm.validationEngine('validate') === false){
                    toastr.warning('Please complete input data.','Warning');
                    return;
                }
                
                var data = $('#entry-detail-frm').serializeObject();
                var id = data.id;
                delete(data.id)
                
                frm.find('.sortable').each(function(){
                    var column = $(this).data('column');
                    var subData = $(this).find('>li').get().map(function(li){
                        return $(li).data('cdata');
                    })
                    var __ = 'data';
                    var columns = App.Menu.settings[App.Menu.sid].data.columns
                    for(var i =0;i<columns.length;i++){
                        if(columns[i].name == column){
                            if(+columns[i].biz==1){
                                __ = 'longdata';
                            }
                        }
                    }
                    if(!data[__]) data[__] = {}
                    data[__][column] = subData;
                })
                if(!id && !JSON.parse(App.Menu.settings[App.Menu.sid].data.add)){
                    toastr.warning('Access denied','Warning');
                     return;
                }
                new App.Request({
                    url: URI.commit,
                    data: {
                        id: id,
                        sid: App.Menu.sid || null,
                        data: data
                    },
                }).done(function(res){
                    if(res.code < 0){
                        toastr.warning(res.message,'Warning');
                    } else {
                        toastr.success(res.message,'Success');
                        if(only===true) return;
                        if(!!App.Menu.settings[App.Menu.sid].data.size){
                            $('#entry-detail').dialog("close");
                        } else {
                            $('#entry-detail').html('<div class="loading"><span>Loading...</span></div>').hide();
                            $('#entrys-list').show();
                        }
                        App.Menu.Refresh();
                    }
                })
            },
            BackSee: function(){
                if(!!App.Menu.settings[App.Menu.sid].data.size){
                    if(seedialog) $('#entry-see').dialog("close");
                } else {
                    $('#entry-see').html('<div class="loading"><span>Loading...</span></div>').hide();
                    $('#entrys-list').show();
                }
            },
            ShowViewDialog: function(id){
                
                if(!!App.Menu.settings[App.Menu.sid].data.size){
                    if ($("#entry-see").length === 0) {
                        $('body').append('<div id="entry-see"></div>');
                    }

                    $('#entry-see').html('<div class="loading"><span>Loading...</span></div>')
                    seedialog = new App.Dialog({
                        'modal': true,
                        'message' : $('#entry-see'),
                        'title': '<h4>View <small>'+App.Menu.settings[App.Menu.sid].title+'</small></h4>',
                        'dialogClass':'',
                        'width': App.Menu.settings[App.Menu.sid].data.size,
                        'type':'notice',
                        'hideclose':true,
                        'closeOnEscape':false,
                        'oncreate': function(event, ui){
                            var toolbar = [
                                '<div class="modal-action">',
                                    '<div class="dropdown pull-right">',
                                        '<a href="JavaScript:" class="icon-options-vertical" data-toggle="dropdown" title="Show more action"></a>',
                                        '<ul class="dropdown-menu" aria-labelledby="dropdownMenu1">',
                                            '<li><a href="#"><span class="icon-settings"></span> Setting</a></li>',
                                            '<li role="separator" class="divider"></li>',
                                            '<li><a href="#"><span class="icon-question"></span> Help</a></li>',
                                        '</ul>',
                                    '</div>',
                                '</div>'
                            ].join('')
                            $(event.target).dialog('widget')
                                .find('.ui-widget-header')
                                .append(toolbar)
                        },
                        'buttons' : [{
                            'text': 'Edit',
                            'class': 'ui-btn btn',
                            'click': function(){
                                $(this).dialog("close");
                                App.Menu.ShowDetailDialog(id);
                            }
                        },{
                            'text': 'Cancel',
                            'class': 'btn btn-link',
                            'click': function() {
                                $(this).dialog("close");
                            }
                        }]
                    })

                    seedialog.open();
                } else {
                    $('#entry-see').html('<div class="loading"><span>Loading...</span></div>').show();
                    $('#entrys-list').hide();
                }

                new App.Request({
                    url: URI.see,
                    // datatype: 'html',
                    data: {
                        id: id || null,
                        sid: App.Menu.sid || null,
                        onlysave: App.Menu.onlysave || null
                    },
                }).done(function(res){
                    if(res.code < 0){
                        toastr.warning(res.message,'Warning');
                        App.Menu.Back()
                    } else {
                        $('#entry-see').html(res.html);
                        App.InitSee($('#entry-see'));
                        
                        if(!!App.Menu.settings[App.Menu.sid].data.size){
                            seedialog.close();
                            seedialog.open();
                        }
                        
                    }
                })
            },
            ShowDetailDialog: function(id){
                App.Menu.BackSee();
                if(
                    id && !JSON.parse(App.Menu.settings[App.Menu.sid].data.edit)
                    ){
                    console.log('E')
                    toastr.warning('Access denied','Warning');
                     return;
                }
                if(
                    !id && !JSON.parse(App.Menu.settings[App.Menu.sid].data.add)
                    ){
                    console.log('A')
                    toastr.warning('Access denied','Warning');
                     return;
                }
                if(!!App.Menu.settings[App.Menu.sid].data.size){
                    if ($("#entry-detail").length === 0) {
                        $('body').append('<div id="entry-detail"></div>');
                    }

                    $('#entry-detail').html('<div class="loading"><span>Loading...</span></div>')
                    dialog = new App.Dialog({
                        'modal': true,
                        'message' : $('#entry-detail'),
                        'title': '<h4>Add <small>'+App.Menu.settings[App.Menu.sid].title+'</small></h4>',
                        'dialogClass':'',
                        'width': App.Menu.settings[App.Menu.sid].data.size,
                        'type':'notice',
                        'hideclose':true,
                        'closeOnEscape':false,
                        'oncreate': function(event, ui){
                            var toolbar = [
                                '<div class="modal-action">',
                                    '<div class="dropdown pull-right">',
                                        '<a href="JavaScript:" class="icon-options-vertical" data-toggle="dropdown" title="Show more action"></a>',
                                        '<ul class="dropdown-menu" aria-labelledby="dropdownMenu1">',
                                            '<li><a href="#"><span class="icon-settings"></span> Setting</a></li>',
                                            '<li role="separator" class="divider"></li>',
                                            '<li><a href="#"><span class="icon-question"></span> Help</a></li>',
                                        '</ul>',
                                    '</div>',
                                '</div>'
                            ].join('')
                            $(event.target).dialog('widget')
                                .find('.ui-widget-header')
                                .append(toolbar)
                        },
                        'buttons' : [{
                            'text': 'Duplicate',
                            'id':'btn-duplicate',
                            'class': 'ui-btn btn ' + (id?'':'hidden'),
                            'click': App.Menu.Duplicate
                        },{
                            'text': 'Done',
                            'class': 'ui-btn btn',
                            'click': App.Menu.Save
                        },{
                            'text': 'Cancel',
                            'class': 'btn btn-link',
                            'click': function() {
                                $(this).dialog("close");
                            }
                        }]
                    })

                    dialog.open();
                } else {
                    $('#entry-detail').html('<div class="loading"><span>Loading...</span></div>').show();
                    $('#entrys-list').hide();
                }

                new App.Request({
                    url: URI.detail,
                    // datatype: 'html',
                    data: {
                        id: id || null,
                        sid: App.Menu.sid || null,
                        onlysave: App.Menu.onlysave || null
                    },
                }).done(function(res){
                    if(res.code < 0){
                        toastr.warning(res.message,'Warning');
                        App.Menu.Back()
                    } else {
                        $('#entry-detail').html(res.html);

                        App.InitForm($('#entry-detail-frm'));
                        App.Menu.CreateList(res.data);
                        if(!!App.Menu.settings[App.Menu.sid].data.size){
                            dialog.close();
                            dialog.open();
                            if(res.data)
                            $('#entry-detail').dialog('widget')
                                .find('.ui-widget-header')
                                .find('h4')
                                .html('Edit<small>'+App.Menu.settings[App.Menu.sid].title+'</small>')
                        }
                        
                    }
                })
            },
            ShowSubDetailDialog: function(column,sid,data){
                if ($("#sub-entry-detail").length === 0) {
                    $('body').append('<div id="sub-entry-detail"></div>');
                }

                $('#sub-entry-detail').html('<div class="loading"><span>Loading...</span></div>');
                var subdialog = new App.Dialog({
                    'modal': true,
                    'message' : $('#sub-entry-detail'),
                    'title': '<h4>Add <small>'+App.Menu.settings[sid].title+'</small></h4>',
                    'dialogClass':'',
                    'width': App.Menu.settings[sid].data.size || 640,
                    'type':'notice',
                    'hideclose':true,
                    'closeOnEscape':false,
                    'oncreate': function(event, ui){
                        var toolbar = [
                            '<div class="modal-action">',
                                '<div class="dropdown pull-right">',
                                    '<a href="JavaScript:" class="icon-options-vertical" data-toggle="dropdown" title="Show more action"></a>',
                                    '<ul class="dropdown-menu" aria-labelledby="dropdownMenu1">',
                                        '<li><a href="#"><span class="icon-settings"></span> Setting</a></li>',
                                        '<li role="separator" class="divider"></li>',
                                        '<li><a href="#"><span class="icon-question"></span> Help</a></li>',
                                    '</ul>',
                                '</div>',
                            '</div>'
                        ].join('')
                        $(event.target).dialog('widget')
                            .find('.ui-widget-header')
                            .append(toolbar)
                    },
                    'buttons' : [{
                        'text': 'Done',
                        'class': 'ui-btn btn',
                        'click': function(){
                            var frm = $('#subentry-detail-frm');
                            if( frm.validationEngine('validate') === false){
                                toastr.warning('Please complete input data.','Warning');
                                return;
                            }
                            var data = $('#subentry-detail-frm').serializeObject();
                            if (editingItem) {
                                editingItem.data('cdata',data);
                                editingItem.find('>div>span').html(data.title)
                            } else {
                                addItem(column,sid,data);
                            }
                            $(this).dialog("close");
                        }
                    },{
                        'text': 'Cancel',
                        'class': 'btn btn-link',
                        'click': function() {
                            $(this).dialog("close");
                        }
                    }]
                })
                subdialog.open();
                new App.Request({
                    url: URI.subdetail,
                    // datatype: 'html',
                    data: {
                        sid: sid,
                        data: data || null
                    },
                }).done(function(res){
                    if(res.code < 0){
                        toastr.warning(res.message,'Warning');
                    } else {
                        $('#sub-entry-detail').html(res.html);

                        App.InitForm($('#subentry-detail-frm'));
                        subdialog.close();
                        subdialog.open();
                        
                    }
                })
            }
        }
    }())
})