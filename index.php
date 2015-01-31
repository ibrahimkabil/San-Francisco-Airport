<?php include('functions.php'); ?>

<html>

    <head>

		<!-- CSS and viewport -->
        <link rel="stylesheet" href="css/style.css">
        <meta name="viewport" content="width=device-width, initial-scale=1">

		<!-- Initiate JQuery -->
        <script src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
        <script src="http://code.jquery.com/jquery-migrate-1.2.1.min.js"></script>

		<!-- Initiate JavaScript functions -->
        <script src="js/functions.js"></script>

		<!-- Initiate Fancybox -->
        <script type="text/javascript" src="fancybox/source/jquery.fancybox.js?v=2.1.5"></script>
        <link rel="stylesheet" type="text/css" href="fancybox/source/jquery.fancybox.css?v=2.1.5" media="screen" />

    </head>

    <body>

    <center>

        <div id='container'>

            <?php
            
            // display content based on selected user action
            
            if ((!isset($_GET['edit']) || isset($_GET['save'])) && !isset($_GET['add'])) {

                include('list.php');
                
            } else if (isset($_GET['edit'])) {

                include('edit.php');
                
            } else if (isset($_GET['add'])) {

                include('add.php');
            }
            
            ?>

        </div>

    </center>
      
    <!-- Navigate to saved scroll position and add CSS highlight to last selected item -->

    <?php if(isset($_SESSION['scroll'])) { ?>
			    
		<script> $(function() { $('#scrollable').scrollTo('#<?php echo $_SESSION['scroll']; ?>',{duration:'slow', offsetTop : '150'}); $('#<?php echo $_SESSION['scroll']; ?>').css('border','4px solid #f2c43e'); }); </script>

    <?php } ?>

</html>

<?php 

// close mysql connection
mysql_close($con); 

?>