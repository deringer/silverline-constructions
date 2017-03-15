<?php

// ###########################################################################
// #### CONFIGURE FROM: ADDRESS ##############################################

// THIS AREA IS OPTIONAL. ONLY TOUCH THESE SETTINGS IF YOU KNOW WHAT YOU ARE
// DOING. PLEASE READ README.TXT FOR MORE INFORMATION.

// If you would like to specify the From: address of emails sent by PHPForm,
// enter it between the double quotes below. If you leave this blank, the
// server will assign the default email address.

$rec_mailto       = "admin@silverlineconstructions.com.au";
$from_address     = "admin@silverlineconstructions.com.au";
$incoming_subject = "Enquiry - SilverLine Constructions";

// ###########################################################################
// ###########################################################################





// ###########################################################################
// #### ACTIVATE REQUIRED FIELDS? ############################################

// THIS AREA IS OPTIONAL. ONLY TOUCH THESE SETTINGS IF YOU KNOW WHAT YOU ARE
// DOING. PLEASE READ README.TXT FOR MORE INFORMATION.
//
// If you would like to make some fields of your form required, change "no" to
// "yes" below.

ini_set("sendmail_from", $from_address);
$required_on = "yes";

// If you have set $required_on to "yes" above, you can make fields required
// by beginning their name with "r_". For example, if you want to require
// a user to enter their name, use the following HTML:
//
// <input type='text' name='r_Name'>
//
// If a user fails to enter a required field, they will be taken to a page
// where a message such as "You have not completed all the required fields."
// will be displayed. Please specify the URL to this file below:

$required_errorpage = "error.htm";

// ###########################################################################
// ###########################################################################





// ###########################################################################
// #### OVERRIDE REQUIRED VARIABLES? #########################################

// THIS AREA IS OPTIONAL. ONLY TOUCH THESE SETTINGS IF YOU KNOW WHAT YOU ARE
// DOING. PLEASE READ README.TXT FOR MORE INFORMATION. NOTE: THIS WILL NOT
// AFFECT YOUR 'TURN ON REQUIRED FIELDS?' SECTION SETTINGS ABOVE.
//
// If you would like to override the three required variables in
// order to hide your email address, email subject, and thank you page
// URL from your email form, change "no" to "yes" below.

$override = "yes";

// If override is set to "yes", the hidden variables on your HTML
// email form named "rec_mailto", "rec_subject", and "rec_thanks" will be
// overridden and can therefore be removed from the form.

// If you have set override to "yes" above, you must specify new values for
// each of these variables below.

// Enter the email address(es) to send the email to.
$incoming_mailto = $from_address;

// Enter the email subject.
//Moved to Top
//$incoming_subject = "Ads Online Contact Form";

// Enter the thank you page URL.
$incoming_thanks = "thanks.htm";

// ###########################################################################
// ###########################################################################





// ###########################################################################
// #### BAN IP ADDRESSES? ####################################################

// THIS AREA IS OPTIONAL. ONLY TOUCH THESE SETTINGS IF YOU KNOW WHAT YOU ARE
// DOING. PLEASE READ README.TXT FOR MORE INFORMATION.
//
// If you would like to ban certain IP addresses from submitting your form,
// change "no" to "yes" below.

$ban_ip_on = "no";

// If you have set $ban_ip_on to "yes" above, please enter a list of the
// IP addresses you would like to ban, seperated only by commas.
// An example has been provided below:

$ban_ip_list = "111.222.33.55,11.33.777.99";

// ###########################################################################
// ###########################################################################





// ###########################################################################
// #### ACTIVATE DOMAIN SECURITY? ############################################
//
// THIS AREA IS OPTIONAL. ONLY TOUCH THESE SETTINGS IF YOU KNOW WHAT YOU ARE
// DOING. PLEASE READ README.TXT FOR MORE INFORMATION.
//
// This setting, when set to "yes" (default), will check to make sure other
// people are not submitting data to your PHPform.php file from their
// external domains. This means that if your domain name is "www.mysite.com",
// only forms on "www.mysite.com" will be able to use this PHPform.php.
// IF YOU ARE RECEIVING ERRORS SUCH AS "INVALID DOMAIN" FOR NO REASON, PLEASE
// CHANGE "yes" TO "no" BELOW.

$secure_domain_on = "yes";

// ###########################################################################
// ###########################################################################




// ###########################################################################
// #### ACTIVATE AUTO-RESPONSE? ##############################################
//
// THIS AREA IS OPTIONAL. ONLY TOUCH THESE SETTINGS IF YOU KNOW WHAT YOU ARE
// DOING. PLEASE READ README.TXT FOR MORE INFORMATION.
//
// This setting, when set to "yes", will make PHPForm automatically reply to
// the user who submitted your form with an email message. If you would like
// to use this feature, change "no" to "yes" below.

$autorespond_on = "no";

// If you have set $autorespond_on to "yes" above, you must specify a subject,
// from-address, and message to include in the auto-response email.

// The following setting is the subject of the auto-response email:
$autorespond_subject = "Your Form Submission";

// The following setting is the from-address of the auto-respond email:
$autorespond_from = "youremail@yoursite.com";

// The following setting is the message of your auto-response email:
$autorespond_contents = "Your submission from our website has been received. Thank you!";

// PHPForm also needs to know how to retrieve the user's email address.
// You must specify the name of the field into which the user will enter
// their email address. For example, if your email form contains an input
// field like "<input type='text' name='Email'>" you would set the
// following setting to "Email".
$autorespond_mailto_field = "Email";

// ###########################################################################
// ###########################################################################










// MAKE SURE PHPFORM IS NOT BEING LOADED FROM THE URL
if ($_SERVER['REQUEST_METHOD'] == "GET") {
    echo "
<html>
<head><title>PHPForm is installed correctly.</title></head>
<body>
<font style='font-family: verdana, arial; font-size: 9pt;'>
<b>PHPDynaForm is installed correctly.</b></font><br>
<font style='font-family: verdana, arial; font-size: 8pt;'>
PHPForm Easy PHP Form Mailer.
</font>
</body></html>
";
    exit();
}

// SET VARIABLES
$incoming_fields = array_keys($_POST);
$incoming_values = array_values($_POST);

if ($override == "no") {
    $incoming_mailto  = @$_POST['rec_mailto'];
    $incoming_subject = @$_POST['rec_subject'];
    $incoming_thanks  = @$_POST['rec_thanks'];
}

$incoming_mailto_cc  = @$_POST['opt_mailto_cc'];
$incoming_mailto_bcc = @$_POST['opt_mailto_bcc'];
$form_url            = $_SERVER["HTTP_REFERER"];
$error               = "no";

// MAKE SURE PHPFORM IS BEING RUN FROM THE RIGHT DOMAIN
if ($secure_domain_on == "yes") {
    $form_url_array = parse_url($form_url);
    $form_domain    = $form_url_array["host"];
    if ($form_domain != $_SERVER["HTTP_HOST"]) {
        echo "<h2>PHPForm Error - Invalid Domain</h2>
You have accessed PHPForm from an external domain - this is not allowed.<br>
You may only submit forms to a PHPForm file that exists on the same domain name.<br>
If you believe to be receiving this message in error, please refer to your readme.txt file.
<br><br>";
        $error = "yes";
    }
}

// CHECK IF MAILTO IS SET
if ($incoming_mailto == "") {
    echo "<h2>PHPForm Error - Missing Field</h2>
Your form located at <a href='$form_url'>$form_url</a> does not work because you forgot to include
the required \"<b>rec_mailto</b>\" field within the form. This field specifies who the email will
be sent to.
<br><br>
This should look like:<br>
&#060;input type=\"hidden\" name=\"rec_mailto\" value=\"youremail@yoursite.com\"&#062;
<br><br>
If you are still confused, please refer to the readme.txt for more information and examples.<br><br><br><br>
";
    $error = "yes";
}

// CHECK IF SUBJECT IS SET
if ($incoming_subject == "") {
    echo "<h2>PHPForm Error - Missing Field</h2>
Your form located at <a href='$form_url'>$form_url</a> does not work because you forgot to include
the required \"<b>rec_subject</b>\" field within the form. This field specifies the subject of
the email that will be sent.
<br><br>
This should look like:<br>
&#060;input type=\"hidden\" name=\"rec_subject\" value=\"New PHPForm Email\"&#062;
<br><br>
If you are still confused, please refer to the readme.txt for more information and examples.<br><br><br><br>
";
    $error = "yes";
}

// CHECK IF THANKS IS SET
if ($incoming_thanks == "") {
    echo "<h2>PHPForm Error - Missing Field</h2>
Your form located at <a href='$form_url'>$form_url</a> does not work because you forgot to include
the required \"<b>rec_thanks</b>\" field within the form. This field specifies what page the user
will be taken to after they submit the form.
<br><br>
This should look like:<br>
&#060;input type=\"hidden\" name=\"rec_thanks\" value=\"thanks.html\"&#062;
<br><br>
If you are still confused, please refer to the readme.txt for more information and examples.<br><br><br><br>
";
    $error = "yes";
}

// CHECK IF IP ADDRESS IS BANNED
if ($ban_ip_on == "yes") {
    if (strstr($ban_ip_list, $_SERVER["REMOTE_ADDR"])) {
        echo "<h2>PHPForm Error - Banned IP</h2>
You cannot use this form because your IP address has been banned by the administrator.<br>
";
        $error = "yes";
    }
}


if ($error == "yes") {
    exit();
}

// SET EMAIL INTRODUCTION
$message = "This email was received from PHPform at $form_url \n\n";

// LOAD EMAIL CONTENTS

//Display all fields
//for ($i = 0; $i < count($incoming_fields); $i++) {
//  $sub = substr($incoming_fields[$i], 0, 2);
//  echo $i . "; " . $sub . "; " . $incoming_fields[$i] . "; |" .$incoming_values[$i] . "| <br>";
//  if($incoming_values[$i] == "") {
//  echo ">>>>> empty" . "<br>";
// }
//  if(!isset($incoming_values[$i])) {
//  echo ">>>>> not set" . "<br>";
//  }
//  if($incoming_values[$i] == " ") {
//  echo ">>>>> space" . "<br>";
//  }
//}

for ($i = 0; $i < count($incoming_fields); $i++) {
    if ($incoming_fields[$i] != "rec_mailto") {
        if ($incoming_fields[$i] != "rec_subject") {
            if ($incoming_fields[$i] != "rec_thanks") {
                if ($incoming_fields[$i] != "opt_mailto_cc") {
                    if ($incoming_fields[$i] != "opt_mailto_bcc") {
                        //Special exclusion for website field
if ($incoming_fields[$i] != "Website") {


// CHECK FOR REQUIRED FIELDS IF ACTIVATED
if ($required_on == "yes") {
    $sub = substr($incoming_fields[$i], 0, 2);
    //echo $i . " - " . $sub . " - " . $incoming_fields[$i] . " |" .$incoming_values[$i] . "| <br>";
    if ($sub == "r_") {
        if ($incoming_values[$i] == "" or ! isset($incoming_values[$i]) or $incoming_values[$i] == " ") {
            //echo "Problem in" . $i . " - " . $sub . " - " . $incoming_fields[$i] . " |" .$incoming_values[$i] . "| <br>";
            //echo "Data Problem " . $i . "<br>";
            header("Location: $required_errorpage");
            exit();
        }
    }
}

                            if (containshtml($incoming_values[$i], $incoming_fields[$i])) {
                                //echo "HTML Problem " . $i . "<br>";
                                header("Location: $required_errorpage");
                                exit();
                            }
// ADD FIELD TO OUTGOING MESSAGE
$message .= "$incoming_fields[$i]:\n$incoming_values[$i]\n\n";
                        }
                    }
                }
            }
        }
    }
}

// SET EMAIL FOOTER
$message .= "\n\nPHP Email Submission Form\nWith Compliments.";

// CLEAR HEADERS
$headers = "";

// ADD FROM ADDRESS
if ($from_address != "") {
    $headers .= "From: $from_address\r\nReply-To: ".$_REQUEST["r_Email"];
}

// CHECK FOR CC OR BCC
if ($incoming_mailto_cc != "") {
    //$headers .= "Cc: $incoming_mailto_cc\r\n";
}
if ($incoming_mailto_bcc != "") {
    //$headers .= "Bcc: $incoming_mailto_bcc\r\n";
}

// SEND EMAIL
mail($incoming_mailto, $incoming_subject, $message, $headers, "-f$from_address\r\n");
//mail($incoming_mailto, $incoming_subject, $message, $headers);

// SEND AUTO-RESPONSE IF ACTIVATED
if ($autorespond_on == "yes") {
    $autorespond_mailto  = @$_POST[$autorespond_mailto_field];
    $autorespond_headers = "From: $autorespond_from";
    mail($autorespond_mailto, $autorespond_subject, $autorespond_contents, $autorespond_headers);
}

// FORWARD TO THANK YOU PAGE
header("Location: $incoming_thanks");


function containshtml($text, $field)
{
    $return = false;
    if (! preg_match("(rec_mailto2|r_Email|r_email)", $field)) {
        if (preg_match("(http:|cc:|bcc:|\.com|\.net|\.org|\.biz|www\.)", $text, $matches)||(strlen($text) !== strlen(strip_tags($text)))) {
            $return = true;
        }
    }

    return $return;
}
