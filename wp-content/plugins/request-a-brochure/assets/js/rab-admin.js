document.addEventListener("DOMContentLoaded", () => {
  rab_listenForBrochureCreation();
  rab_generate();
  rab_generateRequests();

  document
    .getElementById("rab-button-refresh-requests")
    .addEventListener("click", (e) => {
      // disable events
      disable(e.target)(async () => {
        await rab_generateRequests();
        enable(e.target);
      })();
    });
});

// SERVER_DATA is injected from the server and contains rest_url(rest api url)
const apiUrl = `${SERVER_DATA.rest_url}`;
const brochuresUrl = `${apiUrl}/brochures`;
const brochureRequests = `${apiUrl}/brochure-requests`;

/**
 * make a DELETE request to the rest api to delete a brochure
 * @param {HTMLElement} button
 * @param {number} id
 */
const rab_deleteBrochure = async (_this, id) => {
  const parent = _this.parentNode.parentNode;
  disable(parent);

  const success = await rab_captain.delete(`${brochuresUrl}/${id}`);

  if (!success) return enable(parent);

  parent.remove();
};

/**
 * make a DELETE request to the rest api to delete a brochure request
 * @param {HTMLElement} button
 * @param {number} id
 */
const rab_deleteBrochureRequest = async (_this, brochure_id) => {
  const parent = _this.parentNode.parentNode;
  disable(parent);

  const success = await rab_captain.delete(
    `${brochureRequests}/${brochure_id}`
  );
  if (!success) return enable(parent);

  parent.remove();
};

/**
 * make an UPDATE request to the rest api to change the status of a brochure
 * @param {number} id
 * @param {string} status
 */
const rab_changeBrochureStatus = async (_this, id, status) => {
  const parent = _this.parentNode.parentNode;
  disable(parent);

  const success = await rab_captain.put(
    `${SERVER_DATA.rest_url}/brochures/${id}`,
    { active: status ? 0 : 1 }
  );

  if (success) rab_generate();

  enable(parent);
};

/**
 * make an UPDATE request to the rest api to change the status of a brochure
 * @param {number} id
 * @param {string} status
 */
const rab_changeBrochureRequestStatus = async (_this, request_id, status) => {
  const parent = _this.parentNode.parentNode;
  disable(parent);

  const success = await rab_captain.put(`${brochureRequests}/${request_id}`, {
    status,
  });

  if (success) {
    rab_generateRequests();
  }

  enable(parent);
};

/**
 * make a get request to the rest api to get the brochures
 * and generate the tbody
 */
const rab_generate = async () => {
  const data = await rab_captain.get(brochuresUrl);

  const spinner = document.querySelector(".rab-spinner");
  if (!Array.isArray(data) || !data[0]) {
    return (spinner.innerHTML = "<h3>No brochures found.</h3>");
  }

  spinner.innerHTML = "";

  rab_TbodyGenerator(data);
};

/**
 * make a get request to the rest api to get the brochures
 * and generate the tbody
 */
const rab_generateRequests = async () => {
  const data = await rab_captain.get(brochureRequests);

  const requestsSpinner = document.querySelector(".rab-requests-spinner");

  if (!Array.isArray(data) || !data[0]) {
    return (requestsSpinner.innerHTML = "<h3>No brochure requests.</h3>");
  }

  requestsSpinner.innerHTML = "";

  rab_requestsTbodyGenerator(data);
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
 * generate the tbody from the brochures
 * @param {Array} brochures
 */
const rab_requestsTbodyGenerator = (requests) => {
  const tbody = document.getElementById("rab-brochure-requests-tbody");

  // join all the requests with the same request_id together and turn brochure_id into an array of ids
  const requestsWithIds = requests.reduce((acc, curr) => {
    if (!acc[curr.request_id]) {
      acc[curr.request_id] = {
        request_id: curr.request_id,
        name: curr.name,
        address: curr.address,
        brochure_ids: [curr.brochure_id],
        status: curr.status,
      };
    } else {
      acc[curr.request_id].brochure_ids.push(curr.brochure_id);
    }
    return acc;
  }, {});

  let htmldata = "";
  for (const request of Object.values(requestsWithIds)) {
    htmldata += `
    <tr>
        <td>${request.name}</td>
        <td>${request.address}</td>
        <td>${request.brochure_ids.join(", ")}</td>
        <td>${request.status}</td>
        <td>
        <!-- <button 
        onclick="rab_deleteBrochureRequest(this, '${request.request_id}')" 
        id="rab-delete-button" 
        class="button button-secondary">
            DELETE
        </button> -->
        <button 
        onclick="rab_changeBrochureRequestStatus(this, '${
          request.request_id
        }', 'cancelled')"
        class="button button-secondary">
          CANCEL
        </button>
        <button 
        onclick="rab_changeBrochureRequestStatus(this, '${
          request.request_id
        }', 'dispatched')"
        class="button button-secondary">
          DISPATCH
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

    const res = await rab_captain.post(brochuresUrl, {
      brochure: brochure.value,
    });

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
  const data = await res.json();

  if (!res.ok) return false;

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
