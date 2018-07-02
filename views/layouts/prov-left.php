<aside class="main-sidebar" style="background-color: window">

    <section class="sidebar">

        <?= dmstr\widgets\Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu tree', 'data-widget'=> 'tree'],
                'items' => [
                    ['label' => 'Home', 'icon' => 'archive', 'url' => ['prov-home'],],                   
                    [
                        'label' => 'Upload', 
                        'icon' => 'upload',
                        'url' => '#',
                        'items' => [
                            ['label' => 'Data', 'icon' => 'file-excel-o', 'url' => ['prov-upload-data',],],
                            ['label' => 'Fenomena', 'icon' => 'exclamation-triangle', 'url' => ['prov-upload-fenomena',],],                   
                        ],
                    ],
                    [
                        'label' => 'Reg-Nas', 
                        'icon' => 'adjust',
                        'url' => '#',
                        'items' => [
                            ['label' => 'Diskrepansi', 'icon' => 'folder', 'url' => ['prov-regnas-diskrepansi',],],
                            ['label' => 'Rekonsiliasi', 'icon' => 'folder', 'url' => ['prov-regnas-rekonsiliasi',],],
                            [
                                'label' => 'Rekap', 
                                'icon' => 'hdd-o', 
                                'items' => [
                                ['label' => 'Data', 'icon' => 'file-excel-o', 'url' => ['prov-regnas-rekap-data',],],
                                ['label' => 'Fenomena', 'icon' => 'exclamation-triangle', 'url' => ['prov-regnas-rekap-fenomena',],],
                                ],
                            ],
                        ],                        
                    ],
                    [
                        'label' => 'Manajemen User', 
                        'icon' => 'user',
                        'url' => ['prov-manajemen-user',],
                    ]
                ],
            ]
        ) ?>

    </section>

</aside>
