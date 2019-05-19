<?php
require_once("../app/config.php");
$db= new mySqlDB();
$sql="SELECT * FROM dr_list";
$result=$db->query($sql);
if(isset($_GET["submit"])){
    $sql="INSERT INTO `dr_list` ( `dr_name`, `dr_code`) VALUES ( '{$_GET["dr_name"]}', '{$_GET['dr_code']}')";
    $db->query($sql);

}
?>
<html>
<body>
    <form action="manage.php" method="get">
        <label>نام پزشک:</label>
        <input type="text" name="dr_name"><br>
        <label>کد پزشک</label>
        <input type="number" name="dr_code">
        <input type="submit" name="submit" value="submit">
    </form>
    <ul>
        <?php
        while ($row=mysqli_fetch_assoc($result)){
            echo "<li>".$row['dr_name']."||".$row["dr_code"]."</li>";
        }
        ?>
    </ul>
</body>
</html>
<script>



    document.addEventListener('keypress', (event) => {
        const keyName = event.key;

    alert('keypress event\n\n' + 'key: ' + keyName);
    });

</script>
