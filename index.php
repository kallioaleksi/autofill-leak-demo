<?php
  // Checks if running on HTTP instead of HTTPS, and recirects if necessary to ensure security
  if(empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] == "off") {
    $redirect = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    header('HTTP/1.1 301 Moved Permanently');
    header('Location: ' . $redirect);
    exit();
  }

  // Fetches POST data if available
  $_POST   = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
  $runTest = isset($_POST['run-test']) ? true : false;
  if($runTest) {
    $values = [];
    foreach ($_POST as $key => $value) {
      if (!in_array($key, ['run-test', 'name', 'email']) && $value !== '') {
        $values[$key] = $value;
      }
    }
    // If one of the hidden fields isn't empty, autofill has leaked data
    $leaked = implode('', $values) !== '' ? true : false;
  }

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Autofill security leak demo</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <style>
      .tryhiding { margin-left: -9999px; position: absolute; }
      .footer { margin-top: 20px; text-align: center; font-size: 12px; background-color: #ddd; }
    </style>
  </head>
  <body>
    <div class="container">
      <div class="jumbotron">
        <h1>Test your brower's autofill</h1>
        <p>Some browsers have a security leak which can expose too much of your autofilled data to the page.</p>
        <p>You can test how your browser behaves by using autofill to fill in your details in the fields below.</p>
        <p>This test page doesn't save any data, so you'll be safe. This page is open source, you can view the source code on <a href="https://github.com/onik/autofill-leak-demo">GitHub</a></p>
      </div>
      <?php
        if ($runTest) {
          if (!$leaked) {
            echo '<div class="alert alert-success" role="alert"><strong>Great!</strong> Your browser detected the extra fields as hidden and didn\'t supply information without your knowledge! <i>(or you cheated and didn\'t use autofill...)</i></div>';
          } else {
            echo '<div class="alert alert-danger" role="alert"><strong>Oh no!</strong> Your browser exposes too much information to malicious pages. I\'d recommend not using autofill on pages you don\'t trust! See the results below for details.</div>';
            echo '<div class="well"><h2>Information your browser sent:</h2><ul>';
            foreach ($values as $key => $value) {
              echo '<li>' . $key . ': ' . $value . '</li>';
            }
            echo '</ul></div>';
          }
        }
      ?>
      <form method="POST">
        <!-- This field should be normally autofilled -->
        <div class="form-group">
          <label for="name">Name:</label>
          <input class="form-control" type="text" name="name" id="name" autocomplete="name" />
        </div>

        <!-- This field should be normally autofilled -->
        <div class="form-group">
          <label for="email">Email (this should be autofilled when you enter your name and choose to autofill):</label>
          <input class="form-control" type="email" name="email" id="email" autocomplete="email" />
        </div>

        <!-- Hidden field to easily determine if a submit has been made -->
        <input type="hidden" name="run-test" id="run-test" value="true" />

        <!-- These fields are hidden and SHOULDN'T be touched by autofill -->
        <input class="tryhiding" type="text" name="honorific-prefix" id="honorific-prefix" autocomplete="honorific-prefix" />
        <input class="tryhiding" type="text" name="given-name" id="given-name" autocomplete="given-name" />
        <input class="tryhiding" type="text" name="additional-name" id="additional-name" autocomplete="additional-name" />
        <input class="tryhiding" type="text" name="family-name" id="family-name" autocomplete="family-name" />
        <input class="tryhiding" type="text" name="honorific-suffix" id="honorific-suffix" autocomplete="honorific-suffix" />
        <input class="tryhiding" type="text" name="nickname" id="nickname" autocomplete="nickname" />
        <input class="tryhiding" type="text" name="username" id="username" autocomplete="username" />
        <input class="tryhiding" type="text" name="new-password" id="new-password" autocomplete="new-password" />
        <input class="tryhiding" type="text" name="current-password" id="current-password" autocomplete="current-password" />
        <input class="tryhiding" type="text" name="organization-title" id="organization-title" autocomplete="organization-title" />
        <input class="tryhiding" type="text" name="organization" id="organization" autocomplete="organization" />
        <input class="tryhiding" type="text" name="street-address" id="street-address" autocomplete="street-address" />
        <input class="tryhiding" type="text" name="address-line1" id="address-line1" autocomplete="address-line1" />
        <input class="tryhiding" type="text" name="address-line2" id="address-line2" autocomplete="address-line2" />
        <input class="tryhiding" type="text" name="address-line3" id="address-line3" autocomplete="address-line3" />
        <input class="tryhiding" type="text" name="address-level4" id="address-level4" autocomplete="address-level4" />
        <input class="tryhiding" type="text" name="address-level3" id="address-level3" autocomplete="address-level3" />
        <input class="tryhiding" type="text" name="address-level2" id="address-level2" autocomplete="address-level2" />
        <input class="tryhiding" type="text" name="address-level1" id="address-level1" autocomplete="address-level1" />
        <input class="tryhiding" type="text" name="country" id="country" autocomplete="country" />
        <input class="tryhiding" type="text" name="country-name" id="country-name" autocomplete="country-name" />
        <input class="tryhiding" type="text" name="postal-code" id="postal-code" autocomplete="postal-code" />
        <input class="tryhiding" type="text" name="cc-name" id="cc-name" autocomplete="cc-name" />
        <input class="tryhiding" type="text" name="cc-given-name" id="cc-given-name" autocomplete="cc-given-name" />
        <input class="tryhiding" type="text" name="cc-additional-name" id="cc-additional-name" autocomplete="cc-additional-name" />
        <input class="tryhiding" type="text" name="cc-family-name" id="cc-family-name" autocomplete="cc-family-name" />
        <input class="tryhiding" type="text" name="cc-number" id="cc-number" autocomplete="cc-number" />
        <input class="tryhiding" type="text" name="cc-exp" id="cc-exp" autocomplete="cc-exp" />
        <input class="tryhiding" type="text" name="cc-exp-month" id="cc-exp-month" autocomplete="cc-exp-month" />
        <input class="tryhiding" type="text" name="cc-exp-year" id="cc-exp-year" autocomplete="cc-exp-year" />
        <input class="tryhiding" type="text" name="cc-csc" id="cc-csc" autocomplete="cc-csc" />
        <input class="tryhiding" type="text" name="cc-type" id="cc-type" autocomplete="cc-type" />
        <input class="tryhiding" type="text" name="transaction-currency" id="transaction-currency" autocomplete="transaction-currency" />
        <input class="tryhiding" type="text" name="transaction-amount" id="transaction-amount" autocomplete="transaction-amount" />
        <input class="tryhiding" type="text" name="language" id="language" autocomplete="language" />
        <input class="tryhiding" type="text" name="bday" id="bday" autocomplete="bday" />
        <input class="tryhiding" type="text" name="bday-day" id="bday-day" autocomplete="bday-day" />
        <input class="tryhiding" type="text" name="bday-month" id="bday-month" autocomplete="bday-month" />
        <input class="tryhiding" type="text" name="bday-year" id="bday-year" autocomplete="bday-year" />
        <input class="tryhiding" type="text" name="sex" id="sex" autocomplete="sex" />
        <input class="tryhiding" type="text" name="url" id="url" autocomplete="url" />
        <input class="tryhiding" type="text" name="photo" id="photo" autocomplete="photo" />
        <input class="tryhiding" type="text" name="tel" id="tel" autocomplete="tel" />
        <input class="tryhiding" type="text" name="tel-country-code" id="tel-country-code" autocomplete="tel-country-code" />
        <input class="tryhiding" type="text" name="tel-national" id="tel-national" autocomplete="tel-national" />
        <input class="tryhiding" type="text" name="tel-area-code" id="tel-area-code" autocomplete="tel-area-code" />
        <input class="tryhiding" type="text" name="tel-local" id="tel-local" autocomplete="tel-local" />
        <input class="tryhiding" type="text" name="tel-local-prefix" id="tel-local-prefix" autocomplete="tel-local-prefix" />
        <input class="tryhiding" type="text" name="tel-local-suffix" id="tel-local-suffix" autocomplete="tel-local-suffix" />
        <input class="tryhiding" type="text" name="tel-extension" id="tel-extension" autocomplete="tel-extension" />
        <input class="tryhiding" type="text" name="impp" id="impp" autocomplete="impp" />
        <input class="tryhiding" type="text" name="home-tel" id="home-tel" autocomplete="home tel" />
        <input class="tryhiding" type="text" name="home-tel-country-code" id="home-tel-country-code" autocomplete="home tel-country-code" />
        <input class="tryhiding" type="text" name="home-tel-national" id="home-tel-national" autocomplete="home tel-national" />
        <input class="tryhiding" type="text" name="home-tel-area-code" id="home-tel-area-code" autocomplete="home tel-area-code" />
        <input class="tryhiding" type="text" name="home-tel-local" id="home-tel-local" autocomplete="home tel-local" />
        <input class="tryhiding" type="text" name="home-tel-local-prefix" id="home-tel-local-prefix" autocomplete="home tel-local-prefix" />
        <input class="tryhiding" type="text" name="home-tel-local-suffix" id="home-tel-local-suffix" autocomplete="home tel-local-suffix" />
        <input class="tryhiding" type="text" name="home-tel-extension" id="home-tel-extension" autocomplete="home tel-extension" />
        <input class="tryhiding" type="text" name="home-email" id="home-email" autocomplete="home email" />
        <input class="tryhiding" type="text" name="home-impp" id="home-impp" autocomplete="home impp" />
        <input class="tryhiding" type="text" name="work-tel" id="work-tel" autocomplete="work tel" />
        <input class="tryhiding" type="text" name="work-tel-country-code" id="work-tel-country-code" autocomplete="work tel-country-code" />
        <input class="tryhiding" type="text" name="work-tel-national" id="work-tel-national" autocomplete="work tel-national" />
        <input class="tryhiding" type="text" name="work-tel-area-code" id="work-tel-area-code" autocomplete="work tel-area-code" />
        <input class="tryhiding" type="text" name="work-tel-local" id="work-tel-local" autocomplete="work tel-local" />
        <input class="tryhiding" type="text" name="work-tel-local-prefix" id="work-tel-local-prefix" autocomplete="work tel-local-prefix" />
        <input class="tryhiding" type="text" name="work-tel-local-suffix" id="work-tel-local-suffix" autocomplete="work tel-local-suffix" />
        <input class="tryhiding" type="text" name="work-tel-extension" id="work-tel-extension" autocomplete="work tel-extension" />
        <input class="tryhiding" type="text" name="work-email" id="work-email" autocomplete="work email" />
        <input class="tryhiding" type="text" name="work-impp" id="work-impp" autocomplete="work impp" />
        <input class="tryhiding" type="text" name="mobile-tel" id="mobile-tel" autocomplete="mobile tel" />
        <input class="tryhiding" type="text" name="mobile-tel-country-code" id="mobile-tel-country-code" autocomplete="mobile tel-country-code" />
        <input class="tryhiding" type="text" name="mobile-tel-national" id="mobile-tel-national" autocomplete="mobile tel-national" />
        <input class="tryhiding" type="text" name="mobile-tel-area-code" id="mobile-tel-area-code" autocomplete="mobile tel-area-code" />
        <input class="tryhiding" type="text" name="mobile-tel-local" id="mobile-tel-local" autocomplete="mobile tel-local" />
        <input class="tryhiding" type="text" name="mobile-tel-local-prefix" id="mobile-tel-local-prefix" autocomplete="mobile tel-local-prefix" />
        <input class="tryhiding" type="text" name="mobile-tel-local-suffix" id="mobile-tel-local-suffix" autocomplete="mobile tel-local-suffix" />
        <input class="tryhiding" type="text" name="mobile-tel-extension" id="mobile-tel-extension" autocomplete="mobile tel-extension" />
        <input class="tryhiding" type="text" name="mobile-email" id="mobile-email" autocomplete="mobile email" />
        <input class="tryhiding" type="text" name="mobile-impp" id="mobile-impp" autocomplete="mobile impp" />
        <input class="tryhiding" type="text" name="fax-tel" id="fax-tel" autocomplete="fax tel" />
        <input class="tryhiding" type="text" name="fax-tel-country-code" id="fax-tel-country-code" autocomplete="fax tel-country-code" />
        <input class="tryhiding" type="text" name="fax-tel-national" id="fax-tel-national" autocomplete="fax tel-national" />
        <input class="tryhiding" type="text" name="fax-tel-area-code" id="fax-tel-area-code" autocomplete="fax tel-area-code" />
        <input class="tryhiding" type="text" name="fax-tel-local" id="fax-tel-local" autocomplete="fax tel-local" />
        <input class="tryhiding" type="text" name="fax-tel-local-prefix" id="fax-tel-local-prefix" autocomplete="fax tel-local-prefix" />
        <input class="tryhiding" type="text" name="fax-tel-local-suffix" id="fax-tel-local-suffix" autocomplete="fax tel-local-suffix" />
        <input class="tryhiding" type="text" name="fax-tel-extension" id="fax-tel-extension" autocomplete="fax tel-extension" />
        <input class="tryhiding" type="text" name="fax-email" id="fax-email" autocomplete="fax email" />
        <input class="tryhiding" type="text" name="fax-impp" id="fax-impp" autocomplete="fax impp" />
        <input class="tryhiding" type="text" name="pager-tel" id="pager-tel" autocomplete="pager tel" />
        <input class="tryhiding" type="text" name="pager-tel-country-code" id="pager-tel-country-code" autocomplete="pager tel-country-code" />
        <input class="tryhiding" type="text" name="pager-tel-national" id="pager-tel-national" autocomplete="pager tel-national" />
        <input class="tryhiding" type="text" name="pager-tel-area-code" id="pager-tel-area-code" autocomplete="pager tel-area-code" />
        <input class="tryhiding" type="text" name="pager-tel-local" id="pager-tel-local" autocomplete="pager tel-local" />
        <input class="tryhiding" type="text" name="pager-tel-local-prefix" id="pager-tel-local-prefix" autocomplete="pager tel-local-prefix" />
        <input class="tryhiding" type="text" name="pager-tel-local-suffix" id="pager-tel-local-suffix" autocomplete="pager tel-local-suffix" />
        <input class="tryhiding" type="text" name="pager-tel-extension" id="pager-tel-extension" autocomplete="pager tel-extension" />
        <input class="tryhiding" type="text" name="pager-email" id="pager-email" autocomplete="pager email" />
        <input class="tryhiding" type="text" name="pager-impp" id="pager-impp" autocomplete="pager impp" />

        <input class="btn btn-default" type="submit" value="Test me!" />
      </form>
    </div>
    <div class="jumbotron footer">
      <div class="container">
        <small>&copy; 2017 Aleksi Kallio</small>
      </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
  </body>
</html>