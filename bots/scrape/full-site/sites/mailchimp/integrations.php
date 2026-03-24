<?php $current_page = 'integrations'; ?>
<?php include '_header.php'; ?>

<!-- ======================================================================
     INTEGRATIONS — HERO
     ====================================================================== -->
<section class="mc-hero mc-hero--cream" style="padding: var(--space-4xl) 0; text-align: center;">
  <div class="mc-container mc-container--narrow">
    <p class="mc-hero__eyebrow" style="font-family: var(--font-sans); font-size: 13px; font-weight: 600; letter-spacing: 1.5px; text-transform: uppercase; color: var(--mc-teal); margin-bottom: var(--space-md);">INTEGRATIONS DIRECTORY</p>
    <h1 class="mc-hero__title" style="font-family: var(--font-serif); font-size: clamp(32px, 5vw, 56px); font-weight: 300; line-height: 1.15; margin-bottom: var(--space-lg);">Connect your favorite tools to Mailchimp</h1>
    <p class="mc-hero__subtitle" style="font-size: 18px; color: var(--mc-gray); max-width: 640px; margin: 0 auto var(--space-2xl); line-height: 1.6;">
      Browse 300+ integrations to connect your e-commerce, CRM, social media, analytics, and more&mdash;all in one place.
    </p>
    <div class="mc-help-search" style="max-width: 560px; margin: 0 auto; position: relative;">
      <svg class="mc-help-search__icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="position: absolute; left: 16px; top: 50%; transform: translateY(-50%); color: var(--mc-gray);"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
      <input type="text" class="mc-input mc-input--lg mc-help-search__input" placeholder="Search integrations..." style="width: 100%; padding: 14px 20px 14px 48px; border: 2px solid var(--mc-border); border-radius: var(--radius-pill); font-size: 16px; transition: border-color var(--transition-base);">
    </div>
  </div>
</section>

<!-- ======================================================================
     INTEGRATIONS — CATEGORY PILLS
     ====================================================================== -->
<section style="background: var(--mc-white); border-bottom: 1px solid var(--mc-border); padding: var(--space-lg) 0; position: sticky; top: 0; z-index: var(--z-sticky);">
  <div class="mc-container">
    <div style="display: flex; gap: var(--space-sm); overflow-x: auto; padding-bottom: var(--space-xs); -webkit-overflow-scrolling: touch; scrollbar-width: none;">
      <a href="#" style="display: inline-block; padding: 8px 20px; border-radius: var(--radius-pill); font-family: var(--font-sans); font-size: 14px; font-weight: 600; white-space: nowrap; text-decoration: none; background: var(--mc-black); color: var(--mc-white); transition: all var(--transition-fast);">All</a>
      <a href="#ecommerce" style="display: inline-block; padding: 8px 20px; border-radius: var(--radius-pill); font-family: var(--font-sans); font-size: 14px; font-weight: 500; white-space: nowrap; text-decoration: none; background: var(--mc-light-gray); color: var(--mc-black); transition: all var(--transition-fast);">E-Commerce</a>
      <a href="#crm" style="display: inline-block; padding: 8px 20px; border-radius: var(--radius-pill); font-family: var(--font-sans); font-size: 14px; font-weight: 500; white-space: nowrap; text-decoration: none; background: var(--mc-light-gray); color: var(--mc-black); transition: all var(--transition-fast);">CRM</a>
      <a href="#social" style="display: inline-block; padding: 8px 20px; border-radius: var(--radius-pill); font-family: var(--font-sans); font-size: 14px; font-weight: 500; white-space: nowrap; text-decoration: none; background: var(--mc-light-gray); color: var(--mc-black); transition: all var(--transition-fast);">Social Media</a>
      <a href="#analytics" style="display: inline-block; padding: 8px 20px; border-radius: var(--radius-pill); font-family: var(--font-sans); font-size: 14px; font-weight: 500; white-space: nowrap; text-decoration: none; background: var(--mc-light-gray); color: var(--mc-black); transition: all var(--transition-fast);">Analytics</a>
      <a href="#ecommerce" style="display: inline-block; padding: 8px 20px; border-radius: var(--radius-pill); font-family: var(--font-sans); font-size: 14px; font-weight: 500; white-space: nowrap; text-decoration: none; background: var(--mc-light-gray); color: var(--mc-black); transition: all var(--transition-fast);">Payments</a>
      <a href="#" style="display: inline-block; padding: 8px 20px; border-radius: var(--radius-pill); font-family: var(--font-sans); font-size: 14px; font-weight: 500; white-space: nowrap; text-decoration: none; background: var(--mc-light-gray); color: var(--mc-black); transition: all var(--transition-fast);">Advertising</a>
      <a href="#developer" style="display: inline-block; padding: 8px 20px; border-radius: var(--radius-pill); font-family: var(--font-sans); font-size: 14px; font-weight: 500; white-space: nowrap; text-decoration: none; background: var(--mc-light-gray); color: var(--mc-black); transition: all var(--transition-fast);">Developer Tools</a>
      <a href="#" style="display: inline-block; padding: 8px 20px; border-radius: var(--radius-pill); font-family: var(--font-sans); font-size: 14px; font-weight: 500; white-space: nowrap; text-decoration: none; background: var(--mc-light-gray); color: var(--mc-black); transition: all var(--transition-fast);">Productivity</a>
    </div>
  </div>
</section>

<!-- ======================================================================
     INTEGRATIONS — FEATURED (3 large cards)
     ====================================================================== -->
<section class="mc-section">
  <div class="mc-container">
    <h2 style="font-family: var(--font-serif); font-size: clamp(24px, 3vw, 36px); font-weight: 300; margin-bottom: var(--space-2xl);">Featured Integrations</h2>
    <div class="mc-grid mc-grid--3 mc-grid--gap-lg">

      <!-- Shopify -->
      <a href="integrations.php" class="mc-card mc-card--bordered" style="text-decoration: none; color: inherit;">
        <div class="mc-card__image mc-card__image--fixed" style="height: 200px; overflow: hidden;">
          <img src="images/integrations/shopify-card.jpg" alt="Shopify integration" style="width: 100%; height: 100%; object-fit: cover;">
        </div>
        <div class="mc-card__body" style="padding: var(--space-lg);">
          <div style="display: flex; align-items: center; gap: var(--space-sm); margin-bottom: var(--space-md);">
            <img src="images/integrations/shopify.png" alt="Shopify" style="width: 32px; height: 32px; border-radius: var(--radius-sm);">
            <span style="font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; color: var(--mc-teal); background: rgba(0,124,137,0.08); padding: 2px 8px; border-radius: var(--radius-pill);">Popular</span>
          </div>
          <h4 class="mc-card__title" style="font-family: var(--font-serif); font-size: 22px; font-weight: 400; margin-bottom: var(--space-sm);">Shopify</h4>
          <p class="mc-card__desc" style="color: var(--mc-gray); line-height: 1.6; margin-bottom: var(--space-md);">Sync your Shopify store with Mailchimp to create targeted campaigns, automate product recommendations, recover abandoned carts, and track purchase data&mdash;all from one dashboard.</p>
          <span class="mc-card__link" style="color: var(--mc-teal); font-weight: 500;">Connect Shopify &rarr;</span>
        </div>
      </a>

      <!-- WooCommerce -->
      <a href="integrations.php" class="mc-card mc-card--bordered" style="text-decoration: none; color: inherit;">
        <div class="mc-card__image mc-card__image--fixed" style="height: 200px; overflow: hidden;">
          <img src="images/integrations/woo-card.png" alt="WooCommerce integration" style="width: 100%; height: 100%; object-fit: cover;">
        </div>
        <div class="mc-card__body" style="padding: var(--space-lg);">
          <div style="display: flex; align-items: center; gap: var(--space-sm); margin-bottom: var(--space-md);">
            <img src="images/integrations/woocommerce.png" alt="WooCommerce" style="width: 32px; height: 32px; border-radius: var(--radius-sm);">
            <span style="font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; color: var(--mc-teal); background: rgba(0,124,137,0.08); padding: 2px 8px; border-radius: var(--radius-pill);">Popular</span>
          </div>
          <h4 class="mc-card__title" style="font-family: var(--font-serif); font-size: 22px; font-weight: 400; margin-bottom: var(--space-sm);">WooCommerce</h4>
          <p class="mc-card__desc" style="color: var(--mc-gray); line-height: 1.6; margin-bottom: var(--space-md);">Connect your WooCommerce store to sync customers, products, and purchase data. Build automated campaigns that drive repeat purchases and grow your revenue on autopilot.</p>
          <span class="mc-card__link" style="color: var(--mc-teal); font-weight: 500;">Connect WooCommerce &rarr;</span>
        </div>
      </a>

      <!-- QuickBooks -->
      <a href="integrations.php" class="mc-card mc-card--bordered" style="text-decoration: none; color: inherit;">
        <div class="mc-card__image mc-card__image--fixed" style="height: 200px; overflow: hidden;">
          <img src="images/integrations/quickbooks-card.jpg" alt="QuickBooks integration" style="width: 100%; height: 100%; object-fit: cover;">
        </div>
        <div class="mc-card__body" style="padding: var(--space-lg);">
          <div style="display: flex; align-items: center; gap: var(--space-sm); margin-bottom: var(--space-md);">
            <img src="images/integrations/quickbooks.png" alt="QuickBooks" style="width: 32px; height: 32px; border-radius: var(--radius-sm);">
            <span style="font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; color: var(--mc-purple); background: rgba(107,63,160,0.08); padding: 2px 8px; border-radius: var(--radius-pill);">Intuit</span>
          </div>
          <h4 class="mc-card__title" style="font-family: var(--font-serif); font-size: 22px; font-weight: 400; margin-bottom: var(--space-sm);">QuickBooks</h4>
          <p class="mc-card__desc" style="color: var(--mc-gray); line-height: 1.6; margin-bottom: var(--space-md);">Sync financial data from QuickBooks to power smarter marketing. Segment customers by purchase history, spending patterns, and invoice status to send the right message at the right time.</p>
          <span class="mc-card__link" style="color: var(--mc-teal); font-weight: 500;">Connect QuickBooks &rarr;</span>
        </div>
      </a>

    </div>
  </div>
</section>

<!-- ======================================================================
     INTEGRATIONS — E-COMMERCE (4-col grid)
     ====================================================================== -->
<section class="mc-section mc-section--light" id="ecommerce">
  <div class="mc-container">
    <h2 style="font-family: var(--font-serif); font-size: clamp(24px, 3vw, 36px); font-weight: 300; margin-bottom: var(--space-sm);">E-Commerce</h2>
    <p style="color: var(--mc-gray); font-size: 17px; line-height: 1.6; margin-bottom: var(--space-2xl); max-width: 600px;">Sync your online store to automate product emails, recover abandoned carts, and track revenue from every campaign.</p>
    <div class="mc-grid mc-grid--4 mc-grid--gap-md">

      <!-- Shopify -->
      <a href="integrations.php" class="mc-card mc-card--bordered" style="text-decoration: none; color: inherit;">
        <div class="mc-card__body" style="padding: var(--space-lg);">
          <div style="display: flex; align-items: center; gap: var(--space-md); margin-bottom: var(--space-md);">
            <img src="images/integrations/shopify.png" alt="Shopify" style="width: 40px; height: 40px; border-radius: var(--radius-sm);">
            <h4 style="font-family: var(--font-sans); font-size: 16px; font-weight: 600;">Shopify</h4>
          </div>
          <p style="color: var(--mc-gray); font-size: 14px; line-height: 1.5; margin-bottom: var(--space-md);">Sync products, customers, and orders. Automate abandoned cart emails and product recommendations.</p>
          <span style="color: var(--mc-teal); font-size: 14px; font-weight: 500;">Connect &rarr;</span>
        </div>
      </a>

      <!-- WooCommerce -->
      <a href="integrations.php" class="mc-card mc-card--bordered" style="text-decoration: none; color: inherit;">
        <div class="mc-card__body" style="padding: var(--space-lg);">
          <div style="display: flex; align-items: center; gap: var(--space-md); margin-bottom: var(--space-md);">
            <img src="images/integrations/woocommerce.png" alt="WooCommerce" style="width: 40px; height: 40px; border-radius: var(--radius-sm);">
            <h4 style="font-family: var(--font-sans); font-size: 16px; font-weight: 600;">WooCommerce</h4>
          </div>
          <p style="color: var(--mc-gray); font-size: 14px; line-height: 1.5; margin-bottom: var(--space-md);">Connect your WordPress store. Sync customer data, track purchases, and send targeted product emails.</p>
          <span style="color: var(--mc-teal); font-size: 14px; font-weight: 500;">Connect &rarr;</span>
        </div>
      </a>

      <!-- Square -->
      <a href="integrations.php" class="mc-card mc-card--bordered" style="text-decoration: none; color: inherit;">
        <div class="mc-card__body" style="padding: var(--space-lg);">
          <div style="display: flex; align-items: center; gap: var(--space-md); margin-bottom: var(--space-md);">
            <img src="images/integrations/square.png" alt="Square" style="width: 40px; height: 40px; border-radius: var(--radius-sm);">
            <h4 style="font-family: var(--font-sans); font-size: 16px; font-weight: 600;">Square</h4>
          </div>
          <p style="color: var(--mc-gray); font-size: 14px; line-height: 1.5; margin-bottom: var(--space-md);">Sync in-store and online sales data. Build audiences based on purchase behavior and transaction history.</p>
          <span style="color: var(--mc-teal); font-size: 14px; font-weight: 500;">Connect &rarr;</span>
        </div>
      </a>

      <!-- Big Cartel -->
      <a href="integrations.php" class="mc-card mc-card--bordered" style="text-decoration: none; color: inherit;">
        <div class="mc-card__body" style="padding: var(--space-lg);">
          <div style="display: flex; align-items: center; gap: var(--space-md); margin-bottom: var(--space-md);">
            <img src="images/integrations/bigcartel.png" alt="Big Cartel" style="width: 40px; height: 40px; border-radius: var(--radius-sm);">
            <h4 style="font-family: var(--font-sans); font-size: 16px; font-weight: 600;">Big Cartel</h4>
          </div>
          <p style="color: var(--mc-gray); font-size: 14px; line-height: 1.5; margin-bottom: var(--space-md);">Connect your Big Cartel shop to import products and customers. Trigger emails based on purchases and browsing.</p>
          <span style="color: var(--mc-teal); font-size: 14px; font-weight: 500;">Connect &rarr;</span>
        </div>
      </a>

      <!-- Magento -->
      <a href="integrations.php" class="mc-card mc-card--bordered" style="text-decoration: none; color: inherit;">
        <div class="mc-card__body" style="padding: var(--space-lg);">
          <div style="display: flex; align-items: center; gap: var(--space-md); margin-bottom: var(--space-md);">
            <div style="width: 40px; height: 40px; border-radius: var(--radius-sm); background: #F26322; display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 18px; font-family: var(--font-sans);">M</div>
            <h4 style="font-family: var(--font-sans); font-size: 16px; font-weight: 600;">Magento</h4>
          </div>
          <p style="color: var(--mc-gray); font-size: 14px; line-height: 1.5; margin-bottom: var(--space-md);">Integrate Adobe Commerce (Magento) to sync catalog, customer, and order data for personalized marketing.</p>
          <span style="color: var(--mc-teal); font-size: 14px; font-weight: 500;">Connect &rarr;</span>
        </div>
      </a>

      <!-- PrestaShop -->
      <a href="integrations.php" class="mc-card mc-card--bordered" style="text-decoration: none; color: inherit;">
        <div class="mc-card__body" style="padding: var(--space-lg);">
          <div style="display: flex; align-items: center; gap: var(--space-md); margin-bottom: var(--space-md);">
            <div style="width: 40px; height: 40px; border-radius: var(--radius-sm); background: #DF0067; display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 18px; font-family: var(--font-sans);">P</div>
            <h4 style="font-family: var(--font-sans); font-size: 16px; font-weight: 600;">PrestaShop</h4>
          </div>
          <p style="color: var(--mc-gray); font-size: 14px; line-height: 1.5; margin-bottom: var(--space-md);">Sync your PrestaShop store to automate email marketing, recover abandoned carts, and boost conversions.</p>
          <span style="color: var(--mc-teal); font-size: 14px; font-weight: 500;">Connect &rarr;</span>
        </div>
      </a>

      <!-- BigCommerce -->
      <a href="integrations.php" class="mc-card mc-card--bordered" style="text-decoration: none; color: inherit;">
        <div class="mc-card__body" style="padding: var(--space-lg);">
          <div style="display: flex; align-items: center; gap: var(--space-md); margin-bottom: var(--space-md);">
            <div style="width: 40px; height: 40px; border-radius: var(--radius-sm); background: #121118; display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 18px; font-family: var(--font-sans);">B</div>
            <h4 style="font-family: var(--font-sans); font-size: 16px; font-weight: 600;">BigCommerce</h4>
          </div>
          <p style="color: var(--mc-gray); font-size: 14px; line-height: 1.5; margin-bottom: var(--space-md);">Connect BigCommerce to sync customer data and purchase history. Automate post-purchase and re-engagement flows.</p>
          <span style="color: var(--mc-teal); font-size: 14px; font-weight: 500;">Connect &rarr;</span>
        </div>
      </a>

      <!-- Stripe -->
      <a href="integrations.php" class="mc-card mc-card--bordered" style="text-decoration: none; color: inherit;">
        <div class="mc-card__body" style="padding: var(--space-lg);">
          <div style="display: flex; align-items: center; gap: var(--space-md); margin-bottom: var(--space-md);">
            <img src="images/integrations/stripe.png" alt="Stripe" style="width: 40px; height: 40px; border-radius: var(--radius-sm);">
            <h4 style="font-family: var(--font-sans); font-size: 16px; font-weight: 600;">Stripe</h4>
          </div>
          <p style="color: var(--mc-gray); font-size: 14px; line-height: 1.5; margin-bottom: var(--space-md);">Sync Stripe payment data to segment customers by spending, subscription status, and payment frequency.</p>
          <span style="color: var(--mc-teal); font-size: 14px; font-weight: 500;">Connect &rarr;</span>
        </div>
      </a>

    </div>
  </div>
</section>

<!-- ======================================================================
     INTEGRATIONS — CRM & SALES (4-col grid)
     ====================================================================== -->
<section class="mc-section" id="crm">
  <div class="mc-container">
    <h2 style="font-family: var(--font-serif); font-size: clamp(24px, 3vw, 36px); font-weight: 300; margin-bottom: var(--space-sm);">CRM &amp; Sales</h2>
    <p style="color: var(--mc-gray); font-size: 17px; line-height: 1.6; margin-bottom: var(--space-2xl); max-width: 600px;">Keep your contacts in sync across your sales tools and CRM. Trigger campaigns based on deal stages and customer lifecycle.</p>
    <div class="mc-grid mc-grid--4 mc-grid--gap-md">

      <!-- Salesforce -->
      <a href="integrations.php" class="mc-card mc-card--bordered" style="text-decoration: none; color: inherit;">
        <div class="mc-card__body" style="padding: var(--space-lg);">
          <div style="display: flex; align-items: center; gap: var(--space-md); margin-bottom: var(--space-md);">
            <img src="images/integrations/salesforce.png" alt="Salesforce" style="width: 40px; height: 40px; border-radius: var(--radius-sm);">
            <h4 style="font-family: var(--font-sans); font-size: 16px; font-weight: 600;">Salesforce</h4>
          </div>
          <p style="color: var(--mc-gray); font-size: 14px; line-height: 1.5; margin-bottom: var(--space-md);">Bi-directional sync between Salesforce leads, contacts, and Mailchimp audiences. Trigger campaigns from deal stages and lead scores.</p>
          <span style="color: var(--mc-teal); font-size: 14px; font-weight: 500;">Connect &rarr;</span>
        </div>
      </a>

      <!-- HubSpot -->
      <a href="integrations.php" class="mc-card mc-card--bordered" style="text-decoration: none; color: inherit;">
        <div class="mc-card__body" style="padding: var(--space-lg);">
          <div style="display: flex; align-items: center; gap: var(--space-md); margin-bottom: var(--space-md);">
            <img src="images/integrations/hubspot.png" alt="HubSpot" style="width: 40px; height: 40px; border-radius: var(--radius-sm);">
            <h4 style="font-family: var(--font-sans); font-size: 16px; font-weight: 600;">HubSpot</h4>
          </div>
          <p style="color: var(--mc-gray); font-size: 14px; line-height: 1.5; margin-bottom: var(--space-md);">Sync HubSpot contacts, companies, and deals with Mailchimp. Use lifecycle stage data to personalize campaigns automatically.</p>
          <span style="color: var(--mc-teal); font-size: 14px; font-weight: 500;">Connect &rarr;</span>
        </div>
      </a>

      <!-- Pipedrive -->
      <a href="integrations.php" class="mc-card mc-card--bordered" style="text-decoration: none; color: inherit;">
        <div class="mc-card__body" style="padding: var(--space-lg);">
          <div style="display: flex; align-items: center; gap: var(--space-md); margin-bottom: var(--space-md);">
            <div style="width: 40px; height: 40px; border-radius: var(--radius-sm); background: #017737; display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 18px; font-family: var(--font-sans);">P</div>
            <h4 style="font-family: var(--font-sans); font-size: 16px; font-weight: 600;">Pipedrive</h4>
          </div>
          <p style="color: var(--mc-gray); font-size: 14px; line-height: 1.5; margin-bottom: var(--space-md);">Connect your Pipedrive pipeline to Mailchimp. Sync deals, contacts, and activities to send timely follow-ups and nurture sequences.</p>
          <span style="color: var(--mc-teal); font-size: 14px; font-weight: 500;">Connect &rarr;</span>
        </div>
      </a>

      <!-- Zoho CRM -->
      <a href="integrations.php" class="mc-card mc-card--bordered" style="text-decoration: none; color: inherit;">
        <div class="mc-card__body" style="padding: var(--space-lg);">
          <div style="display: flex; align-items: center; gap: var(--space-md); margin-bottom: var(--space-md);">
            <div style="width: 40px; height: 40px; border-radius: var(--radius-sm); background: #E42527; display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 18px; font-family: var(--font-sans);">Z</div>
            <h4 style="font-family: var(--font-sans); font-size: 16px; font-weight: 600;">Zoho CRM</h4>
          </div>
          <p style="color: var(--mc-gray); font-size: 14px; line-height: 1.5; margin-bottom: var(--space-md);">Sync Zoho CRM leads and contacts with Mailchimp audiences. Automate email campaigns based on CRM deal stages and custom fields.</p>
          <span style="color: var(--mc-teal); font-size: 14px; font-weight: 500;">Connect &rarr;</span>
        </div>
      </a>

      <!-- Freshworks -->
      <a href="integrations.php" class="mc-card mc-card--bordered" style="text-decoration: none; color: inherit;">
        <div class="mc-card__body" style="padding: var(--space-lg);">
          <div style="display: flex; align-items: center; gap: var(--space-md); margin-bottom: var(--space-md);">
            <div style="width: 40px; height: 40px; border-radius: var(--radius-sm); background: #FF5C35; display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 18px; font-family: var(--font-sans);">F</div>
            <h4 style="font-family: var(--font-sans); font-size: 16px; font-weight: 600;">Freshworks</h4>
          </div>
          <p style="color: var(--mc-gray); font-size: 14px; line-height: 1.5; margin-bottom: var(--space-md);">Integrate Freshworks CRM to keep contacts and deal data in sync. Enrich your email targeting with sales pipeline insights.</p>
          <span style="color: var(--mc-teal); font-size: 14px; font-weight: 500;">Connect &rarr;</span>
        </div>
      </a>

      <!-- Copper -->
      <a href="integrations.php" class="mc-card mc-card--bordered" style="text-decoration: none; color: inherit;">
        <div class="mc-card__body" style="padding: var(--space-lg);">
          <div style="display: flex; align-items: center; gap: var(--space-md); margin-bottom: var(--space-md);">
            <div style="width: 40px; height: 40px; border-radius: var(--radius-sm); background: #F7A44C; display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 18px; font-family: var(--font-sans);">C</div>
            <h4 style="font-family: var(--font-sans); font-size: 16px; font-weight: 600;">Copper</h4>
          </div>
          <p style="color: var(--mc-gray); font-size: 14px; line-height: 1.5; margin-bottom: var(--space-md);">Sync your Google Workspace CRM with Mailchimp. Automatically add new Copper contacts to audiences and trigger welcome series.</p>
          <span style="color: var(--mc-teal); font-size: 14px; font-weight: 500;">Connect &rarr;</span>
        </div>
      </a>

    </div>
  </div>
</section>

<!-- ======================================================================
     INTEGRATIONS — SOCIAL MEDIA (4-col grid)
     ====================================================================== -->
<section class="mc-section mc-section--light" id="social">
  <div class="mc-container">
    <h2 style="font-family: var(--font-serif); font-size: clamp(24px, 3vw, 36px); font-weight: 300; margin-bottom: var(--space-sm);">Social Media</h2>
    <p style="color: var(--mc-gray); font-size: 17px; line-height: 1.6; margin-bottom: var(--space-2xl); max-width: 600px;">Extend your reach by connecting your social media accounts. Share campaigns, retarget audiences, and grow your following.</p>
    <div class="mc-grid mc-grid--4 mc-grid--gap-md">

      <!-- Facebook -->
      <a href="integrations.php" class="mc-card mc-card--bordered" style="text-decoration: none; color: inherit;">
        <div class="mc-card__body" style="padding: var(--space-lg);">
          <div style="display: flex; align-items: center; gap: var(--space-md); margin-bottom: var(--space-md);">
            <img src="images/integrations/facebook.png" alt="Facebook" style="width: 40px; height: 40px; border-radius: var(--radius-sm);">
            <h4 style="font-family: var(--font-sans); font-size: 16px; font-weight: 600;">Facebook</h4>
          </div>
          <p style="color: var(--mc-gray); font-size: 14px; line-height: 1.5; margin-bottom: var(--space-md);">Create Facebook ad campaigns directly from Mailchimp. Retarget website visitors, find lookalike audiences, and track ad performance alongside email metrics.</p>
          <span style="color: var(--mc-teal); font-size: 14px; font-weight: 500;">Connect &rarr;</span>
        </div>
      </a>

      <!-- Instagram -->
      <a href="integrations.php" class="mc-card mc-card--bordered" style="text-decoration: none; color: inherit;">
        <div class="mc-card__body" style="padding: var(--space-lg);">
          <div style="display: flex; align-items: center; gap: var(--space-md); margin-bottom: var(--space-md);">
            <img src="images/integrations/instagram.png" alt="Instagram" style="width: 40px; height: 40px; border-radius: var(--radius-sm);">
            <h4 style="font-family: var(--font-sans); font-size: 16px; font-weight: 600;">Instagram</h4>
          </div>
          <p style="color: var(--mc-gray); font-size: 14px; line-height: 1.5; margin-bottom: var(--space-md);">Publish organic posts and run Instagram ad campaigns from Mailchimp. Track engagement and grow your audience with shoppable content.</p>
          <span style="color: var(--mc-teal); font-size: 14px; font-weight: 500;">Connect &rarr;</span>
        </div>
      </a>

      <!-- LinkedIn -->
      <a href="integrations.php" class="mc-card mc-card--bordered" style="text-decoration: none; color: inherit;">
        <div class="mc-card__body" style="padding: var(--space-lg);">
          <div style="display: flex; align-items: center; gap: var(--space-md); margin-bottom: var(--space-md);">
            <img src="images/integrations/linkedin.png" alt="LinkedIn" style="width: 40px; height: 40px; border-radius: var(--radius-sm);">
            <h4 style="font-family: var(--font-sans); font-size: 16px; font-weight: 600;">LinkedIn</h4>
          </div>
          <p style="color: var(--mc-gray); font-size: 14px; line-height: 1.5; margin-bottom: var(--space-md);">Target professionals with LinkedIn ad campaigns. Sync lead gen form submissions directly to your Mailchimp audiences for B2B nurturing.</p>
          <span style="color: var(--mc-teal); font-size: 14px; font-weight: 500;">Connect &rarr;</span>
        </div>
      </a>

      <!-- Twitter/X -->
      <a href="integrations.php" class="mc-card mc-card--bordered" style="text-decoration: none; color: inherit;">
        <div class="mc-card__body" style="padding: var(--space-lg);">
          <div style="display: flex; align-items: center; gap: var(--space-md); margin-bottom: var(--space-md);">
            <div style="width: 40px; height: 40px; border-radius: var(--radius-sm); background: #000000; display: flex; align-items: center; justify-content: center;">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="white"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
            </div>
            <h4 style="font-family: var(--font-sans); font-size: 16px; font-weight: 600;">X (Twitter)</h4>
          </div>
          <p style="color: var(--mc-gray); font-size: 14px; line-height: 1.5; margin-bottom: var(--space-md);">Share your email campaigns on X, track social engagement, and build custom audiences based on follower data and interactions.</p>
          <span style="color: var(--mc-teal); font-size: 14px; font-weight: 500;">Connect &rarr;</span>
        </div>
      </a>

      <!-- Pinterest -->
      <a href="integrations.php" class="mc-card mc-card--bordered" style="text-decoration: none; color: inherit;">
        <div class="mc-card__body" style="padding: var(--space-lg);">
          <div style="display: flex; align-items: center; gap: var(--space-md); margin-bottom: var(--space-md);">
            <div style="width: 40px; height: 40px; border-radius: var(--radius-sm); background: #E60023; display: flex; align-items: center; justify-content: center;">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="white"><path d="M12 0a12 12 0 00-4.373 23.178c-.07-.633-.134-1.606.028-2.298.146-.625.943-3.998.943-3.998s-.24-.482-.24-1.194c0-1.119.649-1.954 1.457-1.954.687 0 1.019.516 1.019 1.135 0 .692-.44 1.726-.667 2.685-.19.803.402 1.457 1.193 1.457 1.431 0 2.53-1.51 2.53-3.688 0-1.929-1.386-3.276-3.366-3.276-2.293 0-3.64 1.72-3.64 3.499 0 .693.267 1.435.6 1.838a.24.24 0 01.056.23c-.061.256-.198.803-.225.916-.035.147-.116.178-.268.107-1-.465-1.624-1.926-1.624-3.1 0-2.523 1.834-4.84 5.286-4.84 2.775 0 4.932 1.977 4.932 4.62 0 2.757-1.739 4.976-4.151 4.976-.811 0-1.573-.421-1.834-.919l-.498 1.902c-.181.695-.669 1.566-.995 2.097A12 12 0 1012 0z"/></svg>
            </div>
            <h4 style="font-family: var(--font-sans); font-size: 16px; font-weight: 600;">Pinterest</h4>
          </div>
          <p style="color: var(--mc-gray); font-size: 14px; line-height: 1.5; margin-bottom: var(--space-md);">Run Pinterest ad campaigns from Mailchimp and retarget pinners who engage with your products. Drive traffic from visual discovery.</p>
          <span style="color: var(--mc-teal); font-size: 14px; font-weight: 500;">Connect &rarr;</span>
        </div>
      </a>

      <!-- TikTok -->
      <a href="integrations.php" class="mc-card mc-card--bordered" style="text-decoration: none; color: inherit;">
        <div class="mc-card__body" style="padding: var(--space-lg);">
          <div style="display: flex; align-items: center; gap: var(--space-md); margin-bottom: var(--space-md);">
            <div style="width: 40px; height: 40px; border-radius: var(--radius-sm); background: #000000; display: flex; align-items: center; justify-content: center;">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="white"><path d="M19.59 6.69a4.83 4.83 0 01-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 01-2.88 2.5 2.89 2.89 0 01-2.89-2.89 2.89 2.89 0 012.89-2.89c.28 0 .54.04.79.1v-3.5a6.37 6.37 0 00-.79-.05A6.34 6.34 0 003.15 15.2a6.34 6.34 0 0010.86 4.46V13.2a8.16 8.16 0 005.58 2.2V12a4.85 4.85 0 01-2.42-.64 4.83 4.83 0 01-1.58-1.47V6.69z"/></svg>
            </div>
            <h4 style="font-family: var(--font-sans); font-size: 16px; font-weight: 600;">TikTok</h4>
          </div>
          <p style="color: var(--mc-gray); font-size: 14px; line-height: 1.5; margin-bottom: var(--space-md);">Connect TikTok Ads to Mailchimp. Sync audiences, create video ad campaigns, and track conversions from short-form content.</p>
          <span style="color: var(--mc-teal); font-size: 14px; font-weight: 500;">Connect &rarr;</span>
        </div>
      </a>

    </div>
  </div>
</section>

<!-- ======================================================================
     INTEGRATIONS — ANALYTICS & REPORTING (4-col grid)
     ====================================================================== -->
<section class="mc-section" id="analytics">
  <div class="mc-container">
    <h2 style="font-family: var(--font-serif); font-size: clamp(24px, 3vw, 36px); font-weight: 300; margin-bottom: var(--space-sm);">Analytics &amp; Reporting</h2>
    <p style="color: var(--mc-gray); font-size: 17px; line-height: 1.6; margin-bottom: var(--space-2xl); max-width: 600px;">Measure what matters. Connect your analytics tools to track campaign performance, user behavior, and marketing ROI in one place.</p>
    <div class="mc-grid mc-grid--4 mc-grid--gap-md">

      <!-- Google Analytics -->
      <a href="integrations.php" class="mc-card mc-card--bordered" style="text-decoration: none; color: inherit;">
        <div class="mc-card__body" style="padding: var(--space-lg);">
          <div style="display: flex; align-items: center; gap: var(--space-md); margin-bottom: var(--space-md);">
            <img src="images/integrations/google-analytics.png" alt="Google Analytics" style="width: 40px; height: 40px; border-radius: var(--radius-sm);">
            <h4 style="font-family: var(--font-sans); font-size: 16px; font-weight: 600;">Google Analytics</h4>
          </div>
          <p style="color: var(--mc-gray); font-size: 14px; line-height: 1.5; margin-bottom: var(--space-md);">Automatically add Google Analytics tracking to your campaigns. Measure website traffic, conversions, and revenue driven by every email you send.</p>
          <span style="color: var(--mc-teal); font-size: 14px; font-weight: 500;">Connect &rarr;</span>
        </div>
      </a>

      <!-- Mixpanel -->
      <a href="integrations.php" class="mc-card mc-card--bordered" style="text-decoration: none; color: inherit;">
        <div class="mc-card__body" style="padding: var(--space-lg);">
          <div style="display: flex; align-items: center; gap: var(--space-md); margin-bottom: var(--space-md);">
            <div style="width: 40px; height: 40px; border-radius: var(--radius-sm); background: #7856FF; display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 18px; font-family: var(--font-sans);">M</div>
            <h4 style="font-family: var(--font-sans); font-size: 16px; font-weight: 600;">Mixpanel</h4>
          </div>
          <p style="color: var(--mc-gray); font-size: 14px; line-height: 1.5; margin-bottom: var(--space-md);">Send Mailchimp campaign events to Mixpanel for advanced product analytics. Understand how email engagement impacts user behavior and retention.</p>
          <span style="color: var(--mc-teal); font-size: 14px; font-weight: 500;">Connect &rarr;</span>
        </div>
      </a>

      <!-- Hotjar -->
      <a href="integrations.php" class="mc-card mc-card--bordered" style="text-decoration: none; color: inherit;">
        <div class="mc-card__body" style="padding: var(--space-lg);">
          <div style="display: flex; align-items: center; gap: var(--space-md); margin-bottom: var(--space-md);">
            <div style="width: 40px; height: 40px; border-radius: var(--radius-sm); background: #FF3C00; display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 18px; font-family: var(--font-sans);">H</div>
            <h4 style="font-family: var(--font-sans); font-size: 16px; font-weight: 600;">Hotjar</h4>
          </div>
          <p style="color: var(--mc-gray); font-size: 14px; line-height: 1.5; margin-bottom: var(--space-md);">See heatmaps and session recordings for visitors from your email campaigns. Understand what they do on your site after clicking through.</p>
          <span style="color: var(--mc-teal); font-size: 14px; font-weight: 500;">Connect &rarr;</span>
        </div>
      </a>

      <!-- Segment -->
      <a href="integrations.php" class="mc-card mc-card--bordered" style="text-decoration: none; color: inherit;">
        <div class="mc-card__body" style="padding: var(--space-lg);">
          <div style="display: flex; align-items: center; gap: var(--space-md); margin-bottom: var(--space-md);">
            <div style="width: 40px; height: 40px; border-radius: var(--radius-sm); background: #52BD94; display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 18px; font-family: var(--font-sans);">S</div>
            <h4 style="font-family: var(--font-sans); font-size: 16px; font-weight: 600;">Segment</h4>
          </div>
          <p style="color: var(--mc-gray); font-size: 14px; line-height: 1.5; margin-bottom: var(--space-md);">Use Segment as a customer data platform to route events to Mailchimp. Unify data from every touchpoint for precise audience segmentation.</p>
          <span style="color: var(--mc-teal); font-size: 14px; font-weight: 500;">Connect &rarr;</span>
        </div>
      </a>

    </div>
  </div>
</section>

<!-- ======================================================================
     INTEGRATIONS — DEVELOPER TOOLS (4-col grid)
     ====================================================================== -->
<section class="mc-section mc-section--light" id="developer">
  <div class="mc-container">
    <h2 style="font-family: var(--font-serif); font-size: clamp(24px, 3vw, 36px); font-weight: 300; margin-bottom: var(--space-sm);">Developer Tools</h2>
    <p style="color: var(--mc-gray); font-size: 17px; line-height: 1.6; margin-bottom: var(--space-2xl); max-width: 600px;">Build custom workflows and extend Mailchimp with APIs, webhooks, and automation platforms that connect to your tech stack.</p>
    <div class="mc-grid mc-grid--4 mc-grid--gap-md">

      <!-- Zapier -->
      <a href="integrations.php" class="mc-card mc-card--bordered" style="text-decoration: none; color: inherit;">
        <div class="mc-card__body" style="padding: var(--space-lg);">
          <div style="display: flex; align-items: center; gap: var(--space-md); margin-bottom: var(--space-md);">
            <img src="images/integrations/zapier.png" alt="Zapier" style="width: 40px; height: 40px; border-radius: var(--radius-sm);">
            <h4 style="font-family: var(--font-sans); font-size: 16px; font-weight: 600;">Zapier</h4>
          </div>
          <p style="color: var(--mc-gray); font-size: 14px; line-height: 1.5; margin-bottom: var(--space-md);">Connect Mailchimp to 5,000+ apps with no code. Automate subscriber management, trigger campaigns from any event, and sync data across your entire stack.</p>
          <span style="color: var(--mc-teal); font-size: 14px; font-weight: 500;">Connect &rarr;</span>
        </div>
      </a>

      <!-- API -->
      <a href="integrations.php" class="mc-card mc-card--bordered" style="text-decoration: none; color: inherit;">
        <div class="mc-card__body" style="padding: var(--space-lg);">
          <div style="display: flex; align-items: center; gap: var(--space-md); margin-bottom: var(--space-md);">
            <div style="width: 40px; height: 40px; border-radius: var(--radius-sm); background: var(--mc-teal); display: flex; align-items: center; justify-content: center;">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/></svg>
            </div>
            <h4 style="font-family: var(--font-sans); font-size: 16px; font-weight: 600;">Mailchimp API</h4>
          </div>
          <p style="color: var(--mc-gray); font-size: 14px; line-height: 1.5; margin-bottom: var(--space-md);">Build custom integrations with the Mailchimp Marketing API. Full REST API access to audiences, campaigns, automations, reports, and more.</p>
          <span style="color: var(--mc-teal); font-size: 14px; font-weight: 500;">View docs &rarr;</span>
        </div>
      </a>

      <!-- Webhooks -->
      <a href="integrations.php" class="mc-card mc-card--bordered" style="text-decoration: none; color: inherit;">
        <div class="mc-card__body" style="padding: var(--space-lg);">
          <div style="display: flex; align-items: center; gap: var(--space-md); margin-bottom: var(--space-md);">
            <div style="width: 40px; height: 40px; border-radius: var(--radius-sm); background: var(--mc-dark-gray); display: flex; align-items: center; justify-content: center;">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10 13a5 5 0 007.54.54l3-3a5 5 0 00-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 00-7.54-.54l-3 3a5 5 0 007.07 7.07l1.71-1.71"/></svg>
            </div>
            <h4 style="font-family: var(--font-sans); font-size: 16px; font-weight: 600;">Webhooks</h4>
          </div>
          <p style="color: var(--mc-gray); font-size: 14px; line-height: 1.5; margin-bottom: var(--space-md);">Receive real-time notifications when subscribers join, leave, or update their profiles. Push Mailchimp events to your own systems instantly.</p>
          <span style="color: var(--mc-teal); font-size: 14px; font-weight: 500;">Set up &rarr;</span>
        </div>
      </a>

      <!-- WordPress -->
      <a href="integrations.php" class="mc-card mc-card--bordered" style="text-decoration: none; color: inherit;">
        <div class="mc-card__body" style="padding: var(--space-lg);">
          <div style="display: flex; align-items: center; gap: var(--space-md); margin-bottom: var(--space-md);">
            <img src="images/integrations/wordpress.png" alt="WordPress" style="width: 40px; height: 40px; border-radius: var(--radius-sm);">
            <h4 style="font-family: var(--font-sans); font-size: 16px; font-weight: 600;">WordPress</h4>
          </div>
          <p style="color: var(--mc-gray); font-size: 14px; line-height: 1.5; margin-bottom: var(--space-md);">Add signup forms to your WordPress site, sync subscribers automatically, and display campaign archives. Works with any WordPress theme.</p>
          <span style="color: var(--mc-teal); font-size: 14px; font-weight: 500;">Connect &rarr;</span>
        </div>
      </a>

    </div>
  </div>
</section>

<!-- ======================================================================
     INTEGRATIONS — BUILD YOUR OWN (Feature Row)
     ====================================================================== -->
<section class="mc-section mc-section--cream">
  <div class="mc-container">
    <div class="mc-feature-row">
      <div class="mc-feature-row__image">
        <div style="background: var(--mc-black); border-radius: var(--radius-lg); padding: var(--space-2xl); color: var(--mc-white); font-family: 'Courier New', monospace; font-size: 13px; line-height: 1.7; overflow: hidden;">
          <div style="margin-bottom: var(--space-md);">
            <span style="color: #FF6B6B;">POST</span> <span style="color: #4ECDC4;">/3.0/lists/{list_id}/members</span>
          </div>
          <div style="color: #999; margin-bottom: var(--space-sm);">// Add a subscriber to your audience</div>
          <div>{</div>
          <div>&nbsp;&nbsp;<span style="color: #FFE01B;">"email_address"</span>: <span style="color: #4ECDC4;">"user@example.com"</span>,</div>
          <div>&nbsp;&nbsp;<span style="color: #FFE01B;">"status"</span>: <span style="color: #4ECDC4;">"subscribed"</span>,</div>
          <div>&nbsp;&nbsp;<span style="color: #FFE01B;">"merge_fields"</span>: {</div>
          <div>&nbsp;&nbsp;&nbsp;&nbsp;<span style="color: #FFE01B;">"FNAME"</span>: <span style="color: #4ECDC4;">"Jane"</span>,</div>
          <div>&nbsp;&nbsp;&nbsp;&nbsp;<span style="color: #FFE01B;">"LNAME"</span>: <span style="color: #4ECDC4;">"Doe"</span></div>
          <div>&nbsp;&nbsp;},</div>
          <div>&nbsp;&nbsp;<span style="color: #FFE01B;">"tags"</span>: [<span style="color: #4ECDC4;">"VIP"</span>, <span style="color: #4ECDC4;">"Newsletter"</span>]</div>
          <div>}</div>
          <div style="margin-top: var(--space-md); color: #4ECDC4;">// Response: 200 OK</div>
        </div>
      </div>
      <div class="mc-feature-row__content">
        <p style="font-family: var(--font-sans); font-size: 13px; font-weight: 600; letter-spacing: 1.5px; text-transform: uppercase; color: var(--mc-teal); margin-bottom: var(--space-md);">FOR DEVELOPERS</p>
        <h2 class="mc-feature-row__title" style="font-family: var(--font-serif); font-size: clamp(24px, 3vw, 36px); font-weight: 300;">Build your own integration with the Mailchimp API</h2>
        <p class="mc-feature-row__desc" style="font-size: 17px; line-height: 1.7; color: var(--mc-gray); margin-bottom: var(--space-lg);">
          Our comprehensive REST API gives you full control over audiences, campaigns, automations, and reporting. Build exactly the integration your business needs.
        </p>
        <ul style="list-style: none; padding: 0; margin: 0 0 var(--space-xl) 0;">
          <li style="display: flex; align-items: flex-start; gap: var(--space-sm); margin-bottom: var(--space-md); font-size: 15px; color: var(--mc-dark-gray);">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--mc-teal)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink: 0; margin-top: 2px;"><polyline points="20 6 9 17 4 12"/></svg>
            <span><strong>OAuth 2.0 authentication</strong> &mdash; secure, standard auth flow for third-party apps</span>
          </li>
          <li style="display: flex; align-items: flex-start; gap: var(--space-sm); margin-bottom: var(--space-md); font-size: 15px; color: var(--mc-dark-gray);">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--mc-teal)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink: 0; margin-top: 2px;"><polyline points="20 6 9 17 4 12"/></svg>
            <span><strong>Comprehensive documentation</strong> &mdash; detailed guides, references, and code examples</span>
          </li>
          <li style="display: flex; align-items: flex-start; gap: var(--space-sm); margin-bottom: var(--space-md); font-size: 15px; color: var(--mc-dark-gray);">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--mc-teal)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink: 0; margin-top: 2px;"><polyline points="20 6 9 17 4 12"/></svg>
            <span><strong>Client libraries &amp; SDKs</strong> &mdash; official libraries for Node.js, Python, PHP, Ruby, and more</span>
          </li>
          <li style="display: flex; align-items: flex-start; gap: var(--space-sm); font-size: 15px; color: var(--mc-dark-gray);">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--mc-teal)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink: 0; margin-top: 2px;"><polyline points="20 6 9 17 4 12"/></svg>
            <span><strong>Webhook support</strong> &mdash; real-time event notifications for subscribes, unsubscribes, and more</span>
          </li>
        </ul>
        <a href="features.php" class="mc-btn mc-btn--primary">View API Documentation</a>
      </div>
    </div>
  </div>
</section>

<!-- ======================================================================
     INTEGRATIONS — STATS (Trusted by millions)
     ====================================================================== -->
<section class="mc-stats-section">
  <div class="mc-container">
    <div class="mc-stats-section__header">
      <h2 class="mc-stats-section__heading">Trusted by millions of businesses worldwide</h2>
      <p class="mc-stats-section__subheading">Mailchimp integrations power marketing for businesses of every size&mdash;from solo founders to enterprise teams.</p>
    </div>
    <div class="mc-stats-section__grid">
      <div class="mc-stats-section__item">
        <span class="mc-stats-section__number">13M+</span>
        <span class="mc-stats-section__label">Active users</span>
        <p class="mc-stats-section__desc">Businesses around the world trust Mailchimp to reach their customers and grow their revenue.</p>
      </div>
      <div class="mc-stats-section__item">
        <span class="mc-stats-section__number">300+</span>
        <span class="mc-stats-section__label">Integrations</span>
        <p class="mc-stats-section__desc">Connect your favorite tools&mdash;from e-commerce and CRM to social media and analytics.</p>
      </div>
      <div class="mc-stats-section__item">
        <span class="mc-stats-section__number">11B</span>
        <span class="mc-stats-section__label">Emails per month</span>
        <p class="mc-stats-section__desc">Over 11 billion emails sent every month through the Mailchimp platform and its integrations.</p>
      </div>
      <div class="mc-stats-section__item">
        <span class="mc-stats-section__number">99%</span>
        <span class="mc-stats-section__label">Uptime SLA</span>
        <p class="mc-stats-section__desc">Industry-leading reliability ensures your integrations and campaigns run smoothly around the clock.</p>
      </div>
    </div>
  </div>
</section>

<!-- ======================================================================
     INTEGRATIONS — CTA (Don't see your tool?)
     ====================================================================== -->
<section class="mc-section" style="padding: var(--space-4xl) 0;">
  <div class="mc-container">
    <div style="max-width: 700px; margin: 0 auto; text-align: center;">
      <div style="width: 64px; height: 64px; border-radius: 50%; background: var(--mc-cream); display: flex; align-items: center; justify-content: center; margin: 0 auto var(--space-lg);">
        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="var(--mc-teal)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/></svg>
      </div>
      <h2 style="font-family: var(--font-serif); font-size: clamp(24px, 3.5vw, 40px); font-weight: 300; margin-bottom: var(--space-md);">Don&rsquo;t see your tool?</h2>
      <p style="font-size: 17px; color: var(--mc-gray); line-height: 1.7; margin-bottom: var(--space-lg); max-width: 560px; margin-left: auto; margin-right: auto;">
        We&rsquo;re always adding new integrations. If the tool you need isn&rsquo;t listed, let us know or use our
        API and Zapier to build a custom connection in minutes.
      </p>
      <div style="display: flex; gap: var(--space-md); justify-content: center; flex-wrap: wrap;">
        <a href="contact.php" class="mc-btn mc-btn--primary">Request an Integration</a>
        <a href="features.php" class="mc-btn mc-btn--secondary" style="border: 2px solid var(--mc-border); border-radius: var(--radius-btn); padding: 12px 28px; font-family: var(--font-sans); font-size: 15px; font-weight: 600; text-decoration: none; color: var(--mc-black); transition: all var(--transition-fast);">Explore the API</a>
      </div>
    </div>
  </div>
</section>

<!-- ======================================================================
     INTEGRATIONS — MORE INTEGRATIONS (Additional grid)
     ====================================================================== -->
<section class="mc-section mc-section--cream">
  <div class="mc-container">
    <h2 style="font-family: var(--font-serif); font-size: clamp(24px, 3vw, 36px); font-weight: 300; margin-bottom: var(--space-sm); text-align: center;">More Popular Integrations</h2>
    <p style="color: var(--mc-gray); font-size: 17px; line-height: 1.6; margin-bottom: var(--space-2xl); max-width: 600px; text-align: center; margin-left: auto; margin-right: auto;">Discover even more ways to connect your tools and streamline your marketing workflow.</p>
    <div class="mc-grid mc-grid--4 mc-grid--gap-md">

      <!-- Slack -->
      <a href="integrations.php" class="mc-card mc-card--bordered" style="text-decoration: none; color: inherit;">
        <div class="mc-card__body" style="padding: var(--space-lg);">
          <div style="display: flex; align-items: center; gap: var(--space-md); margin-bottom: var(--space-md);">
            <img src="images/integrations/slack.png" alt="Slack" style="width: 40px; height: 40px; border-radius: var(--radius-sm);">
            <h4 style="font-family: var(--font-sans); font-size: 16px; font-weight: 600;">Slack</h4>
          </div>
          <p style="color: var(--mc-gray); font-size: 14px; line-height: 1.5; margin-bottom: var(--space-md);">Get real-time campaign notifications in Slack. Monitor opens, clicks, and subscriber activity without leaving your workspace.</p>
          <span style="color: var(--mc-teal); font-size: 14px; font-weight: 500;">Connect &rarr;</span>
        </div>
      </a>

      <!-- Eventbrite -->
      <a href="integrations.php" class="mc-card mc-card--bordered" style="text-decoration: none; color: inherit;">
        <div class="mc-card__body" style="padding: var(--space-lg);">
          <div style="display: flex; align-items: center; gap: var(--space-md); margin-bottom: var(--space-md);">
            <img src="images/integrations/eventbrite.png" alt="Eventbrite" style="width: 40px; height: 40px; border-radius: var(--radius-sm);">
            <h4 style="font-family: var(--font-sans); font-size: 16px; font-weight: 600;">Eventbrite</h4>
          </div>
          <p style="color: var(--mc-gray); font-size: 14px; line-height: 1.5; margin-bottom: var(--space-md);">Sync event attendees to Mailchimp audiences. Send pre-event reminders, post-event follow-ups, and grow your community.</p>
          <span style="color: var(--mc-teal); font-size: 14px; font-weight: 500;">Connect &rarr;</span>
        </div>
      </a>

      <!-- Zoom -->
      <a href="integrations.php" class="mc-card mc-card--bordered" style="text-decoration: none; color: inherit;">
        <div class="mc-card__body" style="padding: var(--space-lg);">
          <div style="display: flex; align-items: center; gap: var(--space-md); margin-bottom: var(--space-md);">
            <img src="images/integrations/zoom.png" alt="Zoom" style="width: 40px; height: 40px; border-radius: var(--radius-sm);">
            <h4 style="font-family: var(--font-sans); font-size: 16px; font-weight: 600;">Zoom</h4>
          </div>
          <p style="color: var(--mc-gray); font-size: 14px; line-height: 1.5; margin-bottom: var(--space-md);">Add Zoom webinar and meeting registrants to your audiences. Automate follow-up emails based on attendance and engagement.</p>
          <span style="color: var(--mc-teal); font-size: 14px; font-weight: 500;">Connect &rarr;</span>
        </div>
      </a>

      <!-- WhatsApp -->
      <a href="integrations.php" class="mc-card mc-card--bordered" style="text-decoration: none; color: inherit;">
        <div class="mc-card__body" style="padding: var(--space-lg);">
          <div style="display: flex; align-items: center; gap: var(--space-md); margin-bottom: var(--space-md);">
            <img src="images/integrations/whatsapp.png" alt="WhatsApp" style="width: 40px; height: 40px; border-radius: var(--radius-sm);">
            <h4 style="font-family: var(--font-sans); font-size: 16px; font-weight: 600;">WhatsApp</h4>
          </div>
          <p style="color: var(--mc-gray); font-size: 14px; line-height: 1.5; margin-bottom: var(--space-md);">Reach customers on WhatsApp Business. Send order confirmations, shipping updates, and promotional messages alongside email.</p>
          <span style="color: var(--mc-teal); font-size: 14px; font-weight: 500;">Connect &rarr;</span>
        </div>
      </a>

      <!-- Typeform -->
      <a href="integrations.php" class="mc-card mc-card--bordered" style="text-decoration: none; color: inherit;">
        <div class="mc-card__body" style="padding: var(--space-lg);">
          <div style="display: flex; align-items: center; gap: var(--space-md); margin-bottom: var(--space-md);">
            <img src="images/integrations/typeform.png" alt="Typeform" style="width: 40px; height: 40px; border-radius: var(--radius-sm);">
            <h4 style="font-family: var(--font-sans); font-size: 16px; font-weight: 600;">Typeform</h4>
          </div>
          <p style="color: var(--mc-gray); font-size: 14px; line-height: 1.5; margin-bottom: var(--space-md);">Automatically add Typeform respondents to your Mailchimp audiences. Use form responses as merge fields for personalized campaigns.</p>
          <span style="color: var(--mc-teal); font-size: 14px; font-weight: 500;">Connect &rarr;</span>
        </div>
      </a>

      <!-- Google Ads -->
      <a href="integrations.php" class="mc-card mc-card--bordered" style="text-decoration: none; color: inherit;">
        <div class="mc-card__body" style="padding: var(--space-lg);">
          <div style="display: flex; align-items: center; gap: var(--space-md); margin-bottom: var(--space-md);">
            <img src="images/integrations/google-ads.png" alt="Google Ads" style="width: 40px; height: 40px; border-radius: var(--radius-sm);">
            <h4 style="font-family: var(--font-sans); font-size: 16px; font-weight: 600;">Google Ads</h4>
          </div>
          <p style="color: var(--mc-gray); font-size: 14px; line-height: 1.5; margin-bottom: var(--space-md);">Retarget your Mailchimp contacts with Google Ads. Create Customer Match audiences and track ad conversions alongside email metrics.</p>
          <span style="color: var(--mc-teal); font-size: 14px; font-weight: 500;">Connect &rarr;</span>
        </div>
      </a>

      <!-- Squarespace -->
      <a href="integrations.php" class="mc-card mc-card--bordered" style="text-decoration: none; color: inherit;">
        <div class="mc-card__body" style="padding: var(--space-lg);">
          <div style="display: flex; align-items: center; gap: var(--space-md); margin-bottom: var(--space-md);">
            <div style="width: 40px; height: 40px; border-radius: var(--radius-sm); background: #000000; display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 18px; font-family: var(--font-sans);">S</div>
            <h4 style="font-family: var(--font-sans); font-size: 16px; font-weight: 600;">Squarespace</h4>
          </div>
          <p style="color: var(--mc-gray); font-size: 14px; line-height: 1.5; margin-bottom: var(--space-md);">Add Mailchimp signup forms to your Squarespace site. Sync contacts from commerce orders and newsletter blocks automatically.</p>
          <span style="color: var(--mc-teal); font-size: 14px; font-weight: 500;">Connect &rarr;</span>
        </div>
      </a>

      <!-- Canva -->
      <a href="integrations.php" class="mc-card mc-card--bordered" style="text-decoration: none; color: inherit;">
        <div class="mc-card__body" style="padding: var(--space-lg);">
          <div style="display: flex; align-items: center; gap: var(--space-md); margin-bottom: var(--space-md);">
            <div style="width: 40px; height: 40px; border-radius: var(--radius-sm); background: #00C4CC; display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 18px; font-family: var(--font-sans);">C</div>
            <h4 style="font-family: var(--font-sans); font-size: 16px; font-weight: 600;">Canva</h4>
          </div>
          <p style="color: var(--mc-gray); font-size: 14px; line-height: 1.5; margin-bottom: var(--space-md);">Design stunning email graphics in Canva and import them directly into your Mailchimp email builder. No downloads needed.</p>
          <span style="color: var(--mc-teal); font-size: 14px; font-weight: 500;">Connect &rarr;</span>
        </div>
      </a>

    </div>
  </div>
</section>

<!-- ======================================================================
     INTEGRATIONS — BROWSE ALL CTA
     ====================================================================== -->
<section class="mc-section" style="padding: var(--space-3xl) 0;">
  <div class="mc-container">
    <div style="display: flex; align-items: center; justify-content: space-between; background: var(--mc-light-gray); border-radius: var(--radius-lg); padding: var(--space-2xl) var(--space-3xl); flex-wrap: wrap; gap: var(--space-lg);">
      <div style="flex: 1; min-width: 280px;">
        <h3 style="font-family: var(--font-serif); font-size: 24px; font-weight: 400; margin-bottom: var(--space-sm);">Ready to connect your marketing stack?</h3>
        <p style="color: var(--mc-gray); font-size: 15px; line-height: 1.6;">Start a free trial and explore all 300+ integrations. Set up takes minutes, not hours.</p>
      </div>
      <div style="display: flex; gap: var(--space-md); flex-wrap: wrap;">
        <a href="pricing.php" class="mc-btn mc-btn--primary">Start Free Trial</a>
        <a href="integrations.php" class="mc-link mc-link--arrow" style="font-size: 16px; font-weight: 500; display: flex; align-items: center; color: var(--mc-teal); text-decoration: none;">Browse all integrations &rarr;</a>
      </div>
    </div>
  </div>
</section>

<?php include '_footer.php'; ?>
