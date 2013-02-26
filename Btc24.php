<?php

/*
 * https://bitcoin-24.com/user_api
 * 1LfB9ZSTaz6UM8EmLPsdHzNaJyNKGQpw45
 */
class Btc24 {

    private $apikey = BTC24_API_KEY;
    private $user = BTC24_USER;
    private $trades = 0;

    function Btc24() {
    }

    private function getjson($params) {
        return $this->curl_download("https://bitcoin-24.com/api/user_api.php", $params);
    }
    
    public function getTransactions(){
        //nicht möglich
        //return $this->curl_download("https://bitcoin-24.com/api/export.php?type=json", $this->params());
        return NULL;
    }

    private function makeparams($do) {
        return array_merge(array( 'api' => urlencode($do)), $this->params());
    }
    
    private function params() {
        return array(   'user' =>   urlencode($this->user) ,
                        'key' =>    urlencode($this->apikey)
                    );
    }

	private static function curl_download($url, $post = NULL) {
        if (!function_exists('curl_init')) {
            die('Sorry cURL is not installed!');
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_REFERER, "http://www.example.org/yay.htm");
        curl_setopt($ch, CURLOPT_USERAGENT, "MozillaXYZ/1.0");
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }

    /*
      Account Balance
      Return
      usd - USD in your account
      eur - EUR in your account
      btc - Total Bitcoin
      btc_available - Bitcoin available for trading
     */
	public function get_balance() {
        $params = $this->makeparams('get_balance');
        return $this->getjson($params);
    }
    
    /*
        Bitcoin Address
        Return
        address - Your Bitcoin address on Bitcoin-24
     */
	public function get_addr() {
        $params = $this->makeparams('get_addr');
        return $this->getjson($params);
    }
    
    /*
        Open Orders
        Return
        id - Order-ID
        amount - Bitcoin amount left to buy / sell
        amount_start - Bitcoin you started the offer
        type - Type 1 = Buy, 2 = Sell
        cur - Currency EUR, USD
        price - Price per Bitcoin
        date - timestamp
     */
  public function open_orders() {
        $params = $this->makeparams('open_orders');
        return $this->getjson($params);
    }    
    
    /*
        Cancel Order
        Parameter
        user - Username
        key - API-Key from your Settings
        api - cancel_order
        id - Your order id
        Return
        true - if order is canceled
     */
  public function cancel_order($transid) {
      $params = array_merge(array('id' => $transid),$this->makeparams('open_orders'));
        
        return $this->getjson($params);
    } 
    
    /*Buy Bitcoin
        Parameter
        amount - Bitcoin amount to buy
        price - Price you will pay per Bitcoin
        cur - Currency EUR, USD

        Return
        id - Order-ID
        type - Type 1 = Buy, 2 = Sell
        amount - Bitcoin to buy
        price - Price per Bitcoin
        date - timestamp
        cur - Currency EUR, USD
     */
	public function buy_btc($amount,$price,$cur) {
        $params = array_merge(array('amount' => $amount, 'price' => $price,'cur'=> $cur ),$this->makeparams('buy_btc'));
        return $this->getjson($params);
    } 
    
    /*
        Sell Bitcoin
        Parameter
        amount - Bitcoin amount to sell
        price - Price you will get per Bitcoin
        cur - Currency EUR, USD

        Return
        id - Order-ID
        type - Type 1 = Buy, 2 = Sell
        amount - Bitcoin to sell
        price - Price per Bitcoin
        date - timestamp
        cur - Currency EUR, USD
     */
	public function sell_btc($amount,$price,$cur) {
        $params = array_merge(array('amount' => $amount, 'price' => $price,'cur'=> $cur ),$this->makeparams('buy_btc'));
        $params = $this->makeparams('sell_btc');
        return $this->getjson($params);
    }
    
    /*
        Withdraw Bitcoin
        Parameter
        user - Username
        key - API-Key from your Settings
        api - withdraw_btc
        amount - Bitcoin amount to withdraw
        address - Bitcoin address

        Return
        trans - Transaction ID
     */
	public function withdraw_btc($amount,$address ) {
        $params = array_merge(array('amount' => $amount, 'address' => $address),$this->makeparams('buy_btc'));
        return $this->getjson($params);
    } 
    
    /*
        http://bitcoin24.zendesk.com/entries/21851651-API-trades-and-orderbook

        https://bitcoin-24.com/api/EUR/trades.json (with ?since=id parameter)
        https://bitcoin-24.com/api/USD/trades.json  (with ?since=id parameter)
     */
	public function get_trades($since = NULL, $eur = TRUE) {
        if($eur){
            $url = 'https://bitcoin-24.com/api/EUR/trades.json';
        }else{
            $url = 'https://bitcoin-24.com/api/USD/trades.json';
        }
        if(!is_null($since)){
            $url = $url.'?since='.$since;
        }
        return $this->curl_download($url);
    }
    
    /*
        https://bitcoin-24.com/api/EUR/orderbook.json
        https://bitcoin-24.com/api/USD/orderbook.json
     */
    public function get_orderbook($eur = TRUE){
        if($eur){
            $url= 'https://bitcoin-24.com/api/EUR/orderbook.json';
        }else{
            $url = 'https://bitcoin-24.com/api/USD/orderbook.json';
            
        }
        return $this->curl_download($url);
    }
}


?>