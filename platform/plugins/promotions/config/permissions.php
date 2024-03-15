<?php

return [
    [
        'name' => 'Promotions',
        'flag' => 'promotions.index',
    ],
    [
        'name'        => 'Create',
        'flag'        => 'promotions.create',
        'parent_flag' => 'promotions.index',
    ], 
    [
        'name'        => 'Edit',
        'flag'        => 'promotions.edit',
        'parent_flag' => 'promotions.index',
    ],
    [
        'name'        => 'Delete',
        'flag'        => 'promotions.delete',
        'parent_flag' => 'promotions.index',
    ]
];