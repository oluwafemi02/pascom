<?php
/**
 * Template Name: Toolkit Sales Page
 * 
 * @package FrenchieCare
 */

get_header(); ?>

<style>
.sales-hero {
    background: linear-gradient(135deg, #2C5282 0%, #2D3748 100%);
    color: white;
    padding: 60px 0;
    text-align: center;
}

.sales-hero h1 {
    font-size: 2.5rem;
    margin-bottom: 1rem;
    color: white;
}

.price-box {
    background: #48BB78;
    color: white;
    padding: 20px 40px;
    display: inline-block;
    border-radius: 8px;
    margin: 20px 0;
    font-size: 1.5rem;
    font-weight: bold;
}

.price-strike {
    text-decoration: line-through;
    opacity: 0.7;
    margin-right: 10px;
}

.cta-button {
    background: #ED8936;
    color: white;
    padding: 20px 40px;
    font-size: 1.25rem;
    font-weight: bold;
    border: none;
    border-radius: 8px;
    text-decoration: none;
    display: inline-block;
    margin: 20px 0;
    transition: all 0.3s;
}

.cta-button:hover {
    background: #DD6B20;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
}

.benefit-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 30px;
    margin: 40px 0;
}

.benefit-card {
    background: #F7FAFC;
    padding: 30px;
    border-radius: 8px;
    text-align: center;
    border: 2px solid #E2E8F0;
}

.benefit-icon {
    font-size: 3rem;
    margin-bottom: 1rem;
}

.testimonial-box {
    background: #FEF5E7;
    border-left: 4px solid #ED8936;
    padding: 20px;
    margin: 20px 0;
    font-style: italic;
}

.testimonial-author {
    font-weight: bold;
    color: #2C5282;
    margin-top: 10px;
}

.guarantee-box {
    background: #48BB78;
    color: white;
    padding: 30px;
    text-align: center;
    border-radius: 8px;
    margin: 40px 0;
}

.faq-item {
    background: white;
    border: 1px solid #E2E8F0;
    border-radius: 8px;
    padding: 20px;
    margin: 10px 0;
}

.toolkit-contents {
    background: #EDF2F7;
    padding: 40px;
    border-radius: 8px;
    margin: 40px 0;
}

.contents-list {
    list-style: none;
    padding: 0;
}

.contents-list li {
    padding: 10px 0;
    padding-left: 30px;
    position: relative;
}

.contents-list li:before {
    content: "✓";
    position: absolute;
    left: 0;
    color: #48BB78;
    font-weight: bold;
    font-size: 1.2rem;
}
</style>

<div class="sales-hero">
    <div class="container">
        <h1>Stop Your Frenchie's Suffering Today</h1>
        <h2>The Complete Allergy Management Toolkit for French Bulldog Parents</h2>
        <p>Finally understand and manage your Frenchie's allergies with confidence</p>
        
        <div class="price-box">
            <span class="price-strike">€19</span>
            Launch Price: €9
        </div>
        
        <div>
            <a href="#buy-now" class="cta-button">Get Instant Access →</a>
        </div>
        
        <p style="opacity: 0.8;">⭐⭐⭐⭐⭐ Trusted by 5,000+ Frenchie Parents</p>
    </div>
</div>

<div class="site-content">
    <div class="container">
        
        <section class="problem-section">
            <h2 style="text-align: center; color: #2C5282;">Does This Sound Like Your Frenchie?</h2>
            
            <div class="benefit-grid">
                <div class="benefit-card">
                    <div class="benefit-icon">😔</div>
                    <h3>Constant Scratching</h3>
                    <p>Your Frenchie can't stop scratching, even waking up at night to scratch</p>
                </div>
                
                <div class="benefit-card">
                    <div class="benefit-icon">💸</div>
                    <h3>Expensive Vet Visits</h3>
                    <p>You've spent hundreds on tests and treatments with little improvement</p>
                </div>
                
                <div class="benefit-card">
                    <div class="benefit-icon">😰</div>
                    <h3>Feeling Helpless</h3>
                    <p>You feel guilty watching your best friend suffer without knowing how to help</p>
                </div>
            </div>
        </section>
        
        <section class="solution-section">
            <h2 style="text-align: center; color: #2C5282;">There's a Better Way</h2>
            
            <p style="text-align: center; font-size: 1.2rem;">After helping thousands of Frenchie parents manage their dogs' allergies, we've created the ultimate toolkit that gives you everything you need to:</p>
            
            <div class="benefit-grid">
                <div class="benefit-card">
                    <div class="benefit-icon">📊</div>
                    <h3>Track & Identify Triggers</h3>
                    <p>Pinpoint exactly what's causing your Frenchie's reactions</p>
                </div>
                
                <div class="benefit-card">
                    <div class="benefit-icon">📅</div>
                    <h3>Follow Proven Routines</h3>
                    <p>Daily care checklists that actually prevent flare-ups</p>
                </div>
                
                <div class="benefit-card">
                    <div class="benefit-icon">🚨</div>
                    <h3>Handle Emergencies</h3>
                    <p>Know exactly what to do when symptoms worsen</p>
                </div>
            </div>
        </section>
        
        <section class="toolkit-contents">
            <h2 style="text-align: center;">What's Inside Your Toolkit</h2>
            
            <ul class="contents-list">
                <li><strong>7-Week Elimination Diet Planner</strong> - Systematically identify food triggers (Value: €47)</li>
                <li><strong>Daily Care Checklist</strong> - Printable routines that prevent 80% of flare-ups (Value: €27)</li>
                <li><strong>Food Switch Calculator</strong> - Transition foods safely without triggering reactions (Value: €37)</li>
                <li><strong>Vet Communication Kit</strong> - Save money by asking the right questions (Value: €47)</li>
                <li><strong>Emergency Action Plan</strong> - Handle severe reactions with confidence (Value: €67)</li>
                <li><strong>Seasonal Allergy Calendar</strong> - EU/UK specific allergy forecasts (Value: €27)</li>
                <li><strong>Product Shopping Guide</strong> - Know exactly what to buy and where (Value: €37)</li>
                <li><strong>BONUS: Quick Reference Cards</strong> - Laminate and keep handy (Value: €17)</li>
            </ul>
            
            <p style="text-align: center; font-size: 1.2rem; font-weight: bold;">
                Total Value: €306<br>
                <span style="color: #48BB78;">Your Price Today: Just €9</span>
            </p>
        </section>
        
        <section class="testimonials-section">
            <h2 style="text-align: center; color: #2C5282;">Success Stories from Real Frenchie Parents</h2>
            
            <div class="testimonial-box">
                <p>"The elimination diet planner alone saved me €200 in allergy tests. Within 3 weeks, we identified that chicken was Max's trigger. He hasn't had a flare-up in 6 months!"</p>
                <p class="testimonial-author">- Sarah M., London</p>
            </div>
            
            <div class="testimonial-box">
                <p>"I was spending €150/month on various treatments. The daily checklist helped me prevent problems before they started. Now we only see the vet for regular checkups."</p>
                <p class="testimonial-author">- Marc D., Amsterdam</p>
            </div>
            
            <div class="testimonial-box">
                <p>"The emergency action plan gave me so much peace of mind. When Luna had a severe reaction, I knew exactly what to do. The vet said my quick action made all the difference."</p>
                <p class="testimonial-author">- Emma K., Berlin</p>
            </div>
        </section>
        
        <section class="guarantee-box">
            <h2>30-Day Money-Back Guarantee</h2>
            <p>Try the Frenchie Allergy Toolkit risk-free for 30 days. If you don't see improvement in your dog's condition, we'll refund every penny. No questions asked.</p>
        </section>
        
        <section id="buy-now" class="purchase-section" style="text-align: center; padding: 40px; background: #F7FAFC; border-radius: 8px;">
            <h2>Get Instant Access to Your Toolkit</h2>
            
            <div class="price-box">
                <span class="price-strike">€19</span>
                Today Only: €9
            </div>
            
            <p>✓ Instant download after purchase<br>
            ✓ All future updates included<br>
            ✓ 30-day money-back guarantee<br>
            ✓ Secure checkout via Lemon Squeezy</p>
            
            <a href="https://frenchieallergyhelp.lemonsqueezy.com/checkout/buy/abc123" class="cta-button">
                Get Your Toolkit Now →
            </a>
            
            <p style="opacity: 0.7;">🔒 Secure checkout • VAT included • Instant delivery</p>
        </section>
        
        <section class="faq-section">
            <h2 style="text-align: center; color: #2C5282;">Frequently Asked Questions</h2>
            
            <div class="faq-item">
                <h3>How do I receive the toolkit?</h3>
                <p>Immediately after purchase, you'll receive an email with download links for all materials. You can access them on any device and print the checklists and planners.</p>
            </div>
            
            <div class="faq-item">
                <h3>Is this suitable for puppies?</h3>
                <p>Yes! The toolkit includes specific guidance for puppies vs adult Frenchies. Starting early with allergy prevention is actually ideal.</p>
            </div>
            
            <div class="faq-item">
                <h3>What if I'm not tech-savvy?</h3>
                <p>The toolkit is designed to be simple. PDFs open on any device, and the Excel files work with free programs like Google Sheets. Plus, we provide video tutorials.</p>
            </div>
            
            <div class="faq-item">
                <h3>Do you offer support?</h3>
                <p>Yes! You'll get access to our private Facebook group where you can ask questions and connect with other Frenchie parents using the toolkit.</p>
            </div>
        </section>
        
        <section style="text-align: center; padding: 40px 0;">
            <h2>Your Frenchie Deserves Relief</h2>
            <p style="font-size: 1.2rem;">Every day you wait is another day of discomfort for your best friend.</p>
            
            <a href="#buy-now" class="cta-button">Get the Toolkit Now →</a>
            
            <p>Join 5,000+ Frenchie parents who've transformed their dogs' lives</p>
        </section>
        
    </div>
</div>

<?php get_footer(); ?>