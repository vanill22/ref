<?php

function load_users_data($user_ids) {
    $user_ids = explode(',', $user_ids);
    foreach ($user_ids as $user_id) {
        $db = mysqli_connect("localhost", "root", "123123", "database");
        $sql = mysqli_query($db, "SELECT * FROM users WHERE id=$user_id");
        while($obj = $sql->fetch_object()){
            $data[$user_id] = $obj->name;
        }
        mysqli_close($db);
    }
    return $data;
}
// Как правило, в $_GET['user_ids'] должна приходить строка
// с номерами пользователей через запятую, например: 1,2,17,48
$data = load_users_data($_GET['user_ids']);
foreach ($data as $user_id=>$name) {
    echo "<a href=\"/show_user.php?id=$user_id\">$name</a>";
}


/**
 * Загружает данные пользователей из базы данных.
 *
 * @param array $user_ids Массив идентификаторов пользователей.
 *
 * @return array Ассоциативный массив, где ключи - идентификаторы пользователей,
 *               а значения - их имена.
 */
function load_users_data_ref(array $user_ids): array
{
    $data = [];
    $user_ids = array_map('intval', $user_ids); // преобразуем элементы массива в целые числа

    // Формируем один SQL-запрос с использованием безопасного способа подстановки параметров
    $ids_string = implode(',', $user_ids);
    $db = mysqli_connect("localhost", "root", "123123", "database");
    $sql = mysqli_prepare($db, "SELECT id, name FROM users WHERE id IN ({$ids_string})");
    mysqli_stmt_execute($sql);
    mysqli_stmt_bind_result($sql, $id, $name);

    // Сохраняем результаты в ассоциативный массив
    while (mysqli_stmt_fetch($sql)) {
        $data[$id] = $name;
    }

    mysqli_stmt_close($sql);
    mysqli_close($db);
    return $data;
}

// Пример использования функции
$user_ids = explode(',', $_GET['user_ids']);
$data = load_users_data_ref($user_ids);
foreach ($data as $user_id => $name) {
    echo "<a href=\"/show_user.php?id=$user_id\">$name</a>";
}

//Нет проверки на валидность переданных user_ids.
//В цикле foreach для каждого user_id открывается и закрывается новое соединение с базой данных, что является неоптимальным решением и может привести к проблемам с производительностью.
//В запросе не используется безопасный способ подстановки переменной $user_id, что может привести к SQL-инъекциям.
//Пример проявления уязвимости: если злоумышленник передаст в параметре user_ids строку вида "1,2,17,48; DROP TABLE users", то при выполнении SQL-запроса будет удалена таблица users.

