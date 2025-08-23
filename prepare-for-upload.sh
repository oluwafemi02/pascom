#!/bin/bash

# Prepare WordPress Files for Upload
# This script organizes all files for easy upload to your WordPress installation

echo "🐾 Preparing Frenchie Allergy Help Files for WordPress Upload"
echo "==========================================================="

# Create upload directory
UPLOAD_DIR="wordpress-upload-ready"
rm -rf $UPLOAD_DIR
mkdir -p $UPLOAD_DIR

# Copy theme
echo "📦 Preparing theme..."
if [ -d "frenchie-allergy-help/wp-content/themes/frenchie-care" ]; then
    mkdir -p $UPLOAD_DIR/themes
    cp -r frenchie-allergy-help/wp-content/themes/frenchie-care $UPLOAD_DIR/themes/
    echo "✓ Theme copied to $UPLOAD_DIR/themes/frenchie-care"
else
    echo "⚠ Theme directory not found"
fi

# Copy plugins
echo "📦 Preparing plugins..."
if [ -d "frenchie-allergy-help/wp-content/plugins" ]; then
    mkdir -p $UPLOAD_DIR/plugins
    cp -r frenchie-allergy-help/wp-content/plugins/* $UPLOAD_DIR/plugins/
    echo "✓ Plugins copied to $UPLOAD_DIR/plugins/"
else
    echo "⚠ Plugins directory not found"
fi

# Copy automation scripts
echo "📦 Preparing automation scripts..."
mkdir -p $UPLOAD_DIR/automation
cp wordpress-automation/setup-automation.php $UPLOAD_DIR/automation/
cp wordpress-automation/wp-cli-setup.sh $UPLOAD_DIR/automation/
chmod +x $UPLOAD_DIR/automation/wp-cli-setup.sh
echo "✓ Automation scripts copied"

# Copy content for import
echo "📦 Preparing content files..."
mkdir -p $UPLOAD_DIR/import-content
cp -r site-content $UPLOAD_DIR/import-content/
cp -r legal-pages $UPLOAD_DIR/import-content/
echo "✓ Content files copied"

# Create installation instructions
cat > $UPLOAD_DIR/INSTALLATION.txt << 'EOF'
FRENCHIE ALLERGY HELP - INSTALLATION INSTRUCTIONS
================================================

1. UPLOAD THEME:
   - Upload /themes/frenchie-care/ to your-site/wp-content/themes/
   - Activate in WordPress Admin → Appearance → Themes

2. UPLOAD PLUGINS:
   - Upload contents of /plugins/ to your-site/wp-content/plugins/
   - Activate in WordPress Admin → Plugins

3. RUN AUTOMATION (Choose one method):

   Method A - PHP Script:
   - Upload /automation/setup-automation.php to wp-content/
   - Upload /import-content/ folder to same directory as script
   - Visit: http://your-site.com/wp-content/setup-automation.php

   Method B - WP-CLI:
   - Upload /automation/wp-cli-setup.sh to WordPress root
   - Upload /import-content/ folder to WordPress root
   - Run: ./wp-cli-setup.sh

   Method C - Manual:
   - Follow the guide in COMPLETE-SETUP-GUIDE.md

4. POST-INSTALLATION:
   - Configure email settings (WP Mail SMTP)
   - Add your affiliate links
   - Install recommended plugins
   - Configure SEO settings

Need help? Check COMPLETE-SETUP-GUIDE.md for detailed instructions.
EOF

# Create a comprehensive zip file
echo ""
echo "📦 Creating zip file..."
cd $UPLOAD_DIR
zip -r ../frenchie-wordpress-upload.zip *
cd ..

echo ""
echo "✅ Preparation Complete!"
echo ""
echo "📁 Files organized in: $UPLOAD_DIR/"
echo "📦 Zip file created: frenchie-wordpress-upload.zip"
echo ""
echo "Next steps:"
echo "1. Upload frenchie-wordpress-upload.zip to your server"
echo "2. Extract the files"
echo "3. Follow the instructions in INSTALLATION.txt"
echo ""
echo "For detailed guidance, see COMPLETE-SETUP-GUIDE.md"