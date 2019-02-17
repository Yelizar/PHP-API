
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="author" content="">
    <link rel="icon" href="../../../../favicon.ico">

    <title>Price List</title>

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

</head>

<body>
<header>
    <div class="navbar navbar-dark bg-dark box-shadow">
        <div class="container d-flex justify-content-between">
            <a href="#" class="navbar-brand d-flex align-items-center">

                <strong>Reseller</strong> 
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarHeader" aria-controls="navbarHeader" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
    </div>
</header>

<main role="main">
    <section class="jumbotron ">
        <div class="container">
            <h1 class="jumbotron-heading">Tetramap products</h1>
            <h2 class="jumbotron-heading">Price List</h2>
        </div>
    </section>
    <div class="album py-5 bg-light">
        <div class="container">
            <div class="row">
            <?php
            $process = curl_init("https://student.tetramap.com/Api/Call/price_list.php");
            curl_setopt($process, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
            curl_setopt($process, CURLOPT_HEADER, FALSE);
            curl_setopt($process, CURLOPT_USERPWD, "Yelizar.Huryn@gmail.com" . ":" . "Elik1993");
            curl_setopt($process, CURLOPT_TIMEOUT, 30);
            curl_setopt($process, CURLOPT_POST, 1);
            curl_setopt($process, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($process, CURLOPT_SSL_VERIFYPEER, false);
            $return = curl_exec($process);
            curl_close($process);
            $data = json_decode($return, true);
            foreach ($data as $value => $key) {
                foreach ($key as $num => $item) {
                    if ($num == 0) {
                    } else {
                        echo "
                                <div class=\"col-md-4\">
                                    <div class=\"card mb-4 box-shadow\">
                                        <div class=\"card-body\">
                                            <ul class=\"list-unstyled mt-3 mb-4\">";
                        foreach ($item as $val => $tag) {
                            if ($val == "ProductImage") {

                                $image = file_get_contents($tag);
                                echo '<img src="' . $tag . '">';
                            }
                            if ($val == "ProductID") {
//                            echo "<li>Product Id ... $tag</li>";
                            }
                            if ($val == "ProductName") {
                                echo "<li><h5>$tag</h5></li>";
                            }
                            if ($val == "ProductDescription") {
                                echo "<li>$tag</li>";
                            }
                            if ($val == "ProductPrice") {
                                echo "<li><h4>$tag$</h4></li>";

                            }
                            if ($val == "MinimumQuantity") {
                                echo "<li> Minimum Quantity $tag</li>";

                            }
                        }
                        echo "</ul>
                    <div class=\"d-flex justify-content-between align-items-center\">
                                                <div class=\"btn-group\">
                                                    <button type=\"button\" class=\"btn btn-sm btn-outline-secondary\">Add to cart</button>
                                                </div>
                                                <small class=\"text-muted\">$value</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            ";
                    }
                }
            }
            ?>
            </div>
     </div>
    </div>

</main>

<footer class="text-muted">
    <div class="container">
        <p class="float-right">
            <a href="#">Back to top</a>
        </p>
        <p>Album example is &copy; Bootstrap, but please download and customize it for yourself!</p>
        <p>New to Bootstrap? <a href="/docs/4.0/">Visit the homepage</a> or read our <a href="/docs/4.0/getting-started/">getting started guide</a>.</p>
    </div>
</footer>

<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script>window.jQuery || document.write('<script src="../../../../assets/js/vendor/jquery-slim.min.js"><\/script>')</script>
<script src="../../../../assets/js/vendor/popper.min.js"></script>
<script src="../../../../dist/js/bootstrap.min.js"></script>
<script src="../../../../assets/js/vendor/holder.min.js"></script>
</body>
</html>