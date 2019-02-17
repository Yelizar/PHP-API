<?php
namespace Api\Storage;

use Api\Config\Main;
use Api\Config\Response;
use Api\Config\ResponseInterface;

class Product
{
    public $db;
    protected $table;
    public $PriceListInfo;
    protected $ProductId;
    protected $userInfo;
    protected $response;
    protected $image_way;
    protected $request;

    /**
     * Product constructor.
     * @param $userInfo
     */
    public function __construct($userInfo)
    {
        $this->db = Database::getInstance();
        $this->request = Main::processedRequest();
        $this->userInfo = $userInfo;
        $this->image_way = 'https://shop.student.tetramap.com/assets/';
        $this->table = array(
            "product" => "product",
            "price_list" => "pricelist",
            "price_list_item" => "pricelistitem",
            "product_category" => "productcategory",
            "currency" => "currency",
        );

    }

    /**
     * @return array|null
     */
    public function getPriceList()
    {
        if ($this->PriceListInfo = $this->getPriceListId()) {
            $stmt = $this->db->prepare(sprintf("SELECT 
                                                          PriceList.Product_ID,
                                                          PriceList.Price,
                                                          PriceList.MinimumQuantity,
                                                          Product.ProductName,
                                                          Product.ShortDescription,
                                                          Product.ProductImage,
                                                          Category.Category_ID
                                                          FROM %s PriceList 
                                                          JOIN %s Product ON PriceList.Product_ID = Product.id AND Product.VariationOnly != 1
                                                          JOIN %s Category ON Product.id = Category.Product_ID
                                                          WHERE  PriceList_ID = " . $this->PriceListInfo['id'] . " ",
                $this->table['price_list_item'], $this->table['product'], $this->table['product_category']));
            $stmt->execute();
            $num = $stmt->rowCount();

            if ($num > 0) {
                // products array
                $products_arr = array();
                $products_arr["Workbooks"] = array();
                $products_arr["Digital Suite"] = array();
                $products_arr["Supporting Materials"] = array();
                $products_arr["Events"] = array();

                // retrieve our table contents
                // fetch() is faster than fetchAll()
                while ($result = $stmt->fetch(\PDO::FETCH_ASSOC)) {
                    // extract row
                    extract($result);
                    $product_item = array(
                        "ProductID" => $result['Product_ID'],
                        "ProductName" => html_entity_decode(strip_tags($result['ProductName'])),
                        "ProductDescription" => html_entity_decode(strip_tags($result['ShortDescription'])),
                        "ProductImage" => $this->image_way . $result['ProductImage'],
                        "ProductPrice" => $result['Price'],
                        "Prefix" => $this->PriceListInfo["Prefix"],
                        "CurrencyName" => $this->PriceListInfo["CurrencyName"],
                        "MinimumQuantity" => $result['MinimumQuantity'],
                    );
                    if ($result['Category_ID'] == 1) {
                        array_push($products_arr['Workbooks'], $product_item);
                    }
                    if ($result['Category_ID'] == 2) {
                        array_push($products_arr["Digital Suite"], $product_item);
                    }
                    if ($result['Category_ID'] == 4) {
                        array_push($products_arr["Supporting Materials"], $product_item);
                    }
                    if ($result['Category_ID'] == 3) {
                        array_push($products_arr["Events"], $product_item);
                    }
                }
                return $products_arr;
            }

        }
        return null;
    }

    /**
     * @return array|null
     */
    public function getPriceListId(){

        $stmt = $this->db->prepare(sprintf("SELECT PriceList.id,
                                                           PriceList.FreightCost,
                                                           PriceList.UrgentCost,
                                                           PriceList.tax,
                                                          PriceList.currency_id,
                                                          Currency.CurrencyName,
                                                          Currency.Prefix
                                                          FROM %s PriceList
                                                          JOIN %s Currency ON PriceList.currency_id = Currency.id
                                                          WHERE  Region_ID = " . $this->userInfo['userRegion'] . " 
                                                          AND Group_ID = " . $this->userInfo['userGroup'] . " ",
                                                          $this->table['price_list'] , $this->table['currency']));
        $stmt->execute();
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        $num = $stmt->rowCount();


        if ($num > 0) {
            $PriceListInfo = array(
                "id" => $result['id'],
                "Currency_id" => $result['currency_id'],
                "CurrencyName" => $result["CurrencyName"],
                "Prefix" => $result["Prefix"],
                "FreightCost" => $result["FreightCost"],
                "UrgentCost" => $result["UrgentCost"],
                "Tax" => $result["tax"]
            );
            return $PriceListInfo;
        }

        return null;
    }

    /**
     * @param null $id
     * @return array|null
     */
    public function getProduct($id = null){
        $this->ProductId = is_null($id) ? $this->request->query("id") : $id;
        if ($this->PriceListInfo = $this->getPriceListId()) {
            $stmt = $this->db->prepare(sprintf("SELECT Product.id,
                                                          Product.ProductName,
                                                          Product.Description,
                                                          Product.ShortDescription,
                                                          Product.ProductImage,
                                                          PriceListItem.id as PriceItemID,
                                                          PriceListItem.Price,
                                                          PriceListItem.MinimumQuantity
                                                          FROM %s Product
                                                          JOIN %s PriceListItem
                                                          ON Product.id = PriceListItem.Product_ID 
                                                          AND PriceListItem.PriceList_ID = " . $this->PriceListInfo['id'] . " 
                                                          WHERE  Product.id = " . $this->ProductId . " ", $this->table['product'], $this->table['price_list_item']));
            $stmt->execute();
            //get retrieved row
            $result = $stmt->fetch(\PDO::FETCH_ASSOC);
            $product_arr = array();
            $product_arr['Product'] = array();
            //set value
            $num = $stmt->rowCount();
            if ($num > 0) {
                $product_item = array(
                    "ProductID" => $result['id'],
                    "ProductName" => $result['ProductName'],
                    "ProductDescription" => html_entity_decode(strip_tags($result['Description'])),
                    "ProductShortDescription" => html_entity_decode(strip_tags($result['ShortDescription'])),
                    "ProductImage" => $this->image_way . $result['ProductImage'],
                    "ProductPrice" => $result['Price'],
                    "Prefix" => $this->PriceListInfo["Prefix"],
                    "CurrencyName" => $this->PriceListInfo["CurrencyName"],
                    "MinimumQuantity" => $result['MinimumQuantity'],
                    "PriceItemID" => $result['PriceItemID']);
                array_push($product_arr["Product"], $product_item);
                return $product_arr;
            }
        }
        return null;
    }
}
