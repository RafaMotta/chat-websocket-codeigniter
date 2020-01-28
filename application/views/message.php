<html>
	<head>
		<title>Websocket</title>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	</head>
	<body>
		<style>
			h3{
				font-family: Verdana;
				font-size: 18pt;
				font-style: normal;
				font-weight: bold;
				color:red;
				text-align: center;
			}

		</style>
		<h3></h3>

		<?php echo form_open('message/send', array('name' => 'message', 'method' => 'post')); ?>

		<div class="container">
			<div style="float:right;"><p><h4>Mensagens: <b><span id="msgcount"></span></b></h4></p></div>
			<div class="col-md-3">
				<p><input type="text" placeholder="Texto" class="form-control" size="20px" id="message" name="message" /></p>
			</div>
			<div class="col-md-3"><input type="button"class="btn btn-primary"id="send" name="send" value="Enviar"/></div>
			<div class="col-md-3"></div>
			<div class="col-md-3"></div>
			<table class="table">
				<thead>
					<tr>
						<th>Mensagem</th>
						<th>Data</th>
					</tr>
				</thead>
				<tbody id="message-tbody">
					<?php
					foreach ($allMsgs as $row) {
						?>
						<tr>
							<td>
								<?php
								echo $row['msg'];
								?>
							</td>
							<td>
								<?php echo date("d/m/Y", strtotime(str_replace('-', '/', $row['date']))); ?>
							</td>
							
						</tr>
						<?php
					}
					?>
				</tbody>
			</table>
		</div>

		<?php echo form_close(); ?>

		<script src="<?php echo base_url('node_modules/socket.io-client/dist/socket.io.js'); ?>"></script>
		<script>
			$(document).ready(function() {
				$(document).on("click", "#send", function() {
					var dataString = {
						message: $("#message").val()
					};

					$.ajax({
						type: "POST",
						url: "<?php echo base_url(); ?>message/send",
						data: dataString,
						dataType: "json",
						cache: false,
						success: function(data) {
							if (data.success == true) {
								var socket = io.connect('http://' + window.location.hostname + ':3000');
								socket.emit('new_message', {
									message: data.message,
									date: data.date,
									msgcount: data.msgcount
								});
							}
						}, error: function(xhr, status, error) {
							alert(error);
						},
					});
				});
			});
			
			var socket = io.connect('http://' + window.location.hostname + ':3000');
			socket.on('new_message', function(data) {
				$("#message-tbody").prepend('<tr><td>' + data.message + '</td><td>' + data.date + '</td></tr>');
				$("#msgcount").text(data.msgcount);
			});
		</script>
	</body>
</html>
