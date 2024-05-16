
# Define functions for create, cancel, and view
function create_function {
    $body = @{
        origin = @{
            latitude = 40.7128
            longitude = -74.0060
        }
        destiny = @{
            latitude = 34.0522
            longitude = -118.2437
        }
        transaction = @{
            amount = 100
            timestamp = "2024-05-15 12:30:00"
        }
    } | ConvertTo-Json

    Invoke-RestMethod -Method Post `
                      -Uri 'http://localhost:80/races' `
                      -ContentType 'application/json' `
                      -Body $body
}

function cancel_function {
    param(
        [string]$uuid
    )

    if (-not $uuid) {
        Write-Host "Please provide a UUID to cancel."
        exit 1
    }

    $body = @{
        description = "Vou à pé"
        reason = "Others"
    } | ConvertTo-Json

    Invoke-RestMethod -Method Post `
                      -Uri "http://localhost:80/races/$uuid/cancellation" `
                      -ContentType 'application/json' `
                      -Body $body
}

function view_function {
    param(
        [string]$uuid
    )

    if (-not $uuid) {
        Write-Host "Please provide a UUID to view."
        exit 1
    }

    Invoke-RestMethod -Method Get `
                      -Uri "http://localhost:80/races/$uuid" `
                      -ContentType 'application/json'
}

# Check if the first parameter is provided
if (-not $args) {
    Write-Host "Please provide a valid option: create, cancel, or view"
    exit 1
}

# Use a switch statement to determine which function to call
switch ($args[0]) {
    "create" {
        create_function
        break
    }
    "cancel" {
        cancel_function -uuid $args[1]
        break
    }
    "view" {
        view_function -uuid $args[1]
        break
    }
    default {
        Write-Host "Invalid option: $($args[0]). Please provide a valid option: create, cancel, or view"
        exit 1
    }
}

exit 0