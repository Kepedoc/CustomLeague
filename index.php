<?php
session_start();
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="icon" type="image/x-icon" href="favicon.png">
    <link href="https://fonts.cdnfonts.com/css/planet-kosmos" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Orbitron' rel='stylesheet'>
    <title>Custom League</title>
    <script src="myscripts.js"></script>
</head>
<?php
$conn = new mysqli('localhost', 'root', '', 'loc'); // łączenie z bazą danych
if ($conn->connect_error) {
    die('Błąd połączenia: ' . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') { // dodawanie użytkownika
    if (isset($_POST['nickname'])) {
        $data = htmlspecialchars($_POST['nickname']); 
        $sql = "INSERT INTO user(NICK, WIN) VALUES ('$data', 0)";
        $sql2 = "SELECT * FROM user where NICK='$data'";
        $result = mysqli_query($conn, $sql2);
        if ($result->num_rows == 0){
            mysqli_query($conn, $sql);
        }
    }
}

$sql3 = "SELECT NICK FROM user LIMIT 10"; // wypisywanie niewylosowanych
$result = mysqli_query($conn, $sql3);
$nicki = array('','','','','','','','','','','');
if ($result->num_rows > 0) {
    $index = 1; 
    while($row = $result->fetch_assoc()) {
        $nicki[$index] = $row['NICK'];
        $index++;
    }
}

if (isset($_POST['losuj'])){ // tworzenie tablicy z randomowymi nickami i wstawienie ich do bazy
    $losoweNicki = array('','','','','','','','','','','');
    foreach($nicki as $x){
        $randIndex = rand(1,10);
        $powtuz = true;
        while($powtuz){
            if($losoweNicki[$randIndex] == ''){
                $losoweNicki[$randIndex] = $x;
                $powtuz = false;
            }else{
                $randIndex++;
                if($randIndex > 10){
                    $randIndex = 1;
                }
            }
        }
    }
    $sql1 = "DELETE FROM random_nicks";
    $result = mysqli_query($conn,$sql1);
    for($i = 1; $i <= 10; $i++){
        $sql2 = "INSERT INTO random_nicks(POSITION, NICK) VALUES ($i,'$losoweNicki[$i]')";
        $result = mysqli_query($conn,$sql2);
    }
}

$wins = array('','','','','','','','','','','');
$gamesPlayed = array('','','','','','','','','','','');
$winRateArr = array('','','','','','','','','','','');
$winRateStr = array('','','','','','','','','','','');
// $funArg = array('','','','','','','','','','','');
$sqlGetWinrate = "SELECT user.NICK, wins.nick, wins.win, wins.games FROM `user` JOIN `wins` on user.NICK = wins.nick;";
$resultGetWinrate = mysqli_query($conn,$sqlGetWinrate);
$indexWinrate = 1;
while($row = $resultGetWinrate->fetch_assoc()){
    $gamesPlayed[$indexWinrate] = $row['games'];
    $wins[$indexWinrate] = $row['win'];
    $winRateArr[$indexWinrate] = $wins[$indexWinrate]/$gamesPlayed[$indexWinrate]*100;
    $winRateStr[$indexWinrate] = "Winrate: ".$winRateArr[$indexWinrate]."%";
    // $funArg[$indexWinrate] = "winrate".$indexWinrate;
    $indexWinrate++;
}



$losoweNicki = array('','','','','','','','','','',''); 
$sqlGetRandomNicks = "SELECT POSITION,NICK FROM `random_nicks`;";
$resultGetRandomNicks = mysqli_query($conn,$sqlGetRandomNicks);
while($row = $resultGetRandomNicks->fetch_assoc()){
    $losoweNicki[$row['POSITION']] = $row['NICK'];
}
?>
<body>
<form id="form" method="POST" action="index.php">
    <div class="menu">
        <div class="menu-item-empty"></div>
        <input type="text" name="nickname" style="border-radius: 0px 0 0 25px; border-right: 0; color: #C8AA6E;" placeholder="Nick z lola" class="menu-item" required>
        <button type="submit" value="Ready" class="menu-item-ready">Ready</button>
        <?php 
            if (isset($_GET['wylosowne'])){
                echo "<a href='index.php' class='menu-item-random'>Powrót</a>";
            }else{
                echo "<a href='index.php?wylosowne=TRUE' class='menu-item-random'>Pokaż wylosowane</a>";
            }
        ?>
        <div class="menu-item-empty"></div>
    </div>
    </form>
    <div class="content">
        <div id="left" class="container">
            <?php
                if (isset($_GET['wylosowne'])){
                    echo "
                <div class='icon'>$losoweNicki[1]</div>
                <div class='icon'>$losoweNicki[2]</div>
                <div class='icon'>$losoweNicki[3]</div>
                <div class='icon'>$losoweNicki[4]</div>
                <div class='icon'>$losoweNicki[5]</div>";
                }else{
                    echo "
                <div id='winrate1' onclick='winrate1()' data-winrate='$winRateStr[1]' data-nick='$nicki[1]' class='icon'>$nicki[1]</div>
                <div id='winrate2' onclick='winrate2()' data-winrate='$winRateStr[2]' data-nick='$nicki[2]' class='icon'>$nicki[2]</div> 
                <div id='winrate3' onclick='winrate3()' data-winrate='$winRateStr[3]' data-nick='$nicki[3]' class='icon'>$nicki[3]</div>
                <div id='winrate4' onclick='winrate4()' data-winrate='$winRateStr[4]' data-nick='$nicki[4]' class='icon'>$nicki[4]</div>
                <div id='winrate5' onclick='winrate5()' data-winrate='$winRateStr[5]' data-nick='$nicki[5]' class='icon'>$nicki[5]</div>";
                }
                ?>
        </div>
        <div class="container">
            <div class="league-logo">
                <a href="index.php"><img src="logoBlue5.png" alt="logo"></a>
            </div>
            <div class="discord-link-container">
            <a href="https://discord.gg/Gyr6DQBq3u" target="_blank"><img class="discord" src="discord2.1.png" alt="Discord-Link"></a>
                <!-- <button class="discord-link"><a href="https://discord.gg/Gyr6DQBq3u">Discord Link</a></button> -->
            </div>
        </div>
        <div id="right" class="container">
        <?php
                if (isset($_GET['wylosowne'])){
                    echo "
                <div class='icon'>$losoweNicki[6]</div>
                <div class='icon'>$losoweNicki[7]</div>
                <div class='icon'>$losoweNicki[8]</div>
                <div class='icon'>$losoweNicki[9]</div>
                <div class='icon'>$losoweNicki[10]</div>";
                }else{
                    echo "
                <div id='winrate6' onclick='winrate6()' data-winrate='$winRateStr[6]' data-nick='$nicki[6]' class='icon'>$nicki[6]</div>
                <div id='winrate7' onclick='winrate7()' data-winrate='$winRateStr[7]' data-nick='$nicki[7]' class='icon'>$nicki[7]</div>
                <div id='winrate8' onclick='winrate8()' data-winrate='$winRateStr[8]' data-nick='$nicki[8]' class='icon'>$nicki[8]</div>
                <div id='winrate9' onclick='winrate9()' data-winrate='$winRateStr[9]' data-nick='$nicki[9]' class='icon'>$nicki[9]</div>
                <div id='winrate10' onclick='winrate10()' data-winrate='$winRateStr[10]' data-nick='$nicki[10]' class='icon'>$nicki[10]</div>";
                }
                ?>
        </div>
    </div>

</body>
</html>
<?php
$conn->close();
?>