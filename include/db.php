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

    function InsertDB($table, $to_vals, $values) {
        $text = "INSERT INTO `$table` (";

        foreach ($to_vals as $vals) {
            $text .= "`".$vals."`, ";
        }

        $text = substr($text, 0, strlen($text)-2).') VALUES (';

        foreach ($values as $vals) {
            $text .= (is_numeric($vals) || is_float($vals) ? $vals.', ' : "'".escpe_val($vals)."', ");
        }

        $text = substr($text, 0, strlen($text)-2).')';
        DataBase($text, FALSE, FALSE);
    }

    function UpdateDB($table, $to_vals, $values, $append='') {
        $text = "UPDATE `$table` SET ";

        for ($i = 0; $i != sizeof($to_vals); $i++) {
            $text .= '`'.$to_vals[$i].'` = '.(is_numeric($values[$i]) || is_float($values[$i]) ? $values[$i] : "'".escpe_val($values[$i])."'").', ';
        }

        $text = substr($text, 0, strlen($text)-2);
        DataBase($text.$append, FALSE, FALSE);
    }

    function escpe_val($string) {
        return str_replace("'", "''", $string);
    }

?>