<?php

$_['_Link'] = 'الرابط';
$_['_Name'] = 'القائمة';
$_['_MenuItemType'] = 'نوع القائمة';
$_['_Parent'] = 'القائمة الأب';




// MenuItemTypes
$_['_MenuItemTypesList'] = array(
    array(// row #0
        'ID' => 1,
        'Alias' => 'List_All_Categories',
        'Name' => 'عرض جميع الأقسام',
        'Description' => NULL,
        'Format' => 'product/cats',
        'Editable' => 0,
        'TableData' => NULL,
    ),
    array(// row #1
        'ID' => 2,
        'Alias' => 'List_Contents_Category',
        'Name' => 'عرض جميع الأقسام داخل القسم الأب',
        'Description' => NULL,
        'Format' => 'product/tc/{0}',
        'Editable' => 0,
        'TableData' => 'Categories',
    ),
    array(// row #2
        'ID' => 3,
        'Alias' => 'Single_Article',
        'Name' => 'عرض منتج',
        'Description' => NULL,
        'Format' => 'product/i/{0}',
        'Editable' => 0,
        'TableData' => 'Posts',
    ),
    array(// row #3
        'ID' => 4,
        'Alias' => 'List_All_in_Category',
        'Name' => 'عرض جميع المنتجات داخل القسم',
        'Description' => NULL,
        'Format' => 'product/c/{0}',
        'Editable' => 0,
        'TableData' => 'Categories',
    ),
    array(// row #4
        'ID' => 5,
        'Alias' => 'External_Link',
        'Name' => 'رابط خارجي',
        'Description' => NULL,
        'Format' => '{0}',
        'Editable' => 1,
        'TableData' => NULL,
    ),
    array(// row #5
        'ID' => 6,
        'Alias' => 'Break',
        'Name' => 'فاصل',
        'Description' => NULL,
        'Format' => 'javascript:void(0)',
        'Editable' => 0,
        'TableData' => NULL,
    ),
    array(// row #6
        'ID' => 7,
        'Alias' => 'Code',
        'Name' => 'كود',
        'Description' => NULL,
        'Format' => '{0}',
        'Editable' => 1,
        'TableData' => NULL,
    ),
    array(// row #7
        'ID' => 8,
        'Alias' => 'Page',
        'Name' => 'صفحة',
        'Description' => NULL,
        'Format' => 'page/i/{0}',
        'Editable' => 0,
        'TableData' => 'Pages',
    ),
    array(// row #8
        'ID' => 9,
        'Alias' => 'PhotoAlbum',
        'Name' => 'ألبوم صور',
        'Description' => NULL,
        'Format' => 'gallery/i/{0}',
        'Editable' => 0,
        'TableData' => 'MenuAlbums',
    ),
    array(// row #9
        'ID' => 10,
        'Alias' => 'Videos',
        'Name' => 'فيديوهات',
        'Description' => NULL,
        'Format' => 'video',
        'Editable' => 0,
        'TableData' => NULL,
    ),
    array(// row #10
        'ID' => 11,
        'Alias' => 'Home',
        'Name' => 'الرئيسية',
        'Description' => NULL,
        'Format' => 'home',
        'Editable' => 0,
        'TableData' => NULL,
    ),
);
