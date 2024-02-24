<?php
session_start();
if($_GET['adj-item']){
    unset($_SESSION['adj-item']);
}else if($_GET['sitem']){
    unset($_SESSION['sitem']);
}else if($_GET['price-item']){
    unset($_SESSION['price-item']);
}else if($_GET['sale-item']){
    unset($_SESSION['sale-item']);
    unset($_SESSION['sale_id']);
}else{
    unset($_SESSION['purchase-item']);
    unset($_SESSION['purchase_id']);
}