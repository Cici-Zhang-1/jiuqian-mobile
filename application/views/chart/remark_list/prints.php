<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/23
 * Time: 10:21
 * Des: 打印备忘单
 */
$Nums = count($order);
$Rows = floor($Nums / TWO);
$Two = false; // 是否分左右两列
if ($Rows > ZERO) {
    $Two = true;
}
$Single = $Nums % TWO;
if ($Single) {
    $Rows++;
}
?>
            <div class="my-print-data container-fluid">

                    <?php
                    $Html = '';
                    if ($Two) {
                        for ($I = 0; $I < $Nums; $I++) {
                            if (isset($order[$I])) {
                                $Random = $order[$I]['order_product_board'][array_rand($order[$I]['order_product_board'], ONE)];
                                $Html .= <<<END
<div class="page-line row">
  <div class="j-remark-list col-md-6">
      <p class="my-enhance-2">$order[$I][num]$order[$I][remark]</p>
      <table class="my-table-condensed table table-bordered table-condensed">
          <caption class="j-caption">$Random[num]$Random[order_product_remark]</caption>
          <tbody>
END;
                                foreach ($order[$I]['order_product_board'] as $Key => $Value) {
                                    $Html .= <<<END
            <tr><td>$Value[board]</td><td>$Value[amount]</td><td>$Value[area]</td></tr>
END;
                                }
                                $Html .= <<<END
          </tbody>
      </table>
  </div>
END;
                            }
                            $I++;
                            if (isset($order[$I])) {
                                $Random = $order[$I]['order_product_board'][array_rand($order[$I]['order_product_board'], ONE)];
                                $Html .= <<<END
  <div class="j-remark-list col-md-6">
      <p class="my-enhance-2">$order[$I][num]$order[$I][remark]</p>
      <table class="my-table-condensed table table-bordered table-condensed">
          <caption class="j-caption">$Random[num]$Random[order_product_remark]</caption>
          <tbody>
END;
                                foreach ($order[$I]['order_product_board'] as $Key => $Value) {
                                    $Html .= <<<END
            <tr><td>$Value[board]</td><td>$Value[amount]</td><td>$Value[area]</td></tr>
END;
                                }
                                $Html .= <<<END
          </tbody>
      </table>
  </div>
END;
                            }
                            $Html .= <<<END
</div>
END;

                        }
                    } else {
                        for ($I = 0; $I < $Nums; $I++) {
                            if (isset($order[$I])) {
                                $Random = $order[$I]['order_product_board'][array_rand($order[$I]['order_product_board'], ONE)];
                                $num = $order[$I]['num'];
                                $remark = $order[$I]['remark'];
                                $Html .= <<<END
<div class="page-line row">
  <div class="j-remark-list col-md-6">
      <p class="my-enhance-2">$num $remark</p>
      <table class="my-table-condensed table table-bordered table-condensed">
          <caption class="j-caption my-enhance-2">$Random[num]$Random[order_product_remark]</caption>
          <tbody>
END;
                                foreach ($order[$I]['order_product_board'] as $Key => $Value) {
                                    $Html .= <<<END
            <tr><td>$Value[board]</td><td>$Value[amount]</td><td>$Value[area]</td></tr>
END;
                                }
                                $Html .= <<<END
          </tbody>
      </table>
  </div>
</div>
END;
                            }
                        }
                    }
                    echo $Html;
                    ?>
                </div>
            </div>
        </div>
    </body>
</html>
