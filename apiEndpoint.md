# Spacall Wallet API Documentation

---

## 🟦 Authentication Flow

### 1. Initial Entry
Check if the user exists and decide the next step.
- **POST** `/auth/entry`
- **Body**: `{ "mobile_number": "09123456789" }`
- **Response**: Returns `otp_verification` for new users or `pin_login` for existing.

### 2. Path A: New User (Registration)

#### 2.1 Verify OTP
Confirm the code sent to the mobile number.
- **POST** `/auth/verify-otp`
- **Body**: `{ "mobile_number": "09123456789", "otp": "123456" }`
- **Response**: `{ "next_step": "registration" }`

#### 2.2 Register Profile & Set PIN
- **POST** `/auth/register-profile`
- **Method**: `POST` (Multipart Form-Data)
- **Body**: `first_name`, `last_name`, `gender`, `date_of_birth`, `profile_photo`, `pin` (6 digits), `mobile_number`

### 3. Path B: Returning User (Login)

#### 3.1 Login with PIN
- **POST** `/auth/login-pin`
- **Body**: `{ "mobile_number": "09123456789", "pin": "112233" }`
- **Response**: Returns Bearer Token.

### 4. Forgot PIN Flow

#### 4.1 Request Reset OTP
- **POST** `/auth/forgot-pin`
- **Body**: `{ "mobile_number": "09123456789" }`

#### 4.2 Reset PIN with OTP
- **POST** `/auth/reset-pin`
- **Body**:
  ```json
  {
    "mobile_number": "09123456789",
    "otp": "123456",
    "new_pin": "665544"
  }
  ```

---

## 🟣 Services (Protected)
*Requires Bearer Token in Header*

### 1. List Services
Get all active services grouped by category.
- **URL**: `GET /services`
- **Headers**: `Authorization: Bearer {token}`

### 2. Service Detail
- **URL**: `GET /services/{slug}`
- **Sample**: `GET /services/swedish-massage`
- **Headers**: `Authorization: Bearer {token}`
- **Response**: Includes `benefits` and `contraindications`.
