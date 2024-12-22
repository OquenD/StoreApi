# E-commerce API with Laravel

A RESTful API built with Laravel for managing an e-commerce platform. This API handles user authentication, product management, shopping cart functionality, and order processing.

## Features

- User authentication using Laravel Sanctum
- Product management with variants (size, color, etc.)
- Shopping cart functionality
- Order processing
- Search and filter products

## Requirements

- PHP >= 8.1
- Composer
- MySQL >= 8.0
- Laravel 10.x

## Local Setup

1. Clone the repository:
```bash
git clone <repository-url>
cd <project-folder>
```

2. Install dependencies:
```bash
composer install
```

3. Create environment file:
```bash
cp .env.example .env
```

4. Configure your database in the .env file:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_user
DB_PASSWORD=your_database_password
```

5. Generate application key:
```bash
php artisan key:generate
```

6. Run migrations:
```bash
php artisan migrate
```

7. Start the development server:
```bash
php artisan serve
```

The API will be available at `http://localhost:8000`

## API Endpoints

### Authentication

#### Register User
- **POST** `/api/v1/register`
- **Body:**
```json
{
    "name": "string",
    "email": "string",
    "password": "string"
}
```

#### Login
- **POST** `/api/v1/login`
- **Body:**
```json
{
    "email": "string",
    "password": "string"
}
```

#### Logout
- **POST** `/api/v1/logout`
- **Headers:** Bearer Token required

### Products

#### List Products
- **GET** `/api/v1/products`
- **Query Parameters:**
  - per_page: number (default: 10)

#### Get Product
- **GET** `/api/v1/products/{id}`

#### Search Products
- **GET** `/api/v1/products/search`
- **Query Parameters:**
  - name: string
  - min_price: number
  - max_price: number
  - color: string
  - size: string
  - brand: string
  - collection: string
  - gender: string

### Shopping Cart (Protected Routes)

#### View Cart
- **GET** `/api/v1/cart`
- **Headers:** Bearer Token required

#### Add to Cart
- **POST** `/api/v1/cart/add`
- **Headers:** Bearer Token required
- **Body:**
```json
{
    "variant_id": "number",
    "quantity": "number"
}
```

#### Update Cart Item
- **PUT** `/api/v1/cart/update/{cartItemId}`
- **Headers:** Bearer Token required
- **Body:**
```json
{
    "quantity": "number"
}
```

#### Remove from Cart
- **DELETE** `/api/v1/cart/remove/{cartItemId}`
- **Headers:** Bearer Token required

### Orders (Protected Routes)

#### Create Order
- **POST** `/api/v1/orders/create`
- **Headers:** Bearer Token required
- **Body:**
```json
{
    "payment_method": "string",
    "shipping_address": {
        "street": "string",
        "city": "string",
        "state": "string",
        "postal_code": "string",
        "country": "string"
    }
}
```

#### List Orders
- **GET** `/api/v1/orders`
- **Headers:** Bearer Token required

#### Get Order Details
- **GET** `/api/v1/orders/{id}`
- **Headers:** Bearer Token required

## Authentication

The API uses Laravel Sanctum for authentication. After logging in, you'll receive a token that should be included in subsequent requests as a Bearer token in the Authorization header:

```
Authorization: Bearer <your-token>
```

## Error Handling

The API returns standard HTTP status codes:

- 200: Success
- 201: Created
- 400: Bad Request
- 401: Unauthorized
- 403: Forbidden
- 404: Not Found
- 422: Validation Error
- 500: Server Error

Error responses include a message and, when applicable, validation details:

```json
{
    "message": "The given data was invalid.",
    "errors": {
        "field": ["Error message"]
    }
}
```

## Security

- All passwords are hashed
- Protected routes require authentication
- Users can only access their own resources (cart, orders)
- Input validation is implemented for all endpoints
- CORS middleware is configured for API access

## Testing

To run the tests:

```bash
php artisan test
```

## License

This project is open-sourced software licensed under the MIT license.
