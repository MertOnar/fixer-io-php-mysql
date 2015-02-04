<?php
/**
* @author repat<repat@repat.de>
* @date 04.02.2015
* @description Asks Fixer.io API for exchange rates with EUR as base.
*              Asks for British Pounds, US Dollar, Canadian Dollar, Chinese Renminbi and Norwegian Krone
*              Database Table is EXCHANGE-RATES-FIXERIO.
* @url read: http://repat.de/2015/02/collect-currency-exchange-rates-in-a-mysql-database-with-php-and-fixer-io-api/
*/
// TODO: Download and install Requests Lib: https://github.com/rmccue/Requests
// TODO: Define your database connection
include('vendor/rmccue/requests/library/Requests.php');
Requests::register_autoloader();

$BASE = 'EUR';

$request = Requests::get('https://api.fixer.io/latest?base=' . $BASE, array('Accept' => 'application/json'));

if ($request->status_code == 200) {
  $response = json_decode($request->body);
  $GBP = $response->rates->GBP;
  $CAD = $response->rates->CAD;
  $USD = $response->rates->USD;
  $NOK = $response->rates->NOK;
  $CNY = $response->rates->CNY;
  $rBASE = mysql_real_escape_string($response->base);
  $date = mysql_real_escape_string($response->date);

  $currencies = mysql_real_escape_string("1.0, $USD, $GBP, $NOK, $CNY, $CAD");
  $qry = "INSERT INTO `EXCHANGE-RATES-FIXERIO`(date, base, eur, usd, gbp, nok, cny, cad) VALUES ('$date', '$rBASE', $currencies);";
  $insert = mysql_query($qry, $MYSQLCONNECTION) or print mysql_error();
}
?>
