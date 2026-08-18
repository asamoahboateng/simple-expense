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
    STATUS=$(curl -s -o /dev/null -w '%{http_code}' --max-time 2 "$URL" || true)
    echo "$(date '+%Y-%m-%dT%H:%M:%S') $STATUS" | tee -a "$LOGFILE"
    sleep 0.5
done

echo "Done. Non-200 lines:"
NON200=$(grep -v ' 200$' "$LOGFILE" || true)
if [ -z "$NON200" ]; then
    echo "  (none — zero downtime observed)"
else
    echo "$NON200"
    FIRST=$(echo "$NON200" | head -1 | awk '{print $1}')
    LAST=$(echo "$NON200" | tail -1 | awk '{print $1}')
    echo "Downtime window: $FIRST to $LAST (compare actual timestamps, not line count x 0.5s -- curl's own timeout can stretch each iteration up to ~2.5s during an outage)"
fi
