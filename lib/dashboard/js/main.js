var App = {};
App.pendingOn = function(){
    
}
App.pendingOff = function(){

}
App.ShowLogin = function(){
    if ($("#login-dialog").length === 0) {
        $('body').append('<div id="login-dialog"></div>');
    }
    new App.Dialog({
        'message' : $('#login-dialog'),
        'title': '<h4>Login <small>Authentication</small></h4>',
        'dialogClass':'',
        'width':'320px',
        'type':'notice',
        'hideclose':true,
        'closeOnEscape':false,
        'buttons' : [{
            'text': 'Login',
            'class': 'ui-btn btn-info btn',
            'click': function() {
                
            }
        },{
            'text': 'Cancel',
            'class': 'ui-btn btn',
            'click': function() {
                $(this).dialog("close");
            }
        }]
    }).open();
    $('#login-dialog .validation-frm').validationEngine({
        'scroll': false,
        'isPopup' : true,
        validateNonVisibleFields:true
    });
}
App.Dialog = function (option) {
    var me = this;
    this.option = {
        'modal': true,
        'closeOnEscape': true,
        'type': "notice", //notice,error,question,custom
        'title': null,
        'message': "",
        'uidialog': null,
        'hideclose': false,
        'autoOpen': false,
        'minwidth': '320px',
        'width': 'auto',
        'height': 'auto',
        'dialogClass': '',
        'onload': null,
        'onclose': null,
        'onopen': null,
        'callback': null,
        'buttons': null
    };
    if (option) {
        $.each(option, function(index, value) {
            me.option[index] = value;
        });
    }
    me.option.title = "<div class='ui-" + me.option.type + "'>" + (me.option.title === null ? "Notice Message !" : me.option.title) + "</div>";
    if (me.option.message === null || me.option.message === undefined) {
        
    } else if (typeof(me.option.message) === "object") {
        me.option.uidialog = me.option.message;
    } else {
        if (me.option.uidialog == null) {
            var node = 1;
            var $div = $('.ui-dialog-' + node);
            while ($div.length > 0 && !$div.is(":hidden")) {
                node++;
                $div = $('.ui-dialog-' + node);
            }
            if ($div.length === 0) {
                me.option.uidialog = $('<div/>', {
                    'class': 'ui-dialog-' + node
                }).appendTo($('body'));
            }else me.option.uidialog = $div;
        }
        if (typeof(me.option.message) === "string") {
            if(!me.option.buttons)
            me.option.buttons = {
                Close: function() {
                    $(this).dialog("destroy");
                }
            };
            me.option.uidialog.html('<div style="max-width:320px">' + me.option.message + '</div>');
        } else {
            //me.option.uidialog = $("#bckdialog");
            me.option.uidialog.html("Unkown this message !");
        }
    }
    var dialog = me.option.uidialog.dialog({
        'modal': me.option.modal,
        'autoOpen': me.option.autoOpen,
        'minwidth': me.option.minwidth,
        'dialogClass': me.option.dialogClass + ' ' + me.option.type,
        'resizable': false,
        'width': me.option.width,
        'title': me.option.title,
        'closeOnEscape': me.option.closeOnEscape,
        /*hide                : "explode",*/
        'buttons': me.option.buttons,
        'open': function(event, ui) {
            if (me.option.onopen && typeof(me.option.onopen) === "function") {
                try {
                    me.option.onopen();
                } catch (e) {}
            }
            $(event.target).dialog('widget')
                .css({
                    // 'position': 'fixed'
                })
                .position({
                    'my': 'center',
                    'at': 'center',
                    'of': window
                });
        },
        'close': function(event, ui) {
            if (me.option.onclose && typeof(me.option.onclose) === "function") {
                try {
                    me.option.onclose();
                } catch (e) {}
            }
        },
        'create': function(event, ui) {
            if (me.option.hideclose === true) {
                $(this).closest(".ui-dialog")
                    .find(".ui-dialog-titlebar-close")
                    .hide();
            }
            if (me.option.oncreate && typeof(me.option.oncreate) === "function") {
                try {
                    me.option.oncreate(event, ui);
                } catch (e) {}
            }
        }
    });
    return {
        'open': function() {
            me.option.uidialog.dialog('open');
        },
        'close': function() {
            me.option.uidialog.dialog('close');
        }
    };
}
App.Request = function (opt) {
    var option = {
        'url': null,
        'data': null,
        'datatype': "json",
        'before': null,
        'after': null,
        'done': null
    };
    if (opt)
        $.each(opt, function(index, value) {
            option[index] = value;
        });
    jQuery.ajax({
        type: "POST",
        //cache:false,
        //timeout:10000,
        data: option.data,
        dataType: option.datatype,
        url: option.url,
        success: function(data_result) {
            if (typeof(option.done) === 'function')
                option.done(data_result);
            if (typeof(option.after) === 'function')
                option.after();
            else {
                App.pendingOff();
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            if (typeof(option.after) === 'function')
                option.after();
            else {
                App.pendingOff();
            }
            if(xhr.status == 403){
                window.location.reload()
            } else {
                toastr.error('Sorry. Your request could not be completed. Please check your input data and try again.','Error');
            }
        }
    });
    return {
        done: function(fn) {
            option.done = fn;
        }
    };
}
App.Confirm = function(title, message, callback) {
    if ($("#confirm-dialog").length === 0) {
        $('body').append('<div id="confirm-dialog">'+message+'</div>');
    }
    $('#confirm-dialog').html(message);
    new App.Dialog({
        'message' : $('#confirm-dialog'),
        'title': title,
                'width':'320px',
        'type':'confirm',
        'buttons' : [{
            'text': 'OK',
            'class': 'btn btn-ui',
            'click': function() {
                if(typeof callback == 'function') callback();
                $(this).dialog("close");
            }
        }, {
            'text': 'Cancel',
            'class': 'btn btn-ui',
            'click': function() {
                $(this).dialog("close");
            }
        }]
    }).open();
}
App.SeeGoogleMap = function(mapElement){
    // google.maps.event.addDomListener(window, 'load', function(){

        //
        var lat = +$(mapElement).data('lat') || 10.7546664;
        var lon = +$(mapElement).data('lon') || 106.415041;
        // Basic options for a simple Google Map
        // For more options see: https://developers.google.com/maps/documentation/javascript/reference#MapOptions
        var mapOptions = {
            zoom: 14,
            center: new google.maps.LatLng(lat, lon),
        };
        // Get the HTML DOM element that will contain your map 
        // We are using a div with id="map" seen below in the <body>
        // Create the Google Map using our element and options defined above
        var map = new google.maps.Map(mapElement, mapOptions);
        var marker = new google.maps.Marker({
                position: new google.maps.LatLng(lat, lon),
                map: map
            });

    // });
}
App.AddGoogleMap = function(mapElement,callback){
    // google.maps.event.addDomListener(window, 'load', function(){

        var lat = +$(mapElement).data('lat') || 10.7546664;
        var lon = +$(mapElement).data('lon') || 106.415041;
        // var latlon = [ 10.771921, 106.678296 ]; // 151.20929550000005&lat=-33.8688197
        // var lat  = latlon[0], lon = latlon[1];
        // Basic options for a simple Google Map
        // For more options see: https://developers.google.com/maps/documentation/javascript/reference#MapOptions
        var mapOptions = {
            zoom: 14,
            center: new google.maps.LatLng(lat, lon),
        };
        // Get the HTML DOM element that will contain your map 
        // We are using a div with id="map" seen below in the <body>
        // Create the Google Map using our element and options defined above
        var map = new google.maps.Map(mapElement, mapOptions);
        var marker = new google.maps.Marker({
            position: new google.maps.LatLng(lat, lon),
            map: map,
            draggable:true,

        });
        if(typeof callback == 'function'){
            google.maps.event.addListener(marker,'drag',callback);

            google.maps.event.addListener(marker,'dragend',callback);
            // marker.addListener('drag', callback);
            // marker.addListener('dragend', callback);
        }
    // });
}
App.InitForm = function(frm){
    frm.validationEngine({
        'scroll': false,
        'isPopup' : true,
        validateNonVisibleFields:true
    });
    frm.find('.selectpicker').selectpicker();
    frm.find('.date-picker').get().map(function(elm){
        $(elm).datetimepicker({
            format: 'YYYY-MM-DD'
        });
    });
    frm.find('.date-time-picker').get().map(function(elm){
        $(elm).datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss'
        });
    });
    if(frm.find('textarea[data-editor]').length>0){
        frm.find('textarea[data-editor]').each(function(){
                // addEditorBasic($(this).attr('id'),160);
                App.Editor.addEditorFeature($(this).attr('id'),200);
        })
        
    }
    setTimeout(function(){
        frm.find('[data-googlemap]').get().map(function(elm){
            App.AddGoogleMap(elm,function(e){
                frm.find('input[name="'+$(elm).data('latcolumn')+'"]').val(e.latLng.lat());
                frm.find('input[name="'+$(elm).data('loncolumn')+'"]').val(e.latLng.lng());
                frm.find('span[data-latlonpreview="'+$(elm).data('latcolumn')+$(elm).data('loncolumn')+'"]').text(e.latLng.lat() + ' ' + e.latLng.lng());
            })
        })
    },500)
}
App.InitSee = function(frm){
    setTimeout(function(){
        frm.find('[data-googlemap]').get().map(function(elm){
            App.SeeGoogleMap(elm)
        })
    },500)

}
$(document).ready(function(){
    $('#navigation li>a').click(function(){
        if(!$(this).parent().hasClass('open')){
            $(this).parent().parent().find('>li.open').removeClass('open')
        }
        $(this).parent().toggleClass('open');
    });
    $('li[data-actived]').addClass('active open')
    $('li[data-actived]').parents('li').addClass('active open')
})