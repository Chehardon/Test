<?

	$mysqli = new mysqli('localhost', 'chehardo_t', 'wwpaQURn', 'chehardo_t');

	if ($mysqli->connect_errno)
			$error = 'error';
	
  session_start();
	$admin_login = "admin";
	$admin_password = "123";

	if(isset($_POST['admin_email']) AND isset($_POST['admin_password']) AND isset($_POST['admin'])){
			if($_POST['admin_email'] == $admin_login AND $_POST['admin_password'] == $admin_password){
				  $_SESSION['admin'] = true;
			}else{
				  $_SESSION['admin'] = false;
			}
	}
	if(isset($_GET['exit'])){
		$_SESSION['admin'] = false;
	}
	if(isset($_POST['add_zadachu'])){
		$sql = "INSERT INTO zadachnik (id, user_name, user_email, text)
        VALUES (NULL, '".$_POST['user_name']."', '".$_POST['user_email']."', '".$_POST['text']."') ";
        $mysqli->query($sql);
	}
	if(isset($_POST['edit_zadachu'])){
		$sql = "UPDATE zadachnik SET
			user_name = '".$_POST['user_name']."',
			user_email = '".$_POST['user_email']."',
			text = '".$_POST['text']."',
			status = '".$_POST['status']."'
			WHERE id = ".$_POST['id']."";
		$mysqli->query($sql);
	}
	if(isset($_GET['del'])){
		$sql = "DELETE FROM zadachnik WHERE id = ".$_GET['del']."";
		$mysqli->query($sql);
	}
	
    
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Задачник</title>
    <meta http-equiv=Content-Type content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
		<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">
		<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>
    <style>
		h3, nav, span {margin: 0 10px;}
        select {margin: 0 0 0 10px;}
		.badge-secondary {float: right;}
		.btn.add_zadachu{position: absolute;right: 100px;margin: -43px 0;}
		.login, .exit{position: absolute;right: 20px;margin: -43px 0;}
		.form-control {display: initial;width:auto;}
	</style>
  </head>
  <body>
<script>
// Example starter JavaScript for disabling form submissions if there are invalid fields
(function() {
  'use strict';
  window.addEventListener('load', function() {
    // Fetch all the forms we want to apply custom Bootstrap validation styles to
    var forms = document.getElementsByClassName('needs-validation');
    // Loop over them and prevent submission
    var validation = Array.prototype.filter.call(forms, function(form) {
      form.addEventListener('submit', function(event) {
        if (form.checkValidity() === false) {
          event.preventDefault();
          event.stopPropagation();
        }
        form.classList.add('was-validated');
      }, false);
    });
  }, false);
})();

$(document).ready(function() {
	  $('#example').DataTable();

		$('.edit').click(function(){
			$('#user_name').val($('#user_name_'+$(this).attr('ids')).html());
			$('#user_email').val($('#user_email_'+$(this).attr('ids')).html());
			$('#text').val($('#text_'+$(this).attr('ids')).html());
			$('#id').val($(this).attr('ids'));
			$('#add_zadachu').css( "display", "none" );
			$('#edit_zadachu').css( "display", "" );
			if($('#status_'+$(this).attr('ids')).val()==2){
				$('#status option[value=1]').attr('selected',false);
				$('#status option[value=2]').attr('selected',true);
			}else{
				$('#status option[value=1]').attr('selected',true);
				$('#status option[value=2]').attr('selected',false);
			}
		});
		
		$('.add_zadachu').click(function(){
			$('#user_name, #user_email, #text').val('');
			$('#id').val('');
			$('#add_zadachu').css( "display", "" );
			$('#edit_zadachu').css( "display", "none" );			
		});		
});
</script>			
    
<?
//-----Блок сортировок
        
        if(isset($_POST['sort_user_name']) AND !isset($_POST['sort_clear'])){$_SESSION['user_name'] = $_POST['sort_user_name'];}
        if(isset($_POST['sort_user_email']) AND !isset($_POST['sort_clear'])){$_SESSION['user_email'] = $_POST['sort_user_email'];}
        if(isset($_POST['sort_status']) AND !isset($_POST['sort_clear'])){$_SESSION['status'] = $_POST['sort_status'];}
        $page = ((isset($_GET['page']) AND !isset($_POST['sort_clear']))?$_GET['page']:1);
        if(isset($_POST['sort_clear'])){
            $_SESSION['user_name'] = '';
            $_SESSION['user_email'] = '';
            $_SESSION['status'] ='';
        }
        
		$sql = 'SELECT DISTINCT(user_name) FROM zadachnik';
		$res = $mysqli->query($sql);
		$numRows = mysqli_num_rows($res);
		for($i = 0; $i < $numRows; $i++)
		{
            $row = mysqli_fetch_assoc($res);
            $select_name .= '<option value="'.$row['user_name'].'" '.(($_SESSION['user_name']==$row['user_name'])?" selected":"").'>'.$row['user_name'].'</option>';
        }

        $sql = 'SELECT DISTINCT(user_email) FROM zadachnik';
		$res = $mysqli->query($sql);
		$numRows = mysqli_num_rows($res);
		for($i = 0; $i < $numRows; $i++)
		{
            $row = mysqli_fetch_assoc($res);
            $select_email .= '<option value="'.$row['user_email'].'"'.(($_SESSION['user_email']==$row['user_email'])?" selected":"").'>'.$row['user_email'].'</option>';
        }
?>
    <form name="sort_form" action="index.php" method="post">
    <table class="table table-dark">
        <tr>
            <td>Сортировать:</td>
            <td>Имя:
                    <select class="form-control form-control-sm" name="sort_user_name">
                        <option value="">Все</option>
                        <?=$select_name?>
                    </select>
            </td>
            <td>Email:
                    <select class="form-control form-control-sm" name="sort_user_email">
                        <option value="">Все</option>
                        <?=$select_email?>
                    </select>
            </td>
            <td>Статус:
                    <select class="form-control form-control-sm" name="sort_status">
                        <option value="">Все</option>
                        <option value="1"<?=(($_SESSION['status']=="1")?" selected":"")?>>В работе</option>
                        <option value="2"<?=(($_SESSION['status']=="2")?" selected":"")?>>Закрыта</option>
                    </select>
            </td>
            <td><input type="submit" name="sort_submit" value="Сортировать"><input type="submit" name="sort_clear" value="Очистить"></td>
        </tr>
    </table>
    </form>

<?
//----------Блок задачника
?>

<h3>Задачник</h3>
<button type="button" class="btn btn-primary add_zadachu" data-toggle="modal" data-target="#exampleModal">Добавить задачу</button>	
<?=(($_SESSION['admin']!=true)?'<button type="button" class="btn btn-primary login" data-toggle="modal" data-target="#exampleModal1">Войти</button>':'<a href="index.php?exit"><button type="button" class="btn btn-success exit" >Выйти</button></a>');?>
    <div class="dataTable_wrapper">
			<table class="table table-striped table-bordered table-hover" id="example">
				<thead>
	        <tr>
                <th scope="col">Имя</th>
                <th scope="col">Email</th>
                <th scope="col">Задача</th>
                <th scope="col">Состояние</th>
				<?=(($_SESSION['admin']==true)?'<th scope="col">Действия</th>':'')?>
	        </tr>
				</thead>
        <tbody>
<?

            $sort = '';
            if($_SESSION['user_name']!=''){$sort .= " AND user_name = '".$_SESSION['user_name']."'";}
            if($_SESSION['user_email']!=''){$sort .= " AND user_email = '".$_SESSION['user_email']."'";}
            if($_SESSION['status']!=''){$sort .= " AND status = ".$_SESSION['status']."";}
            if($_SESSION['line_to_page']!='0'){$line_to_page = $_SESSION['line_to_page'];}
            
        if(isset($_GET['page'])){
            $page = $_GET['page'];
        }else{
            $page = 1;
        }

		$sql = 'SELECT * FROM zadachnik
                WHERE id != 0 '.$sort.'';
		$res = $mysqli->query($sql);
		$numRowsPage = mysqli_num_rows($mysqli->query($sql));
        
        
		$sql = 'SELECT * FROM zadachnik
                WHERE id != 0 '.$sort.'
                ORDER BY id DESC
								';
//        echo $sql;
		$res = $mysqli->query($sql);
		$numRows = mysqli_num_rows($res);
		for($i = 0; $i < $numRows; $i++)
		{
			$row = mysqli_fetch_assoc($res);
            echo '<tr>
                        <td scope="col" id="user_name_'.$row['id'].'">'.$row['user_name'].'</td>
                        <td scope="col" id="user_email_'.$row['id'].'">'.$row['user_email'].'</td>
                        <td scope="col" id="text_'.$row['id'].'">'.$row['text'].'</td>
                        <td scope="col">'.(($row['status']=="1")?"<span class='badge badge-primary'>В работе</span>":"<span class='badge badge-success'>Закрыта</span>").'</td>
						'.(($_SESSION['admin']==true)?'
						   <td scope="col">
							<button type="button" class="btn btn-outline-success btn-sm edit" data-toggle="modal" data-target="#exampleModal" ids="'.$row['id'].'">Редактировать</button>
							<a href="index.php?del='.$row['id'].'" onclick="return confirm(\'Вы уверены что хотите удалить поездку?\');">
								<button type="button" class="btn btn-outline-danger btn-sm delit">Удалить</button>
							</a>
							<input id="status_'.$row['id'].'" type="hidden" value="'.$row['status'].'">
							</td>
						':'').'
                </tr>';
        }

?>
			</wtbody>
    </table>
	</div>

<!-- Добавление задачи -->
<form action="index.php" method="post" class="needs-validation" novalidate>
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Добавить задачу</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
			<table>
				<tr>
					<td>Имя</td>
					<td><input class="form-control" id="user_name" name="user_name" required></td>
				</tr>
				<tr>
					<td>Emai</td>
					<td><input class="form-control" id="user_email" name="user_email" required></td>
				</tr>
				<tr>
					<td>Задача</td>
					<td><textarea class="form-control" id="text" name="text" required></textarea></td>
				</tr>
<?=(($_SESSION['admin']==true)?'<tr>
					<td>Статус</td>
					<td>
					    <select class="form-control form-control-sm" name="status" id="status">
							<option value="1">В работе</option>
							<option value="2">Закрыта</option>
						</select>
					</td>
				</tr>
':'');
?>
			</table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
        <button type="submit" class="btn btn-primary" id="add_zadachu" name="add_zadachu">Добавить задачу</button>
        <button type="submit" class="btn btn-primary" id="edit_zadachu" name="edit_zadachu">Сохранить задачу</button>
      </div>
    </div>
  </div>
</div>
<input type="hidden" id="id" name="id" value="">
</form>


<!-- Вход админа -->
<form action="index.php" method="post">
<div class="modal fade" id="exampleModal1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Войти как администратор</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">		
			<table>
				<tr>
					<td>Логин</td>
					<td><input class="form-control" name="admin_email"></td>
				</tr>
				<tr>
					<td>Пароль</td>
					<td><input type="password" class="form-control" name="admin_password"></td>
				</tr>
			</table>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
        <button type="submit" class="btn btn-primary" name="admin">Вход</button>
      </div>
    </div>
  </div>
</div></form>

    
  </body>
</html>
