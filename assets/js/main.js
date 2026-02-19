/**
 * TECHVISION COMPUTER INSTITUTE - Main JavaScript
 * ================================================
 * Handles: sticky header, mobile menu, animations,
 * counter animation, exit popup, mini enquiry, accordion
 */

// ---- Configuration (edit these to rebrand) ----
const SITE_CONFIG = {
  whatsappNumber: "919876543210",  // Change to your number (country code + number, no + sign)
  whatsappMessage: "Hello! I'm interested in learning more about your computer courses.",
  institutePhone: "+91 98765 43210",
  instituteName: "TechVision Computer Institute"
};

document.addEventListener("DOMContentLoaded", function () {

  /* ========== Sticky Header ========== */
  const header = document.querySelector(".site-header");
  window.addEventListener("scroll", () => {
    if (window.scrollY > 40) {
      header.classList.add("scrolled");
    } else {
      header.classList.remove("scrolled");
    }
  }, { passive: true });

  /* ========== Mobile Nav Toggle ========== */
  const navToggle = document.querySelector(".nav-toggle");
  const navMenu = document.querySelector(".nav-menu");
  if (navToggle && navMenu) {
    navToggle.addEventListener("click", () => {
      const isOpen = navMenu.classList.toggle("open");
      navToggle.setAttribute("aria-expanded", isOpen);
    });
    // Close on outside click
    document.addEventListener("click", (e) => {
      if (!navToggle.contains(e.target) && !navMenu.contains(e.target)) {
        navMenu.classList.remove("open");
      }
    });
    // Close on nav link click (mobile)
    navMenu.querySelectorAll(".nav-link").forEach(link => {
      link.addEventListener("click", () => navMenu.classList.remove("open"));
    });
  }

  /* ========== Highlight active nav link ========== */
  const currentPage = window.location.pathname.split("/").pop() || "index.html";
  document.querySelectorAll(".nav-link").forEach(link => {
    const href = link.getAttribute("href") || "";
    if (href === currentPage || (currentPage === "" && href === "index.html")) {
      link.classList.add("active");
    }
  });

  /* ========== Scroll Reveal Animation ========== */
  const reveals = document.querySelectorAll(".reveal");
  if (reveals.length > 0) {
    const revealObserver = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add("visible");
          revealObserver.unobserve(entry.target);
        }
      });
    }, { threshold: 0.12, rootMargin: "0px 0px -40px 0px" });
    reveals.forEach(el => revealObserver.observe(el));
  }

  /* ========== Counter Animation ========== */
  function animateCounter(el, target, duration = 2000) {
    let start = 0;
    const step = target / (duration / 16);
    const timer = setInterval(() => {
      start += step;
      if (start >= target) {
        start = target;
        clearInterval(timer);
      }
      el.textContent = Math.floor(start).toLocaleString("en-IN") + (el.dataset.suffix || "");
    }, 16);
  }

  const counters = document.querySelectorAll(".counter-num");
  if (counters.length > 0) {
    const counterObserver = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          const target = parseInt(entry.target.dataset.target, 10);
          animateCounter(entry.target, target);
          counterObserver.unobserve(entry.target);
        }
      });
    }, { threshold: 0.5 });
    counters.forEach(el => counterObserver.observe(el));
  }

  /* ========== Accordion (FAQ) ========== */
  document.querySelectorAll(".accordion-header").forEach(btn => {
    btn.addEventListener("click", () => {
      const item = btn.closest(".accordion-item");
      const isOpen = item.classList.contains("open");
      // Close all
      document.querySelectorAll(".accordion-item.open").forEach(i => i.classList.remove("open"));
      // Toggle clicked
      if (!isOpen) item.classList.add("open");
    });
  });

  /* ========== Mini Enquiry Popup ========== */
  const miniEnquiry = document.getElementById("miniEnquiry");
  let miniShown = false;
  if (miniEnquiry) {
    setTimeout(() => {
      if (!miniShown) {
        miniEnquiry.classList.add("visible");
        miniShown = true;
      }
    }, 25000); // Show after 25 seconds
    window.addEventListener("scroll", () => {
      if (!miniShown && window.scrollY > 600) {
        miniEnquiry.classList.add("visible");
        miniShown = true;
      }
    }, { passive: true, once: true });

    const closeBtn = miniEnquiry.querySelector(".mini-enquiry-close");
    if (closeBtn) {
      closeBtn.addEventListener("click", () => miniEnquiry.classList.remove("visible"));
    }
  }

  /* ========== Exit Intent Popup ========== */
  const exitPopup = document.getElementById("exitPopup");
  let exitShown = sessionStorage.getItem("exitShown");
  if (exitPopup && !exitShown) {
    document.addEventListener("mouseleave", (e) => {
      if (e.clientY <= 0 && !exitShown) {
        exitPopup.classList.add("active");
        exitShown = "1";
        sessionStorage.setItem("exitShown", "1");
      }
    });
    exitPopup.querySelector(".exit-popup-close")?.addEventListener("click", () => {
      exitPopup.classList.remove("active");
    });
    exitPopup.addEventListener("click", (e) => {
      if (e.target === exitPopup) exitPopup.classList.remove("active");
    });
  }

  /* ========== WhatsApp Button ========== */
  const waBtn = document.getElementById("whatsappBtn");
  if (waBtn) {
    const url = `https://wa.me/${SITE_CONFIG.whatsappNumber}?text=${encodeURIComponent(SITE_CONFIG.whatsappMessage)}`;
    waBtn.setAttribute("href", url);
  }

  /* ========== Lazy Load Images ========== */
  const lazyImages = document.querySelectorAll("img[data-src]");
  if (lazyImages.length > 0 && "IntersectionObserver" in window) {
    const imgObserver = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          const img = entry.target;
          img.src = img.dataset.src;
          img.removeAttribute("data-src");
          imgObserver.unobserve(img);
        }
      });
    });
    lazyImages.forEach(img => imgObserver.observe(img));
  }

  /* ========== Smooth scroll for anchor links ========== */
  document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener("click", function (e) {
      const target = document.querySelector(this.getAttribute("href"));
      if (target) {
        e.preventDefault();
        const offset = (header ? header.offsetHeight : 72) + 16;
        const top = target.getBoundingClientRect().top + window.scrollY - offset;
        window.scrollTo({ top, behavior: "smooth" });
      }
    });
  });

  console.log(`%c${SITE_CONFIG.instituteName}`, "color: #1a3a6e; font-size: 1.2rem; font-weight: bold;");
  console.log("%cBuilt with ❤️ for career-focused education", "color: #e8a020;");
});

/* ========== Mini enquiry form submit (inline handler) ========== */
function submitMiniEnquiry(e) {
  e.preventDefault();
  const form = e.target;
  const data = new FormData(form);
  // Simple feedback - in production this would POST to php/enquiry.php
  const thankMsg = document.getElementById("miniThank");
  if (thankMsg) {
    form.style.display = "none";
    thankMsg.style.display = "block";
  }
  return false;
}
