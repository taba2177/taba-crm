<?php

return [
    // Contact Form
    'contact' => [
        'name' => 'الاسم',
        'email' => 'البريد الإلكتروني',
        'message' => 'الرسالة',
        'quiz' => 'كم الناتج: 3 + 4؟',
        'submit' => 'إرسال الرسالة',
        'success' => 'تم إرسال رسالتك بنجاح!',
        'error' => 'حدث خطأ. يرجى المحاولة مرة أخرى.',
        'quiz_error' => 'الإجابة غير صحيحة',
    ],

    // Quote Request Form
    'quote' => [
        'name' => 'الاسم الكامل',
        'phone' => 'رقم الجوال',
        'district' => 'الحي',
        'notes' => 'ملاحظات',
        'width' => 'العرض',
        'height' => 'الارتفاع',
        'curtain_type' => 'نوع الستارة',
        'room_name' => 'اسم الغرفة',
        'submit' => 'طلب عرض سعر',
        'success' => 'تم إرسال البيانات بنجاح!',
        'error' => 'حدث خطأ أثناء إرسال البيانات. يرجى المحاولة مرة أخرى.',
        'recaptcha_wait' => 'يرجى الانتظار حتى يتم التحقق من reCAPTCHA.',
        'recaptcha_failed' => 'فشل التحقق من reCAPTCHA. يرجى المحاولة مرة أخرى.',
    ],

    // Franchise Form
    'franchise' => [
        'full_name' => 'الاسم الثلاثي',
        'age' => 'العمر',
        'gender' => 'الجنس',
        'education' => 'المؤهل الدراسي',
        'city' => 'المدينة',
        'branches' => 'عدد الوحدات المطلوبة',
        'phone' => 'رقم الهاتف',
        'email' => 'البريد الإلكتروني',
        'country' => 'البلد',
        'province' => 'المحافظة',
        'business_experience' => 'الخبرة في مجال الأعمال',
        'restaurant_experience' => 'الخبرة في مجال المطاعم',
        'investment_amount' => 'مبلغ الاستثمار',
        'has_loans' => 'هل لديك قروض؟',
        'submit' => 'إرسال الطلب',
        'success' => 'تم إرسال طلبك بنجاح! سنتواصل معك قريباً.',
        'error' => 'حدث خطأ أثناء الإرسال. يرجى المحاولة مرة أخرى.',
        'recaptcha_required' => 'التحقق من reCAPTCHA مطلوب',
    ],

    // Validation Messages
    'validation' => [
        'name_required' => 'الاسم مطلوب.',
        'name_regex' => 'الرجاء إدخال اسم صالح يحتوي فقط على حروف.',
        'phone_required' => 'رقم الجوال مطلوب.',
        'phone_regex' => 'رقم الهاتف يجب أن يكون مكونًا من 10 أرقام.',
        'email_required' => 'البريد الإلكتروني مطلوب.',
        'message_required' => 'الرسالة مطلوبة.',
        'quiz_required' => 'يرجى الإجابة على السؤال.',
        'recaptcha_required' => 'التحقق من reCAPTCHA مطلوب.',
        'age_required' => 'العمر مطلوب.',
        'gender_required' => 'الجنس مطلوب.',
        'education_required' => 'المؤهل الدراسي مطلوب.',
        'city_required' => 'المدينة مطلوبة.',
        'branches_required' => 'عدد الوحدات المطلوبة مطلوب.',
        'country_required' => 'البلد مطلوب.',
        'province_required' => 'المحافظة مطلوبة.',
        'investment_amount_required' => 'مبلغ الاستثمار مطلوب.',
        'has_loans_required' => 'حالة القروض مطلوبة.',
    ],

    // Common
    'placeholder' => [
        'name' => 'أدخل اسمك',
        'email' => 'أدخل بريدك الإلكتروني',
        'phone' => 'أدخل رقم هاتفك',
        'message' => 'أدخل رسالتك',
    ],

    // SEO
    'seo' => [
        'franchise_title' => 'امتياز تجاري | انضم إلى علامتنا التجارية',
        'franchise_description' => 'انضم إلى عائلتنا واحصل على امتياز تجاري مميز. فرص استثمارية رائدة في المجال.',
        'franchise_name' => 'الامتياز التجاري',
        'franchise_schema_description' => 'فرص امتياز تجاري مميزة - استثمر مع علامة تجارية رائدة.',
        'default_description' => ':name - شريكك الموثوق للخدمات والحلول الاحترافية.',
    ],
];
