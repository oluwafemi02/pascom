#!/bin/bash

# setup-git-hooks.sh - Set up git hooks for automatic deployment

REPO_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
HOOKS_DIR="$REPO_DIR/.git/hooks"

# Colors
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m'

echo -e "${YELLOW}Setting up git hooks...${NC}"

# Create post-merge hook (runs after git pull)
cat > "$HOOKS_DIR/post-merge" << 'EOF'
#!/bin/bash
# Auto-deploy to Local after git pull

echo "Changes detected. Running local deployment..."
"$(git rev-parse --show-toplevel)/cursor" deploy-local
EOF

chmod +x "$HOOKS_DIR/post-merge"

# Create post-checkout hook (runs after git checkout)
cat > "$HOOKS_DIR/post-checkout" << 'EOF'
#!/bin/bash
# Auto-deploy to Local after branch switch

# Only run on branch checkout, not file checkout
if [ "$3" = "1" ]; then
    echo "Branch changed. Running local deployment..."
    "$(git rev-parse --show-toplevel)/cursor" deploy-local
fi
EOF

chmod +x "$HOOKS_DIR/post-checkout"

echo -e "${GREEN}✓ Git hooks installed${NC}"
echo "Automatic deployment will now run after:"
echo "  - git pull"
echo "  - git checkout (branch switch)"
echo ""
echo "To disable automatic deployment, delete files in .git/hooks/"