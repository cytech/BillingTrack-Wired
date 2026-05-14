<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => 'يجب قبول حقل :attribute.',
    'accepted_if' => 'يجب قبول حقل :attribute عندما يكون :other هو :value.',
    'active_url' => 'حقل :attribute ليس رابطاً صالحاً.',
    'after' => 'يجب أن يكون حقل :attribute تاريخاً لاحقاً لتاريخ :date.',
    'after_or_equal' => 'يجب أن يكون حقل :attribute تاريخاً لاحقاً أو مطابقاً لتاريخ :date.',
    'alpha' => 'يجب أن يحتوي حقل :attribute على أحرف فقط.',
    'alpha_dash' => 'يجب أن يحتوي حقل :attribute على أحرف، أرقام، شرطات، وشرطات سفلية فقط.',
    'alpha_num' => 'يجب أن يحتوي حقل :attribute على أحرف وأرقام فقط.',
    'array' => 'يجب أن يكون حقل :attribute مصفوفة.',
    'before' => 'يجب أن يكون حقل :attribute تاريخاً سابقاً لتاريخ :date.',
    'before_or_equal' => 'يجب أن يكون حقل :attribute تاريخاً سابقاً أو مطابقاً لتاريخ :date.',
    'between' => [
        'numeric' => 'يجب أن تكون قيمة :attribute بين :min و :max.',
        'file' => 'يجب أن يكون حجم ملف :attribute بين :min و :max كيلوبايت.',
        'string' => 'يجب أن يكون طول نص :attribute بين :min و :max حرفاً/أحرف.',
        'array' => 'يجب أن يحتوي حقل :attribute على عناصر يتراوح عددها بين :min و :max.',
    ],
    'boolean' => 'يجب أن تكون قيمة حقل :attribute إما صح أو خطأ (true/false).',
    'confirmed' => 'تأكيد حقل :attribute غير متطابق.',
    'current_password' => 'كلمة المرور غير صحيحة.',
    'date' => 'حقل :attribute ليس تاريخاً صالحاً.',
    'date_equals' => 'يجب أن يكون حقل :attribute تاريخاً مطابقاً لتاريخ :date.',
    'date_format' => 'حقل :attribute لا يتوافق مع الصيغة :format.',
    'different' => 'يجب أن يكون حقل :attribute مختلفاً عن حقل :other.',
    'digits' => 'يجب أن يحتوي حقل :attribute على :digits أرقام.',
    'digits_between' => 'يجب أن يحتوي حقل :attribute على أرقام يتراوح عددها بين :min و :max.',
    'dimensions' => 'أبعاد الصورة في حقل :attribute غير صالحة.',
    'distinct' => 'حقل :attribute يحتوي على قيمة مكررة.',
    'email' => 'يجب أن يكون حقل :attribute عنوان بريد إلكتروني صالحاً.',
    'ends_with' => 'يجب أن ينتهي حقل :attribute بأحد القيم التالية: :values.',
    'exists' => 'القيمة المحددة في حقل :attribute غير صالحة.',
    'file' => 'يجب أن يكون حقل :attribute ملفاً.',
    'filled' => 'حقل :attribute إلزامي ويجب أن يحتوي على قيمة.',
    'gt' => [
        'numeric' => 'يجب أن تكون قيمة :attribute أكبر من :value.',
        'file' => 'يجب أن يكون حجم ملف :attribute أكبر من :value كيلوبايت.',
        'string' => 'يجب أن يكون طول نص :attribute أكثر من :value حرفاً/أحرف.',
        'array' => 'يجب أن يحتوي حقل :attribute على أكثر من :value عناصر.',
    ],
    'gte' => [
        'numeric' => 'يجب أن تكون قيمة :attribute أكبر من أو تساوي :value.',
        'file' => 'يجب أن يكون حجم ملف :attribute أكبر من أو يساوي :value كيلوبايت.',
        'string' => 'يجب أن يكون طول نص :attribute مساوياً أو أكثر من :value حرفاً/أحرف.',
        'array' => 'يجب أن يحتوي حقل :attribute على :value عناصر أو أكثر.',
    ],
    'image' => 'يجب أن يكون حقل :attribute صورة.',
    'in' => 'القيمة المحددة في حقل :attribute غير صالحة.',
    'in_array' => 'حقل :attribute غير موجود في :other.',
    'integer' => 'يجب أن يكون حقل :attribute رقماً صحيحاً.',
    'ip' => 'يجب أن يكون حقل :attribute عنوان IP صالحاً.',
    'ipv4' => 'يجب أن يكون حقل :attribute عنوان IPv4 صالحاً.',
    'ipv6' => 'يجب أن يكون حقل :attribute عنوان IPv6 صالحاً.',
    'json' => 'يجب أن يكون حقل :attribute نص JSON صالحاً.',
    'lt' => [
        'numeric' => 'يجب أن تكون قيمة :attribute أقل من :value.',
        'file' => 'يجب أن يكون حجم ملف :attribute أقل من :value كيلوبايت.',
        'string' => 'يجب أن يكون طول نص :attribute أقل من :value حرفاً/أحرف.',
        'array' => 'يجب أن يحتوي حقل :attribute على أقل من :value عناصر.',
    ],
    'lte' => [
        'numeric' => 'يجب أن تكون قيمة :attribute أقل من أو تساوي :value.',
        'file' => 'يجب أن يكون حجم ملف :attribute أقل من أو يساوي :value كيلوبايت.',
        'string' => 'يجب أن يكون طول نص :attribute مساوياً أو أقل من :value حرفاً/أحرف.',
        'array' => 'يجب ألا يحتوي حقل :attribute على أكثر من :value عناصر.',
    ],
    'max' => [
        'numeric' => 'يجب ألا تكون قيمة :attribute أكبر من :max.',
        'file' => 'يجب ألا يكون حجم ملف :attribute أكبر من :max كيلوبايت.',
        'string' => 'يجب ألا يكون طول نص :attribute أكبر من :max حرفاً/أحرف.',
        'array' => 'يجب ألا يحتوي حقل :attribute على أكثر من :max عناصر.',
    ],
    'mimes' => 'يجب أن يكون حقل :attribute ملفاً من نوع: :values.',
    'mimetypes' => 'يجب أن يكون حقل :attribute ملفاً من نوع: :values.',
    'min' => [
        'numeric' => 'يجب أن تكون قيمة :attribute على الأقل :min.',
        'file' => 'يجب أن يكون حجم ملف :attribute على الأقل :min كيلوبايت.',
        'string' => 'يجب أن يكون طول نص :attribute على الأقل :min حرفاً/أحرف.',
        'array' => 'يجب أن يحتوي حقل :attribute على الأقل على :min عناصر.',
    ],
    'multiple_of' => 'يجب أن يكون حقل :attribute من مضاعفات القيمة :value.',
    'not_in' => 'القيمة المحددة في حقل :attribute غير صالحة.',
    'not_regex' => 'صيغة حقل :attribute غير صالحة.',
    'numeric' => 'يجب أن يكون حقل :attribute رقماً.',
    'password' => 'كلمة المرور غير صحيحة.',
    'present' => 'يجب تقديم حقل :attribute.',
    'regex' => 'صيغة حقل :attribute غير صالحة.',
    'required' => 'حقل :attribute إلزامي.',
    'required_if' => 'حقل :attribute إلزامي عندما يكون :other هو :value.',
    'required_unless' => 'حقل :attribute إلزامي إلا إذا كان :other ضمن :values.',
    'required_with' => 'حقل :attribute إلزامي عند تواجد :values.',
    'required_with_all' => 'حقل :attribute إلزامي عند تواجد جميع :values.',
    'required_without' => 'حقل :attribute إلزامي عند غياب :values.',
    'required_without_all' => 'حقل :attribute إلزامي عند غياب جميع :values.',
    'prohibited' => 'حقل :attribute محظور.',
    'prohibited_if' => 'حقل :attribute محظور عندما يكون :other هو :value.',
    'prohibited_unless' => 'حقل :attribute محظور إلا إذا كان :other ضمن :values.',
    'prohibits' => 'حقل :attribute يمنع تواجد حقل :other.',
    'same' => 'يجب أن يتطابق حقل :attribute مع حقل :other.',
    'size' => [
        'numeric' => 'يجب أن تكون قيمة :attribute مساوية لـ :size.',
        'file' => 'يجب أن يكون حجم ملف :attribute مساوياً لـ :size كيلوبايت.',
        'string' => 'يجب أن يكون طول نص :attribute مساوياً لـ :size حرفاً/أحرف.',
        'array' => 'يجب أن يحتوي حقل :attribute على :size عناصر بالضبط.',
    ],
    'starts_with' => 'يجب أن يبدأ حقل :attribute بأحد القيم التالية: :values.',
    'string' => 'يجب أن يكون حقل :attribute نصاً.',
    'timezone' => 'يجب أن يكون حقل :attribute منطقة زمنية صالحة.',
    'unique' => 'قيمة حقل :attribute مسجلة مسبقاً (مكررة).',
    'uploaded' => 'فشل رفع ملف :attribute.',
    'url' => 'يجب أن يكون حقل :attribute رابطاً صالحاً.',
    'uuid' => 'يجب أن يكون حقل :attribute معرف UUID صالحاً.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'رسالة مخصصة',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    */

    'attributes' => [],

];