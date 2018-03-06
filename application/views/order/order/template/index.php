<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created for jiuqian-desk.
 * User: chuangchuangzhang
 * Date: 2018/2/19
 * Time: 12:44
 *
 * Desc:
 */
?>
<div class="page-line" id="order">
    <div class="my-tools col-md-12">
        <div class="col-md-3">
            <?php include_once 'page_search.php'; ?>
        </div>
        <div class="col-md-offset-3 col-md-6 text-right" id="orderFunction">
            <?php include_once 'func.php'; ?>
        </div>
    </div>
    <div class="my-table col-md-12">
        <?php include_once 'card.php'; ?>
    </div>
    <div class="floatover hide" id="orderFloatover"></div>
</div>

<?php include_once 'form.php'; ?>

<div class="modal fade filter" id="orderFilterModal" tabindex="-1" role="dialog" aria-labelledby="orderFilterModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form  class="form-horizontal" id="orderFilterForm" action="" method="post" role="form">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="orderFilterModalLabel">搜索</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="control-label col-md-2">状态:</label>
                        <div class="col-md-6">
                            <select class="form-control" name="status" multiple="multiple"></select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-2">开始日期:</label>
                        <div class="col-md-6">
                            <input class="form-control datepicker" name="start_date" value="" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-2">结束日期:</label>
                        <div class="col-md-6">
                            <input class="form-control datepicker" name="end_date" value="" />
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                    <button type="submit" class="btn btn-primary" data-dismiss="modal">保存</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php include_once 'script.php'; ?>
