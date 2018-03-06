<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: chuangchuangzhang
 * Date: 2017/8/22
 * Time: 10:42
 *
 * Desc:
 */
?>
        <div class="my-print-data container-fluid">
            <div class="row">
                <div class="col-md-offset-2 col-md-8">
                    <table class="table table-bordered table-striped table-hover table-responsive table-condensed" id="selfDismantleDetailTable">
                        <thead>
                            <tr>
                                <th>编号<i class="fa fa-sort"></i></th>
                                <th>分类<i class="fa fa-sort"></i></th>
                                <th>厚度<i class="fa fa-sort"></i></th>
                                <th>订单编号<i class="fa fa-sort"></i></th>
                                <th>面积<i class="fa fa-sort"></i></th>
                                <th>拆单时间<i class="fa fa-sort"></i></th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                            if (isset($detail) && is_array($detail) && count($detail) > 0) {
                                $i = 1;
                                foreach ($detail as $key => $value) {
                                    echo <<<END
<tr>
    <td>$i</td>
    <td>$value[name]</td>
    <td>$value[board]</td>
    <td>$value[num]</td>
    <td>$value[area]</td>
    <td>$value[dismantled_datetime]</td>
</tr>
END;
                                    $i++;

                                }
                            }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <script src="http://cdn.bootcss.com/jquery/2.1.4/jquery.js"></script>
        <script src="http://cdn.bootcss.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
        <script src="http://cdn.bootcss.com/jquery.tablesorter/2.23.5/js/jquery.tablesorter.min.js"></script>
        <script>
            (function($){
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
                $('#selfDismantleDetailTable').tablesorter($(this).find('thead tr').getHeaders()); /** 表格排序*/
            })(jQuery);
        </script>
    </body>
</html>