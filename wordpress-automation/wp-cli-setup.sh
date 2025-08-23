#!/bin/bash

# Frenchie Allergy Help - WP-CLI Setup Script
# This script automates WordPress setup using WP-CLI commands
# 
# Prerequisites:
# 1. WP-CLI installed (https://wp-cli.org/)
# 2. WordPress already installed
# 3. Run from WordPress root directory

echo "🐾 Frenchie Allergy Help - WP-CLI Setup Script"
echo "=============================================="

# Check if WP-CLI is installed
if ! command -v wp &> /dev/null; then
    echo "❌ WP-CLI is not installed. Please install it first:"
    echo "   curl -O https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar"
    echo "   chmod +x wp-cli.phar"
    echo "   sudo mv wp-cli.phar /usr/local/bin/wp"
    exit 1
fi

# Check if we're in a WordPress directory
if ! wp core is-installed 2>/dev/null; then
    echo "❌ This doesn't appear to be a WordPress installation directory."
    echo "   Please run this script from your WordPress root directory."
    exit 1
fi

echo "✅ WordPress installation detected"
echo ""

# Function to create categories
create_categories() {
    echo "📁 Creating Categories..."
    
    wp term create category "Food Allergies" --slug=food-allergies
    wp term create category "Environmental Allergies" --slug=environmental-allergies
    wp term create category "Skin Care" --slug=skin-care
    wp term create category "Product Reviews" --slug=product-reviews
    wp term create category "Treatment Guides" --slug=treatment-guides
}

# Function to create pages
create_pages() {
    echo ""
    echo "📄 Creating Pages..."
    
    # About Page
    if [ -f "../site-content/pages/about.html" ]; then
        CONTENT=$(cat ../site-content/pages/about.html | sed -n '/<body[^>]*>/,/<\/body>/p' | sed '1d;$d')
        wp post create --post_type=page --post_title="About" --post_content="$CONTENT" --post_status=publish --post_name=about
    fi
    
    # Start Here Page
    if [ -f "../site-content/pages/start-here.html" ]; then
        CONTENT=$(cat ../site-content/pages/start-here.html | sed -n '/<body[^>]*>/,/<\/body>/p' | sed '1d;$d')
        wp post create --post_type=page --post_title="Start Here" --post_content="$CONTENT" --post_status=publish --post_name=start-here
    fi
    
    # Medical Disclaimer
    if [ -f "../site-content/pages/medical-disclaimer.html" ]; then
        CONTENT=$(cat ../site-content/pages/medical-disclaimer.html | sed -n '/<body[^>]*>/,/<\/body>/p' | sed '1d;$d')
        wp post create --post_type=page --post_title="Medical Disclaimer" --post_content="$CONTENT" --post_status=publish --post_name=medical-disclaimer
    fi
    
    # Privacy Policy
    if [ -f "../legal-pages/privacy-policy.html" ]; then
        CONTENT=$(cat ../legal-pages/privacy-policy.html | sed -n '/<body[^>]*>/,/<\/body>/p' | sed '1d;$d')
        wp post create --post_type=page --post_title="Privacy Policy" --post_content="$CONTENT" --post_status=publish --post_name=privacy-policy
    fi
    
    # Affiliate Disclosure
    if [ -f "../legal-pages/affiliate-disclosure.html" ]; then
        CONTENT=$(cat ../legal-pages/affiliate-disclosure.html | sed -n '/<body[^>]*>/,/<\/body>/p' | sed '1d;$d')
        wp post create --post_type=page --post_title="Affiliate Disclosure" --post_content="$CONTENT" --post_status=publish --post_name=affiliate-disclosure
    fi
    
    # Contact Page
    wp post create --post_type=page --post_title="Contact" --post_status=publish --post_name=contact --post_content='
    <h2>Get in Touch</h2>
    <p>Have questions about your Frenchie's allergies? We're here to help!</p>
    [contact-form-7 title="Contact form 1"]
    <h3>Quick Response Times</h3>
    <p>We typically respond within 24-48 hours. For urgent allergy concerns, please consult your veterinarian immediately.</p>'
}

# Function to create posts
create_posts() {
    echo ""
    echo "📝 Creating Posts..."
    
    # Get category IDs
    FOOD_CAT=$(wp term list category --name="Food Allergies" --field=term_id)
    ENV_CAT=$(wp term list category --name="Environmental Allergies" --field=term_id)
    GUIDE_CAT=$(wp term list category --name="Treatment Guides" --field=term_id)
    
    # Food Allergies Guide
    if [ -f "../site-content/articles/food-allergies-guide.html" ]; then
        CONTENT=$(cat ../site-content/articles/food-allergies-guide.html | sed -n '/<body[^>]*>/,/<\/body>/p' | sed '1d;$d')
        wp post create --post_type=post --post_title="French Bulldog Food Allergies: Complete Guide" \
            --post_content="$CONTENT" --post_status=publish \
            --post_category=$FOOD_CAT,$GUIDE_CAT \
            --post_name=french-bulldog-food-allergies-guide
    fi
    
    # Seasonal Allergies Guide
    if [ -f "../site-content/articles/seasonal-allergies-guide.html" ]; then
        CONTENT=$(cat ../site-content/articles/seasonal-allergies-guide.html | sed -n '/<body[^>]*>/,/<\/body>/p' | sed '1d;$d')
        wp post create --post_type=post --post_title="French Bulldog Seasonal Allergies: Management Guide" \
            --post_content="$CONTENT" --post_status=publish \
            --post_category=$ENV_CAT,$GUIDE_CAT \
            --post_name=french-bulldog-seasonal-allergies-guide
    fi
}

# Function to setup menus
setup_menus() {
    echo ""
    echo "🍔 Setting Up Menus..."
    
    # Create Primary Menu
    wp menu create "Primary Menu"
    
    # Add items to Primary Menu
    wp menu item add-custom primary-menu "Home" "/" 
    START_HERE_ID=$(wp post list --post_type=page --name=start-here --field=ID)
    [ ! -z "$START_HERE_ID" ] && wp menu item add-post primary-menu $START_HERE_ID
    ABOUT_ID=$(wp post list --post_type=page --name=about --field=ID)
    [ ! -z "$ABOUT_ID" ] && wp menu item add-post primary-menu $ABOUT_ID
    CONTACT_ID=$(wp post list --post_type=page --name=contact --field=ID)
    [ ! -z "$CONTACT_ID" ] && wp menu item add-post primary-menu $CONTACT_ID
    
    # Assign to location
    wp menu location assign primary-menu primary
    
    # Create Footer Menu
    wp menu create "Footer Menu"
    
    # Add items to Footer Menu
    PRIVACY_ID=$(wp post list --post_type=page --name=privacy-policy --field=ID)
    [ ! -z "$PRIVACY_ID" ] && wp menu item add-post footer-menu $PRIVACY_ID
    AFFILIATE_ID=$(wp post list --post_type=page --name=affiliate-disclosure --field=ID)
    [ ! -z "$AFFILIATE_ID" ] && wp menu item add-post footer-menu $AFFILIATE_ID
    DISCLAIMER_ID=$(wp post list --post_type=page --name=medical-disclaimer --field=ID)
    [ ! -z "$DISCLAIMER_ID" ] && wp menu item add-post footer-menu $DISCLAIMER_ID
    [ ! -z "$CONTACT_ID" ] && wp menu item add-post footer-menu $CONTACT_ID
    
    # Assign to location
    wp menu location assign footer-menu footer
}

# Function to configure settings
configure_settings() {
    echo ""
    echo "⚙️ Configuring Settings..."
    
    # Set permalink structure
    wp rewrite structure '/%postname%/' --hard
    wp rewrite flush --hard
    
    # Disable comments
    wp option update default_comment_status closed
    
    # Set timezone
    wp option update timezone_string 'America/New_York'
    
    # Create Homepage
    HOMEPAGE_ID=$(wp post create --post_type=page --post_title="Home" --post_status=publish --porcelain --post_content='
    <div class="hero-section">
        <h1>Welcome to Frenchie Allergy Help</h1>
        <p class="lead">Your trusted resource for managing French Bulldog allergies naturally and effectively.</p>
        
        <div class="cta-buttons">
            <a href="/start-here/" class="button button-primary">Start Here</a>
            <a href="/blog/" class="button button-secondary">Browse Articles</a>
        </div>
    </div>
    
    <section class="features">
        <h2>How We Can Help Your Frenchie</h2>
        
        <div class="feature-grid">
            <div class="feature">
                <h3>🎯 Identify Allergies</h3>
                <p>Learn to recognize allergy symptoms and distinguish them from other conditions.</p>
            </div>
            
            <div class="feature">
                <h3>🥗 Nutrition Guides</h3>
                <p>Discover the best hypoallergenic foods and elimination diet strategies.</p>
            </div>
            
            <div class="feature">
                <h3>🏠 Home Remedies</h3>
                <p>Natural solutions to soothe your Frenchie\'s allergy symptoms safely.</p>
            </div>
            
            <div class="feature">
                <h3>📋 Action Plans</h3>
                <p>Step-by-step guides for managing both acute reactions and long-term care.</p>
            </div>
        </div>
    </section>')
    
    # Create Blog page
    BLOG_ID=$(wp post create --post_type=page --post_title="Blog" --post_status=publish --porcelain)
    
    # Set static homepage
    wp option update show_on_front page
    wp option update page_on_front $HOMEPAGE_ID
    wp option update page_for_posts $BLOG_ID
}

# Function to install recommended plugins
install_plugins() {
    echo ""
    echo "🔌 Installing Recommended Plugins..."
    
    # Install and activate plugins
    wp plugin install wordpress-seo --activate
    wp plugin install wordfence --activate
    wp plugin install updraftplus --activate
    wp plugin install contact-form-7 --activate
    wp plugin install cookie-notice --activate
    
    echo "Note: WP Rocket is a premium plugin and needs to be installed manually."
}

# Function to activate theme and custom plugins
activate_theme() {
    echo ""
    echo "🎨 Activating Theme and Custom Plugins..."
    
    # Check if theme exists
    if wp theme list --field=name | grep -q "frenchie-care"; then
        wp theme activate frenchie-care
        echo "✓ Activated Frenchie Care theme"
    else
        echo "⚠ Frenchie Care theme not found. Please upload it to wp-content/themes/"
    fi
    
    # Activate custom plugins
    if wp plugin list --field=name | grep -q "frenchie-email-automation"; then
        wp plugin activate frenchie-email-automation
        echo "✓ Activated Frenchie Email Automation"
    fi
    
    if wp plugin list --field=name | grep -q "frenchie-monetization"; then
        wp plugin activate frenchie-monetization
        echo "✓ Activated Frenchie Monetization"
    fi
}

# Main execution
echo "Starting setup process..."
echo ""

# Run all setup functions
create_categories
create_pages
create_posts
setup_menus
configure_settings
activate_theme
install_plugins

echo ""
echo "✅ Setup Complete!"
echo ""
echo "Next Steps:"
echo "1. Upload the Frenchie Care theme to wp-content/themes/ if not already done"
echo "2. Upload custom plugins to wp-content/plugins/ if not already done"
echo "3. Configure SMTP settings for email delivery"
echo "4. Add your affiliate links in Frenchie Monetization settings"
echo "5. Configure Yoast SEO settings"
echo "6. Set up Google Analytics"
echo "7. Create additional content as needed"
echo ""
echo "Access your site at: $(wp option get siteurl)"