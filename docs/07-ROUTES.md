# Application Routes

## Public Routes

```
GET  /                  - Homepage
GET  /menu              - Menu page
GET  /contact           - Contact page
GET  /login             - Login form
GET  /register          - Register form
POST /login             - Process login
POST /register          - Process register
POST /logout            - Logout
```

## User Routes (Authenticated)

```
GET  /checkout          - Checkout page
POST /orders            - Create order
GET  /order-status      - Order status page
GET  /order-history     - Order history
GET  /profile           - User profile
PUT  /profile           - Update profile
```

## Admin Routes (Admin Only)

```
GET    /admin/dashboard           - Admin dashboard
GET    /admin/orders              - All orders
PUT    /admin/orders/{id}/status  - Update order status
PUT    /admin/orders/{id}/time    - Update estimated time
DELETE /admin/orders/{id}         - Delete order

GET    /admin/menus               - List menus
GET    /admin/menus/create        - Create menu form
POST   /admin/menus               - Store menu
GET    /admin/menus/{id}/edit     - Edit menu form
PUT    /admin/menus/{id}          - Update menu
DELETE /admin/menus/{id}          - Delete menu
```

## Broadcasting Routes

```
POST /broadcasting/auth  - Authenticate broadcast channel
```

## API Endpoints (if applicable)

```
GET    /api/menus              - Get all menus
GET    /api/menus/{id}         - Get menu detail
GET    /api/orders/{id}/status - Get order status
POST   /api/orders             - Create order (API)
```

## Route Groups

### Public Group
- No authentication required
- Accessible to everyone

### User Group
- Requires authentication
- Middleware: `auth`
- User-specific features

### Admin Group
- Requires authentication + admin role
- Middleware: `auth`, `isAdmin`
- Admin-only features

## Middleware

- `auth` - Check if user authenticated
- `isAdmin` - Check if user is admin
- `verified` - Check if email verified (optional)
- `throttle:60,1` - Rate limiting

## Example Usage

### Login
```bash
POST /login
Content-Type: application/x-www-form-urlencoded

email=user@coffee.com&password=password
```

### Create Order
```bash
POST /orders
Authorization: Bearer token
Content-Type: application/json

{
  "customer_name": "John Doe",
  "phone": "081234567890",
  "address": "Jl. Merdeka No. 1",
  "items": [
    {"menu_id": 1, "quantity": 2}
  ],
  "pickup_option": "delivery",
  "payment_method": "cash"
}
```

### Update Order Status (Admin)
```bash
PUT /admin/orders/1/status
Authorization: Bearer token
Content-Type: application/json

{
  "status": "preparing"
}
```
