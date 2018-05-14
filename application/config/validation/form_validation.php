<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 *  2014-10-28
 * @author ZhangCC
 * @version
 * @description  
 */

$config = array(
		'test/create' => array(
				array(
						'field' => 'userphone',
						'label' => '注册手机号码',
						'rules' => 'trim|required'
				)	
        ),
		'sign/in' => array(
				array(
						'field' => 'username',
						'label' => '用户名',
						'rules' => 'trim|required|min_length[1]|max_length[64]'
				),
				array(
						'field' => 'password',
						'label' => '登录密码',
						'rules' => 'trim|required|min_length[6]'
				)
		),
        'data/abnormity/add' => array(
            array(
                'field' => 'name',
                'label' => '异形名称',
                'rules' => 'trim|required|min_length[1]|max_length[64]'
            )
        ),
        'data/abnormity/edit' => array(
            array(
                'field' => 'selected',
                'label' => '编号',
                'rules' => 'required|numeric|min_length[1]|max_length[4]'
            ),
            array(
                'field' => 'name',
                'label' => '异形名称',
                'rules' => 'trim|required|min_length[1]|max_length[64]'
            )
        ),
        'data/abnormity/remove' => array(
            array(
                'field' => 'selected[]',
                'label' => '选择项',
                'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
            )
        
        ),
        'data/area/add' => array(
                array(
                    'field' => 'province',
                    'label' => '省',
                    'rules' => 'trim|max_length[32]'
                ),
                array(
                    'field' => 'city',
                    'label' => '市',
                    'rules' => 'trim|required|max_length[32]'
                ),
                array(
                    'field' => 'county',
                    'label' => '县/区',
                    'rules' => 'trim|max_length[32]'
                )
        ),
        'data/area/edit' => array(
                array(
                    'field' => 'selected',
                    'label' => '编号',
                    'rules' => 'required|numeric|min_length[1]|max_length[4]'
                ),
                array(
                    'field' => 'province',
                    'label' => '省',
                    'rules' => 'trim|max_length[32]'
                ),
                array(
                    'field' => 'city',
                    'label' => '市',
                    'rules' => 'trim|required|max_length[32]'
                ),
                array(
                    'field' => 'county',
                    'label' => '县/区',
                    'rules' => 'trim|max_length[32]'
                )
        ),
        'data/area/remove' => array(
                array(
                    'field' => 'selected[]',
                    'label' => '选择项',
                    'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
                )
        ),
        'data/board_class/add' => array(
            array(
                'field' => 'name',
                'label' => '板材环保等级',
                'rules' => 'trim|required|min_length[1]|max_length[4]'
            )
        ),
        'data/board_class/edit' => array(
            array(
                'field' => 'selected',
                'label' => '编号',
                'rules' => 'required|numeric|min_length[1]|max_length[4]'
            ),
            array(
                'field' => 'name',
                'label' => '板材环保等级',
                'rules' => 'trim|required|min_length[1]|max_length[4]'
            )
        ),
        'data/board_class/remove' => array(
            array(
                'field' => 'selected[]',
                'label' => '选择项',
                'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
            )
        
        ),
        'data/board_color/add' => array(
            array(
                'field' => 'name',
                'label' => '板材颜色名称',
                'rules' => 'trim|required|min_length[1]|max_length[64]'
            )
        ),
        'data/board_color/edit' => array(
            array(
                'field' => 'selected',
                'label' => '编号',
                'rules' => 'required|numeric|min_length[1]|max_length[4]'
            ),
            array(
                'field' => 'name',
                'label' => '板材颜色名称',
                'rules' => 'trim|required|min_length[1]|max_length[64]'
            )
        ),
        'data/board_color/remove' => array(
            array(
                'field' => 'selected[]',
                'label' => '选择项',
                'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
            )
        
        ),
        'data/board_nature/add' => array(
            array(
                'field' => 'name',
                'label' => '板材材质名称',
                'rules' => 'trim|required|min_length[1]|max_length[64]'
            )
        ),
        'data/board_nature/edit' => array(
            array(
                'field' => 'selected',
                'label' => '编号',
                'rules' => 'required|numeric|min_length[1]|max_length[4]'
            ),
            array(
                'field' => 'name',
                'label' => '板材材质名称',
                'rules' => 'trim|required|min_length[1]|max_length[64]'
            )
        ),
        'data/board_nature/remove' => array(
            array(
                'field' => 'selected[]',
                'label' => '选择项',
                'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
            )
        
        ),
        'data/board_thick/add' => array(
            array(
                'field' => 'name',
                'label' => '板材厚度',
                'rules' => 'trim|required|numeric|min_length[1]|max_length[10]'
            )
        ),
        'data/board_thick/edit' => array(
            array(
                'field' => 'selected',
                'label' => '编号',
                'rules' => 'required|numeric|min_length[1]|max_length[4]'
            ),
            array(
                'field' => 'name',
                'label' => '板材厚度',
                'rules' => 'trim|required|numeric|min_length[1]|max_length[10]'
            )
        ),
        'data/board_thick/remove' => array(
            array(
                'field' => 'selected[]',
                'label' => '选择项',
                'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
            )
        ),
        'data/classify/add' => array(
            array(
                'field' => 'name',
                'label' => '分类名称',
                'rules' => 'trim|required|min_length[1]|max_length[32]'
            ),
            array(
                'field' => 'class',
                'label' => '等级',
                'rules' => 'trim|required|numeric|max_length[2]'
            ),
            array(
                'field' => 'parent',
                'label' => '父类',
                'rules' => 'trim|required|numeric|max_length[4]'
            ),
            array(
                'field' => 'flag',
                'label' => '标记',
                'rules' => 'trim|max_length[8]'
            ),
            array(
                'field' => 'print_list',
                'label' => '打印清单',
                'rules' => 'trim|required|numeric|max_length[1]'
            ),
            array(
                'field' => 'label',
                'label' => '打印标签',
                'rules' => 'trim|required|numeric|max_length[1]'
            ),
            array(
                'field' => 'optimize',
                'label' => '进优化',
                'rules' => 'trim|required|numeric|max_length[1]'
            ),
            array(
                'field' => 'plate_name',
                'label' => '板块名称',
                'rules' => 'trim|max_length[32]'
            ),
            array(
                'field' => 'width_min',
                'label' => '宽最小尺寸',
                'rules' => 'trim|floatval|decimal|max_length[10]'
            ),
            array(
                'field' => 'width_max',
                'label' => '宽最大尺寸',
                'rules' => 'trim|floatval|decimal|max_length[10]'
            ),
            array(
                'field' => 'length_min',
                'label' => '长最小尺寸',
                'rules' => 'trim|floatval|decimal|max_length[10]'
            ),
            array(
                'field' => 'length_max',
                'label' => '长最大尺寸',
                'rules' => 'trim|floatval|decimal|max_length[10]'
            ),
            array(
                'field' => 'thick',
                'label' => '厚度',
                'rules' => 'trim|intval|numeric|max_length[10]'
            ),
            array(
                'field' => 'edge',
                'label' => '封边',
                'rules' => 'trim|max_length[32]'
            ),
            array(
                'field' => 'slot',
                'label' => '开槽',
                'rules' => 'trim|max_length[64]'
            ),
            array(
                'field' => 'remark',
                'label' => '备注',
                'rules' => 'trim|max_length[128]'
            ),
            array(
                'field' => 'process',
                'label' => '流程',
                'rules' => 'trim[,]|max_length[1024]'
            ),
            array(
                'field' => 'status',
                'label' => '状态',
                'rules' => 'trim|required|numeric|max_length[1]'
            )
        ),
        'data/classify/edit' => array(
            array(
                'field' => 'selected',
                'label' => '编号',
                'rules' => 'required|numeric|min_length[1]|max_length[4]'
            ),
            array(
                'field' => 'name',
                'label' => '分类名称',
                'rules' => 'trim|required|min_length[1]|max_length[32]'
            ),
            array(
                'field' => 'class',
                'label' => '等级',
                'rules' => 'trim|required|numeric|max_length[2]'
            ),
            array(
                'field' => 'parent',
                'label' => '父类',
                'rules' => 'trim|required|numeric|max_length[4]'
            ),
            array(
                'field' => 'flag',
                'label' => '标记',
                'rules' => 'trim|max_length[8]'
            ),
            array(
                'field' => 'print_list',
                'label' => '打印清单',
                'rules' => 'trim|required|numeric|max_length[1]'
            ),
            array(
                'field' => 'label',
                'label' => '打印标签',
                'rules' => 'trim|required|numeric|max_length[1]'
            ),
            array(
                'field' => 'optimize',
                'label' => '进优化',
                'rules' => 'trim|required|numeric|max_length[1]'
            ),
            array(
                'field' => 'plate_name',
                'label' => '板块名称',
                'rules' => 'trim|max_length[32]'
            ),
            array(
                'field' => 'width_min',
                'label' => '宽最小尺寸',
                'rules' => 'trim|floatval|decimal|max_length[10]'
            ),
            array(
                'field' => 'width_max',
                'label' => '宽最大尺寸',
                'rules' => 'trim|floatval|decimal|max_length[10]'
            ),
            array(
                'field' => 'length_min',
                'label' => '长最小尺寸',
                'rules' => 'trim|floatval|decimal|max_length[10]'
            ),
            array(
                'field' => 'length_max',
                'label' => '长最大尺寸',
                'rules' => 'trim|floatval|decimal|max_length[10]'
            ),
            array(
                'field' => 'thick',
                'label' => '厚度',
                'rules' => 'trim|intval|numeric|max_length[10]'
            ),
            array(
                'field' => 'edge',
                'label' => '封边',
                'rules' => 'trim|max_length[32]'
            ),
            array(
                'field' => 'slot',
                'label' => '开槽',
                'rules' => 'trim|max_length[64]'
            ),
            array(
                'field' => 'remark',
                'label' => '备注',
                'rules' => 'trim|max_length[128]'
            ),
            array(
                'field' => 'process',
                'label' => '流程',
                'rules' => 'trim[,]|max_length[1024]'
            ),
            array(
                'field' => 'status',
                'label' => '状态',
                'rules' => 'trim|required|numeric|max_length[1]'
            )
        ),
        'data/classify/remove' => array(
            array(
                'field' => 'selected[]',
                'label' => '选择项',
                'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
            )
        ),
        'data/classify/act' => array(
            array(
                'field' => 'selected[]',
                'label' => '选择项',
                'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
            )
        ),
        'data/face/add' => array(
            array(
                'field' => 'flag',
                'label' => '单双面标记',
                'rules' => 'trim|required|min_length[1]|max_length[128]'
            ),
            array(
                'field' => 'wardrobe_punch',
                'label' => '衣柜打孔名称',
                'rules' => 'trim|max_length[64]'
            ),
            array(
                'field' => 'wardrobe_slot',
                'label' => '衣柜开槽名称',
                'rules' => 'trim|max_length[64]'
            )
        ),
        'data/face/edit' => array(
            array(
                'field' => 'selected',
                'label' => '编号',
                'rules' => 'required|numeric|min_length[1]|max_length[4]'
            ),
            array(
                'field' => 'flag',
                'label' => '单双面标记',
                'rules' => 'trim|required|min_length[1]|max_length[128]'
            ),
            array(
                'field' => 'wardrobe_punch',
                'label' => '衣柜打孔名称',
                'rules' => 'trim|max_length[64]'
            ),
            array(
                'field' => 'wardrobe_slot',
                'label' => '衣柜开槽名称',
                'rules' => 'trim|max_length[64]'
            )
        ),
        'data/face/remove' => array(
            array(
                'field' => 'selected[]',
                'label' => '选择项',
                'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
            )
        ),
        'data/logistics/add' => array(
            array(
                'field' => 'name',
                'label' => '物流名称',
                'rules' => 'trim|required|min_length[1]|max_length[64]'
            ),
            array(
                'field' => 'aid',
                'label' => '地址',
                'rules' => 'trim|numeric|max_length[10]'
            ),
            array(
                'field' => 'address',
                'label' => '街道',
                'rules' => 'trim|max_length[64]'
            ),
            array(
                'field' => 'phone',
                'label' => '联系方式',
                'rules' => 'trim|max_length[64]'
            ),
            array(
                'field' => 'vip',
                'label' => 'vip号',
                'rules' => 'trim|max_length[64]'
            )
        ),
        'data/logistics/edit' => array(
            array(
                'field' => 'selected',
                'label' => '编号',
                'rules' => 'required|numeric|min_length[1]|max_length[4]'
            ),
            array(
                'field' => 'name',
                'label' => '物流名称',
                'rules' => 'trim|required|min_length[1]|max_length[64]'
            ),
            array(
                'field' => 'aid',
                'label' => '地址',
                'rules' => 'trim|numeric|max_length[10]'
            ),
            array(
                'field' => 'address',
                'label' => '街道',
                'rules' => 'trim|max_length[64]'
            ),
            array(
                'field' => 'phone',
                'label' => '联系方式',
                'rules' => 'trim|max_length[64]'
            ),
            array(
                'field' => 'vip',
                'label' => 'vip号',
                'rules' => 'trim|max_length[64]'
            )
        ),
        'data/logistics/remove' => array(
            array(
                'field' => 'selected[]',
                'label' => '选择项',
                'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
            )
        
        ),
        'data/order_type/add' => array(
                array(
                    'field' => 'name',
                    'label' => '名称',
                    'rules' => 'trim|required|min_length[1]|max_length[16]'
                ),
                array(
                    'field' => 'code',
                    'label' => '代号',
                    'rules' => 'trim|required|min_length[1]|max_length[4]'
                )
        ),
        'data/order_type/edit' => array(
                array(
                    'field' => 'selected',
                    'label' => '编号',
                    'rules' => 'required|numeric|min_length[1]|max_length[2]'
                ),
                array(
                    'field' => 'name',
                    'label' => '名称',
                    'rules' => 'trim|required|min_length[1]|max_length[16]'
                ),
                array(
                    'field' => 'code',
                    'label' => '代号',
                    'rules' => 'trim|required|min_length[1]|max_length[4]'
                )
        ),
        'data/out_method/add' => array(
            array(
                'field' => 'name',
                'label' => '出厂方式名称',
                'rules' => 'trim|required|min_length[1]|max_length[64]'
            )
        ),
        'data/out_method/edit' => array(
            array(
                'field' => 'selected',
                'label' => 'selected',
                'rules' => 'required|numeric|min_length[1]|max_length[2]'
            ),
            array(
                'field' => 'name',
                'label' => '出厂方式名称',
                'rules' => 'trim|required|min_length[1]|max_length[64]'
            )
        ),
        'data/out_method/remove' => array(
            array(
                'field' => 'selected[]',
                'label' => 'selected',
                'rules' => 'required|numeric|min_length[1]|max_length[2]'
            )
        ),
        'data/task_level/add' => array(
                array(
                    'field' => 'name',
                    'label' => '名称',
                    'rules' => 'trim|required|min_length[1]|max_length[64]'
                ),
                array(
                    'field' => 'icon',
                    'label' => 'icon',
                    'rules' => 'trim|required|min_length[1]|max_length[64]'
                ),
                array(
                    'field' => 'remark',
                    'label' => '备注',
                    'rules' => 'trim|min_length[1]|max_length[64]'
                )
        ),
        'data/task_level/edit' => array(
                array(
                    'field' => 'selected',
                    'label' => 'selected',
                    'rules' => 'required|numeric|min_length[1]|max_length[2]'
                ),
                array(
                    'field' => 'name',
                    'label' => '名称',
                    'rules' => 'trim|required|min_length[1]|max_length[64]'
                ),
                array(
                    'field' => 'icon',
                    'label' => 'icon',
                    'rules' => 'trim|required|min_length[1]|max_length[64]'
                ),
                array(
                    'field' => 'remark',
                    'label' => '备注',
                    'rules' => 'trim|min_length[1]|max_length[64]'
                )
        ),
        'data/task_level/remove' => array(
                array(
                    'field' => 'selected',
                    'label' => 'selected',
                    'rules' => 'required|numeric|min_length[1]|max_length[2]'
                )
        ),
        'data/train/add' => array(
            array(
                'field' => 'name',
                'label' => '车次名称',
                'rules' => 'trim|required|min_length[1]|max_length[64]'
            )
        ),
        'data/train/edit' => array(
            array(
                'field' => 'selected',
                'label' => '编号',
                'rules' => 'required|numeric|min_length[1]|max_length[4]'
            ),
            array(
                'field' => 'name',
                'label' => '车次名称',
                'rules' => 'trim|required|min_length[1]|max_length[64]'
            )
        ),
        'data/train/remove' => array(
            array(
                'field' => 'selected[]',
                'label' => '选择项',
                'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
            )
        ),
        'data/truck/add' => array(
            array(
                'field' => 'name',
                'label' => '货车名称',
                'rules' => 'trim|required|min_length[1]|max_length[64]'
            )
        ),
        'data/truck/edit' => array(
            array(
                'field' => 'selected',
                'label' => '编号',
                'rules' => 'required|numeric|min_length[1]|max_length[4]'
            ),
            array(
                'field' => 'name',
                'label' => '货车名称',
                'rules' => 'trim|required|min_length[1]|max_length[64]'
            )
        ),
        'data/truck/remove' => array(
            array(
                'field' => 'selected[]',
                'label' => '选择项',
                'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
            )
        ),
        'data/wardrobe_board/add' => array(
            array(
                'field' => 'name',
                'label' => '衣柜板块名称',
                'rules' => 'trim|required|min_length[1]|max_length[64]'
            )
        ),
        'data/wardrobe_board/edit' => array(
            array(
                'field' => 'selected',
                'label' => '编号',
                'rules' => 'required|numeric|min_length[1]|max_length[4]'
            ),
            array(
                'field' => 'name',
                'label' => '衣柜板块名称',
                'rules' => 'trim|required|min_length[1]|max_length[64]'
            )
        ),
        'data/wardrobe_board/remove' => array(
            array(
                'field' => 'selected[]',
                'label' => '选择项',
                'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
            )
        ),
        'data/wardrobe_edge/add' => array(
            array(
                'field' => 'name',
                'label' => '衣柜封边名称',
                'rules' => 'trim|required|min_length[1]|max_length[64]'
            ),
            array(
                'field' => 'ups',
                'label' => '上',
                'rules' => 'trim|floatval|max_length[5]'
            ),
            array(
                'field' => 'downs',
                'label' => '下',
                'rules' => 'trim|floatval|max_length[5]'
            ),
            array(
                'field' => 'lefts',
                'label' => '左',
                'rules' => 'trim|floatval|max_length[5]'
            ),
            array(
                'field' => 'rights',
                'label' => '右',
                'rules' => 'trim|floatval|max_length[5]'
            )
        ),
        'data/wardrobe_edge/edit' => array(
            array(
                'field' => 'selected',
                'label' => '编号',
                'rules' => 'required|numeric|min_length[1]|max_length[4]'
            ),
            array(
                'field' => 'name',
                'label' => '衣柜封边名称',
                'rules' => 'trim|required|min_length[1]|max_length[64]'
            ),
            array(
                'field' => 'ups',
                'label' => '上',
                'rules' => 'trim|floatval|max_length[5]'
            ),
            array(
                'field' => 'downs',
                'label' => '下',
                'rules' => 'trim|floatval|max_length[5]'
            ),
            array(
                'field' => 'lefts',
                'label' => '左',
                'rules' => 'trim|floatval|max_length[5]'
            ),
            array(
                'field' => 'rights',
                'label' => '右',
                'rules' => 'trim|floatval|max_length[5]'
            )
        ),
        'data/wardrobe_edge/remove' => array(
            array(
                'field' => 'selected[]',
                'label' => '选择项',
                'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
            )
        ),
        'data/wardrobe_slot/add' => array(
            array(
                'field' => 'name',
                'label' => '衣柜开槽名称',
                'rules' => 'trim|required|min_length[1]|max_length[64]'
            )
        ),
        'data/wardrobe_slot/edit' => array(
            array(
                'field' => 'selected',
                'label' => '编号',
                'rules' => 'required|numeric|min_length[1]|max_length[4]'
            ),
            array(
                'field' => 'name',
                'label' => '衣柜开槽名称',
                'rules' => 'trim|required|min_length[1]|max_length[64]'
            )
        ),
        'data/wardrobe_slot/remove' => array(
            array(
                'field' => 'selected[]',
                'label' => '选择项',
                'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
            )
        ),
        'data/wardrobe_punch/add' => array(
            array(
                'field' => 'name',
                'label' => '衣柜打孔名称',
                'rules' => 'trim|required|min_length[1]|max_length[64]'
            )
        ),
        'data/wardrobe_punch/edit' => array(
            array(
                'field' => 'selected',
                'label' => '编号',
                'rules' => 'required|numeric|min_length[1]|max_length[4]'
            ),
            array(
                'field' => 'name',
                'label' => '衣柜打孔名称',
                'rules' => 'trim|required|min_length[1]|max_length[64]'
            )
        ),
        'data/wardrobe_punch/remove' => array(
            array(
                'field' => 'selected[]',
                'label' => '选择项',
                'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
            )
        ),
        'data/workflow/add' => array(
            array(
                'field' => 'no',
                'label' => '编号',
                'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
            ),
            array(
                'field' => 'type',
                'label' => '类型',
                'rules' => 'trim|required|min_length[1]|max_length[64]'
            ),
            array(
                'field' => 'name',
                'label' => '名称',
                'rules' => 'trim|required|min_length[1]|max_length[64]'
            ),
            array(
                'field' => 'name_en',
                'label' => '名称',
                'rules' => 'trim|required|min_length[1]|max_length[64]'
            ),
            array(
                'field' => 'file',
                'label' => '名称',
                'rules' => 'trim|required|min_length[1]|max_length[64]'
            )
        ),
        'data/workflow/edit' => array(
            array(
                'field' => 'selected',
                'label' => '编号',
                'rules' => 'required|numeric|min_length[1]|max_length[4]'
            ),
            array(
                'field' => 'no',
                'label' => '流程节点编号',
                'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
            ),
            array(
                'field' => 'type',
                'label' => '类型',
                'rules' => 'trim|required|min_length[1]|max_length[64]'
            ),
            array(
                'field' => 'name',
                'label' => '名称',
                'rules' => 'trim|required|min_length[1]|max_length[64]'
            ),
            array(
                'field' => 'name_en',
                'label' => '名称',
                'rules' => 'trim|required|min_length[1]|max_length[64]'
            ),
            array(
                'field' => 'file',
                'label' => '名称',
                'rules' => 'trim|required|min_length[1]|max_length[64]'
            )
        ),
        'data/workflow/remove' => array(
            array(
                'field' => 'selected[]',
                'label' => '编号',
                'rules' => 'required|numeric|min_length[1]|max_length[4]'
            )
        ),
        'dealer/dealer/add' => array(
            array(
                'field' => 'des',
                'label' => '名称',
                'rules' => 'trim|required|min_length[1]|max_length[64]'
            ),
            array(
                'field' => 'shop',
                'label' => '店名',
                'rules' => 'trim|min_length[1]|max_length[64]'
            ),
            array(
                'field' => 'aid',
                'label' => '地址',
                'rules' => 'trim|numeric|max_length[10]'
            ),
            array(
                'field' => 'dcid',
                'label' => '类型',
                'rules' => 'trim|required|numeric|min_length[1]|max_length[2]'
            ),
            array(
                'field' => 'address',
                'label' => '街道',
                'rules' => 'trim|max_length[64]'
            ),
            array(
                'field' => 'pid',
                'label' => '支付条款',
                'rules' => 'trim|required|is_natural_no_zero|max_length[2]'
            ),
            array(
                'field' => 'remark',
                'label' => '备注',
                'rules' => 'trim|max_length[128]'
            ),
            array(
                'field' => 'delivery_linker',
                'label' => '联系人姓名',
                'rules' => 'trim|required|min_length[1]|max_length[64]'
            ),
            array(
                'field' => 'delivery_phone',
                'label' => '联系人姓名',
                'rules' => 'trim|required|min_length[1]|max_length[64]'
            ),
            array(
                'field' => 'daid',
                'label' => '收货地址',
                'rules' => 'trim|numeric|max_length[10]'
            ),
            array(
                'field' => 'delivery_address',
                'label' => '收货地址详情',
                'rules' => 'trim|max_length[64]'
            ),
            array(
                'field' => 'lid',
                'label' => '要求物流',
                'rules' => 'trim|numeric|max_length[4]'
            ),
            array(
                'field' => 'omid',
                'label' => '出厂方式',
                'rules' => 'trim|numeric|max_length[2]'
            ),
            array(
                'field' => 'name',
                'label' => '联系人姓名',
                'rules' => 'trim|required|min_length[1]|max_length[64]'
            ),
            array(
                'field' => 'mobilephone',
                'label' => '移动电话',
                'rules' => 'trim|required|numeric|max_length[16]'
            ),
            array(
                'field' => 'telephone',
                'label' => '固话',
                'rules' => 'trim|max_length[16]|gh_str_replace'
            ),
            array(
                'field' => 'email',
                'label' => '邮箱',
                'rules' => 'trim|valid_email|max_length[128]'
            ),
            array(
                'field' => 'qq',
                'label' => 'QQ',
                'rules' => 'trim|numeric|max_length[16]'
            ),
            array(
                'field' => 'fax',
                'label' => 'Fax',
                'rules' => 'trim|numeric|max_length[16]|gh_str_replace'
            ),
            array(
                'field' => 'doid',
                'label' => '员工类型',
                'rules' => 'trim|numeric|max_length[2]'
            )
        ),
		'dealer/dealer/edit' => array(
				array(
						'field' => 'selected',
						'label' => '经销商编号',
						'rules' => 'trim|required|numeric|min_length[1]|max_length[10]'
				),
				array(
						'field' => 'des',
						'label' => '名称',
						'rules' => 'trim|required|min_length[1]|max_length[64]'
				),
                array(
                    'field' => 'shop',
                    'label' => '店名',
                    'rules' => 'trim|min_length[1]|max_length[64]'
                ),
				array(
						'field' => 'aid',
						'label' => '地址',
						'rules' => 'trim|numeric|max_length[64]'
				),
    		    array(
    		        'field' => 'address',
    		        'label' => '街道',
    		        'rules' => 'trim|max_length[64]'
    		    ),
    		    array(
        		        'field' => 'pid',
        		        'label' => '支付条款',
        		        'rules' => 'trim|required|is_natural_no_zero|max_length[2]'
    		    ),
				
				array(
						'field' => 'dcid',
						'label' => '类型',
						'rules' => 'trim|required|numeric|min_length[1]|max_length[2]'
				),
				array(
						'field' => 'remark',
						'label' => '备注',
						'rules' => 'trim|max_length[128]'
				),
                array(
                    'field' => 'password',
                    'label' => '密码',
                    'rules' => 'trim|max_length[128]'
                )
		),
		'dealer/dealer/remove' => array(
				array(
						'field' => 'selected[]',
						'label' => '经销商编号',
						'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
				)
		),
        'dealer/dealer_category/add' => array(
            array(
                'field' => 'name',
                'label' => '名称',
                'rules' => 'trim|required|min_length[1]|max_length[32]'
            )
        ),
        'dealer/dealer_category/edit' => array(
            array(
                'field' => 'selected[]',
                'label' => '经销商类别编号',
                'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
            ),
            array(
                'field' => 'name',
                'label' => '名称',
                'rules' => 'trim|required|min_length[1]|max_length[32]'
            )
        ),
        'dealer/dealer_claim/edit' => array(
            array(
                'field' => 'selected[]',
                'label' => '需要认领的经销商编号',
                'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
            )
        ),
        'dealer/dealer_delivery/remove' => array(
            array(
                'field' => 'selected[]',
                'label' => '发货信息编号',
                'rules' => 'trim|required|numeric|min_length[1]|max_length[10]'
            )
        ),
        'dealer/dealer_delivery/add' => array(
            array(
                'field' => 'dealer_id',
                'label' => '经销商编号',
                'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
            ),
            array(
                'field' => 'daid',
                'label' => '地址',
                'rules' => 'trim|numeric|max_length[64]'
            ),
            array(
                'field' => 'delivery_address',
                'label' => '街道',
                'rules' => 'trim|max_length[64]'
            ),
            array(
                'field' => 'lid',
                'label' => '要求物流',
                'rules' => 'trim|required|numeric|max_length[4]'
            ),
            array(
                'field' => 'omid',
                'label' => '出厂方式',
                'rules' => 'trim|required|numeric|max_length[2]'
            ),
            array(
                'field' => 'delivery_linker',
                'label' => '联系人',
                'rules' => 'trim|max_length[32]'
            ),
            array(
                'field' => 'delivery_phone',
                'label' => '联系方式',
                'rules' => 'trim|max_length[64]'
            ),
            array(
                'field' => 'default',
                'label' => '类型',
                'rules' => 'trim|required|numeric|max_length[1]'
            )
        ),
        'dealer/dealer_delivery/edit' => array(
            array(
                'field' => 'selected',
                'label' => '联系人编号',
                'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
            ),
            array(
                'field' => 'dealer_id',
                'label' => '经销商编号',
                'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
            ),
            array(
                'field' => 'daid',
                'label' => '地址',
                'rules' => 'trim|numeric|max_length[64]'
            ),
            array(
                'field' => 'delivery_address',
                'label' => '街道',
                'rules' => 'trim|max_length[64]'
            ),
            array(
                'field' => 'lid',
                'label' => '要求物流',
                'rules' => 'trim|required|numeric|max_length[4]'
            ),
            array(
                'field' => 'omid',
                'label' => '出厂方式',
                'rules' => 'trim|required|numeric|max_length[2]'
            ),
            array(
                'field' => 'delivery_linker',
                'label' => '联系人',
                'rules' => 'trim|max_length[32]'
            ),
            array(
                'field' => 'delivery_phone',
                'label' => '联系方式',
                'rules' => 'trim|max_length[64]'
            ),
            array(
                'field' => 'default',
                'label' => '类型',
                'rules' => 'trim|required|numeric|max_length[1]'
            )
        ),
        'dealer/dealer_linker/remove' => array(
            array(
                'field' => 'selected[]',
                'label' => '供应商联系人编号',
                'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
            )
        ),
        'dealer/dealer_linker/add' => array(
            array(
                'field' => 'dealer_id',
                'label' => '经销商编号',
                'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
            ),
            array(
                'field' => 'name',
                'label' => '姓名',
                'rules' => 'trim|required|min_length[1]|max_length[64]'
            ),
            array(
                'field' => 'primary',
                'label' => '首要联系人',
                'rules' => 'trim|required|numeric|max_length[1]'
            ),
            array(
                'field' => 'mobilephone',
                'label' => '移动电话',
                'rules' => 'trim|required|numeric|max_length[16]'
            ),
            array(
                'field' => 'telephone',
                'label' => '固话',
                'rules' => 'trim|max_length[16]|gh_str_replace'
            ),
            array(
                'field' => 'email',
                'label' => '地址',
                'rules' => 'trim|valid_email|max_length[128]'
            ),
            array(
                'field' => 'qq',
                'label' => 'QQ',
                'rules' => 'trim|numeric|max_length[16]'
            ),
            array(
                'field' => 'fax',
                'label' => 'Fax',
                'rules' => 'trim|numeric|max_length[16]|gh_str_replace'
            ),
            array(
                'field' => 'doid',
                'label' => '联系人类型',
                'rules' => 'trim|numeric|max_length[2]'
            ),
            array(
                'field' => 'remark',
                'label' => '备注',
                'rules' => 'trim|max_length[128]'
            )
        ),
        'dealer/dealer_linker/edit' => array(
            array(
                'field' => 'selected[]',
                'label' => '联系人编号',
                'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
            ),
            array(
                'field' => 'name',
                'label' => '姓名',
                'rules' => 'trim|required|min_length[1]|max_length[64]'
            ),
            array(
                'field' => 'mobilephone',
                'label' => '移动电话',
                'rules' => 'trim|numeric|max_length[16]'
            ),
            array(
                'field' => 'telephone',
                'label' => '固话',
                'rules' => 'trim|max_length[64]|gh_str_replace'
            ),
            array(
                'field' => 'email',
                'label' => '地址',
                'rules' => 'trim|max_length[128]'
            ),
            array(
                'field' => 'qq',
                'label' => 'QQ',
                'rules' => 'trim|numeric|max_length[16]'
            ),
            array(
                'field' => 'oid',
                'label' => '联系人类型',
                'rules' => 'trim|numeric|max_length[2]'
            ),
            array(
                'field' => 'remark',
                'label' => '备注',
                'rules' => 'trim|max_length[128]'
            )
        ),
        'dealer/dealer_organization/add' => array(
            array(
                'field' => 'name',
                'label' => '名称',
                'rules' => 'trim|required|min_length[1]|max_length[64]'
            )
        ),
        'dealer/dealer_organization/edit' => array(
            array(
                'field' => 'selected[]',
                'label' => '组织结构编号',
                'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
            ),
            array(
                'field' => 'name',
                'label' => '名称',
                'rules' => 'trim|required|min_length[1]|max_length[64]'
            )
        ),
        'dealer/dealer_owner/add' => array(
            array(
                'field' => 'uid[]',
                'label' => '属主',
                'rules' => 'trim|required|min_length[1]|max_length[10]'
            ),
            array(
                'field' => 'dealer_id',
                'label' => '客户',
                'rules' => 'trim|required|numeric|max_length[10]'
            ),
            array(
                'field' => 'primary',
                'label' => '首要',
                'rules' => 'trim|required|numeric|max_length[1]'
            )
        ),
        'dealer/dealer_owner/edit' => array(
            array(
                'field' => 'selected',
                'label' => '需要认领的经销商编号',
                'rules' => 'trim|required|min_length[1]|max_length[1024]'
            ),
            array(
                'field' => 'user[]',
                'label' => '属主',
                'rules' => 'trim|numeric|max_length[10]'
            )
        ),
        'dealer/dealer_owner/primary' => array(
            array(
                'field' => 'selected',
                'label' => '属主',
                'rules' => 'trim|required|min_length[1]|max_length[10]'
            )
        ),
        'dealer/dealer_owner/general' => array(
            array(
                'field' => 'selected[]',
                'label' => '属主',
                'rules' => 'trim|required|min_length[1]|max_length[10]'
            )
        ),
        'dealer/dealer_owner/remove' => array(
            array(
                'field' => 'selected[]',
                'label' => '属主',
                'rules' => 'trim|required|min_length[1]|max_length[10]'
            )
        ),
        'dealer/dealer_trace/add' => array(
            array(
                'field' => 'dealer_id',
                'label' => '经销商Id',
                'rules' => 'trim|required|numeric|max_length[10]'
            ),
            array(
                'field' => 'trace',
                'label' => '跟踪',
                'rules' => 'trim|required|max_length[65535]'
            )
        ),
        'dealer/my_dealer/add' => array(
            array(
                'field' => 'des',
                'label' => '名称',
                'rules' => 'trim|required|min_length[1]|max_length[64]'
            ),
            array(
                'field' => 'shop',
                'label' => '店名',
                'rules' => 'trim|min_length[1]|max_length[64]'
            ),
            array(
                'field' => 'aid',
                'label' => '地址',
                'rules' => 'trim|numeric|max_length[10]'
            ),
            array(
                'field' => 'dcid',
                'label' => '类型',
                'rules' => 'trim|required|numeric|min_length[1]|max_length[2]'
            ),
            array(
                'field' => 'address',
                'label' => '街道',
                'rules' => 'trim|max_length[64]'
            ),
            array(
                'field' => 'pid',
                'label' => '支付条款',
                'rules' => 'trim|required|is_natural_no_zero|max_length[2]'
            ),
            array(
                'field' => 'remark',
                'label' => '备注',
                'rules' => 'trim|max_length[128]'
            ),
            array(
                'field' => 'delivery_linker',
                'label' => '联系人姓名',
                'rules' => 'trim|required|min_length[1]|max_length[64]'
            ),
            array(
                'field' => 'delivery_phone',
                'label' => '联系人姓名',
                'rules' => 'trim|required|min_length[1]|max_length[64]'
            ),
            array(
                'field' => 'daid',
                'label' => '收货地址',
                'rules' => 'trim|numeric|max_length[10]'
            ),
            array(
                'field' => 'delivery_address',
                'label' => '收货地址详情',
                'rules' => 'trim|max_length[64]'
            ),
            array(
                'field' => 'lid',
                'label' => '要求物流',
                'rules' => 'trim|numeric|max_length[4]'
            ),
            array(
                'field' => 'omid',
                'label' => '出厂方式',
                'rules' => 'trim|numeric|max_length[2]'
            ),
            array(
                'field' => 'name',
                'label' => '联系人姓名',
                'rules' => 'trim|required|min_length[1]|max_length[64]'
            ),
            array(
                'field' => 'mobilephone',
                'label' => '移动电话',
                'rules' => 'trim|required|numeric|max_length[16]'
            ),
            array(
                'field' => 'telephone',
                'label' => '固话',
                'rules' => 'trim|max_length[16]|gh_str_replace'
            ),
            array(
                'field' => 'email',
                'label' => '邮箱',
                'rules' => 'trim|valid_email|max_length[128]'
            ),
            array(
                'field' => 'qq',
                'label' => 'QQ',
                'rules' => 'trim|numeric|max_length[16]'
            ),
            array(
                'field' => 'fax',
                'label' => 'Fax',
                'rules' => 'trim|numeric|max_length[16]|gh_str_replace'
            ),
            array(
                'field' => 'doid',
                'label' => '员工类型',
                'rules' => 'trim|numeric|max_length[2]'
            )
        ),
        'dealer/my_dealer/edit' => array(
            array(
                'field' => 'selected',
                'label' => '经销商编号',
                'rules' => 'trim|required|numeric|min_length[1]|max_length[10]'
            ),
            array(
                'field' => 'des',
                'label' => '名称',
                'rules' => 'trim|required|min_length[1]|max_length[64]'
            ),
            array(
                'field' => 'shop',
                'label' => '店名',
                'rules' => 'trim|min_length[1]|max_length[64]'
            ),
            array(
                'field' => 'aid',
                'label' => '地址',
                'rules' => 'trim|numeric|max_length[64]'
            ),
            array(
                'field' => 'address',
                'label' => '街道',
                'rules' => 'trim|max_length[64]'
            ),
            array(
                'field' => 'pid',
                'label' => '支付条款',
                'rules' => 'trim|required|is_natural_no_zero|max_length[2]'
            ),
        
            array(
                'field' => 'dcid',
                'label' => '类型',
                'rules' => 'trim|required|numeric|min_length[1]|max_length[2]'
            ),
            array(
                'field' => 'remark',
                'label' => '备注',
                'rules' => 'trim|max_length[128]'
            )
        ),
        'dealer/payterms/add' => array(
            array(
                'field' => 'name',
                'label' => '支付条款名称',
                'rules' => 'trim|required|min_length[1]|max_length[64]'
            )
        ),
        'dealer/payterms/edit' => array(
            array(
                'field' => 'selected',
                'label' => 'selected',
                'rules' => 'required|numeric|min_length[1]|max_length[2]'
            ),
            array(
                'field' => 'name',
                'label' => '支付条款名称',
                'rules' => 'trim|required|min_length[1]|max_length[64]'
            )
        ),
        'drawing/drawing/remove' => array(
            array(
                'field' => 'selected[]',
                'label' => '图纸编号',
                'rules' => 'trim|required|numeric|min_length[1]|max_length[10]'
            )
        ),
        'finance/account/add' => array(
            array(
                'field' => 'name',
                'label' => '财务账户名称',
                'rules' => 'trim|required|max_length[64]'
            ),
            array(
                'field' => 'account',
                'label' => '财务账号',
                'rules' => 'trim|max_length[64]'
            ),
            array(
                'field' => 'host',
                'label' => '户主',
                'rules' => 'trim|max_length[64]'
            ),
            array(
                'field' => 'fee',
                'label' => '费率',
                'rules' => 'trim|decimal|max_length[10]'
            ),
            array(
                'field' => 'fee_max',
                'label' => '最高手续费',
                'rules' => 'trim|decimal|max_length[10]'
            ),
            array(
                'field' => 'in',
                'label' => '收入',
                'rules' => 'trim|floatval|decimal|max_length[10]'
            ),
            array(
                'field' => 'in_fee',
                'label' => '收入手续费',
                'rules' => 'trim|floatval|decimal|max_length[10]'
            ),
            array(
                'field' => 'out',
                'label' => '支出',
                'rules' => 'trim|floatval|decimal|max_length[10]'
            ),
            array(
                'field' => 'out_fee',
                'label' => '支出手续费',
                'rules' => 'trim|floatval|decimal|max_length[10]'
            ),
            array(
                'field' => 'intime',
                'label' => '及时到账',
                'rules' => 'trim|numeric|max_length[1]'
            )
        ),
        'finance/account/edit' => array(
            array(
                'field' => 'selected',
                'label' => '财务账户编号',
                'rules' => 'trim|required|numeric|max_length[4]'
            ),
            array(
                'field' => 'name',
                'label' => '财务账户名称',
                'rules' => 'trim|required|max_length[64]'
            ),
            array(
                'field' => 'account',
                'label' => '财务账号',
                'rules' => 'trim|max_length[64]'
            ),
            array(
                'field' => 'host',
                'label' => '户主',
                'rules' => 'trim|max_length[64]'
            ),
            array(
                'field' => 'fee',
                'label' => '费率',
                'rules' => 'trim|decimal|max_length[10]'
            ),
            array(
                'field' => 'fee_max',
                'label' => '最高手续费',
                'rules' => 'trim|decimal|max_length[10]'
            ),
            array(
                'field' => 'in',
                'label' => '收入',
                'rules' => 'trim|floatval|decimal|max_length[10]'
            ),
            array(
                'field' => 'in_fee',
                'label' => '收入手续费',
                'rules' => 'trim|floatval|decimal|max_length[10]'
            ),
            array(
                'field' => 'out',
                'label' => '支出',
                'rules' => 'trim|floatval|decimal|max_length[10]'
            ),
            array(
                'field' => 'out_fee',
                'label' => '支出手续费',
                'rules' => 'trim|floatval|decimal|max_length[10]'
            ),
            array(
                'field' => 'intime',
                'label' => '及时到账',
                'rules' => 'trim|numeric|max_length[1]'
            )
        ),
        'finance/account/remove' => array(
            array(
                'field' => 'selected[]',
                'label' => '财务账户编号',
                'rules' => 'trim|required|numeric|max_length[4]'
            )
        ),
        'finance/finance_back/edit' => array(
            array(
                'field' => 'selected',
                'label' => '进账编号',
                'rules' => 'trim|required|max_length[1024]'
            ),
            array(
                'field' => 'faid',
                'label' => '账号',
                'rules' => 'trim|required|numeric|max_length[4]'
            )
        ),
        'finance/finance_pay/add' => array(
            array(
                'field' => 'faid',
                'label' => '账号',
                'rules' => 'trim|required|max_length[4]'
            ),
            array(
                'field' => 'in_faid',
                'label' => '转入账号',
                'rules' => 'trim|numeric|max_length[4]'
            ),
            array(
                'field' => 'type',
                'label' => '支出类型',
                'rules' => 'trim|required|max_length[128]'
            ),
            array(
                'field' => 'amount',
                'label' => '支出金额',
                'rules' => 'trim|required|decimal|greater_than[0]|max_length[10]'
            ),
            array(
                'field' => 'fee',
                'label' => '支出手续费',
                'rules' => 'trim|decimal|decimal|greater_than_equal_to[0]|max_length[10]'
            ),
            array(
                'field' => 'bank_date',
                'label' => '到账日期',
                'rules' => 'trim|max_length[128]'
            ),
            array(
                'field' => 'supplier_id',
                'label' => '供应商编号',
                'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
            ),
            array(
                'field' => 'supplier',
                'label' => '供应商名称',
                'rules' => 'trim|min_length[1]|max_length[64]'
            ),
            array(
                'field' => 'remark',
                'label' => '备注',
                'rules' => 'trim|max_length[512]'
            )
        ),
        'finance/finance_pay/edit' => array(
            array(
                'field' => 'selected',
                'label' => '进账编号',
                'rules' => 'trim|required|numeric|max_length[10]'
            ),
            array(
                'field' => 'faid',
                'label' => '账号',
                'rules' => 'trim|required|max_length[4]'
            ),
            array(
                'field' => 'in_faid',
                'label' => '转入账号',
                'rules' => 'trim|numeric|max_length[4]'
            ),
            array(
                'field' => 'type',
                'label' => '支出类型',
                'rules' => 'trim|required|max_length[128]'
            ),
            array(
                'field' => 'amount',
                'label' => '进账金额',
                'rules' => 'trim|required|decimal|greater_than[0]|max_length[10]'
            ),
            array(
                'field' => 'amount',
                'label' => '进账手续费',
                'rules' => 'trim|decimal|greater_than_equal_to[0]|max_length[10]'
            ),
            array(
                'field' => 'bank_date',
                'label' => '到账日期',
                'rules' => 'trim|max_length[128]'
            ),
            array(
                'field' => 'supplier_id',
                'label' => '供应商编号',
                'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
            ),
            array(
                'field' => 'supplier',
                'label' => '供应商名称',
                'rules' => 'trim|min_length[1]|max_length[64]'
            ),
            array(
                'field' => 'remark',
                'label' => '备注',
                'rules' => 'trim|max_length[512]'
            )
        ),
        'finance/finance_pay/remove' => array(
            array(
                'field' => 'selected[]',
                'label' => '进账编号',
                'rules' => 'trim|required|numeric|max_length[10]'
            )
        ),
        'finance/finance_received/add' => array(
            array(
                'field' => 'faid',
                'label' => '账号',
                'rules' => 'trim|required|max_length[64]'
            ),
            array(
                'field' => 'amount',
                'label' => '进账金额',
                'rules' => 'trim|required|decimal|greater_than_equal_to[0]|max_length[10]'
            ),
            array(
                'field' => 'fee',
                'label' => '进账手续费',
                'rules' => 'trim|decimal|decimal|greater_than_equal_to[0]|max_length[10]'
            ),
            array(
                'field' => 'bank_date',
                'label' => '到账日期',
                'rules' => 'trim|max_length[128]'
            ),
            array(
                'field' => 'cargo_no',
                'label' => '货号',
                'rules' => 'trim|max_length[128]'
            ),
            array(
                'field' => 'remark',
                'label' => '备注',
                'rules' => 'trim|max_length[512]'
            )
        ),
        'finance/finance_received/edit' => array(
            array(
                'field' => 'selected',
                'label' => '进账编号',
                'rules' => 'trim|required|numeric|max_length[10]'
            ),
            array(
                'field' => 'faid',
                'label' => '账号',
                'rules' => 'trim|required|max_length[64]'
            ),
            array(
                'field' => 'amount',
                'label' => '进账金额',
                'rules' => 'trim|required|decimal|greater_than_equal_to[0]|max_length[10]'
            ),
            array(
                'field' => 'amount',
                'label' => '进账手续费',
                'rules' => 'trim|decimal|greater_than_equal_to[0]|max_length[10]'
            ),
            array(
                'field' => 'bank_date',
                'label' => '到账日期',
                'rules' => 'trim|max_length[128]'
            ),
            array(
                'field' => 'cargo_no',
                'label' => '货号',
                'rules' => 'trim|max_length[128]'
            ),
            array(
                'field' => 'remark',
                'label' => '备注',
                'rules' => 'trim|max_length[512]'
            )
        ),
        'finance/finance_received/remove' => array(
            array(
                'field' => 'selected[]',
                'label' => '进账编号',
                'rules' => 'trim|required|numeric|max_length[10]'
            )
        ),
        'finance/finance_received_outtime/add' => array(
            array(
                'field' => 'faid',
                'label' => '账号',
                'rules' => 'trim|required|max_length[64]'
            ),
            array(
                'field' => 'amount',
                'label' => '进账金额',
                'rules' => 'trim|required|decimal|greater_than_equal_to[0]|max_length[10]'
            ),
            array(
                'field' => 'fee',
                'label' => '进账手续费',
                'rules' => 'trim|floatval|decimal|greater_than_equal_to[0]|max_length[10]'
            ),
            array(
                'field' => 'type',
                'label' => '进账类型',
                'rules' => 'trim|required|max_length[64]'
            ),
            array(
                'field' => 'did',
                'label' => '经销商编号',
                'rules' => 'trim|intval|max_length[10]'
            ),
            array(
                'field' => 'dealer',
                'label' => '经销商',
                'rules' => 'trim|max_length[512]'
            ),
            array(
                'field' => 'order_num[]',
                'label' => '对应订单',
                'rules' => 'trim|max_length[64]'
            ),
            array(
                'field' => 'cargo_no',
                'label' => '货号',
                'rules' => 'trim|max_length[128]'
            ),
            array(
                'field' => 'corresponding',
                'label' => '对应货款',
                'rules' => 'trim|floatval|decimal|greater_than_equal_to[0]|max_length[10]'
            ),
            array(
                'field' => 'remark',
                'label' => '备注',
                'rules' => 'trim|max_length[512]'
            )
        ),
        'finance/finance_received_outtime/edit' => array(
            array(
                'field' => 'selected',
                'label' => '进账编号',
                'rules' => 'trim|required|numeric|max_length[10]'
            ),
            array(
                'field' => 'fee',
                'label' => '进账手续费',
                'rules' => 'trim|floatval|decimal|greater_than_equal_to[0]|max_length[10]'
            )
        ),
        'finance/finance_received_outtime/remove' => array(
            array(
                'field' => 'selected[]',
                'label' => '进账编号',
                'rules' => 'trim|required|numeric|max_length[10]'
            )
        ),
        'finance/finance_received_pointer/add' => array(
            array(
                'field' => 'frid',
                'label' => '进账编号',
                'rules' => 'trim|required|numeric|max_length[10]'
            ),
            array(
                'field' => 'type',
                'label' => '进账类型',
                'rules' => 'trim|required|max_length[64]'
            ),
            array(
                'field' => 'did',
                'label' => '经销商编号',
                'rules' => 'trim|intval|max_length[10]'
            ),
            array(
                'field' => 'dealer',
                'label' => '经销商',
                'rules' => 'trim|required|max_length[512]'
            ),
            array(
                'field' => 'order_num[]',
                'label' => '对应订单',
                'rules' => 'trim|max_length[64]'
            ),
            array(
                'field' => 'amount',
                'label' => '账户到账',
                'rules' => 'trim|decimal|greater_than_equal_to[0]|max_length[10]'
            ),
            array(
                'field' => 'corresponding',
                'label' => '对应货款',
                'rules' => 'trim|decimal|greater_than_equal_to[0]|max_length[10]'
            ),
            array(
                'field' => 'remark',
                'label' => '备注',
                'rules' => 'trim|max_length[512]'
            )
        ),
        'finance/income_pay/add' => array(
            array(
                'field' => 'type',
                'label' => '类型',
                'rules' => 'trim|required|max_length[16]'
            ),
            array(
                'field' => 'name',
                'label' => '名称',
                'rules' => 'trim|max_length[64]'
            )
        ),
        'finance/income_pay/edit' => array(
            array(
                'field' => 'selected',
                'label' => '收支类型编号',
                'rules' => 'trim|required|numeric|max_length[4]'
            ),
            array(
                'field' => 'type',
                'label' => '类型',
                'rules' => 'trim|required|max_length[16]'
            ),
            array(
                'field' => 'name',
                'label' => '财务账户名称',
                'rules' => 'trim|required|max_length[64]'
            )
        ),
        'manage/operation/add' => array(
            array(
                'field' => 'name',
                'label' => '名称',
                'rules' => 'trim|required|min_length[1]|max_length[128]'
            ),
            array(
                'field' => 'parent',
                'label' => '父级',
                'rules' => 'trim|required|numeric|min_length[1]|max_length[2]'
            ),
            array(
                'field' => 'url',
                'label' => 'URL',
                'rules' => 'trim|min_length[1]|max_length[128]'
            )
        ),
        'manage/operation/edit' => array(
            array(
                'field' => 'selected',
                'label' => '操作编号',
                'rules' => 'trim|required|numeric|min_length[1]|max_length[2]'
            ),
            array(
                'field' => 'name',
                'label' => '名称',
                'rules' => 'trim|required|min_length[1]|max_length[128]'
            ),
            array(
                'field' => 'parent',
                'label' => '父级',
                'rules' => 'trim|required|numeric|min_length[1]|max_length[2]'
            ),
            array(
                'field' => 'url',
                'label' => 'URI',
                'rules' => 'trim|min_length[1]|max_length[128]'
            )
        ),
        'manage/operation/remove' => array(
            array(
                'field' => 'selected[]',
                'label' => '操作编号',
                'rules' => 'trim|required|numeric|min_length[1]|max_length[2]'
            )
        ),
		'manage/myself/edit' => array(
				array(
						'field' => 'selected',
						'label' => '编号',
						'rules' => 'required|numeric|min_length[1]|max_length[10]'
				),
				array(
						'field' => 'username',
						'label' => '用户名',
						'rules' => 'trim|required|min_length[1]|max_length[64]'
				),
				array(
						'field' => 'truename',
						'label' => '真实姓名',
						'rules' => 'trim|required|min_length[1]|max_length[64]'
				),
				array(
						'field' => 'mobilephone',
						'label' => '移动电话',
						'rules' => 'trim|numeric|min_length[1]|max_length[16]'
				),
				array(
						'field' => 'password',
						'label' => '密码',
						'rules' => 'trim|min_length[6]|max_length[64]'
				)
		),

		'manage/user/add' => array(
				array(
						'field' => 'name',
						'label' => '用户名',
						'rules' => 'trim|required|min_length[1]|max_length[64]'
				),
				array(
						'field' => 'truename',
						'label' => '真实姓名',
						'rules' => 'trim|required|min_length[1]|max_length[64]'
				),
				array(
						'field' => 'password',
						'label' => '密码',
						'rules' => 'trim|required|min_length[1]|max_length[64]'
				),
				array(
						'field' => 'mobilephone',
						'label' => '移动电话',
						'rules' => 'trim|numeric|min_length[0]|max_length[16]'
				),
		        array(
    		            'field' => 'ugid',
    		            'label' => '用户组',
    		            'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
		        )
		
		),
		'manage/user/edit' => array(
				array(
						'field' => 'selected',
						'label' => '用户编号',
						'rules' => 'trim|required|numeric|min_length[1]|max_length[10]'
				),
				array(
						'field' => 'name',
						'label' => '用户名',
						'rules' => 'trim|required|min_length[1]|max_length[64]'
				),
				array(
						'field' => 'truename',
						'label' => '真实姓名',
						'rules' => 'trim|required|min_length[1]|max_length[64]'
				),
				array(
						'field' => 'password',
						'label' => '密码',
						'rules' => 'trim|max_length[16]'
				),
				array(
						'field' => 'mobilephone',
						'label' => '移动电话',
						'rules' => 'trim|numeric|min_length[0]|max_length[16]'
				),
		        array(
    		            'field' => 'ugid',
    		            'label' => '用户组',
    		            'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
		        )
				
		),
		'manage/user/remove' =>array(
				array(
						'field' => 'selected[]',
						'label' => '用户编号',
						'rules' => 'trim|required|numeric|min_length[1]|max_length[10]'
				)
		),
        'manage/usergroup_menu/edit' => array(
            array(
                'field' => 'ugid',
                'label' => '用户组编号',
                'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
            ),
            array(
                'field' => 'pid[]',
                'label' => '用户组菜单权限编号',
                'rules' => 'trim|required|numeric|min_length[1]|max_length[10]'
            )
        ),
        'order/cargo_no/edit' => array(
            array(
                'field' => 'cargo_no[]',
                'label' => '货号',
                'rules' => 'trim|required|min_length[1]|max_length[64]'
            )
        ),
        'order/cnc/edit' => array(
            array(
                'field' => 'selected[]',
                'label' => '确认CNC的订单',
                'rules' => 'required|numeric|min_length[1]|max_length[10]'
            )
        ),
        'order/deliveried/redelivery' => array(
                array(
                    'field' => 'selected[]',
                    'label' => '重新发货的订单',
                    'rules' => 'required|numeric|min_length[1]|max_length[10]'
                )
        ),
        'order/edge/edit' => array(
            array(
                'field' => 'selected[]',
                'label' => '确认封边的订单',
                'rules' => 'required|numeric|min_length[1]|max_length[10]'
            )
        ),
        'order/electric_saw/edit' => array(
            array(
                'field' => 'selected[]',
                'label' => '确认下料批次号',
                'rules' => 'required|numeric|min_length[1]|max_length[10]'
            )
        ),
        'order/money_logistics/redelivery' => array(
                array(
                    'field' => 'selected[]',
                    'label' => '物流代收中重新发货的订单',
                    'rules' => 'required|numeric|min_length[1]|max_length[10]'
                )
        ),
        'order/money_month/redelivery' => array(
            array(
                'field' => 'selected[]',
                'label' => '按月结款中重新发货的订单',
                'rules' => 'required|numeric|min_length[1]|max_length[10]'
            )
        ),
        'order/money_factory/redelivery' => array(
            array(
                'field' => 'selected[]',
                'label' => '到厂付款中重新发货的订单',
                'rules' => 'required|numeric|min_length[1]|max_length[10]'
            )
        ),
        'order/order/add' => array(
            array(
                'field' => 'otid',
                'label' => '订单类型',
                'rules' => 'trim|required|min_length[1]|max_length[2]'
            ),
            array(
                'field' => 'did',
                'label' => '经销商编号',
                'rules' => 'trim|required|numeric|min_length[1]|max_length[10]'
            ),
            array(
                'field' => 'dealer',
                'label' => '经销商',
                'rules' => 'trim|required|min_length[1]|max_length[512]'
            ),
            array(
                'field' => 'owner',
                'label' => '业主',
                'rules' => 'trim|max_length[128]'
            ),
            array(
                'field' => 'checker',
                'label' => '对单人',
                'rules' => 'trim|max_length[32]'
            ),
            array(
                'field' => 'checker_phone',
                'label' => '对单电话',
                'rules' => 'trim|max_length[16]'
            ),
            array(
                'field' => 'payer',
                'label' => '支付人',
                'rules' => 'trim|max_length[32]'
            ),
            array(
                'field' => 'payer_phone',
                'label' => '支付电话',
                'rules' => 'trim|max_length[16]'
            ),
            array(
                'field' => 'payterms',
                'label' => '支付条款',
                'rules' => 'trim|max_length[64]'
            ),
            array(
                'field' => 'logistics',
                'label' => '要求物流',
                'rules' => 'trim|max_length[64]'
            ),
            array(
                'field' => 'delivery_area',
                'label' => '收货地区',
                'rules' => 'trim|max_length[64]'
            ),
            array(
                'field' => 'delivery_address',
                'label' => '收货具体地址',
                'rules' => 'trim|max_length[64]'
            ),
            array(
                'field' => 'delivery_linker',
                'label' => '收货人',
                'rules' => 'trim|max_length[16]'
            ),
            array(
                'field' => 'delivery_phone',
                'label' => '收货人联系方式',
                'rules' => 'trim|max_length[16]'
            ),
            array(
                'field' => 'remark',
                'label' => '备注',
                'rules' => 'trim|max_length[1024]'
            ),
            array(
                'field' => 'dealer_remark',
                'label' => '客户备注',
                'rules' => 'trim|max_length[1024]'
            ),
            array(
                'field' => 'pid[]',
                'label' => '产品',
                'rules' => 'trim|numeric|max_length[4]'
            ),
            array(
                'field' => 'request_outdate',
                'label' => '要求出厂日期',
                'rules' => 'trim|max_length[64]'
            ),
            array(
                'field' => 'out_method',
                'label' => '出厂方式',
                'rules' => 'trim|required|max_length[64]'
            )
        ),
        'order/order/edit' => array(
            array(
                'field' => 'selected',
                'label' => '订单ID',
                'rules' => 'trim|intval|required|numeric|min_length[1]|max_length[10]'
            ),
            array(
                'field' => 'owner',
                'label' => '业主',
                'rules' => 'trim|max_length[128]'
            ),
            array(
                'field' => 'flag',
                'label' => '任务等级',
                'rules' => 'trim|numeric|min_length[1]|max_length[2]'
            ),
            array(
                'field' => 'remark',
                'label' => '备注',
                'rules' => 'trim|max_length[512]'
            ),
            array(
                'field' => 'dealer_remark',
                'label' => '客户备注',
                'rules' => 'trim|max_length[1024]'
            ),
            array(
                'field' => 'request_outdate',
                'label' => '要求出厂日期',
                'rules' => 'trim|max_length[64]'
            ),
            array(
                'field' => 'checker',
                'label' => '对单人',
                'rules' => 'trim|max_length[32]'
            ),
            array(
                'field' => 'checker_phone',
                'label' => '对单电话',
                'rules' => 'trim|max_length[16]'
            ),
            array(
                'field' => 'payer',
                'label' => '支付人',
                'rules' => 'trim|max_length[32]'
            ),
            array(
                'field' => 'payer_phone',
                'label' => '支付电话',
                'rules' => 'trim|max_length[16]'
            ),
            array(
                'field' => 'payterms',
                'label' => '支付条款',
                'rules' => 'trim|max_length[64]'
            ),
            array(
                'field' => 'delivery_area',
                'label' => '收货地区',
                'rules' => 'trim|max_length[64]'
            ),
            array(
                'field' => 'delivery_address',
                'label' => '收货具体地址',
                'rules' => 'trim|max_length[64]'
            ),
            array(
                'field' => 'delivery_linker',
                'label' => '收货人',
                'rules' => 'trim|max_length[16]'
            ),
            array(
                'field' => 'delivery_phone',
                'label' => '收货人联系方式',
                'rules' => 'trim|max_length[16]'
            ),
            array(
                'field' => 'logistics',
                'label' => '要求物流',
                'rules' => 'trim|max_length[64]'
            ),
            array(
                'field' => 'out_method',
                'label' => '出厂方式',
                'rules' => 'trim|max_length[64]'
            )
        ),
        'order/order/remove' => array(
            array(
                'field' => 'selected[]',
                'label' => '订单编号',
                'rules' => 'required|numeric|min_length[1]|max_length[10]'
            )
        ),
        'order/producing/edit' => array(
            array(
                'field' => 'selected[]',
                'label' => '订单编号',
                'rules' => 'trim|required|numeric|min_length[1]|max_length[10]'
            )
        ),
        'order/produce_prehandle/edit' => array(
            array(
                'field' => 'selected[]',
                'label' => '订单编号',
                'rules' => 'trim|required|numeric|min_length[1]|max_length[10]'
            )
        ),
        'order/scan/edit' => array(
            array(
                'field' => 'selected[]',
                'label' => '已扫描项',
                'rules' => 'trim|required|numeric|min_length[1]|max_length[10]'
            )
        ),
        'order/table_saw/edit' => array(
            array(
                'field' => 'selected[]',
                'label' => '确认推台锯下料的订单',
                'rules' => 'required|numeric|min_length[1]|max_length[10]'
            )
        ),
        'order/wait_check/edit_asure' => array(
            array(
                'field' => 'selected[]',
                'label' => '订单编号',
                'rules' => 'trim|required|numeric|min_length[1]|max_length[10]'
            )
        ),
        'order/wait_dismantle/edit' => array(
            array(
                'field' => 'selected[]',
                'label' => '订单编号',
                'rules' => 'trim|required|numeric|min_length[1]|max_length[10]'
            )
        ),
        'order/wait_dismantle/remove' => array(
            array(
                'field' => 'selected[]',
                'label' => '订单编号',
                'rules' => 'trim|required|numeric|min_length[1]|max_length[10]'
            )
        ),
        'order/wait_quote/edit' => array(
            array(
                'field' => 'selected[]',
                'label' => '订单编号',
                'rules' => 'trim|required|numeric|min_length[1]|max_length[10]'
            )
        ),
        'order/wait_asure/edit' => array(
            array(
                'field' => 'selected',
                'label' => '订单编号',
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'request_outdate',
                'label' => '要求出厂日期',
                'rules' => 'trim|required'
            )
        ),
        'position/position/add' => array(
            array(
                'field' => 'name',
                'label' => '库位名称',
                'rules' => 'trim|required|max_length[8]'
            )
        ),
        'position/position/edit' => array(
            array(
                'field' => 'selected',
                'label' => '订单编号',
                'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
            ),
            array(
                'field' => 'name',
                'label' => '库位名称',
                'rules' => 'trim|required|max_length[8]'
            ),
            array(
                'field' => 'status',
                'label' => '库位状态',
                'rules' => 'trim|required|numeric|max_length[1]'
            )
        ),
        'position/position/remove' => array(
            array(
                'field' => 'selected[]',
                'label' => '选择项',
                'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
            )
        ),
        'position/position_order_product/add' => array(
            array(
                'field' => 'selected',
                'label' => '库位号',
                'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
            ),
            array(
                'field' => 'opid',
                'label' => '订单产品编号',
                'rules' => 'trim|required|numeric|min_length[1]|max_length[10]'
            ),
            array(
                'field' => 'new_order_product_num',
                'label' => '新入库订单编号',
                'rules' => 'trim|required|max_length[128]'
            ),
            array(
                'field' => 'count',
                'label' => '入库件数',
                'rules' => 'trim|required|numeric|greater_than[0]|max_length[128]'
            ),
            array(
                'field' => 'status',
                'label' => '库位状态',
                'rules' => 'trim|required|numeric|greater_than[0]|max_length[1]'
            )
        ),
        'position/position_order_product/edit' => array(
            array(
                'field' => 'selected',
                'label' => '库位号',
                'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
            ),
            array(
                'field' => 'count',
                'label' => '入库件数',
                'rules' => 'trim|required|numeric|greater_than[0]|min_length[1]|max_length[4]'
            ),
            array(
                'field' => 'status',
                'label' => '订单在库位状态',
                'rules' => 'trim|required|numeric|max_length[1]'
            )
        ),
        'position/position_order_product/edit_out' => array(
            array(
                'field' => 'selected[]',
                'label' => '库位号',
                'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
            )
        ),
        'position/position_order_product/remove' => array(
            array(
                'field' => 'selected[]',
                'label' => '库位号',
                'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
            )
        ),
        'permission/card/add' => array(
            array(
                'field' => 'name',
                'label' => '名称',
                'rules' => 'trim|required|min_length[1]|max_length[64]'
            ),
            array(
                'field' => 'url',
                'label' => 'Url',
                'rules' => 'trim|max_length[128]'
            ),
            array(
                'field' => 'card_type',
                'label' => '卡片类型',
                'rules' => 'trim|required|min_length[1]|max_length[32]'
            ),
            array(
                'field' => 'card_setting',
                'label' => '设置方式',
                'rules' => 'trim|max_length[32]'
            )
        ),
        'permission/card/edit' => array(
            array(
                'field' => 'selected',
                'label' => '卡片编号',
                'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
            ),
            array(
                'field' => 'name',
                'label' => '名称',
                'rules' => 'trim|required|min_length[1]|max_length[64]'
            ),
            array(
                'field' => 'url',
                'label' => 'Url',
                'rules' => 'trim|max_length[128]'
            ),
            array(
                'field' => 'card_type',
                'label' => '卡片类型',
                'rules' => 'trim|required|min_length[1]|max_length[32]'
            ),
            array(
                'field' => 'card_setting',
                'label' => '设置方式',
                'rules' => 'trim|max_length[32]'
            )
        ),
        'permission/card/remove' => array(
            array(
                'field' => 'selected[]',
                'label' => '卡片编号',
                'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
            )
        ),
        'permission/element/add' => array(
            array(
                'field' => 'name',
                'label' => '名称',
                'rules' => 'trim|required|min_length[1]|max_length[64]'
            ),
            array(
                'field' => 'label',
                'label' => '标签',
                'rules' => 'trim|required|min_length[1]|max_length[64]'
            ),
            array(
                'field' => 'classes',
                'label' => '样式',
                'rules' => 'trim|max_length[64]'
            ),
            array(
                'field' => 'displayorder',
                'label' => '显示顺序',
                'rules' => 'trim|numeric|max_length[1]'
            ),
            array(
                'field' => 'checked',
                'label' => '默认',
                'rules' => 'trim|numeric|max_length[1]'
            )
        ),
        'permission/element/edit' => array(
            array(
                'field' => 'selected',
                'label' => '卡片编号',
                'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
            ),
            array(
                'field' => 'name',
                'label' => '名称',
                'rules' => 'trim|required|min_length[1]|max_length[64]'
            ),
            array(
                'field' => 'label',
                'label' => '标签',
                'rules' => 'trim|required|min_length[1]|max_length[64]'
            ),
            array(
                'field' => 'classes',
                'label' => '样式',
                'rules' => 'trim|max_length[64]'
            ),
            array(
                'field' => 'displayorder',
                'label' => '显示顺序',
                'rules' => 'trim|numeric|max_length[1]'
            ),
            array(
                'field' => 'checked',
                'label' => '默认',
                'rules' => 'trim|numeric|max_length[1]'
            )
        ),
        'permission/element/remove' => array(
            array(
                'field' => 'selected[]',
                'label' => '卡片编号',
                'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
            )
        ),
        'permission/form/add' => array(
            array(
                'field' => 'name',
                'label' => '名称',
                'rules' => 'trim|required|min_length[1]|max_length[64]'
            ),
            array(
                'field' => 'label',
                'label' => 'Label',
                'rules' => 'trim|min_length[1]|max_length[64]'
            ),
            array(
                'field' => 'type',
                'label' => '类型',
                'rules' => 'trim|min_length[1]|max_length[32]'
            ),
            array(
                'field' => 'url',
                'label' => 'Url',
                'rules' => 'trim|min_length[1]|max_length[128]'
            ),
            array(
                'field' => 'func_id',
                'label' => '功能',
                'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
            )
        ),
        'permission/form/edit' => array(
            array(
                'field' => 'selected',
                'label' => '表单编号',
                'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
            ),
            array(
                'field' => 'name',
                'label' => '名称',
                'rules' => 'trim|required|min_length[1]|max_length[64]'
            ),
            array(
                'field' => 'label',
                'label' => 'Label',
                'rules' => 'trim|min_length[1]|max_length[64]'
            ),
            array(
                'field' => 'type',
                'label' => '类型',
                'rules' => 'trim|min_length[1]|max_length[32]'
            ),
            array(
                'field' => 'url',
                'label' => 'Url',
                'rules' => 'trim|min_length[1]|max_length[128]'
            ),
            array(
                'field' => 'func_id',
                'label' => '功能',
                'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
            )
        ),
        'permission/form/remove' => array(
            array(
                'field' => 'selected[]',
                'label' => '表单编号',
                'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
            )
        ),
        'permission/func/add' => array(
            array(
                'field' => 'name',
                'label' => '名称',
                'rules' => 'trim|required|min_length[1]|max_length[64]'
            ),
            array(
                'field' => 'mid',
                'label' => '菜单',
                'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
            ),
            array(
                'field' => 'url',
                'label' => 'URL',
                'rules' => 'trim|min_length[1]|max_length[256]'
            ),
            array(
                'field' => 'displayorder',
                'label' => '显示顺序',
                'rules' => 'trim|numeric|min_length[0]|max_length[2]'
            ),
            array(
                'field' => 'img',
                'label' => '图像',
                'rules' => 'trim|min_length[0]|max_length[128]'
            ),
            array(
                'field' => 'group_no',
                'label' => '组号',
                'rules' => 'trim|max_length[32]'
            ),
            array(
                'field' => 'toggle',
                'label' => 'Toggle',
                'rules' => 'trim|max_length[32]'
            ),
            array(
                'field' => 'target',
                'label' => 'Target',
                'rules' => 'trim|max_length[32]'
            ),
            array(
                'field' => 'tag',
                'label' => 'Tag',
                'rules' => 'trim|max_length[32]'
            ),
            array(
                'field' => 'multiple',
                'label' => '多选',
                'rules' => 'trim|numeric|max_length[1]'
            )
        ),
        'permission/func/edit' => array(
            array(
                'field' => 'selected',
                'label' => '功能编号',
                'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
            ),
            array(
                'field' => 'name',
                'label' => '名称',
                'rules' => 'trim|required|min_length[1]|max_length[64]'
            ),
            array(
                'field' => 'mid',
                'label' => '菜单',
                'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
            ),
            array(
                'field' => 'url',
                'label' => 'URL',
                'rules' => 'trim|min_length[1]|max_length[256]'
            ),
            array(
                'field' => 'displayorder',
                'label' => '显示顺序',
                'rules' => 'trim|numeric|min_length[0]|max_length[2]'
            ),
            array(
                'field' => 'img',
                'label' => '图像',
                'rules' => 'trim|min_length[0]|max_length[128]'
            ),
            array(
                'field' => 'group_no',
                'label' => '组号',
                'rules' => 'trim|max_length[32]'
            ),
            array(
                'field' => 'toggle',
                'label' => 'Toggle',
                'rules' => 'trim|max_length[32]'
            ),
            array(
                'field' => 'target',
                'label' => 'Target',
                'rules' => 'trim|max_length[32]'
            ),
            array(
                'field' => 'tag',
                'label' => 'Tag',
                'rules' => 'trim|max_length[32]'
            ),
            array(
                'field' => 'multiple',
                'label' => '多选',
                'rules' => 'trim|numeric|max_length[1]'
            )
        ),
        'permission/func/remove' => array(
            array(
                'field' => 'selected[]',
                'label' => '功能编号',
                'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
            )
        ),
        'permission/menu/add' => array(
            array(
                'field' => 'name',
                'label' => '名称',
                'rules' => 'trim|required|min_length[1]|max_length[128]'
            ),
            array(
                'field' => 'label',
                'label' => 'Label',
                'rules' => 'trim|min_length[1]|max_length[64]'
            ),
            array(
                'field' => 'parent',
                'label' => '父级',
                'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
            ),
            array(
                'field' => 'url',
                'label' => 'URL',
                'rules' => 'trim|min_length[1]|max_length[128]'
            ),
            array(
                'field' => 'displayorder',
                'label' => '显示顺序',
                'rules' => 'trim|numeric|min_length[0]|max_length[2]'
            ),
            array(
                'field' => 'img',
                'label' => '图像',
                'rules' => 'trim|min_length[0]|max_length[128]'
            ),
            array(
                'field' => 'type',
                'label' => '类型',
                'rules' => 'trim|min_length[0]|max_length[32]'
            ),
            array(
                'field' => 'mobile',
                'label' => '移动端显示',
                'rules' => 'trim|numeric|min_length[0]|max_length[1]'
            )
        ),
        'permission/menu/edit' => array(
            array(
                'field' => 'selected',
                'label' => '菜单编号',
                'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
            ),
            array(
                'field' => 'name',
                'label' => '名称',
                'rules' => 'trim|required|min_length[1]|max_length[128]'
            ),
            array(
                'field' => 'label',
                'label' => 'Label',
                'rules' => 'trim|min_length[1]|max_length[64]'
            ),
            array(
                'field' => 'parent',
                'label' => '父级',
                'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
            ),
            array(
                'field' => 'url',
                'label' => 'URI',
                'rules' => 'trim|min_length[1]|max_length[128]'
            ),
            array(
                'field' => 'displayorder',
                'label' => '显示顺序',
                'rules' => 'trim|numeric|min_length[0]|max_length[2]'
            ),
            array(
                'field' => 'img',
                'label' => '图像',
                'rules' => 'trim|min_length[0]|max_length[128]|gh_str_replace'
            ),
            array(
                'field' => 'type',
                'label' => '类型',
                'rules' => 'trim|min_length[0]|max_length[32]'
            ),
            array(
                'field' => 'mobile',
                'label' => '移动端显示',
                'rules' => 'trim|numeric|min_length[0]|max_length[1]'
            )
        ),
        'permission/menu/remove' => array(
            array(
                'field' => 'selected[]',
                'label' => '菜单编号',
                'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
            )
        ),
        'permission/page_form/add' => array(
            array(
                'field' => 'name',
                'label' => '名称',
                'rules' => 'trim|required|min_length[1]|max_length[64]'
            ),
            array(
                'field' => 'label',
                'label' => 'Label',
                'rules' => 'trim|max_length[64]'
            ),
            array(
                'field' => 'type',
                'label' => '类型',
                'rules' => 'trim|max_length[32]'
            ),
            array(
                'field' => 'url',
                'label' => 'Url',
                'rules' => 'trim|max_length[128]'
            ),
            array(
                'field' => 'mid',
                'label' => '菜单',
                'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
            )
        ),
        'permission/page_form/edit' => array(
            array(
                'field' => 'selected',
                'label' => '页面搜索编号',
                'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
            ),
            array(
                'field' => 'mid',
                'label' => '菜单',
                'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
            ),
            array(
                'field' => 'name',
                'label' => '名称',
                'rules' => 'trim|required|min_length[1]|max_length[64]'
            ),
            array(
                'field' => 'type',
                'label' => '类型',
                'rules' => 'trim|max_length[32]'
            ),
            array(
                'field' => 'url',
                'label' => 'Url',
                'rules' => 'trim|max_length[128]'
            ),
            array(
                'field' => 'label',
                'label' => 'Label',
                'rules' => 'trim|max_length[64]'
            )
        ),
        'permission/page_form/remove' => array(
            array(
                'field' => 'selected[]',
                'label' => '页面表单编号',
                'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
            )
        ),
        'permission/page_search/add' => array(
            array(
                'field' => 'name',
                'label' => '名称',
                'rules' => 'trim|required|min_length[1]|max_length[64]'
            ),
            array(
                'field' => 'label',
                'label' => 'Label',
                'rules' => 'trim|max_length[64]'
            ),
            array(
                'field' => 'type',
                'label' => '类型',
                'rules' => 'trim|max_length[32]'
            ),
            array(
                'field' => 'url',
                'label' => 'Url',
                'rules' => 'trim|max_length[128]'
            ),
            array(
                'field' => 'mid',
                'label' => '菜单',
                'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
            )
        ),
        'permission/page_search/edit' => array(
            array(
                'field' => 'selected',
                'label' => '页面搜索编号',
                'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
            ),
            array(
                'field' => 'mid',
                'label' => '菜单',
                'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
            ),
            array(
                'field' => 'name',
                'label' => '名称',
                'rules' => 'trim|required|min_length[1]|max_length[64]'
            ),
            array(
                'field' => 'type',
                'label' => '类型',
                'rules' => 'trim|max_length[32]'
            ),
            array(
                'field' => 'url',
                'label' => 'Url',
                'rules' => 'trim|max_length[128]'
            ),
            array(
                'field' => 'label',
                'label' => 'Label',
                'rules' => 'trim|max_length[64]'
            )
        ),
        'permission/page_search/remove' => array(
            array(
                'field' => 'selected[]',
                'label' => '页面搜索编号',
                'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
            )
        ),
        'permission/role/add' => array(
            array(
                'field' => 'name',
                'label' => '角色名称',
                'rules' => 'trim|required|min_length[1]|max_length[64]'
            )
        ),
        'permission/role/edit' => array(
            array(
                'field' => 'selected',
                'label' => '角色编号',
                'rules' => 'trim|required|numeric|min_length[1]|max_length[64]'
            ),
            array(
                'field' => 'name',
                'label' => '角色名称',
                'rules' => 'trim|min_length[1]|max_length[64]'
            )
        ),
        'permission/role/remove' => array(
            array(
                'field' => 'selected[]',
                'label' => '角色编号',
                'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
            )
        ),
        'permission/role_card/edit' => array(
            array(
                'field' => 'rid',
                'label' => '角色',
                'rules' => 'trim|required|min_length[1]|max_length[4]'
            ),
            array(
                'field' => 'cid[]',
                'label' => '卡片权限',
                'rules' => 'trim|min_length[1]|max_length[4]'
            )
        ),
        'permission/role_element/edit' => array(
            array(
                'field' => 'rid',
                'label' => '角色',
                'rules' => 'trim|required|min_length[1]|max_length[4]'
            ),
            array(
                'field' => 'eid[]',
                'label' => '元素权限',
                'rules' => 'trim|min_length[1]|max_length[4]'
            )
        ),
        'permission/role_form/edit' => array(
            array(
                'field' => 'rid',
                'label' => '角色',
                'rules' => 'trim|required|min_length[1]|max_length[4]'
            ),
            array(
                'field' => 'fid[]',
                'label' => '表单权限',
                'rules' => 'trim|min_length[1]|max_length[4]'
            )
        ),
        'permission/role_func/edit' => array(
            array(
                'field' => 'rid',
                'label' => '角色',
                'rules' => 'trim|required|min_length[1]|max_length[4]'
            ),
            array(
                'field' => 'fid[]',
                'label' => '功能权限',
                'rules' => 'trim|min_length[1]|max_length[4]'
            )
        ),
        'permission/role_menu/edit' => array(
            array(
                'field' => 'rid',
                'label' => '角色',
                'rules' => 'trim|required|min_length[1]|max_length[4]'
            ),
            array(
                'field' => 'mid[]',
                'label' => '菜单权限',
                'rules' => 'trim|min_length[1]|max_length[4]'
            )
        ),
        'permission/role_page_form/edit' => array(
            array(
                'field' => 'rid',
                'label' => '角色',
                'rules' => 'trim|required|min_length[1]|max_length[4]'
            ),
            array(
                'field' => 'pfid[]',
                'label' => '页面表单权限',
                'rules' => 'trim|min_length[1]|max_length[4]'
            )
        ),
        'permission/role_page_search/edit' => array(
            array(
                'field' => 'rid',
                'label' => '角色',
                'rules' => 'trim|required|min_length[1]|max_length[4]'
            ),
            array(
                'field' => 'psid[]',
                'label' => '页面搜索权限',
                'rules' => 'trim|min_length[1]|max_length[4]'
            )
        ),
        'permission/role_visit/edit' => array(
            array(
                'field' => 'rid',
                'label' => '角色',
                'rules' => 'trim|required|min_length[1]|max_length[4]'
            ),
            array(
                'field' => 'vid[]',
                'label' => '访问控制权限',
                'rules' => 'trim|min_length[1]|max_length[4]'
            )
        ),
        'permission/usergroup/add' => array(
            array(
                'field' => 'name',
                'label' => '名称',
                'rules' => 'trim|required|min_length[1]|max_length[128]'
            ),
            array(
                'field' => 'class',
                'label' => '别名',
                'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
            ),
            array(
                'field' => 'parent',
                'label' => '名称',
                'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
            )
        ),
        'permission/usergroup/edit' => array(
            array(
                'field' => 'selected',
                'label' => '用户组编号',
                'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
            ),
            array(
                'field' => 'name',
                'label' => '名称',
                'rules' => 'trim|required|min_length[1]|max_length[128]'
            ),
            array(
                'field' => 'class',
                'label' => '别名',
                'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
            ),
            array(
                'field' => 'parent',
                'label' => '名称',
                'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
            )
        ),
        'permission/usergroup/remove' => array(
            array(
                'field' => 'selected[]',
                'label' => '选择项',
                'rules' => 'trim|required|numeric|min_length[1]|max_length[10]'
            )
        ),
        'permission/usergroup_role/add' => array(
            array(
                'field' => 'uid',
                'label' => '用户组',
                'rules' => 'trim|required|min_length[1]|max_length[4]'
            ),
            array(
                'field' => 'rid[]',
                'label' => '用户角色',
                'rules' => 'trim|required|min_length[1]|max_length[4]'
            )
        ),
        'permission/usergroup_role/edit' => array(
            array(
                'field' => 'uid',
                'label' => '用户组',
                'rules' => 'trim|required|min_length[1]|max_length[4]'
            ),
            array(
                'field' => 'rid[]',
                'label' => '用户角色',
                'rules' => 'trim|min_length[1]|max_length[4]'
            )
        ),
        'permission/visit/add' => array(
            array(
                'field' => 'name',
                'label' => '访问控制名称',
                'rules' => 'trim|required|min_length[1]|max_length[64]'
            ),
            array(
                'field' => 'controller',
                'label' => '访问控制控制器',
                'rules' => 'trim|required|min_length[1]|max_length[64]'
            ),
            array(
                'field' => 'url',
                'label' => 'Url',
                'rules' => 'trim|required|min_length[1]|max_length[128]'
            )
        ),
        'permission/visit/edit' => array(
            array(
                'field' => 'selected',
                'label' => '访问控制编号',
                'rules' => 'trim|required|numeric|min_length[1]|max_length[64]'
            ),
            array(
                'field' => 'name',
                'label' => '访问控制名称',
                'rules' => 'trim|min_length[1]|max_length[64]'
            ),
            array(
                'field' => 'controller',
                'label' => '访问控制控制器',
                'rules' => 'trim|required|min_length[1]|max_length[64]'
            ),
            array(
                'field' => 'url',
                'label' => 'Url',
                'rules' => 'trim|required|min_length[1]|max_length[128]'
            )
        ),
        'permission/visit/remove' => array(
            array(
                'field' => 'selected[]',
                'label' => '访问控制编号',
                'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
            )
        ),
        'product/board/add' => array(
            array(
                'field' => 'name',
                'label' => '名称',
                'rules' => 'trim|min_length[1]|max_length[64]'
            ),
            array(
                'field' => 'length',
                'label' => '长度',
                'rules' => 'trim|required|numeric|min_length[1]|max_length[10]'
            ),
            array(
                'field' => 'width',
                'label' => '宽度',
                'rules' => 'trim|required|numeric|min_length[1]|max_length[10]'
            ),
            array(
                'field' => 'thick_id',
                'label' => '厚度',
                'rules' => 'trim|required|min_length[1]|max_length[15]'
            ),
            array(
                'field' => 'color_id[]',
                'label' => '颜色',
                'rules' => 'trim|required|min_length[1]|max_length[70]'
            ),
            array(
                'field' => 'nature_id',
                'label' => '基材',
                'rules' => 'trim|required|min_length[1]|max_length[70]'
            ),
            array(
                'field' => 'class_id',
                'label' => '环保等级',
                'rules' => 'trim|required|min_length[1]|max_length[10]'
            ),
            array(
                'field' => 'supplier_id',
                'label' => '供应商',
                'rules' => 'trim|required|min_length[1]|max_length[10]'
            ),
            array(
                'field' => 'remark',
                'label' => '备注',
                'rules' => 'trim|min_length[1]|max_length[128]'
            )
        ),
        'product/board/edit' => array(
            array(
                'field' => 'selected',
                'label' => '编号',
                'rules' => 'required|numeric|min_length[1]|max_length[10]'
            ),
            array(
                'field' => 'name',
                'label' => '名称',
                'rules' => 'trim|min_length[1]|max_length[64]'
            ),
            array(
                'field' => 'length',
                'label' => '长度',
                'rules' => 'trim|required|numeric|min_length[1]|max_length[10]'
            ),
            array(
                'field' => 'width',
                'label' => '宽度',
                'rules' => 'trim|required|numeric|min_length[1]|max_length[10]'
            ),
            array(
                'field' => 'thick_id',
                'label' => '厚度',
                'rules' => 'trim|required|min_length[1]|max_length[15]'
            ),
            array(
                'field' => 'color_id[]',
                'label' => '颜色',
                'rules' => 'trim|required|min_length[1]|max_length[70]'
            ),
            array(
                'field' => 'nature_id',
                'label' => '基材',
                'rules' => 'trim|required|min_length[1]|max_length[70]'
            ),
            array(
                'field' => 'class_id',
                'label' => '环保等级',
                'rules' => 'trim|required|min_length[1]|max_length[10]'
            ),
            array(
                'field' => 'supplier_id',
                'label' => '供应商',
                'rules' => 'trim|required|min_length[1]|max_length[10]'
            ),
            array(
                'field' => 'remark',
                'label' => '备注',
                'rules' => 'trim|min_length[1]|max_length[128]'
            )
        ),
        'product/board/remove' => array(
            array(
                'field' => 'selected[]',
                'label' => '选择项',
                'rules' => 'trim|required|numeric|min_length[1]|max_length[10]'
            )
        ),
        'product/fitting/add' => array(
            array(
                'field' => 'type',
                'label' => '类别',
                'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
            ),
            array(
                'field' => 'name',
                'label' => '名称',
                'rules' => 'trim|required|min_length[1]|max_length[64]'
            ),
            array(
                'field' => 'unit',
                'label' => '单位',
                'rules' => 'trim|min_length[1]|max_length[8]'
            ),
            array(
                'field' => 'unit_price',
                'label' => '单价',
                'rules' => 'trim|required|decimal|min_length[1]|max_length[10]'
            ),
            array(
                'field' => 'supplier',
                'label' => '供应商',
                'rules' => 'trim|numeric|min_length[1]|max_length[4]'
            )
        ),
        'product/fitting/edit' => array(
            array(
                'field' => 'selected',
                'label' => '配件编号',
                'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
            ),
            array(
                'field' => 'type',
                'label' => '类别',
                'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
            ),
            array(
                'field' => 'name',
                'label' => '名称',
                'rules' => 'trim|required|min_length[1]|max_length[64]'
            ),
            array(
                'field' => 'unit_price',
                'label' => '单价',
                'rules' => 'trim|required|decimal|min_length[1]|max_length[10]'
            ),
            array(
                'field' => 'unit',
                'label' => '单位',
                'rules' => 'trim|min_length[1]|max_length[8]'
            ),
            array(
                'field' => 'supplier',
                'label' => '供应商',
                'rules' => 'trim|numeric|min_length[1]|max_length[4]'
            )
        ),
        'product/fitting/remove' => array(
            array(
                'field' => 'selected[]',
                'label' => '选择项',
                'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
            )
        ),
        'product/other/add' => array(
            array(
                'field' => 'type',
                'label' => '类别',
                'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
            ),
            array(
                'field' => 'name',
                'label' => '名称',
                'rules' => 'trim|required|min_length[1]|max_length[64]'
            ),
            array(
                'field' => 'spec',
                'label' => '规格',
                'rules' => 'trim|max_length[64]'
            ),
            array(
                'field' => 'unit',
                'label' => '单位',
                'rules' => 'trim|min_length[1]|max_length[8]'
            ),
            array(
                'field' => 'supplier',
                'label' => '供应商',
                'rules' => 'trim|numeric|min_length[1]|max_length[4]'
            )
        ),
        'product/other/edit' => array(
            array(
                'field' => 'selected',
                'label' => '配件编号',
                'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
            ),
            array(
                'field' => 'type',
                'label' => '类别',
                'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
            ),
            array(
                'field' => 'name',
                'label' => '名称',
                'rules' => 'trim|required|min_length[1]|max_length[64]'
            ),
            array(
                'field' => 'spec',
                'label' => '规格',
                'rules' => 'trim|max_length[64]'
            ),
            array(
                'field' => 'unit',
                'label' => '单位',
                'rules' => 'trim|min_length[1]|max_length[8]'
            ),
            array(
                'field' => 'supplier',
                'label' => '供应商',
                'rules' => 'trim|numeric|min_length[1]|max_length[4]'
            )
        ),
        'product/other/remove' => array(
            array(
                'field' => 'selected[]',
                'label' => '选择项',
                'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
            )
        ),
        'product/product/add' => array(
                array(
                    'field' => 'name',
                    'label' => '名称',
                    'rules' => 'trim|required|min_length[1]|max_length[16]'
                ),
                array(
                    'field' => 'code',
                    'label' => '代号',
                    'rules' => 'trim|required|min_length[1]|max_length[4]'
                ),
                array(
                    'field' => 'remark',
                    'label' => '备注',
                    'rules' => 'trim|min_length[1]|max_length[128]'
                )
        ),
        'product/product/edit' => array(
                array(
                    'field' => 'selected',
                    'label' => '编号',
                    'rules' => 'required|numeric|min_length[1]|max_length[4]'
                ),
                array(
                    'field' => 'name',
                    'label' => '名称',
                    'rules' => 'trim|required|min_length[1]|max_length[16]'
                ),
                array(
                    'field' => 'code',
                    'label' => '代号',
                    'rules' => 'trim|required|min_length[1]|max_length[4]'
                ),
                array(
                    'field' => 'remark',
                    'label' => '备注',
                    'rules' => 'trim|min_length[1]|max_length[128]'
                )
        ),
        'product/product/remove' => array(
                array(
                    'field' => 'selected[]',
                    'label' => '选择项',
                    'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
                )
        ),
        'product/server/add' => array(
            array(
                'field' => 'type',
                'label' => '类别',
                'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
            ),
            array(
                'field' => 'name',
                'label' => '名称',
                'rules' => 'trim|required|min_length[1]|max_length[64]'
            ),
            array(
                'field' => 'unit',
                'label' => '单位',
                'rules' => 'trim|min_length[1]|max_length[8]'
            ),
            array(
                'field' => 'unit_price',
                'label' => '单价',
                'rules' => 'trim|required|decimal|min_length[1]|max_length[10]'
            )
        ),
        'product/server/edit' => array(
            array(
                'field' => 'selected',
                'label' => '配件编号',
                'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
            ),
            array(
                'field' => 'type',
                'label' => '类别',
                'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
            ),
            array(
                'field' => 'name',
                'label' => '名称',
                'rules' => 'trim|required|min_length[1]|max_length[64]'
            ),
            array(
                'field' => 'unit',
                'label' => '单位',
                'rules' => 'trim|min_length[1]|max_length[8]'
            ),
            array(
                'field' => 'unit_price',
                'label' => '单价',
                'rules' => 'trim|required|decimal|min_length[1]|max_length[10]'
            )
        ),
        'product/server/remove' => array(
            array(
                'field' => 'selected[]',
                'label' => '选择项',
                'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
            )
        ),
        'stock/stock_outted/redelivery' => array(
            array(
                'field' => 'selected[]',
                'label' => '重新发货的货号',
                'rules' => 'required|numeric|min_length[1]|max_length[10]'
            )
        ),
        'supplier/supplier/add' => array(
            array(
                'field' => 'code',
                'label' => '代号',
                'rules' => 'trim|required|min_length[1]|max_length[4]'
            ),
            array(
                'field' => 'name',
                'label' => '名称',
                'rules' => 'trim|required|min_length[1]|max_length[64]'
            ),
            array(
                'field' => 'remark',
                'label' => '备注',
                'rules' => 'trim|max_length[128]'
            )
        ),
        'supplier/supplier/edit' => array(
            array(
                'field' => 'selected',
                'label' => '供应商编号',
                'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
            ),
            array(
                'field' => 'code',
                'label' => '代号',
                'rules' => 'trim|required|min_length[1]|max_length[4]'
            ),
            array(
                'field' => 'name',
                'label' => '名称',
                'rules' => 'trim|required|min_length[1]|max_length[64]'
            ),
            array(
                'field' => 'remark',
                'label' => '备注',
                'rules' => 'trim|max_length[128]'
            )
        ),
        'supplier/supplier/remove' => array(
            array(
                'field' => 'selected[]',
                'label' => '供应商编号',
                'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
            )
        )
);
