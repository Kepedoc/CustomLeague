<?php
session_start();
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style.css">
    <link rel="icon" type="image/x-icon" href="../favicon.png">
    <link href="https://fonts.cdnfonts.com/css/planet-kosmos" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Orbitron' rel='stylesheet'>
    <title>Custom League</title>
</head>
<?php

$config = require '../conf.php';
$conn = new mysqli(
    $config['host'],
    $config['username'],
    $config['password'],
    $config['database']
); // łączenie z bazą danych

if ($conn->connect_error) {
    die('Błąd połączenia: ' . $conn->connect_error);
}
    $sql3 = "SELECT NICK FROM random_nicks";
    $result = mysqli_query($conn, $sql3);
    $nicki = array();
    $nicki = array_fill(1,10,'');
    if ($result->num_rows > 0) {
        $index = 1; // Indeks do przypisania danych do odpowiednich divów
        while($row = $result->fetch_assoc()) {
            $nicki[$index] = $row['NICK'];
            $index++;
        }
    }

    if (isset($_GET['drop'])){
        $drop1 = "DELETE FROM user;";
        $drop2 = "DELETE FROM random_nicks;";
        mysqli_query($conn, $drop1);
        mysqli_query($conn, $drop2);
    }

    if (isset($_GET['nick'])){
        $nick=$_GET['nick'];
        $sql5 = "DELETE FROM user WHERE NICK='$nick'";
        mysqli_query($conn, $sql5);
    }

if (isset($_GET['victory1'])){ //wygrana drużyna 1 otrzymuje pukt wina i gry w bazie danych a pozostali otrzymują punkt gry
    $sql6 = "SELECT NICK FROM random_nicks WHERE POSITION<=10";
    $Vresult = mysqli_query($conn, $sql6);
    $Vnicki = array();
    $Vnicki = array_fill(1,10,'');
    if ($Vresult->num_rows > 0) {
        $index = 1;
        while($Vrow = $Vresult->fetch_assoc()) {
            $Vnicki[$index] = $Vrow['NICK'];
            $sql9 = "SELECT nick from wins WHERE nick='$Vnicki[$index]'";
            $Vresult2 = mysqli_query($conn, $sql9);
            $Vrow1 = $Vresult2->fetch_assoc();
            if ($Vrow1 != NULL){
                $czyJest = $Vrow1['nick'];
            }else{  
                $sql10 = "INSERT INTO wins(nick) VALUES ('$Vnicki[$index]')";
                mysqli_query($conn, $sql10);
            }
            if($index <= 5) {
            $sql7 = "UPDATE wins SET win = win + 1 where nick='$Vnicki[$index]'";
            $sql8 = "UPDATE wins SET games = games + 1 where nick='$Vnicki[$index]'";
            mysqli_query($conn, $sql7);
            mysqli_query($conn, $sql8);
            }
            if($index >=6) {
            $sql16 = "UPDATE wins SET games = games + 1 where nick='$Vnicki[$index]'";
            mysqli_query($conn, $sql16);
            }
            $index++;
        }
    }
}



if (isset($_GET['victory2'])){  //wygrana drużyna 2 otrzymuje pukt wina i gry w bazie danych a pozostali otrzymują punkt gry
    $sql11 = "SELECT NICK FROM random_nicks WHERE POSITION<=10";
    $Vresult3 = mysqli_query($conn, $sql11);
    $Vnicki2 = array();
    $Vnicki2 = array_fill(1,10,'');
    if ($Vresult3->num_rows > 0) {
        $index = 1;
        while($Vrow = $Vresult3->fetch_assoc()) {
            $Vnicki2[$index] = $Vrow['NICK'];
            $sql12 = "SELECT nick from wins WHERE nick='$Vnicki2[$index]'";
            $Vresult2 = mysqli_query($conn, $sql12);
            $Vrow1 = $Vresult2->fetch_assoc();
            if ($Vrow1 != NULL){
                $czyJest = $Vrow1['nick'];
            }else{  
                $sql13 = "INSERT INTO wins(nick) VALUES ('$Vnicki2[$index]')";
                mysqli_query($conn, $sql13);
            }
            if($index >= 6) {
            $sql17 = "UPDATE wins SET win = win + 1 where nick='$Vnicki2[$index]'";
            $sql18 = "UPDATE wins SET games = games + 1 where nick='$Vnicki2[$index]'";
            mysqli_query($conn, $sql17);
            mysqli_query($conn, $sql18);
            }
            if($index <= 5){
            $sql19 = "UPDATE wins SET games = games + 1 where nick='$Vnicki2[$index]'";
            mysqli_query($conn, $sql19);
            }
            $index++;
        }
    }
}

    

?>
<body>
    <div class="menu">
        <div class="menu-item-empty"></div>
        <a href="../index.php" ><button type="submit" style="border-radius: 0px 0 0 25px; border-right: 0; color: #C8AA6E;" class="menu-item">Index</button></a>
        <form action="../index.php" method="POST"><button type="submit" name="losuj" value="true" class="menu-item-ready">Losuj</button></form>
        <a href="admin.php?drop=true" ><button type="submit" style="border-radius: 0px 0 25px 0; border-left: 0;" class="menu-item-random">DROP</button></a>
        <div class="menu-item-empty"></div>
    </div>
    <div class="mobileLogo">
        <a href="../index.php"><img src="../logoBlue5.png" alt="logo"></a>
    </div>
    <div class="content">
        <div id="left" class="container">
            <div class="icon2"><?php echo "<a href='admin.php?victory1=true'>Victory!</a></div>";?>
            <div class="icon"><?php echo "$nicki[1]"; echo "<a href='admin.php?nick=$nicki[1]'><button>del</button></a></div>";?>
            <div class="icon"><?php echo "$nicki[2]"; echo "<a href='admin.php?nick=$nicki[2]'><button>del</button></a></div>";?>
            <div class="icon"><?php echo "$nicki[3]"; echo "<a href='admin.php?nick=$nicki[3]'><button>del</button></a></div>";?>
            <div class="icon"><?php echo "$nicki[4]"; echo "<a href='admin.php?nick=$nicki[4]'><button>del</button></a></div>";?>
            <div class="icon"><?php echo "$nicki[5]"; echo "<a href='admin.php?nick=$nicki[5]'><button>del</button></a></div>";?>
        </div>
        <div class="container">
            <div class="league-logo">
                <a href="admin.php"><img src="../logoBlue5.png" alt="logo"></a>
            </div>
            <div class="discord-link-container">
                <a href="https://discord.gg/Gyr6DQBq3u" target="_blank"><img class="discord" src="../discord2.1.png"></a>
            </div>
        </div>
        <div id="right" class="container">
            <div class="icon2"><?php echo "<a href='admin.php?victory2=true'>Victory!</a></div>";?>
            <div class="icon2"><?php echo "$nicki[6]"; echo "<a href='admin.php?nick=$nicki[6]'><button>del</button></a></div>";?>
            <div class="icon2"><?php echo "$nicki[7]"; echo "<a href='admin.php?nick=$nicki[7]'><button>del</button></a></div>";?>
            <div class="icon2"><?php echo "$nicki[8]"; echo "<a href='admin.php?nick=$nicki[8]'><button>del</button></a></div>";?>
            <div class="icon2"><?php echo "$nicki[9]"; echo "<a href='admin.php?nick=$nicki[9]'><button>del</button></a></div>";?>
            <div class="icon2"><?php echo "$nicki[10]"; echo "<a href='admin.php?nick=$nicki[10]'><button>del</button></a></div>";?>
        </div>
        <div class="mobileDiscord">
            <a href="https://discord.gg/Gyr6DQBq3u" target="_blank"><img class="discord" src="../discord2.1.png" alt="Discord-Link"></a>
            </div>
    </div>  
    <script src="../script.js"></script>
</body>
</html>


<?php
$conn->close();
?>