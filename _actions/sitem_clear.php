<?php
session_start();
if($_GET['adj-item']){
    unset($_SESSION['adj-item']);
}else if($_GET['sitem']){
    unset($_SESSION['sitem']);
}else if($_GET['price-item']){
    unset($_SESSION['price-item']);
}else{
    unset($_SESSION['sale-item']);
    unset($_SESSION['sale_id']);
}