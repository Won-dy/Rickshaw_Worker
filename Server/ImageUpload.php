<?
$loc = "image/";
$loc = $loc.basename($_FILES['phone']['name']);
if(move_uploaded_file($_FILES['phone']['tmp_name'], $loc))
{
echo "success";
}
else
{
echo "fail";
}
?>