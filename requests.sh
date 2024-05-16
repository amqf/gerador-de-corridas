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

# curl -X POST \
#     -H 'Content-Type: application/json' \
#     -d '{"username": "antoniomquadrosfilho", "email": "antoniomquadrosfilho@gmail.com", "password": "abc123"}' \
#     http://localhost:8080/register


curl -X POST \
    -H 'Content-Type: application/json' \
    -d '{ "origin": { "latitude": 40.7128, "longitude": -74.0060 }, "destiny": { "latitude": 34.0522, "longitude": -118.2437 }, "transaction": { "amount": 100, "timestamp": "2024-05-15 12:30:00" } }' \
    http://localhost:8080/races

# curl -X POST \
#     -H 'Content-Type: application/json' \
#     -d '{ "description": "Vou à pé", "reason": "Others" }' \
#     http://localhost:8080/races/e54c4b5a-8c97-40a5-a747-60b98cc4163e/cancellation

# curl -X GET \
#     -H 'Content-Type: application/json' \
#     -d '{ "description": "Vou à pé", "reason": "Others" }' \
#     http://localhost:8080/races/e54c4b5a-8c97-40a5-a747-60b98cc4163e

# curl -X GET http://localhost:8080/users