<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 *  signin
 * @author Cici
 * @version
 * @description
 * 权限许可视图
 */

?>
<div class="row j-page" id="signin">
    <div class="col-md-3 j-page-search">
        <div class="input-group" id="signinSearch" data-toggle="filter" data-target="#signinTable">
                            <span class="input-group-btn">
                        <button class="btn btn-default" type="button" data-toggle="modal" data-target="#signinFilterModal"><i class="fa fa-search"></i></button>
                    </span>
                                <span class="input-group-btn">
                        <button class="btn btn-default" type="submit">Go!</button>
                    </span>
                    </div>
    </div>
    <div class="col-md-9 text-right j-func" id="signinFunction">
                    </div>
                <div class="j-panel col-md-12">
        <div class="panel panel-default">
            <div class="panel-body table-responsive">
                <table class="table table-bordered table-striped table-hover table-condensed" id="signinTable" data-load="<?php echo site_url('/manage/signin/index/read');?>">
                    <thead>
                        <tr>
                            <th class="td-xs checkall" data-name="selected" data-checkall=false>#</th>
                                                                                                <th class="hide d-none" data-name="v">#</th>
                                                                                                                                <th class="hide " data-name="create_datetime">登录时间</th>
                                                                                                                                <th class="hide " data-name="ip">登录IP</th>
                                                                                                                                <th class="hide " data-name="host">登录主机</th>
                                                                                    </tr>
                    </thead>
                    <tbody>
                    <tr class="loading"><td colspan="15">加载中...</td></tr>
                    <tr class="no-data"><td colspan="15">没有数据</td></tr>
                    <tr class="model">
                        <td name="selected"><input name="v"  type="checkbox" value=""/></td>
                                                                                    <td class="hide d-none" name="v"></td>
                                                                                                                <td class="hide " name="create_datetime"></td>
                                                                                                                <td class="hide " name="ip"></td>
                                                                                                                <td class="hide " name="host"></td>
                                                                        </tr>
                    <tr v-for="(trData, key, index) in card43.data.content" :key="index">
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
    
    
        <div class="modal fade filter" id="signinFilterModal" tabindex="-1" role="dialog" aria-labelledby="signinFilterModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form  class="form-horizontal" id="signinFilterForm" action="" method="post" role="form">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="signinFilterModalLabel">搜索</h4>
                    </div>
                    <div class="modal-body">
                                                                                    <div class="form-group">
                                    <label class="control-label col-md-2" >关键字:</label>
                                    <div class="col-md-6">
                                        <input class="form-control " name="keyword" type="text"       placeholder="关键字" value=""/>
                                    </div>
                                </div>
                                                                            <div class="alert alert-danger alert-dismissible fade in serverError" role="alert"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                        <button type="submit" class="btn btn-primary" data-dismiss="modal">保存</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    </div>

<script type="module">
    (function($){
        new Vue({
            el: '#signin',
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
