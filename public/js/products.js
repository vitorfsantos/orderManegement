// Utility functions for product management modals

// Price formatting functions
function formatPrice(value) {
    // Remove all non-numeric characters
    let numericValue = value.replace(/\D/g, "");

    // If empty, return empty string
    if (!numericValue) return "";

    // Convert to number and divide by 100 to get decimal places
    let price = parseFloat(numericValue) / 100;

    // Format with 2 decimal places using comma as decimal separator
    return price.toFixed(2).replace(".", ",");
}

function formatPriceInput(input) {
    let value = input.value;
    let formattedValue = formatPrice(value);
    input.value = formattedValue;
}

function getNumericPrice(formattedPrice) {
    // Remove all non-numeric characters except comma
    let cleanPrice = formattedPrice.replace(/[^\d,]/g, "");

    // If empty, return 0
    if (!cleanPrice) return "0";

    // Replace comma with dot for decimal separator
    return cleanPrice.replace(",", ".");
}

// Function to format price when loading data (for edit modal)
function formatPriceForDisplay(price) {
    if (!price) return "";

    // If price is already formatted, return as is
    if (typeof price === "string" && price.includes(",")) {
        return price;
    }

    // Convert numeric price to formatted string
    const numericPrice = parseFloat(price);
    return numericPrice.toFixed(2).replace(".", ",");
}

// Function to prepare form data before submission
function prepareFormData(form) {
    const priceInputs = form.querySelectorAll("input[name='price']");
    priceInputs.forEach((priceInput) => {
        if (priceInput) {
            // Convert formatted price to numeric value for submission
            const numericPrice = getNumericPrice(priceInput.value);
            priceInput.value = numericPrice;
        }
    });
}

// Add event listener for form submission
document.addEventListener("DOMContentLoaded", function () {
    const forms = document.querySelectorAll("form");
    forms.forEach((form) => {
        form.addEventListener("submit", function (e) {
            prepareFormData(this);
        });
    });
});

function showSuccess(message) {
    // Create success notification
    const notification = document.createElement("div");
    notification.className =
        "fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50";
    notification.innerHTML = `
        <div class="flex items-center">
            <i class="fas fa-check-circle mr-2"></i>
            <span>${message}</span>
        </div>
    `;

    document.body.appendChild(notification);

    // Remove after 3 seconds
    setTimeout(() => {
        notification.remove();
    }, 3000);
}

function showError(message) {
    // Create error notification
    const notification = document.createElement("div");
    notification.className =
        "fixed top-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50";
    notification.innerHTML = `
        <div class="flex items-center">
            <i class="fas fa-exclamation-circle mr-2"></i>
            <span>${message}</span>
        </div>
    `;

    document.body.appendChild(notification);

    // Remove after 5 seconds
    setTimeout(() => {
        notification.remove();
    }, 5000);
}

function showErrors(errors, prefix = "") {
    // Clear previous errors
    clearErrors(prefix);

    // Show new errors
    Object.keys(errors).forEach((field) => {
        const errorElement = document.getElementById(prefix + field + "_error");
        if (errorElement) {
            errorElement.textContent = errors[field][0];
            errorElement.classList.remove("hidden");
        }
    });
}

function clearErrors(prefix = "") {
    // Clear all error messages
    const errorElements = document.querySelectorAll(`[id$="_error"]`);
    errorElements.forEach((element) => {
        if (element.id.startsWith(prefix)) {
            element.textContent = "";
            element.classList.add("hidden");
        }
    });
}

function deleteProduct(productId, productName) {
    if (
        confirm(
            `Tem certeza que deseja remover o produto "${productName}"? Esta ação não pode ser desfeita.`
        )
    ) {
        fetch(`/admin/products/${productId}`, {
            method: "DELETE",
            headers: {
                "X-Requested-With": "XMLHttpRequest",
                "X-CSRF-TOKEN": document
                    .querySelector('meta[name="csrf-token"]')
                    .getAttribute("content"),
            },
        })
            .then((response) => response.json())
            .then((data) => {
                if (data.success) {
                    showSuccess(data.message);
                    // Reload the page to show updated data
                    window.location.reload();
                } else {
                    showError(data.message || "Erro ao remover produto.");
                }
            })
            .catch((error) => {
                console.error("Error:", error);
                showError("Erro ao remover produto. Tente novamente.");
            });
    }
}

// Modal functions
function openModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.remove("hidden");
        document.body.classList.add("overflow-hidden");
    }
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.add("hidden");
        document.body.classList.remove("overflow-hidden");
    }
}

// Edit product function
function editProduct(productId) {
    fetch(`/admin/products/${productId}/edit`, {
        method: "GET",
        headers: {
            "X-Requested-With": "XMLHttpRequest",
        },
    })
        .then((response) => response.json())
        .then((data) => {
            if (data.success) {
                // Populate form with product data
                document.getElementById("edit_product_id").value =
                    data.product.id;
                document.getElementById("edit_name").value = data.product.name;
                document.getElementById("edit_price").value =
                    formatPriceForDisplay(data.product.price);
                document.getElementById("edit_stock").value =
                    data.product.stock;
                document.getElementById("edit_active").value = data.product
                    .active
                    ? "1"
                    : "0";
                document.getElementById("edit_slug").textContent =
                    data.product.slug;
                document.getElementById("edit_created_at").textContent =
                    data.product.created_at;
                document.getElementById("edit_updated_at").textContent =
                    data.product.updated_at;
                document.getElementById("edit_orders_count").textContent =
                    data.product.orders_count || 0;

                openModal("edit-product-modal");
            } else {
                showError("Erro ao carregar dados do produto.");
            }
        })
        .catch((error) => {
            console.error("Error:", error);
            showError("Erro ao carregar dados do produto.");
        });
}

function submitEditProduct() {
    const productId = document.getElementById("edit_product_id").value;
    const form = document.getElementById("edit-product-form");

    // Prepare form data (format price for submission)
    prepareFormData(form);

    const formData = new FormData(form);

    // Clear previous errors
    clearErrors("edit_");

    fetch(`/admin/products/${productId}`, {
        method: "POST",
        body: formData,
        headers: {
            "X-Requested-With": "XMLHttpRequest",
            "X-HTTP-Method-Override": "PUT",
        },
    })
        .then((response) => response.json())
        .then((data) => {
            if (data.success) {
                closeModal("edit-product-modal");
                showSuccess(data.message);
                // Reload the page to show updated data
                window.location.reload();
            } else {
                showErrors(data.errors, "edit_");
            }
        })
        .catch((error) => {
            console.error("Error:", error);
            showError("Erro ao atualizar produto. Tente novamente.");
        });
}
