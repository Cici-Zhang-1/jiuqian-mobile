<?php
use \Wo as Wo;
use \Wop as Wop;
use \Wm as Wm;
use \Wopb as Wopb;
use \Wopc as Wopc;
use \Wopf as Wopf;
use \Wopo as Wopo;
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2016年1月6日
 * @author Zhangcc
 * @version
 * @des
 * 工作流
 */
 
class Workflow{
    /**
     * 初始化，设置工作流节点类型(订单，订单产品，订单产品板材，订单产品板块...)，根据类型设置模型(Model)
     * 设置对应的源Id，获取所有的工作流节点
     * @param unknown $Type
     */
    public function initialize($Type){
        $H = false;
        switch ($Type) {
            case 'order':
                require_once 'Workflow_order.php';
                $H = new Wo\Workflow_order();
                break;
            case 'order_product':
                require_once 'Workflow_order_product.php';
                $H = new Wop\Workflow_order_product();
                break;
            case 'order_product_board':
                require_once 'Workflow_order_product_board.php';
                $H = new Wopb\Workflow_order_product_board();
                break;
            case 'order_product_classify':
                require_once 'Workflow_order_product_classify.php';
                $H = new Wopc\Workflow_order_product_classify();
                break;
            case 'order_product_fitting':
                require_once 'Workflow_order_product_fitting.php';
                $H = new Wopf\Workflow_order_product_fitting();
                break;
            case 'order_product_other':
                require_once 'Workflow_order_product_other.php';
                $H = new Wopo\Workflow_order_product_other();
                break;
            case 'mrp':
                require_once 'Workflow_mrp.php';
                $H = new Wm\Workflow_mrp();
                break;
        }
        return $H;
    }
}