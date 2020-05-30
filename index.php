<!DOCTYPE html>
<html lang="en-US">

<head>
	<title>ChessEdge</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<!-- Chessboard stylesheet -->
	<link rel="stylesheet" href="css/chessboard-1.0.0.min.css">

	<!-- My stylesheet -->
	<link rel="stylesheet" href="css/stylesheet.css">

	<!-- Chessboard script-->
	<script src="/js/chessboard-1.0.0.min.js"></script>

	<!-- JQuery script-->
	<script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>

	<!-- Chess.js -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/chess.js/0.10.2/chess.js"></script>

	<!-- Font -->
	<link href='https://fonts.googleapis.com/css?family=Armata' rel='stylesheet'>

	<style>
	body {
		font-family: 'Armata';
	}
</style>
</head>

<body>
	<!--Navbar-->
	<div class="topnav">
		<a class="unselectable active-nav left-nav" href="index.php">Home</a>
		<a onclick="document.getElementById('id01').style.display='block'" class="unselectable right-nav">Login</a>
		<a class="unselectable left-nav" href="#">Link</a>
	</div>
	<p id="demo">Title</p>

	<!-- Tabs -->
	<div class="tab-group">
		<!-- Buttons -->
		<button id="tab1" class="unselectable tabbutton" onclick="tab1()">Board</button>
		<button id="tab2" class="unselectable tabbutton" onclick="tab2()">Stream</button>
	</div>
	
	<!-- Main window -->
	<div id="main-window">
	<div id="Chessbomb">
	<iframe src="https://www.chessbomb.com/arena/2020-lindores-abbey-rapid-challenge-finals/32-Nakamura_Hikaru-Carlsen_Magnus?layout=e1&theme=bsdefault&static=0" scrolling="no" frameborder="0" style="width:728px; height:728px; border:none; overflow:visible;"></iframe>
	</div>
		<!-- Chessboard
		<div id="boardContainer">
			<div class="unselectable" id="clockBlack">10:00</div>
			<div class="unselectable" id="nameBlack">Magnus Carlsen</div>
			<div class="unselectable" id="evalBar"></div>
			<div class="unselectable" id="myBoard" style="width: 400px"></div>
			<div class="unselectable" id="nameWhite">Dave McGhee</div>
			<div class="unselectable" id="clockWhite">10:00</div>
		</div>
		-->
		<div id="stream">
		<iframe
			id="stream2"
			src="https://player.twitch.tv/?channel=chess24&parent=streamernews.example.com&muted=true"
			frameborder="0"
			scrolling="no"
			allowfullscreen="true">
		</iframe>
		</div>
	</div>
	
	<!-- Betting container on the right -->
	<div id="betContainer">
	 <?php
 
	$servername = "localhost";
	$username = "root";
	$password = "";

	try {
	  $conn = new PDO("mysql:host=$servername;dbname=ChessEdge", $username, $password);
	  // set the PDO error mode to exception
	  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	  //sql code goes here
	  $stmt = $conn->prepare("SELECT * FROM Bets WHERE InPlay = 1");
	  $stmt->execute();
	  //Filling an array with the results
	  while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
		  $arr[] = $row;
	  }
	  if(!$arr) exit('No rows');
	  // Iterating through the bets
	  foreach ($arr as $value) {
	  //Get the number of options available
	    $numberOfOptions = substr_count($value[2],",") + 1;
	  //Set the correct class for enabled bets
		if ($value[4] == 1) {
			$Enabled = "";
			$textEnabled = "";
		}
		else {
			$Enabled = "betDisabled";
			$textEnabled = "disabled";
		}
		// Echo the bets
		echo '<div id="'.$value[0].'" class = "BetContainer"><div class="unselectable BetText '.$Enabled.'" onmousedown="BetEnlarge('.$value[0].','.$value[0].'Opacity,'.$numberOfOptions.')">'.$value[1].'<br></div><div class="betOpacityDiv" id="'.$value[0].'Opacity">';
		$token = strtok($value[2], ",");
		$x = 1;
		//Echo the options
		while ($token !== false) {
			echo '<div class="BetOption">'.$x.'. '.$token.'</div><div class="unselectable negativeBetButton '.$Enabled.'" id="'.$value[0].$x.'negative" oncmousedown="betAdd('.$value[0].$x.'text)">-</div><input type="text" placeholder="0.00" pattern="[0-9]{}-[0-9]{2}-[0-9]{3}" class="resizedTextbox" id="'.$value[0].$x.'text" '.$textEnabled.'/><div class="unselectable positiveBetButton '.$Enabled.'" onclick="BetAdd('.$value[0].$x.'text)" id="'.$value[0].$x.'positive">+</div>';
			$x++;
		$token = strtok(",");
		}
		echo '</div></div>';
	  }
	  $stmt = null;
	} catch(PDOException $e) {
	  echo "Connection failed: " . $e->getMessage();
	}

	$conn = null;
	?> 
	</div>

	<!-- The Modal -->
	<div id="id01" class="modal">

		<!-- Modal Content -->
		<form class="modal-content animate" action="/action_page.php">
			<span onclick="document.getElementById('id01').style.display='none'"
			  class="close" title="Close Modal">&times;</span>
			<div>
				<input type="text" class="modalText" placeholder="Username" name="uname" required>
			</div>
			<div>
				<input type="password" class="modalText" placeholder="Password" name="psw" required>
			</div>
			<div>
				Remember me<input type="checkbox" checked="checked" name="remember">
			</div>
			<div style="flex-grow: 2">
				<input type="submit" value="Login"></input>
			</div>

		</form>
	</div>

	<!-- Scripts ---------------------------->

	<!-- Chessboard ---------------------------->
	<!--
	<script src="js/chessboard-1.0.0.min.js"></script>
	<script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
	<script>
	// Make board in starting position
	var board = Chessboard('myBoard', 'start');
	// Add chessjs object
	const game = new Chess();
	// Define and load the pgn
	var pgn = '1.e4 e5 2.Nf3 Nf6 3.Nc3 d5 4.exd5 Nxd5 5.Bc4 Nf4 6.O-O e4 7.Re1 Kd7 8.Rxe4 Qg5 9.Nxg5 f6 10.Qg4+ Ne6';
	game.load_pgn(pgn);
	// Move to latest Move
	board.position(game.fen());
	// Get the game history in a nice array  
	var gameHistory = game.history({verbose: true});
	// Define the board to change the colours
	var $board = $('#myBoard');
	// Take the squares from the pgn
	var squareToHighlight1 = gameHistory[gameHistory.length-1].from;
	var squareToHighlight2 = gameHistory[gameHistory.length-1].to;
	// Highlight the square
	$board.find('.square-' + squareToHighlight1).addClass('highlight-black');
	$board.find('.square-' + squareToHighlight2).addClass('highlight-black');

	// stockfish ----------------------------
	  var lozza = new Worker('lozza.js');

	lozza.onmessage = function (e) {
		$('#demo').append(e.data);      //assuming jquery and a div called #dump
	};

	lozza.postMessage('uci');         // get build etc
	lozza.postMessage('ucinewgame');  // reset TT
	lozza.postMessage('position startpos');
	lozza.postMessage('go depth 10'); // 10 ply search
	</script>
	-->
	

	<!-- Modal ---------------------------->
	<script>
	// Get the modal
	var modal = document.getElementById('id01');

	// When the user clicks anywhere outside of the modal, close it
	window.onclick = function(event) {
	if (event.target == modal) {
		modal.style.display = "none";
	}
}	</script>

	<!-- Show tab 2 ---------------------------->
	<script>
	function tab2(){
		//document.getElementById("boardContainer").style.display = "none";
		document.getElementById("Chessbomb").style.display = "none";
		document.getElementById("stream2").style.display = "block";
		document.getElementById("tab1").style.backgroundColor = "#393e46";
		document.getElementById("tab2").style.backgroundColor = "#00adb5";
	}
	</script>

	<!-- Show tab 1 ---------------------------->
	<script>
	function tab1(){
		//document.getElementById("boardContainer").style.display = "block";
		document.getElementById("Chessbomb").style.display = "block";
		document.getElementById("stream2").style.display = "none";
		document.getElementById("tab1").style.backgroundColor = "#00adb5";
		document.getElementById("tab2").style.backgroundColor = "#393e46";
	}
	</script>
	<script>
	function BetEnlarge(identity1, identity2, options){
		alert("Hello! I am an alert box!");
		$blockHeight = 75 + (38*options) + "px";
		if (document.getElementById(identity1).style.height != $blockHeight) {
		document.getElementById(identity1).style.height = $blockHeight;
		document.getElementById(identity2).style.opacity = "1";
		}
		else if (document.getElementById(identity1).style.height = $blockHeight) {
		document.getElementById(identity1).style.height = "75px";
		document.getElementById(identity2).style.opacity = "0";
		}
	}
	</script>
	<script>
	function betAdd(hello) {
		alert("Hello! I am an alert box!");
	}
	</script>
</body>
</html>