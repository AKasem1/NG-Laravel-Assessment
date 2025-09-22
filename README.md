# MediCare - Premium Medical Supplies E-Commerce Platform

A modern, full-featured e-commerce platform built with Laravel, specifically designed for medical supplies and healthcare products. Features a clean, responsive design with comprehensive admin management system.

## 🚀 Features

### Customer Features
- **Unified Authentication System** - Single login/register system for all users
- **Product Catalog** - Browse medical supplies with advanced filtering and search
- **Shopping Cart** - Session-based cart management with real-time updates
- **Secure Checkout** - Customer authentication required for purchase
- **Responsive Design** - Mobile-first approach with modern UI/UX
- **Product Categories** - Organized medical supply categories

### Admin Features
- **Role-Based Access Control** - Admin and Super Admin roles
- **Product Management** - Full CRUD operations for products
- **Category Management** - Organize products into categories
- **Order Management** - Track and manage customer orders
- **Customer Management** - View and manage customer accounts
- **Analytics Dashboard** - Sales reports and product analytics
- **Product Logging** - Track product changes and history

### Technical Features
- **Multi-Guard Authentication** - Separate authentication for customers and admins
- **AJAX Cart Operations** - Real-time cart updates without page refresh
- **Modern CSS Framework** - Custom CSS with Bootstrap integration
- **Responsive Navigation** - Adaptive navbar with role-based display
- **Toast Notifications** - Modern notification system

## 🛠 Tech Stack
- **Backend**: Laravel 10+ (PHP 8.1+)
- **Frontend**: Blade Templates, Bootstrap 5, Custom CSS
- **Database**: MySQL 8+
- **Authentication**: Laravel Guards (Multi-guard system)
- **Session Management**: Laravel Sessions
- **Icons**: Bootstrap Icons
- **JavaScript**: Vanilla JS with modern ES6+ features

## 📋 Prerequisites
- PHP >= 8.1
- Composer >= 2.0
- MySQL >= 8.0
- Node.js >= 16.x (for asset compilation)
- npm or yarn

## 🔧 Installation

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd medicare-ecommerce
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Environment Configuration**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Configure Database**
   Update your `.env` file with database credentials:
   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=medicare_db
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```

5. **Run Migrations**
   ```bash
   php artisan migrate
   ```

6. **Install Frontend Dependencies (if using npm)**
   ```bash
   npm install
   npm run dev
   ```

7. **Seed Database (optional)**
   ```bash
   php artisan db:seed
   ```

8. **Start Development Server**
   ```bash
   php artisan serve
   ```

## 📁 Project Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── AdminController.php          # Admin dashboard & management
│   │   ├── CartController.php           # Shopping cart operations
│   │   ├── HomeController.php           # Public pages & product display
│   │   ├── OrderController.php          # Order processing & management
│   │   ├── ProductController.php        # Product CRUD operations
│   │   ├── CategoryController.php       # Category management
│   │   └── Auth/
│   │       └── UnifiedAuthController.php # Unified authentication system
│   └── Middleware/
│       ├── AuthenticateCustomer.php     # Customer authentication
│       └── Authenticate.php             # Admin authentication
├── Models/
│   ├── User.php                         # Admin users with roles
│   ├── Customer.php                     # Customer accounts
│   ├── Product.php                      # Product model
│   ├── Category.php                     # Product categories
│   ├── Order.php                        # Customer orders
│   ├── OrderItem.php                    # Order line items
│   └── ProductLog.php                   # Product change tracking
└── database/
    └── migrations/                      # Database schema definitions

resources/
├── views/
│   ├── layouts/
│   │   └── app.blade.php               # Main layout template
│   ├── products/
│   │   ├── index.blade.php             # Product listing
│   │   └── show.blade.php              # Product details
│   ├── cart/
│   │   └── index.blade.php             # Shopping cart
│   ├── auth/
│   │   ├── unified-login.blade.php     # Login form
│   │   └── unified-register.blade.php  # Registration form
│   └── admin/                          # Admin panel views
└── css/                                # Custom stylesheets

routes/
└── web.php                             # Application routes
```

## 🔐 Authentication System

### Multi-Guard Setup
- **Customer Guard**: For regular users (customers table)
- **Admin Guard**: For administrators (users table with roles)

### Authentication Routes
- **Login**: `/login` - Works for both customers and admins
- **Register**: `/register` - Creates customer accounts
- **Admin Register**: `/admin/register` - Restricted admin account creation
- **Logout**: `/logout` - Universal logout

### User Roles
- **Customer**: Regular shopping users
- **Admin**: Product and order management
- **Super Admin**: Full system access including user management

## 🛒 Cart System

The cart uses Laravel sessions for storage:
- **Session-based**: No database storage required for cart items
- **AJAX Operations**: Add, update, remove items without page refresh
- **Real-time Updates**: Cart count updates across the site
- **Persistent**: Cart survives browser refresh until checkout or clear

### Cart Operations
```javascript
// Add to cart
addToCart(productId, quantity)

// Update quantity
updateQuantity(productId, newQuantity)

// Remove item
removeFromCart(productId)

// Get cart count
updateCartCount()
```

## 👤 User Management

### Customer Registration Flow
1. User visits `/register`
2. Creates account (saved to customers table)
3. Automatically logged in as customer
4. Redirected to checkout or intended page

### Admin Access
- Admin users have role field: `admin` or `super_admin`
- Super admins can create other admin accounts
- Role-based UI elements (avatar with badges)

## 🏪 Product Management

### Product Features
- **Categories**: Organize products into medical categories
- **Stock Management**: Track inventory levels
- **Image Management**: Product images with gallery
- **Product Logging**: Track all product changes
- **Search & Filter**: Advanced product discovery

### Admin Product Operations
- Create, read, update, delete products
- Manage product categories
- Track product history
- Inventory management

## 📊 Admin Dashboard

### Key Sections
- **Dashboard**: Overview statistics and charts
- **Products**: Product management interface
- **Categories**: Category organization
- **Orders**: Order processing and tracking
- **Customers**: Customer account management
- **Analytics**: Sales reports and insights
- **Settings**: System configuration

## 🔄 Order Management

### Order Flow
1. Add to Cart: Products added via AJAX
2. Checkout: Customer authentication required
3. Order Creation: Order stored in database
4. Processing: Admin can track and update status
5. Completion: Customer notification and tracking

### Order Status Types
- Pending
- Processing
- Shipped
- Delivered
- Cancelled

## 🎨 Frontend Design

### Design System
- **Modern UI**: Clean, medical-themed design
- **Responsive**: Mobile-first approach
- **Color Scheme**: Cyan and orange gradient theme
- **Typography**: Inter font family
- **Components**: Reusable CSS components
- **Animations**: Smooth transitions and hover effects

### Key CSS Classes
```css
:root {
    --primary-cyan: #4ECDC4;
    --primary-orange: #FF8B3D;
    --gradient-primary: linear-gradient(135deg, #FF8B3D 0%, #4ECDC4 100%);
}
```

## 🧪 Development Guidelines

### Code Standards
- Follow Laravel best practices
- Use descriptive variable and method names
- Comment complex business logic
- Maintain consistent indentation

### Database Conventions
- Use Laravel migrations for all schema changes
- Follow Laravel naming conventions
- Create model relationships using Eloquent
- Use soft deletes where appropriate

### Security Best Practices
- Validate all user inputs
- Use CSRF protection on forms
- Sanitize data before database storage
- Implement proper authentication checks

## 🚀 Deployment

### Production Setup
- Set `APP_ENV=production` in `.env`
- Set `APP_DEBUG=false`
- Configure production database
- Run `php artisan optimize`
- Set up proper web server (Nginx/Apache)
- Configure SSL certificate

### Performance Optimization
- Enable caching (`php artisan config:cache`)
- Use CDN for static assets
- Optimize database queries
- Enable gzip compression

## 🐛 Common Issues & Solutions

### Authentication Issues
- Ensure guards are properly configured in `config/auth.php`
- Clear cache after auth changes: `php artisan config:clear`

### Cart Not Working
- Check session configuration
- Verify CSRF tokens are included in AJAX requests

### Permission Denied
- Check user roles and middleware
- Verify route protection is correctly applied

## 📞 Support & Maintenance

### Regular Maintenance
- Update Laravel and dependencies regularly
- Monitor error logs for issues
- Backup database regularly
- Review and update security measures

### Logging
- Application logs: `storage/logs/laravel.log`
- Database queries can be logged in development
- Use Laravel Telescope for debugging (development only)

## 📝 License

This project is built with Laravel framework which is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## 👥 Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Write tests for new functionality
5. Submit a pull request

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains over 2000 video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the Laravel [Patreon page](https://patreon.com/taylorotwell).

### Premium Partners

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Cubet Techno Labs](https://cubettech.com)**
- **[Cyber-Duck](https://cyber-duck.co.uk)**
- **[Many](https://www.many.co.uk)**
- **[Webdock, Fast VPS Hosting](https://www.webdock.io/en)**
- **[DevSquad](https://devsquad.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
- **[OP.GG](https://op.gg)**
- **[WebReinvent](https://webreinvent.com/?utm_source=laravel&utm_medium=github&utm_campaign=patreon-sponsors)**
- **[Lendio](https://lendio.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
