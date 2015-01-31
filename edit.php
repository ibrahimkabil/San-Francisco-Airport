<!-- initiate JavaScript functions -->

<script src="js/edit-functions.js"></script>

<center>

    <br>
    
    <a href='index.php'><img src='imgs/manager-logo.png' width='250'></a>
    
    <div id="main">
        
        <h2 class='shadow' id='venue_name'><?php echo $venue['name']; ?></h2>
        
        <h4 class='shadow' id='location'><?php echo $venue['location']; ?></h4>
	
        <h3 id='primary'><span class='shadow'>Primary Photo</span></h3>

        <?php echo $active_photo; ?>

        <br>
        
        <h3 id='description_header'>
        	<span class='shadow'>Description</span>
        </h3>
        
        <br>
        
        <form name='save' id='save' method='POST' action="?edit=<?php echo $_GET['edit']; ?>&save=1">
            
            <div id='description_textarea'>
            
                <textarea id="description" name="description"><?php if (isset($_POST['photo_description'])) { echo $_POST['photo_description']; } else { echo $venue['description']; } ?></textarea>
            
            </div>
                    
            <br>
            
            <h3 id='yelp_header'>
            
            	<span class='shadow'>Yelp Rating</span>
            
            </h3>
            
            <br>
            
            <span id='yelp_url_text' class='shadow'>Enter Yelp URL to show rating:</span><br>

            <div id='yelp_box'>
                
            <input type="text" id="yelp_url" name="yelp_url" value="<?php if (isset($_POST['yelp_url'])) { echo $_POST['yelp_url']; } else { echo $venue['yelp_url']; } ?>"></div>
                
            <div id='yelp_rating'></div>
            
            <br>
            
            <h3 id='foursquare_header'>
            
            	<span class='shadow'>Foursquare Photos</span>
            
            </h3>
            
            <br>
            
            	<span id='foursquare_span' class='shadow'>Enter Foursquare  URL to manage photos:</span>
            	
            <br>

            <div id="foursquare_box">
                
            <input type="text" name="foursquare_url" id="foursquare_url" value="<?php if (isset($_POST['foursquare_url'])) { echo $_POST['foursquare_url']; } else { echo $venue['foursquare_url']; } ?>"></div>
                    
            <center><div style='width:95%;' id='foursquare_imgs'></div></center>
                                
            <br clear='all'>
            
            <input type='hidden' name='id' value='<?php echo $_GET['edit']; ?>'>
            
            <br>
            
            <div onclick='save_changes()' id="save_changes" class='shadow'>Save Changes</div>
            
        </form>

        <form name='photo_form' id='photo_form' enctype='multipart/form-data' method='post'><input type='hidden' id='photo_description' name='photo_description' value="">
        
            <input type='hidden' name='id' value="<?php echo $_GET['edit']; ?>">
        
            <input onchange='photoSubmit()' type="file" accept="image/*" capture="camera" style='display:none;' id='photo' name='photo'>
        
        </form>

    </div>

</center>
