
<?php include ('layouts/header.php');?>

<?php



include('server/connection.php');

if(!isset($_SESSION['logged_in'])){
  header('location: login.php');
  exit;
}


if(isset($_GET['logout'])){
    if(isset($_SESSION['logged_in'])){
       unset($_SESSION['logged_in']);
       unset($_SESSION['user_email']);
       unset($_SESSION['user_name']);
       header('location: login.php');
       exit;
}

}

if(isset($_POST['change_password'])){
  $password = $_POST['password'];
  $confirmPassword = $_POST['confirmPassword'];
  $user_email = $_SESSION['user_email'];
   
  //if passwords do not match
   if($password !== $confirmPassword){
    header('location: account.php?error=passwords do not match');
    
    
    //if password is less than 6 char
  }else if(strlen($password) < 6){
    
    header('location: account.php?error=password must be at least 6 characters');
    
    //no errors
  } else{
    $stmt = $conn->prepare("UPDATE users SET user_password=? WHERE user_email=?");
     $stmt->bind_param('ss',md5($password),$user_email);
  }
  if($stmt->execute()){
    header('location: account.php?message=password has been updated successfully');
}else{
header('location: account.php?error=could not update password');

}
 
}
//get orders
if(isset($_SESSION['logged_in'])){

  $user_id = $_SESSION['user_id'];
  $stmt = $conn->prepare("SELECT * FROM orders WHERE user_id=? ");
  $stmt->bind_param('i',$user_id);

  $stmt->execute();
  
  $orders = $stmt->get_result();// []

}

?>






            <!--Account-->

            <section class="my-5 py-5">
                <div class="row container mx-auto">
                  <?php if(isset($_GET['payment_message'])){ ?>
                    <p class="mt-5 text-center" style="color:blue"> <?php echo $_GET['payment_message'];?></p>
                    <?php } ?>
                  
                    <div class="text-center mt-3 pt-5 col-lg-6 col-md-12 col-sm-12">
                    <p class="text-center" style="color:green"><?php if(isset($_GET['register_success'])){echo $_GET['register_success'];}?></p>
                    <p class="text-center" style="color:green"><?php if(isset($_GET['login_success'])){echo $_GET['login_success'];}?></p>
                         <h3 class="font-weight-bold">Account Info</h3> 
                        <hr class="mx-auto">
                        <div class="account-info">
                            <p>Name<span> <?php if(isset($_SESSION['user_name'])) {echo $_SESSION['user_name'];} ?></span></p>
                            <p>Email<span> <?php if(isset($_SESSION['user_email'])) {echo $_SESSION['user_email'];} ?></span></p>
                            <p><a href="#orders" id="orders-btn">Your Orders</a></p>
                            <p><a href="account.php?logout=1" id="logout-btn">Logout</a></p>
                        </div>

                    </div>

                    <div class="col-lg-6 col-md-12 col-sm-12">
                        <form id="account-form" method="Post" action="account.php">
                          <p class="text-center" style="color:red"><?php if(isset($_GET['error'])){echo $_GET['error'];}?></p>
                          <p class="text-center" style="color:green"><?php if(isset($_GET['message'])){echo $_GET['message'];}?></p>
                            <h3>Change Password</h3>
                            <hr class="mx-auto">
                            <div class="form-group">
                                <label>Password</label>
                                <input type="password" class="form-control" id="account-password" name="password" placeholder="Password" required/>
                            </div>
                            <div class="form-group">
                                <label>Confirm Password</label>
                                <input type="password" class="form-control" id="account-password-confirm" name="confirmPassword" placeholder="confirmPassword" required/>
                            </div>
                            <div class="form-group">
                                <input type="submit" value="Change Password" name="change_password" class="btn" id="change-pass-btn">
                            </div>
                        </form>
                    </div>


                </div>
            </section>

             <!--Orders-->
        <section id="orders" class="orders container my-5 py-3">
          <div class="container mt-2">
              <h2 class="font-weight-bold text-center">Your Orders</h2>
              <hr class="mx-auto">
          </div>

          <table class="mt-5 pt-5">
              <tr>
                  <th>Order id</th>
                  <th>Order cost</th>
                  <th>Order status</th>
                  <th>Order Date</th>
                  <th>Order details</th>
              </tr>


              <?php while($row = $orders->fetch_assoc() ){ ?>
                
                <tr>
                  <td>
                    <div>
                    <p class="mt-3"><?php echo $row['order_id']; ?></p>
                    </div>
                  </td>

                  <td>
                    <span><?php echo $row['order_cost']; ?></span>
                  </td>

                  <td>
                    <span><?php echo $row['order_status']; ?></span>
                  </td>

                 <td>
                    <span><?php echo  $row['order_date']; ?></span>
                  </td>

                  <td>
                   <form method="POST" action="order_details.php">
                   <input type="hidden" value="<?php echo $row['order_status'];?>" name="order_status"/>
                   <input type="hidden" value="<?php echo $row['order_id'];?>" name="order_id"/>
                   <input class="btn order-details-btn" name="order_details_btn" type="submit" value="details"/>
                   </form>
                  </td>

              </tr>
             

              <?php } ?>

             
             
          </table>


          

          
      </section>  




            <!--Footer-->

          <footer class="mt-5 py-5">
            <div class="row container mx-auto pt-5">
              <div class="footer-one col-lg-3 col-md-6 col-sm-12">
                <img class="logo"src="assets/imgs/logo.png"/>
                <p class="pt-3">We provide the best products for the most affordable prices</p>
              </div>
              <div class="footer-one col-lg-3 col-md-6 col-sm-12">
               <h5 class="pb-2">Featured</h5>
               <ul class="text-uppercase">
                <li><a href="#">Men</a></li>
                <li><a href="#">Women</a></li>
                <li><a href="#">boys</a></li>
                <li><a href="#">girls</a></li>
                <li><a href="#">new arrivals</a></li>
                <li><a href="#">clothes</a></li>
               </ul>
              </div>

              <div class="footer-one col-lg-3 col-md-6 col-sm-12">
                <h5 class="pb-2">Contact Us</h5>
                <div>
                  <h6 class="text-uppercase">Address</h6>
                  <p>1234 Ganeshthan,Thankot</p>
                </div>
                <div>
                  <h6 class="text-uppercase">Phone No</h6>
                  <p>1234567890</p>
                  <div>
                    <h6 class="text-uppercase">Email</h6>
                    <p>newrace@gmail.com</p>
                  </div>
                </div>
              </div>
              <div class="footer-one col-lg-3 col-md-6 col-sm-12">
                <h5 class="pb-2">Instagram</h5>
                <div class="row">
                  <img src="assets/imgs/1.jpg" class="img-fluid w-25 h-100 m-2"/>
                  <img src="assets/imgs/shoe.jpg" class="img-fluid w-25 h-100 m-2"/>
                  <img src="assets/imgs/clothe.jpg" class="img-fluid w-25 h-100 m-2"/>
                  <img src="assets/imgs/clothe1.jpg" class="img-fluid w-25 h-100 m-2"/>
                  <img src="assets/imgs/0.jpg" class="img-fluid w-25 h-100 m-2"/>
                </div>
              </div>
             
            </div>
            
            <div class="copyright mt-5">
              <div class="row container mx-auto">
                <div class="col-lg-3 col-md-5 col-sm-12 mb-4">
                  <img src="assets/imgs/payment.png"/>
                </div>
                <div class="col-lg-3 col-md-5 col-sm-12 mb-4 text-nowrap mb-2">
                 <p>eCommerce @ 2022 ALL Rights Reserved</p>
                </div>
                <div class="col-lg-3 col-md-5 col-sm-12 mb-4 mx-auto">
                  <a href="#"><i class="fab fa-facebook"></i></a>
                  <a href="#"><i class="fab fa-instagram"></i></a>
                  <a href="#"><i class="fab fa-twitter"></i></a>
                  <a href="#"><i class="fab fa-snapchat"></i></a>
                </div>
              </div>
            </div>


         <?php include ('layouts/footer.php');?>