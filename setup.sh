#!/usr/bin/env bash
set -euo pipefail

# ────────────────────────────────────────────────
# CONFIGURATION
# ────────────────────────────────────────────────

PLAYBOOK="playbook.yml"
CLIENTS_FILE="clients.txt"
VARS_FILE="group_vars/all.yml"
EXTRA_ARGS=()

# ────────────────────────────────────────────────
# HELP MENU & ARGUMENT PARSING
# ────────────────────────────────────────────────

usage() {
    echo "Usage: $0 [options]"
    echo ""
    echo "Options:"
    echo "  --nuke              (Cleanup everything (containers, networks, images, OpenVPN configuration))"
    echo "  --restart-c1        (Restart the first container without rebuilding image)"
    echo "  --restart-c2        (Restart the second container without rebuilding image)"
    echo "  --restart-all       (Restart all the Lab containers)"
    echo "  -h, --help          (Show this help menu)"
    echo ""
    exit 0
}

while [[ $# -gt 0 ]]; do
    case "$1" in
        --nuke) EXTRA_ARGS+=("--tags" "nuke"); shift ;;
        --restart-c1) EXTRA_ARGS+=("--tags" "reset_c1"); shift ;;
        --restart-c2) EXTRA_ARGS+=("--tags" "reset_c2"); shift ;;
        --restart-all) EXTRA_ARGS+=("--tags" "reset_all"); shift ;;
        -h|--help) usage ;;
        *) EXTRA_ARGS+=("$1"); shift ;;
    esac
done

# ────────────────────────────────────────────────
# PUBLIC IP CONFIGURATION (PROMPT)
# ────────────────────────────────────────────────

CURRENT_IP=$(grep "openvpn_host:" "$VARS_FILE" | cut -d'"' -f2 || echo "Not found")

echo "🌐 Public IP Configuration"
echo "──────────────────────────"
echo "Current IP in config: $CURRENT_IP"
read -p "Enter new IP (or press Enter to keep current): " NEW_IP

if [[ -n "$NEW_IP" ]]; then
    if [[ -f "$VARS_FILE" ]]; then
        sed -i "s/openvpn_host:.*/openvpn_host: \"$NEW_IP\"/" "$VARS_FILE"
        echo "✅ Public IP updated to: $NEW_IP"
    else
        echo "❌ Error: $VARS_FILE not found!"
        exit 1
    fi
fi

# ────────────────────────────────────────────────
# CLIENT MANAGEMENT
# ────────────────────────────────────────────────

echo ""
echo "👥 VPN Client Management"
echo "──────────────────────────"

[[ ! -f "$CLIENTS_FILE" ]] && touch "$CLIENTS_FILE"

echo "Current clients in $CLIENTS_FILE :"
cat -n "$CLIENTS_FILE" || true
echo ""

read -p "Do you want to edit clients list? (y/N): " EDIT
if [[ "$EDIT" =~ ^[yY]$ ]]; then
    ${EDITOR:-nano} "$CLIENTS_FILE"
fi

CLIENT_COUNT=$(grep -v '^#' "$CLIENTS_FILE" | grep -v '^\s*$' | wc -l || echo 0)

# ────────────────────────────────────────────────
# RUN ANSIBLE
# ────────────────────────────────────────────────

echo ""
if [[ " ${EXTRA_ARGS[*]} " =~ " nuke " ]]; then
    echo "🚨 NUKE MODE ACTIVATED: SYSTEM WILL BE WIPED 🚨"
else
    if [[ "$CLIENT_COUNT" -eq 0 ]]; then
        echo "Empty client list. Use --nuke to cleanup or add clients to continue."
        exit 0
    fi
    echo "🚀 Launching playbook for $CLIENT_COUNT client(s)..."
fi
echo ""

ansible-playbook \
    "$PLAYBOOK" \
    --extra-vars "clients_file=$CLIENTS_FILE" \
    "${EXTRA_ARGS[@]}"

echo ""
echo "✅ Done !"
echo ""