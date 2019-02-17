<?php

namespace Api\Storage;

use Api\Config\Main;
use Api\Config\Response;

class Order
{
    public $db;
    protected $table;
    protected $userInfo;
    protected $response;
    protected $request;
    protected $optionsRequest;
    protected $productRequest;
    protected $addressRequest;
    protected $product;
    protected $order;
    protected $address;
    protected $productLines;
    protected $TotalPrice;

    /**
     * Order constructor.
     * @param $userInfo
     * @param Response $response
     */
    public function __construct($userInfo, Response $response)
    {
        $this->db = Database::getInstance();
        $this->request = Main::processedRequest();
        $this->response = $response;
        $this->userInfo = $userInfo;
        $this->product = new Product($userInfo);
        $this->address = array();
        $this->order = array();
        $this->productLines = array();
        $this->TotalPrice = array();
    }

    /**
     * @return Response
     */
    public function checkOrderSummary(){
        $this->productRequest = $this->request->request["Order"]["Products"];
        $this->addressRequest = $this->request->request["Order"]["Address"];
        $this->optionsRequest = $this->request->request["Order"]["Options"];
        if(is_array($this->productRequest) && is_array($this->addressRequest) && is_array($this->optionsRequest)) {
            if (!is_null($this->ProductDetails()) && !is_null($this->AddressDetails()) && !is_null($this->setOptions())) {
                if($Order_id = $this->insertOrder()) {
                    $this->insertOrderStatus($Order_id);
                    $this->insertOrderLines($Order_id);
                    $this->response->setStatusCode(201);
                    $this->response->setParameters(array("Order created" => ["SubTotal" => $this->TotalPrice['subTotalOrderPrice'], "Delivery" => $this->TotalPrice['Delivery'],
                        "Tax" => $this->TotalPrice['TaxAmount'], "Total" => $this->order['totalOrderPrice'], "Order Number" => $this->order['OrderNumber']]));
                    return $this->response;
                } else {
                    $this->response->setError(400, "Order is not added", "Please read documentation");
                }
            }
        }
        return $this->response;
    }

    /**
     * @return bool|null
     */
    protected function setOptions(){
        if ($this->optionsRequest['Urgent'] == 1) {
            $this->TotalPrice['Delivery'] = ($this->product->PriceListInfo["UrgentCost"] + $this->product->PriceListInfo["FreightCost"]);
            return true;
        } if ($this->optionsRequest['Urgent'] == 0) {
            $this->TotalPrice['Delivery'] = ($this->product->PriceListInfo["FreightCost"]);
            return true;
        }
        $this->response->setError(400, "Options invalid", "Urgent options is empty. Please read documentation");
        return null;
    }

    /**
     * @return bool|null
     */
    protected function ProductDetails(){
        foreach ($this->productRequest as $purchase) {
            $data = array();
            foreach ($purchase as $key => $value) {
                if ($key == "ProductID" && $value != null) {
                    $data["id"] = $value;
                } elseif ($key == "Quantity" && $value != null) {
                    $data["Quantity"] = $value;
                } else {
                    $this->response->setError(400, "Invalid Order", "Format of order is not correct, Please read documentation");
                    return null;
                }
            } if(!$this->validationIndividualProduct($data)){
                $this->response->setError(400, "Order summary failed ", "Product with id " . $data['id']. " dose not exist or Quantity less than minimum ");
                return null;
            }
        }
        return true;
    }

    /**
     * @param $data
     * @return bool|null
     */
    protected function validationIndividualProduct($data){
        if($product_ =  $this->product->getProduct($data['id'])){
            foreach ($product_['Product'] as $item){
                if ($item["MinimumQuantity"] <= $data['Quantity']) {
                    $this->countTotalPrice(($item['ProductPrice'] * $data['Quantity']));
                    $this->createProductOrderLine($item, $data['Quantity']);
                }
                else{
                    return null;
                }
            }
            return true;
        }
        return null;
    }

    protected function AddressDetails(){
        $pattern = array("Organization" => null, "Address" => null, "City" => null,
	    	            "State" => null, "Postcode" => null, "Phone" => null, "Name" => null,  "TaxNumber" => null);

        foreach ($this->addressRequest as $key=>$address) {
            if (count(array_intersect_key($pattern, $address)) == count($pattern)) {
                $this->createAddress($key, $address);
            } else {
                $this->response->setError(400, "Invalid Address", "Please read documentation");
                return null;
            }
        }
        $this->insertAddress();
        return true;
    }

    /**
     * @param $key
     * @param $address
     */
    protected function createAddress($key, $address){
        $this->address["$key"] = array(
            'User_ID' => $this->userInfo['userId'],
            'Company'=> $address['Organization'],
            'UserName' => $address['Name'],
            'Phone'=> $address['Phone'],
            'Address1' => $address['Address'],
            'Address2'=> 'null',
            'City' => $address['City'],
            'State'=> $address['State'],
            'Postcode' => $address['Postcode'],
            'Country_ID' => $this->userInfo['countryID'],
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            'Tax_Number' => $address['TaxNumber']);
    }

    /**
     *
     */
    protected function insertAddress(){
        $data = array("Address" => "INSERT INTO `address`(`User_ID`, 
                                                      `Company`, `Name`, `Phone`, `Address1`, `Address2`,
                                                      `City`, `State`, `Postcode`, `Country_ID`, `created_at`, `updated_at`, `Tax_Number`)
                                                      VALUES (:User_ID,
                                                      :Company, :UserName, :Phone, :Address1, :Address2,
                                                      :City, :State, :Postcode, :Country_ID, :created_at, :updated_at, :Tax_Number)");

//        $stmt = $this->db->prepare($data['Address']);
        foreach ($this->address as $key=>$item) {
//            $stmt->execute($item);
            if ($this->optionsRequest["OneAddress"] == 1) {
                $this->createOrder("ShippingAddress_ID", '1537');
                $this->createOrder("BillingAddress_ID", '1537');
                break;
            } elseif ($this->optionsRequest["OneAddress"] == 0 && $key == "ShippingAddress") {
                $this->createOrder("ShippingAddress_ID", $this->db->lastInsertId());
            } elseif ($this->optionsRequest["OneAddress"] == 0 && $key == "BillingAddress") {
                $this->createOrder("BillingAddress_ID", $this->db->lastInsertId());
            } else {
                $this->response->setError(400, "Invalid Address", "Please read documentation. Invalid format of address");
            }
        }
    }

    /**
     * @param $name
     * @param $parameter
     */
    protected function createOrder($name, $parameter){
        $this->order += [$name=>$parameter];
    }

    /**
     * @return mixed
     */
    protected function insertOrder(){
        $data = array("ProductOrder" => "INSERT INTO `productorder`(`PaymentMethod_ID`, `User_ID`,
                                           `ShippingAddress_ID`, `BillingAddress_ID`, `Currency_ID`, `OrderNumber`, `FreightCost`, `TaxRate`,
                                           `OrderComments`,  `created_at`, `updated_at`, `state`, `urgent`, `totalOrderPrice`)
                                            VALUES (:PaymentMethod_ID, :User_ID,
                                            :ShippingAddress_ID, :BillingAddress_ID, :Currency_ID, :OrderNumber, :FreightCost, :TaxRate, 
                                            :Comments, :created_at, :updated_at, :state, :urgent, :totalOrderPrice)");

        $this->createOrder("Currency_ID", $this->product->PriceListInfo["Currency_id"]);
        $this->createOrder("Comments", $this->optionsRequest['Comments']);
        $this->createOrder("User_ID", $this->userInfo["userId"]);
        $this->createOrder("FreightCost", $this->product->PriceListInfo["FreightCost"]);
        $this->createOrder("urgent", $this->optionsRequest["Urgent"]);
        $this->createOrder("TaxRate", $this->product->PriceListInfo["Tax"]);
        $this->createOrder("PaymentMethod_ID", 2);
        $this->createOrder("OrderNumber", $this->orderNumber());
        $this->createOrder("created_at", date('Y-m-d H:i:s'));
        $this->createOrder("updated_at", date('Y-m-d H:i:s'));
        $this->createOrder("state", "fulfilled");
        $this->TotalPrice['TaxAmount'] =  round(($this->TotalPrice['subTotalOrderPrice'] + $this->TotalPrice['Delivery']) * (0.01 * $this->product->PriceListInfo["Tax"]), 2, PHP_ROUND_HALF_UP);
        $this->order['totalOrderPrice'] = $this->TotalPrice['subTotalOrderPrice'] + $this->TotalPrice['TaxAmount'] + $this->TotalPrice['Delivery'];

        $stmt = $this->db->prepare($data['ProductOrder']);
        $stmt->execute($this->order);
        return $this->db->lastInsertId();
    }

    /**
     * @param $product
     * @param $quantity
     */
    protected function createProductOrderLine($product, $quantity){
        $this->productLines["{$product["ProductID"]}"] = array(
            "Product_ID" => $product['ProductID'],
            "ProductName" => $product['ProductName'],
            "ProductDescription" => $product['ProductShortDescription'],
            "UnitPrice" => $product['ProductPrice'],
            "Quantity" => $quantity,
            "Variation" => "0",
            "created_at" => date('Y-m-d H:i:s'),
            "tax_amount" => round(($product['ProductPrice'] * $quantity) * (0.01 * $this->product->PriceListInfo["Tax"]), 3, PHP_ROUND_HALF_UP),
            "PriceListItem_ID" => $product['PriceItemID']
            );
    }

    /**
     * @param $orderID
     */
    protected function insertOrderLines($orderID){
        $data = array("ProductOrderLine" => "INSERT INTO `productorderline`(`ProductOrder_ID`, `Product_ID`,
                                               `ProductName`, `ProductDescription`, `UnitPrice`, `Quantity`, 
                                               `Variation`, `created_at`,
                                               `tax_amount`, `PriceListItem_ID`)
                                               VALUES (:ProductOrder_ID, :Product_ID,
                                               :ProductName, :ProductDescription, :UnitPrice, :Quantity,
                                               :Variation, :created_at,
                                               :tax_amount, :PriceListItem_ID)");
        foreach ($this->productLines as $item){
            $item +=["ProductOrder_ID" => $orderID];
            $stmt = $this->db->prepare($data["ProductOrderLine"]);
            $stmt->execute($item);
            }
    }

    /**
     * @param $Order_ID
     * @param null $OrderStatus
     */
    protected function insertOrderStatus($Order_ID, $OrderStatus = null){
        $OrderStatus = is_null($OrderStatus) ? 4 : $OrderStatus;
        $data = array("OrderStatus" => "INSERT INTO `productorderstatus`(`OrderStatus_ID`, `ProductOrder_ID`,
                                             `IsActive`, `created_at`)
                                              VALUES (:OrderStatus_ID,:ProductOrder_ID,
                                              :IsActive, :created_at)");
        $data['Values'] = array(
            "OrderStatus_ID" => $OrderStatus,
            "ProductOrder_ID" => $Order_ID,
            "IsActive" => 1,
            "created_at" => date('Y-m-d H:i:s')
        );
        $stmt = $this->db->prepare($data['OrderStatus']);
        $stmt->execute($data['Values']);
    }

    /**
     * @return string
     */
    protected function orderNumber(){
        $stmt = $this->db->prepare(sprintf("SELECT `OrderNumber`
                                                    FROM `productorder`
                                                    WHERE User_ID = " . $this->userInfo["userId"]. "
                                                    ORDER BY `created_at`
                                                    DESC LIMIT 1"));
        $stmt->execute();
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        $OrderNumber = $result["OrderNumber"];
        if(is_null($OrderNumber)){
            return "API-" . $this->userInfo["userId"] . "-A";
        }
        return ++$OrderNumber;
    }

    /**
     * @param $total
     */
    protected function countTotalPrice($total){
        $this->TotalPrice['subTotalOrderPrice'] += $total;
    }

    /**
     * @return array|null
     */
    public function getOrderList()
    {
        echo $this->userInfo['userId'];
        $stmt = $this->db->prepare(sprintf("SELECT porder.OrderNumber, porder.FreightCost, porder.TaxRate,
                                                          porder.OrderComments, pstatus.created_at, porder.FreightUrgent,
                                                          porder.urgent, porder.totalOrderPrice, 
                                                          pline.ProductName,
                                                          pstatus.OrderStatus_ID,
                                                          orderstatus.OrderStatus
                                                    FROM productorder porder
                                                    RIGHT JOIN productorderline pline ON porder.id = pline.ProductOrder_ID
                                                    RIGHT JOIN productorderstatus pstatus ON porder.id = pstatus.ProductOrder_ID
                                                    RIGHT JOIN orderstatus ON pstatus.OrderStatus_ID = orderstatus.id 
                                                    WHERE porder.User_ID = " . $this->userInfo['userId'] . "
                                                    Order By created_at DESC"));

        $stmt->execute();

        //set value
        $num = $stmt->rowCount();

        if ($num > 0) {
            // products array
            $orderList = array();
            $orderList["Order List"] = array();
            $order = array();
            // retrieve our table contents
            // fetch() is faster than fetchAll()

            while ($result = $stmt->fetch(\PDO::FETCH_ASSOC)) {
                // extract row
                if($order[$result['OrderNumber']]['OrderStatus'] != $result['OrderStatus']){
                    $order['Order List'][$result['OrderNumber']]['OrderStatus'] = $result['OrderStatus'];
                }
                if (!array_key_exists($result['OrderNumber'], $order)) {
                    $order[$result['OrderNumber']] = array(
                        "OrderNumber" => $result['OrderNumber'],
                        "FreightCost" => $result['FreightCost'],
                        "TaxRate" => $result['TaxRate'],
                        "OrderComments" => $result["OrderComments"],
                        "created_at" => $result['created_at'],
                        "totalOrderPrice" => $result['totalOrderPrice'],
                        "OrderStatus" => $result['OrderStatus'],
                        "ProductName" => array($result['ProductName']));
                    array_push($order['Order List'], $order);
                }
                elseif($order[$result['OrderNumber']]['OrderStatus'] == $result['OrderStatus'] && !in_array($order[$result['OrderNumber']]['ProductName'], $result['ProductName'])) {
                    array_push($order[$result['OrderNumber']]['ProductName'], $result['ProductName']);
                }

            }
            unset($order['Order List']);
            array_push($orderList['Order List'], $order);

            return $orderList;
        }
        return null;
    }

    /**
     * @return Response
     */
    public function changeOrderStatus(){
        $this->productRequest = $this->request->request['Order'];
        $orderID = $this->getOrderID();
        print_r($this->productRequest);
        if($this->productRequest['Status'] == 12) {
            $this->insertOrderStatus($orderID, $this->productRequest['Status']);
            $this->response->setStatusCode(202);
            $this->response->setParameter("Order Status", "Canceled");
            return $this->response;
        }
         else{
             $this->response->setError(404, "Unavailable Status Code", "Please read documentation");
        }
         return $this->response;
    }

    /**
     * @return mixed
     */
    public function getOrderID(){
        $stmt = $this->db->prepare(sprintf("SELECT id
                                                    FROM productorder 
                                                    WHERE OrderNumber = '" . $this->productRequest['Number'] . "' 
                                                    AND User_ID = " . $this->userInfo['userId'] . " "));
        $stmt->execute();
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        extract($result);
        return $orderID = $result['id'];
    }



}

//        echo var_dump($this->optionsRequest);
//        echo var_dump($this->productRequest);
//        echo var_dump($this->addressRequest);