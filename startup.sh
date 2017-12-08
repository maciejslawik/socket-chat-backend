#!/bin/bash

DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"

nohup php $DIR/scripts/chat.php >/dev/null 2>&1 &
