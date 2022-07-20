<html xmlns="http://www.w3.org/1999/xhtml"
    xml:lang="cs"
    lang="cs"
    dir="ltr">
<head>
  <link href='https://fonts.googleapis.com/css?family=Audiowide' rel='stylesheet'>
	<link rel="stylesheet" href="styl.css" type="text/css" />
	<title>Vyhláška 50 / 1978 Sb.</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>
<header>
		<div id="logo">
				<la>Vyhláška 50/1978 Sb.</la>
				<lb>SOŠ a SOU Lanškroun</lb>
		</div>
		<nav>
            <ul>
                <li>
                    <?php
                        echo " Připojen z IP: ".$_SERVER["REMOTE_ADDR"];
                    ?>
                </li>
            </ul>
        </nav>
        <div id="pravy">
            <?
                echo "Spuštěno: ".date("H:i - d.m.Y");
                
            ?>
		</div>
</header>
<center>
<article align="left">
<br><br><br>
<p align="left">
<form action="vysledky.php" method=GET>
<?
  define("OtazkyMAX",124);
  $pocet_otazek=60;
  echo "<FIELDSET><LEGEND><b><font color=\"blue\">Jméno a příjmení</font></b></LEGEND>\n";
  echo " Jméno:&nbsp; <input type=\"text\" name=\"name\" required minlength=\"3\" maxlength=\"20\" size=\"20\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\n";
  echo " Příjmení:&nbsp; <input type=\"text\" name=\"name1\" required minlength=\"3\" maxlength=\"20\" size=\"20\">\n";	
  echo "</FIELDSET><BR>\n";
	for ($i=0; $i<OtazkyMAX; $i++) {
        $otazky[$i]=$i+1;
    }
    
    for ($i=0; $i<(OtazkyMAX-1); $i++){
        $nindex = mt_rand(0, (OtazkyMAX-1));
        $swap = $otazky[$nindex];
        $otazky[$nindex] = $otazky[$i];
        $otazky[$i] = $swap;
    }
    
   	$spojeni = new mysqli("localhost", "vyhlaska", "0wxU6t[!70l45uGr", "vyhlaska"); 
    //server, uživatel, heslo, databaze
			
        if ($spojeni->connect_errno) {
		    echo ("Connect failed: ".$mysqli->connect_error."<br>\n");
            exit();
        }

        mysqli_set_charset($spojeni,"utf8"); //Volba znakove sady

        //Generovani nahodne posloupnosti otazek pomoci pole nahodnych cisel otazky
        for ($i=0; $i<$pocet_otazek; $i++) {
            $d_zadani = "SELECT * FROM otazky WHERE ID=$otazky[$i]";
            $d_odpoved = "SELECT * FROM odpovedi WHERE ID=$otazky[$i]";
            $vysledek = $spojeni->query($d_zadani);
			      $vypis = mysqli_fetch_assoc($vysledek);
            echo "<FIELDSET><LEGEND><b><font color=\"blue\">".$vypis["Zadani"]."</font></b></LEGEND>\n";
            //echo $vypis["ID"]." ".$vypis["Zadani"]."<br>\n";
            if ($vypis["Image"]=="1") echo "<img src=\"./images/img".$otazky[$i].".png\" align=\"right\">";
            $vysledek->free();
            $vysledek = $spojeni->query($d_odpoved);
            $vypis = mysqli_fetch_assoc($vysledek);
            
            //Generovani nahodne posloupnosti odpovedi
            for ($n=0; $n<3; $n++) {
                    $odpovedi[$n]=$n+1;
            }   
    
            for ($n=0; $n<2; $n++) {
                $nindex = mt_rand(0, 2);
                $swap = $odpovedi[$nindex];
                $odpovedi[$nindex] = $odpovedi[$n];
                $odpovedi[$n] = $swap;
            }
            $value=0;     	        
            for ($n=0;$n<3;$n++) {
                if($odpovedi[$n] == "1") $value=1; else $value=0; 
                $ret="odpoved".$odpovedi[$n];
                if ($vypis["Image"] == "0") {
                    echo "<input type=radio name=\"Odpoved".$i."\" value=\"".$value.".".$vypis["ID"]."\">".$vypis["$ret"]."<br>\n";					
				} else {
                    if ($n==0) echo "<table>\n";
                    echo "<tr><td><input type=radio name=\"Odpoved".$i."\" value=\"".$value.".".$vypis["ID"]."\"></td>";
					echo "<td><img src=\"./images/".$vypis["$ret"]."\"></td></tr><br>\n";
                    if ($n==2) echo "</table><br>\n";
				}
            }
            echo "</FIELDSET><BR>\n";
        }
    $spojeni->close();
	//echo "<table><tr><td><input type=checkbox name=\"pocet\" value=\"".$pocet_otazek."\" checked style=\"display:none;\"><td></table><br>\n"; 
    echo "<table><tr><td><input type=checkbox name=\"cas\" value=\"".date("H:i")."\" checked style=\"display:none;\"><td></table><br>\n";
?>
</p>
<DIV class=buttonek align="center">
	<BUTTON type=SUBMIT class="button button2">ODESLAT</BUTTON>
</DIV>
</form>
<br>
</article>
<footer align="center"><br><br>Frontend and backend &copy;Josef Němec 2020</footer>
</center>
</body>
</html>