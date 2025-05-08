<?php session_start(); ob_start();

error_reporting(E_ALL); ini_set('display_errors', 1); ini_set('log_errors', 1);
$db_host = "localhost"; 
/*




$db_username = "root"; 
$db_pass = ""; 
$db_name = "project_hub";




*/

$db_username = "projectr_project"; 
$db_pass = "Y34GgwK(]h82Yg"; 
$db_name = "projectr_project";





$con = mysqli_connect ("$db_host","$db_username","$db_pass","$db_name");
$siteprefix="pr_";
date_default_timezone_set('Africa/Lagos');
$currentdate=date("Y-m-d");
$currentdatetime=date("Y-m-d H:i:s");
$imagePath='uploads/';
$adminlink='admin/';
$adminName='Project Hub';
$adminimagePath='../../uploads/';
$sitecurrency="₦";
$sitecurrencyCode="&#8358;";
$documentPath='documents/';
$affiliateurl='https://affiliate.projectreporthub.ng/';
$adminurl='https://admin.projectreporthub.ng/';


$sql = "SELECT * from ".$siteprefix."site_settings";
$sql2 = mysqli_query($con,$sql);
while($row = mysqli_fetch_array($sql2))
{$apikey = $row["paystack_key"]; 
$sitemail = $row["site_mail"];
$sitenumber = $row["site_number"];
$sitename = $row["site_name"]; 
$siteimg= $row["site_logo"];
$siteurl= $row["site_url"];
$SMPkey=$row["brevo_key"];
$escrowfee= $row["commision_fee"];
$affiliate_percentage= $row["affliate_percentage"];
$sitedescription= $row["site_description"];
$siteaccno= $row["account_number"];
$siteaccname= $row["account_name"];
$site_bank= $row["site_bank"];
$sitekeywords= $row["site_keywords"];
$google_map= $row["google_map"];} 
$adminlink='admin'.$siteurl;

$siteName=$sitename;
$siteMail=$sitemail;

include "functions.php"; 
?>