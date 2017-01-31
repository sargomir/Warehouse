<?php
/**
 * Message translations.
 * Each array element represents the translation (value) of a message (key).
 * If the value is empty, the message is considered as not translated.
 * Messages that no longer need translation will have their translations
 * enclosed between a pair of '@@' marks.
 *
 * NOTE, this file must be saved in UTF-8 encoding.
 *
 * @version $Id: $
 */
return [
    'Help' => 'Справка',
    'List' => 'Список',
    'Tracking' => 'Отслеживание',
    'Related documents' => 'Связанные документы',
    'All' => 'Все',
    'Select ...' => 'Выберите',
    
    // Login form
    'Sign in' => 'Аутентификация',
    'User' => 'Пользователь',
    'Password' => 'Пароль',
    'Remember me' => 'Запомнить',
    'Login' => 'Войти',
    'Forgot Password?<br> Call {number} to get a new password.' => 'Не можете войти? <br> Позвоните {number} чтобы получить новый пароль.',
    'Profile' => 'Профиль',
    'Sign out' => 'Выйти',    

    // Gridview    
    'Create' => 'Добавить',
    'View' => 'Просмотр',
    'Update' => 'Изменить',
    'Delete' => 'Удалить',
    'Delete selected' => 'Удалить выбранное',
    
    // Contents
    'Content Id' => 'Код строки',
    'Document Id' => 'Код документа',
    'Product Id' => 'Код товара',
    'Amount' => 'Количество',
    'Price' => 'Цена',
    'Document content' => 'Состав документа',
    'Required greater than zero' => 'Значение должно быть больше нуля',
    'Select many hint' => 'Отметьте одну или несколько товарных позиций галочкой и нажмите "Добавить".
        По умолчанию перемещается максимальное количество товара.
        Его можно изменить в форме "Состав документа".',
    'There are {0} records. Are you sure you want to display them all?' => 'Найдено {0} записей. Вы уверены что хотите отобразить их все?',
    'Data integrity check failure.' => 'Ошибка целостости данных. Похоже, что для перемещения или списания было выбрано большее количество товара, чем есть в наличии.',
    
    // Products
    'Product Id' => 'Код товара',
    'Product' => 'Товар',
    'Products' => 'Товары',
    'Manufacturer Id' => 'Код производителя',
    'Article' => 'Артикул',
    'Description' => 'Описание',
    'Dimension' => 'Размерность',
    'New product' => 'Новый товар',
    'Availability' => 'Наличие',
    'Products availability' => 'Товары в наличии',
    
    // Documents
    'Document ID' => 'Код документа',
    'Document' => 'Документ',
    'Company ID' => 'Код компании',
    'Type ID' => 'Код типа документа',
    'Date' => 'Дата',
    'From Warehouse ID' => 'Код склада источника',
    'To Warehouse ID' => 'Код склада назначения',
    'Comment' => 'Заметки',
    'Documents' => 'Документы',
    'Company' => 'Компания',
    'Type' => 'Тип документа',
    'From' => 'Со склада',
    'To' => 'На склад',
    'New document' => 'Новый документ',
    'Depending on Document type this field must be empty' => 'Для установленного типа документа это поле должно быть пустым',
    'Document contents must be empty to change this field' => 'Состав документа должен быть пустым, чтобы изменить это значение',
    
    // Document type
    'Document type' => 'Тип документа',
    'Supply' => 'Поставка',
    'Transfer' => 'Перемещение',
    'Write-off' => 'Списание',
    
    // Manufacturers
    'Manufacturer Id' => 'Код производителя',
    'Manufacturer' => 'Производитель',
    'Manufacturers' => 'Производители',
    'New manufacturer' => 'Новый производитель',
    
    // Warehouses
    'Warehouse Id' => 'Код склада',
    'Warehouse' => 'Склад',
    'Warehouses' => 'Склады',
    'Description' => 'Описание',
    'New warehouse' => 'Новый склад',
];

?>
