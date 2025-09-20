// Utility functions for product management modals

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
