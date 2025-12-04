<?php

@include 'config.php';

session_start();

// $user_id = $_SESSION['user_id'];

// if(!isset($user_id)){
//    header('location:login.php');
// }

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>about</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom admin css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php @include 'header.php'; ?>

<section class="heading">
    <h3>about us</h3>
    <p> <a href="home.php">home</a> / about </p>
</section>

<section class="about">

    <div class="flex">

        <div class="image">
            <img src="coffeeimg/about-us.jpg" alt="">
        </div>

        <div class="content">
            <h3>why choose us?</h3>
            <p>Because we believe in giving you more than just a drink — we give you wellness in a cup. Every sip combines natural flavor, freshness, and health benefits, making it the perfect choice for anyone who wants to stay active, relaxed, and healthy.</p>
            <a href="shop.php" class="btn">shop now</a>
        </div>

    </div>

    <div class="flex">

        <div class="content">
            <h3>Is it good for health?</h3>
            <p>Herbal tea and coffee are not just tasty but also support digestion, boost energy, and refresh your mind. Our blends are designed to give you a healthy lifestyle with every cup, whether you’re starting your morning or relaxing in the evening.</p>
            <a href="contact.php" class="btn">contact us</a>
        </div>

        <div class="image">
            <img src="coffeeimg/about.png" alt="">
        </div>

    </div>

    <div class="flex">

        <div class="image">
            <img src="coffeeimg/p1.jpg" alt="">
        </div>

        <div class="content">
            <h3>What makes our tea & coffee special?</h3>
            <p>Our tea and coffee are crafted from the finest natural herbs and plant-based ingredients—100% herbal, fresh, and chemical-free, for a rich taste and real benefits in every sip.</p>
            <a href="#reviews" class="btn">clients reviews</a>
        </div>

    </div>

</section>

<section class="reviews" id="reviews">

    <h1 class="title">client's reviews</h1>

    <div class="slider-container">
        <div class="slider-track">

            <div class="box">
                <img src="images/pic-1.png" alt="">
                <p>Absolutely love the organic coffee! It’s rich, flavorful, and gives me the perfect start to my day.</p>
                <div class="stars">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star-half-alt"></i>
                </div>
                <h3>Fahad Ahmed</h3>
            </div>

            <div class="box">
                <img src="images/pic-2.png" alt="">
                <p>The tea tastes so fresh and natural. You can really tell it’s organic—no bitterness, just pure flavor.Thank you!"</p>
                <div class="stars">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star-half-alt"></i>
                </div>
                <h3>Jasmin Akter</h3>
            </div>

            <div class="box">
                <img src="images/pic-3.png" alt="">
                <p>I’ve switched completely to BotaniQ coffee and tea. My energy feels cleaner and more consistent now.</p>
                <div class="stars">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star-half-alt"></i>
                </div>
                <h3>Rajib Khan</h3>
            </div>

            <div class="box">
                <img src="images/pic-4.png" alt="">
                <p>"I’ve ordered from here a few times, and I’m always impressed with the quality and creativity. They never disappoint!"</p>
                <div class="stars">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star-half-alt"></i>
                </div>
                <h3>Rani Joy</h3>
            </div>

            <div class="box">
                <img src="images/pic-5.png" alt="">
                <p> Beautiful packaging, fast delivery, and the taste is amazing. My family enjoys it every evening.</p>
                <div class="stars">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star-half-alt"></i>
                </div>
                <h3>Raj Hussain</h3>
            </div>

            <div class="box">
                <img src="images/pic-6.png" alt="">
                <p> The best decision I made was trying their herbal tea. It keeps me relaxed and refreshed all day</p>
                <div class="stars">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star-half-alt"></i>
                </div>
                <h3>Nita Kapur</h3>
            </div>

        </div>
    </div>

</section>

<?php @include 'footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>
