(function($, window, undefined) {
    var generate_id = function (source) {
        return source.trim().split('/').map(function(v) {
            return v.toLowerCase().replace(/( |^)[a-z]/g, function(iv) {
                return iv.toUpperCase();
            });
        }).join('').replace(/( |^)[A-Z]/g, function(iv) {
            return iv.toLowerCase();
        });
    };
    $('#jMenu > li.dropdown > .dropdown-toggle').each(function(i, v) { // Hover the Menu
        var $Toggle = $(this);
        $Toggle.parent('.dropdown').hover(function (e) {
            if (!$(this).hasClass('open')) {
                $Toggle.dropdown('toggle');
            }
        }, function (e) {
            if ($(this).hasClass('open')) {
                $Toggle.dropdown('toggle');
            }
        });
    });
    $('#jMenu a:not(.dropdown-toggle)').each(function (i, v) { // Open Tab 选项卡
        $(this).on('click', function (e) {
            var Title = $(this).text(); // Title
            var Index = $(this).data('index'); // index索引
            var Href = $(this).attr('href');
            if (Href.indexOf('#') === 0) {
                Href = Href.substring(Href.indexOf('#') + 1); // 加载网址
            }
            var Id = generate_id(Href); // Id

            var Exist = false;
            $('#jTabList a').each(function (i, v) {
                if ($(this).data('index') === Index) {
                    $(this).tab('show');
                    Exist = true;
                }
            });

            if (!Exist) {
                var $TabClone = $('#jTabList').children('.clone').clone(true);
                var $TabContentClone = $('#jTabContent').children('.clone').clone(true);
                $TabClone.find('a').data('index', Index).data('load', Href).attr('href', '#' + Id).attr('aria-controls', Id).find('span[name="title"]').text(Title);
                $('#jTabList').append($TabClone.removeClass('clone'));
                $('#jTabContent').append($TabContentClone.attr('id', Id).html('Waiting On ...' + Id).removeClass('clone'));
                $('#jTabList').children('li').last().children('a').tab('show');
            }
        });
    });

    $('#jTabList a i.fa-times').each(function(i, v) { // 关闭Tab选项卡
        $(this).on('click', function (e) {
            $('#jTabContent').find($(this).parent('a').attr('href')).remove();
            var $Li = $(this).parents('li').eq(0);
            if ($Li.next('li').length > 0) {
                $Li.next('li').eq(0).children('a').tab('show');
            } else if ($Li.prev('li').length > 0) {
                $Li.prev('li').eq(0).children('a').tab('show');
            }
            $Li.remove();
        })
    });

    $('#jTabList a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        if ($('#jNavbar').attr('aria-expanded')) {
            $('#jNavbar').collapse('hide'); // 点击Menu之后，显示tab，之后需要关闭Menu
        }
        if (!$(this).data('loaded') && $(this).data('load') != undefined && $(this).data('load') != 'javascript:void(0);') {
            var $This = $(this);
            $.get(BASE_URL + $This.data('load'), function (data) {
                $($This.attr('href')).html(data);
                $This.data('loaded', true);
            });
        }
    })
    $.extend({
        /**
         * Store Input Data
         * @param This
         * @param Value
         */
        storedInputData: function(This, Value){
            var $This = $(This);
            if(This.tagName == 'INPUT'){
                switch($This.attr('type')){
                    case 'text':
                    case 'hidden':
                    case 'tel':
                    case 'number':
                    case 'email':
                    case 'textarea':
                        $This.val(Value);
                        break;
                    case 'radio':
                    case 'checkbox':
                        if($This.val() == Value){
                            $This.prop('checked', true);
                        }else{
                            $This.prop('checked', false);
                        }
                        break;
                }
            }else if(This.tagName == 'SELECT'){
                if($This.find('option[value="'+Value+'"]').length > 0){
                    $This.find('option[value="'+Value+'"]').prop('selected', true);
                }else{
                    $This.append('<option value="'+Value+'" selected>'+Value+'</option>');
                }
            }else if(This.tagName == 'TEXTAREA'){
                $This.val(Value);
            }
            return ;
        }
    });
    /**
     * 表单排序， Resort by bootstrap-table
     * @returns {{headers: {}}}
     */
    $.fn.getHeaders = function(){
        var $Header = {
            headers:{}
        };
        $(this).each(function(j,e){
            var $children = $(this).children();
            for(var i=0; i<$children.length; i++){
                if ($children.eq(i).find('i').length == 0) {
                    $Header.headers[i] = {sorter:false};
                }
            }
        });
        return $Header;
    };
    var t = new Vue({
        el: '#test',
        data: function () {
            return {}
        },
        computed: {
            tests: {
                get: function () {
                    return STORE.state.tests;
                },
                set: function () {

                }
            }
        },
        created: function() {
        }
    });
})(jQuery, window, undefined);
