#!/usr/bin/env bash

# [
#     'id' => '550e8400-e29b-41d4-a716-446655440000',
#     'origin' => [
#         'latitude' => 40.7128,
#         'longitude' => -74.0060,
#     ],
#     'destiny' => [
#         'latitude' => 34.0522,
#         'longitude' => -118.2437,
#     ],
#     'transaction' => [
#         'amount' => 100,
#         'timestamp' => '2024-05-15 12:30:00',
#     ],
# ]


# Define functions for create, cancel, and view
create_function () {
    echo -e 'Creating the race...'
    curl -X POST \
        -H 'Content-Type: application/json' \
        -d '{ "origin": { "latitude": 40.7128, "longitude": -74.0060 }, "destiny": { "latitude": 34.0522, "longitude": -118.2437 }, "transaction": { "amount": 100, "timestamp": "2024-05-15 12:30:00" } }' \
        http://localhost:80/races
}

cancel_function () {
    if [ -z "$2" ]; then
        echo "Please provide a UUID to cancel."
        exit 1
    fi
    
    curl -X POST \
        -H 'Content-Type: application/json' \
        -d '{ "description": "Vou à pé", "reason": "Others" }' \
        http://localhost:80/races/"$2"/cancellation
}

view_function () {
    if [ -z "$2" ]; then
        echo "Please provide a UUID to view."
        exit 1
    fi

    curl -X GET \
        -H 'Content-Type: application/json' \
        http://localhost:80/races/"$2"
}

# Check if the first parameter is provided
if [ -z "$1" ]; then
    echo "Please provide a valid option: create, cancel, or view"
    exit 1
fi

# Use a case statement to determine which function to call
case "$1" in
    "create")
        create_function
        ;;
    "cancel")
        cancel_function "$@"
        ;;
    "view")
        view_function "$@"
        ;;
    *)
        echo "Invalid option: $1. Please provide a valid option: create, cancel, or view"
        exit 1
        ;;
esac

exit 0