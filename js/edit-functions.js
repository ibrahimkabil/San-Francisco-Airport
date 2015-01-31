	$(function() {

        $("#foursquare_url").change(function() {

			console.log('updating foursquare');
            updateFoursquare();

        });
        
        $("#yelp_url").change(function() {

			console.log('updating yelp');
            updateYelp();

        });


        function updateFoursquare() {

            $("#foursquare_imgs").html('');

            var venue = $('#foursquare_url').val();

            if (venue != '') {
                
                venue = venue.substring(venue.lastIndexOf('/') + 1);

                console.log(venue);

                $.ajax({url: "foursquare/foursquare_photos.php?venue=" + venue, success: function(result) {
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

                $.ajax({url: "yelp/yelp_rating.php?venue=" + venue, success: function(result) {
                        console.log(result);
                        $("#yelp_rating").html(result);
                    }});
            }
        }


        updateFoursquare();
        updateYelp();

    });