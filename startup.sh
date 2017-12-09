#!/bin/bash

DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"

nohup php $DIR/scripts/chat.php >/dev/null 2>&1 &
nohup php $DIR/scripts/file_logger.php >/dev/null 2>&1 &
