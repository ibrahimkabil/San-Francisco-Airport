<?php
require_once("src/FoursquareAPI.class.php");

$client_key = "GP3DC4XYK4QI3RJWOUBB22HSLVGSNBX3XNCS25Z0EQKMVG11";
$client_secret = "INOCLBP0WQZJKGNRRILD1INQFDYA312P3YKHQH0W5GVFZRZH";
$foursquare = new FoursquareAPI($client_key, $client_secret);

$params = array();

$venue = $_GET['venue'];

$photos_response = $foursquare->GetPublic("venues/" . $venue . "/photos", $params);

$photos = json_decode($photos_response);

if(isset($photos->response->photos->items)){

    foreach ($photos->response->photos->items as $photo):

        echo "<div style='width:350px;'>";

        echo "<div class='foursquare_img' style='position:relative; background-repeat:no-repeat; background-size:100%; background-image:url(" . $photo->prefix . 'width150' . $photo->suffix . ")'><div class='select_img' style='position:relative; width:65%; height:100%; float:left; z-index:1;'></div><div style='position:relative; z-index:2; width:35%; height:35px;  float:right;' class='zoom_img' zoom='" . $photo->prefix . 'width600' . $photo->suffix . "'><img src='imgs/magnify.png' width='35'></div><div class='photo_num' style='position: relative; z-index: 2;float: left;border-radius: 30px;margin-top: -43px;padding-top: 13px;background-color: #238ecc;width: 40px;font-weight:bold;height: 28px;'></div></div>";

        echo "</div>";

    endforeach;

}else{
    
    echo "<h4 style='color:red;'>Invalid Foursquare URL</h2>";

}

?>

<script>
    
    $(function() {

        var photo_num = 0;

        $('#keyword').click(function() {
            $(this).select();
        });

        $('.select_img').toggle(
                function() {

                    //if($(this).parent().find('.photo_num').html() == '')
                    photo_num++;

                    $(this).parent().addClass('photo_selected');

                    $(this).parent().find('.photo_num').html(photo_num);

                    $(this).parent().find('.photo_num').show();

                },
                function() {
                    photo_num--;

                    updateRankings($(this).parent().find('.photo_num').html());

                    $(this).parent().removeClass('photo_selected');

                    $(this).parent().find('.photo_num').hide();
                }



        );

        $('.zoom_img').click(
                function() {

                    $.fancybox.open($(this).attr('zoom'));

                }
        );

        function updateRankings(rank) {

            $.each($('.photo_num'), function() {

                if ($(this).css('display') == 'block') {

                    if ($(this).html() > rank) {

                        $(this).html($(this).html() - 1);

                    }

                }

            });

        }

    });

</script>