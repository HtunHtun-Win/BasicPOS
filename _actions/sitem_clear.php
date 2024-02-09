<?php
session_start();
if($_GET['sitem']){
    unset($_SESSION['sitem']);
}else{
    unset($_SESSION['sale-item']);
}