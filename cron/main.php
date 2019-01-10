<?php

    include '../include/db.php';

    $push = [];
    $del = [];

    $sql = 'SELECT `item_id`, `content`, `hash` FROM `temp` WHERE datetime("now") >= date(`date`)';
    $sql2 = 'SELECT `quantity`, `content` FROM `items` WHERE `id` = ';

    $rows = DataBase($sql, TRUE, TRUE);

    foreach ($rows as $row) {
        $id = $row['item_id'];
        $content = $row['content'];

        $temp = DataBase($sql2.$id);

        $tmp_q = $temp['quantity'] + 1;
        $tmp_c = $temp['content']."\n".$content;

        $vals = ['quantity', 'content'];
        $items = [$tmp_q, $tmp_c];

        $t = UpdateDB('items', $vals, $items, ' WHERE `id` = '.$id, FALSE);
        $p = 'DELETE FROM `temp` WHERE `hash` = "'.$row['hash'].'"';

        array_push($push, $t);
        array_push($del, $p);
    }

    for ($i = 0; $i != sizeof($push); $i++) {
        DataBase($push[$i], FALSE);
        DataBase($del[$i], FALSE);
    }

?>