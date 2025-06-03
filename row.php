<?php
// Add error reporting for debugging (remove in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Initialize variables
$books = [];
$conn = null;

// Include database connection with error handling
if (file_exists('db.php')) {
    include 'db.php';
    
    // Check if connection was successful
    if (isset($conn) && $conn) {
        // Fetch all books from database
        $sql = "SELECT * FROM books ORDER BY id DESC";
        $result = $conn->query($sql);
        
        if ($result && $result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $books[] = $row;
            }
        }
        $conn->close();
    } else {
        // Log error or handle connection failure
        error_log("Database connection failed");
    }
} else {
    // Handle missing db.php file
    error_log("Database configuration file not found");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ACE Publications</title>

    <link rel="stylesheet" href="https://unpkg.com/swiper@7/swiper-bundle.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">

    <link rel="apple-touch-icon" sizes="180x180" href="favicon_io/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="favicon_io/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="favicon_io/favicon-16x16.png">
    <link rel="manifest" href="favicon_io/site.webmanifest">
</head>

<body>

    <!-- header section starts  -->
    <header class="header">
        <div class="header-1">
            <a href="#" class="logo">
                <svg width="280" height="70" viewBox="0 0 280 70" xmlns="http://www.w3.org/2000/svg">
                    <!-- Background circle for depth -->
                    <circle cx="35" cy="35" r="32" fill="url(#bgGradient)" opacity="0.1"/>
                    
                    <!-- Main logo circle -->
                    <circle cx="35" cy="35" r="28" fill="url(#logoGradient)" stroke="url(#strokeGradient)" stroke-width="2"/>
                    
                    <!-- Book pages effect -->
                    <path d="M15 25 L25 20 L25 50 L15 45 Z" fill="url(#pageGradient1)" opacity="0.8"/>
                    <path d="M25 20 L35 25 L35 55 L25 50 Z" fill="url(#pageGradient2)"/>
                    <path d="M35 25 L45 20 L45 50 L35 55 Z" fill="url(#pageGradient3)"/>
                    <path d="M45 20 L55 25 L55 45 L45 50 Z" fill="url(#pageGradient1)" opacity="0.8"/>
                    
                    <!-- Highlight lines on pages -->
                    <line x1="20" y1="30" x2="20" y2="40" stroke="#fff" stroke-width="1" opacity="0.6"/>
                    <line x1="30" y1="32" x2="30" y2="42" stroke="#fff" stroke-width="1" opacity="0.8"/>
                    <line x1="40" y1="30" x2="40" y2="42" stroke="#fff" stroke-width="1" opacity="0.8"/>
                    <line x1="50" y1="32" x2="50" y2="40" stroke="#fff" stroke-width="1" opacity="0.6"/>
                    
                    <!-- Company name -->
                    <text x="80" y="30" font-family="Georgia, serif" font-size="24" font-weight="bold" fill="#1e8449">ACE</text>
                    <text x="80" y="50" font-family="Georgia, serif" font-size="16" font-weight="bold" fill="#1e8449">PUBLICATIONS</text>
                    
                    <!-- Decorative elements -->
                    <circle cx="75" cy="15" r="2" fill="#2ecc71" opacity="0.6"/>
                    <circle cx="85" cy="12" r="1.5" fill="#27ae60" opacity="0.5"/>
                    <circle cx="95" cy="15" r="1" fill="#16a085" opacity="0.6"/>
                    
                    <!-- Gradients -->
                    <defs>
                        <linearGradient id="logoGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" style="stop-color:#2ecc71;stop-opacity:1" />
                            <stop offset="50%" style="stop-color:#27ae60;stop-opacity:1" />
                            <stop offset="100%" style="stop-color:#1e8449;stop-opacity:1" />
                        </linearGradient>
                        
                        <linearGradient id="bgGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" style="stop-color:#ecf0f1;stop-opacity:1" />
                            <stop offset="100%" style="stop-color:#bdc3c7;stop-opacity:1" />
                        </linearGradient>
                        
                        <linearGradient id="strokeGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" style="stop-color:#ecf0f1;stop-opacity:0.8" />
                            <stop offset="100%" style="stop-color:#95a5a6;stop-opacity:0.4" />
                        </linearGradient>
                        
                        <linearGradient id="pageGradient1" x1="0%" y1="0%" x2="100%" y2="0%">
                            <stop offset="0%" style="stop-color:#ecf0f1;stop-opacity:1" />
                            <stop offset="100%" style="stop-color:#bdc3c7;stop-opacity:0.8" />
                        </linearGradient>
                        
                        <linearGradient id="pageGradient2" x1="0%" y1="0%" x2="100%" y2="0%">
                            <stop offset="0%" style="stop-color:#ffffff;stop-opacity:1" />
                            <stop offset="100%" style="stop-color:#ecf0f1;stop-opacity:0.9" />
                        </linearGradient>
                        
                        <linearGradient id="pageGradient3" x1="0%" y1="0%" x2="100%" y2="0%">
                            <stop offset="0%" style="stop-color:#ecf0f1;stop-opacity:1" />
                            <stop offset="100%" style="stop-color:#d5dbdb;stop-opacity:0.8" />
                        </linearGradient>
                        
                        <linearGradient id="textGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" style="stop-color:#ecf0f1;stop-opacity:1" />
                            <stop offset="100%" style="stop-color:#bdc3c7;stop-opacity:1" />
                        </linearGradient>
                    </defs>
                </svg>
            </a>

            <div class="icons">
                <div id="search-btn" class="fas fa-search"></div>
                <a href="./cart.html" class="fas fa-shopping-cart"></a>
                <div id="login-btn" class="fas fa-user"></div>
            </div>
        </div>

        <div class="header-2">
            <nav class="navbar">
                <a href="./index.php">Home</a>
                <a href="ebook.html">E-Book Reader</a>
                <a href="#featured">Category</a>
                <a href="#reviews">Reviews</a>
                <a href="error.html">Journal</a>
                <a href="./feedback.html">Feedback</a>
                <a href="contact.html">Contact</a>
            </nav>
        </div>
    </header>


    <style>
        {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        .header {
            background: linear-gradient(135deg, #27ae60 0%, #2ecc71 100%);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .header-1 {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 5%;
            background: rgba(255,255,255,0.1);
        }

        .logo {
            text-decoration: none;
            transition: transform 0.3s ease;
        }

        .logo:hover {
            transform: scale(1.05);
        }

        .logo svg {
            height: 70px;
            vertical-align: middle;
            filter: drop-shadow(0 2px 4px rgba(0,0,0,0.3));
        }

        .icons {
            display: flex;
            gap: 1.5rem;
            align-items: center;
        }

        .icons > * {
            color:rgb(164, 206, 26);
            font-size: 1.2rem;
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 50%;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .icons > *:hover {
            background: rgba(46, 204, 113, 0.3);
            color: #2ecc71;
            transform: translateY(-2px);
        }

        .header-2 {
            background: rgba(0,0,0,0.1);
            padding: 0.5rem 0;
        }

        .navbar {
            display: flex;
            justify-content: center;
            gap: 2rem;
            padding: 0 5%;
        }

        .navbar a {
            color:rgb(116, 179, 23);
            text-decoration: none;
            padding: 0.8rem 1.5rem;
            border-radius: 25px;
            transition: all 0.3s ease;
            font-weight: 500;
            position: relative;
            overflow: hidden;
        }

        .navbar a::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.1), transparent);
            transition: left 0.5s ease;
        }

        .navbar a:hover::before {
            left: 100%;
        }

        .navbar a:hover {
            background: rgba(46, 204, 113, 0.2);
            color: #2ecc71;
            transform: translateY(-2px);
        }

        @media (max-width: 768px) {
            .header-1 {
                padding: 1rem 3%;
            }
            
            .navbar {
                flex-wrap: wrap;
                gap: 1rem;
                padding: 0 3%;
            }
            
            .navbar a {
                padding: 0.6rem 1rem;
                font-size: 0.9rem;
            }
        }
    </style>
    <!-- header section ends -->

    <!-- bottom navbar -->
    <nav class="bottom-navbar">
        <a href="#home" class="fas fa-home"></a>
        <a href="#E-Book Reader" class="fas fa-list"></a>
        <a href="#arrivals" class="fas fa-tags"></a>
        <a href="#reviews" class="fas fa-comments"></a>
        <a href="#feedback" class="fas fa-comment-alt"></a>
    </nav>

    <!-- login form -->
    <div class="login-form-container">
        <div id="close-login-btn" class="fas fa-times"></div>
        <form action="">
            <h3>Sign in</h3>
            <span>username</span>
            <input type="email" class="box" placeholder="enter your email">
            <span>password</span>
            <input type="password" class="box" placeholder="enter your password">
    
            <!-- Submit button -->
            <input type="submit" value="Sign In" class="btn">
    
            <p>don't have an account ? <a href="login.html">create one</a></p>
        </form>
    </div>

    <!-- home section -->
    <section class="home" id="home">
        <div class="row">
            <div class="content">
                <h3>upto 75% off</h3>
                <p>If you're an Engineering student and need books, ACE Publications has great deals on a wide range of
                    books. Shop from top authors and avail huge discounts.</p>
            </div>

            <div class="swiper books-slider">
                <div class="swiper-wrapper">
                    <?php if (!empty($books)): ?>
                        <?php foreach (array_slice($books, 0, 6) as $book): ?>
                            <?php
                            // Safely handle image path
                            $imagePath = isset($book['image_url']) ? basename($book['image_url']) : 'book-1.png';
                            $fullImagePath = 'image/' . $imagePath;
                            ?>
                            <a href="#" class="swiper-slide">
                                <img src="<?php echo htmlspecialchars($fullImagePath); ?>" 
                                     alt="<?php echo isset($book['name']) ? htmlspecialchars($book['name']) : 'Book'; ?>"
                                     onerror="this.onerror=null;this.src='image/book-1.png';">
                            </a>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <!-- Default books when no data available -->
                        <a href="#" class="swiper-slide"><img src="image/book-1.png" alt="Book 1"></a>
                        <a href="#" class="swiper-slide"><img src="image/book-2.png" alt="Book 2"></a>
                        <a href="#" class="swiper-slide"><img src="image/book-3.png" alt="Book 3"></a>
                        <a href="#" class="swiper-slide"><img src="image/book-4.png" alt="Book 4"></a>
                        <a href="#" class="swiper-slide"><img src="image/book-5.png" alt="Book 5"></a>
                        <a href="#" class="swiper-slide"><img src="image/book-6.png" alt="Book 6"></a>
                    <?php endif; ?>
                </div>
                <img src="image/stand.png" class="stand" alt="Book Stand">
            </div>
        </div>
    </section>

    <!-- icons section -->
    <section class="icons-container">
        <div class="icons">
            <i class="fas fa-shipping-fast"></i>
            <div class="content">
                <h3>free shipping</h3>
                <p>order over 100</p>
            </div>
        </div>

        <div class="icons">
            <i class="fas fa-lock"></i>
            <div class="content">
                <h3>secure payment</h3>
                <p>100% secure payment</p>
            </div>
        </div>

        <div class="icons">
            <i class="fas fa-redo-alt"></i>
            <div class="content">
                <h3>Easy returns</h3>
                <p>10 days returns</p>
            </div>
        </div>

        <div class="icons">
            <i class="fas fa-headset"></i>
            <div class="content">
                <h3>24/7 support</h3>
                <p>call us anytime</p>
            </div>
        </div>
    </section>

    <!-- featured section -->
    <section class="featured" id="featured">
        <h1 class="heading"> <span>Categories</span> </h1>
        <div class="swiper featured-slider">
            <div class="swiper-wrapper">
                <?php if (!empty($books)): ?>
                    <?php foreach ($books as $book): ?>
                        <?php
                        // Safely handle all book properties
                        $imagePath = isset($book['image_url']) ? basename($book['image_url']) : 'book-1.png';
                        $fullImagePath = 'image/' . $imagePath;
                        $bookName = isset($book['name']) ? $book['name'] : 'Unnamed Book';
                        $bookPrice = isset($book['price']) ? $book['price'] : '0';
                        ?>
                        <div class="swiper-slide box">
                            <div class="icons">
                                <a href="viewread.html" class="fas fa-eye"></a>
                            </div>
                            <div class="image">
                                <img src="<?php echo htmlspecialchars($fullImagePath); ?>" 
                                     alt="<?php echo htmlspecialchars($bookName); ?>"
                                     onerror="this.onerror=null;this.src='image/book-1.png';">
                            </div>
                            <div class="content">
                                <h3><?php echo htmlspecialchars($bookName); ?></h3>
                                <div class="price">₹<?php echo htmlspecialchars($bookPrice); ?></div>
                                <button class="btn add-to-cart" 
                                        data-title="<?php echo htmlspecialchars($bookName); ?>" 
                                        data-price="<?php echo htmlspecialchars($bookPrice); ?>">
                                    Add to cart
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <!-- Default content when no books available -->
                    <div class="swiper-slide box">
                        <div class="icons">
                            <a href="viewread.html" class="fas fa-eye"></a>
                        </div>
                        <div class="image">
                            <img src="image/book-1.png" alt="Sample Book">
                        </div>
                        <div class="content">
                            <h3>Sample Book</h3>
                            <div class="price">₹0</div>
                            <button class="btn add-to-cart" data-title="Sample Book" data-price="0">
                                Add to cart
                            </button>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
        </div>
    </section>

    <!-- newsletter section starts -->
    <section class="newsletter">
        <form onsubmit="showMessage(event)">
            <h3>Subscribe for latest updates</h3>
            <input type="email" name="email" placeholder="Enter your email" required class="box">
            <input type="submit" value="Subscribe" class="btn">
        </form>
        
        <script>
            function showMessage(event) {
                event.preventDefault(); // Prevent form from refreshing the page
                alert("Thanks for subscribing!");
            }
        </script>
    </section>

    <!-- category section starts  -->
    <section class="arrivals" id="arrivals">
        <h1 class="heading"> <span>Category</span> </h1>

        <div class="swiper arrivals-slider">
            <div class="swiper-wrapper">
                <?php if (!empty($books)): ?>
                    <?php foreach (array_slice($books, 0, 4) as $book): ?>
                        <?php
                        $imagePath = isset($book['image_url']) ? basename($book['image_url']) : 'book-1.png';
                        $fullImagePath = 'image/' . $imagePath;
                        $bookName = isset($book['name']) ? $book['name'] : 'Unnamed Book';
                        ?>
                        <a href="viewread.html" class="swiper-slide box">
                            <div class="image">
                                <img src="<?php echo htmlspecialchars($fullImagePath); ?>" 
                                     alt="<?php echo htmlspecialchars($bookName); ?>"
                                     onerror="this.onerror=null;this.src='image/book-1.png';">
                            </div>
                            <div class="content">
                                <h3><?php echo htmlspecialchars($bookName); ?></h3>
                            </div>
                        </a>
                    <?php endforeach; ?>
                <?php else: ?>
                    <!-- Default category items -->
                    <a href="viewread.html" class="swiper-slide box">
                        <div class="image">
                            <img src="image/book-1.png" alt="Sample Book">
                        </div>
                        <div class="content">
                            <h3>Sample Book</h3>
                        </div>
                    </a>
                <?php endif; ?>
            </div>
        </div>

        <?php if (count($books) > 4): ?>
        <div class="swiper arrivals-slider">
            <div class="swiper-wrapper">
                <?php foreach (array_slice($books, 4, 4) as $book): ?>
                    <?php
                    $imagePath = isset($book['image_url']) ? basename($book['image_url']) : 'book-1.png';
                    $fullImagePath = 'image/' . $imagePath;
                    $bookName = isset($book['name']) ? $book['name'] : 'Unnamed Book';
                    ?>
                    <a href="viewread.html" class="swiper-slide box">
                        <div class="image">
                            <img src="<?php echo htmlspecialchars($fullImagePath); ?>" 
                                 alt="<?php echo htmlspecialchars($bookName); ?>"
                                 onerror="this.onerror=null;this.src='image/book-1.png';">
                        </div>
                        <div class="content">
                            <h3><?php echo htmlspecialchars($bookName); ?></h3>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
    </section>

    <!-- deal section starts  -->
    <section class="deal">
        <div class="content">
            <h3>deal of the day</h3>
            <h1>upto 50% off</h1>
            <p>Checkout before this deal expires at midnight.</p>
        </div>

        <div class="image">
            <img src="image/deal-img.jpg" alt="Deal Image">
        </div>
    </section>

    <!-- reviews section starts  -->
    <section class="reviews" id="reviews">
        <h1 class="heading"> <span>client's reviews</span> </h1>

        <div class="swiper reviews-slider">
            <div class="swiper-wrapper">
                <div class="swiper-slide box">
                    <img src="image/pic-1.png" alt="Osman">
                    <h3>Osman</h3>
                    <p>First of all it customer service is excellent. We get all author book for Mumbai University. People should try here affordable and best price.</p>
                    <div class="stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                    </div>
                </div>

                <div class="swiper-slide box">
                    <img src="image/pic-2.png" alt="Marry">
                    <h3>Marry</h3>
                    <p>Best book store almost all books are available for preparation of exam related or other books are available on reasonable price also.</p>
                    <div class="stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                    </div>
                </div>

                <div class="swiper-slide box">
                    <img src="image/pic-3.png" alt="Rahul">
                    <h3>Rahul</h3>
                    <p>Customer Service is good. Greetings to customer and making the required Books available to Customers is very good.</p>
                    <div class="stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                    </div>
                </div>

                <div class="swiper-slide box">
                    <img src="image/pic-4.png" alt="Pooja">
                    <h3>Pooja</h3>
                    <p>This book centre have large amount of a books. The engineering study material all semester books are available.then the peacefull and nice book centre.</p>
                    <div class="stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                    </div>
                </div>

                <div class="swiper-slide box">
                    <img src="image/pic-5.png" alt="Abinaya">
                    <h3>Abinaya</h3>
                    <p>I migrated to the online platform on Just books because I was finding it difficult to go to their libraries before closing time.</p>
                    <div class="stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                    </div>
                </div>

                <div class="swiper-slide box">
                    <img src="image/pic-6.png" alt="Siddhartha">
                    <h3>Siddhartha</h3>
                    <p>I love the product because it is very easy to find. The book are in really organized manner you can easily find the book you want.</p>
                    <div class="stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- feedback section starts  -->
    <section class="blogs" id="blogs">
        <h1 class="heading"> <span>feedback</span> </h1>

        <section class="newsletter">
            <form action="">
                <h3>give your feedback here...</h3>
                <a href="./feedback.html" class="btn">Feedback</a>
            </form>
        </section>
    </section>

    <!-- footer section starts  -->
    <section class="footer">
        <div class="box-container">
            <div class="box">
                <h3>our locations</h3>
                <a href="#"> <i class="fas fa-map-marker-alt"></i> India </a>
                <a href="#"> <i class="fas fa-map-marker-alt"></i> USA </a>
                <a href="#"> <i class="fas fa-map-marker-alt"></i> Malaysia </a>
                <a href="#"> <i class="fas fa-map-marker-alt"></i> Singapore </a>
                <a href="#"> <i class="fas fa-map-marker-alt"></i> UK</a>
                <a href="#"> <i class="fas fa-map-marker-alt"></i> China</a>
            </div>

            <div class="box">
                <h3>quick links</h3>
                <a href="./index.php"> <i class="fas fa-arrow-right"></i> home </a>
                <a href="ebook.html"> <i class="fas fa-arrow-right"></i> E-Book Reader </a>
                <a href="#"> <i class="fas fa-arrow-right"></i> Category </a>
                <a href="#"> <i class="fas fa-arrow-right"></i> reviews </a>
                <a href="error.html"> <i class="fas fa-arrow-right"></i> journal </a>
                <a href="./feedback.html"> <i class="fas fa-arrow-right"></i> feedback </a>
            </div>

            <div class="box">
                <h3>extra links</h3>
                <a href="#"> <i class="fas fa-arrow-right"></i> account info </a>
                <a href="#"> <i class="fas fa-arrow-right"></i> ordered items </a>
                <a href="#"> <i class="fas fa-arrow-right"></i> privacy policy </a>
                <a href="#"> <i class="fas fa-arrow-right"></i> payment method </a>
                <a href="#"> <i class="fas fa-arrow-right"></i> our services </a>
            </div>

            <div class="box">
                <h3>contact info</h3>
                <a href="#"> <i class="fas fa-phone"></i> +91 9715778831 </a>
                <a href="#"> <i class="fas fa-phone"></i> +91 8940193275</a>
                <a href="#"> <i class="fas fa-envelope"></i> soorya971577@gmail.com </a>
                <a href="#"> <i class="fas fa-envelope"></i> acepublications@gmail.com </a>
                <img src="image/worldmap.png" class="map" alt="World Map">
            </div>
        </div>
    </section>

    <!-- loader  -->
    <div class="loader-container">
        <img src="image/loader-img.gif" alt="Loading">
    </div>

    <script src="https://unpkg.com/swiper@7/swiper-bundle.min.js"></script>

    <!-- Initialize all Swiper sliders -->
    <script>
        // Books slider
        var swiper1 = new Swiper(".books-slider", {
            spaceBetween: 10,
            centeredSlides: true,
            autoplay: {
                delay: 9500,
                disableOnInteraction: false,
            },
            loop: true,
            breakpoints: {
                0: { slidesPerView: 1 },
                768: { slidesPerView: 2 },
                1024: { slidesPerView: 3 },
            },
        });

        // Featured slider
        var swiper2 = new Swiper(".featured-slider", {
            spaceBetween: 10,
            loop: true,
            centeredSlides: true,
            autoplay: {
                delay: 3000,
                disableOnInteraction: false,
            },
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
            breakpoints: {
                0: { slidesPerView: 1 },
                768: { slidesPerView: 2 },
                1024: { slidesPerView: 3 },
            },
        });

        // Arrivals slider
        var swiper3 = new Swiper(".arrivals-slider", {
            spaceBetween: 10,
            centeredSlides: true,
            autoplay: {
                delay: 5500,
                disableOnInteraction: false,
            },
            loop: true,
            breakpoints: {
                0: { slidesPerView: 1 },
                450: { slidesPerView: 2 },
                768: { slidesPerView: 3 },
                1024: { slidesPerView: 4 },
            },
        });

        // Reviews slider
        var swiper4 = new Swiper(".reviews-slider", {
            spaceBetween: 10,
            centeredSlides: true,
            autoplay: {
                delay: 7500,
                disableOnInteraction: false,
            },
            loop: true,
            breakpoints: {
                0: { slidesPerView: 1 },
                768: { slidesPerView: 2 },
                1024: { slidesPerView: 3 },
            },
        });

        // Add to cart functionality
        document.querySelectorAll('.add-to-cart').forEach(button => {
            button.addEventListener('click', () => {
                const title = button.getAttribute('data-title');
                const price = parseFloat(button.getAttribute('data-price'));
                let cart = JSON.parse(localStorage.getItem('cart')) || [];
                const existingItem = cart.find(item => item.title === title);
                if (existingItem) {
                    existingItem.quantity = (existingItem.quantity || 1) + 1;
                } else {
                    cart.push({ title, price, quantity: 1 });
                }
                localStorage.setItem('cart', JSON.stringify(cart));
                alert("Item added to cart!");
                window.location.href = "cart.html";
            });
        });
    </script>

    <!-- custom js file link  -->
    <script src="js/script.js"></script>

</body>
</html>