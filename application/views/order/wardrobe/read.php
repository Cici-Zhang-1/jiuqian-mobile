<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 *  2015-5-8
 * @author ZhangCC
 * @version
 * @description  
 */
require_once APPPATH.'views/header2.php';
?>
	<div class="col-md-offset-1 col-md-10" id="wardrobe">
				<br />
				<h3>订单编号:<?php echo $Num;?></h3>
				<hr />
				<div class="col-md-12">
					<table class="table table-striped table-bordered table-condensed" id="wardrobeBoard" data-remote="<?php echo site_url('data/goods/read_json/board');?>">
						<thead>
							<tr>
								<td class="td-xs">柜体</td>
								<td class="td-sm">编号</td>
								<td class="td-md">板材</td>
								<td>板块名称</td>
								<td class="td-sm">宽度</td>
								<td class="td-sm">长度</td>
								<td class="td-xs">厚度</td>
								<td>面积</td>
								<td class="td-sm">数量</td>
								<td>单价</td>
								<td>金额</td>
								<td>备注</td>
							</tr>
						</thead>
						<tbody>
							<?php 
							$Num = 0;
							$Area = 0;
							$Count = 0;
							$Amount = 0;
							if(isset($Dismantle['board']) && is_array($Dismantle['board']) && count($Dismantle['board']) > 0){
								foreach ($Dismantle['board'] as $key=>$value){
							?>
									<tr>
										<td><?php echo $key+1;?></td>
										<td><?php echo $value['num'];?></td>
										<td><?php echo $value['board_des']?></td>
										<td><?php echo $value['board_name'];?></td>
										<td><?php echo $value['width_real']?></td>
										<td><?php echo $value['length_real']?></td>
										<td><?php echo $value['thick_real']?></td>
										<td><?php echo $value['area_real']?></td>
										<td><?php echo $value['count']?></td>
										<td><?php echo $value['unit_price']?></td>
										<td><?php echo $value['amount']?></td>
										<td><?php echo $value['remark']?></td>
									</tr>
							<?php 
								}
							}?>
						</tbody>
					</table>
				</div>
				<div class="col-md-12">
					<table class="table table-striped table-bordered table-condensed" id="wardrobeHardware" data-remote="<?php echo site_url('data/goods/read_json/hardware');?>">
						<thead>
							<tr>
								<td class="td-xs">五金</td>
								<td class="td-sm">编号</td>
								<td class="td-md">五金名称</td>
								<td class="td-sm">数量</td>
								<td class="td-sm">单位</td>
								<td class="td-sm">单价</td>
								<td>金额</td>
								<td>备注</td>
							</tr>
						</thead>
						<tbody>
							<?php 
							$Count = 0;
							$Amount = 0;
							if(isset($Dismantle['hardware']) && is_array($Dismantle['hardware']) && count($Dismantle['hardware']) > 0){
								foreach ($Dismantle['hardware'] as $key=>$value){
							?>
									<tr>
										<td><?php echo $key+1;?></td>
										<td><?php echo $value['num'];?></td>
										<td><?php echo $value['hardware_des'];?></td>
										<td><?php echo $value['count']?></td>
										<td><?php echo $value['unit_price']?></td>
										<td><?php echo $value['unit']?></td>
										<td><?php echo $value['amount']?></td>
										<td><?php echo $value['remark']?></td>
									</tr>
							<?php 
								}
							}?>
						</tbody>
					</table>
				</div>
				<div class="col-md-12">
					<table class="table table-striped table-bordered table-condensed" id="wardrobeCraft" data-remote="<?php echo site_url('data/craft/read_json');?>">
						<thead>
							<tr>
								<td class="td-xs">工艺</td>
								<td class="td-sm">编号</td>
								<td class="td-md">工艺名称</td>
								<td class="td-sm">数量</td>
								<td class="td-sm">单位</td>
								<td class="td-sm">单价</td>
								<td>金额</td>
								<td>备注</td>
							</tr>
						</thead>
						<tbody>
							<?php 
							$Count = 0;
							$Amount = 0;
							if(isset($Dismantle['craft']) && is_array($Dismantle['craft']) && count($Dismantle['craft']) > 0){
								foreach ($Dismantle['craft'] as $key=>$value){
							?>
									<tr>
										<td><?php echo $key+1;?></td>
										<td><?php echo $value['num'];?></td>
										<td><?php echo $value['craft_des'];?></td>
										<td><?php echo $value['count']?></td>
										<td><?php echo $value['unit_price']?></td>
										<td><?php echo $value['unit']?></td>
										<td><?php echo $value['amount']?></td>
										<td><?php echo $value['remark']?></td>
									</tr>
							<?php 
								}
							}?>
						</tbody>
					</table>
				</div>
				<div class="col-md-12">
					<table class="table table-striped table-bordered table-condensed" id="wardrobeOutsourcing" data-remote="<?php echo site_url('data/goods/read_json/outsourcing');?>">
						<thead>
							<tr>
								<td class="td-xs">外购</td>
								<td class="td-sm">编号</td>
								<td class="td-md">外购产品名称</td>
								<td class="td-sm">数量</td>
								<td class="td-sm">单位</td>
								<td class="td-sm">单价</td>
								<td>金额</td>
								<td>备注</td>
							</tr>
						</thead>
						<tbody>
							<?php 
							$Count = 0;
							$Amount = 0;
							if(isset($Dismantle['outsourcing']) && is_array($Dismantle['outsourcing']) && count($Dismantle['outsourcing']) > 0){
								foreach ($Dismantle['outsourcing'] as $key=>$value){
							?>
									<tr>
										<td><?php echo $key+1;?></td>
										<td><?php echo $value['num'];?></td>
										<td><?php echo $value['outsourcing_des'];?></td>
										<td><?php echo $value['count']?></td>
										<td><?php echo $value['unit_price']?></td>
										<td><?php echo $value['unit']?></td>
										<td><?php echo $value['amount']?></td>
										<td><?php echo $value['remark']?></td>
									</tr>
							<?php 
								}
							}?>
						</tbody>
					</table>
				</div>
			</div>
	</body>
</html>