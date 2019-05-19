<?php
if(isset($_POST["submit"])){
    setcookie('room',$_POST['room'],time() + (10 * 365 * 24 * 60 * 60));
    header('Location: drroom.php');
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Doctor</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" media="screen" href="style/bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" media="screen" href="style/flaticon.css" />
    <link rel="stylesheet" type="text/css" media="screen" href="style/drstyle.css" />
    <!-- <script src="main.js"></script> -->
</head>
<body>
    <div class="container-fluid ">
        <div class="row">
            <div class="col-md-12 nav">
                <div class="userBox">
                    <i class="flaticon-doctor"></i>
                    <span class="userInfo">
                        <h5 id="drName">  </h5>
                        <span>اتاق <?php echo $_COOKIE['room'] ?></span>
                    </span>
                </div>
                <div class="date">
                    <h6 id="shDate" class="faNumber"></h6>
                    <h2 class="faNumber"> <span id="currentTime"></span> <i class="flaticon-clock"></i>
                    </h2>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 shiftsBox">
                    <div class="form-group">
                        <form action="drroom.php" method="post">
                            <label for="exampleFormControlSelect1">انتخاب اتاق</label>
                            <select class="form-control" id="room" name="room" >
                                <option value="1" <?php if($_COOKIE['room']==1) echo "selected"?> >اتاق1</option>
                                <option value="2" <?php if($_COOKIE['room']==2) echo "selected"?> >اتاق 2</option>
                            </select>
                            <input type="submit" name="submit" value="ذخیره">
                        </form>
                          </div>
                <button id="nextButton">
                    
                    <i class="flaticon-bell-1"></i>
                    <span id="nextText"> شروع ویزیت</span>
                    <span>Z</span>
                </button>
                <button class="endShift" id="drLeft">
                        <i class="flaticon-appointment"></i>
                        <span>پایان شیفت</span>
                        <span>F5</span>
                </button>
                <div class="alert alert-danger" role="alert" id="notice">

                </div>
            </div>
            <div class="col-md-6 leftBox ">
                    <div class="nobat-box">
                            
                              <div class="reCallShift">
                                    <span class=""> 
                                        <i class="flaticon-bell-1"></i>
                                    </span>  
                                    <span>فراخوان مجدد </span>
                                    <span></span>
                                </div>
                                <br>
                                <div class="nobat">
                                    
                                    <div class="number">
                                            
                                            <h2 id="queue"></h2>
                                    </div>
                                </div>
                                <div class=" ofoqi">
                                        <i class="flaticon-alarm"></i>
                                        <span>  نوبت شماره :</span>
                                </div>
                                
                    </div>
            </div>
        </div>
    </div>
</body>
</html>

<script>
    function checkTime(i) {
        if (i < 10) {
            i = "0" + i;
        }
        return i;
    }
    function startTime() {
        var today = new Date();
        var h = today.getHours();
        var m = today.getMinutes();
        var s = today.getSeconds();
        // add a zero in front of numbers<10
        m = checkTime(m);
        s = checkTime(s);
        document.getElementById('currentTime').innerHTML = h + ":" + m ;
        t = setTimeout(function() {
            startTime()
        }, 30000);
    }
    startTime();
</script>
<script src="assets/jquery-3.3.1.min.js"></script>
<script src="js/data.js"></script>
<script src="js/drroom.js"></script>