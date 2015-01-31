<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // if the method is POST (after photo submission), retreive the "ID" and assign it as "edit" var
    $_GET['edit'] = $_POST['id'];

}

?>

<link rel='stylesheet' type='text/css' href='css/add.css'>

<script>


    $(function() {

        $("#foursquare_url").change(function() {

            updateFoursquare();

        });

        $("#yelp_url").change(function() {

            updateYelp();

        });


        function updateFoursquare() {

            $("#foursquare_imgs").html('');

            var venue = $('#foursquare_url').val();

            if (venue != '') {

                venue = venue.substring(venue.lastIndexOf('/') + 1);

                console.log(venue);

                $.ajax({url: "foursquare_photos.php?venue=" + venue, success: function(result) {
                        console.log(result);
                        $("#foursquare_imgs").html(result);
                    }});
            }
        }

        function updateYelp() {

            $("#yelp_rating").html('');

            var venue = $('#yelp_url').val();

            if (venue != '') {

                venue = venue.substring(venue.lastIndexOf('/') + 1);

                console.log(venue);

                $.ajax({url: "yelp_rating.php?venue=" + venue, success: function(result) {
                        console.log(result);
                        $("#yelp_rating").html(result);
                    }});
            }
        }


        updateFoursquare();
        updateYelp();

    });

</script>

<center>

    <br>
    <a href='submit.php'>
        <img src='imgs/manager-logo.png' width='250'></a>
    <div id="main">
        <form name='save' id='save' method='POST' action="?add_venue=1&save=1&edit=<?php echo $_GET['edit']; ?>">
            <br>
            <h3 id='primary_photo_h3' ><span class='shadow'>Primary Photo</span></h3>

            <?php echo $active_photo; ?><br>
            <br><h3 id="h3_f324_0"><span class='shadow'>Name</span></h3><br>

            <div id="venue_name" >
                <input type="text" id="name" name="name"  value="<?php
                if (isset($_POST['name'])) {
                    echo $_POST['name'];
                } else {
                    echo $venue['name'];
                }
                ?>"></div>
            <br><h3 id='location_h3' ><span class='shadow'>Location</span></h3><br>
Location Summary:<br>
            <div id='location_background' >
                <input type="text" id="location" name="location"  value="<?php
                if (isset($_POST['location'])) {
                    echo $_POST['location'];
                } else {
                    echo $venue['location'];
                }
                ?>"></div>
            <br clear='all'>
            <div id="div_f324_0">
                <div id="div_f324_1">
                    Latitude: <br><div id='lat_background' >
                    <input type="text" id="lat" name="lat"  value="<?php
                    if (isset($_POST['lat'])) {
                        echo $_POST['lat'];
                    } else {
                        echo $venue['lat'];
                    }
                    ?>"></div>  
                </div>
                <div id="div_f324_2">

                    Longitude: <br><div id="lon_background" '>

                        <input type="text" id="lon" name="lon" id="lon_input"  value="<?php
                        if (isset($_POST['lon'])) {
                            echo $_POST['lon'];
                        } else {
                            echo $venue['lon'];
                        }
                        ?>"></div>
                </div>
            </div>
            <br clear='all'>
            <br><h3 id='description_h3' ><span class='shadow'>Description</span></h3><br>
            <div id='photo_descripton_background' >
                <textarea id="description" name="description" ><?php
                    if (isset($_POST['photo_description'])) {
                        echo $_POST['photo_description'];
                    } else {
                        echo $venue['description'];
                    }
                    ?></textarea></div>
            <br><h3 id='yelp_rating_h3' ><span class='shadow'>Yelp Rating</span></h3><br>
            <span id="span_f324_0" class='shadow'>Enter Yelp URL to show rating:</span><br>

            <div id='yelp_url_box' >
                <input type="text" id="yelp_url" name="yelp_url"  value="<?php
                if (isset($_POST['yelp_url'])) {
                    echo $_POST['yelp_url'];
                } else {
                    echo $venue['yelp_url'];
                }
                ?>"></div><div id='yelp_rating'></div>


            <br><h3 id="h3_f324_1"><span class='shadow'>Foursquare Photos</span></h3><br>
            <span id="enter_foursquare_url"  class='shadow'>Enter Foursquare  URL to manage photos:</span><br>

            <div id="foursquare_box" >
                
                <input type="text" id="foursquare_url" name="foursquare_url"  value="<?php
                if (isset($_POST['foursquare_url'])) {
                    echo $_POST['foursquare_url'];
                } else {
                    echo $venue['foursquare_url'];
                }
                ?>"></div>
                
                <?
                echo "<center><div style='width:95%;' id='foursquare_imgs'>";

                echo "</div></center>";
                ?>
                
            <br clear='all'>
            
            <input type='hidden' name='id' value='<?php echo $_GET['edit']; ?>'><br><div onclick='save_changes()'  class='shadow' id='form_save_changes'>Save Changes</div></form>

        <form name='photo_form' id='photo_form' enctype='multipart/form-data' method='post'><input type='hidden' id='photo_description' name='photo_description' value="">

            <input type='hidden' name='id' value="<?php echo $_GET['edit']; ?>">
            <input onchange='photoSubmit()' type="file" accept="image/*" capture="camera"  id='photo' name='photo'>

        </form>


    </div>

</center>
