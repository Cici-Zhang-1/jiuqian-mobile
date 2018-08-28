<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 *  rolePageSearch
 * @author Cici
 * @version
 * @description
 * 权限许可视图
 */

?>
<div class="row j-page" id="rolePageSearch">
    <div class="col-md-3 j-page-search">
        <div class="input-group" id="rolePageSearchSearch" data-toggle="filter" data-target="#rolePageSearchTable">
                    </div>
    </div>
    <div class="col-md-9 text-right j-func" id="rolePageSearchFunction">
                                        <div class="btn-group" role="group">
                <button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                    共选中<span id="rolePageSearchTableSelected" data-num="">0</span>项
                    <span class="caret"></span>
                </button>
                <ul class="dropdown-menu" role="menu" data-table="#rolePageSearchTable">
                    <li><a href="javascript:void(0);" data-toggle="" data-target="#rolePageSearchTable" data-action="<?php echo site_url('/permission/role_page_search/index/read');?>" data-multiple=><i class="fa fa-save"></i>&nbsp;&nbsp;保存</a></li>
                                        </div>
                <div class="j-panel col-md-12">
        <div class="panel panel-default">
            <div class="panel-body table-responsive">
                <table class="table table-bordered table-striped table-hover table-condensed" id="rolePageSearchTable" data-load="<?php echo site_url('/permission/role_page_search/read');?>">
                    <thead>
                        <tr>
                            <th class="td-xs checkall" data-name="selected" data-checkall=false>#</th>
                                                                                                <th class="hide d-none" data-name="v">#</th>
                                                                                                                                <th class="hide " data-name="menu_label">菜单名称</th>
                                                                                                                                <th class="hide " data-name="label">页面搜索</th>
                                                                                    </tr>
                    </thead>
                    <tbody>
                    <tr class="loading"><td colspan="15">加载中...</td></tr>
                    <tr class="no-data"><td colspan="15">没有数据</td></tr>
                    <tr class="model">
                        <td name="selected"><input name="v"  type="checkbox" value=""/></td>
                                                                                    <td class="hide d-none" name="v"></td>
                                                                                                                <td class="hide " name="menu_label"></td>
                                                                                                                <td class="hide " name="label"></td>
                                                                        </tr>
                    <tr v-for="(trData, key, index) in card34.data.content" :key="index">
                        <td v-for="(ivalue, ikey, iindex) in elements" :name="ikey" :class="[ ivalue.classes ]" :key="iindex" v-if="ivalue.checked" v-html="trData[ikey]"></td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="panel-footer">
                <div class="hide btn-group pull-right paging">
                    <p class="footnote"></p>
                    <ul class="pagination">
                        <li><a href="1">首页</a></li>
                        <li class=""><a href="javascript:void(0);" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>
                        <li><a href=""></a></li>
                        <li class=""><a href="" aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li>
                        <li><a href="">尾页</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    
                
    </div>

<script type="module">
    (function($){
        new Vue({
            el: '#rolePageSearch',
            data: function () {
                return {}
            },
            computed: {
                                                                },
            created: function() {
                                                                                },
            methods: {

            }
        });
    })(jQuery);
</script>
