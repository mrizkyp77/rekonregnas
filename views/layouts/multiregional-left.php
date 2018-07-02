<aside class="main-sidebar" style='background-color: gainsboro'>

    <section class="sidebar">

        <?= dmstr\widgets\Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu tree', 'data-widget'=> 'tree'],
                //Menu2 pada sidebar
                'items' => [
                    ['label' => 'Home', 'icon' => 'archive', 'url' => ['multiregional-home'],],
                    ['label' => 'Data', 'icon' => 'file-excel-o', 'url' => ['multiregional-data'],],
                    ['label' => 'Fenomena', 'icon' => 'exclamation-triangle', 'url' => ['multiregional-fenomena'],],
                    [
                        'label' => 'Upload', 
                        'icon' => 'upload',
                        'url' => '#',
                        'items' => [
                            ['label' => 'Data', 'icon' => 'file-excel-o', 'url' => ['multiregional-upload-data',],],
                            ['label' => 'Fenomena', 'icon' => 'exclamation-triangle', 'url' => ['multiregional-upload-fenomena',],],                         
                        ],
                    ],
                    [
                        'label' => 'Manajemen', 
                        'icon' => 'user',
                        'url' => ['multiregional-manajemen-user',],
                    ],
                ],
            ]
        ) ?>

    </section>

</aside>
