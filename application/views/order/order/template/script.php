<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created for jiuqian-desk.
 * User: chuangchuangzhang
 * Date: 2018/2/19
 * Time: 12:55
 *
 * Desc: 脚本区
 */
?>
<script>
    (function($){
        var SessionData = undefined, Item, Index;
        if(!(SessionData = $.sessionStorage('task_level'))){
            $.ajax({
                async: true,
                type: 'get',
                dataType: 'json',
                url: '<?php echo site_url('data/task_level/read');?>',
                success: function(msg){
                    if(msg.error == 0){
                        var Content = msg.data.content;
                        Item = '';
                        for(Index in Content){
                            Item += '<option value="'+Content[Index]['tlid']+'" >'+Content[Index]['name']+'</option>';
                        }
                        $.sessionStorage('task_level', Item);
                        $('#orderModal select[name="flag"]').append(Item);
                    }
                }
            });
        }else{
            $('#orderModal select[name="flag"]').append(SessionData);
        }
        if(!(SessionData = $.sessionStorage('payterms'))){
            $.ajax({
                async: true,
                type: 'get',
                dataType: 'json',
                url: '<?php echo site_url('dealer/payterms/read');?>',
                success: function(msg){
                    if(msg.error == 0){
                        var Content = msg.data.content;
                        Item = '';
                        for(Index in Content){
                            Item += '<option value="'+Content[Index]['name']+'" >'+Content[Index]['name']+'</option>';
                        }
                        $.sessionStorage('payterms', Item);
                        $('#orderModal select[name="payterms"]').append(Item);
                    }
                }
            });
        }else{
            $('#orderModal select[name="payterms"]').append(SessionData);
        }
        if(!(SessionData = $.sessionStorage('out_method'))){
            $.ajax({
                async: true,
                type: 'get',
                dataType: 'json',
                url: '<?php echo site_url('data/out_method/read');?>',
                success: function(msg){
                    if(msg.error == 0){
                        var Content = msg.data.content;
                        Item = '';
                        for(Index in Content){
                            Item += '<option value="'+Content[Index]['name']+'" >'+Content[Index]['name']+'</option>';
                        }
                        $('#orderModal select[name="out_method"]').append(Item);
                        $.sessionStorage('out_method', Content);
                    }
                }
            });
        }else{
            Item = '';
            for(var Index in SessionData){
                Item += '<option value="'+SessionData[Index]['name']+'" >'+SessionData[Index]['name']+'</option>';
            }
            $('#orderModal select[name="out_method"]').append(Item);
        }

        if(!(SessionData = $.sessionStorage('area'))){
            $.ajax({
                async: true,
                type: 'get',
                dataType: 'json',
                url: '<?php echo site_url('data/area/read');?>',
                success: function(msg){
                    if(msg.error == 0){
                        var Content = msg.data.content;
                        Item = '';
                        for(Index in Content){
                            Item += '<option value="'+Content[Index]['area']+'" >'+Content[Index]['area']+'</option>';
                        }
                        $('#orderModal select[name="delivery_area"]').append(Item);
                        $.sessionStorage('area', Content);
                    }
                }
            });
        }else{
            Item = '';
            for(var Index in SessionData){
                Item += '<option value="'+SessionData[Index]['area']+'" >'+SessionData[Index]['area']+'</option>';
            }
            $('#orderModal select[name="delivery_area"]').append(Item);
        }

        if(!(SessionData = $.sessionStorage('logistics'))){
            $.ajax({
                async: true,
                type: 'get',
                dataType: 'json',
                url: '<?php echo site_url('data/logistics/read');?>',
                success: function(msg){
                    if(msg.error == 0){
                        var Content = msg.data.content;
                        Item = '';
                        for(Index in Content){
                            Item += '<option value="'+Content[Index]['name']+'" >'+Content[Index]['name']+'</option>';
                        }
                        $('#orderModal select[name="logistics"]').append(Item);
                        $.sessionStorage('logistics', Content);
                    }
                }
            });
        }else{
            Item = '';
            for(var index in SessionData){
                Item += '<option value="'+SessionData[index]['name']+'" >'+SessionData[index]['name']+'</option>';
            }
            $('#orderModal select[name="logistics"]').append(Item);
        }

        if(!(SessionData = $.sessionStorage('workflow_order'))){
            $.ajax({
                async: true,
                type: 'get',
                dataType: 'json',
                url: '<?php echo site_url('data/workflow/read/order');?>',
                success: function(msg){
                    if(msg.error == 0){
                        var Content = msg.data.content;
                        Item = '';
                        for(Index in Content){
                            Item += '<option value="'+Content[Index]['no']+'" >'+Content[Index]['name']+'</option>';
                        }
                        $('#orderFilterModal select[name="status"]').append(Item);
                        $.sessionStorage('workflow_order', Content);
                    }
                }
            });
        }else{
            Item = '';
            for(var index in SessionData){
                Item += '<option value="'+SessionData[index]['no']+'" >'+SessionData[index]['name']+'</option>';
            }
            $('#orderFilterModal select[name="status"]').append(Item);
        }
        $('div#order').handle_page();
        $('div#orderModal').handle_modal_000();
        $('div#orderFilterModal').handle_modal_000();
    })(jQuery);
</script>
