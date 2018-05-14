<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2016年1月20日
 * @author Administrator
 * @version
 * @des
 */
?>
    <div class="page-line" id="usergroupRole">
        <div class="my-tools col-md-12">
            <div class="col-md-3">
                <div class="hide input-group" data-toggle="search" data-target="#usergroupRoleTable">
		      		<input type="text" class="form-control" name="keyword" placeholder="搜索">
		      		<span class="input-group-btn">
		        		<button class="btn btn-default" id="usergroupRoleSearchBtn" type="button">Go!</button>
		      		</span>
		    	</div>
			</div>
	  		<div class="col-md-offset-3 col-md-6 text-right" id="usergroupRoleFunction">
	  		    <button class="btn btn-primary" data-toggle="save" data-target="#usergroupRoleTable" type="button" value="保存" data-action="<?php echo site_url('permission/usergroup_role/edit');?>"><i class="fa fa-save"></i>&nbsp;&nbsp;保存</button>
	  			<button class="btn btn-default" data-toggle="refresh" type="button" value="刷新"><i class="fa fa-refresh"></i>&nbsp;&nbsp;刷新</button>
	  			<button class="btn btn-default" data-toggle="reply" type="button" value="返回"><i class="fa fa-refresh"></i>&nbsp;&nbsp;返回</button>
	  		</div>
        </div>
		<div class="my-table col-md-12">
			<table class="table table-bordered table-hover table-responsive table-condensed" id="usergroupRoleTable">
				<thead>
					<tr>
						<th class="td-xs" >#</td>
						<th class="td-xs" >层级</th>
						<th >名称</th>
					</tr>
				</thead>
				<tbody>
				    <?php
				    if(isset($Error)){
				        echo <<<END
<tr class="no-data"><td colspan="9">$Error</td></tr>  
END;
				    }else{
				        $Tr = '';
				        foreach ($content as $key => $value){
				            $Line = '|---';
				            if($value['checked']){
				                $Tr .= <<<END
<tr><td ><input name="rid"  type="checkbox" value="$value[rid]" checked/></td>
<td >$Line</td>
<td >$value[name]</td></tr>
END;
				            }else{
				                $Tr .= <<<END
<tr><td ><input name="rid"  type="checkbox" value="$value[rid]"/></td>
<td >$Line</td>
<td >$value[name]</td></tr>
END;
				            }
				        }
				        echo $Tr;
				    }
				    ?>
				</tbody>
				<input type="hidden" name="uid" value="<?php echo $Id;?>" />
			</table>
		</div>
    </div>
	<script type="text/javascript">
		(function($, window, undefined){
			$('div#usergroupRole').handle_page();
		})(jQuery);
	</script>
