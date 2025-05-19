# 📚 BookBuddy - Online Book Store

BookBuddy is a modern, responsive PHP-based online bookstore that allows users to browse, search, and purchase books, while also providing a complete admin dashboard for managing inventory, orders, and users.

---

## 🌟 Features

### ✅ User Features
- Register/Login (Email & Google OAuth)
- Browse books with filters (title, category, author)
- View book details and ratings
- Add/remove books from cart
- Apply promo codes at checkout (`tanni5`, `shiren5`, `noor10`)
- Choose payment method: Stripe or Cash on Delivery
- View past orders and submit reviews

### 🛠️ Admin Features
- Admin Dashboard (`admin_panel.php`)
- Manage books (`manage_books.php`)
- Manage orders (`manage_orders.php`, `order_details.php`, `delete_order.php`)
- Manage users (`manage_users.php`)

---

## 💻 Technologies Used

- Frontend: HTML, Custom CSS, Vanilla JS
- Backend: PHP 7+, MySQL
- Authentication: PHP Sessions, Google OAuth (`google_login.php`)
- Payments: Stripe API Integration (`gateway_payment.php`, `payment_success.php`, `cancel.php`)
- Utilities: Swiper.js for carousels, Composer for dependency management

---

## 📁 Project Structure

├── images/ # Book and asset images
├── js/ # Custom scripts
├── vendor/ # Composer dependencies
├── book_store.sql # Database schema
├── composer.json/lock # Composer config
├── clientid.txt # Google OAuth client ID
├── db.php # Database connection
├── header.php/footer.php # Page layout components

Core Pages
├── index.php # Homepage (book listings, search)
├── description.php # Book detail view
├── cart.php # Cart logic
├── cart_view.php # Cart display
├── cart_remove.php # Remove item from cart
├── update_quantity.php # Update cart quantity
├── checkout.php # Checkout page
├── place_order.php # Order placement
├── payment_success.php # Stripe success
├── cancel.php # Stripe cancel
├── success.php # Final success screen
├── profile.php # User profile and order history
├── submit_review.php # Submit book reviews

Auth
├── register.php
├── login.php
├── logout.php
├── verify.php
├── google_login.php
├── google_callback.php

Admin
├── admin_panel.php
├── manage_books.php
├── manage_orders.php
├── manage_users.php
├── order_details.php
├── delete_order.php

Other
├── team.php
├── test.php # For development/testing


---

## 🛠️ Setup Instructions

1. **Clone or Download the Project**
   ```bash
   git clone https://github.com/your-username/bookbuddy.git
   cd bookbuddy
   
##Database Setup

Import book_store.sql into your MySQL server.

Update your DB credentials in db.php.

Google Login Setup

Create Google OAuth credentials via Google Cloud Console.

Save your client ID in clientid.txt.

Stripe Payment Setup

Create an account at Stripe.

Replace the test keys in gateway_payment.php and related files.
Run Locally
Start a local server (e.g., XAMPP, MAMP).

Access the project via http://localhost/BookStore/index.php.

💸 Promo Codes
Code	Discount
tanni5	5%
shiren5	5%
noor10	10%

📌 Future Improvements
Wishlist feature
Book recommendations
Admin analytics dashboard
Email order notifications
Pagination and sorting










