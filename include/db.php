<?php

    function hash_value($inputed) {
        return md5(sha1($inputed));
    }

    function DataBase($sql, $fetch=TRUE, $multi=FALSE) {
        $db = new PDO('sqlite:'.__DIR__.'/db.sqlite3');
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        if ($fetch) {
            if ($multi) {
                $rez = $db->query($sql);
            } else {
                $rez = $db->prepare($sql);
                $rez->execute();
                $rez = $rez->fetch();
            }

            $db = NULL;
            return $rez;
        } else {
            $rez = $db->exec($sql);
            $db = NULL;
        }
    }

    function InsertDB($db_name, $to_vals, $values) {
        $text = "INSERT INTO `$db_name` (";

        foreach ($to_vals as $vals) {
            $text .= "`".$vals."`, ";
        }

        $text = substr($text, 0, strlen($text)-2).') VALUES (';

        foreach ($values as $vals) {
            $text .= "'".$vals."', ";
        }

        $text = substr($text, 0, strlen($text)-2).')';
        DataBase($text, FALSE, FALSE);
    }


?>