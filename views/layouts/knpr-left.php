<aside class="main-sidebar" style="background-color: whitesmoke">

    <section class="sidebar">

        <?= dmstr\widgets\Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu tree', 'data-widget'=> 'tree'],
                //Menu2 pada sidebar
                'items' => [
                    ['label' => 'Home', 'icon' => 'archive', 'url' => ['knpr-home'],],
                    ['label' => 'Monitoring', 'icon' => 'home', 'url' => ['knpr-monitoring'],],                    
                    [
                        'label' => 'Upload', 
                        'icon' => 'upload',
                        'url' => '#',
                        'items' => [
                            ['label' => 'Data', 'icon' => 'file-excel-o', 'url' => ['knpr-upload-data',],],
                            ['label' => 'Fenomena', 'icon' => 'exclamation-triangle', 'url' => ['knpr-upload-fenomena',],],                   
                        ],
                    ],
                    [
                        'label' => 'Reg-Nas', 
                        'icon' => 'adjust',
                        'url' => '#',
                        'items' => [
                            ['label' => 'Diskrepansi-Prov', 'icon' => 'folder', 'url' => ['knpr-regnas-diskrepansi',],],
                            ['label' => 'Diskrepansi-Pdrb', 'icon' => 'folder', 'url' => ['knpr-regnas-diskrepansi-pdrb',],],
                            ['label' => 'Rekonsiliasi', 'icon' => 'adjust', 'url' => ['knpr-regnas-rekonsiliasi',],],   
                            ['label' => 'Simulasi', 'icon' => 'sliders', 'url' => ['knpr-regnas-simulasi',],],
                            [
                                'label' => 'Rekap', 
                                'icon' => 'hdd-o', 
                                'url' => ['#',],
                                'items'=> [
                                ['label' => 'Data', 'icon' => 'file-excel-o', 'url' => ['knpr-regnas-rekap-data',],],
                                ['label' => 'Fenomena', 'icon' => 'exclamation-triangle', 'url' => ['knpr-regnas-rekap-fenomena',],],
                                    ],
                            ],
                        ],                        
                    ],
                    [
                        'label' => 'Lapres', 
                        'icon' => 'institution',
                        'url' => '#',
                        'items' => [
                            ['label' => 'Pulau', 'icon' => 'folder', 'url' => ['knpr-lapres-pulau',],],
                            ['label' => 'KabKot', 'icon' => 'folder', 'url' => ['knpr-lapres-kabkot',],],                        
                        ],
                    ],
                    ['label' => 'Generate', 'icon' => 'spinner', 'url' => ['knpr-generate'],],
                    ['label' => 'Peta', 'icon' => 'map', 'url' => ['knpr-peta'],],
                    [
                        'label' => 'Manajemen', 
                        'icon' => 'tasks',
                        'url' => '#',
                        'items' => [
                            ['label' => 'User', 
                             'icon' => 'user', 
                             'url' => ['knpr-manajemen-user'],
                            ],
                            ['label' => 'Waktu', 'icon' => 'clock-o', 'url' => ['knpr-manajemen-waktu',],],                        
                        ],
                    ],
                ],
            ]
        ) ?>

    </section>

</aside>
