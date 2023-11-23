<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css" integrity="sha384-oS3vJWv+0UjzBfQzYUhtDYW+Pj2yciDJxpsK1OYPAYjqT085Qq/1cq5FLXAZQ7Ay" crossorigin="anonymous">
    <title>Cart</title>
    <link rel="icon" href="img/logo.jpg" type="image/x-icon">
    <style>
        #cont {
            min-height: 626px;
        }
    </style>
</head>

<body>
    <?php include 'partials/_dbconnect.php'; ?>
    <?php require 'partials/_nav.php' ?>
    <?php
    if ($loggedin) {
    ?>

        <div class="container" id="cont">
            <div class="row">
                <div class="alert alert-info mb-0" style="width: -webkit-fill-available;">
                    Thanh toán trực tuyến hiện đang bị vô hiệu hóa, vì vậy vui lòng chọn tiền mặt khi
                    giao hàng.
                </div>
                <div class="col-lg-12 text-center border rounded bg-light my-3">
                    <h1>Giỏ hàng</h1>
                </div>
                <div class="col-lg-8">
                    <div class="card wish-list mb-3">
                        <table class="table text-center">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">No.</th>
                                    <th scope="col">Món</th>
                                    <th scope="col">Giá</th>
                                    <th scope="col">Số lượng</th>
                                    <th scope="col">Đơn giá</th>
                                    <th scope="col">
                                        <form action="partials/_manageCart.php" method="POST">
                                            <button name="removeAllItem" class="btn btn-sm btn-outline-danger"> <img src="./assets/svg/bin.svg" alt=""> </button>
                                            <input type="hidden" name="userId" value="<?php $userId = $_SESSION['userId'];
                                                                                        echo $userId ?>">
                                        </form>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $sql = "SELECT * FROM `viewcart` WHERE `userId`= $userId";
                                $result = mysqli_query($conn, $sql);
                                $counter = 0;
                                $totalPrice = 0;
                                while ($row = mysqli_fetch_assoc($result)) {
                                    $foodId = $row['foodId'];
                                    $Quantity = $row['itemQuantity'];
                                    $mysql = "SELECT * FROM `food` WHERE foodId = $foodId";
                                    $myresult = mysqli_query($conn, $mysql);
                                    $myrow = mysqli_fetch_assoc($myresult);
                                    $foodName = $myrow['foodName'];
                                    $foodPrice = $myrow['foodPrice'];
                                    $discount = $myrow['discount'];
                                    $total = ($foodPrice - ($foodPrice * ($discount / 100))) * $Quantity;
                                    $counter++;
                                    $totalPrice = $totalPrice + $total;

                                    echo '<tr>
                                            <td>' . $counter . '</td>
                                            <td>' . $foodName . '</td>
                                            <td>' . $foodPrice . '</td>
                                            <td>
                                                <form id="frm' . $foodId . '">
                                                    <input type="hidden" name="foodId" value="' . $foodId . '">
                                                    <input type="number" name="quantity" value="' . $Quantity . '" class="text-center" onchange="updateCart(' . $foodId . ')" onkeyup="return false" style="width:60px" min=1 oninput="check(this)" onClick="this.select();">
                                                </form>
                                            </td>
                                            <td>' . $total . '</td>
                                            <td>
                                                <form action="partials/_manageCart.php" method="POST">
                                                    <button name="removeItem" class="btn btn-sm btn-outline-danger"><img
                                                    src="./assets/svg/bin.svg" alt=""></button>
                                                    <input type="hidden" name="itemId" value="' . $foodId . '">
                                                </form>
                                            </td>
                                        </tr>';
                                }
                                if ($counter == 0) {
                                ?><script>
                                        document.getElementById("cont").innerHTML =
                                            '<div class="col-md-12 my-5"><div class="card"><div class="card-body cart"><div class="col-sm-12 empty-cart-cls text-center"> <img src="https://i.imgur.com/dCdflKN.png" width="130" height="130" class="img-fluid mb-4 mr-3"><h3><strong>Giỏ hàng của bạn đang trống</strong></h3><h4>Hãy mua sắm cho bữa ăn của mình</h4> <a href="index.php" class="btn btn-primary cart-btn-transform m-3" data-abc="true">Tiếp tục</a> </div></div></div></div>';
                                    </script> <?php
                                            }
                                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card wish-list mb-3">
                        <div class="pt-4 border bg-light rounded p-3">
                            <h5 class="mb-3 text-uppercase font-weight-bold text-center">Thanh toán</h5>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between align-items-center border-0 px-0 pb-0 bg-light">
                                    Tổng<span><?php echo $totalPrice ?> VNĐ</span></li>
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0 bg-light">
                                    Phí giao hàng<span>15000</span></li>
                                <li class="list-group-item d-flex justify-content-between align-items-center border-0 px-0 mb-3 bg-light">
                                    <div>
                                        <strong>Tổng tiền phải trả</strong>

                                    </div>
                                    <span><strong><?php echo $totalPrice + 15000 ?> VNĐ</strong> </span>
                                </li>
                            </ul>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault1" checked>
                                <label class="form-check-label" for="flexRadioDefault1">
                                    Thanh toán khi nhận hàng
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="flexRadioDefault1" id="flexRadioDefault1" disabled>
                                <label class="form-check-label" for="flexRadioDefault1">
                                    Thanh toán online
                                </label>
                            </div><br>
                            <button type="button" class="btn btn-primary btn-block" data-toggle="modal" data-target="#checkoutModal">Đặt hàng</button>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="pt-4">
                            <a class="dark-grey-text d-flex justify-content-between" style="text-decoration: none; color: #050607;" data-toggle="collapse" href="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                                Bạn có mã giảm giá ?
                                <span><i class="fas fa-chevron-down pt-1"></i></span>
                            </a>
                            <div class="collapse" id="collapseExample">
                                <div class="mt-3">
                                    <div class="md-form md-outline mb-0">
                                        <input type="text" id="discount-code" class="form-control font-weight-light" placeholder="Enter discount code">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <?php
    } else {
        echo '<div class="container" style="min-height : 610px;">
        <div class="alert alert-info my-3">
            <font style="font-size:22px"><center>Before checkout you need to <strong><a class="alert-link" data-toggle="modal" data-target="#loginModal">Login</a></strong></center></font>
        </div></div>';
    }
    ?>
    <?php require 'partials/_checkoutModal.php'; ?>
    <?php require 'partials/_footer.php' ?>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous">
    </script>
    <script src="https://unpkg.com/bootstrap-show-password@1.2.1/dist/bootstrap-show-password.min.js"></script>
    <script>
        function check(input) {
            if (input.value <= 0) {
                input.value = 1;
            }
        }

        function updateCart(id) {
            $.ajax({
                url: 'partials/_manageCart.php',
                type: 'POST',
                data: $("#frm" + id).serialize(),
                success: function(res) {
                    location.reload();
                }
            })
        }
    </script>
</body>

</html>