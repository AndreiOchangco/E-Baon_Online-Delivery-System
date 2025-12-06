# E-Baon_Online-Delivery-System

<p align="center">
    <img src="./images/e-baon-logo.png" alt="E-Baon Logo" height="200px"/>
</p>

<p align="justify">The E-Baon for CSF System is a web and mobile platform for parcel pick-up and drop-off in the City of San Fernando, La Union. It enables customers to schedule deliveries and allows drivers to manage requests, offering a fast, secure, and reliable local delivery service with real-time tracking and digital transactions.</p>
</br>
</br>
<p align="justify">This README is available in English and Tagalog. Please expand the section for your preferred language.</p>

---

<details>
<summary><strong>English Version (Click to Expand)</strong></summary>

## 🌟 Project Overview

<p align="justify">E-Baon is a three-tier online delivery management system that streamlines the entire ordering and delivery process. It connects customers who want to order items, administrators who oversee operations and monitor business metrics, and delivery riders who fulfill orders efficiently. The system provides real-time order tracking, comprehensive dashboard analytics, and secure user authentication with role-based access control to ensure smooth operations across all user levels.</p>

## ✨ Key Features

### For Customers (Customer View):
*   **User Authentication:** Secure registration and login system with session management.
*   **Menu Browsing:** Browse available menu items with images, detailed descriptions, categories, and real-time pricing.
*   **Order Creation:** Intuitive interface to create new orders with selected items from the menu.
*   **Order Management:** View order history, check current order status, and manage active orders.
*   **Real-Time Order Tracking:** Monitor order status from preparation through delivery completion.
*   **User Profile Management:** View and update personal account information including contact details and delivery addresses.
*   **Responsive Dashboard:** Dedicated customer homepage with quick access to menu and order management.

### For Administrators (Admin Dashboard):
*   **Comprehensive Dashboard:** Real-time overview of key business metrics including total orders, delivered orders, canceled orders, total revenue, and open orders.
*   **Metrics Management:** Easily update and monitor critical business KPIs with intuitive dashboard controls.
*   **Monthly Analytics & Reports:** Visualize monthly trends with charts showing order volumes, delivery success rates, and revenue trends.
*   **Dynamic Data Updates:** Real-time metric calculation based on active orders and delivery status.
*   **Performance Tracking:** Monitor business growth through detailed statistical reports and monthly comparisons.
*   **System Control:** Manage dashboard data, generate reports, and maintain system health.
*   **Statistical Dashboard:** Visual representation of best-performing periods and revenue trends.

### For Delivery Riders (Delivery Management):
*   **Order Assignment View:** Access a comprehensive list of customer orders assigned for delivery.
*   **Detailed Order Information:** View complete order details including customer info and item descriptions.
*   **Real-Time Status Updates:** Update order status during the delivery process (preparing, in transit, delivered, etc.).
*   **Delivery Management:** Track multiple orders and manage delivery routes efficiently.
*   **Rider Profile:** Maintain personal profile information including contact details and availability status.
*   **Order Completion:** Mark orders as delivered and update final delivery status in real-time.

## 🛠️ Technology Stack

*   **Frontend:** HTML5, CSS3, JavaScript
*   **Backend:** PHP (Procedural or with a custom structure)
*   **Database:** MySQL (Managed via phpMyAdmin in XAMPP)
*   **Web Server:** Apache (via XAMPP)

## 📂 Project Structure

```
E-Baon_Online-Delivery-System/
├── Body/                          # Main application pages
│   ├── Admin/                     # Admin dashboard pages
│   │   ├── Admin_Homepage.php     # Main admin dashboard
│   │   ├── update_dashboard_metric.php      # Metric updates
│   │   ├── update_dashboard_monthly_orders.php  # Monthly order tracking
│   │   └── update_dashboard_monthly_revenue.php # Revenue tracking
│   ├── Customer/                  # Customer-facing pages
│   │   ├── Customer_Homepage.php  # Customer dashboard
│   │   ├── Menu_Homepage.php      # Menu browsing
│   │   ├── create_order.php       # Order creation
│   │   ├── Profile_Homepage.php   # User profile management
│   │   └── update_profile.php     # Profile update handler
│   └── Delivery/                  # Delivery rider pages
│       ├── Delivery_Homepage.php  # Rider dashboard
│       ├── Profile_D.php          # Rider profile management
│       └── update_order_status.php # Order status update handler
├── Connnection/                   # Database connection files
│   └── Connection.php             # PDO database connection
├── Css/                           # Stylesheets
│   ├── Admin/                     # Admin page styles
│   ├── Customer/                  # Customer page styles
│   ├── Delivery/                  # Delivery page styles
│   └── Main/                      # Authentication page styles
├── Javascript/                    # Client-side scripts
│   ├── Admin/                     # Admin page scripts
│   ├── Customer/                  # Customer page scripts
│   ├── Delivery/                  # Delivery page scripts
│   └── Main/                      # Authentication scripts
├── Main/                          # Authentication pages
│   ├── Index.php                  # Login page
│   ├── Register.php               # User registration
│   ├── Forgot.php                 # Password recovery
│   └── Logout.php                 # Session termination
├── Database/                      # Database schemas and seeds
│   └── e_bregister.sql            # Database initialization script
├── Image/                         # Application image assets
│   ├── Admin/
│   ├── Customer/
│   ├── Delivery/
│   └── Main/
└── images/                        # Logo and branding assets
```

## 🚀 Getting Started

### Prerequisites

*   **XAMPP:** Installed and running (Apache, PHP, MySQL).
*   **Git:** For cloning and committing changes from local to remote.
*   **GitHub:** For Remote Repository.

### Installation & Setup

1.  **Start XAMPP:** Ensure Apache and MySQL services are running.
2.  **Clone Repository into `htdocs`:**
    *   Navigate to your XAMPP `htdocs` directory.
    *   Run: `git clone https://github.com/AndreiOchangco/E-Baon_Online-Delivery-System.git`
    *   `cd E-Baon_Online-Delivery-System`

3.  **Database Setup:**
    *   Go to `http://localhost/phpmyadmin`.
    *   Create a new database named `e_bregister` (collation `utf8mb4_general_ci`).
    *   Select `e_bregister`, go to "Import", choose `E-Baon_Online-Delivery-System/Database/e_bregister.sql` (or the correct path to your SQL file), and click "Go".

4.  **Configure Database Connection (if necessary):**
    *   Check your PHP database connection files.
    *   Default XAMPP credentials: Host: `localhost`, User: `root`, Password: `(empty)`, DB: `e_bregister`.

5.  **Accessing the Application:**
    *   **Customer Site:** `http://localhost/E-Baon_Online-Delivery-System/` (or `http://localhost/E-Baon_Online-Delivery-System/Main/`)
    *   **Admin Panel:** `http://localhost/E-Baon_Online-Delivery-System/Main/` (or your specific admin path).
        *   *Default Admin Credentials (if any):* Username: `e.baon@gmail.com`, Password: `admin` (Please update)

## 🔐 User Roles & Authentication

The system implements a role-based access control (RBAC) system with three distinct user roles:

### Customer Role
- Access to customer dashboard and menu browsing
- Ability to create and manage orders
- Profile management and order history tracking
- Real-time order status monitoring

### Admin Role
- Full access to administrative dashboard
- Metrics and analytics management
- Order monitoring and status overview
- Dashboard statistics and reporting

### Delivery Rider Role
- Access to assigned orders and delivery management
- Order status update capabilities
- Rider profile management
- Delivery tracking and order completion

## 💻 User Workflows

### Customer Workflow:
1. Register/Login to customer account
2. Browse available menu items
3. Create new order with selected items
4. Track order status in real-time
5. Update profile information as needed
6. View order history

### Admin Workflow:
1. Login to admin dashboard
2. View real-time business metrics
3. Monitor order statistics
4. Update dashboard metrics
5. Analyze monthly trends and revenue
6. Generate business reports

### Delivery Rider Workflow:
1. Login to delivery account
2. View assigned orders
3. Accept or manage delivery assignments
4. Update order status during delivery
5. Mark orders as completed
6. Manage rider profile

## 🔄 Database Management

### Key Tables:
- **users:** Stores user account information with role-based access
- **menu_items:** Catalog of available items with pricing and categories
- **orders:** Customer order records and order details
- **order_status:** Tracks order progress through delivery lifecycle
- **dashboard_stats:** Admin dashboard metrics and KPIs
- **monthly_orders:** Monthly order tracking for analytics
- **monthly_revenue:** Revenue tracking for financial reporting

### Database Connection:
The application uses PHP PDO (PHP Data Objects) for secure database operations with prepared statements to prevent SQL injection attacks.

## 🚀 Running the Application

### Start Development Environment:
1. Launch XAMPP Control Panel
2. Start Apache and MySQL services
3. Navigate to `http://localhost/E-Baon_Online-Delivery-System/Main/`
4. Login with appropriate credentials

### Access Different User Interfaces:
- **Customer Dashboard:** After login as customer
- **Admin Dashboard:** After login as admin
- **Delivery Dashboard:** After login as delivery rider

## 🛡️ Security Considerations

- All password fields are hashed using secure PHP functions
- Session-based authentication prevents unauthorized access
- Database queries use prepared statements to prevent SQL injection
- Role-based access control ensures users can only access their designated areas
- **IMPORTANT:** Update default admin credentials immediately in production

## 📊 Performance & Scalability

The system is designed with the following considerations:
- Efficient database queries with proper indexing
- Session management for scalable user handling
- Modular code structure for easy maintenance
- Static assets (CSS, JavaScript, images) for faster loading
- Responsive design that works across devices

## 📝 License

This work is licensed under a [Creative Commons Attribution-NonCommercial 4.0 International License](https://creativecommons.org/licenses/by-nc/4.0/).
You are free to Share and Adapt the material, under the terms of Attribution and NonCommercial use.
[![License: CC BY-NC 4.0](https://licensebuttons.net/l/by-nc/4.0/88x31.png)](https://creativecommons.org/licenses/by-nc/4.0/)

## 👤 Contributors

*   **Team Developers**
    *   **Andrei Luise Ochangco** - Team Leader, Repository Maintainer, Software Engineer, Sub-UI Designer, Sub-Programmer, Dependencies Checker, Database Administrator - [@AndreiOchangco](https://github.com/AndreiOchangco)
    *   **Louis Ricardo Servito** - Front-end Designer - [@Lone-collab](https://github.com/Lone-collab)
    *   **Ardy Aquino** - Front-end Designer, Back-end Programmer - [@](https://github.com/ardy05aquino-creator)
    *   **Jonardson Ramat** - Assistant
    *   **Mc Harley Disu** - Assistant

</details>
