#!/usr/bin/env bash

# Parse arguments
AUTO_CONFIRM=false
if [[ "$1" == "-y" ]]; then
    AUTO_CONFIRM=true
fi

# Function to run commands with optional auto-confirmation
run_cmd() {
    if $AUTO_CONFIRM; then
        echo "$@ (auto-confirmed)"
        eval "$@ -y"
    else
        echo "$@"
        eval "$@"
    fi
}

# Function to install on Ubuntu
install_on_linux() {
    echo "Detected Ubuntu. Installing dependencies..."

    # Update package lists
    run_cmd "sudo apt-get update"

    # Install PHP 8.3 and required extensions
    echo "Installing PHP 8.3 and extensions..."
    run_cmd "sudo apt-get install lsb-release ca-certificates apt-transport-https software-properties-common"
    run_cmd "sudo add-apt-repository ppa:ondrej/php"
    run_cmd "sudo apt-get update"
    run_cmd "sudo apt-get install php8.3 php8.3-mbstring php8.3-sqlite3"

    # Set PHP 8.3 as the default PHP version
    run_cmd "sudo update-alternatives --set php /usr/bin/php8.3"

    # Verify PHP installation
    echo "Verifying PHP installation..."
    php -v

    # Install curl
    echo "Installing curl..."
    run_cmd "sudo apt-get install curl"

    # Verify curl installation
    echo "Verifying curl installation..."
    curl --version

    # Install Composer
    echo "Installing Composer..."
    curl -sS https://getcomposer.org/installer | php
    run_cmd "sudo mv composer.phar /usr/local/bin/composer"

    # Verify Composer installation
    echo "Verifying Composer installation..."
    composer --version

    # Install jq
    echo "Installing jq..."
    run_cmd "sudo apt-get install jq"

    # Verify jq installation
    echo "Verifying jq installation..."
    jq --version

    echo "Installation completed successfully on Ubuntu."
}

# Function to install on macOS
install_on_macos() {
    echo "Detected macOS. Installing dependencies..."

    # Ensure Homebrew is installed
    if ! command -v brew &> /dev/null; then
        echo "Homebrew not found. Installing Homebrew..."
        /bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh)"
    fi

    # Update Homebrew
    echo "Updating Homebrew..."
    brew update

    # Install PHP 8.3
    echo "Installing PHP 8.3..."
    brew tap shivammathur/php
    brew install shivammathur/php/php@8.3

    # Set PHP 8.3 as the default PHP version
    echo 'export PATH="/usr/local/opt/php@8.3/bin:$PATH"' >> ~/.bash_profile
    source ~/.bash_profile

    # Verify PHP installation
    echo "Verifying PHP installation..."
    php -v

    # Install curl
    echo "Installing curl..."
    brew install curl

    # Verify curl installation
    echo "Verifying curl installation..."
    curl --version

    # Install Composer
    echo "Installing Composer..."
    brew install composer

    # Verify Composer installation
    echo "Verifying Composer installation..."
    composer --version

    # Install jq
    echo "Installing jq..."
    brew install jq

    # Verify jq installation
    echo "Verifying jq installation..."
    jq --version

    echo "Installation completed successfully on macOS."
}

# Determine the OS and call the appropriate function
if [[ "$OSTYPE" == "linux-gnu"* ]]; then
    . /etc/os-release
    install_on_linux
elif [[ "$OSTYPE" == "darwin"* ]]; then
    install_on_macos
else
    echo "Unsupported OS type: $OSTYPE"
    exit 1
fi
