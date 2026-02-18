#!/bin/bash
# Race condition test: два параллельных запроса "Взять в работу"
# Один должен вернуть 302 (success), второй — 409 (conflict)
# Usage: bash race_test.sh [request_id]
# Пример: bash race_test.sh 3
# Нужна заявка в статусе "assigned" для master1. По умолчанию ID=3 из сидов.

set -e
BASE="${BASE_URL:-http://localhost:8000}"
REQ_ID="${1:-3}"
COOKIE_JAR=$(mktemp)
trap "rm -f $COOKIE_JAR /tmp/race_*.txt 2>/dev/null" EXIT

echo "=== Race condition test ==="
echo "URL: $BASE, Request ID: $REQ_ID"
echo ""

# 1. GET login page (get CSRF)
echo "Getting login page..."
PAGE=$(curl -s -c "$COOKIE_JAR" -b "$COOKIE_JAR" "$BASE/login")
CSRF=$(echo "$PAGE" | grep -oP 'name="_token"\s+value="\K[^"]+' || echo "$PAGE" | grep -oP 'content="csrf-token"\s+content="\K[^"]+')
[ -z "$CSRF" ] && CSRF=$(echo "$PAGE" | grep -oP '_token[^>]*value="\K[^"]+')

# 2. Login
echo "Logging in as master1@test.local..."
curl -s -c "$COOKIE_JAR" -b "$COOKIE_JAR" -L -o /dev/null \
  -X POST "$BASE/login" \
  -H "Content-Type: application/x-www-form-urlencoded" \
  -d "email=master1@test.local&password=password&_token=$CSRF"

# 3. GET master page (refresh CSRF for forms)
MASTER_PAGE=$(curl -s -c "$COOKIE_JAR" -b "$COOKIE_JAR" "$BASE/master")
CSRF=$(echo "$MASTER_PAGE" | grep -oP 'name="_token"\s+value="\K[^"]+' || echo "$MASTER_PAGE" | grep -oP 'value="\K[^"]+(?="[^>]*name="_token")')
[ -z "$CSRF" ] && CSRF=$(echo "$MASTER_PAGE" | grep -oP '_token[^>]*value="\K[^"]+')

# 4. Two parallel POST requests
echo "Sending 2 parallel POST requests to /master/$REQ_ID/start..."
(
  CODE=$(curl -s -w "%{http_code}" -o /tmp/race_r1.txt -b "$COOKIE_JAR" -c "$COOKIE_JAR" -L \
    -X POST "$BASE/master/$REQ_ID/start" \
    -H "Content-Type: application/x-www-form-urlencoded" \
    -d "_token=$CSRF")
  echo "Request 1: HTTP $CODE"
) &
(
  CODE=$(curl -s -w "%{http_code}" -o /tmp/race_r2.txt -b "$COOKIE_JAR" -c "$COOKIE_JAR" -L \
    -X POST "$BASE/master/$REQ_ID/start" \
    -H "Content-Type: application/x-www-form-urlencoded" \
    -d "_token=$CSRF")
  echo "Request 2: HTTP $CODE"
) &
wait

echo ""
echo "Expected: one 302 (success redirect), one 409 (conflict)"
