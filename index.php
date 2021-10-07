<html>
<head>
<title>MPESA STK SIMULATOR</title>
</head>
<body>
<form method=post action="stkpush.php">
Mobile Number<input type=text name=mobile_number maxlength=13 size=70 placeholder="Enter a valid mobile number 2547********* or 07*******">
<p>Simulates a payment of 1 shilling</p>
<input type=submit value=submit name=submit><input type=reset value=Cancel>
</form>
<body>
<?php
if(isset($_POST["submit"]))
{
    $mobile_number=$_POST["mobile_number"];
    $mobile_validator="/^[+0-9]{10,12}$/im";
    
    if(!preg_match($mobile_validator,$mobile_number))
    {
    echo "<script>alert('Invalid Mobile Number');</script>";
    echo 'Invalid Mobile Number';
    return;
    }
    
    if(substr($mobile_number,0,1)=="0" || substr($mobile_number,0,4)=="+254")
    {
    //echo "<script>alert('First Digit is 0 in Mobile Number');</script>";
    //echo 'First Digit is 0 in Mobile Number';
    substr_replace($mobile_number,"254",0);
    $mobile_number."254".substr($mobile_number,1,strlen($mobile_number));
    echo "<br><br>MObile Number before $mobile_number.<br><br>New Mobile Number is 254".substr($mobile_number,1,strlen($mobile_number));
    }
}
?>
