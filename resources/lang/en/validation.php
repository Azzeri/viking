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

    'accepted' => 'Atrybut :attribute musi być zaakceptowany.',
    'accepted_if' => 'The :attribute musi być zaakceptowany jeżeli wartość :other wynosi :value.',
    'active_url' => 'Atrybut :attribute nie jest poprawnym adresem URL.',
    'after' => 'Atrybut :attribute musi być datą późniejszą niż :date.',
    'after_or_equal' => 'Atrybut :attribute musi być datą taką samą lub późniejszą niż :date.',
    'alpha' => 'Atrybut :attribute może zawierać tylko litery.',
    'alpha_dash' => 'Atrybut :attribute może zawierać tylko litery, cyfry, myślniki oraz podkreślniki.',
    'alpha_num' => 'Atrybut :attribute może zawierać tylko litery i cyfry.',
    'array' => 'Atrybut :attribute musi być tablicą.',
    'before' => 'Atrybut :attribute musi być datą starszą niż :date.',
    'before_or_equal' => 'Atrybut :attribute musi być datą taką samą lub starszą niż :date.',
    'between' => [
        'numeric' => 'Atrybut :attribute musi mieścić się pomiędzy :min i :max.',
        'file' => 'Atrybut :attribute musi mieścić się pomiędzy :min i :max kilobajtów.',
        'string' => 'Atrybut :attribute musi mieścić się pomiędzy :min i :max znaków.',
        'array' => 'Atrybut :attribute musi mieć pomiędzy :min and :max przedmiotów.',
    ],
    'boolean' => 'Atrybut :attribute musi mieć wartość prawda lub fałsz.',
    'confirmed' => 'Atrybut :attribute nie może być zatwierdzony.',
    'current_password' => 'Hasło jest niepoprawne.',
    'date' => 'Atrybut :attribute nie jest poprawnie sformatowaną datą.',
    'date_equals' => 'Atrybut :attribute musi być taki sam jak :date.',
    'date_format' => 'Atrybut :attribute nie pasuje do formatu :format.',
    'different' => 'Atrybut :attribute i :other muszą być różne.',
    'digits' => 'Atrybut :attribute musi być równy :digits.',
    'digits_between' => 'Atrybut :attribute musi mieścić się pomiędzy :min i :max.',
    'dimensions' => 'Atrybut :attribute ma niepoprawne wymiary.',
    'distinct' => 'Pole atrybutu :attribute ma zduplikowaną wartość.',
    'email' => 'Atrybut :attribute nie jest poprawnym adresem email.',
    'ends_with' => 'Atrybut :attribute musi kończyć się jedną z podanych wartości: :values.',
    'exists' => 'Atrybut :attribute jest niepoprawny.',
    'file' => 'Atrybut :attribute musi być plikiem.',
    'filled' => 'Pole atrybutu :attribute musi mieć wartość.',
    'gt' => [
        'numeric' => 'Atrybut :attribute musi być większy niż :value.',
        'file' => 'Atrybut :attribute musi być większy niż :value kilobajtów.',
        'string' => 'Atrybut :attribute musi być większy niż :value znaków.',
        'array' => 'Atrybut :attribute musi mieć więcej niż :value przedmiotów.',
    ],
    'gte' => [
        'numeric' => 'Atrybut :attribute musi być większy lub równy :value.',
        'file' => 'Atrybut :attribute musi być większy lub równy :value kilobajtów.',
        'string' => 'Atrybut :attribute musi być większy lub równy :value znaków.',
        'array' => 'Atrybut :attribute musi mieć :value lub więcej przedmiotów.',
    ],
    'image' => 'Atrybut :attribute musi być obrazem.',
    'in' => 'Atrybut :attribute jest niepoprawny.',
    'in_array' => 'Atrybut :attribute nie istnieje w :other.',
    'integer' => 'Atrybut :attribute musi być liczbą całkowitą.',
    'ip' => 'Atrybut :attribute musi być poprawnym adresem IP.',
    'ipv4' => 'Atrybut :attribute musi być poprawnym adresem IPV4.',
    'ipv6' => 'Atrybut :attribute musi być poprawnym adresem IPV6',
    'json' => 'Atrybut :attribute musi być w poprawnym formacie JSON',
    'lt' => [
        'numeric' => 'Atrybut :attribute musi być mniejszy niż :value.',
        'file' => 'Atrybut :attribute musi być mniejszy niż :value kilobajtów.',
        'string' => 'Atrybut :attribute musi być mniejszy niż :value znaków.',
        'array' => 'Atrybut :attribute musi mieć mniej niż :value przedmiotów.',
    ],
    'lte' => [
        'numeric' => 'Atrybut :attribute musi być mniejszy lub równy :value.',
        'file' => 'Atrybut :attribute musi być mniejszy lub równy :value kilobajtów.',
        'string' => 'Atrybut :attribute musi być mniejszy lub równy :value znaków.',
        'array' => 'Atrybut :attribute nie może mieć więcej niż :value przedmiotów.',
    ],
    'max' => [
        'numeric' => 'Atrybut :attribute nie może być większy niż :max.',
        'file' => 'Atrybut :attribute nie może być większy niż :max kilobajtów.',
        'string' => 'Atrybut :attribute nie może być większy niż :max znaków.',
        'array' => 'Atrybut :attribute nie może mieć więcej niż :max przedmiotów.',
    ],
    'mimes' => 'Atrybut :attribute musi być typu: :values.',
    'mimetypes' => 'Atrybut :attribute musi być typu: :values.',
    'min' => [
        'numeric' => 'Atrybut :attribute musi wynosić conajmniej :min.',
        'file' => 'Atrybut :attribute musi wynosić conajmniej :min kilobajtów.',
        'string' => 'Atrybut :attribute musi wynosić conajmniej :min znaków.',
        'array' => 'Atrybut :attribute musi mieć conajmniej :min przedmiotów.',
    ],
    'multiple_of' => 'Atrybut :attribute musi być mnogością :value.',
    'not_in' => 'Atrybut selected :attribute jest niepoprawny.',
    'not_regex' => 'Format atrybutu :attribute jest niepoprawny.',
    'numeric' => 'Atrybut :attribute musi być liczbą.',
    'password' => 'Hasło jest niepoprawne.',
    'present' => 'Pole :attribute musi być obecne.',
    'regex' => 'Format atrybutu :attribute jest niepoprawny.',
    'required' => 'Atrybut :attribute jest wymagany.',
    'required_if' => 'Atrybut :attribute jest wymagany gdy :other jest równy :value.',
    'required_unless' => 'Atrybut :attribute jest wymagany, chyba że :other znajduje się w :values.',
    'required_with' => 'Atrybut :attribute jest wymagany gdy :values jest obecny.',
    'required_with_all' => 'Atrybut :attribute jest wymagany gdy :values są obecne.',
    'required_without' => 'Atrybut :attribute jest wymagany gdy :values nie jest obecny.',
    'required_without_all' => 'Atrybut :attribute jest wymagany gdy żaden z :values nie jest obecny.',
    'prohibited' => 'Pole atrybutu :attribute jest niedozwolone.',
    'prohibited_if' => 'Pole atrybutu :attribute jest niedozwolone gdy :other jest równy :value.',
    'prohibited_unless' => 'Pole atrybutu :attribute jest niedozwolone, chyba że :other znajduje się w :values.',
    'prohibits' => 'Pole atrybutu :attribute wyklucza pole :other.',
    'same' => 'Atrybut :attribute oraz :other muszą być takie same.',
    'size' => [
        'numeric' => 'Atrybut :attribute musi być równy :size.',
        'file' => 'Atrybut :attribute musi być równy :size kilobajtów.',
        'string' => 'Atrybut :attribute musi być równy :size znaków.',
        'array' => 'Atrybut :attribute musi zawierać :size przedmiotów.',
    ],
    'starts_with' => 'Atrybut :attribute musi zaczynać się od jednej z wartości: :values.',
    'string' => 'Atrybut :attribute musi być ciągiem znaków.',
    'timezone' => 'Atrybut :attribute musi być poprawną strefą czasową.',
    'unique' => 'Atrybut :attribute jest już zajęty.',
    'uploaded' => 'Atrybut :attribute niepowodzenie w przesyłaniu.',
    'url' => 'Atrybut :attribute musi być poprawnym URL.',
    'uuid' => 'Atrybut :attribute musi być poprawnym UUID.',
    
    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [
        'name' => 'nazwa',
        'userName' => 'imię',
        'surname' => 'nazwisko',
        'nickname' => 'nick',
        'date_birth' => 'data urodzenia',
        'password' => 'hasło',
        'role' => 'rola',
        'email' => 'adres email',
        'description' => 'opis',
        'store_category_id' => 'ID kategorii sklepu',
        'image' => 'zdjęcie',
        'parentCategoryId' => 'ID kategorii nadrzędnej',
        'deleteImage' => 'czy usunąć zdjęcie',
        'parent_category_id' => 'ID kategorii nadrzędnej',
        'store_item_id' => 'ID przedmiotu w sklepie',
        'client_name' => 'imię klienta',
        'client_phone' => 'telefon klienta',
        'client_email' => 'email klienta',
        'title' => 'tytuł',
        'body' => 'treść',
        'resource_link' => 'odnośnik',
        'user_id' => 'ID użytkownika',
        'photo_category_id' => 'ID kategorii zdjęć',
        'images' => 'zdjęcia',
        'inventory_category_id' => 'ID kategorii sprzętu',
        'date_due' => 'termin',
        'notification' => 'przypomnienie',
        'assigned_user' => 'przypisany użytkownik',
        'date_start' => 'data rozpoczęcia',
        'time_start' => 'czas rozpoczęcia',
        'date_end' => 'data zakończenia',
        'time_end' => 'czas zakończenia',
        'addrStreet' => 'ulica',
        'addrNumber' => 'nr',
        'addrPostCode' => 'kod pocztowy',
        'addrTown' => 'miejscowość',
        'description_summary' => 'podsumowanie',
        'is_finished' => 'czy zakończono',
        'participants' => 'uczestnicy',
        'event_task_state_id' => 'ID statusu zadania',
        'event_id' => 'ID wydarzenia',
        'craftspeople' => 'wykonawcy',
        'password' => 'hasło',
        'current_password' => 'obecne hasło'
    ],

];
