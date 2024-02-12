<?php
    require "../config/config.php";
    if($_POST){
        $typeId = $_POST['typeId'];
        $prefix = $_POST['prefix'];
        $digit = $_POST['digit'];
        //update shop info
        $sql = "UPDATE gen_id SET prefix=:prefix, digit=:digit WHERE id=:typeId";
        $pdostatement = $pdo->prepare($sql);
        $pdostatement->execute([
            ':prefix' => $prefix,
            ':digit' => $digit,
            ':typeId' => $typeId
        ]);
    }
?>