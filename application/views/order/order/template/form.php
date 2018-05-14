<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created for jiuqian-desk.
 * User: chuangchuangzhang
 * Date: 2018/2/19
 * Time: 11:10
 *
 * Desc: 表单权限控制
 */
?>

<div class="modal fade" id="orderModal" tabindex="-1" role="dialog" aria-labelledby="orderModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form class="form-horizontal" id="orderForm" action="" method="post" role="form">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="orderModalLabel">编辑</h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="selected" value="" />
                    <div class="form-group">
                        <label class="control-label col-md-2" >任务等级:</label>
                        <div class="col-md-6">
                            <select class="form-control" name="flag"></select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-2" >业主:</label>
                        <div class="col-md-6">
                            <input class="form-control" name="owner" type="text" placeholder="业主" value=""/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-2" >对单:</label>
                        <div class="col-md-6">
                            <input class="form-control" name="checker" type="text" placeholder="对单" value=""/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-2" >支付电话:</label>
                        <div class="col-md-6">
                            <input class="form-control" name="checker_phone" type="text" placeholder="对单电话" value=""/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-2" >支付:</label>
                        <div class="col-md-6">
                            <input class="form-control" name="payer" type="text" placeholder="支付" value=""/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-2" >支付电话:</label>
                        <div class="col-md-6">
                            <input class="form-control" name="payer_phone" type="text" placeholder="支付电话" value=""/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-2" >支付条款:</label>
                        <div class="col-md-6">
                            <select class="form-control" name='payterms'><option value="">--选择支付条款--</option></select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-2" >出厂方式:</label>
                        <div class="col-md-6">
                            <select class="form-control" name='out_method'><option value="">--选择出厂方式--</option></select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-2" >物流:</label>
                        <div class="col-md-6">
                            <select class="form-control" name='logistics'><option value="">--选择物流--</option></select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-2" >收货地址:</label>
                        <div class="col-md-3">
                            <select class="form-control" name="delivery_area"><option value="">--选择收货地区--</option></select>
                        </div>
                        <div class="col-md-3">
                            <input class="form-control" name="delivery_address" type="text" placeholder="客户要求收货具体地址" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-2" >收货人:</label>
                        <div class="col-md-6">
                            <input class="form-control" name="delivery_linker" type="text" placeholder="收货人" value=""/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-2" >收货电话:</label>
                        <div class="col-md-6">
                            <input class="form-control" name="delivery_phone" type="text" placeholder="收货电话" value=""/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-2" >要求出厂:</label>
                        <div class="col-md-6">
                            <input class="form-control datepicker" type="text" name="request_outdate" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-2" >备注:</label>
                        <div class="col-md-6">
                            <textarea class="form-control" rows="3" name="remark" ></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-2" >客户备注:</label>
                        <div class="col-md-6">
                            <textarea class="form-control" rows="3" name="dealer_remark" ></textarea>
                        </div>
                    </div>
                    <div class="alert alert-danger alert-dismissible fade in serverError" role="alert"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                    <button type="submit" class="btn btn-primary" data-save="ajax.modal">保存</button>
                </div>
            </form>
        </div>
    </div>
</div>
