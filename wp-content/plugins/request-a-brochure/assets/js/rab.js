const rabForm = document.getElementById("rab-form");
const chosenBrochures = [];
const minBrochures = 1;
const maxBrochures = 3;

rabForm.querySelectorAll('input[type="checkbox"]').forEach((checkbox) => {
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

rabForm.addEventListener("submit", async (e) => {
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

  const recaptchaResult = await getRecaptchaResult();

  if (recaptchaResult.success && recaptchaResult.score > 0.5) {
    return handleSubmit(formData);
  } else {
    return alert("reCaptcha failed");
  }
});

const handleSubmit = async (formData) => {
  const url = `${SERVER_DATA.rest_url}/brochure-requests`;
  formData.action = "CREATE_BROCHURE_REQUEST";

  const res = await fetch(url, {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
      Accepts: "application/json",
    },
    body: JSON.stringify(formData),
  });

  const data = await data.json();

  if (!res.ok) {
    if (data.message) return alert(data.message);

    return alert("Something went wrong on our side.");
  }

  // replace the form with success message
  const form = rabForm.parentElement;
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

  console.log("recaptcha data:", data);

  return data;
};
