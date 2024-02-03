<?php
require '../_actions/auth.php';
require '../config/config.php';
check_auth();
check_privilege();
//category list query
if($_GET['search']){
    $search = $_GET['search'];
    $catSql = "SELECT * FROM categories WHERE isdeleted=0 AND name LIKE '%$search%'";
    $catPdo = $pdo->prepare($catSql);
    $catPdo->execute();
    $categories = $catPdo->fetchAll(PDO::FETCH_OBJ);
}else{
    $catSql = "SELECT * FROM categories WHERE isdeleted=0";
    $catPdo = $pdo->prepare($catSql);
    $catPdo->execute();
    $categories = $catPdo->fetchAll(PDO::FETCH_OBJ);
}
?>
<table class='table table-bordered table-striped'>
    <thead>
        <tr>
            <th>Name</th>
            <th>Description</th>
            <th width="50px">#</th>
            <th width="50px">#</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($categories as $category) : ?>
            <tr>
                <td><?= $category->name ?></td>
                <td><?= $category->description ?></td>
                <td>
                    <a href="/category.php?id=<?= $category->id ?>">
                        <i class='fa fa-edit'></i>
                    </a>
                </td>
                <td>
                    <a type="submit" onclick="deleteCategory(<?= $category->id ?>)">
                        <i class='fa fa-trash'></i>
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>