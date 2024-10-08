
<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="styles.css">
  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css">
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>


<!-- Initialize Slick Carousel -->
<script>
  $(document).ready(function(){
    $('.category-carousel').slick({
      infinite: true,
      slidesToShow: 4, // Show 4 products at a time
      slidesToScroll: 1, // Scroll 1 product at a time
      prevArrow: '<button type="button" class="slick-prev">Previous</button>',
      nextArrow: '<button type="button" class="slick-next">Next</button>',
      responsive: [
        {
          breakpoint: 992,
          settings: {
            slidesToShow: 3 // Adjust for smaller screens if needed
          }
        },
        {
          breakpoint: 768,
          settings: {
            slidesToShow: 2 // Adjust for smaller screens if needed
          }
        },
        {
          breakpoint: 576,
          settings: {
            slidesToShow: 1 // Adjust for smaller screens if needed
          }
        }
      ]
    });
  });

  // Example JavaScript for Search Functionality
$(document).ready(function(){
  $('.search-bar button').on('click', function(){
    var query = $('.search-bar input').val();
    // Implement search logic here
    console.log('Search query:', query);
  });

  $('#sort-by').on('change', function(){
    var sortBy = $(this).val();
    // Implement sorting logic here
    console.log('Sort by:', sortBy);
  });

  $('input[name="category"]').on('change', function(){
    var selectedCategories = [];
    $('input[name="category"]:checked').each(function(){
      selectedCategories.push($(this).val());
    });
    // Implement filter logic here
    console.log('Selected categories:', selectedCategories);
  });
});

</script>

<style>
   /** Honey Yellow: #ffc107
Lighter Honey Yellow (use this for hover effect): #ffcd38 **/
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        margin: 0;
        padding: 0;
        display: flex;
        justify-content: center;
        flex-direction: column;
    }

    header {
        background-color:#2929a3;
        color: #fff;
        padding: 15px;
        text-align: center;
        width: 100%;
        height:30vh;
    }
    header h1{
        letter-spacing:1px;
    }

    #btn{
        margin: 25px;
    }
    #btn a{
        text-decoration:none;
        border-radius:5rem;
        transition: 0.3s ease;
        padding:10px 15px;
        color:aliceblue;
    }
    
    #btn a.start{
        background-color:#000033;
        transition: 0.3s ease;  
    }
    #btn a.start:hover{
        color:#000033;
        background-color:aliceblue;
    }
    #btn a.learn:hover{
        color:yellow;
    }
    
    .abt{
        background-color:white;
    }
    .abt a{
        text-decoration:none;
    }
    #feature{
        
    }
    #priceplan{
        background-color:white;
        padding-left:20px;
    }
    #heading{
        text-align:center;
        color:#2929a3;
    }

    #findbook{
        background:black;
        color:aliceblue;
        text-align:center;
    }

    #faq h3{
        flex-direction:column;
        text-align:left;
    }
    #faq .right{
        text-align:right;
    }

    #feature h3 {
            text-align:center;  
        }
    #feature .cen {
            text-align:center; 
    }

    #container {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            max-width: 600px; 
            margin: 0 auto;
            margin-bottom:30px; 
    }

    .littlebox {
        height:20vh;
        background: white;
        border: 1px solid #ccc; 
        border-radius:5px;
        padding-left: 20px;  
    }
    .littlebox #size{
        font-size:20px;
    }

    /*Pricing */
    #priceplan h3{
        text-align:center;
    }
    #priceplan .cent {
        text-align:center;
    }
    .box{
        text-align:center;
        background: rgb(178, 194, 247);
        border: 1px solid #ccc; 
        border-radius:5px;
        padding-left: 20px; 
       
    }
    

    /*new css*/
    /* Reset Styles */
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

/* Global Styles */
body {
  font-family: Arial, sans-serif;
  background-color: #e7e5e5; /* Off-white background */
}

.container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 20px;
}

/* Header Styles */
header {
  background-color: #003366; /* Deep blue header */
  padding: 20px 0;
  color: #ffffff; /* White text */
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.logo img {
  height: 50px; /* Adjust logo height as needed */
}

nav ul {
  list-style-type: none;
}

nav ul li {
  display: inline-block;
  margin-left: 20px;
}

nav ul li a {
  color: #FFFFFF; /* White links */
  text-decoration: none;
}

/* Hero Section Styles */
.hero {
  background-image: url('hero.jpg'); /* Replace 'hero-image.jpg' with your image */
  background-size: cover;
  background-position: center;
  color: #FFFFFF; /* White text */
  padding: 100px 0;
  text-align: center;
}

.hero h1 {
  font-size: 48px;
  font-weight: bold;
  margin-bottom: 20px;
  color:rgb(0, 0, 0);

}

.hero h2 {
  font-size: 20px;
  margin-bottom: 40px;
  color:rgb(87, 78, 78);
}

.search-bar {
  max-width: 600px;
  margin: 0 auto;
}

.search-bar input {
  width: 70%;
  padding: 10px;
  border: none;
  border-radius: 5px;
}

.search-bar button {
  width: 25%;
  padding: 10px;
  border: none;
  border-radius: 5px;
  background-color: #28A745; /* Green button */
  color: #FFFFFF; /* White text */
  cursor: pointer;
}

.search-bar button:hover {
  background-color: #218838; /* Darker green on hover */
}

/* Featured Stores Section Styles */
.featured-stores {
  padding: 50px 0;
  text-align: center;
}

.featured-stores h2 {
  font-size: 32px;
  margin-bottom: 30px;
}

.store-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
  gap: 30px;
}

.store-card {
  background-color: #FFFFFF;
  border-radius: 10px;
  box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
  padding: 20px;
}

.store-card img {
  width: 100%;
  border-radius: 10px;
  margin-bottom: 20px;
}

.store-card h3 {
  font-size: 24px;
  margin-bottom: 10px;
}

.store-card p {
  font-size: 16px;
  margin-bottom: 20px;
}

.shop-now-btn {
  display: inline-block;
  padding: 10px 20px;
  background-color: #FFC107; /* Yellow button */
  color: #FFFFFF;
  text-decoration: none;
  border-radius: 5px;
  transition: background-color 0.3s ease;
}

.shop-now-btn:hover {
  background-color: #FFA000; /* Darker yellow on hover */
}

/* Categories Section Styles */
.categories {
  padding: 50px 0;
  text-align: center;
}

.categories h2 {
  font-size: 32px;
  margin-bottom: 30px;
}

.category-grid {
  display: flex;
  justify-content: center;
  align-items: flex-start;
  flex-wrap: wrap;
}

.category {
  width: 100%; /* Adjust as needed */
  margin-bottom: 30px;
}

.category h3 {
  font-size: 24px;
  margin-bottom: 20px;
}

.category-boxes {
  display: grid;
  grid-template-columns: repeat(4, 1fr); /* 4 boxes per row */
  grid-gap: 20px; /* Adjust as needed */
}

.category-box {
  background-color: #ffffff;
  border-radius: 5px;
  padding: 20px;
  text-align: center;
}

.category-box img {
  max-width: 100%;
  height: auto;
  border-radius: 5px;
}

.category-box p {
  margin-top: 10px;
}


/* Customize Slick Carousel Arrows */
.slick-prev,
.slick-next {
  color: #262525; /* Adjust the color as needed */
  font-size: 24px; /* Adjust the font size as needed */
  background-color: transparent; /* Remove any background color */
}

/* Optional: Add hover effect */
.slick-prev:hover,
.slick-next:hover {
  color: #555; /* Change the color on hover if desired */
}



/* Additional Sections and Footer Styles */
/* Add your styles here based on the provided layout and color scheme */
/* Footer Styles */
footer {
  background-color: #003366; /* Deep blue footer */
  color: #FFFFFF; /* White text */
  padding: 50px 0;
}

.footer-container {
  display: flex;
  flex-wrap: wrap;
  justify-content: space-between;
}

.footer-section {
  width: 25%; /* Adjust as needed */
}

.footer-section h3 {
  font-size: 20px;
  margin-bottom: 20px;
}

.footer-section ul {
  padding: 0;
}

.footer-section ul li {
  list-style-type: none;
  margin-bottom: 10px;
}

.footer-section ul li a {
  color: #FFFFFF;
  text-decoration: none;
}

.social-media-icons {
  display: flex;
  justify-content: flex-end;
  margin-bottom: 20px;
}

.social-media-icons a {
  margin-left: 10px;
}

.contact-info p {
  margin-bottom: 10px;
}

.newsletter-form {
  display: flex;
  align-items: center;
}

.newsletter-form input[type="email"],
.newsletter-form button {
  padding: 10px;
  border-radius: 5px;
}

.newsletter-form input[type="email"] {
  flex: 1;
  margin-right: 10px;
}

.newsletter-form button {
  background-color: #FF6600; /* Orange button */
  color: #FFFFFF;
  border: none;
  cursor: pointer;
}



</style>

</head>
<body>

    <header>
        <h1>Discover Bookhives</h1>
        <h5>Your one-stop destination for all your book needs</h5>
        <div id="btn">
            <a class="start" href="shop.php">Get Started</a>
            <a class="learn" href="about.php">Learn More &#x2192</a>
        </div>
    </header>

        <div class="abt"> 
            <h4 id="heading">DETAILS</h4>      
            <h3>About Bookhives</h3>
            <p>Bookhives is an ecommerce website that connects book lovers with independent bookstores. With Bookhives, shopowners can easily list their books, manage orders, and keep track of their inventory.<br>Customers can explore a vast collections of books from different shopowners, add them to their cart, create whislists, and enjoy a seamless shopping expercience.
            Want to know about Bookhives platform?<a href="about.php">Know More</a></p>
        </div>
        
  

<body>
  <!-- Header -->
  <header>
    <div class="container">
      <div class="logo">
        <img src="logo.png" alt="Local Shops Logo">
      </div>
      <nav>
        <ul>
          <li><a href="#">Home</a></li>
          <li><a href="#">About</a></li>
          <li><a href="#">Stores</a></li>
          <li><a href="#">Map</a></li>
          <li><a href="#">Contact</a></li>
             <li class="nav-item">
                            <a class="nav-link click-scroll" href="#chatbot">Chat with Us</a>
                        </li>
        </ul>
        
      </nav>
    </div>
  </header>

  <!-- Hero Section -->
  <section class="hero">
    <div class="container">
      <h1>Discover Your Local Shops Online!</h1>
      <h2>Connecting you to your favorite local stores with just a click</h2>
      <div class="search-bar">
        <input type="text" placeholder="Search for stores...">
        <button>Search</button>
      </div>
    </div>
  </section>

  <!-- Filter and Sort Options -->
<!--<div class="filter-sort-options">
  <select id="sort-by">
    <option value="popularity">Sort by Popularity</option>
    <option value="price-low-high">Sort by Price: Low to High</option>
    <option value="price-high-low">Sort by Price: High to Low</option>
    <option value="new-arrivals">Sort by New Arrivals</option>
  </select>

  <div class="filters">
    <label>
      <input type="checkbox" name="category" value="clothing"> Clothing
    </label>
    <label>
      <input type="checkbox" name="category" value="electronics"> Electronics
    </label>
    
  </div>
</div>-->


  <!-- Interactive Map Section -->
  <section class="map">
    <!-- Interactive Map Goes Here -->
  </section>

  <section class="featured-stores">
    <div class="container">
      <h2>Featured Stores</h2>
      <div class="store-grid">
        <div class="store-card">
          <img src="shop1.png" alt="Store 1">
          <h3>Book Palace</h3>
          <p>All kinds of books at just one store....</p>
          <a href="#" class="shop-now-btn">Shop Now</a>
        </div>

        <div class="store-card">
          <img src="shop2.png" alt="Store 2">
          <h3>Natraj stationary</h3>
          <p>Buy New as well as Used books at half price on its MRP...</p>
          <a href="#" class="shop-now-btn">Shop Now</a>
        </div>
        
        <div class="store-card">
          <img src="shop3.png" alt="Store 1">
          <h3>Ramesh Stationary</h3>
          <p>Here you will get School books, Novels and Story Books..</p>
          <a href="#" class="shop-now-btn">Shop Now</a>
        </div>

        <div class="store-card">
          <img src="shop4.png" alt="Store 1">
          <h3>Suraj Book Depo</h3>
          <p>Get all books collection, Lastest Books, Used books on lease....</p>
          <a href="#" class="shop-now-btn">Shop Now</a>
        </div>
      </div>
    </div>
  </section>
  
 <!-- Categories Section -->
<section class="categories">
  <div class="container">
    <h2>Explore Categories</h2>
    <div class="category-grid">
      <div class="category">
        <h3>Clothing</h3>
        <div class="category-carousel">
          <div class="category-box">
            <img src="prod1.jpg" alt="">
            <p>Rs. 300/-</p>
          </div>
          <div class="category-box">
            <img src="prod2.jpg" alt="Product 1">
            <p>Product Name 2</p>
          </div>
          <div class="category-box">
            <img src="prod3.png" alt="Product 1">
            <p>Rs. 200</p>
          </div>
          <div class="category-box">
            <img src="clothing-product1.jpg" alt="Product 1">
            <p>Product Name 4</p>
          </div>
          <div class="category-box">
            <img src="clothing-product2.jpg" alt="Product 2">
            <p>Product Name 5</p>
          </div>
          <!-- Add more products as needed -->
        </div>
      </div>
      <div class="category">
        <h3>Electronics</h3>
        <div class="category-carousel">
          <div class="category-box">
            <img src="electronics-product1.jpg" alt="Product 1">
            <p>Product Name 1</p>
          </div>
          <div class="category-box">
            <img src="electronics-product1.jpg" alt="Product 1">
            <p>Product Name 1</p>
          </div>
          <div class="category-box">
            <img src="electronics-product1.jpg" alt="Product 1">
            <p>Product Name 1</p>
          </div>
          <div class="category-box">
            <img src="electronics-product1.jpg" alt="Product 1">
            <p>Product Name 1</p>
          </div>
          <div class="category-box">
            <img src="electronics-product1.jpg" alt="Product 1">
            <p>Product Name 1</p>
          </div>
          <div class="category-box">
            <img src="electronics-product2.jpg" alt="Product 2">
            <p>Product Name 2</p>
          </div>
          <!-- Add more products as needed -->
        </div>
      </div>
      <!-- Add more categories as needed -->
    </div>
  </div>
</section>


  
        <!--Chat bot section starts-->
        <section class="textOfChatBot" id="chatbot">
            <h3 class="lengthheading">If you have any Queries <span>please type your queries below </span>to have them
                answered by our chatBot</h3>
        </section>
        <!--Chat bot section starts-->
        <iframe
            src='https://webchat.botframework.com/embed/SaheliLang-bot?s=y6rXHBqVo3E.__uzJy3Z_FXP8ZGiTgRDIm9U6j3inyOOsOVWUgN6Oqk'
            style='min-width: 400px; width: 100%; min-height: 500px;'></iframe>


        <section id="feature">
            <h4 id="heading">FEATURES</h4>
            <h3>Discover the Features of Bookhives</h3>
            <p class="cen">Explore the functionalities that make Bookhives the perfect platform<br>for bookstores and customers alike </p>
            <div id="container">
                
                    <div class="littlebox">
                        <h4 id="size">Shopowner Dashboard</h4>
                        <p>Manage your books, orders, and inventory with ease</p>
                    </div>

                    <div  class="littlebox">
                        <h4 id="size">Book Listing</h4>
                        <p>list your books on Bookhives and reach a wider audience</p>
                    </div>
                    
                    <div  class="littlebox">
                        <h4 id="size">Order Management</h4>
                        <p>Efficiently process and track customer orders</p>
                    </div>

                    <div  class="littlebox">
                        <h4 id="size">Inventory Management</h4>
                        <p>Keep track of your inventory and stock levels</p>
                    </div>
            </div>
        </section>

        <section id="priceplan">
            <h4 id="heading">PRICING</h4>
            <h3>Choose the Right Plan for Your Bookstore</h3>
            <p class="cent">Unlock powerful features to grow your online bookstore</p>
                <div class="box">
                    <h4>FREE</h4>
                    <h5>List your books for free and manage orders and inventory</h5>
                    <p>$<b>0</b></p>
                    <p>Unlimed book listings</p>
                    <p>Order management</p>
                    <p>Inventory management</p>
                    <p>Basic customer support</p>
                    <button>Continue with Free</button>
                </div>
                <div class="box">
                    <h4>BASIC</h4>
                    <h5>Enhanced features for growing bookstores</h5>
                    <p>$<b>7</b>/month<p>
                    <p>All features of Free plan</p>
                    <p>All Free plan Features</p>
                    <p>Priority customer support</p>
                    <p>Advanced analytics and reporting</p>
                    <button>Try the Basic Plan</button>
                </div>
                <div class="box">
                    <h4>PRO</h4>
                    <h5>Premium features for established bookstores</h5>
                    <p>$<b>20</b>/month</p>
                    <p>All features of BASIC plan</p>
                    <p>All Basic Plan Features</p>
                    <p>Dedicated account manager</p>
                    <p>Promotional tools and marketing support</p>
                    <button>Try the PRO plan</button>
                </div>
        </section>

        <section id="collection">
            <h3>Explore our Book Collection</h3>
            <p>Browse through a wide range of books available on BookHives</p>
            <div class="imgs">
                <img src="#" alt="image">
                <img src="#" alt="image">
                <img src="#" alt="image">
                <img src="#" alt="image">
                <img src="#" alt="image">
                <img src="#" alt="image">
                <img src="#" alt="image">
                <img src="#" alt="image">
                <img src="#" alt="image">
                <img src="#" alt="image">
                <img src="#" alt="image">
                <img src="#" alt="image">
            </div>
        </section>

        <section id="findbook">
            <h3>Find Your Next Favorite Book</h3>
            <p>Browse through a wide range of books from various shopowners.</p>
            <div id="btn"><a class="start">Shop Now</a></div>
        </section>

        <section id="faq">
            <h4 id="heading">FAQ</h4>
            <h3>Common questions</h3>
            <p>Here are some of the most common questions that we get.</p>
            <div class="right">
            <h3>How can I list my books on Bookhives?</h3>
            <p>To list your books on Bookhives, you need to create an account as a shop owner. Once you have an account, you can easily add your books to your inventory and manage them.</p>

            <h3>Can I manage my orders on Bookhives?</h3>
            <p>Yes, as a shop owner on Bookhives, you will have access to a dashboard where you can manage your orders. You can view process orders, update thir status, and communicate with customers.</p>

            <h3>How do customers make purchases on Bookhives?</h3>
            <p>Customers can browse through the books listed on Bookhives and add books to their cart. They can then proceed to checkout and make a payment to complete their purchase.</p>

            <h3>What if I want to save a book for later?</h3>
            <p>If you want to save a book for later, you can add it to your wishlist. This way, you can easily find and purchase the book at a later time.</p>

            <h3>Is there a return policy on Bookhives?</h3>
            <p>Yes, Bookhives has a return policy in place. If you receive a book that is damaged or not as described, you can initiate a return request within a specified timeframe.</p>
            </div>
        </section> 
    

         <!-- Footer -->
<footer>
  <div class="container footer-container">
    <div class="footer-section">
      <h3>Quick Links</h3>
      <ul>
        <li><a href="#">Home</a></li>
        <li><a href="#">About</a></li>
        <li><a href="#">Stores</a></li>
        <li><a href="#">Map</a></li>
        <li><a href="#">Contact</a></li>
      </ul>
    </div>
    <div class="footer-section">
      <h3>Social Media</h3>
      <div class="social-media-icons">
        <a href="#"><img src="facebook-icon.png" alt="Facebook"></a>
        <a href="#"><img src="twitter-icon.png" alt="Twitter"></a>
        <a href="#"><img src="instagram-icon.png" alt="Instagram"></a>
      </div>
    </div>
    <div class="footer-section contact-info">
      <h3>Contact Information</h3>
      <p>Email: example@example.com</p>
      <p>Phone: 123-456-7890</p>
      <p>Address: 123 Main St, City, State, Zip</p>
    </div>
    <div class="footer-section newsletter-form">
      <h3>Newsletter Signup</h3>
      <form action="#" method="POST">
        <input type="email" name="email" placeholder="Enter your email">
        <button type="submit">Subscribe</button>
      </form>
    </div>
  </div>
</footer>

</body>
</html>
