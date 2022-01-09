document.addEventListener("DOMContentLoaded", () => {
  rab_listenForBrochureCreation();
  rab_generate();
});

// SERVER_DATA is injected from the server and contains rest_url(rest api url)
const brochuresUrl = `${SERVER_DATA.rest_url}/brochures`;

/**
 * make a DELETE request to the rest api to delete a brochure
 * @param {HTMLElement} button
 * @param {number} id
 */
const rab_deleteBrochure = async (_this, id) => {
  const parent = _this.parentNode.parentNode;
  parent.style.opacity = "0.4";
  parent.style.pointerEvents = "none";

  const success = await rab_captain.delete(
    `${SERVER_DATA.rest_url}/brochures/${id}`
  );
  if (!success) {
    parent.style.opacity = "1";
    parent.style.pointerEvents = "auto";
    return;
  }

  parent.remove();
};

/**
 * make an UPDATE request to the rest api to change the status of a brochure
 * @param {number} id
 * @param {string} status
 */
const rab_changeBrochureStatus = async (_this, id, status) => {
  const parent = _this.parentNode.parentNode;
  parent.style.opacity = "0.4";
  parent.style.pointerEvents = "none";

  const success = await rab_captain.put(
    `${SERVER_DATA.rest_url}/brochures/${id}`,
    JSON.stringify({ active: status ? 0 : 1 })
  );

  if (success) {
    rab_generate();
  }

  parent.style.opacity = "1";
  parent.style.pointerEvents = "auto";
};

/**
 * make a get request to the rest api to get the brochures
 * and generate the tbody
 */
const rab_generate = async () => {
  const data = await rab_captain.get(brochuresUrl);

  if (!Array.isArray(data) || !data[0]) {
    return (document.querySelector(".rab-spinner").innerHTML =
      "<h3>No brochures found.</h3>");
  }

  rab_TbodyGenerator(data);
};

/**
 * generate the tbody from the brochures
 * @param {Array} brochures
 */
const rab_TbodyGenerator = (brochures) => {
  const tbody = document.getElementById("rab-brochures-tbody");

  let htmldata = "";
  for (const brochure of brochures) {
    htmldata += `
    <tr>
        <td>${brochure.brochure}</td>
        <td>${parseInt(brochure.active) ? "ACTIVE" : "INACTIVE"}</td>
        <td>
            <button 
            onclick="rab_deleteBrochure(this, ${brochure.id})" 
            id="rab-delete-button" 
            class="button button-secondary">
                DELETE
            </button>
            <button 
            onclick="rab_changeBrochureStatus(this, ${brochure.id}, ${parseInt(
      brochure.active
    )})"
            class="button button-secondary">
              CHANGE STATUS
            </button>
        </td>
    </tr>
    `;
  }
  tbody.innerHTML = htmldata;
};

/**
 * listen for "click" on button#rab-button-create-brochure
 * and make a post request to the rest api to create a brochure
 * with the name of the brochure taken from the input#rab-input-create-brochure
 */
function rab_listenForBrochureCreation() {
  const rabFormCreateBrochure = document.getElementById(
    "rab-form-create-brochure"
  );
  rabFormCreateBrochure.addEventListener("submit", async (e) => {
    e.preventDefault();

    const brochure = rabFormCreateBrochure.querySelector("input");
    const button = rabFormCreateBrochure.querySelector("button");
    const initialText = button.innerHTML;

    button.innerHTML = "Creating...";
    button.disabled = true;

    const res = await rab_captain.post(
      brochuresUrl,
      JSON.stringify({ brochure: brochure.value })
    );

    brochure.value = "";
    button.innerHTML = initialText;
    button.disabled = false;

    if (!res) return;
    rab_generate();
  });
}

/**
 * simple object to make fetch requests easier
 */
const rab_captain = {
  get: async (url) => await rab_fetcher(url, "GET"),
  post: async (url, body) => await rab_fetcher(url, "POST", body),
  put: async (url, body) => await rab_fetcher(url, "PUT", body),
  delete: async (url) => await rab_fetcher(url, "DELETE"),
};

/**
 * helper method to make fetch requests easier
 * @param {string} url
 * @param {string} method
 * @param {object} body
 */

const rab_fetcher = async (url, method, body = {}) => {
  console.log({ url, method, body });
  const options = {
    method,
    headers: {
      "Content-Type": "application/json",
      Accepts: "application/json",
    },
  };

  if (method === "POST" || method === "PUT") {
    options.body = JSON.stringify(body);
  }

  const res = await fetch(url, options);
  console.log("content-type:", res.headers.get("content-type"));
  const data = await res.json();
  console.log({ data });

  if (!res.ok) {
    return false;
  }

  return data;
};
