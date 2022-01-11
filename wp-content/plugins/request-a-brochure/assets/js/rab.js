const rabForm = document.getElementById("rab-form");
const chosenBrochures = [];
const minBrochures = 1;
const maxBrochures = 3;

rabForm && rabForm.querySelectorAll('input[type="checkbox"]').forEach((checkbox) => {
  checkbox.addEventListener("change", (event) => {
    const checkbox = event.target;
    const brochureId = checkbox.dataset.brochure_id;

    if (checkbox.checked) {
      if (chosenBrochures.length >= maxBrochures) {
        checkbox.checked = false;
        return alert(`You can only choose ${maxBrochures} brochures.`);
      }
      chosenBrochures.push(brochureId);
    } else {
      const index = chosenBrochures.indexOf(brochureId);
      chosenBrochures.splice(index, 1);
    }
  });
});

rabForm && rabForm.addEventListener("submit", async (e) => {
  e.preventDefault();
  const formData = {};

  const inputs = rabForm.querySelectorAll("input:not([type='checkbox'])");
  inputs.forEach((input) => {
    formData[input.name] = input.value;
  });

  if (chosenBrochures.length < minBrochures) {
    return alert(
      `You must choose at least ${minBrochures} brochure${
        minBrochures > 1 ? "s" : ""
      }.`
    );
  }

  formData.brochures = chosenBrochures;

  if(!formData.email) {
    return alert("Please enter your email address.");
  }
  if (!formData.name) {
    return alert("Please enter your name.");
  }

  return handleSubmit(formData);
});

const handleSubmit = async (formData) => {
  const form = rabForm.parentElement;
  disable(form);

  const recaptchaResult = await getRecaptchaResult();
  if (!recaptchaResult.success || recaptchaResult.score < 0.5) {
    enable(form);
    return alert("reCAPTCHA failed.");
  }

  const res = await fetch(`${SERVER_DATA.rest_url}/brochure-requests`, {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
      Accepts: "application/json",
    },
    body: JSON.stringify(formData),
  });

  const data = await res.json();

  enable(form);
  if (!res.ok) {
    if (data.message) return alert(data.message);
    return alert("Something went wrong on our side.");
  }

  // replace the form with success message
  const success = document.createElement("div");
  success.classList.add("rab-success");
  success.innerHTML = `
    <h2>Thank you for your request!</h2>
    <p>We will be in touch with you shortly.</p>
  `;
  form.replaceWith(success);
};

const getRecaptchaResult = async () => {
  const token = await grecaptcha.execute(`${SERVER_DATA.site_key}`, {
    action: "submit",
  });

  const res = await fetch(`${SERVER_DATA.rest_url}/brochure-recaptcha`, {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify({ token }),
  });
  const data = await res.json();

  return data;
};

const disable = (element) => {
  element.style.opacity = 0.5;
  element.style.pointerEvents = "none";
};

const enable = (element) => {
  element.style.opacity = 1;
  element.style.pointerEvents = "auto";
};
