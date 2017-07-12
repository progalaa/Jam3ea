<?php

$_['_BannerPositionsList'] = array(
    array(// row #1
        'ID' => 1,
        'Name' => 'Header',
        'Height' => 90,
        'Width' => 728,
    ),
    array(// row #2
        'ID' => 2,
        'Name' => 'Left1',
        'Height' => 370,
        'Width' => 330,
    ),
);


$_['_Order_State_List'] = array(
    0 => 'لم يتم التأكيد',
    1 => 'معلق',
    2 => 'جاري التجهيز',
    3 => 'تم شحن الطلب',
    5 => 'مكتمل',
    7 => 'ملغي',
    8 => 'مرفوض',
    9 => 'إلغاء عكس الطلب',
    10 => 'فشل',
    11 => 'مردود',
    12 => 'تم عكس الطلب',
    13 => 'إعادة المبلغ',
    16 => 'الطلب باطل',
    15 => 'تم التجهيز',
    14 => 'انتهاء الوقت'
);

$_['_Payments_MethodsList'] = array(
    Payments_Methods::Cash_On_Delivery => "الدفع عند الإستلام",
    Payments_Methods::Knet => "الدفع ببطاقة الكي نت"
);
