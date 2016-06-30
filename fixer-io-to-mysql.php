<?php
/**
* @author repat<repat@repat.de>
* @date 04.02.2015
* @description Asks Fixer.io API for exchange rates with EUR as base.
*              Asks for British Pounds, US Dollar, Canadian Dollar, Chinese Renminbi and Norwegian Krone
*              Database table is exchange_rates_fixerio
* @url read: http://repat.de/2015/02/collect-currency-exchange-rates-in-a-mysql-database-with-php-and-fixer-io-api/
*/
// TODO: Download and install Requests package with composer
// TODO: Define your database connection and/or exchange the mysql_ commands with mysqli_ or PDO

$base = 'EUR';

$request = Requests::get('https://api.fixer.io/latest?base=' . base, array('Accept' => 'application/json'));

if ($request->status_code == 200) {
  $response = json_decode($request->body);
  $GBP = $response->rates->GBP;
  $CAD = $response->rates->CAD;
  $USD = $response->rates->USD;
  $NOK = $response->rates->NOK;
  $CNY = $response->rates->CNY;
  $rBase = mysql_real_escape_string($response->base);
  $date = mysql_real_escape_string($response->date);

  $currencies = mysql_real_escape_string("1.0, $USD, $GBP, $NOK, $CNY, $CAD");
  $qry = "INSERT INTO `exchange_rates_fixerio`(date, base, eur, usd, gbp, nok, cny, cad) VALUES ('$date', '$rBase', $currencies);";
  $insert = mysql_query($qry, $mySQLConnection) or print mysql_error();
}
