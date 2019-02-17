<?php

namespace  Api\Config;


use Api\Storage\Order;
use Api\Storage\Product;

require "../swiftmailer/lib/swift_required.php";


class Main
{

    public $response;
    protected static $processedRequest = null;
    protected $userInfo;


    /**
     * Main constructor.
     * @param Response|null $response
     */
    public function __construct(Response $response = null)
    {
        $this->response = is_null($response) ? new Response() : $response;
        $HttpBasic = new HttpBasic();
        if (!$this->userInfo = $HttpBasic->validateRequest($this->response)){
            $this->sendResponse(null);
            exit();
        }

    }


    public static function processedRequest(){
        if(!self::$processedRequest)
        {
            self::$processedRequest = Request::createFromGlobals();
        }
        return self::$processedRequest;
    }


    public function getPriceList(){
        $priceList = new Product($this->userInfo);
        $result = $priceList->getPriceList();
        $this->sendResponse($result);
    }

    public function getProductInfo(){
        $product = new Product($this->userInfo);
        if(!$result = $product->getProduct()){
            $this->response->setError(404, "Not found", "Product does not exit or not available in your region");
            $this->sendResponse(null);
        }
        else {
            array_pop($result['Product']['0']);
            $this->sendResponse($result);
        }
    }

    public function createOrder(){
        $order = new Order($this->userInfo, $this->response);
        $this->response = $order->checkOrderSummary();
        if(!$this->response->parameters['error']) {
            $this->sendMail("Order Created", "Thank you, Your order has been added. Order number" . $this->response->parameters['Order created']['Order Number'] . " Cheers");
        }
        $this->sendResponse(null);

    }

    public function getOrderList(){
        $order = new Order($this->userInfo, $this->response);
        if(!$result = $order->getOrderList()){
            $this->response->setError(404, "Not found", "You do not have any order yet.");
            $this->sendResponse(null);
        }
        else {
            $this->sendResponse($result);
        }

    }

    public function orderStatus()
    {
        $order = new Order($this->userInfo, $this->response);
        $this->response = $order->changeOrderStatus();
        if(!$this->response->parameters['error']) {
            $this->sendMail("Order Status", "Thank you, Your order status has been canceled.");
        }
        $this->sendResponse(null);
    }


    /**
     * @param $result
     */
    public function sendResponse($result){
        if($result != null) {
            $this->response->setParameters($result);

        }
        $this->wh_log();
        $this->response->send();
    }

    public function sendMail($subject, $body){

        $transport = (new \Swift_SmtpTransport('smtp.gmail.com', 465, 'ssl'))
            ->setUsername('orders@tetramap.com')
            ->setPassword('V9u3g37VnVpetcu9');
        $mailer = new \Swift_Mailer($transport);

        $message = (new \Swift_Message($subject))
            ->setFrom(['orders@tetramap.com' => 'Tetramap.com'])
            ->setTo([$this->userInfo['UserName'] => $this->userInfo['FirstName'] . $this->userInfo['LastName']])
            ->setBody($body);

        $mailer->send($message);
    }

    function wh_log()
    {

        $log  = ' . - '.date("F j, Y, g:i a").PHP_EOL.
            "User: ".$_SERVER['REMOTE_ADDR'] . " --------- " . "API - ".$_SERVER['REQUEST_URI'] .PHP_EOL.
            "Method - ".$_SERVER['REQUEST_METHOD'] .PHP_EOL.
            "Attempt: ".(!$this->response->parameters['error']?'Success':'Failed:  ' .
            $this->response->parameters['error'] . "  " .$this->response->parameters['error_description'] ).PHP_EOL.
            "User Name: ".$this->userInfo['UserName'].PHP_EOL.
            "-------------------------".PHP_EOL;
        $log_filename = "/log";
        if (!file_exists($log_filename))
        {
            mkdir(dirname(__FILE__) . $log_filename, 0777, true);
        }
        $log_file_data = dirname(__FILE__) . $log_filename.'/log_' . date('d-M-Y') . '.log';
        $file = fopen($log_file_data, "a");
        fwrite($file, $log);
        fclose($file);
    }
}

