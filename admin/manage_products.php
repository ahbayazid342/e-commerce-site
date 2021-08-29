<?php
require('top_inc.php');
require('connection_inc.php');
require('function_inc.php');

//default value set
$categories_id = '';
$product_name = '';
$product_mrp = '';
$price = '';
$quantity = '';
$image = '';
$short_description = '';
$description = '';
$meta_title = '';
$meta_description = '';
$meta_keyword = '';

$msg = '';
$image_required = 'required';

if (isset($_GET['id']) && $_GET['id'] != '') {
    $image_required = '';
    $id = get_safe_value($conn, $_GET['id']);
    $sql = mysqli_query($conn, "SELECT * FROM product WHERE id = '$id'");

    $check = mysqli_num_rows($sql);
    if ($check > 0) {
        $rows = mysqli_fetch_assoc($sql);
        $categories_id = $rows['categories_id'];
        $product_name = $rows['product_name'];
        $product_mrp = $rows['product_mrp'];
        $price = $rows['price'];
        $quantity = $rows['quantity'];
        $short_description = $rows['short_description'];
        $description = $rows['description'];
        $meta_description = $rows['meta_description'];
        $meta_keyword = $rows['meta_keyword'];
    } else {
        header('location:products.php');
        die();
    }
}


if (isset($_POST['submit'])) {
    $categories_id = get_safe_value($conn, $_POST['categories_id']);
    $product_name = get_safe_value($conn, $_POST['product_name']);
    $product_mrp = get_safe_value($conn, $_POST['product_mrp']);
    $price = get_safe_value($conn, $_POST['price']);
    $quantity = get_safe_value($conn, $_POST['quantity']);
    $short_description = get_safe_value($conn, $_POST['short_description']);
    $description = get_safe_value($conn, $_POST['description']);
    $meta_description = get_safe_value($conn, $_POST['meta_description']);
    $meta_title = get_safe_value($conn, $_POST['meta_title']);
    $meta_keyword = get_safe_value($conn, $_POST['meta_keyword']);

    //check category already exist or not
    $query = "SELECT * FROM product WHERE product_name = '$product_name'";
    $res = mysqli_query($conn, $query);
    $check = mysqli_num_rows($res);
    if ($check > 0) {
        if (isset($_GET['id']) && $_GET['id'] != '') {
            $getData = mysqli_fetch_assoc($res);
            if ($id == $getData['id']) {
            } else {
                $msg = "product already exist";
            }
        } else {
            $msg = "product already exist";
        }
    }


    if ($msg == '') {

        //for product update
        if (isset($_GET['id']) && $_GET['id'] != '') {

            if ($_FILES['image']['name'] != '') {

                $image = rand(111111, 999999) . '_' . $_FILES['image']['name'];
                move_uploaded_file($_FILES['image']['tmp_name'], '../media/product/' . $image);

                $update_sql = " UPDATE product SET categories_id = '$categories_id', product_name = '$product_name', product_mrp = '$product_mrp', price = '$price', 
                            quantity = '$quantity', short_description = '$short_description', description = '$description', meta_description = '$meta_description', meta_title = '$meta_title',
                            meta_keyword = '$meta_keyword', image = '$image' WHERE id = '$id' ";
            } else {
                $update_sql = " UPDATE product SET categories_id = '$categories_id', product_name = '$product_name', product_mrp = '$product_mrp', price = '$price', 
                quantity = '$quantity', short_description = '$short_description', description = '$description', meta_description = '$meta_description', meta_title = '$meta_title',
                meta_keyword = '$meta_keyword' WHERE id = '$id' ";
            }
            mysqli_query($conn, $update_sql);
        }

        //for product add
        else {
            $image = rand(111111, 999999) . '_' . $_FILES['image']['name'];
            move_uploaded_file($_FILES['image']['tmp_name'], '../media/product/' . $image);
            $insert_query = "INSERT INTO product (categories_id,product_name,product_mrp,price,quantity, image, short_description,description,meta_description, meta_title, meta_keyword, status) VALUES ('$categories_id', '$product_name', '$product_mrp', '$price', '$quantity', '$image' , '$short_description',  '$description',  '$meta_description', '$meta_title',  '$meta_keyword', '1')";
            mysqli_query($conn, $insert_query);
        }
        header('location:products.php');
        die();
    }
}

?>

<div class="content pb-0">
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header"><strong>Product Form</strong></div>

                    <form method="post" enctype="multipart/form-data">
                        <div class="card-body card-block">

                            <div class="form-group"><label for="categories" class=" form-control-label">Categories</label>
                                <select class=" form-control" name="categories_id">
                                    <option>Select Categoty</option>
                                    <?php
                                    $query = mysqli_query($conn, 'SELECT id, categories_name FROM categories');
                                    while ($row = mysqli_fetch_assoc($query)) {
                                        if ($row['id'] == $categories_id) {
                                            echo "<option selected value=" . $row['id'] . ">" . $row['categories_name'] . "</option>";
                                        } else {
                                            echo "<option value=" . $row['id'] . ">" . $row['categories_name'] . "</option>";
                                        }
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="form-group"><label for="product_name" class=" form-control-label">product name</label>
                                <input type="text" name="product_name" id="company" placeholder="Enter your Product name" class="form-control" required value="<?php echo $product_name ?>">
                            </div>

                            <div class="form-group"><label for="product_mrp" class=" form-control-label">Product MRP </label>
                                <input type="text" name="product_mrp" id="company" placeholder="Enter your Product MRP" class="form-control" required value="<?php echo $product_mrp ?>">
                            </div>

                            <div class="form-group"><label for="price" class=" form-control-label">product Price</label>
                                <input type="text" name="price" id="company" placeholder="Enter your Product Proce" class="form-control" required value="<?php echo $price ?>">
                            </div>

                            <div class="form-group"><label for="quantity" class=" form-control-label">product Quantity</label>
                                <input type="text" name="quantity" id="company" placeholder="Enter your Product Quantity" class="form-control" required value="<?php echo $quantity ?>">
                            </div>

                            <div class="form-group"><label for="image" class=" form-control-label">product Image</label>
                                <input type="file" name="image" id="company" placeholder="Enter your Product Image" class="form-control" <?php echo $image_required ?> value="<?php echo $image ?>">
                            </div>

                            <div class="form-group"><label for="short_description" class=" form-control-label">Short Description</label>
                                <textarea name="short_description" id="company" placeholder="Enter Short Description" class="form-control" required><?php echo $short_description ?></textarea>
                            </div>

                            <div class="form-group"><label for="description" class=" form-control-label">Description</label>
                                <textarea name="description" id="company" placeholder="Enter Description" class="form-control" required><?php echo $description ?></textarea>
                            </div>

                            <div class="form-group"><label for="meta_title" class=" form-control-label">Meta Title</label>
                                <textarea name="meta_title" id="company" placeholder="Enter Meta Title" class="form-control" <?php echo $image_required ?>><?php echo $meta_title ?></textarea>
                            </div>

                            <div class="form-group"><label for="meta_description" class=" form-control-label">Meta Description</label>
                                <textarea name="meta_description" id="company" placeholder="Enter Meta Description" class="form-control" required><?php echo $meta_description ?></textarea>
                            </div>

                            <div class="form-group"><label for="meta_keyword" class=" form-control-label">Meta Keyword</label>
                                <textarea name="meta_keyword" id="company" placeholder="Enter Meta Keyword" class="form-control" required><?php echo $meta_keyword ?></textarea>
                            </div>

                            <button name='submit' id="payment-button" type="submit" class="btn btn-lg btn-info btn-block">
                                <span id="payment-button-amount">Submit</span>
                            </button>
                        </div>
                    </form>

                    <div class="field_error" style="margin-left: 22px; margin-bottom : 20px">
                        <?php echo $msg ?>
                    </div>



                </div>
            </div>
        </div>
    </div>
</div>

<?php
require('footer_inc.php');

?>