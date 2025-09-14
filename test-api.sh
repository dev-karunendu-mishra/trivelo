#!/bin/bash

# Trivelo API Testing Script
# This script provides sample API calls for testing the Trivelo Hotel Booking API

BASE_URL="http://127.0.0.1:8000"
API_URL="$BASE_URL/api"

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

echo -e "${BLUE}=== Trivelo API Testing Script ===${NC}"
echo -e "${YELLOW}Base URL: $BASE_URL${NC}"
echo -e "${YELLOW}API Documentation: $BASE_URL/api/documentation${NC}"
echo ""

# Function to make API calls
make_request() {
    local method=$1
    local endpoint=$2
    local data=$3
    local token=$4
    
    echo -e "${BLUE}Making $method request to: $API_URL$endpoint${NC}"
    
    if [ -n "$token" ]; then
        if [ -n "$data" ]; then
            curl -X $method \
                -H "Content-Type: application/json" \
                -H "Authorization: Bearer $token" \
                -d "$data" \
                "$API_URL$endpoint" | jq .
        else
            curl -X $method \
                -H "Authorization: Bearer $token" \
                "$API_URL$endpoint" | jq .
        fi
    else
        if [ -n "$data" ]; then
            curl -X $method \
                -H "Content-Type: application/json" \
                -d "$data" \
                "$API_URL$endpoint" | jq .
        else
            curl -X $method "$API_URL$endpoint" | jq .
        fi
    fi
    echo ""
}

# 1. Authentication
echo -e "${GREEN}=== 1. AUTHENTICATION ===${NC}"

# Register new user
echo -e "${YELLOW}1.1 Register new customer${NC}"
REGISTER_DATA='{
  "name": "John Doe",
  "email": "john.doe@example.com",
  "password": "password123",
  "password_confirmation": "password123",
  "phone": "+1234567890"
}'
make_request "POST" "/auth/register" "$REGISTER_DATA"

# Login user
echo -e "${YELLOW}1.2 Login user${NC}"
LOGIN_DATA='{
  "email": "john.doe@example.com",
  "password": "password123"
}'
RESPONSE=$(curl -s -X POST \
    -H "Content-Type: application/json" \
    -d "$LOGIN_DATA" \
    "$API_URL/auth/login")
    
echo $RESPONSE | jq .

# Extract token for future requests
TOKEN=$(echo $RESPONSE | jq -r '.data.token // empty')

if [ -n "$TOKEN" ] && [ "$TOKEN" != "null" ]; then
    echo -e "${GREEN}Token obtained: ${TOKEN:0:20}...${NC}"
else
    echo -e "${RED}Failed to obtain token. Using existing user...${NC}"
    # Try with existing user
    LOGIN_DATA_EXISTING='{
      "email": "admin@trivelo.com",
      "password": "password"
    }'
    RESPONSE=$(curl -s -X POST \
        -H "Content-Type: application/json" \
        -d "$LOGIN_DATA_EXISTING" \
        "$API_URL/auth/login")
    TOKEN=$(echo $RESPONSE | jq -r '.data.token // empty')
fi

# 2. Hotels
echo -e "${GREEN}=== 2. HOTELS ===${NC}"

# List hotels
echo -e "${YELLOW}2.1 List hotels${NC}"
make_request "GET" "/hotels"

# List hotels with filters
echo -e "${YELLOW}2.2 Search hotels with filters${NC}"
make_request "GET" "/hotels?city=New York&star_rating=4&min_price=100"

# Get hotel details
echo -e "${YELLOW}2.3 Get hotel details${NC}"
make_request "GET" "/hotels/1"

# 3. Rooms
echo -e "${GREEN}=== 3. ROOMS ===${NC}"

# List hotel rooms
echo -e "${YELLOW}3.1 List hotel rooms${NC}"
make_request "GET" "/hotels/1/rooms"

# Get room details
echo -e "${YELLOW}3.2 Get room details${NC}"
make_request "GET" "/rooms/1"

# Check room availability
echo -e "${YELLOW}3.3 Check room availability${NC}"
AVAILABILITY_DATA='{
  "check_in_date": "2024-12-01",
  "check_out_date": "2024-12-05"
}'
make_request "POST" "/rooms/1/check-availability" "$AVAILABILITY_DATA"

# 4. Bookings (requires authentication)
if [ -n "$TOKEN" ] && [ "$TOKEN" != "null" ]; then
    echo -e "${GREEN}=== 4. BOOKINGS (Authenticated) ===${NC}"
    
    # List user bookings
    echo -e "${YELLOW}4.1 List user bookings${NC}"
    make_request "GET" "/bookings" "" "$TOKEN"
    
    # Create booking
    echo -e "${YELLOW}4.2 Create booking${NC}"
    BOOKING_DATA='{
      "room_id": 1,
      "check_in_date": "2024-12-01",
      "check_out_date": "2024-12-05",
      "guests_count": 2,
      "special_requests": "Late check-in requested"
    }'
    make_request "POST" "/bookings" "$BOOKING_DATA" "$TOKEN"
    
    # Get current user info
    echo -e "${YELLOW}4.3 Get current user info${NC}"
    make_request "GET" "/auth/me" "" "$TOKEN"
    
else
    echo -e "${RED}=== 4. BOOKINGS - SKIPPED (No valid token) ===${NC}"
fi

# 5. Amenities
echo -e "${GREEN}=== 5. AMENITIES ===${NC}"

# List amenities
echo -e "${YELLOW}5.1 List amenities${NC}"
make_request "GET" "/amenities"

echo -e "${GREEN}=== API Testing Complete ===${NC}"
echo -e "${YELLOW}For interactive testing, visit: $BASE_URL/api/documentation${NC}"