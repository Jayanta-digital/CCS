/**
 * Certificate Verification Script
 * In production, this calls the PHP backend.
 * For static demo, sample data is embedded here.
 */

// Sample certificate data (in production, PHP + DB handles this)
const CERT_DATA = {
  "TVI-2024-001": { name: "Rahul Kumar Sharma", course: "DCA – Diploma in Computer Applications", year: "2024", grade: "A (85%)" },
  "TVI-2024-002": { name: "Priya Singh", course: "ADCA – Advanced Diploma in Computer Applications", year: "2024", grade: "A+ (92%)" },
  "TVI-2023-018": { name: "Amit Rajput", course: "MS Office & Internet", year: "2023", grade: "B+ (78%)" },
  "TVI-2023-045": { name: "Sunita Devi", course: "Tally Prime with GST", year: "2023", grade: "A (88%)" },
  "TVI-2024-067": { name: "Ravi Anand", course: "Web Designing & Development", year: "2024", grade: "A+ (94%)" },
  "TVI-2022-012": { name: "Kavita Verma", course: "DCA – Diploma in Computer Applications", year: "2022", grade: "B (72%)" },
  "TVI-2024-089": { name: "Suresh Yadav", course: "Python Programming", year: "2024", grade: "A (82%)" },
  "TVI-2023-033": { name: "Anjali Mishra", course: "ADCA – Advanced Diploma in Computer Applications", year: "2023", grade: "A+ (96%)" },
};

function verifyCertificate(e) {
  if (e) e.preventDefault();

  const input = document.getElementById("certNumber");
  const resultDiv = document.getElementById("certResult");
  const loadingDiv = document.getElementById("certLoading");
  const btn = document.getElementById("verifyBtn");

  if (!input || !resultDiv) return;

  const certNum = input.value.trim().toUpperCase();

  if (!certNum) {
    showCertError("Please enter a certificate number.");
    return;
  }

  // Show loading state
  resultDiv.style.display = "none";
  loadingDiv.style.display = "flex";
  btn.disabled = true;
  btn.textContent = "Verifying...";

  // Simulate network delay (remove in production with real PHP)
  setTimeout(() => {
    loadingDiv.style.display = "none";
    btn.disabled = false;
    btn.textContent = "🔍 Verify Certificate";

    const cert = CERT_DATA[certNum];

    if (cert) {
      showCertValid(certNum, cert);
    } else {
      showCertInvalid(certNum);
    }
  }, 1200);
}

function showCertValid(certNum, cert) {
  const resultDiv = document.getElementById("certResult");
  resultDiv.className = "cert-result-card valid";
  resultDiv.innerHTML = `
    <div class="cert-status-badge valid">✅ Certificate Verified – VALID</div>
    <div class="cert-detail-grid">
      <div class="cert-detail-item">
        <div class="cert-detail-label">Certificate Number</div>
        <div class="cert-detail-value" style="font-family: var(--font-mono)">${certNum}</div>
      </div>
      <div class="cert-detail-item">
        <div class="cert-detail-label">Student Name</div>
        <div class="cert-detail-value">${cert.name}</div>
      </div>
      <div class="cert-detail-item">
        <div class="cert-detail-label">Course Name</div>
        <div class="cert-detail-value">${cert.course}</div>
      </div>
      <div class="cert-detail-item">
        <div class="cert-detail-label">Passing Year</div>
        <div class="cert-detail-value">${cert.year}</div>
      </div>
      <div class="cert-detail-item">
        <div class="cert-detail-label">Grade / Marks</div>
        <div class="cert-detail-value" style="color: var(--success)">${cert.grade}</div>
      </div>
      <div class="cert-detail-item">
        <div class="cert-detail-label">Issued By</div>
        <div class="cert-detail-value">TechVision Computer Institute</div>
      </div>
    </div>
    <div class="alert alert-success" style="margin-top: 20px">
      ✅ This is an authentic certificate issued by TechVision Computer Institute. 
      For further verification, contact us at <strong>info@techvisioninstitute.in</strong>
    </div>
  `;
  resultDiv.style.display = "block";
  resultDiv.scrollIntoView({ behavior: "smooth", block: "nearest" });
}

function showCertInvalid(certNum) {
  const resultDiv = document.getElementById("certResult");
  resultDiv.className = "cert-result-card invalid";
  resultDiv.innerHTML = `
    <div class="cert-status-badge invalid">❌ Certificate NOT FOUND – INVALID</div>
    <p style="color: var(--text-body); margin-bottom: 16px">
      No certificate with number <strong style="font-family: var(--font-mono)">${certNum}</strong> was found in our records.
    </p>
    <div class="alert alert-danger">
      ⚠️ If you believe this is an error, please contact us at 
      <strong>+91 98765 43210</strong> or email <strong>verify@techvisioninstitute.in</strong> 
      with your original certificate for manual verification.
    </div>
    <p style="font-size: 0.88rem; color: var(--text-light)">
      Note: Certificate numbers are case-insensitive and follow the format: TVI-YYYY-XXX
    </p>
  `;
  resultDiv.style.display = "block";
  resultDiv.scrollIntoView({ behavior: "smooth", block: "nearest" });
}

function showCertError(msg) {
  const input = document.getElementById("certNumber");
  input.style.borderColor = "var(--danger)";
  input.focus();
  // Simple shake animation
  input.style.animation = "shake 0.4s ease";
  setTimeout(() => {
    input.style.animation = "";
    input.style.borderColor = "";
  }, 500);
}

// Add shake keyframe
const shakeStyle = document.createElement("style");
shakeStyle.textContent = `@keyframes shake{0%,100%{transform:none}25%{transform:translateX(-6px)}75%{transform:translateX(6px)}}`;
document.head.appendChild(shakeStyle);
