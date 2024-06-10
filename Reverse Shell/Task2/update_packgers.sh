#!/bin/bash

# Update package lists
sudo apt update

# Check for available updates (dry run to avoid unintended installations)
echo "Checking for updates..."
updates=$(sudo apt upgrade -s | grep "UPGRADEABLE")

# If updates are available, prompt user for confirmation before applying
if [[ -n "$updates" ]]; then
  echo "The following updates are available:"
  echo "$updates"
  read -r -p "Apply updates? [y/N] " response
  if [[ "$response" =~ ^([Yy]$) ]]; then
    echo "Applying updates..."
    sudo apt upgrade -y
    echo "Updates applied successfully."
  else
    echo "Updates skipped."
  fi
else
  echo "No updates available."
fi

# Clean up unused packages
echo "Cleaning up unused packages..."
sudo apt autoremove -y
echo "Script execution complete."
