/**
 * Components.js - Dynamically injects shared header and footer
 * Usage: include this script and call injectHeader() / injectFooter()
 * Or simply use the static HTML partials in each page.
 *
 * CONFIG: Update SITE_INFO below to rebrand the institute.
 */

const SITE_INFO = {
  name: "TechVision",
  tagline: "Computer Institute",
  phone: "+91 98765 43210",
  phone2: "+91 87654 32109",
  email: "info@techvisioninstitute.in",
  verifyEmail: "verify@techvisioninstitute.in",
  address: "123, Main Market Road, Near Civil Hospital, Gorakhpur, UP – 273001",
  mapQuery: "Gorakhpur, Uttar Pradesh",
  facebook: "#",
  instagram: "#",
  youtube: "#",
  whatsapp: "919876543210",
  regNo: "REG/MSME/UP/2019/12345",
  isoNo: "ISO 9001:2015 Certified",
  estYear: "2019"
};

// This file is kept as a config reference.
// Actual header/footer HTML is written directly into each page for performance.
