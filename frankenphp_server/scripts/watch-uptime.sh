#!/usr/bin/env bash
# Probes a URL every 0.5s and logs timestamp + HTTP status.
# Usage: ./watch-uptime.sh https://expense.manage.ourladyofapostles.edu.gh/up [duration_seconds]
set -euo pipefail

URL="${1:?Usage: $0 <url> [duration_seconds]}"
DURATION="${2:-120}"
LOGFILE="uptime-watch-$(date +%Y%m%d-%H%M%S).log"

echo "Watching $URL for ${DURATION}s -> $LOGFILE"
END=$((SECONDS + DURATION))

while [ $SECONDS -lt $END ]; do
    STATUS=$(curl -s -o /dev/null -w '%{http_code}' --max-time 2 "$URL" || echo "000")
    echo "$(date '+%Y-%m-%dT%H:%M:%S.%3N') $STATUS" | tee -a "$LOGFILE"
    sleep 0.5
done

echo "Done. Non-200 lines:"
grep -v ' 200$' "$LOGFILE" || echo "  (none — zero downtime observed)"
