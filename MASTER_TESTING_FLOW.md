# Spacall API: End-to-End Master Testing Flow 🚀

Follow this sequence in Postman to test the full system using two different accounts (Therapist and Customer).

---

## 🛠️ PHASE 1: THERAPIST SETUP (The "Provider" App)

### 1.1 Request OTP for Therapist
- **POST** `/api/auth/entry`
- **Body**: `{ "mobile_number": "09111111111" }`

### 1.2 Verify OTP for Therapist
- **POST** `/api/auth/verify-otp`
- **Body**: `{ "mobile_number": "09111111111", "otp": "123456" }`

### 1.3 Register as Therapist (Role is Critical)
- **POST** `/api/auth/register-profile`
- **Body**:
  ```json
  {
      "mobile_number": "09111111111",
      "first_name": "Test",
      "last_name": "Therapist",
      "gender": "male",
      "date_of_birth": "1990-01-01",
      "pin": "123456",
      "role": "therapist"
  }
  ```

### 1.4 Login as Therapist
- **POST** `/api/auth/login-pin`
- **Body**: `{ "mobile_number": "09111111111", "pin": "123456" }`
- **Action**: Save the `token` (e.g., **TOKEN_T**).

---

## 🟢 PHASE 2: CUSTOMER SETUP (The "Booking" App)

### 2.1 Request OTP for Customer
- **POST** `/api/auth/entry`
- **Body**: `{ "mobile_number": "09222222222" }`

### 2.2 Verify OTP for Customer
- **POST** `/api/auth/verify-otp`
- **Body**: `{ "mobile_number": "09222222222", "otp": "123456" }`

### 2.3 Register as Customer
- **POST** `/api/auth/register-profile`
- **Body**:
  ```json
  {
      "mobile_number": "09222222222",
      "first_name": "Test",
      "last_name": "Customer",
      "gender": "female",
      "date_of_birth": "1995-05-05",
      "pin": "123456",
      "role": "client"
  }
  ```

### 2.4 Login as Customer
- **POST** `/api/auth/login-pin`
- **Body**: `{ "mobile_number": "09222222222", "pin": "123456" }`
- **Action**: Save the `token` (e.g., **TOKEN_C**).

---

## 💆‍♂️ PHASE 3: THE BOOKING FLOW

### 3.1 [Customer] Find the Therapist
- **GET** `/api/bookings/available-therapists?latitude=14.5&longitude=120.9&radius=10`
- **Headers**: `Authorization: Bearer TOKEN_C`
- **Action**: Note the `uuid` and `provider_id` of your therapist.

### 3.1.2 [Customer] View Therapist Details (The "Details" Screen)
- **GET** `/api/therapists/{uuid}`
- **Headers**: `Authorization: Bearer TOKEN_C`
- **Verification**: Check if you can see their `bio`, `years_of_experience`, and `services`.

### 3.2 [Customer] Book the Service
- **POST** `/api/bookings`
- **Headers**: `Authorization: Bearer TOKEN_C`
- **Body**:
  ```json
  {
      "service_id": 1,
      "provider_id": 11,
      "address": "Client House Address",
      "latitude": 14.5,
      "longitude": 120.9,
      "city": "Manila",
      "province": "Metro Manila",
      "customer_notes": "Door bell is broken."
  }
  ```
- **Action**: Save the `booking_id` from response.

### 3.3 [Therapist] View My Active Job
- **GET** `/api/bookings`
- **Headers**: `Authorization: Bearer TOKEN_T`
- **Verification**: You should see the booking you just made.

### 3.4 [Therapist] Update Progress
- **PATCH** `/api/bookings/{id}/status`
- **Headers**: `Authorization: Bearer TOKEN_T`
- **Body**: `{ "status": "en_route" }`
- **Body**: `{ "status": "arrived" }`
- **Body**: `{ "status": "completed" }`

### 3.5 [Customer] Give a Review
- **POST** `/api/bookings/{id}/reviews`
- **Headers**: `Authorization: Bearer TOKEN_C`
- **Body**:
  ```json
  {
      "rating": 5,
      "body": "Therapist was professional!"
  }
  ```

### 3.6 [Therapist] Check New Rating
- **GET** `/api/therapist/profile`
- **Headers**: `Authorization: Bearer TOKEN_T`
- **Verification**: Check if `average_rating` and `total_reviews` increased.
