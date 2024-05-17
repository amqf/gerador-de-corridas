#!/usr/bin/env bash

# Exibe a mensagem de ajuda
display_help() {
    echo "Usage: $0 [options]"
    echo "Options:"
    echo "  --create                        Create a new race"
    echo "  --cancel <UUID>                 Cancel a race with the specified UUID"
    echo "  --view <UUID>                   View details of a race with the specified UUID"
    echo "  --auto_cancel_after <SECONDS>   Create a race and automatically cancel it after the specified time"
    echo "  --pay <UUID> <VALUE>            Pay for a race with the specified UUID and amount in reals"
    echo "  --help                          Display this help message"
}

# Verifica se o primeiro parâmetro é fornecido
if [ "$1" == "--help" ]; then
    display_help
    exit 0
fi

# Define functions for create, cancel, and view
create_function () {
    curl -sS -X POST \
        -H 'Content-Type: application/json' \
        -d '{ "origin": { "latitude": -22.302407981128297, "longitude": -49.10229971613744 }, "destiny": { "latitude": -22.302715314470994, "longitude": -49.101353497779776 } }' \
        http://localhost:80/races
}

cancel_function () {
    if [ -z "$2" ]; then
        echo "Please provide a UUID to cancel."
        exit 1
    fi

    curl -sS -X POST \
        -H 'Content-Type: application/json' \
        -d '{ "description": "Vou à pé", "reason": "Others" }' \
        http://localhost:80/races/"$2"/cancellation
}

pay_race_function () {
    if [ -z "$2" ]; then
        echo "Please provide a race UUID to pay."
        exit 1
    fi

    if [ -z "$3" ]; then
        echo "Please provide a value in reals"
        exit 1
    fi

    curl -sS -X POST \
        -H 'Content-Type: application/json' \
        -d '{ "amount": '$3' }' \
        http://localhost:80/races/"$2"/payment
}

auto_cancel_after_function () {
    if [ -z "$2" ]; then
        echo "Please provide a SECONDS_TO_SLEEP_IN_SECONDS before cancel the race"
        exit 1
    fi

    echo -e 'Creating one race to be canceled...'
    RACE_UUID=$(create_function | jq -r '.id')
    echo -e "The created race has uuid $RACE_UUID"

    echo -e "Waiting $2 seconds before cancel the race..."
    sleep "$2s"
    
    echo -e "Cancelling the race with uuid $RACE_UUID..."
    curl -sS -X POST \
        -H 'Content-Type: application/json' \
        -d '{ "description": "Vou à pé", "reason": "Others" }' \
        "http://localhost:80/races/$RACE_UUID/cancellation"
}

view_function () {
    if [ -z "$2" ]; then
        echo "Please provide a UUID to view."
        exit 1
    fi

    curl -sS -X GET \
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
    "--create")
        create_function
        ;;
    "--cancel")
        cancel_function "$@"
        ;;
    "--view")
        view_function "$@"
        ;;
    "--auto_cancel_after")
        auto_cancel_after_function "$@"
        ;;
    "--pay")
        pay_race_function "$@"
        ;;
    *)
        echo "Invalid option: $1. Please provide a valid option: create, cancel, or view"
        exit 1
        ;;
esac

exit 0