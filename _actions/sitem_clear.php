<?php
session_start();
if($_GET['adj-item']){
    unset($_SESSION['adj-item']);
}else if($_GET['sitem']){
    unset($_SESSION['sitem']);
}else{
    unset($_SESSION['sale-item']);
}