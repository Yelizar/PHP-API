<!DOCTYPE html>
<html lang="en">
<head>
  <title>Tetramap API</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" href="img/icon.jpg">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="css1.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script> 
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <style>
    /* Set height of the grid so .sidenav can be 100% (adjust if needed) */
    .row.content {height: 1500px}
    
    /* Set gray background color and 100% height */
    .sidenav {
      background-color: #f1f1f1;
      height: 100%;
    }
    
    /* Set black background color, white text and some padding */
    footer {
/*         background-image: url('img/tetramap.png');*/
      background-color: #555;
      color: white;
      padding: 15px;
    }
    
    /* On small screens, set height to 'auto' for sidenav and grid */
    @media screen and (max-width: 767px) {
      .sidenav {
        height: auto;
        padding: 15px;
      }
      .row.content {height: auto;} 
    }
   .col-sm-9.bgimg{ 
        background-image: url('img/tetramap.png');
       
    }
  </style>
</head>
<body>
<div class="header">
    <a href="#default" class="logo"><img src="img/TETRALOGO.PNG" alt="Smiley face" height="50" width="120">TETRAMAP</a>
  <div class="header-right">
    <a href="https://www.tetramap.com/">Home</a>
    <a class="active" href="#" onclick="window.location.reload(true);">API</a>
    <a href="https://www.tetramap.com/contact-us/">Contact</a>
    <a href="https://www.tetramap.com/who-we-are/our-story/">About</a>
  </div>
</div>
<div class="container-fluid">
  <div class="row content">
      <div class="col-sm-3 sidenav" >
          <h4><a name=11>API DOCUMENTATION</a></h4>
      <ul class="nav nav-pills nav-stacked">
        <li><a href="#1">BASIC AUTHENTICATION</a></li>
        <li><a href="#2">PRICELIST</a></li>
        <li><a href="#3">PRODUCTS</a></li>
        <li><a href="#4">ORDER</a></li>
        <li><a href="#5">ORDER LIST</a></li>
        <li><a href="#6" >ORDER STATUS</a></li>
       
        
      </ul><br>
<!--      <div class="input-group">
        <input type="text" class="form-control" placeholder="Search Keywords..">
        <span class="input-group-btn">
          <button class="btn btn-default" type="button">
            <span class="glyphicon glyphicon-search"></span>
          </button>
        </span>
      </div>-->
    </div>

    <div class="col-sm-9" >
     <h2>HOW TO USE OUR API ?</h2>
      
      
      <p>Type the following URL in your browser or API Previewer:
      <br><hr> https://tetramap.com/Api/Call/page.php*<br>
      *The last part of the url have to type the name of page. example:order.php<br>
      Every API call made by the user includes Basic Authentication<hr> </p>
        
      <h2><a name="1" style="color:black;">BASIC AUTHENTICATION</a> </h2>
      
      <!--<h5><span class="label label-danger">Food</span> <span class="label label-primary">Ipsum</span></h5><br>-->
      <p>Tetramap company uses basic authentication to identify the user and region. For using the API the user has to login with their username and password. The username and password is the one that are using for www.tetramap.com .</p>
       Methods supported &nbsp; <b>GET</b> 
      
      
      <hr>
      <h2><a name="2" style="color:black;">PRICELIST</a></h2>
      <br>
      <h4>API Call Overview</h4>
      <br>
      
      <table>
          <tr>
             <td> Description</td>
             <td> <b>&nbsp;This Api call will return the list of products with prices according to the user region. </b> </td>
          </tr>
          <tr> 
              <td> URL</td> 
              <td> <b>&nbsp;https://tetramap.com/Api/Call/price_list.php</b></td>
          </tr> 
          <tr>
              <td> Methods supported </td>
              <td> <b>&nbsp;GET</b> </td>
          </tr>
          <tr>
              <td valign="top"> Example of Output </td>
              <td> <b>&nbsp;<img src="img/Capture.JPG"</b> </td>
          </tr>
      </table>
      <br>
      <br>
      <h4> Parameter description </h4>
      <br>
      <table>
          <tr>
              <td>1.Workbooks,Digital Suite,Supporting Materials,Events</td>
              <td>&nbsp;&nbsp;&nbsp;Category of books provided by the tetramap company.</td>  
          </tr>
          <tr>
              <td>2.ProductID</td>
              <td>&nbsp;&nbsp;&nbsp;ID of the product which is <b> Integer Data type</b></td>
          </tr>
          <tr>
              <td>3.ProductName</td>
              <td>&nbsp;&nbsp;&nbsp;Name of the book.</td>
          </tr>
          <tr>
              <td>4.ProductDescription</td>
              <td>&nbsp;&nbsp;&nbsp;A small description about the book. </td>
          </tr>
          <tr>
              <td>5.ProductImage</td>
              <td>&nbsp;&nbsp;&nbsp;Image of the book.</td>
          </tr>
          <tr>
              <td>6.ProductPrice</td>
              <td>&nbsp;&nbsp;&nbsp;Price of the book which is <b>Float Data type</b>.</td>
          </tr>
          <tr>
              <td>7.Prefix</td>
              <td>&nbsp;&nbsp;&nbsp;Currency Symbol.</td>
          </tr>
          <tr>
              <td>8.CurrencyName</td>
              <td>&nbsp;&nbsp;&nbsp;Name of currency used.</td>
          </tr>
          <tr>
              <td>9.MinimumQuantity</td>
              <td>&nbsp;&nbsp;&nbsp;Minimum Quantity of books to be purchased which is <b>Integer Data type</b></td>
          </tr>
      </table>
      <hr>
      <h2><a name="3" style="color:black;">PRODUCTS</a></h2><p align="right"> <a href=#11>Back to Top</a> </p>
      <br>
      <h4>API Call Overview </h4>
      <br>
      <table>
          <tr>
             <td> Description</td>
             <td> <b>&nbsp;This Api call will return the product information </b> </td>
          </tr>
          <tr> 
              <td> URL</td> 
              <td> <b>&nbsp;https://tetramap.com/Api/Call/product.php?id=2*</b><br>*After the product.php, please specify the parameter id with value. For getting the <b>ID</b>, you can go with the API call Pricelist</td>
          </tr> 
          <tr>
              <td> Methods supported </td>
              <td> <b>&nbsp;GET</b> </td>
          </tr>
          <tr>
              <td valign="top"> Example of Output </td>
              <td> <b>&nbsp;<img src="img/Capture_product.JPG"</b> </td>
          </tr>
      </table>
      <br>
      <br>
      <h4> Parameter description </h4>
      <br>
      <table>
         
          <tr>
              <td>1.ProductID</td>
              <td>&nbsp;&nbsp;&nbsp;ID of the product which is <b> Integer Data type</b></td>
          </tr>
          <tr>
              <td>2.ProductName</td>
              <td>&nbsp;&nbsp;&nbsp;Name of the book.</td>
          </tr>
          <tr>
              <td>3.ProductDescription</td>
              <td>&nbsp;&nbsp;&nbsp;Full Description about the book. </td>
          </tr>
          <tr>
              <td>4.ProductShortDescription</td>
              <td>&nbsp;&nbsp;&nbsp;A Short Description about the book.</td>
          </tr>
           <tr>
              <td>5.ProductImage</td>
              <td>&nbsp;&nbsp;&nbsp;Image of the book.</td>
          </tr>
          <tr>
              <td>6.ProductPrice</td>
              <td>&nbsp;&nbsp;&nbsp;Price of the book which is <b>Float Data type</b>.</td>
          </tr>
          <tr>
              <td>7.Prefix</td>
              <td>&nbsp;&nbsp;&nbsp;Currency Symbol.</td>
          </tr>
          <tr>
              <td>8.CurrencyName</td>
              <td>&nbsp;&nbsp;&nbsp;Name of currency used.</td>
          </tr>
          <tr>
              <td>9.MinimumQuantity</td>
              <td>&nbsp;&nbsp;&nbsp;Minimum Quantity of books to be purchased which is <b>Integer Data type</b></td>
          </tr>
      </table>
      <hr>
      <h4><small> <a name="4" style="color:black;"><h2>ORDERS</h2></a></small></h4><p align="right"> <a href=#11>Back to Top</a> </p>
      <br>
      <h4>API Call Overview</h4>
      <br>
      <table>
          <tr>
             <td> Description</td>
             <td> <b>&nbsp;This Api call will create the orders </b> </td>
          </tr>
          <tr> 
              <td> URL</td> 
              <td> <b>&nbsp;https://tetramap.com/Api/Call/order.php</b></td>
          </tr> 
          <tr>
              <td> Methods supported </td>
              <td> <b>&nbsp;POST,PUT</b> </td>
          </tr>
          <tr>
              <td valign="top"> Example of Creating order </td>
              <td> <b>&nbsp;<img src="img/Capture_order.JPG"</b> </td>
          </tr>
          <tr>
              <td valign="top"> Example of Order Response </td>
              <td> <b>&nbsp;<img src="img/Capture_order_response.JPG"</b> </td>
          </tr>
      </table>
      <br>
      <br>
      <h4> Parameter description </h4>
      <br>
      <table>
         
          <tr>
              <td>1.Options</td>
              <td>&nbsp;&nbsp;&nbsp;Available Options for the order which have three parameters.</td>
          </tr>
          <tr>
              <td></td>
              <td valign="Top">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;A.Urgent</td>
              <td>&nbsp;&nbsp;&nbsp;These parameter have 2 values which is <b>Integer Data Type</b>:<br>&nbsp;&nbsp;&nbsp;<b>1</b>- Which means Urgent delivery.<br>&nbsp;&nbsp;&nbsp;<b>0</b>- Which means Not Urgent delivery.</td>
          </tr>
          <tr>
              <td></td>
              <td valign="Top">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;B.Comments</td>
              <td>&nbsp;&nbsp;&nbsp;Comments about the order.</td>
          </tr>
          <tr>
              <td></td>
              <td valign="Top">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;C.OneAddress</td>
              <td>&nbsp;&nbsp;&nbsp;These parameter have 2 values which is <b>Integer Data Type</b>:<br>&nbsp;&nbsp;&nbsp;<b>1</b>- Which means both shipping and billing address are same.<br>&nbsp;&nbsp;&nbsp;<b>0</b>- Which means both shipping and billing address are different.</td>
          </tr>
           <tr>
              <td>2.Products</td>
              <td>&nbsp;&nbsp;&nbsp;Details of Products ordered which have 2 parameters.</td>
              <td></td>
          </tr>
          <tr>
              <td></td>
              <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;A.ProductID</td>
              <td>&nbsp;&nbsp;&nbsp;ID of the product which is <b>Integer Data type</b>.</td>
          </tr>
          <tr>
              <td></td>
              <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;B.Quantity</td>
              <td>&nbsp;&nbsp;&nbsp;Quantity of books purchased which is <b>Integer Data type</b>.</td>
          </tr>
          <tr>
              <td valign="top">3.Address</td>
              <td>&nbsp;&nbsp;&nbsp;Billing Address and shipping Address which have Following parameters:<br>
                  <ul>
                      <li>Organization</li>
                      <li>Address</li>
                      <li>City</li>
                      <li>State</li>
                      <li>Postcode</li>
                      <li>Phone</li>
                      <li>Name</li>
                      <li>TaxNumber</li>
                  </ul></td>
                  <td></td>
          </tr>
          
      </table>
      <hr>
      <h4><small> <a name="5" style="color:black;"><h2>ORDER LIST</h2></a></small></h4><p align="right"> <a href=#11>Back to Top</a> </p>
      <br>
      <h4>API Call Overview</h4>
      <br>
      <table>
          <tr>
             <td> Description</td>
             <td> <b>&nbsp;This Api call will show the list of orders made by the user.</b> </td>
          </tr>
          <tr> 
              <td> URL</td> 
              <td> <b>&nbsp;https://tetramap.com/Api/Call/order_list.php</b></td>
          </tr> 
          <tr>
              <td> Methods supported </td>
              <td> <b>&nbsp;GET</b> </td>
          </tr>
          <tr>
              <td valign="top"> Example of Output </td>
              <td> <b>&nbsp;<img src="img/Capture_orderlist.JPG"</b> </td>
          </tr>
      </table>
      <br>
      <br>
      <h4> Parameter description </h4>
      <br>
      <table>
         
          <tr>
              <td>1.OrderNumber</td>
              <td>&nbsp;&nbsp;&nbsp;Order number starting with user id</td>
          </tr>
          <tr>
              <td>2.FreightCost</td>
              <td>&nbsp;&nbsp;&nbsp;Transportation Cost which varies from country to country and it is <b>Float Data type</b>.</td>
          </tr>
          <tr>
              <td>3.TaxRate</td>
              <td>&nbsp;&nbsp;&nbsp;Tax cost which varies from place to place and it is <b>Float Data type</b>.</td>
          </tr>
          <tr>
              <td>4.OrderComments</td>
              <td>&nbsp;&nbsp;&nbsp;Any instructions or comments about the order.</td>
          </tr>
           <tr>
              <td>5.created_at</td>
              <td>&nbsp;&nbsp;&nbsp;Date and time of order created.</td>
          </tr>
          <tr>
              <td>6.totalOrderPrice</td>
              <td>&nbsp;&nbsp;&nbsp;Total price of order which is <b>Float Data type</b>.</td>
          </tr>
          <tr>
              <td valign="top">7.OrderStatus</td>
              <td>&nbsp;&nbsp;&nbsp;Status of order which have following values:
                  <ul>
                      <li>Processing</li>
                      <li>Completed</li>
                      <li>Cancelled</li>
                  </ul></td>
          </tr>
          <tr>
              <td>8.ProductName</td>
              <td>&nbsp;&nbsp;&nbsp;Name of products/books.</td>
          </tr>
          
      </table>
      <hr>
      <h4><small> <a name="6" style="color:black;"><h2>ORDER STATUS</h2></a></small></h4> <p align="right"> <a href=#11>Back to Top</a> </p>
      <br>
      <h4>API Call Overview</h4>
      <br>
      <table>
          <tr>
             <td> Description</td>
             <td> <b>&nbsp;This Api call is used to cancel the orders.</b> </td>
          </tr>
          <tr> 
              <td> URL</td> 
              <td> <b>&nbsp;https://tetramap.com/Api/Call/order_status.php</b></td>
          </tr> 
          <tr>
              <td> Methods supported </td>
              <td> <b>&nbsp;PUT,POST</b> </td>
          </tr>
          <tr>
              <td valign="top"> Example of Cancelling an order </td>
              <td> <b>&nbsp;<img src="img/Capture_order_status.JPG"</b> </td>
          </tr>
          <tr>
              <td valign="top"> Example of response on order status </td>
              <td> <b>&nbsp;<img src="img/Capture_order_status_response.JPG"</b> </td>
          </tr>
      </table>
      <hr>
     
      <br><br>
      
<!--      <p><span class="badge">2</span> Comments:</p><br>
      
      <div class="row">
        <div class="col-sm-2 text-center">
          <img src="bandmember.jpg" class="img-circle" height="65" width="65" alt="Avatar">
        </div>
        <div class="col-sm-10">
          <h4>Anja <small>Sep 29, 2015, 9:12 PM</small></h4>
          <p>Keep up the GREAT work! I am cheering for you!! Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
          <br>
        </div>
        <div class="col-sm-2 text-center">
          <img src="bird.jpg" class="img-circle" height="65" width="65" alt="Avatar">
        </div>
        <div class="col-sm-10">
          <h4>John Row <small>Sep 25, 2015, 8:25 PM</small></h4>
          <p>I am so happy for you man! Finally. I am looking forward to read about your trendy life. Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
          <br>
          <p><span class="badge">1</span> Comment:</p><br>
          <div class="row">
            <div class="col-sm-2 text-center">
              <img src="bird.jpg" class="img-circle" height="65" width="65" alt="Avatar">
            </div>
            <div class="col-xs-10">
              <h4>Nested Bro <small>Sep 25, 2015, 8:28 PM</small></h4>
              <p>Me too! WOW!</p>
              <br>
            </div>
          </div>
        </div>
      </div>-->
    </div>
  </div>
</div>

<footer class="container-fluid">
   <div class="col-sm-12">
				<h4>Contact Details</h4>
<div class="col-sm-2  contact">
<p><strong>TetraMap International</strong><br>
Head Office<br>
Auckland, New Zealand<br>
Tel:<a href="tel:+64-22-386-4414">+64 22 386 4414</a></p>
</div>
<div class="col-sm-2  contact">
<p><strong>TetraMap UK Limited</strong><br>
Fulfilment: Stockport, UK<br>
<a href="https://www.tetramap.com/uk/">www.tetramap.com/uk/</a></p>
</div>
<div class="col-sm-2  contact">
<p><strong>TetraMap Latin America</strong><br>
Monterrey, Mexico<br>
Tel:<a href="tel:+52-81-8040-1071">+52 (81) 8040 1071</a><br>
<a href="https://www.tetramap.com/read/latin+america">TetraMap Latin America</a></p>
</div>
<div class="col-sm-2  contact">
<p><strong>TetraMap United States</strong><br>
Orlando, Florida<br>
Tel:<a href="tel:+1-321-281-8383">+1 (321) 281 8383</a><br>
<a href="https://www.tetramap.us">TetraMap United States</a></p>
</div>
<div class="col-sm-2  contact">
<p><a href="https://www.tetramap.com/international/">All Regional Contacts</a></p>
</div>
<div class="col-sm-2  contact">
<p>&nbsp;</p>
</div>			</div>
    <p align="right">© Copyright 2018 Free Bootstrap Templates - All Rights Reserved</p>
</footer>

</body>
</html>
