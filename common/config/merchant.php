<?php

return [

    // ----------------------- 菜单配置 ----------------------- //
    'config' => [
        // 菜单配置
        'menu' => [
            'location' => 'default', // default:系统顶部菜单;addons:应用中心菜单
            'icon' => 'fa fa-fw fa-paypal',
        ],
        // 子模块配置
        'modules' => [
            //基础模块
            'base' => [
                'class' => 'addons\Finance\merchant\modules\base\Module',
            ],
            'purchase' => [
                'class' => 'addons\Finance\merchant\modules\purchase\Module',
            ],
            //报表模块
            'report' => [
                'class' => 'addons\Finance\merchant\modules\report\Module',
            ],
            //资金模块
            'capital' => [
                'class' => 'addons\Finance\merchant\modules\capital\Module',
            ],
        ],
    ],

    // ----------------------- 快捷入口 ----------------------- //

    'cover' => [

    ],

    // ----------------------- 菜单配置 ----------------------- //

    'menu' => [
        [
            'title' => '财务首页',
            'route' => 'main/index',
            'icon' => 'fa-fw fa-dashboard',
            'params' => [

            ],
            'child' => [

            ],
        ],
        [
            'title' => '收款管理',
            'route' => 'capital/receipt/index',
            'icon' => 'fa fa-fw fa-jpy',
            'params' => [

            ],
            'child' => [

            ],
        ],
        [
            'title' => '付款管理',
            'route' => 'capital/payment/index',
            'icon' => 'fa fa-fw fa-paypal',
            'params' => [

            ],
            'child' => [

            ],
        ],
        [
            'title' => '采购管理',
            'route' => 'purchase',
            'icon' => 'fa fa-fw fa-cart-plus',
            'params' => [

            ],
            'child' => [
                [
                    'title' => '采购订单',
                    'route' => 'purchase/order/index',
                    'params' => [

                    ],
                    'child' => [

                    ],
                ],
                [
                    'title' => '采购入库',
                    'route' => 'purchase/storage/index',
                    'params' => [

                    ],
                    'child' => [

                    ],
                ]
            ],
        ],
        //库存
        [
            'title' => '库存管理',
            'route' => 'stock',
            'icon' => 'fa fa-fw fa-flask',
            'params' => [

            ],
            'child' => [
                [
                    'title' => '调拨单列表',
                    'route' => 'stock/allocation/index',
                    'params' => [

                    ],
                    'child' => [

                    ],
                ],
                [
                    'title' => '盘点单列表',
                    'route' => 'stock/inventory/index',
                    'params' => [

                    ],
                    'child' => [

                    ],
                ]
            ],
        ],
        [
            'title' => '资金管理',
            'route' => 'capital',
            'icon' => 'fa fa-fw fa-inbox',
            'params' => [

            ],
            'child' => [
                [
                    'title' => '核销单列表',
                    'route' => 'capital/exporter/index',
                    'params' => [

                    ],
                    'child' => [

                    ],
                ],
                [
                    'title' => '其他收款单',
                    'route' => 'capital/other-receipt/index',
                    'params' => [

                    ],
                    'child' => [

                    ],
                ],
                [
                    'title' => '其他付款单',
                    'route' => 'capital/other-payment/index',
                    'params' => [

                    ],
                    'child' => [

                    ],
                ],
                [
                    'title' => '转账单管理',
                    'route' => 'capital/transfer/index',
                    'params' => [

                    ],
                    'child' => [

                    ],
                ]
            ],
        ],
        [
            'title' => '报表管理',
            'route' => 'report',
            'icon' => 'fa fa-fw fa-file-excel-o',
            'params' => [

            ],
            'child' => [
                [
                    'title' => '现金银行报表',
                    'route' => 'report/capital/cash-bank',
                    'params' => [

                    ],
                    'child' => [

                    ],
                ],
                [
                    'title' => '应收款明细',
                    'route' => 'report/capital/receivables',
                    'params' => [

                    ],
                    'child' => [

                    ],
                ],
                [
                    'title' => '应付款明细',
                    'route' => 'report/capital/payable',
                    'params' => [

                    ],
                    'child' => [

                    ],
                ],
                [
                    'title' => '其他应收款明细',
                    'route' => 'report/capital/other-receivables',
                    'params' => [

                    ],
                    'child' => [

                    ],
                ],
                [
                    'title' => '其他应付款明细',
                    'route' => 'report/capital/other-payable',
                    'params' => [

                    ],
                    'child' => [

                    ],
                ],
                [
                    'title' => '利润报表',
                    'route' => 'report/capital/profit'
                ]
            ],
        ],
        [
            'title' => '资料设置',
            'route' => 'financeConfig',
            'icon' => 'fa fa-fw fa-cog',
            'params' => [],
            'child' => [
                [
                    'title' => '仓库管理',
                    'route' =>'stock/warehouse/index',
                ],
                [
                    'title' => '客户类别',
                    'route' =>'base/customer-cate/index',
                ],
                [
                    'title' => '供应商类别',
                    'route' =>'/base/supplier-cate/index',
                ],
                [
                    'title' => '供应商管理',
                    'route' =>'/base/supplier/index',
                ],

                [
                    'title' => '账户管理',
                    'route' =>'base/account/index',
                ],
                [
                    'title' => '收入类别',
                    'route' =>'base/income-cate/index',
                ],
                [
                    'title' => '支出类别',
                    'route' =>'base/expend-cate/index',
                ],
                [
                    'title' => '账户类别',
                    'route' =>'base/account-cate/index',
                ],
                [
                    'title' => '计量单位',
                    'route' =>'base/unit/index',
                ],
                [
                    'title' => '结算方式',
                    'route' =>'base/settlement-method/index',
                ],
            ]
        ],

    ],

    // ----------------------- 权限配置 ----------------------- //

    'authItem' => [
        [
            'title' => '所有权限',
            'name' => '*',
        ],
    ],
];