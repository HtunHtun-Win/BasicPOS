<?php
	session_start();
	require '../config/config.php';

	if ($_POST) {
		$login_id = $_POST['login_id'];
		$password = $_POST['password'];
		$sql = 'SELECT * FROM users WHERE login_id=:login_id';
		$pdostatement = $pdo->prepare($sql);
		$pdostatement->execute([':login_id'=>$login_id]);
		$user = $pdostatement->fetchObject();
		if($user){
			if($user->password == $password){
				$_SESSION['user_id'] = $user->id;
				$_SESSION['user_name'] = $user->name;
				$_SESSION['user_role'] = $user->role;
				$_SESSION['logged_in'] = true;
				header('Location: /home.php');
			}else{
				$_SESSION['msg'] = "msg";
				header('Location: /index.php');
			}
		}else{
			$_SESSION['msg'] = "msg";
			header('Location: /index.php');
		}
	}else{
		header('Location: /index.php');
	}

?>