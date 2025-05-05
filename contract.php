<?php
if (empty($_GET['user_login'])) {
    header("Location: signup.php");
    exit();
}

$user_id = $_GET['user_login'];
$freelancer_name = htmlspecialchars($_GET['name']);
$freelancer_address = htmlspecialchars($_GET['address']);
$freelancer_representative = htmlspecialchars($_GET['display_name']);
$freelancer_email = htmlspecialchars($_GET['email']);
$freelancer_phone = htmlspecialchars($_GET['phone']);
$current_date = date("F j, Y");


if (isset($_POST['seller_submit'])){
    // Update user status in the database
    include 'backend/connect.php';
    $stmt = $con->prepare("UPDATE pr_users SET seller = 1 WHERE s = ?");
    $stmt->bind_param("s", $user_id);
    $stmt->execute();
    $stmt->close();

    // Redirect to signin page
    header("Location: signin.php?user_login=". $user_id);
    exit();
}


?>
<!DOCTYPE html>
<html>
<head>
    <title>Contract Page</title>
</head>
<body width="70%">
    <center><h1>KYNELI BUSINESS SUPPORT SERVICES</h1>
    <p>Project Report Hub NG ….. The One Stop Shop for Project Research Report Resources in Nigeria!</p>
    <p>61-65 Egbe- Isolo Road,<br>
    Iyana Ejigbo Shopping Arcade,<br>
    Block A, Suite 19,<br>
    Iyana Ejigbo Bus Stop,<br>
    Ejigbo, Lagos.</p>
    <p>Tel: +234 (0) 803 3782 777; +234 (01) 29 52 413</p>
    <p>Email: hello@projectreporthub.ng<br>
    Website: <a href="http://www.projectreporthub.ng">www.projectreporthub.ng</a></p>
    <h2>SALES, MARKETING AND DISTRIBUTION AGREEMENT FOR PROJECT REPORTS</h2>
    <p>THIS AGREEMENT IS MADE THIS DAY</p>
    <p><?php echo date("F j, Y"); ?></p>
    <form method="post" action="">
        <h3>BETWEEN</h3></center>
        <table border="1">
            <tr>
                <td>COMPANY:</td>
                <td>KYNELI BUSINESS SUPPORT SERVICES</td>
            </tr>
            <tr>
                <td>URL:</td>
                <td><a href="http://www.projectreporthub.ng">www.projectreporthub.ng</a></td>
            </tr>
            <tr>
                <td>ADDRESS:</td>
                <td>61-65 Egbe- Isolo Road, Iyana Ejigbo Shopping Arcade, Block A, Suite 19, Iyana Ejigbo Bus Stop, Ejigbo, Lagos State, Nigeria.</td>
            </tr>
            <tr>
                <td>REPRESENTED BY:</td>
                <td>Anaekwe Everistus Nnamdi</td>
            </tr>
            <tr>
                <td>JOB TITLE:</td>
                <td>MD/CEO</td>
            </tr>
            <tr>
                <td>PHONE:</td>
                <td>+234 -1- 29 52 413</td>
            </tr>
            <tr>
                <td>E-MAIL ADDRESS:</td>
                <td>hello@projectreporthub.ng</td>
            </tr>
        </table>
        <p>[HEREINAFTER CALLED THE “PUBLISHER”]</p>
        <h3>AND</h3>
        <table  border="1">
            <tr>
            <td>COMPANY:</td>
            <td><?php echo $freelancer_representative; ?></td>
            </tr>
            <tr>
            <td>ADDRESS:</td>
            <td><?php echo $freelancer_address; ?></td>
            </tr>
            <tr>
            <td>REPRESENTED BY:</td>
            <td><?php echo $freelancer_name; ?></td>
            </tr>
            <tr>
            <td>PHONE:</td>
            <td><?php echo $freelancer_phone; ?></td>
            </tr>
            <tr>
            <td>E-MAIL ADDRESS:</td>
            <td><?php echo $freelancer_email; ?></td>
            </tr>
        </table>
        <p>[HEREINAFTER CALLED THE “FREELANCER”]</p>
<p>Whereas, the publisher operates an online platform www.projectreporthub.ng, which provides access to project reports, thesis, dissertations and other digital content across various industries; and</p>
<p>Whereas, the Freelancer wishes to provide services for creating, and uploading project reports, thesis, dissertations and other digital content for sale on the platform;</p>
<p>Both parties agree to the following terms and conditions:</p>

<h3>1. Scope of Services</h3>
<p>Freelancer agrees to create and submit digital content, including but not limited to project reports, thesis and dissertations (hereinafter referred to as "Content"), based on the specifications provided by the Publisher. The content must comply with the requirements outlined by the Publisher and must be original, free from plagiarism, and not infringe upon any third-party rights.</p>

<h3>2. Submission Process</h3>
<p>Freelancer will submit all Content through the platform’s designated submission system. The Publisher reserves the right to review, approve, reject, or request revisions of the Content before publishing it on www.projectreporthub.ng.</p>

<h3>3. Ownership and Licensing</h3>
<p>Freelancer retains ownership of the Content created. However, by submitting the Content to the Publisher, the Freelancer grants the Publisher the right to market and sell the contents on our platform.</p>

<h3>4. Compensation and Payment</h3>
<p>Freelancer will be compensated for each piece of Content according to the agreed-upon rate prior to submission. Payment terms are as follows:</p>
<ul>
    <li>Rate: The freelancer would be paid seventy percent (70%) of the offer price anytime there is a sale.</li>
    <li>Payment Schedule: Payments will be made monthly, upon approval of the submitted Content.</li>
    <li>Method of Payment: Payments will be made via Bank Transfer or any other means.</li>
</ul>
<p>If the Publisher rejects a submission or requests revisions, the Freelancer will be given the opportunity to amend the Content and resubmit it for approval.</p>

<h3>5. Confidentiality</h3>
<p>Freelancer agrees not to disclose any proprietary or confidential information related to the Publisher, the platform, or its users during and after the term of this Agreement. Confidential information includes any unpublished material, platform features, or strategies not available to the public.</p>

<h3>6. Quality Assurance</h3>
<p>The Freelancer agrees to maintain a high standard of quality for all submitted Content. Content should be free of grammatical, typographical, and factual errors. The Publisher reserves the right to reject content that does not meet these quality standards.</p>

<h3>7. Indemnity</h3>
<p>Freelancer shall indemnify, defend, and hold harmless the Publisher, its officers, directors, employees, and agents from any and all claims, damages, losses, and expenses, including legal fees, arising out of or in connection with the Freelancer's work, including but not limited to any claims of intellectual property infringement.</p>

<h3>8. Independent Contractor Status</h3>
<p>Freelancer is an independent contractor and not an employee of the Publisher. Nothing in this Agreement creates an employer-employee relationship. Freelancer shall not be entitled to any benefits, insurance, or compensation other than what is specified in this Agreement.</p>

<h3>9. Dispute Resolution</h3>
<p>In the event of a dispute arising from or relating to this Agreement, the parties shall first attempt to resolve the issue through informal negotiation. If the dispute cannot be resolved through negotiation, either party may pursue mediation or arbitration in accordance with the laws of the United States of America.</p>

<h3>11. Governing Law</h3>
<p>This Agreement shall be governed by and construed in accordance with the laws of the United States of America, without regard to its conflict of law provisions.</p>

<h3>12. Entire Agreement</h3>
<p>This Agreement constitutes the entire understanding between the parties with respect to the subject matter hereof and supersedes all prior discussions, agreements, or understandings of any kind.</p>

<p>IN WITNESS WHEREOF, the parties hereto have executed this Agreement as of the date first written above.</p>

<p>Publisher: Foraminifera Market Research Limited<br>
Name: Anaekwe Everistus Nnamdi Ikechukwu<br>
Signature: Anaekwe Everistus Nnamdi Ikechukwu<br>
Date: <?php echo date("F j, Y"); ?></p>

<p>Freelancer:<br>
Name: <?php echo $freelancer_name; ?> <br>
Signature: <?php echo $freelancer_name; ?> <br>
Date: <?php echo date("F j, Y"); ?></p>

<p><input type="submit" name="seller_submit" value="SUBMIT" style="width:100%; background-color: #4CAF50; color: white; padding: 15px 32px; text-align: center; text-decoration: none; display: inline-block; font-size: 16px; margin: 4px 2px; cursor: pointer; border: none; border-radius: 4px;"></p>

</form>


</body>
</html>