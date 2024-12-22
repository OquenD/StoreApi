# Laravel E-commerce API

A RESTful API built with Laravel for managing an e-commerce platform. This API handles user authentication, product management, shopping cart functionality, and order processing.

## Features

- User Authentication (Register, Login, Logout)
- Product Management (CRUD operations)
- Shopping Cart Management
- Order Processing
- Product Search and Filtering

## Requirements

- PHP >= 8.0
- Composer
- MySQL/PostgreSQL
- Laravel Sanctum (for authentication)

## Installation

1. Clone the repository:
```bash
git clone <https://github.com/OquenD/StoreApi/>
cd <project-directory>
```

2. Install dependencies:
```bash
composer install
```

3. Create and configure your `.env` file:
```bash
cp .env.example .env
```

4. Configure your database settings in `.env`:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

5. Generate application key:
```bash
php artisan key:generate
```

6. Run database migrations:
```bash
php artisan migrate
```

7. Start the development server:
```bash
php artisan serve
```

## API Endpoints

### Authentication

#### Register
- **POST** `/api/v1/register`
- Body:
  ```json
  {
    "name": "string",
    "email": "string",
    "password": "string (min: 8 characters)"
  }
  ```

#### Login
- **POST** `/api/v1/login`
- Body:
  ```json
  {
    "email": "string",
    "password": "string"
  }
  ```

#### Logout
- **POST** `/api/v1/logout`
- Requires: Authentication Token

#### Get Profile
- **GET** `/api/v1/profile`
- Requires: Authentication Token

### Products

#### List Products
- **GET** `/api/v1/products`
- Query Parameters:
  - `per_page`: number (default: 10)

#### Get Single Product
- **GET** `/api/v1/products/{id}`

#### Create Product
- **POST** `/api/v1/products`
- Requires: Authentication Token
- Body:
  ```json
  {
    "name": "string",
    "description": "string",
    "price": "number",
    "other_attributes": "json",
    "variants": [
      {
        "color": "string",
        "size": "string",
        "stock": "number"
      }
    ]
  }
  ```

#### Update Product
- **PUT** `/api/v1/products/{id}`
- Requires: Authentication Token

#### Delete Product
- **DELETE** `/api/v1/products/{id}`
- Requires: Authentication Token

#### Search Products
- **POST** `/api/v1/products/search`
- Query Parameters:
  - `name`: string
  - `min_price`: number
  - `max_price`: number
  - `attributes`: string
  - `value`: string
  - `color`: string

### Shopping Cart

#### View Cart
- **GET** `/api/v1/cart`
- Requires: Authentication Token

#### Add Item to Cart
- **POST** `/api/v1/cart/items`
- Requires: Authentication Token
- Body:
  ```json
  {
    "variant_id": "number",
    "quantity": "number"
  }
  ```

#### Update Cart Item
- **PUT** `/api/v1/cart/items/{cartItemId}`
- Requires: Authentication Token
- Body:
  ```json
  {
    "quantity": "number"
  }
  ```

#### Remove Cart Item
- **DELETE** `/api/v1/cart/items/{cartItemId}`
- Requires: Authentication Token

### Orders

#### List Orders
- **GET** `/api/v1/orders`
- Requires: Authentication Token

#### Create Order
- **POST** `/api/v1/orders`
- Requires: Authentication Token

#### Get Order Details
- **GET** `/api/v1/orders/{id}`
- Requires: Authentication Token

## Authentication

This API uses Laravel Sanctum for authentication. After logging in, you'll receive a token that should be included in subsequent requests as a Bearer token in the Authorization header:

```
Authorization: Bearer <your-token>
```

## Error Handling

The API returns appropriate HTTP status codes and error messages:

- 200: Success
- 201: Created
- 400: Bad Request
- 401: Unauthorized
- 403: Forbidden
- 404: Not Found
- 500: Internal Server Error

## Development

To run tests:
```bash
php artisan test
```

## Contributing

1. Fork the repository
2. Create your feature branch
3. Commit your changes
4. Push to the branch
5. Create a new Pull Request

## License

[MIT License](LICENSE)
