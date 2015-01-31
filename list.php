<center>

    <br>

    <img src='imgs/manager-logo.png' width='250'>

    <br>

    <br>

    <div id='main'> 

        <div id='scrollable'>

            <?php
            
				// Select all Terminal 2 locations from database
			
            	$query = "SELECT * FROM sfo_explorer WHERE terminal = 'Terminal 2' OR location LIKE '%Terminal 2%' ORDER BY name ASC";

            	$result = mysql_query($query);

            	while ($sfo_events = mysql_fetch_assoc($result)) {
                
                	// Output list of locations
            		echo "<div id='row-".$sfo_events['id']."' onclick='window.location = \"index.php?edit=" . $sfo_events['id'] . "\"' style='cursor:pointer; opacity:1;background-color:#238ecc; width:250px; height:50px; padding-top:20px; padding-left:10px; padding-right:10px; fonr-size:22px;'>" . ($sfo_events['name']) . "<br><span style='font-size:11px;color:#dddddd;'>" . $sfo_events['location'] . "</span></div><br>";
            
            	}

            ?>

        </div>

        <div id="spacer"></div>

    </div>

</center>
