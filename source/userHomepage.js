// === CAROUSEL FUNCTIONALITY ===
const leftBtn = document.querySelector('.left-btn');
const rightBtn = document.querySelector('.right-btn');
const track = document.querySelector('.carousel-track');
const cards = document.querySelectorAll('.news-card');

const scrollAmount = 300;

// Scroll with arrow buttons
if (leftBtn && rightBtn && track) {
  leftBtn.addEventListener('click', () => {
    track.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
  });

  rightBtn.addEventListener('click', () => {
    track.scrollBy({ left: scrollAmount, behavior: 'smooth' });
  });

  // Highlight and scale the center card
  function highlightCenterCard() {
    const trackRect = track.getBoundingClientRect();
    const trackCenter = trackRect.left + trackRect.width / 2;

    cards.forEach(card => {
      const cardRect = card.getBoundingClientRect();
      const cardCenter = cardRect.left + cardRect.width / 2;
      const distance = Math.abs(trackCenter - cardCenter);

      const scale = Math.max(0.9, 1.1 - distance / 400);
      card.style.transform = `scale(${scale})`;
      card.style.opacity = scale <= 0.9 ? "0.6" : "1";

      card.classList.toggle('active', distance < cardRect.width / 2);
    });
  }

  // Trigger highlight on scroll
  track.addEventListener('scroll', () => {
    requestAnimationFrame(highlightCenterCard);
  });

  // Initial highlight
  highlightCenterCard();
  
  // Center the first card initially
  if (cards.length > 0) {
    track.scrollTo({
      left: cards[0].offsetLeft - (track.offsetWidth / 2 - cards[0].offsetWidth / 2),
      behavior: "instant"
    });
  }

  // Auto-scroll every 5 seconds
  let autoScroll = setInterval(() => {
    track.scrollBy({ left: scrollAmount, behavior: 'smooth' });
  }, 5000);

  // Pause auto-scroll on hover
  track.addEventListener('mouseenter', () => clearInterval(autoScroll));
  track.addEventListener('mouseleave', () => {
    autoScroll = setInterval(() => {
      track.scrollBy({ left: scrollAmount, behavior: 'smooth' });
    }, 5000);
  });

  // Swipe support for mobile
  let startX = 0;

  track.addEventListener('touchstart', e => {
    startX = e.touches[0].clientX;
  });

  track.addEventListener('touchend', e => {
    const endX = e.changedTouches[0].clientX;
    const diff = startX - endX;

    if (Math.abs(diff) > 50) {
      track.scrollBy({ left: diff > 0 ? scrollAmount : -scrollAmount, behavior: 'smooth' });
    }
  });
}

// === FAQ DROPDOWN TOGGLE ===
document.addEventListener('DOMContentLoaded', function() {
  const faqItems = document.querySelectorAll('.faq-item');
  
  console.log('FAQ Items found:', faqItems.length); // Debug log

  faqItems.forEach((item, index) => {
    const question = item.querySelector('.faq-question');
    
    if (question) {
      console.log('FAQ Question', index, 'found'); // Debug log
      
      question.addEventListener('click', function(e) {
        e.preventDefault();
        console.log('FAQ clicked:', index); // Debug log
        
        // Check if this item is already active
        const isActive = item.classList.contains('active');
        
        // Close all FAQ items
        faqItems.forEach(faqItem => {
          faqItem.classList.remove('active');
        });
        
        // If it wasn't active, open it
        if (!isActive) {
          item.classList.add('active');
        }
      });
    }
  });
});