<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created for jiuqian-mobile.
 * User: chuangchuangzhang
 * Date: 2018/3/13
 * Time: 11:13
 *
 * Desc:
 */
?>
<nav class="navbar navbar-default hidden-print j-navbar">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#jNavbar" aria-expanded="false">
                <span class="sr-only">Navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="<?php echo base_url(); ?>"><?php echo $title; ?></a>
        </div>

        <div class="collapse navbar-collapse" id="jNavbar">
            <ul class="nav navbar-nav menu" id="jMenu">
                <?php
                if (valid_array($Menu)) {
                    $MenuString = '';
                    $Flag = false;
                    foreach ($Menu as $Key => $Value) {
                        if (preg_match('/^<i\s+class=\"(.*)\"><\/i>$/', $Value['img'], $Matched)) {
                            $Value['img'] = $Matched[1];
                        }
                        if ($Value['class'] == 0 && ($Value['url'] == 'javascript:void(0);' || $Value['url'] == '#')) {
                            if ($Flag) {
                                $MenuString .= <<<END
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="$Value[img]"></i> $Value[name]</a>
                    <ul class="dropdown-menu">
END;
                            } else {
                                $MenuString .= <<<END
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="$Value[img]"></i> $Value[name]</a>
                    <ul class="dropdown-menu">
END;
                                $Flag = true;
                            }

                        } elseif ($Value['class'] == 0 && !($Value['url'] == 'javascript:void(0);' || $Value['url'] == '#')) {
                            if ($Flag) {
                                $MenuString .= <<<END
                    </ul>
                </li>
                <li><a href="#$Value[url]" data-index="$Key"><i class="$Value[img]"></i> $Value[name]</a></li>
END;
                                $Flag = false;
                            } else {
                                $MenuString .= <<<END
                <li><a href="#$Value[url]" data-index="$Key"><i class="$Value[img]"></i> $Value[name]</a></li>
END;
                            }
                        } elseif ($Value['class'] == 1) {
                            $MenuString .= <<<END
                        <li><a href="#$Value[url]" data-index="$Key"><i class="$Value[img]"></i> $Value[name]</a></li>
END;
                        } else {

                        }
                    }
                    if ($Flag) {
                        $MenuString .= <<<END
                    </ul>
                </li>
END;
                    }
                    echo $MenuString;
                }
                ?>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li><a href="javascript:void(0);"><?php echo $truename;?></a></li>
                <li><a href="<?php echo site_url('sign/out');?>"><i class="fa fa-sign-out"></i>退出</a></li>
            </ul>
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>
<div class="container-fluid">
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-11 col-lg-11 col-md-offset-1 col-lg-offset-1" id="jContent">
            <!-- Nav tabs -->
            <ul class="nav nav-tabs" id="jTabList" role="tablist">
                <li role="presentation" class="clone"><a href="#clone" aria-controls="clone" role="tab" data-toggle="tab" data-index="-1"><span name="title">Clone</span> <i class="fa fa-times"></i></a></li>
                <li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab" data-index="0" data-load="/home"><span name="title">Home</span></a></li>
            </ul>

            <!-- Tab panes -->
            <div class="tab-content" id="jTabContent">
                <div role="tabpanel" class="tab-pane clone" id="clone">Waiting clone...</div>
                <div role="tabpanel" class="tab-pane active" id="home"></div>
            </div>
        </div>
    </div>
</div>
