<!DOCTYPE html>
<html>
    <head>
        <title>KU NOTICE</title>
        <link rel="stylesheet" type="text/css" href="pro.css">
        <meta http-equiv="refresh" content="5">
    </head>
    <body>
      <center><b>NOTICE</b></center>
      <div style="position: absolute;width: 100%;height: 10% ;background-color: #000;opacity: 0.3;left: 0%;top: 0%;box-shadow: 0px 0px 50px 0px #000;"></div>   
      <div style="position: absolute;top: 15%;height: 85%;overflow: scroll;width: 100%;">
        <!--<marquee direction="up">-->
        <?php
          $output = shell_exec('php quickstart.php');
          echo $output;
        ?>
        <!--</marquee>-->
        </div>
      <a href="./"><img id="logo" src="./logo.png" /></a>
    </body>
</html>

