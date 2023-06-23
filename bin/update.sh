#!/bin/bash

: '
This script updates simulation every 1 second
'

while true; do
  php bin/console update-simulation-state
  sleep 1
done