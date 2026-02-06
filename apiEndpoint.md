# Spacall Wallet API Documentation

---

## 🟦 Authentication Flow

### 1. Initial Entry
Check if the user exists and decide the next step.
- **POST** `/auth/entry`
- **Body**: `{ "mobile_number": "09123456789" }`
- **Sample Response (New User)**:
  ```json
  {
      "message": "OTP sent successfully",
      "next_step": "otp_verification"
  }
  ```
- **Sample Response (Existing User)**:
  ```json
  {
      "message": "User found",
      "next_step": "pin_login"
  }
  ```

### 2. Path A: New User (Registration)

#### 2.1 Verify OTP
Confirm the code sent to the mobile number.
- **POST** `/auth/verify-otp`
- **Body**: `{ "mobile_number": "09123456789", "otp": "123456" }`
- **Sample Response**:
  ```json
  {
      "message": "OTP verified successfully",
      "next_step": "registration"
  }
  ```

#### 2.2 Register Profile & Set PIN
- **POST** `/auth/register-profile`
- **Method**: `POST` (Multipart Form-Data)
- **Body**: `first_name`, `last_name`, `gender`, `date_of_birth`, `profile_photo`, `pin` (6 digits), `mobile_number`
- **Sample Response**:
  ```json
  {
      "message": "Profile registered successfully",
      "token": "1|abc123token...",
      "user": { "id": 1, "first_name": "John", "last_name": "Doe", ... }
  }
  ```

### 3. Path B: Returning User (Login)

#### 3.1 Login with PIN
- **POST** `/auth/login-pin`
- **Body**: `{ "mobile_number": "09123456789", "pin": "112233" }`
- **Sample Response**:
  ```json
  {
      "message": "Login successful",
      "token": "2|xyz456token...",
      "user": { "id": 1, "first_name": "John", "last_name": "Doe", ... }
  }
  ```

---

## 🟢 Therapist (Protected)
*Requires Bearer Token in Header*

### 1. List All Therapists
- **URL**: `GET /therapists`
- **Sample Response**:
  ```json
  {
      "therapists": [
          {
              "uuid": "550e8400-e29b-41d4-a716-446655440000",
              "user": { "first_name": "John", "last_name": "Doe", "profile_photo_url": "..." },
              "therapist_profile": { "base_rate": 800, "specializations": ["Swedish"] }
          }
      ]
  }
  ```

### 2. View Single Therapist
- **URL**: `GET /therapists/{uuid}`
- **Sample Response**:
  ```json
  {
      "therapist": {
          "uuid": "...",
          "total_earnings": 1500,
          "average_rating": 4.8,
          "user": { ... },
          "therapist_profile": {
              "bio": "Expert in Swedish massage...",
              "specializations": ["Swedish", "Deep Tissue"],
              "base_rate": 800,
              "default_schedule": { ... }
          }
      }
  }
  ```

### 3. View My Profile
- **URL**: `GET /therapist/profile`
- **Sample Response**: Same as "View Single Therapist" but for the authenticated user.

---

## 🟣 Services (Protected)
*Requires Bearer Token in Header*

### 1. List Services
- **URL**: `GET /services`
- **Sample Response**:
  ```json
  {
      "categories": [
          {
              "name": "Wellness & Spa",
              "services": [
                  { "name": "Swedish Massage", "base_price": 800, "slug": "swedish-massage" }
              ]
          }
      ]
  }
  ```
