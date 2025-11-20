<!-- Bootstrap CSS & JS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
  integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB"
  crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
  integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz4YYwJrWVcXK/BmnVDxM+D2scQbITxI"
  crossorigin="anonymous"></script>

<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<!-- === Footer Styles === -->
<style>
:root {
  --site-footer-bg: #182052;
  --footer-accent: #f2c516;
  --footer-hover: #e2b90e;
}

.site-footer {
  background-color: var(--site-footer-bg);
  color: #fff;
  margin-top: auto;
}

.site-footer .footer-logo-section {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.site-footer .logo {
  width: 120px;
  height: auto;
  object-fit: contain;
}

.site-footer .footer-heading {
  font-size: 1.25rem;
  font-weight: 600;
  margin-bottom: 1.25rem;
  color: var(--footer-accent);
  position: relative;
  padding-bottom: 0.5rem;
}

.site-footer .footer-heading::after {
  content: '';
  position: absolute;
  left: 0;
  bottom: 0;
  width: 40px;
  height: 3px;
  background: var(--footer-accent);
  border-radius: 2px;
}

.site-footer .contact-info {
  display: flex;
  flex-direction: column;
  gap: 0.75rem;
}

.site-footer .contact-item {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  font-size: 0.9rem;
  color: rgba(255, 255, 255, 0.85);
  transition: all 0.3s ease;
}

.site-footer .contact-item:hover {
  color: var(--footer-accent);
  transform: translateX(5px);
}

.site-footer .contact-item i {
  width: 20px;
  font-size: 1.1rem;
  color: var(--footer-accent);
}

.site-footer .contact-item a {
  color: inherit;
  text-decoration: none;
  transition: color 0.3s ease;
}

.site-footer .contact-item a:hover {
  color: var(--footer-accent);
}

.site-footer .footer-links {
  list-style: none;
  padding: 0;
  margin: 0;
  display: flex;
  flex-direction: column;
  gap: 0.75rem;
}

.site-footer .footer-links li {
  margin: 0;
}

.site-footer .footer-links a {
  color: rgba(255, 255, 255, 0.85);
  text-decoration: none;
  font-size: 0.9rem;
  transition: all 0.3s ease;
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
}

.site-footer .footer-links a::before {
  content: '›';
  font-size: 1.2rem;
  color: var(--footer-accent);
  transition: transform 0.3s ease;
}

.site-footer .footer-links a:hover {
  color: var(--footer-accent);
  transform: translateX(5px);
}

.site-footer .footer-links a:hover::before {
  transform: translateX(3px);
}

.site-footer .social-section {
  display: flex;
  flex-direction: column;
  gap: 1.25rem;
}

.site-footer .social-links {
  display: flex;
  gap: 0.75rem;
  flex-wrap: wrap;
}

.site-footer .social-btn {
  width: 42px;
  height: 42px;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  border-radius: 50%;
  background: rgba(255, 255, 255, 0.1);
  color: #fff;
  text-decoration: none;
  transition: all 0.3s ease;
  border: 2px solid transparent;
}

.site-footer .social-btn:hover {
  background: var(--footer-accent);
  color: var(--site-footer-bg);
  transform: translateY(-3px);
  box-shadow: 0 4px 12px rgba(242, 197, 22, 0.3);
}

.site-footer .social-btn svg {
  width: 18px;
  height: 18px;
}

.site-footer .footer-divider {
  border-color: rgba(255, 255, 255, 0.15);
  margin: 2rem 0 1.5rem;
}

.site-footer .footer-bottom {
  display: flex;
  justify-content: space-between;
  align-items: center;
  flex-wrap: wrap;
  gap: 1rem;
  font-size: 0.875rem;
  color: rgba(255, 255, 255, 0.7);
}

.site-footer .footer-bottom-links {
  display: flex;
  gap: 1rem;
  align-items: center;
}

.site-footer .footer-bottom-links a {
  color: rgba(255, 255, 255, 0.85);
  text-decoration: none;
  transition: color 0.3s ease;
}

.site-footer .footer-bottom-links a:hover {
  color: var(--footer-accent);
}

.site-footer .footer-bottom-links span {
  color: rgba(255, 255, 255, 0.4);
}

/* Responsive Design */
@media (max-width: 991px) {
  .site-footer .footer-heading::after {
    left: 50%;
    transform: translateX(-50%);
  }
  
  .site-footer .footer-section {
    text-align: center;
  }
  
  .site-footer .footer-logo-section {
    align-items: center;
  }
  
  .site-footer .contact-info {
    align-items: center;
  }
  
  .site-footer .contact-item:hover {
    transform: translateX(0) scale(1.05);
  }
  
  .site-footer .footer-links a:hover {
    transform: translateX(0) scale(1.05);
  }
  
  .site-footer .social-links {
    justify-content: center;
  }
}

/* CRITICAL FIX: Override any conflicting styles from other CSS files */
.site-footer .contact-item {
  background: transparent !important;
  border: none !important;
  padding: 0 !important;
  border-radius: 0 !important;
}

.site-footer .contact-info .contact-item {
  background-color: transparent !important;
}

.site-footer input,
.site-footer textarea,
.site-footer .input-box input {
  display: none !important;
}

@media (max-width: 767px) {
  .site-footer {
    padding: 2rem 0;
  }
  
  .site-footer .footer-bottom {
    flex-direction: column;
    text-align: center;
    gap: 0.75rem;
  }
  
  .site-footer .footer-bottom-links {
    flex-wrap: wrap;
    justify-content: center;
  }
}

@media (max-width: 575px) {
  .site-footer .logo {
    width: 100px;
  }
  
  .site-footer .footer-heading {
    font-size: 1.1rem;
  }
  
  .site-footer .social-btn {
    width: 38px;
    height: 38px;
  }
  
  .site-footer .social-btn svg {
    width: 16px;
    height: 16px;
  }
}
</style>

<!-- === Footer === -->
<footer class="site-footer py-5" role="contentinfo" aria-label="Site footer">
  <div class="container">
    <div class="row g-4 g-lg-5">

      <!-- Logo and Contact Section -->
      <div class="col-lg-4 col-md-6">
        <div class="footer-logo-section">
          <img src="images/brgylogo.png" alt="Barangay 413 logo" class="logo mb-2">
          <div class="contact-info">
            <div class="contact-item">
              <i class="bi bi-person-badge"></i>
              <span>Kap. Aron Smith</span>
            </div>
            <div class="contact-item">
              <i class="bi bi-telephone"></i>
              <a href="tel:+639667403386">0966-740-3386</a>
            </div>
            <div class="contact-item">
              <i class="bi bi-geo-alt"></i>
              <span>Lardizabala St., Brgy. 413, Zone 42, District 4, Sampaloc, Manila</span>
            </div>
            <div class="contact-item">
              <i class="bi bi-envelope"></i>
              <a href="mailto:brgy413.official@gmail.com">brgy413.official@gmail.com</a>
            </div>
          </div>
        </div>
      </div>

      <!-- Quick Links Section -->
      <div class="col-lg-3 col-md-6">
        <div class="footer-section">
          <h3 class="footer-heading">Quick Links</h3>
          <ul class="footer-links">
            <li><a href="userOfficials.php">Barangay Officials</a></li>
            <li><a href="https://www.dpwh.gov.ph/dpwh/Government-Links" target="_blank" rel="noopener">Government Links</a></li>
            <li><a href="https://sampaloc.gov.ph/contact-us/" target="_blank" rel="noopener">Emergency Hotlines</a></li>
            
          </ul>
        </div>
      </div>

      <!-- Services Section -->
      <div class="col-lg-2 col-md-6">
        <div class="footer-section">
          <h3 class="footer-heading">Services</h3>
          <ul class="footer-links">
            <li><a href="request_documents.php">Request Documents</a></li>
            <li><a href="userComplaints.php">Isumbong mo kay Kap!</a></li>
            <li><a href="userHomepage.php">News Updates</a></li>
          </ul>
        </div>
      </div>

      <!-- Social Media Section -->
      <div class="col-lg-3 col-md-6">
        <div class="footer-section">
          <h3 class="footer-heading">Stay Connected</h3>
          <div class="social-section">
            <p class="mb-3" style="font-size: 0.9rem; color: rgba(255, 255, 255, 0.85);">
              Follow us on social media for the latest updates and announcements.
            </p>
            <div class="social-links">
              <a class="social-btn" href="https://www.facebook.com/profile.php?id=61553850416521" aria-label="Facebook" title="Facebook">
                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16">
                  <path d="M16 8.049c0-4.446-3.582-8.05-8-8.05C3.58 0-.002 3.603-.002 8.05c0 4.017 2.926 7.347 6.75 7.951v-5.625h-2.03V8.05H6.75V6.275c0-2.017 1.195-3.131 3.022-3.131.876 0 1.791.157 1.791.157v1.98h-1.009c-.993 0-1.303.621-1.303 1.258v1.51h2.218l-.354 2.326H9.25V16c3.824-.604 6.75-3.934 6.75-7.951z"/>
                </svg>
              </a>
              <a class="social-btn" href="https://twitter.com/login" aria-label="X (Twitter)" title="X">
                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16">
                  <path d="M12.6.75h2.454l-5.36 6.142L16 15.25h-4.937l-3.867-5.07-4.425 5.07H.316l5.733-6.57L0 .75h5.063l3.495 4.633L12.6.75zm-.86 13.028h1.36L4.323 2.145H2.865l8.875 11.633z"/>
                </svg>
              </a>
              <a class="social-btn" href="https://www.instagram.com/accounts/login/" aria-label="Instagram" title="Instagram">
                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16">
                  <path d="M8 0C5.829 0 5.556.01 4.703.048 3.85.088 3.269.222 2.76.42a3.917 3.917 0 0 0-1.417.923A3.927 3.927 0 0 0 .42 2.76C.222 3.268.087 3.85.048 4.7.01 5.555 0 5.827 0 8.001c0 2.172.01 2.444.048 3.297.04.852.174 1.433.372 1.942.205.526.478.972.923 1.417.444.445.89.719 1.416.923.51.198 1.09.333 1.942.372C5.555 15.99 5.827 16 8 16s2.444-.01 3.298-.048c.851-.04 1.434-.174 1.943-.372a3.916 3.916 0 0 0 1.416-.923c.445-.445.718-.891.923-1.417.197-.509.332-1.09.372-1.942C15.99 10.445 16 10.173 16 8s-.01-2.445-.048-3.299c-.04-.851-.175-1.433-.372-1.941a3.926 3.926 0 0 0-.923-1.417A3.911 3.911 0 0 0 13.24.42c-.51-.198-1.092-.333-1.943-.372C10.443.01 10.172 0 7.998 0h.003zm-.717 1.442h.718c2.136 0 2.389.007 3.232.046.78.035 1.204.166 1.486.275.373.145.64.319.92.599.28.28.453.546.598.92.11.281.24.705.275 1.485.039.843.047 1.096.047 3.231s-.008 2.389-.047 3.232c-.035.78-.166 1.203-.275 1.485a2.47 2.47 0 0 1-.599.919c-.28.28-.546.453-.92.598-.28.11-.704.24-1.485.276-.843.038-1.096.047-3.232.047s-2.39-.009-3.233-.047c-.78-.036-1.203-.166-1.485-.276a2.478 2.478 0 0 1-.92-.598 2.48 2.48 0 0 1-.6-.92c-.109-.281-.24-.705-.275-1.485-.038-.843-.046-1.096-.046-3.233 0-2.136.008-2.388.046-3.231.036-.78.166-1.204.276-1.486.145-.373.319-.64.599-.92.28-.28.546-.453.92-.598.282-.11.705-.24 1.485-.276.738-.034 1.024-.044 2.515-.045v.002zm4.988 1.328a.96.96 0 1 0 0 1.92.96.96 0 0 0 0-1.92zm-4.27 1.122a4.109 4.109 0 1 0 0 8.217 4.109 4.109 0 0 0 0-8.217zm0 1.441a2.667 2.667 0 1 1 0 5.334 2.667 2.667 0 0 1 0-5.334z"/>
                </svg>
              </a>
              <a class="social-btn" href="mailto:brgy413.official@gmail.com" aria-label="Email" title="Email">
                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16">
                  <path d="M.05 3.555A2 2 0 0 1 2 2h12a2 2 0 0 1 1.95 1.555L8 8.414.05 3.555zM0 4.697v7.104l5.803-3.558L0 4.697zM6.761 8.83l-6.57 4.027A2 2 0 0 0 2 14h12a2 2 0 0 0 1.808-1.144l-6.57-4.027L8 9.586l-1.239-.757zm3.436-.586L16 11.801V4.697l-5.803 3.546z"/>
                </svg>
              </a>
            </div>
          </div>
        </div>
      </div>

    </div>

    <hr class="footer-divider">

    <div class="footer-bottom">
      <div>© 2025 Barangay 413 — All rights reserved</div>
      <div class="footer-bottom-links">
        <a href="privacy.php">Privacy Policy</a>
        <span>•</span>
        <a href="terms.php">Terms of Service</a>
        <span>•</span>
      </div>
    </div>
  </div>
</footer>