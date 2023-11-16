#!/bin/bash

# Run a real command and save its output to output.txt
ls /var/www/html > output.txt

# Run another real command and append its output to output.txt
df -h >> output.txt

# Run a third real command and save its output to another_output.txt
date > another_output.txt

